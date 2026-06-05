<?php

namespace App\Models;

use App\Plan;
use App\ClientGroups;

use Illuminate\Database\Eloquent\Model;

class AirtimeBundle extends Model
{
    protected $table = "sys_airtime_bundle";
    public function clientGroup()
    {
        return $this->belongsTo(ClientGroups::class, 'client_group_id');
    }
    public function plan()
    {
        return $this->belongsTo(Plan::class, 'plan_id');
    }
}