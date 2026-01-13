<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    {{-- <link rel="icon" href="{{ asset('/public/assets/img/website-pages/logo.png') }}"> --}}

    @routes
    @viteReactRefresh 
      @vite("resources/js/react.jsx")
    @inertiaHead
  </head>
  <body style="">
    @inertia
  </body>
</html>
