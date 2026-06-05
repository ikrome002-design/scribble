<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Client;
use App\Invoices;
use App\SMSPricePlan;

class Receipts extends Model
{
    protected $table = 'receipts';
    protected $fillable = [
        'invoice_no', 'sms_limit', 'mpesa_ref', 'cl_id', 'plan_id', 'description', 'datepaid',
        'amount',
        'subtotal',
        'discount',
        'tax',
        'total',
        'type',
        'pmethod',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class, 'cl_id', 'id');
    }
    public function plan()
    {
        return $this->belongsTo(Plan::class, 'plan_id', 'id');
    }
    public function invoice()
    {
        return $this->belongsToMany(Invoices::class, 'sys_invoices', 'main_invoice_no', 'main_invoice_no');
    }
}