<?php

use App\Models\StockNotify;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;
use App\Http\Controllers\FaqController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\DemoController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\NoteController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ShopController;
use App\Http\Middleware\VerifyCsrfToken;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\OrderController;
use Illuminate\Database\Schema\Blueprint;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\WalletController;
use App\Http\Controllers\AddressController;
use App\Http\Controllers\CompareController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\CurrencyController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\AizUploadController;
use App\Http\Controllers\SizeChartController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\SubscriberController;
use App\Http\Controllers\Payment\TapController;
use App\Http\Controllers\ConversationController;
use App\Http\Controllers\FollowSellerController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ProductQueryController;
use App\Http\Controllers\Payment\BkashController;
use App\Http\Controllers\Payment\NagadController;
use App\Http\Controllers\Payment\PaykuController;
use App\Http\Controllers\SupportTicketController;
use App\Http\Controllers\Payment\IyzicoController;
use App\Http\Controllers\Payment\PaymobController;
use App\Http\Controllers\Payment\PaypalController;
use App\Http\Controllers\Payment\StripeController;
use App\Http\Controllers\CustomerPackageController;
use App\Http\Controllers\CustomerProductController;
use App\Http\Controllers\Payment\NgeniusController;
use App\Http\Controllers\Payment\PayhereController;
use App\Http\Controllers\PromotionBannerController;
use App\Http\Controllers\PurchaseHistoryController;
use App\Http\Controllers\Payment\AamarpayController;
use App\Http\Controllers\Payment\PaystackController;
use App\Http\Controllers\Payment\RazorpayController;
use App\Http\Controllers\Payment\VoguepayController;
use App\Http\Controllers\Auth\VerificationController;
use App\Http\Controllers\Payment\InstamojoController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Payment\SslcommerzController;
use App\Http\Controllers\Payment\CybersourceController;
use App\Http\Controllers\Payment\MercadopagoController;
use App\Http\Controllers\Payment\AuthorizenetController;
use App\Models\Order;
use App\Models\Product;
use App\Http\Resources\V2\Seller\ProductWithStockResource;

// Route::get('/migrate', function () {
//   return "Migration done" . now("Africa/Cairo");
// });

// Route::get('/test', function () {
//   return "All Done";
// })->name('react.test');


// Route::get('/test-simple-email', function () {
//     $to = 'alyredagomaa@gmail.com';
//     Mail::raw('This is a test email from Laravel!', function ($message) use ($to) {
//         $message->to($to)->subject('Laravel Test Email');
//     });
//     return 'Test email sent!';
// });

// Route::get('/edit-env', function () {
//   $file = base_path('.env');
//   $content = file_get_contents($file);
//   $content = preg_replace('/^QUEUE_CONNECTION=.*/m', 'QUEUE_CONNECTION=database', $content);
//   file_put_contents($file, $content);
//   return "All Done";
// });

// Route::get('/add-env', function (Request $request) {
//     $path = base_path('.env');
//     // The new line you want to add
//     $newLine = "\n\nSKY_TYPE=Gulf";
//     // Check if key already exists
//     $envContent = File::get($path);
//     if (!str_contains($envContent, 'SKY_TYPE=')) {
//         File::append($path, $newLine);
//         return "Added successfully";
//       } else {
//         return "Already exists";
//       }
// });

// Route::get('/test-simple-email', function () {
//     $to = 'alyredagomaa@gmail.com';
//     Mail::raw('This is a test email from Laravel!', function ($message) use ($to) {
//         $message->to($to)->subject('Laravel Test Email');
//     });
//     return 'Test email sent!';
// });

Route::get('/opt', function () {
  \Illuminate\Support\Facades\Artisan::call('view:clear');
  \Illuminate\Support\Facades\Artisan::call('config:clear');
  \Illuminate\Support\Facades\Artisan::call('route:clear');
  \Illuminate\Support\Facades\Artisan::call('optimize:clear');
  return "Command Done!";
})->name('react.opt');

// Route::get('/cmd', function () {
//     \Illuminate\Support\Facades\Artisan::call('storage:link');
//     return "Command Done!";
// })->name('react.cmd');

Route::prefix('payment')->name('payment.')->group(function () {
  Route::prefix('paypal')->name('paypal.')->group(function () {
    Route::get('/', [PayPalController::class, 'redirectToPaypal'])->name('redirect');
    Route::get('/success', [PayPalController::class, 'paypalSuccess'])->name('success');
    Route::get('/cancel', [PayPalController::class, 'paypalCancel'])->name('cancel');
  });

  Route::prefix('stripe')->name('stripe.')->group(function () {
    Route::get('/', [StripeController::class, 'redirectToStripe'])->name('redirect');
    Route::get('/success', [StripeController::class, 'stripeSuccess'])->name('success');
    Route::get('/cancel', [StripeController::class, 'stripeCancel'])->name('cancel');
  });
});

Route::post('/change-locale', [HomeController::class, 'changeLocale'])->name('change.locale');

//Home Page
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/home', [HomeController::class, 'index'])->name('react.home');

// Route::get('/old-blade-design', [HomeController::class, 'indexOld'])->name('old-blade-design');


