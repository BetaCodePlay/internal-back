<?php


namespace App\Core\Collections;

/**
 * Class LobbyGamesCollection
 *
 * This class allows to format lobby games data
 *
 * @package App\Core\Collections
 * @author  Derluin Gonzalez
 */
class LobbyGamesCollection
{
    /**
     * Format all LobbyGames
     *
     * @param array $games Sliders data
     */
    public function formatAll($games)
    {
        $timezone = session('timezone');
        foreach ($games as $game) {

            switch ($game->mobile) {
                case true:
                {
                    $game->descriptions = $game->name . ' ' . _i('Mobile');
                    break;
                }
                case false:
                {
                    $game->descriptions = $game->name . ' ' . _i('PC');
                    break;
                }
            }
            $start = !is_null($game->created_at) ? $game->created_at->setTimezone($timezone)->format('d-m-Y') : null;
            $game->start = $start;
            $game->actions .= sprintf(
                '<button type="button" class="btn u-btn-3d btn-sm u-btn-primary mr-2 delete" data-route="%s"><i class="hs-admin-trash"></i> %s</button>',
                route('lobby-games.delete', [$game->game_id, $game->whitelabel_id]),
                _i('Delete')
            );

        }
    }

    /**
     * Format details
     *
     * @param $games
     */
    public function formatDetails($games)
    {
        $timezone = session('timezone');
        $url = s3_asset("sliders/static/{$games->image}");
        $games->file = $games->image;
        $games->image = "<img src='$url' class='img-responsive' width='600'>";
        $start = !is_null($games->start_date) ? $games->start_date->setTimezone($timezone)->format('d-m-Y') : null;
        $end = !is_null($games->start_date) ? $games->end_date->setTimezone($timezone)->format('d-m-Y') : null;
        $games->start = $start;
        $games->end = $end;
    }
}
