<?php

namespace App\Http\Controllers;

use App\Client;
use App\EmailTemplates;
use App\Mail\CreateTicket;
use App\Mail\ReplyTicket;
use App\SupportDepartments;
use App\SupportTicketFiles;
use App\SupportTickets;
use App\SupportTicketsReplies;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\SMSPricePlan;
use Log;
use BinaryCats\Sku\Concerns\SkuGenerator;
use App\Jobs\SendBulkSMS;
use App\Helpers\SMS;
use App\Models\ProSmsNotSent;
use App\Models\ProSubscription;
use Illuminate\Support\Carbon;
use App\Helpers\SmsHelper;
use AfricasTalking\SDK\AfricasTalking;

class TestController extends Controller
{
    public function test()
    {


        $consumer_key = "BpE8eUYPnYsgP8n22k6AeldEriZGwNAO";
        $consumer_secret = "YmvH9gnUBGO5pKvr";
        $url = 'https://api.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials';
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        $credentials = base64_encode($consumer_key . ':' . $consumer_secret);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Authorization: Basic ' . $credentials)); //setting a custom header
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

        $curl_response = curl_exec($curl);

        $token = json_decode($curl_response)->access_token;
        echo $token;
        $url = "https://api.safaricom.co.ke/mpesa/c2b/v2/registerurl";

        $curl_post_data = array(
            "ShortCode" => 4107547,
            "ResponseType" => "Completed",
            "ConfirmationURL" => "https://api.scribble.ke/client/transactions/daraja/confirmation",
            "ValidationURL" => "https://api.scribble.ke/client/transactions/daraja/validation",
        );

        $data_string = json_encode($curl_post_data);

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type:application/json', 'Authorization:Bearer ' . $token)); //setting custom header
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($curl, CURLOPT_HEADER, false);
        $curl_response = curl_exec($curl);
        echo $curl_response;
    }
}
