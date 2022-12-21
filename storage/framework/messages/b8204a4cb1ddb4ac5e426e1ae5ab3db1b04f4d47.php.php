<?php

namespace Dotworkers\Configurations\Repositories;

use Dotworkers\Configurations\Entities\Whitelabel;

/**
 * Class WhitelabelsRepo
 *
 * This class allows to interact with Whitelabel entity
 *
 * @package Dotworkers\Configurations\Repositories
 * @author  Eborio Linarez
 */
class WhitelabelsRepo
{
    /**
     * Get configurations by domain
     *
     * @param string $domain Whitelabel domain
     * @return mixed
     */
    public function getConfigurationsByDomain($domain)
    {
        $configurations = Whitelabel::on(config('configurations.replica'))
            ->select('whitelabels.name AS whitelabel_name', 'whitelabels.description AS whitelabel_description', 'whitelabels.status AS whitelabel_status',
                'configurations.whitelabel_id', 'whitelabels.social_reason AS whitelabel_social_reason', 'whitelabels.domain', 'whitelabels.url AS backoffice_url',
                'configurations.data')
            ->join('configurations', 'whitelabels.id', '=', 'configurations.whitelabel_id')
            ->where('domain', $domain)
            ->orderBy('component_id', 'ASC')
            ->get();
        return $configurations;
    }

    /**
     * Get configurations by url
     *
     * @param string $url Whitelabel $url
     * @return mixed
     */
    public function getConfigurationsByUrl($url)
    {
        $configurations = Whitelabel::on(config('configurations.replica'))
            ->select('whitelabels.name AS whitelabel_name', 'whitelabels.description AS whitelabel_description', 'whitelabels.status AS whitelabel_status',
                'configurations.whitelabel_id', 'whitelabels.social_reason AS whitelabel_social_reason', 'whitelabels.domain', 'whitelabels.url AS backoffice_url',
                'configurations.data',
                'whitelabels.domain')
            ->join('configurations', 'whitelabels.id', '=', 'configurations.whitelabel_id')
            ->where('url', $url)
            ->orderBy('component_id', 'ASC')
            ->get();
        return $configurations;
    }

    /**
     * Get configurations by domain
     *
     * @param string $whitelabel Whitelabel ID
     * @return mixed
     */
    public function getConfigurationsByWhitelabel($whitelabel)
    {
        $configurations = Whitelabel::on(config('configurations.replica'))
            ->select('whitelabels.name AS whitelabel_name', 'whitelabels.description AS whitelabel_description', 'whitelabels.url AS backoffice_url', 'whitelabels.status AS whitelabel_status',
                'configurations.whitelabel_id', 'configurations.data', 'whitelabels.domain')
            ->join('configurations', 'whitelabels.id', '=', 'configurations.whitelabel_id')
            ->where('configurations.whitelabel_id', $whitelabel)
            ->orderBy('component_id', 'ASC')
            ->get();
        return $configurations;
    }

    /**
     * Get configuration by whitelabel and component
     *
     * @param int $whitelabel Whitelabel ID
     * @param int $component Component ID
     * @return mixed
     */
    public function getConfigurationByWhitelabelAndComponent($whitelabel, $component)
    {
        $configurations = Whitelabel::on(config('configurations.replica'))
            ->select('configurations.data')
            ->join('configurations', 'whitelabels.id', '=', 'configurations.whitelabel_id')
            ->where('whitelabels.id', $whitelabel)
            ->where('configurations.component_id', $component)
            ->orderBy('component_id', 'ASC')
            ->first();
        return $configurations;
    }

    /**
     * Update configuration
     *
     * @param int $id Whitelabel ID
     * @param int $component Component ID
     * @param array $data Configuration data
     * @return mixed
     */
    public function updateConfiguration($id, $component, $data)
    {
        $whitelabel = Whitelabel::find($id);
        $whitelabel->components()->syncWithoutDetaching([$component => ['data' => json_encode($data)]]);
        return $whitelabel;
    }
}