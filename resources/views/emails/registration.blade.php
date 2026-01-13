@extends('emails.partials.registration-subscribing.layout', ['footer_text' =>  customTrans('email_registration_footer_message') ])

@section('content')
  <div class="register-status register-status-new">{{ customTrans('email_registration_join_request') }}</div>

  <div class="section">
    <h3 class="f-14 f-bold">{{ customTrans('dear_customer') }}</h3>
    <p class="f-14">{{ customTrans('email_registration_line_1') }}</p>
    <p class="f-14">{{ customTrans('email_registration_line_2') }}</p>
    <p class="f-14">{{ customTrans('email_registration_line_3') }}</p>
    <p>{!! customTrans('email_registration_line_4') !!}</p>

  </div>

@endsection
