<?php

namespace App\Audits\Repositories;

use App\Audits\Entities\Audit;
use App\Audits\Entities\AuditType;
use App\Audits\Enums\AuditTypes;

/**
 * Class AuditsRepo
 *
 * This class allows to interact with Audit entity
 *
 * @package App\Audits\Repositories
 * @author  Gabriel Santiago
 */
class AuditsRepo
{
    /**
     * Find Audits by Type
     *
     * @return mixed
     */
    public function findByType($id)
    {
        $audit = AuditType::where('id', $id)
            ->first();
        return $audit;
    }

    /**
     * Get audits
     *
     * @param int $whitelabel Whitelabel ID
     * @param string $startDate Start date
     * @param string $endDate End date
     * @param int $user User ID
     * @param int $type AuditType ID
     * @return mixed
     */
    public function getAudits($whitelabel, $startDate, $endDate, $users, $type)
    {
        $audits = Audit::select('user_id', 'users.username', 'audit_type_id', 'data')
            ->join('users', 'users.id', '=', 'audits.user_id')
            ->where('audits.whitelabel_id', $whitelabel)
            ->whereBetween('audits.created_at', [$startDate, $endDate]);
        if (!empty($users)) {
            $audits->whereIn('user_id', explode(',', $users));
        }
        if (!empty($type)) {
            $audits->where('audit_type_id', $type);
        }
        $data = $audits->orderBy('audits.created_at', 'DESC')->get();
        return $data;
    }

    /**
     * Get logins
     *
     * @param int $whitelabel Whitelabel ID
     * @param string $startDate Start date
     * @param string $endDate End date
     * @return mixed
     */
    public function getLogins($whitelabel, $startDate, $endDate)
    {
        $audit = Audit::select(\DB::raw('count(*) AS logins'), 'user_id', 'users.username')
            ->join('users', 'users.id', '=', 'audits.user_id')
            ->where('audit_type_id', AuditTypes::$login)
            ->where('users.whitelabel_id', $whitelabel)
            ->whereBetween('audits.created_at', [$startDate, $endDate])
            ->groupBy('user_id', 'username')
            ->get();
        return $audit;
    }

    public function getLoginsTree($whitelabel, $startDate, $endDate, $arrayUsers)
    {
        $audit = Audit::select(\DB::raw('count(*) AS logins'), 'user_id', 'users.username')
            ->join('users', 'users.id', '=', 'audits.user_id')
            ->where('audit_type_id', AuditTypes::$login)
            ->where('users.whitelabel_id', $whitelabel)
            ->whereBetween('audits.created_at', [$startDate, $endDate])
            ->whereIn('users.id', $arrayUsers)
            ->groupBy('user_id', 'username')
            ->get();
        return $audit;
    }

    /**
     * Get Audits Types
     *
     * @return mixed
     */
    public function getTypes()
    {
        $audits = AuditType::select('audit_types.*')
            ->get();
        return $audits;
    }

    /**
     * Get users ips
     *
     * @param integer $user
     * @return mixed
     */
    public function getUsersIps($user)
    {
        $users = Audit::select(\DB::raw('count(id) as quantity'), 'data->ip AS ip')
            ->where('user_id', $user)
            ->groupBy('data->ip')
            ->limit(100)
            ->get();

        return $users;
    }

    /**
     * Get users modified
     *
     * @param integer $user
     * @return mixed
     */
    public function getUsersModified($user)
    {
        $users = Audit::where('user_id', $user)
            ->whereIn('audit_type_id', [AuditTypes::$user_status, AuditTypes::$user_password, AuditTypes::$user_modification, AuditTypes::$manual_transactions, AuditTypes::$points_transactions, AuditTypes::$manual_adjustments, AuditTypes::$bonus_transactions])
            ->orderBy('id', 'DESC')
            ->get();

        return $users;
    }

    /**
     * Filter First Audits by Type
     * @param int $whitelabel Whitelabel Id
     * @param int $user User Id
     * @param int $type Audit Type Id
     * @return mixed
     */
    public function lastByType($user, $type, $whitelabel)
    {
        $audit = Audit::select('id', 'data')->where([
            'user_id' => $user,
            'audit_type_id' => $type,
            'whitelabel_id' => $whitelabel,
        ])->orderBy('id', 'DESC')->first();

        return $audit;
    }

    /**
     * Get last user login
     *
     * @param integer $user
     * @return mixed
     */
    public function lastLogin($user)
    {
        $audit = Audit::where('user_id', $user)
            ->where('audit_type_id', AuditTypes::$login)
            ->orderBy('created_at', 'DESC')
            ->first();
        return $audit;
    }

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
