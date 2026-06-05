<?php

namespace App\Http\Controllers\Team\Team;

use App\Http\Controllers\Controller;
use App\Models\ProSubscription;
use App\Models\TeamSubscription;
use Illuminate\Http\Request;
use App\Models\TeamPlan;
use App\Models\TeamMember;
use App\Models\Staff;
use App\Http\Controllers\PaymentInvoiceController;
use Carbon\Carbon;
use App\Models\ChangePlan;
use App\Models\TeamMembersAction;


class SubscriptionController extends Controller
{
    public function __construct()
    {
        $this->middleware('team');
        $this->middleware('isManager');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $subs = TeamSubscription::where('cl_id', auth('team')->user()->cl_id)->get();

        $teamActions = TeamMembersAction::where('effected', 'No')
            ->where('action_date', '>=', date('Y-m-d'))
            ->whereHas('teamSubscription', function ($q) {
                $q->where('cl_id', auth('team')->user()->cl_id);
            })->get();
        return view('client.team.sub.index', compact('subs', 'teamActions'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {


        $sub = TeamSubscription::where('cl_id', auth('team')->user()->cl_id)->get();
        if (count($sub) > 0) {
            return redirect('team/subscription')->withErrors("Sorry!. You have already have a team subscription. You can opt in or you can contact support.");
        }
        if (auth('team')->user()->client->plan_status != 'Active') {
            return back()->withErrors("You don't have an active main subscription plan.");
        }
        $teamplan = TeamPlan::where('plan_id', auth('team')->user()->client->plan_id)
            ->where('status', 'Active')->first();

        if (!$teamplan) {
            return redirect('team/subscription')->withErrors('There no team link plan asigned to your main subscription. Please contact support');
        }

        $expiryDate = Carbon::parse(auth('team')->user()->client->plan_recurring_date)->endOfDay();
        $today = Carbon::now()->startOfDay();
        $days_remaining = ceil($expiryDate->diffInHours($today) / 24);

        return view('client.team.sub.create', compact('teamplan', 'days_remaining'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $client = auth('team')->user()->client;
        $sub = TeamSubscription::where('cl_id', $client->id)->get();
        if (count($sub) > 0) {
            return redirect('team/subscription')->withErrors("Sorry!. You have already have a team subscription. You can opt in or you can contact support.");
        }
        if ($client->plan_status != 'Active') {
            return redirect('team/subscription')->withErrors("You don't have an active main subscription plan.");
        }
        $expiryDate = Carbon::parse($client->plan_recurring_date)->endOfDay();
        $today = Carbon::now()->startOfDay();

        $days_remaining = ceil($expiryDate->diffInHours($today) / 24);

        if ($days_remaining < 2) {
            return back()->withErrors("You can't add team subscription since your main subscription has expired or is expiring today. 
            You need to renew your main subscription. If you face difficult, Please contact support");
        }
        $teamplan = TeamPlan::where('plan_id', $client->plan_id)
            ->where('status', 'Active')->first();

        if (!$teamplan) {
            return redirect('team/subscription')->withErrors('There no team link plan asigned to your main subscription. Please contact support');
        }



        $request->validate([
            'team_members' => 'required|integer|min:1',
        ]);

        $subscription = new TeamSubscription();
        $subscription->team_members = $request->team_members;
        $subscription->team_plan_id = $teamplan->id;
        $subscription->team_recurring_date = $client->plan_recurring_date;
        $subscription->cl_id = $client->id;
        if ($teamplan->price == 0) {
            $subscription->sub_status = 'Active';
        } else {
            $subscription->sub_status = 'Inactive';
        }
        $subscription->save();

        if ($teamplan->price > 0) {
            $expiryDate = Carbon::parse($client->plan_recurring_date)->endOfDay();
            $today = Carbon::now()->startOfDay();
            $days_remaining = ceil($expiryDate->diffInHours($today) / 24);

            $duedate = Carbon::now()->addDays(5);
            if ($days_remaining < 5) {
                $duedate = $client->plan_recurring_date;
            }

            $pay = new PaymentInvoiceController();

            $items[] = [
                'plan' => $teamplan,
                'quantity' => $request->team_members,
                'remaining_days' => $days_remaining,
                'team_subscription_id' => $subscription->id,
                'team_recurring_date' => $client->plan_recurring_date,
                'team_members' => $request->team_members,
            ];

            $invoice_no = $pay->generateInvoice(client: $client, items: $items, duedate: $duedate);
            return redirect('user/invoices/view/' . $invoice_no)
                ->with('message', 'Please pay this invoice before due date. Please remember you need to have active main Scribble subscription for this invoice to be effective.');
        }
        return redirect('/')->with('message', 'You have successfully update successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\TeamSubscription  $subscription
     * @return \Illuminate\Http\Response
     */
    public function show(TeamSubscription $subscription)
    {
        return back();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\TeamSubscription  $subscription
     * @return \Illuminate\Http\Response
     */
    public function edit(TeamSubscription $subscription)
    {
        return back();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\TeamSubscription  $subscription
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, TeamSubscription $subscription)
    {
        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\TeamSubscription  $subscription
     * @return \Illuminate\Http\Response
     */
    public function destroy(TeamSubscription $subscription)
    {
        return back();
    }

    //generate invoice
    public function generateInvoice(Request $request, $id)
    {
        $client = auth('team')->user()->client;
        $sub = TeamSubscription::where('cl_id', $client->id)->first();

        if (!$sub) {
            return back()->withError('Subscription not found');
        }

        if ($sub->sub_status == 'Active') {
            return back()->withError("You can't generate invoice for active subscription");
        }


        if ($client->plan_status != 'Active') {
            return redirect('team/subscription')->withErrors("You don't have an active main subscription plan.");
        }
        $teamplan = TeamPlan::where('plan_id', $client->plan_id)
            ->where('status', 'Active')->first();

        if (!$teamplan) {
            return redirect('team/subscription')->withErrors('There no team link plan asigned to your main subscription. Please contact support');
        }

        $expiryDate = Carbon::parse($client->plan_recurring_date)->endOfDay();
        $today = Carbon::now()->startOfDay();

        $days_remaining = ceil($expiryDate->diffInHours($today) / 24);

        if ($days_remaining < 2) {
            return back()->withErrors("You can't generate invoice since your main subscription has expired or is expiring today. 
            You need to renew your main subscription. If you face difficult, Please contact support");
        }

        $request->validate([
            'team_members' => 'required|integer|min:1',
        ]);

        $sub->team_recurring_date = $client->plan_recurring_date;
        if ($teamplan->price == 0) {
            $sub->sub_status = 'Active';
            $sub->team_members = $request->team_members;
        }
        $sub->save();

        if ($teamplan->price > 0) {
            $expiryDate = Carbon::parse($client->plan_recurring_date)->endOfDay();
            $today = Carbon::now()->startOfDay();
            $days_remaining = ceil($expiryDate->diffInHours($today) / 24);

            $duedate = Carbon::now()->addDays(5);
            if ($days_remaining < 5) {
                $duedate = $client->plan_recurring_date;
            }

            $pay = new PaymentInvoiceController();

            $items[] = [
                'plan' => $teamplan,
                'quantity' => $request->team_members,
                'remaining_days' => $days_remaining,
                'team_subscription_id' => $sub->id,
                'team_members' => $request->team_members,
                'team_recurring_date' => $client->plan_recurring_date,
            ];

            $invoice_no = $pay->generateInvoice(client: $client, items: $items, duedate: $duedate);
            return redirect('user/invoices/view/' . $invoice_no)
                ->with('message', 'Please pay this invoice before due date.Please remember you need to have active main Scribble subscription for this invoice to be effective.
                 Additionally, ensure that staff members who are not opted out and active does not exceed maximum number of staff members you have subscribed for. 
                 Otherwise the system will automatically opt out and deactivate some staff to meet the required staff members.');
        }
        return redirect('/')->with('message', 'You have successfully subscribed');
    }

    //opt out
    public function optIn(Request $request, $id)
    {
        $client = auth('team')->user()->client;
        $sub = TeamSubscription::where('cl_id', $client->id)
            ->where('opted_out', 'Yes')->first();

        if (!$sub) {
            return back()->withError('Subscription not found or you have already opted in');
        }

        if ($client->plan_status != 'Active') {
            return redirect('team/subscription')->withErrors("You don't have an active main subscription plan.");
        }
        $expiryDate = Carbon::parse($client->plan_recurring_date)->endOfDay();
        $today = Carbon::now()->startOfDay();

        $days_remaining = ceil($expiryDate->diffInHours($today) / 24);

        if ($days_remaining < 2) {
            return back()->withErrors("You can't opt in since your main subscription has expired or is expiring today. 
            You need to renew your main subscription. If you face difficult, Please contact support");
        }

        $teamplan = TeamPlan::where('plan_id', $client->plan_id)
            ->where('status', 'Active')->first();

        if (!$teamplan) {
            return redirect('team/subscription')->withErrors('There no team link plan asigned to your main subscription. Please contact support');
        }


        $request->validate([
            'team_members' => 'required|integer|min:1',
        ]);

        TeamMembersAction::whereHas('teamSubscription', function ($q) {
            $q->where('cl_id', auth('team')->user()->cl_id);
        })->update(['effected' => 'Yes']);

        $sub->team_recurring_date = $client->plan_recurring_date;
        if ($teamplan->price == 0) {
            $sub->sub_status = 'Active';
            $sub->opted_out = 'No';
            $sub->opted_out_date = null;
        }
        $sub->save();

        if ($teamplan->price > 0) {
            $expiryDate = Carbon::parse($client->plan_recurring_date)->endOfDay();
            $today = Carbon::now()->startOfDay();
            $days_remaining = ceil($expiryDate->diffInHours($today) / 24);

            $duedate = Carbon::now()->addDays(5);
            if ($days_remaining < 5) {
                $duedate = $client->plan_recurring_date;
            }

            $pay = new PaymentInvoiceController();

            $items[] = [
                'plan' => $teamplan,
                'quantity' => $request->team_members,
                'remaining_days' => $days_remaining,
                'team_subscription_id' => $sub->id,
                'team_members' => $request->team_members,
                'team_recurring_date' => $client->plan_recurring_date,
            ];

            $invoice_no = $pay->generateInvoice(client: $client, items: $items, duedate: $duedate);
            return redirect('user/invoices/view/' . $invoice_no)
                ->with('message', 'Please pay this invoice before due date.Please remember you need to have active main Scribble subscription for this invoice to be effective.
                 Additionlly, ensure that staff members who are not opted out and active does not exceed maximum of team members you have subscribed for. 
                 Otherwise the system will automatically opt out and deactivate some staff to meet the required staff.');
        }
        return redirect('/')->with('message', 'You have successfully subscribed');
    }


    public function optOut($id)
    {
        $client = auth('team')->user()->client;
        $sub = TeamSubscription::where('id', $id)
            ->where('cl_id', $client->id)
            ->where('opted_out', 'No')
            ->first();

        if (!$sub) {
            return back()->withErrors('Subscription was not found or you have already opted out');
        }

        $date = date('Y-m-d');

        TeamMembersAction::whereHas('teamSubscription', function ($q) {
            $q->where('cl_id', auth('team')->user()->cl_id);
        })->update(['effected' => 'Yes']);
        $sub->opted_out = 'Yes';
        $sub->opted_out_date = $sub->team_recurring_date;

        if ($sub->team_recurring_date < $date) {
            $sub->sub_status = 'Inactive';
            Staff::where('cl_id', $client->id)
                ->update(['status' => 'Inactive']);
        }
        $sub->save();

        return back()->with('message', ' You have opted successfully. 
        All your staff members will become opted out and become inactive when the current billing period ends.');
    }

    //opt out
    public function scaleUpDown(Request $request, $id)
    {
        $request->validate([
            'team_members' => 'required|integer|min:1',
            'scale' => 'required',
        ]);
        if ($request->scale == 'up') {
            $request->validate([
                'scale_up_when' => 'required',
            ]);
        }

        $client = auth('team')->user()->client;
        $sub = TeamSubscription::where('cl_id', $client->id)
            ->where('opted_out', 'No')->first();

        if (!$sub) {
            return back()->withError('Subscription not found or you have already opted out of subscription');
        }

        if ($client->plan_status != 'Active') {
            return redirect('team/subscription')->withErrors("You don't have an active main subscription plan.");
        }
        $expiryDate = Carbon::parse($client->plan_recurring_date)->endOfDay();
        $today = Carbon::now()->startOfDay();

        $days_remaining = ceil($expiryDate->diffInHours($today) / 24);

        $teamplan = TeamPlan::where('plan_id', $client->plan_id)
            ->where('status', 'Active')->first();

        if (!$teamplan) {
            return redirect('team/subscription')->withErrors('There no team link plan asigned to your main subscription. Please contact support');
        }
        $action = $request->scale == 'up' ? 'Increment' : 'Decrement';

        if ($request->scale == 'down') {
            $decrement = TeamMembersAction::where('effected', 'No')
                ->where('action_date', '>=', date('Y-m-d'))
                ->where('action', 'Decrement')
                ->whereHas('teamSubscription', function ($q) use ($client) {
                    $q->where('cl_id', $client->id);
                })->sum('team_members');
            $member_min = $sub->team_members - 1 - $decrement;
            if ($member_min < $request->team_members) {
                return back()->withErrors("You can scale down by maximum of  $member_min member(s)");
            }
        }

        if ($request->scale == 'up' &&  $request->scale_up_when == 'now') {

            if ($teamplan->price == 0) {
                $sub->increment('team_members', $request->team_members);
            }

            if ($teamplan->price > 0) {
                $expiryDate = Carbon::parse($client->plan_recurring_date)->endOfDay();
                $today = Carbon::now()->startOfDay();
                $days_remaining = ceil($expiryDate->diffInHours($today) / 24);

                $duedate = Carbon::now()->addDays(5);
                if ($days_remaining < 5) {
                    $duedate = $client->plan_recurring_date;
                }

                $pay = new PaymentInvoiceController();

                $items[] = [
                    'plan' => $teamplan,
                    'quantity' => $request->team_members,
                    'remaining_days' => $days_remaining,
                    'team_subscription_id' => $sub->id,
                    'team_members' => $request->team_members,
                    'team_recurring_date' => $client->plan_recurring_date,
                    'team_members_increment_by' => $request->team_members,
                    'description' => 'Team Members Increment'
                ];

                $invoice_no = $pay->generateInvoice(client: $client, items: $items, duedate: $duedate);
                return redirect('user/invoices/view/' . $invoice_no)
                    ->with('message', 'Please pay this invoice before due date.');
            }
        }
        $teamAction = new TeamMembersAction();
        $teamAction->team_subscription_id = $sub->id;
        $teamAction->action = $action;
        $teamAction->team_members = $request->team_members;
        $teamAction->action_date = $sub->team_recurring_date;
        $teamAction->save();

        return back()->with('message', "You have successfully added team subscription action. 
        If you scaled down the members, ensure that you have opted out members so as to match the number of team members who will remain at end of the current billing period");
    }

    //stop team member saction at end of billing period
    public function stopTeamMembersAction($id)
    {
        $teamAction = TeamMembersAction::where('id', $id)
            ->whereHas('teamSubscription', function ($q) {
                $q->where('cl_id', auth('team')->user()->cl_id);
            })->first();
        if (!$teamAction) {
            return back()->withErrors('Action not found');
        }

        $teamAction->effected = 'Yes';
        $teamAction->save();

        return back()->with('message', 'Action stopped successfully');
    }
}
