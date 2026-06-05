<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\MpesaTranscation;
use App\MpesaStkPush;
use App\MassInvoices;
use App\Invoices;
use Carbon\Carbon;
use App\Client;
use App\SMSTransaction;
use App\MpesaTranscationStatus;
use App\MpesaValidation;
use App\Http\Controllers\MpesaController;
use App\BuyPlan;


class MpesaResponseController extends Controller
{

    public function __construct()
    {
        $this->middleware('MpesaIps');
    }
    public function stkCallback(Request $request)
    {

        $content = json_decode($request->getContent());

        $resultCode = $content->Body->stkCallback->ResultCode;
        $resultDesc = $content->Body->stkCallback->ResultDesc;
        $merchantRequestID = $content->Body->stkCallback->MerchantRequestID;
        $checkoutRequestID = $content->Body->stkCallback->CheckoutRequestID;
        $resultCode = $content->Body->stkCallback->ResultCode;

        $stkCheck = MpesaStkPush::where([
            ['merchant_id', '=', $merchantRequestID],
            ['checkout_request_id', '=', $checkoutRequestID]
        ]);


        if ($resultCode != 0) {
            return 'The payment not completed';
        }
        if ($stkCheck->count() == 0) {
            return [
                'ResultCode' => 'C2B00012',
                'ResultDesc' => 'Rejected',
            ];
        }
        $Item = $content->Body->stkCallback->CallbackMetadata->Item;
        $itemData = array_column($Item, 'Value', 'Name');

        $invoiceData = $stkCheck->first();

        $amount = $itemData['Amount'];
        $TransID = $itemData['MpesaReceiptNumber'];
        $TransTime = Carbon::createFromFormat('YmdHis', $itemData['TransactionDate'])->format('Y-m-d H:i:s');
        $phoneNumber = $invoiceData->phone_number;

        $mpesa_transaction = new MpesaTranscation();
        $mpesa_transaction->trans_id = $TransID;
        $mpesa_transaction->invoice_no = $invoiceData->invoice_no;
        $mpesa_transaction->transaction_date = $TransTime;
        $mpesa_transaction->business_shortcode = env('MPESA_STK_SHORT_CODE');
        $mpesa_transaction->amount = $amount;
        $mpesa_transaction->phone_number = $phoneNumber;
        $mpesa_transaction->save();

        $payInv = new PaymentInvoiceController();

        $payInv->updateAfterPayment($invoiceData->invoice_no, $invoiceData->type);
        $payInv->generateReceipt($invoiceData->invoice_no, $invoiceData->type, $TransID);
        return [
            'ResultCode' => '0',
            'ResultDesc' => 'Accepted',
        ];
    }

    //transcation validation
    public function validation(Request $request)
    {
        $date_now = date("Y-m-d");
        $content = json_decode($request->getContent());
        $amount = $content->TransAmount;
        $invoice_no = $content->BillRefNumber;


        $invoice_mass = MassInvoices::where([
            ['mass_invoice_no', '=', $invoice_no],
            ['duedate', '>=', $date_now],
            ['total', '=', $amount],
            ['status', '!=', 'Paid']

        ]);

        $invoice_single = Invoices::where([
            ['invoice_no', '=', $invoice_no],
            ['total', '=', $amount],
            ['duedate', '>=', $date_now],
            ['status', '!=', 'Paid']
        ]);

        //reject if invoice does not exist;
        if ($invoice_single->count() == 0 && $invoice_mass->count() == 0) {
            return [
                'ResultCode' => 'C2B00012',
                'ResultDesc' => 'Rejected',
            ];
        }
        //check if one of invoice has been paid for mass invoices
        if ($invoice_mass->count() > 0) {
            $mass_invoice_no =  $invoice_mass->first()->mass_invoice_no;
            $invoices_check = Invoices::where([
                ['mass_invoice_no', '=', $mass_invoice_no],
                ['status', '=', 'Paid']
            ]);
            if ($invoices_check->count() > 0) {
                return [
                    'ResultCode' => 'C2B00012',
                    'ResultDesc' => 'Rejected',
                ];
            }
        }


        $checkTrans = MpesaTranscation::where('trans_id', $content->TransID)->orWhere('invoice_no', $invoice_no);
        if ($checkTrans->count() > 0) {
            return [
                'ResultCode' => 'C2B00012',
                'ResultDesc' => 'Rejected',
            ];
        }
        $firstname = isset($content->FirstName) ? $content->FirstName : '';
        $lastname = isset($content->LastName) ? $content->LastName : '';
        $middlename = isset($content->MiddleName) ? $content->MiddleName : '';

        // $ThirdPartyTransID = bin2hex(random_bytes(128));

        $mpesaStatus = new MpesaValidation();
        $mpesaStatus->transaction_type = $content->TransactionType;
        $mpesaStatus->business_shortcode     = $content->BusinessShortCode;
        // $mpesaStatus->third_party_id =      $ThirdPartyTransID;
        $mpesaStatus->transaction_type = $content->TransactionType;
        $mpesaStatus->trans_id = $content->TransID;
        $mpesaStatus->trans_time = $content->TransTime;
        $mpesaStatus->amount = $content->TransAmount;
        $mpesaStatus->bill_ref_number = $content->BillRefNumber;
        $mpesaStatus->balance = $content->OrgAccountBalance ?: null;
        $mpesaStatus->phone_number = $content->MSISDN;
        $mpesaStatus->name = $firstname . ' ' . $middlename . ' ' . $lastname;
        $mpesaStatus->amount = $content->TransAmount;
        $mpesaStatus->save();
        return [
            'ResultCode' => 0,
            'ResultDesc' => 'Accepted',
        ];
    }

