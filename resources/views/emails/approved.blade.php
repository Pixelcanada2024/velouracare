@extends('emails.partials.registration-subscribing.layout', ['footer_text' =>  customTrans("email_approved_footer") ])

@section('content')
  <div class="register-status register-status-approved">{{ customTrans('email_approved_title') }}
  </div>

  <div class="section">
    <h3 class="f-14 f-bold">{{ customTrans('dear_customer') }}</h3>
    <p class="f-14">{{ customTrans('email_approved_line_1') }}</p>
    <p class="f-14">{{ customTrans('email_approved_line_2') }}</p>
  </div>

  <div class="section">
    <h3 class="f-bol  f-14">{{ customTrans(key: 'email_approved_how_to_get_started') }}</h3>
    <ol style="padding-left: 20px;">
      <li class="f-14">{{ customTrans("email_approved_how_to_get_started_line_1") }}</li>
      <li class="f-14">{{ customTrans("email_approved_how_to_get_started_line_2") }}</li>
      <li class="f-14">{{ customTrans("email_approved_how_to_get_started_line_3") }}</li>
      <li class="f-14">{{ customTrans("email_approved_how_to_get_started_line_4") }}</li>
    </ol>
  </div>
  <div style="text-align: center;">
    @include('emails.partials.registration-subscribing.button', [
        'text' => customTrans('email_approved_login'),
        'link' => route('react.login'),
    ])
  </div>

  <div class="section">
    <p class="f-14">{!! customTrans("email_approved_commit") !!}</p>
  </div>
@endsection
