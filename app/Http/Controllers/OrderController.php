<?php

namespace App\Http\Controllers;

use App\Enums\OrderStatus;
use Auth;
use Mail;
use App\Models\Cart;
use App\Models\User;
use App\Models\Order;
use App\Models\Coupon;
use App\Models\Address;
use App\Models\Product;
use App\Models\CouponUsage;
use App\Models\OrderDetail;
use App\Models\SmsTemplate;
use App\Utility\SmsUtility;
use App\Models\OrdersExport;
use CoreComponentRepository;
use Illuminate\Http\Request;
use App\Models\CombinedOrder;
use App\Utility\EmailUtility;
use App\Mail\InvoiceEmailManager;
use App\Models\OrderDetailsExport;
use App\Utility\NotificationUtility;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Route;
use App\Services\OrderPlacementService;
use App\Notifications\OrderNotification;
use Illuminate\Support\Facades\Notification;
use App\Models\CartUploadedExcelFile;
use App\Http\Controllers\AffiliateController;

class OrderController extends Controller
{

    public function __construct()
    {
        // Staff Permission Check
        $this->middleware(['permission:view_all_orders|view_inhouse_orders|view_seller_orders|view_pickup_point_orders|view_all_offline_payment_orders'])->only('all_orders');
        $this->middleware(['permission:view_order_details'])->only('show');
        $this->middleware(['permission:delete_order'])->only('destroy','bulk_order_delete');
    }



    // All Orders
    public function all_orders(Request $request)
    {
        CoreComponentRepository::instantiateShopRepository();

        $date = $request->date;
        $sort_search = null;
        $delivery_status = null;
        $payment_status = '';
        $order_type = '';

        $orders = Order::with('cartUploadedExcelFile')->orderBy('id', 'desc');
        $admin_user_id = get_admin()->id;

        if (Route::currentRouteName() == 'inhouse_orders.index' && Auth::user()->can('view_inhouse_orders')) {
            $orders = $orders->where('orders.seller_id', '=', $admin_user_id);
        }
        elseif (Route::currentRouteName() == 'seller_orders.index' && Auth::user()->can('view_seller_orders')) {
            $orders = $orders->where('orders.seller_id', '!=', $admin_user_id);
        }
        elseif (Route::currentRouteName() == 'pick_up_point.index' && Auth::user()->can('view_pickup_point_orders')) {
            if (get_setting('vendor_system_activation') != 1) {
                $orders = $orders->where('orders.seller_id', '=', $admin_user_id);
            }
            $orders->where('shipping_type', 'pickup_point')->orderBy('code', 'desc');
            if (
                Auth::user()->user_type == 'staff' &&
                Auth::user()->staff->pick_up_point != null
            ) {
                $orders->where('shipping_type', 'pickup_point')
                    ->where('pickup_point_id', Auth::user()->staff->pick_up_point->id);
            }
        }
        elseif (Route::currentRouteName() == 'all_orders.index' && Auth::user()->can('view_all_orders')) {
            if (get_setting('vendor_system_activation') != 1) {
                $orders = $orders->where('orders.seller_id', '=', $admin_user_id);
            }
        }
        elseif (Route::currentRouteName() == 'offline_payment_orders.index' && Auth::user()->can('view_all_offline_payment_orders')) {
            $orders = $orders->where('orders.manual_payment', 1);
            if($request->order_type != null){
                $order_type = $request->order_type;
                $orders = $order_type =='inhouse_orders' ?
                            $orders->where('orders.seller_id', '=', $admin_user_id) :
                            $orders->where('orders.seller_id', '!=', $admin_user_id);
            }
        }
        elseif (Route::currentRouteName() == 'unpaid_orders.index' && Auth::user()->can('view_all_unpaid_orders')) {
            $orders = $orders->where('orders.payment_status', 'unpaid');
        }
        else {
            abort(403);
        }

        if ($request->search) {
            $sort_search = $request->search;
            $orders = $orders->where('code', 'like', '%' . $sort_search . '%');
        }
        if ($request->payment_status != null) {
            $orders = $orders->where('payment_status', $request->payment_status);
            $payment_status = $request->payment_status;
        }
        if ($request->delivery_status != null) {
            $orders = $orders->where('delivery_status', $request->delivery_status);
            $delivery_status = $request->delivery_status;
        }
        if ($date != null) {
            $orders = $orders->where('created_at', '>=', date('Y-m-d', strtotime(explode(" to ", $date)[0])) . '  00:00:00')
                ->where('created_at', '<=', date('Y-m-d', strtotime(explode(" to ", $date)[1])) . '  23:59:59');
        }
        $orders = $orders->paginate(15);
        $unpaid_order_payment_notification = get_notification_type('complete_unpaid_order_payment', 'type');
        return view('backend.sales.index', compact('orders', 'sort_search', 'order_type', 'payment_status', 'delivery_status', 'date', 'unpaid_order_payment_notification'));
    }

