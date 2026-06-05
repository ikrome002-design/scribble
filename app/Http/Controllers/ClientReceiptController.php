<?php

namespace App\Http\Controllers;

use App\Client;
use App\InvoiceItems;
use App\Receipts;
use App\Invoices;
use App\MassInvoices;
use App\PaymentGateways;
use Illuminate\Support\Facades\Auth;
use Nexmo\Message\Callback\Receipt;
use PDF;
use phpDocumentor\Reflection\Types\Null_;
use App\DataTables\ReceiptsDataTable;

class ClientReceiptController extends Controller
{
    public function __construct()
    {
        $this->middleware('client');
    }

    //======================================================================
    // allInvoices Function Start Here
    //======================================================================
    public function allReceipts(ReceiptsDataTable $dataTable)
    {

        return $dataTable->with('cl_id', auth('client')->user()->id)->render('client.all-receipts');
    }



    //======================================================================
    // viewInvoice Function Start Here
    //======================================================================
    public function viewReceipt($receipt_no)
    {
        $receipt = Receipts::where('cl_id', Auth::guard('client')->user()->id)->where('receipt_no', $receipt_no)->first();
        if ($receipt) {
            $items = InvoiceItems::whereHas('invoice', function ($q) use ($receipt) {
                $q->where('invoice_no', $receipt->invoice_no)
                    ->orWhereHas('massInvoices', function ($q) use ($receipt) {
                        $q->where('mass_invoice_no', $receipt->invoice_no);
                    });
            })->get();
            return view('client.view-receipt', compact('receipt', 'items'));
        } else {
            return redirect('user/receipts/all')->with([
                'message' => language_data('Invoice not found', Auth::guard('client')->user()->lan_id),
                'message_important' => true
            ]);
        }
    }


    //     //======================================================================
    //     // downloadPdf Function Start Here
    //     //======================================================================
    public function downloadPdf($receipt_no)
    {

        $receipt = Receipts::where('cl_id', Auth::guard('client')->user()->id)->where('receipt_no', $receipt_no)->first();
        if ($receipt) {
            $items = InvoiceItems::whereHas('invoice', function ($q) use ($receipt) {
                $q->where('invoice_no', $receipt->invoice_no)
                    ->orwhereHas('massInvoices', function ($q) use ($receipt) {
                        $q->where('mass_invoice_no', $receipt->invoice_no);
                    });
            })->get();
            $pdf = PDF::loadView('payments.pdf-receipt', compact('receipt', 'items'));
            return $pdf->download('receipt-' . $receipt->receipt_no . '.pdf');
        } else {
            return redirect('user/receipts/all')->with([
                'message' => language_data('Receipt not found', Auth::guard('client')->user()->lan_id),
                'message_important' => true
            ]);
        }
    }
}
