<?php

namespace App\BonusSystem\Collections;

use App\BonusSystem\Repositories\AllocationCriteriaRepo;
use App\BonusSystem\Repositories\CampaignsRepo;
use App\BonusSystem\Repositories\RolloversRepo;
use App\Core\Collections\CurrenciesCollection;
use App\Core\Repositories\CurrenciesRepo;
use App\Core\Repositories\ProvidersRepo;
use App\Core\Repositories\TransactionsRepo;
use Carbon\Carbon;
use App\BonusSystem\Repositories\CampaignParticipationRepo;
use App\BonusSystem\Repositories\CampaignParticipationDetailsRepo;
use App\CRM\Repositories\SegmentsRepo;
use App\Users\Repositories\UsersRepo;
use Dotworkers\Bonus\Enums\AllocationCriteria;
use Dotworkers\Bonus\Enums\CampaignParticipationStatus;
use Dotworkers\Configurations\Enums\Providers;
use Dotworkers\Configurations\Enums\ProviderTypes;
use Dotworkers\Configurations\Configurations;
use Dotworkers\Configurations\Enums\TransactionTypes;
use Dotworkers\Security\Enums\Permissions;
use Dotworkers\Wallet\Wallet;
use Illuminate\Support\Facades\Gate;
use Ixudra\Curl\Facades\Curl;

/**
 * Class CampaignsCollection
 *
 * Class to define the campaigns table attributes
 *
 * @package App\BonusSystem\Collections
 * @author Damelys Espinoza
 */
class CampaignsCollection
{
    /**
     * Format all campaigns
     *
     * @param array $campaigns campaigns data
     */
    public function formatAll($campaigns)
    {
        $timezone = session('timezone');
        foreach ($campaigns as $campaign) {
            $campaign->name = !is_null($campaign->name) ? $campaign->name : _i('Without name');
            $campaign->currency_iso = $campaign->currency_iso == '*' ? _i('Everybody') : $campaign->currency_iso;
            $start = !is_null($campaign->start_date) ? $campaign->start_date->setTimezone($timezone)->format('d-m-Y H:i:s') : _i('No start date');
            $end = !is_null($campaign->end_date) ? $campaign->end_date->setTimezone($timezone)->format('d-m-Y H:i:s') : _i('No end date');
            $campaign->dates = "$start <br> $end";
            $criteria = '';

            foreach ($campaign->data->allocation_criteria as $allocationCriteria) {
                $criteria .= AllocationCriteria::getName($allocationCriteria) . '<br>';
            }
            $campaign->allocation_criteria = $criteria;

            if (Gate::allows('access', Permissions::$manage_sliders)) {
                $campaign->actions = sprintf(
                    '<a href="%s" class="btn u-btn-3d btn-sm u-btn-bluegray mr-2"><i class="hs-admin-pencil"></i> %s</a>',
                    route('bonus-system.campaigns.edit', [$campaign->id]),
                    _i('Edit')
                );

                if (!$campaign->status) {
                    $campaign->actions .= sprintf(
                        '<button type="button" class="btn u-btn-3d btn-sm u-btn-primary mr-2 delete" data-route="%s"><i class="hs-admin-trash"></i> %s</button>',
                        route('bonus-system.campaigns.delete', [$campaign->id]),
                        _i('Delete')
                    );
                }
            } else {
                $campaign->actions = '';
            }
            $statusClass = $campaign->status ? 'teal' : 'lightred';
            $statusText = $campaign->status ? _i('Active') : _i('Inactive');
            $campaign->status = sprintf(
                '<span class="u-label g-bg-%s g-rounded-20 g-px-15 g-mr-10 g-mb-15">%s</span>',
                $statusClass,
                $statusText
            );
        }
    }

