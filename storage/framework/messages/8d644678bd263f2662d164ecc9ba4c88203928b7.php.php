<?php


namespace App\CRM\Repositories;

use App\CRM\Entities\MarketingCampaign;
use App\CRM\Enums\MarketingCampaignsStatus;

/**
 * Class MarketingCampaignsRepo
 *
 * This class allows to interact with Marketing campaign entity
 *
 * @package App\CRM\Repositories
 * @author  Damelys Espinoza
 */
class MarketingCampaignsRepo
{
    /**
     * Get all
     *
     * @return mixed
     */
    public function all()
    {
        $campaigns = MarketingCampaign::select('marketing_campaigns.*')
            ->join('segments', 'segments.id', '=', 'marketing_campaigns.segment_id')
            ->where('marketing_campaigns.status', MarketingCampaignsStatus::$pending)
            ->get();
        return $campaigns;
    }

    /**
     * Get all by whitelabel
     *
     * @param int $whitelabel Whitelabel ID
     * @return mixed
     */
    public function allByWhitelabel($whitelabel)
    {
        $campaigns = MarketingCampaign::select('marketing_campaigns.*', 'segments.name', 'email_templates.title as email_title')
            ->join('segments', 'segments.id', '=', 'marketing_campaigns.segment_id')
            ->join('email_templates', 'email_templates.id', '=', 'marketing_campaigns.email_template_id')
            ->where('marketing_campaigns.whitelabel_id', $whitelabel)
            ->orderBy('id', 'DESC')
            ->get();
        return $campaigns;
    }

    /**
     * Delete campaign
     *
     * @param int $id Campaign ID
     * @return mixed
     */
    public function delete($id)
    {
        $campaign = MarketingCampaign::where('id', $id)
            ->whitelabel()
            ->delete();
        return $campaign;
    }

    /**
     * Find
     *
     * @param int $id Campaign ID
     * @return mixed
     */
    public function find($id)
    {
        $campaign = MarketingCampaign::where('id', $id)
            ->whitelabel()
            ->first();
        return $campaign;
    }

    /**
     * Store campaign
     *
     * @param array $data Marketing campaign data
     * @return mixed
     */
    public function store($data)
    {
        $campaign = MarketingCampaign::create($data);
        return $campaign;
    }

    /**
     * Update
     *
     * @param int $id Campaign ID
     * @param array $data Campaign data
     * @return mixed
     */
    public function update($id, $data)
    {
        $campaign = MarketingCampaign::find($id)
            ->update($data);
        return $campaign;
    }
}