Route::get('/about-us', [HomeController::class, 'aboutUs'])->name('react.about-us');

Route::get('/contact-us', [ContactController::class, 'ContactUsPage'])->name('react.contact-us');

Route::get('/faqs', [FaqController::class, 'page'])->name('react.faqs');

Route::middleware('guest')->group(function () {
  Route::prefix('auth')->group(function () {

    Route::get('/login', function () {
      return inertia('Auth/Login');
    })->name('react.login');

    Route::get('/register', function () {
      return redirect('register');
    })->name('react.register');

    Route::get('/forgot-password', function () {
      return inertia('Auth/ForgotPassword');
    })->name('react.forgot-password');
  });
});

Route::middleware('auth')->group(function () {
  Route::get('/promotions', [PromotionBannerController::class, 'promotions'])->name('react.promotions');

  Route::get('/wishlist', [WishlistController::class, 'wishlistIndex'])->name('react.wishlist');

  Route::get('/cart', [CartController::class, 'openCart'])->name('react.cart');

  Route::post('/cart-data', [CartController::class, 'cartData'])->name('react.cart-data');

  Route::post('/upload-cart-file', [CartController::class, 'uploadCartFile'])->name('react.upload-cart-file');

  Route::get(
    '/checkout',
    [CheckoutController::class, 'startCheckout']
  )->name('react.checkout');

  // orders ( without prefix )
  Route::get('/orders/{order}/print', [OrderController::class, 'printInvoice'])->name('orders.print');
  Route::get('/order-details-export/{order}', [OrderController::class, 'orderDetailsExport'])->name('react.order-details-export');
  Route::post('/place-order', [OrderController::class, 'placeOrder'])->name('react.place-order');

  Route::prefix('/dashboard')->group(
    function () {

      // user Dashboard

      Route::get('/', [HomeController::class, 'userDashboard'])
        ->name('react.dashboard');

      Route::get('/redirect-to-dashboard', fn() => redirect()->route('react.dashboard'))->name('dashboard');

      // Account-details

      // Route::get('/account-details', function () {
      //   return inertia('UserDashboard/Account/AccountDetails');
      // })->name('react.dashboard.account-details');

      // Password

      // Route::get('/account-change-password', function () {
      //   return inertia('UserDashboard/Account/ChangePassword');
      // })->name('react.dashboard.change-password');


      // Addresses
      // Route::get('/account-addresses', [HomeController::class, 'userAccountAddresses'])
      //   ->name('react.dashboard.account-addresses');

      // Route::get('/account-shipping-addresses', [HomeController::class, 'userShippingAddress'])
      //   ->name('react.dashboard.account.addresses.shipping-addresses');

      // Route::get('/account-billing-addresses', [HomeController::class, 'userBillingAddress'])
      //   ->name('react.dashboard.account.addresses.billing-addresses');



      // Orders
      Route::get('/orders', [OrderController::class, 'reactIndex'])->name('react.dashboard.orders');
      Route::get('/orders/{order}', [OrderController::class, 'reactShow'])->name('react.dashboard.orders.show');

      Route::get('/orders/print', function () {
        return inertia('UserDashboard/Orders/Partials/PrintOrder');
      })->name('react.dashboard.orders.print');

      // Wishlist
      Route::get(
        '/wishlist',
        [WishlistController::class, 'wishlistIndex']
      )->name('react.dashboard.wishlist');


      // Returns

      Route::get('/returns', function () {
        return inertia('UserDashboard/Returns/Returns');
      })->name('react.dashboard.returns');

      // Transactions
      Route::get('/transactions', function () {
        return inertia('UserDashboard/Transactions/Transactions');
      })->name('react.dashboard.transactions');

      // Newsletter Subscription
      Route::get('/newsletter-subscription', [SubscriberController::class, 'Subscription'])
        ->name('react.dashboard.newsletter-subscription');

      // Newsletter toggle
      Route::post('/newsletter-toggle', [SubscriberController::class, 'toggle'])
        ->name('react.newsletter.toggle');
    }
  );

  Route::get('/products', [ProductController::class, 'ProductsPage'])->name('react.products');
  Route::get('/products/{product}', [ProductController::class, 'singleProductPage'])->name('react.product');
  // Route::get('/products/quick-view/{product}', [ProductController::class, 'getQuickProduct'])->name('products.quick_view');
  // Route::get('/brands/show/{brand}', [BrandController::class, 'brand_show'])->name('react.brand_show');
});

Route::get('/product-ajax-search', [ProductController::class, 'ajaxSearch'])->name('product-ajax-search');

Route::get('/all-brands', [BrandController::class, 'all_brands'])
  ->name('react.brands');

Route::post('/modal-subscribe-newsletter', [SubscriberController::class, 'modalSubscribe'])
  ->name('react.newsletter.modal-subscribe');

Route::get('/unsubscribe-newsletter/{id}/{email}', [SubscriberController::class, 'unsubscribe'])
  ->name('react.newsletter.unsubscribe');

Route::middleware('auth')->post('/wishlist-toggle', [WishlistController::class, 'wishlistToggle'])->name('wishlist-toggle');
Route::middleware('auth')->delete('/wishlist-remove/{wishlist}', [WishlistController::class, 'remove'])->name('wishlist.remove');

