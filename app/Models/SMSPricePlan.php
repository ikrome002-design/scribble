<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SMSPricePlan extends Model
{
    protected $table = 'sys_sms_price_plan';

    public function clientGroup()
    {
        return $this->belongsTo(ClientGroups::class, 'client_group_id');
    }
    public function plan()
    {
        return $this->belongsTo(Plan::class, 'plan_id');
    }
}
