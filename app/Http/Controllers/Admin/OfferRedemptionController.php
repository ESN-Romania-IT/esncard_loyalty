<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BusinessProfile;
use App\Models\ClientProfile;
use App\Models\Offer;
use App\Models\OfferRedemption;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OfferRedemptionController extends Controller
{
    public function store(Request $request, BusinessProfile $business, Offer $offer)
    {
        abort_unless((int)$offer->business_profile_id === (int)$business->id, 404);

        $data = $request->validate([
            'client_profile_id' => ['required', 'integer', 'exists:client_profiles,id'], // adjust if needed
            'qty' => ['required', 'integer', 'min:1', 'max:100'],
        ]);

        $clientId = (int) $data['client_profile_id'];
        $qtyRequested = (int) $data['qty'];

        try {
            $added = 0;

            DB::transaction(function () use ($offer, $clientId, $qtyRequested, &$added) {

                $lockedOffer = Offer::whereKey($offer->id)->lockForUpdate()->firstOrFail();

                if (!$lockedOffer->is_active) {
                    throw new \RuntimeException('This offer is not active.');
                }

                $used = OfferRedemption::where('offer_id', $lockedOffer->id)
                    ->where('client_profile_id', $clientId)
                    ->lockForUpdate()
                    ->count();

                $remaining = max(0, (int)$lockedOffer->uses_per_client - $used);
                $qtyToCreate = min($qtyRequested, $remaining);

                if ($qtyToCreate <= 0) {
                    throw new \RuntimeException('Client has already reached the maximum uses for this offer.');
                }

                for ($i = 0; $i < $qtyToCreate; $i++) {
                    OfferRedemption::create([
                        'offer_id' => $lockedOffer->id,
                        'client_profile_id' => $clientId,
                        'business_profile_id' => $lockedOffer->business_profile_id,
                        'redeemed_at' => now(),
                    ]);
                }

                $added = $qtyToCreate;
            });

            $msg = ($added === $qtyRequested)
                ? "Added {$added} redemption(s)."
                : "Requested {$qtyRequested}, but only {$added} were possible. Added {$added}.";

            return back()->with('status', $msg);

        } catch (\RuntimeException $e) {
            return back()->with('error', $e->getMessage());
        } catch (\Throwable $e) {
            return back()->with('error', 'Something went wrong while redeeming.');
        }
    }



    public function destroy(BusinessProfile $business, Offer $offer, OfferRedemption $redemption)
    {
        // Ensure offer belongs to business
        abort_unless((int) $offer->business_profile_id === (int) $business->id, 404);

        // Ensure redemption belongs to that offer and business
        abort_unless((int) $redemption->offer_id === (int) $offer->id, 404);
        abort_unless((int) $redemption->business_profile_id === (int) $business->id, 404);

        $redemption->delete();

        return back()->with('status', 'Redemption removed successfully.');
    }

    public function destroyForClient(Request $request, BusinessProfile $business, Offer $offer)
    {
        abort_unless((int)$offer->business_profile_id === (int)$business->id, 404);

        $data = $request->validate([
            'client_profile_id' => ['required', 'integer', 'exists:client_profiles,id'],
            'qty' => ['required', 'integer', 'min:1', 'max:100'],
        ]);

        $clientId = (int) $data['client_profile_id'];
        $qtyRequested = (int) $data['qty'];

        $deleted = 0;

        DB::transaction(function () use ($offer, $clientId, $qtyRequested, &$deleted) {
            $ids = OfferRedemption::where('offer_id', $offer->id)
                ->where('client_profile_id', $clientId)
                ->orderBy('id', 'asc')
                ->lockForUpdate()
                ->limit($qtyRequested)
                ->pluck('id');

            if ($ids->isEmpty()) {
                return;
            }

            $deleted = OfferRedemption::whereIn('id', $ids)->delete();
        });

        if ($deleted === 0) {
            return back()->with('error', 'No redemptions exist for that client on this offer.');
        }

        return back()->with('status', "Removed {$deleted} redemption(s).");
    }

}
