<?php

namespace App\Store\Repositories;

use App\Store\Entities\Action;

/**
 * Class ActionsRepo
 *
 * This class allows to interact with actions entity
 *
 * @package App\Store\Repositories
 * @author  Damelys Espinoza
 */
class ActionsRepo
{
    /**
     * Get all actions
     *
     * @return mixed
     */
    public function getAll()
    {
        $actions =  Action::select('actions.*', 'actions_types.name as type')
            ->join('actions_types', 'actions_types.id', '=', 'actions.action_type_id')
            ->get();
        return $actions;
    }
}