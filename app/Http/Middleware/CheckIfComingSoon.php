<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckIfComingSoon
{
  /**
   * Handle an incoming request.
   *
   * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
   */

  public function handle(Request $request, Closure $next)
  {
    if (
      $request->is('/') && $request->isMethod('GET') && !config('app.is_america') && false
    ) {
      return inertia( 'comingSoon/ComingSoon' );
      // return response()->view('coming-soon');
    }

    return $next($request);
  }
}