// Live verification
Route::post('/password/verify-code-live', [ResetPasswordController::class, 'verifyCodeLive'])->name('password.verify-code-live');

Route::post('/password/update', [ResetPasswordController::class, 'reset'])->name('reset.password');

/*
  |--------------------------------------------------------------------------
  | Web Routes
  |--------------------------------------------------------------------------
  |
  | Here is where you can register web routes for your application. These
  | routes are loaded by the RouteServiceProvider within a group which
  | contains the "web" middleware group. Now create something great!
  |
 */

Route::controller(DemoController::class)->group(function () {
  Route::get('/demo/cron_1', 'cron_1');
  Route::get('/demo/cron_2', 'cron_2');
  Route::get('/convert_assets', 'convert_assets');
  Route::get('/convert_category', 'convert_category');
  Route::get('/convert_tax', 'convertTaxes');
  Route::get('/set-category', 'setCategoryToProductCategory');
  Route::get('/insert_product_variant_forcefully', 'insert_product_variant_forcefully');
  Route::get('/update_seller_id_in_orders/{id_min}/{id_max}', 'update_seller_id_in_orders');
  Route::get('/migrate_attribute_values', 'migrate_attribute_values');
});

Route::get('/refresh-csrf', function () {
  return csrf_token();
});

// AIZ Uploader
Route::controller(AizUploadController::class)->group(function () {
  Route::post('/aiz-uploader', 'show_uploader');
  Route::post('/aiz-uploader/upload', 'upload');
  Route::get('/aiz-uploader/get-uploaded-files', 'get_uploaded_files');
  Route::post('/aiz-uploader/get_file_by_ids', 'get_preview_files');
  Route::get('/aiz-uploader/download/{id}', 'attachment_download')->name('download_attachment');
});

Route::group(['middleware' => ['prevent-back-history', 'handle-demo-login']], function () {
  Auth::routes(['verify' => true]);
});

// Login
Route::controller(LoginController::class)->group(function () {
  Route::get('/logout', 'logout');
  Route::get('/social-login/redirect/{provider}', 'redirectToProvider')->name('social.login');
  Route::get('/social-login/{provider}/callback', 'handleProviderCallback')->name('social.callback');
  //Apple Callback
  Route::post('/apple-callback', 'handleAppleCallback');
  Route::get('/account-deletion', 'account_deletion')->name('account_delete');
  //Route::get('/handle-demo-login', 'handle_demo_login')->name('handleDemoLogin');
});

Route::controller(VerificationController::class)->group(function () {
  Route::get('/email/resend', 'resend')->name('verification.resend');
  Route::get('/verification-confirmation/{code}', 'verification_confirmation')->name('email.verification.confirmation');
});

Route::resource('shops', ShopController::class)->middleware('handle-demo-login');
Route::controller(ShopController::class)->group(function () {
  Route::get('/shop/registration/verification', 'verifyRegEmailorPhone')->name('shop-reg.verification');
  Route::post('/shop/registration/verification-code-send', 'sendRegVerificationCode')->name('shop-reg.verification_code_send');
  Route::get('/shop/registration/verify-code/{id}', 'regVerifyCode')->name('shop-reg.verify_code');
  Route::post('/shop/registration/verification-code-confirmation', 'regVerifyCodeConfirmation')->name('shop-reg.verify_code_confirmation');
});

