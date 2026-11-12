<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class PatientVerificationController extends Controller
{
    /**
     * Show the Patient Verification page.
     * Pending tab: residents with status = 'pending'
     * Verified tab: residents with status = 'active'
     */
    public function index(Request $request)
    {
        $search = $request->input('search', '');

        $pendingQuery = User::where('role', 'resident')->where('status', 'pending');
        $verifiedQuery = User::where('role', 'resident')->where('status', 'active');

        if ($search) {
            $pendingQuery->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('phone_number', 'like', "%{$search}%");
            });
            $verifiedQuery->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('phone_number', 'like', "%{$search}%");
            });
        }

        $pendingResidents  = $pendingQuery->latest()->get();
        $verifiedResidents = $verifiedQuery->latest()->get();

        $pendingCount  = $pendingResidents->count();
        $verifiedCount = $verifiedResidents->count();

        return view('dashboards.nurse-patient-verification', compact(
            'pendingResidents',
            'verifiedResidents',
            'pendingCount',
            'verifiedCount',
            'search'
        ));
    }

    /**
     * Approve / verify a resident — set status to active.
     */
    public function verify(User $user)
    {
        // Only residents can be verified through this flow
        if ($user->role !== 'resident') {
            return back()->with('error', 'Only resident accounts can be verified.');
        }

        $user->update(['status' => 'active']);

        return back()->with('success', "Patient {$user->first_name} {$user->last_name} has been verified and can now access the system.");
    }

    /**
     * Reject a resident — set status to inactive.
     */
    public function reject(User $user)
    {
        if ($user->role !== 'resident') {
            return back()->with('error', 'Only resident accounts can be rejected.');
        }

        $user->update(['status' => 'inactive']);

        return back()->with('success', "Patient {$user->first_name} {$user->last_name} has been rejected.");
    }

    /**
     * Delete a resident completely.
     */
    public function destroy(User $user)
    {
        if ($user->role !== 'resident') {
            return back()->with('error', 'Only resident accounts can be deleted here.');
        }

        if ($user->profile_photo && \Illuminate\Support\Facades\Storage::disk('public')->exists($user->profile_photo)) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($user->profile_photo);
        }
        if ($user->id_photo && \Illuminate\Support\Facades\Storage::disk('public')->exists($user->id_photo)) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($user->id_photo);
        }

        $user->delete();

        return back()->with('success', "Patient {$user->first_name} {$user->last_name} has been deleted.");
    }
}
