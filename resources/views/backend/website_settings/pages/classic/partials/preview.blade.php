<!-- Preview Section -->
<div class="mt-5 border-top pt-4">
    <div class="mt-4 mb-4">
        <a href="{{ route('react.home') }}" class="btn btn-primary w-100 text-white" type="button">Preview In The
            Website</a>
    </div>

    @php
        $home_links = json_decode(get_setting('home_links'), true);
    @endphp

    <!-- Website Preview Section -->
    <h5 class="mb-3">{{ translate('Website Preview') }}</h5>
    <div class="bg-light p-4 rounded mb-4">

        <!-- Hero Section Preview -->
        <div class="mb-4">
            <input type="hidden" name="types[]" value="home_links">
            <h6 class="mb-2">{{ translate('Hero Section ') }} ( Tablet & PC )<i class="las la-language text-danger" title="{{translate('Translatable')}}"></i></h6>
            <div class="row">
                <div class="col-12 mb-3">
                    <div class="border rounded mx-auto"
                        style="display: flex; flex-wrap: nowrap; max-width: 800px; overflow: auto;">
                        @if ($home_hero_main)
                            @foreach (explode(',', $home_hero_main) as $index => $item)
                                <div class="border-2 border-primary w-80 mx-auto">
                                    <img src="{{ uploaded_asset($item) }}" style="width: 600px; aspect-ratio: 1 / 1;">
                                    <label class="w-100 mt-4">Its Link: <br>
                                        <input type="text" name="home_links[slider][{{ $index }}]"
                                            value="{{ $home_links['slider'][$index] ?? '' }}" placeholder="#"
                                            class="form-control">
                                    </label>
                                </div>
                            @endforeach
                        @else
                            <div class="w-80 mx-auto border-2 border-primary">
                                <img src="/public/website-assets/home/home_hero{{ $settingNameExtra }}.png" class="w-100">
                                <label class="w-100 mt-4">Its Link: <br>
                                    <input type="text" name="home_links[slider][default]"
                                        value="{{ $home_links['slider']['default'] ?? '' }}" placeholder="#"
                                        class="form-control">
                                </label>
                            </div>
                        @endif
                    </div>
                    <strong class="text-muted">{{ translate('Main Hero Slider Images') }}<i class="las la-language text-danger" title="{{translate('Translatable')}}"></i></strong>
                </div>

                <div class="col-6 mb-3">
                    <div class="border rounded overflow-hidden">
                        @if (get_setting('home_hero_one'.$settingNameExtra))
                            <img src="{{ uploaded_asset(get_setting('home_hero_one'.$settingNameExtra)) }}" class="w-100">
                        @else
                            <img src="/public/website-assets/home/home_hero_one{{ $settingNameExtra }}.png" class="w-100">
                        @endif
                        <label class="w-100 mt-4">Its Link: <br>
                            <input type="text" name="home_links[hero_image_one]"
                                value="{{ $home_links['hero_image_one'] ?? '' }}" placeholder="#" class="form-control">
                        </label>
                    </div>
                    <strong class="text-muted">{{ translate('Hero Image Left') }}</strong>
                </div>

                <div class="col-6 mb-3">
                    <div class="border rounded overflow-hidden">
                        @if (get_setting('home_hero_two'.$settingNameExtra))
                            <img src="{{ uploaded_asset(get_setting('home_hero_two'.$settingNameExtra)) }}" class="w-100">
                        @else
                            <img src="/public/website-assets/home/home_hero_two{{ $settingNameExtra }}.png" class="w-100">
                        @endif
                        <label class="w-100 mt-4">Its Link: <br>
                            <input type="text" name="home_links[hero_image_two]"
                                value="{{ $home_links['hero_image_two'] ?? '' }}" placeholder="#" class="form-control">
                        </label>
                    </div>
                    <strong class="text-muted">{{ translate('Hero Image Right') }}</strong>
                </div>
            </div>
        </div>

        <!-- Save Button -->
        <div class="mt-4 w-100">
            <button type="submit"
                class="btn btn-success w-100 mx-auto btn-md rounded-2 fs-14 fw-700 shadow-success">{{ translate('Save Links') }}</button>
        </div>

        <br>
        <hr>

        <!-- Who We Are & Other Sections Preview -->
        <div class="mb-4">
            <h6 class="mb-2">{{ translate('Content Sections') }}</h6>
            <div class="row">
                <div class="col-12 mb-3">
                    <div class="border rounded overflow-hidden">
                        @if (get_setting('who_we_are_one'))
                            <img src="{{ uploaded_asset(get_setting('who_we_are_one')) }}" class="w-100">
                        @else
                            <img src="/public/website-assets/home/WhoWeAreOne.png" class="w-100">
                        @endif
                    </div>
                    <strong class="text-muted">{{ translate('Who We Are First Image') }}</strong>
                </div>

                <div class="col-12 mb-3">
                    <div class="border rounded overflow-hidden">
                        @if (get_setting('who_we_are_two'))
                            <img src="{{ uploaded_asset(get_setting('who_we_are_two')) }}" class="w-100">
                        @else
                            <img src="/public/website-assets/home/WhoWeAreTwo.png" class="w-100">
                        @endif
                    </div>
                    <strong class="text-muted">{{ translate('Who We Are Second Image') }}</strong>
                </div>

                <div class="col-12 mb-3">
                    <div class="border rounded overflow-hidden">
                        @if (get_setting('why_choose_us_one'))
                            <img src="{{ uploaded_asset(get_setting('why_choose_us_one')) }}" class="w-100">
                        @else
                            <img src="/public/website-assets/home/WhyChooseUsOne.png" class="w-100">
                        @endif
                    </div>
                    <strong class="text-muted">{{ translate('Why Choose Us First') }}</strong>
                </div>

                <div class="col-12 mb-3">
                    <div class="border rounded overflow-hidden">
                        @if (get_setting('why_choose_us_two'))
                            <img src="{{ uploaded_asset(get_setting('why_choose_us_two')) }}" class="w-100">
                        @else
                            <img src="/public/website-assets/home/WhyChooseUsTwo.png" class="w-100">
                        @endif
                    </div>
                    <strong class="text-muted">{{ translate('Why Choose Us Second') }}</strong>
                </div>

            </div>
        </div>

        <!-- Desktop CTA Banners -->
        <div class="mb-4">
            <h6 class="mb-2">{{ translate('Desktop Call-to-Action Banners') }}<i class="las la-language text-danger" title="{{translate('Translatable')}}"></i></h6>
            <div class="row">
                <div class="col-12 mb-3">
                    <div class="border rounded overflow-hidden">
                        @if (get_setting('become_customer_desktop'.$settingNameExtra))
                            <img src="{{ uploaded_asset(get_setting('become_customer_desktop'.$settingNameExtra)) }}" class="w-100">
                        @else
                            <img src="/public/website-assets/home/BecomeCustomer{{$settingNameExtra}}.png" class="w-100">
                        @endif
                    </div>
                    <strong class="text-muted">{{ translate('Become Customer (Desktop)') }}</strong>
                </div>

                <div class="col-12 mb-3">
                    <div class="border rounded overflow-hidden">
                        @if (get_setting('partner_sky_business_desktop'.$settingNameExtra))
                            <img src="{{ uploaded_asset(get_setting('partner_sky_business_desktop'.$settingNameExtra)) }}"
                                class="w-100">
                        @else
                            <img src="/public/website-assets/home/PartnerWithSkyBusiness{{$settingNameExtra}}.png" class="w-100">
                        @endif
                    </div>
                    <strong class="text-muted">{{ translate('Partner with Sky Business (Desktop)') }}</strong>
                </div>
            </div>
        </div>

    </div>

    <!-- Mobile Preview Section -->
    <h5 class="mb-3">{{ translate('Mobile Preview') }}</h5>
    <div class="bg-light p-4 rounded" style="max-width: 600px; margin: 0 auto;">

        <!-- Hero Section Preview Mobile -->
        <div class="mb-4">
            <h6 class="mb-2">{{ translate('Hero Section ') }} ( Mobile )<i class="las la-language text-danger" title="{{translate('Translatable')}}"></i></h6>
            <div class="row">
                <div class="col-12 mb-3">
                    <div class="border rounded mx-auto"
                        style="display: flex; flex-wrap: nowrap; max-width: 800px; overflow: auto;">
                        @if ($home_hero_main_mobile)
                            @foreach (explode(',', $home_hero_main_mobile) as $item)
                                <img src="{{ uploaded_asset($item) }}" class="w-80 mx-auto border-2 border-primary">
                            @endforeach
                        @else
                            <img src="/public/website-assets/home/home_hero_mobile{{$settingNameExtra}}.png" class="w-100">
                        @endif
                    </div>
                    <strong class="text-muted">{{ translate('Main Hero Slider Images') }}</strong>
                </div>

                <div class="col-6 mb-3">
                    <div class="border rounded overflow-hidden">
                        @if (get_setting('home_hero_one_mobile'.$settingNameExtra))
                            <img src="{{ uploaded_asset(get_setting('home_hero_one_mobile'.$settingNameExtra)) }}" class="w-100">
                        @else
                            <img src="/public/website-assets/home/home_hero_one_mobile{{$settingNameExtra}}.png" class="w-100">
                        @endif
                    </div>
                    <strong class="text-muted">{{ translate('Hero Image Left') }}</strong>
                </div>

                <div class="col-6 mb-3">
                    <div class="border rounded overflow-hidden">
                        @if (get_setting('home_hero_two_mobile'.$settingNameExtra))
                            <img src="{{ uploaded_asset(get_setting('home_hero_two_mobile'.$settingNameExtra)) }}" class="w-100">
                        @else
                            <img src="/public/website-assets/home/home_hero_two_mobile{{$settingNameExtra}}.png" class="w-100">
                        @endif
                    </div>
                    <strong class="text-muted">{{ translate('Hero Image Right') }}</strong>
                </div>
            </div>
        </div>
        <!-- Mobile CTA Banners -->
        <div class="mb-4">
            <h6 class="mb-2">{{ translate('Mobile Call-to-Action Banners') }}<i class="las la-language text-danger" title="{{translate('Translatable')}}"></i></h6>
            <div class="row">
                <div class="col-12 mb-3">
                    <div class="border rounded overflow-hidden">
                        @if (get_setting('become_customer_mobile'.$settingNameExtra))
                            <img src="{{ uploaded_asset(get_setting('become_customer_mobile'.$settingNameExtra)) }}" class="w-100"
                                style="height: 100%; object-fit: cover;">
                        @else
                            <img src="/public/website-assets/home/BecomeCustomerMobile{{$settingNameExtra}}.png" class="w-100"
                                style="height: 100%; object-fit: cover;">
                        @endif
                    </div>
                    <small class="text-muted">{{ translate('Become Customer (Mobile)') }}</small>
                </div>

                <div class="col-12 mb-3">
                    <div class="border rounded overflow-hidden">
                        @if (get_setting('partner_sky_business_mobile'.$settingNameExtra))
                            <img src="{{ uploaded_asset(get_setting('partner_sky_business_mobile'.$settingNameExtra)) }}"
                                class="w-100" style="height: 100%; object-fit: cover;">
                        @else
                            <img src="/public/website-assets/home/PartnerWithSkyBusinessMobile{{$settingNameExtra}}.png" class="w-100"
                                style="height: 100%; object-fit: cover;">
                        @endif
                    </div>
                    <small class="text-muted">{{ translate('Partner with Sky Business (Mobile)') }}</small>
                </div>
            </div>
        </div>
    </div>
</div>
