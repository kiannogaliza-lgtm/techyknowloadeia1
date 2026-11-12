@extends('layouts.nurse')

@section('title', 'Returned Medicines')
@section('page-title', 'Returned & Damaged Medicines')

@section('content')
<div class="space-y-6">
    {{-- Header Banner --}}
    <div class="bg-gradient-to-r from-[#0c2d48] to-[#1a5a89] rounded-2xl p-6 text-white shadow-lg flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 text-cyan-200 text-xs font-bold uppercase tracking-wider mb-1">
                <span class="material-icons-outlined text-sm">assignment_return</span>
                Administrative Medicine Ledger
            </div>
            <h2 class="text-2xl font-bold">Returned & Damaged Medicines</h2>
            <p class="text-sm text-cyan-100/80 mt-1 max-w-2xl">
                Official registry of medicines that were broken, damaged, or adjusted during patient consultation or claiming.
            </p>
        </div>

        <div class="flex items-center gap-3">
            <div class="px-5 py-3 bg-white/10 backdrop-blur-md rounded-xl border border-white/15 text-center">
                <p class="text-[11px] text-cyan-200 uppercase font-semibold">Total Units Returned</p>
                <p class="text-2xl font-extrabold text-white">{{ $totalReturnedUnits }}</p>
            </div>
            <div class="px-5 py-3 bg-white/10 backdrop-blur-md rounded-xl border border-white/15 text-center">
                <p class="text-[11px] text-cyan-200 uppercase font-semibold">Total Logs</p>
                <p class="text-2xl font-extrabold text-white">{{ $totalRecords }}</p>
            </div>
        </div>
    </div>

    {{-- Main Table Container --}}
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden p-6">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-5 border-b border-gray-100">
            <div>
                <h3 class="font-bold text-[#1a5a89] text-base">Return & Damage History</h3>
                <p class="text-xs text-gray-500 mt-0.5">Comprehensive audit trail of all quantity adjustments and damaged medicine logs.</p>
            </div>

            {{-- Search --}}
            <form method="GET" action="{{ route('nurse.returns') }}" class="relative w-full sm:w-80">
                <span class="material-icons-outlined absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" style="font-size:18px;">search</span>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search patient, medicine, reason..."
                       class="w-full pl-9 pr-4 py-2 bg-gray-50 border border-gray-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-[#3ac1eb]/40 focus:border-[#3ac1eb] transition-all">
            </form>
        </div>

        @if($returns->isEmpty())
            <div class="py-16 text-center text-gray-400">
                <div class="w-16 h-16 rounded-full bg-emerald-50 text-emerald-500 flex items-center justify-center mx-auto mb-3">
                    <span class="material-icons-outlined" style="font-size: 32px;">verified</span>
                </div>
                <p class="text-base font-bold text-gray-700">No Returned or Damaged Medicines Recorded</p>
                <p class="text-xs text-gray-400 mt-1 max-w-sm mx-auto">
                    {{ request('search') ? 'No logs match your search keywords.' : 'All medicine disbursements are accounted for with no returned or damaged adjustments.' }}
                </p>
            </div>
        @else
            <div class="divide-y divide-gray-100 mt-2">
                @foreach($returns as $item)
                    @php
                        $isRestocked = in_array(strtolower(trim($item->reason)), [
                            'dosage adjusted during consultation',
                            'dosage adjusted by nurse during consultation',
                            'patient declined / partially returned'
                        ]);
                    @endphp
                    <div class="py-4.5 flex flex-col md:flex-row md:items-center justify-between gap-4 hover:bg-gray-50/60 p-3 rounded-xl transition-all">
                        <div class="flex items-start gap-3.5">
                            <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-600 border border-amber-200 flex items-center justify-center shrink-0 mt-0.5">
                                <span class="material-icons-outlined" style="font-size: 22px;">broken_image</span>
                            </div>
                            <div>
                                <div class="flex items-center gap-2 flex-wrap">
                                    <h4 class="text-sm font-bold text-gray-900">{{ $item->medicine_name }}</h4>
                                    <span class="px-2 py-0.5 text-[10px] font-bold rounded-full bg-blue-50 text-blue-700 border border-blue-200">
                                        {{ $item->medicineRequest ? $item->medicineRequest->request_id : ('REQ-' . str_pad($item->medicine_request_id, 3, '0', STR_PAD_LEFT)) }}
                                    </span>
                                    <span class="px-2 py-0.5 text-[10px] font-bold rounded-full bg-amber-100 text-amber-800 border border-amber-300">
                                        {{ $item->quantity_returned }} {{ $item->medicine ? $item->medicine->unit : 'unit(s)' }} Broken/Returned
                                    </span>
                                    <span class="text-xs text-gray-500 font-medium">
                                        • Patient: <strong class="text-gray-800">{{ $item->user ? ($item->user->first_name . ' ' . $item->user->last_name) : 'Unknown Resident' }}</strong>
                                    </span>
                                </div>

                                {{-- Quantity Difference Details --}}
                                <p class="text-xs text-gray-600 mt-1.5 flex items-center gap-2 flex-wrap">
                                    <span>Requested: <strong class="text-gray-800">{{ $item->previous_quantity }}</strong></span>
                                    <span class="text-gray-400">&rarr;</span>
                                    <span>Claimed/Received: <strong class="text-emerald-600">{{ $item->new_quantity }} {{ $item->medicine ? $item->medicine->unit : 'unit(s)' }}</strong></span>
                                    <span class="text-gray-300">•</span>
                                    <span class="text-red-600 font-semibold">-{{ $item->quantity_returned }} {{ $item->medicine ? $item->medicine->unit : 'unit(s)' }} deducted</span>
                                </p>

                                {{-- Reason --}}
                                <div class="mt-2 flex items-center gap-1.5 text-xs text-amber-900 bg-amber-50/80 px-2.5 py-1 rounded-lg border border-amber-200/60 w-fit">
                                    <span class="material-icons-outlined text-amber-600" style="font-size: 14px;">info</span>
                                    <span>Reason: <strong>{{ $item->reason }}</strong></span>
                                </div>
                            </div>
                        </div>

                        {{-- Metadata & Recorded Note --}}
                        <div class="text-left md:text-right shrink-0 border-t md:border-t-0 pt-2 md:pt-0 border-gray-100">
                            <div class="text-xs font-semibold text-gray-700">{{ $item->created_at->format('M d, Y h:i A') }}</div>
                            <div class="text-[11px] text-gray-400 mt-0.5">Recorded by: {{ $item->recorder ? ($item->recorder->first_name . ' ' . $item->recorder->last_name) : 'Healthcare Staff' }}</div>
                            <div class="text-[10px] font-medium mt-1 {{ $isRestocked ? 'text-emerald-700 font-semibold' : 'text-amber-700/80' }}">
                                Status: {{ $isRestocked ? 'Returned to Stock' : 'Not Returned to Stock' }}
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            @if($returns->hasPages())
                <div class="mt-6 pt-4 border-t border-gray-100">
                    {{ $returns->links() }}
                </div>
            @endif
        @endif
    </div>
</div>
@endsection

