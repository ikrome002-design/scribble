<?php

namespace App\Helpers;

use App\SenderIdManage;
use App\Jobs\SendBulkSMS;
use App\SMSGatewayCredential;
use App\SMSGateways;
use App\Http\Controllers\Controller;
use App\CustomSMSGateways;
use Illuminate\Support\Carbon;
use App\Models\ProSmsNotSent;
use App\Models\ProSubscription;
use Illuminate\Support\Facades\Log;

class SmsHelper
{

    function clientSendQuickSms($sender_id, $client, $phone, $message, $minutes = 0, $pro_sub_id = null, $pro_sms_not_sent = null)
    {

        $phone = str_replace("-", "", filter_var($phone, FILTER_SANITIZE_NUMBER_INT));
        if (substr($phone, 0, 1) !== '+') {
            $phone = (substr($phone, 0, 1) == "0") ? preg_replace("/^0/", "+254", $phone) : $phone;
            $phone = strlen($phone) == 9 ? "+254$phone" : $phone;
            $phone = substr($phone, 0, 1) == '+' ? $phone : "+$phone";
        }
        if (preg_match("/^\\+?[1-9][0-9]{7,14}$/", $phone)) {
            $sender = SenderIdManage::find($sender_id);
            if (!$sender_id) {
                return ['Sender id not found not active'];
            }
            if ($sender->status != 'unblock') {
                return ['Sender id not active'];
            }

            $gateways = json_decode($client->sms_gateway);
            $cg_info = '';
            $gateway_credential = null;

            $gateway  = SMSGateways::whereIn('id', $gateways)->where('status', 'Active')->first();
            $msgcount = strlen(preg_replace('/\s+/', ' ', trim($message)));


            if ($msgcount <= 160) {
                $msgcount = 1;
            } else {
                $msgcount = $msgcount / 157;
            }
            $msgcount = ceil($msgcount);

            if ($gateway) {
                if ($gateway->custom == 'Yes') {

                    if ($gateway->type == 'smpp') {
                        $gateway_credential = SMSGatewayCredential::where('gateway_id', $gateway->id)->where('status', 'Active')->orderBy('id', 'desc')->first();
                    } else {
                        $cg_info = CustomSMSGateways::where('gateway_id', $gateway->id)->first();
                    }
                } else {
                    $gateway_credential = SMSGatewayCredential::where('gateway_id', $gateway->id)->where('status', 'Active')->orderBy('id', 'desc')->first();
                    if ($gateway_credential == null) {
                        return ['no gateway credentail'];
                    }
                }

                $sms_count = $client->sms_limit;

                if ($sms_count >= $msgcount) {
                    $remain_sms = $sms_count - $msgcount;
                    $client->sms_limit = $remain_sms;
                    $client->save();
                    $msg_type = 'plain';
                    if ($minutes) {
                        $job = (new SendBulkSMS($client->id, $phone, $gateway, $gateway_credential, $sender->sender_id, $message, $msgcount, $cg_info, '', $msg_type))->delay(Carbon::now()->addMinutes($minutes));
                    } else {
                        $job = ((new SendBulkSMS($client->id, $phone, $gateway, $gateway_credential, $sender->sender_id, $message, $msgcount, $cg_info, '', $msg_type)));
                    }
                    if ($pro_sms_not_sent) {
                        $pro_sms_not = ProSmsNotSent::find($pro_sms_not_sent->id);
                        $pro_sms_not->delete();
                    }
                    dispatch($job);
                    return;
                } else {
                    if (!$pro_sms_not_sent) {
                        if ($pro_sub_id) {
                            $pro_sms = new ProSmsNotSent();
                            $pro_sms->message = $message;
                            $pro_sms->pro_subscription_id = $pro_sub_id;
                            $pro_sms->phone_number = $phone;
                            $pro_sms->save();
                        }
                    }
                }
            }
            return;
        }

        return 'invalid';
    }
}
