<?php


namespace App\Reports\Repositories;

use Illuminate\Support\Facades\DB;

/**
 * Class ReportAgentRepo
 *
 * @package App\Reports\Repositories
 * @author Estarly Olivar
 */
class ReportAgentRepo
{

    /**
     * Get Ids User Children From Father
     *
     * @param int $ownerId id user Father
     * @param string $currency Currency
     * @param int $whitelabel Whitelabel ID
     * @return array
     */
    public function getIdsChildrenFromFather(int $ownerId, string $currency, int $whitelabel)
    {
        $getIdsChildren = DB::select('SELECT * FROM site.get_ids_children_from_father(?,?,?)', [$ownerId, $currency, $whitelabel]);

        return array_column($getIdsChildren,'id');
    }

}
