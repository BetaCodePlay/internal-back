<?php

namespace App\DotSuite\Repositories;

use App\DotSuite\Entities\DotSuiteGame;
use Dotworkers\Configurations\Enums\GamesStatus;

/**
 * Class DotSuiteGameRepo
 *
 * This class allows to interact with DotSuite free spin entity
 *
 * @package App\DotSuite\Repositories
 * @author  Carlos Hurtado
 */
class DotSuiteGamesRepo
{

    /**
     * Get all sets by provider
     *
     * @param int $provider Provider ID.
     * @param string $category Category
     * @return mixed
     */
    public function allGamesByProvider($provider, $category)
    {
        $games = DotSuiteGame::select('id', 'name', 'game AS game_id' )
            ->where('provider_id', $provider)
            ->where('category', $category)
            ->where('status', GamesStatus::$active)
            ->get();
        return $games;
    }

    /**
     * Get all sets in provider
     *
     * @param array $providers Provider ID.
     * @param string $category Category
     * @return mixed
     */
    public function allGamesInProviders($providers, $category)
    {
        $games = DotSuiteGame::select('id', 'name', 'game AS game_id' )
            ->whereIn('provider_id', $providers)
            ->where('category', $category)
            ->where('status', GamesStatus::$active)
            ->get();
        return $games;
    }

    /**
     * Get all sets in provider
     *
     * @param int $game Game ID.
     * @return mixed
     */
    public function findByGame($game)
    {
        $games = DotSuiteGame::select('id', 'name', 'game AS game_id' )
            ->where('game', $game)
            ->where('status', GamesStatus::$active)
            ->first();
        return $games;
    }
}
