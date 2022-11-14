<?php

namespace App\Http\Controllers;

use App\Core\Collections\CurrenciesCollection;
use App\Core\Repositories\CurrenciesRepo;
use App\Whitelabels\Collections\OperationalBalancesCollection;
use App\Whitelabels\Repositories\OperationalBalancesRepo;
use App\Whitelabels\Collections\WhitelabelsCollection;
use App\Whitelabels\Enums\Status;
use App\Whitelabels\Repositories\OperationalBalancesTransactionsRepo;
use App\Whitelabels\Repositories\WhitelabelsStatusRepo;
use App\Whitelabels\Repositories\WhitelabelsRepo;
use Dotworkers\Configurations\Configurations;
use Dotworkers\Configurations\Enums\Providers;
use Dotworkers\Configurations\Utils;
use Dotworkers\Store\Enums\TransactionTypes;
use Illuminate\Http\Request;

/**
 * Class WhitelabelsStatusController
 *
 * This class allows to manage core requests
 *
 * @package App\Http\Controllers
 * @author  Genesis Perez
 */
class WhitelabelsController extends Controller
{
    /**
     * WhitelabelsStatusRepo
     *
     * @var WhitelabelsStatusRepo
     */
    private $whitelabelsStatusRepo;

    /**
     * WhitelabelsRepo
     *
     * @var WhitelabelsRepo
     */
    private $whitelabelsRepo;

    /**
     * WhitelabelsCollection
     *
     * @var WhitelabelsCollection
     */
    private $whitelabelsCollection;

    /**
     * OperationalBalancesRepo
     *
     * @var OperationalBalancesRepo
     */
    private $operationalBalancesRepo;

    /**
     * OperationalBalancesCollection
     *
     * @var OperationalBalancesCollection
     */
    private $operationalBalancesCollection;

    /**
     * OperationalBalancesTransactionsRepo
     *
     * @var OperationalBalancesTransactionsRepo
     */
    private $operationalBalancesTransactionsRepo;

    /**
     * CurrenciesRepo
     *
     * @var CurrenciesRepo
     */
    private $currenciesRepo;

    /**
     * CurrenciesCollection
     *
     * @var CurrenciesCollection
     */
    private $currenciesCollection;

    /**
     * WhitelabelsController constructor
     *
     * @param WhitelabelsStatusRepo $whitelabelsStatusRepo
     * @param WhitelabelsRepo $whitelabelsRepo
     * @param WhitelabelsCollection $whitelabelsCollection
     * @param OperationalBalancesRepo $operationalBalancesRepo
     * @param OperationalBalancesCollection $operationalBalancesCollection
     * @param OperationalBalancesTransactionsRepo $operationalBalancesTransactionsRepo
     * @param CurrenciesRepo $currenciesRepo
     * @param CurrenciesCollection $currenciesCollection
     */
    public function __construct(
        WhitelabelsStatusRepo $whitelabelsStatusRepo,
        WhitelabelsRepo $whitelabelsRepo,
        WhitelabelsCollection $whitelabelsCollection,
        OperationalBalancesRepo $operationalBalancesRepo,
        OperationalBalancesCollection $operationalBalancesCollection,
        OperationalBalancesTransactionsRepo $operationalBalancesTransactionsRepo,
        CurrenciesRepo $currenciesRepo,
        CurrenciesCollection $currenciesCollection
    )
    {
        $this->whitelabelsStatusRepo = $whitelabelsStatusRepo;
        $this->whitelabelsRepo = $whitelabelsRepo;
        $this->whitelabelsCollection = $whitelabelsCollection;
        $this->operationalBalancesRepo = $operationalBalancesRepo;
        $this->operationalBalancesCollection = $operationalBalancesCollection;
        $this->operationalBalancesTransactionsRepo = $operationalBalancesTransactionsRepo;
        $this->currenciesRepo = $currenciesRepo;
        $this->currenciesCollection = $currenciesCollection;
    }

