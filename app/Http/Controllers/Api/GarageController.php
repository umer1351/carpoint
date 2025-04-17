<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Garage;
use App\Models\Service;
use Illuminate\Support\Facades\Auth;

class GarageController extends Controller
{
    public function index() {
        $garages = Garage::with('services')->get();
        return response()->json(["success" => true, "message" => "Garages retrieved successfully.", "data" => $garages]);
    }

    public function create() {
        return response()->json(["success" => true, "message" => "Create executed successfully."]);
    }

    public function store(Request $request) {
        $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $garage = Garage::create([
            'seller_id' => Auth::id(),
            'name' => $request->name,
            'location' => $request->location,
            'description' => $request->description,
        ]);

        return response()->json(["success" => true, "message" => "Garage listed successfully.", "data" => $garage]);
    }

    public function addService(Request $request, $garageId) {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'nullable|numeric|min:0',
        ]);

        $service = Service::create([
            'garage_id' => $garageId,
            'name' => $request->name,
            'price' => $request->price,
        ]);

        return response()->json(["success" => true, "message" => "Service added successfully.", "data" => $service]);
    }
}
