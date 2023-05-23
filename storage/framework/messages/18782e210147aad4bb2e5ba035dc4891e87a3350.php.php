<?php

namespace App\Http\Controllers;

use App\Altenar\Collections\AltenarTicketsCollection;
use App\Altenar\Repositories\AltenarTicketsRepo;
use Dotworkers\Configurations\Configurations;
use Dotworkers\Configurations\Enums\Codes;
use Dotworkers\Configurations\Enums\Status;
use Dotworkers\Configurations\Utils;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Ixudra\Curl\Facades\Curl;
use Symfony\Component\HttpFoundation\Response;
use Xinax\LaravelGettext\Facades\LaravelGettext;

/**
 * Class AltenarController
 *
 * This class allows managing Altenar requests
 *
 * @package App\Http\Controllers
 * @author  Miguel Sira
 */
class AltenarController extends Controller
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
     * @var AltenarTicketsCollection
     */
    private $altenarTicketsCollection;

    /**
     * AltenarController constructor
     *
     * @param AltenarTicketsCollection $altenarTicketsCollection
     */
    public function __construct(AltenarTicketsCollection $altenarTicketsCollection)
    {
        $this->serverUrl = env('ALTENAR_SERVER') . '/api';
        $this->altenarTicketsCollection = $altenarTicketsCollection;
    }

    /**
     * Show ticket search
     *
     * @return Application|Factory|View
     */
    public function ticket()
    {
        $data['title'] = _i('Ticket search');

        return view('back.altenar.ticket', $data);
    }

    /**
     * Get ticket data
     *
     * @param Request $request
     * @param AltenarTicketsRepo $altenarTicketsRepo
     * @return Response
     */
    public function ticketData(Request $request, AltenarTicketsRepo $altenarTicketsRepo): Response
    {
        try {
            $ticket = $request->get('ticket');

            if (!empty($ticket)) {
                $whitelabel = Configurations::getWhitelabel();
                $currency = session('currency');
                $ticketData = $altenarTicketsRepo->findByProviderTransaction($whitelabel, $currency, $ticket);
                $ticketDataReference = $altenarTicketsRepo->findByReference($whitelabel, $currency, $ticket);

                if (!is_null($ticketData) || !is_null($ticketDataReference)) {
                    $url = "$this->serverUrl/ticket/" . $ticket;
                    $curl = Curl::to($url)
                        ->get();
                    $response = json_decode($curl);

                    if ($response->status == Status::$ok) {
                        $ticket = $response->data->Bet;
                        if (!is_null($ticketData)) {
                            $this->altenarTicketsCollection->ticketDetails($ticket, $ticketData, false);
                        } else {
                            $this->altenarTicketsCollection->ticketDetails($ticket, $ticketDataReference, true);
                        }
                        $selections = $this->altenarTicketsCollection->ticketSelections($ticket);
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
        } catch (Exception $ex) {
            Log::error(__METHOD__, ['exception' => $ex]);

            return Utils::failedResponse();
        }
    }

    /**
     * Get ticket info
     *
     * @param int $ticket Ticket ID
     * @return Response
     */
    public function ticketInfo(int $ticket): Response
    {
        try {
            $url = "$this->serverUrl/ticket/" . $ticket;
            $curl = Curl::to($url)
                ->get();
            $response = json_decode($curl);

            if ($response->status == Status::$ok) {
                $ticket = $response->data->ticket;
                $ticketData = $this->altenarTicketsCollection->formatTicket($ticket);
                $data = [
                    'ticket' => $ticketData
                ];
                return Utils::successResponse($data);
            } else {
                return Utils::failedResponse();
            }
        } catch (Exception $ex) {
            Log::error(__METHOD__, ['exception' => $ex]);

            return Utils::failedResponse();
        }
    }
}
