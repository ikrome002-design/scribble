<?php

namespace App;

use App\Models\AirtimeBundle;
use App\Models\ProPlan;
use Illuminate\Database\Eloquent\Model;

class ClientGroups extends Model
{
    protected $table = 'sys_client_groups';
    protected $fillable = ['group_name', 'created_by', 'status'];

    public function smsPricePlan()
    {
        return $this->hasMany(SMSPricePlan::class, 'client_group_id');
    }

    public function airtimeBundle()
    {
        return $this->hasMany(AirtimeBundle::class, 'client_group_id');
    }
    public function proPlan()
    {
        return $this->hasMany(ProPlan::class, 'client_group_id');
    }
}