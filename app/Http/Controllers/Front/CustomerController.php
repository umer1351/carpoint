<?php
namespace App\Http\Controllers\Front;
use App\Http\Controllers\Controller;
use App\Models\LanguageMenuText;
use App\Models\LanguageWebsiteText;
use App\Models\LanguageNotificationText;
use App\Models\User;
use App\Models\Order;
use App\Models\InspectionRequest;
use App\Models\Wishlist;
use App\Models\Amenity;
use App\Models\Listing;
use App\Models\ListingBrand;
use App\Models\ListingLocation;
use App\Models\ListingSocialItem;
use App\Models\ListingAdditionalFeature;
use App\Models\ListingPhoto;
use App\Models\ListingVideo;
use App\Models\ListingAmenity;
use App\Models\Package;
use App\Models\PackagePurchase;
use App\Models\Review;
use App\Models\GeneralSetting;
use App\Models\EmailTemplate;
use App\Models\PageOtherItem;
use App\Mail\PurchaseCompletedEmailToCustomer;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use DB;
use Hash;
use Auth;
use Illuminate\Support\Facades\Mail;
use PayPal\Api\Amount;
use PayPal\Api\Details;
use PayPal\Api\Payment;
use PayPal\Api\PaymentExecution;
use PayPal\Api\Transaction;
use Razorpay\Api\Api;
use Mollie\Laravel\Facades\Mollie;
use Session;
class CustomerController extends Controller
{
	public function __construct() {
    	$this->middleware('auth:web');
    }

    public function dashboard() 
    {
        $page_other_item = PageOtherItem::where('id',1)->first();

        $g_setting = GeneralSetting::where('id', 1)->first();
        $total_active_listing =
            Listing::where('listing_status', 'Active')
            ->where('user_id', Auth::user()->id)
            ->count();

        $total_pending_listing =
            Listing::where('listing_status', 'Pending')
            ->where('user_id', Auth::user()->id)
            ->count();

        $detail = PackagePurchase::with('rPackage')
            ->where('user_id', Auth::user()->id)
            ->where('currently_active', 1)
            ->first();

        return view('front.customer_dashboard', compact('g_setting','total_active_listing','total_pending_listing','detail','page_other_item'));
    }

    public function otp() 
    {
        $page_other_item = PageOtherItem::where('id',1)->first();

        $g_setting = GeneralSetting::where('id', 1)->first();
        

        return view('front.customer_otp_verify', compact('g_setting','page_other_item'));
    }

    public function verifyOtp(Request $request) 
    {
        $page_other_item = PageOtherItem::where('id',1)->first();

        $g_setting = GeneralSetting::where('id', 1)->first();
        $request->validate(['otp' => 'required']);

        $authUserId = Session::get('auth_user_id');
        $sessionOtp = Session::get('auth_otp');

        if (!$authUserId || !$sessionOtp) {
           
            return redirect()->route('customer_login')->with('error', 'Session expired, please login again.');
        }
        if ($request->otp == $sessionOtp) {
            Auth::loginUsingId($authUserId);
            Session::forget(['auth_otp']); // ✅ Remove OTP from session
            Session::put('otp_verified', true); // ✅ Mark OTP as verified
    
            return redirect()->route('customer_dashboard');
           
        } else {
            return redirect()->back()->with('error', 'Invalid OTP.');
          
        }
        // dd($request->otp);

        // return view('front.customer_otp_verify', compact('g_setting','page_other_item'));
    }

    public function update_profile() 
    {
        $user_data = Auth::user();
        $page_other_item = PageOtherItem::where('id',1)->first();
        $g_setting = GeneralSetting::where('id', 1)->first();
        return view('front.customer_update_profile', compact('user_data','g_setting','page_other_item'));
    }

    public function update_profile_confirm(Request $request) 
    {

        if(env('PROJECT_MODE') == 0) {
            return redirect()->back()->with('error', env('PROJECT_NOTIFICATION'));
        }

        $user_data = Auth::user();
        $obj = User::findOrFail($user_data->id);
        $data = $request->only($obj->getFillable());
        $request->validate([
            'email'   =>  [
                'required',
                'email',
                Rule::unique('users')->ignore($user_data->id),
            ]
        ],[
            'email.required' => ERR_EMAIL_REQUIRED,
            'email.email' => ERR_EMAIL_INVALID,
            'email.unique' => ERR_EMAIL_EXIST
        ]);
        $obj->fill($data)->save();
        return redirect()->back()->with('success', SUCCESS_PROFILE_UPDATE);
    }

    public function update_password() 
    {
        $g_setting = GeneralSetting::where('id', 1)->first();
        $page_other_item = PageOtherItem::where('id',1)->first();
        return view('front.customer_update_password', compact('g_setting','page_other_item'));
    }

    public function update_password_confirm(Request $request) 
    {
        if(env('PROJECT_MODE') == 0) {
            return redirect()->back()->with('error', env('PROJECT_NOTIFICATION'));
        }

        $user_data = Auth::user();
        $obj = User::findOrFail($user_data->id);
        $data = $request->only($obj->getFillable());
        $request->validate([
            'password' => 'required',
            're_password' => 'required|same:password',
        ], [
            'password.required' => ERR_PASSWORD_REQUIRED,
            're_password.required' => ERR_RE_PASSWORD_REQUIRED,
            're_password.same' => ERR_RE_PASSWORD_REQUIRED
        ]);
        $data['password'] = Hash::make($request->password);
        unset($data['re_password']);
        $obj->fill($data)->save();
        return redirect()->back()->with('success', SUCCESS_PASSWORD_UPDATE);
    }

    public function update_photo() 
    {
        $user_data = Auth::user();
        $g_setting = DB::table('general_settings')->where('id', 1)->first();
        $page_other_item = PageOtherItem::where('id',1)->first();
        return view('front.customer_update_photo', compact('user_data','g_setting','page_other_item'));
    }

    public function update_photo_confirm(Request $request) 
    {
        if(env('PROJECT_MODE') == 0) {
            return redirect()->back()->with('error', env('PROJECT_NOTIFICATION'));
        }

        $user_data = Auth::user();
        $obj = User::findOrFail($user_data->id);
        $data = $request->only($obj->getFillable());
        $request->validate([
            'photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
        ], [
            'photo.required' => ERR_PHOTO_REQUIRED,
            'photo.image' => ERR_PHOTO_IMAGE,
            'photo.mimes' => ERR_PHOTO_JPG_PNG_GIF,
            'photo.max' => ERR_PHOTO_MAX
        ]);
        if($user_data->photo != '') {
            unlink(public_path('uploads/user_photos/'.$user_data->photo));
        }
        $ext = $request->file('photo')->extension();
        $rand_value = md5(mt_rand(11111111,99999999));
        $final_name = $rand_value.'.'.$ext;
        $request->file('photo')->move(public_path('uploads/user_photos/'), $final_name);
        $data['photo'] = $final_name;
        $obj->fill($data)->save();
        return redirect()->back()->with('success', SUCCESS_PHOTO_UPDATE);
    }

    public function update_banner() 
    {
        $user_data = Auth::user();
        $g_setting = DB::table('general_settings')->where('id', 1)->first();
        $page_other_item = PageOtherItem::where('id',1)->first();
        return view('front.customer_update_banner', compact('user_data','g_setting','page_other_item'));
    }

