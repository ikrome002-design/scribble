<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StaffTransactionRole extends Model
{
    use HasFactory;
    protected $fillable = ['pro_subscription_id', 'last_24_hours', 'last_one_month', 'all', 'daily_summary', 'monthly_summary', 'all_summary', 'assign_roles'];
    public function proSubcription()
    {
        return $this->belongsTo(ProSubscription::class);
    }
    public function staff()
    {
        return $this->belongsTo(Staff::class);
    }
}