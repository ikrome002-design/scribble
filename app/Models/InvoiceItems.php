<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use PayPal\Api\Invoice;

class InvoiceItems extends Model
{
    protected $table = 'sys_invoice_items';
    protected $fillable = [
        'invoice_no',
        'description', 'price',
        'quantity', 'amount', 'plan_id', 'pro_subscription_id',
    ];

    public function invoice()
    {
        return $this->belongsTo(Invoices::class, 'invoice_no', 'invoice_no');
    }
}
