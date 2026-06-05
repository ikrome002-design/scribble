<?php

namespace App;

use App\Models\ProSubscription;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Client extends Authenticatable
{

    use Notifiable;
    protected $table = 'sys_clients';

    protected $fillable = ['groupid', 'parent', 'fname', 'lname', 'company', 'website', 'email', 'username', 'password', 'address1', 'address2', 'state', 'city', 'postcode', 'country', 'phone', 'image', 'datecreated', 'sms_limit', 'api_access', 'api_key', 'api_gateway', 'online', 'status', 'reseller', 'sms_gateway', 'lastlogin', 'pwresetkey', 'pwresetexpiry', 'emailnotify', 'menu_open', 'lan_id', 'remember_token'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'online', 'lastlogin', 'pwresetkey', 'pwresetexpiry'
    ];

    /**
     * @var array
     */

    protected $casts = [
        'sms_gateway' => 'array'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     *
     */
    public function get_sms_gateway()
    {
        return $this->hasOne('App\SMSGateways', 'id', 'sms_gateway');
    }
    public function plan()
    {
        return $this->belongsTo(Plan::class, 'plan_id');
    }
    public function receipts()
    {
        return $this->hasMany(Receipt::class, 'cl_id');
    }
    public function invoices()
    {
        return $this->hasMany(Invoices::class, 'cl_id');
    }

    public function senderId()
    {
        return $this->hasMany(SenderIdManage::class, 'cl_id');
    }

    public function staff()
    {
        return $this->hasMany(Staff::class, 'cl_id');
    }

    public function proSubscription()
    {
        return $this->hasMany(ProSubscription::class, 'cl_id');
    }
    public function getEmailAttribute($value)
    {
        return strtolower($value);
    }

    public static function boot()
    {
        parent::boot();
        self::updating(function ($model) {
            if ($model->sms_limit < 0) {
                $model->sms_limit = 0;
            }
        });
    }
}
