<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
class HomeVideoGallary extends Model
{
    use HasFactory;
      protected $table = 'home_video_gallaries';
    
    protected $fillable = [
          'video'
    ];
   public static function create_data($request)
    {
        return self::create($request->all());
    }

    public static function update_data($request, $videoGallary)
    {
        $videoGallary->update($request->all());
        return $videoGallary;
    }
}
