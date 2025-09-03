<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
class WhatInFarms extends Model
{
    use HasFactory;
      protected $table = 'what_do_in_farm';
    
    protected $fillable = [
          'name','image','video','description','description1','description2','description3','description4','description5','category'
    ];
    public static function create_data($request)
    {
        return self::create($request->all());
    }

    public static function update_data($id, $request)
    {
        $farm = self::findOrFail($id);
        $farm->update($request->all());
        return $farm;
    }
    public function images()
{
    return $this->hasMany(WhatInFarmImage::class, 'what_in_farm_id');
}
}
