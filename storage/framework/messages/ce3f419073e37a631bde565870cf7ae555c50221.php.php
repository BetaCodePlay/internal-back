<?php

namespace Dotworkers\Audits;

use Dotworkers\Audits\Repositories\AuditsRepo;

/**
 * Class Audits
 *
 * This class allows to interact with Audits
 *
 * @package Dotworkers\Audits
 * @author  Orlando Bravo
 */
class Audits
{
    /**
     * Store audit
     *
     * @param int $user User ID
     * @param int $type Audit type ID
     * @param int $whitelabel Whitelabel type ID
     * @param array $data Configuration data
     * @return mixed
     */
    public static function store($user, $type, $whitelabel, $data)
    {
        $auditsRepo = new AuditsRepo();
        $auditData = [
            'user_id' =>  $user,
            'audit_type_id' => $type,
            'whitelabel_id' => $whitelabel,
            'data' =>  $data
        ];
        $audit = $auditsRepo->store($auditData);
        return $audit;
    }
}
