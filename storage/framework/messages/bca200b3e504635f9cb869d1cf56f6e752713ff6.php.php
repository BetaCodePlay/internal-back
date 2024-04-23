<?php


namespace App\Reports\Collections;

use App\Core\Collections\CurrenciesCollection;
use App\Core\Core;
use App\Audits\Repositories\AuditsRepo;
use App\Core\Entities\Provider;
use App\Core\Repositories\CurrenciesRepo;
use App\Users\Repositories\UsersRepo;
use App\Core\Repositories\ProvidersRepo;
use Aws\MachineLearning\MachineLearningClient;
use Carbon\Carbon;
use Dotworkers\Configurations\Configurations;
use Dotworkers\Configurations\Enums\Providers;
use Dotworkers\Configurations\Enums\ProviderTypes;
use Dotworkers\Store\Store;
use Dotworkers\Wallet\Wallet;
use Illuminate\Support\Collection;
use Ixudra\Curl\Facades\Curl;

/**
 * Class ReportsCollection
 *
 * This class allows to format reports data
 *
 * @package App\Reports\Collections
 * @author Damelys Espinoza
 */
class ReportsCollection
{
    /**
     * Format Hour Closure Data
     *
     * @param array $transactions Hour Closure data
     * @return array
     */
    public function formatHourClosureData($transactions)
    {
        $player = 0;
        $won = 0;
        $profit = 0;
        $rtp = 0;
        $formatData = [];
        $format = [];
        if ($transactions !== null) {
            foreach ($transactions as $transaction) {
                foreach ($transaction as $total) {
                    $data = [
                        'whitelabel_id' => $total->whitelabel_id,
                        'provider_id' => $total->provider_id,
                        'played' => number_format($total->played, 2),
                        'won' => number_format($total->won, 2),
                        'profit' => number_format($total->profit, 2),
                        'rtp' => number_format($total->rtp, 2)
                    ];
                    $formatData[] = $data;
                    $player += $total->played;
                    $won += $total->won;
                    $profit += $total->profit;
                    $rtp += $total->rtp;
                }
            }
            $format['data'] = $formatData;
            $format['totals'] = [
                'played' => number_format($player, 2),
                'won' => number_format($won, 2),
                'profit' => number_format($profit, 2),
                'rtp' => number_format($rtp, 2)
            ];
        }
        return $format;
    }

    /**
     *  Format payment method
     *
     * @param array $paymentMethods Payment method data
     */
    public function formatPaymentMethod($paymentMethods)
    {
        $paymentMethodsIds = [];
        $providersRepo = new ProvidersRepo();
        foreach ($paymentMethods as $paymentMethod) {
            $paymentMethodsIds[] = $paymentMethod->payment_method_id;
        }
        $uniquePaymentMethodsData = collect($paymentMethodsIds)->unique()->values()->all();
        $paymentMethodsData = $providersRepo->getByBeyPayIDs($uniquePaymentMethodsData);
        foreach ($paymentMethodsData as $provider) {
            $provider->name = Providers::getName($provider->id);
        }
        $paymentMethodsData[] = ['id' => Providers::$dotworkers, 'name' => _i('Manual transactions')];
        $paymentMethodsData[] = ['id' => Providers::$agents, 'name' => Providers::getName(Providers::$agents)];
        return $paymentMethodsData;
    }

