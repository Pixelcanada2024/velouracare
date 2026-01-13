<?php

namespace App\Services;

use App\Http\Resources\V2\Seller\ProductWithStockResource;
use App\Models\Brand;
use App\Models\BusinessSetting;
use App\Models\Category;
use App\Models\Faq;
use App\Models\Product;
use App\Models\Upload;

class HomeService
{
  public function index()
  {

    // Home Images ----------------------------------------------------

    $lang = app()->getLocale() ?? 'en';
    $settingNameExtra = $lang != 'en' ? '_ar' : '';

    $types = [
      // Home Page Images
      'home_hero_main' . $settingNameExtra,
      'home_hero_one' . $settingNameExtra,
      'home_hero_two' . $settingNameExtra,
      'home_hero_main_mobile' . $settingNameExtra,
      'home_hero_one_mobile' . $settingNameExtra,
      'home_hero_two_mobile' . $settingNameExtra,
      'who_we_are_one',
      'who_we_are_two',
      'why_choose_us_one',
      'why_choose_us_two',
      'partners_in_success_web' . $settingNameExtra,
      'partners_in_success_tablet' . $settingNameExtra,
      'partners_in_success_mobile' . $settingNameExtra,
      'become_customer_desktop' . $settingNameExtra,
      'become_customer_mobile' . $settingNameExtra,
      'partner_sky_business_desktop' . $settingNameExtra,
      'partner_sky_business_mobile' . $settingNameExtra
    ];

    $defaultImages = [
      // Home Page Images fallbacks
      "home_hero_main{$settingNameExtra}" => "/public/website-assets/home/home_hero{$settingNameExtra}.png",
      "home_hero_one{$settingNameExtra}" => "/public/website-assets/home/home_hero_one{$settingNameExtra}.png",
      "home_hero_two{$settingNameExtra}" => "/public/website-assets/home/home_hero_two{$settingNameExtra}.png",
      "home_hero_main_mobile{$settingNameExtra}" => "/public/website-assets/home/home_hero_mobile{$settingNameExtra}.png",
      "home_hero_one_mobile{$settingNameExtra}" => "/public/website-assets/home/home_hero_one_mobile{$settingNameExtra}.png",
      "home_hero_two_mobile{$settingNameExtra}" => "/public/website-assets/home/home_hero_two_mobile{$settingNameExtra}.png",
      "who_we_are_one" => "/public/website-assets/home/WhoWeAreOne.png",
      "who_we_are_two" => "/public/website-assets/home/WhoWeAreTwo.png",
      "why_choose_us_one" => "/public/website-assets/home/WhyChooseUsOne.png",
      "why_choose_us_two" => "/public/website-assets/home/WhyChooseUsTwo.png",
      "partners_in_success_web{$settingNameExtra}" => asset('/public/website-assets/home/partners_with_us/' . $lang . '_web.png'),
      "partners_in_success_tablet{$settingNameExtra}" => asset('/public/website-assets/home/partners_with_us/' . $lang . '_tablet.png'),
      "partners_in_success_mobile{$settingNameExtra}" => asset('/public/website-assets/home/partners_with_us/' . $lang . '_mobile.png'),
      "become_customer_desktop{$settingNameExtra}" => "/public/website-assets/home/BecomeCustomer{$settingNameExtra}.png",
      "become_customer_mobile{$settingNameExtra}" => "/public/website-assets/home/BecomeCustomerMobile{$settingNameExtra}.png",
      "partner_sky_business_desktop{$settingNameExtra}" => "/public/website-assets/home/PartnerWithSkyBusiness{$settingNameExtra}.png",
      "partner_sky_business_mobile{$settingNameExtra}" => "/public/website-assets/home/PartnerWithSkyBusinessMobile{$settingNameExtra}.png"
    ];

    $businessSettings = BusinessSetting::whereIn('type', $types)->get()->keyBy('type');

    // Collect all upload IDs for single query
    $allUploadIds = [];
    foreach ($types as $type) {
      $setting = $businessSettings[$type] ?? null;
      if ($setting && $setting->value) {
        if ($type === 'home_hero_main' . $settingNameExtra || $type === 'home_hero_main_mobile' . $settingNameExtra) {
          // Multiple images (comma-separated)
          $imageIds = explode(',', $setting->value);
          foreach ($imageIds as $imageId) {
            $imageId = trim($imageId);
            if (!empty($imageId)) {
              $allUploadIds[] = $imageId;
            }
          }
        } else {
          // Single image
          $allUploadIds[] = $setting->value;
        }
      }
    }

    // Get all uploads in one query
    $uploads = Upload::whereIn('id', $allUploadIds)->get()->keyBy('id');

    $banners = [];
    foreach ($types as $type) {
      if ($type === 'home_hero_main' . $settingNameExtra || $type === 'home_hero_main_mobile' . $settingNameExtra) {
        // Handle multiple hero images
        $setting = $businessSettings[$type] ?? null;
        $heroImages = [];

        if ($setting && $setting->value) {
          // Get image IDs (comma-separated like 273,274,275)
          $imageIds = explode(',', $setting->value);

          // Build images array in correct order
          foreach ($imageIds as $imageId) {
            $imageId = trim($imageId);
            if (!empty($imageId) && isset($uploads[$imageId])) {
              $upload = $uploads[$imageId];
              if ($upload->file_name) {
                $heroImages[] = asset('/public/' . $upload->file_name);
              } elseif ($upload->external_link) {
                $heroImages[] = $upload->external_link;
              }
            }
          }
        }

        // If no valid images found, use fallback
        if (empty($heroImages)) {
          $heroImages[] = $defaultImages[$type];
        }

        $banners[$type] = $heroImages;
      } else {
        // Handle single images using pre-fetched uploads
        $setting = $businessSettings[$type] ?? null;
        if ($setting && isset($uploads[$setting->value])) {
          $upload = $uploads[$setting->value];
          if ($upload->file_name) {
            $banners[$type] = asset('/public/' . $upload->file_name);
          } elseif ($upload->external_link) {
            $banners[$type] = $upload->external_link;
          } else {
            $banners[$type] = $defaultImages[$type];
          }
        } else {
          $banners[$type] = $defaultImages[$type];
        }
      }
    }

    // Top categories ----------------------------------------------------
    $homeCategories = json_decode(get_setting('home_categories'), true) ?: [];
    $imageMap = [
      '1' => '1-skin-care.png',
      '2' => '2-hair-care.png',
      '3' => '3-health-care.png',
      '4' => '4-makeup.jpg',
    ];
    $textFallbacks = [
      '1' => $lang == 'ar' ? 'العناية بالبشرة' : 'Skin Care',
      '2' => $lang == 'ar' ? 'العناية بالشعر' : 'Hair Care',
      '3' => $lang == 'ar' ? 'العناية بالصحة' : 'Health Care',
      '4' => $lang == 'ar' ? 'المكياج' : 'Makeup',
    ];
    if (empty($homeCategories)) {
      $categoriesForFrontend = [
        '1' => [
          'image' => asset('/public/website-assets/home/' . $imageMap['1']),
          'text' => $textFallbacks['1'],
        ],
        '2' => [
          'image' => asset('/public/website-assets/home/' . $imageMap['2']),
          'text' => $textFallbacks['2'],
        ],
        '3' => [
          'image' => asset('/public/website-assets/home/' . $imageMap['3']),
          'text' => $textFallbacks['3'],
        ],
        '4' => [
          'image' => asset('/public/website-assets/home/' . $imageMap['4']),
          'text' => $textFallbacks['4'],
        ],
      ];
    } else {
      $categoryIds = array_filter(array_column($homeCategories, 'category_id'));
      $categories = Category::whereIn('id', $categoryIds)->get()->keyBy('id');
      $categoriesForFrontend = [];
      foreach ($homeCategories as $key => $cat) {
        $image = isset($cat['image']) ? uploaded_asset($cat['image']) : asset('/public/website-assets/home/' . ($imageMap[$key] ?? '1-skin-care.png'));
        $text = '';
        if (isset($cat['category_id']) && $cat['category_id'] && isset($categories[$cat['category_id']])) {
          $text = $categories[$cat['category_id']]->getTranslation('name');
        } else {
          $text = $textFallbacks[$key] ?? 'Category';
        }
        $categoriesForFrontend[$key] = [
          'image' => $image,
          'text' => $text,
        ];
      }
    }

    $home_links = json_decode(get_setting('home_links'), true);

    # image links
    $imageLinks = [
      'slider' => [],
      'sliderDefault' => $home_links['slider']['default'] ?? '#',
      'hero_image_one' => $home_links['hero_image_one'] ?? '#',
      'hero_image_two' => $home_links['hero_image_two'] ?? '#',
    ];

    $sliderImages = explode(',', $businessSettings['home_hero_main' . $settingNameExtra] ?? '') ?? [];
    foreach ($sliderImages as $index => $value) {
      $imageLinks['slider'][] = $home_links['slider'][$index] ?? '#';
    }

    // Top Brands ----------------------------------------------------
    // Get top brand IDs from business_settings
    $topBrandIds = json_decode(get_setting('top_brands'), true);

    // Fetch brands using those IDs
    $TopBrandsQuery = Brand::with('logo_image')
      ->whereIn('id', $topBrandIds ?? [])
      ->get();

    $TopBrands = $TopBrandsQuery->map(function ($brand) {
      $logoUrl = null;

      if ($brand->logo_image) {
        // If file_name,
        if ($brand->logo_image->file_name) {
          $logoUrl = asset("/public/" . $brand->logo_image->file_name);
        }
        // Or,external_link
        else if ($brand->logo_image->external_link) {
          $logoUrl = $brand->logo_image->external_link;
        }
      }

      return [
        'id' => $brand->id,
        'name' => $brand->name,
        'slug' => $brand->slug,
        'logo' => $logoUrl
      ];
    });


    // Top Products ----------------------------------------------------

    // Fetch featured products
    $featuredProducts = Product::with('thumbnail')->where('featured', 1)
      ->where('published', 1)
      ->where('approved', 1)
      ->orderBy('created_at', 'desc')
      ->take(30)
      ->get();

    // Fetch new arrivals
    $newArrivals = Product::with('thumbnail')->where('new_arrival', 1)
      ->where('published', 1)
      ->where('approved', 1)
      ->orderBy('created_at', 'desc')
      ->take(30)
      ->get();

    // Fetch Top Deals
    $topDeals = Product::with('thumbnail')->where('todays_deal', 1)
      ->where('approved', 1)
      ->where('published', 1)
      ->orderBy('created_at', 'desc')
      ->take(30)
      ->get();


    $featuredProductsFormatted = ProductWithStockResource::collection($featuredProducts)->toArray(request());
    $newArrivalsFormatted = ProductWithStockResource::collection($newArrivals)->toArray(request());
    $topDealsFormatted = ProductWithStockResource::collection($topDeals)->toArray(request());

    // Blog ----------------------------------------------------
    $latestBlogs = \App\Models\Blog::with('bannerUpload')
      ->where('status', 1)
      ->latest()
      ->take(6)
      ->get()
      ->map(function ($blog) {
        return [
          'id' => $blog->id,
          'title' => $blog->title,
          'slug' => $blog->slug,
          'banner' => $blog->bannerUpload
            ? ($blog->bannerUpload->file_name
              ? asset('/public/' . $blog->bannerUpload->file_name)
              : $blog->bannerUpload->external_link)
            : null,
          'created_At' => optional($blog->created_at)->format('M d, Y'),
        ];
      });



    // Text Content Settings ----------------------------------------------------
    $textContent = [
      'who_we_are_title' => get_setting('who_we_are_title', customTrans('who_we_are_title'), $lang, true),
      'who_we_are_description' => get_setting('who_we_are_description', customTrans('who_we_are_description'), $lang, true),
      'we_help_partners_title' => get_setting('we_help_partners_title', customTrans('we_help_partners_title'), $lang, true),
      'we_help_partners_description' => get_setting('we_help_partners_description', customTrans('we_help_partners_description'), $lang, true),
      'we_deliver_value_title' => get_setting('we_deliver_value_title', customTrans('we_deliver_value_title'), $lang, true),
      'we_deliver_value_description' => get_setting('we_deliver_value_description', customTrans('we_deliver_value_description'), $lang, true),
      'premium_product_title' => get_setting('premium_product_title', customTrans('premium_product_title'), $lang, true),
      'premium_product_description' => get_setting('premium_product_description', customTrans('premium_product_description'), $lang, true),
      'competitive_pricing_title' => get_setting('competitive_pricing_title', customTrans('competitive_pricing_title'), $lang, true),
      'competitive_pricing_description' => get_setting('competitive_pricing_description', customTrans('competitive_pricing_description'), $lang, true),
      'fast_delivery_title' => get_setting('fast_delivery_title', customTrans('fast_delivery_title'), $lang, true),
      'fast_delivery_description' => get_setting('fast_delivery_description', customTrans('fast_delivery_description'), $lang, true),
      'customer_support_title' => get_setting('customer_support_title', customTrans('customer_support_title'), $lang, true),
      'customer_support_description' => get_setting('customer_support_description', customTrans('customer_support_description'), $lang, true),
      'home_partners_in_success_desc' => get_setting('home_partners_in_success_desc', customTrans('home_partners_in_success_desc'), $lang, true),
    ];

    // FAQs

    $Faqs = Faq::where('is_in_home', 1)
      ->get();

    return inertia('Home/Home', [
      'home_hero_main' => $banners['home_hero_main' . $settingNameExtra],
      'home_hero_one' => $banners['home_hero_one' . $settingNameExtra],
      'home_hero_two' => $banners['home_hero_two' . $settingNameExtra],
      'home_hero_main_mobile' => $banners['home_hero_main_mobile' . $settingNameExtra],
      'home_hero_one_mobile' => $banners['home_hero_one_mobile' . $settingNameExtra],
      'home_hero_two_mobile' => $banners['home_hero_two_mobile' . $settingNameExtra],
      'who_we_are_one' => $banners['who_we_are_one'],
      'who_we_are_two' => $banners['who_we_are_two'],
      'why_choose_us_one' => $banners['why_choose_us_one'],
      'why_choose_us_two' => $banners['why_choose_us_two'],
      'partners_in_success_web' => $banners['partners_in_success_web' . $settingNameExtra],
      'partners_in_success_tablet' => $banners['partners_in_success_tablet' . $settingNameExtra],
      'partners_in_success_mobile' => $banners['partners_in_success_mobile' . $settingNameExtra],
      'become_customer_desktop' => $banners['become_customer_desktop' . $settingNameExtra],
      'become_customer_mobile' => $banners['become_customer_mobile' . $settingNameExtra],
      'partner_sky_business_desktop' => $banners['partner_sky_business_desktop' . $settingNameExtra],
      'partner_sky_business_mobile' => $banners['partner_sky_business_mobile' . $settingNameExtra],
      'categories' => $categoriesForFrontend,
      'top_brands' => $TopBrands,
      'latest_blogs' => $latestBlogs,
      'text_content' => $textContent,
      'imageLinks' => $imageLinks,
      'featured_products' => $featuredProductsFormatted,
      'new_arrivals' => $newArrivalsFormatted,
      'top_deals' => $topDealsFormatted,
      'faqs' => $Faqs

    ]);
  }
}
