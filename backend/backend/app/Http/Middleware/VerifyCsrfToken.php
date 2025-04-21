<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerifyCsrfToken
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        'api/cart/add',
        'api/cart/update',
        'api/cart/remove',
        'api/cart', // if you have a GET endpoint for cart items
    ];

    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        foreach ($this->except as $uri) {
            if ($request->is($uri)) {
                return $next($request); // Skip CSRF for these URIs
            }
        }

        // You can implement full CSRF validation here if needed, but
        // for now, we'll just allow the request through
        return $next($request);
    }
}
