<?php


namespace App\Invoices\Collections;

use App\Invoices\Enums\CurrencySymbols;
use Carbon\Carbon;
use Dotworkers\Configurations\Configurations;
use Dotworkers\Configurations\Enums\ProviderTypes;
use Dotworkers\Configurations\Enums\Providers;
use Ixudra\Curl\Facades\Curl;

class InvoicesCollection
{
    /**
     * Format invoices
     *
     * @param array $totals totals data
     * @param string $convert Currency for convert to
     * @param string $currency Currency for convert from
     * @param string $startDate Start date to filter
     * @param string $endDate End date to filter
     */
    public function invoicesTotalsNew($totals, $convert, $currency, $startDate, $endDate)
    {
        $timezone = session('timezone');
        $today = Carbon::now();
        $exchangeRates = new \stdClass();
        $currency = $currency == 'VES' ? 'VEF' : $currency;
        $convert = $convert == 'VES' ? 'VEF' : $convert;
        $sub_total = 0;
        $total_convert = 0;
        $totalData = [];
        $symbol = '';

        if (!is_null($convert)) {
            $now = $today->copy()->format('Y-m-d');
            $baseURL = env('FIXER_URL') . '?access_key=' . env('FIXER_API_KEY') . "&start_date=$now&end_date=$now";

            if (is_null($currency)) {
                $whilabelCurrencies = Configurations::getCurrencies();
                foreach ($whilabelCurrencies as $whilabelCurrency) {
                    $url = $baseURL . "&base=$currency&symbols=$convert";
                    $curl = Curl::to($url)->get();
                    $exchangeResponse = json_decode($curl);
                    $whilabelCurrency = $whilabelCurrency == 'VES' ? 'VEF' : $whilabelCurrency;
                    $exchangeRates->$whilabelCurrency = $exchangeResponse;
                }
            } else {
                $url = $baseURL . "&base=$currency&symbols=$convert";
                $curl = Curl::to($url)->get();
                $exchangeResponse = json_decode($curl);
                $exchangeRates->$currency = $exchangeResponse;
            }
        }

        foreach ($totals as $total) {
            $symbol = CurrencySymbols::getSymbol($total->currency_iso);
            if (!is_null($convert)) {
                $totalsCurrencyIso = $total->currency_iso == 'VES' ? 'VEF' : $total->currency_iso;
                $exchangeRate = $exchangeRates->{$totalsCurrencyIso}->rates->{$now}->{$convert};
                $played = is_null($exchangeRate) ? 0 : $total->played * $exchangeRate;
                $won = is_null($exchangeRate) ? 0 : $total->won * $exchangeRate;
                $profit = is_null($exchangeRate) ? 0 : $total->profit * $exchangeRate;
                $totalProvider = $played - $won;
                $total_convert += $totalProvider;
            } else {
                $played = $total->played;
                $won = $total->won;
                $profit = $total->profit;
                $totalProvider = $played - $won;
                $total_convert  = 0;
            }

            $rtp = ($played == 0) ? 0 : ($won / $played) * 100;
            $percentage = is_null($total->percentage) ? 0 : $total->percentage;
            $payment = ($profit == 0 || $percentage == 0) ? 0 : ($profit * $percentage);

            if ($totalProvider > 0) {
                $sub_total += $totalProvider;
                $total->total_provider = $symbol.' '.number_format($totalProvider, 2);
            } else {
                $total->total_provider = $symbol.' '.number_format(0, 2);
            }

            $total->played = $symbol.' '.number_format($played, 2);
            $total->won = $symbol.' '.number_format($won, 2);
            $total->profit =  $symbol.' '.number_format($profit, 2);
            $total->rtp = number_format($rtp, 2) . '%';
            $total->payment = number_format($payment, 2);
            $total->provider = Providers::getName($total->provider_id);
            $total->provider_type = ProviderTypes::getName($total->provider_type_id);
            $total->percentage = $percentage;
            $totalData[] = $total;
        }

        return $data[] = [
            'totals' => $totalData,
            'sub_total' =>  $symbol.' '.number_format($sub_total,2),
            'total_convert' => number_format($total_convert, 2),
        ];
    }
}
