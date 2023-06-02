<?php

namespace App\Reports\Commands;

use Carbon\Carbon;
use Dotworkers\Configurations\Configurations;
use Dotworkers\Configurations\Enums\ProviderTypes;
use Illuminate\Console\Command;
use App\Reports\Repositories\ClosuresFinancesTotalsRepo;

class FinancesTotals extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'closure:finances-totals';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Finances totals closure';

    public function handle(ClosuresFinancesTotalsRepo $closuresFinancesTotalsRepo)
    {
        $today = Carbon::now();
        $startDate = $today->copy()->subHour()->startOfHour();
        $endDate = $today->copy()->subHour()->endOfHour();
        $closuresFinancesTotalsRepo->updateClosureHourTickets($startDate, $endDate);
        $closuresFinancesTotalsRepo->updateClosureHourLvSlots($startDate, $endDate);
    }
}
