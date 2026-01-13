<div style="border-bottom: #E5E7EB solid 1px; margin-top: 10px;">
  <table width="100%" class="container" cellpadding="0" cellspacing="0" border="0">
    <!-- Logo -->
    <tr>
      <td align="center" style="padding: 15px 0;">
        @php
          $logoPath =
            App::getLocale() == 'ar'
            ? uploaded_asset(get_setting('system_ar_logo_white'), false)
            : uploaded_asset(get_setting('system_logo_white'), false);

          $logoSrc = isset($message)
            ? $message->embed(public_path($logoPath))
            : url('/public') . '/' . $logoPath;
        @endphp

        <a href="{{ route('react.home') }}" target="_blank" style="text-decoration:none;">
          <img src="{{ $logoSrc }}" class="logo" alt="logo">
        </a>
      </td>
    </tr>
  </table>
</div>