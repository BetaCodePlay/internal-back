<?php

namespace App\BonusSystem\Collections;

use Dotworkers\Bonus\Enums\CampaignParticipationStatus;

/**
 * Class CampaignParticipationStatusCollection
 *
 * This class allows format participation status data
 *
 * @package App\BonusSystem\Collections
 * @author Eborio Linárez
 */
class CampaignParticipationStatusCollection
{
    public function formatStatus($participationStatus)
    {
        foreach ($participationStatus as $status) {
            switch ($status->id) {
                case CampaignParticipationStatus::$assigned:
                {
                    $status->name = _i('Assigned');
                    break;
                }
                case CampaignParticipationStatus::$in_use:
                {
                    $status->name = _i('In use');
                    break;
                }
                case CampaignParticipationStatus::$canceled_by_user:
                {
                    $status->name = _i('Canceled by user');
                    break;
                }
                case CampaignParticipationStatus::$canceled_by_administrator:
                {
                    $status->name = _i('Canceled by administrator');
                    break;
                }
                case CampaignParticipationStatus::$completed_rollover:
                {
                    $status->name = _i('Completed rollover');
                    break;
                }
                case CampaignParticipationStatus::$expired_rollover:
                {
                    $status->name = _i('Expired rollover');
                    break;
                }
                case CampaignParticipationStatus::$canceled_by_withdrawal:
                {
                    $status->name = _i('Canceled by withdrawal');
                    break;
                }
            }
        }
    }
}
