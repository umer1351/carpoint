<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\GeneralSetting;
use App\Models\PagePrivacyItem;
use Illuminate\Http\Request;
use DB;

class PrivacyController extends Controller
{
    public function index() {
        $g_setting = GeneralSetting::where('id', 1)->first();
        $privacy_data = PagePrivacyItem::where('id', 1)->first();
        return response()->json(["success" => true, "message" => "index executed successfully."]);
    }
}
