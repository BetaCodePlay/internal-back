<?php

namespace App\FinancialReport\Repositories;
use App\FinancialReport\Entities\FinancialReport;

class FinancialReportRepo
{
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
