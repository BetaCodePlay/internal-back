<?php


namespace Dotworkers\Bonus\Repositories;

use Dotworkers\Bonus\Entities\TournamentRanking;

/**
 * Class TournamentRankingRepo
 *
 * This class allows to interact with tournament ranking entity
 *
 * @package Dotworkers\Store\Repositories
 * @author  Damelys Espinoza
 */
class TournamentRankingRepo
{
    /**
     * Get tournament points by user
     *
     * @param int $whitelabel Whitelabel ID
     * @param string $currency Currency ISO
     * @param int $campaign Campaign ID
     * @param int $user User ID
     * @param int $provider Provider ID
     * @return mixed
     */
    public function getTournamentByUser($whitelabel, $currency, $campaign, $user, $provider)
    {
        $list = TournamentRanking::on(config('bonus.connection'))
            ->select('tournament_ranking.*')
            ->where('whitelabel_id', $whitelabel)
            ->where('currency_iso', $currency)
            ->where('campaign_id', $campaign)
            ->where('provider_id', $provider)
            ->where('user_id', $user)
            ->first();
        return $list;
    }

    /**
     * Get tournament list by currency, whitelabel and campaign
     *
     * @param int $whitelabel Whitelabel ID
     * @param string $currency Currency ISO
     * @param int $campaign Campaign ID
     * @return mixed
     */
    public function getTournamentRankingList($whitelabel, $currency, $campaign)
    {
        $list = TournamentRanking::on(config('bonus.connection'))
            ->select(\DB::raw('sum(tournament_ranking.total) AS totals'), 'users.username', 'tournament_ranking.user_id')
            ->join('users', 'users.id', '=', 'tournament_ranking.user_id')
            ->where('tournament_ranking.whitelabel_id', $whitelabel)
            ->where('tournament_ranking.currency_iso', $currency)
            ->where('tournament_ranking.campaign_id', $campaign)
            ->groupBy('tournament_ranking.user_id', 'users.username')
            ->orderBy('totals', 'DESC')
            ->limit(10)
            ->get();
        return $list;
    }

    /**
     * Store
     *
     * @param array $data Tournament data
     * @return mixed
     */
    public function store($data)
    {
        $tournament = \DB::connection(config('sessions.connection'))
            ->table('tournament_ranking')
            ->insert($data);
        return $tournament;
    }

    /**
     * Update
     *
     * @param int $campaign Campaign ID
     * @param int $user User ID
     * @param int $whitelabel Whitelabel ID
     * @param string $currency Currency ISO
     * @param int $provider Provider ID
     * @param array $data Tournament data
     * @return mixed
     */
    public function update($campaign, $user, $whitelabel, $currency, $provider, $data)
    {
        $rollover = TournamentRanking::on(config('bonus.connection'))
            ->where('campaign_id', $campaign)
            ->where('whitelabel_id', $whitelabel)
            ->where('currency_iso', $currency)
            ->where('provider_id', $provider)
            ->where('user_id', $user)
            ->update($data);
        return $rollover;
    }
}