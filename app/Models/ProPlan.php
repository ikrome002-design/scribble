<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\ClientGroups;
use App\Plan;

class ProPlan extends Model
{
    use HasFactory;

    public function clientGroup()
    {
        return $this->belongsTo(ClientGroups::class, 'client_group_id');
    }
    public function plan()
    {
        return $this->belongsTo(Plan::class, 'plan_id');
    }
}