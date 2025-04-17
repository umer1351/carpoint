<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\InspectionRequest;
use App\Models\Listing;
use Illuminate\Support\Facades\Auth;

class InspectionRequestController extends Controller
{
    // Request an inspection for a listing (Web or API)
    public function requestInspection(Request $request, $listingId) {
        $listing = Listing::findOrFail($listingId);

        // Check if user is a buyer
        if (Auth::user()->user_role !== 'buyer') {
            if ($request->wantsJson()) {
                return response()->json(["success" => false, "message" => 'Only buyers can request an inspection.'], 403);
            } else {
                return redirect()->back()->with('error', 'Only buyers can request an inspection.');
            }
        }

        // Check if request already exists
        $exists = InspectionRequest::where('buyer_id', Auth::id())
                                   ->where('listing_id', $listingId)
                                   ->where('status', 'Pending')
                                   ->exists();

        if ($exists) {
            if ($request->wantsJson()) {
                return response()->json(["success" => false, "message" => 'You have already requested an inspection for this listing.'], 400);
            } else {
                return redirect()->back()->with('error', 'You have already requested an inspection for this listing.');
            }
        }

        // Create a new inspection request
        InspectionRequest::create([
            'buyer_id' => Auth::id(),
            'seller_id' => $listing->user_id,
            'listing_id' => $listingId,
            'status' => 'Pending'
        ]);

        if ($request->wantsJson()) {
            return response()->json(["success" => true, "message" => 'Inspection request sent successfully.'], 200);
        } else {
            return redirect()->back()->with('success', 'Inspection request sent successfully.');
        }
    }

    // Seller's inspection requests (API)
    public function sellerRequests(Request $request) {
        $requests = InspectionRequest::where('seller_id', Auth::id())->get();

        if ($request->wantsJson()) {
            return response()->json([
                "success" => true,
                "message" => "Seller's inspection requests retrieved successfully.",
                "data" => $requests
            ], 200);
        } else {
            return view('inspection_requests.seller_requests', compact('requests'));
        }
    }

    // Update status of an inspection request (Web or API)
    public function updateStatus(Request $request, $id, $status) {
        $requestModel = InspectionRequest::findOrFail($id);

        // Only seller can update status
        if ($requestModel->seller_id != Auth::id()) {
            if ($request->wantsJson()) {
                return response()->json(["success" => false, "message" => 'Unauthorized action.'], 403);
            } else {
                return redirect()->back()->with('error', 'Unauthorized action.');
            }
        }

        // Update the request status
        $requestModel->update(['status' => $status]);

        if ($request->wantsJson()) {
            return response()->json(["success" => true, "message" => 'Inspection request status updated successfully.'], 200);
        } else {
            return redirect()->back()->with('success', 'Inspection request ' . strtolower($status) . ' successfully.');
        }
    }
}
