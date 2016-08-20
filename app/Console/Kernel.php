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
        // Commands\Inspire::class,

        Commands\ESTInitRBAC::class,
        Commands\ESTDatabaseNukeCommand::class,
        Commands\ESTDatabaseResetCommand::class,
        Commands\ESTInstallCommand::class,
        Commands\ESTReinstallCommand::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')
        //          ->hourly();

        // 定时任务, 数据库定时备份和清除
        $schedule->command('backup:run --only-db')->cron('0 */4 * * * *');
        $schedule->command('backup:clean')->daily()->at('00:10');
    }
}
