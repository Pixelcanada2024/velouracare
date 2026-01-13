<!-- Home Slider -->
<div class="tab-pane fade" id="home_slider" role="tabpanel" aria-labelledby="home-slider-tab">
  <form action="{{ route('business_settings.update') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <input type="hidden" name="tab" value="home_slider">
    <input type="hidden" name="types[][{{ $lang }}]" value="home_slider_images">
    <input type="hidden" name="types[][{{ $lang }}]" value="home_slider_links">

    <div class="bg-white p-3 p-sm-2rem">
      <div class="w-100">
        <!-- Information -->
        <div class="fs-11 d-flex mb-2rem">
          <div>
            <svg id="_79508b4b8c932dcad9066e2be4ca34f2" data-name="79508b4b8c932dcad9066e2be4ca34f2"
              xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16">
              <path id="Path_40683" data-name="Path 40683"
                d="M8,16a8,8,0,1,1,8-8A8.024,8.024,0,0,1,8,16ZM8,1.333A6.667,6.667,0,1,0,14.667,8,6.686,6.686,0,0,0,8,1.333Z"
                fill="#9da3ae" />
              <path id="Path_40684" data-name="Path 40684"
                d="M10.6,15a.926.926,0,0,1-.667-.333c-.333-.467-.067-1.133.667-2.933.133-.267.267-.6.4-.867a.714.714,0,0,1-.933-.067.644.644,0,0,1,0-.933A3.408,3.408,0,0,1,11.929,9a.926.926,0,0,1,.667.333c.333.467.067,1.133-.667,2.933-.133.267-.267.6-.4.867a.714.714,0,0,1,.933.067.644.644,0,0,1,0,.933A3.408,3.408,0,0,1,10.6,15Z"
                transform="translate(-3.262 -3)" fill="#9da3ae" />
              <circle id="Ellipse_813" data-name="Ellipse 813" cx="1" cy="1" r="1" transform="translate(8 3.333)"
                fill="#9da3ae" />
              <path id="Path_40685" data-name="Path 40685"
                d="M12.833,7.167a1.333,1.333,0,1,1,1.333-1.333A1.337,1.337,0,0,1,12.833,7.167Zm0-2a.63.63,0,0,0-.667.667.667.667,0,1,0,1.333,0A.63.63,0,0,0,12.833,5.167Z"
                transform="translate(-3.833 -1.5)" fill="#9da3ae" />
            </svg>
          </div>
          <div class="ml-2 text-gray">
            <div class="mb-2">{{ translate('Minimum dimensions required: 1100px width X 460px height.') }}</div>
            <div>
              {{ translate('We have limited banner height to maintain UI. We had to crop from both left & right side in view for different devices to make it responsive. Before designing banner keep these points in mind.') }}
            </div>
          </div>
        </div>

        <!-- Images & links -->
        <div class="home-slider-target">
          @php
      $home_slider_images = get_setting('home_slider_images', null, $lang);
      $home_slider_links = get_setting('home_slider_links', null, $lang);
    @endphp
          @if ($home_slider_images != null)
          @foreach (json_decode($home_slider_images, true) as $key => $value)
        <div class="p-3 p-md-4 mb-3 mb-md-2rem remove-parent" style="border: 1px dashed #e4e5eb;">
        <div class="row gutters-5">
          <!-- Image -->
          <div class="col-md-5">
          <div class="form-group mb-md-0">
          <div class="input-group" data-toggle="aizuploader" data-type="image">
          <div class="input-group-prepend">
            <div class="input-group-text bg-soft-secondary font-weight-medium">{{ translate('Browse')}}</div>
          </div>
          <div class="form-control file-amount">{{ translate('Choose File') }}</div>
          <input type="hidden" name="home_slider_images[]" class="selected-files"
            value="{{ json_decode($home_slider_images, true)[$key] }}">
          </div>
          <div class="file-preview box sm">
          </div>
          </div>
          </div>
          <!-- link -->
          <div class="col-md">
          <div class="form-group mb-md-0">
          <input type="text" class="form-control" placeholder="http://" name="home_slider_links[]"
          value="{{ isset(json_decode($home_slider_links, true)[$key]) ? json_decode($home_slider_links, true)[$key] : '' }}">
          </div>
          </div>
          <!-- remove parent button -->
          <div class="col-md-auto">
          <div class="form-group mb-md-0">
          <button type="button" class="mt-1 btn btn-icon btn-circle btn-sm btn-soft-danger"
          data-toggle="remove-parent" data-parent=".remove-parent">
          <i class="las la-times"></i>
          </button>
          </div>
          </div>
        </div>
        </div>
        @endforeach
      @endif
        </div>

        <!-- Add button -->
        <div class="">
          <button type="button"
            class="btn btn-block border hov-bg-soft-secondary fs-14 rounded-0 d-flex align-items-center justify-content-center"
            style="background: #fcfcfc;" data-toggle="add-more" data-content='
											<div class="p-3 p-md-4 mb-3 mb-md-2rem remove-parent" style="border: 1px dashed #e4e5eb;">
												<div class="row gutters-5">
													<!-- Image -->
													<div class="col-md-5">
														<div class="form-group mb-md-0">
															<div class="input-group" data-toggle="aizuploader" data-type="image">
																<div class="input-group-prepend">
																	<div class="input-group-text bg-soft-secondary font-weight-medium">{{ translate('Browse')}}</div>
																</div>
																<div class="form-control file-amount">{{ translate('Choose File') }}</div>
																<input type="hidden" name="home_slider_images[]" class="selected-files" value="">
															</div>
															<div class="file-preview box sm">
															</div>
														</div>
													</div>
													<!-- link -->
													<div class="col-md">
														<div class="form-group mb-md-0">
															<input type="text" class="form-control" placeholder="http://" name="home_slider_links[]" value="">
														</div>
													</div>
													<!-- remove parent button -->
													<div class="col-md-auto">
														<div class="form-group mb-md-0">
															<button type="button" class="mt-1 btn btn-icon btn-circle btn-sm btn-soft-danger" data-toggle="remove-parent" data-parent=".remove-parent">
																<i class="las la-times"></i>
															</button>
														</div>
													</div>
												</div>
											</div>' data-target=".home-slider-target">
            <i class="las la-2x text-success la-plus-circle"></i>
            <span class="ml-2">{{ translate('Add New') }}</span>
          </button>
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

