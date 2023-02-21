<?php

use Xinax\LaravelGettext\Facades\LaravelGettext;

/**
 * Class LobbyGamesCollection
 *
 * This class allows to format lobby games data
 *
 * @package App\Core\Collections
 * @author  Genesis Perez
 */
class LobbyGamesCollection
{
    /**
     * Format all Lobby Dotsuite Games
     *
     * @param array $games Games data
     */
    public function formatAll($games, $items, $order, $image)
    {
        foreach ($games as $game) {
            $game->game = $game->name;
            $game->provider = $game->provider_name;
            $game->actions = sprintf(
                '<button type="button" class="btn u-btn-3d btn-sm u-btn-primary mr-2 delete" data-route="%s"><i class="hs-admin-trash"></i> %s</button>',
                route('games.delete', [$game->game_id]),
                _i('Delete game')
            );
            $game->actions .= sprintf(
                '<a href="%s" class="btn u-btn-3d btn-sm u-btn-bluegray mr-2"><i class="hs-admin-pencil"></i> %s</a>',
                route('games.edit', [$game->game_id]),
                _i('Edit')
            );
            $order = $game->order;
            if ($order == 0) {
                $game->order = _i('Has no order');
            }
            $image = $game->image;
            if (!is_null($image)) {
                $url = s3_asset("lobby/{$game->image}");
                $game->image = "<img src='$url' class='img-responsive'>";
            }else{
                $game->image = _i('Not image');
            }
            foreach ($items as $item) {
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
     * Format lobby dotsuite games name
     *
     * @param array $games Games data
     */
    public function formatDotsuiteGames($games)
    {
        foreach ($games as $game) {
            $game->description = $game->name;
        }
    }

    /**
     * Format lobby dotsuite games image
     *
     * @param array $image Image data
     *
     */
    public function formatByImage($image)
    {
        if (!is_null($image)) {
            if (!is_null($image->image)) {
                $url = s3_asset("lobby/{$image->image}");
                $image->file = $image->image;
                $image->image = "<img src='$url' class='img-responsive'>";
            } else {
                $image->image = '';
            }
        } else {
            $image = new \stdClass();
            $image->image = null;
            $image->file = null;
        }
        return $image;
    }

}
