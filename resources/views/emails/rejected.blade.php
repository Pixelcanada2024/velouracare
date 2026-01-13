@extends('emails.partials.registration-subscribing.layout', ['footer_text' => customTrans('email_rejected_footer')])

@section('content')
  <div class="register-status register-status-rejected">{{ customTrans('email_rejected_title') }}
  </div>

  <div class="section">
    <h3>{{ customTrans('dear_customer') }}</h3>
    <p>{{ customTrans('email_rejected_line_1') }}</p>
    <p>{{ customTrans('email_rejected_line_2') }}</p>
    <p>{!! customTrans('email_rejected_line_3') !!}</p>
  </div>


  <div class="section">
    <p>{!! customTrans('email_rejected_line_4') !!}</p>
    <p>{{ customTrans('email_rejected_line_5') }}</p>
  </div>
@endsection
