<?php

namespace App\BonusSystem\Collections;

use Dotworkers\Bonus\Enums\AllocationCriteria;

/**
 * Class AllocationCriteriaCollection
 *
 * This class allows to format allocation criteria
 *
 * @package App\BonusSystem\Collections
 * @author  Eborio Linárez
 */
class AllocationCriteriaCollection
{
    /**
     * Format all types
     *
     * @param array $allocationCriteria Allocation criteria
     */
    public function formatAll($allocationCriteria)
    {
        foreach ($allocationCriteria as $criteria) {
            switch ($criteria->id) {
                case AllocationCriteria::$welcome_bonus_without_deposit: {
                    $criteria->name = _i('Registration bonus');
                    break;
                }
                case AllocationCriteria::$bonus_code_with_deposit: {
                    $criteria->name = _i('Bonus code with deposit');
                    break;
                }
                case AllocationCriteria::$bonus_code: {
                    $criteria->name = _i('Bonus code');
                    break;
                }
                case AllocationCriteria::$welcome_bonus_with_deposit: {
                    $criteria->name = _i('Welcome bonus with deposit');
                    break;
                }
                case AllocationCriteria::$next_deposit_bonus: {
                    $criteria->name = _i('Next deposit bonus');
                    break;
                }
            }
        }
    }
}
