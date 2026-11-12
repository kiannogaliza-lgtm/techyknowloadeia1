<?php

namespace App\Http\Controllers;

use App\Models\Medicine;
use App\Models\MedicineRequest;
use App\Models\HomeServiceRequest;
use App\Models\Schedule;
use App\Models\Announcement;
use App\Models\User;
use Illuminate\Http\Request;

class GlobalSearchController extends Controller
{
    /**
     * Unified search endpoint for Nurse, BHW, and Resident portals.
     */
    public function search(Request $request)
    {
        $query = trim($request->input('q', ''));
        if (strlen($query) < 1) {
            return response()->json([
                'results' => []
            ]);
        }

        $user = auth()->user();
        if (!$user) {
            return response()->json(['results' => []], 401);
        }

        $results = [];

        try {
            if ($user->role === 'nurse') {
                $results = $this->searchForNurse($query);
            } elseif ($user->role === 'bhw') {
                $results = $this->searchForBhw($query, $user);
            } elseif ($user->role === 'resident') {
                $results = $this->searchForResident($query, $user);
            }
        } catch (\Throwable $e) {
            \Log::error('Search failed: ' . $e->getMessage(), [
                'exception' => $e,
                'query' => $query,
                'user_id' => $user->id,
            ]);
            return response()->json(['results' => [], 'error' => $e->getMessage()], 500);
        }

        return response()->json([
            'query' => $query,
            'results' => $results,
        ]);
    }

    /**
     * Search for Nurse role.
     */
    protected function searchForNurse(string $q): array
    {
        $results = [];

        // 1. Medicines (Inventory)
        $medicines = Medicine::where('name', 'like', "%{$q}%")
            ->orWhere('category', 'like', "%{$q}%")
            ->orWhere('dosage', 'like', "%{$q}%")
            ->limit(5)
            ->get();

        foreach ($medicines as $m) {
            $results[] = [
                'category' => 'Medicines',
                'icon' => 'medication',
                'title' => $m->name . ($m->dosage ? " ({$m->dosage})" : ''),
                'subtitle' => "Category: {$m->category} • Stock: {$m->stock} {$m->unit} • Status: {$m->status}",
                'url' => route('nurse.inventory', ['search' => $m->name]),
                'tab' => 'inventory',
            ];
        }

        // 2. Medicine Requests
        $requests = MedicineRequest::with('user')
            ->where(function ($sub) use ($q) {
                $sub->where('item_service', 'like', "%{$q}%")
                    ->orWhere('notes', 'like', "%{$q}%")
                    ->orWhereHas('user', function ($uq) use ($q) {
                        $uq->where('first_name', 'like', "%{$q}%")
                           ->orWhere('last_name', 'like', "%{$q}%")
                           ->orWhere('phone_number', 'like', "%{$q}%");
                    });
            })
            ->limit(5)
            ->get();

        foreach ($requests as $r) {
            $userName = $r->user ? "{$r->user->first_name} {$r->user->last_name}" : 'Resident';
            $results[] = [
                'category' => 'Medicine Requests',
                'icon' => 'local_shipping',
                'title' => "{$r->request_id}: {$r->item_service} (Qty: {$r->quantity})",
                'subtitle' => "Patient: {$userName} • Status: {$r->status} • Type: {$r->type}",
                'url' => route('nurse.medicine-requests', ['search' => $r->item_service]),
                'tab' => 'medicine-requests',
            ];
        }

        // 3. Home Services
        $homeServices = HomeServiceRequest::where('patient_name', 'like', "%{$q}%")
            ->orWhere('service_type', 'like', "%{$q}%")
            ->orWhere('address', 'like', "%{$q}%")
            ->orWhere('contact_number', 'like', "%{$q}%")
            ->limit(5)
            ->get();

        foreach ($homeServices as $hs) {
            $dateStr = $hs->preferred_date ? $hs->preferred_date->format('M d, Y') : 'N/A';
            $results[] = [
                'category' => 'Home Services',
                'icon' => 'home_health',
                'title' => "{$hs->formatted_id}: {$hs->patient_name} - {$hs->service_type}",
                'subtitle' => "Status: {$hs->status} • Date: {$dateStr} • {$hs->address}",
                'url' => route('nurse.home-services', ['search' => $hs->patient_name]),
                'tab' => 'home-services',
            ];
        }

        // 4. Patients & Users
        $users = User::where(function ($sub) use ($q) {
            $sub->where('first_name', 'like', "%{$q}%")
                ->orWhere('last_name', 'like', "%{$q}%")
                ->orWhere('phone_number', 'like', "%{$q}%");
        })
        ->limit(5)
        ->get();

        foreach ($users as $u) {
            $targetUrl = ($u->role === 'resident' && $u->status === 'pending')
                ? route('nurse.patient-verification', ['search' => $u->first_name])
                : route('nurse.users', ['search' => $u->first_name]);

            $results[] = [
                'category' => 'Users & Patients',
                'icon' => 'person',
                'title' => "{$u->first_name} {$u->last_name} (" . ucfirst($u->role) . ")",
                'subtitle' => "Phone: " . ($u->phone_number ?? 'N/A') . " • Status: {$u->status}",
                'url' => $targetUrl,
                'tab' => 'users',
            ];
        }

        // 5. Schedules / Appointments
        $schedules = Schedule::where('patient_name', 'like', "%{$q}%")
            ->orWhere('service', 'like', "%{$q}%")
            ->orWhere('type', 'like', "%{$q}%")
            ->limit(4)
            ->get();

        foreach ($schedules as $s) {
            $dateStr = $s->scheduled_date ? $s->scheduled_date->format('M d, Y') : '';
            $results[] = [
                'category' => 'Schedules',
                'icon' => 'calendar_month',
                'title' => "{$s->formatted_id}: {$s->patient_name} - {$s->service}",
                'subtitle' => "Date: {$dateStr} {$s->scheduled_time} • Status: {$s->status}",
                'url' => route('nurse.scheduling'),
                'tab' => 'scheduling',
            ];
        }

        // 6. Announcements
        $announcements = Announcement::where('title', 'like', "%{$q}%")
            ->orWhere('content', 'like', "%{$q}%")
            ->limit(3)
            ->get();

        foreach ($announcements as $a) {
            $results[] = [
                'category' => 'Announcements',
                'icon' => 'campaign',
                'title' => $a->title,
                'subtitle' => "Posted on: {$a->date} • Status: {$a->status}",
                'url' => route('nurse.announcements'),
                'tab' => 'announcements',
            ];
        }

        return $results;
    }

