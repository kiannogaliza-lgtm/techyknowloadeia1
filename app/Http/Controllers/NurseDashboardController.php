<?php

namespace App\Http\Controllers;

use App\Models\Medicine;
use App\Models\MedicineRequest;
use Illuminate\Http\Request;

class NurseDashboardController extends Controller
{
    /**
     * Show the nurse dashboard with stats, charts, and pending requests.
     */
    public function index()
    {
        $pendingRequests = MedicineRequest::with('user')
            ->where('status', 'Pending')
            ->latest()
            ->get();

        $pendingCount = $pendingRequests->count();

        // Low stock items (stock <= reorder_level and stock > 0)
        $lowStockCount = Medicine::whereColumn('stock', '<=', 'reorder_level')
            ->where('stock', '>', 0)
            ->count();

        // Critical stock (stock = 0)
        $criticalCount = Medicine::where('stock', '<=', 0)->count();

        $totalLowStock = $lowStockCount + $criticalCount;

        // Expired medicines
        $expiredCount = Medicine::whereNotNull('expiration_date')->where('expiration_date', '<=', \Carbon\Carbon::today())->count();

        // Medicine data for bar chart
        $medicines = Medicine::orderBy('stock', 'desc')->limit(6)->get();

        // Request distribution for pie chart
        $totalRequests = MedicineRequest::count();
        $medicineRequestCount = MedicineRequest::where('type', 'Medicine')->count();
        $homeServiceCount = MedicineRequest::where('type', 'Home Service')->count();

        // Patients verified today
        $verifiedTodayCount = \App\Models\User::where('role', 'resident')
            ->where('status', 'active')
            ->whereDate('updated_at', \Carbon\Carbon::today())
            ->count();

        // Scheduled Appointments (Upcoming)
        $scheduledAppointmentsCount = \App\Models\Schedule::where('scheduled_date', '>=', \Carbon\Carbon::today())
            ->whereIn('status', ['Confirmed', 'Pending'])
            ->count();

        return view('dashboards.nurse', compact(
            'pendingRequests',
            'pendingCount',
            'totalLowStock',
            'medicines',
            'totalRequests',
            'medicineRequestCount',
            'homeServiceCount',
            'verifiedTodayCount',
            'scheduledAppointmentsCount',
            'expiredCount'
        ));
    }

    /**
     * Show the medicine inventory management page.
     */
    public function inventory(Request $request)
    {
        $query = Medicine::query();

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('dosage', 'like', '%' . $search . '%');
            });
        }

        // Category filter
        if ($request->filled('category') && $request->category !== 'all') {
            $query->where('category', $request->category);
        }

        // Status filter
        if ($request->filled('status') && $request->status !== 'all') {
            if ($request->status === 'In Stock') {
                $query->whereColumn('stock', '>', 'reorder_level');
            } elseif ($request->status === 'Low Stock') {
                $query->whereColumn('stock', '<=', 'reorder_level')->where('stock', '>', 0);
            } elseif ($request->status === 'Critical') {
                $query->where('stock', '<=', 0);
            } elseif ($request->status === 'Expired') {
                $query->whereNotNull('expiration_date')->where('expiration_date', '<=', \Carbon\Carbon::today());
            }
        }

        // Sort
        $sort = $request->input('sort', 'name');
        $direction = $request->input('direction', 'asc');
        $allowedSorts = ['name', 'dosage', 'category', 'stock', 'unit', 'expiration_date', 'status'];

        if (in_array($sort, $allowedSorts)) {
            $query->orderBy($sort, $direction);
        } else {
            $query->orderBy('name', 'asc');
        }

        $medicines = $query->paginate(10)->withQueryString();

        // Summary counts
        $inStockCount = Medicine::whereColumn('stock', '>', 'reorder_level')->count();
        $lowStockCount = Medicine::whereColumn('stock', '<=', 'reorder_level')->where('stock', '>', 0)->count();
        $criticalCount = Medicine::where('stock', '<=', 0)->count();
        $totalItems = Medicine::count();

        return view('dashboards.nurse-inventory', compact(
            'medicines',
            'inStockCount',
            'lowStockCount',
            'criticalCount',
            'totalItems'
        ));
    }

    /**
     * Show the Returned & Damaged Medicines ledger for Nurse.
     */
    public function returnedMedicines(Request $request)
    {
        $query = \App\Models\MedicineReturn::with(['user', 'medicineRequest', 'medicine', 'recorder'])
            ->orderBy('created_at', 'desc');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('medicine_name', 'like', "%{$search}%")
                  ->orWhere('reason', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($uq) use ($search) {
                      $uq->where('first_name', 'like', "%{$search}%")
                         ->orWhere('last_name', 'like', "%{$search}%");
                  })
                  ->orWhereHas('medicineRequest', function ($mq) use ($search) {
                      $mq->where('request_id', 'like', "%{$search}%");
                  });
            });
        }

        $returns = $query->paginate(15)->withQueryString();
        $totalReturnedUnits = \App\Models\MedicineReturn::sum('quantity_returned');
        $totalRecords = \App\Models\MedicineReturn::count();

        return view('dashboards.nurse-returned-medicines', compact(
            'returns',
            'totalReturnedUnits',
            'totalRecords'
        ));
    }

    /**
     * Mark all notifications as read for Nurse
     */
    public function markNotificationsRead(Request $request)
    {
        $user = auth()->user();
        if ($user) {
            $user->update(['notifications_read_at' => now()]);
        }
        return response()->json(['success' => true]);
    }
}
