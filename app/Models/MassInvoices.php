<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Client;
use App\Invoices;

class MassInvoices extends Model
{
    protected $table = 'mass_invoices';
    protected $fillable = [
        'mass_invoice_no', 'quantity', 'sms_limit', 'amount', 'discount', 'tax', 'description',
        'cl_id', 'created_by', 'created', 'duedate', 'datepaid', 'subtotal', 'total', 'status', 'pmethod',  'bill_created', 'note',
    ];

    public function  client()
    {
        return $this->belongsTo(Client::class, 'cl_id');
    }


    public function Invoices()
    {
        return $this->hasMany(Invoices::class, 'mass_invoice_no', 'mass_invoice_no');
    }
}