    /**
     * Format campaigns by criteria
     *
     * @param array $campaigns Campaigns data
     * @param string $convert Convert Currency
     * @param string $campaignStartDate Start date
     * @param string $campaignEndDate End date
     */
    public function formatCampaignByCriteria($campaigns, $convert, $campaignStartDate, $campaignEndDate): array
    {

        $campaignParticipationDetailsRepo = new CampaignParticipationDetailsRepo();
        $campaignParticipationRepo = new CampaignParticipationRepo();
        $currenciesRepo = new CurrenciesRepo();
        $rolloversRepo = new RolloversRepo();
        $currenciesCollection = new CurrenciesCollection();
        $allocationCriteriaRepo = new AllocationCriteriaRepo();
        $campaignsRepo = new CampaignsRepo();
        $timezone = session('timezone');
        $today = Carbon::now();
        $generalTotals = [];
        $totalUsedBonus = 0;
        $totalEndedBonus = 0;
        $totalActiveBonus = 0;
        $totalConvertedBonus = 0;
        $totalDepositedAmount = 0;
        $exchangeRates = new \stdClass();
        $convert = $convert == 'VES' ? 'VEF' : $convert;

        if (!is_null($convert)) {
            $now = $today->copy()->format('Y-m-d');
            $baseURL = env('FIXER_TIME_SERIES_URL') . '?access_key=' . env('FIXER_API_KEY') . "&start_date=$now&end_date=$now";
            $currenciesData = $currenciesRepo->all();
            $currencies = $currenciesCollection->getOnlyIsos($currenciesData);

            foreach ($currencies as $currency) {
                $currency = $currency == 'VES' ? 'VEF' : $currency;
                $url = $baseURL . "&base=$currency&symbols=$convert";
                $curl = Curl::to($url)->get();
                $exchangeResponse = json_decode($curl);
                $exchangeRates->$currency = $exchangeResponse;
            }
        }

        foreach ($campaigns as $campaign) {
            $totalUsed = 0;
            $totalEnded = 0;
            $totalActive = 0;
            $totalConverted = 0;
            $totalDeposited = 0;
            $totalCriteriaMet = 0;
            $campaignsIds[] = $campaign->id;
            $allVersionCampaign = [];
            $campaignData = json_decode($campaign->data);

            if (!is_null($convert)) {
                $campaignCurrencyIso = $campaign->currency_iso == 'VES' ? 'VEF' : $campaign->currency_iso;
                $exchangeRate = $exchangeRates->{$campaignCurrencyIso}->rates->{$now}->{$convert};
            }

            if (!is_null($campaign->original_campaign)) {
                $allVersionCampaignData = $campaignsRepo->getVersions($campaign->original_campaign);
            } else {
                $allVersionCampaignData = $campaignsRepo->getVersions($campaign->id);
            }

            foreach ($allVersionCampaignData as $versions) {
                $allVersionCampaign[] = $versions->id;
                $campaignsIds[] = $versions->id;
            }

            $usedBonuses = $campaignParticipationDetailsRepo->getByCampaignAndStatus($allVersionCampaign, [CampaignParticipationStatus::$in_use]);
            $endedBonuses = $campaignParticipationDetailsRepo->getByCampaignAndStatus($allVersionCampaign, [CampaignParticipationStatus::$canceled_by_user, CampaignParticipationStatus::$canceled_by_administrator, CampaignParticipationStatus::$canceled_by_withdrawal, CampaignParticipationStatus::$completed_rollover, CampaignParticipationStatus::$expired_rollover]);
            $convertedBonuses = $campaignParticipationDetailsRepo->getByCampaignAndStatus($allVersionCampaign, [CampaignParticipationStatus::$completed_rollover]);
            $activeBonuses = Wallet::getTotalBonusByCampaigns($campaignsIds);
            $allocationCriteriaMet = $allocationCriteriaRepo->getByAllocationCriteria($campaignsIds, $campaignStartDate, $campaignEndDate);
            $participation = $campaignParticipationRepo->getTotalParticipation($allVersionCampaign, $campaignStartDate, $campaignEndDate);
            $activeUsers = $campaignParticipationRepo->getTotalParticipationAdStatus($allVersionCampaign, [CampaignParticipationStatus::$in_use, CampaignParticipationStatus::$assigned], $campaignStartDate, $campaignEndDate);

            foreach ($usedBonuses as $usedBonus) {
                $rollovers = $rolloversRepo->getByCampaign($usedBonus->campaign_id, $campaignStartDate, $campaignEndDate);

                foreach ($rollovers as $rollover) {
                    if (!is_null($convert)) {
                        $totalUsed += is_null($exchangeRate) ? 0 : $rollover->bonus * $exchangeRate;
                        $totalDeposited += is_null($exchangeRate) ? 0 : $rollover->deposit * $exchangeRate;
                    } else {
                        $totalUsed += $rollover->bonus;
                        $totalDeposited += $rollover->deposit;
                    }
                }
            }

            foreach ($endedBonuses as $endedBonus) {
                $rollovers = $rolloversRepo->getByCampaign($endedBonus->campaign_id, $campaignStartDate, $campaignEndDate);

                foreach ($rollovers as $rollover) {
                    if (!is_null($convert)) {
                        $totalEnded += is_null($exchangeRate) ? 0 : $rollover->final_amount * $exchangeRate;
                    } else {
                        $totalEnded += $rollover->final_amount;
                    }
                }
            }

            foreach ($convertedBonuses as $convertedBonus) {
                $rollovers = $rolloversRepo->getByCampaign($convertedBonus->campaign_id, $campaignStartDate, $campaignEndDate);

                foreach ($rollovers as $rollover) {
                    if (!is_null($convert)) {
                        $totalConverted += is_null($exchangeRate) ? 0 : $rollover->converted * $exchangeRate;
                    } else {
                        $totalConverted += $rollover->converted;
                    }
                }
            }

            foreach ($activeBonuses->data->bonus as $activeBonus) {
                if ($activeBonus->campaign_id == $campaign->id || in_array($activeBonus->campaign_id, $allVersionCampaign)) {

                    if (!is_null($convert)) {
                        $totalActive += is_null($exchangeRate) ? 0 : $activeBonus->total_bonus * $exchangeRate;
                    } else {
                        $totalActive += $activeBonus->total_bonus;
                    }
                }
            }

            foreach ($allocationCriteriaMet as $criteriaMet) {
                if ($criteriaMet->campaign_id == $campaign->id || in_array($criteriaMet->campaign_id, $allVersionCampaign)) {
                    $totalCriteriaMet += $criteriaMet->criteria_met_quantity;
                }
            }

            $criteria = '';

            foreach ($campaignData->allocation_criteria as $allocationCriteria) {
                $criteria .= AllocationCriteria::getName($allocationCriteria) . '<br>';
            }
            $campaign->criteria = $criteria;
            $campaign->vertical = ProviderTypes::getName($campaign->provider_type_id);
            $campaign->active_users = number_format($activeUsers);
            $campaign->used_bonus = number_format($totalUsed, 2);
            $campaign->ended_bonus = number_format($totalEnded, 2);
            $campaign->active_bonus = number_format($totalActive, 2);
            $campaign->converted_bonus = number_format($totalConverted, 2);
            $campaign->deposited_amount = number_format($totalDeposited, 2);
            $campaign->criteria_met = number_format($totalCriteriaMet);
            $campaign->claimed = number_format($participation);
            $totalUsedBonus += $totalUsed;
            $totalEndedBonus += $totalEnded;
            $totalDepositedAmount += $totalDeposited;
            $totalActiveBonus += $totalActive;
            $totalConvertedBonus += $totalConverted;
            $startDate = Carbon::createFromFormat('Y-m-d H:i:s', $campaign->start_date)->setTimezone($timezone)->format('d-m-Y h:i:s');
            $endDate = !is_null($campaign->end_date) ? Carbon::createFromFormat('Y-m-d H:i:s', $campaign->end_date)->setTimezone($timezone)->format('d-m-Y') : _i('No end date');
            $startDateName = _i('Start');
            $endDateName = _i('End');

            $campaign->campaign_id = sprintf(
                '<a href="%s" class="btn u-btn-3d u-btn-primary btn-sm" target="_blank">%s</a>',
                route('bonus-system.campaigns.edit', [$campaign->id]),
                $campaign->id
            );

            $campaign->campaign = sprintf(
                '<strong>%s</strong><br>%s<br>',
                $campaign->name,
                $criteria
            );

            if (isset($campaignData->promo_codes) && $campaignData->promo_codes != []) {
                foreach ($campaignData->promo_codes as $promo_codes)
                    $campaign->promo_code = sprintf(
                    '<ul><li><strong>%s</strong>%s%s%s<strong>%s</strong>%s%s</li></li></ul>',
                        _i('Promo code'),
                    ': ',
                    $promo_codes->promo_code,
                        ' - ',
                    _i('Btag'),
                    ': ',
                    $promo_codes->btag,
                );
            } else {
                $campaign->promo_code = '';
            }

            $statusClass = $campaign->status ? 'teal' : 'lightred';
            $statusText = $campaign->status ? _i('Active') : _i('Inactive');
            $campaign->campaign .= sprintf(
                '<span class="u-label g-bg-%s g-rounded-20 g-px-15 g-mr-10 g-mb-15">%s</span>',
                $statusClass,
                $statusText
            );

            if (!is_null($campaign->end_date)) {
                $campaign->dates = sprintf(
                    "<strong>%s:</strong> %s <br> <strong>%s</strong>: %s",
                    $startDateName,
                    $startDate,
                    $endDateName,
                    $endDate
                );
            } else {
                $campaign->dates = sprintf(
                    "<strong>%s:</strong> %s",
                    $startDateName,
                    $startDate
                );
            }
        }

        $generalTotals['used_bonus'] = number_format($totalUsedBonus, 2);
        $generalTotals['ended_bonus'] = number_format($totalEndedBonus, 2);
        $generalTotals['active_bonus'] = number_format($totalActiveBonus, 2);
        $generalTotals['converted_bonus'] = number_format($totalConvertedBonus, 2);
        $generalTotals['deposited_amount'] = number_format($totalDepositedAmount, 2);

        return [
            'campaigns' => $campaigns,
            'general_totals' => $generalTotals
        ];
    }