Route::controller(HomeController::class)->group(function () {
  Route::get('/registration/verification', 'verifyRegEmailorPhone')->name('registration.verification');
  Route::post('/registration/verification-code-send', 'sendRegVerificationCode')->name('customer-reg.verification_code_send');
  Route::get('/registration/verify-code/{id}', 'regVerifyCode')->name('customer-reg.verify_code');
  Route::post('/registration/verification-code-confirmation', 'regVerifyCodeConfirmation')->name('customer-reg.verify_code_confirmation');
  Route::get('/email-change/callback', 'email_change_callback')->name('email_change.callback');
  Route::post('/password/reset/email/submit', 'reset_password_with_code')->name('password.update');

  Route::get('/users/login', 'login')->name('user.login')->middleware('handle-demo-login');
  Route::get('/seller/login', 'login')->name('seller.login')->middleware('handle-demo-login');
  Route::get('/deliveryboy/login', 'login')->name('deliveryboy.login')->middleware('handle-demo-login');
  Route::get('/users/registration', 'registration')->name('user.registration')->middleware('handle-demo-login');
  Route::post('/users/login/cart', 'cart_login')->name('cart.login.submit')->middleware('handle-demo-login');

  Route::post('/import-data', 'import_data');

  Route::post('/home/section/featured', 'load_featured_section')->name('home.section.featured');
  Route::post('/home/section/todays-deal', 'load_todays_deal_section')->name('home.section.todays_deal');
  Route::post('/home/section/best-selling', 'load_best_selling_section')->name('home.section.best_selling');
  Route::post('/home/section/newest-products', 'load_newest_product_section')->name('home.section.newest_products');
  Route::post('/home/section/home-categories', 'load_home_categories_section')->name('home.section.home_categories');
  Route::post('/home/section/best-sellers', 'load_best_sellers_section')->name('home.section.best_sellers');
  Route::post('/home/section/preorder-products', 'load_preorder_featured_products_section')->name('home.section.preorder_products');

  //category dropdown menu ajax call
  Route::post('/category/nav-element-list', 'get_category_items')->name('category.elements');

  //Flash Deal Details Page
  Route::get('/flash-deals', 'all_flash_deals')->name('flash-deals');
  Route::get('/flash-deal/{slug}', 'flash_deal_details')->name('flash-deal-details');

  //Todays Deal Details Page
  Route::get('/todays-deal', 'todays_deal')->name('todays-deal');

  Route::get('/product/{slug}', 'product')->name('product');
  Route::post('/product/variant-price', 'variant_price')->name('products.variant_price');
  Route::get('/shop/{slug}', 'shop')->name('shop.visit');
  Route::get('/shop/{slug}/{type}', 'filter_shop')->name('shop.visit.type');

  Route::get('/customer-packages', 'premium_package_index')->name('customer_packages_list_show');

  // Route::get('/brands', 'all_brands')->name('brands.all');
  Route::get('/categories', 'all_categories')->name('categories.all');
  Route::get('/sellers', 'all_seller')->name('sellers');
  Route::get('/coupons', 'all_coupons')->name('coupons.all');
  Route::get('/inhouse', 'inhouse_products')->name('inhouse.all');


  // Policies
  // Route::get('/old-seller-policy', 'sellerpolicy')->name('sellerpolicy');
  // Route::get('/old-return-policy', 'returnpolicy')->name('returnpolicy');
  // Route::get('/old-support-policy', 'supportpolicy')->name('supportpolicy');
  // Route::get('/old-terms', 'terms')->name('terms');
  // Route::get('/old-privacy-policy', 'privacypolicy')->name('privacypolicy');

  Route::get('/track-your-order', 'trackOrder')->name('orders.track');
});

// Language Switch
Route::post('/language', [LanguageController::class, 'changeLanguage'])->name('language.change');

// Currency Switch
Route::post('/currency', [CurrencyController::class, 'changeCurrency'])->name('currency.change');

// Size Chart Show
Route::get('/size-charts-show/{id}', [SizeChartController::class, 'show'])->name('size-charts-show');

Route::get('/sitemap.xml', function () {
  return base_path('sitemap.xml');
});

// Classified Product
Route::controller(CustomerProductController::class)->group(function () {
  Route::get('/customer-products', 'customer_products_listing')->name('customer.products');
  Route::get('/customer-products?category={category_slug}', 'search')->name('customer_products.category');
  Route::get('/customer-products?city={city_id}', 'search')->name('customer_products.city');
  Route::get('/customer-products?q={search}', 'search')->name('customer_products.search');
  Route::get('/customer-product/{slug}', 'customer_product')->name('customer.product');
});

// Search
Route::controller(SearchController::class)->group(function () {
  Route::get('/search', 'index')->name('search');
  Route::get('/search?keyword={search}', 'index')->name('suggestion.search');
  Route::post('/ajax-search', 'ajax_search')->name('search.ajax');
  Route::get('/category/{category_slug}', 'listingByCategory')->name('products.category');
  Route::get('/brand/{brand_slug}', 'listingByBrand')->name('products.brand');
});

// Cart
Route::controller(CartController::class)->group(function () {
  Route::get('/shopping-cart', 'index')->name('cart');
  Route::post('/cart/show-cart-modal', 'showCartModal')->name('cart.showCartModal');
  Route::post('/cart/addtocart', 'addToCart')->name('cart.addToCart');
  Route::post('/cart/removeFromCart', 'removeFromCart')->name('cart.removeFromCart');
  Route::post('/cart/updateQuantity', 'updateQuantity')->name('cart.updateQuantity');
  Route::post('/cart/updateCartStatus', 'updateCartStatus')->name('cart.updateCartStatus');
});

//Paypal START
Route::controller(PaypalController::class)->group(function () {
  Route::get('/paypal/payment/done', 'getDone')->name('payment.done');
  Route::get('/paypal/payment/cancel', 'getCancel')->name('payment.cancel');
});
//Cybersource START
Route::controller(CybersourceController::class)->group(function () {
  Route::post('/cyber-source/payment/process', 'process')->name('cybersource.process');
  Route::any('/cyber-source/payment/callback', 'callback')->name('cybersource.callback');
  Route::any('/cyber-source/payment/webhook', 'webhook')->name('cybersource.webhook');
  Route::get('/cyber-source/payment/cancel', 'getCancel')->name('cybersource.cancel');
});

//Mercadopago START
Route::controller(MercadopagoController::class)->group(function () {
  Route::any('/mercadopago/payment/done', 'paymentstatus')->name('mercadopago.done');
  Route::any('/mercadopago/payment/cancel', 'callback')->name('mercadopago.cancel');
});
//Mercadopago

// SSLCOMMERZ Start
Route::controller(SslcommerzController::class)->group(function () {
  Route::get('/sslcommerz/pay', 'index');
  Route::POST('/sslcommerz/success', 'success');
  Route::POST('/sslcommerz/fail', 'fail');
  Route::POST('/sslcommerz/cancel', 'cancel');
  Route::POST('/sslcommerz/ipn', 'ipn');
});
//SSLCOMMERZ END

