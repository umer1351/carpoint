<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserProfileController extends Controller
{
    public function update(Request $request)
    {
        // Validation
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'name' => 'nullable|string',
            'password' => 'nullable|string|min:6',
            'country' => 'nullable|string',
            'address' => 'nullable|string',
            'state' => 'nullable|string',
            'city' => 'nullable|string',
            'zip' => 'nullable|string'
           
        ]);

        // User fetch
        $user = User::where('email', $request->email)->first();

        // Fields update
        if ($request->name) $user->name = $request->name;
        if ($request->country) $user->country = $request->country;
        if ($request->address) $user->address = $request->address;
        if ($request->state) $user->state = $request->state;
        if ($request->city) $user->city = $request->city;
        if ($request->zip) $user->zip = $request->zip;
        if ($request->photo) $user->photo = $request->photo;
        if ($request->password) $user->password = Hash::make($request->password);

        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Profile updated successfully!',
            'data' => $user
        ]);
    }

    public function delete(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        $user = User::where('email', $request->email)->first();
        $user->delete();

        return response()->json([
            'success' => true,
            'message' => 'Account deleted successfully.'
        ]);
    }
}