    /**
     * format Campaign Participation
     *
     * @param array $users Users data
     * @param string $convert Convert Currency
     */
    public function formatCampaignParticipation($users, $convert)
    {
        $campaignParticipationDetailsRepo = new CampaignParticipationDetailsRepo();
        $currenciesRepo = new CurrenciesRepo();
        $rolloversRepo = new RolloversRepo();
        $transactionsRepo = new TransactionsRepo();
        $currenciesCollection = new CurrenciesCollection();
        $campaignsRepo = new CampaignsRepo();
        $timezone = session('timezone');
        $today = Carbon::now();
        $generalTotals = [];
        $totalUsedBonus = 0;
        $totalEndedBonus = 0;
        $totalActiveBonus = 0;
        $totalConvertedBonus = 0;
        $totalDepositedAmount = 0;
        $exchangeRates = new \stdClass();
        $convert = $convert == 'VES' ? 'VEF' : $convert;

        if (!is_null($convert)) {
            $now = $today->copy()->format('Y-m-d');
            $baseURL = env('FIXER_TIME_SERIES_URL') . '?access_key=' . env('FIXER_API_KEY') . "&start_date=$now&end_date=$now";
            $currenciesData = $currenciesRepo->all();
            $currencies = $currenciesCollection->getOnlyIsos($currenciesData);

            foreach ($currencies as $currency) {
                $currency = $currency == 'VES' ? 'VEF' : $currency;
                $url = $baseURL . "&base=$currency&symbols=$convert";
                $curl = Curl::to($url)->get();
                $exchangeResponse = json_decode($curl);
                $exchangeRates->$currency = $exchangeResponse;
            }
        }

        foreach ($users as $user) {
            $totalUsed = 0;
            $totalEnded = 0;
            $totalActive = 0;
            $totalConverted = 0;
            $totalDeposited = 0;
            $campaignsIds[] = $user->campaign_id;
            $usersIds[] = $user->user_id;
            $allVersionCampaign = [];

            if (!is_null($convert)) {
                $campaignCurrencyIso = $user->currency_iso == 'VES' ? 'VEF' : $user->currency_iso;
                $exchangeRate = $exchangeRates->{$campaignCurrencyIso}->rates->{$now}->{$convert};
            }
            // if (!is_null($user->original_campaign)) {
            //     $allVersionCampaignData = $campaignsRepo->getVersions($user->original_campaign);
            // } else {
            //     $allVersionCampaignData = $campaignsRepo->getVersions($user->campaign_id);
            // }

            // foreach ($allVersionCampaignData as $versions) {
            //     $allVersionCampaign[] = $versions->id;
            //     $campaignsIds[] = $versions->id;
            // }

            $usedBonuses = $campaignParticipationDetailsRepo->getByCampaignStatusAndUser($allVersionCampaign, [CampaignParticipationStatus::$in_use], $user->user_id);
            $endedBonuses = $campaignParticipationDetailsRepo->getByCampaignStatusAndUser($allVersionCampaign, [CampaignParticipationStatus::$canceled_by_user, CampaignParticipationStatus::$canceled_by_administrator, CampaignParticipationStatus::$canceled_by_withdrawal, CampaignParticipationStatus::$completed_rollover, CampaignParticipationStatus::$expired_rollover], $user->user_id);
            $convertedBonuses = $campaignParticipationDetailsRepo->getByCampaignStatusAndUser($allVersionCampaign, [CampaignParticipationStatus::$completed_rollover], $user->user_id);
            $activeBonuses = Wallet::getTotalBonusByCampaignsAndUsers($campaignsIds, $usersIds);
            $providersTypes = [ProviderTypes::$payment, ProviderTypes::$manual_adjustments, ProviderTypes::$dotworkers, ProviderTypes::$bonus_transactions];
            $depositHistory = $transactionsRepo->getTransactionsHistory($user->user_id, TransactionTypes::$credit, $user->currency_iso, $providersTypes);
            $withdrawalHistory = $transactionsRepo->getTransactionsHistory($user->user_id, TransactionTypes::$debit, $user->currency_iso, $providersTypes);
            $profit = $depositHistory - $withdrawalHistory;

            $user->user = sprintf(
                '<a href="%s" class="btn u-btn-3d u-btn-primary btn-sm" target="_blank">%s</a>',
                route('users.details', [$user->user_id]),
                $user->user_id
            );

            foreach ($usedBonuses as $usedBonus) {
                $rollovers = $rolloversRepo->getByCampaignAndUser($usedBonus->campaign_id, $user->user_id);

                foreach ($rollovers as $rollover) {
                    if (!is_null($convert)) {
                        $totalUsed += is_null($exchangeRate) ? 0 : $rollover->bonus * $exchangeRate;
                        $totalDeposited += is_null($exchangeRate) ? 0 : $rollover->deposit * $exchangeRate;
                    } else {
                        $totalUsed += $rollover->bonus;
                        $totalDeposited += $rollover->deposit;
                    }
                }
            }

            foreach ($endedBonuses as $endedBonus) {
                $rollovers = $rolloversRepo->getByCampaignAndUser($endedBonus->campaign_id, $user->user_id);

                foreach ($rollovers as $rollover) {
                    if (!is_null($convert)) {
                        $totalEnded += is_null($exchangeRate) ? 0 : $rollover->final_amount * $exchangeRate;
                    } else {
                        $totalEnded += $rollover->final_amount;
                    }
                }
            }

            foreach ($convertedBonuses as $convertedBonus) {
                $rollovers = $rolloversRepo->getByCampaignAndUser($convertedBonus->campaign_id, $user->user_id);

                foreach ($rollovers as $rollover) {
                    if (!is_null($convert)) {
                        $totalConverted += is_null($exchangeRate) ? 0 : $rollover->converted * $exchangeRate;
                    } else {
                        $totalConverted += $rollover->converted;
                    }
                }
            }

            foreach ($activeBonuses->data->bonus as $activeBonus) {
                if ($activeBonus->campaign_id == $user->campaign_id || in_array($activeBonus->campaign_id, $allVersionCampaign) && $activeBonus->user_id == $user->user_id) {

                    if (!is_null($convert)) {
                        $totalActive += is_null($exchangeRate) ? 0 : $activeBonus->balance * $exchangeRate;
                    } else {
                        $totalActive += $activeBonus->balance;
                    }
                }
            }

            if (!is_null($convert)) {
                $totalDepositHistory = is_null($exchangeRate) ? 0 : $depositHistory * $exchangeRate;
            } else {
                $totalDepositHistory = $depositHistory;
            }

            if (!is_null($convert)) {
                $totalWithdrawalHistory = is_null($exchangeRate) ? 0 : $withdrawalHistory * $exchangeRate;
            } else {
                $totalWithdrawalHistory = $withdrawalHistory;
            }

            if (!is_null($convert)) {
                $totalUserProfit = is_null($exchangeRate) ? 0 : $profit * $exchangeRate;
            } else {
                $totalUserProfit = $profit;
            }

            $criteria = '';

            foreach ($user->data->allocation_criteria as $allocationCriteria) {
                $criteria .= AllocationCriteria::getName($allocationCriteria) . '<br>';
            }
            $user->vertical = ProviderTypes::getName($user->provider_type_id);
            $user->criteria = $criteria;
            $user->currency = $user->currency_iso;
            $user->used_bonus = number_format($totalUsed, 2);
            $user->ended_bonus = number_format($totalEnded, 2);
            $user->active_bonus = number_format($totalActive, 2);
            $user->converted_bonus = number_format($totalConverted, 2);
            $user->deposited_amount = number_format($totalDeposited, 2);
            $totalUsedBonus += $totalUsed;
            $totalEndedBonus += $totalEnded;
            $totalDepositedAmount += $totalDeposited;
            $totalActiveBonus += $totalActive;
            $totalConvertedBonus += $totalConverted;
            $startDate = Carbon::createFromFormat('Y-m-d H:i:s', $user->start_date)->setTimezone($timezone)->format('d-m-Y H:i:s');
            $endDate = !is_null($user->end_date) ? Carbon::createFromFormat('Y-m-d H:i:s', $user->end_date)->setTimezone($timezone)->format('d-m-Y H:i:s') : _i('No end date');
            $startDateName = _i('Start');
            $endDateName = _i('End');

            $user->user_deposit_history = number_format($totalDepositHistory, 2);
            $user->user_withdrawal_history = number_format($totalWithdrawalHistory, 2);
            $user->profit = number_format($totalUserProfit, 2);
            if ($totalDeposited > 0) {
                $percentage = $totalConverted / $totalDeposited * 100;
            } else {
                $percentage = 0;
            }
            $user->percentage = number_format($percentage, 2);

            $user->campaign_id = sprintf(
                '<a href="%s" class="btn u-btn-3d u-btn-primary btn-sm" target="_blank">%s</a>',
                route('bonus-system.campaigns.edit', [$user->campaign_id]),
                $user->campaign_id
            );

            $user->campaign = sprintf(
                '<strong>%s</strong><br>%s<br>',
                $user->name,
                $user->criteria
            );

            $statusClass = $user->status ? 'teal' : 'lightred';
            $statusText = $user->status ? _i('Active') : _i('Inactive');
            $user->campaign .= sprintf(
                '<span class="u-label g-bg-%s g-rounded-20 g-px-15 g-mr-10 g-mb-15">%s</span>',
                $statusClass,
                $statusText
            );

            if (!is_null($user->end_date)) {
                $user->dates = sprintf(
                    "<strong>%s:</strong> %s <br> <strong>%s</strong>: %s",
                    $startDateName,
                    $startDate,
                    $endDateName,
                    $endDate
                );
            } else {
                $user->dates = sprintf(
                    "<strong>%s:</strong> %s",
                    $startDateName,
                    $startDate
                );
            }
        }

        $generalTotals['used_bonus'] = number_format($totalUsedBonus, 2);
        $generalTotals['ended_bonus'] = number_format($totalEndedBonus, 2);
        $generalTotals['active_bonus'] = number_format($totalActiveBonus, 2);
        $generalTotals['converted_bonus'] = number_format($totalConvertedBonus, 2);
        $generalTotals['deposited_amount'] = number_format($totalDepositedAmount, 2);

        return [
            'users' => $users,
            'general_totals' => $generalTotals
        ];

    }

