<?php

namespace App\FinancialReport\Collections;

/**
 * Class FinancialReportCollection
 *
 * This class allows to format financial report data
 *
 * @package App\FinancalReport\Collections
 * @author Genesis Perez
 */
class FinancialReportCollection
{
    /**
     * Format all WhitelabelsGames
     *
     * @param array $games Games data
     */
    public function formatAllReport($reports)
    {
        \Log::info(__METHOD__, ['report' => $reports]);
        foreach ($reports as $report) {
            $report->makers = $report->maker;
            $report->provider = $report->name;
            $report->currency = $report->currency_iso;
            $report->amount_real = $report->amount;
            $report->amount_load = $report->load_amount;
            $report->date_load = $report->load_date;
            $report->limits = $report->limit;
            /*$game->actions .= sprintf(
                '<button type="button" class="btn u-btn-3d btn-sm u-btn-primary mr-2 delete" data-route="%s"><i class="hs-admin-trash"></i> %s</button>',
                route('whitelabels-games.delete', [$game->game_id, $game->whitelabel_game_category_id]),
                _i('Delete')
            );*/
        }
    }

    /**
     * Format all financial maker
     *
     * @param array $maker Games data
     */
    public function formatAll($maker)
    {
        foreach ($maker as $makers) {
            $makers->description = $makers->maker;
        }
    }

}
