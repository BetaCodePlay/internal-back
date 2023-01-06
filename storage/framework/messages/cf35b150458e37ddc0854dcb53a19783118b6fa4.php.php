<?php

namespace App\Whitelabels\Repositories;

use App\Whitelabels\Entities\WhitelabelsStatus;

/**
 * Class WhitelabelsStatusRepo
 *
 * This class allows to interact with Whitelabels status entity
 *
 * @package App\Core\Repositories
 * @author  Genesis Perez
 */
class WhitelabelsStatusRepo
{
    /**
     * Get all
     *
     * @return mixed
     */
    public function all()
    {
        $whitelabel = WhitelabelsStatus::orderBy('name', 'ASC')
            ->get();
        return $whitelabel;
    }
}
