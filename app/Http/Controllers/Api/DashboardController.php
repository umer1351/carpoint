<?php
namespace App\Http\Controllers\Api;
use App\Models\User;
use App\Models\Admin;
use App\Models\Listing;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;

class DashboardController extends Controller
{
    public function __construct() {
        $this->middleware('auth.admin:admin');
    }

    public function index() {
        $total_active_customers = User::where('status', 'Active')->count();
        $total_pending_customers = User::where('status', 'Pending')->count();
        $total_active_listings = Listing::where('listing_status', 'Active')->count();
        $total_pending_listings = Listing::where('listing_status', 'Pending')->count();
        
        return response()->json(["success" => true, "message" => "index executed successfully."]);
    }
}