    public function update_banner_confirm(Request $request) 
    {
        if(env('PROJECT_MODE') == 0) {
            return redirect()->back()->with('error', env('PROJECT_NOTIFICATION'));
        }

        $user_data = Auth::user();
        $obj = User::findOrFail($user_data->id);
        $data = $request->only($obj->getFillable());
        $request->validate([
            'banner' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
        ],[
            'banner.required' => ERR_PHOTO_REQUIRED,
            'banner.image' => ERR_PHOTO_IMAGE,
            'banner.mimes' => ERR_PHOTO_JPG_PNG_GIF,
            'banner.max' => ERR_PHOTO_MAX
        ]);
        if($user_data->banner != '') {
            unlink(public_path('uploads/user_photos/'.$user_data->banner));
        }
        $ext = $request->file('banner')->extension();
        $rand_value = md5(mt_rand(11111111,99999999));
        $final_name = $rand_value.'.'.$ext;
        $request->file('banner')->move(public_path('uploads/user_photos/'), $final_name);
        $data['banner'] = $final_name;
        $obj->fill($data)->save();
        return redirect()->back()->with('success', SUCCESS_BANNER_UPDATE);
    }


    public function listing_view() 
    {
        $user_data = Auth::user();

        $page_other_item = PageOtherItem::where('id',1)->first();

        $detail = PackagePurchase::with('rPackage')
            ->where('user_id', $user_data->id)
            ->where('currently_active', 1)
            ->first();

        if($detail == null) {
            return Redirect()->route('customer_package')->with('error', ERR_ENROLL_PACKAGE_FIRST);
        }

        // Date Over Check
        $today = date('Y-m-d');
        $expire_date = $detail->package_end_date;
        if($today > $expire_date) {
            return Redirect()->route('customer_package')->with('error', ERR_LISTING_DATE_EXPIRED);
        }


        $g_setting = GeneralSetting::where('id', 1)->first();
        $listing = Listing::with('rListingBrand', 'rListingLocation')
            ->where('user_id',$user_data->id)
            ->get();
        return view('front.customer_listing_view', compact('g_setting','listing','page_other_item'));
    }

    public function listing_view_detail($id) 
    {
        $user_data = Auth::user();
        $check_other = Listing::where('id', $id)->first();

        $page_other_item = PageOtherItem::where('id',1)->first();

        if( (!$check_other) || ($check_other->user_id != $user_data->id)) {
            abort(404);
        }

        $g_setting = GeneralSetting::where('id', 1)->first();

        $listing = Listing::where('id', $id)->first();
        $listing_brand = ListingBrand::orderBy('id','asc')->get();
        $listing_location = ListingLocation::orderBy('id','asc')->get();
        $amenity = Amenity::orderBy('id','asc')->get();

        $existing_amenities_array = array();
        $listing_amenities = ListingAmenity::where('listing_id',$id)->orderBy('id','asc')->get();
        foreach($listing_amenities as $row) {
            $existing_amenities_array[] = $row->amenity_id;
        }

        $listing_photos = ListingPhoto::where('listing_id',$id)->orderBy('id','asc')->get();
        $listing_videos = ListingVideo::where('listing_id',$id)->orderBy('id','asc')->get();
        $listing_additional_features = ListingAdditionalFeature::where('listing_id',$id)->orderBy('id','asc')->get();
        $listing_social_items = ListingSocialItem::where('listing_id',$id)->orderBy('id','asc')->get();

        $detail = PackagePurchase::with('rPackage')
            ->where('user_id', $user_data->id)
            ->where('currently_active', 1)
            ->first();

        $total_amenities = $detail->rPackage->total_amenities;
        $total_photos = $detail->rPackage->total_photos;
        $total_videos = $detail->rPackage->total_videos;
        $total_social_items = $detail->rPackage->total_social_items;
        $total_additional_features = $detail->rPackage->total_additional_features;

        return view('front.customer_listing_view_detail', compact('g_setting','listing','listing_brand','listing_location','amenity','listing_photos','listing_videos','listing_additional_features','listing_social_items','listing_amenities','existing_amenities_array','total_amenities','total_photos','total_videos','total_social_items','total_additional_features','page_other_item'));
    }


    public function listing_add() 
    {
        $user_data = Auth::user();

        $page_other_item = PageOtherItem::where('id',1)->first();

        // Check if he has access to add listing
        $listing_error_message = '';
        $detail = PackagePurchase::with('rPackage')
            ->where('user_id', $user_data->id)
            ->where('currently_active', 1)
            ->first();

        $total_listing_added_by_customer =
            Listing::where('user_id', $user_data->id)
            ->count();

        $total_amenities = 0;
        $total_photos = 0;
        $total_videos = 0;
        $total_social_items = 0;
        $total_additional_features = 0;
        $allow_featured = 0;

        if($detail == null) {
            return Redirect()->route('customer_package')->with('error', ERR_ENROLL_PACKAGE_FIRST);
        } else {
            // Date Over Check
            $today = date('Y-m-d');
            $expire_date = $detail->package_end_date;
            if($today > $expire_date) {
                return Redirect()->route('customer_package')->with('error', ERR_LISTING_DATE_EXPIRED);
            }

            // Maximum Quota Check
            $remaining_listing = $detail->rPackage->total_listings-$total_listing_added_by_customer;
            if($remaining_listing == 0) {
                return Redirect()->route('customer_package')->with('error', MAXIMUM_LIMIT_REACHED);
            }

            $total_amenities = $detail->rPackage->total_amenities;
            $total_photos = $detail->rPackage->total_photos;
            $total_videos = $detail->rPackage->total_videos;
            $total_social_items = $detail->rPackage->total_social_items;
            $total_additional_features = $detail->rPackage->total_additional_features;
            $allow_featured = $detail->rPackage->allow_featured;
        }

        $g_setting = GeneralSetting::where('id', 1)->first();
        $listing = Listing::get();
        $listing_brand = ListingBrand::orderBy('id','asc')->get();
        $listing_location = ListingLocation::orderBy('id','asc')->get();
        $amenity = Amenity::orderBy('id','asc')->get();
        return view('front.customer_listing_add', compact('g_setting','listing','listing_brand','listing_location','amenity', 'listing_error_message','total_amenities','total_photos','total_videos','total_social_items','total_additional_features','allow_featured','page_other_item'));
    }

