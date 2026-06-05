<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class ProSubscriptionFile extends Model
{
    use HasFactory;

    public function proSubscription()
    {
        return $this->belongsTo(ProSubscription::class);
    }

    public function visitor()
    {
        return $this->hasMany(visitor::class);
    }
}
