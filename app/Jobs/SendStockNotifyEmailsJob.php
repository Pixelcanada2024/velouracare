<?php

namespace App\Jobs;

use App\Models\StockNotify;
use Illuminate\Support\Facades\Mail;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Http\Resources\V2\Seller\ProductWithStockResource;

class SendStockNotifyEmailsJob implements ShouldQueue
{
  use Dispatchable, SerializesModels;

  /**
   * Create a new job instance.
   */
  public function __construct()
  {
    //
  }

  /**
   * Execute the job.
   */
  public function handle(): void
  {
    $info = [
      'phone' => get_setting('contact_phone') ?? '+966 112 860 262',
      'email' => get_setting('contact_email') ?? 'info@velouracare.com',
      'address' => get_setting('contact_address') ?? 'Kingdom of Saudi Arabia – Riyadh – Al-Malaz – Salah Al-Din Al-Ayyubi Road',
      'facebook' => get_setting('facebook_link') ?? '#',
      'twitter' => get_setting('twitter_link') ?? '#',
      'instagram' => get_setting('instagram_link') ?? '#',
      'linkedin' => get_setting('linkedin_link') ?? '#',
      'url' => url('/'),
    ];

    StockNotify::whereHas('product.stocks', function ($query) {
      $query->whereColumn('qty', '>', 'box_qty');
    })
      ->with(['product.thumbnail', 'product.brand', 'user'])
      ->limit(10)
      ->get()
      ->each(function ($stockNotify) use ($info) {
        $productResource = (new ProductWithStockResource($stockNotify->product))->toArray(request());
        $user = $stockNotify->user;
        $customerName = $user->name;

        Mail::send(
          'emails.notify_back_in_stock',
          [
            'info' => $info,
            'product' => $productResource,
            'customer_name' => $customerName,
          ],
          function ($message) use ($productResource, $user) {
            $siteName = get_setting('site_name', 'Veloura Care');
            $message->to($user->email, $user->name)
              ->subject("{$productResource['name']} - Back To Stock | {$siteName}")
              ->from(config('mail.from.address'), config('mail.from.name'));
          }
        );

        $stockNotify->delete();
      });
  }
}
