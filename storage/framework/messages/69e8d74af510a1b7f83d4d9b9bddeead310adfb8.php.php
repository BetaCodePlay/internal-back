<?php

namespace App\Core\Collections;


/**
 * Class GamesCollection
 *
 * This class allows to format games data
 *
 * @package App\Core\Collections
 * @author  Derluin Gonzalez
 */
class GamesCollection
{
    /**
     * Format lobby
     *
     * @param array $games Games data
     */
    public function formatGames($games)
    {
        foreach ($games as $game) {
            switch ($game->mobile) {
                case true:
                {
                    $game->description = $game->name . ' ' . _i('(Mobile)');
                    break;
                }
                case false:
                {
                    $game->description = $game->name . ' ' . _i('(Desktop)');
                    break;
                }
            }
        }
    }

}
