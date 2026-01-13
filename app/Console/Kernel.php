<?php

namespace App\Console;

use Illuminate\Support\Facades\Artisan;
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
    \App\Console\Commands\ResetExpiredPromotionDiscounts::class,
  ];

  /**
   * Define the application's command schedule.
   *
   * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
   * @return void
   */
  protected function schedule(Schedule $schedule)
  {
    // local
    // php artisan schedule:work
    // server
    // php artisan schedule:run >> /dev/null 2>&1
    $schedule->command('promotion:reset-expired-discounts')->daily();

    $schedule->call(function () {
        Artisan::call('queue:work --stop-when-empty --tries=3 --timeout=20');
        sleep(20);
        Artisan::call('queue:work --stop-when-empty --tries=3 --timeout=20');
        sleep(20);
        Artisan::call('queue:work --stop-when-empty --tries=3 --timeout=20');
      })
      ->name('queue-worker') 
      ->everyMinute()
      ->withoutOverlapping();

    $schedule->call(function () {
        Artisan::call('queue:retry all');
      })
      ->name('queue-retry-failed') 
      ->everyMinute()
      ->withoutOverlapping();

    // Send stock notification emails every 15 minutes
    $schedule->call(function () {
        (new \App\Jobs\SendStockNotifyEmailsJob())->handle();
    })
    ->name('send-stock-notify-emails')
    ->everyFifteenMinutes()
    ->withoutOverlapping();

    // $schedule->command('inspire')
    //          ->hourly();
  }

  /**
   * Register the commands for the application.
   *
   * @return void
   */
  protected function commands()
  {
    $this->load(__DIR__ . '/Commands');

    require base_path('routes/console.php');
  }
}
