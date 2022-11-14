<?php

namespace App\Reports\Repositories;

use App\Reports\Entities\ClosureGameTotal;

/**
 * Class ClosuresGamesTotalsRepo
 *
 * This class allows to manage ClosureGameTotal entity
 *
 * @package App\Reports\Repositories
 * @author Damelys Espinoza
 */
class ClosuresGamesTotalsRepo
{
    /**
     * @param $whitelabel
     * @param $startDate
     * @param $endDate
     * @param $currency
     * @param $provider
     * @return mixed
     */
    public function getGamesTotals($whitelabel, $startDate, $endDate, $currency, $provider)
    {
        $totals = ClosureGameTotal::select('game_id', 'game_name as name', 'mobile', \DB::raw('sum(bets) AS bets'), \DB::raw('sum(played) AS played'), \DB::raw('sum(won) AS won'),
                \DB::raw('sum(profit) AS profit'),  \DB::raw('sum(rtp) AS rtp'))
            ->where('start_date', '>=', $startDate)
            ->where('end_date', '<=', $endDate)
            ->where('whitelabel_id', $whitelabel)
            ->where('currency_iso', $currency)
            ->where('provider_id', $provider)
            ->groupBy('game_id', 'game_name', 'mobile')
            ->get();
        return $totals;
    }

    /**
     * @param $whitelabel
     * @param $startDate
     * @param $endDate
     * @param $currency
     * @param $provider
     * @return mixed
     */
    public function getMostPlayedGames($whitelabel, $startDate, $endDate, $currency, $provider)
    {
        $totals = ClosureGameTotal::select('game_name as name', 'mobile', \DB::raw('sum(bets) AS bets'))
            ->whereBetween('date', [$startDate, $endDate])
            ->where('whitelabel_id', $whitelabel)
            ->where('currency_iso', $currency)
            ->where('provider_id', $provider)
            ->groupBy('game_name', 'mobile')
            ->orderBy('bets', 'DESC')
            ->get();
        return $totals;
    }

    /**
     * Store closure
     *
     * @param $data
     * @return mixed
     */
    public function store($data)
    {
        $closure = ClosureGameTotal::create($data);
        return $closure;
    }
}
