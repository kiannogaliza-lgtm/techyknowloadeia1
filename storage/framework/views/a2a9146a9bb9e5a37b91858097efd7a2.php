<?php $__env->startSection('title', 'Reports & Analytics'); ?>
<?php $__env->startSection('page-title', 'Reports & Analytics'); ?>

<?php $__env->startSection('styles'); ?>
<style>
    /* Report-specific styles */
    .report-stat-card {
        background: #ffffff;
        border-radius: 12px;
        padding: 22px 24px;
        border: 1px solid #e2e8f0;
        position: relative;
        overflow: hidden;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .report-stat-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.08);
    }
    .report-stat-card .stat-label {
        font-size: 12px;
        font-weight: 600;
        color: #0369a1;
        text-transform: uppercase;
        letter-spacing: 0.03em;
        margin-bottom: 6px;
    }
    .report-stat-card .stat-value {
        font-size: 32px;
        font-weight: 800;
        color: #0f172a;
        line-height: 1.1;
    }
    .report-stat-card .stat-change {
        font-size: 12px;
        font-weight: 600;
        margin-top: 6px;
    }
    .report-stat-card .stat-change.positive { color: #059669; }
    .report-stat-card .stat-change.negative { color: #dc2626; }
    .report-stat-card .stat-icon {
        position: absolute;
        top: 16px;
        right: 16px;
        color: #0ea5e9;
        opacity: 0.6;
    }

    /* Chart card */
    .chart-card {
        background: #ffffff;
        border-radius: 12px;
        border: 1px solid #e2e8f0;
        padding: 24px;
    }
    .chart-card h3 {
        font-size: 15px;
        font-weight: 700;
        color: #0369a1;
        margin-bottom: 16px;
    }

    /* Section card */
    .section-card {
        background: #ffffff;
        border-radius: 12px;
        border: 1px solid #e2e8f0;
        padding: 24px;
    }
    .section-card h3 {
        font-size: 15px;
        font-weight: 700;
        color: #0f172a;
        margin-bottom: 16px;
    }

    /* Report header */
    .report-header {
        background: #ffffff;
        border-radius: 12px;
        border: 1px solid #e2e8f0;
        padding: 18px 24px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 24px;
    }
    .report-header h2 {
        font-size: 16px;
        font-weight: 700;
        color: #0f172a;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    /* Export buttons */
    .btn-export {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 8px 18px;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        border: none;
        transition: all 0.2s ease;
    }
    .btn-export-pdf {
        background: #dc2626;
        color: #ffffff;
    }
    .btn-export-pdf:hover {
        background: #b91c1c;
        box-shadow: 0 4px 12px rgba(220, 38, 38, 0.3);
    }
    .btn-export-excel {
        background: #059669;
        color: #ffffff;
    }
    .btn-export-excel:hover {
        background: #047857;
        box-shadow: 0 4px 12px rgba(5, 150, 105, 0.3);
    }

    /* Chart link */
    .chart-link {
        display: flex;
        align-items: center;
        gap: 4px;
        font-size: 13px;
        font-weight: 600;
        color: #2563eb;
        justify-content: center;
        margin-top: 12px;
        cursor: pointer;
        transition: color 0.2s;
    }
    .chart-link:hover { color: #1d4ed8; }

    /* Top medicines table */
    .top-medicines-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 14px;
    }
    .top-medicines-table thead tr {
        background: #f0f7ff;
        border-bottom: 2px solid #e2e8f0;
    }
    .top-medicines-table th {
        text-align: left;
        padding: 12px 16px;
        font-weight: 700;
        color: #0f172a;
        font-size: 13px;
    }
    .top-medicines-table td {
        padding: 14px 16px;
        border-bottom: 1px solid #f1f5f9;
        color: #334155;
    }
    .top-medicines-table tbody tr:hover {
        background: #f8fafc;
    }
    .top-medicines-table .medicine-name {
        color: #0369a1;
        font-weight: 600;
        text-decoration: underline;
        text-decoration-style: dotted;
        text-underline-offset: 3px;
    }
    .fulfilled-badge {
        color: #059669;
        font-weight: 700;
    }
    .pending-badge {
        color: #d97706;
        font-weight: 700;
    }
</style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    
    <div class="report-header">
        <h2>
            <span class="material-icons-outlined" style="font-size:22px; color:#0369a1;">assessment</span>
            Reports & Analytics
        </h2>
        <div class="flex items-center gap-3">
            <button class="btn-export btn-export-pdf" onclick="window.print()">
                <span class="material-icons-outlined" style="font-size:16px;">picture_as_pdf</span>
                Export PDF
            </button>
            <button class="btn-export btn-export-excel" id="exportExcelBtn">
                <span class="material-icons-outlined" style="font-size:16px;">table_view</span>
                Export Excel
            </button>
        </div>
    </div>

    
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-5 mb-7">
        
        <div class="report-stat-card">
            <span class="stat-icon material-icons-outlined" style="font-size:24px;">trending_up</span>
            <div class="stat-label">Total Patients</div>
            <div class="stat-value"><?php echo e($totalPatients); ?></div>
            <div class="stat-change <?php echo e($patientChange >= 0 ? 'positive' : 'negative'); ?>">
                <?php echo e($patientChange >= 0 ? '+' : ''); ?><?php echo e($patientChange); ?>% from last month
            </div>
        </div>

        
        <div class="report-stat-card">
            <span class="stat-icon material-icons-outlined" style="font-size:24px;">trending_up</span>
            <div class="stat-label">Medicine Requests</div>
            <div class="stat-value"><?php echo e($totalRequests); ?></div>
            <div class="stat-change <?php echo e($requestChange >= 0 ? 'positive' : 'negative'); ?>">
                <?php echo e($requestChange >= 0 ? '+' : ''); ?><?php echo e($requestChange); ?>% from last month
            </div>
        </div>

        
        <div class="report-stat-card">
            <span class="stat-icon material-icons-outlined" style="font-size:24px;">trending_up</span>
            <div class="stat-label">Home Services</div>
            <div class="stat-value"><?php echo e($homeServiceCount); ?></div>
            <div class="stat-change <?php echo e($hsChange >= 0 ? 'positive' : 'negative'); ?>">
                <?php echo e($hsChange >= 0 ? '+' : ''); ?><?php echo e($hsChange); ?>% from last month
            </div>
        </div>

        
        <div class="report-stat-card">
            <span class="stat-icon material-icons-outlined" style="font-size:24px;">trending_up</span>
            <div class="stat-label">Immunizations</div>
            <div class="stat-value"><?php echo e($immunizationCount); ?></div>
            <div class="stat-change <?php echo e($immChange >= 0 ? 'positive' : 'negative'); ?>">
                <?php echo e($immChange >= 0 ? '+' : ''); ?><?php echo e($immChange); ?>% from last month
            </div>
        </div>
    </div>

    
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-7">
        
        <div class="chart-card">
            <h3>Patient Registration Trend</h3>
            <div style="height: 240px;">
                <canvas id="patientTrendChart"></canvas>
            </div>
            <div class="chart-link">
                <span class="material-icons-outlined" style="font-size:16px;">arrow_forward</span>
                Patients
            </div>
        </div>

        
        <div class="chart-card">
            <h3>Medicine Requests Trend</h3>
            <div style="height: 240px;">
                <canvas id="requestTrendChart"></canvas>
            </div>
            <div class="chart-link">
                <span class="material-icons-outlined" style="font-size:16px;">arrow_forward</span>
                Requests
            </div>
        </div>
    </div>

    

    
    <div class="chart-card mb-7">
        <h3>Service Comparison</h3>
        <div style="height: 300px;">
            <canvas id="serviceComparisonChart"></canvas>
        </div>
    </div>

    
    <div class="section-card">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h3 class="!mb-1">Resident Medicine Requests</h3>
                <p class="text-xs text-gray-500">Real-time log of recent residents who requested medicines</p>
            </div>
            <span class="text-xs font-semibold px-3 py-1 bg-blue-50 text-blue-700 rounded-full border border-blue-100">
                Total Requests: <?php echo e($residentRequests->count()); ?>

            </span>
        </div>

        <?php if($residentRequests->isEmpty()): ?>
            <div class="text-center py-10 text-gray-400">
                <span class="material-icons-outlined mb-2" style="font-size:42px;">medication</span>
                <p class="font-medium">No resident requests yet</p>
                <p class="text-sm mt-1">Data will appear once residents submit medicine requests.</p>
            </div>
        <?php else: ?>
            <div class="overflow-x-auto border border-gray-200 rounded-xl">
                <table class="top-medicines-table" id="residentRequestsTable">
                    <thead>
                        <tr>
                            <th style="border-right: 1px solid #e2e8f0;">Name of Resident (Requester)</th>
                            <th style="border-right: 1px solid #e2e8f0;">Actual Patient (Recipient)</th>
                            <th style="border-right: 1px solid #e2e8f0; text-align: center;">Maintenance</th>
                            <th style="border-right: 1px solid #e2e8f0;">Medicine Requested</th>
                            <th style="border-right: 1px solid #e2e8f0; text-align: center;">Quantity</th>
                            <th style="border-right: 1px solid #e2e8f0; text-align: center;">Med Distribution</th>
                            <th style="text-align: center;">Total Requests</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $residentRequests; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $req): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td style="border-right: 1px solid #f1f5f9; font-weight: 600; color: #1e293b;">
                                    <div class="flex items-center gap-2.5">
                                        <div class="w-7 h-7 rounded-full bg-[#1a5a89] text-white flex items-center justify-center text-xs font-bold shrink-0">
                                            <?php echo e(strtoupper(substr($req->resident_name, 0, 1))); ?>

                                        </div>
                                        <div>
                                            <div class="leading-tight"><?php echo e($req->resident_name); ?></div>
                                            <div class="text-[11px] text-gray-500 font-normal">
                                                <?php echo e($req->requester_age ? $req->requester_age : 'Age not recorded'); ?>

                                                <?php if($req->requester_category): ?> &bull; <?php echo e($req->requester_category); ?> <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td style="border-right: 1px solid #f1f5f9;">
                                    <?php if($req->is_for_other): ?>
                                        <div class="font-bold text-purple-800 text-xs flex items-center gap-1">
                                            <span class="material-icons-outlined" style="font-size:14px;">family_restroom</span>
                                            <?php echo e($req->patient_name); ?>

                                        </div>
                                        <div class="text-[11px] text-purple-700 mt-0.5">
                                            <?php echo e($req->patient_age ?? 'Age N/A'); ?> &bull; <?php echo e($req->patient_rel); ?>

                                        </div>
                                    <?php else: ?>
                                        <div class="text-xs font-medium text-gray-700 flex items-center gap-1">
                                            <span class="material-icons-outlined text-gray-400" style="font-size:14px;">person</span>
                                            Self (<?php echo e($req->requester_age ?? 'Resident'); ?>)
                                        </div>
                                    <?php endif; ?>
                                </td>
                                <td style="border-right: 1px solid #f1f5f9; text-align: center;">
                                    <?php if(!empty($req->is_maintenance)): ?>
                                        <span class="inline-flex items-center gap-1 text-[11px] font-bold px-2 py-0.5 rounded-full bg-amber-50 text-amber-700 border border-amber-200">
                                            <span class="material-icons-outlined" style="font-size:13px;">medication</span> Yes
                                        </span>
                                    <?php else: ?>
                                        <span class="text-xs text-gray-400 font-medium">—</span>
                                    <?php endif; ?>
                                </td>
                                <td style="border-right: 1px solid #f1f5f9;">
                                    <span class="medicine-name"><?php echo e($req->medicine_name); ?></span>
                                </td>
                                <td style="border-right: 1px solid #f1f5f9; text-align: center; font-weight: 700; color: #0f172a;">
                                    <?php echo e($req->quantity); ?>

                                </td>
                                <td style="border-right: 1px solid #f1f5f9; text-align: center;">
                                    <?php if(strtolower($req->delivery_method) === 'delivery'): ?>
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-purple-50 text-purple-700 border border-purple-200">
                                            <span class="material-icons-outlined" style="font-size:14px;">local_shipping</span> Home Delivery
                                        </span>
                                    <?php else: ?>
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                            <span class="material-icons-outlined" style="font-size:14px;">storefront</span> Barangay Health Center
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td style="text-align: center; font-weight: 700; color: #1a5a89;">
                                    <span class="inline-flex items-center justify-center px-3 py-1 rounded-full text-xs font-bold bg-blue-50 text-blue-800 border border-blue-200">
                                        <?php echo e($req->total_requests); ?>

                                    </span>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>

    
    <div class="report-card">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
            <div>
                <h2 class="text-base font-bold text-gray-900 flex items-center gap-2">
                    <span class="material-icons-outlined text-green-600" style="font-size:22px;">vaccines</span>
                    Children Vaccination & Immunization Log
                </h2>
                <p class="text-xs text-gray-400 mt-0.5">Records of infant, toddler, and child vaccinations administered, tracked across 1st, 2nd, and succeeding doses.</p>
            </div>
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-green-50 text-green-700 border border-green-200 self-start sm:self-auto">
                Total Vaccines Logged: <?php echo e($childVaccinations->count()); ?>

            </span>
        </div>

        <?php if($childVaccinations->isEmpty()): ?>
            <div class="text-center py-10 text-gray-400">
                <span class="material-icons-outlined mb-2 text-gray-300" style="font-size:42px;">child_care</span>
                <p class="font-medium text-gray-600">No children vaccination records logged yet</p>
                <p class="text-xs text-gray-400 mt-1">Vaccination doses administered will be listed here.</p>
            </div>
        <?php else: ?>
            <div class="overflow-x-auto border border-gray-200 rounded-xl">
                <table class="top-medicines-table">
                    <thead>
                        <tr>
                            <th style="border-right: 1px solid #e2e8f0;">Child Name</th>
                            <th style="border-right: 1px solid #e2e8f0;">Age / Category</th>
                            <th style="border-right: 1px solid #e2e8f0;">Parent / Resident</th>
                            <th style="border-right: 1px solid #e2e8f0;">Vaccine</th>
                            <th style="border-right: 1px solid #e2e8f0; text-align: center;">Dose</th>
                            <th style="border-right: 1px solid #e2e8f0;">Administered Date</th>
                            <th style="border-right: 1px solid #e2e8f0;">Provider</th>
                            <th style="text-align: center;">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $childVaccinations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $vac): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td style="border-right: 1px solid #f1f5f9; font-weight: 700; color: #1e293b;">
                                    <div class="flex items-center gap-2">
                                        <div class="w-6 h-6 rounded-full bg-green-100 text-green-700 flex items-center justify-center text-xs font-bold">
                                            <?php echo e(strtoupper(substr($vac->child_name, 0, 1))); ?>

                                        </div>
                                        <span><?php echo e($vac->child_name); ?></span>
                                    </div>
                                </td>
                                <td style="border-right: 1px solid #f1f5f9; font-size: 11px; color: #475569;">
                                    <?php echo e($vac->age_category); ?>

                                </td>
                                <td style="border-right: 1px solid #f1f5f9; font-size: 12px; color: #1a5a89; font-weight: 600;">
                                    <?php echo e($vac->parent ? ($vac->parent->first_name . ' ' . $vac->parent->last_name) : 'Walk-in / Guardian'); ?>

                                </td>
                                <td style="border-right: 1px solid #f1f5f9; font-weight: 600; color: #0f172a;">
                                    <?php echo e($vac->vaccine_name); ?>

                                </td>
                                <td style="border-right: 1px solid #f1f5f9; text-align: center;">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-blue-50 text-blue-700 border border-blue-200">
                                        <?php echo e($vac->dose_number); ?>

                                    </span>
                                </td>
                                <td style="border-right: 1px solid #f1f5f9; font-size: 12px; color: #64748b;">
                                    <?php echo e($vac->date_administered ? $vac->date_administered->format('M d, Y') : 'Pending'); ?>

                                </td>
                                <td style="border-right: 1px solid #f1f5f9; font-size: 11px; color: #64748b;">
                                    <?php echo e($vac->administered_by ?? 'Health Center Staff'); ?>

                                </td>
                                <td style="text-align: center;">
                                    <span class="inline-flex items-center gap-1 text-[11px] font-bold px-2 py-0.5 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        <span class="material-icons-outlined" style="font-size:12px;">check</span> <?php echo e($vac->status); ?>

                                    </span>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js"></script>
<script>
    const monthLabels = <?php echo json_encode($monthLabels, 15, 512) ?>;

    // ── Shared chart defaults ────────────────────────────────────
    const tooltipOptions = {
        backgroundColor: '#0c2d48',
        titleFont: { family: 'Inter', size: 12 },
        bodyFont: { family: 'Inter', size: 12 },
        padding: 12,
        cornerRadius: 8,
    };

    const gridColor = 'rgba(0,0,0,0.04)';
    const tickFont  = { family: 'Inter', size: 11 };
    const tickColor = '#94a3b8';

    // ── Patient Registration Trend (line) ────────────────────────
    new Chart(document.getElementById('patientTrendChart').getContext('2d'), {
        type: 'line',
        data: {
            labels: monthLabels,
            datasets: [{
                label: 'Patients',
                data: <?php echo json_encode($patientTrend, 15, 512) ?>,
                borderColor: '#0c4a6e',
                backgroundColor: 'rgba(12, 74, 110, 0.08)',
                borderWidth: 2.5,
                pointRadius: 5,
                pointBackgroundColor: '#0c4a6e',
                pointBorderColor: '#ffffff',
                pointBorderWidth: 2,
                pointHoverRadius: 7,
                tension: 0.3,
                fill: true,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: tooltipOptions,
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { font: tickFont, color: tickColor },
                    grid: { color: gridColor },
                },
                x: {
                    ticks: { font: tickFont, color: tickColor },
                    grid: { color: gridColor, drawBorder: false },
                }
            }
        }
    });

    // ── Medicine Requests Trend (line) ───────────────────────────
    new Chart(document.getElementById('requestTrendChart').getContext('2d'), {
        type: 'line',
        data: {
            labels: monthLabels,
            datasets: [{
                label: 'Requests',
                data: <?php echo json_encode($requestTrend, 15, 512) ?>,
                borderColor: '#06b6d4',
                backgroundColor: 'rgba(6, 182, 212, 0.08)',
                borderWidth: 2.5,
                pointRadius: 5,
                pointBackgroundColor: '#06b6d4',
                pointBorderColor: '#ffffff',
                pointBorderWidth: 2,
                pointHoverRadius: 7,
                tension: 0.3,
                fill: true,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: tooltipOptions,
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { font: tickFont, color: tickColor },
                    grid: { color: gridColor },
                },
                x: {
                    ticks: { font: tickFont, color: tickColor },
                    grid: { color: gridColor, drawBorder: false },
                }
            }
        }
    });

    // ── Service Comparison (grouped bar) ─────────────────────────
    new Chart(document.getElementById('serviceComparisonChart').getContext('2d'), {
        type: 'bar',
        data: {
            labels: monthLabels,
            datasets: [
                {
                    label: 'Home Service',
                    data: <?php echo json_encode($homeServiceTrend, 15, 512) ?>,
                    backgroundColor: '#0c4a6e',
                    borderRadius: 4,
                    barPercentage: 0.7,
                    categoryPercentage: 0.65,
                },
                {
                    label: 'Immunization',
                    data: <?php echo json_encode($immunizationTrend, 15, 512) ?>,
                    backgroundColor: '#38bdf8',
                    borderRadius: 4,
                    barPercentage: 0.7,
                    categoryPercentage: 0.65,
                },
                {
                    label: 'Ultrasound',
                    data: <?php echo json_encode($ultrasoundTrend, 15, 512) ?>,
                    backgroundColor: '#22c55e',
                    borderRadius: 4,
                    barPercentage: 0.7,
                    categoryPercentage: 0.65,
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        usePointStyle: true,
                        pointStyle: 'rectRounded',
                        padding: 20,
                        font: { family: 'Inter', size: 12, weight: '500' },
                        color: '#475569',
                    }
                },
                tooltip: tooltipOptions,
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { font: tickFont, color: tickColor, stepSize: 9 },
                    grid: { color: gridColor },
                },
                x: {
                    ticks: { font: tickFont, color: tickColor },
                    grid: { display: false },
                }
            }
        }
    });

    // ── Export Excel (Spreadsheet with proper format & styling) ──
    document.getElementById('exportExcelBtn').addEventListener('click', function() {
        const table = document.getElementById('residentRequestsTable');
        if (!table) {
            alert('No resident request data available to export.');
            return;
        }

        // Generate styled HTML Excel document
        let excelContent = '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40">' +
            '<head>' +
            '<meta http-equiv="content-type" content="text/plain; charset=UTF-8"/>' +
            '<style>' +
            'table { border-collapse: collapse; width: 100%; font-family: Arial, sans-serif; }' +
            'th { background-color: #0c2d48; color: #ffffff; font-weight: bold; padding: 10px; border: 1px solid #cccccc; text-align: left; }' +
            'td { padding: 8px 10px; border: 1px solid #cccccc; color: #333333; }' +
            '</style>' +
            '</head>' +
            '<body>' +
            '<h3 style="font-family: Arial, sans-serif; color: #0c2d48; margin-bottom: 8px;">MEDLINK — Resident Medicine Requests Report</h3>' +
            '<p style="font-family: Arial, sans-serif; font-size: 12px; color: #666; margin-top: 0; margin-bottom: 16px;">Exported on: ' + new Date().toLocaleString() + '</p>' +
            '<table border="1">' +
            '<thead>' +
            '<tr>' +
            '<th>Name of Resident (Requester)</th>' +
            '<th>Actual Patient (Recipient)</th>' +
            '<th>Medicine Requested</th>' +
            '<th style="text-align: center;">Quantity</th>' +
            '<th style="text-align: center;">Med Distribution</th>' +
            '<th style="text-align: center;">Total Requests</th>' +
            '</tr>' +
            '</thead>' +
            '<tbody>';

        const rows = table.querySelectorAll('tbody tr');
        rows.forEach(tr => {
            const cells = tr.querySelectorAll('td');
            if (cells.length >= 6) {
                const requester   = cells[0].innerText.replace(/\s+/g, ' ').trim();
                const patient     = cells[1].innerText.replace(/\s+/g, ' ').trim();
                const medicine    = cells[3].innerText.replace(/\s+/g, ' ').trim();
                const quantity    = cells[4].innerText.trim();
                const distribution= cells[5].innerText.replace(/\s+/g, ' ').trim();
                const totalReqs   = cells[6].innerText.trim();

                excelContent += `
                    <tr>
                        <td>${requester}</td>
                        <td>${patient}</td>
                        <td>${medicine}</td>
                        <td style="text-align: center;">${quantity}</td>
                        <td style="text-align: center;">${distribution}</td>
                        <td style="text-align: center;">${totalReqs}</td>
                    </tr>`;
            }
        });

        excelContent += `
                    </tbody>
                </table>
            </body>
            </html>`;

        const blob = new Blob(['\ufeff', excelContent], { type: 'application/vnd.ms-excel;charset=utf-8' });
        const url = URL.createObjectURL(blob);
        const a = document.createElement('a');
        const filename = 'Resident_Medicine_Requests_' + new Date().toISOString().slice(0, 10) + '.xls';
        a.href = url;
        a.download = filename;
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
        URL.revokeObjectURL(url);
    });
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.nurse', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\techyknowloadeia1\resources\views/dashboards/nurse-reports.blade.php ENDPATH**/ ?>