    /**
     * Search for BHW role.
     */
    protected function searchForBhw(string $q, User $user): array
    {
        $results = [];

        // 1. Medicines in inventory (view only)
        $medicines = Medicine::where('name', 'like', "%{$q}%")
            ->orWhere('category', 'like', "%{$q}%")
            ->orWhere('dosage', 'like', "%{$q}%")
            ->limit(5)
            ->get();

        foreach ($medicines as $m) {
            $results[] = [
                'category' => 'Medicine Inventory',
                'icon' => 'medication',
                'title' => $m->name . ($m->dosage ? " ({$m->dosage})" : ''),
                'subtitle' => "Category: {$m->category} • Stock: {$m->stock} {$m->unit} • Status: {$m->status}",
                'tab' => 'inventory',
                'action_data' => $m->name,
            ];
        }

        // 2. Home Services (all or assigned)
        $homeServices = HomeServiceRequest::where(function ($sub) use ($q) {
            $sub->where('patient_name', 'like', "%{$q}%")
                ->orWhere('service_type', 'like', "%{$q}%")
                ->orWhere('address', 'like', "%{$q}%")
                ->orWhere('contact_number', 'like', "%{$q}%");
        })
        ->limit(6)
        ->get();

        foreach ($homeServices as $hs) {
            $dateStr = $hs->preferred_date ? $hs->preferred_date->format('M d, Y') : 'N/A';
            $isMine = ($hs->assigned_to == $user->id) ? ' (Assigned to You)' : '';
            $results[] = [
                'category' => 'Home Services',
                'icon' => 'home_health',
                'title' => "{$hs->formatted_id}: {$hs->patient_name} - {$hs->service_type}{$isMine}",
                'subtitle' => "Status: {$hs->status} • Date: {$dateStr} • {$hs->address}",
                'tab' => 'services',
                'action_data' => $hs->id,
            ];
        }

        // 3. Medicine Delivery Requests
        $medicineRequests = MedicineRequest::with('user')
            ->where(function ($sub) use ($q) {
                $sub->where('item_service', 'like', "%{$q}%")
                    ->orWhere('notes', 'like', "%{$q}%")
                    ->orWhereHas('user', function ($uq) use ($q) {
                        $uq->where('first_name', 'like', "%{$q}%")
                           ->orWhere('last_name', 'like', "%{$q}%")
                           ->orWhere('phone_number', 'like', "%{$q}%");
                    });
            })
            ->limit(5)
            ->get();

        foreach ($medicineRequests as $mr) {
            $userName = $mr->user ? "{$mr->user->first_name} {$mr->user->last_name}" : 'Resident';
            $results[] = [
                'category' => 'Medicine Delivery',
                'icon' => 'local_shipping',
                'title' => "{$mr->request_id}: {$mr->item_service} (Qty: {$mr->quantity})",
                'subtitle' => "Patient: {$userName} • Status: {$mr->status} • Method: {$mr->delivery_method}",
                'tab' => 'medicine-requests',
                'action_data' => $mr->item_service,
            ];
        }

        // 4. Announcements
        $announcements = Announcement::where('title', 'like', "%{$q}%")
            ->orWhere('content', 'like', "%{$q}%")
            ->limit(4)
            ->get();

        foreach ($announcements as $a) {
            $results[] = [
                'category' => 'Announcements',
                'icon' => 'campaign',
                'title' => $a->title,
                'subtitle' => "Posted on {$a->date} by {$a->author_name}",
                'tab' => 'announcements',
                'action_data' => $a->id,
            ];
        }

        return $results;
    }

