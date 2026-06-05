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

class ReceiptController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
    }

    //======================================================================
    // allInvoices Function Start Here
    //======================================================================
    public function allReceipts(ReceiptsDataTable $dataTable)
    {

        return $dataTable->render('admin.all-receipts');
    }


    //======================================================================
    // viewInvoice Function Start Here
    //======================================================================
    public function viewReceipt($id)
    {
        $receipt = Receipts::find($id);
        if ($receipt) {
            $items = InvoiceItems::whereHas('invoice', function ($q) use ($receipt) {
                $q->where('invoice_no', $receipt->invoice_no)
                    ->orWhereHas('massInvoices', function ($q) use ($receipt) {
                        $q->where('mass_invoice_no', $receipt->invoice_no);
                    });
            })->get();

            return view('admin.view-receipt', compact('receipt',  'items'));
        } else {
            return redirect('receipts/all')->with([
                'message' => 'Receipt not found',
                'message_important' => true
            ]);
        }
    }


    //     //======================================================================
    //     // downloadPdf Function Start Here
    //     //======================================================================
    public function downloadPdf($id)
    {

        $receipt = Receipts::find($id);
        if ($receipt) {
            $items = InvoiceItems::whereHas('invoice', function ($q) use ($receipt) {
                $q->where('invoice_no', $receipt->invoice_no)
                    ->orWhereHas('massInvoices', function ($q) use ($receipt) {
                        $q->where('mass_invoice_no', $receipt->invoice_no);
                    });
            })->get();

            $pdf = PDF::loadView('payments.pdf-receipt', compact('receipt', 'items'));
            return $pdf->download('receipt-' . $receipt->receipt_no . '.pdf');
        } else {
            return redirect('/receipts/all')->with([
                'message' => 'receipt not found',
                'message_important' => true
            ]);
        }
    }

    // delete receipt
    //======================================================================
    public function deleteReceipt($id)
    {
        $receipt = Receipts::find($id);

        if ($receipt) {
            $receipt->delete();
            return redirect('receipts/all')->with('message', 'Receipt successfully deleted');
        } else {
            return redirect('receipts/all')->with([
                'message' => 'Invoice not found',
                'message_important' => true
            ]);
        }
    }
}
