<?php

namespace App\Http\Controllers\Business;

use App\Http\Controllers\Controller;
use App\Models\ClientProfile;
use App\Models\Offer;
use App\Models\OfferRedemption;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class BusinessDashboardController extends Controller
{


    public function index(Request $request)
    {
        $businessProfile = $request->user()?->business_profile;

        $activeOffers = $businessProfile
            ? Offer::query()
                ->where('business_profile_id', $businessProfile->id)
                ->where('is_active', true)
                ->withCount('redemptions')
                ->orderBy('title')
                ->get()
            : collect();

        return view('business.business-dashboard', [
            'user' => $request->user(),
            'activeOffers' => $activeOffers,
        ]);
    }

    public function activeOffersStats(Request $request): JsonResponse
    {
        $businessProfile = $request->user()?->business_profile;

        if (!$businessProfile) {
            return response()->json([
                'ok' => false,
                'message' => 'Business profile not found.',
            ], 403);
        }

        $offers = Offer::query()
            ->where('business_profile_id', $businessProfile->id)
            ->where('is_active', true)
            ->withCount('redemptions')
            ->orderBy('title')
            ->get()
            ->map(fn ($offer) => [
                'id' => $offer->id,
                'title' => $offer->title,
                'redemptions_count' => (int) $offer->redemptions_count,
                'show_url' => route('business.offers.show', $offer),
            ]);

        return response()->json([
            'ok' => true,
            'offers' => $offers,
        ]);
    }

    public function verifyQr(Request $request): JsonResponse
    {
        $data = $request->validate([
            'payload' => ['required', 'string'],
            'signature' => ['required', 'string'],
        ]);

        $client = $this->validateQrPayload($data['payload'], $data['signature']);

        if (!$client) {
            return response()->json([
                'ok' => false,
                'message' => 'Invalid or expired QR code.',
            ], 422);
        }

        return response()->json([
            'ok' => true,
            'client' => $client,
        ]);
    }

    public function openQr(Request $request)
    {
        $payloadEncoded = $request->query('payload');
        $signature = $request->query('signature');

        $payloadJson = $this->decodeBase64Url($payloadEncoded ?? '');

        $client = $payloadJson && $signature
            ? $this->validateQrPayload($payloadJson, $signature)
            : null;

        return view('business.business-dashboard', [
            'user' => $request->user(),
            'scannedClient' => $client,
            'qrOpenError' => $client ? null : 'Invalid or expired QR code.',
            'activeOffers' => Offer::query()
                ->where('business_profile_id', $request->user()?->business_profile?->id)
                ->where('is_active', true)
                ->withCount('redemptions')
                ->orderBy('title')
                ->get(),
        ]);
    }

    public function offers(Request $request): JsonResponse
    {
        $data = $request->validate([
            'client_profile_id' => ['required', 'integer', 'exists:client_profiles,id'],
        ]);

        $businessProfile = $request->user()?->business_profile;

        if (!$businessProfile) {
            return response()->json([
                'ok' => false,
                'message' => 'Business profile not found.',
            ], 403);
        }

        $clientId = (int) $data['client_profile_id'];

        $offers = Offer::query()
            ->where('business_profile_id', $businessProfile->id)
            ->where('is_active', true)
            ->orderBy('title')
            ->get(['id', 'title', 'uses_per_client']);

        $usedByOffer = OfferRedemption::query()
            ->where('business_profile_id', $businessProfile->id)
            ->where('client_profile_id', $clientId)
            ->selectRaw('offer_id, COUNT(*) as used_count')
            ->groupBy('offer_id')
            ->pluck('used_count', 'offer_id');

        $result = $offers->map(fn ($offer) => [
            'id' => $offer->id,
            'title' => $offer->title,
            'uses_per_client' => (int) $offer->uses_per_client,
            'used_count' => (int) ($usedByOffer[$offer->id] ?? 0),
        ]);

        return response()->json([
            'ok' => true,
            'offers' => $result,
        ]);
    }

    public function redeem(Request $request): JsonResponse
    {
        $data = $request->validate([
            'client_profile_id' => ['required', 'integer', 'exists:client_profiles,id'],
            'offer_id' => ['required', 'integer', 'exists:offers,id'],
            'qty' => ['required', 'integer', 'min:1', 'max:100'],
        ]);

        $businessProfile = $request->user()?->business_profile;

        if (!$businessProfile) {
            return response()->json([
                'ok' => false,
                'message' => 'Business profile not found.',
            ], 403);
        }

        $clientId = (int) $data['client_profile_id'];
        $qtyRequested = (int) $data['qty'];

        try {
            $added = 0;

            DB::transaction(function () use ($data, $businessProfile, $clientId, $qtyRequested, &$added) {
                $offer = Offer::whereKey($data['offer_id'])
                    ->lockForUpdate()
                    ->firstOrFail();

                if ((int) $offer->business_profile_id !== (int) $businessProfile->id) {
                    throw new \RuntimeException('Offer does not belong to this business.');
                }

                if (!$offer->is_active) {
                    throw new \RuntimeException('This offer is not active.');
                }

                $used = OfferRedemption::where('offer_id', $offer->id)
                    ->where('client_profile_id', $clientId)
                    ->lockForUpdate()
                    ->count();

                $remaining = max(0, (int) $offer->uses_per_client - $used);
                $qtyToCreate = min($qtyRequested, $remaining);

                if ($qtyToCreate <= 0) {
                    throw new \RuntimeException('Client has already reached the maximum uses for this offer.');
                }

                for ($i = 0; $i < $qtyToCreate; $i++) {
                    OfferRedemption::create([
                        'offer_id' => $offer->id,
                        'client_profile_id' => $clientId,
                        'business_profile_id' => $offer->business_profile_id,
                        'redeemed_at' => now(),
                    ]);
                }

                $added = $qtyToCreate;
            });

            return response()->json([
                'ok' => true,
                'added' => $added,
                'message' => $added === $qtyRequested
                    ? "Added {$added} redemption(s)."
                    : "Requested {$qtyRequested}, but only {$added} were possible.",
            ]);
        } catch (\RuntimeException $e) {
            return response()->json([
                'ok' => false,
                'message' => $e->getMessage(),
            ], 422);
        } catch (\Throwable $e) {
            return response()->json([
                'ok' => false,
                'message' => 'Something went wrong while redeeming.',
            ], 500);
        }
    }

    private function validateQrPayload(string $payloadJson, string $signature): ?array
    {
        $expected = hash_hmac('sha256', $payloadJson, config('services.qr.hmac_secret'));

        if (!hash_equals($expected, $signature)) {
            return null;
        }

        $payload = json_decode($payloadJson, true);
        if (!is_array($payload)) {
            return null;
        }

        $clientProfileId = Arr::get($payload, 'client_profile_id');
        $exp = Arr::get($payload, 'exp');

        if (!$clientProfileId || !$exp) {
            return null;
        }

        if ((int) $exp < now()->subSeconds(30)->timestamp) {
            return null;
        }

        $clientProfile = ClientProfile::find($clientProfileId);

        if (!$clientProfile) {
            return null;
        }

        return [
            'client_profile_id' => $clientProfile->id,
            'first_name' => $clientProfile->first_name,
            'last_name' => $clientProfile->last_name,
        ];
    }

    private function decodeBase64Url(string $value): ?string
    {
        if ($value === '') {
            return null;
        }

        $padded = $value . str_repeat('=', (4 - strlen($value) % 4) % 4);
        $decoded = base64_decode(strtr($padded, '-_', '+/'), true);

        return $decoded === false ? null : $decoded;
    }
}
