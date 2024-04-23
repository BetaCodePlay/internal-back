<?php

namespace App\Notifications\Repositories;

use App\Notifications\Entities\Notification;

/**
 * Class NotificationsRepo
 *
 * This class allows to interact with notifications entity
 *
 * @package App\Notifications\Repositories
 */
class NotificationsRepo
{
    /**
     * Get all notification
     *
     * @return mixed
     */
    public function all()
    {
        $notification = Notification::whitelabel()
            ->get();
        return $notification;
    }

    /**
     * Delete notification
     *
     * @param int $id Notification ID
     * @return mixed
     */
    public function delete($id)
    {
        $notification = Notification::where('id', $id)
            ->whitelabel()
            ->first();
        $notification->delete();
        return $notification;
    }

    /**
     * Find notification
     *
     * @param int $id Notification ID
     * @return mixed
     */
    public function find($id)
    {
        $notification = Notification::where('id', $id)
            ->whitelabel()
            ->first();
        return $notification;
    }

    /**
     * Notifications users
     *
     * @param int $id Notification ID
     * @return mixed
     */
    public function listUsers($id, $whitelabel)
    {
        $users =  Notification::select('notifications.id', 'users.id as user','users.username', 'users.email', 'profiles.first_name', 'profiles.last_name')
            ->join('notification_user', 'notifications.id', '=', 'notification_user.notification_id')
            ->join('users', 'notification_user.user_id', '=', 'users.id')
            ->join('profiles', 'users.id', '=', 'profiles.user_id')
            ->where('notifications.whitelabel_id', $whitelabel)
            ->where('notifications.id', $id)
            ->get();
        return $users;
    }

    /**
     * Notifications Segment
     *
     * @param int $id Notification ID
     * @return mixed
     */
    public function notificationSegment($id, $whitelabel)
    {
        $notificationsId =  Notification::select('notifications.id', 'notification_segment_user.segment_id', 'segments.data')
            ->join('notification_segment_user', 'notifications.id', '=', 'notification_segment_user.notification_id')
            ->join('segments', 'notification_segment_user.segment_id', '=', 'segments.id')
            ->where('notifications.whitelabel_id', $whitelabel)
            ->where('notifications.id', $id)
            ->first();
        return $notificationsId;
    }

    /**
     * Notifications users
     *
     * @param int $id Notification ID
     * @param int $user Users ID
     * @return mixed
     */
    public function removeUser($id, $user)
    {
        $user = \DB::table('notification_user')->where('notification_id', $id)->where('user_id', $user)->delete();
        return $user;
    }

    /**
     * Verification users
     *
     * @param int $id Notification ID
     * @param int $user Users ID
     * @return mixed
     */
    public function verificationUser($id, $user)
    {
        $user = \DB::table('notification_user')->where('notification_id', $id)->where('user_id', $user)->first();
        return $user;
    }

    /**
     * Verification users
     *
     * @param int $id Notification ID
     * @param int $segment segment ID
     * @return mixed
     */
    public function verificationSegments($id, $segment)
    {
        $user = \DB::table('notification_segment_user')->where('notification_id', $id)->where('segment_id', $segment)->first();
        return $user;
    }

    /**
     * Store notification
     * @param array $data Notification data
     * @param int $user User ID
     * @return mixed
     */
    public function store($data, $users)
    {
        $notification = Notification::create($data);

        if (!is_null($users) && !in_array(null, $users)){
            foreach ($users as $user) {
                $notification->users()->attach($user);
            }
        }
        return $notification;
    }


    /**
     * Update notification
     *
     * @param int $id Notification ID
     * @param array $data Notification data
     * @return mixed
     */
    public function update($id, $data)
    {
        $notification = Notification::find($id);
        $notification->fill($data);
        $notification->save();
        return $notification;
    }


    /**
     * Store notification
     * @param array $data Notification data
     * @param int $user User ID
     * @return mixed
     */
    public function users($id, $user)
    {
        $notification = Notification::find($id);
        $notification->users()->attach($user);
        return $notification;
    }

    /**
     * Store notification
     * @param array $data Notification data
     * @param int $user User ID
     * @return mixed
     */
    public function segment($id, $segment)
    {
        $notification = Notification::find($id);
        $notification->segment()->attach($segment);
        return $notification;
    }
}
