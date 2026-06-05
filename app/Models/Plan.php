<?php

namespace App;

use App\Models\AirtimeBundle;
use App\Models\ProPlan;
use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    protected $table = 'plans';
    public function planFeatures()
    {
        return $this->hasMany(PlanFeatures::class);
    }

    public function smsPricePlan()
    {
        return $this->hasMany(SMSPricePlan::class, 'plan_id');
    }
    public function Plan()
    {
        return $this->hasMany(Plan::class, 'plan_id');
    }
    public function airtimeBundle()
    {
        return $this->hasMany(AirtimeBundle::class, 'plan_id');
    }

    public function proPlan()
    {
        return $this->hasMany(ProPlan::class, 'plan_id');
    }
}