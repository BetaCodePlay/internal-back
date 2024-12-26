<?php

namespace App\FinancialReport\Repositories;
use App\FinancialReport\Entities\FinancialReport;
use Illuminate\Support\Facades\DB;

class FinancialReportRepo
{
    /**
     * Delete
     *
     * @param int $id ID
     * @return mixed
     */
    public function delete($id)
    {
        $financial =FinancialReport::where('id', $id)
            ->first();
        $financial->delete();
        return $financial;
    }

    /**
     * Find by id
     *
     * @return static
     */
    public function findById($id)
    {
        return FinancialReport::where('id', $id)
            ->first();
    }

    /**
     * Get all
     *
     * @param array $provider Financial provider
     * @return static
     */
    public function all()
    {
        $financial = FinancialReport::select('providers.name', 'financial_report.id', 'financial_report.currency_iso', 'financial_report.amount', 'financial_report.provider_id',
            'financial_report.load_amount', 'financial_report.maker', 'financial_report.total_played', 'financial_report.load_date', 'financial_report.limit')
            ->join('providers', 'financial_report.provider_id', '=', 'providers.id')
            ->get();
        return $financial;
    }

    /**
     * Store financial
     *
     * @param array $data Financial data
     * @return static
     */
    public function store($data)
    {
        $financial = FinancialReport::create($data);
        return $financial;
    }

    /**
     * @param $provider
     * @param $maker
     * @param $currency
     * @return mixed
     */
    public function updateTotalPlayed($provider, $maker, $currency)
    {
        return DB::select('Select * from site.update_totalplayed(?,?,?)',[$provider, $maker, $currency]);
    }


    /**
     * @param $provider
     * @param $maker
     * @param $currency
     * @return mixed
     */
    public function reportBenefit($provider, $maker, $currency, $startDate, $endDate, $timezone, $percentage, $chips)
    {
        return DB::select('Select * from site.report_benefit(?,?,?,?,?,?,?,?)',[$provider, $maker, $currency, $startDate, $endDate, $timezone, $percentage, $chips]);
    }

    /**
     * Update
     *
     * @param int $id Slider ID
     * @param array $data Slider data
     * @return mixed
     */
    public function update($id, $data)
    {
        $financial = FinancialReport::find($id);
        $financial->fill($data);
        $financial->save();
        return $financial;
    }
}
