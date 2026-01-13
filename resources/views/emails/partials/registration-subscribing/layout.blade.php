<!DOCTYPE html>
<html lang="{{ App::getLocale() }}" dir="{{ App::getLocale() == 'ar' ? 'rtl' : 'ltr' }}">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sky Business Trade</title>
  @include('emails.partials.registration-subscribing.style')

  @if (App::getLocale() === 'ar')
    <!-- Arabic: Tajawal -->
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
      * {
        font-family: 'Tajawal', sans-serif !important;
        direction: rtl;
        text-align: right;
      }
    </style>
  @else
    <!-- English: Inter -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
      * {
        font-family: 'Inter', sans-serif !important;
        direction: ltr;
        text-align: left;
      }
    </style>
  @endif
</head>


<body>

  @include('emails.partials.registration-subscribing.header')

  <div class="container">
    <table width="100%" cellpadding="0" cellspacing="0">

      <!-- Content -->
      <tr>
        <td class="content-wrapper">
          @yield('content')

          {{-- @include('emails.partials.registration-subscribing.need-help') --}}

          {{-- @include('emails.partials.registration-subscribing.apps') --}}


        </td>
      </tr>

    </table>
  </div>

  @if ($show_footer ?? true)
    <div style="border-top: #E5E7EB solid 1px;margin:20px 0 20px 0">
      @include('emails.partials.registration-subscribing.follow')

      @include('emails.partials.registration-subscribing.footer', [
          'footer_text' => $footer_text ?? 'We look forward to a successful and prosperous partnership!',
      ])
  @endif

  </div>
</body>

</html>
