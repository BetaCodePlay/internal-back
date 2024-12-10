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
     * Format all Report
     *
     * @param array $report Report data
     */
    public function formatAllReport($reports)
    {
        $reportsRepo = new FinancialReportRepo();
        foreach ($reports as $report) {
            $report->makers = $report->maker;
            $report->provider = $report->name;
            $report->currency = $report->currency_iso;
            $totalPlayed = $reportsRepo->updateTotalPlayed($report->provider_id, $report->maker, $report->currency_iso);
            if ($totalPlayed == true) {
                $report->totalPlayed = $report->total_played;
            } else {
                $report->totalPlayed = 0;
            }
            $report->amount_real = $report->amount;
            $report->amount_load = $report->load_amount;
            $report->date_load = $report->load_date;
            $report->limits = $report->limit;
            $report->actions = sprintf(
                '<a href="%s" class="btn u-btn-3d btn-sm u-btn-bluegray mr-2"><i class="hs-admin-pencil"></i> %s</a>',
                route('financial-report.edit', [$report->id]),
                _i('Edit')
            );
            $report->actions .= sprintf(
                '<button type="button" class="btn u-btn-3d btn-sm u-btn-primary mr-2 delete" data-route="%s"><i class="hs-admin-trash"></i> %s</button>',
                route('financial-report.delete', [$report->id]),
                _i('Delete')
            );
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



    /**
     * Format all report provider
     *
     * @param array $reports Games data
     */
    public function formatAllReportProvider($reports, $chips, $percentage, $startDate, $endDate, $maker)
    {
        foreach ($reports as $report) {
            $report->makers = $maker;
            $report->providers = $report->name;
            $report->benefits= $report->benefit;
            $report->chip = $chips;
            $report->consumeds= $report->consumed;
            $report->balances = $report->balance;
            $report->percentages = $percentage;
            $report->dates = $startDate;
            $report->actions = sprintf(
                '<a href="%s" class="btn u-btn-3d btn-sm u-btn-bluegray mr-2"><i class="hs-admin-pencil"></i> %s</a>',
                route('financial-report.edit'/*, [$report->id]*/),
                _i('Edit')
            );
            $report->actions .= sprintf(
                '<button type="button" class="btn u-btn-3d btn-sm u-btn-primary mr-2 delete" data-route="%s"><i class="hs-admin-trash"></i> %s</button>',
                route('financial-report.delete'/*, [$report->id]*/),
                _i('Delete')
            );
        }
    }

}
