<?php

namespace App\Http\Controllers;

use App\AppConfig;
use App\BlackListContact;
use App\Campaigns;
use App\CampaignSubscriptionList;
use App\Classes\Permission;
use App\Classes\PhoneNumber;
use App\Client;
use App\ClientGroups;
use App\ContactList;
use App\CustomSMSGateways;
use App\ImportPhoneNumber;
use App\IntCountryCodes;
use App\Jobs\SendBulkMMS;
use App\Jobs\SendBulkSMS;
use App\Jobs\SendBulkVoice;
use App\Keywords;
use App\Operator;
use App\RecurringSMS;
use App\RecurringSMSContacts;
use App\ScheduleSMS;
use App\SenderIdManage;
use App\SMSBundles;
use App\SMSGatewayCredential;
use App\SMSGateways;
use App\SMSHistory;
use App\SMSInbox;
use App\SMSPlanFeature;
use App\SMSPricePlan;
use App\SMSTemplates;
use App\TwoWayCommunication;
use App\Models\PlanName;
use App\Models\AirtimeBundle;
use App\Models\BuyPlan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use libphonenumber\PhoneNumberFormat;
use libphonenumber\PhoneNumberToCarrierMapper;
use libphonenumber\PhoneNumberUtil;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\Datatables\Datatables;
use App\Invoices;
use App\Mail\SendInvoice;
use Carbon\Carbon;
use PDF;

class ClientPLanController extends Controller
{
    /**
     *  ClientPLanController Controller constructor.
     */
    public function __construct()
    {
        $this->middleware('client');
    }

    //======================================================================
    // pricePlan Function Start Here
    //======================================================================
    //sms price plan view
    public function allPricePlans()
    {
        $price_plan = SMSPricePlan::all();
        $client = Client::find(Auth::guard('client')->user()->id);

        return view('client.all-price-plans', compact('price_plan', 'client'));
    }
    public function ViewPlan($id)
    {
        $sms_plan = SMSPricePlan::find($id);
        if (!$sms_plan) {
            return redirect('user/price-plans/all')->withErrors('The plan was not found');
        }
        $client = Client::find(Auth::guard('client')->user()->id);
        return view('client.view-plan', compact('sms_plan', 'client'));
    }

    //change plan 
    public function changePlan($id)
    {
        $plan = SMSPricePlan::find($id);
        if (!$plan) {
            return redirect('user/price-plans/all')->withErrors('The plan was not found');
        }


        if ($plan->price > 0) {

            $invoice = new PaymentInvoiceController();
            $items[] = ['plan' => $plan, 'quantity' => 1, 'plan_id' => $plan->id];
            $invoice_no = $invoice->generateInvoice('', 'Single', $items, $invoices = null);

            return redirect('user/invoices/view/' . $invoice_no)->with('message', 'The plan was successfully changed. Please pay for this invoice for it to be effective.');
        }

        return redirect('user/price-plans/all')->with('message', 'The plan was successfully changes');
    }


    //airtime bundle view
    public function AllAirtimeBundles()
    {
        $airtime_bundle = AirtimeBundle::all();
        $client = Client::find(Auth::guard('client')->user()->id);
        return view('client.all-artime-bundles', compact('airtime_bundle', 'client'));
    }

    public function BuyAirtimeBundle($id)
    {
        $plan = AirtimeBundle::find($id);
        if (!$plan) {
            return redirect('user/airtime-bundles/all')->withErrors('The airtime bundle  was not found');
        }
        $invoice = new PaymentInvoiceController();
        $invoice_no = $invoice->generateInvoice(Auth::guard('client')->user()->id, 'Single', 'Payment for Airtime Bundle', $plan);
        return redirect('user/invoices/view/' . $invoice_no)->with('message', 'Please pay for this invoice to receive sms airtime bundle.');
    }
}