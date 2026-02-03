<?php

namespace App\Http\Controllers\Business;

use App\Http\Controllers\Controller;
use App\Models\Offer;
use App\Models\OfferRedemption;
use Illuminate\Http\Request;

class BusinessOfferController extends Controller
{
    public function index(Request $request)
    {
        $businessProfile = $request->user()?->business_profile;

        abort_unless($businessProfile, 403);

        $q = $request->query('q');
        $active = $request->query('active');
        $sort = $request->query('sort', 'redemptions_desc');

        $offers = Offer::query()
            ->where('business_profile_id', $businessProfile->id)
            ->withCount('redemptions')
            ->when($q, fn ($query) => $query->where('title', 'like', "%{$q}%"))
            ->when($active !== null && $active !== '', function ($query) use ($active) {
                $query->where('is_active', (bool) ((int) $active));
            })
            ->when($sort, function ($query) use ($sort) {
                return match ($sort) {
                    'redemptions_asc' => $query->orderBy('redemptions_count', 'asc'),
                    'redemptions_desc' => $query->orderBy('redemptions_count', 'desc'),
                    default => $query->orderBy('redemptions_count', 'desc'),
                };
            })
            ->orderBy('title')
            ->paginate(10)
            ->withQueryString();

        return view('business.offers.index', [
            'offers' => $offers,
            'q' => $q,
            'active' => $active,
            'sort' => $sort,
        ]);
    }

    public function show(Request $request, Offer $offer)
    {
        $businessProfile = $request->user()?->business_profile;

        abort_unless($businessProfile, 403);
        abort_unless((int) $offer->business_profile_id === (int) $businessProfile->id, 404);

        $offer->loadCount('redemptions');

        $clients = OfferRedemption::query()
            ->where('offer_id', $offer->id)
            ->selectRaw('client_profile_id, COUNT(*) as used_count, MAX(redeemed_at) as last_redeemed_at')
            ->groupBy('client_profile_id')
            ->with(['clientProfile'])
            ->orderByDesc('used_count')
            ->get();

        return view('business.offers.show', [
            'offer' => $offer,
            'clients' => $clients,
        ]);
    }

    public function edit(Request $request, Offer $offer)
    {
        $businessProfile = $request->user()?->business_profile;

        abort_unless($businessProfile, 403);
        abort_unless((int) $offer->business_profile_id === (int) $businessProfile->id, 404);

        return view('business.offers.edit', [
            'offer' => $offer,
        ]);
    }

    public function update(Request $request, Offer $offer)
    {
        $businessProfile = $request->user()?->business_profile;

        abort_unless($businessProfile, 403);
        abort_unless((int) $offer->business_profile_id === (int) $businessProfile->id, 404);

        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'uses_per_client' => ['required', 'integer', 'min:1', 'max:1000'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $offer->update([
            'title' => $data['title'],
            'uses_per_client' => $data['uses_per_client'],
            'is_active' => (bool) ($data['is_active'] ?? false),
        ]);

        return redirect()
            ->route('business.offers.show', $offer)
            ->with('status', 'Offer updated successfully.');
    }

    public function create(Request $request)
    {
        $businessProfile = $request->user()?->business_profile;

        abort_unless($businessProfile, 403);

        return view('business.offers.create');
    }

    public function store(Request $request)
    {
        $businessProfile = $request->user()?->business_profile;

        abort_unless($businessProfile, 403);

        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'uses_per_client' => ['required', 'integer', 'min:1', 'max:1000'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        Offer::create([
            'business_profile_id' => $businessProfile->id,
            'title' => $data['title'],
            'uses_per_client' => $data['uses_per_client'],
            'is_active' => (bool) ($data['is_active'] ?? false),
        ]);

        return redirect()
            ->route('business.offers.index')
            ->with('status', 'Offer created successfully.');
    }
}
