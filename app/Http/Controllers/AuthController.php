<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $role = $request->input('role', 'resident');
        if (!in_array($role, ['resident', 'nurse'])) {
            $role = 'resident';
        }

        $isResident = ($role === 'resident');

        $rules = [
            'role'         => ['required', 'in:resident,nurse'],
            'first_name'   => ['required', 'string', 'max:255'],
            'last_name'    => ['required', 'string', 'max:255'],
            'phone_number' => ['required', 'regex:/^09[0-9]{9}$/', 'unique:users'],
            'age'          => ['required', 'integer', 'min:0', 'max:130'],
            'password'     => ['required', 'string', 'min:8', 'confirmed'],
        ];

        if ($isResident) {
            $rules['id_photo'] = ['required', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:5120'];
        } else {
            $rules['id_photo'] = ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:5120'];
        }

        $validated = $request->validate($rules, [
            'phone_number.regex' => 'The phone number must be a valid 11-digit Philippine mobile number starting with 09 (e.g. 09123456789).',
        ]);

        // Store the ID photo if uploaded
        $idPhotoPath = null;
        if ($request->hasFile('id_photo')) {
            $idPhotoPath = $request->file('id_photo')->store('id_photos', 'public');
        }

        $age = (int) $validated['age'];
        $ageCategory = \App\Models\User::resolveAgeCategory($age);

        $user = \App\Models\User::create([
            'first_name'   => $validated['first_name'],
            'last_name'    => $validated['last_name'],
            'phone_number' => $validated['phone_number'],
            'age'          => $age,
            'age_category' => $ageCategory,
            'password'     => Hash::make($validated['password']),
            'role'         => $role,
            'status'       => $isResident ? 'pending' : 'active',
            'id_photo'     => $idPhotoPath,
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        if ($isResident) {
            // New residents go to the pending-approval waiting page
            return redirect()->route('pending.approval');
        }

        // Newly registered nurses go directly to dashboard
        $user->update(['last_login_at' => now()]);
        return redirect()->route('dashboard');
    }

    public function login(Request $request)
    {
        $request->validate([
            'phone_number' => ['required'],
            'password'     => ['required'],
            'role'         => ['required', 'in:resident,bhw,nurse'],
        ]);

        $user = \App\Models\User::where('phone_number', $request->phone_number)->first();

        if (!$user) {
            return back()->withErrors([
                'phone_number' => 'Incorrect number.',
            ])->onlyInput('phone_number');
        }

        if (!Hash::check($request->password, $user->password)) {
            return back()->withErrors([
                'password' => 'Incorrect password.',
            ])->onlyInput('phone_number');
        }

        if ($user->role !== $request->role) {
            return back()->withErrors([
                'role' => 'Incorrect role selected for this account.',
            ])->onlyInput('phone_number');
        }

        // Block pending residents — redirect to waiting page after login
        if ($user->role === 'resident' && $user->status === 'pending') {
            Auth::login($user, $request->boolean('remember'));
            $request->session()->regenerate();
            $user->update(['last_login_at' => now()]);
            return redirect()->route('pending.approval');
        }

        // Block rejected/inactive residents
        if ($user->role === 'resident' && $user->status === 'inactive') {
            return back()->withErrors([
                'phone_number' => 'Your account has been rejected. Please contact the health center.',
            ])->onlyInput('phone_number');
        }

        Auth::login($user, $request->boolean('remember'));
        $request->session()->regenerate();

        // Track last login timestamp
        $user->update(['last_login_at' => now()]);

        return redirect()->intended('dashboard');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
