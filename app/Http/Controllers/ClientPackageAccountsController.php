<?php

namespace App\Http\Controllers;


use App\PlanFeatures;
use Illuminate\Http\Request;
use App\Helpers\PriceCalculation;
use \Carbon\Carbon;
use App\Account;
use App\Plan;
use Auth;
use App\Client;
use App\BuyPlan;
use Illuminate\Support\Collection;
use App\Models\ProPlan;
use App\Models\ProSubscription;


class ClientPackageAccountsController extends Controller
{
    public function __construct()
    {
        $this->middleware('client');
    }

    //plan control
    public  function viewPackages()
    {
        $packages = Plan::where('status', 'Active')->get();
        $client = Client::find(Auth::guard('client')->user()->id);
        $today = date('Y-m-d');

        $days_remaining = 0;
        if ($client->plan_recurring_date) {
            $expiryDate = Carbon::parse(Carbon::createFromFormat('Y-m-d', $client->plan_recurring_date)->endOfDay());
            $days_remaining = ceil($expiryDate->diffInHours($today) / 24);
        }
        return view('client.packages', compact('packages', 'client', 'days_remaining'));
    }



    public function  changePackage(Request $request, $id)
    {

        $plan = Plan::where('status', 'Active')->where('id', $id)->first();
        if (!$plan) {
            return redirect('/user/package/all')->withErrors('Package not found');
        }
        $client = Client::find(Auth::guard('client')->user()->id);

        $today = date('Y-m-d');
        if ($id == $client->plan_id) {

            $expiryDate = Carbon::parse(Carbon::createFromFormat('Y-m-d', $client->plan_recurring_date)->endOfDay());
            $days_remaining = ceil($expiryDate->diffInHours($today) / 24);
            if ($days_remaining > 1) {
                return back()->withErrors("You can't generate invoice on active plan");
            }
        }

        $items = [];

        if ($plan->price == 0) {
            $client->plan_status = 'Active';
            $client->plan_recurring_date = date('Y-m-d', strtotime('+30 days', strtotime("Today")));
            $client->plan_id == $request->plan_id;
            $client->save();
        }

        $pros = ProSubscription::where('opted_out', 'No')
            ->where('cl_id', auth('client')->user()->id)
            ->get();

        foreach ($pros as $p) {
            $proplan = ProPlan::where('plan_id', $plan->id)->first();
            if ($proplan) {
                $items[] = [
                    'plan' => $proplan,
                    'quantity' => 1,
                    'pro_subscription_id' => $p->id,
                ];
            }
        }



        if ($plan->price > 0) {
            $items[] = ['plan' => $plan, 'quantity' => 1, 'plan_id' => $plan->id];
        }

        //send invoice items  are greater 0
        if (count($items) > 0) {
            $invoice = new PaymentInvoiceController();
            $invoice_no = $invoice->generateInvoice($client, 'Single', $items, $invoices = null);
            return redirect('user/invoices/view/' . $invoice_no)->with('message', 'Pay for this invoice for subscriptions(s) to be effective.');
        }
        return back()->with('message', 'Updated successfully');
    }
    public function ViewFeatures(Request $request, $id)
    {
        $package = Plan::find($id);
        $features = PlanFeatures::where('plan_id', $id)->get();
        return view('client.view-package-features', compact('package', 'features'));
    }


    // accounts control
    public  function viewAccounts()
    {
        $accounts = Account::all();
        $client = Client::find(Auth::guard('client')->user()->id);

        return view('client.view-accounts', compact('accounts', 'client'));
    }




    public function changeAccount($id)
    {
        $client = Client::find(Auth::guard('client')->user()->id);
        $client->account_type = $id;
        $client->save();

        return back()->with([
            'message' => 'The account was successfully  changed.',

        ]);
    }
}
