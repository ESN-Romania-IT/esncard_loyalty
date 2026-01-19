<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\ClientProfile;
use App\Models\BusinessProfile;
use App\Models\OfferRedemption;

class ClientController extends Controller
{
   public function index(Request $request)
    {
        $q = $request->query('q');

        $clients = ClientProfile::query()
        ->with('user') // needed for email
        ->when($q, function ($query) use ($q) {
            $query->where(function ($sub) use ($q) {
                $sub->where('first_name', 'like', "%{$q}%")
                    ->orWhere('last_name', 'like', "%{$q}%")
                    ->orWhereHas('user', function ($user) use ($q) {
                        $user->where('email', 'like', "%{$q}%");
                    });
            });
        })
        ->orderByDesc('id')
        ->paginate(10)
        ->withQueryString();

        return view('admin.clients.index', [
            'clients' => $clients,
            'q' => $q,
        ]);
    }

    public function show(Request $request, ClientProfile $client)
    {
        $q = $request->query('q');

        $businesses = BusinessProfile::query()
            ->select('id', 'business_name')
            ->with(['offers' => function ($q) {
                $q->where('is_active', true)
                ->select('id', 'business_profile_id', 'title', 'uses_per_client');
            }])
            ->orderBy('business_name')
            ->get();

        $usedByOffer = OfferRedemption::query()
            ->where('client_profile_id', $client->id)
            ->selectRaw('offer_id, COUNT(*) as used_count')
            ->groupBy('offer_id')
            ->pluck('used_count', 'offer_id');

        // Group redemptions by offer (and business) for this client
        $rows = DB::table('offer_redemptions as r')
            ->join('offers as o', 'o.id', '=', 'r.offer_id')
            ->join('business_profiles as b', 'b.id', '=', 'o.business_profile_id')
            ->where('r.client_profile_id', $client->id)
            ->when($q, function ($query) use ($q) {
                $query->where(function ($sub) use ($q) {
                    $sub->where('b.business_name', 'like', "%{$q}%")
                        ->orWhere('o.title', 'like', "%{$q}%");
                });
            })
            ->groupBy(
                'o.id',
                'o.title',
                'o.uses_per_client',
                'b.id',
                'b.business_name'
            )
            ->select([
                'o.id as offer_id',
                'o.title as offer_title',
                'o.uses_per_client',
                'b.id as business_id',
                'b.business_name',
                DB::raw('COUNT(*) as redeemed_count'),
            ])
            ->orderByDesc('redeemed_count')
            ->paginate(15)
            ->withQueryString();

        return view('admin.clients.show', [
            'q' => $q,
            'client' => $client,
            'rows' => $rows,
            'businesses' => $businesses,
            'usedByOffer' => $usedByOffer
        ]);
    }
}
