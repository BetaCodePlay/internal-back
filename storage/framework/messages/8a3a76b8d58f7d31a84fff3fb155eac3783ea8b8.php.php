<?php

namespace App\Reports\Commands;

use App\Core\Repositories\CoreRepo;
use App\Core\Repositories\ProvidersRepo;
use App\Reports\Repositories\ClosuresGamesTotalsRepo;
use App\Whitelabels\Enums\Status;
use App\Whitelabels\Repositories\WhitelabelsRepo;
use Carbon\Carbon;
use Dotworkers\Configurations\Configurations;
use Dotworkers\Configurations\Enums\ProviderTypes;
use Dotworkers\Configurations\Utils;
use Illuminate\Console\Command;

class GamesTotals extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'closure:games-totals';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Games totals closure';

    public function handle(WhitelabelsRepo $whitelabelsRepo, ProvidersRepo $providersRepo, CoreRepo $coreRepo, ClosuresGamesTotalsRepo $closuresGamesTotalsRepo)
    {
        $whitelabels = $whitelabelsRepo->getByStatus([Status::$active, Status::$suspended, Status::$whitelabel_maintenance, Status::$whitelabel_dotpanel_maintenance]);
        $providers = $providersRepo->getByTypes([ProviderTypes::$casino, ProviderTypes::$live_casino, ProviderTypes::$virtual, ProviderTypes::$sportbook, ProviderTypes::$racebook, ProviderTypes::$live_games, ProviderTypes::$poker]);
        $today = Carbon::now();
        $startDate = $today->copy()->subHour()->startOfHour();
        $endDate = $today->copy()->subHour()->endOfHour();

        foreach ($whitelabels as $whitelabel) {
            $currencies = Configurations::getCurrenciesByWhitelabel($whitelabel->id);

            foreach ($providers as $provider) {
                if (!is_null($provider->tickets_table) && !empty($provider->tickets_table) && $provider->games_table) {
                    foreach ($currencies as $currency) {
                        $gamesTotals = $coreRepo->getGamesTotalsClosure($whitelabel->id, $startDate, $endDate, $currency, $provider->tickets_table, $provider->games_table);

                        if (count((array)$gamesTotals['debit']) > 0) {
                            foreach ($gamesTotals['debit'] as $key => $debit) {
                                foreach ($gamesTotals['credit'] as $credit) {
                                    if ($debit->id == $credit->id) {
                                        $gameProfit = $debit->total - $credit->total;
                                        $rtp = ($debit->total == 0) ? 0 : ($credit->total / $debit->total) * 100;
                                        $closureData = [
                                            'game_id' => $debit->id,
                                            'game_name' => $debit->name,
                                            'mobile' => $debit->mobile,
                                            'played' => number_format($debit->total, 2, '.', ''),
                                            'won' => number_format($credit->total, 2, '.', ''),
                                            'profit' => number_format($gameProfit, 2, '.', ''),
                                            'rtp' => number_format($rtp, 2, '.', ''),
                                            'bets' => $debit->bets,
                                            'start_date' => $startDate,
                                            'end_date' => $endDate,
                                            'currency_iso' => $currency,
                                            'whitelabel_id' => $whitelabel->id,
                                            'provider_id' => $provider->id
                                        ];
                                        $closuresGamesTotalsRepo->store($closureData);
                                        unset($gamesTotals['debit'][$key]);
                                    }
                                }
                            }
                            foreach ($gamesTotals['debit'] as $debitItem) {
                                $closureData = [
                                    'game_id' => $debitItem->id,
                                    'game_name' => $debitItem->name,
                                    'mobile' => $debitItem->mobile,
                                    'played' => number_format($debitItem->total, 2, '.', ''),
                                    'won' => number_format(0, 2, '.', ''),
                                    'profit' => number_format($debitItem->total, 2, '.', ''),
                                    'rtp' => number_format(0, 2, '.', ''),
                                    'bets' => $debitItem->bets,
                                    'start_date' => $startDate,
                                    'end_date' => $endDate,
                                    'currency_iso' => $currency,
                                    'whitelabel_id' => $whitelabel->id,
                                    'provider_id' => $provider->id
                                ];
                                $closuresGamesTotalsRepo->store($closureData);
                            }
                        }
                    }
                }
            }
        }
    }
}
