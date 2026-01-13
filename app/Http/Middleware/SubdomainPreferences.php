<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class SubdomainPreferences
{
  /**
   * Handle an incoming request.
   *
   * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
   */
  public function handle(Request $request, Closure $next): Response
  { 
    if ( config('app.env') == 'production' ) {
      $host = $request->getHost();
      $subdomain = strtolower(explode('.', $host)[0]);

      $allowedCountries = config('app.countries');

      $currentCountry = Session::get('country', 'sa');

      $currencies = config('app.currencies');

      if (
        in_array($subdomain, $allowedCountries)
        && $currentCountry != $subdomain
      ) {
        Session::put('country', $subdomain);
        Session::put('currency', $currencies[$subdomain]);
      }
    }

    return $next($request);
  }
}
