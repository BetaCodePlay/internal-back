<?php

namespace Dotworkers\Store\Repositories;

use Dotworkers\Store\Entities\ActionConfiguration;

/**
 * Class ActionsConfigurationsRepo
 *
 * This class allows to interact with ActionConfiguration entity
 *
 * @package Dotworkers\Store\Repositories
 * @author  Damelys Espinoza
 */
class ActionsConfigurationsRepo
{
    /**
     * Get action configuration
     *
     * @param int $action Action ID
     * @param int $providerType Type provider ID
     * @param int $whitelabel Whitelabel ID
     * @param string $currency Currency ISO
     * @return mixed
     */
    public function getAction($action, $providerType, $whitelabel, $currency, $status)
    {
        $actions = ActionConfiguration::on(config('store.connection'))
            ->select('actions_configurations.*', 'actions.action_type_id', 'actions.name as action')
            ->join('actions', 'actions.id', '=', 'actions_configurations.action_id')
            ->where('actions_configurations.action_id', $action)
            ->where('actions_configurations.provider_type_id', $providerType)
            ->where('actions_configurations.whitelabel_id', $whitelabel)
            ->where('actions_configurations.currency_iso', $currency)
            ->where('actions_configurations.status', $status)
            ->first();
        return $actions;
    }
}