<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
class Categories extends Model
{
    use HasFactory;
      protected $table = 'categories';
    
    protected $fillable = [
          'name','image','tagline','description','terms'
    ];
    public function products()
    {
        return $this->hasMany(Product::class, 'category_id');
    }
    public static function create_data($request)
    {    
        self::create($request->all());
    }
    public static function update_data($id, $request)
    {
        $category = self::findOrFail($id);
        $category->update($request->all());
        return $category;
    }
}
