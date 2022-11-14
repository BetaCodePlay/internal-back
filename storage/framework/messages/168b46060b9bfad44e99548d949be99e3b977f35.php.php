<?php


namespace App\Notifications\Repositories;

use App\Notifications\Entities\NotificationType;

/**
 * Class NotificationsTypesRepo
 *
 * This class allows to interact with notifications types entity
 *
 * @package App\Notifications\Repositories
 */
class NotificationsTypesRepo
{
    /**
     * Get all notification types
     *
     * @return mixed
     */
    public function all()
    {
        $notification = NotificationType::select('id', 'name')
            ->orderBy('name', 'asc')
            ->get();
        return $notification;
    }

    /**
     * Get all notification types
     *
     * @return mixed
     */
    public function find($id)
    {
        $notification = NotificationType::where('id', $id)
            ->first();
        return $notification;
    }
}