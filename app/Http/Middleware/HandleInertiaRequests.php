<?php

namespace App\Http\Middleware;

use App\Enums\OrderStatus;
use App\Http\Resources\V2\Seller\ProductResource;
use App\Http\Resources\V2\Seller\ProductWithStockResource;
use App\Models\BusinessSetting;
use App\Models\Category;
use App\Models\LastViewedProduct;
use App\Models\Product;
use App\Models\StockNotify;
use App\Models\Upload;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
  /**
   * The root template that's loaded on the first page visit.
   *
   * @see https://inertiajs.com/server-side-setup#root-template
   *
   * @var string
   */
  protected $rootView = 'app';

  /**
   * Determines the current asset version.
   *
   * @see https://inertiajs.com/asset-versioning
   */
  public function version(Request $request): ?string
  {
    return parent::version($request);
  }

  /**
   * Define the props that are shared by default.
   *
   * @see https://inertiajs.com/shared-data
   *
   * @return array<string, mixed>
   */
  public function share(Request $request): array
  {

    $locale = app()->getLocale();

    $user = $request->user() ?? null;

    $types = [
      'show_social_links',
      'facebook_link',
      'twitter_link',
      'instagram_link',
      'whatsapp_link',
      'linkedin_link',
      'contact_address',
      'contact_phone',
      'contact_email',
      'map_iframe_src',
      'work_hours',
      'website_name',
      'site_icon',
      'system_logo_white',
      'system_ar_logo_white',
      'mobile_system_logo_white',
      'footer_qr_code',
      'meta_title',
      'meta_description',
      'meta_keywords',
      'meta_image',

      'pop_up_marketing_product_id',
      'pop_up_marketing_image_id'
    ];

    $businessSettings = BusinessSetting::whereIn('type', $types)->get();

    // Social Links
    $show_social_links = $businessSettings->where('type', 'show_social_links')->first();
    $facebook_link = $businessSettings->where('type', 'facebook_link')->first();
    $twitter_link = $businessSettings->where('type', 'twitter_link')->first();
    $instagram_link = $businessSettings->where('type', 'instagram_link')->first();
    $linkedin_link = $businessSettings->where('type', 'linkedin_link')->first();
    $whatsapp_link = $businessSettings->where('type', 'whatsapp_link')->first();

    // Contact INfo
    $contact_address = $businessSettings->where('type', 'contact_address')->where('lang', $locale)->first();
    $contact_phone = $businessSettings->where('type', 'contact_phone')->first();
    $contact_email = $businessSettings->where('type', 'contact_email')->first();
    $work_hours = $businessSettings->where('type', 'work_hours')->where('lang', $locale)->first();


    // Meta
    $map_iframe_src = $businessSettings->where('type', 'map_iframe_src')->first();
    $website_name = $businessSettings->where('type', 'website_name')->first();

    $site_icon_id_setting = $businessSettings->where('type', 'site_icon')->first();
    $site_icon = !!$site_icon_id_setting?->value ? uploaded_asset($site_icon_id_setting?->value) : null;

    $system_logo_white_id_setting = $businessSettings->where('type', 'system_logo_white')->first();
    $system_ar_logo_white_id_setting = $businessSettings->where('type', 'system_ar_logo_white')->first();

    if ($locale === 'ar') {
      $system_logo_white = !!$system_ar_logo_white_id_setting?->value
        ? uploaded_asset($system_ar_logo_white_id_setting->value)
        : null;
    } else {
      $system_logo_white = !!$system_logo_white_id_setting?->value
        ? uploaded_asset($system_logo_white_id_setting->value)
        : null;
    }

    $mobile_system_logo_white_id_setting = $businessSettings->where('type', 'mobile_system_logo_white')->first();
    $mobile_system_logo_white = !!$mobile_system_logo_white_id_setting?->value ? uploaded_asset($mobile_system_logo_white_id_setting?->value) : null;

    $footer_qr_code_id_setting = $businessSettings->where('type', 'footer_qr_code')->first();
    $footer_qr_code = uploaded_asset($footer_qr_code_id_setting?->value);

    $meta_title = $businessSettings->where('type', 'meta_title')->first();
    $meta_description = $businessSettings->where('type', 'meta_description')->first();
    $meta_keywords = $businessSettings->where('type', 'meta_keywords')->first();

    $meta_image_id_setting = $businessSettings->where('type', 'meta_image')->first();
    $meta_image = Upload::find($meta_image_id_setting?->value);
    $meta_image = $meta_image ? uploaded_asset($meta_image->id) : null;


    // Get recently viewed products for the user
    $recentlyViewedProducts = [];

    if ($user) {
      $recentlyViewedProducts = LastViewedProduct::where('user_id', $user->id)
        ->orderBy('updated_at', 'desc')
        ->take(8)
        ->with(['product.thumbnail', 'product.brand'])
        ->get()
        ->pluck('product')
        ->filter()
        ->values();

      $recentlyViewedProducts = ProductWithStockResource::collection($recentlyViewedProducts)->toArray(request());
    }


    // pop up marketing
    $pop_up_marketing_product_id = $businessSettings->where('type', 'pop_up_marketing_product_id')->first();

    if ($pop_up_marketing_product_id) {

      $pop_up_marketing_product = Product::select('id', 'name', 'thumbnail_img')
        ->setEagerLoads([])
        ->with('thumbnail')
        ->find($pop_up_marketing_product_id?->value);

      if ($pop_up_marketing_product) {
        $pop_up_marketing_product->image = $pop_up_marketing_product->thumbnail_img
          ? (
            $pop_up_marketing_product->thumbnail?->external_link
            ?: asset('/public/' . $pop_up_marketing_product->thumbnail?->file_name)
          )
          : null;
      }

      // Image of pop up
      $pop_up_marketing_image_id = $businessSettings
        ->where('type', 'pop_up_marketing_image_id')
        ->first();


      $pop_up_marketing_image = $pop_up_marketing_image_id?->value ? uploaded_asset($pop_up_marketing_image_id?->value) : null;

    }

    $skyType = config('app.sky_type', 'Gulf');

    $defaultCurrency = $skyType === 'America' ? 'USD' : 'SAR';

    $defaultCountry = $skyType === 'America' ? 'us' : 'sa';

    $currency = Session::get('currency', $defaultCurrency);

    $homeCategoryNames = Category::get()->map(function ($category) {
      return [
        'id' => $category->id,
        'name' => $category->getTranslation('name'),
      ];
    })->toArray();

    $orderStatusOrder = OrderStatus::getOrderStatusOrderArray();
    $notifyMeProducts = StockNotify::where('user_id', $user?->id)?->pluck('product_id')?->toArray() ?? [];

    return [
      ...parent::share($request),
      'queryParams' => $request->all(),
      'currentFullUrl' => $request->fullUrl(),
      'csrfToken' => csrf_token(),
      'orderStatusOrder' => $orderStatusOrder,
      'locale' => $locale,
      'currency' => $currency,
      "isSkyAmerica" => $skyType === 'America' ? true : false,
      'notifyMeProducts'=>$notifyMeProducts,
      'preferences' => [
        'country' => Session::get('country', $defaultCountry),
        'locale' => $locale,
        'currency' => $currency,
      ],
      'auth' => [
        'user' => $user ? [
          'id' => $user->id,
          'name' => $user->name,
          'email' => $user->email,
          'phone' => $user?->phone,
          'created_at' => $user?->created_at->format('d M Y'),
        ] :
          // [
          //     'id' => 1,
          //     'name' => 'test',
          //     'email' => 'test@gmail.com',
          // ]

          null,
      ],

      'flash' => [
        'title' => session('title'),
        'message' => session('message'),
        'status' => session('status'),
        'type' => session('type'),
      ],
      'footer_links' => [
        'show' => !empty($show_social_links?->value) ? $show_social_links->value : null,
        'facebook_link' => !empty($facebook_link?->value) ? $facebook_link->value : "#",
        'twitter_link' => !empty($twitter_link?->value) ? $twitter_link->value : "#",
        'instagram_link' => !empty($instagram_link?->value) ? $instagram_link->value : "#",
        'linkedin_link' => !empty($linkedin_link?->value) ? $linkedin_link->value : "#",
        'whatsapp_link' => !empty($whatsapp_link?->value) ? $whatsapp_link->value : "#",
      ],

      'contact_info' => [
        'contact_address' => !empty($contact_address?->value) ? $contact_address->value : customTrans('address'),
        'contact_phone' => !empty($contact_phone?->value) ? $contact_phone->value : '+1-905-302-2795',
        'contact_email' => !empty($contact_email?->value) ? $contact_email->value : 'info@velouracare.sa',
        'work_hours' => !empty($work_hours?->value) ? $work_hours->value : customTrans('work_hours'),
      ],

      'meta' => [
        'map_iframe_src' => !empty($map_iframe_src?->value) ? $map_iframe_src->value : null,
        'website_name' => !empty($website_name?->value) ? $website_name->value : "Qdistrio",
        'site_icon' => $site_icon,
        'system_logo_white' => $system_logo_white,
        'mobile_system_logo_white' => $mobile_system_logo_white,
        'footer_qr_code' => $footer_qr_code,

        'meta_title' => !empty($meta_title?->value) ? $meta_title->value : "Qdistrio",
        'meta_description' => !empty($meta_description?->value) ? $meta_description->value : "Qdistrio is a beauty care e-commerce",
        'meta_keywords' => !empty($meta_keywords?->value) ? $meta_keywords->value : "qdistrio, Qdistrio, Quality Distribution, quality distribution,",
        'meta_image' => $meta_image,
      ],

      'pop_up_marketing' => [
        'product' => $pop_up_marketing_product ?? null,
        'image' => $pop_up_marketing_image ?? null,
      ],

      'recentlyViewedProducts' => $recentlyViewedProducts,
      'homeCategories' => $homeCategoryNames,
    ];
  }

  public function handle(Request $request, \Closure $next)
  {
    $response = parent::handle($request, $next);

    if (
      $request->header('X-Inertia') &&
      $response->getStatusCode() === 200 &&
      $request->route() &&
      str_starts_with($request->route()->getName() ?? '', 'react.')
    ) {

      $response->headers->set('Cache-Control', 'no-cache, no-store, must-revalidate, private');
      $response->headers->set('Pragma', 'no-cache');
      $response->headers->set('Expires', '0');
    }

    return $response;
  }
}
