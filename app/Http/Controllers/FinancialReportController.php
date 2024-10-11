<?php

namespace App\Http\Controllers;

/**
 * Class FinancialReportController
 *
 * This class allows to manage financial report requests
 *
 * @package App\Http\Controllers
 * @author  Genesis Perez
 */
class FinancialReportController
{
    /**
     * Get the games in view
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        try {
            $data['title'] = _i('Crear');
            return view('back.financial-report.index', $data);
        } catch (\Exception $e) {
            \Log::error(__METHOD__, ['exception' => $e]);
            abort(500);
        }
    }

}