<!-- Today's Deal -->
<div class="tab-pane fade" id="todays_deal" role="tabpanel" aria-labelledby="todays-deal-tab">
  <form action="{{ route('business_settings.update') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <input type="hidden" name="tab" value="todays_deal">
    <div class="bg-white p-3 p-sm-2rem">
      <div class="row">
        <!-- Large Banner -->
        <div class="col-lg-6">
          <div class="form-group">
            <label class="col-from-label fs-13 fw-500">{{ translate("Large Banner") }}
              (<small>{{ translate('Will be shown in large device') }}</small>)</label>
            <div class="input-group " data-toggle="aizuploader" data-type="image">
              <div class="input-group-prepend">
                <div class="input-group-text bg-soft-secondary">{{ translate('Browse') }}</div>
              </div>
              <div class="form-control file-amount">{{ translate('Choose File') }}</div>
              <input type="hidden" name="types[][{{ $lang }}]" value="todays_deal_banner">
              <input type="hidden" name="todays_deal_banner"
                value="{{ get_setting('todays_deal_banner', null, $lang) }}" class="selected-files">
            </div>
            <div class="file-preview box"></div>
            <small
              class="text-muted">{{ translate("Minimum dimensions required: 1370px width X 242px height.") }}</small>
          </div>
        </div>
        <!-- Small Banner -->
        <div class="col-lg-6">
          <div class="form-group">
            <label class="col-from-label fs-13 fw-500">{{ translate("Small Banner") }}
              (<small>{{ translate('Will be shown in small device') }}</small>)</label>
            <div class="input-group" data-toggle="aizuploader" data-type="image">
              <div class="input-group-prepend">
                <div class="input-group-text bg-soft-secondary">{{ translate('Browse') }}</div>
              </div>
              <div class="form-control file-amount">{{ translate('Choose File') }}</div>
              <input type="hidden" name="types[][{{ $lang }}]" value="todays_deal_banner_small">
              <input type="hidden" name="todays_deal_banner_small"
                value="{{ get_setting('todays_deal_banner_small', null, $lang) }}" class="selected-files">
            </div>
            <div class="file-preview box"></div>
            <small
              class="text-muted">{{ translate("Minimum dimensions required: 400px width X 200px height.") }}</small>
          </div>
        </div>
        <!-- Products background color -->
        <div class="col-lg-6">
          <div class="form-group">
            <label class="col-from-label fs-13 fw-500">{{ translate('Products background color') }}</label>
            <div class="input-group">
              @php $todays_deal_bg_color = get_setting('todays_deal_bg_color'); @endphp
              <input type="hidden" name="types[]" value="todays_deal_bg_color">
              <input type="text" class="form-control aiz-color-input" placeholder="#000000" name="todays_deal_bg_color"
                value="{{ $todays_deal_bg_color }}">
              <div class="input-group-append">
                <span class="input-group-text p-0">
                  <input class="aiz-color-picker border-0 size-40px" type="color" value="{{ $todays_deal_bg_color }}">
                </span>
              </div>
            </div>
          </div>
        </div>

        <!-- Banner Text Color -->
        <div class="col-lg-12">
          <div class="form-group">
            <label class="col-from-label fs-13 fw-500">{{ translate("Today's Deal Banner Text Color") }}</label>
            <div class="input-group d-flex">
              @php
        $todays_deal_banner_text_color = get_setting('todays_deal_banner_text_color');
      @endphp
              <input type="hidden" name="types[]" value="todays_deal_banner_text_color">
              <div class="radio mar-btm mr-3 d-flex align-items-center">
                <input id="todays_deal_banner_text_light" class="magic-radio" type="radio"
                  name="todays_deal_banner_text_color" value="light" @if(($todays_deal_banner_text_color == 'light') || ($todays_deal_banner_text_color == null)) checked @endif>
                <label for="todays_deal_banner_text_light" class="mb-0 ml-2">{{translate('Light')}}</label>
              </div>
              <div class="radio mar-btm mr-3 d-flex align-items-center">
                <input id="todays_deal_banner_text_dark" class="magic-radio" type="radio"
                  name="todays_deal_banner_text_color" value="dark" @if($todays_deal_banner_text_color == 'dark') checked
          @endif>
                <label for="todays_deal_banner_text_dark" class="mb-0 ml-2">{{translate('Dark')}}</label>
              </div>
            </div>
          </div>
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

