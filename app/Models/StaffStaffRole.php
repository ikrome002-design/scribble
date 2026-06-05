<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StaffStaffRole extends Model
{
    use HasFactory;
    protected $fillable = ['pro_subscription_id', 'staff_id', 'add', 'edit', 'delete', 'view', 'otp', 'assign_roles'];
    public function proSubcription()
    {
        return $this->belongsTo(ProSubscription::class);
    }
    public function staff()
    {
        return $this->belongsTo(Staff::class);
    }
}