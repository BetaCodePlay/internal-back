<?php

namespace App\Http\Controllers;

use Dotworkers\Configurations\Configurations;
use App\Core\Repositories\CredentialsRepo;
use App\Core\Repositories\GamesRepo;
use App\FinancialReport\Repositories\FinancialReportRepo;
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
     * @param FinancialReportRepo $financialReportRepo
     *
     */
    public function __construct(CredentialsRepo $credentialsRepo, GamesRepo $gamesRepo, FinancialReportCollection $financialReportCollection, FinancialReportRepo $financialReportRepo)
    {
        $this->credentialsRepo = $credentialsRepo;
        $this->gamesRepo = $gamesRepo;
        $this->financialReportCollection = $financialReportCollection;
        $this->financialReportRepo = $financialReportRepo;
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
            $user = auth()->user()->id;
            $provider = $this->credentialsRepo->searchByWhitelabel($whitelabel, $currency);
            $maker = $this->gamesRepo->getMakersByProvider($provider);
            $data['title'] = _i('Create');
            $data['user'] = $user;
            $data['currencies'] = Configurations::getCurrencies();
            $data['providers'] = $provider;
            $data['maker'] = $maker;
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
            $provider = $request->change_provider;
            $maker = $request->maker;
            $amount = $request->amount;
            $load_amount = $request->load_amount;
            $load_date = $request->load_date;
            $limit = $request->limit;
            $user = $request->user;
            $currency = $request->currency;
            $total_played = $request->total_played;

            $financialData = [
                'provider_id' => $provider,
                'maker' => $maker,
                'amount' => $amount,
                'load_amount' => $load_amount,
                'load_date' => $load_date,
                'limit' => $limit,
                'user_id' => $user,
                'currency_iso' => $currency,
                'total_played' => $total_played
            ];
            \Log::info(__METHOD__, ['data' => $financialData]);
            $this->financialReportRepo->store($financialData);


            $data = [
                'title' => _i('Saved'),
                'message' => _i('The data was saved successfully'),
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
