<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BusinessLocation;
use App\Models\BusinessProfile; // imported the right model for the foreign key relationship

class BusinessLocationController extends Controller
{
        public function store(Request $request)
    {
        $validated = $request->validate([
            'latitude'  => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'address'   => 'nullable|string|max:255',
        ]);

        $location = auth()->user()->business_profile->locations()->create($validated);

        return response()->json([
            'message'  => 'Location pinned successfully!',
            'location' => $location,
        ], 201);
    }

        public function index()
        {
            $profile = auth()->user()->business_profile;
            $locations = $profile->locations()->get(['id', 'latitude', 'longitude', 'address']);

            return view('business.locations.index', [
                'locations' => $locations,
            ]);
        }

        public function destroy(BusinessLocation $location)
    {
        abort_unless($location->business_profile_id === auth()->user()->business_profile->id, 403);

        $location->delete();

        return response()->json(['message' => 'Location deleted successfully!']);
    }

}