    //confirmation
    public function confirmation(Request $request)
    {
        $content = json_decode($request->getContent());

        $checkTrans = MpesaTranscation::where('trans_id', $content->TransID);
        if ($checkTrans->count() > 0) {
            return [
                'ResultCode' => 'C2B00012',
                'ResultDesc' => 'Rejected',
            ];
        }

        $firstname = isset($content->FirstName) ? $content->FirstName : '';
        $lastname = isset($content->LastName) ? $content->LastName : '';
        $middlename = isset($content->MiddleName) ? $content->MiddleName : '';

        $mpesaTrans = new MpesaTranscation();
        $mpesaTrans->transaction_type = $content->TransactionType;
        $mpesaTrans->business_shortcode     = $content->BusinessShortCode;
        // $mpesaTrans->third_party_id = $content->ThirdPartyTransID;
        $mpesaTrans->transaction_type = $content->TransactionType;
        $mpesaTrans->trans_id = $content->TransID;
        $mpesaTrans->transaction_date = $content->TransTime;
        $mpesaTrans->amount = $content->TransAmount;
        $mpesaTrans->business_shortcode = $content->BusinessShortCode;
        $mpesaTrans->invoice_no = $content->BillRefNumber;
        $mpesaTrans->balance = $content->OrgAccountBalance ?: null;
        $mpesaTrans->phone_number = $content->MSISDN;
        $mpesaTrans->name = $firstname . ' ' . $middlename . ' ' . $lastname;
        $mpesaTrans->amount = $content->TransAmount;
        $mpesaTrans->save();


        $payInv = new PaymentInvoiceController();

        //checky type of invoice
        $invoice_mass = MassInvoices::where([
            ['mass_invoice_no', '=', $content->BillRefNumber],
            ['status', '!=', 'Paid']

        ]);
        $invoice_single = Invoices::where([
            ['invoice_no', '=', $content->BillRefNumber],
            ['status', '!=', 'Paid']
        ]);
        $invoice_no = null;
        if ($invoice_mass->count() > 0) {
            $invoice_no = $invoice_mass->get()->first()->mass_invoice_no;
        } else if ($invoice_single->count() > 0) {

            $invoice_no = $invoice_single->get()->first()->invoice_no;
        }


        if ($invoice_mass->count() > 0) {
            $payInv->updateAfterPayment($invoice_no, 'Mass');
            $payInv->generateReceipt($invoice_no, 'Mass', $content->TransID);
        } else if ($invoice_single->count() > 0) {
            $payInv->updateAfterPayment($invoice_no, 'Single');
            $payInv->generateReceipt($invoice_no, 'Single', $content->TransID);
        }


        return [
            'ResultCode' => '0',
            'ResultDesc' => 'Accepted',
        ];
    }

    //timeout
    public function timeout(Request $request)
    {
        return [
            'ResultCode' => 123245,
            'ResultDesc' => 'Reject',
        ];
    }
}
