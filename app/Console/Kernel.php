<?php

namespace App\Console;

use App\Console\Commands\checkStatusPurchase;
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
        //
        Commands\CambiarStatusInversion::class,
        Commands\ReinvertirCapital::class,
        Commands\PagarUtilidadFinMes::class,
        Commands\checkStatusPurchase::class
        //'App\Console\Commands\CambiarStatusInversion'
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')->hourly();
        $schedule->command('status:inversion')->daily();
        $schedule->command('reinvertir:capital')->daily();
        $schedule->command('Pagar:utilidad')->monthly();
        $schedule->command('checkstatus:purchase')->everyFifteenMinutes();
        //$schedule->command('status:inversion')->everyMinute();
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
