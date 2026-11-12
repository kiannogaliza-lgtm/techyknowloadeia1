<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Medicine;
use App\Models\MedicineRequest;
use App\Models\MedicineReturn;
use App\Models\HomeServiceRequest;
use App\Models\Schedule;

class ResidentDashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // Get all user's requests to evaluate cooldowns and active requests
        $userRequests = MedicineRequest::where('user_id', $user->id)->get();

        // Get Available Medicines (for dashboard panel & request form) with cooldown status
        $medicines = Medicine::all()->map(function ($med) use ($userRequests) {
            $medData = $med->toArray();
            $medData['status'] = $med->status;

            // Delivery method blocking info
            $medData['pickup_blocked'] = false;
            $medData['pickup_block_reason'] = null;
            $medData['delivery_blocked'] = false;
            $medData['delivery_block_reason'] = null;

            // Check if there's an active Pending or Accepted request for this medicine
            $activeReq = $userRequests->first(function ($r) use ($med) {
                return ($r->medicine_id === $med->id || strcasecmp($r->item_service, $med->name) === 0)
                    && in_array($r->status, ['Pending', 'Accepted']);
            });

            if ($activeReq) {
                $medData['can_request'] = false;
                $medData['lock_reason'] = "You have an active request ({$activeReq->status}) for this medicine.";
                $medData['days_remaining'] = null;
                $medData['available_date'] = null;

                // Mark the specific delivery method restrictions
                $methodName = $activeReq->delivery_method === 'delivery' ? 'Home Delivery' : 'Pickup at Health Center';
                $oppositeMethod = $activeReq->delivery_method === 'delivery' ? 'Pickup' : 'Home Delivery';
                if ($activeReq->delivery_method === 'pickup') {
                    $medData['delivery_blocked'] = true;
                    $medData['delivery_block_reason'] = "Blocked: You already have an active Pickup request ({$activeReq->status}) for this medicine.";
                    $medData['pickup_blocked'] = true;
                    $medData['pickup_block_reason'] = "You already have an active Pickup request ({$activeReq->status}) for this medicine.";
                } else {
                    $medData['pickup_blocked'] = true;
                    $medData['pickup_block_reason'] = "Blocked: You already have an active Home Delivery request ({$activeReq->status}) for this medicine.";
                    $medData['delivery_blocked'] = true;
                    $medData['delivery_block_reason'] = "You already have an active Home Delivery request ({$activeReq->status}) for this medicine.";
                }
                return $medData;
            }

            // Check cooldown from last Approved request
            $lastApproved = $userRequests->where('status', 'Approved')
                ->filter(function ($r) use ($med) {
                    return $r->medicine_id === $med->id || strcasecmp($r->item_service, $med->name) === 0;
                })
                ->sortByDesc('updated_at')
                ->first();

            $timesPerDay = $med->dosage_times_per_day;
            $daysCycle = $med->dosage_days ?? 1;

            if (!$timesPerDay && !empty($med->dosage)) {
                if (preg_match('/(\d+)\s*(?:times|time|x)/i', $med->dosage, $m)) {
                    $timesPerDay = (int) $m[1];
                }
            }

            if ($lastApproved && $timesPerDay && $timesPerDay > 0) {
                $prevQty = $lastApproved->quantity ?? 1;
                $daysNeeded = max(1, (int) ceil(($prevQty / $timesPerDay) * $daysCycle));
                $availableDate = $lastApproved->updated_at->copy()->addDays($daysNeeded);

                if (now()->lt($availableDate)) {
                    $remainingDays = max(1, (int) ceil(now()->diffInSeconds($availableDate) / 86400));
                    $medData['can_request'] = false;
                    $medData['lock_reason'] = "Current dosage supply running until " . $availableDate->format('M d, Y') . ". Available again in {$remainingDays} day(s).";
                    $medData['days_remaining'] = $remainingDays;
                    $medData['available_date'] = $availableDate->format('Y-m-d');

                    $lastMethod = $lastApproved->delivery_method ?? 'pickup';
                    $lastMethodLabel = $lastMethod === 'delivery' ? 'Home Delivery' : 'Pickup';
                    $otherMethodLabel = $lastMethod === 'delivery' ? 'Pickup' : 'Home Delivery';

                    if ($lastMethod === 'pickup') {
                        $medData['delivery_blocked'] = true;
                        $medData['delivery_block_reason'] = "Blocked: Previous {$lastMethodLabel} dosage supply is still active until " . $availableDate->format('M d, Y') . " ({$remainingDays}d remaining).";
                        $medData['pickup_blocked'] = true;
                        $medData['pickup_block_reason'] = "Dosage supply running until " . $availableDate->format('M d, Y') . " ({$remainingDays}d remaining).";
                    } else {
                        $medData['pickup_blocked'] = true;
                        $medData['pickup_block_reason'] = "Blocked: Previous {$lastMethodLabel} dosage supply is still active until " . $availableDate->format('M d, Y') . " ({$remainingDays}d remaining).";
                        $medData['delivery_blocked'] = true;
                        $medData['delivery_block_reason'] = "Dosage supply running until " . $availableDate->format('M d, Y') . " ({$remainingDays}d remaining).";
                    }

                    return $medData;
                }
            }

            $medData['can_request'] = ($med->status !== 'Critical');
            $medData['lock_reason'] = $med->status === 'Critical' ? 'Out of stock' : null;
            $medData['days_remaining'] = 0;
            $medData['available_date'] = null;

            return $medData;
        });

        // Get all of this resident's own medicine requests (for the My Requests tab)
        $myMedicineRequests = MedicineRequest::where('user_id', $user->id)
            ->with('medicine')
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($req) {
                return [
                    'id'              => $req->id,
                    'request_id'      => $req->request_id,
                    'name'            => $req->item_service ?? 'Medicine Request',
                    'medicine_name'   => $req->medicine->name ?? $req->item_service,
                    'quantity'        => $req->quantity ?? 1,
                    'delivery_method' => $req->delivery_method ?? 'pickup',
                    'notes'           => $req->notes ?? '',
                    'date'            => $req->created_at->format('Y-m-d'),
                    'status'          => $req->status,
                ];
            });

        // Get this resident's medicine returns (broken / damaged medicines)
        $myMedicineReturns = MedicineReturn::where('user_id', $user->id)
            ->with(['medicineRequest', 'medicine', 'recorder'])
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($ret) {
                return [
                    'id'                 => $ret->id,
                    'medicine_name'      => $ret->medicine_name,
                    'previous_quantity'  => $ret->previous_quantity,
                    'new_quantity'       => $ret->new_quantity,
                    'quantity_returned'  => $ret->quantity_returned,
                    'reason'             => $ret->reason,
                    'unit'               => $ret->medicine ? $ret->medicine->unit : 'unit(s)',
                    'request_id'         => $ret->medicineRequest ? $ret->medicineRequest->request_id : ('REQ-' . str_pad($ret->medicine_request_id, 3, '0', STR_PAD_LEFT)),
                    'recorded_by'        => $ret->recorder ? ($ret->recorder->first_name . ' ' . $ret->recorder->last_name) : 'Barangay Nurse',
                    'date'               => $ret->created_at->format('M d, Y h:i A'),
                ];
            });

        // Get Recent Requests (Medicine & Home Service) for the dashboard overview
        $hsRequests = HomeServiceRequest::where('user_id', $user->id)->orderBy('created_at', 'desc')->take(5)->get()->map(function ($req) {
            return [
                'id'     => $req->id,
                'name'   => $req->service_type ?? 'Home Service',
                'type'   => 'Home Service',
                'date'   => $req->created_at->format('Y-m-d'),
                'status' => $req->status,
            ];
        });

        $recentRequests = $myMedicineRequests->take(5)->map(fn($r) => [
            'id' => $r['id'], 'name' => $r['name'], 'type' => 'Medicine', 'date' => $r['date'], 'status' => $r['status'],
        ])->concat($hsRequests)->sortByDesc('date')->take(4)->values();

        // Stats
        $activeMedCount   = MedicineRequest::where('user_id', $user->id)->whereIn('status', ['Pending', 'Accepted', 'Approved'])->count();
        $activeHsCount    = HomeServiceRequest::where('user_id', $user->id)->whereIn('status', ['Pending', 'Approved'])->count();
        $activeRequestsCount = $activeMedCount + $activeHsCount;

        $completedMedCount = MedicineRequest::where('user_id', $user->id)->where('status', 'Completed')->count();
        $completedHsCount  = HomeServiceRequest::where('user_id', $user->id)->where('status', 'Completed')->count();
        $completedCount    = $completedMedCount + $completedHsCount;

        $upcomingSchedulesCount = Schedule::where('patient_name', 'like', '%' . $user->first_name . '%')->where('status', 'upcoming')->count();

        // Expired medicines count
        $expiredMedicinesCount = Medicine::whereNotNull('expiration_date')->where('expiration_date', '<=', \Carbon\Carbon::today())->count();
        $returnedMedicinesCount = $myMedicineReturns->count();

        // Announcements from database — syncs with Nurse and BHW management
        $announcements = \App\Models\Announcement::where('status', 'active')
            ->orderBy('date', 'desc')
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($ann) {
                return [
                    'id'      => $ann->id,
                    'title'   => $ann->title,
                    'date'    => $ann->date ? $ann->date->format('M d, Y') : $ann->created_at->format('M d, Y'),
                    'content' => $ann->content,
                    'author'  => $ann->author_name ?? 'Barangay Health Center',
                ];
            });

        // URL for the medicine request form POST action (passed to Vue)
        $medicineRequestStoreUrl = route('resident.requests.medicine.store');

        // URL for the home service request form POST action (passed to Vue)
        $homeServiceStoreUrl = route('resident.requests.home-service.store');

        // Get all booked home service times (excluding rejected) to block out slots
        $bookedHomeServices = HomeServiceRequest::where('status', '!=', 'Rejected')
            ->select('preferred_date', 'preferred_time')
            ->get()
            ->map(function ($req) {
                return [
                    'date' => $req->preferred_date ? $req->preferred_date->format('Y-m-d') : null,
                    'time' => $req->preferred_time,
                ];
            });

        // Get my upcoming schedules
        $mySchedules = Schedule::where('user_id', $user->id)
            ->orderBy('scheduled_date')
            ->orderBy('scheduled_time')
            ->get()
            ->map(function ($sch) {
                return [
                    'id' => $sch->id,
                    'formatted_id' => $sch->formatted_id,
                    'type' => $sch->type,
                    'service' => $sch->service,
                    'date' => $sch->scheduled_date->format('Y-m-d'),
                    'time' => \Carbon\Carbon::parse($sch->scheduled_time)->format('g:i A'),
                    'status' => $sch->status,
                ];
            });

        // Get all booked schedules (to block out times)
        $bookedSchedules = Schedule::whereIn('status', ['Pending', 'Confirmed'])
            ->select('scheduled_date', 'scheduled_time')
            ->get()
            ->map(function ($sch) {
                return [
                    'date' => $sch->scheduled_date->format('Y-m-d'),
                    'time' => \Carbon\Carbon::parse($sch->scheduled_time)->format('g:i A'), // Convert to 12h for easier matching
                ];
            });

        // Notifications
        $notifications = \App\Models\AppNotification::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();
            
        $markReadUrl = route('resident.notifications.read');

        // Notification summary counts for the dropdown (like nurse/BHW portal)
        // If the resident marked all as read, only count operational requests updated/created after that timestamp
        $readAt = $user->notifications_read_at;

        $pendingMedQuery = MedicineRequest::where('user_id', $user->id)->where('status', 'Pending');
        $acceptedMedQuery = MedicineRequest::where('user_id', $user->id)->where('status', 'Accepted');
        $approvedMedQuery = MedicineRequest::where('user_id', $user->id)->where('status', 'Approved');
        $pendingHomeServiceQuery = HomeServiceRequest::where('user_id', $user->id)->whereIn('status', ['Pending', 'Approved']);
        $upcomingScheduleQuery = Schedule::where('user_id', $user->id)->where('status', 'Confirmed');

        if ($readAt) {
            $pendingMedQuery->where('updated_at', '>', $readAt);
            $acceptedMedQuery->where('updated_at', '>', $readAt);
            $approvedMedQuery->where('updated_at', '>', $readAt);
            $pendingHomeServiceQuery->where('updated_at', '>', $readAt);
            $upcomingScheduleQuery->where('updated_at', '>', $readAt);
        }

        $pendingMedRequests = $pendingMedQuery->count();
        $acceptedMedRequests = $acceptedMedQuery->count();
        $approvedMedRequests = $approvedMedQuery->count();
        $pendingHomeServiceRequests = $pendingHomeServiceQuery->count();
        $upcomingScheduleCount = $upcomingScheduleQuery->count();

        $unreadReturnsCount = $readAt 
            ? MedicineReturn::where('user_id', $user->id)->where('created_at', '>', $readAt)->count()
            : $returnedMedicinesCount;

        $notificationSummary = [
            'pendingMedicineRequests' => $pendingMedRequests,
            'acceptedMedicineRequests' => $acceptedMedRequests,
            'approvedMedicineRequests' => $approvedMedRequests,
            'pendingHomeServices' => $pendingHomeServiceRequests,
            'upcomingSchedules' => $upcomingScheduleCount,
            'returnedMedicines' => $unreadReturnsCount,
        ];

        // URL for the schedule form POST action
        $scheduleStoreUrl = route('resident.requests.schedule.store');

        // URL for the profile update PUT action
        $profileUpdateUrl = route('resident.profile.update');

        return view('dashboards.resident', compact(
            'medicines',
            'myMedicineRequests',
            'myMedicineReturns',
            'recentRequests',
            'activeRequestsCount',
            'completedCount',
            'upcomingSchedulesCount',
            'expiredMedicinesCount',
            'announcements',
            'medicineRequestStoreUrl',
            'homeServiceStoreUrl',
            'bookedHomeServices',
            'mySchedules',
            'bookedSchedules',
            'scheduleStoreUrl',
            'notifications',
            'notificationSummary',
            'markReadUrl',
            'profileUpdateUrl'
        ));
    }
    
    /**
     * Mark all notifications as read.
     */
    public function markNotificationsRead()
    {
        $user = auth()->user();
        \App\Models\AppNotification::where('user_id', $user->id)->update(['is_read' => true]);
        $user->update(['notifications_read_at' => now()]);
        return response()->json(['success' => true]);
    }

    /**
     * Update the resident profile.
     */
    public function updateProfile(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'first_name'                 => ['required', 'string', 'max:255'],
            'last_name'                  => ['required', 'string', 'max:255'],
            'phone_number'               => ['required', 'regex:/^09[0-9]{9}$/', \Illuminate\Validation\Rule::unique('users')->ignore($user->id)],
            'email'                      => ['nullable', 'email', 'max:255', \Illuminate\Validation\Rule::unique('users')->ignore($user->id)],
            'age'                        => ['nullable', 'integer', 'min:0', 'max:130'],
            'dob'                        => ['nullable', 'date'],
            'gender'                     => ['nullable', 'string', 'max:50'],
            'blood_type'                 => ['nullable', 'string', 'max:10'],
            'address'                    => ['nullable', 'string', 'max:500'],
            'allergies'                  => ['nullable', 'string', 'max:500'],
            'chronic_conditions'         => ['nullable', 'string', 'max:500'],
            'last_checkup'               => ['nullable', 'date'],
            'emergency_contact_name'     => ['nullable', 'string', 'max:255'],
            'emergency_contact_relation' => ['nullable', 'string', 'max:255'],
            'emergency_contact_phone'    => ['nullable', 'regex:/^09[0-9]{9}$/'],
        ], [
            'phone_number.regex' => 'The phone number must be a valid 11-digit Philippine mobile number starting with 09 (e.g. 09123456789).',
            'emergency_contact_phone.regex' => 'The emergency contact phone number must be a valid 11-digit Philippine mobile number starting with 09 (e.g. 09123456789).',
        ]);

        if (isset($validated['age']) && $validated['age'] !== null) {
            $validated['age'] = (int) $validated['age'];
            $validated['age_category'] = \App\Models\User::resolveAgeCategory($validated['age']);
        }

        $user->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Profile updated successfully!',
            'user'    => $user->fresh(),
        ]);
    }
}

