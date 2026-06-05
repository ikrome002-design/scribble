<?php

namespace App\Http\Controllers\Team;


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
use App\Models\ChangePlan;
use App\Models\ProSubscription;
use App\Models\TeamPlan;
use App\Models\TeamSubscription;


class ClientPackageAccountsController extends Controller
{
    public function __construct()
    {
        $this->middleware('team');
        $this->middleware('isManager');
    }

    //plan control
    public  function viewPackages()
    {
        $date = date('Y-m-d');
        $packages = Plan::where('status', 'Active')->get();
        $client = Client::find(auth('team')->user()->cl_id);
        $changePlan = ChangePlan::where('cl_id', auth('team')->user()->cl_id)
            ->where('plan_id', '!=', null)->where('change_plan_date', '>', $date)->first();
        return view('client.packages', compact('packages', 'client',  'changePlan'));
    }


    //change and renew package;
    public function changeRenewPackage(Request $request, $id)
    {
        $action = $request->action;
        $billed_frequency = $request->billed_frequency;
        $billed_months = $request->billed_months;
        $change_plan_now = $request->change_plan_now ?? 0;
        $client = Client::find(auth('team')->user()->cl_id);
        $date = date('Y-m-d');

        if (!($action == 'change_frequency' || $action == 'change' || $action == 'renew' || $action == "stop_plan_change")) {
            return  back()->withErrors("Don't tamper Intefere with url");
        }
        if ($billed_frequency < 0 || $billed_frequency > 12) {
            return  back()->withErrors("Billed Frequency must be 1 to 12 months");
        }

        //change billed frequency
        if ($action == 'change_frequency') {
            $client->billed_frequency = $billed_frequency;
            $client->save();
            return back()->with('message', 'Billed frequency updated successfully');
        }

        //change billed frequency
        if ($action == 'stop_plan_change') {
            ChangePlan::where('cl_id', auth('team')->user()->cl_id)->update([
                'plan_id' => null,
                'billed_days' => null,
                'pro_ids' => null,
                'invoice_no' => null,
                'change_plan_date' => null,

            ]);
            return back()->with('message', 'You have successfully stopped the change of plan.');
        }


        $billed_days = $billed_frequency * 30;

        //check if already change plan exist
        if ($id != $client->plan_id) {
            $changePlan = ChangePlan::where('cl_id', auth('team')->user()->cl_id)
                ->where('plan_id', '!=', null)->where('change_plan_date', '>', $date)->first();
            if ($changePlan) {
                return back()
                    ->withErrors('You already have a plan change occuring in later date. Please stop the change before changing the plan');
            }
        }

        if ($action == 'renew') {
            if ($billed_months < 0 || $billed_months > 12) {
                return  back()->withErrors("Billed months must be 1 to 12 months");
            }
            $billed_days = $billed_months * 30;
            $client->billed_frequency = $billed_frequency;
            $client->save();
        }

        $plan = Plan::where('status', 'Active')->where('id', $id)->first();
        if (!$plan) {
            return back()->withErrors('Package not found or not active');
        }

        $plan_price = $plan->price;


        $pros = ProSubscription::where('opted_out', 'No')->where('cl_id', auth('team')->user()->cl_id);
        $pro_plan = ProPlan::where('plan_id', $plan->id)->where('status', 'Active')->first();
        $pro_price = 0;
        if ($pros->count() > 0) {
            if ($pro_plan) {
                $pro_price = $pro_plan->price;
            }
        }

        $teamSub = TeamSubscription::where('opted_out', 'No')->where('cl_id', auth('team')->user()->cl_id)->First();
        $team_plan = TeamPlan::where('plan_id', $plan->id)->where('status', 'Active')->first();

        $team_price = 0;
        if ($teamSub) {
            $request->validate([
                'team_members' => 'required|integer|min:1'
            ]);
            if ($team_plan) {
                $team_price = $team_plan->price;
            }
        }

        $total_price = $plan_price + $pro_price + $team_price;
        //if total price is zero, no need of invoiceno, chnage plan
        if ($total_price == 0) {
            //chnage plan now
            if ($change_plan_now == 1 || $client->plan_status == 'Inactive' || $client->plan_recurring_date < $date) {
                $client->plan_recurring_date = Carbon::now()
                    ->addDays($billed_days)->format('Y-m-d');
                $client->plan_status = 'Active';
                $client->plan_id =  $plan->id;
                $client->billed_frequency = $billed_frequency;
                $client->save();
                if ($pros->count() > 0) {
                    $pros->update(
                        [
                            'sub_status' => 'Active',
                            'pro_recurring_date' => Carbon::now()->addDays($billed_days)->format('Y-m-d'),
                            'pro_plan_id' => $pro_plan->id
                        ]
                    );
                }
                if ($teamSub) {
                    $teamSub->sub_status = 'Active';
                    $teamSub->team_recurring_date = Carbon::now()->addDays($billed_days)->format('Y-m-d');
                    $teamSub->team_plan_id = $team_plan->id;
                }
            } else {
                $pro_ids = [];
                foreach ($pros->get() as $pro) {
                    $pro_ids[] = $pro->id;
                }
                $team_id = null;
                if ($teamSub) {
                    $team_id = $teamSub->id;
                }
                $pro_ids = implode(',', $pro_ids);
                $change_plan_date = Carbon::parse($client->plan_recurring_date)->addDay()->format('Y-m-d');
                ChangePlan::upsert(
                    [
                        'cl_id' => $client->id,
                        'change_plan_date' => $change_plan_date,
                        'pro_ids' => $pro_ids,
                        'plan_id' => $plan->id,
                        'billed_days' => $billed_days,
                        'billed_frequency' => $billed_frequency,
                        'team_id' => $team_id,
                        'team_members' => $request->team_members
                    ],
                    ['cl_id'],
                    [
                        'change_plan_date',
                        'pro_ids',
                        'plan_id',
                        'billed_days',
                        'billed_frequency',
                        'team_id',
                        'team_members'
                    ]
                );
            }
        } else {
            //create invoice
            $items[] = [
                'plan' => $plan,
                'plan_id' => $plan->id,
                'quantity' => $billed_frequency,
                'billed_days ' => $billed_days,
                'billed_frequency' => $billed_frequency,
                'change_plan_now' => $change_plan_now,
            ];
            foreach ($pros->get() as $p) {

                if ($pro_plan) {
                    $items[] = [
                        'plan' => $pro_plan,
                        'pro_subscription_id' => $p->id,
                        'quantity' => $billed_frequency,
                        'billed_days ' => $billed_days,
                        'billed_frequency' => $billed_frequency,
                        'change_plan_now' => $change_plan_now,
                    ];
                }
            }
            if ($teamSub) {
                if ($team_plan) {
                    $items[] = [
                        'plan' => $team_plan,
                        'team_subscription_id' => $teamSub->id,
                        'billed_days ' => $billed_days,
                        'billed_frequency' => $billed_frequency,
                        'change_plan_now' => $change_plan_now,
                        'quantity' => $request->team_members * $billed_frequency,
                        'team_members' => $request->team_members,
                    ];
                }
            }
            //send invoice items  are greater 0
            $duedate = Carbon::now()->addDays(5)->format('Y-m-d');
            if (count($items) > 0) {
                $invoice = new PaymentInvoiceController();
                $invoice_no = $invoice->generateInvoice(client: $client, items: $items, duedate: $duedate);
                return redirect('user/invoices/view/' . $invoice_no)->with('message', 'Pay for this invoice for subscriptions(s) to be effective.');
            }
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
        $client = Client::find(auth('team')->user()->cl_id);

        return view('client.view-accounts', compact('accounts', 'client'));
    }




    public function changeAccount($id)
    {
        $client = Client::find(auth('team')->user()->cl_id);
        $client->account_type = $id;
        $client->save();

        return back()->with([
            'message' => 'The account was successfully  changed.',

        ]);
    }
}
