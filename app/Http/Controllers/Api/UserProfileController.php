<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Models\Listing;

class UserProfileController extends Controller
{
     public function profile(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found.'
            ], 404);
        }

        // User listings
        $listings = Listing::where('user_id', $user->id)->get();

        return response()->json([
            'success' => true,
            'message' => 'User profile fetched successfully.',
            'data' => [
                'user' => $user,
                'listings' => $listings
            ]
        ]);
    }

    
    public function update(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'name' => 'nullable|string',
            'phone' => 'nullable|string',
            'country' => 'nullable|string',
            'address' => 'nullable|string',
            'state' => 'nullable|string',
            'city' => 'nullable|string',
            'zip' => 'nullable|string',
            'photo' => 'nullable|file|mimes:jpg,jpeg,png|max:2048',
            'password' => 'nullable|string|min:6',
        ]);

        $user = User::where('email', $request->email)->first();

        $user->name = $request->name ?? $user->name;
        $user->phone = $request->phone ?? $user->phone;
        $user->country = $request->country ?? $user->country;
        $user->address = $request->address ?? $user->address;
        $user->state = $request->state ?? $user->state;
        $user->city = $request->city ?? $user->city;
        $user->zip = $request->zip ?? $user->zip;

        // Photo Upload
        if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/user_photos'), $fileName);
            $user->photo = 'uploads/user_photos/' . $fileName;
        }

        // Password update
        if ($request->password) {
            $user->password = Hash::make($request->password);
        }

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
