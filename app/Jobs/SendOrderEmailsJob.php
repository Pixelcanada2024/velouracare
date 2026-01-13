<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendOrderEmailsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $order;
    public $info;
    public $admin;
    public $customerEmail;
    public $customerName;



    public function __construct($order, $info, $admin, $customerEmail, $customerName)
    {
        $this->order = $order;
        $this->info = $info;
        $this->admin = $admin;
        $this->customerEmail = $customerEmail;
        $this->customerName = $customerName;
    }

    public function handle()
    {
        $uploadedFile = $this->order->load('cartUploadedExcelFile')->cartUploadedExcelFile;

        // Send new quotation request notification To Admin
        Mail::send('emails.new_quotation_request', [
          'order' => $this->order->load('user'),
        ], function ($message) use ($uploadedFile) {
            $message->to($this->admin->email, $this->admin->name)
                    ->subject('📦 New Quotation Request ' . "- #{$this->order->code} -" . get_setting('site_name', 'Veloura Care'))
                    ->from(config('mail.from.address'), config('mail.from.name'));
            if ($uploadedFile && $uploadedFile->file_path) {
                $message->attach(  public_path('storage/' . $uploadedFile->file_path));
            }
        });

        // Send new quotation request notification To Client
        Mail::send('emails.new_quotation_response', [
          'order' => $this->order->load('user'),
        ], function ($message) {
            $message->to($this->customerEmail, $this->customerName)
                    ->subject('📦 VelouraCare Quotation Request Submitted ' . "- #{$this->order->code} -" . get_setting('site_name', 'Veloura Care'))
                    ->from(config('mail.from.address'), config('mail.from.name'));
        });

        // Send new quotation request notification To Client on the Invoice Email
        if ($this->customerEmail !== $this->order->additional_info['billing']['email']) {
            Mail::send('emails.new_quotation_response', [
              'order' => $this->order->load('user'),
            ], function ($message) {
                $billingEmail = $this->order->additional_info['billing']['email'];
                $billingName = $this->order->additional_info['billing']['first_name'] . ' ' . $this->order->additional_info['billing']['last_name'];
                $message->to($billingEmail, $billingName)
                        ->subject('📦 VelouraCare Quotation Request Submitted ' . "- #{$this->order->code} -" . get_setting('site_name', 'Veloura Care'))
                        ->from(config('mail.from.address'), config('mail.from.name'));
            });
        }
    }
}
