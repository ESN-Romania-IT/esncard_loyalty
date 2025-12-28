<?php

namespace App\Http\Controllers\Business;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BusinessDashboardController extends Controller
{


    public function index(Request $request)
    {
        return view('business.business-dashboard', [
            'user' => $request->user(),
        ]);
    }
}
