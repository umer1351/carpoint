<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\GeneralSetting;
use App\Models\PageTermItem;
use Illuminate\Http\Request;
use DB;

class TermController extends Controller
{
    public function index() {
        $g_setting = GeneralSetting::where('id', 1)->first();
        $terms_data = PageTermItem::where('id', 1)->first();
        if (!$terms_data) {
            return response()->json(["success" => false, "message" => "Terms not found."], 404);
        }
    
        return response()->json([
            "success" => true,
            "data" => [
                "general_settings" => $g_setting,
                "terms" => $terms_data
            ]
        ]);
    }
}
