@extends('layouts.nurse')

@section('title', 'Home Service Management')
@section('page-title', 'Home Service Management')

@section('styles')
<style>
    /* ── Tab Navigation ─────────────────────────────────────────── */
    .hs-tab {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 8px 22px;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        border: none;
        transition: all 0.2s ease;
        background: #f1f5f9;
        color: #475569;
    }
    .hs-tab:hover {
        background: #e2e8f0;
        color: #1e293b;
    }
    .hs-tab.active {
        background: #0c2d48;
        color: #ffffff;
        box-shadow: 0 4px 12px rgba(12, 45, 72, 0.25);
    }

    /* ── Request Card ─────────────────────────────────────────── */
    .hs-card {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 14px;
        padding: 22px 24px;
        transition: all 0.2s ease;
    }
    .hs-card:hover {
        border-color: #cbd5e1;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.04);
    }

    /* ── Status Badges ─────────────────────────────────────────── */
    .badge-hs-pending {
        display: inline-flex;
        align-items: center;
        padding: 2px 10px;
        border-radius: 9999px;
        font-size: 11px;
        font-weight: 600;
        background: #fef3c7;
        color: #d97706;
        border: 1px solid #fde68a;
    }
    .badge-hs-accepted {
        display: inline-flex;
        align-items: center;
        padding: 2px 10px;
        border-radius: 9999px;
        font-size: 11px;
        font-weight: 600;
        background: #dbeafe;
        color: #1d4ed8;
        border: 1px solid #bfdbfe;
    }
    .badge-hs-approved {
        display: inline-flex;
        align-items: center;
        padding: 2px 10px;
        border-radius: 9999px;
        font-size: 11px;
        font-weight: 600;
        background: #e0f2fe;
        color: #0284c7;
        border: 1px solid #bae6fd;
    }
    .badge-hs-completed {
        display: inline-flex;
        align-items: center;
        padding: 2px 10px;
        border-radius: 9999px;
        font-size: 11px;
        font-weight: 600;
        background: #dcfce7;
        color: #16a34a;
        border: 1px solid #bbf7d0;
    }
    .badge-hs-rejected {
        display: inline-flex;
        align-items: center;
        padding: 2px 10px;
        border-radius: 9999px;
        font-size: 11px;
        font-weight: 600;
        background: #fee2e2;
        color: #dc2626;
        border: 1px solid #fecaca;
    }

    /* ── Action Buttons ─────────────────────────────────────────── */
    .btn-action-view {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 6px 14px;
        border-radius: 7px;
        font-size: 12px;
        font-weight: 600;
        background: #2563eb;
        color: #ffffff;
        border: none;
        cursor: pointer;
        transition: all 0.2s;
    }
    .btn-action-view:hover {
        background: #1d4ed8;
        box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
    }

    .btn-action-approve {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 6px 14px;
        border-radius: 7px;
        font-size: 12px;
        font-weight: 600;
        background: #16a34a;
        color: #ffffff;
        border: none;
        cursor: pointer;
        transition: all 0.2s;
    }
    .btn-action-approve:hover {
        background: #15803d;
        box-shadow: 0 4px 12px rgba(22, 163, 74, 0.3);
    }

    .btn-action-reject {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 6px 14px;
        border-radius: 7px;
        font-size: 12px;
        font-weight: 600;
        background: #ef4444;
        color: #ffffff;
        border: none;
        cursor: pointer;
        transition: all 0.2s;
    }
    .btn-action-reject:hover {
        background: #dc2626;
        box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
    }

    .btn-action-delete {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 6px 12px;
        border-radius: 7px;
        font-size: 12px;
        font-weight: 600;
        background: #fee2e2;
        color: #dc2626;
        border: 1px solid #fecaca;
        cursor: pointer;
        transition: all 0.2s;
    }
    .btn-action-delete:hover {
        background: #dc2626;
        color: #ffffff;
        border-color: #dc2626;
        box-shadow: 0 4px 12px rgba(220, 38, 38, 0.25);
    }

    /* ── Reason / Symptoms Box ──────────────────────────────────── */
    .reason-box {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        padding: 12px 16px;
        margin-top: 16px;
    }

    /* ── Modal Overlay ─────────────────────────────────────────── */
    .hs-modal-overlay {
        position: fixed;
        inset: 0;
        background: rgba(0, 0, 0, 0.55);
        backdrop-filter: blur(4px);
        z-index: 60;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 24px;
        opacity: 0;
        pointer-events: none;
        transition: opacity 0.25s ease;
    }
    .hs-modal-overlay.active {
        opacity: 1;
        pointer-events: auto;
    }
    .hs-modal-card {
        background: #ffffff;
        border-radius: 16px;
        width: 100%;
        max-width: 580px;
        max-height: 90vh;
        overflow-y: auto;
        box-shadow: 0 25px 60px rgba(0, 0, 0, 0.2);
        transform: translateY(16px);
        transition: transform 0.25s ease;
    }
    .hs-modal-overlay.active .hs-modal-card {
        transform: translateY(0);
    }
</style>
@endsection

