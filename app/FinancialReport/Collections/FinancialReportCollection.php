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
    public function formatAllReportProvider($provider, $maker, $currency, $startDate, $endDate, $timezone, $percentage, $chips)
    {
        $reportsRepo = new FinancialReportRepo();
        $reports = $reportsRepo->reportBenefit($provider, $maker, $currency, $startDate, $endDate, $timezone, $percentage, $chips);
        \Log::info(__METHOD__, ['$reports' => $reports]);
        foreach ($reports as $report) {
            $report->makers = $maker;
            if(is_null($report->makers)){
                $report->makers = _i('Sin Maker');
            }
            $report->providers = $report->name;
            if(is_null($report->providers)){
                $report->providers = _i('Sin Proveedor');
            }
            $report->benefits= $report->benefit;
            if(is_null($report->benefits)){
                $report->benefits = 0;
            }
            $report->chip = $chips;
            if(is_null($report->chip)){
                $report->chip = 0;
            }
            $report->consumeds= $report->consumed;
            if(is_null($report->consumeds)){
                $report->consumeds = 0;
            }
            $report->balances = $report->balance;
            if(is_null($report->balances)){
                $report->balances = 0;
            }
            $report->percentages = $percentage;
            if(is_null($report->percentages)){
                $report->percentages = 0;
            }
            $report->dates = $startDate;
            if(is_null($report->dates)){
                $report->dates = 0;
            }
            $report->actions = _i('Edit');
        }
    }

}