    /**
     * Format campaigns user
     *
     * @param array $campaigns Camapigns data
     * @param int $user User ID
     * @param int $wllet Wallet ID
     */
    public function formatCampaignUser($campaigns, $user, $wallet)
    {
        foreach ($campaigns as $campaign) {
            $campaign->actions = sprintf(
                '<button type="button" class="btn u-btn-3d btn-sm u-btn-primary mr-2 delete" id="delete" data-route="%s"><i class="hs-admin-trash"></i> %s</button>',
                route('bonus-system.campaigns.users.remover-user-data', [$campaign->id, $user, $wallet]),
                _i('Remove')
            );
        }
    }

    /**
     * Format details
     *
     * @param $campaign
     */
    public function formatDetails($campaign)
    {
        $usersRepo = new UsersRepo();
        // $segmentsRepo = new SegmentsRepo();
        $providersRepo = new ProvidersRepo();
        $timezone = session('timezone');
        $start = $campaign->start_date->setTimezone($timezone)->format('d-m-Y h:i a');
        $end = !is_null($campaign->end_date) ? $campaign->end_date->setTimezone($timezone)->format('d-m-Y h:i a') : null;
        $campaign->start = $start;
        $campaign->end = $end;

        if (isset($campaign->data->percentage)) {
            $campaign->data->percentage = $campaign->data->percentage * 100;
        }

        if (isset($campaign->data->include_users)) {
            $users = $usersRepo->getByIDs($campaign->data->include_users);
            $usersData = [];

            foreach ($users as $user) {
                $userObject = new \stdClass();
                $userObject->id = $user->id;
                $userObject->title = $user->username;
                $usersData[] = $userObject;
            }
            $campaign->include_users = $usersData;
        }

        if (isset($campaign->data->exclude_users)) {
            $users = $usersRepo->getByIDs($campaign->data->exclude_users);
            $usersData = [];

            foreach ($users as $user) {
                $userObject = new \stdClass();
                $userObject->id = $user->id;
                $userObject->title = $user->username;
                $usersData[] = $userObject;
            }
            $campaign->exclude_users = $usersData;
        }

        if (isset($campaign->data->include_segments)) {
            $segments = $segmentsRepo->getByIDs($campaign->data->include_segments);
            $segmentsData = [];

            foreach ($segments as $segment) {
                $segmentObject = new \stdClass();
                $segmentObject->id = $segment->id;
                $segmentObject->title = $segment->name;
                $segmentsData[] = $segmentObject;
            }
            $campaign->include_segments = $segmentsData;
        }

        if (isset($campaign->data->exclude_segments)) {
            $segments = $segmentsRepo->getByIDs($campaign->data->exclude_segments);
            $segmentsData = [];

            foreach ($segments as $segment) {
                $segmentObject = new \stdClass();
                $segmentObject->id = $segment->id;
                $segmentObject->title = $segment->name;
                $segmentsData[] = $segmentObject;
            }
            $campaign->exclude_segments = $segmentsData;
        }

        if (isset($campaign->data->include_payment_methods)) {
            $paymentMethods = $providersRepo->getByIDs($campaign->data->include_payment_methods);
            $paymentMethodsData = [];

            foreach ($paymentMethods as $paymentMethod) {
                if ($paymentMethod->id == Providers::$dotworkers) {
                    $name = _i('Manual transactions');
                } elseif ($paymentMethod->id == Providers::$agents_users) {
                    $name = _i('Agents transactions');
                } else {
                    $name = Providers::getName($paymentMethod->id);
                }
                $paymentMethodsObject = new \stdClass();
                $paymentMethodsObject->id = $paymentMethod->id;
                $paymentMethodsObject->title = $name;
                $paymentMethodsData[] = $paymentMethodsObject;
            }
            $campaign->include_payment_methods = $paymentMethodsData;
        }

        if (isset($campaign->data->exclude_payment_methods)) {
            $paymentMethods = $providersRepo->getByIDs($campaign->data->exclude_payment_methods);
            $paymentMethodsData = [];

            foreach ($paymentMethods as $paymentMethod) {
                if ($paymentMethod->id == Providers::$dotworkers) {
                    $name = _i('Manual transactions');
                } elseif ($paymentMethod->id == Providers::$agents_users) {
                    $name = _i('Agent transactions');
                } else {
                    $name = Providers::getName($paymentMethod->id);
                }
                $paymentMethodsObject = new \stdClass();
                $paymentMethodsObject->id = $paymentMethod->id;
                $paymentMethodsObject->title = $name;
                $paymentMethodsData[] = $paymentMethodsObject;
            }
            $campaign->exclude_payment_methods = $paymentMethodsData;
        }

        if (isset($campaign->data->promo_codes)) {
            $promoCodes = $campaign->data->promo_codes;
            $promoCodesData = [];
            foreach ($promoCodes as $promoCode) {
                $promoCodesObject = new \stdClass();
                $promoCodesObject->btag = $promoCode->btag;
                $promoCodesObject->promo_code = $promoCode->promo_code;
                $promoCodesData[] = $promoCodesObject;
            }
            $campaign->promo_codes = $promoCodesData;
        }

        if (isset($campaign->data->exclude_providers)) {
            $excludeProvidersBets = $providersRepo->getByIDs($campaign->data->exclude_providers);
            $excludeProvidersBetsData = [];

            foreach ($excludeProvidersBets as $excludeProvidersBet) {
                $name = Providers::getName($excludeProvidersBet->id);

                $excludeProvidersBetObject = new \stdClass();
                $excludeProvidersBetObject->id = $excludeProvidersBet->id;
                $excludeProvidersBetObject->title = $name;
                $excludeProvidersBetsData[] = $excludeProvidersBetObject;
            }
            $campaign->exclude_provider_bet = $excludeProvidersBetsData;
        }
    }