    /**
     * Change whitelabel status
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function changeStatus(Request $request)
    {
        try {
            $whitelabel = $request->whitelabel;
            $statusData = [
                'status' => $request->status
            ];
            $this->whitelabelsRepo->update($whitelabel, $statusData);
            $data = [
                'title' => _i('Status updated'),
                'message' => _i('Whitelabel status was updated successfully'),
                'close' => _i('Close'),
            ];
            return Utils::successResponse($data);

        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex]);
            return Utils::failedResponse();
        }
    }

    /**
     * Get currency by whitelabels
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function create()
    {
        $data['title'] = _i('Create whitelabel');
        return view('back.whitelabels.create', $data);
    }

    /**
     * Show create view
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function currencyByWhitelabel(Request $request)
    {
        $whitelabel = $request->whitelabel;
        $whitelabelCurrencies = Configurations::getCurrenciesByWhitelabel($whitelabel);
        $allCurrencies = $this->currenciesRepo->all();
        $currencies = $this->currenciesCollection->formatWhitelabelCurrencies($whitelabelCurrencies, $allCurrencies);
        $data = [
            'currencies' => $currencies
        ];
        return Utils::successResponse($data);

    }

    /**
     * Edit whitelabel
     *
     * @param int $id Whitelabel ID
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function edit($id)
    {
        $whitelabel = $this->whitelabelsRepo->find($id);
        $data['whitelabel'] = $whitelabel;
        $data['title'] = _i('Edit whitelabel %s', [$whitelabel->description]);
        return view('back.whitelabels.edit', $data);
    }

    /**
     * Whitelabels operational balances
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function operationalBalances()
    {
        $data['title'] = _i('Operational balances');
        return view('back.whitelabels.operational-balances', $data);
    }

    /**
     * Get whitelabels operational balances data
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function operationalBalancesData()
    {
        try {
            $operationalBalances = $this->operationalBalancesRepo->all();
            $this->operationalBalancesCollection->formatAll($operationalBalances);
            $data = [
                'operational_balances' => $operationalBalances
            ];
            return Utils::successResponse($data);

        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex]);
            return Utils::failedResponse();
        }
    }

    /**
     * Store whitelabel
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|alpha_dash|unique:whitelabels,name',
            'description' => 'required',
            'domain' => 'required|unique:whitelabels,domain',
        ]);

        try {
            $data = [
                'name' => strtoupper($request->name),
                'description' => ucwords($request->description),
                'domain' => strtolower($request->domain),
                'status' => Status::$development
            ];
            $levels = [1, 2, 3, 4, 5];
            $levelsData = [];

            foreach ($levels as $level) {
                $levelObject = new \stdClass();
                $levelObject->id = $level;
                $levelObject->en_US = "Level {$level}";
                $levelObject->es_ES = "Nivel {$level}";
                $levelObject->pt_BR = "Nível {$level}";
                $levelObject->he_IL = "{$level} שלב";
                $levelsData[] = $levelObject;
            }

            $accessComponent = [
                'registration' => [
                    'allow' => null,
                    'username_regex' => '/^[a-zA-Z0-9]{4,12}+$/',
                    'password_regex' => '/\\S*(^(?=.*\\d)(?=.*[a-zA-Z]).{8,}$)/',
                    'email' => [
                        'field' => null,
                        'confirmation' => null,
                    ],
                    'phone' => [
                        'field' => null,
                        'confirmation' => null,
                    ],
                    'birth_date' => [
                        'field' => null
                    ],
                    'referral_code' => null,
                    'social' => [
                        'facebook' => false,
                        'google' => false,
                    ],
                ],
                'login' => [
                    'allow' => null,
                    'forgot_password' => true,
                    'social' => [
                        'facebook' => false,
                        'google' => false,
                    ],
                ],
                'complete_profile' => false,
                'levels' => $levelsData,
                'languages' => [],
                'default_language' => null,
                'main_route' => [
                    'desktop' => [
                        'main' => null,
                        'auth' => null,
                    ],
                    'mobile' => [
                        'main' => null,
                        'auth' => null,
                    ],
                ],
            ];

            $designComponent = [
                'template' => [
                    'theme' => 'default',
                    'header' => null,
                    'footer' => null,
                    'home' => null,
                    'login' => null,
                    'register' => null,
                    'guest_header' => true,
                    'guest_footer' => true,
                    'open_games' => null,
                    'casino' => [
                        'view' => null,
                        'square' => null,
                    ],
                    'slots' => [
                        'view' => null,
                        'square' => null,
                    ],
                    'live_casino' => [
                        'view' => null,
                        'square' => null,
                    ],
                    'virtual' => [
                        'view' => null,
                        'square' => null,
                    ],
                    'golden_race' => [
                        'view' => null,
                        'square' => null,
                    ],
                    'games' => [
                        'view' => null,
                        'square' => null,
                    ],
                ],
                'favicon' => null,
                'logo' => [
                    'img_dark' => null,
                    'img_light' => null,
                ],
                's3_directory' => null,
                'menu' => [],
                'social_networks' => [],
                'whitelabel_info' => [
                    'email' => null,
                    'call' => null,
                    'text' => null,
                    'additional_text' => null,
                ],
            ];

            $currenciesComponent = [
                'currencies' => []
            ];

            $emailComponent = [
                'email' => [
                    'mailer' => null,
                    'host' => null,
                    'port' => null,
                    'encryption' => null,
                    'username' => null,
                    'password' => null,
                    'mailgun_domain' => null,
                ]
            ];

            $servicesComponent = [
                'store' => [
                    'active' => null,
                    'currency_name' => 'PTS',
                    'currency_icon' => null
                ],
                'free_currency' => [
                    'currency_name' => 'FREE',
                    'currency_icon' => 'FREE',
                ],
                'messages' => null,
                'payments' => null,
                'agents' => null,
                'chats' => [],
                'google_analytics' => [
                    'active' => null,
                    'code' => null,
                ],
                'google_tag_manager' => [
                    'active' => null,
                    'code' => null,
                ],
                'bonus' => null
            ];

            $whitelabel = $this->whitelabelsRepo->store($data, $accessComponent, $designComponent, $currenciesComponent, $emailComponent, $servicesComponent);

            $data = [
                'title' => _i('Whitelabel created'),
                'message' => _i('Whitelabel created successfully'),
                'route' => route('whitelabels.edit', [$whitelabel->id])
            ];
            return Utils::successResponse($data);

        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex]);
            return Utils::failedResponse();
        }
    }

    /**
     * Update operational balance
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function updateOperationalBalance(Request $request)
    {
        $this->validate($request, [
            'transaction_type' => 'required',
            'amount' => 'required'
        ]);

        try {
            $whitelabel = $request->whitelabel;
            $currency = $request->currency;
            $amount = $request->amount;
            $transactionType = $request->transaction_type;

            $transactionData = [
                'amount' => $amount,
                'operator' => auth()->user()->username,
                'provider_id' => Providers::$dotworkers,
                'whitelabel_id' => $whitelabel,
                'currency_iso' => $currency,
                'transaction_type_id' => $transactionType
            ];
            $this->operationalBalancesTransactionsRepo->store($transactionData);

            if ($transactionType == TransactionTypes::$credit) {
                $this->operationalBalancesRepo->increment($whitelabel, $currency, $amount);
            } else {
                $this->operationalBalancesRepo->decrement($whitelabel, $currency, $amount);
            }

            $data = [
                'title' => _i('Operational balance updated'),
                'message' => _i('Whitelabel operational balance was updated successfully'),
                'close' => _i('Close'),
            ];
            return Utils::successResponse($data);

        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex]);
            return Utils::failedResponse();
        }
    }

    /**
     * Show whitelabel status view
     */
    public function whitelabelsStatus()
    {
        $whitelabelsData = $this->whitelabelsRepo->all();
        $data['title'] = _i('Whitelabels status');
        $data['whitelabels'] = $whitelabelsData;
        return view('back.whitelabels.whitelabels-status', $data);
    }

    /**
     * Get whitelabels status data
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function whitelabelsStatusData()
    {
        try {
            $whitelabels = $this->whitelabelsRepo->all();
            $status = $this->whitelabelsStatusRepo->all();
            $this->whitelabelsCollection->formatStatus($whitelabels, $status);
            $data = [
                'whitelabels' => $whitelabels
            ];
            return Utils::successResponse($data);

        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex]);
            return Utils::failedResponse();
        }
    }
}
