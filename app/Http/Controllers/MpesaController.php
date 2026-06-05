<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\MpesaStkPush;
use App\MpesaTranscationStatus;
use Mpesa;
use Log;
use Exception;
use App\Http\Controllers\PaymentInvoiceController;

class MpesaController extends Controller
{
    public $BusinessShortCode;
    public $LipaNaMpesaPasskey;
    public $Environment;
    public $Initiator;
    public $SecurityCredentials;
    public $ResultURL;
    public $TimeoutURL;
    public $CallBackURL;
    public $StkShortCode;

    public function __construct()
    {
        $this->BusinessShortCode = env('MPESA_BUSINESS_SHORT_CODE');
        $this->LipaNaMpesaPasskey = env('LIPA_NA_MPESA_PASS_KEY');
        $this->Environment = env('MPESA_ENV');
        $this->Initiator = env('MPESA_INITIATOR');
        $this->SecurityCredentials = env('MPESA_SECURITY_CREDENTIAL');
        $this->ResultURL = env('MPESA_RESULT_URL');
        $this->TimeoutURL = env('MPESA_TIMEOUT_URL');
        $this->CallBackURL = env('MPESA_CALLBACK_URL');
        $this->StkShortCode = env('MPESA_STK_SHORT_CODE');
    }

    //for stk push
    public function stkSimulation($invoice_no, $cl_id, $amount, $PhoneNumber, $description, $type)
    {


        $PhoneNumber = (substr($PhoneNumber, 0, 1) == "+") ? str_replace("+", "", $PhoneNumber) : $PhoneNumber;
        $PhoneNumber = (substr($PhoneNumber, 0, 1) == "0") ? preg_replace("/^0/", "254", $PhoneNumber) : $PhoneNumber;
        $PhoneNumber = strlen($PhoneNumber) == 9 ? "254{$PhoneNumber}" : $PhoneNumber;

        $mpesa = new \Safaricom\Mpesa\Mpesa();
        $Amount = round($amount);
        $PartyA = $PhoneNumber;
        $PartyB = $this->StkShortCode;
        $AccountReference = $invoice_no;
        $TransactionDesc = $description;
        $Remarks = "Thank for paying!";
        $BusinessShortCode  = $this->StkShortCode;
        $LipaNaMpesaPasskey = $this->LipaNaMpesaPasskey;
        $TransactionType = 'CustomerPayBillOnline';
        $CallBackURL        = $this->CallBackURL;

        try {
            $stk = $mpesa->STKPushSimulation(
                $BusinessShortCode,
                $LipaNaMpesaPasskey,
                $TransactionType,
                $Amount,
                $PartyA,
                $PartyB,
                $PhoneNumber,
                $CallBackURL,
                $AccountReference,
                $TransactionDesc,
                $Remarks
            );

            $result = json_decode(json_encode(json_decode($stk)), true);

            if (isset($result['errorCode'])) {
                return ['error' => $result['errorMessage']];
            }

            $stkData = new MpesaStkPush();
            $stkData->phone_number = $PhoneNumber;
            $stkData->checkout_request_id = $result['CheckoutRequestID'];
            $stkData->merchant_id = $result['MerchantRequestID'];
            $stkData->invoice_no = $invoice_no;
            $stkData->type = $type;
            $stkData->cl_id = $cl_id;
            $stkData->save();
            return true;
        } catch (Exception $e) {
            return ['error' => 'There was error. Please use mpesa paybill.'];
        }
    }



    // for check transaction status query
    public function transactionStatusRequest($content)
    {

        $mpesa = new \Safaricom\Mpesa\Mpesa();


        $Initiator  =   $this->Initiator;
        $SecurityCredential = $this->SecurityCredentials;
        $CommandID = 'TransactionStatusQuery';
        $TransactionID  = $content->TransID;
        $PartyA       = $this->BusinessShortCode;
        $IdentifierType = 4;
        $ResultURL  =  $this->ResultURL;
        $QueueTimeOutURL = $this->TimeoutURL;
        $Remarks = 'Payment for invoice';
        $Occasion = '';
        $trasactionStatus = $mpesa->transactionStatus(
            $Initiator,
            $SecurityCredential,
            $CommandID,
            $TransactionID,
            $PartyA,
            $IdentifierType,
            $ResultURL,
            $QueueTimeOutURL,
            $Remarks,
            $Occasion
        );

        $result = json_decode(json_encode(json_decode($trasactionStatus)), true);

        if ($result['ResponseCode'] !== "0") {
            return ['error' => 'There was error. Please use another procedure'];
        }
        $mpesaStatus = new MpesaTranscationStatus();
        $mpesaStatus->OriginatorConversationID = $result['OriginatorConversationID'];
        $mpesaStatus->ConversationID = $result['ConversationID'];
        $mpesaStatus->save();
        return true;
    }
}
