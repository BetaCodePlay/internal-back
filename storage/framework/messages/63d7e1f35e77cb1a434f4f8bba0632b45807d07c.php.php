<?php

namespace App\CRM\Collections;

use App\Core\Enums\Languages;
use App\Core\Repositories\TransactionsRepo;
use App\Reports\Repositories\ClosuresUsersTotalsRepo;
use Carbon\Carbon;
use Dotworkers\Configurations\Configurations;
use Dotworkers\Security\Enums\Permissions;
use Dotworkers\Wallet\Wallet;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Gate;

/**
 * Class SegmentsCollection
 *
 * This class allows to format segments data
 *
 * @package App\CRM\Collections
 * @author  Eborio Linarez
 */
class SegmentsCollection
{
    /**
     * Check deposits quantity
     *
     * @param int $deposits Deposits data
     * @param string $options Deposits options
     * @param int $quantity Deposits quantity
     * @return bool|void
     */
    private function checkDepositsQuantity($deposits, $options, $quantity)
    {
        if (!empty($deposits)) {
            switch ($options) {
                case '<=':
                {
                    return $quantity <= $deposits;
                    break;
                }
                case '>=':
                {
                    return $quantity >= $deposits;
                    break;
                }
                case '==':
                {
                    return $quantity == $deposits;
                    break;
                }
            }
        }
    }

    /**
     * Check played amount
     *
     * @param int $played Played data
     * @param string $options Played options
     * @param int $amount Played amount
     * @return bool|void
     */
    private function checkPlayedAmount($played, $options, $amount)
    {
        if (!empty($deposits)) {
            switch ($options) {
                case '<=':
                {
                    return $amount <= $played;
                    break;
                }
                case '>=':
                {
                    return $amount >= $played;
                    break;
                }
                case '==':
                {
                    return $amount == $played;
                    break;
                }
            }
        }
    }

    /**
     * Format all segments
     *
     * @param array $segments Segments data
     */
    public function formatAll($segments)
    {
        foreach ($segments as $segment) {
            $segment->quantity = count($segment->data);
            $segment->actions = sprintf(
                '<a href="%s" class="btn u-btn-3d btn-sm u-btn-bluegray mr-2" ><i class="hs-admin-pencil"></i> %s</a>',
                route('segments.edit', [$segment->id]),
                _i('Edit')
            );

            //$statusClass = $segment->status ? 'bluegray' : 'primary';
            //$statusText = $segment->status ? _i('Active') : _i('Inactive');
            //$statusIcon = $segment->status ? 'hs-admin-check' : 'hs-admin-close';
            //$status = $segment->status ? 1 : 0;
            //$segment->actions .= sprintf(
           //     '<button type="button" class="btn u-btn-3d btn-sm u-btn-%s mr-2 disable" data-route="%s" ><i class="%s"></i> %s</button>',
            //    $statusClass,
            //    route('segments.disable', [$segment->id, $status]),
            //    $statusIcon,
            //    $statusText
            //);
            if (Gate::allows('access', Permissions::$manage_segmentation_tool)) {
                $segment->actions .= sprintf(
                    '<button type="button" class="btn u-btn-3d btn-sm u-btn-primary mr-2 delete" data-route="%s"><i class="hs-admin-trash"></i> %s</button>',
                    route('segments.delete', [$segment->id]),
                    _i('Delete')
                );
            }
        }
    }

    /**
     * Format details
     *
     * @param object $segment Segment data
     * @return void
     */
    public function formatDetails($segment)
    {
        $segment->last_login = Carbon::createFromFormat('Y-m-d', $segment->filter->last_login)->format('d-m-Y');
        $segment->last_deposit = Carbon::createFromFormat('Y-m-d', $segment->filter->last_deposit)->format('d-m-Y');
        $segment->last_withdrawal = Carbon::createFromFormat('Y-m-d', $segment->filter->last_withdrawal)->format('d-m-Y');
        $segment->registration_date = Carbon::createFromFormat('Y-m-d', $segment->filter->registration_date)->format('d-m-Y');
    }