//Stipe Start
Route::controller(StripeController::class)->group(function () {
  Route::get('stripe', 'stripe');
  Route::post('/stripe/create-checkout-session', 'create_checkout_session')->name('stripe.get_token');
  Route::any('/stripe/payment/callback', 'callback')->name('stripe.callback');
  Route::get('/stripe/success', 'success')->name('stripe.success');
  Route::get('/stripe/cancel', 'cancel')->name('stripe.cancel');
});
//Stripe END

// Compare
Route::controller(CompareController::class)->group(function () {
  Route::get('/compare', 'index')->name('compare');
  Route::get('/compare/reset', 'reset')->name('compare.reset');
  Route::post('/compare/addToCompare', 'addToCompare')->name('compare.addToCompare');
  Route::get('/compare/details/{id}', 'details')->name('compare.details');
});

// Subscribe
Route::resource('subscribers', SubscriberController::class);

Route::group(['middleware' => ['auth']], function () {

  // Route::post('/user/update-basic-profile', [HomeController::class, 'userBasicProfileUpdate'])->name('user.basic-profile.update');
  Route::post('/user/update-profile-password', [HomeController::class, 'userPasswordUpdate'])->name('user.password-profile.update');

  // Route::post('/user/update-shipping-address', [HomeController::class, 'storeShippingAddress'])->name('user.shipping-address.update');
  // Route::post('/user/update-billing-address', [HomeController::class, 'storeBillingAddress'])->name('user.billing-address.update');

  Route::post('/user/create-address', [HomeController::class, 'StoreAddress'])->name('user.address.create');
  Route::post('/user/update-address/{address}', [HomeController::class, 'UpdateAddress'])->name('user.address.update');
  Route::post('/user/update-address-type/{address}', [HomeController::class, 'UpdateAddressType'])->name('user.address.update-type');
  Route::delete('/user/delete-address/{address}', [HomeController::class, 'DeleteAddress'])->name('user.address.delete');
});

Route::group(['middleware' => ['user', 'verified', 'unbanned']], function () {

  Route::controller(HomeController::class)->group(function () {
    // Route::get('/dashboard', 'dashboard')->name('dashboard')->middleware(['prevent-back-history']);
    Route::get('/wallet_recharge_success', 'wallet_recharge_success')->name('wallet_recharge_success')->middleware(['prevent-back-history']);
    Route::get('/profile', 'profile')->name('profile');
    Route::post('/new-user-verification', 'new_verify')->name('user.new.verify');
    Route::post('/new-user-email', 'update_email')->name('user.change.email');
    Route::post('/user/update-profile', 'userProfileUpdate')->name('user.profile.update');
  });

  Route::controller(NotificationController::class)->group(function () {
    Route::get('/all-notifications', 'customerIndex')->name('customer.all-notifications');
    Route::post('/notifications/bulk-delete', 'bulkDeleteCustomer')->name('notifications.bulk_delete');
    Route::get('/notification/read-and-redirect/{id}', 'readAndRedirect')->name('notification.read-and-redirect');
    Route::get('/non-linkable-notification-read', 'nonLinkableNotificationRead')->name('non-linkable-notification-read');
  });
});

// Checkout Routs
Route::group(['prefix' => 'checkout'], function () {
  Route::controller(CheckoutController::class)->group(function () {
    // Route::get('/', 'index')->name('checkout');
    // Route::any('/delivery-info', 'store_shipping_info')->name('checkout.store_shipping_infostore');
    // Route::post('/payment-select', 'store_delivery_info')->name('checkout.store_delivery_info');
    // Route::post('/payment', 'checkout')->name('payment.checkout');
    // Route::get('/order-confirmed', 'order_confirmed')->name('order_confirmed');
    // Route::post('/apply-coupon-code', 'apply_coupon_code')->name('checkout.apply_coupon_code');
    // Route::post('/remove-coupon-code', 'remove_coupon_code')->name('checkout.remove_coupon_code');
    // Route::post('/guest-customer-info-check', 'guestCustomerInfoCheck')->name('guest_customer_info_check');
    // Route::post('/updateDeliveryAddress', 'updateDeliveryAddress')->name('checkout.updateDeliveryAddress');
    // Route::post('/updateDeliveryInfo', 'updateDeliveryInfo')->name('checkout.updateDeliveryInfo');
  });
});

