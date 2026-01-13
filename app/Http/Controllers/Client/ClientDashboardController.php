<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ClientDashboardController extends Controller
{

    public function index(Request $request)
    {
        logger($request->user()->profile);
        return view('client.client-dashboard', [
            'user' => $request->user(),
        ]);
    }
}
