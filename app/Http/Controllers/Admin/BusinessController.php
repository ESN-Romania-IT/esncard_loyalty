<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BusinessProfile;

class BusinessController extends Controller
{
    public function index(Request $request)
    {
         $q = $request->query('q');

       $businesses = BusinessProfile::query()
        ->with('user')
        ->when($q, function ($query) use ($q) {
            $query->where(function ($sub) use ($q) {
                $sub->where('business_name', 'like', "%{$q}%")
                    ->orWhereHas('user', function ($user) use ($q) {
                        $user->where('email', 'like', "%{$q}%");
                    });
            });
        })
        ->orderByDesc('id')
        ->paginate(10)
        ->withQueryString();

        return view('admin.businesses.index', [
            'businesses' => $businesses,
            'q' => $q,
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(BusinessProfile $business)
    {
        return redirect()->route('admin.businesses.offers.index', $business);
    }
}
