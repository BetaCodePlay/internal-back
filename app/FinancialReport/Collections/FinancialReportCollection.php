<?php

namespace App\FinancialReport\Collections;
use App\FinancialReport\Repositories\FinancialReportRepo;

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
        $reportsRepo = new FinancialReportRepo();
        foreach ($reports as $report) {
            $report->makers = $report->maker;
            $report->provider = $report->name;
            $report->currency = $report->currency_iso;
            $report->amount_real = $report->amount;
            $report->amount_load = $report->load_amount;
            $report->date_load = $report->load_date;
            $report->limits = $report->limit;
            $report->actions = sprintf(
                '<a href="%s" class="btn u-btn-3d btn-sm u-btn-bluegray mr-2"><i class="hs-admin-pencil"></i> %s</a>',
                route('financial-report.edit', [$report->id]),
                _i('Edit')
            );
            $totalPlayed= $reportsRepo->updateTotalPlayed($report->name, $report->maker, $report->currency_iso);
            \Log::info(__METHOD__, ['$totalPlayed' => $totalPlayed]);
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