    public function listing_add_store(Request $request) 
    {
        if(env('PROJECT_MODE') == 0) {
            return redirect()->back()->with('error', env('PROJECT_NOTIFICATION'));
        }

        $user_data = Auth::user();
        $request->validate([
            'listing_name' => 'required|unique:listings',
            'listing_slug' => 'unique:listings',
            'listing_description' => 'required',
            'listing_featured_photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'listing_price' => 'required|numeric'
        ],
        [
            'listing_name.required' => ERR_NAME_REQUIRED,
            'listing_name.unique' => ERR_NAME_EXIST,
            'listing_slug.unique' => ERR_SLUG_UNIQUE,
            'listing_description.required' => ERR_DESCRIPTION_REQUIRED,
            'listing_featured_photo.required' => ERR_PHOTO_REQUIRED,
            'listing_featured_photo.image' => ERR_PHOTO_IMAGE,
            'listing_featured_photo.mimes' => ERR_PHOTO_JPG_PNG_GIF,
            'listing_featured_photo.max' => ERR_PHOTO_MAX,
            'listing_price.required' => ERR_PRICE_REQUIRED,
            'listing_price.numeric' => ERR_PRICE_NUMERIC
        ]);

        $statement = DB::select("SHOW TABLE STATUS LIKE 'listings'");
        $ai_id = $statement[0]->Auto_increment;

        $rand_value = md5(mt_rand(11111111,99999999));
        $ext = $request->file('listing_featured_photo')->extension();
        $final_name = $rand_value.'.'.$ext;
        $request->file('listing_featured_photo')->move(public_path('uploads/listing_featured_photos'), $final_name);

        $listing = new Listing();
        $data = $request->only($listing->getFillable());
        if(empty($data['listing_slug'])) {
            unset($data['listing_slug']);
            $data['listing_slug'] = Str::slug($request->listing_name);
        }
        if(preg_match('/\s/',$data['listing_slug'])) {
            return Redirect()->back()->with('error', ERR_SLUG_WHITESPACE);
        }
        $data['listing_featured_photo'] = $final_name;
        $data['user_id'] = $user_data->id;
        $data['admin_id'] = 0;
        $data['listing_status'] = 'Pending';
        if($request->is_featured == null) {
            $data['is_featured'] = 'No';
        } else {
            $data['is_featured'] = $request->is_featured;
        }
        $listing->fill($data)->save();

        // Amenity
        if($request->amenity != '') {
            $arr_amenity = array();
            foreach($request->amenity as $item) {
                $arr_amenity[] = $item;
            }
            for($i=0;$i<count($arr_amenity);$i++) {
                $obj = new ListingAmenity;
                $obj->listing_id = $ai_id;
                $obj->amenity_id = $arr_amenity[$i];
                $obj->save();
            }
        }

        // Photo
        if($request->photo_list == '') {
            // No photo is selected
        } else {
            foreach($request->photo_list as $item) {
                $file_in_mb = $item->getSize()/1024/1024;
                $main_file_ext = $item->extension();
                $main_mime_type = $item->getMimeType();
                if( ($main_mime_type == 'image/jpeg' || $main_mime_type == 'image/png' || $main_mime_type == 'image/gif') && $file_in_mb <= 2 ) {
                    $rand_value = md5(mt_rand(11111111,99999999));
                    $final_photo_name = $rand_value.'.'.$main_file_ext;
                    $item->move(public_path('uploads/listing_photos'), $final_photo_name);

                    $obj = new ListingPhoto;
                    $obj->listing_id = $ai_id;
                    $obj->photo = $final_photo_name;
                    $obj->save();
                }
            }
        }


        // Video
        if($request->youtube_video_id[0] != '') {
            $arr_youtube_video_id = array();
            foreach($request->youtube_video_id as $item) {
                $arr_youtube_video_id[] = $item;
            }
            for($i=0;$i<count($arr_youtube_video_id);$i++) {
                if($arr_youtube_video_id[$i] != '') {
                    $obj = new ListingVideo;
                    $obj->listing_id = $ai_id;
                    $obj->youtube_video_id = $arr_youtube_video_id[$i];
                    $obj->save();
                }
            }
        }


        // Social Icons
        if($request->social_icon[0] != '') {
            $arr_social_icon = array();
            $arr_social_url = array();
            foreach($request->social_icon as $item) {
                $arr_social_icon[] = $item;
            }
            foreach($request->social_url as $item) {
                $arr_social_url[] = $item;
            }
            for($i=0;$i<count($arr_social_icon);$i++) {
                if( ($arr_social_icon[$i] != '') && ($arr_social_url[$i] != '') ) {
                    $obj = new ListingSocialItem;
                    $obj->listing_id = $ai_id;
                    $obj->social_icon = $arr_social_icon[$i];
                    $obj->social_url = $arr_social_url[$i];
                    $obj->save();
                }
            }
        }


        // Additional Features
        if($request->additional_feature_name[0] != '') {
            $arr_additional_feature_name = array();
            $arr_additional_feature_value = array();
            foreach($request->additional_feature_name as $item) {
                $arr_additional_feature_name[] = $item;
            }
            foreach($request->additional_feature_value as $item) {
                $arr_additional_feature_value[] = $item;
            }
            for($i=0;$i<count($arr_additional_feature_name);$i++) {
                if( ($arr_additional_feature_name[$i] != '') && ($arr_additional_feature_value[$i] != '') ) {
                    $obj = new ListingAdditionalFeature;
                    $obj->listing_id = $ai_id;
                    $obj->additional_feature_name = $arr_additional_feature_name[$i];
                    $obj->additional_feature_value = $arr_additional_feature_value[$i];
                    $obj->save();
                }
            }
        }
        return redirect()->route('customer_listing_view')->with('success', SUCCESS_LISTING_ADD);
    }

    public function listing_edit($id) 
    {
        $user_data = Auth::user();
        $check_other = Listing::where('id', $id)->first();

        $page_other_item = PageOtherItem::where('id',1)->first();

        if( (!$check_other) || ($check_other->user_id != $user_data->id)) {
            abort(404);
        }

        $g_setting = GeneralSetting::where('id', 1)->first();

        $listing = Listing::where('id', $id)->first();
        $listing_brand = ListingBrand::orderBy('id','asc')->get();
        $listing_location = ListingLocation::orderBy('id','asc')->get();
        $amenity = Amenity::orderBy('id','asc')->get();

        $existing_amenities_array = array();
        $listing_amenities = ListingAmenity::where('listing_id',$id)->orderBy('id','asc')->get();
        foreach($listing_amenities as $row) {
            $existing_amenities_array[] = $row->amenity_id;
        }

        $listing_photos = ListingPhoto::where('listing_id',$id)->orderBy('id','asc')->get();
        $listing_videos = ListingVideo::where('listing_id',$id)->orderBy('id','asc')->get();
        $listing_additional_features = ListingAdditionalFeature::where('listing_id',$id)->orderBy('id','asc')->get();
        $listing_social_items = ListingSocialItem::where('listing_id',$id)->orderBy('id','asc')->get();

        $detail = PackagePurchase::with('rPackage')
            ->where('user_id', $user_data->id)
            ->where('currently_active', 1)
            ->first();

        $total_amenities = $detail->rPackage->total_amenities;
        $total_photos = $detail->rPackage->total_photos;
        $total_videos = $detail->rPackage->total_videos;
        $total_social_items = $detail->rPackage->total_social_items;
        $total_additional_features = $detail->rPackage->total_additional_features;
        $allow_featured = $detail->rPackage->allow_featured;

        return view('front.customer_listing_edit', compact('g_setting','listing','listing_brand','listing_location','amenity','listing_photos','listing_videos','listing_additional_features','listing_social_items','listing_amenities','existing_amenities_array','total_amenities','total_photos','total_videos','total_social_items','total_additional_features','allow_featured','page_other_item'));
    }

