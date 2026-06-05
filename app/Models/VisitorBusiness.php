<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VisitorBusiness extends Model
{
    use HasFactory;
    public function proSubscription()
    {
        return $this->belongsTo(ProSubscription::class);
    }
    public function visitor()
    {
        return $this->hasMany(Visitor::class);
    }
}