<!-- Banner Level 1 -->
<div class="tab-pane fade" id="banner_1" role="tabpanel" aria-labelledby="banner-1-tab">
  <form action="{{ route('business_settings.update') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <input type="hidden" name="tab" value="banner_1">
    <input type="hidden" name="types[][{{ $lang }}]" value="home_banner1_images">
    <input type="hidden" name="types[][{{ $lang }}]" value="home_banner1_links">

    <div class="bg-white p-3 p-sm-2rem">
      <div class="w-100">
        <label class="col-from-label fs-13 fw-500 mb-0">{{ translate('Banner & Links (Max 3)') }}</label>
        <div class="small text-muted mb-3">{{ translate("Minimum dimensions required: 436px width X 436px height.") }}
        </div>

        <!-- Images & links -->
        <div class="home-banner1-target">
          @php
      $home_banner1_images = get_setting('home_banner1_images', null, $lang);
      $home_banner1_links = get_setting('home_banner1_links', null, $lang);
    @endphp
          @if ($home_banner1_images != null)
          @foreach (json_decode($home_banner1_images, true) as $key => $value)
        <div class="p-3 p-md-4 mb-3 mb-md-2rem remove-parent" style="border: 1px dashed #e4e5eb;">
        <div class="row gutters-5">
          <!-- Image -->
          <div class="col-md-5">
          <div class="form-group mb-md-0">
          <div class="input-group" data-toggle="aizuploader" data-type="image">
          <div class="input-group-prepend">
            <div class="input-group-text bg-soft-secondary font-weight-medium">{{ translate('Browse')}}</div>
          </div>
          <div class="form-control file-amount">{{ translate('Choose File') }}</div>
          <input type="hidden" name="home_banner1_images[]" class="selected-files"
            value="{{ json_decode($home_banner1_images, true)[$key] }}">
          </div>
          <div class="file-preview box sm">
          </div>
          </div>
          </div>
          <!-- link -->
          <div class="col-md">
          <div class="form-group mb-md-0">
          <input type="text" class="form-control" placeholder="http://" name="home_banner1_links[]"
          value="{{ isset(json_decode($home_banner1_links, true)[$key]) ? json_decode($home_banner1_links, true)[$key] : '' }}">
          </div>
          </div>
          <!-- remove parent button -->
          <div class="col-md-auto">
          <div class="form-group mb-md-0">
          <button type="button" class="mt-1 btn btn-icon btn-circle btn-sm btn-soft-danger"
          data-toggle="remove-parent" data-parent=".remove-parent">
          <i class="las la-times"></i>
          </button>
          </div>
          </div>
        </div>
        </div>
        @endforeach
      @endif
        </div>

        <!-- Add button -->
        <div class="">
          <button type="button"
            class="btn btn-block border hov-bg-soft-secondary fs-14 rounded-0 d-flex align-items-center justify-content-center"
            style="background: #fcfcfc;" data-toggle="add-more" data-content='
											<div class="p-3 p-md-4 mb-3 mb-md-2rem remove-parent" style="border: 1px dashed #e4e5eb;">
												<div class="row gutters-5">
													<!-- Image -->
													<div class="col-md-5">
														<div class="form-group mb-md-0">
															<div class="input-group" data-toggle="aizuploader" data-type="image">
																<div class="input-group-prepend">
																	<div class="input-group-text bg-soft-secondary font-weight-medium">{{ translate('Browse')}}</div>
																</div>
																<div class="form-control file-amount">{{ translate('Choose File') }}</div>
																<input type="hidden" name="home_banner1_images[]" class="selected-files" value="">
															</div>
															<div class="file-preview box sm">
															</div>
														</div>
													</div>
													<!-- link -->
													<div class="col-md">
														<div class="form-group mb-md-0 mb-0">
															<input type="text" class="form-control" placeholder="http://" name="home_banner1_links[]" value="">
														</div>
													</div>
													<!-- remove parent button -->
													<div class="col-md-auto">
														<div class="form-group mb-md-0">
															<button type="button" class="mt-1 btn btn-icon btn-circle btn-sm btn-soft-danger" data-toggle="remove-parent" data-parent=".remove-parent">
																<i class="las la-times"></i>
															</button>
														</div>
													</div>
												</div>
											</div>' data-target=".home-banner1-target">
            <i class="las la-2x text-success la-plus-circle"></i>
            <span class="ml-2">{{ translate('Add New') }}</span>
          </button>
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

