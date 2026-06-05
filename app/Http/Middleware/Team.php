<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;


class Team
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

        if (!auth('team')->check()) {
            return redirect('//staff.' . env('APP_DOMAIN'));
        }

        if (auth('team')->user()->status != 'Active' || auth('team')->user()->is_team != 'Yes' || auth('team')->user()->client->status != 'Active') {
            auth('team')->logout();
            return redirect('//staff.' . env('APP_DOMAIN'))->withErrors('You are not allowed to login. Contact your business owner');
        }
        return $next($request);
    }
}