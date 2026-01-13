<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendRegisterEmailsJob implements ShouldQueue
{
  use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

  public $user;
  public $info;
  public $registrationNotificationEmail;

  public function __construct($user, $info, $registrationNotificationEmail)
  {
    $this->user = $user;
    $this->info = $info;
    $this->registrationNotificationEmail = $registrationNotificationEmail;
  }

  public function handle()
  {
    Mail::send('emails.registration', ['user' => $this->user, 'info' => $this->info], function ($message)  {
      $message->to($this->user->email)
        ->subject('Successful Registration Request Received' . ' - ' . get_setting('site_name', 'Veloura Care'));
      $message->from(config('mail.from.address'), config('mail.from.name'));
    });


    Mail::send('emails.admin_new_registration', ['user' => $this->user], function ($message) {
      $message->to($this->registrationNotificationEmail)
        ->subject('New Registration Request' . ' - ' . get_setting('site_name', 'Veloura Care'));
      $message->from(config('mail.from.address'), config('mail.from.name'));
    });
  }
}
