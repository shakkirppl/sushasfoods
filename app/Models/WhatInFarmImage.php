<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WhatInFarmImage extends Model
{
    protected $fillable = [
        'what_in_farm_id',
        'image',
    ];

    public function farm()
    {
        return $this->belongsTo(WhatInFarms::class, 'what_in_farm_id');
    }
}