    /**
     * Format type providers
     *
     * @param array $types Types providers data
     */
    public function formatTypeProviders($types)
    {
        foreach ($types as $type) {
            $type->name = ProviderTypes::getName($type->id);
        }
    }

    /**
     * Format type providers
     *
     * @param array $types Types providers data
     */
    public function formatProviders($types)
    {
        foreach ($types as $type) {
            $type->name = Providers::getName($type->id);
        }
    }

    /**
     * Format users
     *
     * @param array $users Users data
     * @param int $campaign Campaign ID
     */
    public function formatUsers($users, $campaign)
    {
        foreach ($users as $user) {
            $user->user = sprintf(
                '<a href="%s" class="btn u-btn-3d u-btn-primary btn-sm" target="_blank">%s</a>',
                route('users.details', [$user->id]),
                $user->id
            );
            $user->user_name = $user->username;
            $user->actions .= sprintf(
                '<button type="button" class="btn u-btn-3d btn-sm u-btn-primary mr-2 delete" data-route="%s"><i class="hs-admin-trash"></i> %s</button>',
                route('bonus-system.campaigns.remove-users'),
                _i('Remove')
            );
        }
        $data = [
            'users' => $users
        ];
        return $data;
    }

    /**
     * Format version
     *
     * @param array $versions Versions data
     * @param int $campaign Campaign ID
     */
    public function formatVersion($versions)
    {
        $versionData = [];
        foreach ($versions as $version) {
            $versionObject = new \stdClass();
            $versionObject->id_campaign = $version->id;
            $versionObject->version = $version->version;
            $versionData[] = $versionObject;
        }
       return $versionData;
    }
}
