<?php

namespace App\Http\Controllers;

use App\Core\Repositories\ProvidersRepo;
use App\Reports\Repositories\ClosuresUsersTotalsRepo;
use Carbon\Carbon;
use App\Invoices\Collections\InvoicesCollection;
use Dotworkers\Configurations\Configurations;
use Dotworkers\Configurations\Enums\PaymentMethods;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade as PDF;
use Dotworkers\Configurations\Utils;

/**
 * Class InvoicesController
 *
 * This class allows to manage invoices requests
 *
 * @package App\Http\Controllers
 * @author Carlos Hurtado
 */
class InvoicesController extends Controller
{
    /**
     * ClosuresUsersTotalsRepo
     *
     * @var ClosuresUsersTotalsRepo
     */
    private $closuresUsersTotalsRepo;

    /**
     * InvoicesCollections
     *
     * @var InvoicesCollection
     */
    private $invoicesCollection;

    /***
     * InvoicesController constructor.
     *
     * @param InvoicesCollection $invoicesCollection
     * @param ClosuresUsersTotalsRepo $closuresUsersTotalsRepo
     */
    public function __construct(InvoicesCollection $invoicesCollection, ClosuresUsersTotalsRepo $closuresUsersTotalsRepo)
    {
        $this->invoicesCollection = $invoicesCollection;
        $this->closuresUsersTotalsRepo = $closuresUsersTotalsRepo;
    }

    /**
     * Get invoice data
     *
     * @param string $startDate Start date
     * @param string $endDate End date
     * @param int $whitelabel Whitelabel ID
     * @return Response
     */
    public function invoiceData($startDate, $endDate, $whitelabel, Request $request)
    {
        try {
            $timezone = session('timezone');
            $data['month'] = Carbon::createFromFormat('Y-m-d', $startDate)->setTimezone($timezone)->isoFormat('MMMM');
            $startDate = Utils::startOfDayUtc($startDate);
            $endDate = Utils::endOfDayUtc($endDate);
            $currency = $request->currency;
            $convert = $request->convert;
            $provider = $request->provider;
            $totalsWl = $this->closuresUsersTotalsRepo->whitelabelsClosuresTotals($startDate, $endDate, $currency, $provider, $whitelabel);
            $totals = $this->closuresUsersTotalsRepo->getWhitelabelsTotal($totalsWl);
            $description = Configurations::getWhitelabelDescription($whitelabel);
            $totalData = $this->invoicesCollection->invoicesTotalsNew($totals,  $convert, $currency, $startDate, $endDate);
            $timezone = session('timezone');
            $data['date'] = Carbon::now()->setTimezone($timezone)->format('d/m/Y');
            $data['title'] = _i('Invoices Nº: #######');
            $data['table_title'] = [
                'description' => _i('Description'),
                'units' => _i('Units'),
                'total' => _i('Total'),
                'totals' => _i('Totals')
            ];

            $data['data_client'] = [
               'sub_title_client' => _i('Details client'),
               'messrs' => _i('Messrs:') .' '.$description,
               'attention' => _i('Attention: Carlos'),
               'email' =>  _i('Email: carlos.hurtado@dotworkers.net'),
               'phone' =>  _i('Phone: 4264331131'),
               'sub_title_details' => "{$description} - {$currency}",
            ];
            $invoices = [
                ['description' =>  $description, 'unit' => number_format(1,2),  'total' => 2000000],
            ];

            $sub_total = 0;
            foreach ($invoices as $invoice) {
                $total = $invoice['total'];
                $sub_total += $total;
                $invoice['total'] = number_format( $total, 2);
            }

            $data['invoices'] = [
                'invoices_service' => $invoices,
                'sub_total_title' => _i('Sub-total:'),
                'total_title' => _i('total:'),
                'sub_total' => number_format($sub_total, 2)
            ];

            $data['payment_methods_title'] = _i('Payment methods');
            $data['payment_methods']  = [
                ['payment_method_type' => PaymentMethods::$zelle, 'data' => ['name' => 'Valentina García', 'email' => 'garciavalen@gmail.com'], 'data_title' => ['email'=> _i('Email'), 'name'=> _i('Name')], 'currency' => 'USD', 'payment' => _i('Zelle')],
                ['payment_method_type' => PaymentMethods::$paypal, 'data' => ['name' => 'Miguel Sira', 'email' => 'miguelsira86@gmail.com'], 'data_title' => ['email'=> _i('Email'), 'name'=> _i('Name')], 'currency' => 'USD', 'payment' =>  _i('Paypal')],
                ['payment_method_type' => PaymentMethods::$uphold, 'data' => ['email' => 'finanzas@dotworkers.com'],
                    'data_title' => ['email'=> _i('Email')],'currency' => 'USD', 'payment' => _i('Bitcoin').' / '._i('USDT') .' / '. _i('Uphold'),],
                ['payment_method_type' => PaymentMethods::$wire_transfers, 'data' => ['bank'=> 'Exterior', 'account_number'=> '01150022641004498563', 'name'=> 'Dotworkers Venezuela', 'dni'=> 'J-407471414', 'email'=> 'finanzas@dotworkers.com', 'type_account'=> 'corriente'],
                    'data_title' => ['bank'=> _i('Bank'), 'account_number'=> _i('Account number'), 'name'=> _i('Name'), 'dni'=> _i('RIF'), 'email'=> _i('Email'), 'type_account'=>_i('Account')],'currency' => 'USD', 'payment' => _i('Bank Transfers in Bolivars')],
            ];
            $data['invoices_details'] = $totalData;
            $pdf = PDF::loadView('back.invoices.index', ['data'=> $data]);
            $pdf->setPaper('A4', 'portrait');
            return $pdf->stream();
        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex]);
            abort(500);
        }
    }
}
