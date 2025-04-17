<?php 
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class CheckOtpVerification
{
   public function handle(Request $request, Closure $next)
{
    // Debugging: Check if route is being detected correctly
    if ($request->route()) {
        \Log::info("Middleware Route: " . $request->route()->getName());
    } else {
        \Log::info("Middleware: No route detected");
    }

    // Skip OTP check for OTP page itself
    if ($request->route() && $request->route()->getName() === 'customer_otp_verify') {
        return $next($request);
    }

    // Check if OTP is verified in session
    if (!Session::has('otp_verified') || !Session::get('otp_verified')) {
        return redirect()->route('customer_otp_verify');
    }

    return $next($request);
}

}

