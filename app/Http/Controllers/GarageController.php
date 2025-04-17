<?php
namespace App\Http\Controllers;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\Garage;
use App\Models\Service;
use Illuminate\Support\Facades\Auth;

class GarageController extends Controller
{
    public function index()
    {
        $garages = Garage::with('services')->get();
        return view('garages.index', compact('garages'));
    }

    public function create()
    {
        return view('garages.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        Garage::create([
            'seller_id' => Auth::id(),
            'name' => $request->name,
            'location' => $request->location,
            'description' => $request->description,
        ]);

        return redirect()->route('garages.index')->with('success', 'Garage listed successfully.');
    }

    public function addService(Request $request, $garageId)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'nullable|numeric|min:0',
        ]);

        Service::create([
            'garage_id' => $garageId,
            'name' => $request->name,
            'price' => $request->price,
        ]);

        return redirect()->back()->with('success', 'Service added successfully.');
    }
}
