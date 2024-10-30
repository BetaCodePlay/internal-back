<?php

namespace App\Audits\Repositories;

use App\Audits\Entities\Audit;
use App\Audits\Entities\AuditType;
use App\Audits\Enums\AuditTypes;
use App\Role\Enums\OrderTableIPColumns;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

/**
 *
 */
class AuditsRepo
{
    /**
     * @param $id
     * @return mixed
     */
    public function findByType($id)
    : mixed {
        return AuditType::where('id', $id)
            ->first();
    }

    /**
     * @param $whitelabel
     * @param $startDate
     * @param $endDate
     * @param $users
     * @param $type
     * @return mixed
     */
    public function getAudits($whitelabel, $startDate, $endDate, $users, $type)
    : mixed {
        $audits = Audit::select('user_id', 'users.username', 'audit_type_id', 'data')
            ->join('users', 'users.id', '=', 'audits.user_id')
            ->where('audits.whitelabel_id', $whitelabel)
            ->whereBetween('audits.created_at', [$startDate, $endDate]);
        if (! empty($users)) {
            $audits->whereIn('user_id', explode(',', $users));
        }
        if (! empty($type)) {
            $audits->where('audit_type_id', $type);
        }
        return $audits->orderBy('audits.created_at', 'DESC')->get();
    }

    /**
     * @param $whitelabel
     * @param $startDate
     * @param $endDate
     * @return mixed
     */
    public function getLogins($whitelabel, $startDate, $endDate)
    : mixed {
        return Audit::select(DB::raw('count(*) AS logins'), 'user_id', 'users.username')
            ->join('users', 'users.id', '=', 'audits.user_id')
            ->where('audit_type_id', AuditTypes::$login)
            ->where('users.whitelabel_id', $whitelabel)
            ->whereBetween('audits.created_at', [$startDate, $endDate])
            ->groupBy('user_id', 'username')
            ->get();
    }

    /**
     * @param $whitelabel
     * @param $startDate
     * @param $endDate
     * @param $arrayUsers
     * @return mixed
     */
    public function getLoginsTree($whitelabel, $startDate, $endDate, $arrayUsers)
    : mixed {
        return Audit::select(DB::raw('count(*) AS logins'), 'user_id', 'users.username')
            ->join('users', 'users.id', '=', 'audits.user_id')
            ->where('audit_type_id', AuditTypes::$login)
            ->where('users.whitelabel_id', $whitelabel)
            ->whereBetween('audits.created_at', [$startDate, $endDate])
            ->whereIn('users.id', $arrayUsers)
            ->groupBy('user_id', 'username')
            ->get();
    }

    /**
     * @return mixed
     */
    public function getTypes()
    {
        return AuditType::select('audit_types.*')
            ->get();
    }

    /**
     * @param $user
     * @return mixed
     */
    public function getUsersIps($user)
    : mixed {
        return Audit::select(DB::raw('count(id) as quantity'), 'data->ip AS ip')
            ->where('user_id', $user)
            ->groupBy('data->ip')
            ->limit(100)
            ->get();
    }

    /**
     * @param Request $request
     * @return array
     */
    public function getUserIp(Request $request): array {
        $draw        = $request->input('draw', 1);
        $start       = $request->input('start', 0);
        $length      = $request->input('length', 10);
        $searchValue = $request->input('search.value');
        $orderColumn = $request->input('order.0.column');
        $orderDir    = $request->input('order.0.dir');
        $userId      = $request->input('userId');
        $auditQuery  = $this->getIpQuery($userId);

        $auditQuery->where(function ($query) use ($searchValue) {
            $query->where('data->ip', 'like', "%$searchValue%");
        });

        $orderableColumns = OrderTableIPColumns::getOrderTableIPColumns();
        $orderBy = array_key_exists($orderColumn, $orderableColumns)
            ? $orderableColumns[$orderColumn]
            : 'data->ip';

        $audit = $auditQuery->orderBy($orderBy, $orderDir ?: 'asc')
            ->get();

        $resultCount   = $audit->count();
        $slicedResults = $audit->slice($start, $length)->map(function ($item, $index) {
            return [
                $item['ip'] ?? null,
                $item['quantity'] ?? null,
            ];
        })->values()->all();

        return [
            'draw'            => (int)$draw,
            'recordsTotal'    => $resultCount,
            'recordsFiltered' => $resultCount,
            'data'            => $slicedResults,
        ];
    }

    /**
     * @param $userId
     * @return mixed
     */
    function getIpQuery($userId)
    : mixed {
        return Audit::select(['data->ip as ip', DB::raw('count(id) as quantity')])
            ->where('user_id', $userId)
            ->groupBy('data->ip');
    }


    /**
     * @param string $timezone
     * @param string|int $authUserId
     * @return Collection
     */
    public function getRecentAudits(string $timezone, string | int $authUserId): Collection
    {
        $audits = DB::table('audits')
            ->join('audit_types', 'audits.audit_type_id', '=', 'audit_types.id')
            ->latest('audits.created_at')
            ->where('audits.user_id', $authUserId)
            ->take(10)
            ->select([
                'audit_types.name',
                'audits.created_at'
            ])
            ->get();

        $audits->transform(function ($audit) use ($timezone) {
            $audit->formatted_date = Carbon::parse($audit->created_at)->setTimezone($timezone)->format('d M h:ia');
            return $audit;
        });

        return $audits;
    }



    /**
     * @param $user
     * @return mixed
     */
    public function getUsersModified($user)
    : mixed {
        return Audit::where('user_id', $user)
            ->whereIn(
                'audit_type_id',
                [
                    AuditTypes::$user_status,
                    AuditTypes::$user_password,
                    AuditTypes::$user_modification,
                    AuditTypes::$login,
                    AuditTypes::$dotpanel_login,
                    AuditTypes::$manual_transactions,
                    AuditTypes::$points_transactions,
                    AuditTypes::$manual_adjustments,
                    AuditTypes::$bonus_transactions
                ]
            )
            ->orderBy('id', 'DESC')
            ->get();
    }

    /**
     * @param $user
     * @param $type
     * @param $whitelabel
     * @return mixed
     */
    public function lastByType($user, $type, $whitelabel)
    {
        return Audit::select('id', 'data')->where([
            'user_id' => $user,
            'audit_type_id' => $type,
            'whitelabel_id' => $whitelabel,
        ])->orderBy('id', 'DESC')->first();
    }

    /**
     * @param $user
     * @return mixed
     */
    public function lastLogin($user)
    {
        return Audit::where('user_id', $user)
            ->where('audit_type_id', AuditTypes::$login)
            ->orderBy('created_at', 'DESC')
            ->first();
    }

    /**
     * @param $data
     * @return mixed
     */
    public function store($data)
    : mixed {
        return Audit::create($data);
    }
}
