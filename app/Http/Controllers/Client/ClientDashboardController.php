<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class ClientDashboardController extends Controller
{

    public function index(Request $request)
    {
        logger($request->user()->profile);


        $user = $request->user();

        $payload = [
            'client_profile_id' => $user->profile->id,
            'first_name' => $user->profile->first_name,
            'last_name'  =>  $user->profile->last_name,
            'exp'        => now()->addMinutes(20)->timestamp,
        ];

        $payloadJson = json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

        $signature = hash_hmac(
            'sha256',
            $payloadJson,
            config('services.qr.hmac_secret')
        );

        $payloadEncoded = rtrim(strtr(base64_encode($payloadJson), '+/', '-_'), '=');

        $qrData = route('business.qr.open', [
            'payload' => $payloadEncoded,
            'signature' => $signature,
        ]);

        $redemptions = $this->redemptionsByBusiness($user->profile->id);

        return view('client.client-dashboard', [
            'user' => $user,
            'qrData' => $qrData,
            'redemptionsByBusiness' => $redemptions,
        ]);
    }

    public function stats(Request $request): JsonResponse
    {
        $user = $request->user();
        $redemptions = $this->redemptionsByBusiness($user->profile->id);

        $businesses = $redemptions->map(function ($offers, $businessName) {
            return [
                'business_name' => $businessName,
                'offers' => $offers->map(fn ($offer) => [
                    'offer_title' => $offer->offer_title,
                    'redeemed_count' => (int) $offer->redeemed_count,
                    'uses_per_client' => (int) $offer->uses_per_client,
                ])->values(),
            ];
        })->values();

        return response()->json([
            'ok' => true,
            'businesses' => $businesses,
        ]);
    }

    private function redemptionsByBusiness(int $clientProfileId)
    {
        return DB::table('offer_redemptions as r')
            ->join('offers as o', 'o.id', '=', 'r.offer_id')
            ->join('business_profiles as b', 'b.id', '=', 'o.business_profile_id')
            ->where('r.client_profile_id', $clientProfileId)
            ->groupBy('b.id', 'b.business_name', 'o.id', 'o.title', 'o.uses_per_client')
            ->select([
                'b.id as business_id',
                'b.business_name',
                'o.id as offer_id',
                'o.title as offer_title',
                'o.uses_per_client',
                DB::raw('COUNT(*) as redeemed_count'),
            ])
            ->orderBy('b.business_name')
            ->orderBy('o.title')
            ->get()
            ->groupBy('business_name');
    }
}