    public function listing_update(Request $request, $id) 
    {
        if(env('PROJECT_MODE') == 0) {
            return redirect()->back()->with('error', env('PROJECT_NOTIFICATION'));
        }

        $user_data = Auth::user();
        $listing = Listing::findOrFail($id);
        $data = $request->only($listing->getFillable());

        if ($request->hasFile('listing_featured_photo')) {

            $request->validate([
                'listing_featured_photo' => 'image|mimes:jpeg,png,jpg,gif|max:2048'
            ],[
                'listing_featured_photo.image' => ERR_PHOTO_IMAGE,
                'listing_featured_photo.mimes' => ERR_PHOTO_JPG_PNG_GIF,
                'listing_featured_photo.max' => ERR_PHOTO_MAX
            ]);

            unlink(public_path('uploads/listing_featured_photos/'.$listing->listing_featured_photo));

            // Uploading the file
            $ext = $request->file('listing_featured_photo')->extension();
            $rand_value = md5(mt_rand(11111111,99999999));
            $final_name = $rand_value.'.'.$ext;
            $request->file('listing_featured_photo')->move(public_path('uploads/listing_featured_photos/'), $final_name);

            unset($data['listing_featured_photo']);
            $data['listing_featured_photo'] = $final_name;
        }

        $request->validate([
            'listing_name'   =>  [
                'required',
                Rule::unique('listings')->ignore($id),
            ],
            'listing_slug'   =>  [
                Rule::unique('listings')->ignore($id),
            ],
            'listing_description' => 'required',
            'listing_price' => 'required|numeric'
        ],[
            'listing_name.required' => ERR_NAME_REQUIRED,
            'listing_name.unique' => ERR_NAME_EXIST,
            'listing_slug.unique' => ERR_SLUG_UNIQUE,
            'listing_description.required' => ERR_DESCRIPTION_REQUIRED,
            'listing_price.required' => ERR_PRICE_REQUIRED,
            'listing_price.numeric' => ERR_PRICE_NUMERIC
        ]);
        if(empty($data['listing_slug'])) {
            unset($data['listing_slug']);
            $data['listing_slug'] = Str::slug($request->listing_name);
        }
        if(preg_match('/\s/',$data['listing_slug'])) {
            return Redirect()->back()->with('error', ERR_SLUG_WHITESPACE);
        }
        $listing->fill($data)->save();


        // Amenity
        $existing_amenities_array = array();
        $arr_amenity = array();
        $result1 = array();
        $result2 = array();

        $listing_amenities = ListingAmenity::where('listing_id',$id)->orderBy('id','asc')->get();
        foreach($listing_amenities as $row) {
            $existing_amenities_array[] = $row->amenity_id;
        }

        if($request->amenity != '') {
            foreach($request->amenity as $item) {
                $arr_amenity[] = $item;
            }
        }

        $result1 = array_values(array_diff($existing_amenities_array, $arr_amenity));
        if(!empty($result1)) {
            for($i=0;$i<count($result1);$i++) {
                ListingAmenity::where('listing_id', $id)
                    ->where('amenity_id', $result1[$i])
                    ->delete();
            }
        }

        $result2 = array_values(array_diff($arr_amenity,$existing_amenities_array));
        if(!empty($result2)) {
            for($i=0;$i<count($result2);$i++) {
                $obj = new ListingAmenity;
                $obj->listing_id = $id;
                $obj->amenity_id = $result2[$i];
                $obj->save();
            }
        }


        // Photo
        if($request->photo_list == '') {
            // No photo is selected
        } else {
            foreach($request->photo_list as $item) {
                $file_in_mb = $item->getSize()/1024/1024;
                $main_file_ext = $item->extension();
                $main_mime_type = $item->getMimeType();

                if( ($main_mime_type == 'image/jpeg' || $main_mime_type == 'image/png' || $main_mime_type == 'image/gif') && $file_in_mb <= 2 ) {
                    $rand_value = md5(mt_rand(11111111,99999999));
                    $final_photo_name = $rand_value.'.'.$main_file_ext;
                    $item->move(public_path('uploads/listing_photos'), $final_photo_name);

                    $obj = new ListingPhoto;
                    $obj->listing_id = $id;
                    $obj->photo = $final_photo_name;
                    $obj->save();
                }
            }
        }


        // Video
        if($request->youtube_video_id != '') {
            $arr_youtube_video_id = array();
            foreach($request->youtube_video_id as $item) {
                $arr_youtube_video_id[] = $item;
            }
            for($i=0;$i<count($arr_youtube_video_id);$i++) {
                if($arr_youtube_video_id[$i] != '') {
                    $obj = new ListingVideo;
                    $obj->listing_id = $id;
                    $obj->youtube_video_id = $arr_youtube_video_id[$i];
                    $obj->save();
                }
            }
        }


        // Social Icons
        if($request->social_icon != '') {
            $arr_social_icon = array();
            $arr_social_url = array();
            foreach($request->social_icon as $item) {
                $arr_social_icon[] = $item;
            }
            foreach($request->social_url as $item) {
                $arr_social_url[] = $item;
            }
            for($i=0;$i<count($arr_social_icon);$i++) {
                if( ($arr_social_icon[$i] != '') && ($arr_social_url[$i] != '') ) {
                    $obj = new ListingSocialItem;
                    $obj->listing_id = $id;
                    $obj->social_icon = $arr_social_icon[$i];
                    $obj->social_url = $arr_social_url[$i];
                    $obj->save();
                }
            }
        }


        // Additional Features
        if($request->additional_feature_name != '') {
            $arr_additional_feature_name = array();
            $arr_additional_feature_value = array();
            foreach($request->additional_feature_name as $item) {
                $arr_additional_feature_name[] = $item;
            }
            foreach($request->additional_feature_value as $item) {
                $arr_additional_feature_value[] = $item;
            }
            for($i=0;$i<count($arr_additional_feature_name);$i++) {
                if( ($arr_additional_feature_name[$i] != '') && ($arr_additional_feature_value[$i] != '') ) {
                    $obj = new ListingAdditionalFeature;
                    $obj->listing_id = $id;
                    $obj->additional_feature_name = $arr_additional_feature_name[$i];
                    $obj->additional_feature_value = $arr_additional_feature_value[$i];
                    $obj->save();
                }
            }
        }
        return redirect()->route('customer_listing_view')->with('success', SUCCESS_LISTING_EDIT);
    }

    public function listing_delete($id) 
    {
        if(env('PROJECT_MODE') == 0) {
            return redirect()->back()->with('error', env('PROJECT_NOTIFICATION'));
        }

        $listing = Listing::findOrFail($id);
        unlink(public_path('uploads/listing_featured_photos/'.$listing->listing_featured_photo));
        $listing->delete();

        ListingAmenity::where('listing_id', $id)->delete();
        ListingSocialItem::where('listing_id', $id)->delete();
        ListingVideo::where('listing_id', $id)->delete();
        ListingAdditionalFeature::where('listing_id', $id)->delete();

        $all_photos = ListingPhoto::where('listing_id',$id)->get();
        foreach($all_photos as $item) {
            unlink(public_path('uploads/listing_photos/'.$item->photo));
        }

        ListingPhoto::where('listing_id', $id)->delete();

        // Success Message and redirect
        return Redirect()->back()->with('success', SUCCESS_LISTING_DELETE);
    }


    public function listing_delete_social_item($id) 
    {
        if(env('PROJECT_MODE') == 0) {
            return redirect()->back()->with('error', env('PROJECT_NOTIFICATION'));
        }

        $listing_social_item = ListingSocialItem::findOrFail($id);
        $listing_social_item->delete();
        return Redirect()->back()->with('success', SUCCESS_SOCIAL_ITEM_DELETE);
    }

    public function listing_delete_photo($id) 
    {
        if(env('PROJECT_MODE') == 0) {
            return redirect()->back()->with('error', env('PROJECT_NOTIFICATION'));
        }

        $listing_photo = ListingPhoto::findOrFail($id);
        unlink(public_path('uploads/listing_photos/'.$listing_photo->photo));
        $listing_photo->delete();
        return Redirect()->back()->with('success', SUCCESS_PHOTO_DELETE);
    }

    public function listing_delete_video($id) 
    {
        if(env('PROJECT_MODE') == 0) {
            return redirect()->back()->with('error', env('PROJECT_NOTIFICATION'));
        }

        $listing_video = ListingVideo::findOrFail($id);
        $listing_video->delete();
        return Redirect()->back()->with('success', SUCCESS_VIDEO_DELETE);
    }

    public function listing_delete_additional_feature($id) 
    {
        if(env('PROJECT_MODE') == 0) {
            return redirect()->back()->with('error', env('PROJECT_NOTIFICATION'));
        }

        $listing_additional_feature = ListingAdditionalFeature::findOrFail($id);
        $listing_additional_feature->delete();
        return Redirect()->back()->with('success', SUCCESS_ADDITIONAL_FEATURE_DELETE);
    }

    public function submit_review(Request $request) 
    {
        if(env('PROJECT_MODE') == 0) {
            return redirect()->back()->with('error', env('PROJECT_NOTIFICATION'));
        }

        $user_data = Auth::user();
        $request->validate([
            'review' => 'required'
        ], [
            'review.required' => ERR_REVIEW_REQUIRED
        ]);

        // Logged in user. As this is front end, user must be a customer
        $review = new Review;
        $review->listing_id = $request->listing_id;
        $review->agent_id = $user_data->id;
        $review->agent_type = 'Customer';
        $review->rating = $request->rating;
        $review->review = $request->review;
        $review->save();

        return Redirect()->back()->with('success', SUCCESS_RATING_PLACED);
    }

