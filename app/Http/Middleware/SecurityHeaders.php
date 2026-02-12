<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SecurityHeaders
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        // Basic security headers
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
        $response->headers->set('X-XSS-Protection', '0');

        // HSTS only when request is secure
        if ($request->isSecure()) {
            $response->headers->set('Strict-Transport-Security', 'max-age=63072000; includeSubDomains; preload');
        }

        // Content Security Policy: be strict in production, relaxed in local/dev so Vite/devtools work
        if (app()->environment('production')) {
            $csp = "default-src 'self'; script-src 'self'; style-src 'self'; img-src 'self' data:; font-src 'self' https://fonts.gstatic.com;";
        } else {
            // Development: be permissive so local dev servers, HMR and CDNs are not blocked
            $csp = "default-src * 'unsafe-inline' 'unsafe-eval' data: blob:; ";
            $csp .= "connect-src * ws: wss:; ";
            $csp .= "script-src * 'unsafe-inline' 'unsafe-eval' data: blob:; ";
            $csp .= "style-src * 'unsafe-inline' data:; ";
            $csp .= "img-src * data: blob:; ";
            $csp .= "font-src * data:;";
        }
        $response->headers->set('Content-Security-Policy', $csp);

        return $response;
    }
}
