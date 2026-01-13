<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class RedirectToCountrySubdomain
{
  /**
   * Handle an incoming request.
   *
   * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
   */
  public function handle(Request $request, Closure $next): Response
  {
    $redirectedQueryString = $request->query('redirected', 0);
    $sessionRedirected = Session::get('redirected', 0);
    $env = config('app.env' , 'local');
    $skyType = config('app.sky_type', 'Gulf');
    $redirectTo = null;

    if ($redirectedQueryString == 0 && $sessionRedirected == 0 && $env === 'production' && $skyType === 'America') {

      $domains = config('app.domains');
      $allowedCountries = config('app.countries');

      try {
        $ip = $request->ip();
        $response = Http::get("http://ip-api.com/json/{$ip}?fields=status,countryCode");

        if ($response->successful() && $response['status'] === 'success') {
          $countryCode = strtolower($response['countryCode']);
          // Validate allowed countries
          if (in_array($countryCode, $allowedCountries) && $countryCode != 'us') {
            $redirectTo = $domains[$countryCode]  ?? null;
          }
          //  else if ( $countryCode !== 'ca' ) {
          //   $redirectTo = $domains['sa'];
          // }
        }
      } catch (\Exception $e) {
        Log::error('Geolocation API error: ' . $e->getMessage());
      }
    }

    if ($sessionRedirected == 0) {
      Session::put('redirected', 1);
    }

    if ($redirectTo != null) {
      $path = route('react.home', [], false);
      return redirect()->away(  "https://{$redirectTo}{$path}" );
    }

    return $next($request);
  }
}
