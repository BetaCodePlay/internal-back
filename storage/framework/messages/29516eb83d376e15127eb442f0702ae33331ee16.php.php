<?php


namespace App\CRM\Collections;

use App\CRM\Enums\MarketingCampaignsStatus;
use Dotworkers\Security\Enums\Permissions;
use Illuminate\Support\Facades\Gate;

/**
 * Class MarketingCampaignsCollection
 *
 * This class allows to format marketing campaigns data
 *
 * @package App\CRM\Collections
 * @author  Damelys Espinoza
 */
class MarketingCampaignsCollection
{
    /**
     * Format all
     *
     * @param array $campaigns Campaign data
     */
    public function formatAll($campaigns)
    {
        $timezone = session('timezone');
        foreach ($campaigns as $campaign) {
            $date = $campaign->scheduled_date->setTimezone($timezone)->format('d-m-Y h:i a');
            $campaign->title = !is_null($campaign->title) ? $campaign->title : _i('Without title');
            $campaign->language = $campaign->language == '*' ? _i('Everybody') : $campaign->language;
            $campaign->currency_iso = $campaign->currency_iso == '*' ? _i('Everybody') : $campaign->currency_iso;
            $campaign->segment = !is_null($campaign->name) ? $campaign->name : _i('Without name');
            $campaign->email_title = !is_null($campaign->email_title) ? $campaign->email_title : _i('Without name');
            $campaign->date = $date;

            switch ($campaign->status) {

                case MarketingCampaignsStatus::$pending: {
                    $statusClass = 'blue';
                    $statusText = _i('Pending');
                    break;
                }

                case MarketingCampaignsStatus::$sent: {
                    $statusClass = 'teal';
                    $statusText = _i('Sent');
                    break;
                }

                case MarketingCampaignsStatus::$cancelled: {
                    $statusClass = 'lightred';
                    $statusText = _i('Cancelled');
                    break;
                }
            }
            $campaign->status = sprintf(
                '<span class="u-label g-bg-%s g-rounded-20 g-px-15 g-mr-10 g-mb-15">%s</span>',
                $statusClass,
                $statusText
            );

            if (Gate::allows('access', Permissions::$manage_marketing_campaigns)) {
                $campaign->actions = sprintf(
                    '<a href="%s" class="btn u-btn-3d btn-sm u-btn-bluegray mr-2"><i class="hs-admin-pencil"></i> %s</a>',
                    route('marketing-campaigns.edit', [$campaign->id]),
                    _i('Edit')
                );
                $campaign->actions .= sprintf(
                    '<button type="button" class="btn u-btn-3d btn-sm u-btn-primary mr-2 delete" data-route="%s"><i class="hs-admin-trash"></i> %s</button>',
                    route('marketing-campaigns.delete', [$campaign->id]),
                    _i('Delete')
                );
            }
        }
    }

    /**
     * Format all
     *
     * @param array $campaign Campaign data
     */
    public function formatCampaign($campaign)
    {
        $timezone = session('timezone');
        $date = $campaign->scheduled_date->setTimezone($timezone)->format('d-m-Y h:i a');
        $campaign->date = $date;
    }
}