Route::group(['middleware' => ['customer', 'verified', 'unbanned']], function () {

  // Purchase History
  Route::resource('purchase_history', PurchaseHistoryController::class);
  Route::controller(PurchaseHistoryController::class)->group(function () {
    Route::get('/purchase_history/details/{id}', 'purchase_history_details')->name('purchase_history.details');
    Route::get('/purchase_history/destroy/{id}', 'order_cancel')->name('purchase_history.destroy');
    Route::get('digital-purchase-history', 'digital_index')->name('digital_purchase_history.index');
    Route::get('/digital-products/download/{id}', 'download')->name('digital-products.download');

    Route::get('/re-order/{id}', 're_order')->name('re_order');
  });

  // Wishlist
  Route::resource('wishlists', WishlistController::class);

  //Follow
  Route::controller(FollowSellerController::class)->group(function () {
    Route::get('/followed-seller', 'index')->name('followed_seller');
    Route::get('/followed-seller/store', 'store')->name('followed_seller.store');
    Route::get('/followed-seller/remove', 'remove')->name('followed_seller.remove');
  });

  // Wallet
  Route::controller(WalletController::class)->group(function () {
    Route::get('/wallet', 'index')->name('wallet.index');
    Route::post('/recharge', 'recharge')->name('wallet.recharge');
    Route::get('/wallet_payment_email_test', 'wallet_payment_email_test')->name('wallet.wallet_payment_email_test');
  });

  // Support Ticket
  Route::resource('support_ticket', SupportTicketController::class);
  Route::post('support_ticket/reply', [SupportTicketController::class, 'seller_store'])->name('support_ticket.seller_store');

  // Customer Package
  Route::post('/customer-packages/purchase', [CustomerPackageController::class, 'purchase_package'])->name('customer_packages.purchase');

  // Customer Product
  Route::resource('customer_products', CustomerProductController::class);
  Route::controller(CustomerProductController::class)->group(function () {
    Route::get('/customer_products/{id}/edit', 'edit')->name('customer_products.edit');
    Route::post('/customer_products/published', 'updatePublished')->name('customer_products.published');
    Route::post('/customer_products/status', 'updateStatus')->name('customer_products.update.status');
    Route::get('/customer_products/destroy/{id}', 'destroy')->name('customer_products.destroy');
  });

  // Product Review
  Route::post('/product-review-modal', [ReviewController::class, 'product_review_modal'])->name('product_review_modal');

  Route::post('/order/re-payment', [CheckoutController::class, 'orderRePayment'])->name('order.re_payment');
});


Route::get('translation-check/{check}', [LanguageController::class, 'get_translation']);

Route::controller(AddressController::class)->group(function () {
  Route::post('/get-states', 'getStates')->name('get-state');
  Route::post('/get-cities', 'getCities')->name('get-city');
});

Route::group(['middleware' => ['auth']], function () {
  Route::get('toggle-stock-notify/{product}', function ($product, \Illuminate\Http\Request $request) {
    $user = $request->user();
    $stock_notify = [
        'product_id' => $product,
        'user_id' => $user->id,
    ];
    // Toggle record efficiently
    StockNotify::firstWhere($stock_notify)?->delete()
    ?? StockNotify::create($stock_notify);
    return back();
  })->name('toggle-stock-notify');

  Route::get('invoice/{order_id}', [InvoiceController::class, 'invoice_download'])->name('invoice.download');

  // Reviews
  Route::resource('/reviews', ReviewController::class);

  // Product Conversation
  Route::resource('conversations', ConversationController::class);
  Route::controller(ConversationController::class)->group(function () {
    Route::get('/conversations/destroy/{id}', 'destroy')->name('conversations.destroy');
    Route::post('conversations/refresh', 'refresh')->name('conversations.refresh');
  });

  // Product Query
  Route::resource('product-queries', ProductQueryController::class);

  Route::resource('messages', MessageController::class);

  //Address
  Route::resource('addresses', AddressController::class);
  Route::controller(AddressController::class)->group(function () {
    // Route::post('/get-states', 'getStates')->name('get-state');
    // Route::post('/get-cities', 'getCities')->name('get-city');
    Route::post('/addresses/update/{id}', 'update')->name('addresses.update');
    Route::get('/addresses/destroy/{id}', 'destroy')->name('addresses.destroy');
    Route::get('/addresses/set-default/{id}', 'set_default')->name('addresses.set_default');
  });

  Route::controller(NoteController::class)->group(function () {
    Route::post('/get-notes', 'getNotes')->name('get_notes');
    Route::get('/get-single-note/{id}', 'getSingleNote')->name('get-single-note');
  });
});

Route::get('/instamojo/payment/pay-success', [InstamojoController::class, 'success'])->name('instamojo.success');

Route::post('rozer/payment/pay-success', [RazorpayController::class, 'payment'])->name('payment.rozer');

Route::get('/paystack/payment/callback', [PaystackController::class, 'handleGatewayCallback']);
Route::get('/paystack/new-callback', [PaystackController::class, 'paystackNewCallback']);

Route::controller(VoguepayController::class)->group(function () {
  Route::get('/vogue-pay', 'showForm');
  Route::get('/vogue-pay/success/{id}', 'paymentSuccess');
  Route::get('/vogue-pay/callback', 'handleCallback');
  Route::get('/vogue-pay/failure/{id}', 'paymentFailure');
});


//Iyzico
Route::any('/iyzico/payment/callback/{payment_type}/{amount?}/{payment_method?}/{combined_order_id?}/{customer_package_id?}/{seller_package_id?}', [IyzicoController::class, 'callback'])->name('iyzico.callback');

Route::get('/customer-products/admin', [IyzicoController::class, 'initPayment'])->name('profile.edit');

