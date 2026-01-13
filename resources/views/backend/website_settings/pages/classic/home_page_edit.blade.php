@extends('backend.layouts.app')

@section('content')
    @php
        $lang = $_GET['lang'] ?? 'en';
        $settingNameExtra = $lang != 'en' ? '_ar' : '';
        $home_hero_main = get_setting('home_hero_main'.$settingNameExtra);
        $home_hero_main_mobile = get_setting('home_hero_main_mobile'.$settingNameExtra);
    @endphp
    <div class="page-content">
        <div class="aiz-titlebar text-left mt-2 pb-2 px-3 px-md-2rem border-bottom border-gray">
            <div class="row align-items-center">
                <div class="col">
                    <h1 class="h3">{{ translate('Homepage Settings (Classic)') }}</h1>
                </div>
                {{-- <div class="col text-right">
					<a class="btn has-transition btn-xs p-0 hov-svg-danger" href="{{ route('home') }}"
						target="_blank" data-toggle="tooltip" data-placement="top" data-title="{{ translate('View Tutorial Video') }}">
						<svg xmlns="http://www.w3.org/2000/svg" width="19.887" height="16" viewBox="0 0 19.887 16">
							<path id="_42fbab5a39cb8436403668a76e5a774b" data-name="42fbab5a39cb8436403668a76e5a774b" d="M18.723,8H5.5A3.333,3.333,0,0,0,2.17,11.333v9.333A3.333,3.333,0,0,0,5.5,24h13.22a3.333,3.333,0,0,0,3.333-3.333V11.333A3.333,3.333,0,0,0,18.723,8Zm-3.04,8.88-5.47,2.933a1,1,0,0,1-1.473-.88V13.067a1,1,0,0,1,1.473-.88l5.47,2.933a1,1,0,0,1,0,1.76Zm-5.61-3.257L14.5,16l-4.43,2.377Z" transform="translate(-2.17 -8)" fill="#9da3ae"/>
						</svg>
					</a>
				</div> --}}
            </div>
        </div>

        <div class="d-sm-flex">
            <!-- page side nav -->
            <div class="page-side-nav c-scrollbar-light px-3 py-2">
                <ul class="nav nav-tabs flex-sm-column border-0" role="tablist" aria-orientation="vertical">
                    <!-- Top Brands -->
                    <li class="nav-item ">
                        <a class="nav-link active " id="brands-tab" href="#brands" data-toggle="tab" data-target="#brands"
                            type="button" role="tab" aria-controls="brands" aria-selected="true">
                            {{ translate('Top Brands') }}
                        </a>
                    </li>
                    <!-- Home Page Images -->
                    <li class="nav-item">
                        <a class="nav-link" id="home-page-images-tab" href="#home-page-images" data-toggle="tab"
                            data-target="#home-page-images" type="button" role="tab" aria-controls="home-page-images">
                            {{ translate('Home Page Images') }}
                        </a>
                    </li>
                    <!-- Home Page Category -->
                    <li class="nav-item">
                        <a class="nav-link" id="home-page-category-tab" href="#home-page-category" data-toggle="tab"
                            data-target="#home-page-category" type="button" role="tab" aria-controls="home-page-category">
                            {{ translate('Home Page Category') }}
                        </a>
                    </li>
                    <!-- Home Page Partners In Success -->
                    <li class="nav-item">
                        <a class="nav-link" id="home-page-partners-in-success-tab" href="#home-page-partners-in-success" data-toggle="tab"
                            data-target="#home-page-partners-in-success" type="button" role="tab" aria-controls="home-page-partners-in-success">
                            {{ translate('Home Page Partners In Success') }}
                        </a>
                    </li>
                    <!-- Home Page Content -->
                    <li class="nav-item">
                        <a class="nav-link" id="home-page-content-tab" href="#home-page-content" data-toggle="tab"
                            data-target="#home-page-content" type="button" role="tab"
                            aria-controls="home-page-content">
                            {{ translate('Home Page Content') }}
                        </a>
                    </li>
                    <!-- About Us Images -->
                    <li class="nav-item">
                        <a class="nav-link" id="about-us-images-tab" href="#about-us-images" data-toggle="tab"
                            data-target="#about-us-images" type="button" role="tab" aria-controls="about-us-images">
                            {{ translate('About Us Images') }}
                        </a>
                    </li>
                    <!-- About Us Content -->
                    <li class="nav-item">
                        <a class="nav-link" id="about-us-content-tab" href="#about-us-content" data-toggle="tab"
                            data-target="#about-us-content" type="button" role="tab"
                            aria-controls="about-us-content">
                            {{ translate('About Us Content') }}
                        </a>
                    </li>
                </ul>
            </div>

            <!-- tab content -->
            <div class="flex-grow-1 p-sm-3 p-lg-2rem mb-2rem mb-md-0">
                <div class="tab-content">

                    <!-- Language Bar -->
                    <ul class="nav nav-tabs nav-fill language-bar">
                        @foreach (get_all_active_language() as $key => $language)
                            <li class="nav-item">
                                <a class="nav-link text-reset @if ($language->code == $lang) active @endif py-3"
                                    href="{{ route('custom-pages.edit', ['id' => $page->slug, 'lang' => $language->code, 'page' => 'home']) }}">
                                    <img src="{{ static_asset('assets/img/flags/' . $language->code . '.png') }}"
                                        height="11" class="mr-1">
                                    <span>{{ $language->name }}</span>
                                </a>
                            </li>
                        @endforeach
                    </ul>

                    @include('backend.website_settings.pages.classic.partials.home_page_category')

                    @include('backend.website_settings.pages.classic.partials.partners_in_success')

                    <!-- Home Page Images Content -->
                    <div class="tab-pane fade" id="home-page-images" role="tabpanel" aria-labelledby="home-page-images-tab">
                        <div class="bg-white p-3 p-sm-2rem">
                            <form action="{{ route('business_settings.update') }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="tab" value="home-page-images">
                                <!-- Hero Section Images  ( Mobile )-->
                                <div class="mb-5">
                                    <h5 class="mb-3"> Hero Section Images ( Mobile ) <i class="las la-language text-danger" title="{{translate('Translatable')}}"></i> </h5>
                                    <div class="row">
                                        <!-- Main Hero Image  -->
                                        <div class="col-md-4 mb-4">
                                            <div class="form-group">
                                                <label
                                                    class="col-from-label fs-13 fw-500">{{ translate('Main Hero Slider Images (Mobile)') }}</label><br>
                                                <p class="text-muted">{{ translate('Recommended size: 700*500px') }}</p>

                                                <!-- Enable multiple selection -->
                                                <div class="input-group" data-toggle="aizuploader" data-type="image"
                                                    data-multiple="true">
                                                    <div class="input-group-prepend">
                                                        <div class="input-group-text bg-soft-secondary">
                                                            {{ translate('Browse') }}</div>
                                                    </div>
                                                    <div class="form-control file-amount">{{ translate('Choose File') }}
                                                    </div>
                                                    <input type="hidden" name="types[]" value="home_hero_main_mobile{{$settingNameExtra}}">

                                                    <!-- Multiple selected files here -->
                                                    <input type="hidden" name="home_hero_main_mobile{{$settingNameExtra}}"
                                                        value="{{ $home_hero_main_mobile }}" class="selected-files">
                                                </div>

                                                <div class="file-preview box sm"></div>
                                            </div>
                                        </div>

                                        <!-- Hero Image Left -->
                                        <div class="col-md-4 mb-4">
                                            <div class="form-group">
                                                <label
                                                    class="col-from-label fs-13 fw-500">{{ translate('Hero Image Left Mobile') }}</label><br>
                                                <p class="text-muted">{{ translate('Recommended size: 340*260px') }}</p>
                                                <div class="input-group" data-toggle="aizuploader" data-type="image">
                                                    <div class="input-group-prepend">
                                                        <div class="input-group-text bg-soft-secondary">
                                                            {{ translate('Browse') }}</div>
                                                    </div>
                                                    <div class="form-control file-amount">{{ translate('Choose File') }}
                                                    </div>
                                                    <input type="hidden" name="types[]" value="home_hero_one_mobile{{$settingNameExtra}}">
                                                    <input type="hidden" name="home_hero_one_mobile{{$settingNameExtra}}"
                                                        value="{{ get_setting('home_hero_one_mobile'.$settingNameExtra) }}"
                                                        class="selected-files">
                                                </div>
                                                <div class="file-preview box sm"></div>
                                            </div>
                                        </div>

                                        <!-- Hero Image Right -->
                                        <div class="col-md-4 mb-4">
                                            <div class="form-group">
                                                <label
                                                    class="col-from-label fs-13 fw-500">{{ translate('Hero Image Right Mobile') }}</label><br>
                                                <p class="text-muted">{{ translate('Recommended size: 340*260px') }}</p>
                                                <div class="input-group" data-toggle="aizuploader" data-type="image">
                                                    <div class="input-group-prepend">
                                                        <div class="input-group-text bg-soft-secondary">
                                                            {{ translate('Browse') }}</div>
                                                    </div>
                                                    <div class="form-control file-amount">{{ translate('Choose File') }}
                                                    </div>
                                                    <input type="hidden" name="types[]" value="home_hero_two_mobile{{$settingNameExtra}}">
                                                    <input type="hidden" name="home_hero_two_mobile{{$settingNameExtra}}"
                                                        value="{{ get_setting('home_hero_two_mobile' . $settingNameExtra) }}"
                                                        class="selected-files">
                                                </div>
                                                <div class="file-preview box sm"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Hero Section Images  ( Tablet & PC )-->
                                <div class="mb-5">
                                    <h5 class="mb-3">Hero Section Images ( Tablet & PC ) <i class="las la-language text-danger" title="{{translate('Translatable')}}"></i></h5>
                                    <div class="row">
                                        <!-- Main Hero Image  -->
                                        <div class="col-md-4 mb-4">
                                            <div class="form-group">
                                                <label
                                                    class="col-from-label fs-13 fw-500">{{ translate('Main Hero Slider Images ') }}</label><br>
                                                <p class="text-muted">{{ translate('Recommended size: 1400x670px') }}</p>

                                                <!-- Enable multiple selection -->
                                                <div class="input-group" data-toggle="aizuploader" data-type="image"
                                                    data-multiple="true">
                                                    <div class="input-group-prepend">
                                                        <div class="input-group-text bg-soft-secondary">
                                                            {{ translate('Browse') }}</div>
                                                    </div>
                                                    <div class="form-control file-amount">{{ translate('Choose File') }}
                                                    </div>
                                                    <input type="hidden" name="types[]" value="home_hero_main{{$settingNameExtra}}">

                                                    <!-- Multiple selected files here -->
                                                    <input type="hidden" name="home_hero_main{{$settingNameExtra}}"
                                                        value="{{ $home_hero_main }}" class="selected-files">
                                                </div>

                                                <div class="file-preview box sm"></div>
                                            </div>
                                        </div>

                                        <!-- Hero Image Left -->
                                        <div class="col-md-4 mb-4">
                                            <div class="form-group">
                                                <label
                                                    class="col-from-label fs-13 fw-500">{{ translate('Hero Image Left') }}</label><br>
                                                <p class="text-muted">{{ translate('Recommended size: 690x390px') }}</p>
                                                <div class="input-group" data-toggle="aizuploader" data-type="image">
                                                    <div class="input-group-prepend">
                                                        <div class="input-group-text bg-soft-secondary">
                                                            {{ translate('Browse') }}</div>
                                                    </div>
                                                    <div class="form-control file-amount">{{ translate('Choose File') }}
                                                    </div>
                                                    <input type="hidden" name="types[]" value="home_hero_one{{$settingNameExtra}}">
                                                    <input type="hidden" name="home_hero_one{{$settingNameExtra}}"
                                                        value="{{ get_setting('home_hero_one'.$settingNameExtra) }}"
                                                        class="selected-files">
                                                </div>
                                                <div class="file-preview box sm"></div>
                                            </div>
                                        </div>

                                        <!-- Hero Image Right -->
                                        <div class="col-md-4 mb-4">
                                            <div class="form-group">
                                                <label
                                                    class="col-from-label fs-13 fw-500">{{ translate('Hero Image Right') }}</label><br>
                                                <p class="text-muted">{{ translate('Recommended size: 690x390px') }}</p>
                                                <div class="input-group" data-toggle="aizuploader" data-type="image">
                                                    <div class="input-group-prepend">
                                                        <div class="input-group-text bg-soft-secondary">
                                                            {{ translate('Browse') }}</div>
                                                    </div>
                                                    <div class="form-control file-amount">{{ translate('Choose File') }}
                                                    </div>
                                                    <input type="hidden" name="types[]" value="home_hero_two{{$settingNameExtra}}">
                                                    <input type="hidden" name="home_hero_two{{$settingNameExtra}}"
                                                        value="{{ get_setting('home_hero_two' . $settingNameExtra) }}"
                                                        class="selected-files">
                                                </div>
                                                <div class="file-preview box sm"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                                <!-- Who We Are  First Image -->
                                <div class="mb-5">
                                    <h5 class="mb-3">{{ translate('Who We Are Section') }}</h5>
                                    <div class="row">
                                        <!-- Who We Are First Image -->
                                        <div class="col-md-6 mb-4">
                                            <div class="form-group">
                                                <label
                                                    class="col-from-label fs-13 fw-500">{{ translate('Who We Are  First Image') }}</label><br>
                                                <p class="text-muted">{{ translate('Recommended size: 530x300px') }}</p>
                                                <div class="input-group" data-toggle="aizuploader" data-type="image">
                                                    <div class="input-group-prepend">
                                                        <div class="input-group-text bg-soft-secondary">
                                                            {{ translate('Browse') }}</div>
                                                    </div>
                                                    <div class="form-control file-amount">{{ translate('Choose File') }}
                                                    </div>
                                                    <input type="hidden" name="types[]" value="who_we_are_one">
                                                    <input type="hidden" name="who_we_are_one"
                                                        value="{{ get_setting('who_we_are_one') }}"
                                                        class="selected-files">
                                                </div>
                                                <div class="file-preview box sm"></div>
                                            </div>
                                        </div>

                                        <!-- 'Who We Are Second Image -->
                                        <div class="col-md-6 mb-4">
                                            <div class="form-group">
                                                <label
                                                    class="col-from-label fs-13 fw-500">{{ translate('Who We Are Second Image') }}</label><br>
                                                <p class="text-muted">{{ translate('Recommended size: 370x485px') }}</p>
                                                <div class="input-group" data-toggle="aizuploader" data-type="image">
                                                    <div class="input-group-prepend">
                                                        <div class="input-group-text bg-soft-secondary">
                                                            {{ translate('Browse') }}</div>
                                                    </div>
                                                    <div class="form-control file-amount">{{ translate('Choose File') }}
                                                    </div>
                                                    <input type="hidden" name="types[]" value="who_we_are_two">
                                                    <input type="hidden" name="who_we_are_two"
                                                        value="{{ get_setting('who_we_are_two') }}"
                                                        class="selected-files">
                                                </div>
                                                <div class="file-preview box sm"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Why Choose Us Section Images -->
                                <div class="mb-5">
                                    <h5 class="mb-3">{{ translate('Why Choose Us Section') }}</h5>
                                    <div class="row">
                                        <!-- Why Choose Us One -->
                                        <div class="col-md-6 mb-4">
                                            <div class="form-group">
                                                <label
                                                    class="col-from-label fs-13 fw-500">{{ translate('Why Choose Us First Image') }}</label><br>
                                                <p class="text-muted">{{ translate('Recommended size: 780x600px') }}</p>
                                                <div class="input-group" data-toggle="aizuploader" data-type="image">
                                                    <div class="input-group-prepend">
                                                        <div class="input-group-text bg-soft-secondary">
                                                            {{ translate('Browse') }}</div>
                                                    </div>
                                                    <div class="form-control file-amount">{{ translate('Choose File') }}
                                                    </div>
                                                    <input type="hidden" name="types[]" value="why_choose_us_one">
                                                    <input type="hidden" name="why_choose_us_one"
                                                        value="{{ get_setting('why_choose_us_one') }}"
                                                        class="selected-files">
                                                </div>
                                                <div class="file-preview box sm"></div>
                                            </div>
                                        </div>

                                        <!-- Why Choose Us Two -->
                                        <div class="col-md-6 mb-4">
                                            <div class="form-group">
                                                <label
                                                    class="col-from-label fs-13 fw-500">{{ translate('Why Choose Us Second Image') }}</label><br>
                                                <p class="text-muted">{{ translate('Recommended size: 780x600px') }}</p>
                                                <div class="input-group" data-toggle="aizuploader" data-type="image">
                                                    <div class="input-group-prepend">
                                                        <div class="input-group-text bg-soft-secondary">
                                                            {{ translate('Browse') }}</div>
                                                    </div>
                                                    <div class="form-control file-amount">{{ translate('Choose File') }}
                                                    </div>
                                                    <input type="hidden" name="types[]" value="why_choose_us_two">
                                                    <input type="hidden" name="why_choose_us_two"
                                                        value="{{ get_setting('why_choose_us_two') }}"
                                                        class="selected-files">
                                                </div>
                                                <div class="file-preview box sm"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>



                                <!-- Call to Action Images -->
                                <div class="mb-5">
                                    <h5 class="mb-3">{{ translate('Call to Action Images') }}</h5>
                                    <div class="row">
                                        <!-- Become Customer Desktop -->
                                        <div class="col-md-6 mb-4">
                                            <div class="form-group">
                                                <label
                                                    class="col-from-label fs-13 fw-500">{{ translate('Become Customer (Desktop)') }} <i class="las la-language text-danger" title="{{translate('Translatable')}}"></i></label><br>
                                                <p class="text-muted">{{ translate('Recommended size: 1400x390px') }}</p>
                                                <div class="input-group" data-toggle="aizuploader" data-type="image">
                                                    <div class="input-group-prepend">
                                                        <div class="input-group-text bg-soft-secondary">
                                                            {{ translate('Browse') }}</div>
                                                    </div>
                                                    <div class="form-control file-amount">{{ translate('Choose File') }}
                                                    </div>
                                                    <input type="hidden" name="types[]" value="become_customer_desktop{{$settingNameExtra}}">
                                                    <input type="hidden" name="become_customer_desktop{{$settingNameExtra}}"
                                                        value="{{ get_setting('become_customer_desktop'.$settingNameExtra) }}"
                                                        class="selected-files">
                                                </div>
                                                <div class="file-preview box sm"></div>
                                            </div>
                                        </div>

                                        <!-- Become Customer Mobile -->
                                        <div class="col-md-6 mb-4">
                                            <div class="form-group">
                                                <label
                                                    class="col-from-label fs-13 fw-500">{{ translate('Become Customer (Mobile)') }} <i class="las la-language text-danger" title="{{translate('Translatable')}}"></i></label><br>
                                                <p class="text-muted">{{ translate('Recommended size: 720x300px') }}</p>
                                                <div class="input-group" data-toggle="aizuploader" data-type="image">
                                                    <div class="input-group-prepend">
                                                        <div class="input-group-text bg-soft-secondary">
                                                            {{ translate('Browse') }}</div>
                                                    </div>
                                                    <div class="form-control file-amount">{{ translate('Choose File') }}
                                                    </div>
                                                    <input type="hidden" name="types[]" value="become_customer_mobile{{$settingNameExtra}}">
                                                    <input type="hidden" name="become_customer_mobile{{$settingNameExtra}}"
                                                        value="{{ get_setting('become_customer_mobile'.$settingNameExtra) }}"
                                                        class="selected-files">
                                                </div>
                                                <div class="file-preview box sm"></div>
                                            </div>
                                        </div>

                                        <!-- Partner with Veloura Care Desktop -->
                                        <div class="col-md-6 mb-4">
                                            <div class="form-group">
                                                <label
                                                    class="col-from-label fs-13 fw-500">{{ translate('Partner with Veloura Care (Desktop)') }}<i class="las la-language text-danger" title="{{translate('Translatable')}}"></i></label><br>
                                                <p class="text-muted">{{ translate('Recommended size: 1400x200px') }}</p>
                                                <div class="input-group" data-toggle="aizuploader" data-type="image">
                                                    <div class="input-group-prepend">
                                                        <div class="input-group-text bg-soft-secondary">
                                                            {{ translate('Browse') }}</div>
                                                    </div>
                                                    <div class="form-control file-amount">{{ translate('Choose File') }}
                                                    </div>
                                                    <input type="hidden" name="types[]"
                                                        value="partner_sky_business_desktop{{$settingNameExtra}}">
                                                    <input type="hidden" name="partner_sky_business_desktop{{$settingNameExtra}}"
                                                        value="{{ get_setting('partner_sky_business_desktop'.$settingNameExtra) }}"
                                                        class="selected-files">
                                                </div>
                                                <div class="file-preview box sm"></div>
                                            </div>
                                        </div>

                                        <!-- Partner with VelouraCare Mobile -->
                                        <div class="col-md-6 mb-4">
                                            <div class="form-group">
                                                <label
                                                    class="col-from-label fs-13 fw-500">{{ translate('Partner with Veloura Care (Mobile)') }}<i class="las la-language text-danger" title="{{translate('Translatable')}}"></i></label><br>
                                                <p class="text-muted">{{ translate('Recommended size: 770x200px') }}</p>
                                                <div class="input-group" data-toggle="aizuploader" data-type="image">
                                                    <div class="input-group-prepend">
                                                        <div class="input-group-text bg-soft-secondary">
                                                            {{ translate('Browse') }}</div>
                                                    </div>
                                                    <div class="form-control file-amount">{{ translate('Choose File') }}
                                                    </div>
                                                    <input type="hidden" name="types[]"
                                                        value="partner_sky_business_mobile{{$settingNameExtra}}">
                                                    <input type="hidden" name="partner_sky_business_mobile{{$settingNameExtra}}"
                                                        value="{{ get_setting('partner_sky_business_mobile'.$settingNameExtra) }}"
                                                        class="selected-files">
                                                </div>
                                                <div class="file-preview box sm"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Save Button -->
                                <div class="mt-4 text-right">
                                    <button type="submit"
                                        class="btn btn-success w-230px btn-md rounded-2 fs-14 fw-700 shadow-success">{{ translate('Save') }}</button>
                                </div>

                            </form>
                            <form action="{{ route('business_settings.update') }}" method="POST">
                                @csrf
                                <input type="hidden" name="tab" value="home-page-images">
                                @include('backend.website_settings.pages.classic.partials.preview', [
                                    'home_hero_main' => $home_hero_main,
                                    'home_hero_main_mobile' => $home_hero_main_mobile,
                                    'settingNameExtra' => $settingNameExtra,
                                ])
                            </form>
                        </div>
                    </div>

                    <!-- Home Page Content -->
                    <div class="tab-pane fade" id="home-page-content" role="tabpanel"
                        aria-labelledby="home-page-content-tab">
                        <form action="{{ route('business_settings.update') }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="tab" value="home-page-content">
                            <input type="hidden" name="lang" value="{{ $lang }}">

                            <div class="bg-white p-3 p-sm-2rem">
                                <!-- Who We Are Section -->
                                <div class="mb-5">
                                    <h5 class="mb-3">{{ translate('Who We Are Section') }}</h5>

                                    <!-- Who We Are Title -->
                                    <div class="form-group mb-4">
                                        <label class="col-from-label fs-13 fw-500">{{ translate('Main Title') }}<i class="las la-language text-danger" title="{{translate('Translatable')}}"></i></label>
                                        <input type="hidden" name="types[]" value="who_we_are_title">
                                        <input type="text" class="form-control" name="who_we_are_title"
                                            value="{{ get_setting('who_we_are_title', null , $lang , true) }}"
                                            placeholder="{{ customTrans('who_we_are_title', $lang) }}">
                                    </div>

                                    <!-- Who We Are Description -->
                                    <div class="form-group mb-4">
                                        <label
                                            class="col-from-label fs-13 fw-500">{{ translate('Main Description') }}<i class="las la-language text-danger" title="{{translate('Translatable')}}"></i></label>
                                        <input type="hidden" name="types[]" value="who_we_are_description">
                                        <textarea class="form-control" name="who_we_are_description" rows="4"
                                            placeholder='{{ customTrans('who_we_are_description', $lang) }}'>{{ get_setting('who_we_are_description', null , $lang , true) }}</textarea>
                                    </div>

                                    <!-- We Help Partners Title -->
                                    <div class="form-group mb-4">
                                        <label
                                            class="col-from-label fs-13 fw-500">{{ translate('We Help Partners Title') }}<i class="las la-language text-danger" title="{{translate('Translatable')}}"></i></label>
                                        <input type="hidden" name="types[]" value="we_help_partners_title">
                                        <input type="text" class="form-control" name="we_help_partners_title"
                                            value="{{ get_setting('we_help_partners_title', null , $lang , true) }}"
                                            placeholder="{{ customTrans('we_help_partners_title', $lang) }}">
                                    </div>

                                    <!-- We Help Partners Description -->
                                    <div class="form-group mb-4">
                                        <label
                                            class="col-from-label fs-13 fw-500">{{ translate('We Help Partners Description') }}<i class="las la-language text-danger" title="{{translate('Translatable')}}"></i></label>
                                        <input type="hidden" name="types[]" value="we_help_partners_description">
                                        <textarea class="form-control" name="we_help_partners_description" rows="3"
                                            placeholder="{{ customTrans('we_help_partners_description', $lang)  }}"
                                            >{{ get_setting('we_help_partners_description', null , $lang , true) }}</textarea>
                                    </div>

                                    <!-- We Deliver Value Title -->
                                    <div class="form-group mb-4">
                                        <label
                                            class="col-from-label fs-13 fw-500">{{ translate('We Deliver Value Title') }}<i class="las la-language text-danger" title="{{translate('Translatable')}}"></i></label>
                                        <input type="hidden" name="types[]" value="we_deliver_value_title">
                                        <input type="text" class="form-control" name="we_deliver_value_title"
                                            value="{{ get_setting('we_deliver_value_title', null , $lang , true) }}"
                                            placeholder="{{ customTrans('we_deliver_value_title', $lang) }}">
                                    </div>

                                    <!-- We Deliver Value Description -->
                                    <div class="form-group mb-4">
                                        <label
                                            class="col-from-label fs-13 fw-500">{{ translate('We Deliver Value Description') }}<i class="las la-language text-danger" title="{{translate('Translatable')}}"></i></label>
                                        <input type="hidden" name="types[]" value="we_deliver_value_description">
                                        <textarea class="form-control" name="we_deliver_value_description" rows="3"
                                            placeholder="{{ customTrans('we_deliver_value_description', $lang) }}">{{ get_setting('we_deliver_value_description', null , $lang , true) }}</textarea>
                                    </div>
                                </div>

                                <!-- Why Choose Us Section -->
                                <div class="mb-5">
                                    <h5 class="mb-3">{{ translate('Why Choose Us Section') }}</h5>

                                    <!-- Premium Product Selection -->
                                    <div class="row mb-4">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label
                                                    class="col-from-label fs-13 fw-500">{{ translate('Premium Product Selection Title') }}<i class="las la-language text-danger" title="{{translate('Translatable')}}"></i></label>
                                                <input type="hidden" name="types[]" value="premium_product_title">
                                                <input type="text" class="form-control" name="premium_product_title"
                                                    value="{{ get_setting('premium_product_title', null , $lang , true) }}"
                                                    placeholder="{{ customTrans('premium_product_title', $lang) }}">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label
                                                    class="col-from-label fs-13 fw-500">{{ translate('Premium Product Selection Description') }}<i class="las la-language text-danger" title="{{translate('Translatable')}}"></i></label>
                                                <input type="hidden" name="types[]" value="premium_product_description">
                                                <textarea class="form-control" name="premium_product_description" rows="2"
                                                    placeholder="{{ customTrans('premium_product_description', $lang) }}">{{ get_setting('premium_product_description', null , $lang , true) }}</textarea>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Competitive Pricing -->
                                    <div class="row mb-4">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label
                                                    class="col-from-label fs-13 fw-500">{{ translate('Competitive Pricing Title') }}<i class="las la-language text-danger" title="{{translate('Translatable')}}"></i></label>
                                                <input type="hidden" name="types[]" value="competitive_pricing_title">
                                                <input type="text" class="form-control"
                                                    name="competitive_pricing_title"
                                                    value="{{ get_setting('competitive_pricing_title', null , $lang , true) }}"
                                                    placeholder="{{ customTrans('competitive_pricing_title', $lang) }}">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label
                                                    class="col-from-label fs-13 fw-500">{{ translate('Competitive Pricing Description') }}<i class="las la-language text-danger" title="{{translate('Translatable')}}"></i></label>
                                                <input type="hidden" name="types[]"
                                                    value="competitive_pricing_description">
                                                <textarea class="form-control" name="competitive_pricing_description" rows="2"
                                                    placeholder="{{ customTrans('competitive_pricing_description', $lang) }}">{{ get_setting('competitive_pricing_description', null , $lang , true) }}</textarea>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Fast & Reliable Delivery -->
                                    <div class="row mb-4">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label
                                                    class="col-from-label fs-13 fw-500">{{ translate('Fast & Reliable Delivery Title') }}<i class="las la-language text-danger" title="{{translate('Translatable')}}"></i></label>
                                                <input type="hidden" name="types[]" value="fast_delivery_title">
                                                <input type="text" class="form-control" name="fast_delivery_title"
                                                    value="{{ get_setting('fast_delivery_title', null , $lang , true) }}"
                                                    placeholder="{{ customTrans('fast_delivery_title', $lang) }}">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label
                                                    class="col-from-label fs-13 fw-500">{{ translate('Fast & Reliable Delivery Description') }}<i class="las la-language text-danger" title="{{translate('Translatable')}}"></i></label>
                                                <input type="hidden" name="types[]" value="fast_delivery_description">
                                                <textarea class="form-control" name="fast_delivery_description" rows="2"
                                                    placeholder="{{ customTrans('fast_delivery_description', $lang) }}">{{ get_setting('fast_delivery_description', null , $lang , true) }}</textarea>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Dedicated Customer Support -->
                                    <div class="row mb-4">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label
                                                    class="col-from-label fs-13 fw-500">{{ translate('Dedicated Customer Support Title') }}<i class="las la-language text-danger" title="{{translate('Translatable')}}"></i></label>
                                                <input type="hidden" name="types[]" value="customer_support_title">
                                                <input type="text" class="form-control" name="customer_support_title"
                                                    value="{{ get_setting('customer_support_title' , null , $lang , true) }}"
                                                    placeholder="{{ customTrans('customer_support_title', $lang) }}">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label
                                                    class="col-from-label fs-13 fw-500">{{ translate('Dedicated Customer Support Description') }}<i class="las la-language text-danger" title="{{translate('Translatable')}}"></i></label>
                                                <input type="hidden" name="types[]"
                                                    value="customer_support_description">
                                                <textarea class="form-control" name="customer_support_description" rows="2"
                                                    placeholder="{{customTrans('customer_support_description', $lang)}}">{{ get_setting('customer_support_description', null , $lang , true) }}</textarea>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Partners In Success-->
                                    <div class="form-group mb-4">
                                        <label
                                            class="col-from-label fs-13 fw-500">{{ translate('Partners In Success Description') }}<i class="las la-language text-danger" title="{{translate('Translatable')}}"></i></label>
                                        <input type="hidden" name="types[]" value="home_partners_in_success_desc">
                                        <textarea class="form-control" name="home_partners_in_success_desc" rows="3"
                                            placeholder="{{ customTrans('home_partners_in_success_desc', $lang) }}">{{ get_setting('home_partners_in_success_desc', null , $lang , true) }}</textarea>
                                    </div>
                                </div>

                                <!-- Save Button -->
                                <div class="mt-4 text-right">
                                    <button type="submit"
                                        class="btn btn-success w-230px btn-md rounded-2 fs-14 fw-700 shadow-success">{{ translate('Save') }}</button>
                                </div>
                            </div>
                        </form>
                    </div>

                    <!-- Top Brands -->
                    <div class="tab-pane fade  show active" id="brands" role="tabpanel" aria-labelledby="brands-tab">
                        <form action="{{ route('business_settings.update') }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="tab" value="brands">
                            <div class="bg-white p-3 p-sm-2rem">
                                <div class="w-100">
                                    <label
                                        class="col-from-label fs-13 fw-500 mb-3">{{ translate('Top Brands (Max 12)') }}</label>
                                    <!-- Brands -->
                                    <div class="form-group">
                                        <input type="hidden" name="types[]" value="top_brands">
                                        <select name="top_brands[]" class="form-control aiz-selectpicker" multiple
                                            data-max-options="12" data-live-search="true"
                                            data-selected="{{ get_setting('top_brands') }}">
                                            @foreach (\App\Models\Brand::all() as $key => $brand)
                                                <option value="{{ $brand->id }}">
                                                    {{ $brand->getTranslation('name') }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <!-- Save Button -->
                                <div class="mt-4 text-right">
                                    <button type="submit"
                                        class="btn btn-success w-230px btn-md rounded-2 fs-14 fw-700 shadow-success">{{ translate('Save') }}</button>
                                </div>
                            </div>
                        </form>
                    </div>

                    <!-- About Us Images -->
                    <div class="tab-pane fade" id="about-us-images" role="tabpanel" aria-labelledby="about-us-images-tab">
                        <div class="bg-white p-3 p-sm-2rem">
                            <form action="{{ route('business_settings.update') }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="tab" value="about-us-images">

                                <!-- About Us Hero Images -->
                                <div class="mb-5">
                                    <h5 class="mb-3">About Us Hero Images</h5>
                                    <div class="row">
                                        <!-- Desktop Hero Image -->
                                        <div class="col-md-6 mb-4">
                                            <div class="form-group">
                                                <label class="col-from-label fs-13 fw-500">{{ translate('About Us Hero (Desktop)') }}<i class="las la-language text-danger" title="{{translate('Translatable')}}"></i></label><br>
                                                <p class="text-muted">{{ translate('Recommended size: 1200*600px') }}</p>
                                                <div class="input-group" data-toggle="aizuploader" data-type="image">
                                                    <div class="input-group-prepend">
                                                        <div class="input-group-text bg-soft-secondary">{{ translate('Browse') }}</div>
                                                    </div>
                                                    <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                                                    <input type="hidden" name="types[]" value="about_us_hero_desktop{{$settingNameExtra}}">
                                                    <input type="hidden" name="about_us_hero_desktop{{$settingNameExtra}}" value="{{ get_setting('about_us_hero_desktop'.$settingNameExtra) }}" class="selected-files">
                                                </div>
                                                <div class="file-preview box sm"></div>
                                            </div>
                                        </div>
                                        <!-- Mobile Hero Image -->
                                        <div class="col-md-6 mb-4">
                                            <div class="form-group">
                                                <label class="col-from-label fs-13 fw-500">{{ translate('About Us Hero (Mobile)') }}<i class="las la-language text-danger" title="{{translate('Translatable')}}"></i></label><br>
                                                <p class="text-muted">{{ translate('Recommended size: 350*240px') }}</p>
                                                <div class="input-group" data-toggle="aizuploader" data-type="image">
                                                    <div class="input-group-prepend">
                                                        <div class="input-group-text bg-soft-secondary">{{ translate('Browse') }}</div>
                                                    </div>
                                                    <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                                                    <input type="hidden" name="types[]" value="about_us_hero_mobile{{$settingNameExtra}}">
                                                    <input type="hidden" name="about_us_hero_mobile{{$settingNameExtra}}" value="{{ get_setting('about_us_hero_mobile'.$settingNameExtra) }}" class="selected-files">
                                                </div>
                                                <div class="file-preview box sm"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Mission and Values Images -->
                                <div class="mb-5">
                                    <h5 class="mb-3">Mission and Values Images</h5>
                                    <div class="row">
                                        <!-- Our Mission Image -->
                                        <div class="col-md-6 mb-4">
                                            <div class="form-group">
                                                <label class="col-from-label fs-13 fw-500">{{ translate('Our Mission Image') }}<i class="las la-language text-danger" title="{{translate('Translatable')}}"></i></label><br>
                                                <p class="text-muted">{{ translate('Recommended size: 640*760px') }}</p>
                                                <div class="input-group" data-toggle="aizuploader" data-type="image">
                                                    <div class="input-group-prepend">
                                                        <div class="input-group-text bg-soft-secondary">{{ translate('Browse') }}</div>
                                                    </div>
                                                    <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                                                    <input type="hidden" name="types[]" value="our_mission_image{{$settingNameExtra}}">
                                                    <input type="hidden" name="our_mission_image{{$settingNameExtra}}" value="{{ get_setting('our_mission_image'.$settingNameExtra) }}" class="selected-files">
                                                </div>
                                                <div class="file-preview box sm"></div>
                                            </div>
                                        </div>
                                        <!-- Our Values Image -->
                                        <div class="col-md-6 mb-4">
                                            <div class="form-group">
                                                <label class="col-from-label fs-13 fw-500">{{ translate('Our Values Image') }}<i class="las la-language text-danger" title="{{translate('Translatable')}}"></i></label><br>
                                                <p class="text-muted">{{ translate('Recommended size: 640*760px') }}</p>
                                                <div class="input-group" data-toggle="aizuploader" data-type="image">
                                                    <div class="input-group-prepend">
                                                        <div class="input-group-text bg-soft-secondary">{{ translate('Browse') }}</div>
                                                    </div>
                                                    <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                                                    <input type="hidden" name="types[]" value="our_values_image{{$settingNameExtra}}">
                                                    <input type="hidden" name="our_values_image{{$settingNameExtra}}" value="{{ get_setting('our_values_image'.$settingNameExtra) }}" class="selected-files">
                                                </div>
                                                <div class="file-preview box sm"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Partner Images -->
                                <div class="mb-5">
                                    <h5 class="mb-3">Partner with Veloura Care Images</h5>
                                    <div class="row">
                                        <!-- Desktop Partner Image -->
                                        <div class="col-md-6 mb-4">
                                            <div class="form-group">
                                                <label class="col-from-label fs-13 fw-500">{{ translate('Partner with Veloura Care (Desktop)') }}<i class="las la-language text-danger" title="{{translate('Translatable')}}"></i></label><br>
                                                <p class="text-muted">{{ translate('Recommended size: 1200*200px') }}</p>
                                                <div class="input-group" data-toggle="aizuploader" data-type="image">
                                                    <div class="input-group-prepend">
                                                        <div class="input-group-text bg-soft-secondary">{{ translate('Browse') }}</div>
                                                    </div>
                                                    <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                                                    <input type="hidden" name="types[]" value="partner_with_sky_business_desktop{{$settingNameExtra}}">
                                                    <input type="hidden" name="partner_with_sky_business_desktop{{$settingNameExtra}}" value="{{ get_setting('partner_with_sky_business_desktop'.$settingNameExtra) }}" class="selected-files">
                                                </div>
                                                <div class="file-preview box sm"></div>
                                            </div>
                                        </div>
                                        <!-- Mobile Partner Image -->
                                        <div class="col-md-6 mb-4">
                                            <div class="form-group">
                                                <label class="col-from-label fs-13 fw-500">{{ translate('Partner with Veloura Care (Mobile)') }}<i class="las la-language text-danger" title="{{translate('Translatable')}}"></i></label><br>
                                                <p class="text-muted">{{ translate('Recommended size: 400*200px') }}</p>
                                                <div class="input-group" data-toggle="aizuploader" data-type="image">
                                                    <div class="input-group-prepend">
                                                        <div class="input-group-text bg-soft-secondary">{{ translate('Browse') }}</div>
                                                    </div>
                                                    <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                                                    <input type="hidden" name="types[]" value="partner_with_sky_business_mobile{{$settingNameExtra}}">
                                                    <input type="hidden" name="partner_with_sky_business_mobile{{$settingNameExtra}}" value="{{ get_setting('partner_with_sky_business_mobile'.$settingNameExtra) }}" class="selected-files">
                                                </div>
                                                <div class="file-preview box sm"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Competitive Price / Bulk Ordering -->
                                <div class="mb-5">
                                    <h5 class="mb-3">Competitive Price / Bulk Ordering</h5>
                                    <div class="row">
                                        <!-- Competitive Price Image -->
                                        <div class="col-md-6 mb-4">
                                            <div class="form-group">
                                                <label class="col-from-label fs-13 fw-500">{{ translate('Competitive Price') }}<i class="las la-language text-danger" title="{{translate('Translatable')}}"></i></label><br>
                                                <p class="text-muted">{{ translate('Recommended size: 380*320px') }}</p>
                                                <div class="input-group" data-toggle="aizuploader" data-type="image">
                                                    <div class="input-group-prepend">
                                                        <div class="input-group-text bg-soft-secondary">{{ translate('Browse') }}</div>
                                                    </div>
                                                    <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                                                    <input type="hidden" name="types[]" value="competitive_price_image{{$settingNameExtra}}">
                                                    <input type="hidden" name="competitive_price_image{{$settingNameExtra}}" value="{{ get_setting('competitive_price_image'.$settingNameExtra) }}" class="selected-files">
                                                </div>
                                                <div class="file-preview box sm"></div>
                                            </div>
                                        </div>
                                        <!-- Bulk Ordering Image -->
                                        <div class="col-md-6 mb-4">
                                            <div class="form-group">
                                                <label class="col-from-label fs-13 fw-500">{{ translate('Bulk Ordering') }}<i class="las la-language text-danger" title="{{translate('Translatable')}}"></i></label><br>
                                                <p class="text-muted">{{ translate('Recommended size: 380*320px') }}</p>
                                                <div class="input-group" data-toggle="aizuploader" data-type="image">
                                                    <div class="input-group-prepend">
                                                        <div class="input-group-text bg-soft-secondary">{{ translate('Browse') }}</div>
                                                    </div>
                                                    <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                                                    <input type="hidden" name="types[]" value="bulk_ordering_image{{$settingNameExtra}}">
                                                    <input type="hidden" name="bulk_ordering_image{{$settingNameExtra}}" value="{{ get_setting('bulk_ordering_image'.$settingNameExtra) }}" class="selected-files">
                                                </div>
                                                <div class="file-preview box sm"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Efficient Logistics / Exclusive Offers -->
                                <div class="mb-5">
                                    <h5 class="mb-3">Efficient Logistics / Exclusive Offers</h5>
                                    <div class="row">
                                        <!-- Efficient Logistics Image -->
                                        <div class="col-md-6 mb-4">
                                            <div class="form-group">
                                                <label class="col-from-label fs-13 fw-500">{{ translate('Efficient Logistics') }}<i class="las la-language text-danger" title="{{translate('Translatable')}}"></i></label><br>
                                                <p class="text-muted">{{ translate('Recommended size: 380*320px') }}</p>
                                                <div class="input-group" data-toggle="aizuploader" data-type="image">
                                                    <div class="input-group-prepend">
                                                        <div class="input-group-text bg-soft-secondary">{{ translate('Browse') }}</div>
                                                    </div>
                                                    <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                                                    <input type="hidden" name="types[]" value="efficient_logistics_image{{$settingNameExtra}}">
                                                    <input type="hidden" name="efficient_logistics_image{{$settingNameExtra}}" value="{{ get_setting('efficient_logistics_image'.$settingNameExtra) }}" class="selected-files">
                                                </div>
                                                <div class="file-preview box sm"></div>
                                            </div>
                                        </div>
                                        <!-- Exclusive Offers Image -->
                                        <div class="col-md-6 mb-4">
                                            <div class="form-group">
                                                <label class="col-from-label fs-13 fw-500">{{ translate('Exclusive Offers') }}<i class="las la-language text-danger" title="{{translate('Translatable')}}"></i></label><br>
                                                <p class="text-muted">{{ translate('Recommended size: 380*320px') }}</p>
                                                <div class="input-group" data-toggle="aizuploader" data-type="image">
                                                    <div class="input-group-prepend">
                                                        <div class="input-group-text bg-soft-secondary">{{ translate('Browse') }}</div>
                                                    </div>
                                                    <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                                                    <input type="hidden" name="types[]" value="exclusive_offers_image{{$settingNameExtra}}">
                                                    <input type="hidden" name="exclusive_offers_image{{$settingNameExtra}}" value="{{ get_setting('exclusive_offers_image'.$settingNameExtra) }}" class="selected-files">
                                                </div>
                                                <div class="file-preview box sm"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                                <!-- Save Button -->
                                <div class="mt-4 text-right">
                                    <button type="submit" class="btn btn-success w-230px btn-md rounded-2 fs-14 fw-700 shadow-success">{{ translate('Save') }}</button>
                                </div>
                            </form>
                        </div>
                        <form action="{{ route('business_settings.update') }}" method="POST">
                          @csrf
                          <input type="hidden" name="tab" value="about-us-images">
                          @include('backend.website_settings.pages.classic.partials.about_us_preview', ['settingNameExtra' => $settingNameExtra])
                      </form>
                    </div>


                    <!-- About Us Content -->
                    <div class="tab-pane fade" id="about-us-content" role="tabpanel" aria-labelledby="about-us-content-tab">
                        <form action="{{ route('business_settings.update') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="tab" value="about-us-content">
                            <input  type="hidden" name="lang" value="{{ $lang }}" >

                            <div class="bg-white p-3 p-sm-2rem">
                                <!-- Hero Section -->
                                <div class="mb-5">
                                    <h5 class="mb-3">Hero Section</h5>
                                    <div class="form-group">
                                        <label class="col-from-label fs-13 fw-500">{{ translate('Hero Title') }}<i class="las la-language text-danger" title="{{translate('Translatable')}}"></i></label>
                                        <input type="hidden" name="types[]" value="about_us_hero_title">
                                        <input type="text" class="form-control" name="about_us_hero_title" value="{{ get_setting('about_us_hero_title', null,$lang ,true) }}" placeholder="{{ customTrans('about_us_hero_title', $lang) }}">
                                    </div>
                                </div>

                                <!-- We Are Distributors Section -->
                                <div class="mb-5">
                                    <h5 class="mb-3">We Are Distributors Section</h5>
                                    <div class="form-group mb-3">
                                        <label class="col-from-label fs-13 fw-500">{{ translate('Title') }}<i class="las la-language text-danger" title="{{translate('Translatable')}}"></i></label>
                                        <input type="hidden" name="types[]" value="about_us_we_are_distributors_title">
                                        <input type="text" class="form-control" name="about_us_we_are_distributors_title" value="{{ get_setting('about_us_we_are_distributors_title',null,$lang ,true) }}" placeholder="{{ customTrans('about_us_we_are_distributors_title') }}">
                                    </div>
                                    <div class="form-group">
                                        <label class="col-from-label fs-13 fw-500">{{ translate('Description') }}<i class="las la-language text-danger" title="{{translate('Translatable')}}"></i></label>
                                        <input type="hidden" name="types[]" value="about_us_we_are_distributors_description">
                                        <textarea class="form-control" name="about_us_we_are_distributors_description" rows="4" placeholder="{{ customTrans('about_us_we_are_distributors_description') }}">{{ get_setting('about_us_we_are_distributors_description', null,$lang ,true) }}</textarea>
                                    </div>
                                </div>

                                <!-- Statistics Section -->
                                <div class="mb-5">
                                    <h5 class="mb-3">Statistics Section</h5>
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="col-from-label fs-13 fw-500">{{ translate('Years of Experience') }}<i class="las la-language text-danger" title="{{translate('Translatable')}}"></i></label>
                                                <input type="hidden" name="types[]" value="about_us_stats_years_experience">
                                                <input type="text" class="form-control" name="about_us_stats_years_experience" value="{{ get_setting('about_us_stats_years_experience', null,$lang ,true) }}" placeholder="{{ customTrans('about_us_stats_years_experience', $lang) }}">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="col-from-label fs-13 fw-500">{{ translate('Number of Brands') }}<i class="las la-language text-danger" title="{{translate('Translatable')}}"></i></label>
                                                <input type="hidden" name="types[]" value="about_us_stats_brands">
                                                <input type="text" class="form-control" name="about_us_stats_brands" value="{{ get_setting('about_us_stats_brands', null,$lang ,true) }}" placeholder="{{ customTrans('about_us_stats_brands',$lang) }}">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="col-from-label fs-13 fw-500">{{ translate('Number of Products') }}<i class="las la-language text-danger" title="{{translate('Translatable')}}"></i></label>
                                                <input type="hidden" name="types[]" value="about_us_stats_products">
                                                <input type="text" class="form-control" name="about_us_stats_products" value="{{ get_setting('about_us_stats_products', null,$lang ,true) }}" placeholder="{{ customTrans('about_us_stats_products',$lang) }}">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="col-from-label fs-13 fw-500">{{ translate('Number of Clients') }}<i class="las la-language text-danger" title="{{translate('Translatable')}}"></i></label>
                                                <input type="hidden" name="types[]" value="about_us_stats_clients">
                                                <input type="text" class="form-control" name="about_us_stats_clients" value="{{ get_setting('about_us_stats_clients', null,$lang ,true) }}" placeholder="{{ customTrans('about_us_stats_clients',$lang) }}">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Our Mission Section -->
                                <div class="mb-5">
                                    <h5 class="mb-3">Our Mission Section</h5>
                                    <div class="form-group mb-3">
                                        <label class="col-from-label fs-13 fw-500">{{ translate('Mission Title') }}<i class="las la-language text-danger" title="{{translate('Translatable')}}"></i></label>
                                        <input type="hidden" name="types[]" value="about_us_our_mission_title">
                                        <input type="text" class="form-control" name="about_us_our_mission_title" value="{{ get_setting('about_us_our_mission_title', null,$lang ,true) }}" placeholder="{{ customTrans('about_us_our_mission_title',$lang) }}">
                                    </div>
                                    <div class="form-group mb-3">
                                        <label class="col-from-label fs-13 fw-500">{{ translate('Mission Description 1') }}<i class="las la-language text-danger" title="{{translate('Translatable')}}"></i></label>
                                        <input type="hidden" name="types[]" value="about_us_our_mission_description_1">
                                        <textarea class="form-control" name="about_us_our_mission_description_1" rows="3" placeholder="{{ customTrans('about_us_our_mission_description_1',$lang) }}">{{ get_setting('about_us_our_mission_description_1', null,$lang ,true) }}</textarea>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-from-label fs-13 fw-500">{{ translate('Mission Description 2') }}<i class="las la-language text-danger" title="{{translate('Translatable')}}"></i></label>
                                        <input type="hidden" name="types[]" value="about_us_our_mission_description_2">
                                        <textarea class="form-control" name="about_us_our_mission_description_2" rows="3" placeholder="{{ customTrans('about_us_our_mission_description_2',$lang) }}">{{ get_setting('about_us_our_mission_description_2', null,$lang ,true) }}</textarea>
                                    </div>
                                </div>

                                <!-- What We Offer Section -->
                                <div class="mb-5">
                                    <h5 class="mb-3">What We Offer Section</h5>
                                    <div class="form-group mb-3">
                                        <label class="col-from-label fs-13 fw-500">{{ translate('What We Offer Title') }}<i class="las la-language text-danger" title="{{translate('Translatable')}}"></i></label>
                                        <input type="hidden" name="types[]" value="about_us_what_we_offer_title">
                                        <input type="text" class="form-control" name="about_us_what_we_offer_title" value="{{ get_setting('about_us_what_we_offer_title', null,$lang ,true) }}" placeholder="{{ customTrans('about_us_what_we_offer_title',$lang) }}">
                                    </div>
                                    <div class="form-group mb-3">
                                        <label class="col-from-label fs-13 fw-500">{{ translate('Description 1') }}<i class="las la-language text-danger" title="{{translate('Translatable')}}"></i></label>
                                        <input type="hidden" name="types[]" value="about_us_what_we_offer_description_1">
                                        <textarea class="form-control" name="about_us_what_we_offer_description_1" rows="3" placeholder="{{ customTrans('about_us_what_we_offer_description_1',$lang) }}">{{ get_setting('about_us_what_we_offer_description_1', null,$lang ,true) }}</textarea>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-from-label fs-13 fw-500">{{ translate('Description 2') }}<i class="las la-language text-danger" title="{{translate('Translatable')}}"></i></label>
                                        <input type="hidden" name="types[]" value="about_us_what_we_offer_description_2">
                                        <textarea class="form-control" name="about_us_what_we_offer_description_2" rows="3" placeholder="{{ customTrans('about_us_what_we_offer_description_2',$lang) }}">{{ get_setting('about_us_what_we_offer_description_2', null,$lang ,true) }}</textarea>
                                    </div>
                                </div>

                                <!-- Our Values Section -->
                                <div class="mb-5">
                                    <h5 class="mb-3">Our Values Section</h5>
                                    <div class="form-group mb-3">
                                        <label class="col-from-label fs-13 fw-500">{{ translate('Values Title') }}<i class="las la-language text-danger" title="{{translate('Translatable')}}"></i></label>
                                        <input type="hidden" name="types[]" value="about_us_our_values_title">
                                        <input type="text" class="form-control" name="about_us_our_values_title" value="{{ get_setting('about_us_our_values_title', null,$lang ,true) }}" placeholder="{{ customTrans('about_us_our_values_title',$lang) }}">
                                    </div>
                                    <div class="form-group">
                                        <label class="col-from-label fs-13 fw-500">{{ translate('Values Description') }}<i class="las la-language text-danger" title="{{translate('Translatable')}}"></i></label>
                                        <input type="hidden" name="types[]" value="about_us_our_values_description">
                                        <textarea class="form-control" name="about_us_our_values_description" rows="4" placeholder="{{ customTrans('about_us_our_values_description',$lang) }}">{{ get_setting('about_us_our_values_description', null,$lang ,true) }}</textarea>
                                    </div>
                                </div>

                                <!-- Contact Section -->
                                <div class="mb-5">
                                    <h5 class="mb-3">Contact Section</h5>
                                    <div class="form-group mb-3">
                                        <label class="col-from-label fs-13 fw-500">{{ translate('Contact Title') }}<i class="las la-language text-danger" title="{{translate('Translatable')}}"></i></label>
                                        <input type="hidden" name="types[]" value="about_us_contact_title">
                                        <input type="text" class="form-control" name="about_us_contact_title" value="{{ get_setting('about_us_contact_title', null,$lang ,true) }}" placeholder="{{ customTrans('about_us_contact_title',$lang) }}">
                                    </div>
                                    <div class="form-group mb-3">
                                        <label class="col-from-label fs-13 fw-500">{{ translate('Contact Description 1') }}<i class="las la-language text-danger" title="{{translate('Translatable')}}"></i></label>
                                        <input type="hidden" name="types[]" value="about_us_contact_description_1">
                                        <textarea class="form-control" name="about_us_contact_description_1" rows="3" placeholder="{{ customTrans('about_us_contact_description_1',$lang) }}">{{ get_setting('about_us_contact_description_1', null,$lang ,true) }}</textarea>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-from-label fs-13 fw-500">{{ translate('Contact Description 2') }}<i class="las la-language text-danger" title="{{translate('Translatable')}}"></i></label>
                                        <input type="hidden" name="types[]" value="about_us_contact_description_2">
                                        <textarea class="form-control" name="about_us_contact_description_2" rows="3" placeholder="{{ customTrans('about_us_contact_description_2',$lang) }}">{{ get_setting('about_us_contact_description_2', null,$lang ,true) }}</textarea>
                                    </div>
                                </div>

                                <!-- Why We Are The Best Section -->
                                <div class="mb-5">
                                    <h5 class="mb-3">Why We Are The Best Section</h5>
                                    <div class="form-group mb-4">
                                        <label class="col-from-label fs-13 fw-500">{{ translate('Section Title') }}<i class="las la-language text-danger" title="{{translate('Translatable')}}"></i></label>
                                        <input type="hidden" name="types[]" value="about_us_why_best_title">
                                        <input type="text" class="form-control" name="about_us_why_best_title" value="{{ get_setting('about_us_why_best_title', null,$lang ,true) }}" placeholder="{{ customTrans('about_us_why_best_title',$lang) }}">
                                    </div>

                                    <!-- Grid Items -->
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="card mb-3">
                                                <div class="card-header">
                                                    <h6>{{ translate('Competitive Price') }}</h6>
                                                </div>
                                                <div class="card-body">
                                                    <div class="form-group mb-3">
                                                        <label class="col-from-label fs-13 fw-500">{{ translate('Title') }}<i class="las la-language text-danger" title="{{translate('Translatable')}}"></i></label>
                                                        <input type="hidden" name="types[]" value="about_us_competitive_price_title">
                                                        <input type="text" class="form-control" name="about_us_competitive_price_title" value="{{ get_setting('about_us_competitive_price_title', null,$lang ,true) }}" placeholder="{{ customTrans('about_us_competitive_price_title',$lang) }}">
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="col-from-label fs-13 fw-500">{{ translate('Description') }}<i class="las la-language text-danger" title="{{translate('Translatable')}}"></i></label>
                                                        <input type="hidden" name="types[]" value="about_us_competitive_price_description">
                                                        <textarea class="form-control" name="about_us_competitive_price_description" rows="3" placeholder="{{ customTrans('about_us_competitive_price_description',$lang) }}">{{ get_setting('about_us_competitive_price_description', null,$lang ,true) }}</textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="card mb-3">
                                                <div class="card-header">
                                                    <h6>{{ translate('Bulk Ordering') }}</h6>
                                                </div>
                                                <div class="card-body">
                                                    <div class="form-group mb-3">
                                                        <label class="col-from-label fs-13 fw-500">{{ translate('Title') }}<i class="las la-language text-danger" title="{{translate('Translatable')}}"></i></label>
                                                        <input type="hidden" name="types[]" value="about_us_bulk_ordering_title">
                                                        <input type="text" class="form-control" name="about_us_bulk_ordering_title" value="{{ get_setting('about_us_bulk_ordering_title', null,$lang ,true) }}" placeholder="{{ customTrans('about_us_bulk_ordering_title',$lang) }}">
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="col-from-label fs-13 fw-500">{{ translate('Description') }}<i class="las la-language text-danger" title="{{translate('Translatable')}}"></i></label>
                                                        <input type="hidden" name="types[]" value="about_us_bulk_ordering_description">
                                                        <textarea class="form-control" name="about_us_bulk_ordering_description" rows="3" placeholder="{{ customTrans('about_us_bulk_ordering_description',$lang) }}">{{ get_setting('about_us_bulk_ordering_description', null,$lang ,true) }}</textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="card mb-3">
                                                <div class="card-header">
                                                    <h6>{{ translate('Efficient Logistics') }}</h6>
                                                </div>
                                                <div class="card-body">
                                                    <div class="form-group mb-3">
                                                        <label class="col-from-label fs-13 fw-500">{{ translate('Title') }}<i class="las la-language text-danger" title="{{translate('Translatable')}}"></i></label>
                                                        <input type="hidden" name="types[]" value="about_us_efficient_logistics_title">
                                                        <input type="text" class="form-control" name="about_us_efficient_logistics_title" value="{{ get_setting('about_us_efficient_logistics_title', null,$lang ,true) }}" placeholder="{{ customTrans('about_us_efficient_logistics_title',$lang) }}">
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="col-from-label fs-13 fw-500">{{ translate('Description') }}<i class="las la-language text-danger" title="{{translate('Translatable')}}"></i></label>
                                                        <input type="hidden" name="types[]" value="about_us_efficient_logistics_description">
                                                        <textarea class="form-control" name="about_us_efficient_logistics_description" rows="3" placeholder="{{ customTrans('about_us_efficient_logistics_description',$lang) }}">{{ get_setting('about_us_efficient_logistics_description', null,$lang ,true) }}</textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="card mb-3">
                                                <div class="card-header">
                                                    <h6>{{ translate('Exclusive Offers') }}</h6>
                                                </div>
                                                <div class="card-body">
                                                    <div class="form-group mb-3">
                                                        <label class="col-from-label fs-13 fw-500">{{ translate('Title') }}<i class="las la-language text-danger" title="{{translate('Translatable')}}"></i></label>
                                                        <input type="hidden" name="types[]" value="about_us_exclusive_offers_title">
                                                        <input type="text" class="form-control" name="about_us_exclusive_offers_title" value="{{ get_setting('about_us_exclusive_offers_title', null,$lang ,true) }}" placeholder="{{ customTrans('about_us_exclusive_offers_title',$lang) }}">
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="col-from-label fs-13 fw-500">{{ translate('Description') }}<i class="las la-language text-danger" title="{{translate('Translatable')}}"></i></label>
                                                        <input type="hidden" name="types[]" value="about_us_exclusive_offers_description">
                                                        <textarea class="form-control" name="about_us_exclusive_offers_description" rows="3" placeholder="{{ customTrans('about_us_exclusive_offers_description',$lang) }}">{{ get_setting('about_us_exclusive_offers_description', null,$lang ,true) }}</textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Save Button -->
                                <div class="mt-4 text-right">
                                    <button type="submit" class="btn btn-success w-230px btn-md rounded-2 fs-14 fw-700 shadow-success">{{ translate('Save') }}</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script type="text/javascript">
        $(document).ready(function() {
            AIZ.plugins.bootstrapSelect('refresh');
        });
    </script>
    <script>
        $(document).ready(function() {
            var hash = document.location.hash;
            if (hash) {
                $('.nav-tabs a[href="' + hash + '"]').tab('show');
            } else {
                $('.nav-tabs a[href="#home_slider"]').tab('show');
            }

            // Change hash for page-reload
            $('.nav-tabs a').on('shown.bs.tab', function(e) {
                window.location.hash = e.target.hash;
            });
        });
    </script>
@endsection
