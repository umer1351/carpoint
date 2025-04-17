<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\Amenity;
use Illuminate\Http\Request;

class AmenityController extends Controller
{
    public function index() {
        $amenities = Amenity::all();
        return response()->json([
            "success" => true,
            "data" => $amenities
        ]);
    }

    public function create() {
        return response()->json([
            "success" => true,
            "message" => "Amenity creation page"
        ]);
    }

    public function store(Request $request) {
        $request->validate([
            'amenity_name' => 'required|unique:amenities',
            'amenity_slug' => 'unique:amenities'
        ]);

        $amenity = new Amenity();
        $amenity->fill($request->all());
        $amenity->save();

        return response()->json([
            "success" => true,
            "message" => "Amenity created successfully",
            "data" => $amenity
        ]);
    }

    public function edit($id) {
        $amenity = Amenity::findOrFail($id);
        return response()->json([
            "success" => true,
            "data" => $amenity
        ]);
    }

    public function update(Request $request, $id) {
        $request->validate([
            'amenity_name' => 'required|unique:amenities,amenity_name,' . $id,
            'amenity_slug' => 'unique:amenities,amenity_slug,' . $id
        ]);

        $amenity = Amenity::findOrFail($id);
        $amenity->update($request->all());

        return response()->json([
            "success" => true,
            "message" => "Amenity updated successfully",
            "data" => $amenity
        ]);
    }

    public function destroy($id) {
        $amenity = Amenity::findOrFail($id);
        $amenity->delete();

        return response()->json([
            "success" => true,
            "message" => "Amenity deleted successfully"
        ]);
    }
}
