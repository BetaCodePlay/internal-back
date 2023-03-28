<?php

namespace App\Users\Collections;

use App\Agents\Repositories\AgentsRepo;
use App\Users\Enums\DocumentStatus;
use App\Users\Repositories\AutoLockUsersRepo;
use Carbon\Carbon;
use Dotworkers\Configurations\Configurations;
use Dotworkers\Configurations\Enums\Providers;
use App\Audits\Enums\AuditTypes;
use Dotworkers\Configurations\Enums\TransactionTypes;
use Dotworkers\Security\Enums\Permissions;
use Illuminate\Support\Facades\Gate;
use Xinax\LaravelGettext\Facades\LaravelGettext;


/**
 * Class UsersCollection
 *
 * This class allows to format users data
 *
 * @package App\Users\Collections
 * @author  Eborio Linarez
 */
class UsersCollection
{

    /**
     * UsersController constructor.
     * @param AgentsRepo $agentsRepo
     */
    public function __construct(AgentsRepo $agentsRepo)
    {
        $this->agentsRepo = $agentsRepo;

    }

    /**
     * Format agent
     *
     * @param object $agent Agent data
     */
    public function formatAgent($agent)
    {
        if (!is_null($agent)) {
            $agent->username = sprintf(
                '<a href="%s" class="btn u-btn-3d u-btn-primary btn-sm" target="_blank">%s</a>',
                route('users.details', [$agent->user_id]),
                $agent->username
            );
        }
    }

    /**
     * Parent Tree
     * @param int $userId User Id
     * @return string
     */
    public function treeFatherFormat($userId){

        $agent = $this->agentsRepo->existsUser($userId);
        $link = '';
        if(isset($agent->user_id)){
            $link .= '<ul class="list" id="ul_'.$agent->user_id.'">
                        <li  class="" id="li_'.$agent->user_id.'">'.$agent->username.'</li>';
            if(isset($agent->user_id)){
                $link .= $this->treeFatherFormat($agent->user_id);
            }
            $link .= '</ul>';
        }

        return $link;

    }

    /**
     * Formatting User Gender
     * @param array $users gender data
     *
     */
    public function formatGender($users)
    {

        $genderData = [];
        $countM = 0;
        $countF = 0;
        $countIM = 0;
        foreach ($users as $key => $user) {
            switch ($user->gender) {
                case "M":
                {
                    (int)$countM++;
                    break;
                }
                case "F":
                {
                    (int)$countF++;
                    break;
                }
                case null:
                {
                    (int)$countIM++;
                    break;
                }
            }
        }

        $genderData[0] = ['gender' => _i('Male'), 'quantity' => $countM];
        $genderData[1] = ['gender' => _i('Female'), 'quantity' => $countF];
        $genderData[2] = ['gender' => _i('Incomplete profiles'), 'quantity' => $countIM];

        return $genderData;
    }

    /**
     * Format login graphic
     *
     * @param array $audits Login audits data
     * @param array $period Dates period
     * @return array
     */
    public function formatLoginGraphic($audits, $period)
    {
        $auditsData = [];

        foreach ($period as $key => $date) {
            $date = $date->format('Y-m-d');
            $dateDesktop = 0;
            $dateMobile = 0;

            if (count($audits) > 0) {
                foreach ($audits as $audit) {
                    if ($audit->created_at->format('Y-m-d') == $date) {
                        if ($audit->data->mobile) {
                            $dateMobile++;

                        } else {
                            $dateDesktop++;
                        }
                    }
                }
                $auditsData[] = [
                    'date' => $date,
                    'Desktop' => $dateDesktop,
                    'Mobile' => $dateMobile,
                    'name_desktop' => _i('Desktop'),
                    'name_mobile' => _i('Mobile')
                ];
            }
        }
        return $auditsData;
    }

    /**
     * Format registered users graphic
     *
     * @param array $period Period data
     * @param array $users Users data
     * @return array
     */
    public function formatRegisteredGraphic($period, $users)
    {
        $usersData = collect();
        $dates = [];
        foreach ($period as $key => $date) {
            $dates[] = $date->format('Y-m');
        }

        foreach ($dates as $key => $date) {
            foreach ($users as $user) {
                if ($user->date == $date) {
                    $userObject = new \stdClass();
                    $userObject->date = $date;
                    $userObject->quantity = $user->quantity;
                    $usersData->push($userObject);
                    unset($dates[$key]);
                }
            }
        }

        foreach ($dates as $dateItem) {
            $userObject = new \stdClass();
            $userObject->date = $dateItem;
            $userObject->quantity = 0;
            $usersData->push($userObject);
        }
        return $usersData->sortBy('date')->values()->all();
    }

