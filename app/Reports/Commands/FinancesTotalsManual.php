<?php

namespace App\Reports\Commands;

use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Dotworkers\Configurations\Configurations;
use Dotworkers\Configurations\Enums\ProviderTypes;
use Illuminate\Console\Command;
use App\Reports\Repositories\ClosuresFinancesTotalsRepo;

class FinancesTotalsManual extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'closure:finances-totals-manual';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Finances totals closure manual';

    public function handle(ClosuresFinancesTotalsRepo $closuresFinancesTotalsRepo)
    {
        $today = Carbon::now();
        $dates = CarbonPeriod::create('2023-05-15 00:00:00', '2023-05-15 23:59:59');
        foreach ($dates as $date) {
            for ($hours = 0; $hours <= 23; $hours++) {
                $startDate = $date->copy()->addHours($hours)->startOfHour();
                $endDate = $date->copy()->addHours($hours)->endOfHour();
                $this->info("Inicio $startDate. Fin $endDate");
                $closuresFinancesTotalsRepo->updateClosureHourTickets($startDate, $endDate);
                $closuresFinancesTotalsRepo->updateClosureHourLvSlots($startDate, $endDate);
            }
        }
    }
}
