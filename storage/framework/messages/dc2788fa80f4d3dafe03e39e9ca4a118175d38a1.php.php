<?php

namespace App\Store\Collections;

use App\Store\Enums\Actions;
use Carbon\Carbon;
use Dotworkers\Configurations\Enums\Providers;
use Dotworkers\Configurations\Enums\ProviderTypes;
use Dotworkers\Security\Enums\Permissions;
use Dotworkers\Store\Enums\TransactionTypes;
use Illuminate\Support\Facades\Gate;

/**
 * Class StoreCollection
 *
 * This class allows to format store data
 *
 * @package App\Store\Collections
 * @author  Damelys Espinoza
 */
class StoreCollection
{
    /**
     * Format all actions
     *
     * @param array $actions Actions data
     */
    public function formatAllActions($actions)
    {
        foreach ($actions as $action) {
            $action->name = !is_null($action->name) ? $action->name : _i('Without Name');
            if (Gate::allows('access', Permissions::$manage_sliders)) {
                $action->actions = sprintf(
                    '<a href="%s" class="btn u-btn-3d btn-sm u-btn-bluegray mr-2"><i class="hs-admin-pencil"></i> %s</a>',
                    route('store.actions.edit', [$action->id]),
                    _i('Edit')
                );

                $action->actions .= sprintf(
                    '<button type="button" class="btn u-btn-3d btn-sm u-btn-primary mr-2 delete" data-route="%s"><i class="hs-admin-trash"></i> %s</button>',
                    route('store.actions.delete', [$action->id]),
                    _i('Delete')
                );
            }
        }
    }

    /**
     * Format all sliders
     *
     * @param array $rewards Rewards data
     */
    public function formatAllRewards($rewards)
    {
        $timezone = session('timezone');
        foreach ($rewards as $reward) {
            $url = s3_asset("store-rewards/{$reward->image}");
            $file = $reward->image;
            $start = !is_null($reward->start_date) ? $reward->start_date->setTimezone($timezone)->format('d-m-Y') : _i('No starting date');
            $end = !is_null($reward->end_date) ? $reward->end_date->setTimezone($timezone)->format('d-m-Y') : _i('No end date');
            $reward->image = "<img src='$url' class='img-responsive' width='200'>";
            $reward->name = !is_null($reward->name) ? $reward->name : _i('Without Name');
            $reward->language = $reward->language == '*' ? _i('Everybody') : $reward->language;
            $reward->currency_iso = $reward->currency_iso == '*' ? _i('Everybody') : $reward->currency_iso;
            $reward->dates = "$start <br> $end";
            if (is_null($reward->category_name)) {
                $reward->category_name = _i('Without category');
            }
            $statusClass = $reward->status ? 'teal' : 'lightred';
            $statusText = $reward->status ? _i('Published') : _i('Unpublished');
            $reward->status = sprintf(
                '<span class="u-label g-bg-%s g-rounded-20 g-px-15 g-mr-10 g-mb-15">%s</span>',
                $statusClass,
                $statusText
            );

            if (Gate::allows('access', Permissions::$manage_sliders)) {
                $reward->actions = sprintf(
                    '<a href="%s" class="btn u-btn-3d btn-sm u-btn-bluegray mr-2"><i class="hs-admin-pencil"></i> %s</a>',
                    route('store.rewards.edit', [$reward->id, $file]),
                    _i('Edit')
                );

                $reward->actions .= sprintf(
                    '<button type="button" class="btn u-btn-3d btn-sm u-btn-primary mr-2 delete" data-route="%s"><i class="hs-admin-trash"></i> %s</button>',
                    route('store.rewards.delete', [$reward->id, $file]),
                    _i('Delete')
                );
            }
        }
    }

