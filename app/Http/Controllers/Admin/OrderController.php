<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        $orders = \App\Models\Order::with(['user', 'service'])->latest()->paginate(20);
        return view('admin.orders.index', compact('orders'));
    }
}
