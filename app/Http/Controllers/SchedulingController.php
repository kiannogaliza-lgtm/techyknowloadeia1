<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use App\Models\AppNotification;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;

class SchedulingController extends Controller
{
    /**
     * Display the scheduling management page.
     */
    public function index(Request $request)
    {
        $selectedDate = $request->query('date', now()->toDateString());

        $schedules = Schedule::with('assignedBhw')->orderBy('scheduled_date')
            ->orderBy('scheduled_time')
            ->get();

        // BHW users for assignment dropdown
        $bhwUsers = User::where('role', 'bhw')->where('status', 'active')->get();

        // Count by status
        $confirmedCount = $schedules->where('status', 'Confirmed')->count();
        $pendingCount = $schedules->where('status', 'Pending')->count();
        $cancelledCount = $schedules->where('status', 'Cancelled')->count();

        // Available time slots for the selected date
        $allSlots = [
            '09:00', '10:00', '11:00',
            '13:00', '14:00', '15:00',
            '16:00', '17:00',
        ];

        $bookedSlots = Schedule::where('scheduled_date', $selectedDate)
            ->whereIn('status', ['Confirmed', 'Pending'])
            ->pluck('scheduled_time')
            ->map(fn ($t) => Carbon::parse($t)->format('H:i'))
            ->toArray();

        $availableSlots = array_diff($allSlots, $bookedSlots);

        // Calendar data — schedules grouped by date for the current month
        $calendarMonth = $request->query('month', now()->format('Y-m'));
        $calendarStart = Carbon::parse($calendarMonth . '-01')->startOfMonth();
        $calendarEnd = $calendarStart->copy()->endOfMonth();

        $calendarSchedules = Schedule::whereBetween('scheduled_date', [$calendarStart, $calendarEnd])
            ->get()
            ->groupBy(fn ($s) => $s->scheduled_date->format('Y-m-d'));

        return view('dashboards.nurse-scheduling', compact(
            'schedules',
            'confirmedCount',
            'pendingCount',
            'cancelledCount',
            'selectedDate',
            'availableSlots',
            'calendarSchedules',
            'calendarMonth',
            'calendarStart',
            'calendarEnd',
            'bhwUsers'
        ));
    }

    /**
     * Store a new schedule.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'patient_name' => 'required|string|max:255',
            'type' => 'required|in:Immunization,Ultrasound',
            'service' => 'required|string|max:255',
            'scheduled_date' => 'required|date',
            'scheduled_time' => 'required',
            'status' => 'nullable|in:Confirmed,Pending',
        ]);

        $validated['status'] = $validated['status'] ?? 'Pending';
        $validated['scheduled_time'] = Carbon::parse($validated['scheduled_time'])->format('H:i:s');

        Schedule::create($validated);

        return redirect()->route('nurse.scheduling')->with('success', 'Schedule created successfully.');
    }

    /**
     * Store a new schedule from resident portal.
     */
    public function storeResident(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|in:Immunization,Ultrasound',
            'scheduled_date' => 'required|date',
            'scheduled_time' => 'required',
            'notes' => 'nullable|string|max:500',
        ]);

        $user = auth()->user();

        // Default service name based on type
        $service = $validated['type'] === 'Immunization' ? 'Vaccination' : 'Prenatal/General Ultrasound';

        Schedule::create([
            'user_id' => $user->id,
            'patient_name' => $user->first_name . ' ' . $user->last_name,
            'type' => $validated['type'],
            'service' => $service,
            'scheduled_date' => $validated['scheduled_date'],
            'scheduled_time' => Carbon::parse($validated['scheduled_time'])->format('H:i:s'),
            'notes' => $validated['notes'] ?? null,
            'status' => 'Pending',
        ]);

        return redirect()->route('dashboard')->with('success', 'Appointment scheduled successfully.');
    }

    /**
     * Update an existing schedule.
     */
    public function update(Request $request, Schedule $schedule)
    {
        $validated = $request->validate([
            'patient_name' => 'required|string|max:255',
            'type' => 'required|in:Immunization,Ultrasound',
            'service' => 'required|string|max:255',
            'scheduled_date' => 'required|date',
            'scheduled_time' => 'required',
            'status' => 'nullable|in:Confirmed,Pending,Cancelled',
        ]);

        $schedule->update($validated);

        return redirect()->route('nurse.scheduling')->with('success', 'Schedule ' . $schedule->formatted_id . ' updated successfully.');
    }

    /**
     * Confirm a pending schedule.
     */
    public function confirm(Schedule $schedule)
    {
        $schedule->update(['status' => 'Confirmed']);

        // Notify the resident
        if ($schedule->user_id) {
            AppNotification::create([
                'user_id' => $schedule->user_id,
                'type' => 'info',
                'title' => 'Appointment Confirmed',
                'message' => "{$schedule->type} appointment scheduled for {$schedule->scheduled_date->format('M d, Y')} at {$schedule->formatted_time} has been confirmed.",
            ]);
        }

        return redirect()->back()->with('success', 'Schedule ' . $schedule->formatted_id . ' confirmed.');
    }

    /**
     * Assign a schedule to a BHW (nurse action).
     */
    public function assignToBhw(Request $request, Schedule $schedule)
    {
        $validated = $request->validate([
            'assigned_to' => 'required|exists:users,id',
        ]);

        $bhw = User::find($validated['assigned_to']);
        $schedule->assigned_to = $bhw->id;
        $schedule->assigned_bhw_name = 'BHW ' . $bhw->first_name . ' ' . $bhw->last_name;
        $schedule->assignment_status = 'assigned';
        $schedule->save();

        // Notify the BHW
        AppNotification::create([
            'user_id' => $bhw->id,
            'type'    => 'info',
            'title'   => 'New Schedule Assignment',
            'message' => "You have been assigned to a {$schedule->type} appointment for {$schedule->patient_name} on {$schedule->scheduled_date->format('M d, Y')} at {$schedule->formatted_time}.",
        ]);

        return redirect()->back()->with('success', $schedule->formatted_id . ' assigned to ' . $schedule->assigned_bhw_name . ' successfully.');
    }

    /**
     * BHW confirms their assigned schedule.
     */
    public function confirmByBhw(Request $request, Schedule $schedule)
    {
        if ($schedule->assigned_to !== auth()->id()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $schedule->assignment_status = 'confirmed';
        $schedule->save();

        // Notify the nurse / resident
        if ($schedule->user_id) {
            AppNotification::create([
                'user_id' => $schedule->user_id,
                'type'    => 'success',
                'title'   => 'Appointment BHW Confirmed',
                'message' => "BHW {$schedule->assigned_bhw_name} has confirmed your {$schedule->type} appointment on {$schedule->scheduled_date->format('M d, Y')}.",
            ]);
        }

        return response()->json(['success' => true, 'message' => 'Assignment confirmed.']);
    }

    /**
     * Cancel / delete a schedule.
     */
    public function destroy(Schedule $schedule)
    {
        $id = $schedule->formatted_id;

        // Notify the resident before deleting
        if ($schedule->user_id) {
            AppNotification::create([
                'user_id' => $schedule->user_id,
                'type' => 'warning',
                'title' => 'Appointment Cancelled',
                'message' => "Your {$schedule->type} appointment on {$schedule->scheduled_date->format('M d, Y')} at {$schedule->formatted_time} has been cancelled.",
            ]);
        }

        $schedule->delete();

        return redirect()->route('nurse.scheduling')->with('success', 'Schedule ' . $id . ' has been cancelled.');
    }
}
