<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeaders
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);
        
        // Content-Security-Policy - Adjust based on your specific external resources
        $response->headers->set(
            'Content-Security-Policy', 
            "default-src 'self'; " .
            "script-src 'self' 'unsafe-inline' https://cdn.jsdelivr.net https://ajax.googleapis.com; " .
            "style-src 'self' 'unsafe-inline' https://fonts.googleapis.com https://cdn.jsdelivr.net; " .
            "font-src 'self' https://fonts.gstatic.com; " .
            "img-src 'self' data: https://*.fbcdn.net https://*.googleusercontent.com; " .
            "connect-src 'self' https://graph.facebook.com https://accounts.google.com https://api.linkedin.com; " .
            "frame-src 'self' https://www.facebook.com https://accounts.google.com https://www.linkedin.com;"
        );
        
        // Prevent MIME type sniffing
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        
        // Prevent clickjacking
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');
        
        // Referrer policy to limit information sent to other websites
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
        
        // Permissions policy - restrict browser features
        $response->headers->set('Permissions-Policy', 'camera=(), microphone=(), geolocation=(), interest-cohort=()');
        
        // Enable strict HTTPS on production
        if (app()->environment('production')) {
            $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains');
        }
        
        // XSS Protection header
        $response->headers->set('X-XSS-Protection', '1; mode=block');
        
        return $response;
    }
}
