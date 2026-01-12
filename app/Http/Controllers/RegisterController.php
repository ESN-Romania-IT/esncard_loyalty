<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function show(){
        return view('auth.register');
    }

    public function register(RegisterRequest $request){
        //add validation logic for esn card code

        if (!$this->mockValidateEsnCard($request->esncard_code)) {
            return back()
                ->withErrors(['esncard_code' => 'Invalid ESNcard code.'])
                ->withInput();
        }

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
    }

    private function mockValidateEsnCard($code)
    {
        // Mock rule: only accept codes starting with "ESN"
        return str_starts_with($code, 'ESN');
    }
}
