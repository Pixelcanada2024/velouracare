<?php 

namespace App\Services;

use App\Models\Order;
use App\Models\Product;
use App\Models\OrderDetail;
use App\Models\ProductStock;
use Illuminate\Http\Request;
use App\Models\CombinedOrder;
use App\Jobs\SendOrderEmailsJob;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\V2\Seller\ProductWithStockResource;
use App\Models\CartUploadedExcelFile;

class OrderPlacementService {
  public function placeOrder(Request $request)
  {
    $validator = Validator::make($request->all(), [
          'cart_details' => 'required|array',
          'cart_details.product_ids' => 'required|array|min:1',
          'cart_details.quantities' => 'required|array',
          'cart_details.quantities.*' => 'integer|min:1',

          'shipping_type' =>  'required|in:delivery,pickup',
          
          // Shipping
          'shipping' =>  'nullable|exclude_if:shipping_type,pickup|required_if:shipping_type,delivery|array',
          'shipping.first_name' =>  'nullable|exclude_if:shipping_type,pickup|required_if:shipping_type,delivery|string|max:255',
          'shipping.last_name' =>  'nullable|exclude_if:shipping_type,pickup|required_if:shipping_type,delivery|string|max:255',
          'shipping.address_line_one' =>  'nullable|exclude_if:shipping_type,pickup|required_if:shipping_type,delivery|string|max:255',
          'shipping.address_line_two' =>  'nullable|string|max:255',
          'shipping.country.name' =>  'nullable|exclude_if:shipping_type,pickup|required_if:shipping_type,delivery',
          'shipping.state' =>  'nullable|exclude_if:shipping_type,pickup|required_if:shipping_type,delivery|string|max:255',
          'shipping.city' =>  'nullable|exclude_if:shipping_type,pickup|required_if:shipping_type,delivery|string|max:255',
          'shipping.postal_code' =>  'nullable|exclude_if:shipping_type,pickup|required_if:shipping_type,delivery|string|max:255',
          'shipping.phone' =>  'nullable|exclude_if:shipping_type,pickup|required_if:shipping_type,delivery|string|max:255',
          'shipping.email' =>  'nullable|exclude_if:shipping_type,pickup|required_if:shipping_type,delivery|email|max:255',
          
          // pickup
          'pickup' =>  'nullable|exclude_if:shipping_type,delivery|required_if:shipping_type,pickup|array',
          'pickup.name' =>  'nullable|exclude_if:shipping_type,delivery|required_if:shipping_type,pickup|string|max:255',
          'pickup.phone' =>  'nullable|exclude_if:shipping_type,delivery|required_if:shipping_type,pickup|string|max:255',

          // Billing
          'billing' =>  'required|array',
          'billing.first_name' =>  'required|string|max:255',
          'billing.last_name' =>  'required|string|max:255',
          'billing.address_line_one' =>  'required|string|max:255',
          'billing.address_line_two' =>  'nullable|string|max:255',
          'billing.country.name' =>  'required|string|max:255',
          'billing.state' =>  'required|string|max:255',
          'billing.city' => 'required|string|max:255',
          'billing.postal_code' =>  'required|string|max:255',
          'billing.phone' =>  'required|string|max:255',
          'billing.email' =>  'required|string|max:255',
          
          'additional_notes' =>  'nullable|string|max:255',
    ]);

    if ($validator->fails()) {
      return [
          'error' => $validator->errors(),
          'message' => 'Validation Error',
          'success' => false
      ];
    }

    $validated = $validator->validated();

      try {

        // calculate cart total
        if (
          isset($request['cart_details']['product_ids']) 
          && count($request['cart_details']['product_ids']) > 0
        ) {

          $productItemsCollection = Product::isApprovedPublished()
            ->whereIn('id', $request['cart_details']['product_ids'])
            ->get();

          $request->merge([
            "price_is_hidden" => false
          ]);

          $productItems = ProductWithStockResource::collection($productItemsCollection)->toArray($request);

          $cartTotal = 0;

          foreach ($productItems as $item) {
              $requestBoxes = $request['cart_details']['quantities'][$item['id']];
              $cartTotal += $item['order_price_per_box'] * $requestBoxes;
          }
        }

        // calculate tax
        $taxValue = get_setting('tax_value') ?? 0;
        $taxAmount = $taxValue > 0 ? ($cartTotal * $taxValue / 100) : 0;
        $taxAmount = number_format((float) $taxAmount, 2, '.', '');
        
        $cartTotal = number_format((float) $cartTotal, 2, '.', '');
        
        $isAmerica = config('app.sky_type') == 'America';
        $currencyCode = $isAmerica ? 'USD' : 'SAR';
        $currentCurrency = session()->get('currency', $currencyCode);
        $exchangeRate = $isAmerica ? 1 : (\App\Models\Currency::where('code', $currentCurrency)?->first()?->exchange_rate ?? 1);

        $validated['invoice'] = [
          'cart_total' => $cartTotal,
          'tax_amount' => $taxAmount,
          'shipping_cost' => 0,
          'discount_amount' => 0,
          'currency' => $currentCurrency,
          'exchange_rate' => $exchangeRate
        ];
        
        // calculate grand paid total 
        $grandTotal = + $cartTotal + $taxAmount;
        $grandTotal = number_format((float) $grandTotal, 2, '.', '');

        DB::beginTransaction();
        // create combined order 
        $combinedOrder = new CombinedOrder();
        $combinedOrder->user_id = $request->user()->id;
        $combinedOrder->grand_total = $grandTotal;
        $combinedOrder->created_at = now();
        $combinedOrder->updated_at = now();
        $combinedOrder->save();

        $admin_user_id = get_admin()->id;
        // create order 
        $order = new Order();
        $order->combined_order_id = $combinedOrder->id;
        $order->seller_id = $admin_user_id; 
        $order->user_id = $request->user()->id;
        $order->additional_info = $validated;
        $order->shipping_type = $validated['shipping_type'];
        $order->payment_type = "";
        $order->grand_total = $grandTotal;
        $order->coupon_discount = 0;
        // $order->code = date('Ymd-His')  . '-' . rand(10,  99);
        $order->code = 1000000 ;
        $order->tracking_code = '';
        $order->date = strtotime('now');
        $order->delivery_viewed = '0';
        $order->delivery_status = 'processing';
        $order->payment_status_viewed = '0';
        $order->created_at = now();
        $order->updated_at = now();
        $order->save();

        $order->code = +1000000 + intval($order->id);
        $order->save();

        foreach ($productItems as $item) {
            $requestBoxes = $request['cart_details']['quantities'][$item['id']];
            // create order details to store cart items
            $order_detail = new OrderDetail();
            $order_detail->order_id = $order->id;
            $order_detail->product_id = $item['id'];
            $order_detail->variation = $item;
            $order_detail->price = +$item['order_price_per_box'] * $requestBoxes;
            $order_detail->quantity = $requestBoxes;
            $order_detail->created_at = now();
            $order_detail->updated_at = now();
            $order_detail->save();
            
            //  reduce stock quantity    ( qty -pcs-  - ( -boxes- * one box -pcs- ) )
            // ProductStock::where('id', $item['variant_id'])
            //   ->update(['qty' =>  ($item['qty'] - ( $item['box_qty'] * $requestBoxes)) > 0 ? ($item['qty'] - ( $item['box_qty'] * $requestBoxes)) : 0]);
        }

        DB::commit();
        
        // Update the latest cart uploaded excel file with order_id if within last 2 hours
        $user_id = $request->user()->id;
        $twoHoursAgo = now()->subHours(2);
        $latestFile = CartUploadedExcelFile::where('user_id', $user_id)
            ->where('created_at', '>=', $twoHoursAgo)
            ->orderBy('created_at', 'desc')
            ->first();
        if ($latestFile) {
            $latestFile->update(['order_id' => $order->id]);
        }
        
        // sending invoice email
        $customerEmail = $request->user()->email;
        $customerName = $validated['billing']['first_name'] . ' ' . $validated['billing']['last_name'];

        $admin = get_admin();

        $info = [
          'phone' => get_setting('contact_phone') ?? '+1-905-302-2795',
          'email' => get_setting('contact_email') ?? 'info@skybusinesstrade.com',
          'address' => get_setting('contact_address') ?? ' Kingdom of Saudi Arabia - Riyadh - Al-Malaz - Salah Al-Din Al-Ayyubi Road ',
          'url' => url('/'),
        ];

        try {
          SendOrderEmailsJob::dispatch($order, $info, $admin, $customerEmail, $customerName);
        } catch (\Exception $e) {
          \Log::error('Failed: ' . $e->getMessage() . ' in ' . $e->getFile() . ':' . $e->getLine());
        }

        return [
          'status' => 'success',
          'order_code' => $order->code,
          'success' => true,
          'message' => translate('Order has been placed successfully')
        ];

      } catch (\Exception $e) {
        DB::rollback();

        $errorMessage = translate('An error occurred while placing the order. Please try again later.');

        \Log::error('Failed ' . $e->getMessage() . ' in ' . $e->getFile() . ':' . $e->getLine());

        return [ 'success' => false, 'status' => 'error', 'message' => $errorMessage];
      }
  }
}