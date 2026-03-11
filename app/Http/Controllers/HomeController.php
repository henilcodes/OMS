<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Customer;
use App\Models\Order;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function index()
    {
        $totalProducts = Product::count();
        $totalCustomers = Customer::count();
        $totalSales = Order::whereIn('status', ['completed','pending'])->sum('total_amount');
        $orderStatuses = Order::select('status', DB::raw('count(*) as total'))->groupBy('status')->get();
        return view('home', compact('totalProducts', 'totalCustomers', 'totalSales', 'orderStatuses'));
    }
}
