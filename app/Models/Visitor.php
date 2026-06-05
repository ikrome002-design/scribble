<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Visitor extends Model
{
    use HasFactory;

    public function checkedInBy()
    {
        return $this->belongsTo(Staff::class, 'checked_in_by');
    }
    public function checkedOutBy()
    {
        return $this->belongsTo(Staff::class, 'checked_out_by');
    }
    public function editedBy()
    {
        return $this->belongsTo(Staff::class, 'edited_by');
    }
    public function visitorBusiness()
    {
        return $this->belongsTo(VisitorBusiness::class);
    }
    public function proSubscription()
    {
        return $this->belongsTo(ProSubscription::class);
    }
}
