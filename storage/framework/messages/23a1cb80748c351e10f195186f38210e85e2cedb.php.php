<?php


namespace App\Store\Repositories;

use App\Store\Entities\ActionConfiguration;
use Illuminate\Support\Facades\DB;


/**
 * Class ActionsConfigurationsRepo
 *
 * This class allows to interact with actions_configurations entity
 *
 * @package App\Store\Repositories
 * @author  Damelys Espinoza
 */
class ActionsConfigurationsRepo
{
    /**
     * Delete action
     *
     * @param int $id action ID
     * @return mixed
     */
    public function delete($id)
    {
        $action = \DB::table('actions_configurations')
            ->where('action_id', $id)
            ->delete();
        return $action;
    }

    /**
     * Get action configuration
     *
     * @param $id
     * @param int $whitelabel Whitelabel ID
     * @param string $currency Currency ISO
     * @return mixed
     */
    public function find($id, $currency, $whitelabel)
    {
        $action = ActionConfiguration::select('actions_configurations.*', 'actions.name')
            ->join('actions', 'actions.id', '=', 'actions_configurations.action_id')
            ->where('action_id', $id)
            ->where('currency_iso', $currency)
            ->where('whitelabel_id', $whitelabel)
            ->first();
        return $action;
    }

    /**
     * Get all configurations
     *
     * @param $currency
     * @param $whitelabel
     * @return mixed
     */
    public function getAll($whitelabel)
    {
        $actions =  ActionConfiguration::select('actions_configurations.*', 'actions.name', 'actions_types.name as type')
            ->join('actions', 'actions.id', '=', 'actions_configurations.action_id')
            ->join('actions_types', 'actions_types.id', '=', 'actions.action_type_id')
            ->where('actions_configurations.whitelabel_id', $whitelabel)
            ->get();
        return $actions;
    }

    /**
     * Get all configurations
     *
     * @param int $action Action ID
     * @return mixed
     */
    public function getByAction($action)
    {
        return  ActionConfiguration::select('actions_configurations.*')
            ->where('actions_configurations.action_id', $action)
            ->get();
    }

    /**
     * Store actions configurations
     *
     * @param $action
     * @return ActionConfiguration
     */
    public function store($action)
    {
        $actions = new ActionConfiguration();
        $actions->fill($action);
        $actions->save();
        return $actions;
    }

    /**
     * Update actions
     *
     * @param int $id Actions configurations ID
     * @param array $configurationData Data action configuration
     * @param string $currency Currency ISO
     * @return mixed
     */
    public function update($id, $currency, $whitelabel, $configurationData)
    {
        $actions =  DB::table('actions_configurations')->updateOrInsert(
                ['action_id' => $id, 'currency_iso' => $currency, 'whitelabel_id' => $whitelabel],
             $configurationData
            );
        return $actions;
    }
}
