<?php


namespace App\DotSuite\Collections;

use App\DotSuite\Enums\FreeSpinsStatus;
use App\DotSuite\Repositories\DotSuiteFreeSpinsRepo;
use App\DotSuite\Repositories\DotSuiteGamesRepo;
use App\Users\Repositories\UsersRepo;
use Dotworkers\Configurations\Enums\Codes;
use Dotworkers\Configurations\Enums\Providers;
use Dotworkers\Wallet\Wallet;
use Carbon\Carbon;

/**
 * Class DotSuiteCollection
 *
 * This class allows to format dot suite data
 *
 * @package App\DotSuite\Collections
 * @author  Damelys Espinoza
 */
class DotSuiteCollection
{
    /**
     * Format all triple-cherry
     *
     * @param array $tripleCherry Triple cherry data
     * @param var $currency Currency Iso
     */
    public function formatAllTripleCherry($tripleCherry, $currency)
    {
        $dotSuiteFreeSpinsRepo = new DotSuiteFreeSpinsRepo();
        $data = [];
        foreach ($tripleCherry as $cherry) {
            $id = $cherry->id;
            $promotion = $dotSuiteFreeSpinsRepo->allListPromotionsIdAndCurrency($id, $currency);
            if (!is_null($promotion)) {
                $statusClass = $cherry->enabled ? 'teal' : 'lightred';
                $statusText = $cherry->enabled ? _i('Active') : _i('Inactive');
                $cherry->status = sprintf(
                    '<span class="u-label g-bg-%s g-rounded-20 g-px-15 g-mr-10 g-mb-15">%s</span>',
                    $statusClass,
                    $statusText
                );

                $cherry->start_date = Carbon::now()->timestamp($cherry->from)->format('d-m-Y');
                $cherry->end_date = Carbon::now()->timestamp($cherry->to)->format('d-m-Y');
                $data[] = $cherry;
            }
        }
        return $data;
    }

    /**
     * Format for player list active
     *
     * @param array $dataActive For player list active
     */
    public function formatListActive($dataActive, $user)
    {
        $data = [];
        $usersRepo = new UsersRepo();
        foreach($dataActive as $active) {
            $listObject = new \stdClass();
            $users = $active->users;
            if (($key = array_search($user, $users)) !== false) {
                $userData = $usersRepo->find($users[$key]);
                $listObject->new_campaign = $active->data->new_campaign_id;
                $listObject->rounds = $active->free_spins;
                $listObject->id = $active->id;
                $listObject->username = $userData['username'];
                $listObject->user = sprintf(
                    '<a href="%s" class="btn u-btn-3d u-btn-primary btn-sm" target="_blank">%s</a>',
                    route('users.details', [$userData['id']]),
                    $userData['id']
                );
                $listObject->actions = sprintf(
                    '<button type="button" class="btn u-btn-3d btn-sm u-btn-primary mr-2 delete" data-route="%s"><i class="hs-admin-check"></i> %s</button>',
                    route('dot-suite.for-player-active-data', [$active->currency_iso, $userData['id'], $active->id]),
                    _i('Active')
                );
                $data[] = $listObject;
            }
        }
        return $data;
    }

    /**
     * Format for player list cancel
     *
     * @param array $dataCancel For player list cancel
     */
    public function formatListCancel($dataCancel, $user)
    {
        $data = [];
        $usersRepo = new UsersRepo();
        foreach($dataCancel as $cancel) {
            $listObject = new \stdClass();
            $users = $cancel->users;
            if (($key = array_search($user, $users)) !== false) {
                $userData = $usersRepo->find($users[$key]);
                $listObject->new_campaign = $cancel->data->new_campaign_id;
                $listObject->rounds = $cancel->free_spins;
                $listObject->username = $userData->username;
                $listObject->user = sprintf(
                    '<a href="%s" class="btn u-btn-3d u-btn-primary btn-sm" target="_blank">%s</a>',
                    route('users.details', [$userData->id]),
                    $userData->id
                );
                $listObject->actions = sprintf(
                    '<button type="button" class="btn u-btn-3d btn-sm u-btn-primary mr-2 delete" data-route="%s"><i class="hs-admin-close"></i> %s</button>',
                    route('dot-suite.for-player-cancel-data', [$cancel->currency_iso, $cancel->data->new_campaign_id, $cancel->id]),
                    _i('Cancel')
                );
                $data[] = $listObject;
            }
        }
        return $data;
    }