    public function package() 
    {
        $g_setting = GeneralSetting::where('id', 1)->first();
        $page_other_item = PageOtherItem::where('id',1)->first();
        $package = Package::orderBy('package_order', 'asc')->get();
        return view('front.customer_package', compact('g_setting','package','page_other_item'));
    }

    public function free_enroll($id) 
    {
        if(env('PROJECT_MODE') == 0) {
            return redirect()->back()->with('error', env('PROJECT_NOTIFICATION'));
        }

        $user_data = Auth::user();

        // Make all other previous packages status to 0 and this package status 1
        $data['currently_active'] = 0;

        PackagePurchase::where('user_id',$user_data->id)->update($data);

        // Selected Package Detail
        $package_detail = Package::where('id',$id)->first();
        $valid_days = $package_detail->valid_days;
        $start_date = date('Y-m-d');
        $end_date = date('Y-m-d', strtotime("+$valid_days days"));

        $obj = new PackagePurchase;
        $obj->user_id = $user_data->id;
        $obj->package_id = $id;
        $obj->transaction_id = '';
        $obj->paid_amount = 0;
        $obj->payment_method = '';
        $obj->payment_status = 'Completed';
        $obj->package_start_date = $start_date;
        $obj->package_end_date = $end_date;
        $obj->currently_active = 1;
        $obj->save();
        return Redirect()->route('customer_package_purchase_history')->with('success', SUCCESS_PACKAGE_ENROLL);
    }

    public function my_reviews() 
    {
        $user_data = Auth::user();
        $g_setting = GeneralSetting::where('id', 1)->first();

        $page_other_item = PageOtherItem::where('id',1)->first();

        $detail = PackagePurchase::with('rPackage')
            ->where('user_id', $user_data->id)
            ->where('currently_active', 1)
            ->first();

        if($detail == null) {
            return Redirect()->route('customer_package')->with('error', ERR_ENROLL_PACKAGE_FIRST);
        }

        // Date Over Check
        $today = date('Y-m-d');
        $expire_date = $detail->package_end_date;
        if($today > $expire_date) {
            return Redirect()->route('customer_package')->with('error', ERR_LISTING_DATE_EXPIRED);
        }


        $reviews = Review::where('agent_id', $user_data->id)->where('agent_type', 'Customer')
            ->orderBy('id', 'asc')
            ->paginate(10);
        return view('front.customer_my_reviews', compact('g_setting','reviews','page_other_item'));
    }

    public function review_edit($id) 
    {
        $g_setting = GeneralSetting::where('id', 1)->first();
        $page_other_item = PageOtherItem::where('id',1)->first();
        $review_single = Review::findOrFail($id);
        return view('front.customer_my_review_edit', compact('review_single','page_other_item'));
    }

    public function review_update(Request $request, $id) {

        if(env('PROJECT_MODE') == 0) {
            return redirect()->back()->with('error', env('PROJECT_NOTIFICATION'));
        }

        $review = Review::findOrFail($id);
        $data = $request->only($review->getFillable());
        $request->validate([
            'review' => 'required'
        ]);
        $review->fill($data)->save();
        return redirect()->route('customer_my_reviews')->with('success', SUCCESS_REVIEW_UPDATE);
    }

    public function review_delete($id) 
    {
        if(env('PROJECT_MODE') == 0) {
            return redirect()->back()->with('error', env('PROJECT_NOTIFICATION'));
        }

        $review = Review::findOrFail($id);
        $review->delete();
        return Redirect()->back()->with('success', SUCCESS_REVIEW_DELETE);
    }

    public function wishlist() 
    {
        $user_data = Auth::user();
        $g_setting = GeneralSetting::where('id', 1)->first();

        $page_other_item = PageOtherItem::where('id',1)->first();

        $detail = PackagePurchase::with('rPackage')
            ->where('user_id', $user_data->id)
            ->where('currently_active', 1)
            ->first();

        if($detail == null) {
            return Redirect()->route('customer_package')->with('error', ERR_ENROLL_PACKAGE_FIRST);
        }

        // Date Over Check
        $today = date('Y-m-d');
        $expire_date = $detail->package_end_date;
        if($today > $expire_date) {
            return Redirect()->route('customer_package')->with('error', ERR_LISTING_DATE_EXPIRED);
        }

        
        $wishlist = Wishlist::where('user_id', $user_data->id)->orderBy('id', 'asc')->paginate(10);
        return view('front.customer_wishlist', compact('g_setting','wishlist','page_other_item'));
    }

    public function wishlist_delete($id) 
    {
        if(env('PROJECT_MODE') == 0) {
            return redirect()->back()->with('error', env('PROJECT_NOTIFICATION'));
        }

        $obj = Wishlist::findOrFail($id);
        $obj->delete();
        return Redirect()->back()->with('success', SUCCESS_ITEM_REMOVED_FROM_WISHLIST);
    }

    public function purchase_history() 
    {
        $user_data = Auth::user();
        $g_setting = GeneralSetting::where('id', 1)->first();
        $page_other_item = PageOtherItem::where('id',1)->first();
        $package_purchase = PackagePurchase::with('rPackage')
            ->where('user_id', $user_data->id)
            ->orderBy('id', 'desc')
            ->get();
        return view('front.customer_package_purchase_history', compact('g_setting','package_purchase','page_other_item'));
    }

    public function purchase_history_detail($id) 
    {
        $user_data = Auth::user();
        $g_setting = GeneralSetting::where('id', 1)->first();
        $page_other_item = PageOtherItem::where('id',1)->first();
        $detail = PackagePurchase::with('rPackage')
            ->where('id', $id)
            ->first();
        if(!$detail) {
            abort(404);
        }
        return view('front.customer_package_purchase_history_detail', compact('g_setting','detail','page_other_item'));
    }

    public function invoice($id) 
    {
        $user_data = Auth::user();
        $g_setting = GeneralSetting::where('id', 1)->first();
        $page_other_item = PageOtherItem::where('id',1)->first();
        $detail = PackagePurchase::with('rPackage')
            ->where('id', $id)
            ->first();

        if(!$detail) {
            abort(404);
        }
        return view('front.customer_package_purchase_invoice', compact('user_data','g_setting','detail','page_other_item'));
    }

    public function buy_package($id) 
    {
        $g_setting = GeneralSetting::where('id',1)->first();
        $package_detail = Package::where('id',$id)->first();
        $page_other_item = PageOtherItem::where('id',1)->first();
        session()->put('package_id',$id);
        session()->put('package_name',$package_detail->package_name);
        session()->put('package_price',$package_detail->package_price);
        return view('front.customer_package_buy', compact('g_setting','page_other_item'));
    }

