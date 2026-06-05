<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RedirectIfNotClient
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = 'client')
    {
        if (!Auth::guard($guard)->check()) {
            return redirect(env('APP_URL') . '/login')->with([
                'message' => 'Login before you continue',
                'message_important' => true
            ]);
        } elseif (Auth::guard($guard)->user()->status != 'Active') {
            return redirect(env('APP_URL') . '/login')->withErrors('You are not active. Please contact support.');
        }
        return $next($request);
    }
}