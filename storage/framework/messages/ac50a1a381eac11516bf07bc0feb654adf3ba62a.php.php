<?php

namespace App\Store\Commands;

use App\Reports\Repositories\ClosuresUsersTotalsRepo;
use App\Store\Repositories\ActionsConfigurationsRepo;
use App\Users\Repositories\UsersRepo;
use Carbon\Carbon;
use Dotworkers\Configurations\Enums\Providers;
use Dotworkers\Store\Enums\Actions;
use Dotworkers\Store\Store;
use Illuminate\Console\Command;

class PointsCashback extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'store:points-cashback';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Points cashback';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $actionConfigurationsRepo = new ActionsConfigurationsRepo();
        $usersRepo = new UsersRepo();
        $closuresUsersTotalsRepo = new ClosuresUsersTotalsRepo();
        $actions = $actionConfigurationsRepo->getByAction(Actions::$profit);

        foreach ($actions as $action) {
            $whitelabel = $action->whitelabel_id;
            $currency = $action->currency_iso;
            $providerType = $action->provider_type_id;
            $users = $usersRepo->getByWhitelabelAndCurrency($whitelabel, $currency);
            $startDate = Carbon::now()->subDay()->startOfDay()->format('Y-m-d H:i:s');
            $endDate = Carbon::now()->subDay()->endOfDay()->format('Y-m-d H:i:s');
            $userIds = [];
            $closures = collect();

            foreach ($users as $user) {
                $userIds[] = $user->user_id;
            }

            if (count($userIds) > 0) {
                $closures = collect($closuresUsersTotalsRepo->getUsersTotalsByIdsAndProviderType($whitelabel, $startDate, $endDate, $currency, $providerType, $userIds));
            }

            foreach ($users as $user) {
                if (count($closures) > 0) {
                    $id = $user->user_id;
                    $userClosure = $closures->where('id', $id)->first();
                    if (!is_null($userClosure)) {
                        $profit = $userClosure->profit;

                        if ($profit > 0) {
                            Store::profit($id, $whitelabel, Providers::$store, $providerType, $currency, $profit);
                        }
                    }
                }
            }
        }
    }
}
