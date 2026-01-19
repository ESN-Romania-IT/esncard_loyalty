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
        return view('client.client-dashboard', [
            'user' => $request->user(),
        ]);

        $response = Http::timeout(1000)
        ->withHeaders([
            'x-bypass-cf-api' => config('services.esn.bypass_key'),
        ])
        ->get('https://esncard.org/services/1.0/card.json', [
            'code' => $student->esncard_serial,
        ]);

        if (! $response->successful()) {
            return view('dashboard', [
                'qrError' => 'Could not validate ESN card. Try again later.',
                'qrData'  => 'ERROR_VALIDATING_ESNCARD',
            ]);
        }

        $data = $response->json();

        if (empty($data) || ($data[0]['status'] ?? '') !== 'active') {
            return view('dashboard', [
                'qrError' => 'Invalid ESN card. QR code cannot be generated.',
                'qrData'  => 'ERROR_INVALID_ESNCARD',
            ]);
        }

        $payload = [
            'first_name' => $student->forename,
            'last_name'  => $student->surname,
            'esn_code'   => substr($student->esncard_serial, 0, 6),
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

        return view('dashboard', compact('qrData'));
    }
}
