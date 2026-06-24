<?php

namespace App\Http\Middleware;

use App\Services\SubscriptionService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SubscriptionMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();

        // Bypass check for Superadmin (by email or role) and Admin role
        if ($user->email === 'admin@teman-seakad.com' || $user->hasRole('Superadmin') || $user->hasRole('Admin')) {
            return $next($request);
        }

        // Bypass for logout routes to allow expired users to log out
        if ($request->routeIs('admin.logout') || $request->routeIs('logout')) {
            return $next($request);
        }

        // Check if user has an active subscription
        if (! app(SubscriptionService::class)->checkActive($user)) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Masa aktif akun Anda sudah berakhir, silahkan melakukan perpanjangan',
                ], 403);
            }

            return redirect()->route('subscription.expired');
        }

        return $next($request);
    }
}
