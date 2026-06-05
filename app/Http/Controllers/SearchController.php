<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ProSubscription;
use App\Client;


class SearchController extends Controller
{
    public function client(Request $request)
    {
        if (auth('admin')->check()) {
            $clients = [];
            $search = $request->q;
            $clients = Client::where('fname', 'LIKE', "%$search%")
                ->where('lname', 'LIKE', "%$search%")
                ->where('email', 'LIKE', "%$search%")
                ->limit(15)->get();
            return response()->json($clients);
        }
    }

    public function proSubscription(Request $request)
    {

        $subs = [];
        $search = $request->q;
        $role = $request->role;
        $where = $request->where;
        if (auth('admin')->check()) {
            $subs = ProSubscription::select("id", "business_name",)
                ->where('business_name', 'LIKE', "%$search%")
                ->limit(15)->get();
            return response()->json($subs);
        }

        if (auth('client')->check()) {
            $subs = ProSubscription::select("id", "business_name",)
                ->where('business_name', 'LIKE', "%$search%")
                ->where('cl_id', auth('client')->user()->id)
                ->limit(15)->get();
            return response()->json($subs);
        }

        if (auth('staff')->check()) {

            $subs = ProSubscription::where('sub_status', 'Active')
                ->whereHas($role, function ($q) use ($where) {
                    $q->where('staff_id', auth('staff')->user()->id)
                        ->where($where, 1);
                })->limit(15)->get();
            return response()->json($subs);
        }
    }
}
