<?php

namespace App\Http\Controllers;

use App\Core\Collections\ProductsLimitsCollection;
use App\Core\Repositories\CurrenciesRepo;
use App\Core\Repositories\ProvidersLimitsRepo;
use App\Core\Repositories\ProvidersRepo;
use App\Whitelabels\Repositories\WhitelabelsRepo;
use Carbon\Carbon;
use Dotworkers\Configurations\Configurations;
use Dotworkers\Configurations\Enums\Providers;
use Dotworkers\Configurations\Utils;
use Illuminate\Http\Request;

/**
 * Class ProvidersLimitsController
 *
 * This class allows to manage platform limits requests
 *
 * @package App\Http\Controllers
 * @author  Eborio Linarez
 */
class ProvidersLimitsController extends Controller
{
    /**
     * ProvidersLimitsRepo
     *
     * @var ProvidersLimitsRepo
     */
    private $providersLimitsRepo;

    /**
     * ProductsLimitsCollection
     *
     * @var ProductsLimitsCollection
     */
    private $productsLimitsCollection;

    /**
     * ProvidersRepo
     *
     * @var ProvidersRepo
     */
    private $providersRepo;

    private $whitelabelsRepo;

    private $currenciesRepo;

    /**
     * PagesController constructor
     *
     * @param ProvidersLimitsRepo $providersLimitsRepo
     * @param ProductsLimitsCollection $productsLimitsCollection
     * @param ProvidersRepo $providersRepo
     * @param WhitelabelsRepo $whitelabelsRepo ,
     * @param CurrenciesRepo $currenciesRepo
     */
    public function __construct
    (
        ProvidersLimitsRepo $providersLimitsRepo,
        ProductsLimitsCollection $productsLimitsCollection,
        ProvidersRepo $providersRepo,
        WhitelabelsRepo $whitelabelsRepo,
        CurrenciesRepo $currenciesRepo
    )
    {
        $this->providersLimitsRepo = $providersLimitsRepo;
        $this->productsLimitsCollection = $productsLimitsCollection;
        $this->providersRepo = $providersRepo;
        $this->whitelabelsRepo = $whitelabelsRepo;
        $this->currenciesRepo = $currenciesRepo;
    }

    /**
     * Get all pages
     *
     * @param int $provider Provider ID
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function all($provider)
    {
        try {
            $limits = $this->providersLimitsRepo->all($provider);
            $this->productsLimitsCollection->formatAll($limits);
            $data = [
                'limits' => $limits
            ];
            return Utils::successResponse($data);

        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex]);
            return Utils::failedResponse();
        }
    }

    /**
     * Create products limits
     *
     * @param int $provider Provider ID
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create($provider)
    {
        $data['whitelabels'] = $this->whitelabelsRepo->all();
        $data['currencies_list'] = $this->currenciesRepo->all();
        $providerData = $this->providersRepo->find($provider);
        $data['provider'] = $provider;
        $data['title'] = _i('Create limits') . ': ' . $providerData->name;
        return view('back.providers-limits.create', $data);
    }

    /**
     * Edit limits
     *
     * @param int $whitelabel Whitelabel ID
     * @param string $currency Currency ISO
     * @param int $provider Provider ID
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($whitelabel, $currency, $provider)
    {
        try {
            $limit = $this->providersLimitsRepo->find($whitelabel, $currency, $provider);
            $providerData = $this->providersRepo->find($limit->provider_id);
            $this->productsLimitsCollection->formatDetails($limit);
            $data['limit'] = $limit;
            $data['title'] = _i('Update limits') . ': ' . $providerData->name;
            return view('back.providers-limits.edit', $data);

        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex]);
            abort(500);
        }
    }

    /**
     * Show pages list
     *
     * @param int $provider Provider ID
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index($provider)
    {
        $data['provider'] = $provider;
        $data['title'] = _i('Provider limits');
        return view('back.providers-limits.index', $data);
    }

    public function providerCurrency($currency)
    {
        try {
            $whitelabel = Configurations::getWhitelabel();
            $providers = $this->providersRepo->getByWhitelabelandFilterByCurrency($whitelabel, $currency);
            return Utils::successResponse($providers);
            
        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex, 'currency' => $currency]);
            return Utils::failedResponse();
        }
    }

    /**
     * Store limits
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $provider = $request->provider;

        switch ($provider) {
            case Providers::$sportbook:
            {
                $this->validate($request, [
                    'min_bet' => 'required|numeric|gt:0',
                    'max_bet' => 'required|numeric|gt:0',
                    'max_selections' => 'required|numeric|gt:0',
                    'max_selections_not_favorites' => 'required|numeric|gt:0',
                    'straight_bet_limit' => 'required|numeric|gt:0',
                    'parlay_bet_limit' => 'required|numeric|gt:0',
                ]);
                break;
            }
            case Providers::$center_horses:
            {
                break;
            }
        }

        try {
            switch ($provider) {
                case Providers::$sportbook:
                {
                    $limits = [
                        'min_bet' => $request->min_bet,
                        'max_bet' => $request->max_bet,
                        'max_selections' => $request->max_selections,
                        'max_selections_not_favorites' => $request->max_selections_not_favorites,
                        'straight_bet_limit' => $request->straight_bet_limit,
                        'parlay_bet_limit' => $request->parlay_bet_limit,
                    ];
                    break;
                }
                case Providers::$center_horses:
                {
                    break;
                }
            }
            $limitData = [
                'whitelabel_id' => $request->whitelabel,
                'currency_iso' => $request->currency,
                'provider_id' => $provider,
                'data' => $limits
            ];
            $this->providersLimitsRepo->store($limitData);
            $data = [
                'title' => _i('Limit created'),
                'message' => _i('Boundary data was stored correctly'),
                'close' => _i('Close')
            ];
            return Utils::successResponse($data);

        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex, 'request' => $request->all()]);
            return Utils::failedResponse();
        }
    }

    /**
     * Update pages
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(Request $request)
    {
        $this->validate($request, [
            'amount' => 'required|numeric|gt:0'
        ]);

        try {
            $id = $request->id;
            $whitelabel = Configurations::getWhitelabel();

            $limitData = [
                'amount' => $request->amount,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];

            $this->providersLimitsRepo->update($id, $whitelabel, $limitData);
            $data = [
                'title' => _i('Page updated'),
                'message' => _i('The page data was updated correctly'),
                'close' => _i('Close')
            ];
            return Utils::successResponse($data);

        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex, 'request' => $request->all()]);
            return Utils::failedResponse();
        }
    }
}
