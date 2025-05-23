<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\Category;
use App\Models\PageBlogItem;
use App\Models\GeneralSetting;
use Illuminate\Http\Request;
use DB;

class CategoryController extends Controller
{
    public function detail($slug) {
        $category_single = Category::where('category_slug', $slug)->first();

        if (!$category_single) {
            return response()->json([
                "success" => false,
                "message" => "Category not found"
            ], 404);
        }

        $g_setting = GeneralSetting::where('id', 1)->first();
        $blog_items = Blog::with('rCategory')
            ->where('category_id', $category_single->id)
            ->paginate(9);

        $blog_items_no_pagi = Blog::orderby('id', 'desc')->get();
        $categories = Category::get();
        $blog = PageBlogItem::where('id', 1)->first();

        return response()->json([
            "success" => true,
            "data" => [
                "general_setting" => $g_setting,
                "blog_items" => $blog_items,
                "blog_items_no_pagi" => $blog_items_no_pagi,
                "categories" => $categories,
                "category_single" => $category_single,
                "blog" => $blog
            ]
        ]);
    }
}
