<?php

namespace App\Models;

use App\Client;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProSubscription extends Model
{
    use HasFactory;

    public function client()
    {
        return $this->belongsTo(Client::class, 'cl_id');
    }

    public function visitorBusiness()
    {
        return $this->hasMany(VisitorBusiness::class);
    }


    public function shortcodeTransaction()
    {
        return  $this->hasMany(ShortcodeTransaction::class, 'shortcode', 'shortcode');
    }
    public function staff()
    {
        return  $this->hasMany(Staff::class);
    }
    public function staffStaffRole()
    {
        return  $this->hasMany(StaffStaffRole::class);
    }

    public function staffTransactionRole()
    {
        return  $this->hasMany(StaffTransactionRole::class);
    }

    public function staffVisitorRole()
    {
        return  $this->hasMany(StaffVisitorRole::class);
    }

    public function proSubscriptionFile()
    {
        return $this->hasMany(ProSubscriptionFile::class);
    }

    public function ProSmsNotSent()
    {
        return $this->hasMany(ProSmsNotSent::class);
    }
}
