<?php

namespace App\Core\Repositories;

use App\Core\Entities\LobbyGames;
use Illuminate\Support\Facades\DB;

/**
 * Class LobbyGamesRepo
 *
 * This class allows to interact with gameLobby entity
 *
 * @package App\Core\Repositories
 * @author Genesis Perez
 */
class LobbyGamesRepo
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
        $game = LobbyGames::where('game_id', $games)
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
    public function getGamesWhitelabel($whitelabel, $category, $provider, $route, $order, $game, $image)
    {
        $games = LobbyGames::select('games.name','lobby_games.whitelabel_id', 'lobby_games.game_id',
            'lobby_games.route','lobby_games.order','lobby_games.created_at', 'lobby_games.image', 'providers.name as provider_name')
            ->join('whitelabels', 'lobby_games.whitelabel_id', 'whitelabels.id')
            ->join('games', 'lobby_games.game_id', 'games.id')
            ->join('providers', 'games.provider_id', '=', 'providers.id')
            ->where('lobby_games.whitelabel_id',$whitelabel);
        \Log::notice(__METHOD__, ['games' => $games]);
        if (!is_null($provider)) {
            $games->where('games.provider_game_id', $provider);
        }

        if (!is_null($route)) {
            $games->where('lobby_games.route', $route);
        }

        if (!is_null($image)) {
            $games->where('lobby_games.image', $image);
        }

        if (!is_null($order)) {
            $games->where('lobby_games.order', $order);
        }

        if (!is_null($game)) {
            $games->where('games.name', $game);
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
        $game = LobbyGames::select('games.name')
            ->join('games', 'lobby_games.game_id', 'games.id')
            ->where('lobby_games.whitelabel_id',$whitelabel)
            ->get();
        return $game;
    }

    /**
     * Find by ID
     *
     * @param int $id Games ID
     * @return mixed
     */
    public function findById($id)
    {
        $games = LobbyGames::where('game_id', $id)
            ->first();
        return $games;
    }

    /**
     * Find route
     *
     * @param null|string $route Route String
     * @return mixed
     */
    public function findRoute($route)
    {
        return LobbyGames::where('route', $route)
            ->get();
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
        $game = LobbyGames::where('game_id', $games)
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
        $games = LobbyGames::create($data);
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

        $games = LobbyGames::where('game_id', $game)
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
        return LobbyGames::where('game_id', $id)
            ->update($data);
    }

}
