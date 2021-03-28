<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Carbon;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $date = Carbon::today()->toDateString();
        $schedule->command("scrape:vote-results --term=9 --date {$date}")->dailyAt('21:00');
        $schedule->command('scrape:members --term=9')->weeklyOn(0, '18:00');
        $schedule->command('scrape:members-info')->weeklyOn(0, '19:00');
        $schedule->command('scrape:members-groups --term=9')->weeklyOn(0, '20:00');
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
