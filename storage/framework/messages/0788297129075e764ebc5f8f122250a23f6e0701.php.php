<?php

namespace App\Store\Collections;

use App\Store\Enums\Actions;

/**
 * Class ActionsCollection
 *
 * This class allows to format action type data
 *
 * @package App\Store\Collections
 * @author  Carlos Hurtado
 */
class ActionsCollection
{
    /**
     * Format all actions type
     *
     * @param array $actions Actions data
     */
    public function formatActionsType($actionsType)
    {
        $currency = session('currency');
        foreach ($actionsType as $action) {
            $action->name = Actions::getName($action->id);
            $action->actions = sprintf(
                '<a href="%s" class="btn u-btn-3d btn-sm u-btn-bluegray mr-2"><i class="hs-admin-pencil"></i> %s</a>',
                route('store.actions.edit', [$currency, $action->id]),
                _i('Edit')
            );
        }
    }
}
