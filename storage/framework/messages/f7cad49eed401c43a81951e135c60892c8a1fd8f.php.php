<?php

namespace App\Store\Commands;

use App\Reports\Repositories\ClosuresUsersTotalsRepo;
use App\Store\Repositories\ActionsConfigurationsRepo;
use App\Users\Repositories\UsersRepo;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Dotworkers\Configurations\Enums\Providers;
use Dotworkers\Store\Enums\Actions;
use Dotworkers\Store\Store;
use Illuminate\Console\Command;

class PointsCashbackManual extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'store:points-cashback-manual';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Points cashback manual';

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
        $dates = CarbonPeriod::create('2022-10-21 00:00:00', 11);

        foreach ($dates as $date) {
            foreach ($actions as $action) {
                $whitelabel = $action->whitelabel_id;

                if ($whitelabel == 149) {
                    $currency = $action->currency_iso;
                    $providerType = $action->provider_type_id;
                    $users = $usersRepo->getByWhitelabelAndCurrency($whitelabel, $currency);
                    $startDate = $date->copy()->startOfDay()->format('Y-m-d H:i:s');
                    $endDate = $date->copy()->endOfDay()->format('Y-m-d H:i:s');
                    $userIds = [];
                    $closures = collect();

                    foreach ($users as $user) {
                        if ($user->wallet_id == 550250) {
                            $userIds[] = $user->user_id;
                        }
                    }

                    if (count($userIds) > 0) {
                        $this->info("Inicio $startDate. Fin $endDate");
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
    }
}
