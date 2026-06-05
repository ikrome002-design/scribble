<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\MpesaTranscation;
use App\Models\ProSubscription;
use App\Models\ShortcodeTransaction;
use App\SenderIdManage;
use App\SMSGateways;
use App\Client;
use App\Models\Staff;
use App\Helpers\SmsHelper;

class MpesaResponseController extends Controller
{

    public function __construct()
    {
        $this->middleware('MpesaIps');
    }
    public function validation(Request $request)
    {
        return [
            'ResultCode' => '0',
            'ResultDesc' => 'Accepted',
        ];
    }
    //confirmation
    public function confirmation(Request $request)
    {
        $content = json_decode($request->getContent());
        $checkTrans = ShortcodeTransaction::where('trans_id', $content->TransID);
        if ($checkTrans->count() > 0) {
            return [
                'ResultCode' => 'C2B00012',
                'ResultDesc' => 'Rejected',
            ];
        }
        $sub = ProSubscription::where('shortcode', $content->BusinessShortCode)->first();

        if (!$sub) {
            return ['Prosubscription not found'];
        }

        $firstname = isset($content->FirstName) ? $content->FirstName : '';
        $lastname = isset($content->LastName) ? $content->LastName : '';
        $middlename = isset($content->MiddleName) ? $content->MiddleName : '';

        $mpesaTrans = new ShortcodeTransaction();
        $mpesaTrans->transaction_type = $content->TransactionType;
        $mpesaTrans->shortcode     = $content->BusinessShortCode;
        // $mpesaTrans->third_party_id = $content->ThirdPartyTransID;
        $mpesaTrans->transaction_type = $content->TransactionType;
        $mpesaTrans->trans_id = $content->TransID;
        $mpesaTrans->transaction_date = $content->TransTime;
        $mpesaTrans->amount = $content->TransAmount;
        $mpesaTrans->bill_ref_number = $content->BillRefNumber ?? null;
        $mpesaTrans->balance = $content->OrgAccountBalance ?: null;
        $mpesaTrans->phone_number = $content->MSISDN;
        $mpesaTrans->name = $firstname . ' ' . $middlename . ' ' . $lastname;
        $mpesaTrans->amount = $content->TransAmount;
        $mpesaTrans->save();

        //check if shortcode active
        $sub = ProSubscription::where('shortcode', $content->BusinessShortCode)
            ->where('sub_status', 'active')->first();

        if (!$sub) {
            return ['Prosubscription not active or not found'];
        }

        if (!$sub->sender_id) {
            return ['sender not added'];
        }

        $client = Client::find($sub->cl_id);

        $sms_client = "$firstname $lastname  $content->MSISDN paid KES $content->TransAmount to $sub->business_name using reference number $content->TransID. 
        Thank you for using Scribble. https://scribble.ke A Citrus Labs Limited product.";
        $sms_customer = "Dear valued customer, Thank you for entrusting us with your recent purchase and service requirements. We appreciate your business and hope you are pleased with your purchase. We appreciate your support and hope to see you again. Best wishes, $sub->business_name. This message is sent via Scribble. https://scribble.ke/ A Citrus Labs Limited product.";



        $staff_sms = Staff::whereHas('proSubscription', function ($q) use ($content) {
            $q->where('shortcode', $content->BusinessShortCode)
                ->whereHas('staffTransactionRole', function ($q) {
                    $q->where('transaction_sms', 1);
                });
        })->get();

        $sms_helper = new SmsHelper();
        if ($content->TransactionType === 'Pay Bill') {
            $staff = Staff::where('unique_id', $content->BillRefNumber)->first();

            if ($staff) {
                $sms_helper->clientSendQuickSms($sub->sender_id, $client, $staff->phone_number, $sms_client, 0, $sub->id);
            } else {
                if ($sms_helper->clientSendQuickSms($sub->sender_id, $client, $content->BillRefNumber, $sms_customer, 10, $sub->id) == 'invalid') {

                    if (count($staff_sms) > 0) {
                        foreach ($staff_sms as $s) {
                            $sms_helper->clientSendQuickSms($sub->sender_id, $client, $s->phone_number, $sms_client, 0, $sub->id);
                        }
                    } else {
                        $sms_helper->clientSendQuickSms($sub->sender_id, $client, $sub->phone_number, $sms_client, 0, $sub->id);
                    }
                } else {
                    if (count($staff_sms) > 0) {
                        foreach ($staff_sms as $s) {
                            $sms_helper->clientSendQuickSms($sub->sender_id, $client, $s->phone_number, $sms_client, 0, $sub->id);
                        }
                    } else {
                        $sms_helper->clientSendQuickSms($sub->sender_id, $client, $sub->phone_number, $sms_client, 0, $sub->id);
                    }
                }
            }
        } else {
            if (count($staff_sms) > 0) {
                foreach ($staff_sms as $s) {
                    $sms_helper->clientSendQuickSms($sub->sender_id, $client, $s->phone_number, $sms_client, 0, $sub->id);
                }
            } else {
                $sms_helper->clientSendQuickSms($sub->sender_id, $client, $sub->phone_number, $sms_client, 0, $sub->id);
            }
        }
        return ['all sent'];
    }
}
