<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $customer = User::where('email', $request->email)->where('status', 'Active')->first();

        if (!$customer || !Hash::check($request->password, $customer->password)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        $token = $customer->createToken('auth_token')->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'User' => $customer
        ]);
    }
    public function register(Request $request) {
        if(env('PROJECT_MODE') == 0) {
            return response()->json(['error' => env('PROJECT_NOTIFICATION')], 400);
        }
    
        $obj = new User();
        $data = $request->only($obj->getFillable());
    
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required',
            're_password' => 'required|same:password',
            'user_role' => 'required'
        ]);
    
        // Generate a unique token
        $token = Str::random(60);  // You can adjust the length if needed
    
        unset($request->re_password);
        $data['token'] = $token;
        $data['password'] = Hash::make($request->password);
        $data['status'] = 'Active';  // Set status to Active
        $obj->fill($data)->save();
    
        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'message' => 'Registration successful. Your account is now active.',
        ]);
    }
    
}