<!-- Preorder Banner 1 -->
<div class="tab-pane fade" id="preorder_banner_1" role="tabpanel" aria-labelledby="preorder-banner-2-tab">
  <form action="{{ route('business_settings.update') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <input type="hidden" name="tab" value="preorder_banner_1">
    <input type="hidden" name="types[][{{ $lang }}]" value="home_preorder_banner_1_images">
    <input type="hidden" name="types[][{{ $lang }}]" value="home_preorder_banner_1_links">

    <div class="bg-white p-3 p-sm-2rem">
      <div class="w-100">
        <label class="col-from-label fs-13 fw-500 mb-0">{{ translate('Banner & Links (Max 3)') }}</label>
        <div class="small text-muted mb-3">
          {{ translate("Minimum dimensions required: 1370px width X 360px height (If use a single banner).") }}</div>

        <!-- Images & links -->
        <div class="home-preorder_banner_1-target">
          @php
      $home_preorder_banner_1_images = get_setting('home_preorder_banner_1_images', null, $lang);
      $home_preorder_banner_1_links = get_setting('home_preorder_banner_1_links', null, $lang);
    @endphp
          @if ($home_preorder_banner_1_images != null)
          @foreach (json_decode($home_preorder_banner_1_images, true) as $key => $value)
        <div class="p-3 p-md-4 mb-3 mb-md-2rem remove-parent" style="border: 1px dashed #e4e5eb;">
        <div class="row gutters-5">
          <!-- Image -->
          <div class="col-md-5">
          <div class="form-group mb-md-0">
          <div class="input-group" data-toggle="aizuploader" data-type="image">
          <div class="input-group-prepend">
            <div class="input-group-text bg-soft-secondary font-weight-medium">{{ translate('Browse')}}</div>
          </div>
          <div class="form-control file-amount">{{ translate('Choose File') }}</div>
          <input type="hidden" name="home_preorder_banner_1_images[]" class="selected-files"
            value="{{ json_decode($home_preorder_banner_1_images, true)[$key] }}">
          </div>
          <div class="file-preview box sm">
          </div>
          </div>
          </div>
          <!-- link -->
          <div class="col-md">
          <div class="form-group mb-md-0">
          <input type="text" class="form-control" placeholder="http://" name="home_preorder_banner_1_links[]"
          value="{{ isset(json_decode($home_preorder_banner_1_links, true)[$key]) ? json_decode($home_preorder_banner_1_links, true)[$key] : '' }}">
          </div>
          </div>
          <!-- remove parent button -->
          <div class="col-md-auto">
          <div class="form-group mb-md-0">
          <button type="button" class="mt-1 btn btn-icon btn-circle btn-sm btn-soft-danger"
          data-toggle="remove-parent" data-parent=".remove-parent">
          <i class="las la-times"></i>
          </button>
          </div>
          </div>
        </div>
        </div>
        @endforeach
      @endif
        </div>

        <!-- Add button -->
        <div class="">
          <button type="button"
            class="btn btn-block border hov-bg-soft-secondary fs-14 rounded-0 d-flex align-items-center justify-content-center"
            style="background: #fcfcfc;" data-toggle="add-more" data-content='
											<div class="p-3 p-md-4 mb-3 mb-md-2rem remove-parent" style="border: 1px dashed #e4e5eb;">
												<div class="row gutters-5">
													<!-- Image -->
													<div class="col-md-5">
														<div class="form-group mb-md-0">
															<div class="input-group" data-toggle="aizuploader" data-type="image">
																<div class="input-group-prepend">
																	<div class="input-group-text bg-soft-secondary font-weight-medium">{{ translate('Browse')}}</div>
																</div>
																<div class="form-control file-amount">{{ translate('Choose File') }}</div>
																<input type="hidden" name="home_preorder_banner_1_images[]" class="selected-files" value="">
															</div>
															<div class="file-preview box sm">
															</div>
														</div>
													</div>
													<!-- link -->
													<div class="col-md">
														<div class="form-group mb-md-0 mb-0">
															<input type="text" class="form-control" placeholder="http://" name="home_preorder_banner_1_links[]" value="">
														</div>
													</div>
													<!-- remove parent button -->
													<div class="col-md-auto">
														<div class="form-group mb-md-0">
															<button type="button" class="mt-1 btn btn-icon btn-circle btn-sm btn-soft-danger" data-toggle="remove-parent" data-parent=".remove-parent">
																<i class="las la-times"></i>
															</button>
														</div>
													</div>
												</div>
											</div>' data-target=".home-preorder_banner_1-target">
            <i class="las la-2x text-success la-plus-circle"></i>
            <span class="ml-2">{{ translate('Add New') }}</span>
          </button>
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

