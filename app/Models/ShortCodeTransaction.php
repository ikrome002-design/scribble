<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ShortcodeTransaction extends Model
{
    use HasFactory;

    public function proSubscription()
    {
        return  $this->belongsTo(ProSubscription::class, 'shortcode', 'shortcode');
    }
    public function staff()
    {
        return  $this->belongsTo(staff::class, 'bill_ref_number');
    }
}
