<?php

namespace App\Http\Controllers;

use App\Models\ChildVaccination;
use App\Models\User;
use Illuminate\Http\Request;

class ChildVaccinationController extends Controller
{
    /**
     * Display child vaccinations management for nurses & BHW.
     */
    public function index(Request $request)
    {
        $search = $request->query('search', '');
        $doseFilter = $request->query('dose', 'all');

        $query = ChildVaccination::with('parent')->latest('date_administered');

        if ($doseFilter !== 'all' && !empty($doseFilter)) {
            $query->where('dose_number', $doseFilter);
        }

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('child_name', 'like', "%{$search}%")
                  ->orWhere('vaccine_name', 'like', "%{$search}%")
                  ->orWhereHas('parent', function ($pq) use ($search) {
                      $pq->where('first_name', 'like', "%{$search}%")
                         ->orWhere('last_name', 'like', "%{$search}%");
                  });
            });
        }

        $vaccinations = $query->paginate(15)->withQueryString();

        // Counts
        $totalVaccinations = ChildVaccination::count();
        $firstDoseCount    = ChildVaccination::where('dose_number', '1st Dose')->count();
        $secondDoseCount   = ChildVaccination::where('dose_number', '2nd Dose')->count();
        $succeedingCount   = ChildVaccination::whereIn('dose_number', ['3rd Dose', 'Booster 1', 'Booster 2'])->count();

        $parents = User::where('role', 'resident')->orderBy('first_name')->get();

        return view('dashboards.nurse-vaccinations', compact(
            'vaccinations',
            'totalVaccinations',
            'firstDoseCount',
            'secondDoseCount',
            'succeedingCount',
            'doseFilter',
            'search',
            'parents'
        ));
    }

    /**
     * Store a child vaccination dose.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id'           => 'nullable|exists:users,id',
            'child_name'        => 'required|string|max:255',
            'child_dob'         => 'nullable|date',
            'child_gender'      => 'nullable|string|in:Male,Female',
            'vaccine_name'      => 'required|string|max:255',
            'dose_number'       => 'required|string|in:1st Dose,2nd Dose,3rd Dose,Booster 1,Booster 2',
            'date_administered' => 'required|date',
            'status'            => 'required|string|in:Completed,Scheduled,Missed',
            'notes'             => 'nullable|string',
        ]);

        $administeredBy = auth()->user() ? (auth()->user()->first_name . ' ' . auth()->user()->last_name) : 'Barangay Health Center';

        ChildVaccination::create(array_merge($validated, [
            'administered_by' => $administeredBy,
        ]));

        return redirect()->route('nurse.vaccinations')->with('success', "Vaccination dose recorded successfully for {$validated['child_name']}!");
    }

    /**
     * Delete a child vaccination record.
     */
    public function destroy(ChildVaccination $childVaccination)
    {
        $childVaccination->delete();
        return redirect()->route('nurse.vaccinations')->with('success', 'Vaccination record deleted.');
    }
}