<!-- Banner Level 2 -->
<div class="tab-pane fade" id="banner_2" role="tabpanel" aria-labelledby="banner-2-tab">
  <form action="{{ route('business_settings.update') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <input type="hidden" name="tab" value="banner_2">
    <input type="hidden" name="types[][{{ $lang }}]" value="home_banner2_images">
    <input type="hidden" name="types[][{{ $lang }}]" value="home_banner2_links">

    <div class="bg-white p-3 p-sm-2rem">
      <div class="w-100">
        <label class="col-from-label fs-13 fw-500 mb-0">{{ translate('Banner & Links (Max 3)') }}</label>
        <div class="small text-muted mb-3">
          {{ translate("Minimum dimensions required: 1370px width X 420px height (If use a single banner).") }}</div>

        <!-- Images & links -->
        <div class="home-banner2-target">
          @php
      $home_banner2_images = get_setting('home_banner2_images', null, $lang);
      $home_banner2_links = get_setting('home_banner2_links', null, $lang);
    @endphp
          @if ($home_banner2_images != null)
          @foreach (json_decode($home_banner2_images, true) as $key => $value)
        <div class="p-3 p-md-4 mb-3 mb-md-2rem remove-parent" style="border: 1px dashed #e4e5eb;">
        <div class="row gutters-5">
          <!-- Image -->
          <div class="col-md-5">
          <div class="form-group mb-md-0">
          <div class="input-group" data-toggle="aizuploader" data-type="image">
          <div class="input-group-prepend">
            <div class="input-group-text bg-soft-secondary font-weight-medium">{{ translate('Browse')}}</div>
          </div>
          <div class="form-control file-amount">{{ translate('Choose File') }}</div>
          <input type="hidden" name="home_banner2_images[]" class="selected-files"
            value="{{ json_decode($home_banner2_images, true)[$key] }}">
          </div>
          <div class="file-preview box sm">
          </div>
          </div>
          </div>
          <!-- link -->
          <div class="col-md">
          <div class="form-group mb-md-0">
          <input type="text" class="form-control" placeholder="http://" name="home_banner2_links[]"
          value="{{ isset(json_decode($home_banner2_links, true)[$key]) ? json_decode($home_banner2_links, true)[$key] : '' }}">
          </div>
          </div>
          <!-- remove parent button -->
          <div class="col-md-auto">
          <div class="form-group mb-md-0">
          <button type="button" class="mt-1 btn btn-icon btn-circle btn-sm btn-soft-danger"
          data-toggle="remove-parent" data-parent=".remove-parent">
          <i class="las la-times"></i>
          </button>
          </div>
          </div>
        </div>
        </div>
        @endforeach
      @endif
        </div>

        <!-- Add button -->
        <div class="">
          <button type="button"
            class="btn btn-block border hov-bg-soft-secondary fs-14 rounded-0 d-flex align-items-center justify-content-center"
            style="background: #fcfcfc;" data-toggle="add-more" data-content='
											<div class="p-3 p-md-4 mb-3 mb-md-2rem remove-parent" style="border: 1px dashed #e4e5eb;">
												<div class="row gutters-5">
													<!-- Image -->
													<div class="col-md-5">
														<div class="form-group mb-md-0">
															<div class="input-group" data-toggle="aizuploader" data-type="image">
																<div class="input-group-prepend">
																	<div class="input-group-text bg-soft-secondary font-weight-medium">{{ translate('Browse')}}</div>
																</div>
																<div class="form-control file-amount">{{ translate('Choose File') }}</div>
																<input type="hidden" name="home_banner2_images[]" class="selected-files" value="">
															</div>
															<div class="file-preview box sm">
															</div>
														</div>
													</div>
													<!-- link -->
													<div class="col-md">
														<div class="form-group mb-md-0 mb-0">
															<input type="text" class="form-control" placeholder="http://" name="home_banner2_links[]" value="">
														</div>
													</div>
													<!-- remove parent button -->
													<div class="col-md-auto">
														<div class="form-group mb-md-0">
															<button type="button" class="mt-1 btn btn-icon btn-circle btn-sm btn-soft-danger" data-toggle="remove-parent" data-parent=".remove-parent">
																<i class="las la-times"></i>
															</button>
														</div>
													</div>
												</div>
											</div>' data-target=".home-banner2-target">
            <i class="las la-2x text-success la-plus-circle"></i>
            <span class="ml-2">{{ translate('Add New') }}</span>
          </button>
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

