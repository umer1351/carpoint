<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Listing;
use Illuminate\Http\Request;

class ListingController extends Controller
{
    public function index(Request $request)
    {
        // Optional: Pagination ka size query se
        $perPage = $request->get('per_page', 10);

        // Listing data with relationships
        $listings = Listing::with(['rListingBrand', 'rListingLocation', 'user', 'photos'])
    ->when($request->brand_id, fn ($q) => $q->where('listing_brand_id', $request->brand_id))
    ->when($request->location_id, fn ($q) => $q->where('listing_location_id', $request->location_id))
    ->when($request->listing_body, fn ($q) => $q->where('listing_body', $request->listing_body))
    ->when($request->listing_door, fn ($q) => $q->where('listing_door', $request->listing_door))
    ->when($request->listing_wheel, fn ($q) => $q->where('listing_wheel', $request->listing_wheel))
    ->when($request->listing_engine_capacity, fn ($q) => $q->where('listing_engine_capacity', $request->listing_engine_capacity))
    ->when($request->listing_price, fn ($q) => $q->where('listing_price', $request->listing_price))
    ->paginate($perPage);

        

        // Response
        return response()->json([
            'success' => true,
            'data' => $listings
        ]);
    }
}
