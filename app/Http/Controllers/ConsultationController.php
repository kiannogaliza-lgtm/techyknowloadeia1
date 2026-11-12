<?php

namespace App\Http\Controllers;

use App\Models\Consultation;
use App\Models\ChildVaccination;
use App\Models\User;
use App\Models\Announcement;
use App\Models\AppNotification;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ConsultationController extends Controller
{
    /**
     * Display the consultations & disease cases summary page for nurses.
     */
    public function index(Request $request)
    {
        $diseaseFilter = $request->query('disease', 'all');
        $search = $request->query('search', '');

        $query = Consultation::latest('consultation_date');

        if ($diseaseFilter !== 'all' && !empty($diseaseFilter)) {
            $query->where('disease_case', $diseaseFilter);
        }

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('patient_name', 'like', "%{$search}%")
                  ->orWhere('disease_case', 'like', "%{$search}%")
                  ->orWhere('diagnosis', 'like', "%{$search}%")
                  ->orWhere('address', 'like', "%{$search}%");
            });
        }

        $consultations = $query->paginate(15)->withQueryString();

        // ── Summary statistics per communicable disease ───────────────────────
        $dengueCount = Consultation::where('disease_case', 'Dengue')->count();
        $hivCount    = Consultation::where('disease_case', 'HIV')->count();
        $tbCount     = Consultation::where('disease_case', 'Tuberculosis')->count();
        $measlesCount= Consultation::where('disease_case', 'Measles')->count();
        $totalCases  = Consultation::count();

        // Disease Threshold Limit (e.g. 5 cases triggers epidemic warning / automatic announcement)
        $thresholdLimit = 5;
        $dengueAlert = $dengueCount >= $thresholdLimit;
        $hivAlert    = $hivCount >= $thresholdLimit;
        $tbAlert     = $tbCount >= $thresholdLimit;
        $measlesAlert= $measlesCount >= $thresholdLimit;

        // Registered residents for modal dropdown
        $residents = User::where('role', 'resident')->orderBy('first_name')->get();

        return view('dashboards.nurse-consultations', compact(
            'consultations',
            'dengueCount',
            'hivCount',
            'tbCount',
            'measlesCount',
            'totalCases',
            'thresholdLimit',
            'dengueAlert',
            'hivAlert',
            'tbAlert',
            'measlesAlert',
            'diseaseFilter',
            'search',
            'residents'
        ));
    }

    /**
     * Store a new patient consultation record.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id'           => 'nullable|exists:users,id',
            'patient_name'      => 'required|string|max:255',
            'patient_age'       => 'nullable|integer|min:0|max:130',
            'patient_gender'    => 'nullable|string|in:Male,Female,Other',
            'address'           => 'nullable|string|max:255',
            'disease_case'      => 'required|string|max:100',
            'symptoms'          => 'nullable|string',
            'diagnosis'         => 'nullable|string',
            'treatment_notes'   => 'nullable|string',
            'consultation_date' => 'required|date',
            'status'            => 'required|string|in:Active,Recovered,Referred',
        ]);

        $attendingNurse = auth()->user() ? (auth()->user()->first_name . ' ' . auth()->user()->last_name) : 'Barangay Nurse';

        $consultation = Consultation::create(array_merge($validated, [
            'attending_nurse' => $attendingNurse,
        ]));

        // Check if cases of this disease now reach or exceed the threshold limit (5 cases)
        $caseCount = Consultation::where('disease_case', $validated['disease_case'])->count();
        $threshold = 5;

        $autoAnnounced = false;
        if ($caseCount >= $threshold) {
            // Check if an announcement for this threshold was already posted recently
            $recentAnnouncement = Announcement::where('title', 'like', "%{$validated['disease_case']} Alert%")
                ->where('created_at', '>=', now()->subDays(3))
                ->first();

            if (!$recentAnnouncement) {
                // Publish community alert announcement automatically to all residents
                Announcement::create([
                    'user_id'     => auth()->id(),
                    'author_name' => $attendingNurse . ' (Health Center)',
                    'author_role' => 'nurse',
                    'title'       => "⚠️ Public Health Advisory: {$validated['disease_case']} Cases in Barangay",
                    'content'     => "Notice to all residents: The Barangay Health Center has logged {$caseCount} cases of {$validated['disease_case']}. Please take necessary preventive measures, eliminate breeding sites, and immediately visit the health center if experiencing symptoms.",
                    'date'        => now()->toDateString(),
                    'status'      => 'active',
                ]);

                // Notify all active resident users
                $residents = User::where('role', 'resident')->get();
                foreach ($residents as $resident) {
                    AppNotification::create([
                        'user_id'    => $resident->id,
                        'title'      => "Health Advisory: {$validated['disease_case']} Alert",
                        'message'    => "The health center has monitored an increase in {$validated['disease_case']} cases ({$caseCount} cases). Please review the official advisory.",
                        'type'       => 'announcement',
                        'is_read'    => false,
                    ]);
                }
                $autoAnnounced = true;
            }
        }

        $msg = "Consultation record saved successfully!";
        if ($autoAnnounced) {
            $msg .= " ⚠️ Warning: {$validated['disease_case']} cases reached {$caseCount} (Limit: {$threshold}). An automatic public health announcement has been published to all residents.";
        }

        return redirect()->route('nurse.consultations')->with('success', $msg);
    }

    /**
     * Delete a consultation record.
     */
    public function destroy(Consultation $consultation)
    {
        $consultation->delete();
        return redirect()->route('nurse.consultations')->with('success', 'Consultation record deleted.');
    }
}