    /**
     * Format segmentation data
     *
     * @param int $whitelabel Whitelabel ID
     * @param array $users Users data
     * @param string $depositsOptions Deposits options
     * @param int $deposits Deposits quantity to filter
     * @param string $balanceOptions Balance options
     * @param float $balance Balance to filter
     * @param string $playedOptions Played options
     * @param float $played Played amount to filter
     * @param bool $fullProfile User full profile filter
     * @param array $filter Filter data
     * @return array
     */
    public function formatSegmentationData($whitelabel, $users, $depositsOptions, $deposits, $balanceOptions, $balance, $playedOptions, $played, $fullProfile, $filter)
    {
        $transactionsRepo = new TransactionsRepo();
        $closuresUsersTotalsRepo = new ClosuresUsersTotalsRepo();
        $usersData = [];
        $usersIds = [];
        $usersWallets = collect();
        $walletsData = collect();

        if (!is_null($fullProfile)) {
            $users = collect($users);
            $filtered = $users->filter(function ($value) use ($fullProfile) {
                return $fullProfile == $value->profile_completed;
            });
            $users = $filtered->all();
        }

        foreach ($users as $user) {
            $usersWallets->push($user->wallet_id);
        }

        if (count($usersWallets) > 0) {
            foreach ($usersWallets->chunk(1000) as $chunk) {
                $walletAccessToken = Wallet::clientAccessToken();
                $wallets = $chunk->toArray();
                $wallets = Wallet::getUsersBalancesByAmounts($wallets, $balanceOptions, $balance, $walletAccessToken->access_token);
                $walletsData->push($wallets->data->wallets);
            }
        }
        $walletsData = $walletsData->collapse()->keyBy('id')->all();

        foreach ($users as $user) {
            $id = $user->wallet_id;
            if (isset($walletsData[$id])) {
                $user->balance = number_format($walletsData[$id]->balance, 2);
                $user->user = sprintf(
                    '<a href="%s" class="btn u-btn-3d btn-sm u-btn-primary" target="_blank">%s</a>',
                    route('users.details', $user->id),
                    $user->id
                );
                $user->full_name = "{$user->first_name} {$user->last_name}";
                $user->deposit = !is_null($user->last_deposit) ? $user->last_deposit->format('d-m-Y H:i:s') : '';
                $user->withdrawal = !is_null($user->last_debit) ? $user->last_debit->format('d-m-Y H:i:s') : '';
                $user->login = !is_null($user->last_login) ? $user->last_login->format('d-m-Y H:i:s') : '';
                $user->created = $user->created_at->format('d-m-Y H:i:s');
                $user->language = !is_null($user->language) ? Languages::getName($user->language) : '';
                $user->profile = $user->profile_completed ? _i('Completed') : _i('Incomplete');
                $user->deposits = $transactionsRepo->getUniqueDepositorsByUserId($user->id, $user->currency_iso, $whitelabel)->count();
                $user->played = $closuresUsersTotalsRepo->getPlayedByUser($user->id, $user->currency_iso, $whitelabel);

                if (!is_null($deposits)) {
                    if (!$this->checkDepositsQuantity($deposits, $depositsOptions, $user->deposits)) {
                        continue;
                    }
                }

                if (!is_null($played)) {
                    if (!$this->checkPlayedAmount($played, $playedOptions, $user->played)) {
                        continue;
                    }
                }
                $user->played = number_format($user->played, 2);

                $usersData[] = $user;
                $usersIds[] = $user->id;
            }
        }

        //\Log::info('$users', [count($usersData)]);

        return [
            'users' => $usersData,
            'ids' => $usersIds,
            'filter' => $filter
        ];
    }

    /**
     * Format segments user
     * @param array $segments Segments data
     * @param int $user User ID
     */
    public function formatSegmentsUser($segments, $user)
    {
        $data = [];
        foreach ($segments as $segment) {
            foreach ($segment->data as $item) {
                if ($item == $user) {
                    $button = sprintf(
                        '<button type="button" class="btn u-btn-3d btn-sm u-btn-primary mr-2 delete" id="delete" data-route="%s"><i class="hs-admin-trash"></i> %s</button>',
                        route('segments.remover-user', [$segment->id, $user]),
                        _i('Remove')
                    );

                    $segmentObject = new \stdClass();
                    $segmentObject->name = $segment->name;
                    $segmentObject->actions = $button;
                    $data[] = $segmentObject;
                }
            }
        }
        return $data;
    }

    /**
     * Format users list segments
     *
     * @param int $segment Segments id
     * @param array $usersSegment Users of segments data
     */
    public function formatUserslist($segment, $usersSegment)
    {
        foreach ($usersSegment as $user) {
            $user->user = sprintf(
                '<a href="%s" class="btn u-btn-3d u-btn-primary btn-sm" target="_blank">%s</a>',
                route('users.details', [$user->id]),
                $user->id
            );
            $user->username = $user->username;
            $user->actions .= sprintf(
                '<button type="button" class="btn u-btn-3d btn-sm u-btn-primary mr-2 delete" id="delete" data-route="%s"><i class="hs-admin-trash"></i> %s</button>',
                route('segments.remover-user', [$segment, $user->id]),
                _i('Remove')
            );
        }
    }
}
