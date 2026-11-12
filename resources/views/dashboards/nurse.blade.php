@extends('layouts.nurse')

@section('title', 'Nurse Dashboard')
@section('page-title', 'Nurse Dashboard')

@section('content')
    {{-- Stat Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-5 gap-5 mb-8">
        {{-- Pending Requests --}}
        <a href="{{ route('nurse.medicine-requests') }}" class="stat-card hover:border-blue-300 hover:shadow-md transition-all cursor-pointer">
            <div>
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Pending Requests</p>
                <p class="text-3xl font-extrabold text-gray-900">{{ $pendingCount }}</p>
            </div>
            <div class="w-11 h-11 rounded-full bg-red-50 flex items-center justify-center">
                <span class="material-icons-outlined text-red-500" style="font-size:22px;">pending_actions</span>
            </div>
        </a>

        {{-- Patients Verified Today --}}
        <div class="stat-card">
            <div>
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Patients Verified</p>
                <p class="text-3xl font-extrabold text-gray-900">{{ $verifiedTodayCount }}</p>
            </div>
            <div class="w-11 h-11 rounded-full bg-green-50 flex items-center justify-center">
                <span class="material-icons-outlined text-green-500" style="font-size:22px;">how_to_reg</span>
            </div>
        </div>

        {{-- Low Stock Items --}}
        <div class="stat-card">
            <div>
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Low Stock</p>
                <p class="text-3xl font-extrabold text-gray-900">{{ $totalLowStock }}</p>
            </div>
            <div class="w-11 h-11 rounded-full bg-orange-50 flex items-center justify-center">
                <span class="material-icons-outlined text-orange-500" style="font-size:22px;">inventory</span>
            </div>
        </div>

        {{-- Expired Medicines --}}
        <div class="stat-card">
            <div>
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Expired</p>
                <p class="text-3xl font-extrabold text-gray-900">{{ $expiredCount }}</p>
            </div>
            <div class="w-11 h-11 rounded-full bg-red-100 flex items-center justify-center">
                <span class="material-icons-outlined text-red-600" style="font-size:22px;">warning</span>
            </div>
        </div>

        {{-- Scheduled Appointments --}}
        <div class="stat-card">
            <div>
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Scheduled</p>
                <p class="text-3xl font-extrabold text-gray-900">{{ $scheduledAppointmentsCount }}</p>
            </div>
            <div class="w-11 h-11 rounded-full bg-blue-50 flex items-center justify-center">
                <span class="material-icons-outlined text-blue-500" style="font-size:22px;">calendar_month</span>
            </div>
        </div>
    </div>

    {{-- Charts Row --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        {{-- Medicine Inventory Overview --}}
        <div class="bg-white rounded-xl border border-gray-200 p-6">
            <h2 class="text-base font-bold text-gray-900 mb-5 flex items-center gap-2">
                <span class="material-icons-outlined text-blue-500" style="font-size:20px;">bar_chart</span>
                Medicine Inventory Overview
            </h2>
            <div style="height: 260px;">
                <canvas id="inventoryChart"></canvas>
            </div>
        </div>

        {{-- Request Distribution --}}
        <div class="bg-white rounded-xl border border-gray-200 p-6">
            <h2 class="text-base font-bold text-gray-900 mb-5 flex items-center gap-2">
                <span class="material-icons-outlined text-green-500" style="font-size:20px;">donut_large</span>
                Request Distribution
            </h2>
            <div class="flex items-center justify-center" style="height: 260px;">
                <canvas id="distributionChart"></canvas>
            </div>
        </div>
    </div>

    {{-- Action Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
        {{-- Verify Patients --}}
        <a href="#" class="group block rounded-xl p-6 text-white transition-all hover:scale-[1.02] hover:shadow-xl" style="background: linear-gradient(135deg, #059669, #10b981);">
            <span class="material-icons-outlined mb-3 block opacity-80" style="font-size:32px;">how_to_reg</span>
            <h3 class="text-lg font-bold">Verify Patients</h3>
            <p class="text-sm mt-1 opacity-80">Review and approve new patient registrations</p>
        </a>

        {{-- Manage Inventory --}}
        <a href="{{ route('nurse.inventory') }}" class="group block rounded-xl p-6 text-white transition-all hover:scale-[1.02] hover:shadow-xl" style="background: linear-gradient(135deg, #0891b2, #06b6d4);">
            <span class="material-icons-outlined mb-3 block opacity-80" style="font-size:32px;">inventory_2</span>
            <h3 class="text-lg font-bold">Manage Inventory</h3>
            <p class="text-sm mt-1 opacity-80">Update medicine stock and requests</p>
        </a>

        {{-- Generate Reports --}}
        <a href="#" class="group block rounded-xl p-6 text-white transition-all hover:scale-[1.02] hover:shadow-xl" style="background: linear-gradient(135deg, #2563eb, #3b82f6);">
            <span class="material-icons-outlined mb-3 block opacity-80" style="font-size:32px;">summarize</span>
            <h3 class="text-lg font-bold">Generate Reports</h3>
            <p class="text-sm mt-1 opacity-80">View analytics and create reports</p>
        </a>
    </div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js"></script>
<script>
    // Medicine Inventory Bar Chart
    const medicineNames = @json($medicines->pluck('name'));
    const medicineStocks = @json($medicines->pluck('stock'));

    const barCtx = document.getElementById('inventoryChart').getContext('2d');
    new Chart(barCtx, {
        type: 'bar',
        data: {
            labels: medicineNames,
            datasets: [{
                label: 'Stock',
                data: medicineStocks,
                backgroundColor: 'rgba(37, 99, 235, 0.85)',
                borderColor: 'rgba(37, 99, 235, 1)',
                borderWidth: 1,
                borderRadius: 6,
                hoverBackgroundColor: 'rgba(37, 99, 235, 1)',
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#0c2d48',
                    titleFont: { family: 'Inter', size: 12 },
                    bodyFont: { family: 'Inter', size: 12 },
                    padding: 12,
                    cornerRadius: 8,
                    callbacks: {
                        label: function(context) {
                            return 'stock : ' + context.parsed.y;
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    max: 160,
                    ticks: {
                        stepSize: 40,
                        font: { family: 'Inter', size: 11 },
                        color: '#94a3b8'
                    },
                    grid: { color: 'rgba(0,0,0,0.04)' }
                },
                x: {
                    ticks: {
                        font: { family: 'Inter', size: 11 },
                        color: '#94a3b8'
                    },
                    grid: { display: false }
                }
            }
        }
    });

    // Request Distribution Doughnut Chart
    const doughnutCtx = document.getElementById('distributionChart').getContext('2d');
    new Chart(doughnutCtx, {
        type: 'doughnut',
        data: {
            labels: ['Medicine', 'Home Service', 'Immunization', 'Ultrasound'],
            datasets: [{
                data: [
                    {{ $medicineRequestCount ?: 45 }},
                    {{ $homeServiceCount ?: 28 }},
                    15,
                    12
                ],
                backgroundColor: [
                    '#2563eb',
                    '#06b6d4',
                    '#f59e0b',
                    '#10b981'
                ],
                borderWidth: 0,
                hoverOffset: 8,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '55%',
            plugins: {
                legend: {
                    position: 'right',
                    labels: {
                        usePointStyle: true,
                        pointStyle: 'circle',
                        padding: 16,
                        font: { family: 'Inter', size: 12, weight: '500' },
                        color: '#475569',
                        generateLabels: function(chart) {
                            const data = chart.data;
                            const total = data.datasets[0].data.reduce((a, b) => a + b, 0);
                            return data.labels.map((label, i) => {
                                const value = data.datasets[0].data[i];
                                const pct = total > 0 ? Math.round((value / total) * 100) : 0;
                                return {
                                    text: label + ' ' + pct + '%',
                                    fillStyle: data.datasets[0].backgroundColor[i],
                                    hidden: false,
                                    index: i,
                                    pointStyle: 'circle',
                                };
                            });
                        }
                    }
                },
                tooltip: {
                    backgroundColor: '#0c2d48',
                    titleFont: { family: 'Inter', size: 12 },
                    bodyFont: { family: 'Inter', size: 12 },
                    padding: 12,
                    cornerRadius: 8,
                }
            }
        }
    });
</script>
@endsection
