<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StaffVisitorRole extends Model
{
    use HasFactory;

    protected $fillable = ['pro_subscription_id', 'staff_id', 'add', 'edit', 'delete', 'view', 'assign_roles'];
    public function proSubscription()
    {
        return $this->belongsTo(ProSubscription::class);
    }

    public function staff()
    {
        return $this->belongsTo(Staff::class);
    }
}