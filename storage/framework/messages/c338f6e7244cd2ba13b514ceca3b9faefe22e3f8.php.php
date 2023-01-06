<?php

namespace App\BonusSystem\Repositories;

use App\BonusSystem\Entities\AllocationCriteria;
use App\BonusSystem\Entities\AllocationCriteriaMet;

/**
 * Class AllocationCriteriaRepo
 *
 * This class allows manage AllocationCriteria entity
 *
 * @package App\BonusSystem\Repositories
 * @author Damelys Espinoza
 */
class AllocationCriteriaRepo
{
    /**
     * Get all allocation criteria
     *
     * @return mixed
     */
    public function all()
    {
        $campaign = AllocationCriteria::select('id', 'name', 'show')
            ->orderBy('name', 'asc')
            ->whereNotNull('show')
            ->get();
        return $campaign;
    }

    /**
     * Find allocation criteria
     *
     * @return mixed
     */
    public function find($id)
    {
        $campaign = AllocationCriteria::where('id', $id)
            ->first();
        return $campaign;
    }

    /**
     * Get by Allocation criteria
     *
     * @param array $campaigns Campaign ID
     * @return mixed
     */
    public function getByAllocationCriteria($campaigns, $startDate, $endDate)
    {
        $allocationCriteria = AllocationCriteriaMet::select('campaign_id', \DB::raw('count(campaign_allocation_criteria_met.*) AS criteria_met_quantity'))
            ->whereIn('campaign_id', $campaigns);

        if (!is_null($startDate) && !is_null($endDate)) {
            $allocationCriteria->whereBetween('created_at', [$startDate, $endDate]);
        }

        return $allocationCriteria->groupBy('campaign_id')->get();
    }

}