    /**
     * Search for Resident role.
     */
    protected function searchForResident(string $q, User $user): array
    {
        $results = [];

        // 1. Available Medicines to request
        $medicines = Medicine::where('name', 'like', "%{$q}%")
            ->orWhere('category', 'like', "%{$q}%")
            ->orWhere('dosage', 'like', "%{$q}%")
            ->limit(5)
            ->get();

        foreach ($medicines as $m) {
            $avail = ($m->stock > 0) ? "In Stock ({$m->stock} {$m->unit})" : "Out of Stock";
            $results[] = [
                'category' => 'Available Medicines',
                'icon' => 'medication',
                'title' => $m->name . ($m->dosage ? " ({$m->dosage})" : ''),
                'subtitle' => "{$m->category} • {$avail} • Click to request",
                'tab' => 'medicine',
                'action_data' => $m->name,
            ];
        }

        // 2. My Medicine Requests
        $myRequests = MedicineRequest::where('user_id', $user->id)
            ->where(function ($sub) use ($q) {
                $sub->where('item_service', 'like', "%{$q}%")
                    ->orWhere('notes', 'like', "%{$q}%")
                    ->orWhere('status', 'like', "%{$q}%");
            })
            ->limit(5)
            ->get();

        foreach ($myRequests as $mr) {
            $dateStr = $mr->created_at ? $mr->created_at->format('M d, Y') : '';
            $results[] = [
                'category' => 'My Medicine Requests',
                'icon' => 'local_shipping',
                'title' => "{$mr->request_id}: {$mr->item_service} (Qty: {$mr->quantity})",
                'subtitle' => "Status: {$mr->status} • Method: {$mr->delivery_method} • Date: {$dateStr}",
                'tab' => 'dashboard',
                'action_data' => $mr->id,
            ];
        }

        // 3. My Home Service Bookings
        $myServices = HomeServiceRequest::where('user_id', $user->id)
            ->where(function ($sub) use ($q) {
                $sub->where('service_type', 'like', "%{$q}%")
                    ->orWhere('patient_name', 'like', "%{$q}%")
                    ->orWhere('reason_symptoms', 'like', "%{$q}%")
                    ->orWhere('status', 'like', "%{$q}%");
            })
            ->limit(5)
            ->get();

        foreach ($myServices as $ms) {
            $dateStr = $ms->preferred_date ? $ms->preferred_date->format('M d, Y') : '';
            $results[] = [
                'category' => 'My Home Services',
                'icon' => 'home_health',
                'title' => "{$ms->formatted_id}: {$ms->service_type}",
                'subtitle' => "Status: {$ms->status} • Date: {$dateStr} • {$ms->patient_name}",
                'tab' => 'service',
                'action_data' => $ms->id,
            ];
        }

        // 4. My Schedules / Appointments
        $mySchedules = Schedule::where('user_id', $user->id)
            ->where(function ($sub) use ($q) {
                $sub->where('service', 'like', "%{$q}%")
                    ->orWhere('notes', 'like', "%{$q}%")
                    ->orWhere('status', 'like', "%{$q}%");
            })
            ->limit(4)
            ->get();

        foreach ($mySchedules as $sch) {
            $dateStr = $sch->scheduled_date ? $sch->scheduled_date->format('M d, Y') : '';
            $results[] = [
                'category' => 'My Schedules',
                'icon' => 'calendar_month',
                'title' => "{$sch->formatted_id}: {$sch->service}",
                'subtitle' => "Status: {$sch->status} • Date: {$dateStr} {$sch->scheduled_time}",
                'tab' => 'schedule',
                'action_data' => $sch->id,
            ];
        }

        return $results;
    }
}
