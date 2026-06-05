<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeamMembersAction extends Model
{
    use HasFactory;
    public function teamSubscription()
    {
        return $this->belongsTo(TeamSubscription::class);
    }
}
