<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\LanguageMenuText;
use App\Models\LanguageNotificationText;
use App\Models\LanguageWebsiteText;
use App\Models\User;
use App\Models\Review;
use App\Models\GeneralSetting;
use App\Models\EmailTemplate;
use App\Models\PageOtherItem;
use App\Mail\RegistrationEmailToCustomer;
use App\Mail\ResetPasswordMessageToCustomer;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use DB;
use Hash;
use Auth;
use Illuminate\Support\Facades\Mail;
use App\Services\TwilioService;
use Session;


class CustomerAuthController extends Controller
{
    protected $twilio;
	public function __construct(TwilioService $twilio) {
        $this->middleware('guest:web')->except('logout');
        $this->twilio = $twilio;
    }

    public function login() {
        $page_other_item = PageOtherItem::where('id',1)->first();
    	return response()->json(["success" => true, "message" => "login executed successfully."]);
    }

  
    public function login_store(Request $request) {
        $g_setting = GeneralSetting::where('id', 1)->first();
            $request->validate([
                'email' => 'required|email',
                'password' => 'required',
            ]);
        
            if ($g_setting->google_recaptcha_status == 'Show') {
                $request->validate([
                    'g-recaptcha-response' => 'required'
                ]);
    }
        
            $credential = [
                'email' => $request->email,
                'password' => $request->password,
                'status' => 'Active'
            ];
        
            if (Auth::guard('web')->attempt($credential)) {
                $user = Auth::guard('web')->user();
        
                if (!$user->phone) {
                    return redirect()->back()->with('error', 'Phone number is required for OTP verification.');
                }
        
                // Generate OTP
                $otp = rand(100000, 999999);
                Session::put('auth_user_id', $user->id);
                Session::put('auth_otp', $otp);
                Session::put('otp_verified', false); // ✅ OTP verified nahi hai
        
                // Send OTP via Twilio
                // $this->twilio->sendOtp($user->phone, $otp);
                return redirect()->route('customer_otp');
                // return redirect()->route('homes'); // ✅ Redirect to OTP page
            } else {
                return redirect()->back()->with('error', 'Invalid credentials.');
            }
        }
        
    
    public function showOtpVerification() {
        return response()->json(["success" => true, "message" => "showOtpVerification executed successfully."]);
    }

    public function verifyOtp(Request $request) {
        $request->validate(['otp' => 'required']);

    $authUserId = Session::get('auth_user_id');
    $sessionOtp = Session::get('auth_otp');

    if (!$authUserId || !$sessionOtp) {
        return redirect()->route('customer_login')->with('error', 'Session expired, please login again.');
    }

    dd($request);
    if ($request->otp == $sessionOtp) {
        Auth::loginUsingId($authUserId);
        Session::forget(['auth_otp']); // ✅ Remove OTP from session
        Session::put('otp_verified', true); // ✅ Mark OTP as verified

        return redirect()->route('customer_dashboard')->with('success', 'Login successful.');
    } else {
        return redirect()->back()->with('error', 'Invalid OTP.');
    }
    }

    public function logout() {
        Auth::guard('web')->logout();
        return redirect()->route('customer_login');
    }

    public function registration() {
        $page_other_item = PageOtherItem::where('id',1)->first();
    	return response()->json(["success" => true, "message" => "registration executed successfully."]);
    }

