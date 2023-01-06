<?php

namespace App\WhitelabelsGames\Repositories;

use App\WhitelabelsGames\Entities\WhitelabelsGames;

/**
 * Class WhitelabelGamesRepo
 *
 * This class allows to interact with WhitelabelGames entity
 *
 * @package App\WhitelabelsGames\Repositories
 * @author  Carlos Hurtado
 */
class WhitelabelGamesRepo
{
    /**
     * Delete whitelabel game
     *
     * @param int $games Whitelabel Game ID
     * @param int $category Whitelabel category ID
     * @param int $whitelabel Whitelabel ID
     * @return mixed
     */
    public function delete($games, $category, $whitelabel)
    {
        $game = WhitelabelsGames::where('game_id', $games)
            ->where('whitelabel_game_category_id', $category)
            ->where('whitelabel_id', $whitelabel)
            ->delete();
        return $game;
    }

    /**
     * All Whitelabel games
     *
     * @param int $provider Provider ID
     * @param int $category Category ID
     * @return static
     */
    public function getGamesWhitelabel($whitelabel, $provider, $category)
    {

        $games = WhitelabelsGames::select('games.name', 'games.mobile', 'whitelabel_games.whitelabel_id', 'whitelabel_games.game_id', 'whitelabel_games.whitelabel_game_category_id', 'whitelabel_games.created_at', 'providers.name as provider_name', 'whitelabel_games_categories.translations')
            ->join('whitelabel_games_categories', 'whitelabel_games.whitelabel_game_category_id', 'whitelabel_games_categories.id')
            ->join('whitelabels', 'whitelabel_games.whitelabel_id', 'whitelabels.id')
            ->join('games', 'whitelabel_games.game_id', 'games.id')
            ->join('providers', 'games.provider_id', '=', 'providers.id')
            ->where('whitelabel_games.whitelabel_id',$whitelabel);

        if (!is_null($provider)) {
            $games->where('games.provider_id', $provider);
        }

        if (!is_null($category)) {
            $games->where('whitelabel_games.whitelabel_game_category_id', $category);
        }

        $data = $games->get();
        return $data;
    }

    /**
     * Search by games whitelabel games
     *
     * @param int $games whitelabel Game ID
     * @param int $cetegory Category Game ID
     * @param int $whitelabel Whitelabel ID
     * @return static
     */
    public function searchByGames($games, $cetegory, $whitelabel)
    {
        $game = WhitelabelsGames::where('game_id', $games)
            ->where('whitelabel_game_category_id', $cetegory)
            ->where('whitelabel_id', $whitelabel)
            ->first();
        return $game;
    }

    /**
     * Store whitelabel games
     *
     * @param array $data Lobby Games data
     * @return static
     */
    public function store($data)
    {
        $games = WhitelabelsGames::create($data);
        return $games;
    }
}
