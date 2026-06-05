<?php

namespace App\Models;

use App\Client;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use BinaryCats\Sku\HasSku;
use BinaryCats\Sku\Concerns\SkuOptions;

class Staff extends Authenticatable
{
    use HasFactory, Notifiable, HasSku;


    public function client()
    {
        return   $this->belongsTo(Client::class, 'cl_id');
    }
    public function proSubscription()
    {
        return  $this->belongsTo(ProSubscription::class);
    }

    public function staff()
    {
        return  $this->belongsTo(Staff::class);
    }

    public function staffStaffRole()
    {
        return  $this->hasMany(StaffStaffRole::class);
    }

    public function staffTransactionRole()
    {
        return  $this->hasMany(StaffTransactionRole::class);
    }

    public function staffVisitorsRole()
    {
        return  $this->hasMany(StaffVisitorRole::class);
    }

    public function skuOptions(): SkuOptions
    {
        return SkuOptions::make()
            ->from(['label', 'email'])
            ->target('unique_id')
            ->using('-')
            ->forceUnique(true)
            ->generateOnCreate(true)
            ->refreshOnUpdate(false);
    }

    public function checkedInBy()
    {
        return $this->hasMany(Visitor::class, 'checked_in_by');
    }
    public function checkedOutBy()
    {
        return $this->hasMany(Visitor::class, 'checked_out_by');
    }
    public function editedBy()
    {
        return$this->hasMany(Visitor::class, 'edited_by');
    }
}