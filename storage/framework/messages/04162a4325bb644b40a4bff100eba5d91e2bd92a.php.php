<?php

namespace App\DotSuite\Collections;
use Xinax\LaravelGettext\Facades\LaravelGettext;

/**
 * Class LobbyDotSuiteGamesCollection
 *
 * This class allows to format lobby dotsuite games data
 *
 * @package App\DotSuite\Collections
 * @author Genesis Perez
 */

class LobbyDotSuiteGamesCollection
{
    /**
     * Format all Lobby Dotsuite Games
     *
     * @param array $games Games data
     */
    public function formatAll($games, $menu, $order)
    {
        foreach ($games as $game) {
            $game->game = $game->name;
            $game->provider = $game->provider_name;
            $game->actions .= sprintf(
                '<button type="button" class="btn u-btn-3d btn-sm u-btn-primary mr-2 delete" data-route="%s"><i class="hs-admin-trash"></i> %s</button>',
                route('dot-suite.lobby-games.delete', [$game->dotsuite_game_id]),
                _i('Delete')
            );
            $order = $game->order;
            if ($order == 0) {
                $game->order = _i('Has no order');
            }

            foreach ($menu as $item) {
                $locale = LaravelGettext::getLocale();
                if ($item->route == $game->route) {
                    if ($item->route == 'core.index') {
                        $game->route = _i('Home');

                    } else {
                        $game->route = $item->metas->$locale->name;
                    }
                    break;
                }
            }
        }
    }

    /**
     * Format all WhitelabelsGames
     *
     * @param array $games Games data
     */
    public function format($games, $menu, $order)
    {
        foreach ($games as $game) {
            $game->game = $game->name;
            $game->provider = $game->provider_name;
            $game->actions .= sprintf(
                '<button type="button" class="btn u-btn-3d btn-sm u-btn-primary mr-2 delete" data-route="%s"><i class="hs-admin-trash"></i> %s</button>',
                route('dot-suite.lobby.delete', [$game->game_id]),
                _i('Delete')
            );
            $order = $game->order;
            if ($order == 0) {
                $game->order = _i('Has no order');
            }

            foreach ($menu as $item) {
                $locale = LaravelGettext::getLocale();
                if ($item->route == $game->route) {
                    if ($item->route == 'core.index') {
                        $game->route = _i('Home');

                    } else {
                        $game->route = $item->metas->$locale->name;
                    }
                    break;
                }
            }
        }
    }
}
