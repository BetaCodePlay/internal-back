<?php

namespace App\Http\Controllers;

use Dotworkers\Configurations\Configurations;
use App\Core\Repositories\CredentialsRepo;
use App\Core\Repositories\GamesRepo;
use App\FinancialReport\Collections\FinancialReportCollection;
use Dotworkers\Configurations\Utils;
use Illuminate\Http\Request;

/**
 * Class FinancialReportController
 *
 * This class allows to manage financial report requests
 *
 * @package App\Http\Controllers
 * @author  Genesis Perez
 */
class FinancialReportController
{
    /**
     * ImagesController constructor
     *
     * @param CredentialsRepo $credentialsRepo
     * @param GamesRepo $gamesRepo
     * @param FinancialReportCollection $financialReportCollection
     *
     */
    public function __construct(CredentialsRepo $credentialsRepo, GamesRepo $gamesRepo, FinancialReportCollection $financialReportCollection)
    {
        $this->credentialsRepo = $credentialsRepo;
        $this->gamesRepo = $gamesRepo;
        $this->financialReportCollection = $financialReportCollection;
    }

    /**
     * Get all
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function all(Request $request)
    {
        try {
            $provider = $request->provider;
            $amount = $request->amount;
            $load_amount = $request->load_amount;
            $load_date = $request->load_date;
            $limit = $request->limit;
            $data = [
                'amount' => $amount,
                'load_amount' => $load_amount,
                'load_date' => $load_date,
                'limit' => $limit
            ];
            return Utils::successResponse($data);

        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex]);
            return Utils::failedResponse();
        }
    }


    /**
     * Get providers
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        try {
            $whitelabel = Configurations::getWhitelabel();
            $currency = session('currency');
            $provider = $this->credentialsRepo->searchByWhitelabel($whitelabel, $currency);
            $data['title'] = _i('Create');
            $data['providers'] = $provider;
            return view('back.financial-report.index', $data);
        } catch (\Exception $e) {
            \Log::error(__METHOD__, ['exception' => $e]);
            abort(500);
        }
    }

    /**
     * Provider maker
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function maker(Request $request)
    {
        try {
            $provider = $request->change_provider;
            $maker = [];
            if (!is_null($provider)) {
                $maker = $this->gamesRepo->getMakersByProvider($provider);
                $this->financialReportCollection->formatAll($maker);
            }
            $data = [
                'maker' => $maker
            ];
            return Utils::successResponse($data);

        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex]);
            return Utils::failedResponse();
        }
    }

    /**
     * Store
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        try {


            $data = [
                'title' => _i('Saved game'),
                'message' => _i('The game was assigned to the category selected successfully'),
                'close' => _i('Close'),
                'route' => route('financial-report.index')
            ];
            return Utils::successResponse($data);

        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex]);
            return Utils::failedResponse();
        }
    }

}
