<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Invoices extends Model
{
    protected $table = 'sys_invoices';

    public function client()
    {
        return $this->belongsTo(Client::class, 'cl_id');
    }
    public function items()
    {
        return $this->hasMany(InvoiceItems::class, 'invoice_no', 'invoice_no');
    }

    public function massInvoices()
    {
        return $this->belongsTo(MassInvoices::class, 'mass_invoice_no', 'mass_invoice_no');
    }
}
