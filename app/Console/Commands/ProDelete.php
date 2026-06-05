<?php

namespace App\Console\Commands;

use App\Models\ProSubscription;
use App\Models\ProSubscriptionFile;
use Illuminate\Console\Command;
use App\Models\Staff;
use App\Models\Visitor;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;

class ProDelete extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pro:delete';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'delete visitors and pro subscriptions';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {

        $six_months = Carbon::now()->subMonths(6)->format('Y-m-d');
        $one_year = Carbon::now()->subYear()->format('Y-m-d H:i:s');

        $visitors = Visitor::where('created_at', '<=', $one_year);

        foreach ($visitors->get() as $v) {
            Storage::disk('private')->delete('visitor/' . $v->image);
        }
        if ($visitors->count() > 0) {
            $visitors->delete();
        }


        $pros = ProSubscription::where('plan_recurring_date', '<=', $six_months);

        foreach ($pros->get() as $p) {
            foreach ($p->proSubscriptionFile()->get() as $f) {
                Storage::disk('private')->delete('pro-subscription/' . $f->filename);
            }
            foreach ($p->visitor()->get() as $v) {
                Storage::disk('private')->delete('visitor/' . $v->image);
            }
            foreach ($p->staff()->get() as $s) {
                Storage::disk('private')->delete('visitor/' . $s->image);
            }
        }

        if ($pros->count() > 0) {
            $pros->delete();
        }
        return;
    }
}