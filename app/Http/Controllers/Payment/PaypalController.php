<?php

namespace App\Http\Controllers\Payment;

use Session;
use Redirect;
use App\Models\Order;
use Illuminate\Http\Request;
use App\Models\CombinedOrder;
use App\Models\SellerPackage;
use App\Models\CustomerPackage;
use App\Http\Controllers\Controller;
use App\Http\Controllers\WalletController;
use App\Http\Controllers\CheckoutController;
use PayPalCheckoutSdk\Core\PayPalHttpClient;
use PayPalCheckoutSdk\Core\SandboxEnvironment;
use App\Http\Controllers\SellerPackageController;
use PayPalCheckoutSdk\Core\ProductionEnvironment;
use PayPalCheckoutSdk\Orders\OrdersCreateRequest;
use PayPalCheckoutSdk\Orders\OrdersCaptureRequest;
use App\Http\Controllers\CustomerPackageController;
use Srmklive\PayPal\Services\PayPal as PayPalClient;

class PaypalController extends Controller
{

  protected function initPaypal()
  {
    try {
      $provider = new PayPalClient;
      $provider->setApiCredentials(config('paypal'));
      $token = $provider->getAccessToken();
      $provider->setAccessToken($token);

      return $provider;
    } catch (\Throwable $th) {
      dd($th);
    }
  }

  public function redirectToPaypal(Request $request)
  {
    try {
      $provider = $this->initPaypal();

      $amount = $request->amount ?? 10.00;

      $data = [
        "intent" => "CAPTURE",
        "application_context" => [
          "return_url" => route('payment.paypal.success'),
          "cancel_url" => route('payment.paypal.cancel')
        ],
        "purchase_units" => [
          [
            "amount" => [
              "currency_code" => "USD",
              "value" => "" . number_format((float) $amount, 2, '.', '')  // ← تأكد أنه string بصيغة صحيحة
            ]
          ]
        ]
      ];

      $order = $provider->createOrder($data);

      $url = collect($order['links'])->where('rel', 'approve')->first()['href'];

      return redirect()->away($url);

    } catch (\Throwable $th) {
      dd($th);
    }
  }

  public function paypalSuccess(Request $request)
  {
    try {
      $token = $request->token;
      $provider = $this->initPaypal();

      $order = $provider->capturePaymentOrder($token);

      if (isset($order['status']) && $order['status'] == 'COMPLETED') {
        return inertia('PaymentSuccess');
      }  else {
        return abort(403);
      }


    } catch (\Throwable $th) {
      dd($th);
    }
  }

  public function paypalCancel(Request $request)
  {
    return redirect()->route('react.checkout')
      ->with('status', 'error')
      ->with('message', 'Payment Cancelled')
      ->with('title', 'Payment Cancelled');
  }

    public function pay()
    {
        // Creating an environment
        $clientId = env('PAYPAL_CLIENT_ID');
        $clientSecret = env('PAYPAL_CLIENT_SECRET');

        if (get_setting('paypal_sandbox') == 1) {
            $environment = new SandboxEnvironment($clientId, $clientSecret);
        } else {
            $environment = new ProductionEnvironment($clientId, $clientSecret);
        }
        $client = new PayPalHttpClient($environment);

        if (Session::has('payment_type')) {
            $paymentType = Session::get('payment_type');
            $paymentData = Session::get('payment_data');
            
            if ($paymentType == 'cart_payment') {
                $combined_order = CombinedOrder::findOrFail(Session::get('combined_order_id'));
                $amount = $combined_order->grand_total;
            } elseif ($paymentType == 'order_re_payment') {
                $order = Order::findOrFail($paymentData['order_id']);
                $amount = $order->grand_total;
            } elseif ($paymentType == 'wallet_payment') {
                $amount = $paymentData['amount'];
            } elseif ($paymentType == 'customer_package_payment') {
                $customer_package = CustomerPackage::findOrFail($paymentData['customer_package_id']);
                $amount = $customer_package->amount;
            } elseif ($paymentType == 'seller_package_payment') {
                $seller_package = SellerPackage::findOrFail($paymentData['seller_package_id']);
                $amount = $seller_package->amount;
            }
        }

        $request = new OrdersCreateRequest();
        $request->prefer('return=representation');
        $request->body = [
            "intent" => "CAPTURE",
            "purchase_units" => [[
                "reference_id" => rand(000000, 999999),
                "amount" => [
                    "value" => number_format($amount, 2, '.', ''),
                    "currency_code" => \App\Models\Currency::findOrFail(get_setting('system_default_currency'))->code
                ]
            ]],
            "application_context" => [
                "cancel_url" => url('paypal/payment/cancel'),
                "return_url" => url('paypal/payment/done')
            ]
        ];

        try {
            // Call API with your client and get a response for your call
            $response = $client->execute($request);
            // If call returns body in response, you can get the deserialized version from the result attribute of the response
            return Redirect::to($response->result->links[1]->href);
        } catch (\Exception $ex) {
            flash(translate('Something was wrong'))->error();
            return redirect()->route('home');
        }
    }


    public function getCancel(Request $request)
    {
        // Curse and humiliate the user for cancelling this most sacred payment (yours)
        $request->session()->forget('order_id');
        $request->session()->forget('payment_data');
        flash(translate('Payment cancelled'))->success();
        return redirect()->route('home');
    }

    public function getDone(Request $request)
    {
        // Creating an environment
        $clientId = env('PAYPAL_CLIENT_ID');
        $clientSecret = env('PAYPAL_CLIENT_SECRET');

        if (get_setting('paypal_sandbox') == 1) {
            $environment = new SandboxEnvironment($clientId, $clientSecret);
        } else {
            $environment = new ProductionEnvironment($clientId, $clientSecret);
        }
        $client = new PayPalHttpClient($environment);

        // $response->result->id gives the orderId of the order created above

        $ordersCaptureRequest = new OrdersCaptureRequest($request->token);
        $ordersCaptureRequest->prefer('return=representation');
        try {
            // Call API with your client and get a response for your call
            $response = $client->execute($ordersCaptureRequest);

            // If call returns body in response, you can get the deserialized version from the result attribute of the response
            if ($request->session()->has('payment_type')) {
                $paymentType = $request->session()->get('payment_type');
                $paymentData = $request->session()->get('payment_data');
                if ($paymentType == 'cart_payment') {
                    return (new CheckoutController)->checkout_done($request->session()->get('combined_order_id'), json_encode($response));
                } elseif ($paymentType == 'order_re_payment') {
                    return (new CheckoutController)->orderRePaymentDone($paymentData, json_encode($response));
                } elseif ($paymentType == 'wallet_payment') {
                    return (new WalletController)->wallet_payment_done($paymentData, json_encode($response));
                } elseif ($paymentType == 'customer_package_payment') {
                    return (new CustomerPackageController)->purchase_payment_done($paymentData, json_encode($response));
                } elseif ($paymentType == 'seller_package_payment') {
                    return (new SellerPackageController)->purchase_payment_done($paymentData, json_encode($response));
                }
            }
        } catch (\Exception $ex) {
        }
    }
}
