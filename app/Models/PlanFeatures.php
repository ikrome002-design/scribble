<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PlanFeatures extends Model
{
    protected $table = 'plan_features';
    public function package()
    {
        $this->belongsTo(Plan::class);
    }
}