<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class SenderIdManage extends Model
{
    protected $table = 'sys_sender_id_management';
    protected $fillable = ['cl_id', 'sender_id', 'status'];
    protected $casts = [
        'cl_id' => 'array'
    ];

    public function SenderIdFile()
    {
        return $this->hasMany(SenderIdFiles::class, 'sender_id');
    }

    public function client()
    {
        return $this->belongsTo(Client::class, 'cl_id');
    }
}