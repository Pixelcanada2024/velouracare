@extends('emails.partials.registration-subscribing.layout', ['footer_text' => customTrans('email_subscribing_footer_message'),'show_footer'=>false])

@section('content')
  <div class="section">
    <h2 class="f-bold f-14" style=" color: #004AAD;">
      {{ customTransWithReplace(
          'new_quotation_request_new_quotation_request_received',
          ['id' => $order->code, 'name' => $order->user->name]
      ) }}
    </h2>
    <p>{{ customTrans('new_quotation_request_dear_sky_business_team') }}</p>
    <p class="f-14">{{ customTrans('new_quotation_request_automated_notification') }}</p>

  </div>

  <div class="section f-14">
    <h2 class="f-bold f-14">{{ customTrans('new_quotation_request_request_details') }}</h2>
    <ul style="padding-left: 20px;">
      <li >{{ customTrans('new_quotation_request_request_id') }} #{{ $order?->code }}</li>
      <li >{{ customTrans('new_quotation_request_request_client_name') }} {{ $order->user?->name }}</li>
      <li >{{ customTrans('new_quotation_request_request_client_company') }} {{ $order->user?->businessInfo?->company_name }}</li>
      <li >{{ customTrans('new_quotation_request_request_client_email') }} {{ $order->user?->email }}</li>
      <li >{{ customTrans('new_quotation_request_request_client_phone') }} {{ $order->user?->phone }}</li>
      <li >{{ customTrans('new_quotation_request_request_client_date_submitted') }} {{ $order->created_at->format('Y-m-d') }}</li>
    </ul>
  </div>

  <div class="section f-14">
    <h2 class="f-bold  f-14">{{ customTrans('new_quotation_request_uploaded_files') }}</h2>
    <p >{!! customTrans('new_quotation_request_uploaded_files_desc') !!}</p>
  </div>

  <div class="section f-14">
    <h2 class="f-bold f-14">{{ customTrans('new_quotation_request_next_steps') }}</h2>
    <ol style="padding-left: 20px;">
      <li >{{ customTrans('new_quotation_request_next_steps_1') }}</li>
      <li >{{ customTrans('new_quotation_request_next_steps_2') }}</li>
      <li >{{ customTrans('new_quotation_request_next_steps_3') }}</li>
    </ol>
  </div>

  <div class="section f-14">
    <span>{!! customTrans('new_quotation_request_please_ensure') !!}</span>
    <br>
    <span>{!! customTrans('new_quotation_request_thank_you') !!}</span>
    <br>
    <span>{!! customTrans('new_quotation_request_automated_system') !!}</span>
  </div>
@endsection
