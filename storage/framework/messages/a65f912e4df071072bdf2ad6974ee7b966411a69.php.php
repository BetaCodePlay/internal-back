<?php

namespace App\Http\Controllers;

use App\CRM\Repositories\SegmentsRepo;
use App\Notifications\Import\UsersNotificationImport;
use App\Users\Repositories\UsersRepo;
use App\Notifications\Collections\NotificationsCollection;
use App\Notifications\Enums\NotificationTypes;
use App\Notifications\Repositories\NotificationsGroupsRepo;
use App\Notifications\Repositories\NotificationsGroupsUsersRepo;
use App\Notifications\Repositories\NotificationsRepo;
use App\Notifications\Repositories\NotificationsTypesRepo;
use Dotworkers\Configurations\Configurations;
use Dotworkers\Configurations\Utils;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * Class NotificationsController
 *
 * This class allows to manage notifications requests
 *
 * @package App\Http\Controllers
 */
class NotificationsController extends Controller
{
    /**
     * NotificationsRepo
     *
     * @var NotificationsRepo
     */
    private $notificationsRepo;

    /**
     * NotificationsCollection
     *
     * @var NotificationsCollection
     */
    private $notificationsCollection;

    /**
     * File path
     *
     * @var string
     */
    private $filePath;

    /**
     * NotificationsGroupsRepo
     *
     * @var
     */
    private $notificationsGroupsRepo;

    /**
     * NotificationsTypesRepo
     *
     * @var
     */
    private $notificationsTypesRepo;

    /**
     * NotificationsGroupsUsersRepo
     * @var
     */
    private $notificationsGroupsUsersRepo;

    /**
     *  SegmentsRepo
     *
     * @var SegmentsRepo
     */
    private $segmentsRepo;

    /**
     * UsersRepo
     *
     * @var UsersRepo
     */
    private $usersRepo;

    /**
     * NotificationsController constructor.
     *
     * @param NotificationsRepo $notificationsRepo
     * @param NotificationsCollection $notificationsCollection
     * @param NotificationsGroupsRepo $notificationsGroupsRepo
     * @param NotificationsTypesRepo $notificationsTypesRepo
     * @param NotificationsGroupsUsersRepo $notificationsGroupsUsersRepo
     * @param SegmentsRepo $segmentsRepo
     * @param UsersRepo $usersRepo
     */
    public function __construct(NotificationsRepo $notificationsRepo, NotificationsCollection $notificationsCollection,  NotificationsGroupsRepo $notificationsGroupsRepo, NotificationsTypesRepo $notificationsTypesRepo, NotificationsGroupsUsersRepo  $notificationsGroupsUsersRepo, SegmentsRepo $segmentsRepo,  UsersRepo $usersRepo)
    {
        $this->notificationsRepo = $notificationsRepo;
        $this->notificationsCollection = $notificationsCollection;
        $s3Directory = Configurations::getS3Directory();
        $this->filePath = "$s3Directory/notifications/";
        $this->notificationsGroupsRepo = $notificationsGroupsRepo;
        $this->notificationsTypesRepo = $notificationsTypesRepo;
        $this->notificationsGroupsUsersRepo = $notificationsGroupsUsersRepo;
        $this->segmentsRepo = $segmentsRepo;
        $this->usersRepo = $usersRepo;
    }