    /**
     * Format all pragmatic
     *
     * @param array $listPragmatic List pragmatic data
     */
    public function formatListPragmatic($listPragmatic, $user)
    {
        $data = [];
        foreach ($listPragmatic as $list) {
            $listObject = new \stdClass();
            $users = $list->users;
            $walletData = Wallet::getByClient($users[0], $list->currency_iso);
            if ($walletData->code == Codes::$ok) {
                $walletUser = $walletData->data->wallet->id;
                $listObject->bonus_code = $list->data->bonus_code;
                $listObject->currency = $list->currency_iso;
                $listObject->rounds = $list->free_spins;
                $listObject->id = $list->id;
                $listObject->actions = sprintf(
                    '<button type="button" class="btn u-btn-3d btn-sm u-btn-primary mr-2 delete" data-route="%s"><i class="hs-admin-trash"></i> %s</button>',
                    route('dot-suite.cancel-free-rounds-data', [$walletUser, $list->data->bonus_code]),
                    _i('Cancel')
                );
                $data[] = $listObject;
            }
        }
        return $data;
    }

    /**
     * Format all caleta gaming
     *
     * @param array $listCaletaGamin List caleta gaming data
     * @param int $user User ID
     */
    public function formatListCaletaGaming($listCaletaGamin, $user)
    {
        $dataList = [];
        $usersRepo = new UsersRepo();
        foreach ($listCaletaGamin as $list) {
            $users = $list->users;
            if (!is_null($user)) {
                if(in_array($user, $users)){
                    $listObject = new \stdClass();
                    $userData = $usersRepo->find($list->users);
                    $listObject->amount = number_format($list->data->amount, 2);
                    $listObject->reference = $list->data->code_reference;
                    $listObject->currency = $list->currency_iso;
                    $listObject->rounds = $list->free_spins;
                    $listObject->username = $userData->username;
                    $listObject->actions = sprintf(
                        '<button type="button" class="btn u-btn-3d btn-sm u-btn-primary mr-2 delete" data-route="%s"><i class="hs-admin-trash"></i> %s</button>',
                        route('dot-suite.free-spins.cancel-free-spins-data', [$list->data->code_reference, $list->provider_id, $list->id]),
                        _i('Cancel')
                    );
                    $listObject->user = sprintf(
                        '<a href="%s" class="btn u-btn-3d u-btn-primary btn-sm" target="_blank">%s</a>',
                        route('users.details',[$user , $list->currency_iso]),
                        $user
                    );
                    $dataList[] = $listObject;
                }
            } else {
                $listObject = new \stdClass();
                $userData = $usersRepo->find($list->users);
                $listObject->amount = number_format($list->data->amount, 2);
                $listObject->reference = $list->data->code_reference;
                $listObject->currency = $list->currency_iso;
                $listObject->rounds = $list->free_spins;
                $listObject->username = $userData->username;
                $listObject->actions = sprintf(
                    '<button type="button" class="btn u-btn-3d btn-sm u-btn-primary mr-2 delete" data-route="%s"><i class="hs-admin-trash"></i> %s</button>',
                    route('dot-suite.free-spins.cancel-free-spins-data', [$list->data->code_reference, $list->provider_id, $list->id]),
                    _i('Cancel')
                );
                $listObject->user = sprintf(
                    '<a href="%s" class="btn u-btn-3d u-btn-primary btn-sm" target="_blank">%s</a>',
                    route('users.details',[$list->users , $list->currency_iso]),
                    $user
                );
                $dataList[] = $listObject;
            }
        }
        return $dataList;
    }

