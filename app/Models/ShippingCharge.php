<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
class ShippingCharge extends Model
{
    use HasFactory;
  protected $table = 'shipping_charge';
   
   protected $fillable = ['location_type', 'min_weight', 'max_weight', 'charge'];
 
}