    /**
     * Format all actions configurations
     *
     * @param array $actions Actions data
     */
    public function formatAllActionsConfigurations($actions)
    {
        $timezone = session('timezone');
        foreach ($actions as $action) {
            $action->name = Actions::getName($action->action_id);
            $action->currency = $action->currency_iso;

            $start = !is_null($action->data->start_date) ? Carbon::createFromFormat('Y-m-d H:i:s', $action->data->start_date)->setTimezone($timezone)->format('d-m-Y H:i:s') : _i('No starting date');
            $end = !is_null($action->data->end_date) ? Carbon::createFromFormat('Y-m-d H:i:s', $action->data->end_date)->setTimezone($timezone)->format('d-m-Y H:i:s') : _i('No end date');
            $action->dates = "$start <br> $end";

            switch ($action->action_id) {
                case 1:
                    $action->data = sprintf(
                        '<ul><li>%s: %s</li><li>%s: %s</li><li>%s: %s</li><li>%s: %s</li></ul>',
                        _i('Points desktop'),
                        $action->data->points,
                        _i('Amount desktop'),
                        number_format($action->data->amount, 2),
                        _i('Points mobile'),
                        $action->data->mobile_points,
                        _i('Amount mobile'),
                        number_format($action->data->mobile_amount, 2),
                    );
                    break;
                case 2:
                    $action->data = sprintf(
                        '<ul> <li>%s: %s</li><li>%s: %s</li></ul>',
                        _i('Points desktop'),
                        $action->data->points,
                        _i('Points mobile'),
                        $action->data->mobile_points,
                    );
                    break;
                case 3:
                    $action->data = sprintf(
                        '<ul> <li>%s: %s</li><li>%s: %s</li><li>%s: %s</li><li>%s: %s</li></ul>',
                        _i('Points desktop'),
                        $action->data->points,
                        _i('Amount desktop'),
                        number_format($action->data->amount, 2),
                        _i('Points mobile'),
                        $action->data->mobile_points,
                        _i('Amount mobile'),
                        number_format($action->data->mobile_amount, 2),
                    );
                    break;
            }

            if (Gate::allows('access', Permissions::$manage_sliders)) {
                $action->actions = sprintf(
                    '<a href="%s" class="btn u-btn-3d btn-sm u-btn-bluegray mr-2"><i class="hs-admin-pencil"></i> %s</a>',
                    route('store.actions.edit', [$action->action_id, $action->currency_iso]),
                    _i('Edit')
                );

                /*$action->actions .= sprintf(
                    '<button type="button" class="btn u-btn-3d btn-sm u-btn-primary mr-2 delete" data-route="%s"><i class="hs-admin-trash"></i> %s</button>',
                    route('store.actions.delete', [$action->action_id]),
                    _i('Delete')
                );*/
            }
        }
    }

    /**
     * Format update redeemed rewards
     *
     * @param array $rewards Rewards data
     */
    public function formatRedeemedRewards($rewards)
    {
        $timezone = session('timezone');
        foreach ($rewards as $reward) {
           $reward->date = $reward->created_at->setTimezone($timezone)->format('d-m-Y H:i:s');
           $reward->user = sprintf(
               '<a href="%s" class="btn u-btn-3d btn-sm u-btn-primary mr-2" target="_blank">%s</a>',
               route('users.details', [$reward->user_id]),
               $reward->user_id
           );
           $dataAmount = json_decode($reward->data);
           $reward->amount = number_format($dataAmount->amount,2);
        }
    }

