<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Medicine;
use App\Models\MedicineRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReportsController extends Controller
{
    /**
     * Show the Reports & Analytics page.
     */
    public function index()
    {
        // ── Stat cards ──────────────────────────────────────────────
        $totalPatients     = User::where('role', 'resident')->count();
        $totalRequests     = MedicineRequest::count();
        $homeServiceCount  = MedicineRequest::where('type', 'Home Service')->count();
        $immunizationCount = MedicineRequest::where('type', 'Immunization')->count();

        // Month-over-month change helpers (current vs previous month)
        $now       = Carbon::now();
        $thisMonth = $now->month;
        $thisYear  = $now->year;
        $lastMonth = $now->copy()->subMonth();

        $patientsThisMonth = User::where('role', 'resident')
            ->whereMonth('created_at', $thisMonth)
            ->whereYear('created_at', $thisYear)
            ->count();
        $patientsLastMonth = User::where('role', 'resident')
            ->whereMonth('created_at', $lastMonth->month)
            ->whereYear('created_at', $lastMonth->year)
            ->count();
        $patientChange = $patientsLastMonth > 0
            ? round(($patientsThisMonth - $patientsLastMonth) / $patientsLastMonth * 100)
            : ($patientsThisMonth > 0 ? 100 : 0);

        $requestsThisMonth = MedicineRequest::whereMonth('created_at', $thisMonth)
            ->whereYear('created_at', $thisYear)
            ->count();
        $requestsLastMonth = MedicineRequest::whereMonth('created_at', $lastMonth->month)
            ->whereYear('created_at', $lastMonth->year)
            ->count();
        $requestChange = $requestsLastMonth > 0
            ? round(($requestsThisMonth - $requestsLastMonth) / $requestsLastMonth * 100)
            : ($requestsThisMonth > 0 ? 100 : 0);

        $hsThisMonth = MedicineRequest::where('type', 'Home Service')
            ->whereMonth('created_at', $thisMonth)
            ->whereYear('created_at', $thisYear)
            ->count();
        $hsLastMonth = MedicineRequest::where('type', 'Home Service')
            ->whereMonth('created_at', $lastMonth->month)
            ->whereYear('created_at', $lastMonth->year)
            ->count();
        $hsChange = $hsLastMonth > 0
            ? round(($hsThisMonth - $hsLastMonth) / $hsLastMonth * 100)
            : ($hsThisMonth > 0 ? 100 : 0);

        $immThisMonth = MedicineRequest::where('type', 'Immunization')
            ->whereMonth('created_at', $thisMonth)
            ->whereYear('created_at', $thisYear)
            ->count();
        $immLastMonth = MedicineRequest::where('type', 'Immunization')
            ->whereMonth('created_at', $lastMonth->month)
            ->whereYear('created_at', $lastMonth->year)
            ->count();
        $immChange = $immLastMonth > 0
            ? round(($immThisMonth - $immLastMonth) / $immLastMonth * 100)
            : ($immThisMonth > 0 ? 100 : 0);

        // ── Monthly trends (last 5 months) ──────────────────────────
        $monthLabels   = [];
        $patientTrend  = [];
        $requestTrend  = [];

        for ($i = 4; $i >= 0; $i--) {
            $month = $now->copy()->subMonths($i);
            $monthLabels[] = $month->format('M');

            $patientTrend[] = User::where('role', 'resident')
                ->whereMonth('created_at', $month->month)
                ->whereYear('created_at', $month->year)
                ->count();

            $requestTrend[] = MedicineRequest::whereMonth('created_at', $month->month)
                ->whereYear('created_at', $month->year)
                ->count();
        }

        // ── Service comparison (last 5 months, stacked bar) ─────────
        $homeServiceTrend   = [];
        $immunizationTrend  = [];
        $ultrasoundTrend    = [];

        for ($i = 4; $i >= 0; $i--) {
            $month = $now->copy()->subMonths($i);

            $homeServiceTrend[] = MedicineRequest::where('type', 'Home Service')
                ->whereMonth('created_at', $month->month)
                ->whereYear('created_at', $month->year)
                ->count();

            $immunizationTrend[] = MedicineRequest::where('type', 'Immunization')
                ->whereMonth('created_at', $month->month)
                ->whereYear('created_at', $month->year)
                ->count();

            $ultrasoundTrend[] = MedicineRequest::where('type', 'Ultrasound')
                ->whereMonth('created_at', $month->month)
                ->whereYear('created_at', $month->year)
                ->count();
        }

        // ── Resident Medicine Requests (for Resident Requests Report Table) ──────
        // Syncs all resident requests so nurse can see requester age and actual patient age
        $residentRequests = MedicineRequest::with(['user', 'medicine'])
            ->where('type', 'Medicine')
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($req) {
                $residentName = $req->user ? ($req->user->first_name . ' ' . $req->user->last_name) : 'Unknown Resident';
                $medName = $req->medicine->name ?? ($req->item_service ?? 'Medicine');
                
                // Requester age
                $requesterAge = $req->user?->age !== null ? "{$req->user->age} yrs" : null;
                $requesterCategory = $req->user?->age_category ?? null;

                // Actual patient details
                $isForOther = ($req->patient_type === 'other' && !empty($req->patient_name));
                $patientName = $isForOther ? $req->patient_name : $residentName;
                $patientAge = $isForOther ? ($req->patient_age !== null ? "{$req->patient_age} yrs" : null) : $requesterAge;
                $patientRel = $isForOther ? ($req->patient_relationship ?? 'Dependent') : 'Self';

                // Total requests made by this resident
                $userTotalRequests = $req->user_id 
                    ? MedicineRequest::where('user_id', $req->user_id)->where('type', 'Medicine')->count() 
                    : 1;

                return (object)[
                    'id'                 => $req->id,
                    'resident_name'      => $residentName,
                    'requester_age'      => $requesterAge,
                    'requester_category' => $requesterCategory,
                    'is_for_other'       => $isForOther,
                    'patient_name'       => $patientName,
                    'patient_age'        => $patientAge,
                    'patient_rel'        => $patientRel,
                    'medicine_name'      => $medName,
                    'quantity'           => $req->quantity ?? 1,
                    'delivery_method'    => ucfirst($req->delivery_method ?? 'pickup'),
                    'status'             => $req->status ?? 'Pending',
                    'date'               => $req->created_at ? $req->created_at->format('M d, Y h:i A') : 'N/A',
                    'total_requests'     => $userTotalRequests,
                    'is_maintenance'     => (bool) ($req->user?->is_maintenance),
                ];
            });

        // ── Child / Dependent Vaccination Records for Reports ─────────────────
        $childVaccinations = \App\Models\ChildVaccination::with('parent')
            ->orderBy('date_administered', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('dashboards.nurse-reports', compact(
            'totalPatients',
            'totalRequests',
            'homeServiceCount',
            'immunizationCount',
            'patientChange',
            'requestChange',
            'hsChange',
            'immChange',
            'monthLabels',
            'patientTrend',
            'requestTrend',
            'homeServiceTrend',
            'immunizationTrend',
            'ultrasoundTrend',
            'residentRequests',
            'childVaccinations'
        ));
    }
}
