<?php

namespace App\Http\Controllers\Team;

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
        $this->middleware('team');
    }

    //======================================================================
    // allInvoices Function Start Here
    //======================================================================
    public function allReceipts(ReceiptsDataTable $dataTable)
    {

        return $dataTable->with('cl_id', auth('team')->user()->cl_id)->render('client.all-receipts');
    }



    //======================================================================
    // viewInvoice Function Start Here
    //======================================================================
    public function viewReceipt($receipt_no)
    {
        $receipt = Receipts::where('cl_id', auth('team')->user()->cl_id)->where('receipt_no', $receipt_no)->first();
        if ($receipt) {
            $items = InvoiceItems::whereHas('invoice', function ($q) use ($receipt) {
                $q->where('invoice_no', $receipt->invoice_no);
            })->get();
            return view('client.view-receipt', compact('receipt', 'items'));
        } else {
            return redirect('user/receipts/all')->with([
                'message' => 'Invoice not found',
                'message_important' => true
            ]);
        }
    }


    //     //======================================================================
    //     // downloadPdf Function Start Here
    //     //======================================================================
    public function downloadPdf($receipt_no)
    {

        $receipt = Receipts::where('cl_id', auth('team')->user()->cl_id)->where('receipt_no', $receipt_no)->first();
        if ($receipt) {
            $items = InvoiceItems::whereHas('invoice', function ($q) use ($receipt) {
                $q->where('invoice_no', $receipt->invoice_no);
            })->get();
            $pdf = PDF::loadView('payments.pdf-receipt', compact('receipt', 'items'));
            return $pdf->download('receipt-' . $receipt->receipt_no . '.pdf');
        } else {
            return redirect('user/receipts/all')->with([
                'message' => 'Receipt not found',
                'message_important' => true
            ]);
        }
    }
}
