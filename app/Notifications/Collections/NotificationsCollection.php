<?php

namespace App\Notifications\Collections;

use App\Core\Enums\Languages;
use App\Notifications\Enums\NotificationTypes;
use Dotworkers\Security\Enums\Permissions;
use Illuminate\Support\Facades\Gate;

/**
 * Class NotificationsCollection
 *
 * This class allows to format notification data
 *
 * @package App\Notifications\Collections
 */
class NotificationsCollection
{
    /**
     * Format all notifications
     *
     * @param array $notifications notifications data
     */
    public function formatAll($notifications)
    {
        $timezone = session('timezone');
        foreach ($notifications as $notification) {
            if (!is_null($notification->image)){
                $url = s3_asset("notifications/{$notification->image}");
                $file = $notification->image;
                $notification->image = "<img src='$url' class='img-responsive' width='200'>";
            } else {
                $notification->image = _i('Without image');
                $file = null;
            }
            $notification->title = !is_null($notification->title) ? $notification->title : _i('Without title');
            $notification->language =  $notification->language == '*' ? _i('Everybody') : Languages::getName($notification->language);
            $notification->currency_iso =  $notification->currency_iso == '*' ? _i('Everybody') : $notification->currency_iso;
            $notification->date = $notification->created_at->setTimezone($timezone)->format('d-m-Y H:i:s');
            $statusClass = $notification->status ? 'teal' : 'lightred';
            $statusText = $notification->status ? _i('Active') : _i('Inactive');
            $notification->status = sprintf(
                '<span class="u-label g-bg-%s g-rounded-20 g-px-15 g-mr-10 g-mb-15">%s</span>',
                $statusClass,
                $statusText
            );

            if (Gate::allows('access', Permissions::$manage_sliders)) {
                $notification->actions = sprintf(
                    '<a href="%s" class="btn u-btn-3d btn-sm u-btn-bluegray mr-2"><i class="hs-admin-pencil"></i> %s</a>',
                    route('notifications.edit', [$notification->id]),
                    _i('Edit')
                );
                if ($notification->notification_type_id != NotificationTypes::$all_users) {
                    $notification->actions .= sprintf(
                        '<button class="btn u-btn-3d btn-sm u-btn-bluegray mr-2" data-toggle="modal" data-target="#list-users" data-route="%s"><i class="hs-admin-list"></i> %s</button>',
                        route('notifications.list-user',[$notification->id, $notification->notification_type_id]),
                        _i('List users')
                    );
                } else {
                    $notification->actions .= sprintf(
                        '<span class="u-label g-bg-teal g-rounded-20 g-px-15 g-mr-10 g-mb-15">%s</span>',
                        _i('All users')
                    );
                }

                $notification->actions .= sprintf(
                    '<button type="button" class="btn u-btn-3d btn-sm u-btn-primary mr-2 delete" data-route="%s"><i class="hs-admin-trash"></i> %s</button>',
                    route('notifications.delete', [$notification->id, $file]),
                    _i('Delete')
                );
            }
        }
    }

    /**
     * Format all notifications groups
     *
     * @param array $notifications notifications groups data
     */
    public function formatAllGroups($notifications)
    {
        foreach ($notifications as $notification) {
            $notification->name = !is_null($notification->name) ? $notification->name : _i('Without name');
            $notification->description = !is_null($notification->description) ? $notification->description : _i('Without description');
            $notification->currency_iso =  $notification->currency_iso == '*' ? _i('Everybody') : $notification->currency_iso;
            $notification->operator = $notification->operator_id;

            if (Gate::allows('access', Permissions::$manage_sliders)) {
                $notification->actions = sprintf(
                    '<a href="%s" class="btn u-btn-3d btn-sm u-btn-bluegray mr-2"><i class="hs-admin-pencil"></i> %s</a>',
                    route('notifications.groups.edit', [$notification->id]),
                    _i('Edit')
                );
                $notification->actions .= sprintf(
                    '<button type="button" class="btn u-btn-3d btn-sm u-btn-primary mr-2 delete" data-route="%s"><i class="hs-admin-trash"></i> %s</button>',
                    route('notifications.groups.delete', [$notification->id]),
                    _i('Delete')
                );

                $notification->actions .= sprintf(
                    '<a href="%s" class="btn u-btn-3d btn-sm u-btn-primary mr-2"><i class="hs-admin-pencil"></i> %s</a>',
                    route('notifications.groups.assign', [$notification->id]),
                    _i('Assign')
                );
            }
        }
    }

    /**
     * Format details
     *
     * @param $notification
     */
    public function formatDetails($notification)
    {
        if (!is_null($notification->image)){
            $url = s3_asset("notifications/{$notification->image}");
            $notification->file = $notification->image;
            $notification->image = "<img src='$url' class='img-responsive' width='200'>";
        } else {
            $notification->file = null;
            $notification->image = null;
        }
    }

    /**
     * Format details users
     *
     * @param $notification
     */
    public function formatDetailsUsers($users, $id, $type)
    {
        $data = [];
        foreach ($users as $user) {
            $itemObject = new \stdClass();

            $route = $type == NotificationTypes::$segment ? route('users.details', [$user->id]) : route('users.details', [$user->user]);
            $userId = $type == NotificationTypes::$segment ? $user->id : $user->user;
            $itemObject->user = sprintf(
                '<a href="%s" class="btn u-btn-3d u-btn-primary btn-sm" target="_blank">%s</a>',
                $route,
                $userId
            );
            $itemObject->username = $user->username;


            $routeRemove = $type == NotificationTypes::$segment ?  route('notifications.remove-user', [$id, $user->id]) :  route('notifications.remove-user', [$user->id, $user->user]);
            $itemObject->user = sprintf(
                '<a href="%s" class="btn u-btn-3d u-btn-primary btn-sm" target="_blank">%s</a>',
                $route,
                $userId
            );
            $itemObject->actions = $type == NotificationTypes::$segment ? _i('No action')  :  sprintf(
                '<button type="button" class="btn u-btn-3d btn-sm u-btn-bluegray mr-2 remove" data-route="%s"><i class="hs-admin-trash"></i> %s</a>',
                $routeRemove,
                _i('Delete')
            );
            $data[] = $itemObject;
        }
        return $data;
    }

    /**
     * Format details groups
     *
     * @param $notification
     */
    public function formatDetailsGroups($notification)
    {
        $url = s3_asset("notifications/groups/{$notification->image}");
        $notification->file = $notification->image;
        $notification->image = "<img src='$url' class='img-responsive' width='200'>";
    }

    /**
     * Format users of notifications groups
     *
     * @param array $notifications notifications groups data
     */
    public function formatUsers($notifications)
    {
        foreach ($notifications as $notification) {
            $notification->first_name = !is_null($notification->first_name) ? $notification->first_name : _i('Without name');
            $notification->last_name = !is_null($notification->last_name) ? $notification->last_name : _i('Without last name');
            $notification->email = !is_null($notification->email) ? $notification->email : _i('Without email');

            if (Gate::allows('access', Permissions::$manage_sliders)) {
                $notification->actions = sprintf(
                    '<button type="button" class="btn u-btn-3d btn-sm u-btn-primary mr-2 delete" data-route="%s"><i class="hs-admin-trash"></i> %s</button>',
                    route('notifications.groups.delete.user', [$notification->notification_group_id, $notification->user_id]),
                    _i('Delete')
                );
            }
        }
    }
}
