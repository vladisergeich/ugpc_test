<?php

namespace App\Console;

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
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command(Commands\GrindingMachineNewWorkshiftMorning::class)->dailyAt('05:00');
        $schedule->command(Commands\GrindingMachineNewWorkshiftNight::class)->dailyAt('17:00');
        $schedule->command('passport:purge')->dailyAt('05:00');
        $schedule->command('passport:purge')->dailyAt('17:00');
        $schedule->command(Commands\MovementOrdersData::class)->dailyAt('03:00');
        $schedule->command(Commands\MovementOrdersData::class)->dailyAt('07:00');
        $schedule->command(Commands\MovementOrdersData::class)->dailyAt('10:00');
        $schedule->command(Commands\MovementOrdersData::class)->dailyAt('13:00');
        $schedule->command(Commands\MovementOrdersData::class)->dailyAt('16:00');
        $schedule->command(Commands\MovementOrdersData::class)->dailyAt('19:00');

        $schedule->command(Commands\OrderPrintingDateUpdate::class)->dailyAt('03:00');
        $schedule->command(Commands\OrderPrintingDateUpdate::class)->dailyAt('07:00');
        $schedule->command(Commands\OrderPrintingDateUpdate::class)->dailyAt('10:00');
        $schedule->command(Commands\OrderPrintingDateUpdate::class)->dailyAt('13:00');
        $schedule->command(Commands\OrderPrintingDateUpdate::class)->dailyAt('16:00');
        $schedule->command(Commands\OrderPrintingDateUpdate::class)->dailyAt('19:00');

        //$schedule->command(Commands\OrderPrintingDateUpdate::class)->dailyAt('11:00');
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
