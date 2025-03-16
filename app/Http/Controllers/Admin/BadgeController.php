<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;

use App\Models\Badge;
use Illuminate\Http\Request;

use DB;
Use Auth;
class BadgeController extends Controller
{

    public function __construct() {
        $this->middleware('auth.admin:admin');
    }

    public function index()
    {
        $badges = Badge::all();
        return view('admin.badge_view', compact('badges'));
    }

    public function create()
    {
        return view('admin.badge_create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'icon' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'description' => 'nullable|string',
        ]);

        // Badge::create($request->all());
        $rand_value = md5(mt_rand(11111111,99999999));
        $ext = $request->file('icon')->extension();
        $final_name = $rand_value.'.'.$ext;
        $request->file('icon')->move(public_path('uploads/badges/'), $final_name);

        $badge = new Badge();
        $data = $request->only($badge->getFillable());
        

        unset($data['icon']);
        $data['icon'] = $final_name;
        
        $badge->fill($data)->save();
        return redirect()->route('admin_badges_view')->with('success', 'Badge added successfully!');
    }

    public function edit($id)
    {
        $badge = Badge::findOrFail($id);
        return view('admin.badge_edit', compact('badge'));
    }

    public function update(Request $request, $id)
    {
        $badge = Badge::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'icon' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'description' => 'nullable|string',
        ]);

        $data = $request->only($badge->getFillable());
        // dd($badge);
        if ($request->hasFile('icon')) {

            $request->validate([
                'icon' => 'image|mimes:jpeg,png,jpg,gif|max:2048'
            ]);

            unlink(public_path('uploads/badges/'.$badge->icon));

            // Uploading the file
            $rand_value = md5(mt_rand(11111111,99999999));
            $ext = $request->file('icon')->extension();
            $final_name = $rand_value.'.'.$ext;
            $request->file('icon')->move(public_path('uploads/badges/'), $final_name);

            unset($data['icon']);
            $data['icon'] = $final_name;
        }

        // $badge->update($request->all());
         $badge->fill($data)->save();
        return redirect()->route('admin_badges_view')->with('success', 'Badge updated successfully!');
    }

    public function destroy($id)
    {
        $badge = Badge::findOrFail($id);
        $badge->delete();
        return redirect()->route('admin_badges_view')->with('success', 'Badge deleted successfully!');
    }

    public function change_status($id) {
        $badge = Badge::find($id);
        if($badge->status == 'Active') {
            if(env('PROJECT_MODE') == 0) {
                $message=env('PROJECT_NOTIFICATION');
            } else {
                $badge->status = 'Pending';
                $message=SUCCESS_ACTION;
                $badge->save();
            }
        } else {
            if(env('PROJECT_MODE') == 0) {
                $message=env('PROJECT_NOTIFICATION');
            } else {
                $badge->status = 'Active';
                $message=SUCCESS_ACTION;
                $badge->save();
            }
        }
        return response()->json($message);
    }
}
