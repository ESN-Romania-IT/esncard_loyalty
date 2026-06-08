<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ClientProfile;
use App\Models\ClientStamp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminClientStampController extends Controller
{
    /**
     * Award stamps to a client for a given business.
     */
    public function store(Request $request, ClientProfile $client)
    {
        $data = $request->validate([
            'business_profile_id' => ['required', 'integer', 'exists:business_profiles,id'],
            'qty' => ['required', 'integer', 'min:1', 'max:100'],
        ]);

        $now = now();
        $rows = [];
        for ($i = 0; $i < (int) $data['qty']; $i++) {
            $rows[] = [
                'client_profile_id'   => $client->id,
                'business_profile_id' => $data['business_profile_id'],
                'awarded_at'          => $now,
                'redeemed_at'         => null,
            ];
        }

        ClientStamp::insert($rows);

        return back()->with('status', $data['qty'] . ' stamp(s) awarded successfully.');
    }

    /**
     * Remove un-redeemed stamps from a client for a given business.
     */
    public function destroyForBusiness(Request $request, ClientProfile $client)
    {
        $data = $request->validate([
            'business_profile_id' => ['required', 'integer', 'exists:business_profiles,id'],
            'qty' => ['required', 'integer', 'min:1', 'max:100'],
        ]);

        $deleted = 0;

        DB::transaction(function () use ($client, $data, &$deleted) {
            $ids = ClientStamp::where('client_profile_id', $client->id)
                ->where('business_profile_id', $data['business_profile_id'])
                ->whereNull('redeemed_at')
                ->orderBy('awarded_at', 'asc')
                ->lockForUpdate()
                ->limit((int) $data['qty'])
                ->pluck('id');

            if ($ids->isEmpty()) {
                return;
            }

            $deleted = ClientStamp::whereIn('id', $ids)->delete();
        });

        if ($deleted === 0) {
            return back()->with('error', 'No available stamps to remove for that business.');
        }

        return back()->with('status', $deleted . ' stamp(s) removed successfully.');
    }
}
