@extends('emails.partials.registration-subscribing.layout', ['footer_text' => customTrans('notify_back_in_stock_footer_message')])

@section('content')
  <h2 class="welcome-message-title" style="padding:0px 20px">
    {{ customTransWithReplace('notify_back_in_stock_product_name_back_in_stock_your_wait_is_over', [
        'product_name' => $product['name'],
    ]) }}
  </h2>
  @php
          $product_img_path = $product['image_relative_path'];
          $product_img_src = isset($message)
            ? $message->embed(public_path($product_img_path))
            : url('/public') . '/' . $product_img_path;
  @endphp


  <div class="section" style="text-align: {{ app()->getLocale() === 'ar' ? 'right' : 'left' }};">
    <a href="{{ $product_img_src }}" target="_blank">
      <img class="notify_back_in_stock_image" src="{{ $product_img_src }}" alt="{{ $product['name'] }}"
        style="width:fit-content; max-width:80%;  height: 220px; max-height:230px; object-fit: contain; cursor: pointer;  {{ app()->getLocale() === 'ar' ? 'margin-left:auto;' : 'margin-right:auto;' }}">
    </a>
  </div>

  <div class="section">
    <h3 class="f-14 f-bold m-0">
      {{ customTransWithReplace(
          'notify_back_in_stock_dear',
          ['user_name' => $customer_name]
      ) }}
    </h3>
    <p class="f-14">
      {{ customTransWithReplace('notify_back_in_stock_product_first_section', ['product_name' => $product['name']]) }}
    </p>
    <p class="f-14">
      {{ customTransWithReplace('notify_back_in_stock_product_second_section', ['product_name' => $product['name']]) }}
    </p>
    <p class="f-14">
      {{ customTransWithReplace('notify_back_in_stock_product_third_section', ['product_name' => $product['name']]) }}
    </p>

  </div>
  <div style="padding:0 20px">
    <a href="{{ route('react.product', $product['id']) }}"
      style="background:#202228; color:white; text-decoration:none; padding:8px 32px; border-radius:12px; display:inline-block; font-size:14px; font-weight:600; max-width:300px; direction:{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}; text-align:center;">
      <span style="vertical-align:middle;">{{ customTrans('notify_back_in_stock_product_button') }}</span>
      @php
      $path = "/assets/img/emails/common/" . (app()->getLocale() !== 'ar' ? 'right' : 'left') .  "-outline-arrow-icon.png";
        $arrowSrc = isset($message)
            ? $message->embed(public_path($path))
            : asset('public' . $path);
      @endphp
      <img src="{{ $arrowSrc }}" alt="Arrow"
        style="vertical-align:middle; width:16px; height:16px; margin-left:6px;">
    </a>

  </div>
  <div class="section">
    <p  class="f-14">
      {{ customTrans('notify_back_in_stock_thanks') }}
    </p>
    <p class="f-14">
      {!! customTrans('notify_back_in_stock_contact_for_help') !!}
    </p>
  </div>
@endsection
