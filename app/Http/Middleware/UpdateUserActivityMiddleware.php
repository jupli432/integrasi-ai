<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class UpdateUserActivityMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {


        Log::info("called middleware");
        $guards = [
            'web',
            'company',
            'admin'
        ];

        foreach ($guards as $guard) {
            try {
                if (Auth::guard($guard)->check()) {

                    /**
                     * 
                     * @var \App\User | \App\Company | \App\Admin | null
                     */
                    $user = Auth::guard($guard)->user();
                    $cacheKey = "{$guard}-user-online-{$user->id}";
                    $lastActiveKey = "{$guard}-last-active-{$user->id}";
                    // Update last_active timestamp in database
                    // Store online status in cache (valid for 5 minutes)
                    $user->update(['last_active' => now()]);
                    Cache::put($cacheKey, true, now()->addMinutes(5));
                    // Store online status in cache
                    // Update last_active in DB only if it hasn't been updated in the last 5 minutes
                    if (!Cache::has($lastActiveKey)) {
                        $user->update(['last_active' => now()]);
                        Cache::put($cacheKey, true, now()->addMinutes(5));
                        Cache::put($lastActiveKey, true, now()->addMinutes(5));
                    }
                }
            } catch (\Throwable $th) { // Log only critical issues, avoid flooding logs with minor warnings
                Log::error("Failed to update user activity for guard {$guard}: " . $th->getMessage());
            }
        }


        return $next($request);
    }
}
