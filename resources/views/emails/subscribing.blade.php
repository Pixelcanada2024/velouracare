@extends('emails.partials.registration-subscribing.layout', ['footer_text' => customTrans('email_subscribing_footer_message')])

@section('content')
  <h2 class="welcome-message-title" style="padding:0px 20px">{{ customTrans('email_subscribing_welcome_message') }}</h2>

  @if (isset($message))
    <img class="image" src="{{ $message->embed(public_path('/assets/img/emails/subscribing/one.png')) }}" alt="Intro image">
  @else
    <img class="image" src="{{ asset('public/assets/img/emails/subscribing/one.png') }}" alt="Intro image">
  @endif


  <div class="section">
    <h3 class="f-14 f-bold">{{ customTrans('dear_customer') }}</h3>
    <p class="f-14">{{ customTrans('email_subscribing_dear_customer_message') }}</p>
  </div>

  <div class="section">
    <h3 class="f-14 f-bold">{{ customTrans('email_subscribing_by_subscribing') }}</h3>
    <ul style="padding-left: 20px;">
      <li class="f-14">{{ customTrans('email_subscribing_by_subscribing_feature_one') }}</li>
      <li class="f-14">{{ customTrans('email_subscribing_by_subscribing_feature_two') }}</li>
      <li class="f-14">{{ customTrans('email_subscribing_by_subscribing_feature_three') }}</li>
    </ul>
    <p style="color:#666666 " class="f-14">{!! customTransWithReplace('email_subscribing_our_commit',['link' => request()->getHost()]) !!}</p>
  </div>
  <div style="padding:0 20px">
    @include('emails.partials.registration-subscribing.button', [
        'text' => customTrans('email_subscribing_order_now'),
        "textMobile" => customTrans('email_subscribing_order_now_mobile'),
        'link' => route('react.products'),
    ])
  </div>

  <div class="section f-14">
    <p>{!! customTrans('email_subscribing_any_ask') !!}</p>
  </div>

  @if (isset($subscriber))
    <div class="section f-12" style="background: #F8F8F8; padding:1pxx">
      <p  style="text-align: center;">
        {{customTrans('email_subscribing_unsubscribe_message')}} <a href="{{ route('react.newsletter.unsubscribe', ['id' => $subscriber->id, 'email' => $subscriber->email]) }}" style="color: #0073e6; text-decoration: underline;">{{customTrans('email_subscribing_unsubscribe_button')}}</a>
      </p>
    </div>
  @endif
@endsection
