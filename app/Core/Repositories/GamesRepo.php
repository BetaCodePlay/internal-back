<?php

namespace App\Core\Repositories;

use App\Core\Entities\Game;
use Dotworkers\Configurations\Enums\GamesStatus;
use Dotworkers\Configurations\Enums\Providers;
use Illuminate\Support\Facades\DB;

/**
 * Class GamesRepo
 *
 * This class allows to interact with Game entity
 *
 * @package App\Core\Repositories
 * @author Derluin Gonzalez
 */
class GamesRepo
{
    /**
     * Get all games
     *
     * @return mixed
     */
    public function all()
    {
        $games = Game::orderBy('name', 'ASC')
            ->get();
        return $games;
    }


    /**
     * Find games
     *
     * @param int $games games type ID
     * @return mixed
     */
    public function find($games)
    {
        $games = Game::find($games);
        return $games;
    }

    /**
     * Get by status
     *
     * @param array $status Game status
     * @return mixed
     */
    public function getByStatus($status)
    {
        $games = Game::select('games.*')
            ->whereIn('status', $status)
            ->orderBy('description', 'ASC')
            ->get();
        return $games;
    }


    /**
     * Get fun games
     *
     * @param int $whitelabel Whitelabel ID
     * @param string $currency Currency ISO
     * @param array $providers Providers IDs
     * @return mixed
     */
    public function getFunGames($whitelabel, $currency, $providers)
    {
        $games = Game::select('games.*')
            ->join('providers', 'games.provider_id', '=', 'providers.id')
            ->join('credentials', 'providers.id', '=', 'credentials.provider_id')
            ->where('credentials.client_id', $whitelabel)
            ->where('credentials.currency_iso', $currency)
            ->where('fun', true)
            ->where('credentials.status', true)
            ->whereIn('providers.id', $providers)
            ->where('providers.status', true)
            ->where(function ($query) use ($whitelabel) {
                $query->where(function ($query) use ($whitelabel) {
                    $query->whereNotIn(
                        'games.id',
                        [
                            \DB::raw(
                                "SELECT exclude_games.game_id FROM exclude_games WHERE exclude_games.whitelabel_id = '$whitelabel'"
                            )
                        ]
                    )
                        ->where('games.status', GamesStatus::$active);
                })
                    ->orWhereIn(
                        'games.id',
                        [
                            \DB::raw(
                                "SELECT include_games.game_id FROM include_games WHERE include_games.whitelabel_id = '$whitelabel'"
                            )
                        ]
                    );
            })
            ->get();
        return $games;
    }

    /**
     * Get games by provider
     *
     * @param int $whitelabel Whitelabel ID
     * @param string $currency Currency ISO
     * @param int $provider Provider ID
     * @param string $devices Devices
     * @return mixed
     */
    public function getGamesByProvider($whitelabel, $currency, $provider, $devices)
    {
        $games = Game::select('games.*')
            ->join('providers', 'games.provider_id', '=', 'providers.id')
            ->join('credentials', 'providers.id', '=', 'credentials.provider_id')
            ->where('credentials.client_id', $whitelabel)
            ->where('credentials.currency_iso', $currency)
            ->where('credentials.status', true)
            ->where('providers.id', $provider)
            ->where('providers.status', true)
            ->where(function ($query) use ($whitelabel) {
                $query->where(function ($query) use ($whitelabel) {
                    $query->whereNotIn(
                        'games.id',
                        [
                            \DB::raw(
                                "SELECT exclude_games.game_id FROM exclude_games WHERE exclude_games.whitelabel_id = '$whitelabel'"
                            )
                        ]
                    )
                        ->where('games.status', GamesStatus::$active);
                })
                    ->orWhereIn(
                        'games.id',
                        [
                            \DB::raw(
                                "SELECT include_games.game_id FROM include_games WHERE include_games.whitelabel_id = '$whitelabel'"
                            )
                        ]
                    );
            });

        if (! is_null($devices)) {
            if ($devices == 'true') {
                $games->where('games.mobile', true);
            }

            if ($devices == 'false') {
                $games->where('games.mobile', false);
            }
        }
        $data = $games->get();
        return $data;
    }

    /**
     * Update games
     *
     * @param int $id Game ID
     * @param array $data Game data
     * @return mixed
     */
    public function update($id, $data)
    {
        $games = Game::find($id);
        $games->fill($data);
        $games->save();
        return $games;
    }

    /**
     * Get dotsuite games
     *
     * @param int $whitelabel Whitelabel ID
     * @param string $currency Currency ISO
     * @return mixed
     */
    public function getGames($whitelabel, $currency, $provider)
    {
        $games = Game::select(
            'games.id',
            'games.name',
            'games.slug',
            'games.image',
            'games.maker',
            'games.category',
            'games.provider_id'
        )
            ->join('providers', 'games.provider_id', '=', 'providers.id')
            ->join('credentials', 'providers.id', '=', 'credentials.provider_id')
            ->where('credentials.client_id', $whitelabel)
            ->where('credentials.currency_iso', $currency)
            ->where('providers.id', $provider)
            ->where('credentials.status', true)
            ->where(function ($query) use ($whitelabel) {
                $query->where(function ($query) use ($whitelabel) {
                    $query->whereNotIn(
                        'games.id',
                        [
                            \DB::raw(
                                "SELECT exclude_games.game_id FROM exclude_games WHERE exclude_games.whitelabel_id = '$whitelabel'"
                            )
                        ]
                    )
                        ->where('games.status', GamesStatus::$active);
                })
                    ->orWhereIn(
                        'games.id',
                        [
                            \DB::raw(
                                "SELECT include_games.game_id FROM include_games WHERE include_games.whitelabel_id = '$whitelabel'"
                            )
                        ]
                    );
            })
            ->get();
        return $games;
    }

