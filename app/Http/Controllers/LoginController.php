<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function show()
    {
        return view('auth.login');
    }

    public function login(LoginRequest $request)
    {
        $credentials = $request->validate([
        'email' => ['required', 'email'],
        'password' => ['required', 'string'],
        ]);

        $remember = $request->boolean('remember');

        if (!Auth::attempt($credentials, $remember)) {
            return back()
                ->withErrors(['email' => 'Invalid email or password.'])
                ->withInput($request->only('email'));
        }

        $request->session()->regenerate();

        $role = Auth::user()->role;

        return match ($role) {
            'admin' => redirect()->route('admin.admin-dashboard'),
            'business_user' => redirect()->route('business.business-dashboard'),
            default => redirect()->route('client.client-dashboard'),
        };
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
