<?php

namespace App\WhitelabelsGames\Collections;

use Xinax\LaravelGettext\Facades\LaravelGettext;
/**
 * Class WhitelabelsGamesCollection
 *
 * This class allows to format whitelabels games data
 *
 * @package App\Core\Collections
 * @author Carlos Hurtado
 */
class WhitelabelsGamesCollection
{
    /**
     * Format all WhitelabelsGames
     *
     * @param array $games Games data
     */
    public function formatAll($games)
    {
        $language = LaravelGettext::getLocale();
        foreach ($games as $game) {
            switch ($game->mobile) {
                case true:
                {
                    $game->device = _i('Mobile');
                    break;
                }
                case false:
                {
                    $game->device = _i('Desktop');
                    break;
                }
            }
            $game->game = $game->name;
            $translations = json_decode($game->translations);
            $name = $translations->$language ?? $translations->en_US;
            $game->category = $name;
            $game->provider = $game->provider_name;
            $game->actions .= sprintf(
                '<button type="button" class="btn u-btn-3d btn-sm u-btn-primary mr-2 delete" data-route="%s"><i class="hs-admin-trash"></i> %s</button>',
                route('whitelabels-games.delete', [$game->game_id, $game->whitelabel_game_category_id]),
                _i('Delete')
            );
        }
    }

    /**
     * Format all WhitelabelsGames
     *
     * @param array $gamesCategories Games categories data
     */
    public function formatCategories($gamesCategories)
    {
        $language = LaravelGettext::getLocale();
        foreach ($gamesCategories as $category) {
            $translations = json_decode($category->translations);
            $name = $translations->$language ?? $translations->en_US;
            $category->category = $name;
        }
    }
}
