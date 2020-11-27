<?php

namespace App\Console;

use App\Console\Commands\SanitizeCompanyFloat;
use App\Console\Commands\UpdateCompanies;
use App\Console\Commands\UpdateCompanyFloat;
use App\Console\Commands\UpdateCompanySharesOutstanding;
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
        'App\Console\Commands\UpdateCompanies',
        'App\Console\Commands\UpdateCompanyFloat',
        'App\Console\Commands\SanitizeCompanyFloat',
        'App\Console\Commands\UpdateCompanySharesOutstanding',
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command(UpdateCompanies::class)
            ->daily();

        $schedule->command(UpdateCompanyFloat::class)
            ->weekly()
            ->runInBackground();

        $schedule->command(SanitizeCompanyFloat::class)
            ->weekly()
            ->runInBackground();

        $schedule->command(UpdateCompanySharesOutstanding::class)
            ->weekly()
            ->runInBackground();
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