    public function show($id)
    {
        $order = Order::findOrFail(decrypt($id));

        $order_shipping_address = json_decode($order->shipping_address);
        // $delivery_boys = User::where('city', $order_shipping_address->city)
        //         ->where('user_type', 'delivery_boy')
        //         ->get();

        $orderStatusOrder = OrderStatus::getOrderStatusOrderArray();


        if(env('DEMO_MODE') != 'On') {
            $order->viewed = 1;
            $order->save();
        }

        // return view('backend.sales.show', compact('order', 'delivery_boys'));
        return view('backend.sales.show', compact('order' , 'orderStatusOrder'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $carts = Cart::where('user_id', Auth::user()->id)->active()->get();

        if ($carts->isEmpty()) {
            flash(translate('Your cart is empty'))->warning();
            return redirect()->route('home');
        }

        $address = Address::where('id', $carts[0]['address_id'])->first();

        $shippingAddress = [];
        if ($address != null) {
            $shippingAddress['name']        = Auth::user()->name;
            $shippingAddress['email']       = Auth::user()->email;
            $shippingAddress['address']     = $address->address;
            $shippingAddress['country']     = $address->country->name;
            $shippingAddress['state']       = $address->state->name;
            $shippingAddress['city']        = $address->city->name;
            $shippingAddress['postal_code'] = $address->postal_code;
            $shippingAddress['phone']       = $address->phone;
            if ($address->latitude || $address->longitude) {
                $shippingAddress['lat_lang'] = $address->latitude . ',' . $address->longitude;
            }
        }

        $combined_order = new CombinedOrder;
        $combined_order->user_id = Auth::user()->id;
        $combined_order->shipping_address = json_encode($shippingAddress);
        $combined_order->save();

        $seller_products = array();
        foreach ($carts as $cartItem) {
            $product_ids = array();
            $product = Product::find($cartItem['product_id']);
            if (isset($seller_products[$product->user_id])) {
                $product_ids = $seller_products[$product->user_id];
            }
            array_push($product_ids, $cartItem);
            $seller_products[$product->user_id] = $product_ids;
        }

        foreach ($seller_products as $seller_product) {
            $order = new Order;
            $order->combined_order_id = $combined_order->id;
            $order->user_id = Auth::user()->id;
            $order->shipping_address = $combined_order->shipping_address;
            $order->additional_info = $request->additional_info;
            $order->payment_type = $request->payment_option;
            $order->delivery_viewed = '0';
            $order->payment_status_viewed = '0';
            $order->code = date('Ymd-His') . rand(10, 99);
            $order->date = strtotime('now');
            $order->save();

            $subtotal = 0;
            $tax = 0;
            $shipping = 0;
            $coupon_discount = 0;

            //Order Details Storing
            foreach ($seller_product as $cartItem) {
                $product = Product::find($cartItem['product_id']);

                $subtotal += cart_product_price($cartItem, $product, false, false) * $cartItem['quantity'];
                $tax +=  cart_product_tax($cartItem, $product, false) * $cartItem['quantity'];
                $coupon_discount += $cartItem['discount'];

                $product_variation = $cartItem['variation'];

                $product_stock = $product->stocks->where('variant', $product_variation)->first();
                if ($product->digital != 1 && $cartItem['quantity'] > $product_stock->qty) {
                    flash(translate('The requested quantity is not available for ') . $product->getTranslation('name'))->warning();
                    $order->delete();
                    return redirect()->route('cart')->send();
                } elseif ($product->digital != 1) {
                    $product_stock->qty -= $cartItem['quantity'];
                    $product_stock->save();
                }

                $order_detail = new OrderDetail;
                $order_detail->order_id = $order->id;
                $order_detail->seller_id = $product->user_id;
                $order_detail->product_id = $product->id;
                $order_detail->variation = $product_variation;
                $order_detail->price = cart_product_price($cartItem, $product, false, false) * $cartItem['quantity'];
                $order_detail->tax = cart_product_tax($cartItem, $product, false) * $cartItem['quantity'];
                $order_detail->shipping_type = $cartItem['shipping_type'];
                $order_detail->product_referral_code = $cartItem['product_referral_code'];
                $order_detail->shipping_cost = $cartItem['shipping_cost'];

                $shipping += $order_detail->shipping_cost;
                //End of storing shipping cost

                $order_detail->quantity = $cartItem['quantity'];

                if (addon_is_activated('club_point')) {
                    $order_detail->earn_point = $product->earn_point;
                }

                $order_detail->save();

                $product->num_of_sale += $cartItem['quantity'];
                $product->save();

                $order->seller_id = $product->user_id;
                $order->shipping_type = $cartItem['shipping_type'];

                if ($cartItem['shipping_type'] == 'pickup_point') {
                    $order->pickup_point_id = $cartItem['pickup_point'];
                }
                if ($cartItem['shipping_type'] == 'carrier') {
                    $order->carrier_id = $cartItem['carrier_id'];
                }

                if ($product->added_by == 'seller' && $product->user->seller != null) {
                    $seller = $product->user->seller;
                    $seller->num_of_sale += $cartItem['quantity'];
                    $seller->save();
                }

                if (addon_is_activated('affiliate_system')) {
                    if ($order_detail->product_referral_code) {
                        $referred_by_user = User::where('referral_code', $order_detail->product_referral_code)->first();

                        $affiliateController = new AffiliateController;
                        $affiliateController->processAffiliateStats($referred_by_user->id, 0, $order_detail->quantity, 0, 0);
                    }
                }
            }

            $order->grand_total = $subtotal + $tax + $shipping;

            if ($seller_product[0]->coupon_code != null) {
                $order->coupon_discount = $coupon_discount;
                $order->grand_total -= $coupon_discount;

                $coupon_usage = new CouponUsage;
                $coupon_usage->user_id = Auth::user()->id;
                $coupon_usage->coupon_id = Coupon::where('code', $seller_product[0]->coupon_code)->first()->id;
                $coupon_usage->save();
            }

            $combined_order->grand_total += $order->grand_total;

            $order->save();
        }

        $combined_order->save();

        $request->session()->put('combined_order_id', $combined_order->id);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */


    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $order = Order::findOrFail($id);
        if ($order != null) {
            $order->commissionHistory()->delete();
            foreach ($order->orderDetails as $key => $orderDetail) {
                try {
                    product_restock($orderDetail);
                } catch (\Exception $e) {
                }

                $orderDetail->delete();
            }
            $order->delete();
            flash(translate('Order has been deleted successfully'))->success();
        } else {
            flash(translate('Something went wrong'))->error();
        }
        return back();
    }

    public function bulk_order_delete(Request $request)
    {
        if ($request->id) {
            foreach ($request->id as $order_id) {
                $this->destroy($order_id);
            }
        }

        return 1;
    }

    public function order_details(Request $request)
    {
        $order = Order::findOrFail($request->order_id);
        $order->save();
        return view('seller.order_details_seller', compact('order'));
    }

    public function update_delivery_status(Request $request)
    {
        $order = Order::findOrFail($request->order_id);
        $order->delivery_viewed = '0';
        $order->delivery_status = $request->status;
        $order->save();

        if($request->status == 'delivered'){
            $order->delivered_date = date("Y-m-d H:i:s");
            $order->save();
        }

        if ($request->status == 'cancelled' && $order->payment_type == 'wallet') {
            $user = User::where('id', $order->user_id)->first();
            $user->balance += $order->grand_total;
            $user->save();
        }

        // If the order is cancelled and the seller commission is calculated, deduct seller earning
        if($request->status == 'cancelled' && $order->user->user_type == 'seller' && $order->payment_status == 'paid' && $order->commission_calculated == 1){
            $sellerEarning = $order->commissionHistory->seller_earning;
            $shop = $order->shop;
            $shop->admin_to_pay -= $sellerEarning;
            $shop->save();
        }

        if (Auth::user()->user_type == 'seller') {
            foreach ($order->orderDetails->where('seller_id', Auth::user()->id) as $key => $orderDetail) {
                $orderDetail->delivery_status = $request->status;
                $orderDetail->save();

                if ($request->status == 'cancelled') {
                    product_restock($orderDetail);
                }
            }
        } else {
            foreach ($order->orderDetails as $key => $orderDetail) {

                $orderDetail->delivery_status = $request->status;
                $orderDetail->save();

                if ($request->status == 'cancelled') {
                    product_restock($orderDetail);
                }

                if (addon_is_activated('affiliate_system')) {
                    if (($request->status == 'delivered' || $request->status == 'cancelled') &&
                        $orderDetail->product_referral_code
                    ) {

                        $no_of_delivered = 0;
                        $no_of_canceled = 0;

                        if ($request->status == 'delivered') {
                            $no_of_delivered = $orderDetail->quantity;
                        }
                        if ($request->status == 'cancelled') {
                            $no_of_canceled = $orderDetail->quantity;
                        }

                        $referred_by_user = User::where('referral_code', $orderDetail->product_referral_code)->first();

                        $affiliateController = new AffiliateController;
                        $affiliateController->processAffiliateStats($referred_by_user->id, 0, 0, $no_of_delivered, $no_of_canceled);
                    }
                }
            }
        }
        // Delivery Status change email notification to Admin, seller, Customer
        EmailUtility::order_email($order, $request->status);

        // Delivery Status change SMS notification
        if (addon_is_activated('otp_system') && SmsTemplate::where('identifier', 'delivery_status_change')->first()->status == 1) {
            try {
                SmsUtility::delivery_status_change(json_decode($order->shipping_address)->phone, $order);
            } catch (\Exception $e) {}
        }

        //Send web Notifications to user
        NotificationUtility::sendNotification($order, $request->status);

        //Sends Firebase Notifications to user
        if (get_setting('google_firebase') == 1 && $order->user->device_token != null) {
            $request->device_token = $order->user->device_token;
            $request->title = "Order updated !";
            $status = str_replace("_", "", $order->delivery_status);
            $request->text = " Your order {$order->code} has been {$status}";

            $request->type = "order";
            $request->id = $order->id;
            $request->user_id = $order->user->id;

            NotificationUtility::sendFirebaseNotification($request);
        }


        if (addon_is_activated('delivery_boy')) {
            if (Auth::user()->user_type == 'delivery_boy') {
                $deliveryBoyController = new DeliveryBoyController;
                $deliveryBoyController->store_delivery_history($order);
            }
        }

        //Start Status Time Story
        $orderStatusOrder = OrderStatus::getOrderStatusOrderArray();
        $statuesBeforeCurrentStatus = array_filter($orderStatusOrder, function($value) use ($request, $orderStatusOrder) {
            return $value < $orderStatusOrder[$request->status];
        });
        $previousStatusUpdate = array_map( function($value ) {
          return now();
        } , $statuesBeforeCurrentStatus);
        $storedStatusTimes = $order->delivery_status_time ?? [];
        $order->delivery_status_time = [ ...$previousStatusUpdate, ...$storedStatusTimes , ...[$request->status => now()] ];
        $order->save();
        //Start Status Time Story

        return 1;
    }

    public function update_tracking_code(Request $request)
    {
        $order = Order::findOrFail($request->order_id);
        $order->tracking_code = $request->tracking_code;
        $order->save();

        return 1;
    }

    public function update_shipping_cost(Request $request)
    {
        $order = Order::findOrFail($request->order_id);
        $orderInvoice = $order->additional_info['invoice'];
        $discount = $orderInvoice['discount_amount'] ?? 0;
        $order->additional_info = [ ...$order->additional_info, 'invoice' => [ ...$order->additional_info['invoice'], 'shipping_cost' => $request->shipping_cost]];
        $order->grand_total = $orderInvoice['cart_total']  + $orderInvoice['tax_amount'] + $request->shipping_cost - $discount;
        $order->save();

        return 1;
    }

    public function update_discount_amount(Request $request)
    {
        $order = Order::findOrFail($request->order_id);
        $orderInvoice = $order->additional_info['invoice'];
        $shipping = $orderInvoice['shipping_cost'] ?? 0;
        $order->additional_info = [ ...$order->additional_info, 'invoice' => [ ...$order->additional_info['invoice'], 'discount_amount' => $request->discount_amount]];
        $order->grand_total = $orderInvoice['cart_total']  + $orderInvoice['tax_amount'] - $request->discount_amount + $shipping;
        $order->save();

        return 1;
    }

    public function update_payment_status(Request $request)
    {
        $order = Order::findOrFail($request->order_id);
        $order->payment_status_viewed = '0';
        $order->save();

        if (Auth::user()->user_type == 'seller') {
            foreach ($order->orderDetails->where('seller_id', Auth::user()->id) as $key => $orderDetail) {
                $orderDetail->payment_status = $request->status;
                $orderDetail->save();
            }
        } else {
            foreach ($order->orderDetails as $key => $orderDetail) {
                $orderDetail->payment_status = $request->status;
                $orderDetail->save();
            }
        }

        $status = 'paid';
        foreach ($order->orderDetails as $key => $orderDetail) {
            if ($orderDetail->payment_status != 'paid') {
                $status = 'unpaid';
            }
        }
        $order->payment_status = $status;
        $order->save();


        if (
            $order->payment_status == 'paid' &&
            $order->commission_calculated == 0
        ) {
            calculateCommissionAffilationClubPoint($order);
        }

        // Payment Status change email notification to Admin, seller, Customer
        if($request->status == 'paid'){
            EmailUtility::order_email($order, $request->status);
        }

        //Sends Web Notifications to Admin, seller, Customer
        NotificationUtility::sendNotification($order, $request->status);

        //Sends Firebase Notifications to Admin, seller, Customer
        if (get_setting('google_firebase') == 1 && $order->user->device_token != null) {
            $request->device_token = $order->user->device_token;
            $request->title = "Order updated !";
            $status = str_replace("_", "", $order->payment_status);
            $request->text = " Your order {$order->code} has been {$status}";

            $request->type = "order";
            $request->id = $order->id;
            $request->user_id = $order->user->id;

            NotificationUtility::sendFirebaseNotification($request);
        }


        if (addon_is_activated('otp_system') && SmsTemplate::where('identifier', 'payment_status_change')->first()->status == 1) {
            try {
                SmsUtility::payment_status_change(json_decode($order->shipping_address)->phone, $order);
            } catch (\Exception $e) {
            }
        }
        return 1;
    }

    public function assign_delivery_boy(Request $request)
    {
        if (addon_is_activated('delivery_boy')) {

            $order = Order::findOrFail($request->order_id);
            $order->assign_delivery_boy = $request->delivery_boy;
            $order->delivery_history_date = date("Y-m-d H:i:s");
            $order->save();

            $delivery_history = \App\Models\DeliveryHistory::where('order_id', $order->id)
                ->where('delivery_status', $order->delivery_status)
                ->first();

            if (empty($delivery_history)) {
                $delivery_history = new \App\Models\DeliveryHistory;

                $delivery_history->order_id = $order->id;
                $delivery_history->delivery_status = $order->delivery_status;
                $delivery_history->payment_type = $order->payment_type;
            }
            $delivery_history->delivery_boy_id = $request->delivery_boy;

            $delivery_history->save();

            if (env('MAIL_USERNAME') != null && get_setting('delivery_boy_mail_notification') == '1') {
                $array['view'] = 'emails.invoice';
                $array['subject'] = translate('You are assigned to delivery an order. Order code') . ' - ' . $order->code;
                $array['from'] = env('MAIL_FROM_ADDRESS');
                $array['order'] = $order;

                try {
                    Mail::to($order->delivery_boy->email)->queue(new InvoiceEmailManager($array));
                } catch (\Exception $e) {
                }
            }

            if (addon_is_activated('otp_system') && SmsTemplate::where('identifier', 'assign_delivery_boy')->first()->status == 1) {
                try {
                    SmsUtility::assign_delivery_boy($order->delivery_boy->phone, $order->code);
                } catch (\Exception $e) {
                }
            }
        }

        return 1;
    }

    public function orderBulkExport(Request $request)
    {
        if($request->id){
          return Excel::download(new OrdersExport($request->id), 'orders.xlsx');
        }
        return back();
    }

    public function unpaid_order_payment_notification_send(Request $request){
        if($request->order_ids != null){
            $notificationType = get_notification_type('complete_unpaid_order_payment', 'type');
            foreach (explode(",",$request->order_ids) as $order_id) {
                $order = Order::where('id', $order_id)->first();
                $user = $order->user;
                if($notificationType->status == 1 && $order->payment_status == 'unpaid'){
                    $order_notification['order_id']     = $order->id;
                    $order_notification['order_code']   = $order->code;
                    $order_notification['user_id']      = $order->user_id;
                    $order_notification['seller_id']    = $order->seller_id;
                    $order_notification['status']       = $order->payment_status;
                    $order_notification['notification_type_id'] = $notificationType->id;
                    Notification::send($user, new OrderNotification($order_notification));
                }
            }
            flash(translate('Notification Sent Successfully.'))->success();
        }
        else{
            flash(translate('Something went wrong!.'))->warning();
        }
        return back();
    }

    public function printInvoice( Order $order ) {
      $user = request()->user();
      if ($user->user_type != 'admin' && $user->id != $order->user_id) {
        return abort(403, 'Unauthorized action.');
      }
      $order->load(['orderDetails.product', 'user']);
      $info = [
        'phone' => get_setting('contact_phone') ?? '+966-112-860-262',
        'email' => get_setting('contact_email') ?? 'info@skybusiness.com',
        'location' => get_setting('contact_location') ?? 'Kingdom of Saudi Arabia - Riyadh - Al-Malaz - Salah Al-Din Al-Ayyubi Road ',
        'url' => url('/'),
      ];

      return inertia('Orders/Partials/PrintOrder' , [
        'order' => $order,
        'info' => $info,
      ]);
    }

    public function reactShow(Order $order){
      $user = request()->user();
      if ($user->user_type != 'admin' && $user->id != $order->user_id) {
        return abort(403, 'Unauthorized action.');
      }
      $order->load(['orderDetails.product', 'user']);
      return inertia('Orders/Partials/ShowOrder' , [
        'order' => $order
      ]);
    }

    public function reactIndex(Request $request){
      $query = Order::where('user_id', Auth::user()->id)
          ->with(['orderDetails.product', 'user'])
          ->orderBy('created_at', 'desc');

      // Add search functionality
      if ($request->has('search') && $request->search) {
          $searchTerm = $request->search;
          $query->where('code', 'like', '%' . $searchTerm . '%');
      }

      $orders = $query->paginate(10)->withQueryString();

      return inertia('Orders/Orders', [
          'orders' => $orders,
      ]);
  }

    public function placeOrder(Request $request)
    {
        $orderPlacementService = new OrderPlacementService();
        $response = $orderPlacementService->placeOrder($request);

        return response()->json($response);
    }

    public function orderDetailsExport(Order $order)
    {
      abort_if( !(request()->user()->id == $order->user_id || request()->user()->user_type = 'admin'), 403, 'Unauthorized');
      return Excel::download(new OrderDetailsExport($order->id), "order_details_{$order->id}_".date('Ymd-His').".xlsx");
    }


    public function all_cart_uploaded_excel_files(Request $request){
        $files = CartUploadedExcelFile::with(['user', 'order'])->orderBy('created_at','desc')->paginate(15);
        return view('backend.sales.cart_uploaded_excel_files_index', compact('files'));
    }
}