    /**
     * Get games totals
     *
     * @param array $totals Totals data
     * @return array
     */
    public function gamesTotals($totals, $nowTotals)
    {
        $gamesData = [];
        $generalTotals = [];
        $played = 0;
        $won = 0;
        $profit = 0;

        if (count($totals) > 0) {
            foreach ($totals as $total) {
                $gamePlayed = 0;
                $gameWon = 0;
                $gameProfit = 0;
                $gameBets = 0;

                if (!is_null($nowTotals)) {
                    if (isset($nowTotals['debit'])) {
                        foreach ($nowTotals['debit'] as $key => $debit) {
                            foreach ($nowTotals['credit'] as $credit) {
                                if ($debit->id == $credit->id) {
                                    $gamePlayed += $debit->total;
                                    $gameWon += $credit->total;
                                    $gameProfit += $debit->total - $credit->total;
                                    $gameBets += $debit->bets;
                                    unset($nowTotals['debit'][$key]);
                                }
                            }
                        }
                        foreach ($nowTotals['debit'] as $debitItem) {
                            $gamePlayed += $debitItem->total;
                            $gameProfit += $debitItem->total;
                            $gameBets += $debitItem->bets;
                        }
                    }
                }

                $gamePlayed += $total->played;
                $gameWon += $total->won;
                $gameProfit += $total->profit;
                $gameBets += $total->bets;

                $played += $gamePlayed;
                $won += $gameWon;
                $profit += $gameProfit;
                $average = $gamePlayed / $gameBets;
                $rtp = ($gamePlayed == 0) ? 0 : ($gameWon / $gamePlayed) * 100;
                $platform = $total->mobile ? _i('Mobile') : _i('Desktop');

                $gamesData[] = [
                    'name' => $total->name,
                    'platform' => $platform,
                    'bets' => $total->bets,
                    'average' => number_format($average, 2),
                    'played' => number_format($gamePlayed, 2),
                    'won' => number_format($gameWon, 2),
                    'profit' => number_format($gameProfit, 2),
                    'played_original' => $gamePlayed,
                    'won_original' => $gameWon,
                    'profit_original' => $gameProfit,
                    'rtp' => number_format($rtp, 2) . '%'
                ];
            }
        } else {
            if (!is_null($nowTotals)) {
                if (isset($nowTotals['debit'])) {
                    foreach ($nowTotals['debit'] as $debitKey => $debit) {
                        foreach ($nowTotals['credit'] as $creditKey => $credit) {
                            if ($debit->id == $credit->id) {
                                $gameProfit = $debit->total - $credit->total;
                                $played += $debit->total;
                                $won += $credit->total;
                                $profit += $gameProfit;
                                $average = $debit->total / $debit->bets;
                                $rtp = ($debit->total == 0) ? 0 : ($credit->total / $debit->total) * 100;
                                $platform = $debit->mobile ? _i('Mobile') : _i('Desktop');

                                $gamesData[] = [
                                    'name' => $debit->name,
                                    'platform' => $platform,
                                    'bets' => $debit->bets,
                                    'average' => number_format($average, 2),
                                    'played' => number_format($debit->total, 2),
                                    'won' => number_format($credit->total, 2),
                                    'profit' => number_format($gameProfit, 2),
                                    'played_original' => $debit->total,
                                    'won_original' => $credit->total,
                                    'profit_original' => $gameProfit,
                                    'rtp' => number_format($rtp, 2) . '%'
                                ];
                                unset($nowTotals['debit'][$debitKey]);
                                unset($nowTotals['credit'][$creditKey]);
                            }
                        }
                    }
                    foreach ($nowTotals['debit'] as $debitItem) {
                        $played += $debitItem->total;
                        $profit += $debitItem->total;
                        $average = $debitItem->total / $debitItem->bets;
                        $platform = $debitItem->mobile ? _i('Mobile') : _i('Desktop');

                        $gamesData[] = [
                            'name' => $debitItem->name,
                            'platform' => $platform,
                            'bets' => $debitItem->bets,
                            'average' => number_format($average, 2),
                            'played' => number_format($debitItem->total, 2),
                            'won' => number_format(0, 2),
                            'profit' => number_format($debitItem->total, 2),
                            'played_original' => $debitItem->total,
                            'won_original' => 0,
                            'profit_original' => $debitItem->total,
                            'rtp' => number_format(0, 2) . '%'
                        ];
                    }

                    foreach ($nowTotals['credit'] as $creditItem) {
                        $profit -= $creditItem->total;
                        $average = 0;

                        $gamesData[] = [
                            'name' => $creditItem->name,
                            'mobile' => $creditItem->mobile,
                            'bets' => 0,
                            'average' => number_format($average, 2),
                            'played' => number_format(0, 2),
                            'won' => number_format($creditItem->total, 2),
                            'profit' => number_format(-$creditItem->total, 2),
                            'played_original' => 0,
                            'won_original' => $creditItem->total,
                            'profit_original' => -$creditItem->total,
                            'rtp' => number_format(100, 2) . '%'
                        ];
                    }
                }
            }
        }

        $totalRTP = ($played == 0) ? 0 : ($won / $played) * 100;
        $generalTotals['played'] = number_format($played, 2);
        $generalTotals['won'] = number_format($won, 2);
        $generalTotals['profit'] = number_format($profit, 2);
        $generalTotals['rtp'] = number_format($totalRTP, 2) . '%';

        return [
            'games' => $gamesData,
            'totals' => $generalTotals
        ];
    }

    /**
     * Format most played games
     *
     * @param array $games Games data
     */
    public function mostPlayedGames($games)
    {
        foreach ($games as $game) {
            $game->platform = $game->mobile ? _i('Mobile') : _i('Desktop');
        }
    }

    /**
     * Format most played by providers
     *
     * @param array $games Games data
     */
    public function mostPlayedByProviders($games)
    {
        $usersRepo = new UsersRepo();
        $providerRepo = new ProvidersRepo();
        foreach ($games as $game) {
            $userData = $usersRepo->find($game->user_id);
            $providerData = $providerRepo->find($game->provider_id);
            $game->user = sprintf(
                '<a href="%s" class="btn u-btn-3d u-btn-primary btn-sm" target="_blank">%s</a>',
                route('users.details', [$game->user_id]),
                $game->user_id
            );
            $game->provider = $providerData->name;
            $game->email = $userData->email;
        }
    }

