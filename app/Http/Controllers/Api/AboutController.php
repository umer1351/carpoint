<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\GeneralSetting;
use App\Models\PageAboutItem;
use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\Mail;

class AboutController extends Controller
{
    public function index() {
        $g_setting = GeneralSetting::where('id', 1)->first();
        $about_data = PageAboutItem::where('id', 1)->first();
       
        if (!$about_data) {
            return response()->json(["success" => false, "message" => "Terms not found."], 404);
        }
    
        return response()->json([
            "success" => true,
            "data" => [
                "general_settings" => $g_setting,
                "about_data" => $about_data
            ]
        ]);
    }
}
