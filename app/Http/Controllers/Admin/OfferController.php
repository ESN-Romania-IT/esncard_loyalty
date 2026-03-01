<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BusinessProfile;
use App\Models\Offer;
use App\Models\OfferRedemption;
use App\Models\ClientProfile;
use Illuminate\Support\Facades\DB;

class OfferController extends Controller
{
    public function index(BusinessProfile $business)
    {
        $offers = Offer::query()
            ->where('business_profile_id', $business->id)
            ->orderByDesc('id')
            ->paginate(10)
            ->withQueryString();

        return view('admin.offers.index', [
            'business' => $business,
            'offers' => $offers,
        ]);
    }

    public function create(BusinessProfile $business)
    {
        return view('admin.offers.create', [
            'business' => $business,
        ]);
    }

    public function store(Request $request, BusinessProfile $business)
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'uses_per_client' => ['required', 'integer', 'min:1', 'max:1000'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        Offer::create([
            'business_profile_id' => $business->id,
            'title' => $data['title'],
            'uses_per_client' => $data['uses_per_client'],
            'is_active' => (bool)($data['is_active'] ?? false),
        ]);
        return redirect()
            ->route('admin.businesses.offers.index', $business)
            ->with('status', 'Offer created successfully.');
    }

    public function show(BusinessProfile $business, Offer $offer)
    {
        $this->ensureOfferBelongsToBusiness($business, $offer);

        $redemptions = OfferRedemption::query()
        ->where('offer_id', $offer->id)
        ->selectRaw('client_profile_id, COUNT(*) as used_count')
        ->groupBy('client_profile_id')
        ->with(['clientProfile.user'])
        ->orderByDesc('used_count')
        ->paginate(15)
        ->withQueryString();

        $clients = ClientProfile::query()->with('user')->orderBy('first_name')->get();


        $totalRedeemed = OfferRedemption::where('offer_id', $offer->id)->count();

        return view('admin.offers.show', [
            'business' => $business,
            'offer' => $offer,
            'redemptions' => $redemptions,
            'clients' => $clients,
            'totalRedeemed' => $totalRedeemed,
        ]);
    }

    public function edit(BusinessProfile $business, Offer $offer)
    {
        $this->ensureOfferBelongsToBusiness($business, $offer);

        return view('admin.offers.edit', [
            'business' => $business,
            'offer' => $offer,
        ]);
    }

    public function update(Request $request, BusinessProfile $business, Offer $offer)
    {
        $this->ensureOfferBelongsToBusiness($business, $offer);

        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'uses_per_client' => ['required', 'integer', 'min:1', 'max:1000'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $offer->update([
            'title' => $data['title'],
            'uses_per_client' => $data['uses_per_client'],
            'is_active' => (bool)($data['is_active'] ?? false),
        ]);

        return redirect()
            ->route('admin.businesses.offers.index', $business)
            ->with('status', 'Offer updated successfully.');
    }

    public function destroy(BusinessProfile $business, Offer $offer)
    {
        $this->ensureOfferBelongsToBusiness($business, $offer);

        $offer->delete();

        return redirect()
            ->route('admin.businesses.offers.index', $business)
            ->with('status', 'Offer deleted successfully.');
    }

    private function ensureOfferBelongsToBusiness(BusinessProfile $business, Offer $offer): void
    {
        abort_unless((int)$offer->business_profile_id === (int)$business->id, 404);
    }
}
