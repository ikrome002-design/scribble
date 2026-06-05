<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ClientMainSubscription
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if (auth('admin')->check() || auth('client')->check()) {
            if (auth('admin')->check()) {
                return $next($request);
            }
            if (auth('client')->check()) {
                if (auth('client')->user()->plan_status == 'Active') {
                    return $next($request);
                } else {
                    return redirect('user/package/all')->withErrors('You main plan is not active');
                }
            }
        }
    }
}