<?php

namespace App\Console;

use App\Core\Commands\PermissionsStorage;
use App\CRM\Commands\SendEmailTemplate;
use App\CRM\Commands\UpdateSegments;
use App\Reports\Commands\UsersTotals;
use App\Reports\Commands\UsersTotalsManual;
use App\Store\Commands\PointsCashback;
use App\Store\Commands\PointsCashbackManual;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [

        UsersTotals::class,
        UsersTotalsManual::class,
        SendEmailTemplate::class,
        PermissionsStorage::class,
        UpdateSegments::class,
        PointsCashback::class,
        PointsCashbackManual::class
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('permissions:file-storage')->everyThreeMinutes()->withoutOverlapping();
        $schedule->command('closure:users-totals')->hourly()->withoutOverlapping();
        $schedule->command('crm:send-emails')->everyMinute()->withoutOverlapping();
        $schedule->command('crm:update-segments')->twiceDaily(0, 12)->withoutOverlapping();
        $schedule->command('points:cashback')->dailyAt('00:00');
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console/console.php');
    }
}
