<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
class Review extends Model
{
    use HasFactory;
     protected $fillable = [
        'in_date',
        'in_time',
        'product_id',
        'user_id',
        'store_id',
        'review',
        'start_ratings',
        'name',
        'status',
    ];
    public function product()
    {
        return $this->hasMany(Product::class,'id','product_id');
    }
    public function stores()
    {
    return $this->hasMany(Stores::class,'id','store_id');
    }
    public function user()
    {
    return $this->hasMany(User::class,'id','user_id');
    }
}
