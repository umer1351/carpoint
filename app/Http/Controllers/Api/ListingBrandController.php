<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ListingBrand;
use Illuminate\Http\Request;

class ListingBrandController extends Controller
{
    // list all brands
    public function index()
    {
        $brands = ListingBrand::all();

        return response()->json([
            'success' => true,
            'data' => $brands
        ]);
    }
}



