<?php

namespace App\Console\Commands;

use App\Models\ProSubscription;
use Illuminate\Console\Command;
use App\Helpers\SmsHelper;
use Illuminate\Support\Carbon;

class ProDailySummary extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pro:daily-summary';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'sending daily summary';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {

        $now_time = date('H:i:00');
        $now_date = date('Y-m-d');
        $subs = ProSubscription::where('sub_status', 'Active')
            ->where('summary_time', $now_time)
            ->where('transactions', 1)
            ->where('shortcode', '!=', null)
            ->whereHas('client', function ($q) {
                $q->where('status', 'active');
            })->get();

        $clients = [];
        foreach ($subs as $s) {
            if ($s->phone_number) {
                $name = $s->client()->first()->fname;
                $start_of_day = Carbon::parse($now_date)->startOfDay()->format('Y-m-d H:i:s');
                $end_of_day = Carbon::parse($now_date)->endOfDay()->format('Y-m-d H:i:s');
                $total = $s->shortcodeTransaction()
                    ->whereBetween('transaction_date', [$start_of_day, $end_of_day])->sum('amount');
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
            $client = $c[0]['sub']->client;
            $message = "Hello $name,  Here is your Summary Daily Transactions Report as on $now_date at $now_time: ";
            foreach ($c as $v) {
                $business_name = $v['business_name'];
                $shortcode_type = $v['shortcode_type'];
                $shortcode = $v['shortcode'];
                $total = $v['total'];
                $message .= "$business_name ($shortcode_type, $shortcode) : KES $total";
            }
            $message .= "A Citrus Labs Limited product";
            $sms_helper = new SmsHelper();
            $sms_helper->clientSendQuickSms($sender_id, $client, $k, $message);
        }
    }
}