    public function registration_store(Request $request) {
        if(env('PROJECT_MODE') == 0) {
            return redirect()->back()->with('error', env('PROJECT_NOTIFICATION'));
    }

        $g_setting = GeneralSetting::where('id', 1)->first();
        $token = hash('sha256',time());
        $obj = new User();
        $data = $request->only($obj->getFillable());
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required',
            're_password' => 'required|same:password',
            'user_role' => 'required'
        ], [
            'name.required' => ERR_NAME_REQUIRED,
            'email.required' => ERR_EMAIL_REQUIRED,
            'email.email' => ERR_EMAIL_INVALID,
            'password.required' => ERR_PASSWORD_REQUIRED,
            're_password.required' => ERR_RE_PASSWORD_REQUIRED,
            're_password.same' => ERR_PASSWORDS_MATCH,
            'user_role.required' => ERR_ROLE_REQUIRED
        ]);

        if($g_setting->google_recaptcha_status == 'Show') {
            $request->validate([
                'g-recaptcha-response' => 'required'
            ], [
               'g-recaptcha-response.required' => ERR_RECAPTCHA_REQUIRED
            ]);
        }
        unset($request->re_password);
        $data['password'] = Hash::make($request->password);
        $data['token'] = $token;
        $data['status'] = 'Pending';
        $obj->fill($data)->save();

        // Send Email
        $et_data = EmailTemplate::where('id', 6)->first();
        $subject = $et_data->et_subject;
        $message = $et_data->et_content;
        $verification_link = url('customer/registration/verify/'.$token.'/'.$request->email);
        $message = str_replace('[[verification_link]]', $verification_link, $message);
        Mail::to($request->email)->send(new RegistrationEmailToCustomer($subject,$message));
        return redirect()->back()->with('success', SUCCESS_REGISTRATION_EMAIL_SEND);
    }

    public function registration_verify() {
        if(env('PROJECT_MODE') == 0) {
            return redirect()->back()->with('error', env('PROJECT_NOTIFICATION'));
    }

        $email_from_url = request()->segment(count(request()->segments()));
        $aa = User::where('email', $email_from_url)->first();
        if(!$aa) {
            return redirect()->route('customer_login');
        }
        $expected_url = url('customer/registration/verify/'.$aa->token.'/'.$aa->email);
        $current_url = url()->current();
        if($expected_url != $current_url) {
            return redirect()->route('customer_login');
        }
        $data['status'] = 'Active';
        $data['token'] = '';
        User::where('email',$email_from_url)->update($data);
        return redirect()->route('customer_login')->with('success', SUCCESS_REGISTRATION_VERIFY_DONE);
    }


    public function forget_password() {
        $page_other_item = PageOtherItem::where('id',1)->first();
        return response()->json(["success" => true, "message" => "forget_password executed successfully."]);
    }

    public function forget_password_store(Request $request) {
        if(env('PROJECT_MODE') == 0) {
            return redirect()->back()->with('error', env('PROJECT_NOTIFICATION'));
    }

        $g_setting = GeneralSetting::where('id', 1)->first();
        $request->validate([
            'email' => 'required|email'
        ],[
            'email.required' => ERR_EMAIL_REQUIRED,
            'email.email' => ERR_EMAIL_INVALID
        ]);

        if($g_setting->google_recaptcha_status == 'Show') {
            $request->validate([
                'g-recaptcha-response' => 'required'
            ], [
                'g-recaptcha-response.required' => ERR_RECAPTCHA_REQUIRED
            ]);
        }

        $check_email = User::where('email',$request->email)->where('status','Active')->first();
        if(!$check_email) {
            return redirect()->back()->with('error', ERR_EMAIL_NOT_FOUND);
        } else {
            $et_data = EmailTemplate::where('id', 7)->first();
            $subject = $et_data->et_subject;
            $message = $et_data->et_content;
            $token = hash('sha256',time());
            $reset_link = url('customer/reset-password/'.$token.'/'.$request->email);
            $message = str_replace('[[reset_link]]', $reset_link, $message);

            $data['token'] = $token;
            User::where('email',$request->email)->update($data);
            Mail::to($request->email)->send(new ResetPasswordMessageToCustomer($subject,$message));
        }
        return redirect()->back()->with('success', SUCCESS_FORGET_PASSWORD_EMAIL_SEND);
    }


    public function reset_password() {
        $page_other_item = PageOtherItem::where('id',1)->first();

        $g_setting = GeneralSetting::where('id', 1)->first();
        $email_from_url = request()->segment(count(request()->segments()));

        $aa = User::where('email', $email_from_url)->first();

        if(!$aa) {
            return redirect()->route('customer_login');
    }

        $expected_url = url('customer/reset-password/'.$aa->token.'/'.$aa->email);
        $current_url = url()->current();
        if($expected_url != $current_url) {
            return redirect()->route('customer_login');
        }
        $email = $aa->email;
        return view('front.customer_reset_password', compact('g_setting', 'email', 'page_other_item'));
    }

    public function reset_password_update(Request $request) {
        if(env('PROJECT_MODE') == 0) {
            return redirect()->back()->with('error', env('PROJECT_NOTIFICATION'));
    }
        
        $g_setting = GeneralSetting::where('id', 1)->first();
        $request->validate([
            'new_password' => 'required',
            'retype_password' => 'required|same:new_password',
        ], [
            'new_password.required' => ERR_NEW_PASSWORD_REQUIRED,
            'retype_password.required' => ERR_RE_PASSWORD_REQUIRED,
            'retype_password.same' => ERR_PASSWORDS_MATCH
        ]);

        if($g_setting->google_recaptcha_status == 'Show') {
            $request->validate([
                'g-recaptcha-response' => 'required'
            ], [
                'g-recaptcha-response.required' => ERR_RECAPTCHA_REQUIRED
            ]);
        }

        $data['password'] = Hash::make($request->new_password);
        $data['token'] = '';
        User::where('email', $request->current_email)->update($data);
        return redirect()->route('customer_login')->with('success', SUCCESS_RESET_PASSWORD);
    }
}
