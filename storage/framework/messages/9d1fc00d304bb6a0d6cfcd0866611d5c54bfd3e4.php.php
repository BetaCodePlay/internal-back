<?php

namespace App\BonusSystem;

use App\BonusSystem\Repositories\CampaignParticipationDetailsRepo;
use App\BonusSystem\Repositories\CampaignParticipationRepo;
use App\BonusSystem\Repositories\RolloversRepo;
use App\BonusSystem\Repositories\RolloversTypesRepo;
use Carbon\Carbon;
use Dotworkers\Bonus\Bonus;
use Dotworkers\Bonus\Enums\CampaignParticipationStatus;
use Dotworkers\Bonus\Enums\RolloverStatus;
use Dotworkers\Configurations\Configurations;
use Dotworkers\Wallet\Wallet;
use Illuminate\Support\Facades\Log;

/**
 * Class Rollovers
 *
 * This class allows manage rollovers data
 *
 * @package App\BonusSystem
 * @author  Eborio Linarez
 */
class Rollovers
{
    /**
     * Cancel rollovers
     *
     * @param int $campaign Campaign ID
     * @param int $user User ID
     * @param string $currency Currency ISO
     * @param int $participationStatus Participation status
     * @param int $whitelabel Whitelabel ID
     * @return void
     */
    public static function cancelRollovers($campaign, $user, $currency, $participationStatus, $whitelabel)
    {
        $clientToken = null;
        $transaction = null;

        try {
            $campaignParticipationRepo = new CampaignParticipationRepo();
            $campaignParticipationDetailsRepo = new CampaignParticipationDetailsRepo();
            $rolloversTypesRepo = new RolloversTypesRepo();
            $rolloversRepo = new RolloversRepo();
            $clientToken = Wallet::clientAccessToken();
            $providerTypes = [];

            $campaignParticipation = $campaignParticipationRepo->findByCampaignAndUser($campaign, $user);

            $rolloverData = [
                'status' => RolloverStatus::$cancelled,
            ];
            $participationData = [
                'participation_status_id' => $participationStatus,
                'updated_at' => Carbon::now()
            ];

            $rolloversTypeData = $rolloversTypesRepo->getByCampaign($campaign);

            if (!is_null($rolloversTypeData)) {
                if ($campaignParticipation->participation_status_id == CampaignParticipationStatus::$in_use) {
                    $transaction = Wallet::clearWallet($user, $currency, $rolloversTypeData->provider_type_id, $clientToken->access_token);
                    if (!is_null($transaction->data->transaction)) {
                        $finalAmount = $transaction->data->transaction->amount;
                    } else {
                        $finalAmount = 0;
                    }
                    $rolloverData['final_amount'] = $finalAmount;
                    $rolloverData['converted'] = 0;
                    $campaignParticipationRepo->update($campaign, $user, $participationData);
                    $rolloversRepo->update($campaign, $user, $rolloverData);
                }
            } else {
                $campaignParticipationRepo->update($campaign, $user, $participationData);
            }

            if ($whitelabel != 68) {
                $detailsData = [
                    'campaign_id' => $campaignParticipation->campaign_id,
                    'user_id' => $user,
                    'participation_status_id' => $participationStatus
                ];
                $campaignParticipationDetailsRepo->store($detailsData);
            }

            if ($campaignParticipation->participation_status_id == CampaignParticipationStatus::$in_use) {
                $userParticipation = $campaignParticipationRepo->getByUserCurrencyAndStatus($user, $currency, $campaignParticipation->participation_status_id);
                $bonusWallets = Wallet::getByClient($user, $currency, $bonus = true, $clientToken->access_token);

                foreach ($userParticipation as $participation) {
                    $providerTypes[] = $participation->provider_type_id;
                }

                $nextBonusCampaign = $campaignParticipationRepo->findNextBonusToClaim($user, $currency, $providerTypes);

                if (!is_null($nextBonusCampaign)) {
                    foreach ($bonusWallets->data->bonus as $bonusWallet) {
                        if ($bonusWallet->provider_type_id == $nextBonusCampaign->provider_type_id) {
                            Bonus::creditBonus($user, $currency, $whitelabel, $bonusWallet->id, $nextBonusCampaign, $nextBonus = true);
                            $status = CampaignParticipationStatus::$in_use;

                            $participationData = [
                                'participation_status_id' => CampaignParticipationStatus::$in_use,
                                'updated_at' => Carbon::now()
                            ];
                            $campaignParticipationRepo->update($nextBonusCampaign->id, $user, $participationData);
                            if ($whitelabel != 68) {
                                $detailsData = [
                                    'campaign_id' => $nextBonusCampaign->id,
                                    'user_id' => $user,
                                    'participation_status_id' => $status
                                ];
                                $campaignParticipationDetailsRepo->store($detailsData);
                            }
                        }
                    }
                }
            }
        } catch (\Exception $ex) {
            Log::error(__METHOD__, ['exception' => $ex, 'campaign' => $campaign, 'user' => $user, 'transaction' => $transaction, 'currency' => $currency, 'client_token' => $clientToken]);
        }
    }
}
