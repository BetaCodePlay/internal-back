<?php

namespace Dotworkers\Bonus;

use Carbon\Carbon;
use Dotworkers\Bonus\Collections\TournamentRankingCollection;
use Dotworkers\Bonus\Enums\AllocationCriteria;
use Dotworkers\Bonus\Enums\CampaignParticipationStatus;
use Dotworkers\Bonus\Enums\DepositTypes;
use Dotworkers\Bonus\Enums\RolloverStatus;
use Dotworkers\Bonus\Events\RolloverComplete;
use Dotworkers\Bonus\Events\WalletBonus;
use Dotworkers\Bonus\Repositories\CampaignParticipationDetailsRepo;
use Dotworkers\Bonus\Repositories\CampaignParticipationRepo;
use Dotworkers\Bonus\Repositories\CampaignsRepo;
use Dotworkers\Bonus\Repositories\CampaignTotalBetsRepo;
use Dotworkers\Bonus\Repositories\RolloversRepo;
use Dotworkers\Bonus\Repositories\TournamentRankingRepo;
use Dotworkers\Bonus\Repositories\TransactionsRepo;
use Dotworkers\Configurations\Configurations;
use Dotworkers\Configurations\Enums\Providers;
use Dotworkers\Configurations\Enums\ProviderTypes;
use Dotworkers\Configurations\Enums\TransactionStatus;
use Dotworkers\Configurations\Enums\TransactionTypes;
use Dotworkers\Sessions\Sessions;
use Dotworkers\Wallet\Wallet;
use Illuminate\Support\Str;

/**
 * Class Bonus
 *
 * This class allows manage whitelabels bonus
 *
 * @package Dotworkers\Bonus
 * @author  Damelys Espinoza
 * @author  Eborio Linárez
 */
class Bonus
{
    /**
     * Activate bonus by bet
     *
     * @param int $whitelabel Whitelabel ID
     * @param int $user User ID
     * @param int $campaign Campaign ID
     */
    private static function activateBonusByBet($whitelabel, $user, $campaign)
    {
        if (Configurations::getBonus($whitelabel)) {
            $campaignsRepo = new CampaignsRepo();
            $campaignParticipationRepo = new CampaignParticipationRepo();
            $campaignParticipationDetailsRepo = new CampaignParticipationDetailsRepo();
            $campaignData = $campaignsRepo->find($campaign);

            if (self::activeCampaign($campaignData) && $campaignData->status) {
                $data = [
                    'participation_status_id' => CampaignParticipationStatus::$assigned_for_bet,
                    'campaign_id' => $campaign,
                    'user_id' => $user,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ];
                $campaignParticipationRepo->upsert($user, $campaign, $data);
                $campaignParticipationDetailsRepo->store($data);
            }
        }
    }