<!-- Banner Level 3 -->
<div class="tab-pane fade" id="banner_3" role="tabpanel" aria-labelledby="banner-3-tab">
  <form action="{{ route('business_settings.update') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <input type="hidden" name="tab" value="banner_3">
    <input type="hidden" name="types[][{{ $lang }}]" value="home_banner3_images">
    <input type="hidden" name="types[][{{ $lang }}]" value="home_banner3_links">

    <div class="bg-white p-3 p-sm-2rem">
      <div class="w-100">
        <label class="col-from-label fs-13 fw-500 mb-0">{{ translate('Banner & Links (Max 3)') }}</label>
        <div class="small text-muted mb-3">{{ translate("Minimum dimensions required: 436px width X 436px height.") }}
        </div>

        <!-- Images & links -->
        <div class="home-banner3-target">
          @php
      $home_banner3_images = get_setting('home_banner3_images', null, $lang);
      $home_banner3_links = get_setting('home_banner3_links', null, $lang);
    @endphp
          @if ($home_banner3_images != null)
          @foreach (json_decode($home_banner3_images, true) as $key => $value)
        <div class="p-3 p-md-4 mb-3 mb-md-2rem remove-parent" style="border: 1px dashed #e4e5eb;">
        <div class="row gutters-5">
          <!-- Image -->
          <div class="col-md-5">
          <div class="form-group mb-md-0">
          <div class="input-group" data-toggle="aizuploader" data-type="image">
          <div class="input-group-prepend">
            <div class="input-group-text bg-soft-secondary font-weight-medium">{{ translate('Browse')}}</div>
          </div>
          <div class="form-control file-amount">{{ translate('Choose File') }}</div>
          <input type="hidden" name="home_banner3_images[]" class="selected-files"
            value="{{ json_decode($home_banner3_images, true)[$key] }}">
          </div>
          <div class="file-preview box sm">
          </div>
          </div>
          </div>
          <!-- link -->
          <div class="col-md">
          <div class="form-group mb-md-0">
          <input type="text" class="form-control" placeholder="http://" name="home_banner3_links[]"
          value="{{ isset(json_decode($home_banner3_links, true)[$key]) ? json_decode($home_banner3_links, true)[$key] : '' }}">
          </div>
          </div>
          <!-- remove parent button -->
          <div class="col-md-auto">
          <div class="form-group mb-md-0">
          <button type="button" class="mt-1 btn btn-icon btn-circle btn-sm btn-soft-danger"
          data-toggle="remove-parent" data-parent=".remove-parent">
          <i class="las la-times"></i>
          </button>
          </div>
          </div>
        </div>
        </div>
        @endforeach
      @endif
        </div>

        <!-- Add button -->
        <div class="">
          <button type="button"
            class="btn btn-block border hov-bg-soft-secondary fs-14 rounded-0 d-flex align-items-center justify-content-center"
            style="background: #fcfcfc;" data-toggle="add-more" data-content='
											<div class="p-3 p-md-4 mb-3 mb-md-2rem remove-parent" style="border: 1px dashed #e4e5eb;">
												<div class="row gutters-5">
													<!-- Image -->
													<div class="col-md-5">
														<div class="form-group mb-md-0">
															<div class="input-group" data-toggle="aizuploader" data-type="image">
																<div class="input-group-prepend">
																	<div class="input-group-text bg-soft-secondary font-weight-medium">{{ translate('Browse')}}</div>
																</div>
																<div class="form-control file-amount">{{ translate('Choose File') }}</div>
																<input type="hidden" name="home_banner3_images[]" class="selected-files" value="">
															</div>
															<div class="file-preview box sm">
															</div>
														</div>
													</div>
													<!-- link -->
													<div class="col-md">
														<div class="form-group mb-md-0 mb-0">
															<input type="text" class="form-control" placeholder="http://" name="home_banner3_links[]" value="">
														</div>
													</div>
													<!-- remove parent button -->
													<div class="col-md-auto">
														<div class="form-group mb-md-0">
															<button type="button" class="mt-1 btn btn-icon btn-circle btn-sm btn-soft-danger" data-toggle="remove-parent" data-parent=".remove-parent">
																<i class="las la-times"></i>
															</button>
														</div>
													</div>
												</div>
											</div>' data-target=".home-banner3-target">
            <i class="las la-2x text-success la-plus-circle"></i>
            <span class="ml-2">{{ translate('Add New') }}</span>
          </button>
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

