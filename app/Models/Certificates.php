<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
class Certificates extends Model
{
    use HasFactory;
      protected $table = 'certificates';
    
    protected $fillable = [
          'name','image'
    ];
    public static function create_data($request)
    {    
        self::create($request->all());
    }
    public static function update_data($request)
    {
        $brand->update($request->all());
    }
}
