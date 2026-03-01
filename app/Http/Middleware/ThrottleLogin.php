<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Cache\RateLimiter;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ThrottleLogin
{
    protected RateLimiter $limiter;

    public function __construct(RateLimiter $limiter)
    {
        $this->limiter = $limiter;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $key = $this->resolveRequestSignature($request);
        $maxAttempts = 5;
        $decayMinutes = 1;

        if ($this->limiter->tooManyAttempts($key, $maxAttempts)) {
            $seconds = $this->limiter->availableIn($key);
            
            return back()
                ->withInput($request->only('username'))
                ->withErrors([
                    'username' => "Too many login attempts. Please try again in {$seconds} seconds."
                ]);
        }

        $this->limiter->hit($key, $decayMinutes * 60);

        return $next($request);
    }

    /**
     * Resolve request signature for rate limiting
     */
    protected function resolveRequestSignature(Request $request): string
    {
        return sha1($request->ip() . '|' . $request->input('username'));
    }
}
