<?php

namespace App\Console\Commands;

use App\Invoices;
use App\Client;
use App\Http\Controllers\PaymentInvoiceController;
use App\Models\ChangePlan;
use Illuminate\Console\Command;
use App\Plan;
use App\Models\ProPlan;
use App\Models\ProSubscription;
use Carbon\Carbon;
use App\Models\TeamPlan;
use App\Models\TeamSubscription;
use App\Models\Staff;
use App\Models\TeamMembersAction;


class UpdatePlansInvoices extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:plansinvoices';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Activate, deactivate plans, set expired invoices, change plans ';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $current_date = date('Y-m-d');
        //update expired invoices
        Invoices::where('duedate', '<', $current_date)
            ->where('status', 'Unpaid')
            ->orWhere('status', 'Partially Paid')
            ->update(['status' => 'Expired']);
        //stop action which less are than today
        TeamMembersAction::where('effected', 'No')
            ->where('action_date', '<', $current_date)
            ->update(['effected' => 'Yes']);
        //change plans
        $change = ChangePlan::where('change_plan_date', $current_date)
            ->where('plan_id', '!=', null)->get();
        foreach ($change as $ch) {
            $expiry = Carbon::now()->addDays($ch->billed_days)->format('Y-m-d');
            $client = Client::find($ch->cl_id);
            $client->plan_id = $ch->plan_id;
            $client->plan_status = 'Active';
            $client->billed_frequency = $ch->billed_frequency;
            $client->save();
            $pro_plan = ProPlan::where('plan_id', $ch->plan_id)->first();
            $team_plan = TeamPlan::where('plan_id', $ch->plan_id)->first();
            $pro_ids = explode(',', $ch->pro_ids);
            ProSubscription::whereIn('id', $pro_ids)
                ->update([
                    'sub_status' => 'Active',
                    'pro_plan_id' => $pro_plan->id,
                    'pro_recurring_date' => $expiry
                ]);
            TeamSubscription::where('id', $change->team_id)
                ->update([
                    'sub_status' => 'Active',
                    'team_plan_id' => $team_plan->id,
                    'team_members' => $$ch->team_members,
                    'team_recurring_date' => $expiry
                ]);
        }
        ChangePlan::where('change_plan_date', $current_date)
            ->where('plan_id', '!=', null)->update([
                'plan_id' => null,
                'billed_days' => null,
                'pro_ids' => null,
                'invoice_no' => null,
                'change_plan_date' => null,
                'team_id' => null,
                'team_members' => null,
                'billed_frequency' => 1,

            ]);

        //deactivate price greater than zero
        Client::where('plan_recurring_date', '<', $current_date)
            ->where('plan_status', 'Active')
            ->whereHas('Plan', function ($q) {
                $q->where('price', '>', 0);
            })->update(
                ['plan_status' => 'Inactive']
            );

        //deactivate pro price greater than zero
        ProSubscription::where('pro_recurring_date', '<', $current_date)
            ->where('sub_status', 'Active')
            ->whereHas('proPlan', function ($q) {
                $q->where('price', '>', 0);
            })->update(
                ['sub_status' => 'Inactive']
            );

        //deactivate team price greater than zero
        TeamSubscription::where('team_recurring_date', '<', $current_date)
            ->where('sub_status', 'Active')
            ->whereHas('teamPlan', function ($q) {
                $q->where('price', '>', 0);
            })->update(
                ['sub_status' => 'Inactive']
            );

        //extend where plan price is zero
        $clients = Client::where('plan_recurring_date', '<', $current_date)
            ->where('plan_status', 'Active')
            ->whereHas('plan', function ($q) {
                $q->where('price', 0);
            })->get();
        foreach ($clients as $client) {
            $days = $client->billed_frequency * 30;
            $expiry = Carbon::now()->addDays($days)->format('Y-m-d');
            $client->plan_status = 'Active';
            $client->plan_recurring_date = $expiry;
            $client->save();
        }
        //extend if pro plan has zero
        $pros = ProSubscription::where('pro_recurring_date', '<', $current_date)
            ->where('sub_status', 'Active')
            ->whereHas('proPlan', function ($q) {
                $q->where('price', 0);
            })->get();

        foreach ($pros as $pro) {
            $client = Client::find($pro->cl_id);
            $days = $client->billed_frequency * 30;
            $expiry = Carbon::now()->addDays($days)->format('Y-m-d');
            $pro->sub_status = 'Active';
            $pro->pro_recurring_date = $expiry;
            $pro->save();
        }

        //extend if team plan has zero
        $teams = TeamSubscription::where('team_recurring_date', '<', $current_date)
            ->where('sub_status', 'Active')
            ->whereHas('teamPlan', function ($q) {
                $q->where('price', 0);
            })->get();

        foreach ($teams as $team) {
            $client = Client::find($team->cl_id);
            $days = $client->billed_frequency * 30;
            $expiry = Carbon::now()->addDays($days)->format('Y-m-d');
            $team->sub_status = 'Active';
            $team->team_recurring_date = $expiry;
            $team->save();
        }

        //deactivate staff members which were opted out
        Staff::where('team_opted_out', 'Yes')
            ->where('team_opted_out_date', '<', $current_date)
            ->update(['status' => 'Inactive']);


        return;
    }
}
