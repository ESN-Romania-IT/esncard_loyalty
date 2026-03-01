<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class RegisterController extends Controller
{
    public function show(){
        return view('auth.register');
    }

    public function register(RegisterRequest $request){
        $user = User::create([
            'email' => $request->email,
            'esncard_code' => $request->esncard_code,
            'role' => 'standard_user',
            'password'=> Hash::make($request->password),
        ]);

        $user->profile()->create([
            'first_name' => $request->first_name,
            'last_name'  => $request->last_name,
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('me');
    }
}
