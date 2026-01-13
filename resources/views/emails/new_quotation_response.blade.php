@extends('emails.partials.registration-subscribing.layout', ['footer_text' => customTrans('email_subscribing_footer_message'), 'show_footer' => false])

@section('content')
  <div class="section f-14">
    <h2 class="f-bold f-14" style=" color: #004AAD;">
      {{ customTransWithReplace('new_quotation_response_new_quotation_response_received', ['id' => $order->code]) }}
    </h2>
    <p>
      {{ customTransWithReplace('new_quotation_response_dear_customer', ['customer_name' => $order->user->name]) }}
    </p>

    <p>{{ customTrans('new_quotation_response_dear_customer_thank_you') }}</p>

    <p>
      {{ customTransWithReplace('new_quotation_response_your_order_number', ['id' => $order->code]) }}
    </p>

    <p>{{ customTrans('new_quotation_response_our_team_1') }}</p>

    <p>{{ customTrans('new_quotation_response_our_team_2') }}</p>


    <p>{{ customTrans('new_quotation_response_our_question') }}</p>

    @php
      $footer_qr_code_path = uploaded_asset(get_setting('footer_qr_code') , false);
      $footer_qr_code_src = isset($message)
        ? $message->embed(public_path($footer_qr_code_path))
        : url('/public') . '/' . $footer_qr_code_path;
    @endphp

    <a href="{{ get_setting('whatsapp_link') }}" target="_blank" rel="noopener noreferrer">
      <img style="width:120px"
        src="{{ $footer_qr_code_src }}"
        alt="QR Code">
    </a>

    <p class="f-14"><span style="color:#8E0606; " class="f-bold"> {{ customTrans('new_quotation_response_our_note') }} </span> {{ customTrans('new_quotation_response_our_note_desc') }} </p>

    <p>{!! customTrans('new_quotation_response_thank_you') !!}</p>
  </div>

  <div class="section f-14">
    <span>{!! customTrans('new_quotation_response_best') !!}</span>
    <br>
    <span>{!! customTrans('new_quotation_response_team') !!}</span>
  </div>
@endsection
