<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
class InvoiceNumber extends Model
{
    use HasFactory;
    public static function updateinvoiceNumber($bill_mode,$store_id)
    {
        $invoice_no =  self::where('bill_mode',$bill_mode)->where('store_id',$store_id)->first();
        $invoice_no->bill_no = $invoice_no->bill_no+1;
        $invoice_no->save();
     
    }
    public static function ReturnInvoice($bill_mode,$store_id)
    {
    	$invoice_no =  self::where('bill_mode',$bill_mode)->where('store_id',$store_id)->first();
        $bill_no=$invoice_no->bill_no+1;
        $code='';
        if($bill_no<10)
        {$code='000';}
        elseif($bill_no<100)
        {$code='00';}
        elseif($bill_no<1000)
        {$code='0';}
        else{$code='';}
    	return $invoice_no->bill_prefix.$code.$bill_no;
    }
}
