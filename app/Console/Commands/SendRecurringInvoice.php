<?php

namespace App\Console\Commands;

use App\InvoiceItems;
use App\Invoices;
use App\Client;
use App\Http\Controllers\PaymentInvoiceController;
use Illuminate\Console\Command;
use App\Plan;
use App\BuyPlan;
use Carbon\Carbon;
use App\Models\ProPlan;
use App\Models\ProSubscription;

class SendRecurringInvoice extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'invoice:recurring';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'For Sending Recurring Invoice';

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
        $clients_active = Client::where('plan_recurring_date', $current_date)
            ->where('status', 'active')->get();


        foreach ($clients_active as $client) {
            $items = [];
            $plan = Plan::find($client->plan_id);
            $proPlan = ProPlan::where('plan_id', $plan->id)->first();
            foreach ($client->proSubscription()->where('opted_out', 'No')->get() as $p) {
                if ($proPlan) {
                    $items[] = [
                        'plan' => $proPlan,
                        'quantity' => 1,
                        'pro_subscription_id' => $p->id,
                    ];
                }
            }

            if ($plan->price > 0) {
                $items[] = ['plan' => $plan, 'quantity' => 1, 'plan_id' => $plan->id];
            } else {
                $client->plan_recurring_date = date('Y-m-d', strtotime('+30 days', strtotime("Today")));
                $client->save();
            }
            //send invoice items  are greater 0
            if (count($items) > 0) {
                $message = "Please pay for the following before due date. The susbcription(s) will become inactive after today.";
                $invoice = new PaymentInvoiceController();
                $invoice_no = $invoice->generateInvoice($client, 'Single', $items, $invoices = null, $message);
            }
        }

        $expiryDate = date('Y-m-d');
        $clients = Client::where('plan_recurring_date', '<', $expiryDate)
            ->where('plan_status', 'Active')
            ->get();
        foreach ($clients as $c) {
            $plan = Plan::find($c->plan_id);
            if ($plan->price > 0) {
                $c->plan_status = 'Inactive';
                $c->save();
            } else {
                $c->plan_status = 'Active';
                $c->plan_recurring_date = date('Y-m-d', strtotime('+30 days', strtotime("Today")));
                $c->save();
            }

            foreach ($c->proSubscription()->where('sub_status', 'Active')->get() as $p) {
                $p->sub_status = 'Inactive';
                $p->save();
            }
        }

        return;
    }
}
