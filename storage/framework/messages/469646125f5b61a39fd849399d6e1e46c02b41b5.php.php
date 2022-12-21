<?php


namespace App\Store\Repositories;
use App\Store\Entities\ActionType;


/**
 * Class ActionsTypeRepo
 *
 * This class allows to interact with actions_type entity
 *
 * @package App\Store\Repositories
 * @author  Damelys Espinoza
 */
class ActionsTypeRepo
{
    /**
     * Get all actions types
     *
     * @return mixed
     */
    public function all()
    {
        $actions = ActionType::select('actions_types.*')
            ->get();
        return $actions;
    }
}