<?php


namespace App\Notifications\Repositories;

use App\Notifications\Entities\NotificationGroupUser;

/**
 * Class NotificationsGroupsUsersRepo
 *
 * This class allows to interact with notification group user entity
 *
 * @package App\Notifications\Repositories
 */
class NotificationsGroupsUsersRepo
{
    /**
     * Delete user for group notification
     *
     * @param int $group ID Group Notification
     * @param int $user ID User
     * @return mixed
     */
    public function delete($group, $user)
    {
        $userGroup = \DB::table('notifications_groups_user')
            ->where('notification_group_id', $group)
            ->where('user_id', $user)
            ->delete();
        return $userGroup;
    }

    /**
     * Get user by group
     *
     * @param $id
     * @return mixed
     */
    public function getUsersByGroup($id)
    {
        $userGroup = NotificationGroupUser::where('notification_group_id', $id)
            ->get();
        return $userGroup;
    }
    /**
     * Store
     *
     * @param $user
     * @param $group
     */
    public function store($user, $group)
    {
        $userGroup = new NotificationGroupUser();
        $userGroup->notification_group_id = $group;
        $userGroup->user_id = $user;
        $userGroup->save();
    }
}
