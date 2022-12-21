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
 * @author Derluin Gonzalez
 */
class LobbyGamesRepo
{

    /**
     * All lobby games
     *
     * @return static
     */
    public function all()
    {
        $games = LobbyGames::select('games.name', 'games.mobile', 'whitelabels.description', 'custom_lobby_games.whitelabel_id', 'custom_lobby_games.game_id')
            ->join('whitelabels', 'custom_lobby_games.whitelabel_id', 'whitelabels.id')
            ->join('games', 'custom_lobby_games.game_id', 'games.id')
            ->get();
        return $games;
    }

    /**
     * All lobby games
     *
     * @return static
     */
    public function getWhitelabel($whitelabel)
    {
        $games = LobbyGames::select('games.name', 'games.mobile', 'whitelabels.description', 'custom_lobby_games.whitelabel_id', 'custom_lobby_games.game_id', 'custom_lobby_games.created_at')
            ->join('whitelabels', 'custom_lobby_games.whitelabel_id', 'whitelabels.id')
            ->join('games', 'custom_lobby_games.game_id', 'games.id')
            ->where('custom_lobby_games.whitelabel_id',$whitelabel)
            ->get();
        return $games;
    }

    /**
     * Search by games lobby games
     *
     * @param int $games Lobby Game ID
     * @param int $whitelabel Whitelabel ID
     * @return static
     */
    public function searchByGames($games, $whitelabel)
    {
        $game = LobbyGames::where('game_id', $games)
            ->where('whitelabel_id', $whitelabel)
            ->first();
        return $game;
    }

    /**
     * Store lobby games
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
     * Update lobby game
     *
     * @param int $id Lobby Game ID
     * @param array $data Lobby Game data
     * @return mixed
     */
    public function update($id, $data)
    {
        $games = LobbyGames::find($id);
        $games->fill($data);
        $games->save();
        return $games;
    }

    /**
     * Delete lobby game
     *
     * @param int $games Lobby Game ID
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
}
