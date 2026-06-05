<?php

namespace App\Models;

use App\Client;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use BinaryCats\Sku\HasSku;
use BinaryCats\Sku\Concerns\SkuOptions;

class TeamMember extends Authenticatable
{
    use HasFactory, Notifiable, HasSku;


    public function client()
    {
        return   $this->belongsTo(Client::class, 'cl_id');
    }

    public function teamPlan()
    {
        return  $this->belongsTo(TeamPlan::class);
    }

    public function skuOptions(): SkuOptions
    {
        return SkuOptions::make()
            ->from(['label', 'email'])
            ->target('unique_id')
            ->using('-')
            ->forceUnique(true)
            ->generateOnCreate(true)
            ->refreshOnUpdate(false);
    }
}