    /**
     * Check if the campaign is active
     *
     * @param object $campaignData Campaign data
     * @return bool
     */
    private static function activeCampaign($campaignData)
    {
        $date = Carbon::now();

        if (!is_null($campaignData)) {
            if (is_null($campaignData->start_date) && is_null($campaignData->end_date) ||
                is_null($campaignData->start_date) && $campaignData->end_date >= $date ||
                $campaignData->start_date <= $date && is_null($campaignData->end_date) ||
                $campaignData->start_date <= $date && $campaignData->end_date >= $date
            ) {
                return true;

            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    /**
     * Assign bonus
     *
     * @param object $campaign Campaign data
     * @param float $bonus Deposit amount
     * @param int $wallet Wallet ID
     * @param string $walletToken Wallet access token
     * @param string $user User ID
     * @param string $currency Currency ISO
     * @param int $whitelabel Whitelabel ID
     * @param null|string $operator Operator ID
     */
    private static function assignBonus($campaign, $bonus, $wallet, $walletToken, $user, $currency, $whitelabel, $operator = null)
    {
        $transactionsRepo = new TransactionsRepo();
        $firstLocale = array_keys((array)$campaign->translations)[0];
        $language = Sessions::findUserByID($user)->language;

        if (!is_null($language)) {
            $name = isset($campaign->translations->$language) ? $campaign->translations->$language->name : $campaign->translations->$firstLocale->name;
        } else {
            $name = $campaign->translations->$firstLocale->name;
        }

        $transactionData = [
            'campaign' => $name,
            'released' => 'false',
            'provider_transaction' => Str::uuid()->toString()
        ];
        $walletTransaction = Wallet::creditManualTransactions($bonus, Providers::$bonus, $transactionData, $wallet, $walletToken);
        $walletData = [
            'campaign_id' => $campaign->id
        ];
        Wallet::updateByUser($wallet, $walletData, $walletToken);

        $additionalData['wallet_transaction'] = $walletTransaction->data->transaction->id;
        $additionalData['campaign'] = $campaign->name;

        if (!is_null($operator)) {
            $additionalData['operator'] = $operator;
        }

        $transactionType = TransactionTypes::$credit;
        $status = TransactionStatus::$approved;
        $whitelabelTransactionData = [
            'user_id' => $user,
            'amount' => $bonus,
            'currency_iso' => $currency,
            'transaction_type_id' => $transactionType,
            'transaction_status_id' => $status,
            'provider_id' => Providers::$bonus,
            'data' => $additionalData,
            'whitelabel_id' => $whitelabel
        ];
        $transactionsRepo->store($whitelabelTransactionData, $status, []);
    }

    /**
     * Generate rollover
     *
     * @param int $whitelabel Whitelabel ID
     * @param string $user User ID
     * @param float $amount Amount
     * @param int $provider Provider ID
     * @param int $providerType Provider type ID
     * @param string $currency Currency ISO
     */
    public static function bet($whitelabel, $user, $amount, $provider, $providerType, $currency, $additionalData)
    {
        if (Configurations::getBonus($whitelabel)) {
            $campaignsRepo = new CampaignsRepo();
            $rolloversRepo = new RolloversRepo();
            $campaignParticipationRepo = new CampaignParticipationRepo();
            $campaignParticipationDetailsRepo = new CampaignParticipationDetailsRepo();
            $campaignData = $campaignsRepo->findCampaignByProviderType($whitelabel, $currency, $providerType, $user, RolloverStatus::$pending);

            if (!is_null($campaignData) && $campaignData->data->rollovers) {
                if ($campaignData->rollover_status == RolloverStatus::$pending) {
                    $excludeProviders = json_decode($campaignData->exclude_providers);

                    if (is_null($excludeProviders) || !in_array($provider, $excludeProviders)) {
                        if ($providerType == ProviderTypes::$sportbook && $provider == Providers::$altenar) {
                            $apply = false;

                            if ($additionalData['type'] == 'debit') {
                                if (is_null($campaignData->data->simple)) {
                                    foreach ($additionalData['odds']['selections'] as $selection) {
                                        if ($selection['quota'] >= $campaignData->data->odd) {
                                            $apply = true;
                                            break;
                                        }
                                    }
                                } else {
                                    if ($campaignData->data->simple) {
                                        if ($additionalData['odds']['quantity'] == 1) {
                                            if ($additionalData['odds']['selections'][0]['quota'] >= $campaignData->data->odd) {
                                                $apply = true;
                                            }
                                        }
                                    } else {
                                        if ($additionalData['odds']['quantity'] > 1) {
                                            foreach ($additionalData['odds']['selections'] as $selection) {
                                                if ($selection['quota'] >= $campaignData->data->odd) {
                                                    $apply = true;
                                                    break;
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        } else {
                            $apply = true;
                        }

                        if ($apply) {
                            $amountRollover = $amount + $campaignData->total;
                            $remainingAmount = null;

                            if ($amountRollover < $campaignData->target) {
                                $rolloverData = [
                                    'total' => $amountRollover
                                ];

                            } else {
                                $rolloverData = [
                                    'total' => $campaignData->target,
                                    'status' => RolloverStatus::$completed
                                ];
                                $status = CampaignParticipationStatus::$completed_rollover;
                                $participationData = [
                                    'participation_status_id' => $status
                                ];
                                $campaignParticipationRepo->update($campaignData->id, $user, $participationData);

                                $detailsData = [
                                    'campaign_id' => $campaignData->id,
                                    'user_id' => $user,
                                    'participation_status_id' => $status
                                ];
                                if ($whitelabel != 68) {
                                    $campaignParticipationDetailsRepo->store($detailsData);
                                }

                                if ($amountRollover > $campaignData->target) {
                                    $remainingAmount = $amountRollover - $campaignData->target;
                                }
                                event(new RolloverComplete($user, $providerType, $campaignData, $whitelabel, $remainingAmount));
                            }
                            $rolloversRepo->update($campaignData->id, $user, $campaignData->rollover_type_id, $rolloverData);
                        }
                    }
                }
            }
        }
    }

    /**
     * Bonus by bet
     *
     * @param int $whitelabel Whitelabel ID
     * @param string $currency Currency ISO
     * @param int $user User ID
     * @param int $wallet Wallet ID
     * @param string $walletToken Wallet access token
     * @param int $campaignId Campaign ID
     */
    private static function bonusByBet($whitelabel, $currency, $user, $wallet, $walletToken, $campaignId)
    {
        if (Configurations::getBonus($whitelabel)) {
            $campaignsRepo = new CampaignsRepo();
            $rolloversRepo = new RolloversRepo();
            $campaign = $campaignsRepo->find($campaignId);

            if (self::activeCampaign($campaign) && $campaign->status) {
                $campaignData = $campaign->data;
                $bonus = $campaignData->bonus;

                if ($campaignData->rollovers) {
                    $rolloverTypes = $rolloversRepo->getTypes($campaign->id);

                    foreach ($rolloverTypes as $type) {
                        $target = $type->multiplier * $bonus;
                        $rolloversData = [
                            'campaign_id' => $campaign->id,
                            'user_id' => $user,
                            'status' => RolloverStatus::$pending,
                            'rollover_type_id' => $type->id,
                            'target' => $target,
                            'created_at' => Carbon::now(),
                            'updated_at' => Carbon::now(),
                            'expiration_date' => Carbon::now()->addDays($type->days),
                            'bonus' => $bonus
                        ];
                        $rolloversRepo->store($rolloversData);
                    }
                }
                self::assignBonus($campaign, $bonus, $wallet, $walletToken, $user, $currency, $whitelabel, $operator = null);
            }
        }
    }

    /**
     * Credit bonus
     *
     * @param int $user User ID
     * @param string $currency Currency ISO
     * @param int $whitelabel Whitelabel ID
     * @param int $wallet Wallet ID
     * @param object $campaignData Campaign data
     * @param bool $nextBonus Next bonus to credit
     * @param null|float $rolloverAmount Initial rollover amount
     * @return void
     */
    public static function creditBonus($user, $currency, $whitelabel, $wallet, $campaignData, $nextBonus = false, $rolloverAmount = null)
    {
        if (Configurations::getBonus($whitelabel)) {
            $campaignParticipationRepo = new CampaignParticipationRepo();
            $userParticipation = $campaignParticipationRepo->getByUserCurrencyAndStatus($user, $currency);
            $providerTypes = [];

            foreach ($userParticipation as $participation) {
                $providerTypes[] = $participation->provider_type_id;
            }

            if ($nextBonus) {
                $campaignData = $campaignParticipationRepo->findNextBonusToClaim($user, $currency, $providerTypes);
                $campaignData->data = json_decode($campaignData->data);
            }

            if (!is_null($campaignData)) {
                if (in_array(AllocationCriteria::$bet, $campaignData->data->allocation_criteria)) {
                    self::activateBonusByBet($whitelabel, $user, $campaignData->id);

                } else {
                    $walletAccessToken = !is_null(session('wallet_access_token')) ? session('wallet_access_token') : Sessions::findUserByID($user)->wallet_access_token;

                    if (in_array(AllocationCriteria::$registration, $campaignData->data->allocation_criteria)) {
                        self::depositBonus($whitelabel, $currency, $user, $deposit = null, AllocationCriteria::$deposit, $wallet, $walletAccessToken, $campaignData->id, $rolloverAmount, $operator = null);
                    }

                    if (in_array(AllocationCriteria::$deposit, $campaignData->data->allocation_criteria)) {
                        $transactionsRepo = new TransactionsRepo();
                        switch ($campaignData->data->deposit_type) {
                            case DepositTypes::$first: {
                                $firstDeposit = $transactionsRepo->findFirstDeposit($user, $currency);
                                self::depositBonus($whitelabel, $currency, $user, $firstDeposit->amount, AllocationCriteria::$deposit, $wallet, $walletAccessToken, $campaignData->id, $rolloverAmount, $operator = null);
                                break;
                            }
                            case DepositTypes::$next: {
                                $transactionsApproved = $transactionsRepo->findLastDepositBeforeDate($user, $campaignData->currency_iso, $campaignData->start_date);
                                self::depositBonus($whitelabel, $currency, $user,$transactionsApproved->amount, AllocationCriteria::$deposit, $wallet, $walletAccessToken, $campaignData->id, $rolloverAmount, $operator = null);
                                break;
                            }
                        }
                    }

                    $participationData = [
                        'participation_status_id' => CampaignParticipationStatus::$in_use
                    ];
                    $campaignParticipationRepo->update($campaignData->id, $user, $participationData);
                }
            }
        }
    }

    /**
     * Cumulative bets
     *
     * @param int $whitelabel Whitelabel ID
     * @param int $user User ID
     * @param int $campaignId Campaign ID
     * @param float $betAmount Bet amount
     * @param string $currency Currency ISO
     * @param int $providerType Provider type ID
     * @param int $provider Provider ID
     */
    private static function cumulativeBets($whitelabel, $user, $campaignId, $betAmount, $currency, $providerType, $provider, $wallet, $walletToken)
    {
        if (Configurations::getBonus($whitelabel)) {
            $campaignsRepo = new CampaignsRepo();
            $campaignTotalBetsRepo = new CampaignTotalBetsRepo();
            $campaign = $campaignsRepo->find($campaignId);

            if (self::activeCampaign($campaign) && $campaign->status) {
                $campaignData = $campaign->data;
                $bets = $campaignTotalBetsRepo->userParticipationByCampaign($campaignId, $user);

                if ($campaignData->provider_type == $providerType) {
                    if (isset($campaignData->exclude_providers)) {

                        if (!in_array($provider, $campaignData->exclude_providers)) {
                            $data = [
                                'currency_iso' => $currency,
                                'created_at' => Carbon::now(),
                                'updated_at' => Carbon::now(),
                            ];

                            if (is_null($bets)) {
                                $data['amount'] = $betAmount;

                            } else {
                                $totalBet = $bets->amount + $betAmount;
                                $data['amount'] = $totalBet;
                            }

                            if ( $data['amount'] >= $campaignData->total_bets) {
                                $campaignTotalBetsRepo->update($user, $campaignId, $data);
                                self::bonusByBet($whitelabel, $currency, $user, $wallet, $walletToken, $campaignId);
                            }
                        }
                    }
                }
            }
        }
    }

    /**
     * Activate daily bet
     *
     * @param int $whitelabel Whitelabel ID
     * @param string $currency Currency ISO
     * @param string $user User ID
     * @param int $wallet Wallet ID
     * @param int $bet Amount bet
     * @return null
     */
    public static function dailyBet($whitelabel, $currency, $user, $wallet, $bet)
    {
        if (Configurations::getBonus($whitelabel)) {
            $campaignsRepo = new CampaignsRepo();
            $campaignTotalBetsRepo = new CampaignTotalBetsRepo();
            $campaign = $campaignsRepo->findCampaign($whitelabel, $currency, AllocationCriteria::$bet_bonus);

            if (self::activeCampaign($campaign) && $campaign->status) {
                $campaignData = $campaign->data;
                $today = Carbon::now();
                $startDate = $today->copy()->startOfDay();;
                $endDate = $today->copy()->endOfDay();
                $userParticipation = $campaignTotalBetsRepo->userParticipation($campaign->id, $user, $startDate, $endDate);
                $userParticipationByCampaign = $campaignTotalBetsRepo->userParticipationByCampaign($campaign->id, $user);

                if (is_null($userParticipationByCampaign)) {
                    $max = $campaignData->max_amount;
                    if ($bet > $max) {
                        $data = [
                            'campaign_id' => $campaign->id,
                            'user_id' => $user,
                            'amount' => $bet,
                            'currency_iso' => $currency,
                            'created_at' => Carbon::now(),
                            'updated_at' => Carbon::now(),
                        ];
                        $campaignTotalBetsRepo->update($user, $campaign->id, $data);
                        $bonus = $campaignData->bonus;
                        event(new WalletBonus($wallet, $currency, $campaign->name, $bonus));
                        return $bonus;
                    } else {
                        $data = [
                            'campaign_id' => $campaign->id,
                            'user_id' => $user,
                            'amount' => $bet,
                            'currency_iso' => $currency,
                            'created_at' => Carbon::now(),
                            'updated_at' => Carbon::now(),
                        ];
                        $campaignTotalBetsRepo->update($user, $campaign->id, $data);
                        return null;
                    }
                } else {
                    if (is_null($userParticipation)) {
                        $max = $campaignData->max_amount;
                        $amountBet = $bet;

                        if ($amountBet < $max) {
                            $bonusData = [
                                'amount' => $amountBet,
                                'updated_at' => Carbon::now()
                            ];
                            $campaignTotalBetsRepo->update($user, $campaign->id, $bonusData);
                            return null;
                        } else {
                            $bonusData = [
                                'amount' => $amountBet,
                                'updated_at' => Carbon::now()
                            ];
                            $campaignTotalBetsRepo->update($user, $campaign->id, $bonusData);
                            $bonus = $campaignData->bonus;
                            event(new WalletBonus($wallet, $currency, $campaign->name, $bonus));
                            return $bonus;
                        }
                    } else {
                        $max = $campaignData->max_amount;
                        if ($userParticipation->amount < $max) {
                            $amountBet = $bet + $userParticipation->amount;

                            if ($amountBet < $max) {
                                $bonusData = [
                                    'amount' => $amountBet,
                                    'updated_at' => Carbon::now()
                                ];
                                $campaignTotalBetsRepo->update($user, $campaign->id, $bonusData);
                                return null;
                            } else {
                                $bonusData = [
                                    'amount' => $amountBet,
                                    'updated_at' => Carbon::now()
                                ];
                                $campaignTotalBetsRepo->update($user, $campaign->id, $bonusData);
                                $bonus = $campaignData->bonus;
                                event(new WalletBonus($wallet, $currency, $campaign->name, $bonus));
                                return $bonus;
                            }
                        } else {
                            return null;
                        }
                    }
                }
            } else {
                return null;
            }
        } else {
            return null;
        }
    }

    /**
     * Activate deposit bonus
     *
     * @param int $whitelabel Whitelabel ID
     * @param string $currency Currency ISO
     * @param string $user User ID
     * @param float $deposit Deposit amount
     * @param int $allocationCriteria Allocation criteria ID
     * @param int $wallet Wallet ID
     * @param string $walletToken Wallet access token
     * @param int $campaignId Campaign ID
     * @param null|double $rolloverAmount Initial rollover amount
     * @param string $operator Operator ID
     */
    private static function depositBonus($whitelabel, $currency, $user, $deposit, $allocationCriteria, $wallet, $walletToken, $campaignId, $rolloverAmount = null, $operator = null)
    {
        if (Configurations::getBonus($whitelabel)) {
            $campaignsRepo = new CampaignsRepo();
            $rolloversRepo = new RolloversRepo();
            $campaign = $campaignsRepo->find($campaignId);

            if (self::activeCampaign($campaign) && $campaign->status) {
                $campaignData = $campaign->data;

                if ($allocationCriteria == AllocationCriteria::$deposit) {
                    if (isset($campaignData->percentage)) {
                        $bonus = $deposit * $campaignData->percentage;

                        if ($bonus > $campaignData->limit) {
                            $bonus = $campaignData->limit;
                        }
                    } else {
                        $bonus = $campaignData->bonus;
                    }

                } else {
                    $bonus = $campaignData->bonus;
                }

                if ($campaignData->rollovers) {
                    $rolloverTypes = $rolloversRepo->getTypes($campaign->id);

                    foreach ($rolloverTypes as $type) {
                        if ($allocationCriteria == AllocationCriteria::$deposit) {
                            if (is_null($type->include_deposit)) {
                                $target = $type->multiplier * ($bonus + $deposit);
                            } elseif ($type->include_deposit) {
                                $target = $type->multiplier * $deposit;
                            } else {
                                $target = $type->multiplier * $bonus;
                            }
                        } else {
                            $target = $type->multiplier * $bonus;
                        }

                        $rolloversData = [
                            'campaign_id' => $campaign->id,
                            'user_id' => $user,
                            'status' => RolloverStatus::$pending,
                            'deposit' => $deposit,
                            'rollover_type_id' => $type->id,
                            'target' => $target,
                            'created_at' => Carbon::now(),
                            'updated_at' => Carbon::now(),
                            'expiration_date' => Carbon::now()->addDays($type->days),
                            'bonus' => $bonus
                        ];

                        if (!is_null($rolloverAmount) && $rolloverAmount > 0) {
                            $rolloversData['total'] = $rolloverAmount;
                        }
                        $rolloversRepo->store($rolloversData);
                    }
                }
                self::assignBonus($campaign, $bonus, $wallet, $walletToken, $user, $currency, $whitelabel, $operator);
            }
        }
    }

    /**
     * Activate login bonus
     *
     * @param int $whitelabel Whitelabel ID
     * @param string $currency Currency ISO
     * @param string $user User ID
     * @param int $wallet Wallet ID
     * @param string $walletToken Wallet access token
     * @param int $logins Logins
     */
    public static function login($whitelabel, $currency, $user, $wallet, $walletToken, $logins)
    {
        if (Configurations::getBonus($whitelabel)) {
            $campaignsRepo = new CampaignsRepo();
            $campaign = $campaignsRepo->findCampaign($whitelabel, $currency, AllocationCriteria::$login_bonus);

            if (self::activeCampaign($campaign) && $campaign->status) {
                $campaignData = $campaign->data;
                $loginsData = 1;

                if ($logins == $loginsData) {
                    $bonus = $campaignData->bonus;
                    self::assignBonus($campaign, $bonus, $wallet, $walletToken, $user, $currency, $whitelabel);
                }
            }
        }
    }

    /**
     * Get user rollover complete
     *
     * @param int $whitelabel Whitelabel ID
     * @param string $user User ID
     * @param string $currency Currency ISO
     * @param int $providerType Provider type ID
     * @return bool
     */
    public static function rolloverComplete($whitelabel, $user, $currency, $providerType)
    {
        $campaignsRepo = new CampaignsRepo();
        $campaignData = $campaignsRepo->findCampaignByProviderType($whitelabel, $currency, $providerType, $user);

        if (!is_null($campaignData)) {
            if ($campaignData->rollover_status == RolloverStatus::$completed) {
                return true;
            } else {
                return false;
            }
        } else {
            return true;
        }
    }

    /**
     * Activate tournament
     *
     * @param int $whitelabel Whitelabel ID
     * @param string $currency Currency ISO
     * @param string $user User ID
     * @param int $game Game ID
     * @param float $amount Amount
     * @param int $provider Provider ID
     */
    public static function tournament($whitelabel, $currency, $user, $game, $amount, $provider)
    {
        if (Configurations::getBonus($whitelabel)) {
            $campaignsRepo = new CampaignsRepo();
            $campaign = $campaignsRepo->findCampaign($whitelabel, $currency, AllocationCriteria::$tournament);

            if (self::activeCampaign($campaign) && $campaign->status) {
                $campaignData = $campaign->data;
                $providersConfig = $campaign->data->providers;

                if ($campaignData->bonus_min = '*' && $campaignData->bonus_max = '*' ||
                        $campaignData->bonus_min = '*' && $campaignData->bonus_max <= $amount ||
                            $campaignData->bonus_min >= $amount && $campaignData->bonus_max = '*' ||
                                $campaignData->bonus_min >= $amount && $campaignData->bonus_max <= $amount
                ) {
                    foreach ($providersConfig as $providerData) {
                        $gamesData = $providerData->games;

                        if ($providerData->id == $provider) {

                            if ($providerData->games = '*' || in_array($game, $gamesData)) {

                                if ($providerData->exclude = '*' || !in_array($game, $providerData)) {
                                    $tournamentRankingRepo = new TournamentRankingRepo();
                                    $generated = $tournamentRankingRepo->getTournamentByUser($whitelabel, $currency, $campaign->id, $user, $provider);

                                    if (!is_null($generated)) {
                                        $total = $generated->total + $amount;
                                        $totalData = [
                                            'total' => $total
                                        ];
                                        $tournamentRankingRepo->update($campaign->id, $user, $whitelabel, $currency, $provider, $totalData);
                                    } else {
                                        $totalData = [
                                            'user_id' => $user,
                                            'whitelabel_id' => $whitelabel,
                                            'currency_iso' => $currency,
                                            'total' => $amount,
                                            'provider_id' => $provider,
                                            'campaign_id' => $campaign->id,
                                            'created_at' => Carbon::now(),
                                            'updated_at' => Carbon::now(),
                                        ];
                                        $tournamentRankingRepo->store($totalData);
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
    }

    /**
     *  Get tournaments names
     *
     * @param int $whitelabel Whitelabel ID
     * @param string $currency Currency ISO
     * @return object
     */
    public static function tournamentsNames($whitelabel, $currency)
    {
        if (Configurations::getBonus($whitelabel)) {
            $campaignsRepo = new CampaignsRepo();
            $status = false;
            $campaignData = $campaignsRepo->getCampaignName($whitelabel, $currency, AllocationCriteria::$tournament, $status);

            if (!is_null($campaignData)) {
                return $campaignData;
            } else {
                $campaignData = null;
            }
            return $campaignData;
        }
    }

    /**
     *  Get tournament ranking
     *
     * @param int $whitelabel Whitelabel ID
     * @param string $currency Currency ISO
     *
     * @return \stdClass|null
     */
    public static function tournamentRanking($whitelabel, $currency)
    {
        if (Configurations::getBonus($whitelabel)) {
            $campaignsRepo = new CampaignsRepo();
            $campaignData = $campaignsRepo->findCampaign($whitelabel, $currency, AllocationCriteria::$tournament);

            if (self::activeCampaign($campaignData) && $campaignData->status) {
                $tournamentRankingRepo = new TournamentRankingRepo();
                $tournamentRankingCollection = new TournamentRankingCollection();
                $ranking = $tournamentRankingRepo->getTournamentRankingList($whitelabel, $currency, $campaignData->id);
                $tournamentRankingCollection->formatRanking($ranking, $campaignData->data);

                $data = new \stdClass();
                $data->ranking = $ranking;
                $data->campaign = $campaignData;

            } else {
                $data = null;
            }
            return $data;
        }
    }

    /**
     *  Get tournament ranking by campaign
     *
     * @param int $whitelabel Whitelabel ID
     * @param string $currency Currency ISO
     * @param int $campaign Campaign ID
     * @return object
     */
    public static function tournamentRankingByCampign($whitelabel, $currency, $campaign)
    {
        if (Configurations::getBonus($whitelabel)) {
            $campaignsRepo = new CampaignsRepo();
            $status = false;
            $campaignData = $campaignsRepo->findById($campaign, $status);

            if (!is_null($campaignData)) {
                $tournamentRankingRepo = new TournamentRankingRepo();
                $tournamentRankingCollection = new TournamentRankingCollection();
                $ranking = $tournamentRankingRepo->getTournamentRankingList($whitelabel, $currency, $campaign);
                $tournamentRankingCollection->formatRanking($ranking, $campaignData->data);
            } else {
                $ranking = null;
            }
            return $ranking;
        }
    }

    /**
     * Activate wallet balance bonus
     *
     * @param int $whitelabel Whitelabel ID
     * @param string $currency Currency ISO
     * @param string $user User ID
     * @param int $wallet Wallet ID
     * @param int $balance Balance in wallet
     * @return null
     */
    public static function walletBalance($whitelabel, $currency, $user, $wallet, $balance)
    {
        if (Configurations::getBonus($whitelabel)) {
            $campaignsRepo = new CampaignsRepo();
            $campaignParticipationRepo = new CampaignParticipationRepo();
            $campaign = $campaignsRepo->findCampaign($whitelabel, $currency, AllocationCriteria::$wallet_bonus);

            if (self::activeCampaign($campaign) && $campaign->status) {
                $campaignData = $campaign->data;

                $today = Carbon::now();
                $startDate = $today->copy()->startOfDay();;
                $endDate = $today->copy()->endOfDay();
                $campaignUser = $campaignParticipationRepo->userParticipation($campaign->id, $user, $startDate, $endDate);
                $min = $campaignData->min_amount;

                if ($balance < $min && is_null($campaignUser)) {
                    $data = [
                        'campaign_id' => $campaign->id,
                        'user_id' => $user,
                        'participation_status_id' => CampaignParticipationStatus::$in_use,
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now(),
                    ];
                    $campaignParticipationRepo->upsert($user, $campaign->id, $data);
                    $bonus = $campaignData->bonus;
                    event(new WalletBonus($wallet, $currency, $campaign->name, $bonus));
                    return $bonus;
                } else {
                    return null;
                }
            } else {
                return null;
            }
        } else {
            return null;
        }
    }
}
