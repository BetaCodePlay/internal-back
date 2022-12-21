<?php

namespace App\Core\Repositories;

use App\Core\Entities\Currency;

/**
 * Class CurrenciesRepo
 *
 * This class allows to interact with Currency entity
 *
 * @package App\Core\Repositories
 * @author  Orlando Bravo
 */
class CurrenciesRepo
{
    /**
     * Get all currencies
     *
     * @return mixed
     */
    public function all()
    {
        return Currency::select('iso', 'name', 'credit_limit', 'translations')
            ->orderBy('iso', 'asc')
            ->where('iso', '!=', 'VEF')
            ->get();
    }

    /**
     * Find currency by ISO
     *
     * @param string $iso Currency ISO
     * @return mixed
     */
    public function find($iso)
    {
        return Currency::find($iso);
    }

}
