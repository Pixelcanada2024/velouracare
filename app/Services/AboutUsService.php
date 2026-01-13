<?php

namespace App\Services;

use App\Http\Resources\V2\Seller\ProductWithStockResource;
use App\Models\Brand;
use App\Models\BusinessSetting;
use App\Models\Category;
use App\Models\Faq;
use App\Models\Product;
use App\Models\Upload;

class AboutUsService
{
  public function index()
  {
    $lang = app()->getLocale() ?? 'en';
    $settingNameExtra = $lang != 'en' ? '_ar' : '';

    // About Us Images ----------------------------------------------------
    $types = [
      'about_us_hero_desktop'.$settingNameExtra,
      'about_us_hero_mobile'.$settingNameExtra,
      'our_mission_image'.$settingNameExtra,
      'our_values_image'.$settingNameExtra,
      'partner_with_sky_business_desktop'.$settingNameExtra,
      'partner_with_sky_business_mobile'.$settingNameExtra,
      'competitive_price_image'.$settingNameExtra,
      'bulk_ordering_image'.$settingNameExtra,
      'efficient_logistics_image'.$settingNameExtra,
      'exclusive_offers_image'.$settingNameExtra,
    ];

    $defaultImages = [
      "about_us_hero_desktop".$settingNameExtra => "/public/website-assets/aboutUs/AboutUsHero{$settingNameExtra}.png",
      "about_us_hero_mobile".$settingNameExtra => "/public/website-assets/aboutUs/AboutUsHeroMobile{$settingNameExtra}.png",
      "our_mission_image".$settingNameExtra => "/public/website-assets/aboutUs/OurMission{$settingNameExtra}.png",
      "our_values_image".$settingNameExtra => "/public/website-assets/aboutUs/OurValues{$settingNameExtra}.png",
      "partner_with_sky_business_desktop".$settingNameExtra => "/public/website-assets/aboutUs/PartnerWithSkyBusiness{$settingNameExtra}.png",
      "partner_with_sky_business_mobile".$settingNameExtra => "/public/website-assets/aboutUs/PartnerWithSkyBusinessMobile{$settingNameExtra}.png",
      "competitive_price_image".$settingNameExtra => "/public/website-assets/aboutUs/GridOne{$settingNameExtra}.png",
      "bulk_ordering_image".$settingNameExtra => "/public/website-assets/aboutUs/GridTwo{$settingNameExtra}.jpg",
      "efficient_logistics_image".$settingNameExtra => "/public/website-assets/aboutUs/GridThree{$settingNameExtra}.png",
      "exclusive_offers_image".$settingNameExtra => "/public/website-assets/aboutUs/GridFour{$settingNameExtra}.png"
    ];

    $businessSettings = BusinessSetting::whereIn('type', $types)->get()->keyBy('type');

    // Collect all upload IDs for single query
    $allUploadIds = [];
    foreach ($types as $type) {
      $setting = $businessSettings[$type] ?? null;
      if ($setting && $setting->value) {
        $allUploadIds[] = $setting->value;
      }
    }

    // Get all uploads in one query
    $uploads = Upload::whereIn('id', $allUploadIds)->get()->keyBy('id');

    $images = [];
    foreach ($types as $type) {
      $setting = $businessSettings[$type] ?? null;
      if ($setting && $setting->value && isset($uploads[$setting->value])) {
        $images[$type] = uploaded_asset($setting->value);
      } else {
        $images[$type] = $defaultImages[$type];
      }
    }

    // Text Content Settings ----------------------------------------------------
    $textContent = [
        // Hero Section
        'hero_title' => get_setting('about_us_hero_title', customTrans('about_us_hero_title', $lang),$lang,true),

        // We Are Distributors Section
        'we_are_distributors_title' => get_setting('about_us_we_are_distributors_title', customTrans('about_us_we_are_distributors_title', locale: $lang),$lang,true),
        'we_are_distributors_description' => get_setting('about_us_we_are_distributors_description', customTrans('about_us_we_are_distributors_description', $lang),$lang,true),

        // Stats
        'stats_years_experience' => get_setting('about_us_stats_years_experience', customTrans('about_us_stats_years_experience', $lang),$lang,true),
        'stats_brands' => get_setting('about_us_stats_brands', customTrans('about_us_stats_brands', $lang),$lang,true),
        'stats_products' => get_setting('about_us_stats_products', customTrans('about_us_stats_products', $lang),$lang,true),
        'stats_clients' => get_setting('about_us_stats_clients', customTrans('about_us_stats_clients', $lang),$lang,true),

        // Our Mission Section
        'our_mission_title' => get_setting('about_us_our_mission_title', customTrans('about_us_our_mission_title', $lang),$lang,true),
        'our_mission_description_1' => get_setting('about_us_our_mission_description_1', customTrans('about_us_our_mission_description_1', $lang),$lang,true),
        'our_mission_description_2' => get_setting('about_us_our_mission_description_2', customTrans('about_us_our_mission_description_2', $lang),$lang,true),

        // What We Offer Section
        'what_we_offer_title' => get_setting('about_us_what_we_offer_title', customTrans('about_us_what_we_offer_title', $lang),$lang,true),
        'what_we_offer_description_1' => get_setting('about_us_what_we_offer_description_1', customTrans('about_us_what_we_offer_description_1', $lang),$lang,true),
        'what_we_offer_description_2' => get_setting('about_us_what_we_offer_description_2', customTrans('about_us_what_we_offer_description_2', $lang),$lang,true),

        // Our Values Section
        'our_values_title' => get_setting('about_us_our_values_title', customTrans('about_us_our_values_title', $lang),$lang,true),
        'our_values_description' => get_setting('about_us_our_values_description', customTrans('about_us_our_values_description', $lang),$lang,true),

        // Contact Section
        'contact_title' => get_setting('about_us_contact_title', customTrans('about_us_contact_title', $lang),$lang,true),
        'contact_description_1' => get_setting('about_us_contact_description_1', customTrans('about_us_contact_description_1', $lang),$lang,true),
        'contact_description_2' => get_setting('about_us_contact_description_2', customTrans('about_us_contact_description_2', $lang),$lang,true),

        // Why We Are The Best Section
        'why_best_title' => get_setting('about_us_why_best_title', customTrans('about_us_why_best_title', $lang),$lang,true),

        // Grid Items
        'competitive_price_title' => get_setting('about_us_competitive_price_title', customTrans('about_us_competitive_price_title', $lang),$lang,true),
        'competitive_price_description' => get_setting('about_us_competitive_price_description', customTrans('about_us_competitive_price_description', $lang),$lang,true),

        'bulk_ordering_title' => get_setting('about_us_bulk_ordering_title', customTrans('about_us_bulk_ordering_title', $lang),$lang,true),
        'bulk_ordering_description' => get_setting('about_us_bulk_ordering_description', customTrans('about_us_bulk_ordering_description', $lang),$lang,true),

        'efficient_logistics_title' => get_setting('about_us_efficient_logistics_title', customTrans('about_us_efficient_logistics_title', $lang),$lang,true),
        'efficient_logistics_description' => get_setting('about_us_efficient_logistics_description', customTrans('about_us_efficient_logistics_description', $lang),$lang,true),

        'exclusive_offers_title' => get_setting('about_us_exclusive_offers_title', customTrans('about_us_exclusive_offers_title', $lang),$lang,true),
        'exclusive_offers_description' => get_setting('about_us_exclusive_offers_description', customTrans('about_us_exclusive_offers_description', $lang),$lang,true),
    ];


    return inertia('About/AboutUs', [
      'images' => $images,
      'textContent' => $textContent
    ]);
  }
}
