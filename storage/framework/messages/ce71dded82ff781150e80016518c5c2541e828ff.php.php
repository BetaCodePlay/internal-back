<?php

namespace App\Http\Controllers;

use App\IQSoft\Collections\IQSoftTicketsCollection;
use App\IQSoft\Repositories\IQSoftTicketsRepo;
use App\Whitelabels\Repositories\WhitelabelsRepo;
use App\Core\Repositories\CurrenciesRepo;
use Dotworkers\Configurations\Configurations;
use Dotworkers\Configurations\Enums\Codes;
use Dotworkers\Configurations\Enums\Providers;
use Dotworkers\Configurations\Enums\Status;
use Dotworkers\Configurations\Utils;
use Illuminate\Http\Request;
use Ixudra\Curl\Facades\Curl;
use Xinax\LaravelGettext\Facades\LaravelGettext;

/**
 * Class IQSoftController
 *
 * This class allows to manage IQ Soft requests
 *
 * @package App\Http\Controllers
 * @author  Eborio Linarez
 * @author  Yeltsin Linares
 */
class IQSoftController extends Controller
{
    /**
     * Server URL
     *
     * @var string
     */
    private $serverUrl;

    /**
     * IQSoftTicketsCollection
     *
     * @var IQSoftTicketsCollection
     */
    private $iqSoftTicketsCollection;

    /**
     * CurrenciesRepo
     *
     * @var CurrenciesRepo
     */
    private $currenciesRepo;

    /**
     * WhitelabelsRepo
     *
     * @var WhitelabelsRepo
     */
    private $whitelabelsRepo;

    /**
     * IQSoftTicketsRepo
     *
     * @var IQSoftTicketsRepo
     */
    private $iqSoftTicketsRepo;

    /**
     * IQSoftController constructor
     *
     * @param IQSoftTicketsCollection $iqSoftTicketsCollection
     * @param WhitelabelsRepo $whitelabelsRepo
     * @param CurrenciesRepo $currenciesRepo
     * @param IQSoftTicketsRepo $iqSoftTicketsRepo
     */
    public function __construct(IQSoftTicketsCollection $iqSoftTicketsCollection, WhitelabelsRepo $whitelabelsRepo, CurrenciesRepo $currenciesRepo, IQSoftTicketsRepo $iqSoftTicketsRepo)
    {
        $this->serverUrl = env('IQ_SOFT_SERVER') . '/api';
        $this->iqSoftTicketsCollection = $iqSoftTicketsCollection;
        $this->whitelabelsRepo = $whitelabelsRepo;
        $this->currenciesRepo = $currenciesRepo;
        $this->iqSoftTicketsRepo = $iqSoftTicketsRepo;
    }

    /**
     * Show ticket search
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function ticket()
    {
        $data['title'] = _i('Ticket search');
        return view('back.iq-soft.ticket', $data);
    }

    /**
     * Get ticket data
     *
     * @param Request $request
     * @param int $ticket Ticket ID
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function ticketData(Request $request, IQSoftTicketsRepo $iqSoftTicketsRepo)
    {
        try {
            $ticket = $request->ticket;

            if (!empty($ticket)) {
                $whitelabel = Configurations::getWhitelabel();
                $currency = session('currency');
                $ticketData = $iqSoftTicketsRepo->findByProviderTransaction($whitelabel, $currency, $ticket);

                if (!is_null($ticketData)) {
                    $url = "$this->serverUrl/ticket";
                    $requestData = [
                        'language' => LaravelGettext::getLocale(),
                        'ticket' => $ticket
                    ];
                    $curl = Curl::to($url)
                        ->withData($requestData)
                        ->get();
                    $response = json_decode($curl);

                    if ($response->status == Status::$ok) {
                        $ticket = $response->data->ticket;
                        $this->iqSoftTicketsCollection->ticketDetails($ticket, $ticketData);
                        $selections =  $this->iqSoftTicketsCollection->ticketSelections($ticket);
                        $data = [
                            'ticket' => [$ticket],
                            'selections' => $selections
                        ];
                        return Utils::successResponse($data);
                    } else {
                        return Utils::failedResponse();
                    }
                } else {
                    $data = [
                        'title' => _i('Ticket not found'),
                        'message' => _i('The ticket entered does not exist'),
                        'close' => _i('Close')
                    ];
                    return Utils::errorResponse(Codes::$not_found, $data);
                }
            } else {
                $data = [
                    'ticket' => [],
                    'selections' => []
                ];
                return Utils::successResponse($data);
            }

        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex]);
            return Utils::failedResponse();
        }
    }

    /**
     * Get ticket info
     *
     * @param int $ticket Ticket ID
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function ticketInfo($ticket)
    {
        try {
            $url = "$this->serverUrl/ticket";
            $requestData = [
                'language' => LaravelGettext::getLocale(),
                'ticket' => $ticket
            ];
            $curl = Curl::to($url)
                ->withData($requestData)
                ->get();
            $response = json_decode($curl);
            if ($response->status == Status::$ok) {
                $ticket = $response->data->ticket;
                $ticketData = $this->iqSoftTicketsCollection->formatTicket($ticket);
                $data = [
                    'ticket' => $ticketData
                ];
                return Utils::successResponse($data);
            } else {
                return Utils::failedResponse();
            }

        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex]);
            return Utils::failedResponse();
        }
    }

    /**
     * Show ticket search
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function totals()
    {
        $data['whitelabels'] = $this->whitelabelsRepo->all();
        $data['currency_client'] = $this->currenciesRepo->all();
        $data['title'] = _i('Ticket search');
        return view('back.reports.iq-soft.totals', $data);
    }

    /**
     * Get totals data
     *
     * @param Request $request
     * @param null|string $startDate Start date to filter
     * @param null|string $endDate End date to filter
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function totalsData(Request $request, $startDate = null, $endDate = null)
    {
        try {
            $whitelabel = $request->whitelabel;
            $currency = $request->currency;
            if (!is_null($whitelabel) && !is_null($currency)) {
                $startDate = Utils::startOfDayUtc($startDate);
                $endDate = Utils::endOfDayUtc($endDate);
                $ticketsData = $this->iqSoftTicketsRepo->getByUserDates($whitelabel, $currency, $startDate, $endDate);
                if (!is_null($ticketsData)) {
                    $tickets = $this->iqSoftTicketsCollection->formatTotals($ticketsData);
                    $data = [
                        'totals' => $tickets
                    ];
                } else {
                    $data = [
                        'totals' => []
                    ];
                }
            } else {
                $data = [
                    'totals' => []
                ];
            }
            return Utils::successResponse($data);

        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex, 'request' => $request->all(), 'start_date' => $startDate, 'end_date' => $endDate]);
            return Utils::failedResponse();
        }
    }

}
