<?php

namespace Dotworkers\Audits\Repositories;

use Dotworkers\Audits\Entities\Audit;

/**
 * Class AuditsRepo
 *
 * This class allows to interact with provider notification table
 *
 * @package Dotworkers\Audits\Repositories
 * @author  Orlando Bravo
 */
class AuditsRepo
{
    /**
     * Store audit
     *
     * @param array $data
     * @return mixed
     */
    public function store($data)
    {
        $audit = Audit::create($data);
        return $audit;
    }
}