@section('content')
<div class="space-y-6">

    {{-- Main Container Card --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-8">

        {{-- Top Pill Tabs and Action Buttons --}}
        <div class="flex items-center gap-3 mb-6 pb-2 border-b border-gray-100 flex-wrap">
            <button type="button" onclick="switchTab('pending')" id="tab-btn-pending" class="hs-tab active">
                Pending ({{ $pendingCount }})
            </button>
            <button type="button" onclick="switchTab('accepted')" id="tab-btn-accepted" class="hs-tab">
                Accepted ({{ $acceptedCount }})
            </button>
            <button type="button" onclick="switchTab('approved')" id="tab-btn-approved" class="hs-tab">
                Approved ({{ $approvedCount }})
            </button>
            <button type="button" onclick="switchTab('completed')" id="tab-btn-completed" class="hs-tab">
                Completed ({{ $completedCount }})
            </button>
            @if($rejectedCount > 0)
            <button type="button" onclick="switchTab('rejected')" id="tab-btn-rejected" class="hs-tab">
                Rejected ({{ $rejectedCount }})
            </button>
            @endif

            <div class="ml-auto flex items-center gap-2">
                <button type="button" onclick="openClearModal()" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-red-50 hover:bg-red-100 text-red-600 border border-red-200 rounded-lg text-xs font-semibold shadow-sm transition-colors" title="Clear requests in list">
                    <span class="material-icons-outlined text-sm">delete_sweep</span>
                    <span>Clear List</span>
                </button>
                <button type="button" onclick="openNewRequestModal()" class="inline-flex items-center gap-1.5 px-3.5 py-1.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-xs font-semibold shadow-sm transition-colors">
                    <span class="material-icons-outlined text-sm">add</span>
                    <span>New Request</span>
                </button>
            </div>
        </div>

        {{-- ── TAB 1: PENDING REQUESTS ────────────────────────────── --}}
        <div id="tab-pane-pending" class="hs-pane space-y-4">
            @forelse($pendingRequests as $req)
                <div class="hs-card">
                    {{-- Header Row --}}
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <div class="flex items-center gap-2.5">
                                <h3 class="text-base font-bold text-gray-900">{{ $req->patient_name }}</h3>
                                <span class="badge-hs-pending">Pending</span>
                            </div>
                            <p class="text-xs text-gray-400 font-medium mt-0.5">Request ID: {{ $req->formatted_id }}</p>
                        </div>

                        {{-- Actions for Pending --}}
                        <div class="flex items-center gap-2">
                            <button type="button" onclick="viewDetails({{ json_encode($req) }})" class="btn-action-view">
                                <span class="material-icons-outlined" style="font-size:15px;">visibility</span>
                                <span>View</span>
                            </button>
                            <form method="POST" action="/nurse/home-services/{{ $req->id }}/accept" class="inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn-action-approve" style="background:#2563eb;" title="Accept request & notify resident request has been accepted">
                                    <span class="material-icons-outlined" style="font-size:15px;">check_circle</span>
                                    <span>Accept</span>
                                </button>
                            </form>
                            <button type="button" onclick="openRejectModal({{ json_encode($req) }})" class="btn-action-reject">
                                <span>Reject</span>
                            </button>
                        </div>
                    </div>

                    {{-- 3-Column Info Grid --}}
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-4">
                        {{-- Col 1: Service & Preferred Date --}}
                        <div>
                            <div class="text-[11px] font-medium text-gray-400 uppercase tracking-wide">Service Type</div>
                            <div class="text-sm font-semibold text-gray-800 mt-0.5">{{ $req->service_type }}</div>

                            <div class="text-[11px] font-medium text-gray-400 uppercase tracking-wide mt-3">Preferred Date</div>
                            <div class="text-sm font-semibold text-gray-800 mt-0.5">{{ \Carbon\Carbon::parse($req->preferred_date)->format('Y-m-d') }}</div>
                        </div>

                        {{-- Col 2: Address & Preferred Time --}}
                        <div>
                            <div class="text-[11px] font-medium text-gray-400 uppercase tracking-wide flex items-center gap-1">
                                <span class="material-icons-outlined text-gray-400" style="font-size:14px;">location_on</span>
                                <span>Address</span>
                            </div>
                            <div class="text-sm font-semibold text-gray-800 mt-0.5">{{ $req->address }}</div>

                            <div class="text-[11px] font-medium text-gray-400 uppercase tracking-wide mt-3">Preferred Time</div>
                            <div class="text-sm font-semibold text-gray-800 mt-0.5">{{ $req->preferred_time }}</div>
                        </div>

                        {{-- Col 3: Contact --}}
                        <div>
                            <div class="text-[11px] font-medium text-gray-400 uppercase tracking-wide flex items-center gap-1">
                                <span class="material-icons-outlined text-gray-400" style="font-size:14px;">call</span>
                                <span>Contact</span>
                            </div>
                            <div class="text-sm font-semibold text-gray-800 mt-0.5">{{ $req->contact_number }}</div>
                        </div>
                    </div>

                    {{-- Reason / Symptoms Box --}}
                    <div class="reason-box">
                        <div class="text-[11px] font-medium text-gray-400">Reason / Symptoms</div>
                        <div class="text-xs text-gray-700 font-medium mt-1">{{ $req->reason_symptoms ?: 'General health assessment needed' }}</div>
                    </div>
                </div>
            @empty
                <div class="text-center py-14 border border-dashed border-gray-200 rounded-xl bg-gray-50/50">
                    <span class="material-icons-outlined text-gray-300 text-5xl mb-2">pending_actions</span>
                    <p class="text-sm font-semibold text-gray-600">No Pending Requests</p>
                    <p class="text-xs text-gray-400 mt-1">All home service requests have been reviewed.</p>
                </div>
            @endforelse
        </div>

        {{-- ── TAB 2: ACCEPTED REQUESTS ───────────────────────────── --}}
        <div id="tab-pane-accepted" class="hs-pane space-y-4 hidden">
            @forelse($acceptedRequests as $req)
                <div class="hs-card">
                    {{-- Header Row --}}
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <div class="flex items-center gap-2.5">
                                <h3 class="text-base font-bold text-gray-900">{{ $req->patient_name }}</h3>
                                <span class="badge-hs-accepted">Accepted (Coming)</span>
                            </div>
                            <p class="text-xs text-gray-400 font-medium mt-0.5">Request ID: {{ $req->formatted_id }}</p>
                        </div>

                        {{-- Actions for Accepted --}}
                        <div class="flex items-center gap-2">
                            <button type="button" onclick="viewDetails({{ json_encode($req) }})" class="btn-action-view">
                                <span class="material-icons-outlined" style="font-size:15px;">visibility</span>
                                <span>View</span>
                            </button>
                            <button type="button" onclick="openApproveModal({{ json_encode($req) }})" class="btn-action-approve">
                                <span>Approve</span>
                            </button>
                            <form method="POST" action="/nurse/home-services/{{ $req->id }}/complete" class="inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn-action-approve" style="background:#059669;">
                                    <span class="material-icons-outlined" style="font-size:15px;">task_alt</span>
                                    <span>Complete</span>
                                </button>
                            </form>
                        </div>
                    </div>

                    {{-- 3-Column Info Grid --}}
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-4">
                        {{-- Col 1: Service & Preferred Date --}}
                        <div>
                            <div class="text-[11px] font-medium text-gray-400 uppercase tracking-wide">Service Type</div>
                            <div class="text-sm font-semibold text-gray-800 mt-0.5">{{ $req->service_type }}</div>

                            <div class="text-[11px] font-medium text-gray-400 uppercase tracking-wide mt-3">Preferred Date</div>
                            <div class="text-sm font-semibold text-gray-800 mt-0.5">{{ \Carbon\Carbon::parse($req->preferred_date)->format('Y-m-d') }}</div>
                        </div>

                        {{-- Col 2: Address & Preferred Time --}}
                        <div>
                            <div class="text-[11px] font-medium text-gray-400 uppercase tracking-wide flex items-center gap-1">
                                <span class="material-icons-outlined text-gray-400" style="font-size:14px;">location_on</span>
                                <span>Address</span>
                            </div>
                            <div class="text-sm font-semibold text-gray-800 mt-0.5">{{ $req->address }}</div>

                            <div class="text-[11px] font-medium text-gray-400 uppercase tracking-wide mt-3">Preferred Time</div>
                            <div class="text-sm font-semibold text-gray-800 mt-0.5">{{ $req->preferred_time }}</div>
                        </div>

                        {{-- Col 3: Contact & Status Info --}}
                        <div>
                            <div class="text-[11px] font-medium text-gray-400 uppercase tracking-wide flex items-center gap-1">
                                <span class="material-icons-outlined text-gray-400" style="font-size:14px;">call</span>
                                <span>Contact</span>
                            </div>
                            <div class="text-sm font-semibold text-gray-800 mt-0.5">{{ $req->contact_number }}</div>

                            <div class="text-[11px] font-medium text-gray-400 uppercase tracking-wide mt-3">Notification Status</div>
                            <div class="text-sm font-semibold text-blue-600 mt-0.5">Resident notified (team coming)</div>
                        </div>
                    </div>

                    {{-- Reason / Symptoms Box --}}
                    <div class="reason-box">
                        <div class="text-[11px] font-medium text-gray-400">Reason / Symptoms</div>
                        <div class="text-xs text-gray-700 font-medium mt-1">{{ $req->reason_symptoms ?: 'General health assessment needed' }}</div>
                    </div>
                </div>
            @empty
                <div class="text-center py-14 border border-dashed border-gray-200 rounded-xl bg-gray-50/50">
                    <span class="material-icons-outlined text-gray-300 text-5xl mb-2">airport_shuttle</span>
                    <p class="text-sm font-semibold text-gray-600">No Accepted Requests</p>
                    <p class="text-xs text-gray-400 mt-1">Accepted requests where the team is currently on the way will appear here.</p>
                </div>
            @endforelse
        </div>

        {{-- ── TAB 3: APPROVED REQUESTS ───────────────────────────── --}}
        <div id="tab-pane-approved" class="hs-pane space-y-4 hidden">
            @forelse($approvedRequests as $req)
                <div class="hs-card">
                    {{-- Header Row --}}
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <div class="flex items-center gap-2.5">
                                <h3 class="text-base font-bold text-gray-900">{{ $req->patient_name }}</h3>
                                <span class="badge-hs-approved">Approved</span>
                            </div>
                            <p class="text-xs text-gray-400 font-medium mt-0.5">Request ID: {{ $req->formatted_id }}</p>
                        </div>

                        {{-- Actions for Approved --}}
                        <div class="flex items-center gap-2">
                            <button type="button" onclick="viewDetails({{ json_encode($req) }})" class="btn-action-view">
                                <span class="material-icons-outlined" style="font-size:15px;">visibility</span>
                                <span>View</span>
                            </button>
                        </div>
                    </div>

                    {{-- 3-Column Info Grid --}}
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-4">
                        {{-- Col 1: Service & Preferred Date --}}
                        <div>
                            <div class="text-[11px] font-medium text-gray-400 uppercase tracking-wide">Service Type</div>
                            <div class="text-sm font-semibold text-gray-800 mt-0.5">{{ $req->service_type }}</div>

                            <div class="text-[11px] font-medium text-gray-400 uppercase tracking-wide mt-3">Preferred Date</div>
                            <div class="text-sm font-semibold text-gray-800 mt-0.5">{{ \Carbon\Carbon::parse($req->preferred_date)->format('Y-m-d') }}</div>
                        </div>

                        {{-- Col 2: Address & Preferred Time --}}
                        <div>
                            <div class="text-[11px] font-medium text-gray-400 uppercase tracking-wide flex items-center gap-1">
                                <span class="material-icons-outlined text-gray-400" style="font-size:14px;">location_on</span>
                                <span>Address</span>
                            </div>
                            <div class="text-sm font-semibold text-gray-800 mt-0.5">{{ $req->address }}</div>

                            <div class="text-[11px] font-medium text-gray-400 uppercase tracking-wide mt-3">Preferred Time</div>
                            <div class="text-sm font-semibold text-gray-800 mt-0.5">{{ $req->preferred_time }}</div>
                        </div>

                        {{-- Col 3: Contact & Assigned To --}}
                        <div>
                            <div class="text-[11px] font-medium text-gray-400 uppercase tracking-wide flex items-center gap-1">
                                <span class="material-icons-outlined text-gray-400" style="font-size:14px;">call</span>
                                <span>Contact</span>
                            </div>
                            <div class="text-sm font-semibold text-gray-800 mt-0.5">{{ $req->contact_number }}</div>

                            <div class="text-[11px] font-medium text-gray-400 uppercase tracking-wide mt-3">Assigned To</div>
                            <div class="text-sm font-semibold text-blue-600 mt-0.5">{{ $req->assigned_name ?: 'Unassigned' }}</div>
                        </div>
                    </div>

                    {{-- Reason / Symptoms Box --}}
                    <div class="reason-box">
                        <div class="text-[11px] font-medium text-gray-400">Reason / Symptoms</div>
                        <div class="text-xs text-gray-700 font-medium mt-1">{{ $req->reason_symptoms ?: 'Regular BP monitoring required' }}</div>
                    </div>
                </div>
            @empty
                <div class="text-center py-14 border border-dashed border-gray-200 rounded-xl bg-gray-50/50">
                    <span class="material-icons-outlined text-gray-300 text-5xl mb-2">check_circle_outline</span>
                    <p class="text-sm font-semibold text-gray-600">No Approved Requests</p>
                    <p class="text-xs text-gray-400 mt-1">Approved requests ready for visit or assignment will appear here.</p>
                </div>
            @endforelse
        </div>

        {{-- ── TAB 3: COMPLETED REQUESTS ──────────────────────────── --}}
        <div id="tab-pane-completed" class="hs-pane space-y-4 hidden">
            @forelse($completedRequests as $req)
                <div class="hs-card">
                    {{-- Header Row --}}
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <div class="flex items-center gap-2.5">
                                <h3 class="text-base font-bold text-gray-900">{{ $req->patient_name }}</h3>
                                <span class="badge-hs-completed">Completed</span>
                            </div>
                            <p class="text-xs text-gray-400 font-medium mt-0.5">Request ID: {{ $req->formatted_id }}</p>
                        </div>

                        {{-- Actions for Completed --}}
                        <div class="flex items-center gap-2">
                            <button type="button" onclick="viewDetails({{ json_encode($req) }})" class="btn-action-view">
                                <span class="material-icons-outlined" style="font-size:15px;">visibility</span>
                                <span>View</span>
                            </button>
                            <button type="button" onclick="openDeleteModal({{ json_encode($req) }})" class="btn-action-delete" title="Delete Request">
                                <span class="material-icons-outlined" style="font-size:15px;">delete</span>
                                <span>Delete</span>
                            </button>
                        </div>
                    </div>

                    {{-- 3-Column Info Grid --}}
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-4">
                        {{-- Col 1: Service & Preferred Date --}}
                        <div>
                            <div class="text-[11px] font-medium text-gray-400 uppercase tracking-wide">Service Type</div>
                            <div class="text-sm font-semibold text-gray-800 mt-0.5">{{ $req->service_type }}</div>

                            <div class="text-[11px] font-medium text-gray-400 uppercase tracking-wide mt-3">Preferred Date</div>
                            <div class="text-sm font-semibold text-gray-800 mt-0.5">{{ \Carbon\Carbon::parse($req->preferred_date)->format('Y-m-d') }}</div>
                        </div>

                        {{-- Col 2: Address & Preferred Time --}}
                        <div>
                            <div class="text-[11px] font-medium text-gray-400 uppercase tracking-wide flex items-center gap-1">
                                <span class="material-icons-outlined text-gray-400" style="font-size:14px;">location_on</span>
                                <span>Address</span>
                            </div>
                            <div class="text-sm font-semibold text-gray-800 mt-0.5">{{ $req->address }}</div>

                            <div class="text-[11px] font-medium text-gray-400 uppercase tracking-wide mt-3">Preferred Time</div>
                            <div class="text-sm font-semibold text-gray-800 mt-0.5">{{ $req->preferred_time }}</div>
                        </div>

                        {{-- Col 3: Contact & Assigned To --}}
                        <div>
                            <div class="text-[11px] font-medium text-gray-400 uppercase tracking-wide flex items-center gap-1">
                                <span class="material-icons-outlined text-gray-400" style="font-size:14px;">call</span>
                                <span>Contact</span>
                            </div>
                            <div class="text-sm font-semibold text-gray-800 mt-0.5">{{ $req->contact_number }}</div>

                            <div class="text-[11px] font-medium text-gray-400 uppercase tracking-wide mt-3">Assigned To</div>
                            <div class="text-sm font-semibold text-blue-600 mt-0.5">{{ $req->assigned_name ?: 'BHW Carlos Lopez' }}</div>
                        </div>
                    </div>

                    {{-- Reason / Symptoms Box --}}
                    <div class="reason-box">
                        <div class="text-[11px] font-medium text-gray-400">Reason / Symptoms</div>
                        <div class="text-xs text-gray-700 font-medium mt-1">{{ $req->reason_symptoms ?: 'Post surgical wound dressing change' }}</div>
                    </div>
                </div>
            @empty
                <div class="text-center py-14 border border-dashed border-gray-200 rounded-xl bg-gray-50/50">
                    <span class="material-icons-outlined text-gray-300 text-5xl mb-2">task_alt</span>
                    <p class="text-sm font-semibold text-gray-600">No Completed Requests</p>
                    <p class="text-xs text-gray-400 mt-1">Completed home visits will be archived here.</p>
                </div>
            @endforelse
        </div>

        {{-- ── TAB 4: REJECTED REQUESTS (IF ANY) ──────────────────── --}}
        @if($rejectedCount > 0)
        <div id="tab-pane-rejected" class="hs-pane space-y-4 hidden">
            @foreach($rejectedRequests as $req)
                <div class="hs-card opacity-80">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <div class="flex items-center gap-2.5">
                                <h3 class="text-base font-bold text-gray-900">{{ $req->patient_name }}</h3>
                                <span class="badge-hs-rejected">Rejected</span>
                            </div>
                            <p class="text-xs text-gray-400 font-medium mt-0.5">Request ID: {{ $req->formatted_id }}</p>
                        </div>
                        <div class="flex items-center gap-2">
                            <button type="button" onclick="viewDetails({{ json_encode($req) }})" class="btn-action-view">
                                <span class="material-icons-outlined" style="font-size:15px;">visibility</span>
                                <span>View</span>
                            </button>
                            <button type="button" onclick="openDeleteModal({{ json_encode($req) }})" class="btn-action-delete" title="Delete Request">
                                <span class="material-icons-outlined" style="font-size:15px;">delete</span>
                                <span>Delete</span>
                            </button>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-4">
                        <div>
                            <div class="text-[11px] font-medium text-gray-400 uppercase tracking-wide">Service Type</div>
                            <div class="text-sm font-semibold text-gray-800 mt-0.5">{{ $req->service_type }}</div>
                        </div>
                        <div>
                            <div class="text-[11px] font-medium text-gray-400 uppercase tracking-wide">Address</div>
                            <div class="text-sm font-semibold text-gray-800 mt-0.5">{{ $req->address }}</div>
                        </div>
                        <div>
                            <div class="text-[11px] font-medium text-gray-400 uppercase tracking-wide">Rejection Reason</div>
                            <div class="text-sm font-semibold text-red-600 mt-0.5">{{ $req->rejection_reason ?: 'None specified' }}</div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        @endif

    </div>

</div>

{{-- ── MODAL: VIEW DETAILS ────────────────────────────────────────── --}}
<div id="viewModal" class="hs-modal-overlay">
    <div class="hs-modal-card">
        {{-- Header --}}
        <div class="px-6 py-5 border-b border-gray-100 flex items-center justify-between">
            <div class="flex items-center gap-2.5">
                <div class="w-9 h-9 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center">
                    <span class="material-icons-outlined text-xl">home_repair_service</span>
                </div>
                <div>
                    <h3 class="font-bold text-gray-900 text-base" id="modalPatientName">Home Service Details</h3>
                    <p class="text-xs text-gray-400 font-medium" id="modalRequestId">HS-000</p>
                </div>
            </div>
            <button type="button" onclick="closeModal('viewModal')" class="text-gray-400 hover:text-gray-600 transition-colors">
                <span class="material-icons-outlined">close</span>
            </button>
        </div>

        {{-- Body --}}
        <div class="p-6 space-y-4">
            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-xl border border-gray-100">
                <span class="text-xs text-gray-500 font-medium">Request Status</span>
                <span id="modalStatusBadge" class="badge-hs-pending">Pending</span>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div class="p-3 bg-gray-50 rounded-xl border border-gray-100">
                    <span class="text-[11px] font-medium text-gray-400 uppercase tracking-wider block">Service Type</span>
                    <span id="modalServiceType" class="text-sm font-bold text-gray-800 mt-0.5 block">—</span>
                </div>
                <div class="p-3 bg-gray-50 rounded-xl border border-gray-100">
                    <span class="text-[11px] font-medium text-gray-400 uppercase tracking-wider block">Contact Number</span>
                    <span id="modalContact" class="text-sm font-bold text-gray-800 mt-0.5 block">—</span>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div class="p-3 bg-gray-50 rounded-xl border border-gray-100">
                    <span class="text-[11px] font-medium text-gray-400 uppercase tracking-wider block">Preferred Date</span>
                    <span id="modalDate" class="text-sm font-semibold text-gray-800 mt-0.5 block">—</span>
                </div>
                <div class="p-3 bg-gray-50 rounded-xl border border-gray-100">
                    <span class="text-[11px] font-medium text-gray-400 uppercase tracking-wider block">Preferred Time</span>
                    <span id="modalTime" class="text-sm font-semibold text-gray-800 mt-0.5 block">—</span>
                </div>
            </div>

            <div class="p-3 bg-gray-50 rounded-xl border border-gray-100">
                <span class="text-[11px] font-medium text-gray-400 uppercase tracking-wider block">Address</span>
                <span id="modalAddress" class="text-sm font-semibold text-gray-800 mt-0.5 block">—</span>
            </div>

            <div class="p-3 bg-gray-50 rounded-xl border border-gray-100" id="modalAssignedContainer">
                <span class="text-[11px] font-medium text-gray-400 uppercase tracking-wider block">Assigned Health Worker</span>
                <span id="modalAssignedTo" class="text-sm font-bold text-blue-600 mt-0.5 block">—</span>
            </div>

            <div class="p-3 bg-gray-50 rounded-xl border border-gray-100">
                <span class="text-[11px] font-medium text-gray-400 uppercase tracking-wider block">Reason / Symptoms</span>
                <p id="modalReason" class="text-xs text-gray-700 font-medium mt-1 leading-relaxed">—</p>
            </div>
        </div>

        {{-- Footer --}}
        <div class="px-6 py-4 border-t border-gray-100 flex items-center justify-between bg-gray-50/50 rounded-b-16">
            <div id="modalDynamicActions" class="flex items-center gap-2"></div>
            <div class="flex items-center gap-2 ml-auto">
                <button type="button" id="modalDeleteBtn" onclick="" class="btn-action-delete">
                    <span class="material-icons-outlined" style="font-size:15px;">delete</span>
                    <span>Delete</span>
                </button>
                <button type="button" onclick="closeModal('viewModal')" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 text-xs font-semibold rounded-lg transition-colors">
                    Close
                </button>
            </div>
        </div>
    </div>
</div>

{{-- ── MODAL: DELETE INDIVIDUAL REQUEST ──────────────────────────── --}}
<div id="deleteModal" class="hs-modal-overlay">
    <div class="hs-modal-card max-w-md">
        <form id="deleteForm" method="POST">
            @csrf
            @method('DELETE')
            <div class="p-6 text-center space-y-3">
                <div class="w-12 h-12 rounded-full bg-red-100 text-red-600 flex items-center justify-center mx-auto">
                    <span class="material-icons-outlined text-2xl">delete_forever</span>
                </div>
                <h3 class="font-bold text-gray-900 text-base" id="deleteModalTitle">Delete Request</h3>
                <p class="text-xs text-gray-500 leading-relaxed" id="deleteModalMessage">
                    Are you sure you want to permanently delete this home service request? This action cannot be undone.
                </p>
            </div>

            <div class="px-6 py-4 border-t border-gray-100 flex items-center justify-end gap-3 bg-gray-50/50 rounded-b-16">
                <button type="button" onclick="closeModal('deleteModal')" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 text-xs font-semibold rounded-lg transition-colors">
                    Cancel
                </button>
                <button type="submit" class="px-5 py-2 bg-red-600 hover:bg-red-700 text-white text-xs font-semibold rounded-lg shadow-sm transition-colors">
                    Delete Request
                </button>
            </div>
        </form>
    </div>
</div>

{{-- ── MODAL: CLEAR LIST / BULK DELETE ───────────────────────────── --}}
<div id="clearModal" class="hs-modal-overlay">
    <div class="hs-modal-card max-w-md">
        <form method="POST" action="{{ route('home-services.clear') }}">
            @csrf
            <div class="px-6 py-5 border-b border-gray-100 flex items-center justify-between">
                <div class="flex items-center gap-2.5">
                    <div class="w-9 h-9 rounded-full bg-red-50 text-red-600 flex items-center justify-center">
                        <span class="material-icons-outlined text-xl">delete_sweep</span>
                    </div>
                    <div>
                        <h3 class="font-bold text-gray-900 text-base">Clear Requests List</h3>
                        <p class="text-xs text-gray-400 font-medium">Remove multiple items at once</p>
                    </div>
                </div>
                <button type="button" onclick="closeModal('clearModal')" class="text-gray-400 hover:text-gray-600 transition-colors">
                    <span class="material-icons-outlined">close</span>
                </button>
            </div>

            <div class="p-6 space-y-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-2">Select which records to clear:</label>
                    <div class="space-y-2">
                        <label class="flex items-center gap-3 p-3 bg-gray-50 border border-gray-200 rounded-xl cursor-pointer hover:bg-gray-100 transition-colors">
                            <input type="radio" name="status" value="Completed" checked class="text-red-600 focus:ring-red-500">
                            <div>
                                <div class="text-xs font-bold text-gray-800">Completed Requests Only ({{ $completedCount }})</div>
                                <div class="text-[11px] text-gray-400">Safely clear past completed visit records</div>
                            </div>
                        </label>
                        <label class="flex items-center gap-3 p-3 bg-gray-50 border border-gray-200 rounded-xl cursor-pointer hover:bg-gray-100 transition-colors">
                            <input type="radio" name="status" value="Pending" class="text-red-600 focus:ring-red-500">
                            <div>
                                <div class="text-xs font-bold text-gray-800">Pending Requests Only ({{ $pendingCount }})</div>
                                <div class="text-[11px] text-gray-400">Clear all unanswered requests</div>
                            </div>
                        </label>
                        <label class="flex items-center gap-3 p-3 bg-gray-50 border border-gray-200 rounded-xl cursor-pointer hover:bg-gray-100 transition-colors">
                            <input type="radio" name="status" value="Approved" class="text-red-600 focus:ring-red-500">
                            <div>
                                <div class="text-xs font-bold text-gray-800">Approved Requests Only ({{ $approvedCount }})</div>
                                <div class="text-[11px] text-gray-400">Clear all approved schedule requests</div>
                            </div>
                        </label>
                        <label class="flex items-center gap-3 p-3 bg-red-50/40 border border-red-200 rounded-xl cursor-pointer hover:bg-red-50 transition-colors">
                            <input type="radio" name="status" value="all" class="text-red-600 focus:ring-red-500">
                            <div>
                                <div class="text-xs font-bold text-red-700">Clear ALL Requests ({{ $pendingCount + $approvedCount + $completedCount + $rejectedCount }})</div>
                                <div class="text-[11px] text-red-500">Wipe all home service request records completely</div>
                            </div>
                        </label>
                    </div>
                </div>
            </div>

            <div class="px-6 py-4 border-t border-gray-100 flex items-center justify-end gap-3 bg-gray-50/50 rounded-b-16">
                <button type="button" onclick="closeModal('clearModal')" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 text-xs font-semibold rounded-lg transition-colors">
                    Cancel
                </button>
                <button type="submit" class="px-5 py-2 bg-red-600 hover:bg-red-700 text-white text-xs font-semibold rounded-lg shadow-sm transition-colors">
                    Clear Selected
                </button>
            </div>
        </form>
    </div>
</div>

{{-- ── MODAL: APPROVE REQUEST ────────────────────────────────────── --}}
<div id="approveModal" class="hs-modal-overlay">
    <div class="hs-modal-card">
        <form id="approveForm" method="POST">
            @csrf
            @method('PATCH')
            <div class="px-6 py-5 border-b border-gray-100 flex items-center justify-between">
                <div class="flex items-center gap-2.5">
                    <div class="w-9 h-9 rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center">
                        <span class="material-icons-outlined text-xl">check_circle</span>
                    </div>
                    <div>
                        <h3 class="font-bold text-gray-900 text-base">Approve Home Service</h3>
                        <p class="text-xs text-gray-400 font-medium" id="approveModalSub">Assign worker and confirm schedule</p>
                    </div>
                </div>
                <button type="button" onclick="closeModal('approveModal')" class="text-gray-400 hover:text-gray-600 transition-colors">
                    <span class="material-icons-outlined">close</span>
                </button>
            </div>

            <div class="p-6 space-y-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Assign Barangay Health Worker (BHW)</label>
                    <select name="assigned_to" id="approveAssignedToSelect" class="w-full px-3.5 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500">
                        <option value="">-- Select BHW (or enter name below) --</option>
                        @foreach($bhwUsers as $bhw)
                            <option value="{{ $bhw->id }}" data-name="BHW {{ $bhw->first_name }} {{ $bhw->last_name }}">BHW {{ $bhw->first_name }} {{ $bhw->last_name }} ({{ $bhw->phone_number }})</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Or Custom Assigned Name</label>
                    <input type="text" name="assigned_bhw_name" id="approveAssignedCustomInput" placeholder="e.g. Kian, BHW Anna Garcia" class="w-full px-3.5 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500">
                    <p class="text-[11px] text-gray-400 mt-1">If you type a custom name here (e.g. Kian), this exact name will be used in the resident's notification.</p>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Nurse Instructions / Notes (Optional)</label>
                    <textarea name="nurse_notes" rows="3" placeholder="Add specific care instructions or notes for the visiting BHW..." class="w-full px-3.5 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500"></textarea>
                </div>
            </div>

            <div class="px-6 py-4 border-t border-gray-100 flex items-center justify-end gap-3 bg-gray-50/50 rounded-b-16">
                <button type="button" onclick="closeModal('approveModal')" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 text-xs font-semibold rounded-lg transition-colors">
                    Cancel
                </button>
                <button type="submit" class="px-5 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold rounded-lg shadow-sm transition-colors">
                    Confirm Approval
                </button>
            </div>
        </form>
    </div>
</div>

{{-- ── MODAL: REJECT REQUEST ─────────────────────────────────────── --}}
<div id="rejectModal" class="hs-modal-overlay">
    <div class="hs-modal-card">
        <form id="rejectForm" method="POST">
            @csrf
            @method('PATCH')
            <div class="px-6 py-5 border-b border-gray-100 flex items-center justify-between">
                <div class="flex items-center gap-2.5">
                    <div class="w-9 h-9 rounded-full bg-red-50 text-red-600 flex items-center justify-center">
                        <span class="material-icons-outlined text-xl">cancel</span>
                    </div>
                    <div>
                        <h3 class="font-bold text-gray-900 text-base">Reject Request</h3>
                        <p class="text-xs text-gray-400 font-medium" id="rejectModalSub">Provide a reason for rejection</p>
                    </div>
                </div>
                <button type="button" onclick="closeModal('rejectModal')" class="text-gray-400 hover:text-gray-600 transition-colors">
                    <span class="material-icons-outlined">close</span>
                </button>
            </div>

            <div class="p-6 space-y-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Reason for Rejection</label>
                    <textarea name="rejection_reason" rows="3" required placeholder="State reason (e.g. resident unreachable, service not offered for home visit, schedule conflict)..." class="w-full px-3.5 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-red-500/20 focus:border-red-500"></textarea>
                </div>
            </div>

            <div class="px-6 py-4 border-t border-gray-100 flex items-center justify-end gap-3 bg-gray-50/50 rounded-b-16">
                <button type="button" onclick="closeModal('rejectModal')" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 text-xs font-semibold rounded-lg transition-colors">
                    Cancel
                </button>
                <button type="submit" class="px-5 py-2 bg-red-600 hover:bg-red-700 text-white text-xs font-semibold rounded-lg shadow-sm transition-colors">
                    Reject Request
                </button>
            </div>
        </form>
    </div>
</div>

{{-- ── MODAL: NEW HOME SERVICE REQUEST ───────────────────────────── --}}
<div id="newRequestModal" class="hs-modal-overlay">
    <div class="hs-modal-card">
        <form method="POST" action="{{ route('home-services.store') }}">
            @csrf
            <div class="px-6 py-5 border-b border-gray-100 flex items-center justify-between">
                <div class="flex items-center gap-2.5">
                    <div class="w-9 h-9 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center">
                        <span class="material-icons-outlined text-xl">add_task</span>
                    </div>
                    <div>
                        <h3 class="font-bold text-gray-900 text-base">New Home Service Request</h3>
                        <p class="text-xs text-gray-400 font-medium">Log a home care visit request</p>
                    </div>
                </div>
                <button type="button" onclick="closeModal('newRequestModal')" class="text-gray-400 hover:text-gray-600 transition-colors">
                    <span class="material-icons-outlined">close</span>
                </button>
            </div>

            <div class="p-6 space-y-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Patient Full Name <span class="text-red-500">*</span></label>
                    <input type="text" name="patient_name" required placeholder="e.g. Juan Dela Cruz" class="w-full px-3.5 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500">
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Service Type <span class="text-red-500">*</span></label>
                        <select name="service_type" required class="w-full px-3.5 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500">
                            <option value="Medical Check-up">Medical Check-up</option>
                            <option value="Blood Pressure Monitoring">Blood Pressure Monitoring</option>
                            <option value="Wound Care">Wound Care</option>
                            <option value="Post-natal Care">Post-natal Care</option>
                            <option value="Elderly Health Check">Elderly Health Check</option>
                            <option value="Vaccination Assistance">Vaccination Assistance</option>
                            <option value="Medication Delivery">Medication Delivery</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Contact Number <span class="text-red-500">*</span></label>
                        <input type="tel" name="contact_number" required maxlength="11" pattern="09[0-9]{9}" inputmode="numeric" oninput="validatePhPhone(this)" placeholder="09123456789" title="Please enter an 11-digit Philippine number starting with 09 (e.g. 09123456789)" class="w-full px-3.5 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Address <span class="text-red-500">*</span></label>
                    <input type="text" name="address" required placeholder="e.g. 123 Main St, Barangay Honrado" class="w-full px-3.5 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500">
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Preferred Date <span class="text-red-500">*</span></label>
                        <input type="date" name="preferred_date" required value="{{ date('Y-m-d') }}" class="w-full px-3.5 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Preferred Time <span class="text-red-500">*</span></label>
                        <select name="preferred_time" required class="w-full px-3.5 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500">
                            <option value="8:00 AM - 10:00 AM">8:00 AM - 10:00 AM</option>
                            <option value="10:00 AM - 12:00 PM">10:00 AM - 12:00 PM</option>
                            <option value="1:00 PM - 3:00 PM">1:00 PM - 3:00 PM</option>
                            <option value="2:00 PM - 4:00 PM">2:00 PM - 4:00 PM</option>
                            <option value="3:00 PM - 5:00 PM">3:00 PM - 5:00 PM</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Reason / Symptoms</label>
                    <textarea name="reason_symptoms" rows="2" placeholder="e.g. General health assessment needed" class="w-full px-3.5 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500"></textarea>
                </div>
            </div>

            <div class="px-6 py-4 border-t border-gray-100 flex items-center justify-end gap-3 bg-gray-50/50 rounded-b-16">
                <button type="button" onclick="closeModal('newRequestModal')" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 text-xs font-semibold rounded-lg transition-colors">
                    Cancel
                </button>
                <button type="submit" class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold rounded-lg shadow-sm transition-colors">
                    Save Request
                </button>
            </div>
        </form>
    </div>
</div>

@endsection

@section('scripts')
<script>
    let activeRequest = null;

    // Tab switching function
    function switchTab(tab) {
        // Deactivate all tab buttons
        document.querySelectorAll('.hs-tab').forEach(el => el.classList.remove('active'));
        // Hide all panes
        document.querySelectorAll('.hs-pane').forEach(el => el.classList.add('hidden'));

        // Activate target button & pane
        const btn = document.getElementById('tab-btn-' + tab);
        const pane = document.getElementById('tab-pane-' + tab);
        if (btn) btn.classList.add('active');
        if (pane) pane.classList.remove('hidden');

        // Update URL param without full reload
        const url = new URL(window.location);
        url.searchParams.set('tab', tab);
        window.history.replaceState({}, '', url);
    }

    // Initialize tab from URL param if present
    document.addEventListener('DOMContentLoaded', () => {
        const params = new URLSearchParams(window.location.search);
        const activeTab = params.get('tab');
        if (activeTab && document.getElementById('tab-pane-' + activeTab)) {
            switchTab(activeTab);
        }
    });

    // Modal helpers
    function openModal(id) {
        const modal = document.getElementById(id);
        if (modal) modal.classList.add('active');
    }

    function closeModal(id) {
        const modal = document.getElementById(id);
        if (modal) modal.classList.remove('active');
    }

    function openNewRequestModal() {
        openModal('newRequestModal');
    }

    function openClearModal() {
        openModal('clearModal');
    }

    // Individual Delete Modal
    function openDeleteModal(req) {
        const form = document.getElementById('deleteForm');
        form.action = `/nurse/home-services/${req.id}`;
        document.getElementById('deleteModalTitle').textContent = `Delete Request ${req.formatted_id || ('HS-' + String(req.id).padStart(3, '0'))}`;
        document.getElementById('deleteModalMessage').textContent = `Are you sure you want to permanently delete the home service request for ${req.patient_name}? This action cannot be undone.`;
        openModal('deleteModal');
    }

    // View Details Modal
    function viewDetails(req) {
        activeRequest = req;
        document.getElementById('modalPatientName').textContent = req.patient_name || 'Home Service Request';
        document.getElementById('modalRequestId').textContent = 'Request ID: ' + (req.formatted_id || ('HS-' + String(req.id).padStart(3, '0')));
        
        // Status Badge
        const badgeEl = document.getElementById('modalStatusBadge');
        badgeEl.textContent = req.status;
        badgeEl.className = '';
        if (req.status === 'Pending') {
            badgeEl.className = 'badge-hs-pending';
        } else if (req.status === 'Accepted') {
            badgeEl.className = 'badge-hs-accepted';
        } else if (req.status === 'Approved') {
            badgeEl.className = 'badge-hs-approved';
        } else if (req.status === 'Completed') {
            badgeEl.className = 'badge-hs-completed';
        } else {
            badgeEl.className = 'badge-hs-rejected';
        }

        document.getElementById('modalServiceType').textContent = req.service_type || '—';
        document.getElementById('modalContact').textContent = req.contact_number || '—';
        document.getElementById('modalDate').textContent = req.preferred_date ? req.preferred_date.substring(0, 10) : '—';
        document.getElementById('modalTime').textContent = req.preferred_time || '—';
        document.getElementById('modalAddress').textContent = req.address || '—';
        document.getElementById('modalReason').textContent = req.reason_symptoms || 'None provided';

        const assignedContainer = document.getElementById('modalAssignedContainer');
        const assignedToEl = document.getElementById('modalAssignedTo');
        const assignedName = req.assigned_name || req.assigned_bhw_name;
        if (assignedName) {
            assignedToEl.textContent = assignedName;
            assignedContainer.classList.remove('hidden');
        } else if (req.status === 'Pending') {
            assignedToEl.textContent = 'Pending Assignment';
            assignedContainer.classList.remove('hidden');
        } else {
            assignedToEl.textContent = 'Unassigned';
            assignedContainer.classList.remove('hidden');
        }

        // Delete button hook in modal - only for Completed requests
        const deleteBtn = document.getElementById('modalDeleteBtn');
        if (req.status === 'Completed') {
            deleteBtn.classList.remove('hidden');
            deleteBtn.onclick = function() {
                closeModal('viewModal');
                openDeleteModal(req);
            };
        } else {
            deleteBtn.classList.add('hidden');
            deleteBtn.onclick = null;
        }

        // Dynamic footer actions
        const actionsContainer = document.getElementById('modalDynamicActions');
        actionsContainer.innerHTML = '';

        if (req.status === 'Pending') {
            actionsContainer.innerHTML = `
                <form method="POST" action="/nurse/home-services/${req.id}/accept" class="inline">
                    <input type="hidden" name="_token" value="${document.querySelector('meta[name="csrf-token"]').getAttribute('content')}">
                    <input type="hidden" name="_method" value="PATCH">
                    <button type="submit" class="btn-action-approve" style="background:#2563eb;">
                        <span class="material-icons-outlined" style="font-size:15px;">check_circle</span>
                        <span>Accept</span>
                    </button>
                </form>
                <button type="button" onclick="closeModal('viewModal'); openRejectModal(${JSON.stringify(req).replace(/"/g, '&quot;')})" class="btn-action-reject">
                    <span class="material-icons-outlined" style="font-size:15px;">close</span>
                    <span>Reject</span>
                </button>
            `;
        } else if (req.status === 'Accepted') {
            actionsContainer.innerHTML = `
                <button type="button" onclick="closeModal('viewModal'); openApproveModal(${JSON.stringify(req).replace(/"/g, '&quot;')})" class="btn-action-approve">
                    <span class="material-icons-outlined" style="font-size:15px;">check</span>
                    <span>Approve</span>
                </button>
                <form method="POST" action="/nurse/home-services/${req.id}/complete" class="inline">
                    <input type="hidden" name="_token" value="${document.querySelector('meta[name="csrf-token"]').getAttribute('content')}">
                    <input type="hidden" name="_method" value="PATCH">
                    <button type="submit" class="btn-action-approve" style="background:#059669;">
                        <span class="material-icons-outlined" style="font-size:15px;">task_alt</span>
                        <span>Mark as Completed</span>
                    </button>
                </form>
            `;
        } else if (req.status === 'Approved') {
            actionsContainer.innerHTML = `
                <form method="POST" action="/nurse/home-services/${req.id}/complete" class="inline">
                    <input type="hidden" name="_token" value="${document.querySelector('meta[name="csrf-token"]').getAttribute('content')}">
                    <input type="hidden" name="_method" value="PATCH">
                    <button type="submit" class="btn-action-approve" style="background:#059669;">
                        <span class="material-icons-outlined" style="font-size:15px;">task_alt</span>
                        <span>Mark as Completed</span>
                    </button>
                </form>
            `;
        }

        openModal('viewModal');
    }

    // Approve Modal
    function openApproveModal(req) {
        const form = document.getElementById('approveForm');
        form.action = `/nurse/home-services/${req.id}/approve`;
        document.getElementById('approveModalSub').textContent = `Patient: ${req.patient_name} (${req.formatted_id || ('HS-' + String(req.id).padStart(3, '0'))})`;
        
        // Reset or pre-fill inputs
        const customInput = document.getElementById('approveAssignedCustomInput');
        const selectBhw = document.getElementById('approveAssignedToSelect');
        if (customInput) customInput.value = req.assigned_bhw_name || '';
        if (selectBhw) selectBhw.value = req.assigned_to || '';
        
        openModal('approveModal');
    }

    // Reject Modal
    function openRejectModal(req) {
        const form = document.getElementById('rejectForm');
        form.action = `/nurse/home-services/${req.id}/reject`;
        document.getElementById('rejectModalSub').textContent = `Patient: ${req.patient_name} (${req.formatted_id || ('HS-' + String(req.id).padStart(3, '0'))})`;
        openModal('rejectModal');
    }

    function validatePhPhone(input) {
        let val = input.value.replace(/[^0-9]/g, '');
        if (val.length >= 1 && val[0] !== '0') {
            val = '';
        } else if (val.length >= 2 && val.substring(0, 2) !== '09') {
            val = '0';
        }
        input.value = val.slice(0, 11);
        if (input.value.length > 0 && (!input.value.startsWith('09') || input.value.length < 11)) {
            input.setCustomValidity('Must be an 11-digit Philippine phone number starting with 09 (e.g. 09123456789)');
        } else {
            input.setCustomValidity('');
        }
    }

    // Close on escape key or backdrop click
    document.querySelectorAll('.hs-modal-overlay').forEach(modal => {
        modal.addEventListener('click', (e) => {
            if (e.target === modal) modal.classList.remove('active');
        });
    });

    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            document.querySelectorAll('.hs-modal-overlay.active').forEach(m => m.classList.remove('active'));
        }
    });
</script>
@endsection
