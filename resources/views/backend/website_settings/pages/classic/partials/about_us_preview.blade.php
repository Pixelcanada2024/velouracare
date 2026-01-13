<!-- About Us Preview Section -->
<div class="mt-5 border-top pt-4">
    <div class="mt-4 mb-4">
        <a href="{{ route('react.about-us') }}" class="btn btn-primary w-100 text-white" type="button">Preview About Us Page</a>
    </div>

    <!-- About Us Website Preview Section -->
    <h5 class="mb-3">{{ translate('About Us Images Preview') }}</h5>
    <div class="bg-light p-4 rounded mb-4">

        <!-- Hero Section Preview -->
        <div class="mb-4">
            <h6 class="mb-2">{{ translate('About Us Hero Section') }}<i class="las la-language text-danger" title="{{translate('Translatable')}}"></i></h6>
            <div class="row">
                <div >
                    <div class="border rounded overflow-hidden">
                        @if (get_setting('about_us_hero_desktop'.$settingNameExtra))
                            <img src="{{ uploaded_asset(get_setting('about_us_hero_desktop'.$settingNameExtra)) }}" class="w-100">
                        @else
                            <img src="/public/website-assets/aboutUs/AboutUsHero{{$settingNameExtra}}.png" class="w-100">
                        @endif
                    </div>
                    <strong class="text-muted">{{ translate('Web Hero Image') }}</strong>
                </div>

                <div >
                    <div class="border rounded overflow-hidden">
                        @if (get_setting('about_us_hero_mobile'.$settingNameExtra))
                            <img src="{{ uploaded_asset(get_setting('about_us_hero_mobile'.$settingNameExtra)) }}" class="w-100">
                        @else
                            <img src="/public/website-assets/aboutUs/AboutUsHeroMobile{{ $settingNameExtra }}.png" class="w-100">
                        @endif
                    </div>
                    <strong class="text-muted">{{ translate('Mobile Hero Image') }}</strong>
                </div>
            </div>
        </div>

        <!-- Mission and Values Images Preview -->
        <div class="mb-4">
            <h6 class="mb-2">{{ translate('Mission and Values Section') }}</h6>
            <div class="row">
                <div class="col-6 mb-3">
                    <div class="border rounded overflow-hidden">
                        @if (get_setting('our_mission_image'.$settingNameExtra))
                            <img src="{{ uploaded_asset(get_setting('our_mission_image'.$settingNameExtra)) }}" class="w-100">
                        @else
                            <img src="/public/website-assets/aboutUs/OurMission{{ $settingNameExtra }}.png" class="w-100">
                        @endif
                    </div>
                    <strong class="text-muted">{{ translate('Our Mission Image') }}</strong>
                </div>

                <div class="col-6 mb-3">
                    <div class="border rounded overflow-hidden">
                        @if (get_setting('our_values_image'.$settingNameExtra))
                            <img src="{{ uploaded_asset(get_setting('our_values_image'.$settingNameExtra)) }}" class="w-100">
                        @else
                            <img src="/public/website-assets/aboutUs/OurValues{{ $settingNameExtra }}.png" class="w-100">
                        @endif
                    </div>
                    <strong class="text-muted">{{ translate('Our Values Image') }}</strong>
                </div>
            </div>
        </div>

        <!-- Partner Images Preview -->
        <div class="mb-4">
            <h6 class="mb-2">{{ translate('Partner with Veloura Care Section') }}<i class="las la-language text-danger" title="{{translate('Translatable')}}"></i></h6>
            <div class="row">
                <div>
                    <div class="border rounded overflow-hidden">
                        @if (get_setting('partner_with_sky_business_desktop'.$settingNameExtra))
                            <img src="{{ uploaded_asset(get_setting('partner_with_sky_business_desktop'.$settingNameExtra)) }}" class="w-100">
                        @else
                            <img src="/public/website-assets/aboutUs/PartnerWithSkyBusiness{{ $settingNameExtra }}.png" class="w-100">
                        @endif
                    </div>
                    <strong class="text-muted">{{ translate('Partner Web Image') }}</strong>
                </div>

                <div >
                    <div class="border rounded overflow-hidden">
                        @if (get_setting('partner_with_sky_business_mobile'.$settingNameExtra))
                            <img src="{{ uploaded_asset(get_setting('partner_with_sky_business_mobile'.$settingNameExtra)) }}" class="w-100">
                        @else
                            <img src="/public/website-assets/aboutUs/PartnerWithSkyBusinessMobile{{ $settingNameExtra }}.png" class="w-100">
                        @endif
                    </div>
                    <strong class="text-muted">{{ translate('Partner Mobile Image') }}</strong>
                </div>
            </div>
        </div>

        <!-- Why We Are The Best Images Preview -->
        <div class="mb-4">
            <h6 class="mb-2">{{ translate('Why We Are The Best Section') }}<i class="las la-language text-danger" title="{{translate('Translatable')}}"></i></h6>
            <div class="row">
                <div class="col-6 mb-3">
                    <div class="border rounded overflow-hidden">
                        @if (get_setting('competitive_price_image'.$settingNameExtra))
                            <img src="{{ uploaded_asset(get_setting('competitive_price_image'.$settingNameExtra)) }}" class="w-100">
                        @else
                            <img src="/public/website-assets/aboutUs/GridOne{{ $settingNameExtra }}.png" class="w-100">
                        @endif
                    </div>
                    <strong class="text-muted">{{ translate('Competitive Price Image') }}</strong>
                </div>

                <div class="col-6 mb-3">
                    <div class="border rounded overflow-hidden">
                        @if (get_setting('bulk_ordering_image'.$settingNameExtra))
                            <img src="{{ uploaded_asset(get_setting('bulk_ordering_image'.$settingNameExtra)) }}" class="w-100">
                        @else
                            <img src="/public/website-assets/aboutUs/GridTwo{{ $settingNameExtra }}.jpg" class="w-100">
                        @endif
                    </div>
                    <strong class="text-muted">{{ translate('Bulk Ordering Image') }}</strong>
                </div>

                <div class="col-6 mb-3">
                    <div class="border rounded overflow-hidden">
                        @if (get_setting('efficient_logistics_image'.$settingNameExtra))
                            <img src="{{ uploaded_asset(get_setting('efficient_logistics_image'.$settingNameExtra)) }}" class="w-100">
                        @else
                            <img src="/public/website-assets/aboutUs/GridThree{{ $settingNameExtra }}.png" class="w-100">
                        @endif
                    </div>
                    <strong class="text-muted">{{ translate('Fast Delivery Image') }}</strong>
                </div>

                <div class="col-6 mb-3">
                    <div class="border rounded overflow-hidden">
                        @if (get_setting('exclusive_offers_image'.$settingNameExtra))
                            <img src="{{ uploaded_asset(get_setting('exclusive_offers_image'.$settingNameExtra)) }}" class="w-100">
                        @else
                            <img src="/public/website-assets/aboutUs/GridFour{{ $settingNameExtra }}.png" class="w-100">
                        @endif
                    </div>
                    <strong class="text-muted">{{ translate('Customer Support Image') }}</strong>
                </div>
            </div>
        </div>

    </div>
</div>
