<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\TeamSubscription;

class ClientTeamSubscription
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

        $cl_id = auth('client')->user()->id ?? auth('team')->user()->cl_id  ?? auth('staff')->user()->cl_id;
        $teamSub = TeamSubscription::where('cl_id', $cl_id)->first();
        if (!$teamSub) {
            return redirect('/team/subscription')->withErrors('You must have an active Team link subscription before you procced to staff section.');
        }
        if (auth('client')->check() ?? auth('team')->user()->team_role == 'Manager') {
            if ($teamSub->sub_status == 'Inactive') {
                return redirect("//" . env('APP_DOMAIN') . "/team/subscription")->withErrors('Your Team link subscription Inactive');
            }
        }

        if ($teamSub->sub_status == 'Inactive') {
            return redirect("//staff" . env('APP_DOMAIN') . "/team/subscription")->withErrors('Team subscription has expired. Contact your business owner');
        }
        return $next($request);
    }
}
