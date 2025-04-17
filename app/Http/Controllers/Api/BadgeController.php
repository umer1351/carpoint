<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\Badge;
use Illuminate\Http\Request;

class BadgeController extends Controller
{
    public function index() {
        $badges = Badge::all();
        return response()->json([
            "success" => true,
            "data" => $badges
        ]);
    }

    public function create() {
        return response()->json([
            "success" => true,
            "message" => "Create Badge page"
        ]);
    }

    public function store(Request $request) {
        $request->validate([
            'name' => 'required|string|max:255',
            'icon' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'description' => 'nullable|string',
        ]);

        $rand_value = md5(mt_rand(11111111,99999999));
        $ext = $request->file('icon')->extension();
        $final_name = $rand_value.'.'.$ext;
        $request->file('icon')->move(public_path('uploads/badges/'), $final_name);

        $badge = new Badge();
        $data = $request->only($badge->getFillable());
        unset($data['icon']);
        $data['icon'] = $final_name;

        $badge->fill($data)->save();

        return response()->json([
            "success" => true,
            "message" => "Badge added successfully!",
            "data" => $badge
        ]);
    }

    public function edit($id) {
        $badge = Badge::findOrFail($id);
        return response()->json([
            "success" => true,
            "data" => $badge
        ]);
    }

    public function update(Request $request, $id) {
        $badge = Badge::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'icon' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'description' => 'nullable|string',
        ]);

        $data = $request->only($badge->getFillable());
        
        if ($request->hasFile('icon')) {
            $request->validate([
                'icon' => 'image|mimes:jpeg,png,jpg,gif|max:2048'
            ]);

            unlink(public_path('uploads/badges/'.$badge->icon));

            $rand_value = md5(mt_rand(11111111,99999999));
            $ext = $request->file('icon')->extension();
            $final_name = $rand_value.'.'.$ext;
            $request->file('icon')->move(public_path('uploads/badges/'), $final_name);

            unset($data['icon']);
            $data['icon'] = $final_name;
        }

        $badge->fill($data)->save();

        return response()->json([
            "success" => true,
            "message" => "Badge updated successfully!",
            "data" => $badge
        ]);
    }

    public function destroy($id) {
        $badge = Badge::findOrFail($id);
        $badge->delete();

        return response()->json([
            "success" => true,
            "message" => "Badge deleted successfully!"
        ]);
    }

    public function change_status($id) {
        $badge = Badge::find($id);
        if ($badge->status == 'Active') {
            $badge->status = 'Pending';
            $badge->save();
            return response()->json([
                "success" => true,
                "message" => "Badge status changed to Pending"
            ]);
        } else {
            $badge->status = 'Active';
            $badge->save();
            return response()->json([
                "success" => true,
                "message" => "Badge status changed to Active"
            ]);
        }
    }
}
