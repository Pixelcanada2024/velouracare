@extends('backend.layouts.app')

@section('content')
  <div class="aiz-titlebar text-left mt-2 mb-3">
    <div class="row align-items-center">
      <div class="col">
        <h1 class="h3">{{ translate('Website Footer') }}</h1>
      </div>
    </div>
  </div>

  <!-- Language -->
  <ul class="nav nav-tabs nav-fill language-bar">
    @foreach (get_all_active_language() as $key => $language)
      <li class="nav-item">
        <a class="nav-link text-reset @if ($language->code == $lang) active @endif py-3"
          href="{{ route('website.footer', ['lang' => $language->code]) }}">
          <img src="{{ static_asset('assets/img/flags/' . $language->code . '.png') }}" height="11" class="mr-1">
          <span>{{ $language->name }}</span>
        </a>
      </li>
    @endforeach
  </ul>

  <div style="width:100%; height:3rem;"></div>

  <!-- Footer Widget -->
  <div class="card">
    <div class="card-header">
      <h6 class="fw-600 mb-0">{{ translate('Footer Widget') }}</h6>
    </div>
    <div class="card-body">
      <div class="row gutters-10">

        <!-- Footer Info -->
        {{-- <div class="col-lg-12">
          <div class="card shadow-none bg-light">
            <div class="card-header">
              <h6 class="mb-0">{{ translate('Footer Info Widget') }}</h6>
            </div>
            <div class="card-body">
              <form action="{{ route('business_settings.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <!-- Title -->
                <div class="form-group">
                  <label>{{ translate('Title') }} ({{ translate('Translatable') }})</label>
                  <input type="hidden" name="types[][{{ $lang }}]" value="footer_title">
                  <input type="text" class="form-control" placeholder="Footer title" name="footer_title"
                    value="{{ get_setting('footer_title',null,$lang) }}">
                </div>
                <!-- About description -->
                <div class="form-group">
                  <label>{{ translate('Footer description') }} ({{ translate('Translatable') }})</label>
                  <input type="hidden" name="types[][{{ $lang }}]" value="footer_description">
                  <textarea class="form-control" name="footer_description" rows="6"
                    placeholder="Type..">{{ get_setting('footer_description',null,$lang); }}</textarea>
                </div>
                <!-- Update Button -->
                <div class="mt-4 text-right">
                  <button type="submit" class="btn btn-success w-230px btn-md rounded-2 fs-14 fw-700 shadow-success">{{
                    translate('Update') }}</button>
                </div>
              </form>
            </div>
          </div>
        </div> --}}

        <!-- About Widget -->
        <div class="col-lg-6">
          <div class="card shadow-none bg-light">
            <div class="card-header">
              <h6 class="mb-0">{{ translate('About Widget') }}</h6>
            </div>
            <div class="card-body">
              <form action="{{ route('business_settings.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <!-- Footer Logo -->
                {{-- <div class="form-group">
                  <label class="form-label" for="signinSrEmail">{{ translate('Footer Logo') }}</label>
                  <div class="input-group " data-toggle="aizuploader" data-type="image">
                    <div class="input-group-prepend">
                      <div class="input-group-text bg-soft-secondary font-weight-medium">{{ translate('Browse') }}</div>
                    </div>
                    <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                    <input type="hidden" name="types[]" value="footer_logo">
                    <input type="hidden" name="footer_logo" class="selected-files"
                      value="{{ get_setting('footer_logo') }}">
                  </div>
                  <div class="file-preview"></div>
                  <small class="text-muted">{{ translate("Minimum dimensions required: 275px width X 44px height.")
                    }}</small>
                </div> --}}
                <!-- About description -->
                {{-- <div class="form-group">
                  <label>{{ translate('About description') }} ({{ translate('Translatable') }})</label>
                  <input type="hidden" name="types[][{{ $lang }}]" value="about_us_description">
                  <textarea class="aiz-text-editor form-control" name="about_us_description"
                    data-buttons='[["font", ["bold", "underline", "italic"]],["para", ["ul", "ol"]],["view", ["undo","redo"]]]'
                    placeholder="Type.." data-min-height="150">
                                                    {!! get_setting('about_us_description', null, $lang) !!}
                                                </textarea>
                </div> --}}
                <!-- Play Store Link -->
                <div class="form-group">
                  <label>{{ translate('Play Store Link') }}</label>
                  <input type="hidden" name="types[]" value="play_store_link">
                  <input type="text" class="form-control" placeholder="http://" name="play_store_link"
                    value="{{ get_setting('play_store_link') }}">
                </div>
                <!-- App Store Link -->
                <div class="form-group">
                  <label>{{ translate('App Store Link') }}</label>
                  <input type="hidden" name="types[]" value="app_store_link">
                  <input type="text" class="form-control" placeholder="http://" name="app_store_link"
                    value="{{ get_setting('app_store_link') }}">
                </div>
                <!-- Map Iframe Src -->
                {{-- <div class="form-group">
                  <label>{{ translate('Map Iframe Src') }}</label>
                  <input type="hidden" name="types[]" value="map_iframe_src">
                  <input type="text" class="form-control" placeholder="http://" name="map_iframe_src"
                    value="{{ get_setting('map_iframe_src') }}">
                </div> --}}
                <!-- QR Code -->
                <div class="form-group">
                  <label>{{ translate('WhatsApp QR Code') }}</label>
                  <div class="input-group " data-toggle="aizuploader" data-type="image">
                    <div class="input-group-prepend">
                      <div class="input-group-text bg-soft-secondary font-weight-medium">{{ translate('Browse') }}</div>
                    </div>
                    <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                    <input type="hidden" name="types[]" value="footer_qr_code">
                    <input type="hidden" name="footer_qr_code" class="selected-files"
                      value="{{ get_setting('footer_qr_code') }}">
                  </div>
                  <div class="file-preview box sm"></div>
                </div>
                <!-- WhatsApp Link -->
                <div class="input-group form-group">
                  <div class="input-group-prepend">
                    <span class="input-group-text"><i class="lab la-whatsapp"></i></span>
                  </div>
                  <input type="hidden" name="types[]" value="whatsapp_link">
                  <input type="text" class="form-control" placeholder="http://" name="whatsapp_link"
                    value="{{ get_setting('whatsapp_link') }}">
                </div>
                <!-- Update Button -->
                <div class="mt-4 text-right">
                  <button type="submit"
                    class="btn btn-success w-230px btn-md rounded-2 fs-14 fw-700 shadow-success">{{ translate('Update') }}</button>
                </div>
              </form>
            </div>
          </div>
        </div>

        <!-- Contact Info Widget -->
        <div class="col-lg-6">
          <div class="card shadow-none bg-light">
            <div class="card-header">
              <h6 class="mb-0">{{ translate('Contact Info Widget') }}</h6>
            </div>
            <div class="card-body">
              <form action="{{ route('business_settings.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <!-- Contact address -->
                <div class="form-group">
                  <label>{{ translate('Main Contact address') }} ({{ translate('Translatable') }})</label>
                  <input type="hidden" name="types[][{{ $lang }}]" value="contact_address">
                  <input type="text" class="form-control" placeholder="{{ customTrans('address') }}"
                    name="contact_address" value="{{ get_setting('contact_address', null, $lang, true) }}">
                </div>
                <!-- Contact phone -->
                <div class="form-group">
                  <label>{{ translate('Contact phone') }}</label>
                  <input type="hidden" name="types[]" value="contact_phone">
                  <input type="text" class="form-control" placeholder="{{ translate('Phone') }}" name="contact_phone"
                    value="{{ get_setting('contact_phone') }}">
                </div>
                <!-- Contact email -->
                <div class="form-group">
                  <label>{{ translate('Contact email') }}</label>
                  <input type="hidden" name="types[]" value="contact_email">
                  <input type="text" class="form-control" placeholder="{{ translate('Email') }}" name="contact_email"
                    value="{{ get_setting('contact_email') }}">
                </div>
                <!-- Contact email -->
                <div class="form-group">
                  <label>{{ translate('Work Hours') }}</label>
                  <input type="hidden" name="types[][{{ $lang }}]" value="work_hours">
                  <input type="text" class="form-control" placeholder="{{ customTrans('work_hours') }}" name="work_hours"
                    value="{{ get_setting('work_hours', null, $lang, true) }}">
                </div>
                <!-- Update Button -->
                <div class="mt-4 text-right">
                  <button type="submit"
                    class="btn btn-success w-230px btn-md rounded-2 fs-14 fw-700 shadow-success">{{ translate('Update') }}</button>
                </div>
              </form>
            </div>
          </div>
        </div>

        <!-- Link Widget One -->
        {{-- <div class="col-lg-12">
          <div class="card shadow-none bg-light">
            <div class="card-header">
              <h6 class="mb-0">{{ translate('Link Widget One') }}</h6>
            </div>
            <div class="card-body">
              <form action="{{ route('business_settings.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <!-- Title -->
                <div class="form-group">
                  <label>{{ translate('Title') }} ({{ translate('Translatable') }})</label>
                  <input type="hidden" name="types[][{{ $lang }}]" value="widget_one">
                  <input type="text" class="form-control" placeholder="Widget title" name="widget_one"
                    value="{{ get_setting('widget_one', null, $lang) }}">
                </div>
                <!-- Links -->
                <div class="form-group">
                  <label>{{ translate('Links') }} - ({{ translate('Translatable') }} {{ translate('Label') }})</label>
                  <div class="w3-links-target">
                    <input type="hidden" name="types[][{{ $lang }}]" value="widget_one_labels">
                    <input type="hidden" name="types[]" value="widget_one_links">
                    @if (get_setting('widget_one_labels', null, $lang) != null)
                    @foreach (json_decode(get_setting('widget_one_labels', null, $lang), true) as $key => $value)
                    @php
                    $widget_one_links = '';
                    if (isset(json_decode(get_setting('widget_one_links'), true)[$key])) {
                    $widget_one_links = json_decode(get_setting('widget_one_links'), true)[$key];
                    }
                    @endphp
                    <div class="row gutters-5">
                      <div class="col-4">
                        <div class="form-group">
                          <input type="text" class="form-control" placeholder="{{ translate('Label') }}"
                            name="widget_one_labels[]" value="{{ $value }}">
                        </div>
                      </div>
                      <div class="col">
                        <div class="form-group">
                          <input type="text" class="form-control" placeholder="http://" name="widget_one_links[]"
                            value="{{ $widget_one_links }}">
                        </div>
                      </div>
                      <div class="col-auto">
                        <button type="button" class="mt-1 btn btn-icon btn-circle btn-sm btn-soft-danger"
                          data-toggle="remove-parent" data-parent=".row">
                          <i class="las la-times"></i>
                        </button>
                      </div>
                    </div>
                    @endforeach
                    @endif
                  </div>
                  <button type="button" class="btn btn-soft-secondary btn-sm" data-toggle="add-more" data-content='<div class="row gutters-5">
                                    <div class="col-4">
                                      <div class="form-group">
                                        <input type="text" class="form-control" placeholder="{{translate(' Label')}}"
                    name="widget_one_labels[]">
                </div>
            </div>
            <div class="col">
              <div class="form-group">
                <input type="text" class="form-control" placeholder="http://" name="widget_one_links[]">
              </div>
            </div>
            <div class="col-auto">
              <button type="button" class="mt-1 btn btn-icon btn-circle btn-sm btn-soft-danger"
                data-toggle="remove-parent" data-parent=".row">
                <i class="las la-times"></i>
              </button>
            </div>
          </div>' data-target=".w3-links-target">
          {{ translate('Add New') }}
          </button>
        </div>
        <!-- Update Button -->
        <div class="mt-4 text-right">
          <button type="submit" class="btn btn-success w-230px btn-md rounded-2 fs-14 fw-700 shadow-success">{{
            translate('Update') }}</button>
        </div>
        </form>
      </div>
    </div>
  </div> --}}

  </div>
  </div>
  </div>
  @include('backend.website_settings.footer_partials.location_info')

  <!-- Contact Info -->
  <div class="card">
    <div class="card-header">
      <h6 class="fw-600 mb-0">{{ translate('Contact Info') }}</h6>
    </div>
    <form action="{{ route('business_settings.update') }}" method="POST" enctype="multipart/form-data">
      @csrf
      <div class="card-body">
        <!-- Copyright Widget -->
        {{-- <div class="card shadow-none bg-light">
          <div class="card-header">
            <h6 class="mb-0">{{ translate('Copyright Widget ') }}</h6>
          </div>
          <div class="card-body">
            <div class="form-group">
              <label>{{ translate('Copyright Text') }} ({{ translate('Translatable') }})</label>
              <input type="hidden" name="types[][{{ $lang }}]" value="frontend_copyright_text">
              <textarea class="aiz-text-editor form-control" name="frontend_copyright_text"
                data-buttons='[["font", ["bold", "underline", "italic"]],["insert", ["link"]],["view", ["undo","redo"]]]'
                placeholder="Type.." data-min-height="150">
                                            {!! get_setting('frontend_copyright_text', null, $lang) !!}
                                        </textarea>
            </div>
          </div>
        </div> --}}

        <!-- Social Link Widget -->
        <div class="col-lg-6">
          <div class="card shadow-none bg-light">
            <div class="card-header">
              <h6 class="mb-0">{{ translate('Social Link Widget ') }}</h6>
            </div>
            <div class="card-body">
              <div class="form-group row">
                <label class="col-md-2 col-from-label">{{ translate('Show Social Links?') }}</label>
                <div class="col-md-9">
                  <label class="aiz-switch aiz-switch-success mb-0">
                    <input type="hidden" name="types[]" value="show_social_links">
                    <input type="checkbox" name="show_social_links" @if (get_setting('show_social_links') == 'on') checked
                    @endif>
                    <span></span>
                  </label>
                </div>
              </div>
              <div class="form-group">
                <label>{{ translate('Social Links') }}</label>
                <!-- Facebook Link -->
                <div class="input-group form-group">
                  <div class="input-group-prepend">
                    <span class="input-group-text"><i class="lab la-facebook-f"></i></span>
                  </div>
                  <input type="hidden" name="types[]" value="facebook_link">
                  <input type="text" class="form-control" placeholder="http://" name="facebook_link"
                    value="{{ get_setting('facebook_link') }}">
                </div>
                <!-- Twitter Link -->
                <div class="input-group form-group">
                  <div class="input-group-prepend">
                    <span class="input-group-text">
                      <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" fill="currentColor" width="16"
                        height="16">
                        <path
                          d="M357.2 48L427.8 48 273.6 224.2 455 464 313 464 201.7 318.6 74.5 464 3.8 464 168.7 275.5-5.2 48 140.4 48 240.9 180.9 357.2 48zM332.4 421.8l39.1 0-252.4-333.8-42 0 255.3 333.8z" />
                      </svg>
                    </span>
                  </div>

                  <input type="hidden" name="types[]" value="twitter_link">
                  <input type="text" class="form-control" placeholder="http://" name="twitter_link"
                    value="{{ get_setting('twitter_link') }}">
                </div>

                <!-- Instagram Link -->
                <div class="input-group form-group">
                  <div class="input-group-prepend">
                    <span class="input-group-text"><i class="lab la-instagram"></i></span>
                  </div>
                  <input type="hidden" name="types[]" value="instagram_link">
                  <input type="text" class="form-control" placeholder="http://" name="instagram_link"
                    value="{{ get_setting('instagram_link') }}">
                </div>
                <!-- Youtube Link -->
                <div class="input-group form-group">
                  <div class="input-group-prepend">
                    <span class="input-group-text"><i class="lab la-youtube"></i></span>
                  </div>
                  <input type="hidden" name="types[]" value="youtube_link">
                  <input type="text" class="form-control" placeholder="http://" name="youtube_link"
                    value="{{ get_setting('youtube_link') }}">
                </div>
                <!-- Linkedin Link -->
                <div class="input-group form-group">
                  <div class="input-group-prepend">
                    <span class="input-group-text"><i class="lab la-linkedin-in"></i></span>
                  </div>
                  <input type="hidden" name="types[]" value="linkedin_link">
                  <input type="text" class="form-control" placeholder="http://" name="linkedin_link"
                    value="{{ get_setting('linkedin_link') }}">
                </div>
                <!-- TikTok Link -->
                {{-- <div class="input-group form-group">
                  <div class="input-group-prepend">
                    <span class="input-group-text">
                      <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" fill="currentColor" width="16"
                        height="16">
                        <path
                          d="M448.5 209.9c-44 .1-87-13.6-122.8-39.2l0 178.7c0 33.1-10.1 65.4-29 92.6s-45.6 48-76.6 59.6-64.8 13.5-96.9 5.3-60.9-25.9-82.7-50.8-35.3-56-39-88.9 2.9-66.1 18.6-95.2 40-52.7 69.6-67.7 62.9-20.5 95.7-16l0 89.9c-15-4.7-31.1-4.6-46 .4s-27.9 14.6-37 27.3-14 28.1-13.9 43.9 5.2 31 14.5 43.7 22.4 22.1 37.4 26.9 31.1 4.8 46-.1 28-14.4 37.2-27.1 14.2-28.1 14.2-43.8l0-349.4 88 0c-.1 7.4 .6 14.9 1.9 22.2 3.1 16.3 9.4 31.9 18.7 45.7s21.3 25.6 35.2 34.6c19.9 13.1 43.2 20.1 67 20.1l0 87.4z" />
                      </svg>
                    </span>
                  </div>

                  <input type="hidden" name="types[]" value="tiktok_link">
                  <input type="text" class="form-control" placeholder="http://" name="tiktok_link"
                    value="{{ get_setting('tiktok_link') }}">
                </div> --}}

              </div>
            </div>
          </div>
        </div>

        <!-- Download App Link -->
        {{-- @if (get_setting('vendor_system_activation') == 1 || addon_is_activated('delivery_boy'))
        <div class="card shadow-none bg-light">
          <div class="card-header">
            <h6 class="mb-0">{{ translate('Download App Link') }}</h6>
          </div>
          <div class="card-body">
            <!-- Seller App Link -->
            @if (get_setting('vendor_system_activation') == 1)
            <div class="form-group">
              <label>{{ translate('Seller App Link') }}</label>
              <div class="input-group form-group">
                <input type="hidden" name="types[]" value="seller_app_link">
                <input type="text" class="form-control" placeholder="http://" name="seller_app_link"
                  value="{{ get_setting('seller_app_link')}}">
              </div>
            </div>
            @endif
            <!-- Delivery Boy App Link -->
            @if (addon_is_activated('delivery_boy'))
            <div class="form-group">
              <label>{{ translate('Delivery Boy App Link') }}</label>
              <div class="input-group form-group">
                <input type="hidden" name="types[]" value="delivery_boy_app_link">
                <input type="text" class="form-control" placeholder="http://" name="delivery_boy_app_link"
                  value="{{ get_setting('delivery_boy_app_link')}}">
              </div>
            </div>
            @endif
          </div>
        </div>
        @endif --}}

        <!-- Payment Methods Widget -->
        {{-- <div class="card shadow-none bg-light">
          <div class="card-header">
            <h6 class="mb-0">{{ translate('Payment Methods Widget ') }}</h6>
          </div>
          <div class="card-body">
            <div class="form-group">
              <label>{{ translate('Payment Methods') }}</label>
              <div class="input-group" data-toggle="aizuploader" data-type="image" data-multiple="true">
                <div class="input-group-prepend">
                  <div class="input-group-text bg-soft-secondary font-weight-medium">{{ translate('Browse')}}</div>
                </div>
                <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                <input type="hidden" name="types[]" value="payment_method_images">
                <input type="hidden" name="payment_method_images" class="selected-files"
                  value="{{ get_setting('payment_method_images')}}">
              </div>
              <div class="file-preview box sm"></div>
              <small class="text-muted">{{ translate("Minimum dimensions required: 144px width X 20px height.") }}</small>
            </div>
          </div>
        </div> --}}

        <!-- Update Button -->
        <div class="mt-4 text-right">
          <button type="submit"
            class="btn btn-success w-230px btn-md rounded-2 fs-14 fw-700 shadow-success">{{ translate('Update') }}</button>
        </div>
      </div>
    </form>
  </div>
@endsection
