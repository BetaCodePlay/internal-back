<?php

namespace App\Http\Controllers;

use App\Core\Repositories\CountriesRepo;
use App\CRM\Collections\SegmentsCollection;
use App\CRM\Enums\TypeUserLoadSegment;
use App\CRM\Import\UsersImport;
use App\CRM\Repositories\SegmentsRepo;
use App\Users\Repositories\UsersRepo;
use Carbon\Carbon;
use Dotworkers\Configurations\Configurations;
use Dotworkers\Configurations\Enums\Codes;
use Dotworkers\Configurations\Utils;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class SegmentsController
 *
 * This class manage segmentation tool requests
 *
 * @package App\Http\Controllers
 * @author  Carlos Hurtado
 */
class SegmentsController extends Controller
{
    /**
     * SegmentsRepo
     *
     * @var SegmentsRepo
     */
    private $segmentsRepo;

    /**
     * SegmentsCollection
     *
     * @var SegmentsCollection
     */
    private $segmentsCollection;

    /**
     * CountriesRepo
     *
     * @var CountriesRepo
     */
    private $countriesRepo;

    /**
     * UsersRepo
     *
     * @var UsersRepo
     */
    private $usersRepo;

    /**
     * SegmentsController constructor
     *
     * @param SegmentsRepo $segmentsRepo
     * @param SegmentsCollection $segmentsCollection
     * @param CountriesRepo $countriesRepo
     * @param UsersRepo $usersRepo
     */
    public function __construct(SegmentsRepo $segmentsRepo, SegmentsCollection $segmentsCollection, CountriesRepo $countriesRepo, UsersRepo $usersRepo)
    {
        $this->segmentsRepo = $segmentsRepo;
        $this->segmentsCollection = $segmentsCollection;
        $this->countriesRepo = $countriesRepo;
        $this->usersRepo = $usersRepo;
    }

