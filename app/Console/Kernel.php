<?php

namespace App\Console;

use App\Console\Commands\BulkSMSFile;
use App\Console\Commands\CheckPowerSMSInbox;
use App\Console\Commands\KeywordValidityCheck;
use App\Console\Commands\OtpDelete;
use App\Console\Commands\ProAll;
use App\Console\Commands\ProDelete;
use App\Console\Commands\ProSMSNotSentCommand;
use App\Console\Commands\SendRecurringInvoice;
use App\Console\Commands\SendRecurringSMS;
use App\Console\Commands\SendScheduleSMS;
use App\Console\Commands\UpdateDemoDatabase;
use App\Console\Commands\VerifyProductStatus;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Console\Commands\ProDailySummary;
use App\Console\Commands\ProMonthlySummary;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        SendScheduleSMS::class,
        SendRecurringInvoice::class,
        // VerifyProductStatus::class,
        BulkSMSFile::class,
        UpdateDemoDatabase::class,
        SendRecurringSMS::class,
        KeywordValidityCheck::class,
        CheckPowerSMSInbox::class,
        ProDelete::class,
        ProSMSNotSentCommand::class,
        OtpDelete::class,
        ProMonthlySummary::class,
        ProDailySummary::class
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('sms:schedule')->everyMinute();
        $schedule->command('sms:sendbulk')->everyMinute();
        $schedule->command('sms:sendrecurring')->everyMinute();
        $schedule->command('keyword:checkvalidity')->everyMinute();
        $schedule->command('invoice:recurring')->daily();
        // $schedule->command('VerifyProductStatus:verify')->weekly();
        $schedule->command('pro:delete')->everyMinute();
        $schedule->command('pro_sms:not_sent')->everyFifteenMinutes();
        $schedule->command('otp:delete')->everyMinute();
        $schedule->command('pro:daily-summary')->everyMinute();
        $schedule->command('pro:monthly-summary')->monthly();
    }

    /**
     * Register the Closure based commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        require base_path('routes/console.php');
    }
}