    /**
     * Format update actions configurations
     *
     * @param array $actions Actions data
     * @param int $id Action type
     */
    public function formatUpdateActionsConfigurations($action, $id)
    {
        $items = null;
        $itemObject = new \stdClass();
        $timezone = session('timezone');
        $itemObject->action_id = is_null($action) ? (int)$id : $action->action_id;
        $itemObject->currency_iso = is_null($action) ?  session('currency') : $action->currency_iso;
        $itemObject->start_date = is_null($action) ? "" : Carbon::createFromFormat('Y-m-d H:i:s', $action->data->start_date)->setTimezone($timezone)->format('Y-m-d H:i:s');
        $itemObject->end_date = is_null($action) ? "" :  Carbon::createFromFormat('Y-m-d H:i:s', $action->data->end_date)->setTimezone($timezone)->format('Y-m-d H:i:s');
        $itemObject->exclude_providers = is_null($action) ? [] : $action->exclude_providers;
        $itemObject->provider_type_id = is_null($action) ? "" : $action->provider_type_id;
        $itemObject->provider_type_name = is_null($action) ? "" : ProviderTypes::getName($action->provider_type_id);
        switch ($id) {
            case 1:
                $itemObject->points = is_null($action) ? "" : $action->data->points;
                $itemObject->amount = is_null($action) ? "" : $action->data->amount;
                $itemObject->mobile_points = is_null($action) ? "" : $action->data->mobile_points;
                $itemObject->mobile_amount = is_null($action) ? "" : $action->data->mobile_amount;
                break;
            case 2:
                $itemObject->points = is_null($action) ? "" : $action->data->points;
                $itemObject->mobile_points = is_null($action) ? "" : $action->data->mobile_points;
                $itemObject->amount = "";
                $itemObject->mobile_amount = "";
                break;
            case 3:
                $itemObject->points = is_null($action) ? "" : $action->data->points;
                $itemObject->amount = is_null($action) ? "" : $action->data->amount;
                $itemObject->mobile_points = is_null($action) ? "" : $action->data->mobile_points;
                $itemObject->mobile_amount = is_null($action) ? "" : $action->data->mobile_amount;
                break;
        }
        $items = $itemObject;
        return $items;
    }

    /**
     * Format claims
     *
     * @param array $claims Claims data
     */
    public function formatClaims($claims)
    {
        foreach ($claims as $claim) {
            $timezone = session('timezone');
            $claim->date = $claim->created_at->setTimezone($timezone)->format('d-m-Y H:i:s');
            $claim->points = number_format($claim->points, 0);
            $claim->prize = number_format($claim->data->amount, 2);
        }
    }

    /**
     * Format details
     *
     * @param object $reward Reward data
     */
    public function formatDetails($reward)
    {
        $timezone = session('timezone');
        $url = s3_asset("store-rewards/{$reward->image}");
        $reward->file = $reward->image;
        $reward->image = "<img src='$url' class='img-responsive' width='200'>";
        $reward->start = !is_null($reward->start_date) ? $reward->start_date->setTimezone($timezone)->format('d-m-Y') : null;
        $reward->end = !is_null($reward->end_date) ? $reward->end_date->setTimezone($timezone)->format('d-m-Y') : null;
    }

    /**
     * Format search
     *
     * @param array $categories Categories data
     */
    public function formatSearch($categories)
    {
        foreach ($categories as $category) {

            $category->client = sprintf(
                '<a href="%s" class="btn u-btn-3d u-btn-primary btn-sm">%s</a>',
                route('store.categories.details', [$category->id]),
                $category->id
            );
            $category->actions = sprintf(
                '<button type="button" class="btn u-btn-3d btn-sm u-btn-primary mr-2 delete" id="delete" data-route="%s"><i class="hs-admin-trash"></i> %s</button>',
                route('store.categories.delete', [$category->id]),
                _i('Delete')
            );
        }
    }

    /**
     * Format transactions
     *
     * @param array $transactions Transactions data
     */
    public function formatTransactions($transactions)
    {
        foreach ($transactions as $transaction) {
            $timezone = session('timezone');
            $transaction->date = $transaction->created_at->setTimezone($timezone)->format('d-m-Y H:i:s');
            $transaction->amount = number_format($transaction->amount, 2);
            $transaction->debit = $transaction->transaction_type_id == TransactionTypes::$debit ? $transaction->amount : '-';
            $transaction->credit = $transaction->transaction_type_id == TransactionTypes::$credit ? $transaction->amount : '-';
            $transaction->balance = number_format($transaction->balance, 2);
            $transaction->provider = Providers::getName($transaction->provider_id);
        }
    }
}
