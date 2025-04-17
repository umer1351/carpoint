<?php
namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FinanceInquiry;
use App\Models\Listing;
use Illuminate\Support\Facades\Auth;

class FinanceInquiryController extends Controller
{
    public function submitInquiry(Request $request, $listingId)
    {
        $request->validate([
            'term' => 'required|in:1 Year,2 Years,3 Years',
            'down_payment' => 'nullable|numeric|min:0',
            'message' => 'nullable|string|max:500',
        ]);

        // Check if user is a buyer
        if (Auth::user()->user_role !== 'buyer') {
            return redirect()->back()->with('error', 'Only buyers can submit finance inquiries.');
        }

        FinanceInquiry::create([
            'buyer_id' => Auth::id(),
            'listing_id' => $listingId,
            'term' => $request->term,
            'down_payment' => $request->down_payment,
            'message' => $request->message,
            'status' => 'Pending',
        ]);

        return redirect()->back()->with('success', 'Finance inquiry submitted successfully.');
    }

    public function adminInquiries()
    {
        $inquiries = FinanceInquiry::with('buyer', 'listing')->get();
        return view('admin.finance_inquiries.index', compact('inquiries'));
    }
}
