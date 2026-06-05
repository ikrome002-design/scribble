<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\ClientGroups;
use App\Plan;

class TeamPlan extends Model
{
    use HasFactory;


    public function plan()
    {
        return $this->belongsTo(Plan::class, 'plan_id');
    }
}
