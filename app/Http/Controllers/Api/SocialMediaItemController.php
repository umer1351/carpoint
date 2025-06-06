<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\SocialMediaItem;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use DB;
use Auth;

class SocialMediaItemController extends Controller
{
    public function __construct() {
        $this->middleware('auth.admin:admin');
    }

    public function index() {
        $social_media = SocialMediaItem::orderBy('social_order')->get();
        return response()->json(["success" => true, "message" => "index executed successfully."]);
    }

    public function create() {
        return response()->json(["success" => true, "message" => "create executed successfully."]);
    }

    public function store(Request $request) {
        if(env('PROJECT_MODE') == 0) {
            return redirect()->back()->with('error', env('PROJECT_NOTIFICATION'));
    }

        $social_media = new SocialMediaItem();
        $data = $request->only($social_media->getFillable());

        $request->validate([
            'social_url' => 'required',
            'social_icon' => 'required',
            'social_order' => 'numeric|min:0|max:32767'
        ],[
            'social_url.required' => ERR_URL_REQUIRED,
            'social_icon.required' => ERR_ICON_REQUIRED,
            'social_order.numeric' => ERR_ORDER_NUMERIC,
            'social_order.min' => ERR_ORDER_MIN,
            'social_order.max' => ERR_ORDER_MAX,
        ]);

        $social_media->fill($data)->save();
        return redirect()->route('admin_social_media_view')->with('success', SUCCESS_ACTION);
    }

    public function edit($id) {
        $social_media = SocialMediaItem::findOrFail($id);
        return response()->json(["success" => true, "message" => "edit executed successfully."]);
    }

    public function update(Request $request, $id) {
        if(env('PROJECT_MODE') == 0) {
            return redirect()->back()->with('error', env('PROJECT_NOTIFICATION'));
    }

        $social_media = SocialMediaItem::findOrFail($id);
        $data = $request->only($social_media->getFillable());

        $request->validate([
            'social_url' => 'required',
            'social_icon' => 'required',
            'social_order' => 'numeric|min:0|max:32767'
        ],[
            'social_url.required' => ERR_URL_REQUIRED,
            'social_icon.required' => ERR_ICON_REQUIRED,
            'social_order.numeric' => ERR_ORDER_NUMERIC,
            'social_order.min' => ERR_ORDER_MIN,
            'social_order.max' => ERR_ORDER_MAX,
        ]);

        $social_media->fill($data)->save();
        return redirect()->route('admin_social_media_view')->with('success', SUCCESS_ACTION);
    }

    public function destroy($id) {
        if(env('PROJECT_MODE') == 0) {
            return redirect()->back()->with('error', env('PROJECT_NOTIFICATION'));
    }
        
        $social_media = SocialMediaItem::findOrFail($id);
        $social_media->delete();
        return Redirect()->back()->with('success', SUCCESS_ACTION);
    }
}
