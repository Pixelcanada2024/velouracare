
<div class="header" style="border-bottom: #E5E7EB solid 1px;padding-bottom:10px; margin-top: 10px;">
    <table width="100%" class="container" cellpadding="0" cellspacing="0" border="0">
      <tr>
        <td >
          <a href="{{ $info['url'] ?? route('react.home') }}">
            @if (isset($message))
              <img class="logo" src="{{ $message->embed(public_path('/assets/img/emails/common/logo.png')) }}"
                alt="logo">
            @else
              <img class="logo" src="{{ asset('public/assets/img/emails/common/logo.png') }}" alt="logo">
            @endif
          </a>
        </td>
        <td align="right" valign="top" >
          <div class="invoice-info">
            <div class="invoice-title" style="font-size:24px;font-weight:bold;color:#282A31;float:right;margin-left:auto;">INVOICE</div>
          </div>
        </td>
      </tr>
      <tr>
        <td colspan="2" >
          <table cellspacing="0" cellpadding="0" border="0">
            <tr class="header-icons">
              <td style="padding-top:10px">
                @if (isset($message))
                  <img src="{{ $message->embed(public_path('/assets/img/emails/common/phone.png')) }}" alt="phone icon" class="img">
                @else
                  <img src="{{ asset('public/assets/img/emails/common/phone.png') }}" alt="phone icon" class="img">
                @endif
              </td>
              <td>
                <span>{{ $info['phone'] }}</span>
              </td>
            </tr>
            <tr class="header-icons">
              <td style="padding-top:10px">
                @if (isset($message))
                  <img src="{{ $message->embed(public_path('/assets/img/emails/common/location.png')) }}" alt="location icon" class="img">
                @else
                  <img src="{{ asset('public/assets/img/emails/common/location.png') }}" alt="location icon" class="img">
                @endif
              </td>
              <td>
                <span title="{{ $info['address'] }}" style="max-width: 200px; overflow: hidden; text-overflow: ellipsis;">{{ $info['address'] }}</span>
              </td>
            </tr>
            <tr class="header-icons">
              <td style="padding-top:10px">
                @if (isset($message))
                  <img src="{{ $message->embed(public_path('/assets/img/emails/common/email.png')) }}" alt="email icon" class="img">
                @else
                  <img src="{{ asset('public/assets/img/emails/common/email.png') }}" alt="email icon" class="img">
                @endif
              </td>
              <td>
                <a href="mailto:{{ $info['email'] }}" target="_blank" id="email-link">
                  <span>{{ $info['email'] }}</span>
                </a>
              </td>
            </tr>
          </table>
        </td>
      </tr>
    </table>
</div>
