<?php

namespace App\Http\Controllers;

use App\Client;
use App\InvoiceItems;
use App\Invoices;
use App\MassInvoices;
use App\Mail\SendInvoice;
use App\PaymentGateways;
use Illuminate\Support\Facades\Auth;
use PDF;
use App\DataTables\InvoicesDataTable;

class ClientInvoiceController extends Controller
{
    public function __construct()
    {
        $this->middleware('client');
    }

    //======================================================================
    // allInvoices Function Start Here
    //======================================================================
    public function allInvoices(InvoicesDataTable $dataTable)
    {

        return $dataTable->with('cl_id', auth('client')->user()->id)->render('client.all-invoices');
    }


    //generate Upaid Invoices
    public function generateUpaidInvoices()
    {

        $invoices = Invoices::where([
            ['cl_id', Auth::guard('client')->user()->id],
            ['status', 'Unpaid'],
            ['duedate', '>=', date('Y-m-d')]
        ])->get();
        $client = Client::find(Auth::guard('client')->user()->id);
        if ($invoices->count() == 0) {
            return back()->withErrors(['No unpaid invoices found whose due date is equal or greater than today.']);
        }

        $invoice = new PaymentInvoiceController();
        $invoice_no = $invoice->generateInvoice($client, 'Mass', [], $invoices);




        return redirect('/user/invoices/mass/' . $invoice_no)->with('You can find this invoice in your email. Please remember it is due today.');
    }

    public function unpaidInvoices($mass_invoice_no)
    {


        $check_mass = MassInvoices::where('mass_invoice_no', $mass_invoice_no);

        if ($check_mass->count() == 0) {
            return redirect('/user/invoices/all')->withErrors(['The invoice does not exist']);
        }
        $inv = $check_mass->first();
        $invoices = Invoices::where([
            ['cl_id', Auth::guard('client')->user()->id],
            ['mass_invoice_no', $mass_invoice_no],
        ])->get();
        $client = Client::find($inv->cl_id);

        $inv_related = [];

        foreach ($invoices as  $v) {
            $inv_related[] = $v->invoice_no;
        }

        $items = InvoiceItems::whereIn('invoice_no', $inv_related)->get();

        return view('client.mass-invoices', compact('inv', 'invoices', 'items', 'client'));
    }

    //======================================================================
    // recurringInvoices Function Start Here
    //======================================================================
    public function recurringInvoices()
    {

        $invoices = Invoices::where('cl_id', Auth::guard('client')->user()->id)->where('recurring', '!=', '0')->orderBy('updated_at', 'DESC')->get();
        return view('client.all-invoices', compact('invoices'));
    }


    //======================================================================
    // viewInvoice Function Start Here
    //======================================================================
    public function viewInvoice($id)
    {

        $inv = Invoices::where('cl_id', Auth::guard('client')->user()->id)->where('invoice_no', $id)->first();
        if ($inv) {
            $items = InvoiceItems::where('invoice_no', $id)->get();
            $client = Client::find($inv->cl_id);
            return view('client.view-invoice', compact('client', 'inv', 'items'));
        } else {
            return redirect('user/invoices/all')->withErrors([
                'message' => language_data('Invoice not found', Auth::guard('client')->user()->lan_id),
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
                'message' => language_data('Invoice not found', Auth::guard('client')->user()->lan_id),
                'message_important' => true
            ]);
        }
    }

    //======================================================================
    // printView Function Start Here
    //======================================================================
    public function printView($id)
    {
        $inv = Invoices::where('cl_id', Auth::guard('client')->user()->id)->where('invoice_no', $id)->first();

        if ($inv) {
            $client    = Client::where('status', 'Active')->find($inv->cl_id);
            $pdf = PDF::loadView('payments.pdf-invoice', compact('client', 'inv'));
            return $pdf->stream();
        } else {
            return redirect('user/invoices/all')->with([
                'message' => language_data('Invoice not found', Auth::guard('client')->user()->lan_id),
                'message_important' => true
            ]);
        }
    }

    //======================================================================
    // downloadPdf Function Start Here
    //======================================================================
    public function downloadPdf($id)
    {
        $inv = Invoices::where('cl_id', Auth::guard('client')->user()->id)->where('invoice_no', $id)->first();

        if ($inv) {
            $client    = Client::where('status', 'Active')->find($inv->cl_id);


            $items = InvoiceItems::where('invoice_no', $inv->invoice_no)->get();
            $file_name       = 'invoice-' . $inv->invoice_no . '.pdf';
            $pdf = PDF::loadView('payments.pdf-invoice', compact('client', 'inv', 'items'));
            return $pdf->download('invoice-' . $inv->invoice_no . '.pdf');
        } else {
            return redirect('user/invoices/all')->with([
                'message' => language_data('Invoice not found', Auth::guard('client')->user()->lan_id),
                'message_important' => true
            ]);
        }
    }

    public function massDownloadPdf($mass_invoice_no)
    {
        $inv = MassInvoices::where('cl_id', Auth::guard('client')->user()->id)->where('mass_invoice_no', $mass_invoice_no)->first();

        if ($inv) {
            $invoices = Invoices::where([
                ['cl_id', Auth::guard('client')->user()->id],
                ['mass_invoice_no', $mass_invoice_no],
            ])->get();
            $client = Client::find($inv->cl_id);
            $inv_related = [];

            foreach ($invoices as  $v) {
                $inv_related[] = $v->invoice_no;
            }
            $items = InvoiceItems::whereIn('invoice_no', $inv_related)->get();
            $file_name       = 'invoice-' . $inv->mass_invoice_no . '.pdf';
            $pdf = PDF::loadView('payments.pdf-invoice', compact('client', 'inv', 'items'));
            return $pdf->download('invoice-' . $inv->mass_invoice_no . '.pdf');
        } else {
            return redirect('user/invoices/all')->with([
                'message' => language_data('Invoice not found', Auth::guard('client')->user()->lan_id),
                'message_important' => true
            ]);
        }
    }
}