    public function paypal() 
    {
        if(!session()->get('package_id')) {
            return redirect()->to('/');
        }

        $user_data = Auth::user();
        $g_setting = GeneralSetting::where('id',1)->first();
        $client = $g_setting->paypal_client_id;
        $secret = $g_setting->paypal_secret_key;

        $final_price = session()->get('package_price')*session()->get('currency_value');
        $final_price = round($final_price,2);

        $admin_amount = session()->get('package_price');

        $apiContext = new \PayPal\Rest\ApiContext(
            new \PayPal\Auth\OAuthTokenCredential(
                $client, // ClientID
                $secret // ClientSecret
            )
        );

        $paymentId = request('paymentId');
        $payment = Payment::get($paymentId, $apiContext);

        $execution = new PaymentExecution();
        $execution->setPayerId(request('PayerID'));

        $transaction = new Transaction();
        $amount = new Amount();
        $details = new Details();

        $details->setShipping(0)
                ->setTax(0)
                ->setSubtotal($final_price);

        $amount->setCurrency(session()->get('currency_name'));
        $amount->setTotal($final_price);
        $amount->setDetails($details);
        $transaction->setAmount($amount);

        $execution->addTransaction($transaction);

        $result = $payment->execute($execution, $apiContext);


        if($result->state == 'approved')
        {
            if(env('PROJECT_MODE') == 0) {
                return Redirect()->route('customer_package_purchase_history')->with('error', env('PROJECT_NOTIFICATION'));
            } else {

                $paid_amount = $result->transactions[0]->amount->total;

                // Make all other previous packages status to 0 and this package status 1
                $data['currently_active'] = 0;
                PackagePurchase::where('user_id',$user_data->id)->update($data);

                // Selected Package Detail
                $package_detail = Package::where('id',session()->get('package_id'))->first();
                $valid_days = $package_detail->valid_days;
                $start_date = date('Y-m-d');
                $end_date = date('Y-m-d', strtotime("+$valid_days days"));

                $obj = new PackagePurchase;
                $obj->user_id = $user_data->id;
                $obj->package_id = session()->get('package_id');
                $obj->transaction_id = $paymentId;
                $obj->paid_amount = $final_price;
                $obj->paid_currency = session()->get('currency_name');
                $obj->paid_currency_symbol = session()->get('currency_symbol');
                $obj->admin_amount = $admin_amount;
                $obj->payment_method = 'PayPal';
                $obj->payment_status = 'Completed';
                $obj->package_start_date = $start_date;
                $obj->package_end_date = $end_date;
                $obj->currently_active = 1;
                $obj->save();


                // Send Email To Customer
                $payment_method = 'PayPal';
                $et_data = EmailTemplate::where('id', 8)->first();
                $subject = $et_data->et_subject;
                $message = $et_data->et_content;

                $message = str_replace('[[customer_name]]', $user_data->name, $message);
                $message = str_replace('[[transaction_id]]', $paymentId, $message);
                $message = str_replace('[[payment_method]]', $payment_method, $message);
                $message = str_replace('[[paid_amount]]', session()->get('currency_symbol').$paid_amount, $message);
                $message = str_replace('[[payment_status]]', 'Completed', $message);
                $message = str_replace('[[package_start_date]]', $start_date, $message);
                $message = str_replace('[[package_end_date]]', $end_date, $message);
                Mail::to($user_data->email)->send(new PurchaseCompletedEmailToCustomer($subject,$message));

                session()->forget('package_id');
                session()->forget('package_name');
                session()->forget('package_price');

                return Redirect()->route('customer_package_purchase_history')->with('success', SUCCESS_PACKAGE_PURCHASE);
            }
        }
        else {
            return redirect()->to('/');
        }
    }

    // public function stripe()
    // {
    //     if(!session()->get('package_id')) {
    //         return redirect()->to('/');
    //     }

    //     $user_data = Auth::user();
    //     $g_setting = GeneralSetting::where('id', 1)->first();
    //     $stripe_secret_key = $g_setting->stripe_secret_key;

    //     $admin_amount = session()->get('package_price');
    //     $final_price = $admin_amount*session()->get('currency_value');
    //     $final_price = round($final_price,2);

    //     \Stripe\Stripe::setApiKey($stripe_secret_key);

    //     if(isset($_POST['stripeToken']))
    //     {
    //         \Stripe\Stripe::setVerifySslCerts(false);

    //         $token = $_POST['stripeToken'];
    //         $response = \Stripe\Charge::create([
    //             'amount' => $final_price*100,
    //             'currency' => session()->get('currency_name'),
    //             'description' => 'Stripe Payment',
    //             'source' => $token,
    //             'metadata' => ['order_id' => uniqid()],
    //         ]);

    //         $bal = \Stripe\BalanceTransaction::retrieve($response->balance_transaction);
    //         $balJson = $bal->jsonSerialize();

    //         if(env('PROJECT_MODE') == 0) {
    //             return Redirect()->route('customer_package_purchase_history')->with('error', env('PROJECT_NOTIFICATION'));
    //         } else {
    //             $paid_amount = $balJson['amount']/100;

    //             // Make all other previous packages status to 0 and this package status 1
    //             $data['currently_active'] = 0;
    //             PackagePurchase::where('user_id',$user_data->id)->update($data);
    
    //             // Selected Package Detail
    //             $package_detail = Package::where('id',session()->get('package_id'))->first();
    //             $valid_days = $package_detail->valid_days;
    //             $start_date = date('Y-m-d');
    //             $end_date = date('Y-m-d', strtotime("+$valid_days days"));    
    
    //             $obj = new PackagePurchase;
    //             $obj->user_id = $user_data->id;
    //             $obj->package_id = session()->get('package_id');
    //             $obj->transaction_id = $response->balance_transaction;
    //             $obj->paid_amount = $final_price;
    //             $obj->paid_currency = session()->get('currency_name');
    //             $obj->paid_currency_symbol = session()->get('currency_symbol');
    //             $obj->admin_amount = $admin_amount;
    //             $obj->payment_method = 'Stripe';
    //             $obj->payment_status = 'Completed';
    //             $obj->package_start_date = $start_date;
    //             $obj->package_end_date = $end_date;
    //             $obj->currently_active = 1;
    //             $obj->save();
    
    //             // Send Email To Customer
    //             $payment_method = 'Stripe';
    
    //             $et_data = EmailTemplate::where('id', 8)->first();
    //             $subject = $et_data->et_subject;
    //             $message = $et_data->et_content;
    
    //             $message = str_replace('[[customer_name]]', $user_data->name, $message);
    //             $message = str_replace('[[transaction_id]]', $response->balance_transaction, $message);
    //             $message = str_replace('[[payment_method]]', $payment_method, $message);
    //             $message = str_replace('[[paid_amount]]', session()->get('currency_symbol').$paid_amount, $message);
    //             $message = str_replace('[[payment_status]]', 'Completed', $message);
    //             $message = str_replace('[[package_start_date]]', $start_date, $message);
    //             $message = str_replace('[[package_end_date]]', $end_date, $message);
    //             Mail::to($user_data->email)->send(new PurchaseCompletedEmailToCustomer($subject,$message));
    
    //             session()->forget('package_id');
    //             session()->forget('package_name');
    //             session()->forget('package_price');

    //             return Redirect()->route('customer_package_purchase_history')->with('success', SUCCESS_PACKAGE_PURCHASE);
    //         }
            
    //     }