    /**
     * Get all notifications
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function all()
    {
        try {
            $notifications = $this->notificationsRepo->all();
            $this->notificationsCollection->formatAll($notifications);
            $data = [
                'notifications' => $notifications
            ];
            return Utils::successResponse($data);
        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex]);
            return Utils::failedResponse();
        }
    }

    /**
     * Get all notifications groups
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function allGroups()
    {
        try {
            $groups = $this->notificationsGroupsRepo->all();
            $this->notificationsCollection->formatAllGroups($groups);
            $data = [
                'groups' => $groups
            ];
            return Utils::successResponse($data);
        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex]);
            return Utils::failedResponse();
        }
    }

    /**
     * Show view assign group
     *
     * @param $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function assignGroup($id)
    {
        try {
            $data['group'] = $id;
            $data['title'] = _i('Assign group');
            return view('back.notifications.groups.assign', $data);
        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex, 'slider' => $id]);
            abort(500);
        }
    }

    /**
     * Assign user to group
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function assignGroupUser(Request $request)
    {
        try {
            $user = $request->user;
            $group = $request->group;
            $this->notificationsGroupsUsersRepo->store($user, $group);
            $data = [
                'title' => _i('User assign'),
                'message' => _i('The user was successfully assign'),
                'close' => _i('Close')
            ];
            return Utils::successResponse($data);
        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex]);
            abort(500);
        }
    }

    /**
     * Show notification view
     *
     * @param int $id Notification ID
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($id)
    {
        $notification = $this->notificationsRepo->find($id);
        if (!is_null($notification)) {
            try {
                $types = $this->notificationsTypesRepo->find($notification->notification_type_id);
                $this->notificationsCollection->formatDetails($notification);
                $segments = $this->segmentsRepo->allByWhitelabel();
                $groups = $this->notificationsGroupsRepo->all();
                $data['groups'] = $groups;
                $data['segments'] = $segments;
                $data['notification'] = $notification;
                $data['types'] = $types;
                $data['title'] = _i('Update notification');
                return view('back.notifications.edit', $data);

            } catch (\Exception $ex) {
                \Log::error(__METHOD__, ['exception' => $ex, 'slider' => $id]);
                abort(500);
            }
        } else {
            abort(404);
        }
    }

    /**
     * Show notification groups view
     *
     * @param int $id Notification ID
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function editGroups($id)
    {
        $notification = $this->notificationsGroupsRepo->find($id);
        if (!is_null($notification)) {
            try {
                $this->notificationsCollection->formatDetailsGroups($notification);
                $data['notification'] = $notification;
                $data['title'] = _i('Update group');
                return view('back.notifications.groups.edit', $data);
            } catch (\Exception $ex) {
                \Log::error(__METHOD__, ['exception' => $ex, 'slider' => $id]);
                abort(500);
            }
        } else {
            abort(404);
        }
    }

    /**
     * Show create view
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        try {
            $types = $this->notificationsTypesRepo->all();
            $groups = $this->notificationsGroupsRepo->all();
            $segments = $this->segmentsRepo->allByWhitelabel();
            $data['segments'] = $segments;
            $data['groups'] = $groups;
            $data['types'] = $types;
            $data['title'] = _i('New notification');
            return view('back.notifications.create', $data);
        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex]);
            abort(500);
        }
    }

    /**
     * Show create notifications groups view
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function createGroups()
    {
        try {
            $data['title'] = _i('New groups');
            return view('back.notifications.groups.create', $data);
        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex]);
            abort(500);
        }
    }

    /**
     * Delete notifications
     *
     * @param int $id notifications ID
     * @param string $file File name
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function delete($id, $file = null)
    {
        try {
            if (!is_null($file)){
                $path = "{$this->filePath}{$file}";
                Storage::delete($path);
            }
            $this->notificationsRepo->delete($id);
            $data = [
                'title' => _i('Notification removed'),
                'message' => _i('The notification was successfully removed'),
                'close' => _i('Close')
            ];
            return Utils::successResponse($data);
        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex, 'id' => $id, 'file' => $file]);
            return Utils::failedResponse();
        }
    }

    /**
     * Delete notifications groups
     *
     * @param int $id notifications groups ID
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function deleteGroups($id)
    {
        try {
            $this->notificationsGroupsUsersRepo->delete($id);
            $data = [
                'title' => _i('Group removed'),
                'message' => _i('The group was successfully removed'),
                'close' => _i('Close')
            ];
            return Utils::successResponse($data);
        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex, 'id' => $id]);
            return Utils::failedResponse();
        }
    }

    /**
     * Delete user for notifications groups
     *
     * @param int $id notifications groups ID
     * @param int $user User ID
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function deleteUserForGroup($id, $user)
    {
        try {
            $this->notificationsGroupsUsersRepo->delete($id, $user);
            $data = [
                'title' => _i('User removed'),
                'message' => _i('The user was successfully removed for group'),
                'close' => _i('Close')
            ];
            return Utils::successResponse($data);
        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex, 'id' => $id, 'user' => $user]);
            return Utils::failedResponse();
        }
    }

    /**
     * Get all users of group
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function groupUsers($group)
    {
        try {
            $whitelabel = Configurations::getWhitelabel();
            $currency = session('currency');
            $notifications = $this->notificationsGroupsRepo->users($currency, $whitelabel, $group);
            $this->notificationsCollection->formatUsers($notifications);
            $data = [
                'notifications' => $notifications
            ];
            return Utils::successResponse($data);
        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex]);
            return Utils::failedResponse();
        }
    }

    /**
     * Get all users of notificacion
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listUser($id, $type)
    {
        try {
            $notificationUsers = '';
            $whitelabel = Configurations::getWhitelabel();
            if ($type == NotificationTypes::$segment) {
                $segments = $this->notificationsRepo->notificationSegment($id, $whitelabel);
                if(!is_null($segments->data)){
                    $usersId = json_decode($segments->data);
                    $notificationUsers = $this->usersRepo->getByIDs($usersId);
                }
            } else {
                $notificationUsers = $this->notificationsRepo->listUsers($id, $whitelabel);
            }
            $users = $this->notificationsCollection->formatDetailsUsers($notificationUsers, $id, $type);
            $data = [
                'users' => $users
            ];
            return Utils::successResponse($data);
        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex]);
            return Utils::failedResponse();
        }
    }

    /**
     * Remove user from notification
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function removeUser($id, $user)
    {
        try {
            $this->notificationsRepo->removeUser($id, $user);
            $data = [
                'title' => _i('User removed'),
                'message' => _i('User has been removed from the messaging system.'),
                'close' => _i('Close')
            ];
            return Utils::successResponse($data);
        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex]);
            return Utils::failedResponse();
        }
    }

    /**
     * Store notification
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'language' => 'required',
            'title' => 'required',
            'content' => 'required',
            'currency' => 'required',
            'type' => 'required',
            'users' => 'required_if:type,1',
            'excel_file' => 'required_if:type,5',
        ]);

        try {
            $image = $request->file('image');
            if (!is_null($image)) {
                $extension = $image->getClientOriginalExtension();
                $originalName = str_replace(".$extension", '', $image->getClientOriginalName());
                $name = Str::slug($originalName) . time() . '.' . $extension;
                $path = "{$this->filePath}{$name}";
                Storage::put($path, file_get_contents($image->getRealPath()), 'public');
            } else {
                $name = null;
            }
            $users = $request->users;
            $group = $request->groups;
            $segmentId = $request->segments;
            $excelFile = $request->file('excel_file');
            $whitelabel = Configurations::getWhitelabel();
            $type = $request->type == NotificationTypes::$excel ? NotificationTypes::$user : $request->type;
            $currency = $request->currency;
            $notificationData = [
                'whitelabel_id' => $whitelabel,
                'image' => $name,
                'title' => $request->title,
                'content' => $request->input('content'),
                'language' => $request->language,
                'currency_iso' => $currency,
                'status' => $request->status,
                'notification_type_id' => $type,
                'operator_id' => auth()->user()->id,
                'deleted_at' => null,
            ];

            if ($request->type == NotificationTypes::$excel && !is_null($excelFile)){
                $import = new UsersNotificationImport($whitelabel);
                $import->import($excelFile);
                $users = $import->data;
            }

            $notification = $this->notificationsRepo->store($notificationData, $users);

            if (!is_null($group)){
                $groupUsers = $this->notificationsGroupsUsersRepo->getUsersByGroup($group);
                foreach ($groupUsers as $users){
                    $this->notificationsRepo->users($notification->id, $users->user_id);
                }
            }

            //if ($type == NotificationTypes::$segment && !is_null($segmentId)){
            //    $dataSegment = $this->notificationsRepo->verificationSegments($notification->id, $segmentId);
            //    if (is_null($dataSegment)){
            //        $this->notificationsRepo->segment($notification->id, $segmentId);
            //   }
            //}

            if ($type == NotificationTypes::$excel) {
                $messages = _i('Excel successfully uploaded, The notification has been successfully published.');
            } else {
                $messages = _i('The notification was published correctly');
            }

            $user_id = auth()->user()->id;
            $auditData = [
                'ip' => Utils::userIp($request),
                'user_id' => $user_id,
                'username' => auth()->user()->username,
                'notification_data' => $notificationData
            ];

            //Audits::store($user_id, AuditTypes::$notification_creation, Configurations::getWhitelabel(), $auditData);

            $data = [
                'title' => _i('Notification published'),
                'message' =>$messages,
                'close' => _i('Close')
            ];
            return Utils::successResponse($data);
        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex, 'request' => $request->all()]);
            return Utils::failedResponse();
        }
    }

    /**
     * Store notification groups
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Illuminate\Validation\ValidationException
     */
    public function storeGroups(Request $request)
    {
        $this->validate($request, [
            'description' => 'required',
            'name' => 'required',
            'currency' => 'required'
        ]);

        try {
            $notificationData = [
                'whitelabel_id' => Configurations::getWhitelabel(),
                'description' => $request->description,
                'name' => $request->name,
                'currency_iso' => $request->currency,
                'operator_id' => auth()->user()->id
            ];
            $this->notificationsGroupsRepo->store($notificationData);

            $data = [
                'title' => _i('Group created'),
                'message' => _i('The group was published correctly'),
                'close' => _i('Close')
            ];
            return Utils::successResponse($data);
        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex, 'request' => $request->all()]);
            return Utils::failedResponse();
        }
    }

    /**
     * Show notification list
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $data['title'] = _i('List of notifications');
        return view('back.notifications.index', $data);
    }

    /**
     * Show notification groups list
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function indexGroups()
    {
        $data['title'] = _i('List of groups');
        return view('back.notifications.groups.index', $data);
    }

    /**
     * Update notifications
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(Request $request)
    {
        $this->validate($request, [
            'language' => 'required',
            'title' => 'required',
            'content' => 'required',
            'currency' => 'required',
            'type' => 'required'
        ]);

        try {
            $id = $request->id;
            $file = $request->file;
            $image = $request->file('image');

            $users = $request->users;
            $segment = $request->segments;
            $type = $request->type_notification;
            $currency = $request->currency;
            $excelFile = $request->file('excel_file');
            $whitelabel = Configurations::getWhitelabel();

            $notificationData = [
                'content' => $request->input('content'),
                'title' => $request->title,
                'language' => $request->language,
                'currency_iso' => $currency,
                'status' => $request->status,
                'notification_type_id' => $request->type_notification,
                'deleted_at' => null
            ];

            if (!is_null($image)) {
                $extension = $image->getClientOriginalExtension();
                $originalName = str_replace(".$extension", '', $image->getClientOriginalName());
                $name = Str::slug($originalName) . time() . '.' . $extension;
                $newFilePath = "{$this->filePath}{$name}";
                $oldFilePath = "{$this->filePath}{$file}";
                Storage::put($newFilePath, file_get_contents($image->getRealPath()), 'public');
                Storage::delete($oldFilePath);
                $postData['image'] = $name;
                $file = $name;
            } else {
                $postData['image'] = null;
            }

            $this->notificationsRepo->update($id, $notificationData);

            if ($type == NotificationTypes::$excel && !is_null($excelFile)){
                $import = new UsersNotificationImport($currency, $whitelabel);
                $import->import($excelFile);
                $users = $import->data;
            }

            //if ($type == NotificationTypes::$segment){
            //    $dataSegment = $this->notificationsRepo->verificationSegments($id, $segment);
            //    if (is_null($dataSegment)){
            //        $this->notificationsRepo->segment($id, $segment);
            //    }
            //}

            if (!is_null($users) && !in_array(null, $users)) {
                foreach ($users as $user) {
                    $userNotificacion = $this->notificationsRepo->verificationUser($id, $user);
                    if (is_null($userNotificacion)) {
                        $this->notificationsRepo->users($id, $user);
                    }
                }
            }

            $user_id = auth()->user()->id;
            $auditData = [
                'ip' => Utils::userIp($request),
                'user_id' => $user_id,
                'username' => auth()->user()->username,
                'notification_data' => [
                    'id' => $id,
                    'data' => $notificationData
                ]
            ];

            //Audits::store($user_id, AuditTypes::$notification_modification, Configurations::getWhitelabel(), $auditData);

            $data = [
                'title' => _i('Notification updated'),
                'message' => _i('The notification data was updated correctly'),
                'close' => _i('Close'),
                'file' => $file
            ];
            return Utils::successResponse($data);
        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex, 'request' => $request->all()]);
            return Utils::failedResponse();
        }
    }

    /**
     * Update notifications groups
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Illuminate\Validation\ValidationException
     */
    public function updateGroups(Request $request)
    {
        $this->validate($request, [
            'description' => 'required',
            'name' => 'required',
            'currency' => 'required'
        ]);

        try {
            $id = $request->id;
            $notificationData = [
                'name' => $request->name,
                'description' => $request->description,
                'currency_iso' => $request->currency,
                'operator_id' => auth()->user()->id
            ];

            $this->notificationsGroupsRepo->update($id, $notificationData);
            $data = [
                'title' => _i('Group updated'),
                'message' => _i('The group data was updated correctly'),
                'close' => _i('Close'),
            ];
            return Utils::successResponse($data);
        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex, 'request' => $request->all()]);
            return Utils::failedResponse();
        }
    }
}