//payhere below
Route::controller(PayhereController::class)->group(function () {
  Route::get('/payhere/checkout/testing', 'checkout_testing')->name('payhere.checkout.testing');
  Route::get('/payhere/wallet/testing', 'wallet_testing')->name('payhere.checkout.testing');
  Route::get('/payhere/customer_package/testing', 'customer_package_testing')->name('payhere.customer_package.testing');

  Route::any('/payhere/checkout/notify', 'checkout_notify')->name('payhere.checkout.notify');
  Route::any('/payhere/checkout/return', 'checkout_return')->name('payhere.checkout.return');
  Route::any('/payhere/checkout/cancel', 'chekout_cancel')->name('payhere.checkout.cancel');

  Route::any('/payhere/order-re-payment/notify', 'orderRepaymentNotify')->name('payhere.order_re_payment.notify');
  Route::any('/payhere/order-re-payment/return', 'orderRepaymentReturn')->name('payhere.order_re_payment.return');
  Route::any('/payhere/order-re-payment/cancel', 'orderRepaymentCancel')->name('payhere.order_re_payment.cancel');

  Route::any('/payhere/wallet/notify', 'wallet_notify')->name('payhere.wallet.notify');
  Route::any('/payhere/wallet/return', 'wallet_return')->name('payhere.wallet.return');
  Route::any('/payhere/wallet/cancel', 'wallet_cancel')->name('payhere.wallet.cancel');

  Route::any('/payhere/seller_package_payment/notify', 'sellerPackageNotify')->name('payhere.seller_package_payment.notify');
  Route::any('/payhere/seller_package_payment/return', 'sellerPackageReturn')->name('payhere.seller_package_payment.return');
  Route::any('/payhere/seller_package_payment/cancel', 'sellerPackageCancel')->name('payhere.seller_package_payment.cancel');

  Route::any('/payhere/customer_package_payment/notify', 'customer_package_notify')->name('payhere.customer_package_payment.notify');
  Route::any('/payhere/customer_package_payment/return', 'customer_package_return')->name('payhere.customer_package_payment.return');
  Route::any('/payhere/customer_package_payment/cancel', 'customer_package_cancel')->name('payhere.customer_package_payment.cancel');
});

//N-genius
Route::controller(NgeniusController::class)->group(function () {
  Route::any('ngenius/cart_payment_callback', 'cart_payment_callback')->name('ngenius.cart_payment_callback');
  Route::any('ngenius/order_re_payment_callback', 'order_re_payment_callback')->name('ngenius.order_re_payment_callback');
  Route::any('ngenius/wallet_payment_callback', 'wallet_payment_callback')->name('ngenius.wallet_payment_callback');
  Route::any('ngenius/customer_package_payment_callback', 'customer_package_payment_callback')->name('ngenius.customer_package_payment_callback');
  Route::any('ngenius/seller_package_payment_callback', 'seller_package_payment_callback')->name('ngenius.seller_package_payment_callback');
});

Route::controller(BkashController::class)->group(function () {
  Route::get('/bkash/create-payment', 'create_payment')->name('bkash.create_payment');
  Route::get('/bkash/callback', 'callback')->name('bkash.callback');
  Route::get('/bkash/success', 'success')->name('bkash.success');
});

Route::get('/checkout-payment-detail', [StripeController::class, 'checkout_payment_detail']);

//Nagad
Route::get('/nagad/callback', [NagadController::class, 'verify'])->name('nagad.callback');

//aamarpay
Route::controller(AamarpayController::class)->group(function () {
  Route::post('/aamarpay/success', 'success')->name('aamarpay.success');
  Route::post('/aamarpay/fail', 'fail')->name('aamarpay.fail');
});

//Authorize-Net-Payment
Route::post('/dopay/online', [AuthorizenetController::class, 'handleonlinepay'])->name('dopay.online');
Route::get('/authorizenet/cardtype', [AuthorizenetController::class, 'cardType'])->name('authorizenet.cardtype');

//payku
Route::get('/payku/callback/{id}', [PaykuController::class, 'callback'])->name('payku.result');

// Paymob
Route::any('/paymob/callback', [PaymobController::class, 'callback']);

// tap
Route::any('/tap/callback', [TapController::class, 'callback'])->name('tap.callback');

//Blog Section
Route::controller(BlogController::class)->group(function () {
  Route::get('/blog', 'all_blog')->name('blog');
  Route::get('/blog/{blog}', 'blog_details')->name('blog.details');
  Route::post('/blog/generate-slug', 'generateSlug')->name('generate.slug');
});


Route::controller(ContactController::class)->group(function () {
  Route::post('/contact', 'contact')->name('contact');
  Route::post('/contact-us', 'contactUs')->name('contact-us');
});



// -----------------------------------------------------------------------------------------------------

// Info For Emails
$info = [
  'phone' => get_setting('contact_phone') ?? '+966 112 860 262',
  'email' => get_setting('contact_email') ?? 'info@velouracare.com',
  'address' => get_setting('contact_address') ?? 'Kingdom of Saudi Arabia – Riyadh – Al-Malaz – Salah Al-Din Al-Ayyubi Road ',
  'facebook' => get_setting('facebook_link') ?? '#',
  'twitter' => get_setting('twitter_link') ?? '#',
  'instagram' => get_setting('instagram_link') ?? '#',
  'linkedin' => get_setting('linkedin_link') ?? '#',
  'url' => url('/'),
];