    /**
     * Format products totals data
     *
     * @param array $totals Products totals
     * @param array $users Products users
     * @param array $latestUsers Last products users
     * @param array $bets Products bets
     * @param array $latestBets Last products bets
     * @param string $convert Currency to convert
     * @param string $currency Currency filter
     * @param string $startDate Start date to filter
     * @param string $endDate End date to filter
     * @return array
     */
    public function productsTotals($totals, $users, $latestUsers, $bets, $latestBets, $convert, $currency, $startDate, $endDate): array
    {
        $totalPlayed = 0;
        $totalWon = 0;
        $totalProfit = 0;
        $totalRtp = 0;
        $totalsData = collect();
        $exchangeRates = new \stdClass();
        $currency = $currency == 'VES' ? 'VEF' : $currency;
        $convert = $convert == 'VES' ? 'VEF' : $convert;

        if (!is_null($convert)) {
            $startDate = Carbon::createFromFormat('Y-m-d H:i:s', $startDate)->format('Y-m-d');
            $endDate = Carbon::createFromFormat('Y-m-d H:i:s', $endDate)->format('Y-m-d');
            $baseURL = env('FIXER_TIME_SERIES_URL') . '?access_key=' . env('FIXER_API_KEY') . "&start_date=$startDate&end_date=$endDate";

            if (is_null($currency)) {
                $whilabelCurrencies = Configurations::getCurrencies();
                foreach ($whilabelCurrencies as $whilabelCurrency) {
                    $url = $baseURL . "&base=$whilabelCurrency&symbols=$convert";
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
            $totalDate = Carbon::createFromFormat('Y-m-d H:i:s', $total->start_date)->format('Y-m-d');

            if ($totalsData->where('provider_id', $total->provider_id)->count() == 0) {
                $totalObject = new \stdClass();
                $totalObject->provider_id = $total->provider_id;
                $totalObject->provider_type_id = $total->provider_type_id;
                $totalObject->currency_iso = $total->currency_iso;
                $totalObject->bets = $total->bets;
                $totalObject->users = 0;
                $totalObject->latest_users = 0;
                $totalObject->latest_bets = 0;
                $totalObject->played = 0;
                $totalObject->won = 0;
                $totalObject->profit = 0;
                $totalsData->push($totalObject);
            }

            if (count($totalsData) > 0) {
                foreach ($totalsData as $totalData) {
                    if ($totalData->provider_id == $total->provider_id) {
                        if (!is_null($convert)) {
                            $totalCurrencyIso = $total->currency_iso == 'VES' ? 'VEF' : $total->currency_iso;
                            $exchangeRate = $exchangeRates->{$totalCurrencyIso}->rates->{$totalDate}->{$convert};
                            $played = is_null($exchangeRate) ? 0 : $total->played * $exchangeRate;
                            $won = is_null($exchangeRate) ? 0 : $total->won * $exchangeRate;
                            $profit = is_null($exchangeRate) ? 0 : $total->profit * $exchangeRate;

                        } else {
                            $played = $total->played;
                            $won = $total->won;
                            $profit = $total->profit;
                        }

                        $totalPlayed += $played;
                        $totalWon += $won;
                        $totalProfit += $profit;

                        $totalData->played += $played;
                        $totalData->won += $won;
                        $totalData->profit += $profit;
                    }
                }
            }
        }

        foreach ($totalsData as $totalData) {
            foreach ($users as $key => $user) {
                if (is_null($currency)) {
                    if ($totalData->provider_id == $user->provider_id) {
                        $totalData->users++;
                        unset($users[$key]);
                    }
                } else {
                    if ($totalData->provider_id == $user->provider_id && $totalData->currency_iso == $user->currency_iso) {
                        $totalData->users++;
                        unset($users[$key]);
                    }
                }
            }

            foreach ($latestUsers as $key => $user) {
                if (is_null($currency)) {
                    if ($totalData->provider_id == $user->provider_id) {
                        $totalData->latest_users++;
                        unset($latestUsers[$key]);
                    }
                } else {
                    if ($totalData->provider_id == $user->provider_id && $totalData->currency_iso == $user->currency_iso) {
                        $totalData->latest_users++;
                        unset($latestUsers[$key]);
                    }
                }
            }

            foreach ($bets as $key => $bet) {
                if (is_null($currency)) {
                    if ($totalData->provider_id == $bet->provider_id) {
                        $totalData->bets += $bet->bets;
                        unset($bets[$key]);
                    }
                } else {
                    if ($totalData->provider_id == $bet->provider_id && $totalData->currency_iso == $bet->currency_iso) {
                        $totalData->bets += $bet->bets;
                        unset($bets[$key]);
                    }
                }
            }

            foreach ($latestBets as $key => $bet) {
                if (is_null($currency)) {
                    if ($totalData->provider_id == $bet->provider_id) {
                        $totalData->bets += $bet->bets;
                        unset($latestBets[$key]);
                    }
                } else {
                    if ($totalData->provider_id == $bet->provider_id && $totalData->currency_iso == $bet->currency_iso) {
                        $totalData->bets += $bet->bets;
                        unset($latestBets[$key]);
                    }
                }
            }

            $rtp = ($totalData->played == 0) ? 0 : ($totalData->won / $totalData->played) * 100;
            $hold = 100 - $rtp;
            $totalData->provider = Providers::getName($totalData->provider_id);
            $totalData->provider_type = ProviderTypes::getName($totalData->provider_type_id);
            $totalData->users = number_format($totalData->users, 0);
            $totalData->latest_users = number_format($totalData->latest_users, 0);
            $totalData->bets = number_format($totalData->bets, 0);
            $totalData->latest_bets = number_format($totalData->latest_bets, 0);
            $totalData->played = number_format($totalData->played, 2);
            $totalData->won = number_format($totalData->won, 2);
            $totalData->profit = number_format($totalData->profit, 2);
            $totalData->rtp = number_format($rtp, 2) . '%';
            $totalData->hold = number_format($hold, 2) . '%';
        }

        $totalRtp += ($totalPlayed == 0) ? 0 : ($totalWon / $totalPlayed) * 100;
        $totalHold = ($totalRtp == 0) ? 100 : (100 - $totalRtp);
        $generalTotals['played'] = number_format($totalPlayed, 2);
        $generalTotals['won'] = number_format($totalWon, 2);
        $generalTotals['profit'] = number_format($totalProfit, 2);
        $generalTotals['rtp'] = number_format($totalRtp, 2) . '%';
        $generalTotals['hold'] = number_format($totalHold, 2) . '%';

        $response = [
            'totals' => $totalsData,
            'totals_general' => $generalTotals
        ];
        return $response;
    }

    /**
     * Format products totals data
     *
     * @param array $totals Products totals
     * @param string $convert Currency to convert
     * @param string $currency Currency filter
     * @param float $vesRate VES rate
     * @param float $arsRate ARS rate
     * @return Collection
     */
    public function productsTotalsOverview($totals, $convert, $currency, $vesRate, $arsRate): Collection
    {
        $exchangeRates = new \stdClass();
        $currenciesRepo = new CurrenciesRepo();
        $currenciesCollection = new CurrenciesCollection();
        $totalsData = collect();
        $currency = $currency == 'VES' ? 'VEF' : $currency;
        $convert = $convert == 'VES' ? 'VEF' : $convert;

        if (!is_null($convert)) {
            $baseURL = env('FIXER_LATEST_URL') . '?access_key=' . env('FIXER_API_KEY');

            if (is_null($currency)) {
                $currencies = $currenciesRepo->all();
                $isos = $currenciesCollection->getOnlyIsos($currencies);
                $isos = implode(',', $isos);
                $url = $baseURL . "&base=$convert&symbols=$isos";
            } else {
                $url = $baseURL . "&base=$convert&symbols=$currency";
            }
            $curl = Curl::to($url)->get();
            $exchangeRates = json_decode($curl);
        }

        foreach ($totals as $total) {
            if ($totalsData->where('provider_id', $total->provider_id)->count() == 0) {
                $totalObject = new \stdClass();
                $totalObject->provider_id = $total->provider_id;
                $totalObject->provider_type_id = $total->provider_type_id;
                $totalObject->currency_iso = $total->currency_iso;
                $totalObject->played = 0;
                $totalObject->won = 0;
                $totalObject->profit = 0;
                $totalsData->push($totalObject);
            }

            if (count($totalsData) > 0) {
                foreach ($totalsData as $totalData) {
                    if ($totalData->provider_id == $total->provider_id) {
                        if (!is_null($convert)) {
                            $totalCurrency = $total->currency_iso == 'VES' ? 'VEF' : $total->currency_iso;
                            $exchangeRate = $exchangeRates->rates->{$totalCurrency} ?? 1;

                            if ($total->currency_iso == 'VES') {
                                $exchangeRate = $vesRate;
                            }

                            if ($total->currency_iso == 'ARS') {
                                $exchangeRate = $arsRate;
                            }

                            $played = is_null($exchangeRate) ? 0 : $total->played / $exchangeRate;
                            $won = is_null($exchangeRate) ? 0 : $total->won / $exchangeRate;
                            $profit = is_null($exchangeRate) ? 0 : $total->profit / $exchangeRate;

                        } else {
                            $played = $total->played;
                            $won = $total->won;
                            $profit = $total->profit;
                        }

                        $totalData->played += $played;
                        $totalData->won += $won;
                        $totalData->profit += $profit;
                    }
                }
            }
        }

        foreach ($totalsData as $totalData) {
            $rtp = ($totalData->played == 0) ? 0 : ($totalData->won / $totalData->played) * 100;
            $totalData->provider = Providers::getName($totalData->provider_id);
            $totalData->provider_type = ProviderTypes::getName($totalData->provider_type_id);
            $totalData->played = number_format($totalData->played, 2);
            $totalData->won = number_format($totalData->won, 2);
            $totalData->profit = number_format($totalData->profit, 2);
            $totalData->rtp = number_format($rtp, 2) . '%';
        }
        return $totalsData;
    }

    /**
     * Format products totals data by user
     *
     * @param array $totals Products totals
     * @return array
     */
    public function productsTotalsByUser($totals)
    {
        $totalPlayed = 0;
        $totalWon = 0;
        $totalProfit = 0;

        foreach ($totals as $total) {
            $total->latest_users = 0;
            $total->latest_bets = 0;

            $totalPlayed += $total->played;
            $totalWon += $total->won;
            $totalProfit += $total->profit;
            $rtp = ($total->played == 0) ? 0 : ($total->won / $total->played) * 100;
            $total->users = number_format($total->users, 0);
            $total->bets = number_format($total->bets, 0);
            $total->played = number_format($total->played, 2);
            $total->won = number_format($total->won, 2);
            $total->profit = number_format($total->profit, 2);
            $hold = 100 - $rtp;
            $total->rtp = number_format($rtp, 2) . '%';
            $total->hold = number_format($hold, 2) . '%';
            $total->provider = Providers::getName($total->provider_id);
            $total->provider_type = ProviderTypes::getName($total->provider_type_id);
            $total->currency = $total->currency_iso;
        }

        $response = [
            'totals' => $totals
        ];

        return $response;
    }

    /**
     * Format total logins
     *
     * @param $totals
     */
    public function referredUsers($users)
    {
        $timezone = session('timezone');
        foreach ($users as $user) {
            $user->user = sprintf(
                '<a href="%s" class="btn u-btn-3d u-btn-primary btn-sm" target="_blank">%s</a>',
                route('users.details', $user->id),
                $user->id
            );
            //$user->created_at = $user->created_at->setTimezone($timezone)->format('d-m-Y H:i:s');
        }
    }

    /**
     * Format registered users
     *
     * @param array $users Users data
     * @param int $deposits Deposits quantity
     * @return mixed
     */
    public function registeredUsers($users, $deposits, $options)
    {
        $timezone = session('timezone');
        $usersData = [];

        foreach ($users as $user) {
            if (!is_null($deposits)) {
                switch ($options) {
                    case '<=':
                    {
                        if ($user->deposits <= $deposits) {
                            $usersData[] = $user;
                        }
                        break;
                    }
                    case '>=':
                    {
                        if ($user->deposits >= $deposits) {
                            $usersData[] = $user;
                        }
                        break;
                    }
                    case '==':
                    {
                        if ($user->deposits == $deposits) {
                            $usersData[] = $user;
                        }
                        break;
                    }
                }
            } else {
                $usersData[] = $user;
            }

            $user->user = sprintf(
                '<a href="%s" class="btn u-btn-3d u-btn-primary btn-sm" target="_blank">%s</a>',
                route('users.details', $user->id),
                $user->id
            );
            if (!is_null($user->referral_code)) {
                $user->referral_code = sprintf(
                    '<a href="%s" class="btn u-btn-3d u-btn-primary btn-sm" target="_blank">%s</a>',
                    route('users.details', $user->referral_id),
                    $user->referral_code
                );
            }
            $user->full_name = "$user->first_name $user->last_name";
            $user->date = $user->created_at->setTimezone($timezone)->format('d-m-Y H:i:s');
            $statusClass = $user->status ? 'teal' : 'lightred';
            $statusText = $user->status ? _i('Active') : _i('Blocked');
            $user->status = sprintf(
                '<span class="u-label g-bg-%s g-rounded-20 g-px-15 g-mr-10 g-mb-15">%s</span>',
                $statusClass,
                $statusText
            );
        }
        return $usersData;
    }

    /**
     * Format total logins
     *
     * @param $totals
     */
    public function totalLogins($totals)
    {
        foreach ($totals as $total) {
            $total->user = sprintf(
                '<a href="%s" class="btn u-btn-3d u-btn-primary btn-sm" target="_blank">%s</a>',
                route('users.details', $total->user_id),
                $total->user_id
            );
        }
    }

    /**
     * Format users birthdays
     *
     * @param $users
     */
    public function usersBirthdays($users)
    {
        $timezone = session('timezone');
        foreach ($users as $user) {
            $user->user = sprintf(
                '<a href="%s" class="btn u-btn-3d u-btn-primary btn-sm" target="_blank">%s</a>',
                route('users.details', $user->id),
                $user->id
            );
            $user->user_name = $user->username;
            $user->birth_date = Carbon::createFromFormat('Y-m-d', $user->date)->format('d-m-Y');
        }
    }

    /**
     * Format users conversion
     *
     * @param array $users Users data
     * @return array
     */
    public function usersConversion($users)
    {
        $timezone = session('timezone');
        $completedProfiles = 0;
        $deposits = 0;
        $totalUsers = count($users);
        $totals = [];

        foreach ($users as $user) {
            $user->user = sprintf(
                '<a href="%s" class="btn u-btn-3d u-btn-primary btn-sm" target="_blank">%s</a>',
                route('users.details', $user->id),
                $user->id
            );

            if ($user->profile_completed) {
                $user->profile = "<label class='label label-sm label-success'>" . _i('Completed') . "</label>";
            } else {
                $user->profile = "<label class='label label-sm label-danger'>" . _i('Incomplete') . "</label>";
            }

            if ($user->profile_completed == true) {
                $completedProfiles++;
            }
            if ($user->deposits > 0) {
                $deposits++;
            }
            $user->created = Carbon::createFromFormat('Y-m-d H:i:s', $user->created_at)->setTimezone($timezone);
            $user->last_login_user = !is_null($user->last_login) ? $user->last_login->setTimezone($timezone)->format('d-m-Y H:i:s') : _i('No access');
        }

        $percentageProfiles = ($totalUsers == 0) ? 0 : round(($completedProfiles / $totalUsers) * 100, 2);
        $percentageDeposits = ($totalUsers == 0) ? 0 : round(($deposits / $totalUsers) * 100, 2);
        $totals['users'] = count($users);
        $totals['completed_profiles'] = $completedProfiles;
        $totals['percentage_profiles'] = "$percentageProfiles%";
        $totals['deposits'] = $deposits;
        $totals['percentage_deposits'] = "$percentageDeposits%";

        return [
            'users' => $users,
            'totals' => $totals
        ];
    }

    /**
     * Format users balances
     *
     * @param array $wallets Wallets data
     * @return array
     */
    public function usersBalances($wallets)
    {
        $totalBalances = 0;
        $totalLockedBalances = 0;
        $totalBonus = 0;
        $usersBalance = [];
        $auxUser = [];
        foreach ($wallets as $wallet) {
            $totalBalances += $wallet->balance;
            $totalLockedBalances += $wallet->balance_locked;
            $wallet->balance = number_format($wallet->balance, 2);
            $wallet->balance_locked = number_format($wallet->balance_locked, 2);

            $wallet->bonus_balance = number_format(0, 2);
            $wallet->user = sprintf(
                '<a href="%s" class="btn u-btn-3d u-btn-primary btn-sm" target="_blank">%s</a>',
                route('users.details', $wallet->user),
                $wallet->user
            );
            /*if (!in_array($wallet->user, $auxUser)) {
                $auxUser[] = $wallet->user;
                $userObject = new \stdClass();
                $totalBalances += $wallet->balance;
                $totalLockedBalances += $wallet->balance_locked;
                $user = $wallet->user;
                $wallet->user = sprintf(
                    '<a href="%s" class="btn u-btn-3d u-btn-primary btn-sm" target="_blank">%s</a>',
                    route('users.details', $wallet->user),
                    $wallet->user
                );
                $userObject->balance_locked = number_format($wallet->balance_locked, 2);
                $userObject->login = _i('No access');
                $userObject->username = $wallet->username;
                if($wallet->bonus){
                    $userObject->balance = '';
                    $totalBonus += $wallet->balance;
                    $userObject->bonus_balance = $wallet->balance;
                } else {
                    $userObject->balance = number_format($wallet->balance, 2);
                    $userObject->bonus_balance = 0;
                }
                $userObject->user = $wallet->user;
                $userObject->id = $user;
                $usersBalance[] = $userObject;
            } else {
                foreach ($usersBalance as $userBalance) {
                    if ($wallet->user == $userBalance->id) {
                        if($wallet->bonus){
                            $totalBonus += $wallet->balance;
                            $userBalance->bonus_balance += $wallet->balance;
                        } else {
                            $userBalance->balance = number_format($wallet->balance, 2);
                        }
                    }
                }
            }*/
        }

//        foreach($usersBalance as $userBalance){
//            $bonus = number_format($userBalance->bonus_balance, 2);
//            $userBalance->bonus_balance = $bonus;
//        }
//        return [
//            'wallets' => $usersBalance,
//            'total_balances' => number_format($totalBalances, 2),
//            'total_bonus_balances' => number_format($totalBonus, 2),
//            'total_locked_balances' => number_format($totalLockedBalances, 2)
//        ];
        return [
            'wallets' => $wallets,
            'total_balances' => number_format($totalBalances, 2),
            'total_locked_balances' => number_format($totalLockedBalances, 2)
        ];
    }

    /**
     * Format users bets
     *
     * @param array $users Users data
     * @return array
     */
    public function usersBets($users)
    {

        foreach ($users as $user) {
            $user->username = $user->username;
            $user->played = number_format($user->played, 2);
            $user->profit = number_format($user->profit, 2);
            $user->won = number_format($user->won, 2);
            $user->game = $user->name;
            $user->user = sprintf(
                '<a href="%s" class="btn u-btn-3d u-btn-primary btn-sm" target="_blank">%s</a>',
                route('users.details', $user->id),
                $user->id
            );
        }
        return [
            'users' => $users
        ];
    }

    /**
     * Format user totals
     *
     * @param array $totals Users totals
     * @return mixed
     */
    public function usersTotals($totals, $nowTotals = null)
    {
        $usersData = [];
        $generalTotals = [];
        $played = 0;
        $won = 0;
        $profit = 0;
        $currency = session('currency');

        if (count($totals) > 0) {
            foreach ($totals as $total) {
                $userPlayed = 0;
                $userWon = 0;
                $userProfit = 0;
                $userBets = 0;

                if (!is_null($nowTotals)) {
                    if (isset($nowTotals['debit'])) {
                        foreach ($nowTotals['debit'] as $key => $debit) {
                            foreach ($nowTotals['credit'] as $credit) {
                                if ($total->id == $debit->id && $debit->id == $credit->id) {
                                    $gameProfit = $debit->total - $credit->total;
                                    $userPlayed += $debit->total;
                                    $userWon += $credit->total;
                                    $userProfit += $gameProfit;
                                    $userBets += $debit->bets;
                                    unset($nowTotals['debit'][$key]);
                                }
                            }
                        }
                        foreach ($nowTotals['debit'] as $debitItem) {
                            if ($total->id == $debitItem->id) {
                                $userPlayed += $debitItem->total;
                                $userProfit += $debitItem->total;
                                $userBets += $debitItem->bets;
                            }
                        }
                    }
                }

                $userPlayed += $total->played;
                $userWon += $total->won;
                $userProfit += $total->profit;
                $userBets += $total->bets;

                $played += $userPlayed;
                $won += $userWon;
                $profit += $userProfit;
                $average = ($userBets == 0) ? $userPlayed : ($userPlayed / $userBets);
                $rtp = ($userPlayed == 0) ? 0 : ($userWon / $userPlayed) * 100;

                if (isset($total->id)) {
                    $user = sprintf(
                        '<a href="%s" class="btn u-btn-3d u-btn-primary btn-sm" target="_blank">%s</a>',
                        route('users.details', $total->id),
                        $total->id
                    );
                    $walletData = Wallet::getByClient($total->id, $currency, false);
                    $wallet = $walletData->data->wallet->id;
                } else {
                    $user = null;
                    $wallet = null;
                }

                $usersData[] = [
                    'user' => $user,
                    'wallet' => $wallet,
                    'username' => $total->username,
                    'bets' => $total->bets,
                    'average' => number_format($average, 2),
                    'played' => number_format($userPlayed, 2),
                    'won' => number_format($userWon, 2),
                    'profit' => number_format($userProfit, 2),
                    'played_original' => $userPlayed,
                    'won_original' => $userWon,
                    'profit_original' => $userProfit,
                    'rtp' => number_format($rtp, 2) . '%'
                ];
            }
        } else {
            if (!is_null($nowTotals)) {
                if (isset($nowTotals['debit'])) {
                    foreach ($nowTotals['debit'] as $debitKey => $debit) {
                        foreach ($nowTotals['credit'] as $creditKey => $credit) {
                            if ($debit->id == $credit->id) {
                                if (isset($nowTotals['reverse'])) {
                                    foreach ($nowTotals['reverse'] as $reverse) {
                                        if ($reverse->id == $credit->id) {
                                            $credit->total = $credit->total - $reverse->total;
                                            unset($nowTotals['reverse'][$debitKey]);
                                        }
                                    }
                                }

                                if (isset($nowTotals['return'])) {
                                    foreach ($nowTotals['return'] as $return) {
                                        if ($return->id == $debit->id) {
                                            $debit->total = $debit->total - $return->total;
                                            unset($nowTotals['return'][$debitKey]);
                                        }
                                    }
                                }

                                if (isset($nowTotals['cancel'])) {
                                    foreach ($nowTotals['cancel'] as $cancel) {
                                        if ($cancel->id == $debit->id) {
                                            $debit->total = $debit->total - $cancel->total;
                                            unset($nowTotals['cancel'][$debitKey]);
                                        }
                                    }
                                }

                                $userProfit = $debit->total - $credit->total;
                                $played += $debit->total;
                                $won += $credit->total;
                                $profit += $userProfit;
                                $average = $debit->total / $debit->bets;
                                $rtp = ($debit->total == 0) ? 0 : ($credit->total / $debit->total) * 100;

                                $user = sprintf(
                                    '<a href="%s" class="btn u-btn-3d u-btn-primary btn-sm" target="_blank">%s</a>',
                                    route('users.details', $debit->id),
                                    $debit->id
                                );

                                $walletData = Wallet::getByClient($debit->id, $currency, false);
                                $wallet = $walletData->data->wallet->id;

                                $usersData[] = [
                                    'user' => $user,
                                    'wallet' => $wallet,
                                    'username' => $debit->username,
                                    'bets' => $debit->bets,
                                    'average' => number_format($average, 2),
                                    'played' => number_format($debit->total, 2),
                                    'won' => number_format($credit->total, 2),
                                    'profit' => number_format($userProfit, 2),
                                    'played_original' => $debit->total,
                                    'won_original' => $credit->total,
                                    'profit_original' => $userProfit,
                                    'rtp' => number_format($rtp, 2) . '%'
                                ];
                                unset($nowTotals['debit'][$debitKey]);
                                unset($nowTotals['credit'][$creditKey]);
                            }
                        }
                    }
                    foreach ($nowTotals['debit'] as $debitItemKey => $debitItem) {

                        if (isset($nowTotals['return'])) {
                            foreach ($nowTotals['return'] as $return) {
                                if ($return->id == $debitItem->id) {
                                    $debitItem->total = $debitItem->total - $return->total;
                                    unset($nowTotals['return'][$debitItemKey]);
                                }
                            }
                        }

                        if (isset($nowTotals['cancel'])) {
                            foreach ($nowTotals['cancel'] as $cancel) {
                                if ($cancel->id == $debitItem->id) {
                                    $debitItem->total = $debitItem->total - $cancel->total;
                                    unset($nowTotals['cancel'][$debitItemKey]);
                                }
                            }
                        }

                        $played += $debitItem->total;
                        $profit += $debitItem->total;
                        $average = $debitItem->total / $debitItem->bets;

                        $user = sprintf(
                            '<a href="%s" class="btn u-btn-3d u-btn-primary btn-sm" target="_blank">%s</a>',
                            route('users.details', $debitItem->id),
                            $debitItem->id
                        );

                        $walletData = Wallet::getByClient($debitItem->id, $currency, false);
                        $wallet = $walletData->data->wallet->id;

                        $usersData[] = [
                            'user' => $user,
                            'wallet' => $wallet,
                            'username' => $debitItem->username,
                            'bets' => $debitItem->bets,
                            'average' => number_format($average, 2),
                            'played' => number_format($debitItem->total, 2),
                            'won' => number_format(0, 2),
                            'profit' => number_format($debitItem->total, 2),
                            'played_original' => $debitItem->total,
                            'won_original' => 0,
                            'profit_original' => $debitItem->total,
                            'rtp' => number_format(0, 2) . '%'
                        ];
                    }

                    foreach ($nowTotals['credit'] as $creditItemKey => $creditItem) {

                        if (isset($nowTotals['reverse'])) {
                            foreach ($nowTotals['reverse'] as $reverse) {
                                if ($reverse->id == $creditItem->id) {
                                    $creditItem->total = $creditItem->total - $reverse->total;
                                    unset($nowTotals['reverse'][$creditItemKey]);
                                }
                            }
                        }

                        $won += $creditItem->total;
                        $profit -= $creditItem->total;
                        $average = 0;

                        $user = sprintf(
                            '<a href="%s" class="btn u-btn-3d u-btn-primary btn-sm" target="_blank">%s</a>',
                            route('users.details', $creditItem->id),
                            $creditItem->id
                        );

                        $walletData = Wallet::getByClient($creditItem->id, $currency, false);
                        $wallet = $walletData->data->wallet->id;

                        $usersData[] = [
                            'user' => $user,
                            'wallet' => $wallet,
                            'username' => $creditItem->username,
                            'bets' => 0,
                            'average' => number_format($average, 2),
                            'played' => number_format(0, 2),
                            'won' => number_format($creditItem->total, 2),
                            'profit' => number_format(-$creditItem->total, 2),
                            'played_original' => 0,
                            'won_original' => $creditItem->total,
                            'profit_original' => -$creditItem->total,
                            'rtp' => number_format(100, 2) . '%'
                        ];
                    }
                }
            }
        }

        $totalRTP = ($played == 0) ? 0 : ($won / $played) * 100;
        $generalTotals['played'] = number_format($played, 2);
        $generalTotals['won'] = number_format($won, 2);
        $generalTotals['profit'] = number_format($profit, 2);
        $generalTotals['rtp'] = number_format($totalRTP, 2) . '%';

        return [
            'users' => $usersData,
            'totals' => $generalTotals
        ];
    }

    /**
     * Format whitelabels totals data
     *
     * @param array $totals Whitelabels totals
     */
    public function whitelabelsTotals($totals)
    {
        foreach ($totals as $total) {
            $rtp = ($total->played == 0) ? 0 : ($total->won / $total->played) * 100;
            $total->played = number_format($total->played, 2);
            $total->won = number_format($total->won, 2);
            $total->profit = number_format($total->profit, 2);
            $total->rtp = number_format($rtp, 2) . '%';
            $total->provider = Providers::getName($total->provider_id);
            $total->provider_type = ProviderTypes::getName($total->provider_type_id);
        }
    }

    /**
     * Format whitelabels totals data (newReport)
     *
     * @param array $totals Whitelabels totals
     * @param string $convert Currency for convert to
     * @param string $currency Currency for convert from
     * @param string $startDate Start date to filter
     * @param string $endDate End date to filter
     * @param int $provider Provider to filter
     */
    public function whitelabelsTotalsNew($totals, $convert, $currency, $startDate, $endDate, $provider)
    {
        $timezone = session('timezone');
        $today = Carbon::now();
        if(!is_null($startDate) || !is_null($endDate)) {
            $startDate = Carbon::createFromFormat('Y-m-d H:i:s', $startDate)->format('Y-m-d');
            $endDate = Carbon::createFromFormat('Y-m-d H:i:s', $endDate)->format('Y-m-d');
        }
        $exchangeRates = new \stdClass();
        $currency = $currency == 'VES' ? 'VEF' : $currency;
        $convert = $convert == 'VES' ? 'VEF' : $convert;

        if (!is_null($convert)) {
            $now = $today->copy()->format('Y-m-d');
            $baseURL = env('FIXER_TIME_SERIES_URL') . '?access_key=' . env('FIXER_API_KEY') . "&start_date=$now&end_date=$now";

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
            if (!is_null($convert)) {
                $totalsCurrencyIso = $total->currency_iso == 'VES' ? 'VEF' : $total->currency_iso;
                $exchangeRate = $exchangeRates->{$totalsCurrencyIso}->rates->{$now}->{$convert};
                $played = is_null($exchangeRate) ? 0 : $total->played * $exchangeRate;
                $won = is_null($exchangeRate) ? 0 : $total->won * $exchangeRate;
                $profit = is_null($exchangeRate) ? 0 : $total->profit * $exchangeRate;
            } else {
                $played = $total->played;
                $won = $total->won;
                $profit = $total->profit;
            }

            $rtp = ($played == 0) ? 0 : ($won / $played) * 100;
            $percentage = is_null($total->percentage) ? 0 : $total->percentage;
            $payment = ($profit == 0 || $percentage == 0) ? 0 : ($profit * $percentage);
            $total->played = number_format($played, 2);
            $total->won = number_format($won, 2);
            $total->profit = number_format($profit, 2);
            $total->rtp = number_format($rtp, 2) . '%';
            $total->payment = number_format($payment, 2);
            $total->provider = Providers::getName($total->provider_id);
            $total->provider_type = ProviderTypes::getName($total->provider_type_id);
            $total->percentage = number_format($percentage, 2);
            //$total->actions = sprintf(
            //    '<a href="%s" class="btn u-btn-3d btn-sm u-btn-bluegray mr-2" target="_blank">%s</a>',
            //    route('invoices.invoice-data', [$startDate, $endDate, $total->whitelabel_id, $provider, $currency, $convert]),
            //    _i('Invoices'),
            //);
        }
    }

    /**
     * Format whitelabels active and provider
     *
     * @param array $whitelabels Whitelabels data
     */
    public function whitelabelsAndProviders($whitelabels)
    {
        foreach ($whitelabels as $whitelabel){
            $whitelabel->actions = sprintf(
                '<button class="btn u-btn-3d btn-sm u-btn-bluegray mr-2" data-toggle="modal" data-target="#update-percentage" data-credential="%s" data-currency="%s" data-percentage="%s" data-provider="%s"><i class="hs-admin-pencil"></i> %s</button>',
                $whitelabel->client_id,
                $whitelabel->currency_iso,
                $whitelabel->percentage,
                $whitelabel->provider,
                _i('Edit')
            );
            $whitelabel->provider = Providers::getName($whitelabel->provider);
            $whitelabel->percentage = number_format($whitelabel->percentage, 2) . ' %';
        }
    }
}
