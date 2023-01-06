<?php

namespace App\Core\Repositories;

use App\Core\Entities\SectionGame;

/**
 * Class SectionGamesRepo
 *
 * This class allows to interact with SectionGame entity
 *
 * @package App\Core\Repositories
 * @author  Genesis Perez
 */
class SectionGamesRepo
{

    /**
     * Get all games by section
     *
     * @param string $section Section String
     * @return mixed
     */
    public function allGameBySection($section)
    {
        $games = SectionGame::select('games.name', 'games.mobile', 'game_section.whitelabel_id', 'game_section.additional_info', 'game_section.section', 'game_section.game_id', 'game_section.id')
            ->where('section', $section)
            ->join('games', 'game_section.game_id', 'games.id')
            ->whitelabel()
            ->get();
        return $games;
    }

    /**
     * Delete games
     *
     * @param int $id SectionGame ID
     * @return mixed
     */
    public function delete($id)
    {
        $game= SectionGame::where('id', $id)
            ->whitelabel()
            ->first();
        $game->delete();
        return $game;
    }

    /**
     * Find games by ID
     *
     * @param int $id Game ID
     * @return mixed
     */
    public function find($id)
    {
        $game = SectionGame::where('id', $id)
            ->first();
        return $game;
    }

    /**
     * Search by games and section
     *
     * @param int $games Lobby Game ID
     * @param int $whitelabel Whitelabel ID
     * @param string $section Section
     * @return static
     */
    public function searchByGamesAndSection($games, $whitelabel, $section)
    {
        $game = SectionGame::where('game_id', $games)
            ->where('whitelabel_id', $whitelabel)
            ->where('section', $section)
            ->first();
        return $game;
    }
    /**
     * Search by games and section and additional
     *
     * @param int $games Game ID
     * @param int $whitelabel Whitelabel ID
     * @param string $section Section Game
     * @param string $additional Section Game
     * @return static
     */
    public function searchBySectionAndAdditional($games, $whitelabel, $section, $additional)
    {
        $game = SectionGame::where('game_id', $games)
            ->where('whitelabel_id', $whitelabel)
            ->where('section', $section)
            ->where('additional_info', $additional)
            ->first();
        return $game;
    }


    /**
     * Store games
     *
     * @param array $data Games data
     * @return static
     */
    public function store($data)
    {
        $games = SectionGame::create($data);
        return $games;
    }

    /**
     * Update games
     *
     * @param int $id SectionGame ID
     * @param array $data SectionGame data
     * @return mixed
     */
    public function update($id, $data)
    {
        $game = SectionGame::find($id);
        $game->fill($data);
        $game->save();
        return $game;
    }
}