    // }
    public function stripe(Request $request)
    {
        // if (!session()->get('package_id') && !session()->get('listing_id')) {
        //     return redirect()->to('/');
        // }

        $user_data = Auth::user();
        $g_setting = GeneralSetting::where('id', 1)->first();
        $stripe_secret_key = $g_setting->stripe_secret_key;

        $payment_type = $request->input('payment_type'); // package ya listing
       
        if ($payment_type == 'package') {
            $admin_amount = session()->get('package_price');
        } elseif ($payment_type == 'listing') {
            $admin_amount = $request->input('final_price');
        } else {
            return redirect()->back()->with('error', 'Invalid payment type.');
        }
        
        $final_price =(int) $admin_amount;
        
        
        \Stripe\Stripe::setApiKey($stripe_secret_key);

        if (isset($_POST['stripeToken'])) {
            \Stripe\Stripe::setVerifySslCerts(false);

            $token = $_POST['stripeToken'];
            $response = \Stripe\Charge::create([
                'amount' => $final_price,
                'currency' => session()->get('currency_name'),
                'description' => ucfirst($payment_type) . ' Payment',
                'source' => $token,
                'metadata' => ['order_id' => uniqid()],
            ]);
            
            $bal = \Stripe\BalanceTransaction::retrieve($response->balance_transaction);
            $balJson = $bal->jsonSerialize();

            if (env('PROJECT_MODE') == 0) {
                return Redirect()->route('customer_package_purchase_history')->with('error', env('PROJECT_NOTIFICATION'));
            } else {
                $paid_amount = $balJson['amount'];

                if ($payment_type == 'package') {
                    // Make all other previous packages inactive
                    PackagePurchase::where('user_id', $user_data->id)->update(['currently_active' => 0]);

                    $package_detail = Package::where('id', session()->get('package_id'))->first();
                    $valid_days = $package_detail->valid_days;
                    $start_date = date('Y-m-d');
                    $end_date = date('Y-m-d', strtotime("+$valid_days days"));

                    $obj = new PackagePurchase();
                    $obj->user_id = $user_data->id;
                    $obj->package_id = session()->get('package_id');
                    $obj->package_start_date = $start_date;
                    $obj->package_end_date = $end_date;
                    $obj->currently_active = 1;
                    $obj->transaction_id = $response->balance_transaction;
                    $obj->paid_amount = $final_price/100;
                    $obj->paid_currency = session()->get('currency_name');
                    $obj->paid_currency_symbol = session()->get('currency_symbol');
                    $obj->admin_amount = $admin_amount;
                    $obj->payment_method = 'Stripe';
                    $obj->payment_status = 'Completed';
                    $obj->save();
                } else {
                    $obj = new Listing();
                    $obj->id = $request->input('listing_id');;
                    $order_id = $request->input('order_id');
                    Order::where('id', $order_id)->update([
                        'payment_status' => 'Paid',
                        'paid_amount' => $final_price/100,
                        'paid_currency' => session()->get('currency_name'),
                        'payment_method' => 'Stripe',
                    ]);
                    Listing::where('id', $request->input('listing_id'))->update([
                        'is_sold' => '1',
                    ]);
                }

                

                // Send Email
                $et_data = EmailTemplate::where('id', 8)->first();
                $subject = $et_data->et_subject;
                $message = $et_data->et_content;

                $message = str_replace('[[customer_name]]', $user_data->name, $message);
                $message = str_replace('[[transaction_id]]', $response->balance_transaction, $message);
                $message = str_replace('[[payment_method]]', 'Stripe', $message);
                $message = str_replace('[[paid_amount]]', session()->get('currency_symbol') . $paid_amount, $message);
                $message = str_replace('[[payment_status]]', 'Completed', $message);

                Mail::to($user_data->email)->send(new PurchaseCompletedEmailToCustomer($subject, $message));

                // Forget sessions
                if ($payment_type == 'package') {
                    session()->forget('package_id');
                    session()->forget('package_price');
                } else {
                    session()->forget('listing_id');
                    session()->forget('listing_price');
                }

                return Redirect()->route('customer_package_purchase_history')->with('success', SUCCESS_PACKAGE_PURCHASE);
            }
        }
    }

    public function stripe_inspection(Request $request)
    {
        // if (!session()->get('package_id') && !session()->get('listing_id')) {
        //     return redirect()->to('/');
        // }

        $user_data = Auth::user();
        $g_setting = GeneralSetting::where('id', 1)->first();
        $stripe_secret_key = $g_setting->stripe_secret_key;

        
        $final_price =200;
        
        
        \Stripe\Stripe::setApiKey($stripe_secret_key);

        if (isset($_POST['stripeToken'])) {
            \Stripe\Stripe::setVerifySslCerts(false);

            $token = $_POST['stripeToken'];
            $response = \Stripe\Charge::create([
                'amount' => $final_price,
                'currency' => session()->get('currency_name'),
                'description' =>  'Inspection Payment',
                'source' => $token,
                'metadata' => ['order_id' => uniqid()],
            ]);
            
            $bal = \Stripe\BalanceTransaction::retrieve($response->balance_transaction);
            $balJson = $bal->jsonSerialize();

            if (env('PROJECT_MODE') == 0) {
                return Redirect()->route('customer_package_purchase_history')->with('error', env('PROJECT_NOTIFICATION'));
            } else {
                $paid_amount = $balJson['amount'];

                
                    
                
                    $listing = Listing::findOrFail($request->input('listing_id'));

                    // Check if user is a buyer
                    if (Auth::user()->user_role !== 'buyer') {
                        return redirect()->back()->with('error', 'Only buyers can request an inspection.');
                    }
            
                    // Check if request already exists
                    $exists = InspectionRequest::where('buyer_id', Auth::id())
                                               ->where('listing_id', $request->input('listing_id'))
                                               ->where('status', 'Pending')
                                               ->exists();
            
                    if ($exists) {
                        return redirect()->back()->with('error', 'You have already requested an inspection for this listing.');
                    }
            
                    // Create a new request
                    InspectionRequest::create([
                        'buyer_id' => Auth::id(),
                        'seller_id' => $request->input('seller_id'),
                        'listing_id' => $request->input('listing_id'),
                        'status' => 'Accepted'
                    ]);
            
                    return redirect()->back()->with('success', 'Inspection request sent successfully.');
                

               
               

                // return Redirect()->route('customer_package_purchase_history')->with('success', SUCCESS_PACKAGE_PURCHASE);
            }
        }
    }

    public function razorpay(Request $request)
    {
        if(!session()->get('package_id')) {
            return redirect()->to('/');
        }

        $user_data = Auth::user();
        $g_setting = GeneralSetting::where('id', 1)->first();

        $admin_amount = session()->get('package_price');
        $final_price = $admin_amount*session()->get('currency_value');
        $final_price = round($final_price,2);

        $input = $request->all();
        $api = new Api($g_setting->razorpay_key_id,$g_setting->razorpay_key_secret);
        $payment = $api->payment->fetch($input['razorpay_payment_id']);

        if(count($input)&&!empty($input['razorpay_payment_id'])) {
            try {
                $response = $api->payment->fetch($input['razorpay_payment_id'])->capture(array('amount'=>$payment['amount']));

                $payId = $response->id;

                if(env('PROJECT_MODE') == 0) {
                    return Redirect()->route('customer_package_purchase_history')->with('error', env('PROJECT_NOTIFICATION'));
                } else {

                    // Make all other previous packages status to 0 and this package status 1
                    $data['currently_active'] = 0;
                    PackagePurchase::where('user_id',$user_data->id)->update($data);
        
                    // Selected Package Detail
                    $package_detail = Package::where('id',session()->get('package_id'))->first();
                    $valid_days = $package_detail->valid_days;
                    $start_date = date('Y-m-d');
                    $end_date = date('Y-m-d', strtotime("+$valid_days days"));    
        
                    $obj = new PackagePurchase;
                    $obj->user_id = $user_data->id;
                    $obj->package_id = session()->get('package_id');
                    $obj->transaction_id = $payId;
                    $obj->paid_amount = $final_price;
                    $obj->paid_currency = session()->get('currency_name');
                    $obj->paid_currency_symbol = session()->get('currency_symbol');
                    $obj->admin_amount = $admin_amount;
                    $obj->payment_method = 'RazorPay';
                    $obj->payment_status = 'Completed';
                    $obj->package_start_date = $start_date;
                    $obj->package_end_date = $end_date;
                    $obj->currently_active = 1;
                    $obj->save();
        
                    // Send Email To Customer
                    $payment_method = 'Razorpay';
        
                    $et_data = EmailTemplate::where('id', 8)->first();
                    $subject = $et_data->et_subject;
                    $message = $et_data->et_content;
        
                    $message = str_replace('[[customer_name]]', $user_data->name, $message);
                    $message = str_replace('[[transaction_id]]', $payId, $message);
                    $message = str_replace('[[payment_method]]', $payment_method, $message);
                    $message = str_replace('[[paid_amount]]', session()->get('currency_symbol').$final_price, $message);
                    $message = str_replace('[[payment_status]]', 'Completed', $message);
                    $message = str_replace('[[package_start_date]]', $start_date, $message);
                    $message = str_replace('[[package_end_date]]', $end_date, $message);
                    Mail::to($user_data->email)->send(new PurchaseCompletedEmailToCustomer($subject,$message));
        
                    session()->forget('package_id');
                    session()->forget('package_name');
                    session()->forget('package_price');

                    return Redirect()->route('customer_package_purchase_history')->with('success', SUCCESS_PACKAGE_PURCHASE);
                }
            } catch (Exception $e) {
                return Redirect()->back()->with('error', ERR_PAYMENT_FAILED);
            }
        }
    }


