<?php

namespace App\DotSuite\Repositories;
use App\DotSuite\Entities\LobbyDotSuiteGames;
use Dotworkers\Configurations\Configurations;

/**
 * Class LobbyDotSuiteGamesRepo
 *
 * This class allows to interact with LobbyDotSuiteGames entity
 *
 * @package App\Core\Repositories
 * @author  Genesis Perez
 */
class LobbyDotSuiteGamesRepo
{
    /**
     * Delete whitelabel game
     *
     * @param int $games Whitelabel Game ID
     * @param int $whitelabel Whitelabel ID
     * @return mixed
     */
    public function delete($games, $whitelabel)
    {
        $game = LobbyDotSuiteGames::where('dotsuite_game_id', $games)
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
    public function getGamesDotsuiteWhitelabel($whitelabel, $category, $provider, $route, $order, $game, $image)
    {
        $games = LobbyDotSuiteGames::select('dotsuite_games.name','lobby_dotsuite_whitelabels.whitelabel_id', 'lobby_dotsuite_whitelabels.dotsuite_game_id',
            'lobby_dotsuite_whitelabels.route','lobby_dotsuite_whitelabels.order','lobby_dotsuite_whitelabels.created_at', 'lobby_dotsuite_whitelabels.image', 'providers.name as provider_name')
            ->join('whitelabels', 'lobby_dotsuite_whitelabels.whitelabel_id', 'whitelabels.id')
            ->join('dotsuite_games', 'lobby_dotsuite_whitelabels.dotsuite_game_id', 'dotsuite_games.id')
            ->join('providers', 'dotsuite_games.provider_id', '=', 'providers.id')
            ->where('lobby_dotsuite_whitelabels.whitelabel_id',$whitelabel);

        if (!is_null($provider)) {
            $games->where('dotsuite_games.provider_game_id', $provider);
        }

        if (!is_null($route)) {
            $games->where('lobby_dotsuite_whitelabels.route', $route);
        }

        if (!is_null($image)) {
            $games->where('lobby_dotsuite_whitelabels.image', $image);
        }

        if (!is_null($order)) {
            $games->where('lobby_dotsuite_whitelabels.order', $order);
        }

        if (!is_null($game)) {
            $games->where('dotsuite_games.name', $game);
        }

        $data = $games->get();
        return $data;
    }

    /**
     * Search by games whitelabel games
     *
     * @param int $provider whitelabel Game ID
     * @param int $cetegory Category Game ID
     * @param int $whitelabel Whitelabel ID
     * @return static
     */
    public function searchGamesByWhitelabel($whitelabel)
    {
        $game = LobbyDotSuiteGames::select('dotsuite_games.name')
            ->join('dotsuite_games', 'lobby_dotsuite_whitelabels.dotsuite_game_id', 'dotsuite_games.id')
            ->where('lobby_dotsuite_whitelabels.whitelabel_id',$whitelabel)
            ->get();
        return $game;
    }

    /**
     * Find route
     *
     * @param null|string $route Route String
     * @return mixed
     */
    public function findRoute($route)
    {
        return LobbyDotSuiteGames::where('route', $route)
            ->get();
    }

    /**
     * Find by ID
     *
     * @param int $id Games ID
     * @return mixed
     */
    public function findById($id)
    {
        $games = LobbyDotSuiteGames::where('dotsuite_game_id', $id)
            ->first();
        return $games;
    }

    /**
     * Search by games whitelabel games
     *
     * @param int $games whitelabel Game ID
     *
     * @param int $whitelabel Whitelabel ID
     * @return static
     */
    public function searchByDotsuiteGames($games, $whitelabel)
    {
        $game = LobbyDotSuiteGames::where('dotsuite_game_id', $games)
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
        $games = LobbyDotSuiteGames::create($data);
        return $games;
    }

    /**
     * Update whitelabel games
     *
     * @param int $game dotsuite games ID
     * @param String $route dotsuite games route
     * @param String $name dotsuite games image
     * @param array $data Lobby dotsuite games data
     * @return static
     */
    public function update($game, $route, $data)
    {

        $games = LobbyDotSuiteGames::where('dotsuite_game_id', $game)
            ->where('whitelabel_id', Configurations::getWhitelabel())
            ->where('route', $route)
            ->update($data);
        return $games;
    }

    /**
     * Update images
     *
     * @param int $id SectionModal ID
     * @param array $data SectionModal data
     * @return mixed
     */
    public function updateImage($id, $data)
    {
        return LobbyDotSuiteGames::where('dotsuite_game_id', $id)
            ->update($data);
    }
}
