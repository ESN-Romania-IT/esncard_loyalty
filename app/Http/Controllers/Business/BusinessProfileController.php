<?php

namespace App\Http\Controllers\Business;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class BusinessProfileController extends Controller
{
    public function edit(Request $request)
    {
        $user = $request->user();
        $locations = $user->business_profile->locations()->get(['id', 'latitude', 'longitude', 'address']);

        return view('business.profile', [
            'user' => $user,
            'locations' => $locations,
        ]);
    }

    public function update(Request $request)
    {
        $user = $request->user();
        $businessProfile = $user->business_profile;

        $validated = $request->validate([
            'business_name' => [
                'required', 'string', 'max:255',
                Rule::unique('business_profiles', 'business_name')->ignore($businessProfile->id),
            ],
            'email' => [
                'required', 'string', 'email', 'max:255',
                Rule::unique('users', 'email')->ignore($user->id),
            ],
        ]);

        $businessProfile->update(['business_name' => $validated['business_name']]);
        $user->update(['email' => $validated['email']]);

        return response()->json([
            'message' => 'Profile updated successfully!',
            'business_name' => $businessProfile->business_name,
            'email' => $user->email,
        ]);
    }
}
