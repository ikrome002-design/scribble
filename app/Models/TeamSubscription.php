<?php

namespace App\Models;

use App\Client;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeamSubscription extends Model
{
    use HasFactory;

    public function client()
    {
        return $this->belongsTo(Client::class, 'cl_id');
    }

    public function teamPlan()
    {
        return $this->belongsTo(TeamPlan::class);
    }

    public function teamMembersAction()
    {
        return $this->hasMany(TeamMembersAction::class);
    }
}
