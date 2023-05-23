<?php


namespace App\Users\Repositories;


use App\Users\Entities\AutoLockUser;

class AutoLockUsersRepo
{
    /**
     * Delete auto lock user
     *
     * @param int $id Auto lock user ID
     * @return mixed
     */
    public function deleteAutoLockUser($id)
    {
        $users = AutoLockUser::find($id);
        $users->delete();
        return $users;
    }

    /**
     * Unblock auto lock user
     *
     * @param int $user User id
     * @return mixed
     */
    public function unlockUser($user)
    {
        return AutoLockUser::whereNull('deleted_at')
            ->where('active', false)
            ->where('user_id', $user)
            ->first();
    }

    /**
     * Autolocked users
     *
     * @param int $whitelabel Whitelabel ID
     * @param string $startDate Start date to filter
     * @param string $endDate End date to filter
     * @param int $month Month param
     * @return mixed
     */
    public function autoLockedUsersTotals($whitelabel, $startDate, $endDate, $month)
    {
        $users = AutoLockUser::select('user_id', 'username', 'end_date', 'start_date', 'auto_lock_users.created_at', 'auto_lock_users.currency_iso')
            ->join('users', 'auto_lock_users.user_id', '=', 'users.id')
            ->where('active', false)
            ->where('auto_lock_users.whitelabel_id', $whitelabel)
            ->whereBetween('auto_lock_users.created_at', [$startDate, $endDate])
            ->where('users.status', false)
            ->whereNull('deleted_at');
        if (!is_null($month)) {
            if ($month == 0) {
                $users->whereNull('end_date');
            } else {
                $users->where(\DB::raw("(end_date::date - start_date::date)"), '<=', $month);
            }
        }
        $data = $users->groupBy('user_id', 'username', 'auto_lock_users.created_at', 'end_date', 'start_date', 'auto_lock_users.currency_iso')->get();
        return $data;
    }

    /**
     * Count auto lock by user
     *
     * @param int $user User ID
     */
    public function countAutoLock($user)
    {
        return $user = \DB::table('auto_lock_users')
            ->where('auto_lock_users.user_id', $user)
            ->count();
    }
}
