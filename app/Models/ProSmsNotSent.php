<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProSmsNotSent extends Model
{
    use HasFactory;

    public function proSubscription()
    {
        return $this->belongsTo(ProSubscription::class);
    }
}