    /**
     * Get games by category and maker
     *
     * @param int $whitelabel Whitelabel ID
     * @param string $currency Currency ISO
     * @param string $category Games Category
     * @param string $maker Games Maker
     * @param int $product Games product_id
     * @return mixed
     */
    public function getGamesByCategoryAndMaker($whitelabel, $currency, $provider, $category, $maker, $product)
    {
        $games = Game::select(
            'games.id',
            'games.name',
            'games.slug',
            'games.image',
            'games.maker',
            'games.category',
            'games.provider_id'
        )
            ->join('providers', 'games.provider_id', '=', 'providers.id')
            ->join('credentials', 'providers.id', '=', 'credentials.provider_id')
            ->where('credentials.client_id', $whitelabel)
            ->where('credentials.currency_iso', $currency)
            ->where('credentials.status', true)
            ->where(function ($query) use ($whitelabel) {
                $query->where(function ($query) use ($whitelabel) {
                    $query->whereNotIn(
                        'games.id',
                        [
                            \DB::raw(
                                "SELECT exclude_games.game_id FROM exclude_games WHERE exclude_games.whitelabel_id = '$whitelabel'"
                            )
                        ]
                    )
                        ->where('games.status', GamesStatus::$active);
                })
                    ->orWhereIn(
                        'games.id',
                        [
                            \DB::raw(
                                "SELECT include_games.game_id FROM include_games WHERE include_games.whitelabel_id = '$whitelabel'"
                            )
                        ]
                    );
            });

        if (! is_null($provider)) {
            $games->where('providers.id', $provider);
        }

        if (! is_null($category)) {
            $games->where('games.category', $category);
        }

        if (! is_null($maker)) {
            $games->where('games.maker', $maker);
        }

        if (! is_null($product)) {
            $games->where('games.product_id', $product);
        }
        $data = $games->get();
        return $data;
    }

    /**
     * Get dotSuite games by provider
     *
     * @param int $provider
     * @return mixed
     */
    public function getDotSuiteGamesByProvider($provider)
    {
        $games = Game::where('provider_id', $provider)
            ->get();
        return $games;
    }

    /**
     * Get dotSuite games by provider, maker, category and product
     *
     * @param int $provider
     * @param string $category
     * @param string $maker
     * @param string $product
     * @return mixed
     */
    public function getDotSuiteGamesByProviderAndMakerAndCategoryAndProduct($provider, $category, $maker, $product)
    {
        $games = Game::where('maker', $maker);
        if (! is_null($provider)) {
            $games->where('provider_id', $provider);
        }
        if (! is_null($category)) {
            $games->where('category', $category);
        }
        if (! is_null($product)) {
            $games->where('product_id', $product);
        }
        $data = $games->get();
        return $data;
    }

    /**
     * Get categories
     *
     * @return mixed
     */
    public function getCategories()
    {
        $games = Game::select('category')
            ->distinct()
            ->get();
        return $games;
    }

    /**
     * Get categories by makers
     *
     * @return mixed
     */
    public function getCategoriesByMaker($maker)
    {
        $games = Game::select('category')
            ->distinct()
            ->where('maker', $maker)
            ->get();
        return $games;
    }

    /**
     * Get makers by category
     *
     * @param int $provider
     * @return mixed
     */
    public function getMakersByCategory($category)
    {
        $games = Game::select('maker')
            ->distinct()
            ->where('category', $category)
            ->get();
        return $games;
    }

    /**
     * Get makers
     *
     * @param int $provider
     * @return mixed
     */
    public function getMakers()
    {
        $games = Game::select('maker')
            ->distinct()
            ->get();
        return $games;
    }

    /**
     * Get makers by provider
     *
     * @param int $provider
     * @return mixed
     */
    public function getMakersByProvider($provider)
    {
        $games = Game::select('maker')
            ->distinct()
            ->where('provider_id', $provider)
            ->get();
        return $games;
    }

    /**
     * Get products
     *
     * @param int
     * @return mixed
     */
    public function getProducts()
    {
        return Game::select('product_id')
            ->distinct()
            ->where('provider_id', Providers::$bet_connections)
            ->get();
    }

    /**
     * Get providers by maker
     *
     * @param int
     * @return mixed
     */
    public function getProvidersByMaker($maker)
    {
        return Game::select('providers.id', 'providers.name')
            ->distinct()
            ->join('providers', 'games.provider_id', '=', 'providers.id')
            ->join('credentials', 'providers.id', '=', 'credentials.provider_id')
            ->where('games.maker', $maker)
            ->get();
    }

    public function best10(string|int $whitelabelId, string $currency)
    {
        return DB::table('closures_users_totals_2023_hour')
            ->select('closures_users_totals_2023_hour.game_id', 'closures_users_totals_2023_hour.currency_iso', 'closures_users_totals_2023_hour.whitelabel_id',
                DB::raw("DATE_FORMAT(closures_users_totals_2023_hour.created_at, '%Y-%m') AS month"),
                DB::raw("SUM(closures_users_totals_2023_hour.played) AS total_played"),
                'games.name AS game_name')
            ->join('games', 'closures_users_totals_2023_hour.game_id', '=', 'games.id')
            ->groupBy('closures_users_totals_2023_hour.game_id', 'closures_users_totals_2023_hour.currency_iso', 'closures_users_totals_2023_hour.whitelabel_id', 'month')
            ->orderByDesc('total_played')
            ->limit(10)
            ->get();
    }

}
