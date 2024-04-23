<?php

namespace App\Notifications\Repositories;

use App\Notifications\Entities\NotificationGroup;

/**
 * Class NotificationsGroupsRepo
 *
 *  This class allows to interact with notifications groups entity
 *
 * @package App\Notifications\Repositories
 */
class NotificationsGroupsRepo
{
    /**
     * Get all notification group
     *
     * @return mixed
     */
    public function all()
    {
        $notification = NotificationGroup::whitelabel()
            ->get();
        return $notification;
    }

    /**
     * @param $group
     * @param $user
     * @return $groupUser
     */
    public function createGroupUsers($group, $user)
    {
        $groupUser = new NotificationGroup();
        $groupUser->users($group, $user);
        return  $groupUser;
    }

    /**
     * Delete notification group
     *
     * @param int $id Notification group ID
     * @return mixed
     */
    public function delete($id)
    {
        $notification = NotificationGroup::where('id', $id)
            ->whitelabel()
            ->first();
        $notification->delete();
        return $notification;
    }

    /**
     * Find notification group
     *
     * @param int $id Notification group ID
     * @return mixed
     */
    public function find($id)
    {
        $notification = NotificationGroup::where('id', $id)
            ->whitelabel()
            ->first();
        return $notification;
    }

    /**
     * Store notification group
     *
     * @param array $data Notification group data
     * @return mixed
     */
    public function store($data)
    {
        $notification = NotificationGroup::create($data);
        return $notification;
    }

    /**
     * Users of notification group
     *
     * @param $currency
     * @param $whitelabel
     * @param $group
     * @return mixed
     */
    public function users($currency, $whitelabel, $group)
    {
        $notification = NotificationGroup::select('profiles.first_name', 'profiles.last_name', 'users.email', 'users.username', 'notifications_groups_user.user_id','notifications_groups_user.notification_group_id')
            ->join('notifications_groups_user', 'notifications_groups_user.notification_group_id', '=', 'notifications_groups.id')
            ->join('users', 'users.id', '=', 'notifications_groups_user.user_id')
            ->join('profiles', 'profiles.user_id', '=', 'users.id')
            ->where('users.whitelabel_id', $whitelabel)
            ->where('notifications_groups.currency_iso', $currency)
            ->where('notifications_groups_user.notification_group_id', $group)
            ->get();
        return $notification;
    }


    /**
     * Update notification group
     *
     * @param int $id Notification group ID
     * @param array $data Notification group data
     * @return mixed
     */
    public function update($id, $data)
    {
        $notification = NotificationGroup::find($id);
        $notification->fill($data);
        $notification->save();
        return $notification;
    }
}
