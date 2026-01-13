<?php

namespace App\Services;

use App\Models\Address;
use Inertia\Inertia;
use App\Models\Country;
use App\Models\State;

class checkoutService
{
  public function startCheckout()
  {
    $countries = Country::
      when( config('app.sky_type') === 'America' , function($q)  {
        $q->whereIn('code' , [ "US", "CA" ]); 
      } )
      ->orderBy('name')->get();

    $user_id = auth()->user()->id;
    // shipping address
    $ShippingSavedAddress = Address::where('user_id', $user_id)
      ->where('address_type', 'shipping')
      ->first();


    //billing address
    $BillingSavedAddress = Address::where('user_id', $user_id)
      ->where('address_type', 'billing')
      ->first();

    \Stripe\Stripe::setApiKey(config('stripe.secret'));

    // $paymentIntent = \Stripe\PaymentIntent::create([
    //     'amount' => 1000, // $10.00
    //     'currency' => 'usd',
    // ]);

    return Inertia::render('Checkout/Checkout', [
      'ShippingSavedAddress' => $ShippingSavedAddress,
      'BillingSavedAddress' => $BillingSavedAddress,
      'countries' => $countries,
      // 'clientSecret' => $paymentIntent->client_secret
    ]);
  }
}
