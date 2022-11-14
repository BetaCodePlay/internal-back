<?php

namespace App\Core\Repositories;

use App\Core\Entities\Country;

/**
 * Class CountriesRepo
 *
 * This class allows to interact with Country entity
 *
 * @package App\Core\Repositories
 * @author  Eborio Linarez
 */
class CountriesRepo
{
    /**
     * Get all countries
     *
     * @return mixed
     */
    public function all()
    {
        return Country::on('replica')
            ->orderBy('name', 'ASC')
            ->get();
    }

    /**
     * Find country
     *
     * @param string $iso Country ISO
     * @return mixed
     */
    public function find($iso)
    {
        $country = Country::find($iso);
        return $country;
    }
}
