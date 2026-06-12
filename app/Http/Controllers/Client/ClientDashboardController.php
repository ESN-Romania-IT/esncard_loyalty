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
        $user = $request->user();

        $payload = [
            'client_profile_id' => $user->profile->id,
            'first_name' => $user->profile->first_name,
            'last_name'  => $user->profile->last_name,
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
        $stampCards = $this->stampCardsByBusiness($user->profile->id);

        return view('client.client-dashboard', [
            'user' => $user,
            'qrData' => $qrData,
            'redemptionsByBusiness' => $redemptions,
            'stampCardsByBusiness' => $stampCards,
        ]);
    }

    public function stats(Request $request): JsonResponse
    {
        $user = $request->user();
        $redemptions = $this->redemptionsByBusiness($user->profile->id);
        $stampCards = $this->stampCardsByBusiness($user->profile->id);

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

        $stampBusinesses = $stampCards->map(function ($data, $businessName) {
            return [
                'business_name' => $businessName,
                'stamp_balance' => $data['stamp_balance'],
                'cards' => collect($data['cards'])->map(fn ($card) => [
                    'offer_title' => $card->offer_title,
                    'stamps_required' => (int) $card->stamps_required,
                    'completions' => (int) $card->completions,
                ])->values(),
            ];
        })->values();

        return response()->json([
            'ok' => true,
            'businesses' => $businesses,
            'stamp_businesses' => $stampBusinesses,
        ]);
    }

    private function redemptionsByBusiness(int $clientProfileId)
    {
        return DB::table('offer_redemptions as r')
            ->join('offers as o', 'o.id', '=', 'r.offer_id')
            ->join('business_profiles as b', 'b.id', '=', 'o.business_profile_id')
            ->where('r.client_profile_id', $clientProfileId)
            ->where('o.type', 'discount')
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
    public function edit()
    {
        $user = auth()->user();
        return view('client.edit-user', compact('user'));
    }
   public function update(Request $request)
{
    $validated = $request->validate([
        'first_name'   => 'required|string|max:255',
        'last_name'    => 'required|string|max:255',
        'email'        => 'required|email|unique:users,email,' . auth()->id(),
        'esncard_code' => 'nullable|string|max:255',
    ]);

    $user = auth()->user();

    // update pe tabelul users
    $user->update([
        'email'        => $validated['email'],
        'esncard_code' => $validated['esncard_code'],
    ]);

    // update pe tabelul client_profiles
    $user->profile->update([
        'first_name' => $validated['first_name'],
        'last_name'  => $validated['last_name'],
    ]);


    return redirect()->route('client.dashboard')->with('success', 'Profile updated!');
}
public function destroy(Request $request)
{
    $user = auth()->user();

    auth()->logout();

    $user->profile()->delete();
    $user->delete();

    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect('/')->with('success', 'Contul a fost șters.');
}

    private function stampCardsByBusiness(int $clientProfileId): \Illuminate\Support\Collection
    {
        // Shared stamp balance per business (available stamps not yet redeemed)
        $balanceByBusiness = DB::table('client_stamps')
            ->where('client_profile_id', $clientProfileId)
            ->whereNull('redeemed_at')
            ->selectRaw('business_profile_id, COUNT(*) as stamp_balance')
            ->groupBy('business_profile_id')
            ->pluck('stamp_balance', 'business_profile_id');

        // Completions per stamp_card offer for this client
        $completionsByOffer = DB::table('offer_redemptions as r')
            ->join('offers as o', 'o.id', '=', 'r.offer_id')
            ->where('r.client_profile_id', $clientProfileId)
            ->where('o.type', 'stamp_card')
            ->selectRaw('r.offer_id, COUNT(*) as completions_count')
            ->groupBy('r.offer_id')
            ->pluck('completions_count', 'offer_id');

        // All businesses where this client has stamps OR has completed a stamp card
        $businessIdsFromStamps = $balanceByBusiness->keys();
        $businessIdsFromCompletions = DB::table('offer_redemptions as r')
            ->join('offers as o', 'o.id', '=', 'r.offer_id')
            ->where('r.client_profile_id', $clientProfileId)
            ->where('o.type', 'stamp_card')
            ->pluck('o.business_profile_id')
            ->unique();

        $businessIds = $businessIdsFromStamps->merge($businessIdsFromCompletions)->unique();

        if ($businessIds->isEmpty()) {
            return collect();
        }

        // Fetch stamp_card offers grouped by business
        $offersByBusiness = DB::table('offers as o')
            ->join('business_profiles as b', 'b.id', '=', 'o.business_profile_id')
            ->whereIn('o.business_profile_id', $businessIds)
            ->where('o.type', 'stamp_card')
            ->where('o.is_active', true)
            ->select(['b.id as business_id', 'b.business_name', 'o.id as offer_id', 'o.title as offer_title', 'o.stamps_required'])
            ->orderBy('b.business_name')
            ->orderBy('o.title')
            ->get()
            ->map(function ($row) use ($completionsByOffer) {
                $row->completions = (int) ($completionsByOffer[$row->offer_id] ?? 0);
                return $row;
            })
            ->groupBy('business_id');

        return $offersByBusiness->map(function ($cards, $businessId) use ($balanceByBusiness) {
            return [
                'stamp_balance' => (int) ($balanceByBusiness[$businessId] ?? 0),
                'business_name' => $cards->first()->business_name,
                'cards' => $cards->values(),
            ];
        })->keyBy(fn ($data) => $data['business_name']);
    }
}
