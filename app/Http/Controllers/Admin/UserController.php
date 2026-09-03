<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $role = $request->query('role');
        $q = $request->query('q');

        $users = User::query()
            ->when($role, fn ($query) => $query->where('role', $role))

            ->when($q, function ($query) use ($q) {
                $query->where(function ($group) use ($q) {
                    $group->where('users.email', 'like', "%{$q}%")
                        ->orWhereHas('profile', function ($profile) use ($q) {
                            $profile->where('first_name', 'like', "%{$q}%")
                                    ->orWhere('last_name', 'like', "%{$q}%");
                        })
                        ->orWhereHas('business_profile', function ($business) use ($q) {
                            $business->where('business_name', 'like', "%{$q}%");
                        });
                });
            })
            ->orderBy('id', 'desc')
            ->paginate(10)
            ->withQueryString();

        return view('admin.users.index', [
            'users' => $users,
            'role' => $role,
            'q' => $q,
            'roles' => $this->roles(),
        ]);
    }

    public function create()
    {
        return view('admin.users.create', [
            'allowAdmin' => true,
            'roles' => $this->roles(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'email' => ['required','email','max:255','unique:users,email'],
            'role'  => ['required', Rule::in(['standard_user','business_user','admin'])],
            'esncard_code' => ['nullable','string','max:255','unique:users,esncard_code','required_if:role, standard_user', Rule::unique('users', 'esncard_code')],

            'password' => ['required','confirmed', Password::min(8)->mixedCase()],

            // Conditional fields:
            'first_name' => ['required_if:role,standard_user','nullable','string','max:255'],
            'last_name'  => ['required_if:role,standard_user','nullable','string','max:255'],
            'business_name' => ['required_if:role,business_user','nullable','string','max:255'],
        ]);
        if ($data['role'] !== 'standard_user') {
            $data['esncard_code'] = null;
        }
        $user = User::create([
            'email' => $data['email'],
            'role' => $data['role'],
            'esncard_code' => $data['esncard_code'] ?? null,
            'password' => Hash::make($data['password']),
        ]);

        if ($user->role === 'standard_user') {
            $user->profile()->create([
                'first_name' => $data['first_name'],
                'last_name'  => $data['last_name'],
            ]);
        }

        if ($user->role === 'business_user') {
            $user->business_profile()->create([
                'business_name' => $data['business_name'],
            ]);
        }

        return redirect()->route('admin.users.index')
            ->with('status', 'User created successfully.');
    }

    public function edit(User $user)
    {
        return view('admin.users.edit', [
            'user' => $user,
            'allowAdmin' => true,
            'roles' => $this->roles(),
        ]);
    }

    public function update(Request $request, User $user)
    {
        $esnCardRules = ['nullable','string','max:255','required_if:role,standard_user'];

        if ($request->input('esncard_code') !== $user->esncard_code) {
            $esnCardRules[] = Rule::unique('users', 'esncard_code')->ignore($user->id);
        }

        $data = $request->validate([
            'email' => ['required','email','max:255', Rule::unique('users','email')->ignore($user->id)],
            'role'  => ['required', Rule::in(['standard_user','business_user','admin'])],
            'esncard_code' => $esnCardRules,

            'password' => ['nullable','confirmed', Password::min(8)->mixedCase()],

            'first_name' => ['required_if:role,standard_user','nullable','string','max:255'],
            'last_name'  => ['required_if:role,standard_user','nullable','string','max:255'],
            'business_name' => ['required_if:role,business_user','nullable','string','max:255'],
        ]);
        if ($data['role'] !== 'standard_user') {
            $data['esncard_code'] = null;
        }

        $user->update([
            'email' => $data['email'],
            'role' => $data['role'],
            'esncard_code' => $data['esncard_code'] ?? null,
        ]);

        if (!empty($data['password'])) {
            $user->update(['password' => Hash::make($data['password'])]);
        }

        if ($user->role !== 'standard_user') {
            $user->profile()->delete();
        }
        if ($user->role !== 'business_user') {
            $user->business_profile()->delete();
        }

        if ($user->role === 'standard_user') {
            $user->profile()->updateOrCreate(
                ['user_id' => $user->id],
                ['first_name' => $data['first_name'], 'last_name' => $data['last_name']]
            );
        }

        if ($user->role === 'business_user') {
            $user->business_profile()->updateOrCreate(
                ['user_id' => $user->id],
                ['business_name' => $data['business_name']]
            );
        }

        return redirect()->route('admin.users.edit', $user)
            ->with('status', 'User updated successfully.');
    }

    public function destroy(User $user)
    {
        if ($user->is(Auth::user())) {
            return back()->withErrors([
                'delete' => 'You cannot delete your own account.',
            ]);
        }

        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('status', 'User deleted successfully.');
    }

    private function roles(): array
    {
        return [
            'standard_user' => 'Standard',
            'business_user' => 'Business',
            'admin' => 'Admin',
        ];
    }
}
