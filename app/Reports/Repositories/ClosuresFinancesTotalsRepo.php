<?php

namespace App\Reports\Repositories;

use Illuminate\Support\Facades\DB;

/**
 * Class ClosuresGamesTotalsRepo
 *
 * This class allows to manage ClosuresFinancesTotals entity
 *
 * @package App\Reports\Repositories
 * @author Orlando Bravo
 */
class ClosuresFinancesTotalsRepo
{
    /**
     * @param $startDate
     * @param $endDate
     * @return mixed
     */
    public function updateClosureHourTickets( $startDate, $endDate)
    {
      return DB::select('Select * from site.cmd_update_closure_hour(?,?)',[$startDate, $endDate]);
    }

    /**
     * @param $startDate
     * @param $endDate
     * @return mixed
     */
    public function updateClosureHourLvSlots( $startDate, $endDate)
    {
       return DB::select('Select * from site.cmd_update_closure_hour_lv_slots(?,?)',[$startDate, $endDate]);
    }
}
