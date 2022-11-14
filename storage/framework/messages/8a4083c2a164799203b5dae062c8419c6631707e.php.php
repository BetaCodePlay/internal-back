<?php

namespace App\Core\Collections;

use Dotworkers\Security\Enums\Permissions;
use Illuminate\Support\Facades\Gate;

/**
 * Class SectionGamesCollection
 *
 * This class allows to format section games data
 *
 * @package App\Core\Collections
 * @author  Genesis Perez
 */
class SectionGamesCollection
{
    /**
     * Format all games
     *
     * @param array $games Games data
     */
    public function formatAll($games)
    {
        foreach ($games as $game) {
            switch ($game->mobile) {
                case true:
                {
                    $game->description = $game->name . ' ' . _i('Mobile');
                    break;
                }
                case false:
                {
                    $game->description = $game->name . ' ' . _i('PC');
                    break;
                }
            }
            if (Gate::allows('access', Permissions::$manage_section_games)) {
                $game->actions .= sprintf(
                    '<button type="button" class="btn u-btn-3d btn-sm u-btn-primary mr-2 delete" data-route="%s"><i class="hs-admin-trash"></i> %s</button>',
                    route('section-games.delete', [$game->id]),
                    _i('Delete')
                );
            }
        }
    }
}
