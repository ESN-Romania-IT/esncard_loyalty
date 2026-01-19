<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ClientProfile;
use App\Models\Offer;
use App\Models\OfferRedemption;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ClientRedemptionController extends Controller
{

    public function store(Request $request, ClientProfile $client)
    {
        $data = $request->validate([
            'offer_id' => ['required', 'integer', 'exists:offers,id'],
            'qty' => ['required', 'integer', 'min:1', 'max:100'],
        ]);

        try {
            DB::transaction(function () use ($data, $client) {
                // Lock offer row for consistent max_uses_per_client
                $offer = Offer::whereKey($data['offer_id'])->lockForUpdate()->firstOrFail();

                if (!$offer->is_active) {
                    throw new \RuntimeException('This offer is not active.');
                }

                $qtyRequested = (int) $data['qty'];

                $used = OfferRedemption::where('offer_id', $offer->id)
                    ->where('client_profile_id', $client->id)
                    ->lockForUpdate()
                    ->count();

                $remaining = max(0, (int)$offer->uses_per_client - $used);
                $qtyToCreate = min($qtyRequested, $remaining);

                if ($qtyToCreate <= 0) {
                    throw new \RuntimeException('Client has already reached the maximum uses for this offer.');
                }

                for ($i = 0; $i < $qtyToCreate; $i++) {
                    OfferRedemption::create([
                        'offer_id' => $offer->id,
                        'client_profile_id' => $client->id,
                        'business_profile_id' => $offer->business_profile_id,
                        'redeemed_at' => now(),
                    ]);
                }
            });

            return back()->with('status', $qtyToCreate === $qtyRequested
                ? 'Redemptions added successfully.'
                : "Requested {$qtyRequested}, but only {$qtyToCreate} were possible. Added {$qtyToCreate}."
            );
        } catch (\RuntimeException $e) {
            return back()->with('error', $e->getMessage());
        } catch (\Throwable $e) {
            return back()->with('error', 'Something went wrong while redeeming the offer.');
        }
    }

    public function destroy(ClientProfile $client, OfferRedemption $redemption)
    {
        abort_unless((int) $redemption->client_profile_id === (int) $client->id, 404);

        $redemption->delete();

        return back()->with('status', 'Redemption removed successfully.');
    }

    public function destroyForOffer(Request $request, ClientProfile $client)
    {
        $data = $request->validate([
            'offer_id' => ['required', 'integer', 'exists:offers,id'],
            'qty' => ['required', 'integer', 'min:1', 'max:100'],
        ]);

        $offerId = (int) $data['offer_id'];
        $qtyRequested = (int) $data['qty'];

        $deleted = 0;

        DB::transaction(function () use ($client, $offerId, $qtyRequested, &$deleted) {
            // lock matching rows so concurrent deletes don't collide
            $ids = OfferRedemption::where('client_profile_id', $client->id)
                ->where('offer_id', $offerId)
                ->orderBy('id', 'asc') // FIFO
                ->lockForUpdate()
                ->limit($qtyRequested)
                ->pluck('id');

            if ($ids->isEmpty()) {
                return;
            }

            $deleted = OfferRedemption::whereIn('id', $ids)->delete();
        });

        if ($deleted === 0) {
            return back()->with('error', 'No redemptions exist for that offer.');
        }

        return back()->with('status', "Removed {$deleted} redemption(s).");
    }

}
