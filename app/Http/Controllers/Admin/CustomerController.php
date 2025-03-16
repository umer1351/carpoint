<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Badge;
use App\Models\Listing;
use App\Models\PackagePurchase;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use DB;
Use Auth;

class CustomerController extends Controller
{
    public function __construct() {
        $this->middleware('auth.admin:admin');
    }

    public function index() {
        $customers = User::get();
        return view('admin.customer_view', compact('customers'));
    }

    public function detail($id) {
        $customer_detail = User::with('badges')->where('id', $id)->first();
         // Already assigned badge IDs
        $assigned_badge_ids = $customer_detail->badges->pluck('id')->toArray();
        
        // Fetch only those badges that are NOT assigned
        $badges = Badge::whereNotIn('id', $assigned_badge_ids)->get();
        $customer_detail = User::with('badges')->where('id',$id)->first();
        
        return view('admin.customer_detail', compact('customer_detail', 'badges'));
    }

    public function destroy($id) {

        if(env('PROJECT_MODE') == 0) {
            return redirect()->back()->with('error', env('PROJECT_NOTIFICATION'));
        }

        // Before deleting, check this customer is used in another table
        $cnt = Listing::where('admin_id',0)->where('user_id',$id)->count();
        if($cnt>0) {
            return redirect()->back()->with('error', ERR_ITEM_DELETE);
        }

        $cnt1 = PackagePurchase::where('user_id',$id)->count();
        if($cnt1>0) {
            return redirect()->back()->with('error', ERR_ITEM_DELETE);
        }

        $cnt2 = Review::where('agent_id',$id)->where('agent_type','Customer')->count();
        if($cnt2>0) {
            return redirect()->back()->with('error', ERR_ITEM_DELETE);
        }

        User::where('id', $id)->delete();
        return Redirect()->back()->with('success', SUCCESS_ACTION);
    }

    public function change_status($id) {
        $customer = User::find($id);
        if($customer->status == 'Active') {
            if(env('PROJECT_MODE') == 0) {
                $message=env('PROJECT_NOTIFICATION');
            } else {
                $customer->status = 'Pending';
                $message=SUCCESS_ACTION;
                $customer->save();
            }
        } else {
            if(env('PROJECT_MODE') == 0) {
                $message=env('PROJECT_NOTIFICATION');
            } else {
                $customer->status = 'Active';
                $message=SUCCESS_ACTION;
                $customer->save();
            }
        }
        return response()->json($message);
    }

    public function assignBadge(Request $request, $id) {
        $request->validate([
            'badge_id' => 'required|exists:badges,id'
        ]);
    
        $customer = User::findOrFail($id);
        $customer->badges()->syncWithoutDetaching([$request->badge_id]); // Assign badge without removing existing ones
    
        return redirect()->back()->with('success', 'Badge assigned successfully!');
    }

    public function removeBadge(Request $request, $id)
    {
        $customer = User::findOrFail($id);
        
        // Badge ko user se detach karna
        $customer->badges()->detach($request->badge_id);

        return redirect()->back()->with('success', 'Badge removed successfully!');
    }

    public function sellerListings($id) {
        $seller = User::findOrFail($id);
    
        // Seller ki sari listings aur sold count
        $listings = Listing::where('user_id', $id)
            ->withCount(['orders as sold_count']) // Sold items count
            ->get();
    
        return view('admin.seller_listings', compact('seller', 'listings'));
    }
    

}