    /**
     * Format details
     *
     * @param object $user User data
     * @param array $transactionsTotals Transactions totals
     * @param array $manualTransactionsTotals Manual transactions totals
     * @param float $bonus Bonus total
     */
    public function formatDetails($user, $transactionsTotals, $manualTransactionsTotals, $bonus)
    {
        $timezone = session('timezone');
        $user->full_name = (is_null($user->first_name) && is_null($user->last_name)) ? $user->username : "{$user->username} | {$user->first_name} {$user->last_name}";
        $user->created = $user->created_at->setTimezone($timezone)->format('d-m-Y H:i:s');
        $user->birth_date = date('d-m-Y', strtotime($user->birth_date));
        $user->login = is_null($user->last_login) ? _i('No access') : $user->last_login->setTimezone($timezone)->format('d-m-Y H:i:s');
        $profit = ($user->deposits + $user->manual_deposits) - ($user->withdrawals + $user->manual_withdrawals);
        $user->deposits = number_format($transactionsTotals['deposits']);
        $user->withdrawals = number_format($transactionsTotals['withdrawals']);
        $user->manual_deposits = number_format($manualTransactionsTotals['deposits']);
        $user->manual_withdrawals = number_format($manualTransactionsTotals['withdrawals']);
        $user->profit = number_format($profit, 2);
        $user->bonus = number_format($bonus, 2);
        if(!is_null($user->avatar)){
            $user->avatar = s3_asset("avatar/{$user->avatar}");
        }
    }

    /**
     * Format search
     *
     * @param array $users Users data
     */
    public function formatExcludeProviderUser($users)
    {
        $timezone = session('timezone');
        foreach ($users as $user) {
            $user->user = sprintf(
                '<a href="%s" class="btn u-btn-3d u-btn-primary btn-sm" target="_blank">%s</a>',
                route('users.details', [$user->user_id]),
                $user->user_id
            );
            $user->date = $user->created_at->setTimezone($timezone)->format('d-m-Y H:i:s');
            $user->actions = sprintf(
                '<button type="button" class="btn u-btn-3d btn-sm u-btn-primary mr-2 delete" id="delete" data-route="%s"><i class="hs-admin-trash"></i> %s</button>',
                route('users.exclude-providers-users.delete', [$user->user_id, $user->provider_id, $user->currency_iso]),
                _i('Delete')
            );
        }
    }

    /**
     * Format document verification
     *
     * @param array $documents Documents data
     */
    public function formatDocument($documents)
    {
        $language = LaravelGettext::getLocale();
        $timezone = session('timezone');
        foreach ($documents as $document) {
            $document->user = sprintf(
                '<a href="%s" class="btn btn-primary btn-sm" target="_blank">%s</a>',
                route('users.details', [$document->user_id]),
                $document->user_id
            );
            $document->user_name = $document->username;
            $document->date = $document->created_at->setTimezone($timezone)->format('d-m-Y H:i:s');
            $translations = json_decode($document->translations);
            $name = $translations->$language ?? $translations->en_US;
            $document->name_type = $name;
            $document->currency = $document->currency_iso;
            $url = s3_asset("documents/{$document->name}");
            $extension = substr($document->name, -3);
            if ($extension != 'pdf') {
                $image = "<img src='$url' class='img-responsive' width='100%'>";
            } else {
                $image = "<iframe src='$url' class='responsive-iframe'  width='700px' height='900px' frameborder='0' allowfullscreen></iframe>";
            }
            $document->action = sprintf(
                '<button class="btn u-btn-3d btn-sm u-btn-bluegray mr-2" data-toggle="modal" data-target="#watch-document-modal" data-document="%s"><i class="hs-admin-eye"></i> %s</button>',
                $image,
                _i('View')
            );

            $document->action .= sprintf(
                '<button class="btn u-btn-3d btn-sm u-btn-bluegray mr-2" data-toggle="modal" data-target="#document-edit-modal" data-user="%s" data-id="%s"><i class="hs-admin-check"></i> %s</button>',
                $document->user_id,
                $document->id,
                _i('Edit')
            );

            $document->action .= sprintf(
                '<button class="btn u-btn-3d btn-sm u-btn-bluegray mr-2" data-toggle="modal" data-target="#document-approved-modal" data-type="%s" data-user="%s" data-status="%s" data-id="%s"><i class="hs-admin-check"></i> %s</button>',
                $document->document_type_id,
                $document->user_id,
                DocumentStatus::$approved,
                $document->id,
                _i('Approve')
            );

            $document->action .= sprintf(
                '<button class="btn u-btn-3d btn-sm u-btn-bluegray mr-2" data-toggle="modal" data-target="#document-rejected-modal" data-type="%s" data-user="%s" data-status="%s" data-id="%s" data-file="%s"><i class="hs-admin-close"></i> %s</button>',
                $document->document_type_id,
                $document->user_id,
                DocumentStatus::$rejected,
                $document->id,
                $document->name,
                _i('Rejected')
            );
        }

        $data = [
            'documents' => $documents
        ];
        return $data;
    }

