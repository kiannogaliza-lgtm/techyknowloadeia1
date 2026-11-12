@extends('layouts.nurse')

@section('title', 'Ultrasound & Immunization Scheduling')
@section('page-title', 'Ultrasound & Immunization Scheduling')

@section('styles')
<style>
    /* ── View Toggle Tabs ──────────────────────────────────────── */
    .sch-tab {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 8px 20px;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        border: none;
        transition: all 0.2s ease;
        background: #f1f5f9;
        color: #475569;
    }
    .sch-tab:hover {
        background: #e2e8f0;
        color: #1e293b;
    }
    .sch-tab.active {
        background: #0c2d48;
        color: #ffffff;
        box-shadow: 0 4px 12px rgba(12, 45, 72, 0.25);
    }

    /* ── Schedule Table ────────────────────────────────────────── */
    .sch-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        font-size: 13px;
    }
    .sch-table thead th {
        background: #0c2d48;
        color: #ffffff;
        padding: 12px 16px;
        text-align: left;
        font-weight: 600;
        font-size: 12px;
        text-transform: uppercase;
        letter-spacing: 0.03em;
        white-space: nowrap;
    }
    .sch-table thead th:first-child { border-radius: 10px 0 0 0; }
    .sch-table thead th:last-child { border-radius: 0 10px 0 0; }
    .sch-table tbody tr {
        transition: background 0.15s ease;
    }
    .sch-table tbody tr:hover {
        background: #f8fafc;
    }
    .sch-table tbody td {
        padding: 14px 16px;
        border-bottom: 1px solid #e2e8f0;
        vertical-align: middle;
        color: #334155;
    }
    .sch-table tbody tr:last-child td {
        border-bottom: none;
    }

    /* ── Type Badges ───────────────────────────────────────────── */
    .badge-immunization {
        display: inline-flex;
        align-items: center;
        padding: 3px 12px;
        border-radius: 6px;
        font-size: 11px;
        font-weight: 600;
        background: #dcfce7;
        color: #16a34a;
        border: 1px solid #bbf7d0;
    }
    .badge-ultrasound {
        display: inline-flex;
        align-items: center;
        padding: 3px 12px;
        border-radius: 6px;
        font-size: 11px;
        font-weight: 600;
        background: #ede9fe;
        color: #7c3aed;
        border: 1px solid #ddd6fe;
    }

    /* ── Status Text ───────────────────────────────────────────── */
    .status-confirmed {
        color: #059669;
        font-weight: 600;
        font-size: 13px;
    }
    .status-pending {
        color: #d97706;
        font-weight: 600;
        font-size: 13px;
    }
    .status-cancelled {
        color: #dc2626;
        font-weight: 600;
        font-size: 13px;
    }

    /* ── Action Buttons ────────────────────────────────────────── */
    .btn-action {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 4px 12px;
        border-radius: 6px;
        font-size: 11px;
        font-weight: 600;
        cursor: pointer;
        border: none;
        transition: all 0.2s ease;
    }
    .btn-confirm {
        background: #059669;
        color: #fff;
    }
    .btn-confirm:hover {
        background: #047857;
        box-shadow: 0 2px 8px rgba(5, 150, 105, 0.3);
    }
    .btn-edit {
        background: #2563eb;
        color: #fff;
    }
    .btn-edit:hover {
        background: #1d4ed8;
        box-shadow: 0 2px 8px rgba(37, 99, 235, 0.3);
    }
    .btn-cancel {
        background: #dc2626;
        color: #fff;
    }
    .btn-cancel:hover {
        background: #b91c1c;
        box-shadow: 0 2px 8px rgba(220, 38, 38, 0.3);
    }
    .btn-assign {
        background: #7c3aed;
        color: #fff;
    }
    .btn-assign:hover {
        background: #6d28d9;
        box-shadow: 0 2px 8px rgba(124, 58, 237, 0.3);
    }
    .btn-reassign {
        background: #6b7280;
        color: #fff;
    }
    .btn-reassign:hover {
        background: #4b5563;
        box-shadow: 0 2px 8px rgba(107, 114, 128, 0.3);
    }
    /* BHW assigned badge */
    .bhw-badge {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 2px 8px;
        background: #ede9fe;
        color: #6d28d9;
        border: 1px solid #ddd6fe;
        border-radius: 6px;
        font-size: 11px;
        font-weight: 600;
        margin-top: 2px;
    }
    .bhw-badge.confirmed {
        background: #dcfce7;
        color: #15803d;
        border-color: #bbf7d0;
    }

    /* ── Time Slot Pills ───────────────────────────────────────── */
    .time-slot {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 8px 24px;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 600;
        background: #ecfdf5;
        color: #059669;
        border: 1px solid #a7f3d0;
        transition: all 0.2s ease;
        cursor: default;
    }
    .time-slot:hover {
        background: #d1fae5;
        border-color: #6ee7b7;
    }

    /* ── Calendar Grid ─────────────────────────────────────────── */
    .cal-grid {
        display: grid;
        grid-template-columns: repeat(7, 1fr);
        gap: 1px;
        background: #e2e8f0;
        border-radius: 12px;
        overflow: hidden;
    }
    .cal-header {
        background: #0c2d48;
        color: #ffffff;
        padding: 10px 4px;
        text-align: center;
        font-size: 12px;
        font-weight: 600;
    }
    .cal-day {
        background: #ffffff;
        min-height: 90px;
        padding: 6px 8px;
        position: relative;
        cursor: pointer;
        transition: background 0.15s ease;
    }
    .cal-day:hover {
        background: #f0f9ff;
    }
    .cal-day.today {
        background: #eff6ff;
    }
    .cal-day.other-month {
        background: #f8fafc;
        color: #cbd5e1;
    }
    .cal-day .day-number {
        font-size: 13px;
        font-weight: 600;
        color: #334155;
    }
    .cal-day.other-month .day-number {
        color: #cbd5e1;
    }
    .cal-day.today .day-number {
        background: #2563eb;
        color: #fff;
        width: 24px;
        height: 24px;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }
    .cal-dot {
        display: inline-block;
        width: 6px;
        height: 6px;
        border-radius: 50%;
        margin-right: 2px;
    }
    .cal-dot.immunization { background: #16a34a; }
    .cal-dot.ultrasound { background: #7c3aed; }
    .cal-event {
        font-size: 10px;
        padding: 2px 6px;
        border-radius: 4px;
        margin-top: 2px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .cal-event.immunization {
        background: #dcfce7;
        color: #16a34a;
    }
    .cal-event.ultrasound {
        background: #ede9fe;
        color: #7c3aed;
    }

    /* ── Add / Edit Modal ──────────────────────────────────────── */
    .sch-modal-overlay {
        position: fixed;
        inset: 0;
        background: rgba(0,0,0,0.5);
        backdrop-filter: blur(4px);
        z-index: 50;
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0;
        pointer-events: none;
        transition: opacity 0.3s ease;
    }
    .sch-modal-overlay.active {
        opacity: 1;
        pointer-events: auto;
    }
    .sch-modal {
        background: #ffffff;
        border-radius: 16px;
        padding: 32px;
        width: 100%;
        max-width: 520px;
        box-shadow: 0 25px 60px rgba(0,0,0,0.15);
        transform: translateY(20px);
        transition: transform 0.3s ease;
    }
    .sch-modal-overlay.active .sch-modal {
        transform: translateY(0);
    }
    .sch-modal h2 {
        font-size: 20px;
        font-weight: 700;
        color: #0f172a;
        margin-bottom: 24px;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .sch-modal label {
        display: block;
        font-size: 13px;
        font-weight: 600;
        color: #374151;
        margin-bottom: 6px;
    }
    .sch-modal input,
    .sch-modal select {
        width: 100%;
        padding: 10px 14px;
        border: 1px solid #d1d5db;
        border-radius: 8px;
        font-size: 14px;
        transition: border-color 0.2s ease, box-shadow 0.2s ease;
        outline: none;
        background: #fff;
    }
    .sch-modal input:focus,
    .sch-modal select:focus {
        border-color: #2563eb;
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.12);
    }
    .sch-form-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 16px;
    }
    .sch-form-full {
        grid-column: 1 / -1;
    }

    /* ── Empty State ───────────────────────────────────────────── */
    .sch-empty {
        text-align: center;
        padding: 48px 24px;
        color: #94a3b8;
    }
    .sch-empty .material-icons-outlined {
        font-size: 48px;
        margin-bottom: 12px;
        opacity: 0.5;
    }
</style>
@endsection

@section('content')

{{-- Header Card --}}
<div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6 mb-6">
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-3">
            <span class="material-icons-outlined text-blue-600" style="font-size:28px;">calendar_month</span>
            <h2 class="text-xl font-bold text-gray-900">Schedule Management</h2>
        </div>
        <button onclick="openAddModal()" class="flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-lg font-semibold text-sm shadow-md hover:shadow-lg transition-all hover:from-blue-700 hover:to-blue-800">
            <span class="material-icons-outlined" style="font-size:18px;">add</span> Add Schedule
        </button>
    </div>

    {{-- View Toggle --}}
    <div class="flex gap-2 mt-5">
        <button class="sch-tab active" id="tab-list" onclick="switchView('list')">List View</button>
        <button class="sch-tab" id="tab-calendar" onclick="switchView('calendar')">Calendar View</button>
    </div>
</div>

{{-- ═══════════════════════════════════════════════════════════ --}}
{{-- LIST VIEW                                                   --}}
{{-- ═══════════════════════════════════════════════════════════ --}}
<div id="view-list">
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
        @if($schedules->isEmpty())
            <div class="sch-empty">
                <span class="material-icons-outlined">event_busy</span>
                <p class="text-base font-semibold">No schedules yet</p>
                <p class="text-sm mt-1">Click "+ Add Schedule" to create your first appointment.</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="sch-table">
                    <thead>
                        <tr>
                            <th>Schedule ID</th>
                            <th>Patient</th>
                            <th>Type</th>
                            <th>Service</th>
                            <th>Date</th>
                            <th>Time</th>
                            <th>Status</th>
                            <th>Assigned BHW</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($schedules as $schedule)
                        <tr>
                            <td class="font-semibold text-gray-700">{{ $schedule->formatted_id }}</td>
                            <td>{{ $schedule->patient_name }}</td>
                            <td>
                                <span class="badge-{{ strtolower($schedule->type) }}">{{ $schedule->type }}</span>
                            </td>
                            <td>{{ $schedule->service }}</td>
                            <td>{{ $schedule->scheduled_date->format('Y-m-d') }}</td>
                            <td>
                                <span class="flex items-center gap-1">
                                    <span class="material-icons-outlined text-gray-400" style="font-size:16px;">schedule</span>
                                    {{ $schedule->formatted_time }}
                                </span>
                            </td>
                            <td>
                                <span class="status-{{ strtolower($schedule->status) }}">{{ $schedule->status }}</span>
                            </td>
                            {{-- Assigned BHW Cell --}}
                            <td>
                                @if($schedule->assigned_bhw_name)
                                    <span class="bhw-badge {{ $schedule->assignment_status === 'confirmed' ? 'confirmed' : '' }}">
                                        <span class="material-icons-outlined" style="font-size:12px;">{{ $schedule->assignment_status === 'confirmed' ? 'check_circle' : 'person' }}</span>
                                        {{ $schedule->assigned_bhw_name }}
                                    </span>
                                    @if($schedule->assignment_status === 'confirmed')
                                        <div style="font-size:10px;color:#15803d;margin-top:2px;">✓ BHW Confirmed</div>
                                    @endif
                                @else
                                    <span style="color:#94a3b8;font-size:12px;">Unassigned</span>
                                @endif
                            </td>
                            {{-- Actions Cell --}}
                            <td>
                                <div class="flex gap-2 flex-wrap">
                                    @if($schedule->status === 'Pending')
                                        <form method="POST" action="{{ route('scheduling.confirm', $schedule) }}" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn-action btn-confirm">Confirm</button>
                                        </form>
                                    @endif
                                    <button class="btn-action btn-edit" onclick='openEditModal(@json($schedule))'>Edit</button>
                                    <button
                                        class="btn-action {{ $schedule->assigned_bhw_name ? 'btn-reassign' : 'btn-assign' }}"
                                        onclick='openAssignModal(@json($schedule))'
                                    >
                                        <span class="material-icons-outlined" style="font-size:13px;vertical-align:middle;">person_add</span>
                                        {{ $schedule->assigned_bhw_name ? 'Reassign' : 'Assign BHW' }}
                                    </button>
                                    <form method="POST" action="{{ route('scheduling.destroy', $schedule) }}" class="inline" onsubmit="return confirm('Cancel schedule {{ $schedule->formatted_id }}?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-action btn-cancel">Cancel</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    {{-- Available Time Slots --}}
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6 mt-6">
        <h3 class="text-base font-bold text-blue-700 mb-4 flex items-center gap-2">
            <span class="material-icons-outlined" style="font-size:20px;">access_time</span>
            Available Time Slots — {{ \Carbon\Carbon::parse($selectedDate)->format('F d, Y') }}
        </h3>

        @if(count($availableSlots) > 0)
            <div class="flex flex-wrap gap-3">
                @foreach($availableSlots as $slot)
                    <div class="time-slot">{{ \Carbon\Carbon::parse($slot)->format('g:i A') }}</div>
                @endforeach
            </div>
        @else
            <p class="text-sm text-gray-400">No available time slots for this date.</p>
        @endif
    </div>
</div>

{{-- ═══════════════════════════════════════════════════════════ --}}
{{-- CALENDAR VIEW                                               --}}
{{-- ═══════════════════════════════════════════════════════════ --}}
<div id="view-calendar" style="display:none;">
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6">
        {{-- Month Navigation --}}
        @php
            $prevMonth = $calendarStart->copy()->subMonth()->format('Y-m');
            $nextMonth = $calendarStart->copy()->addMonth()->format('Y-m');
        @endphp
        <div class="flex items-center justify-between mb-5">
            <a href="{{ route('nurse.scheduling', ['month' => $prevMonth]) }}" class="flex items-center gap-1 text-sm font-semibold text-gray-500 hover:text-blue-600 transition-colors">
                <span class="material-icons-outlined" style="font-size:20px;">chevron_left</span> Prev
            </a>
            <h3 class="text-lg font-bold text-gray-900">{{ $calendarStart->format('F Y') }}</h3>
            <a href="{{ route('nurse.scheduling', ['month' => $nextMonth]) }}" class="flex items-center gap-1 text-sm font-semibold text-gray-500 hover:text-blue-600 transition-colors">
                Next <span class="material-icons-outlined" style="font-size:20px;">chevron_right</span>
            </a>
        </div>

        {{-- Legend --}}
        <div class="flex items-center gap-5 mb-4">
            <div class="flex items-center gap-1.5 text-xs font-medium text-gray-500">
                <span class="cal-dot immunization"></span> Immunization
            </div>
            <div class="flex items-center gap-1.5 text-xs font-medium text-gray-500">
                <span class="cal-dot ultrasound"></span> Ultrasound
            </div>
        </div>

        <div class="cal-grid">
            {{-- Header --}}
            @foreach(['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'] as $day)
                <div class="cal-header">{{ $day }}</div>
            @endforeach

            {{-- Days --}}
            @php
                $startDay = $calendarStart->copy()->startOfWeek(Carbon\Carbon::SUNDAY);
                $endDay = $calendarEnd->copy()->endOfWeek(Carbon\Carbon::SATURDAY);
                $current = $startDay->copy();
                $today = now()->toDateString();
            @endphp

            @while($current <= $endDay)
                @php
                    $dateKey = $current->format('Y-m-d');
                    $isCurrentMonth = $current->month === $calendarStart->month;
                    $isToday = $dateKey === $today;
                    $daySchedules = $calendarSchedules[$dateKey] ?? collect();
                @endphp
                <div class="cal-day {{ $isToday ? 'today' : '' }} {{ !$isCurrentMonth ? 'other-month' : '' }}">
                    <div class="day-number">{{ $current->day }}</div>
                    @if($isCurrentMonth && $daySchedules->count() > 0)
                        <div class="mt-1 space-y-0.5">
                            @foreach($daySchedules->take(3) as $evt)
                                <div class="cal-event {{ strtolower($evt->type) }}" title="{{ $evt->patient_name }} — {{ $evt->service }}">
                                    {{ $evt->formatted_time }} {{ Str::limit($evt->patient_name, 10) }}
                                </div>
                            @endforeach
                            @if($daySchedules->count() > 3)
                                <div class="text-[10px] text-gray-400 font-medium pl-1">+{{ $daySchedules->count() - 3 }} more</div>
                            @endif
                        </div>
                    @endif
                </div>
                @php $current->addDay(); @endphp
            @endwhile
        </div>
    </div>
</div>

{{-- ═══════════════════════════════════════════════════════════ --}}
{{-- ADD SCHEDULE MODAL                                          --}}
{{-- ═══════════════════════════════════════════════════════════ --}}
<div class="sch-modal-overlay" id="addModal">
    <div class="sch-modal">
        <h2>
            <span class="material-icons-outlined text-blue-600">add_circle</span>
            Add New Schedule
        </h2>
        <form method="POST" action="{{ route('scheduling.store') }}">
            @csrf
            <div class="sch-form-grid">
                <div class="sch-form-full">
                    <label for="add_patient_name">Patient Name</label>
                    <input type="text" id="add_patient_name" name="patient_name" placeholder="Enter patient name" required>
                </div>
                <div>
                    <label for="add_type">Type</label>
                    <select id="add_type" name="type" required onchange="updateServiceOptions(this.value, 'add_service')">
                        <option value="">Select type</option>
                        <option value="Immunization">Immunization</option>
                        <option value="Ultrasound">Ultrasound</option>
                    </select>
                </div>
                <div>
                    <label for="add_service">Service</label>
                    <select id="add_service" name="service" required>
                        <option value="">Select type first</option>
                    </select>
                </div>
                <div>
                    <label for="add_date">Date</label>
                    <input type="date" id="add_date" name="scheduled_date" required>
                </div>
                <div>
                    <label for="add_time">Time</label>
                    <select id="add_time" name="scheduled_time" required>
                        <option value="">Select time</option>
                        <option value="09:00">9:00 AM</option>
                        <option value="10:00">10:00 AM</option>
                        <option value="11:00">11:00 AM</option>
                        <option value="13:00">1:00 PM</option>
                        <option value="14:00">2:00 PM</option>
                        <option value="15:00">3:00 PM</option>
                        <option value="16:00">4:00 PM</option>
                        <option value="17:00">5:00 PM</option>
                    </select>
                </div>
            </div>
            <div class="flex justify-end gap-3 mt-6">
                <button type="button" onclick="closeModal('addModal')" class="px-5 py-2.5 bg-gray-100 text-gray-600 rounded-lg font-semibold text-sm hover:bg-gray-200 transition-all">Cancel</button>
                <button type="submit" class="px-5 py-2.5 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-lg font-semibold text-sm shadow-md hover:shadow-lg transition-all">Save Schedule</button>
            </div>
        </form>
    </div>
</div>

{{-- ═══════════════════════════════════════════════════════════ --}}
{{-- EDIT SCHEDULE MODAL                                         --}}
{{-- ═══════════════════════════════════════════════════════════ --}}
<div class="sch-modal-overlay" id="editModal">
    <div class="sch-modal">
        <h2>
            <span class="material-icons-outlined text-amber-500">edit</span>
            Edit Schedule
        </h2>
        <form method="POST" id="editForm">
            @csrf
            @method('PUT')
            <div class="sch-form-grid">
                <div class="sch-form-full">
                    <label for="edit_patient_name">Patient Name</label>
                    <input type="text" id="edit_patient_name" name="patient_name" required>
                </div>
                <div>
                    <label for="edit_type">Type</label>
                    <select id="edit_type" name="type" required onchange="updateServiceOptions(this.value, 'edit_service')">
                        <option value="Immunization">Immunization</option>
                        <option value="Ultrasound">Ultrasound</option>
                    </select>
                </div>
                <div>
                    <label for="edit_service">Service</label>
                    <select id="edit_service" name="service" required>
                        <option value="">Select service</option>
                    </select>
                </div>
                <div>
                    <label for="edit_date">Date</label>
                    <input type="date" id="edit_date" name="scheduled_date" required>
                </div>
                <div>
                    <label for="edit_time">Time</label>
                    <select id="edit_time" name="scheduled_time" required>
                        <option value="09:00">9:00 AM</option>
                        <option value="10:00">10:00 AM</option>
                        <option value="11:00">11:00 AM</option>
                        <option value="13:00">1:00 PM</option>
                        <option value="14:00">2:00 PM</option>
                        <option value="15:00">3:00 PM</option>
                        <option value="16:00">4:00 PM</option>
                        <option value="17:00">5:00 PM</option>
                    </select>
                </div>
                <div>
                    <label for="edit_status">Status</label>
                    <select id="edit_status" name="status">
                        <option value="Pending">Pending</option>
                        <option value="Confirmed">Confirmed</option>
                        <option value="Cancelled">Cancelled</option>
                    </select>
                </div>
            </div>
            <div class="flex justify-end gap-3 mt-6">
                <button type="button" onclick="closeModal('editModal')" class="px-5 py-2.5 bg-gray-100 text-gray-600 rounded-lg font-semibold text-sm hover:bg-gray-200 transition-all">Cancel</button>
                <button type="submit" class="px-5 py-2.5 bg-gradient-to-r from-amber-500 to-amber-600 text-white rounded-lg font-semibold text-sm shadow-md hover:shadow-lg transition-all">Update Schedule</button>
            </div>
        </form>
    </div>
</div>

@endsection

{{-- ═══════════════════════════════════════════════════════════ --}}
{{-- ASSIGN TO BHW MODAL                                        --}}
{{-- ═══════════════════════════════════════════════════════════ --}}
<div class="sch-modal-overlay" id="assignBhwModal">
    <div class="sch-modal" style="max-width:460px;">
        <h2>
            <span class="material-icons-outlined text-purple-600">person_add</span>
            Assign to BHW
        </h2>
        <div class="bg-purple-50 border border-purple-100 rounded-xl p-4 mb-5">
            <p class="text-sm font-semibold text-purple-700" id="assignScheduleId"></p>
            <p class="text-sm text-gray-600 mt-1" id="assignScheduleInfo"></p>
        </div>
        <form method="POST" id="assignBhwForm">
            @csrf
            @method('PATCH')
            <div>
                <label for="assign_bhw_select" class="block text-sm font-semibold text-gray-700 mb-2">Select BHW Worker</label>
                @if(isset($bhwUsers) && $bhwUsers->isNotEmpty())
                    <select id="assign_bhw_select" name="assigned_to" required
                        style="width:100%;padding:10px 14px;border:1px solid #d1d5db;border-radius:8px;font-size:14px;outline:none;background:#fff;">
                        <option value="">— Select a BHW —</option>
                        @foreach($bhwUsers as $bhw)
                            <option value="{{ $bhw->id }}">{{ $bhw->first_name }} {{ $bhw->last_name }}</option>
                        @endforeach
                    </select>
                @else
                    <div class="text-sm text-red-500 bg-red-50 border border-red-100 rounded-lg p-3">
                        <span class="material-icons-outlined" style="font-size:16px;vertical-align:middle;">warning</span>
                        No approved BHW users available. Please add BHW users in User Management.
                    </div>
                @endif
            </div>
            <div class="flex justify-end gap-3 mt-6">
                <button type="button" onclick="closeModal('assignBhwModal')" class="px-5 py-2.5 bg-gray-100 text-gray-600 rounded-lg font-semibold text-sm hover:bg-gray-200 transition-all">Cancel</button>
                @if(isset($bhwUsers) && $bhwUsers->isNotEmpty())
                    <button type="submit" class="px-5 py-2.5 text-white rounded-lg font-semibold text-sm shadow-md hover:shadow-lg transition-all" style="background:linear-gradient(to right,#7c3aed,#6d28d9);">
                        <span class="material-icons-outlined" style="font-size:15px;vertical-align:middle;">check</span>
                        Assign BHW
                    </button>
                @endif
            </div>
        </form>
    </div>
</div>

@section('scripts')
<script>
    // ── Service options per type ──────────────────────────────────
    const serviceOptions = {
        Immunization: [
            'COVID-19 Booster',
            'Flu Vaccine',
            'Hepatitis B Vaccine',
            'BCG Vaccine',
            'Measles Vaccine',
            'Tetanus Toxoid',
        ],
        Ultrasound: [
            'Prenatal Checkup',
            'General Ultrasound',
            'Abdominal Ultrasound',
            'Pelvic Ultrasound',
        ]
    };

    function updateServiceOptions(type, selectId) {
        const select = document.getElementById(selectId);
        const currentVal = select.value;
        select.innerHTML = '<option value="">Select service</option>';
        if (type && serviceOptions[type]) {
            serviceOptions[type].forEach(s => {
                const opt = document.createElement('option');
                opt.value = s;
                opt.textContent = s;
                if (s === currentVal) opt.selected = true;
                select.appendChild(opt);
            });
        }
    }

    // ── View Toggle ───────────────────────────────────────────────
    function switchView(view) {
        document.getElementById('view-list').style.display = view === 'list' ? 'block' : 'none';
        document.getElementById('view-calendar').style.display = view === 'calendar' ? 'block' : 'none';
        document.getElementById('tab-list').classList.toggle('active', view === 'list');
        document.getElementById('tab-calendar').classList.toggle('active', view === 'calendar');
    }

    // ── Modals ────────────────────────────────────────────────────
    function openAddModal() {
        document.getElementById('addModal').classList.add('active');
    }

    function openEditModal(schedule) {
        const form = document.getElementById('editForm');
        form.action = '/nurse/scheduling/' + schedule.id;
        document.getElementById('edit_patient_name').value = schedule.patient_name;
        document.getElementById('edit_type').value = schedule.type;
        updateServiceOptions(schedule.type, 'edit_service');
        document.getElementById('edit_service').value = schedule.service;
        document.getElementById('edit_date').value = schedule.scheduled_date ? schedule.scheduled_date.split('T')[0] : '';
        // Time may come as "HH:MM:SS" or "HH:MM"
        const rawTime = schedule.scheduled_time || '';
        const timeParts = rawTime.split(':');
        const timeVal = timeParts.length >= 2 ? timeParts[0].padStart(2, '0') + ':' + timeParts[1].padStart(2, '0') : '';
        document.getElementById('edit_time').value = timeVal;
        document.getElementById('edit_status').value = schedule.status;
        document.getElementById('editModal').classList.add('active');
    }

    function closeModal(id) {
        document.getElementById(id).classList.remove('active');
    }

    // ── Assign to BHW Modal ───────────────────────────────────────
    function openAssignModal(schedule) {
        const form = document.getElementById('assignBhwForm');
        form.action = '/nurse/scheduling/' + schedule.id + '/assign-bhw';
        document.getElementById('assignScheduleId').textContent = schedule.formatted_id;
        document.getElementById('assignScheduleInfo').textContent =
            schedule.patient_name + ' — ' + schedule.service;
        // Pre-select current assigned BHW if any
        const sel = document.getElementById('assign_bhw_select');
        if (sel && schedule.assigned_to) {
            sel.value = schedule.assigned_to;
        }
        document.getElementById('assignBhwModal').classList.add('active');
    }

    // Close modals on overlay click
    document.querySelectorAll('.sch-modal-overlay').forEach(overlay => {
        overlay.addEventListener('click', function(e) {
            if (e.target === this) this.classList.remove('active');
        });
    });

    // Close modals with Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            document.querySelectorAll('.sch-modal-overlay.active').forEach(m => m.classList.remove('active'));
        }
    });
</script>
@endsection
