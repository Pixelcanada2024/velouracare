<div class="container">
  <div class="follow-section ">
    <h3 class="f-12">{{ customTrans('footer_stay_connected_with_us_on_social_media') }}</h3>
    <table class="follow-icons">
      <tr>
        <td>
          <a class="follow-icon" href="{{ $info['facebook'] }}" target="_blank">
            @if (isset($message))
              <img src="{{ $message->embed(public_path('/assets/img/emails/common/facebook.png')) }}" alt="facebook">
            @else
              <img src="{{ asset('public/assets/img/emails/common/facebook.png') }}" alt="facebook">
            @endif
          </a>
          <a class="follow-icon" href="{{ $info['instagram'] }}" target="_blank">
            @if (isset($message))
              <img src="{{ $message->embed(public_path('/assets/img/emails/common/instagram.png')) }}" alt="instagram">
            @else
              <img src="{{ asset('public/assets/img/emails/common/instagram.png') }}" alt="instagram">
            @endif
          </a>
          <a class="follow-icon" href="{{ $info['twitter'] }}" target="_blank">
            @if (isset($message))
              <img src="{{ $message->embed(public_path('/assets/img/emails/common/x.png')) }}" alt="x">
            @else
              <img src="{{ asset('public/assets/img/emails/common/x.png') }}" alt="x">
            @endif
          </a>
          <a class="follow-icon" href="{{ $info['linkedin'] }}" target="_blank">
            @if (isset($message))
              <img src="{{ $message->embed(public_path('/assets/img/emails/common/linkedin.png')) }}" alt="linkedin">
            @else
              <img src="{{ asset('public/assets/img/emails/common/linkedin.png') }}" alt="linkedin">
            @endif
          </a>
        </td>
      </tr>
    </table>
  </div>

</div>