    /**
     * Format document verification by user
     *
     * @param array $documents Documents data
     */
    public function formatDocumentByUser($documents)
    {
        $language = LaravelGettext::getLocale();
        $timezone = session('timezone');
        foreach ($documents as $document) {
            $document->date = $document->created_at->setTimezone($timezone)->format('d-m-Y H:i:s');
            $translations = json_decode($document->translations);
            $name = $translations->$language ?? $translations->en_US;
            $document->name_type = $name;
            $url = s3_asset("documents/{$document->name}");
            $extension = substr($document->name, -3);
            if ($extension != 'pdf') {
                $image = "<img src='$url' class='img-responsive' width='100%'>";
            } else {
                $image = "<iframe src='$url' class='responsive-iframe' width='700px' height='900px' frameborder='0' allowfullscreen></iframe>";
            }
            $document->visualize = sprintf(
                '<button class="btn u-btn-3d btn-sm u-btn-bluegray mr-2" data-toggle="modal" data-target="#watch-document-modal" data-document="%s"><i class="hs-admin-eye"></i> %s</button>',
                $image,
                _i('View')
            );

            $document->visualize .= sprintf(
                '<button class="btn u-btn-3d btn-sm u-btn-bluegray mr-2" data-toggle="modal" data-target="#document-rejected-modal" data-type="%s" data-user="%s" data-status="%s" data-id="%s" data-file="%s"><i class="hs-admin-close"></i> %s</button>',
                $document->document_type_id,
                $document->user_id,
                DocumentStatus::$rejected,
                $document->id,
                $document->name,
                _i('Delete')
            );

            $document->status = DocumentStatus::getName($document->status);
        }

        $data = [
            'documents' => $documents
        ];
        return $data;
    }

    /**
     * Format import Data
     *
     * @param array $data Transaction data
     * @param array $failures Failures data
     */
    public function formatImportData($failures, $transactions)
    {
        $transactionsData = [];
        $dataAux = [];
        foreach ($transactions as $key => $transaction) {
            $position = array_search($transaction->username, $dataAux);
            if ($position === false) {
                array_push($dataAux, $transaction->username);
                $totalObject = new \stdClass();
                $totalObject->username = $transaction->username;
                $totalObject->currency = $transaction->currency;
                $totalObject->amount = number_format($transaction->amount, 2);
                $totalObject->description = $transaction->description;
                $totalObject->attribute = '';
                $totalObject->error = '';
                $transactionsData[$key] = $totalObject;
            }
        }

        foreach ($failures as $failure) {
            foreach ($transactionsData as $data) {
                if ($failure->values()['username'] == $data->username) {
                    $data->attribute = $failure->attribute();
                    foreach ($failure->errors() as $e) {
                        $data->error = $e;
                    }
                }
            }
        }

        $data = [
            'transaction' => $transactionsData
        ];
        return $data;
    }

    /**
     * Format search
     *
     * @param array $users Users data
     */
    public function formatSearch($users)
    {
        foreach ($users as $user) {
            if (isset($user->currency_iso)) {
                $user->user = sprintf(
                    '<a href="%s" class="btn u-btn-3d u-btn-primary btn-sm" target="_blank">%s</a>',
                    route('users.details', [$user->id, $user->currency_iso]),
                    $user->id
                );
            } else {
                $user->user = sprintf(
                    '<a href="%s" class="btn u-btn-3d u-btn-primary btn-sm" target="_blank">%s</a>',
                    route('users.details', [$user->id]),
                    $user->id
                );
            }
            $user->gender = !is_null($user->gender) ? ($user->gender == 'F' ? _i('Female') : _i('Male')) : null;
            $statusClass = $user->status ? 'teal' : 'lightred';
            $statusText = $user->status ? _i('Active') : _i('Blocked');
            $user->status = sprintf(
                '<span class="u-label g-bg-%s g-rounded-20 g-px-15 g-mr-10 g-mb-15">%s</span>',
                $statusClass,
                $statusText
            );
        }
    }

    /**
     * Format referral list
     *
     * @param array $users Users data
     * @param int $playedData Deposits played data
     * @return int
     */
    public function formatReferralList($users)
    {
        $timezone = session('timezone');
        foreach ($users as $user) {
            $user->user = sprintf(
                '<a href="%s" class="btn u-btn-3d u-btn-primary btn-sm" target="_blank">%s</a>',
                route('users.details', [$user->id]),
                $user->id
            );
            $user->date = Carbon::createFromFormat('Y-m-d H:i:s', $user->created_at)->setTimezone($timezone)->format('d-m-Y H:i:s');
            $user->currency = $user->register_currency;
            $user->actions = sprintf(
                '<button type="button" class="btn u-btn-3d btn-sm u-btn-primary mr-2 remove-referral" id="remove-referral" data-route="%s"><i class="hs-admin-trash"></i> %s</button>',
                route('referrals.remove-referral-user', [$user->id]),
                _i('Remove')
            );
        }
    }

    /**
     * Format status users
     *
     * @param array $users Users data
     */
    public function formatStatusUsers($users)
    {
        foreach ($users as $user) {

            $user->user = sprintf(
                '<a href="%s" class="btn u-btn-3d u-btn-primary btn-sm" target="_blank">%s</a>',
                route('users.details', [$user->id]),
                $user->id
            );

            $statusClass = $user->status ? 'teal' : 'lightred';
            $statusText = $user->status ? _i('Active') : _i('Blocked');
            $user->status = sprintf(
                '<span class="u-label g-bg-%s g-rounded-20 g-px-15 g-mr-10 g-mb-15">%s</span>',
                $statusClass,
                $statusText
            );

        }
    }

