<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Order;

class OrderApiController extends Controller
{
    public function index()
    {
        $orders = Order::with(['customer', 'orderItems.product'])->latest()->get();
        return response()->json($orders);
    }
}
