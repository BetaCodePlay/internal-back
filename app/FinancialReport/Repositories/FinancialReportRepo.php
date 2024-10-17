<?php

namespace App\FinancialReport\Repositories;
use App\FinancialReport\Entities\FinancialReport;

class FinancialReportRepo
{

    /**
     * Get all
     *
     * @param array $provider Financial provider
     * @return static
     */
    public function all($provider)
    {
        $financial = FinancialReport::where('provider_id', $provider)
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
}