    /**
     * Format users temp
     *
     * @param $users
     */
    public function formatUsersTemp($users)
    {
        $timezone = session('timezone');
        foreach ($users as $user) {
            $user->date = $user->created_at->setTimezone($timezone)->format('d-m-Y H:i:s');
            $user->action = sprintf(
                '<button class="btn u-btn-3d btn-sm u-btn-bluegray mr-2" data-toggle="modal" data-target="#send-email-modal" data-email="%s" data-username="%s"><i class="hs-admin-email"></i> %s</button>',
                $user->email,
                $user->username,
                _i('Resend email')
            );

            $user->action .= sprintf(
                '<button class="btn u-btn-3d btn-sm u-btn-bluegray mr-2" data-toggle="modal" data-target="#activation-modal" data-email="%s" data-username="%s"><i class="hs-admin-email"></i> %s</button>',
                $user->email,
                $user->username,
                _i('Manual activation')
            );

            $user->action .= sprintf(
                '<button type="button" class="btn u-btn-3d btn-sm u-btn-primary mr-2 delete" data-route="%s"><i class="hs-admin-trash"></i> %s</button>',
                route('users.delete', [$user->username]),
                _i('Delete')
            );
        }

        $data = [
            'users' => $users
        ];
        return $data;
    }

    /**
     * Get type audit
     *
     * @param $users
     * @return string
     */
    public function getTypeAudit($users)
    {
        foreach ($users as $user) {
            $timezone = session('timezone');
            $user->date = $user->created_at->setTimezone($timezone)->format('d-m-Y H:i:s');
            switch ($user->audit_type_id) {
                case AuditTypes::$login:
                {
                    $user->types = _i('Login');
                    if (!is_null($user->data->mobile)) {
                        $mobile = $user->data->mobile;
                        if ($mobile = true) {
                            $user->details = sprintf(
                                '<ul><li><strong>%s</strong>%s%s</li><li><strong>%s</strong>%s%s</li></ul>',
                                _i('IP'),
                                ': ',
                                $user->data->ip,
                                _i('Device'),
                                ': ',
                                _i('Mobile'),
                            );
                        } else {
                            $user->details = sprintf(
                                '<ul><li><strong>%s</strong>%s%s</li><li><strong>%s</strong>%s%s</li></ul>',
                                _i('IP'),
                                ': ',
                                $user->data->ip,
                                _i('Device'),
                                ': ',
                                _i('Desktop'),
                            );
                        }
                    } else {
                        $user->details = sprintf(
                            '<ul><li><strong>%s</strong>%s%s</li></ul>',
                            _i('IP'),
                            ': ',
                            $user->data->ip,
                        );
                    }
                    break;
                }
                case AuditTypes::$dotpanel_login:
                {
                    $user->types = _i('Dotpanel login');
                    if (!is_null($user->data->mobile)) {
                        $mobile = $user->data->mobile;
                        if ($mobile = true) {
                            $user->details = sprintf(
                                '<ul><li><strong>%s</strong>%s%s</li><li><strong>%s</strong>%s%s</li></ul>',
                                _i('IP'),
                                ': ',
                                $user->data->ip,
                                _i('Device'),
                                ': ',
                                _i('Mobile'),
                            );
                        } else {
                            $user->details = sprintf(
                                '<ul><li><strong>%s</strong>%s%s</li><li><strong>%s</strong>%s%s</li></ul>',
                                _i('IP'),
                                ': ',
                                $user->data->ip,
                                _i('Device'),
                                ': ',
                                _i('Desktop'),
                            );
                        }
                    } else {
                        $user->details = sprintf(
                            '<ul><li><strong>%s</strong>%s%s</li></ul>',
                            _i('IP'),
                            ': ',
                            $user->data->ip,
                        );
                    }
                    break;
                }
                case AuditTypes::$user_modification:
                {
                    if (isset($user->data->user_data->email) && !is_null($user->data->user_data->email)) {
                        $email = $user->data->user_data->email;
                    } else {
                        $email = "";
                    }
                    if (isset($user->data->user_data->dni) && !is_null($user->data->user_data->dni)) {
                        $dni = $user->data->user_data->dni;
                    } else {
                        $dni = "";
                    }
                    if (isset($user->data->user_data->last_name) && !is_null($user->data->user_data->last_name)) {
                        $lastName = $user->data->user_data->last_name;
                    } else {
                        $lastName = "";
                    }
                    if (isset($user->data->user_data->first_name) && !is_null($user->data->user_data->first_name)) {
                        $firstName = $user->data->user_data->first_name;
                    } else {
                        $firstName = "";
                    }
                    if (isset($user->data->user_data->gender) && !is_null($user->data->user_data->gender)) {
                        $gender = $user->data->user_data->gender;
                    } else {
                        $gender = "";
                    }
                    if (isset($user->data->user_data->level) && !is_null($user->data->user_data->level)) {
                        $level = $user->data->user_data->level;
                    } else {
                        $level = "";
                    }
                    if (isset($user->data->user_data->country_iso) && !is_null($user->data->user_data->country_iso)) {
                        $country = $user->data->user_data->country_iso;
                    } else {
                        $country = "";
                    }
                    if (isset($user->data->user_data->timezone) && !is_null($user->data->user_data->timezone)) {
                        $timezone = $user->data->user_data->timezone;
                    } else {
                        $timezone = "";
                    }
                    if (isset($user->data->user_data->address) && !is_null($user->data->user_data->address)) {
                        $address = $user->data->user_data->address;
                    } else {
                        $address = "";
                    }
                    if (isset($user->data->user_data->phone) && !is_null($user->data->user_data->phone)) {
                        $phone = $user->data->user_data->phone;
                    } else {
                        $phone = "";
                    }
                    if (isset($user->data->user_data->birth_date) && !is_null($user->data->user_data->birth_date)) {
                        $birthDate = $user->data->user_data->birth_date;
                    } else {
                        $birthDate = "";
                    }
                    $user->types = _i('User modification');
                    $user->details = sprintf(
                        '<ul><li><strong>%s</strong>%s%s</li><li><strong>%s</strong>%s%s</li><li><strong>%s</strong>%s%s</li><li><strong>%s</strong>%s%s</li><li><strong>%s</strong>%s%s</li><li><strong>%s</strong>%s%s</li><li><strong>%s</strong>%s%s</li><li><strong>%s</strong>%s%s</li><li><strong>%s</strong>%s%s</li><li><strong>%s</strong>%s%s</li><li><strong>%s</strong>%s%s</li><li><strong>%s</strong>%s%s</li></ul>',
                        _i('IP'),
                        ': ',
                        $user->data->ip,
                        _i('Email'),
                        ': ',
                        $email,
                        _i('DNI'),
                        ': ',
                        $dni,
                        _i('First name'),
                        ': ',
                        $firstName,
                        _i('Last name'),
                        ': ',
                        $lastName,
                        _i('Gender'),
                        ': ',
                        $gender,
                        _i('Level'),
                        ': ',
                        $level,
                        _i('Country ISO'),
                        ': ',
                        $country,
                        _i('Timezone'),
                        ': ',
                        $timezone,
                        _i('Address'),
                        ': ',
                        $address,
                        _i('Phone'),
                        ': ',
                        $phone,
                        _i('Birth date'),
                        ': ',
                        $birthDate,

                    );
                    break;
                }
                case AuditTypes::$user_creation:
                {
                    $user->types = _i('User creation');
                    $user->details = sprintf(
                        '<ul><li><strong>%s</strong>%s%s</li><li><strong>%s</strong>%s%s</li><li><strong>%s</strong>%s%s</li></ul>',
                        _i('IP'),
                        ': ',
                        $user->data->ip,
                        _i('Username'),
                        ': ',
                        $user->data->username,
                        _i('Currency'),
                        ': ',
                        $user->data->user_data->currency_iso
                    );
                    break;
                }
                case AuditTypes::$user_status:
                {
                    if (!is_null($user->data->old_status)) {
                        if ($user->data->old_status == '1') {
                            $oldStatus = 'active';
                        } else {
                            $oldStatus = 'inactive';
                        }
                    }

                    if (!is_null($user->data->new_status)) {
                        if ($user->data->new_status == true) {
                            $newStatus = 'active';
                        } else {
                            $newStatus = 'inactive';
                        }
                    }

                    if (isset($user->data->description) && !is_null($user->data->description)) {
                        $description = $user->data->description;
                    } else {
                        $description = "";
                    }

                    $user->types = _i('User status');
                    $user->details = sprintf(
                        '<ul><li><strong>%s</strong>%s%s</li><li><strong>%s</strong>%s%s</li><li><strong>%s</strong>%s%s</li><li><strong>%s</strong>%s%s</li><li><strong>%s</strong>%s%s</li></ul>',
                        _i('IP'),
                        ': ',
                        $user->data->ip,
                        _i('Username'),
                        ': ',
                        !is_null($user->data->username) ? $user->data->username : null,
                        _i('Old status'),
                        ': ',
                        $oldStatus,
                        _i('New status'),
                        ': ',
                        $newStatus,
                        _i('Description'),
                        ': ',
                        $description,

                    );
                    break;
                }
                case AuditTypes::$user_password:
                {
                    $user->types = _i('User password');
                    $user->details = sprintf(
                        '<ul><li><strong>%s</strong>%s%s</li><li><strong>%s</strong>%s%s</li><li><strong>%s</strong>%s%s</li></ul>',
                        _i('IP'),
                        ': ',
                        $user->data->ip,
                        _i('Username'),
                        ': ',
                        $user->data->username,
                        _i('Password'),
                        ': ',
                        $user->data->password,
                    );
                    break;
                }
                case AuditTypes::$support_login:
                {
                    $user->types = _i('Support login');
                    if (!is_null($user->data->mobile)) {
                        $mobile = $user->data->mobile;
                        if ($mobile = true) {
                            $user->details = sprintf(
                                '<ul><li><strong>%s</strong>%s%s</li><li><strong>%s</strong>%s%s</li><li><strong>%s</strong>%s%s</li></ul>',
                                _i('IP'),
                                ': ',
                                $user->data->ip,
                                _i('Username'),
                                ': ',
                                $user->data->username,
                                _i('Device'),
                                ': ',
                                _i('Mobile'),
                            );
                        } else {
                            $user->details = sprintf(
                                '<ul><li><strong>%s</strong>%s%s</li><li><strong>%s</strong>%s%s</li><li><strong>%s</strong>%s%s</li></ul>',
                                _i('IP'),
                                ': ',
                                $user->data->ip,
                                _i('Username'),
                                ': ',
                                $user->data->username,
                                _i('Device'),
                                ': ',
                                _i('Desktop'),
                            );
                        }
                    } else {
                        $user->details = sprintf(
                            '<ul><li><strong>%s</strong>%s%s</li><li><strong>%s</strong>%s%s</li></ul>',
                            _i('IP'),
                            ': ',
                            $user->data->ip,
                            _i('Username'),
                            ': ',
                            $user->data->username,
                        );
                    }
                    break;

                }
                case AuditTypes::$manual_transactions:
                {
                    $user->types = _i('Manual transactions');
                    if (isset ($user->data->user_data) && !is_null($user->data->user_data)) {
                        if (isset($user->data->transaction->amount) && !is_null($user->data->transaction->amount)) {
                            $amount = $user->data->transaction->amount;
                        } else {
                            $amount = "";
                        }
                        if (isset($user->data->transaction->currency) && !is_null($user->data->transaction->currency)) {
                            $currency = $user->data->transaction->currency;
                        } else {
                            $currency = "";
                        }
                        if (isset($user->data->transaction->transaction_type_id) && !is_null($user->data->transaction->transaction_type_id)) {
                            if ($user->data->transaction->transaction_type_id = 1) {
                                $transactionType = _i('Credit');
                            } else {
                                $transactionType = _i('Debit');
                            }
                        } else {
                            $transactionType = "";
                        }
                        if (isset($user->data->transaction->description) && !is_null($user->data->transaction->description)) {
                            $description = $user->data->user_data->first_name;
                        } else {
                            $description = "";
                        }
                        if (isset($user->data->user_data->details->data->operator) && !is_null($user->data->user_data->details->data->operator)) {
                            $operator = $user->data->user_data->details->data->operator;
                        } else {
                            $operator = "";
                        }
                        if (isset($user->data->user_data->details->data->wallet_transaction) && !is_null($user->data->user_data->details->data->wallet_transaction)) {
                            $walletTransaction = $user->data->user_data->details->data->wallet_transaction;
                        } else {
                            $walletTransaction = "";
                        }
                        $user->details = sprintf(
                            '<ul><li><strong>%s</strong>%s%s</li><li><strong>%s</strong>%s%s</li><li><strong>%s</strong>%s%s</li><li><strong>%s</strong>%s%s</li><li><strong>%s</strong>%s%s</li><li><strong>%s</strong>%s%s</li><li><strong>%s</strong>%s%s</li><li><strong>%s</strong>%s%s</li><li><strong>%s</strong>%s%s</li></ul>',
                            _i('IP'),
                            ': ',
                            $user->data->ip,
                            _i('Username'),
                            ': ',
                            $user->data->username,
                            _i('User ID'),
                            ': ',
                            $user->data->user_id,
                            _i('Amount'),
                            ': ',
                            $amount,
                            _i('Currency'),
                            ': ',
                            $currency,
                            _i('Type'),
                            ': ',
                            $transactionType,
                            _i('Description'),
                            ': ',
                            $description,
                            _i('Operator'),
                            ': ',
                            $operator,
                            _i('Wallet transaction'),
                            ': ',
                            $walletTransaction,
                        );
                    } else {
                        if (isset($user->data->transaction->amount) && !is_null($user->data->transaction->amount)) {
                            $amount = $user->data->transaction->amount;
                        } else {
                            $amount = "";
                        }
                        if (isset($user->data->transaction->currency) && !is_null($user->data->transaction->currency)) {
                            $currency = $user->data->transaction->currency;
                        } else {
                            $currency = "";
                        }
                        if (isset($user->data->transaction->transaction_type_id) && !is_null($user->data->transaction->transaction_type_id)) {
                            if ($user->data->transaction->transaction_type_id = 1) {
                                $transactionType = _i('Credit');
                            } else {
                                $transactionType = _i('Debit');
                            }
                        } else {
                            $transactionType = "";
                        }
                        if (isset($user->data->transaction->description) && !is_null($user->data->transaction->description)) {
                            $description = $user->data->transaction->description;
                        } else {
                            $description = "";
                        }
                        $user->details = sprintf(
                            '<ul><li><strong>%s</strong>%s%s</li><li><strong>%s</strong>%s%s</li><li><strong>%s</strong>%s%s</li><li><strong>%s</strong>%s%s</li><li><strong>%s</strong>%s%s</li><li><strong>%s</strong>%s%s</li><li><strong>%s</strong>%s%s</li></ul>',
                            _i('IP'),
                            ': ',
                            $user->data->ip,
                            _i('Username'),
                            ': ',
                            $user->data->username,
                            _i('User ID'),
                            ': ',
                            $user->data->user_id,
                            _i('Amount'),
                            ': ',
                            $amount,
                            _i('Currency'),
                            ': ',
                            $currency,
                            _i('Type'),
                            ': ',
                            $transactionType,
                            _i('Description'),
                            ': ',
                            $description,
                        );
                    }

                    break;
                }
                case AuditTypes::$manual_adjustments:
                {
                    $user->types = _i('Manual adjustments');
                    if (isset ($user->data->user_data) && !is_null($user->data->user_data)) {
                        if (isset($user->data->user_data->amount) && !is_null($user->data->user_data->amount)) {
                            $amount = $user->data->user_data->amount;
                        } else {
                            $amount = "";
                        }
                        if (isset($user->data->user_data->currency) && !is_null($user->data->user_data->currency)) {
                            $currency = $user->data->user_data->currency;
                        } else {
                            $currency = "";
                        }
                        if (isset($user->data->user_data->transaction_type_id) && !is_null($user->data->user_data->transaction_type_id)) {
                            if ($user->data->user_data->transaction_type_id = 1) {
                                $transactionType = _i('Credit');
                            } else {
                                $transactionType = _i('Debit');
                            }
                        } else {
                            $transactionType = "";
                        }
                        if (isset($user->data->user_data->description) && !is_null($user->data->user_data->description)) {
                            $description = $user->data->user_data->first_name;
                        } else {
                            $description = "";
                        }
                        if (isset($user->data->user_data->details->data->operator) && !is_null($user->data->user_data->details->data->operator)) {
                            $operator = $user->data->user_data->details->data->operator;
                        } else {
                            $operator = "";
                        }
                        if (isset($user->data->user_data->details->transaction_status_id) && !is_null($user->data->user_data->details->transaction_status_id)) {
                            if ($user->data->user_data->details->transaction_status_id = 1) {
                                $transactionStatus = _i('Pending');
                            } elseif ($user->data->user_data->details->transaction_status_id = 2) {
                                $transactionStatus = _i('Approved');
                            } elseif ($user->data->user_data->details->transaction_status_id = 3) {
                                $transactionStatus = _i('Rejected');
                            } else {
                                $transactionStatus = "";
                            }
                        } else {
                            $transactionStatus = "";
                        }
                        if (isset($user->data->user_data->details->provider_id) && !is_null($user->data->user_data->details->provider_id)) {
                            $provider = Providers::getName($user->data->user_data->details->provider_id);
                        } else {
                            $provider = "";
                        }
                        $user->details = sprintf(
                            '<ul><li><strong>%s</strong>%s%s</li><li><strong>%s</strong>%s%s</li><li><strong>%s</strong>%s%s</li><li><strong>%s</strong>%s%s</li><li><strong>%s</strong>%s%s</li><li><strong>%s</strong>%s%s</li><li><strong>%s</strong>%s%s</li><li><strong>%s</strong>%s%s</li><li><strong>%s</strong>%s%s</li><li><strong>%s</strong>%s%s</li></ul>',
                            _i('IP'),
                            ': ',
                            $user->data->ip,
                            _i('Username'),
                            ': ',
                            $user->data->username,
                            _i('User ID'),
                            ': ',
                            $user->data->user_data->details->user_id,
                            _i('Amount'),
                            ': ',
                            $amount,
                            _i('Currency'),
                            ': ',
                            $currency,
                            _i('Type'),
                            ': ',
                            $transactionType,
                            _i('Status'),
                            ': ',
                            $transactionStatus,
                            _i('Provider'),
                            ': ',
                            $provider,
                            _i('Description'),
                            ': ',
                            $description,
                            _i('Operator'),
                            ': ',
                            $operator,
                        );
                    } else {
                        $user->details = "";
                    }
                    break;
                }
                case AuditTypes::$bonus_transactions:
                {
                    $user->types = _i('Bonus transactions');
                    if (isset ($user->data->user_data) && !is_null($user->data->user_data)) {
                        if (isset($user->data->user_data->amount) && !is_null($user->data->user_data->amount)) {
                            $amount = $user->data->user_data->amount;
                        } else {
                            $amount = "";
                        }
                        if (isset($user->data->user_data->currency) && !is_null($user->data->user_data->currency)) {
                            $currency = $user->data->user_data->currency;
                        } else {
                            $currency = "";
                        }
                        if (isset($user->data->user_data->transaction_type_id) && !is_null($user->data->user_data->transaction_type_id)) {
                            if ($user->data->user_data->transaction_type_id = 1) {
                                $transactionType = _i('Credit');
                            } else {
                                $transactionType = _i('Debit');
                            }
                        } else {
                            $transactionType = "";
                        }
                        if (isset($user->data->user_data->description) && !is_null($user->data->user_data->description)) {
                            $description = $user->data->user_data->first_name;
                        } else {
                            $description = "";
                        }
                        if (isset($user->data->user_data->details->data->operator) && !is_null($user->data->user_data->details->data->operator)) {
                            $operator = $user->data->user_data->details->data->operator;
                        } else {
                            $operator = "";
                        }
                        if (isset($user->data->user_data->details->transaction_status_id) && !is_null($user->data->user_data->details->transaction_status_id)) {
                            if ($user->data->user_data->details->transaction_status_id = 1) {
                                $transactionStatus = _i('Pending');
                            } elseif ($user->data->user_data->details->transaction_status_id = 2) {
                                $transactionStatus = _i('Approved');
                            } elseif ($user->data->user_data->details->transaction_status_id = 3) {
                                $transactionStatus = _i('Rejected');
                            } else {
                                $transactionStatus = "";
                            }
                        } else {
                            $transactionStatus = "";
                        }
                        if (isset($user->data->user_data->details->provider_id) && !is_null($user->data->user_data->details->provider_id)) {
                            $provider = Providers::getName($user->data->user_data->details->provider_id);
                        } else {
                            $provider = "";
                        }
                        if (isset($user->data->user_data->details->data->wallet_transaction) && !is_null($user->data->user_data->details->data->wallet_transaction)) {
                            $wallet = $user->data->user_data->details->data->wallet_transaction;
                        } else {
                            $wallet = "";
                        }
                        $user->details = sprintf(
                            '<ul><li><strong>%s</strong>%s%s</li><li><strong>%s</strong>%s%s</li><li><strong>%s</strong>%s%s</li><li><strong>%s</strong>%s%s</li><li><strong>%s</strong>%s%s</li><li><strong>%s</strong>%s%s</li><li><strong>%s</strong>%s%s</li><li><strong>%s</strong>%s%s</li><li><strong>%s</strong>%s%s</li><li><strong>%s</strong>%s%s</li><li><strong>%s</strong>%s%s</li></ul>',
                            _i('IP'),
                            ': ',
                            $user->data->ip,
                            _i('Username'),
                            ': ',
                            $user->data->username,
                            _i('User ID'),
                            ': ',
                            $user->data->user_data->details->user_id,
                            _i('Amount'),
                            ': ',
                            $amount,
                            _i('Currency'),
                            ': ',
                            $currency,
                            _i('Type'),
                            ': ',
                            $transactionType,
                            _i('Status'),
                            ': ',
                            $transactionStatus,
                            _i('Provider'),
                            ': ',
                            $provider,
                            _i('Description'),
                            ': ',
                            $description,
                            _i('Operator'),
                            ': ',
                            $operator,
                            _i('Wallet transaction'),
                            ': ',
                            $wallet,
                        );
                    } else {
                        $user->details = "";
                    }
                    break;
                }
            }
        }
    }

