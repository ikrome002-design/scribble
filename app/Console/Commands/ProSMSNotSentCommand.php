<?php

namespace App\Console\Commands;

use App\Client;
use App\Helpers\SmsHelper;
use Illuminate\Console\Command;
use App\Models\ProSmsNotSent;
use App\Models\ProSubscription;

class ProSMSNotSentCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pro_sms:not_sent';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send Pro subscription not sent Sms not sent';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {

        $today = date('Y-m-d');
        $opted = ProSmsNotSent::whereHas('proSubscription', function ($q) use ($today) {
            $q->where('opted_out_date', '<', $today)
                ->where('opted_out_date', '!=', null)
                ->orWhereHas('client', function ($q) {
                    $q->where('status', '!=', 'active');
                });
        });
        if ($opted->count() > 0) {
            $opted->delete();
        }
        $pro_sms_not = ProSmsNotSent::all();
        $sms_helper = new SmsHelper();
        foreach ($pro_sms_not as $sms_not_sent) {
            $sub = ProSubscription::find($sms_not_sent->proSubscription->id);
            $client = Client::find($sms_not_sent->proSubscription->cl_id);
            $phone_number = $sms_not_sent->phone_number;
            $message =  $sms_not_sent->message;
            $sms_helper->clientSendQuickSms($sub->sender_id, $client, $phone_number, $message, 0, null, $sms_not_sent);
        }
    }
}