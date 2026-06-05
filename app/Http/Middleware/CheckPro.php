<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\ProSubscription;

class CheckPro
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

        if (auth('client')->check()) {
            $checkPro = ProSubscription::where('cl_id', auth('client')->user()->id);
            if ($checkPro->count() == 0) {
                return redirect('/signup-allsubs');
            }
            if ($checkPro->where('shortcode_status', 'complete')->count() == 0) {
                return redirect('/signup-incomplete');
            }
            return $next($request);
        }
    }
}
