<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class Staff
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

        if (!auth('staff')->check()) {
            return redirect('//staff.' . env('APP_DOMAIN'));
        }
        if (auth('staff')->user()->status != 'Active' || auth('staff')->user()->client->status != 'Active') {
            auth('staff')->logout();
            return redirect('//staff.' . env('APP_DOMAIN'))->withErrors('You are not allowed to login. Contact your business owner');
        }
        return $next($request);
    }
}