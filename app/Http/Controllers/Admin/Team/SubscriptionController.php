<?php

namespace App\Http\Controllers\Admin\Team;

use App\Client;
use App\DataTables\TeamSubscriptionsDataTable;
use App\Http\Controllers\Controller;
use App\Models\TeamSubscription;
use Illuminate\Http\Request;
use App\Models\TeamPlan;
use App\Http\Controllers\PaymentInvoiceController;
use Carbon\Carbon;


class SubscriptionController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(TeamSubscriptionsDataTable $dataTable)
    {
        return $dataTable->render('admin.team.sub.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.team.sub.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $request->validate([
            'team_members' => 'required|integer|min:1',
            'client' => 'required',
        ]);
        $client = Client::find($request->client);
        $sub = TeamSubscription::where('cl_id', $client->id)->get();
        if (count($sub) > 0) {
            return redirect('team/subscription')->withErrors("Sorry!. The client a a team subscription.");
        }
        if ($client->plan_status != 'Active') {
            return redirect('team/subscription')->withErrors("The client does not have an active main subscription plan.");
        }
        $teamplan = TeamPlan::where('plan_id', $client->plan_id)
            ->where('status', 'Active')->first();

        if (!$teamplan) {
            return redirect('team/subscription')->withErrors('There no team link plan asigned to main subscription.');
        }



        $subscription = new TeamSubscription();
        $subscription->sub_status = $request->generate_invoice == 'Yes' ? 'Inactive' : 'Active';
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

        if ($teamplan->price > 0 && $request->generate_invoice == 'Yes') {
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
            ];

            $pay->generateInvoice(client: $client, items: $items, duedate: $duedate);
        }
        return back()->with('message', 'You have successfully added');
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

        return view('admin.team.sub.edit', compact('subscription'));
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
        $request->validate([
            'team_members' => 'required|integer|min:1',
        ]);
        $client = Client::find($subscription->cl_id);

        if ($request->sub_status == 'Active' && $client->plan_status != 'Active') {
            return back()->withErrors("You can't set subscription active while the main subscription is inactive.");
        }

        if ($request->opted_out == 'Yes' && $subscription->opted_out_date == null) {
            $subscription->opted_out_date = date('Y-m-d');
        }

        if ($request->opted_out == 'No') {
            $subscription->opted_out_date = null;
        }

        $subscription->opted_out = $request->opted_out;
        $subscription->team_members = $request->team_members;
        $subscription->team_recurring_date = $client->plan_recurring_date;
        $subscription->sub_status = $request->sub_status;
        $subscription->save();

        return back()->with('message', 'You have successfully updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\TeamSubscription  $subscription
     * @return \Illuminate\Http\Response
     */
    public function destroy(TeamSubscription $subscription)
    {
        $subscription->delete();
        return back()->with('message', 'Deleted successfully');
    }
}
