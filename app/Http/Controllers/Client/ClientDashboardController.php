<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ClientDashboardController extends Controller
{

    public function index(Request $request)
    {
        logger($request->user()->profile);


        $user = $request->user();

        $payload = [
            'first_name' => $user->profile->first_name,
            'last_name'  =>  $user->profile->last_name,
            'exp'        => now()->addMinutes(10)->timestamp,
        ];

        $payloadJson = json_encode($payload);

        $signature = hash_hmac(
            'sha256',
            $payloadJson,
            config('services.qr.hmac_secret')
        );

        $qrData = json_encode([
            'payload'   => $payload,
            'signature' => $signature,
        ]);

        return view('client.client-dashboard', [
            'user' => $user,
            'qrData' => $qrData
        ]);
    }
}