    public function flutterwave(Request $request)
    {
        if(!session()->get('package_id')) {
            return redirect()->to('/');
        }

        $user_data = Auth::user();
        $g_setting = GeneralSetting::where('id', 1)->first();

        $admin_amount = session()->get('package_price');
        $final_price = $admin_amount*session()->get('currency_value');
        $final_price = round($final_price,2);

        $curl = curl_init();
        $tnx_id = $request->tnx_id;
        $url = "https://api.flutterwave.com/v3/transactions/$tnx_id/verify";
        $token = $g_setting->flutterwave_secret_key;
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "Content-Type: application/json",
                "Authorization: Bearer $token"
            ),
        ));
        $response = curl_exec($curl);
        curl_close($curl);
        $response = json_decode($response);

        if($response->status == 'success') {
            if(env('PROJECT_MODE') == 0) {
                return Redirect()->route('customer_package_purchase_history')->with('error', env('PROJECT_NOTIFICATION'));
            } else {
                // Make all other previous packages status to 0 and this package status 1
                $data['currently_active'] = 0;
                PackagePurchase::where('user_id',$user_data->id)->update($data);
    
                // Selected Package Detail
                $package_detail = Package::where('id',session()->get('package_id'))->first();
                $valid_days = $package_detail->valid_days;
                $start_date = date('Y-m-d');
                $end_date = date('Y-m-d', strtotime("+$valid_days days"));    
    
                $obj = new PackagePurchase;
                $obj->user_id = $user_data->id;
                $obj->package_id = session()->get('package_id');
                $obj->transaction_id = $tnx_id;
                $obj->paid_amount = $final_price;
                $obj->paid_currency = session()->get('currency_name');
                $obj->paid_currency_symbol = session()->get('currency_symbol');
                $obj->admin_amount = $admin_amount;
                $obj->payment_method = 'Flutterwave';
                $obj->payment_status = 'Completed';
                $obj->package_start_date = $start_date;
                $obj->package_end_date = $end_date;
                $obj->currently_active = 1;
                $obj->save();
    
                // Send Email To Customer
                $payment_method = 'Flutterwave';
    
                $et_data = EmailTemplate::where('id', 8)->first();
                $subject = $et_data->et_subject;
                $message = $et_data->et_content;
    
                $message = str_replace('[[customer_name]]', $user_data->name, $message);
                $message = str_replace('[[transaction_id]]', $tnx_id, $message);
                $message = str_replace('[[payment_method]]', $payment_method, $message);
                $message = str_replace('[[paid_amount]]', session()->get('currency_symbol').$final_price, $message);
                $message = str_replace('[[payment_status]]', 'Completed', $message);
                $message = str_replace('[[package_start_date]]', $start_date, $message);
                $message = str_replace('[[package_end_date]]', $end_date, $message);
                Mail::to($user_data->email)->send(new PurchaseCompletedEmailToCustomer($subject,$message));
    
                session()->forget('package_id');
                session()->forget('package_name');
                session()->forget('package_price');

                return Redirect()->route('customer_package_purchase_history')->with('success', SUCCESS_PACKAGE_PURCHASE);
            }
        } else {
            return Redirect()->back()->with('error', ERR_PAYMENT_FAILED);
        }
    }


    public function mollie(Request $request)
    {
        if(!session()->get('package_id')) {
            return redirect()->to('/');
        }

        $user_data = Auth::user();
        $g_setting = GeneralSetting::where('id', 1)->first();

        $admin_amount = session()->get('package_price');
        $final_price = $admin_amount*session()->get('currency_value');
        $final_price = round($final_price,2);

        Mollie::api()->setApiKey($g_setting->mollie_api_key);

        $payment = Mollie::api()->payments()->create([
            'amount' => [
                'currency' => session()->get('currency_name'),
                'value' => ''.sprintf('%0.2f', $final_price).'',
            ],
            'description' => env('APP_NAME'),
            'redirectUrl' => route('customer_payment_mollie_notify'),
        ]);
        $payment = Mollie::api()->payments()->get($payment->id);

        session()->put('payment_id',$payment->id);

        return redirect($payment->getCheckoutUrl(), 303);
    }

    public function mollie_notify(Request $request)
    {
        $user_data = Auth::user();
        $g_setting = GeneralSetting::where('id', 1)->first();

        $admin_amount = session()->get('package_price');
        $final_price = $admin_amount*session()->get('currency_value');
        $final_price = round($final_price,2);

        Mollie::api()->setApiKey($g_setting->mollie_api_key);
        $payment = Mollie::api()->payments->get(session()->get('payment_id'));
        if ($payment->isPaid())
        {
            if(env('PROJECT_MODE') == 0) {
                return Redirect()->route('customer_package_purchase_history')->with('error', env('PROJECT_NOTIFICATION'));
            } else {
                
                // Make all other previous packages status to 0 and this package status 1
                $data['currently_active'] = 0;
                PackagePurchase::where('user_id',$user_data->id)->update($data);
    
                // Selected Package Detail
                $package_detail = Package::where('id',session()->get('package_id'))->first();
                $valid_days = $package_detail->valid_days;
                $start_date = date('Y-m-d');
                $end_date = date('Y-m-d', strtotime("+$valid_days days"));    
    
                $obj = new PackagePurchase;
                $obj->user_id = $user_data->id;
                $obj->package_id = session()->get('package_id');
                $obj->transaction_id = $payment->id;
                $obj->paid_amount = $final_price;
                $obj->paid_currency = session()->get('currency_name');
                $obj->paid_currency_symbol = session()->get('currency_symbol');
                $obj->admin_amount = $admin_amount;
                $obj->payment_method = 'Mollie';
                $obj->payment_status = 'Completed';
                $obj->package_start_date = $start_date;
                $obj->package_end_date = $end_date;
                $obj->currently_active = 1;
                $obj->save();
    
                // Send Email To Customer
                $payment_method = 'Mollie';
    
                $et_data = EmailTemplate::where('id', 8)->first();
                $subject = $et_data->et_subject;
                $message = $et_data->et_content;
    
                $message = str_replace('[[customer_name]]', $user_data->name, $message);
                $message = str_replace('[[transaction_id]]', $payment->id, $message);
                $message = str_replace('[[payment_method]]', $payment_method, $message);
                $message = str_replace('[[paid_amount]]', session()->get('currency_symbol').$final_price, $message);
                $message = str_replace('[[payment_status]]', 'Completed', $message);
                $message = str_replace('[[package_start_date]]', $start_date, $message);
                $message = str_replace('[[package_end_date]]', $end_date, $message);
                Mail::to($user_data->email)->send(new PurchaseCompletedEmailToCustomer($subject,$message));
    
                session()->forget('package_id');
                session()->forget('package_name');
                session()->forget('package_price');

                return Redirect()->route('customer_package_purchase_history')->with('success', SUCCESS_PACKAGE_PURCHASE);
            }
        }
        else {
            return Redirect()->back()->with('error', ERR_PAYMENT_FAILED);
        }
    }

    public function requestToBuy($listing_id)
    {
        $listing = Listing::findOrFail($listing_id);

        if ($listing->is_sold) {
            return redirect()->back()->with('error', 'This listing is already sold.');
        }

        $seller_id = $listing->user_id; // Seller is the one who created the listing

        // Ensure seller_id is valid
        if (!$seller_id) {
            return back()->with('error', 'Seller information is missing.');
        }

        Order::create([
            'listing_id' => $listing->id,
            'buyer_id' => auth()->id(),
            'seller_id' => $listing->user_id,
            'status' => 'pending',
            'payment_status' => 'pending',
        ]);

        return redirect()->back()->with('success', 'Your purchase request has been sent.');
    }


}
