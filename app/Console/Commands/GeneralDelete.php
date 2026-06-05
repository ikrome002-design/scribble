<?php

namespace App\Console\Commands;

use App\Models\ProSubscription;
use App\Models\ProSubscriptionFile;
use Illuminate\Console\Command;
use App\Models\Staff;
use App\Models\TeamSubscription;
use App\Models\Visitor;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;

class GeneralDelete extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'general:delete';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'delete visitors, pro subscriptions, staff';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {

        $six_months = Carbon::now()->subMonths(6)->format('Y-m-d');
        $one_year = Carbon::now()->subYear()->format('Y-m-d H:i:s');

        $pros = ProSubscription::where('pro_recurring_date', '<', $six_months)->get();
        $teams = TeamSubscription::where('team_recurring_date', '<', $six_months)->get();
        $visitors = Visitor::where('created_at', '<', $one_year)->get();
        $staff = Staff::where('team_opted_out', 'Yes')
            ->where('team_opted_out_date', '!=', null)
            ->where('team_opted_out_date', '<', $six_months)->get();

        foreach ($staff as $s) {
            Storage::disk('private')->delete('staff/' . $s->image);
            $s->delete();
        }

        foreach ($visitors as $v) {
            Storage::disk('private')->delete('visitor/' . $v->image);
            $v->delete();
        }


        foreach ($pros as $p) {
            foreach ($p->proSubscriptionFile()->get() as $f) {
                Storage::disk('private')->delete('pro-subscription/' . $f->filename);
                $f->delete();
            }
            foreach ($p->visitor()->get() as $v) {
                Storage::disk('private')->delete('visitor/' . $v->image);
                $v->delete();
            }
            $p->delete();
        }

        foreach ($teams as $team) {
            $staff = Staff::where('cl_id', $team->cl_id)->get();
            foreach ($staff as $s) {
                Storage::disk('private')->delete('staff/' . $s->image);
                $s->delete();
            }
            $team->delete();
        }

        return;
    }
}