// Route::get('/test-email', function () use ($info) {
//   $user = User::find(24);

//   $user->load('businessInfo');

//   $registrationNotificationEmail = get_setting('registration_notification_email') ?? 'info@velouracare.com';

//   Mail::send('emails.admin_new_registration', ['user' => $user], function ($message) use ($registrationNotificationEmail) {
//     $message->to($registrationNotificationEmail)
//       ->subject('New Registration Request' . ' - ' . get_setting('site_name', 'Veloura Care'));
//     $message->from(config('mail.from.address'), config('mail.from.name'));
//   });

//   Mail::send('emails.registration', ['user' => $user, 'info' => $info], function ($message) use ($user) {
//     $message->to($user->email)
//       ->subject('Successful Registration Request Received' . ' - ' . get_setting('site_name', 'Veloura Care'));
//     $message->from(config('mail.from.address'), config('mail.from.name'));
//   });

//   return "Emails sent";
// });


Route::get('/email-subscribing', function () use ($info) {
  $subscriber = App\Models\Subscriber::latest('created_at')->first();
  return view('emails.subscribing', ['info' => $info, 'subscriber' => $subscriber]);
})->name('email-subscribing');


Route::get('/email-registration', function () use ($info) {
  return view('emails.registration', ['info' => $info]);
})->name('email-registration');

Route::get('/email-approved', function () use ($info) {
  return view('emails.approved', ['info' => $info]);
})->name('email-approved');

Route::get('/email-rejected', function () use ($info) {

  return view('emails.rejected', ['info' => $info]);
})->name('email-rejected');

Route::get('/new-quotation-request', function () use ($info) {

  $order = Order::latest('created_at')->first();
  return view('emails.new_quotation_request', ['info' => $info, 'order' => $order]);
})->name('new-quotation-request');

Route::get('/new-quotation-response', function () use ($info) {
  $order = Order::latest('created_at')->first();
  return view('emails.new_quotation_response', ['info' => $info, 'order' => $order]);
})->name('new-quotation-response');

Route::get('/email-order-invoice', function () use ($info) {

  // $order = Order::find(19);
  // return view('Emails/order_invoice', ['info' => $info, 'order' => $order,'message' => "hello"]);

  return view('emails.order_invoice', ['info' => $info]);
})->name('email-order-invoice');

Route::get('/test-order-emails', function () use ($info) {
  $order = Order::latest('created_at')->first();

  // sending invoice email
  $customerEmail = request()->user()->email;
  $customerName = 'tester' . ' ' . '1';

  $admin = get_admin();

  $info = [
    'phone' => get_setting('contact_phone') ?? '+1-905-302-2795',
    'email' => get_setting('contact_email') ?? 'info@velouracare.com',
    'address' => get_setting('contact_address') ?? ' Kingdom of Saudi Arabia - Riyadh - Al-Malaz - Salah Al-Din Al-Ayyubi Road ',
    'url' => url('/'),
  ];

  try {
    \App\Jobs\SendOrderEmailsJob::dispatch($order, $info, $admin, $customerEmail, $customerName);
    return 'Order Emails Sent';
  } catch (\Exception $e) {
    \Log::error('Failed: ' . $e->getMessage() . ' in ' . $e->getFile() . ':' . $e->getLine());
    return 'Check Log. Failed to send order emails.';
  }
})->name('test-order-emails');

Route::get('/notify-back-in-stock', function () use ($info) {

  $product = Product::latest('created_at')->with(['thumbnail', 'brand'])->first();
  $productResource = (new ProductWithStockResource($product))->toArray(request());
  $customer_name ="ibrahim";
  return view('emails.notify_back_in_stock', ['info' => $info, 'product' => $productResource, 'customer_name' => $customer_name]);
})->name('notify-back-in-stock');


Route::get('/test-notify-back-in-stock', function () use ($info) {
  // app()->setLocale('ar');
  $product = Product::latest('created_at')->with(['thumbnail', 'brand'])->first();
  $productResource = (new ProductWithStockResource($product))->toArray(request());
  $customer_name ="ibrahim";

  $admin =get_admin();
  // return view('emails.notify_back_in_stock', ['info' => $info, 'product' => $productResource, 'customer_name' => $customer_name]);
  Mail::send('emails.notify_back_in_stock', ['info' => $info, 'product' => $productResource, 'customer_name' => $customer_name], function ($message) use ($productResource, $admin) {
      $message->to($admin->email, $admin->name)
              ->subject("{$productResource['name']} - Back To Stock" . get_setting('site_name', 'Veloura Care'))
              ->from(config('mail.from.address'), config('mail.from.name'));
  });
  return 'Email Sent';
})->name('notify-back-in-stock');


// -----------------------------------------------------------------------------------------------------



Route::controller(PageController::class)->group(function () {
  //mobile app balnk page for webview
  Route::get('/mobile-page/{slug}', 'mobile_custom_page')->name('mobile.custom-pages');

  //Custom page
  Route::get('/{slug}', 'show_custom_page')->name('custom-pages.show_custom_page');
});



