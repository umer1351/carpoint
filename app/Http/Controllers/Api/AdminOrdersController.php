<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;

use App\Models\Order;
use Illuminate\Http\Request;

class AdminOrdersController extends Controller
{
    public function __construct() {
        $this->middleware('auth.admin:admin');
    }

    public function index() {
        // Get all orders with related buyer, seller, and listing
        $orders = Order::with('buyer', 'seller', 'listing')->get();

        // If no orders are found, return an error response
        if ($orders->isEmpty()) {
            return response()->json([
                "success" => false, 
                "message" => "No orders found."
            ], 404);
        }
    
        // Return success with orders data
        return response()->json([
            "success" => true,
            "data" => [
                "orders" => $orders
            ]
        ]);
    }

    public function approve(Order $order) {
        // Update the order status to 'approved'
        $order->status = 'approved';
        $order->save();

        // Return a success response
        return response()->json([
            "success" => true,
            "message" => "Order approved! Buyer can now make payment."
        ]);
    }

    public function reject(Order $order) {
        // Update the order status to 'cancelled'
        $order->status = 'cancelled';
        $order->save();

        // Return an error response
        return response()->json([
            "success" => false,
            "message" => "Order rejected!"
        ]);
    }
}