    /**
     * Format all caleta gaming
     *
     * @param array $listFreeSpins List caleta gaming data
     * @param int $user User ID
     */
    public function formatListFreeSpins($listFreeSpins, $user)
    {
        $dataList = [];
        $usersRepo = new UsersRepo();
        $gameDotSuiteRepo = new DotSuiteGamesRepo();
        $timezone = session('timezone');
        foreach ($listFreeSpins as $list) {
            $users = $list->users;
            if (!is_null($user)) {
                if(in_array($user, $users)){
                    $listObject = new \stdClass();
                    $userData = $usersRepo->find($list->users);
                    $game = $gameDotSuiteRepo->findByGame($list->games_id[0]);
                    $listObject->amount = number_format($list->data->amount, 2);
                    $listObject->start_date = $list->created_at->setTimezone($timezone)->format('d-m-Y H:i:s');
                    $listObject->end_date =  Carbon::createFromFormat('Y-m-d H:i:s', $list->data->expiration_date)->setTimezone($timezone)->format('Y-m-d H:i:s');
                    $listObject->reference = $list->data->code_reference;
                    $listObject->currency = $list->currency_iso;
                    $listObject->rounds = $list->free_spins;
                    $listObject->username = $userData->username;
                    $listObject->game = $game->name;
                    $listObject->user = sprintf(
                        '<a href="%s" class="btn u-btn-3d u-btn-primary btn-sm" target="_blank">%s</a>',
                        route('users.details',[$users[0] , $list->currency_iso]),
                        $users[0]
                    );
                    $dataList[] = $listObject;
                }
            } else {
                $listObject = new \stdClass();
                $userData = $usersRepo->find($list->users);
                $game = $gameDotSuiteRepo->findByGame($list->games_id[0]);
                $listObject->amount = number_format($list->data->amount, 2);
                $listObject->start_date = $list->created_at->setTimezone($timezone)->format('d-m-Y H:i:s');
                $listObject->end_date = Carbon::createFromFormat('Y-m-d H:i:s', $list->data->expiration_date)->setTimezone($timezone)->format('Y-m-d H:i:s');
                $listObject->reference = $list->data->code_reference;
                $listObject->currency = $list->currency_iso;
                $listObject->rounds = $list->free_spins;
                $listObject->username = $userData->username;
                $listObject->game = $game->name;
                $listObject->user = sprintf(
                    '<a href="%s" class="btn u-btn-3d u-btn-primary btn-sm" target="_blank">%s</a>',
                    route('users.details',[$users[0] , $list->currency_iso]),
                    $users[0]
                );
                $dataList[] = $listObject;
            }
        }
        return $dataList;
    }

    /**
     * Format for player
     *
     * @param array $forplayers For player data
     */
    public function formatForPlayer($forplayers)
    {
        foreach ($forplayers as $key => $forplayer) {
            if (is_null($forplayer->new_campaign_id)) {
                unset($forplayers[$key]);
            }
        }
    }

    /**
     * Format free spin
     *
     * @param array $freeSpins Free sping data
     */
    public function formatFreeSpins($freeSpins)
    {
        foreach ($freeSpins as $freeSpin) {
            $freeSpin->bonus_code = $freeSpin->data->bonus_code;
        }
    }

    /**
     * Format promotions
     *
     * @param array $promotions $promotions data
     */
    public function formatPromotions($promotions)
    {
        $data = [];
        foreach ($promotions as $promotion) {
            if (!is_null($promotion->promotion_id)) {
                $listObject = new \stdClass();
                $listObject->free_spins = $promotion->free_spins;
                $listObject->name = !is_null($promotion->name) ? $promotion->name : '';
                $listObject->promotion = $promotion->promotion_id;
                if ($promotion->status == FreeSpinsStatus::$disable) {
                    $listObject->actions = sprintf(
                        '<button type="button" class="btn u-btn-3d u-btn-primary btn-sm mr-2 delete" data-route="%s"><i class="hs-admin-check"></i> %s</button>',
                        route('dot-suite.enable-promotion-data', [$promotion->currency_iso, $promotion->promotion_id, 1, $promotion->id]),
                        _i('Enable')
                    );
                }
                if ($promotion->status == FreeSpinsStatus::$enable) {
                    $listObject->actions = sprintf(
                        '<button type="button" class="btn u-btn-3d btn-sm u-btn-bluegray mr-2 delete" data-route="%s"><i class="hs-admin-close"></i> %s</button>',
                        route('dot-suite.enable-promotion-data', [$promotion->currency_iso, $promotion->promotion_id, 0, $promotion->id]),
                        _i('Disable')
                    );
                }
                $data[] = $listObject;
            }
        }
        return $data;
    }

