<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class SenderIdFiles extends Model
{
    protected $table = 'sender_id_files';

    public function senderId()
    {
        return $this->belongsTo(SenderIdManage::class, 'sender_id');
    }
}