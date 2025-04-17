<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use DB;
use Hash;
use Auth;

class ProfileController extends Controller
{
    public function __construct() {
        $this->middleware('auth.admin:admin');
    }

    public function profile() {
        $admin_data = Auth::user();
        return response()->json(["success" => true, "message" => "Profile fetched successfully.", "data" => $admin_data]);
    }

    public function profile_update(Request $request) {
        if (env('PROJECT_MODE') == 0) {
            return response()->json([
                'success' => false,
                'message' => env('PROJECT_NOTIFICATION')
            ], 400);
        }

        $admin_data = Auth::user();
        $obj = Admin::findOrFail($admin_data->id);
        $data = $request->only($obj->getFillable());
        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
        ],[
            'name.required' => ERR_NAME_REQUIRED,
            'email.required' => ERR_EMAIL_REQUIRED,
            'email.email' => ERR_EMAIL_INVALID
        ]);

        $obj->fill($data)->save();

        return response()->json([
            'success' => true,
            'message' => SUCCESS_ACTION,
            'data' => $obj
        ]);
    }

    public function password() {
        return response()->json(["success" => true, "message" => "Password info fetched successfully."]);
    }

    public function password_update(Request $request) {
        if (env('PROJECT_MODE') == 0) {
            return response()->json([
                'success' => false,
                'message' => env('PROJECT_NOTIFICATION')
            ], 400);
        }

        $admin_data = Auth::user();
        $obj = Admin::findOrFail($admin_data->id);
        $data = $request->only($obj->getFillable());
        $request->validate([
            'password' => 'required',
            're_password' => 'required|same:password',
        ],[
            'password.required' => ERR_PASSWORD_REQUIRED,
            're_password.required' => ERR_RE_PASSWORD_REQUIRED,
            're_password.same' => ERR_PASSWORDS_MATCH
        ]);

        $data['password'] = Hash::make($request->password);
        unset($data['re_password']);
        $obj->fill($data)->save();

        return response()->json([
            'success' => true,
            'message' => SUCCESS_ACTION,
            'data' => $obj
        ]);
    }

    public function photo() {
        $admin_data = Auth::user();
        return response()->json(["success" => true, "message" => "Photo info fetched successfully."]);
    }

    public function photo_update(Request $request) {
        if (env('PROJECT_MODE') == 0) {
            return response()->json([
                'success' => false,
                'message' => env('PROJECT_NOTIFICATION')
            ], 400);
        }

        $admin_data = Auth::user();
        $obj = Admin::findOrFail($admin_data->id);
        $data = $request->only($obj->getFillable());
        $request->validate([
            'photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
        ],[
            'photo.required' => ERR_PHOTO_REQUIRED,
            'photo.image' => ERR_PHOTO_IMAGE,
            'photo.mimes' => ERR_PHOTO_JPG_PNG_GIF,
            'photo.max' => ERR_PHOTO_MAX
        ]);

        unlink(public_path('uploads/user_photos/'.$admin_data->photo));

        $ext = $request->file('photo')->extension();
        $rand_value = md5(mt_rand(11111111,99999999));
        $final_name = $rand_value . '.' . $ext;
        $request->file('photo')->move(public_path('uploads/user_photos/'), $final_name);
        $data['photo'] = $final_name;
        
        $obj->fill($data)->save();

        return response()->json([
            'success' => true,
            'message' => SUCCESS_ACTION,
            'data' => $obj
        ]);
    }

    public function banner() {
        $admin_data = Auth::user();
        return response()->json(["success" => true, "message" => "Banner info fetched successfully."]);
    }

    public function banner_update(Request $request) {
        if (env('PROJECT_MODE') == 0) {
            return response()->json([
                'success' => false,
                'message' => env('PROJECT_NOTIFICATION')
            ], 400);
        }

        $admin_data = Auth::user();
        $obj = Admin::findOrFail($admin_data->id);
        $data = $request->only($obj->getFillable());
        $request->validate([
            'banner' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
        ],[
            'banner.required' => ERR_PHOTO_REQUIRED,
            'banner.image' => ERR_PHOTO_IMAGE,
            'banner.mimes' => ERR_PHOTO_JPG_PNG_GIF,
            'banner.max' => ERR_PHOTO_MAX
        ]);

        unlink(public_path('uploads/user_photos/'.$admin_data->banner));

        $ext = $request->file('banner')->extension();
        $rand_value = md5(mt_rand(11111111,99999999));
        $final_name = $rand_value . '.' . $ext;
        $request->file('banner')->move(public_path('uploads/user_photos/'), $final_name);
        $data['banner'] = $final_name;
        
        $obj->fill($data)->save();

        return response()->json([
            'success' => true,
            'message' => SUCCESS_ACTION,
            'data' => $obj
        ]);
    }
}
