<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
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
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function all()
    {
        try {
            $report = $this->financialReportRepo->all();
            $this->financialReportCollection->formatAllReport($report);
            $data = [
                'financial' => $report
            ];
            return Utils::successResponse($data);
        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex]);
            return Utils::failedResponse();
        }
    }

    /**
     * Edit view
     * @param Request $id
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($id)
    {
        try {
            $financial = $this->financialReportRepo->findById($id);
            $whitelabel = Configurations::getWhitelabel();
            $currency = session('currency');
            $user = auth()->user()->id;
            $provider = $this->credentialsRepo->searchByWhitelabel($whitelabel, $currency);
            $data['title'] = _i('Edit transactions');
            $data['user'] = $user;
            $data['id'] = $financial->id;
            $data['currencies'] = Configurations::getCurrencies();
            $data['providers'] = $provider;
            return view('back.financial-report.edit', $data);
        } catch (\Exception $e) {
            \Log::error(__METHOD__, ['exception' => $e]);
            abort(500);
        }
    }

    /**
     * Delete
     * @param int $id ID
     * * @return \Symfony\Component\HttpFoundation\Response
     */
    public function delete($id)
    {
        try {
            $this->financialReportRepo->delete($id);
            $data = [
                'title' => _i('Data removed'),
                'message' => _i('The data was successfully removed'),
                'close' => _i('Close')
            ];
            return Utils::successResponse($data);
        } catch (\Exception $e) {
            \Log::error(__METHOD__, ['exception' => $e]);
            abort(500);
        }
    }

    /**
     * Index view
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
            $data['title'] = _i('Create');
            $data['user'] = $user;
            $data['currencies'] = Configurations::getCurrencies();
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
            $timezone = session('timezone');
            $load_date = !is_null($request->load_date) ? Carbon::createFromFormat('d-m-Y h:i a', $request->load_date, $timezone)->setTimezone('UTC') : null;
            $limit = $request->limit;
            $user = $request->user;
            $currency = $request->currency;

            $financialData = [
                'provider_id' => $provider,
                'maker' => $maker,
                'amount' => $amount,
                'load_amount' => $load_amount,
                'load_date' => $load_date,
                'limit' => $limit,
                'user_id' => $user,
                'currency_iso' => $currency
            ];
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

    /**
     * Update
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(Request $request)
    {
        try {
            $id = $request->id;
            $provider = $request->change_provider;
            $maker = $request->maker;
            $amount = $request->amount;
            $load_amount = $request->load_amount;
            $timezone = session('timezone');
            $load_date = !is_null($request->load_date) ? Carbon::createFromFormat('d-m-Y h:i a', $request->load_date, $timezone)->setTimezone('UTC') : null;
            $limit = $request->limit;
            $user = $request->user;
            $currency = $request->currency;

            $financialData = [
                'provider_id' => $provider,
                'maker' => $maker,
                'amount' => $amount,
                'load_amount' => $load_amount,
                'load_date' => $load_date,
                'limit' => $limit,
                'user_id' => $user,
                'currency_iso' => $currency
            ];

            $this->financialReportRepo->update($id, $financialData);
            $data = [
                'title' => _i('Saved'),
                'message' => _i('The data was edited successfully'),
                'close' => _i('Close'),
                'route' => route('financial-report.index')
            ];
            return Utils::successResponse($data);

        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex]);
            return Utils::failedResponse();
        }
    }


    /**
     * Search
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Illuminate\Validation\ValidationException
     */
    public function search(Request $request, $startDate = null, $endDate = null)
    {
        try {
            if (!is_null($startDate) && !is_null($endDate)) {
                $provider = $request->provider;
                $timezone = session('timezone');
                $maker = $request->maker;
                $currency = $request->currency;
                $percentage = $request->percentage;
                $chips = $request->chips;
                $report = $this->financialReportRepo->reportBenefit($provider, $maker, $currency, $startDate, $endDate, $timezone, $percentage, $chips);
                $this->financialReportCollection->formatAllReportProvider($report, $request->provider, $maker, $startDate, $percentage, $chips);
            } else {
                $report = [];
            }
            $data = [
                'report' => $report
            ];
            return Utils::successResponse($data);
        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex]);
            return Utils::failedResponse();
        }

    }

    /**
     * Index report providerview
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function indexReportProvider()
    {
        try {
            $whitelabel = Configurations::getWhitelabel();
            $currency = session('currency');
            $user = auth()->user()->id;
            $provider = $this->credentialsRepo->searchByWhitelabel($whitelabel, $currency);
            $data['title'] = _i('Create');
            $data['user'] = $user;
            $data['currencies'] = Configurations::getCurrencies();
            $data['providers'] = $provider;
            return view('back.financial-report.providers-amount.index', $data);
        } catch (\Exception $e) {
            \Log::error(__METHOD__, ['exception' => $e]);
            abort(500);
        }
    }


}
