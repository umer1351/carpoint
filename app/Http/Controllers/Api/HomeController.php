<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\PageHomeItem;
use App\Models\Listing;
use App\Models\ListingBrand;
use App\Models\ListingLocation;
use App\Models\HomeAdvertisement;
use App\Models\Testimonial;
use DB;

class HomeController extends Controller
{
    public function index() {
        // Fetching home advertisement data
        $adv_home_data = HomeAdvertisement::where('id', 1)->first();

        // Fetching page home items
        $page_home_items = PageHomeItem::where('id', 1)->first();

        // Fetching testimonials
        $testimonials = Testimonial::all();

        // Fetching listing brands ordered by name
        $listing_brands = ListingBrand::orderBy('listing_brand_name', 'asc')->get();

        // Fetching listing locations ordered by name
        $listing_locations = ListingLocation::orderBy('listing_location_name', 'asc')->get();

        // Fetching the most popular listing brands based on listing count
        $orderwise_listing_brands = DB::select('
            SELECT * FROM listing_brands as r1
            LEFT JOIN (
                SELECT listing_brand_id, count(*) as total
                FROM listings as l
                JOIN listing_brands as lc ON l.listing_brand_id = lc.id
                GROUP BY listing_brand_id
                ORDER BY total DESC
            ) as r2 ON r1.id = r2.listing_brand_id
            ORDER BY r2.total DESC
        ');

        // Fetching the most popular listing locations based on listing count
        $orderwise_listing_locations = DB::select('
            SELECT * FROM listing_locations as r1
            LEFT JOIN (
                SELECT listing_location_id, count(*) as total
                FROM listings as l
                JOIN listing_locations as ll ON l.listing_location_id = ll.id
                GROUP BY listing_location_id
                ORDER BY total DESC
            ) as r2 ON r1.id = r2.listing_location_id
            ORDER BY r2.total DESC
        ');

        // Fetching active and featured listings along with their brands and locations
        $listings = Listing::with('rListingBrand', 'rListingLocation')
            ->orderBy('listing_name', 'asc')
            ->where('listing_status', 'Active')
            ->where('is_featured', 'Yes')
            ->get();

        // Return the data as a JSON response
        return response()->json([
            "success" => true,
            "message" => "index executed successfully.",
            "data" => [
                'advertisement' => $adv_home_data,
                'home_items' => $page_home_items,
                'testimonials' => $testimonials,
                'listing_brands' => $listing_brands,
                'listing_locations' => $listing_locations,
                'orderwise_listing_brands' => $orderwise_listing_brands,
                'orderwise_listing_locations' => $orderwise_listing_locations,
                'listings' => $listings,
            ]
        ]);
    }
}
