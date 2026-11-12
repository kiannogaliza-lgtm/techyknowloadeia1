<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class UserManagementController extends Controller
{
    /**
     * Display the user management page with filtering and search.
     */
    public function index(Request $request)
    {
        $query = User::query();

        // Role filter
        if ($request->filled('role') && $request->role !== 'all') {
            $query->where('role', $request->role);
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('phone_number', 'like', "%{$search}%");
            });
        }

        $users = $query->orderBy('created_at', 'desc')->get();

        // Summary counts
        $totalNurses = User::where('role', 'nurse')->count();
        $totalBhw = User::where('role', 'bhw')->count();
        $totalResidents = User::where('role', 'resident')->count();
        $activeUsers = User::where('status', 'active')->count();

        return view('dashboards.nurse-users', compact(
            'users',
            'totalNurses',
            'totalBhw',
            'totalResidents',
            'activeUsers'
        ));
    }

    /**
     * Store a newly created user.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name'    => ['required', 'string', 'max:255'],
            'last_name'     => ['required', 'string', 'max:255'],
            'phone_number'  => ['required', 'regex:/^09[0-9]{9}$/', 'unique:users,phone_number'],
            'age'           => ['nullable', 'integer', 'min:0', 'max:130'],
            'role'          => ['required', 'in:resident,bhw,nurse'],
            'password'      => ['required', 'string', 'min:8', 'confirmed'],
            'profile_photo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:2048'],
        ], [
            'phone_number.regex' => 'The phone number must be a valid 11-digit Philippine mobile number starting with 09 (e.g. 09123456789).',
        ]);

        $photoPath = null;
        if ($request->hasFile('profile_photo')) {
            $photoPath = $request->file('profile_photo')->store('profile_photos', 'public');
        }

        $age = isset($validated['age']) && $validated['age'] !== null ? (int) $validated['age'] : null;

        User::create([
            'first_name'    => $validated['first_name'],
            'last_name'     => $validated['last_name'],
            'phone_number'  => $validated['phone_number'],
            'age'           => $age,
            'age_category'  => User::resolveAgeCategory($age),
            'role'          => $validated['role'],
            'password'      => Hash::make($validated['password']),
            'status'        => 'active',
            'profile_photo' => $photoPath,
        ]);

        return redirect()->route('nurse.users')->with('success', 'User account created successfully.');
    }

    /**
     * Update the specified user.
     */
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'first_name'    => ['required', 'string', 'max:255'],
            'last_name'     => ['required', 'string', 'max:255'],
            'phone_number'  => ['required', 'regex:/^09[0-9]{9}$/', Rule::unique('users')->ignore($user->id)],
            'age'           => ['nullable', 'integer', 'min:0', 'max:130'],
            'role'          => ['required', 'in:resident,bhw,nurse'],
            'status'        => ['required', 'in:active,inactive'],
            'is_maintenance'=> ['nullable', 'boolean'],
            'profile_photo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:2048'],
        ], [
            'phone_number.regex' => 'The phone number must be a valid 11-digit Philippine mobile number starting with 09 (e.g. 09123456789).',
        ]);

        $validated['is_maintenance'] = $request->boolean('is_maintenance');

        if ($request->hasFile('profile_photo')) {
            if ($user->profile_photo && Storage::disk('public')->exists($user->profile_photo)) {
                Storage::disk('public')->delete($user->profile_photo);
            }
            $validated['profile_photo'] = $request->file('profile_photo')->store('profile_photos', 'public');
        }

        if (array_key_exists('age', $validated)) {
            $validated['age'] = $validated['age'] !== null && $validated['age'] !== '' ? (int) $validated['age'] : null;
            $validated['age_category'] = User::resolveAgeCategory($validated['age']);
        }

        $user->update($validated);

        // Optional password change
        if ($request->filled('password')) {
            $request->validate([
                'password' => ['string', 'min:8', 'confirmed'],
            ]);
            $user->update(['password' => Hash::make($request->password)]);
        }

        return redirect()->route('nurse.users')->with('success', 'User account updated successfully.');
    }

    /**
     * Delete the specified user.
     */
    public function destroy(User $user)
    {
        // Prevent deleting yourself
        if ($user->id === auth()->id()) {
            return redirect()->route('nurse.users')->with('error', 'You cannot delete your own account.');
        }

        if ($user->profile_photo && Storage::disk('public')->exists($user->profile_photo)) {
            Storage::disk('public')->delete($user->profile_photo);
        }

        $user->delete();

        return redirect()->route('nurse.users')->with('success', 'User account deleted successfully.');
    }
}
