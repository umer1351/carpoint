<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;

use App\Models\Order;
use App\Models\Badge;
use Illuminate\Http\Request;

use DB;
Use Auth;

class AdminOrdersController extends Controller
{
    public function __construct() {
        $this->middleware('auth.admin:admin');
    }

    public function index()
    {
        $orders = Order::with('buyer', 'seller', 'listing')->get();
        return view('admin.order_view', compact('orders'));
    }

    public function approve(Order $order)
    {
        $order->status = 'approved';
        $order->save();
        return back()->with('success', 'Order Approved! Buyer can now make payment.');
    }

    public function reject(Order $order)
    {
        $order->status = 'cancelled';
        $order->save();
        return back()->with('error', 'Order Rejected!');
    }
}