@if(addon_is_activated('auction'))
  <!-- Auction Banner -->
  <div class="tab-pane fade" id="auction" role="tabpanel" aria-labelledby="auction-tab">
    <form action="{{ route('business_settings.update') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <input type="hidden" name="tab" value="auction">
    <div class="bg-white p-3 p-sm-2rem">
      <div class="w-100">
      <label class="col-from-label fs-13 fw-500 mb-3">{{ translate('Auction Banner') }}</label>
      <!-- Images -->
      <div class="form-group">
        <div class="input-group" data-toggle="aizuploader" data-type="image">
        <div class="input-group-prepend">
          <div class="input-group-text bg-soft-secondary font-weight-medium">{{ translate('Browse')}}</div>
        </div>
        <div class="form-control file-amount">{{ translate('Choose File') }}</div>
        <input type="hidden" name="types[][{{ $lang }}]" value="auction_banner_image">
        <input type="hidden" name="auction_banner_image" class="selected-files"
          value="{{ get_setting('auction_banner_image', null, $lang) }}">
        </div>
        <div class="file-preview box sm">
        </div>
        <small class="text-muted">{{ translate("Minimum dimensions required: 435px width X 485px height.") }}</small>
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
@endif

@if(get_setting('coupon_system') == 1)
  <!-- Coupon system -->
  <div class="tab-pane fade" id="coupon" role="tabpanel" aria-labelledby="coupon-tab">
    <form action="{{ route('business_settings.update') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <input type="hidden" name="tab" value="coupon">
    <div class="bg-white p-3 p-sm-2rem">
      <div class="w-100">
      <div class="row gutters-16">
        <!-- Background Color -->
        <div class="col-lg-4">
        <div class="form-group">
          <label class="col-from-label fs-13 fw-500">{{ translate('Background color') }}</label>
          <div class="input-group mb-3">
          <input type="hidden" name="types[]" value="cupon_background_color">
          <input type="text" class="form-control aiz-color-input" placeholder="#000000"
            name="cupon_background_color" value="{{ get_setting('cupon_background_color') }}">
          <div class="input-group-append">
            <span class="input-group-text p-0">
            <input class="aiz-color-picker border-0 size-40px" type="color"
              value="{{ get_setting('cupon_background_color') }}">
            </span>
          </div>
          </div>
        </div>
        </div>
        <!-- Title -->
        <div class="col-lg-8">
        <div class="form-group">
          <label class="col-from-label fs-13 fw-500">{{ translate('Title') }}</label>
          <input type="hidden" name="types[][{{ $lang }}]" value="cupon_title">
          <input type="text" class="form-control" placeholder="{{ translate('Title') }}" name="cupon_title"
          value="{{ get_setting('cupon_title', null, $lang) }}">
        </div>
        </div>
        <!-- Subtitle -->
        <div class="col-12">
        <div class="form-group">
          <label class="col-from-label fs-13 fw-500">{{ translate('Subtitle') }}</label>
          <input type="hidden" name="types[][{{ $lang }}]" value="cupon_subtitle">
          <input type="text" class="form-control" placeholder="{{ translate('Subtitle') }}" name="cupon_subtitle"
          value="{{ get_setting('cupon_subtitle', null, $lang) }}">
        </div>
        </div>
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
@endif

<!-- newestPreorder -->
<div class="tab-pane fade" id="newestPreorder" role="tabpanel" aria-labelledby="newestPreorder-tab">
  <form action="{{ route('business_settings.update') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <input type="hidden" name="tab" value="newestPreorder">
    <div class="bg-white p-3 p-sm-2rem">
      <div class="form-group">
        <label class="col-from-label fs-13 fw-500">{{ translate("Banner") }}</label>
        <div class="input-group " data-toggle="aizuploader" data-type="image">
          <div class="input-group-prepend">
            <div class="input-group-text bg-soft-secondary">{{ translate('Browse') }}</div>
          </div>
          <div class="form-control file-amount">{{ translate('Choose File') }}</div>
          <input type="hidden" name="types[][{{ $lang }}]" value="newest_preorder_banner_image">
          <input type="hidden" name="newest_preorder_banner_image"
            value="{{ get_setting('newest_preorder_banner_image', null, $lang) }}" class="selected-files">
        </div>
        <div class="file-preview box"></div>
      </div>
      <!-- Save Button -->
      <div class="mt-4 text-right">
        <button type="submit"
          class="btn btn-success w-230px btn-md rounded-2 fs-14 fw-700 shadow-success">{{ translate('Save') }}</button>
      </div>
    </div>
  </form>
</div>