    /**
     * Format promotions select
     *
     * @param array $promotions $promotions data
     */
    public function formatPromotionsSelect($promotions)
    {
        $data = [];
        foreach ($promotions as $promotion) {
            if (!is_null($promotion->promotion_id)) {
                $listObject = new \stdClass();
                $listObject->name = !is_null($promotion->name) ? $promotion->name : '';
                $listObject->promotion = $promotion->promotion_id;

                $data[] = $listObject;
            }
        }
        return $data;
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
     * Format user totals dotsuite
     *
     * @param array $totals Users totals
     * @param string $currency Currency ISO
     * @return mixed
     */
    public function usersTotals($nowTotals, $currency)
    {
        $usersData = [];
        $generalTotals = [];
        $played = 0;
        $won = 0;
        $profit = 0;
        if (!is_null($nowTotals)) {
            if (isset($nowTotals['debit'])) {
                foreach ($nowTotals['debit'] as $debitKey => $debit) {
                    foreach ($nowTotals['credit'] as $creditKey => $credit) {
                        if ($debit->id == $credit->id && $credit->provider == $debit->provider) {
                            $userProfit = $debit->total - $credit->total;

                            $played += $debit->total;
                            $won += $credit->total;
                            $profit += $userProfit;
                            $rtp = ($debit->total == 0) ? 0 : ($credit->total / $debit->total) * 100;

                            if (isset($debit->id)) {
                                $user = sprintf(
                                    '<a href="%s" class="btn u-btn-3d u-btn-primary btn-sm" target="_blank">%s</a>',
                                    route('users.details', $debit->id),
                                    $debit->id
                                );
                                $walletData = Wallet::getByClient($debit->id, $currency, false);
                                $wallet = $walletData->data->wallet->id;
                            } else {
                                $user = null;
                                $wallet = null;
                            }
                            $providerName = Providers::getName($debit->provider);
                            $usersData[] = [
                                'user' => $user,
                                'wallet' => $wallet,
                                'username' => $debit->username,
                                'provider_name' => $providerName,
                                'bets' => $debit->bets,
                                'played' => number_format($debit->total, 2),
                                'won' => number_format($credit->total, 2),
                                'profit' => number_format($userProfit, 2),
                                'played_original' => $debit->total,
                                'won_original' => $credit->total,
                                'profit_original' => $userProfit,
                                'rtp' => number_format($rtp, 2) . '%',
                            ];
                            unset($nowTotals['debit'][$debitKey]);
                            unset($nowTotals['credit'][$creditKey]);
                        }
                    }
                }
                foreach ($nowTotals['debit'] as $debitItemKey => $debitItem) {
                    $played += $debitItem->total;
                    $profit += $debitItem->total;

                    if (isset($debitItem->id)) {
                        $user = sprintf(
                            '<a href="%s" class="btn u-btn-3d u-btn-primary btn-sm" target="_blank">%s</a>',
                            route('users.details', $debitItem->id),
                            $debitItem->id
                        );
                        $walletData = Wallet::getByClient($debitItem->id, $currency, false);
                        $wallet = $walletData->data->wallet->id;
                    } else {
                        $user = null;
                        $wallet = null;
                    }
                    $providerName = Providers::getName($debitItem->provider);
                    $usersData[] = [
                        'user' => $user,
                        'wallet' => $wallet,
                        'username' => $debitItem->username,
                        'provider_name' => $providerName,
                        'bets' => $debitItem->bets,
                        'played' => number_format($debitItem->total, 2),
                        'won' => number_format(0, 2),
                        'profit' => number_format($debitItem->total, 2),
                        'played_original' => $debitItem->total,
                        'won_original' => 0,
                        'profit_original' => $debitItem->total,
                        'rtp' => number_format(0, 2) . '%',
                    ];
                    unset($nowTotals['debit'][$debitItemKey]);
                }

                foreach ($nowTotals['credit'] as $creditItemKey => $creditItem) {
                    $won += $creditItem->total;
                    $profit -= $creditItem->total;

                    if (isset($creditItem->id)) {
                        $user = sprintf(
                            '<a href="%s" class="btn u-btn-3d u-btn-primary btn-sm" target="_blank">%s</a>',
                            route('users.details', $creditItem->id),
                            $creditItem->id
                        );
                        $walletData = Wallet::getByClient($creditItem->id, $currency, false);
                        $wallet = $walletData->data->wallet->id;
                    } else {
                        $user = null;
                        $wallet = null;
                    }
                    $providerName = Providers::getName($creditItem->provider);
                    $usersData[] = [
                        'user' => $user,
                        'wallet' => $wallet,
                        'username' => $creditItem->username,
                        'provider_name' => $providerName,
                        'bets' => 0,
                        'played' => number_format(0, 2),
                        'won' => number_format($creditItem->total, 2),
                        'profit' => number_format(-$creditItem->total, 2),
                        'played_original' => 0,
                        'won_original' => $creditItem->total,
                        'profit_original' => -$creditItem->total,
                        'rtp' => number_format(100, 2) . '%'
                    ];
                    unset($nowTotals['credit'][$creditItemKey]);
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
}
