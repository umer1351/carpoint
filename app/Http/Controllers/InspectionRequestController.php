<?php
namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\InspectionRequest;
use App\Models\Listing;
use Illuminate\Support\Facades\Auth;

class InspectionRequestController extends Controller
{
    public function requestInspection($listingId)
    {
        $listing = Listing::findOrFail($listingId);

        // Check if user is a buyer
        if (Auth::user()->user_role !== 'buyer') {
            return redirect()->back()->with('error', 'Only buyers can request an inspection.');
        }

        // Check if request already exists
        $exists = InspectionRequest::where('buyer_id', Auth::id())
                                   ->where('listing_id', $listingId)
                                   ->where('status', 'Pending')
                                   ->exists();

        if ($exists) {
            return redirect()->back()->with('error', 'You have already requested an inspection for this listing.');
        }

        // Create a new request
        InspectionRequest::create([
            'buyer_id' => Auth::id(),
            'seller_id' => $listing->user_id,
            'listing_id' => $listingId,
            'status' => 'Pending'
        ]);

        return redirect()->back()->with('success', 'Inspection request sent successfully.');
    }

    public function sellerRequests()
    {
        $requests = InspectionRequest::where('seller_id', Auth::id())->get();
        return view('front.seller_inspection', compact('requests'));
    }

    public function updateStatus($id, $status)
    {
        $request = InspectionRequest::findOrFail($id);

        // Only seller can update status
        if ($request->seller_id != Auth::id()) {
            return redirect()->back()->with('error', 'Unauthorized action.');
        }

        $request->update(['status' => $status]);
        return redirect()->back()->with('success', 'Inspection request ' . strtolower($status) . ' successfully.');
    }
}
