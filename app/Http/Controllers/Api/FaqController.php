<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;

class FaqController extends Controller
{
    public function index() {
        // Fetching general settings, FAQ, and faqs from the database
        $g_setting = DB::table('general_settings')->where('id', 1)->first();
        $faq = DB::table('page_faq_items')->where('id', 1)->first();
        $faqs = DB::table('faqs')->orderby('faq_order', 'asc')->get();

        // Returning data in JSON format
        return response()->json([
            "success" => true,
            "data" => [
                "general_settings" => $g_setting,
                "faq" => $faq,
                "faqs" => $faqs
            ]
        ]);
    }
}
