<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class StudentAuthController extends Controller
{
    public function register($id)
    {
        $names =
            [
                'aaa' => 'Cafe Bar',
                'bbb' => 'Cafezinho',
                'ccc' => 'Cafeteria'
            ];

        $data = [];

        if (!array_key_exists($id, $names)) {
            abort(404);
        }

        $data['restaurant_name'] = $names[$id];

        $data['register_action'] = route('register.submit', ['id' => $id]);
        $data['terms_of_service_url'] = route('terms-and-conditions');
        return view('register', $data);
    }

    public function register_submit($id, Request $request)
    {
        // Verify the ESNcard
        $data = $request->validate([
            'esncard_serial_code'    => ['required','string','max:64'],
            'name' => ['required','string','max:64'],
            'surname'  => ['required','string','max:64'],
        ]);

        // Verify if the student with this name and surname exists
        $student = Student::where('forename', $data['name'])
            ->where('surname', $data['surname'])->first();

        // If the student is found, we will log them in if the hashed ESNcard serial code matches
        if ($student) {
            if (!Hash::check($data['esncard_serial_code'], $student->esncard_serial)) {
                return back()->withErrors(['esncard_serial_code' => 'The ESNcard serial code is not valid']);
            }
        } else {
            // Otherwise, we will create a new student
            $student = Student::create([
                'forename' => $data['name'],
                'surname' => $data['surname'],
                'esncard_serial' => $data['esncard_serial_code'],
            ]);
        }

        // Login using the student guard
        Auth::guard('student')->login($student);

        $request->session()->regenerate();

        return redirect()->intended('/me');

    }

    public function loginForm()
    {
        return view('login');
    }

    public function login(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string'],
            'surname' => ['required', 'string'],
            'esncard_serial_code' => ['required', 'string'],
        ]);

        $student = Student::where('forename', $data['name'])
            ->where('surname', $data['surname'])
            ->first();

        if (! $student) {
            return back()->withErrors([
                'name' => 'Student not found',
            ]);
        }

        if ($data['esncard_serial_code'] !== $student->esncard_serial) {
            return back()->withErrors([
                'esncard_serial_code' => 'Invalid ESNcard code',
            ]);
        }

        Auth::guard('student')->login($student);
        $request->session()->regenerate();

        return redirect('/me');
    }

    public function logout(Request $request)
    {
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