<!-- Category Wise Products -->
<div class="tab-pane fade" id="home_categories" role="tabpanel" aria-labelledby="home-categories-tab">
  <form action="{{ route('business_settings.update') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <input type="hidden" name="tab" value="home_categories">
    <div class="bg-white p-3 p-sm-2rem">
      <div class="w-100">
        <label class="col-from-label fs-13 fw-500 mb-3">{{ translate('Categories') }}</label>
        <div class="home-categories-target">
          <input type="hidden" name="types[]" value="home_categories">
          @php $home_categories = get_setting('home_categories'); @endphp
          @if ($home_categories != null)
          @php $categories = \App\Models\Category::where('parent_id', 0)->with('childrenCategories')->get(); @endphp
          @foreach (json_decode($home_categories, true) as $key => $value)
        <div class="p-3 p-md-4 mb-3 mb-md-2rem remove-parent" style="border: 1px dashed #e4e5eb;">
        <div class="row gutters-5">
          <div class="col">
          <div class="form-group mb-0">
          <select class="form-control aiz-selectpicker" name="home_categories[]" data-live-search="true"
          data-selected={{ $value }} required>
          @foreach ($categories as $category)
          <option value="{{ $category->id }}">{{ $category->getTranslation('name') }}</option>
          @foreach ($category->childrenCategories as $childCategory)
          @include('categories.child_category', ['child_category' => $childCategory])
          @endforeach
        @endforeach
          </select>
          </div>
          </div>
          <div class="col-auto">
          <button type="button" class="mt-1 btn btn-icon btn-circle btn-sm btn-soft-danger"
          data-toggle="remove-parent" data-parent=".remove-parent">
          <i class="las la-times"></i>
          </button>
          </div>
        </div>
        </div>
        @endforeach
      @endif
        </div>

        <!-- Add button -->
        <div class="">
          <button type="button"
            class="btn btn-block border hov-bg-soft-secondary fs-14 rounded-0 d-flex align-items-center justify-content-center"
            style="background: #fcfcfc;" data-toggle="add-more" data-content='
											<div class="p-4 mb-3 mb-md-2rem remove-parent" style="border: 1px dashed #e4e5eb;">
												<div class="row gutters-5">
													<div class="col">
														<div class="form-group mb-0">
															<select class="form-control aiz-selectpicker" name="home_categories[]" data-live-search="true" required>
																@foreach (\App\Models\Category::where('parent_id', 0)->with('childrenCategories')->get() as $category)
                                                    <option value="{{ $category->id }}">{{ $category->getTranslation('name') }}</option>
                                                    @foreach ($category->childrenCategories as $childCategory)
                                    @include('categories.child_category', ['child_category' => $childCategory])
                                    @endforeach
                                @endforeach
															</select>
														</div>
													</div>
													<div class="col-auto">
														<button type="button" class="mt-1 btn btn-icon btn-circle btn-sm btn-soft-danger" data-toggle="remove-parent" data-parent=".remove-parent">
															<i class="las la-times"></i>
														</button>
													</div>
												</div>
											</div>' data-target=".home-categories-target">
            <i class="las la-2x text-success la-plus-circle"></i>
            <span class="ml-2">{{ translate('Add New') }}</span>
          </button>
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

<!-- Classifieds -->
<div class="tab-pane fade" id="classifieds" role="tabpanel" aria-labelledby="classifieds-tab">
  <form action="{{ route('business_settings.update') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <input type="hidden" name="tab" value="classifieds">
    <div class="bg-white p-3 p-sm-2rem">
      <div class="row">
        <!-- Large Banner -->
        <div class="col-lg-6">
          <div class="form-group">
            <label class="col-from-label fs-13 fw-500">{{ translate("Large Banner") }}
              (<small>{{ translate('Will be shown in large device') }}</small>)</label>
            <div class="input-group " data-toggle="aizuploader" data-type="image">
              <div class="input-group-prepend">
                <div class="input-group-text bg-soft-secondary">{{ translate('Browse') }}</div>
              </div>
              <div class="form-control file-amount">{{ translate('Choose File') }}</div>
              <input type="hidden" name="types[][{{ $lang }}]" value="classified_banner_image">
              <input type="hidden" name="classified_banner_image"
                value="{{ get_setting('classified_banner_image', null, $lang) }}" class="selected-files">
            </div>
            <div class="file-preview box"></div>
          </div>
        </div>
        <!-- Small Banner -->
        <div class="col-lg-6">
          <div class="form-group">
            <label class="col-from-label fs-13 fw-500">{{ translate("Small Banner") }}
              (<small>{{ translate('Will be shown in small device') }}</small>)</label>
            <div class="input-group " data-toggle="aizuploader" data-type="image">
              <div class="input-group-prepend">
                <div class="input-group-text bg-soft-secondary">{{ translate('Browse') }}</div>
              </div>
              <div class="form-control file-amount">{{ translate('Choose File') }}</div>
              <input type="hidden" name="types[][{{ $lang }}]" value="classified_banner_image_small">
              <input type="hidden" name="classified_banner_image_small"
                value="{{ get_setting('classified_banner_image_small', null, $lang) }}" class="selected-files">
            </div>
            <div class="file-preview box"></div>
          </div>
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