    /**
     * Add user to segment
     *
     * @param Request $request
     * @return Response
     */
    public function addUser(Request $request)
    {
        $this->validate($request, [
            'segments' => 'required'
        ]);
        try {
            $segmentId = $request->segments;
            $user = $request->user;
            $segment = $this->segmentsRepo->getBySegmentId($segmentId);

            if (in_array($user, $segment->data)) {
                $data = [
                    'title' => _i('Duplicate user'),
                    'message' => _i('User already exists in segment'),
                    'close' => _i('Close'),
                    'status' => false
                ];
                return Utils::errorResponse(Codes::$forbidden, $data);

            } else {
                $segmentUserId = $segment->data;
                array_push($segmentUserId, $user);
                $segmentUsers = [
                    'data' => $segmentUserId
                ];

                $this->segmentsRepo->update($segmentId, $segmentUsers);

                $data = [
                    'title' => _i('Segment updated'),
                    'message' => _i('The user was added to the segment successfully'),
                    'close' => _i('Close'),
                    'route' => route('users.details', [$user])
                ];
                return Utils::successResponse($data);
            }

        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex]);
            return Utils::failedResponse();
        }
    }

    /**
     * Get all segments
     *
     * @return Response
     */
    public function all()
    {
        try {
            $segments = $this->segmentsRepo->allByWhitelabel();
            $this->segmentsCollection->formatAll($segments);
            $data = [
                'segments' => $segments
            ];
            return Utils::successResponse($data);

        } catch (\Exception $ex) {
            Log::error(__METHOD__, ['exception' => $ex]);
            return Utils::failedResponse();
        }
    }

    /**
     * Show Segmentation Tool report
     *
     * @return Application|Factory|View
     */
    public function create()
    {
        try {
            $data['currency_client'] = Configurations::getCurrencies();
            $data['countries'] = $this->countriesRepo->all();
            $data['title'] = _i('Create segment');
            return view('back.crm.segments.create', $data);

        } catch (\Exception $ex) {
            Log::error(__METHOD__, ['exception' => $ex]);
            abort(500);
        }
    }

    /**
     * Delete sliders
     *
     * @param int $id Slider ID
     * @param string $file File name
     * @return Response
     */
    public function delete($id)
    {
        try {
            $this->segmentsRepo->delete($id);
            $data = [
                'title' => _i('Segment removed'),
                'message' => _i('The segment was successfully removed'),
                'close' => _i('Close')
            ];
            return Utils::successResponse($data);

        } catch (\Exception $ex) {
            Log::error(__METHOD__, ['exception' => $ex, 'id' => $id]);
            return Utils::failedResponse();
        }
    }

    /**
     * Edit segments
     *
     * @param int $id Slider ID
     * @return Application|Factory|View
     */
    public function edit($id)
    {
        $whitelabel = Configurations::getWhitelabel();
        $segment = $this->segmentsRepo->find($id, $whitelabel);
        $this->segmentsCollection->formatDetails($segment);

        if (!is_null($segment)) {
            try {
                $data['currency_client'] = Configurations::getCurrencies();
                $data['countries'] = $this->countriesRepo->all();
                $data['segment'] = $segment;
                $data['title'] = _i('Update segment');
                return view('back.crm.segments.edit', $data);

            } catch (\Exception $ex) {
                Log::error(__METHOD__, ['exception' => $ex, 'id' => $id]);
                abort(500);
            }
        } else {
            abort(404);
        }
    }

    /**
     * Show list of segments
     *
     * @return Application|Factory|View
     */
    public function index()
    {
        $data['title'] = _i('List of segments');
        return view('back.crm.segments.index', $data);
    }

    /**
     * Remover user segment
     *
     * @param int $id Segment ID
     * @param int $user User ID
     * @return Response
     */
    public function removerUser($id, $user)
    {
        try {
            $segment = $this->segmentsRepo->getBySegmentId($id);
            $users = $segment->data;
            $usersIds = [];
            if (($key = array_search($user, $users)) !== false) {
                unset($users[$key]);
                foreach ($users as $userItem) {
                    $usersIds[] = $userItem;
                }
                $segmentData = [
                    'data' => $usersIds
                ];
                $this->segmentsRepo->update($id, $segmentData);
            }

            $data = [
                'title' => _i('User removed'),
                'message' => _i(' User was removed from segment successfully'),
                'close' => _i('Close')
            ];

            return Utils::successResponse($data);

        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex]);
            return Utils::failedResponse();
        }
    }

    /**
     * Update segments
     *
     * @param Request $request
     * @return Response
     * @throws ValidationException
     */
    public function update(Request $request)
    {
        try {
            if (!is_null($request->users)) {
                $users = explode(',', $request->users);
            } else {
                $users = [];
            }

            $id = $request->id;
            $segmentData = [
                'name' => $request->name,
                'description' => $request->description,
                'data' => $users,
                'whitelabel_id' => Configurations::getWhitelabel(),
                'filter' => json_decode($request->filter)
            ];
            $this->segmentsRepo->update($id, $segmentData);

            $user_id = auth()->user()->id;
            $auditData = [
                'ip' => Utils::userIp($request),
                'user_id' => $user_id,
                'username' => auth()->user()->username,
                'segments_data' => $segmentData
            ];

            //Audits::store($user_id, AuditTypes::$segments_creation, Configurations::getWhitelabel(), $auditData);

            $data = [
                'title' => _i('Segment created'),
                'message' => _i('The segment was created successfully'),
                'close' => _i('Close')
            ];
            return Utils::successResponse($data);

        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex, 'request' => $request]);
            return Utils::failedResponse();
        }
    }

    /**
     * Users list segment
     *
     * @param int $id Segment ID
     * @return Response
     */
    public function usersList($id)
    {
        try {
            $whitelabel = Configurations::getWhitelabel();
            $segment = $this->segmentsRepo->find($id, $whitelabel);
            $usersSegment = $this->usersRepo->getByIDs($segment->data);
            $this->segmentsCollection->formatUserslist($id, $usersSegment);
            $data = [
                'users' => $usersSegment,
            ];
            return Utils::successResponse($data);

        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex, 'id' => $id]);
            return Utils::failedResponse();
        }
    }

    /**
     * Get user segment
     *
     * @param int $user User ID
     * @return Response
     */
    public function userSegment($user)
    {
        try {
            $segments = $this->segmentsRepo->allByWhitelabel();
            $segmentsData = $this->segmentsCollection->formatSegmentsUser($segments, $user);
            $data = [
                'segments' => $segmentsData
            ];
            return Utils::successResponse($data);

        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex]);
            return Utils::failedResponse();
        }
    }

    /**
     * Store segments
     *
     * @param Request $request
     * @return Response
     * @throws ValidationException
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'description' => 'required'
        ]);

        try {
            if (!is_null($request->users)) {
                $users = explode(',', $request->users);
            } else {
                $users = [];
            }

            $segmentData = [
                'name' => $request->name,
                'description' => $request->description,
                'data' => $users,
                'whitelabel_id' => Configurations::getWhitelabel(),
            ];

            if (!is_null($request->filter)) {
                $segmentData += [
                    'filter' => json_decode($request->filter)
                ];
            }
            $this->segmentsRepo->store($segmentData);

            $user_id = auth()->user()->id;
            $auditData = [
                'ip' => Utils::userIp($request),
                'user_id' => $user_id,
                'username' => auth()->user()->username,
                'segments_data' => $segmentData
            ];

            //Audits::store($user_id, AuditTypes::$segments_creation, Configurations::getWhitelabel(), $auditData);

            $data = [
                'title' => _i('Segment created'),
                'message' => _i('The segment was created successfully'),
                'close' => _i('Close')
            ];
            return Utils::successResponse($data);

        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex, 'request' => $request]);
            return Utils::failedResponse();
        }
    }

    /**
     * Get users data
     *
     * @param Request $request
     * @return Response
     */
    public function usersData(Request $request): Response
    {
        try {
            $country = $request->country;
            $excludeCountry = $request->exclude_country;
            $balanceOptions = $request->balance_options;
            $balance = $request->balance;
            $depositsOptions = $request->deposits_options;
            $deposits = $request->deposits;
            $lastLoginOptions = $request->last_login_options;
            $lastLogin = $request->last_login;
            $lastDepositOptions = $request->last_deposit_options;
            $lastDeposit = $request->last_deposit;
            $lastWithdrawalOptions = $request->last_withdrawal_options;
            $lastWithdrawal = $request->last_withdrawal;
            $registrationOptions = $request->registration_options;
            $registrationDate = $request->registration_date;
            $playedOptions = $request->played_options;
            $played = $request->played;
            $fullProfile = $request->full_profile;
            $currency = $request->currency;
            $status = $request->status;
            $language = $request->language;
            $timezone = session('timezone');
            $whitelabel = Configurations::getWhitelabel();
            $filter = [];

            if (!is_null($country) && !in_array('', $country)) {
                $filter += [
                    'country' => $country,
                ];
            }

            if (!empty($excludeCountry)) {
                $filter += [
                    'exclude_country' => $excludeCountry,
                ];
            }

            if (!is_null($lastLogin)) {
                $lastLogin = Carbon::createFromFormat('d-m-Y', $lastLogin, $timezone)->setTimezone('UTC')->format('Y-m-d');
                $filter += [
                    'last_login_options' => $lastLoginOptions,
                    'last_login' => $lastLogin
                ];
            }

            if (!is_null($lastDeposit)) {
                $lastDeposit = Carbon::createFromFormat('d-m-Y', $lastDeposit, $timezone)->setTimezone('UTC')->format('Y-m-d');
                $filter += [
                    'last_deposit_options' => $lastDepositOptions,
                    'last_deposit' => $lastDeposit
                ];
            }

            if (!is_null($lastWithdrawal)) {
                $lastWithdrawal = Carbon::createFromFormat('d-m-Y', $lastWithdrawal, $timezone)->setTimezone('UTC')->format('Y-m-d');
                $filter += [
                    'last_withdrawal_options' => $lastWithdrawalOptions,
                    'last_withdrawal' => $lastWithdrawal
                ];
            }

            if (!is_null($registrationDate)) {
                $registrationDate = Carbon::createFromFormat('d-m-Y', $registrationDate, $timezone)->setTimezone('UTC')->format('Y-m-d');
                $filter += [
                    'registration_options' => $registrationOptions,
                    'registration_date' => $registrationDate
                ];
            }

            if (!is_null($balance)) {
                $filter += [
                    'balance_options' => $balanceOptions,
                    'balance' => $balance,
                ];
            }

            if (!is_null($played)) {
                $filter += [
                    'played_options' => $playedOptions,
                    'played' => $played,
                ];
            }

            if (!is_null($deposits)) {
                $filter += [
                    'deposits_options' => $depositsOptions,
                    'deposits' => $deposits,
                ];
            }

            if (!is_null($currency) && !in_array(null, $currency)) {
                $filter += [
                    'currency' => $currency,
                ];
            }

            if (!is_null($language) && !in_array(null, $language)) {
                $filter += [
                    'language' => $language,
                ];
            }

            $filter += [
                'status' => $status,
            ];

            $users = $this->usersRepo->getSegmentation($country, $currency, $excludeCountry, $status, $whitelabel, $lastLoginOptions, $lastLogin, $lastDepositOptions, $lastDeposit, $lastWithdrawalOptions, $lastWithdrawal, $language, $registrationOptions, $registrationDate);

            if (!is_null($fullProfile)) {
                $filter += [
                    'full_profile' => $fullProfile,
                ];
            }
            $usersData = $this->segmentsCollection->formatSegmentationData($whitelabel, $users, $depositsOptions, $deposits, $balanceOptions, $balance, $playedOptions, $played, $fullProfile, $filter);
            return Utils::successResponse($usersData);

        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex]);
            return Utils::failedResponse();
        }
    }
}
