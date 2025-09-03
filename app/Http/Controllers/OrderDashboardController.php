<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use Carbon\Carbon;
class OrderDashboardController extends Controller
{
    //
    public function index()
    {
        // Today's Orders
        $todayOrders = Order::whereDate('created_at', Carbon::today())->count();
        
        // This Week's Orders
        $weekOrders = Order::whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->count();
        
        // This Month's Orders
        $monthOrders = Order::whereMonth('created_at', Carbon::now()->month)->count();
        
        // Till Date Orders
        $totalOrders = Order::count();
        
        // Pending Orders
        $pendingOrders = Order::where('delivery_status', 'pending')->count();

    
        return view('orders.dashboard', compact(
            'todayOrders', 
            'weekOrders', 
            'monthOrders', 
            'totalOrders', 
            'pendingOrders' 
           
        ));
    }
}
