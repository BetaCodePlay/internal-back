<?php

namespace App\Reports\Commands;

use App\Core\Repositories\CoreRepo;
use App\Core\Repositories\ProvidersRepo;
use App\Reports\Repositories\ClosuresUsersTotalsRepo;
use App\Whitelabels\Enums\Status;
use App\Whitelabels\Repositories\WhitelabelsRepo;
use Carbon\Carbon;
use Dotworkers\Configurations\Configurations;
use Dotworkers\Configurations\Enums\Providers;
use Dotworkers\Configurations\Enums\ProviderTypes;
use Illuminate\Console\Command;

class UsersTotals extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'closure:users-totals';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Users totals closure';

    public function handle(WhitelabelsRepo $whitelabelsRepo, ProvidersRepo $providersRepo, CoreRepo $coreRepo, ClosuresUsersTotalsRepo $closuresUsersTotalsRepo)
    {
        $whitelabels = $whitelabelsRepo->getByStatus([Status::$active, Status::$whitelabel_maintenance, Status::$whitelabel_dotpanel_maintenance]);
        $providers = $providersRepo->getByTypes([ProviderTypes::$casino, ProviderTypes::$live_casino, ProviderTypes::$virtual, ProviderTypes::$sportbook, ProviderTypes::$racebook, ProviderTypes::$live_games, ProviderTypes::$poker]);
        $today = Carbon::now();
        $startDate = $today->copy()->subHour()->startOfHour();
        $endDate = $today->copy()->subHour()->endOfHour();

        foreach ($whitelabels as $whitelabel) {
            $currencies = Configurations::getCurrenciesByWhitelabel($whitelabel->id);

            foreach ($providers as $provider) {
                if (!is_null($provider->tickets_table) && !empty($provider->tickets_table) && $provider->id != Providers::$dot_suite) {
                    try {
                        $providerID = null;
                        $dotsuiteProviders = [
                            Providers::$wnet_games,
                            Providers::$gamzix,
                            Providers::$smart_soft,
                            Providers::$swintt,
                            Providers::$sky_wind,
                            Providers::$rgs_gaming,
                            Providers::$booming_games_origin,
                            Providers::$net_ent,
                            Providers::$skillzz_gaming,
                            Providers::$dragon_gaming,
                            Providers::$eurasian_gaming,
                            Providers::$tangente,
                            Providers::$bet_by,
                            Providers::$fresh_deck,
                            Providers::$plexasoft,
                            Providers::$ruby_play,
                            Providers::$ezugi_games,
                            Providers::$betsoft_vg,
                            Providers::$tom_horn_vg,
                            Providers::$platipus_vg,
                            Providers::$booongo_vg,
                            Providers::$playson_vg,
                            Providers::$leap_vg,
                            Providers::$arrows_edge_vg,
                            Providers::$red_rake_vg,
                            Providers::$geet_bet,
                            Providers::$triple_cherry_original,
                            Providers::$evo_play,
                            Providers::$caleta_gaming,
                            Providers::$bgaming,
                            Providers::$event_bet,
                            Providers::$vivo_gaming_bingo,
                            Providers::$barbara_bang,
                            Providers::$beter,
                            Providers::$endorphina,
                            Providers::$pari_play,
                            Providers::$ainsworth,
                            Providers::$five_men_gaming,
                            Providers::$tgg_interactive,
                            Providers::$vibra,
                            Providers::$one_touch,
                            Providers::$one_touch,
                            Providers::$belatra,
                            Providers::$play_son,
                            Providers::$urgent_games,
                            Providers::$fbm_gaming,
                            Providers::$inbet,
                            Providers::$patagonia,
                            Providers::$pg_soft,
                            Providers::$booongo,
                            Providers::$game_art,
                            Providers::$booming_games,
                            Providers::$kiron_interactive,
                            Providers::$hacksaw_gaming,
                            Providers::$triple_cherry,
                            Providers::$espresso_games,
                            Providers::$betsoft,
                            Providers::$pragmatic_play,
                            Providers::$pragmatic_play_live_casino,
                            Providers::$pragmatic_play_virtual
                        ];

                        if (in_array($provider->id, $dotsuiteProviders)) {
                            $providerID = $provider->id;
                        }
                        $ticketProvider = is_null($providerID) ? $provider->id : $providerID;

                        foreach ($currencies as $currency) {
                            if (in_array($provider->id, $dotsuiteProviders)) {
                                $usersTotals = $coreRepo->getUsersTotalsClosureDotSuite($whitelabel->id, $startDate, $endDate, $currency, $provider->tickets_table, $provider->games_table, $providerID);;
                            } else {
                                $usersTotals = $coreRepo->getUsersTotalsClosure($whitelabel->id, $startDate, $endDate, $currency, $provider->tickets_table, $provider->games_table, $providerID);
                            }

                            if (count((array)$usersTotals['debit']) > 0) {
                                foreach ($usersTotals['debit'] as $debitKey => $debit) {
                                    foreach ($usersTotals['credit'] as $creditKey => $credit) {
                                        if ($provider->games_table) {
                                            if (!in_array($provider->id, $dotsuiteProviders)) {
                                                if ($debit->game_id == $credit->game_id && $debit->user_id == $credit->user_id) {
                                                    $gameProfit = $debit->total - $credit->total;
                                                    $rtp = ($debit->total == 0) ? 0 : ($credit->total / $debit->total) * 100;
                                                    $closureData = [
                                                        'user_id' => $debit->user_id,
                                                        'username' => $debit->username,
                                                        'played' => number_format($debit->total, 2, '.', ''),
                                                        'won' => number_format($credit->total, 2, '.', ''),
                                                        'profit' => number_format($gameProfit, 2, '.', ''),
                                                        'rtp' => number_format($rtp, 2, '.', ''),
                                                        'bets' => $debit->bets,
                                                        'start_date' => $startDate,
                                                        'end_date' => $endDate,
                                                        'game_id' => $debit->game_id,
                                                        'currency_iso' => $currency,
                                                        'whitelabel_id' => $whitelabel->id,
                                                        'provider_id' => $ticketProvider
                                                    ];

                                                    $closuresUsersTotalsRepo->store($closureData);
                                                    unset($usersTotals['debit'][$debitKey]);
                                                    unset($usersTotals['credit'][$creditKey]);
                                                }
                                            } else {
                                                if ($debit->dotsuite_game_id == $credit->dotsuite_game_id && $debit->user_id == $credit->user_id) {
                                                    $gameProfit = $debit->total - $credit->total;
                                                    $rtp = ($debit->total == 0) ? 0 : ($credit->total / $debit->total) * 100;
                                                    $closureData = [
                                                        'user_id' => $debit->user_id,
                                                        'username' => $debit->username,
                                                        'game_id' => $debit->dotsuite_game_id,
                                                        'played' => number_format($debit->total, 2, '.', ''),
                                                        'won' => number_format($credit->total, 2, '.', ''),
                                                        'profit' => number_format($gameProfit, 2, '.', ''),
                                                        'rtp' => number_format($rtp, 2, '.', ''),
                                                        'bets' => $debit->bets,
                                                        'start_date' => $startDate,
                                                        'end_date' => $endDate,
                                                        'currency_iso' => $currency,
                                                        'whitelabel_id' => $whitelabel->id,
                                                        'provider_id' => $ticketProvider
                                                    ];

                                                    $closuresUsersTotalsRepo->store($closureData);
                                                    unset($usersTotals['debit'][$debitKey]);
                                                    unset($usersTotals['credit'][$creditKey]);
                                                }
                                            }
                                        } else {
                                            if ($debit->user_id == $credit->user_id) {
                                                $gameProfit = $debit->total - $credit->total;
                                                $rtp = ($debit->total == 0) ? 0 : ($credit->total / $debit->total) * 100;
                                                $closureData = [
                                                    'user_id' => $debit->user_id,
                                                    'username' => $debit->username,
                                                    'played' => number_format($debit->total, 2, '.', ''),
                                                    'won' => number_format($credit->total, 2, '.', ''),
                                                    'profit' => number_format($gameProfit, 2, '.', ''),
                                                    'rtp' => number_format($rtp, 2, '.', ''),
                                                    'bets' => $debit->bets,
                                                    'start_date' => $startDate,
                                                    'end_date' => $endDate,
                                                    'currency_iso' => $currency,
                                                    'whitelabel_id' => $whitelabel->id,
                                                    'provider_id' => $ticketProvider
                                                ];
                                                $closuresUsersTotalsRepo->store($closureData);
                                                unset($usersTotals['debit'][$debitKey]);
                                                unset($usersTotals['credit'][$creditKey]);
                                            }
                                        }
                                    }
                                }
                                foreach ($usersTotals['debit'] as $debitItem) {
                                    if ($provider->games_table) {
                                        $closureData = [
                                            'user_id' => $debitItem->user_id,
                                            'username' => $debitItem->username,
                                            'played' => number_format($debitItem->total, 2, '.', ''),
                                            'won' => number_format(0, 2, '.', ''),
                                            'profit' => number_format($debitItem->total, 2, '.', ''),
                                            'rtp' => number_format(0, 2, '.', ''),
                                            'bets' => $debitItem->bets,
                                            'start_date' => $startDate,
                                            'end_date' => $endDate,
                                            'currency_iso' => $currency,
                                            'whitelabel_id' => $whitelabel->id,
                                            'provider_id' => $ticketProvider
                                        ];

                                        if (!in_array($provider->id, $dotsuiteProviders)) {
                                            $closureData['game_id'] = $debitItem->game_id;
                                        } else {
                                            $closureData['game_id'] = $debitItem->dotsuite_game_id;
                                        }
                                        $closuresUsersTotalsRepo->store($closureData);

                                    } else {
                                        $closureData = [
                                            'user_id' => $debitItem->user_id,
                                            'username' => $debitItem->username,
                                            'played' => number_format($debitItem->total, 2, '.', ''),
                                            'won' => number_format(0, 2, '.', ''),
                                            'profit' => number_format($debitItem->total, 2, '.', ''),
                                            'rtp' => number_format(0, 2, '.', ''),
                                            'bets' => $debitItem->bets,
                                            'start_date' => $startDate,
                                            'end_date' => $endDate,
                                            'currency_iso' => $currency,
                                            'whitelabel_id' => $whitelabel->id,
                                            'provider_id' => $ticketProvider
                                        ];
                                        $closuresUsersTotalsRepo->store($closureData);
                                    }
                                }
                                foreach ($usersTotals['credit'] as $creditItem) {
                                    if ($provider->games_table) {
                                        $closureData = [
                                            'user_id' => $creditItem->user_id,
                                            'username' => $creditItem->username,
                                            'played' => number_format(0, 2, '.', ''),
                                            'won' => number_format($creditItem->total, 2, '.', ''),
                                            'profit' => number_format(-$creditItem->total, 2, '.', ''),
                                            'rtp' => number_format(0, 2, '.', ''),
                                            'bets' => 0,
                                            'start_date' => $startDate,
                                            'end_date' => $endDate,
                                            'currency_iso' => $currency,
                                            'whitelabel_id' => $whitelabel->id,
                                            'provider_id' => $ticketProvider
                                        ];
                                        if (!in_array($provider->id, $dotsuiteProviders)) {
                                            $closureData['game_id'] = $debitItem->game_id;
                                        } else {
                                            $closureData['game_id'] = $debitItem->dotsuite_game_id;
                                        }
                                        $closuresUsersTotalsRepo->store($closureData);

                                    } else {
                                        $closureData = [
                                            'user_id' => $creditItem->user_id,
                                            'username' => $creditItem->username,
                                            'played' => number_format(0, 2, '.', ''),
                                            'won' => number_format($creditItem->total, 2, '.', ''),
                                            'profit' => number_format(-$creditItem->total, 2, '.', ''),
                                            'rtp' => number_format(0, 2, '.', ''),
                                            'bets' => 0,
                                            'start_date' => $startDate,
                                            'end_date' => $endDate,
                                            'currency_iso' => $currency,
                                            'whitelabel_id' => $whitelabel->id,
                                            'provider_id' => $ticketProvider
                                        ];
                                        $closuresUsersTotalsRepo->store($closureData);
                                    }
                                }

                            }
                        }
                    } catch (\Exception $ex) {
                        continue;
                    }
                }
            }
        }
    }
}