    /**
     * Format users autolocked totals data
     *
     * @param array $users users autolocked totals
     */
    public function autoLockedUsers($users)
    {
        $timezone = session('timezone');
        $autoLockUsersRepo = new AutoLockUsersRepo();
        foreach ($users as $user) {
            $numberLock = $autoLockUsersRepo->countAutoLock($user->user_id);
            $startDate = Carbon::parse($user->start_date);
            $endDate = Carbon::parse($user->end_date);
            $user->user = sprintf(
                '<a href="%s" class="btn u-btn-3d u-btn-primary btn-sm" target="_blank">%s</a>',
                route('users.details', [$user->user_id]),
                $user->user_id
            );
            $user->lock_date = Carbon::parse($user->created_at)->setTimezone($timezone)->format('d-m-Y H:i:s');
            $user->lock_time = !is_null($user->end_date) ? $endDate->diffInMonths($startDate) : _i('Permanent');
            $user->auto_locked = $numberLock;
            $user->unlock_date = !is_null($user->end_date) ? $user->end_date->setTimezone($timezone)->format('d-m-Y H:i:s') : '';
            $user->currency = $user->currency_iso;
        }
        $data = [
            'users' => $users
        ];
        return $data;

    }

    /**
     * Format user list charging point
     *
     * @param $user user
     */
    public function userListChargingPoint($user)
    {
        $usersData = [];
        $userObject = new \stdClass();
        $userObject->user = sprintf(
            '<a href="%s" class="btn u-btn-3d u-btn-primary btn-sm" target="_blank">%s</a>',
            route('users.details', [$user->id, $user->currency_iso]),
            $user->id
        );
        $userObject->currency_iso = $user->currency_iso;
        $userObject->username = $user->username;
        $userObject->first_name = $user->first_name;
        $userObject->last_name = $user->last_name;
        $userObject->email = $user->email;
        $statusClass = $user->status ? 'teal' : 'lightred';
        $statusText = $user->status ? _i('Active') : _i('Blocked');
        $userObject->status = sprintf(
            '<span class="u-label g-bg-%s g-rounded-20 g-px-15 g-mr-10 g-mb-15">%s</span>',
            $statusClass,
            $statusText
        );
        $userObject->actions = '';
        if (Gate::allows('access', Permissions::$process_credit)) {
            $userObject->actions .= sprintf(
                '<button class="btn u-btn-3d btn-sm u-btn-bluegray mr-2" data-toggle="modal" data-target="#process-credit-modal" data-wallet="%s" data-user="%s" data-transaction-type="%s">%s</button>',
                $user->wallet_id,
                $user->id,
                TransactionTypes::$credit,
                _i('Process')
            );
        }
        $usersData[] = $userObject;
        return $usersData;
    }

}
