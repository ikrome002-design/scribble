<?php

namespace App\Http\Controllers\Team;

use App\Client;
use App\InvoiceItems;
use App\Invoices;
use App\Mail\SendInvoice;
use App\PaymentGateways;
use Illuminate\Support\Facades\Auth;
use PDF;
use App\DataTables\InvoicesDataTable;

class ClientInvoiceController extends Controller
{
    public function __construct()
    {
        $this->middleware('team');
    }

    //======================================================================
    // allInvoices Function Start Here
    //======================================================================
    public function allInvoices(InvoicesDataTable $dataTable)
    {

        return $dataTable->with('cl_id', auth('team')->user()->cl_id)->render('client.all-invoices');
    }


    //generate Upaid Invoices
    public function generateUpaidInvoices()
    {
        //update invoices with wrong recurring date
        $client = Client::find(auth('team')->user()->cl_id);
        $date = date('Y-m-d');
        Invoices::whereHas('items', function ($q) use ($date, $client) {
            $q->where('pro_recurring_date', '!=', null)
                ->where(function ($q) use ($date, $client) {
                    $q->where('pro_recurring_date', '!=', $client->plan_recurring_date)
                        ->orWhere('pro_recurring_date', '<', $date);
                });
        })->update(['status' => 'Expired']);
        $invoices = Invoices::where('cl_id', auth('team')->user()->cl_id)
            ->where('status', 'Unpaid')
            ->orWhere('status', "Partially Paid");
        $client = Client::find(auth('team')->user()->cl_id);
        if ($invoices->count() == 0) {
            return back()->withErrors('No unpaid or partially paid invoices found.');
        }

        $invoice = new PaymentInvoiceController();
        $invoice_no = $invoice->generateInvoice($client, 'Mass', [], $invoices);

        return redirect('/user/invoices/mass/' . $invoice_no)->with('message', 'Please pay for the following invoice. You can find this invoice in your email.');
    }

    public function massInvoices($mass_invoice_no)
    {


        $invoices = Invoices::where('mass_invoice_no', $mass_invoice_no)->orderBy('duedate', 'ASC')->get();

        if (count($invoices) == 0) {
            return redirect('/user/invoices/all')->withErrors(['The invoice does not exist']);
        }
        $inv = $invoices->first();
        $client = Client::find(auth('team')->user()->cl_id);
        $items = InvoiceItems::whereHas('invoice', function ($q) use ($mass_invoice_no) {
            $q->where('mass_invoice_no', $mass_invoice_no);
        })->get();
        return view('client.mass-invoices', compact('mass_invoice_no', 'inv', 'invoices', 'items', 'client'));
    }

    //======================================================================
    // recurringInvoices Function Start Here
    //======================================================================
    public function recurringInvoices()
    {

        $invoices = Invoices::where('cl_id', auth('team')->user()->cl_id)->where('recurring', '!=', '0')->orderBy('updated_at', 'DESC')->get();
        return view('client.all-invoices', compact('invoices'));
    }


    //======================================================================
    // viewInvoice Function Start Here
    //======================================================================
    public function viewInvoice($id)
    {

        $inv = Invoices::where('cl_id', auth('team')->user()->cl_id)->where('invoice_no', $id)->first();
        if ($inv) {
            $items = InvoiceItems::where('invoice_no', $id)->get();
            $client = Client::find($inv->cl_id);
            return view('client.view-invoice', compact('client', 'inv', 'items'));
        } else {
            return redirect('user/invoices/all')->withErrors([
                'message' => 'Invoice not found',
                'message_important' => true
            ]);
        }
    }

    //======================================================================
    // clientIView Function Start Here
    //======================================================================
    public function clientIView($id)
    {
        $inv = Invoices::where('invoice_no', $id)->first();;
        if ($inv) {
            $client    = Client::where('status', 'Active')->find($inv->cl_id);
            return view('client.invoice-client-view', compact('client', 'inv'));
        } else {
            return redirect('user/invoices/all')->with([
                'message' => 'Invoice not found',
                'message_important' => true
            ]);
        }
    }

    //======================================================================
    // printView Function Start Here
    //======================================================================
    public function printView($id)
    {
        $inv = Invoices::where('cl_id', auth('team')->user()->cl_id)->where('invoice_no', $id)->first();

        if ($inv) {
            $client    = Client::where('status', 'Active')->find($inv->cl_id);
            $pdf = PDF::loadView('payments.pdf-invoice', compact('client', 'inv'));
            return $pdf->stream();
        } else {
            return redirect('user/invoices/all')->with([
                'message' => 'Invoice not found',
                'message_important' => true
            ]);
        }
    }

    //======================================================================
    // downloadPdf Function Start Here
    //======================================================================
    public function downloadPdf($id)
    {
        $inv = Invoices::where('cl_id', auth('team')->user()->cl_id)->where('invoice_no', $id)->first();

        if ($inv) {
            $client    = Client::where('status', 'Active')->find($inv->cl_id);


            $items = InvoiceItems::where('invoice_no', $inv->invoice_no)->get();
            $file_name       = 'invoice-' . $inv->invoice_no . '.pdf';
            $pdf = PDF::loadView('payments.pdf-invoice', compact('client', 'inv', 'items'));
            return $pdf->download('invoice-' . $inv->invoice_no . '.pdf');
        } else {
            return redirect('user/invoices/all')->with([
                'message' => 'Invoice not found',
                'message_important' => true
            ]);
        }
    }

    public function massDownloadPdf($mass_invoice_no)
    {
        $invoices = Invoices::where('mass_invoice_no', $mass_invoice_no)->orderBy('duedate', 'ASC')->get();

        if (count($invoices) == 0) {
            return redirect('/user/invoices/all')->withErrors(['The invoice does not exist']);
        }
        $inv = $invoices->first();
        $client = Client::find(auth('team')->user()->cl_id);
        $items = InvoiceItems::whereHas('invoice', function ($q) use ($mass_invoice_no) {
            $q->where('mass_invoice_no', $mass_invoice_no);
        })->get();
        $pdf = PDF::loadView('payments.pdf-invoice', compact('mass_invoice_no', 'inv', 'invoices', 'items', 'client'));
        return $pdf->download('invoice-' . $inv->invoice_no . '.pdf');
    }
}
