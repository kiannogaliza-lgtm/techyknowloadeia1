<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Schedule;
use App\Models\AppNotification;
use Carbon\Carbon;

class WalkinController extends Controller
{
    /**
     * Store a new walk-in patient.
     *
     * This creates a resident user account (auto-verified, no password required for login
     * since they are walk-ins). It also creates a walk-in schedule entry so the nurse
     * can see them in the scheduling system, and sends notifications.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name'    => 'required|string|max:255',
            'last_name'     => 'required|string|max:255',
            'dob'           => 'nullable|date',
            'age'           => 'nullable|integer|min:0|max:130',
            'agecategory'   => 'nullable|string|max:100',
            'gender'        => 'nullable|string|in:Male,Female',
            'phone_number'  => ['nullable', 'regex:/^09[0-9]{9}$/'],
            'address'       => 'nullable|string|max:500',
            'purpose'       => 'required|string|max:255',
            'symptoms'      => 'nullable|string|max:1000',
        ], [
            'phone_number.regex' => 'The phone number must be a valid 11-digit Philippine mobile number starting with 09 (e.g. 09123456789).',
        ]);

        // Calculate age from dob if age not explicitly provided
        $age = isset($validated['age']) && $validated['age'] !== null ? (int) $validated['age'] : null;
        if ($age === null && !empty($validated['dob'])) {
            try {
                $age = Carbon::parse($validated['dob'])->age;
            } catch (\Exception $e) {}
        }

        // Determine age category
        $ageCategory = null;
        if ($age !== null) {
            $ageCategory = User::resolveAgeCategory($age);
        } elseif (!empty($validated['agecategory']) && $validated['agecategory'] !== 'Age Category') {
            $ageCategory = $validated['agecategory'];
        }

        // Check if user with same phone already exists (if phone provided)
        $existingUser = null;
        if (!empty($validated['phone_number'])) {
            $existingUser = User::where('phone_number', $validated['phone_number'])->first();
        }

        if ($existingUser) {
            // Use the existing user — update any missing fields
            $existingUser->update(array_filter([
                'dob'          => $validated['dob'] ?? null,
                'age'          => $age,
                'age_category' => $ageCategory,
                'gender'       => $validated['gender'] ?? null,
                'address'      => $validated['address'] ?? null,
            ]));
            $user = $existingUser;
        } else {
            // Create a new resident account — auto-approved, walk-in type
            // Generate a unique phone number if not provided
            $phoneNumber = $validated['phone_number'] ?: 'WALKIN-' . time() . '-' . rand(1000, 9999);

            $user = User::create([
                'first_name'   => $validated['first_name'],
                'last_name'    => $validated['last_name'],
                'phone_number' => $phoneNumber,
                'password'     => Hash::make('walkin_' . time()), // Default password
                'role'         => 'resident',
                'status'       => 'active', // Auto-approved since BHW registers in-person
                'dob'          => $validated['dob'] ?? null,
                'age'          => $age,
                'age_category' => $ageCategory,
                'gender'       => $validated['gender'] ?? null,
                'address'      => $validated['address'] ?? 'Barangay Honrado',
            ]);
        }

        // Create a Walk-in schedule entry so it appears in the Nurse scheduling system
        Schedule::create([
            'user_id'        => $user->id,
            'patient_name'   => $validated['first_name'] . ' ' . $validated['last_name'],
            'type'           => 'Walk-in',
            'service'        => $validated['purpose'],
            'scheduled_date' => Carbon::today(),
            'scheduled_time' => Carbon::now()->format('H:i:s'),
            'notes'          => $validated['symptoms'] ?? null,
            'status'         => 'Pending',
        ]);

        // Notify the BHW who registered this patient
        AppNotification::create([
            'user_id' => auth()->id(),
            'type'    => 'success',
            'title'   => 'Walk-in patient registered',
            'message' => $validated['first_name'] . ' ' . $validated['last_name'] . ' — ' . $validated['purpose'],
        ]);

        // Also notify all nurses about the new walk-in
        $nurses = User::where('role', 'nurse')->get();
        foreach ($nurses as $nurse) {
            AppNotification::create([
                'user_id' => $nurse->id,
                'type'    => 'info',
                'title'   => 'New walk-in patient',
                'message' => $validated['first_name'] . ' ' . $validated['last_name'] . ' registered by BHW ' . auth()->user()->first_name . ' for ' . $validated['purpose'],
            ]);
        }

        // Notify the resident themselves (if their account exists)
        AppNotification::create([
            'user_id' => $user->id,
            'type'    => 'info',
            'title'   => 'Walk-in visit registered',
            'message' => 'Your walk-in visit for "' . $validated['purpose'] . '" has been registered at the health center.',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Walk-in patient registered successfully.',
            'patient' => [
                'id'   => $user->id,
                'name' => $user->first_name . ' ' . $user->last_name,
            ],
        ]);
    }

    /**
     * Get recent walk-in registrations for display.
     */
    public function recent()
    {
        $recentWalkins = Schedule::where('type', 'Walk-in')
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get()
            ->map(function ($schedule) {
                return [
                    'id'           => $schedule->id,
                    'patient_name' => $schedule->patient_name,
                    'service'      => $schedule->service,
                    'date'         => $schedule->scheduled_date->format('Y-m-d'),
                    'time'         => Carbon::parse($schedule->scheduled_time)->format('g:i A'),
                    'status'       => $schedule->status,
                    'notes'        => $schedule->notes,
                ];
            });

        return response()->json($recentWalkins);
    }
}
