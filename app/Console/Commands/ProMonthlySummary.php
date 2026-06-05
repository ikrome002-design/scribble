<?php

namespace App\Console\Commands;

use App\Models\ProSubscription;
use Illuminate\Console\Command;
use App\Helpers\SmsHelper;
use Illuminate\Support\Carbon;

class ProMonthlySummary extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pro:monthly-summary';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'sending monthly summary';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {



        $prev_month = Carbon::now()->subMonth();
        $end_date = $prev_month->format('Y-m-d');

        $subs = ProSubscription::where('sub_status', 'Active')
            ->where('transactions', 1)
            ->where('shortcode', '!=', null)
            ->whereHas('client', function ($q) {
                $q->where('status', 'active');
            })->get();

        $clients = [];
        foreach ($subs as $s) {
            if ($s->phone_number) {
                $name = $s->client->fname;
                $start_of_month = $prev_month->startOfMonth()->format('Y-m-d H:i:s');
                $end_of_month = $prev_month->endOfMonth()->format('Y-m-d H:i:s');
                $total = $s->shortcodeTransaction()
                    ->whereBetween('transaction_date', [$start_of_month, $end_of_month])->sum('amount');
                $clients["$s->phone_number"][] = [
                    'business_name' => $s->business_name,
                    'shortcode_type' => $s->shortcode_type,
                    'shortcode' => $s->shortcode,
                    'total' => $total,
                    'sender_id' => $s->sender_id,
                    'sub' => $s
                ];
            }
        }

        foreach ($clients as $k => $c) {
            $name = $c[0]['sub']->client->lname;
            $sender_id = $c[0]['sub']->sender_id;
            $client = $c[0]['sub']->client()->first();
            $message = "Hello $name, Here is your Monthly Summary Transactions Report for period ending $end_date at 11:59PM: ";
            foreach ($c as $v) {
                $business_name = $v['business_name'];
                $shortcode_type = $v['shortcode_type'];
                $shortcode = $v['shortcode'];
                $total = $v['total'];
                $message .= "$business_name ($shortcode_type, $shortcode) : KES $total ";
            }
            $message .= "A Citrus Labs Limited product";
            $sms_helper = new SmsHelper();
            $sms_helper->clientSendQuickSms($sender_id, $client, $k, $message);
        }
    }
}
