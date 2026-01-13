<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureInertiaPageResponse
{
  public function handle(Request $request, Closure $next)
  {
    $response = $next($request);

    // Skip check if it's a real Inertia request (AJAX)
    if (
      $request->ajax() 
      ||
      $request->header('X-Inertia') 
    ) {
      return $response;
    }

    $contentType = $response->headers->get('Content-Type', '');
    $content = $response->getContent();

    // Detect unintended Inertia JSON payload
    $isInertiaJson =
      str_contains($contentType, 'application/json') &&
      str_contains($content, '{"component":') &&
      str_contains($content, ',"props":') &&
      !str_contains($content, 'id="app"');

    if ($isInertiaJson) {
      \Log::warning('Unexpected Inertia JSON response detected.', [
        'url' => $request->fullUrl(),
        'content_type' => $contentType,
      ]);

      // Redirect to force full-page Inertia render
      return inertia('Errors/Fallback');
      // return redirect()->to($request->fullUrl());
    }

    return $response;
  }
}
