<?php $__env->startSection('title', 'Consultations & Disease Monitoring'); ?>
<?php $__env->startSection('page-title', 'Consultations & Disease Monitoring'); ?>

<?php $__env->startSection('styles'); ?>
<style>
    .disease-tab {
        padding: 8px 16px;
        border-radius: 10px;
        font-size: 13px;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.2s;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }
    .disease-tab.active {
        background: #0c2d48;
        color: #ffffff;
        box-shadow: 0 4px 12px rgba(12, 45, 72, 0.2);
    }
    .disease-tab:not(.active) {
        background: #f1f5f9;
        color: #475569;
    }
    .disease-tab:not(.active):hover {
        background: #e2e8f0;
        color: #0f172a;
    }
    .badge-dengue { background: #fee2e2; color: #dc2626; border: 1px solid #fca5a5; }
    .badge-tb     { background: #fef3c7; color: #d97706; border: 1px solid #fcd34d; }
    .badge-hiv    { background: #f3e8ff; color: #7e22ce; border: 1px solid #d8b4fe; }
    .badge-measles{ background: #ffedd5; color: #c2410c; border: 1px solid #fdba74; }
    .badge-other  { background: #e0f2fe; color: #0284c7; border: 1px solid #bae6fd; }

    @media print {
        header, aside, .no-print, button, form, .pagination {
            display: none !important;
        }
        body, .main-wrapper {
            background: #fff !important;
            margin: 0 !important;
            padding: 0 !important;
        }
        .print-only {
            display: block !important;
        }
    }
    .print-only {
        display: none;
    }
</style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="space-y-6">

    
    <div class="print-only p-6 border-b-2 border-gray-800 text-center">
        <h1 class="text-2xl font-black text-gray-900 uppercase">Barangay Honorato Health Center</h1>
        <h2 class="text-base font-bold text-gray-700 mt-1">Official Disease Surveillance & Consultation Report</h2>
        <p class="text-xs text-gray-500 mt-0.5">Municipal Health Office Reporting & Epidemic Case Monitoring &bull; Date: <?php echo e(now()->format('F d, Y h:i A')); ?></p>
    </div>

    
    <?php if($dengueAlert || $tbAlert || $hivAlert || $measlesAlert): ?>
        <div class="p-5 rounded-2xl bg-gradient-to-r from-red-600 to-rose-700 text-white shadow-lg flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <div class="flex items-center gap-3.5">
                <div class="w-12 h-12 rounded-xl bg-white/20 flex items-center justify-center shrink-0">
                    <span class="material-icons-outlined text-white" style="font-size:28px;">warning</span>
                </div>
                <div>
                    <h3 class="font-extrabold text-base tracking-wide flex items-center gap-2">
                        <span>EPIDEMIC THRESHOLD REACHED (<?php echo e($thresholdLimit); ?>+ Cases Alert)</span>
                        <span class="px-2 py-0.5 rounded-full text-[10px] font-black bg-white text-red-700 uppercase">Barangay Alert Active</span>
                    </h3>
                    <p class="text-xs text-red-100 mt-0.5">
                        High incidence detected:
                        <?php if($dengueAlert): ?> <strong>Dengue (<?php echo e($dengueCount); ?> cases)</strong> <?php endif; ?>
                        <?php if($tbAlert): ?> <strong>&bull; Tuberculosis (<?php echo e($tbCount); ?> cases)</strong> <?php endif; ?>
                        <?php if($hivAlert): ?> <strong>&bull; HIV (<?php echo e($hivCount); ?> cases)</strong> <?php endif; ?>
                        <?php if($measlesAlert): ?> <strong>&bull; Measles (<?php echo e($measlesCount); ?> cases)</strong> <?php endif; ?>
                        &bull; Automatic public health announcement has been broadcast to all resident dashboards.
                    </p>
                </div>
            </div>
            <button onclick="window.print()" class="px-4 py-2.5 bg-white text-red-700 hover:bg-red-50 text-xs font-bold rounded-xl transition-all shadow shrink-0 flex items-center gap-1.5 cursor-pointer">
                <span class="material-icons-outlined" style="font-size:16px;">print</span>
                <span>Print Health Office Report</span>
            </button>
        </div>
    <?php endif; ?>

    
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-4">
        
        <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Total Consultations</p>
                <p class="text-2xl font-black text-gray-800"><?php echo e($totalCases); ?></p>
            </div>
            <div class="w-11 h-11 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center">
                <span class="material-icons-outlined" style="font-size:22px;">medical_services</span>
            </div>
        </div>

        
        <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm flex items-center justify-between relative overflow-hidden">
            <div>
                <div class="flex items-center gap-1.5 mb-1">
                    <p class="text-xs font-semibold text-red-600 uppercase tracking-wider">Dengue</p>
                    <?php if($dengueAlert): ?> <span class="w-2 h-2 rounded-full bg-red-600 animate-pulse"></span> <?php endif; ?>
                </div>
                <p class="text-2xl font-black text-red-600"><?php echo e($dengueCount); ?></p>
                <p class="text-[10px] text-gray-400 mt-0.5">Threshold: <?php echo e($thresholdLimit); ?> cases</p>
            </div>
            <div class="w-11 h-11 rounded-xl bg-red-50 text-red-600 flex items-center justify-center">
                <span class="material-icons-outlined" style="font-size:22px;">pest_control</span>
            </div>
        </div>

        
        <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm flex items-center justify-between">
            <div>
                <div class="flex items-center gap-1.5 mb-1">
                    <p class="text-xs font-semibold text-amber-600 uppercase tracking-wider">Tuberculosis (TB)</p>
                    <?php if($tbAlert): ?> <span class="w-2 h-2 rounded-full bg-amber-600 animate-pulse"></span> <?php endif; ?>
                </div>
                <p class="text-2xl font-black text-amber-600"><?php echo e($tbCount); ?></p>
                <p class="text-[10px] text-gray-400 mt-0.5">Threshold: <?php echo e($thresholdLimit); ?> cases</p>
            </div>
            <div class="w-11 h-11 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center">
                <span class="material-icons-outlined" style="font-size:22px;">air</span>
            </div>
        </div>

        
        <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm flex items-center justify-between">
            <div>
                <div class="flex items-center gap-1.5 mb-1">
                    <p class="text-xs font-semibold text-purple-600 uppercase tracking-wider">HIV Surveillance</p>
                    <?php if($hivAlert): ?> <span class="w-2 h-2 rounded-full bg-purple-600 animate-pulse"></span> <?php endif; ?>
                </div>
                <p class="text-2xl font-black text-purple-600"><?php echo e($hivCount); ?></p>
                <p class="text-[10px] text-gray-400 mt-0.5">Threshold: <?php echo e($thresholdLimit); ?> cases</p>
            </div>
            <div class="w-11 h-11 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center">
                <span class="material-icons-outlined" style="font-size:22px;">healing</span>
            </div>
        </div>

        
        <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm flex items-center justify-between">
            <div>
                <div class="flex items-center gap-1.5 mb-1">
                    <p class="text-xs font-semibold text-orange-600 uppercase tracking-wider">Measles</p>
                    <?php if($measlesAlert): ?> <span class="w-2 h-2 rounded-full bg-orange-600 animate-pulse"></span> <?php endif; ?>
                </div>
                <p class="text-2xl font-black text-orange-600"><?php echo e($measlesCount); ?></p>
                <p class="text-[10px] text-gray-400 mt-0.5">Threshold: <?php echo e($thresholdLimit); ?> cases</p>
            </div>
            <div class="w-11 h-11 rounded-xl bg-orange-50 text-orange-600 flex items-center justify-center">
                <span class="material-icons-outlined" style="font-size:22px;">coronavirus</span>
            </div>
        </div>
    </div>

    
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        
        <div class="p-6 border-b border-gray-100 flex flex-col md:flex-row md:items-center md:justify-between gap-4 no-print">
            <div class="flex flex-wrap items-center gap-2">
                <a href="<?php echo e(route('nurse.consultations')); ?>" class="disease-tab <?php echo e($diseaseFilter === 'all' ? 'active' : ''); ?>">
                    <span>All Cases</span>
                    <span class="px-1.5 py-0.2 rounded-full text-[10px] bg-white/20"><?php echo e($totalCases); ?></span>
                </a>
                <a href="<?php echo e(route('nurse.consultations', ['disease' => 'Dengue'])); ?>" class="disease-tab <?php echo e($diseaseFilter === 'Dengue' ? 'active' : ''); ?>">
                    <span>Dengue</span>
                    <span class="px-1.5 py-0.2 rounded-full text-[10px] <?php echo e($diseaseFilter === 'Dengue' ? 'bg-white/20' : 'bg-red-100 text-red-700'); ?>"><?php echo e($dengueCount); ?></span>
                </a>
                <a href="<?php echo e(route('nurse.consultations', ['disease' => 'Tuberculosis'])); ?>" class="disease-tab <?php echo e($diseaseFilter === 'Tuberculosis' ? 'active' : ''); ?>">
                    <span>Tuberculosis</span>
                    <span class="px-1.5 py-0.2 rounded-full text-[10px] <?php echo e($diseaseFilter === 'Tuberculosis' ? 'bg-white/20' : 'bg-amber-100 text-amber-700'); ?>"><?php echo e($tbCount); ?></span>
                </a>
                <a href="<?php echo e(route('nurse.consultations', ['disease' => 'HIV'])); ?>" class="disease-tab <?php echo e($diseaseFilter === 'HIV' ? 'active' : ''); ?>">
                    <span>HIV</span>
                    <span class="px-1.5 py-0.2 rounded-full text-[10px] <?php echo e($diseaseFilter === 'HIV' ? 'bg-white/20' : 'bg-purple-100 text-purple-700'); ?>"><?php echo e($hivCount); ?></span>
                </a>
                <a href="<?php echo e(route('nurse.consultations', ['disease' => 'Measles'])); ?>" class="disease-tab <?php echo e($diseaseFilter === 'Measles' ? 'active' : ''); ?>">
                    <span>Measles</span>
                    <span class="px-1.5 py-0.2 rounded-full text-[10px] <?php echo e($diseaseFilter === 'Measles' ? 'bg-white/20' : 'bg-orange-100 text-orange-700'); ?>"><?php echo e($measlesCount); ?></span>
                </a>
            </div>

            <div class="flex items-center gap-2.5">
                <button onclick="window.print()" class="px-3.5 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-bold rounded-xl transition-all flex items-center gap-1.5 cursor-pointer" title="Print official summary report for Health Office">
                    <span class="material-icons-outlined" style="font-size:16px;">print</span>
                    <span>Print Summary</span>
                </button>
                <button onclick="openRecordModal()" class="px-4 py-2.5 bg-[#0c2d48] hover:bg-[#144976] text-white text-xs font-bold rounded-xl transition-all shadow flex items-center gap-1.5 cursor-pointer">
                    <span class="material-icons-outlined" style="font-size:16px;">add_circle</span>
                    <span>Record Consultation</span>
                </button>
            </div>
        </div>

        
        <?php if($consultations->isEmpty()): ?>
            <div class="text-center py-16 text-gray-400">
                <span class="material-icons-outlined mb-2 text-gray-300" style="font-size:56px;">healing</span>
                <p class="font-semibold text-gray-600 text-base">No consultation records found</p>
                <p class="text-xs text-gray-400 mt-1">Click "Record Consultation" above to add patient diagnosis and disease monitoring cases.</p>
            </div>
        <?php else: ?>
            <div class="overflow-x-auto w-full">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-gray-100 bg-gray-50/60 text-xs uppercase tracking-wider text-gray-500 font-semibold">
                            <th class="py-3.5 px-5 text-left">Date</th>
                            <th class="py-3.5 px-5 text-left">Patient Details</th>
                            <th class="py-3.5 px-5 text-left">Disease Case</th>
                            <th class="py-3.5 px-5 text-left">Symptoms & Diagnosis</th>
                            <th class="py-3.5 px-5 text-left">Treatment / Notes</th>
                            <th class="py-3.5 px-5 text-center">Status</th>
                            <th class="py-3.5 px-5 text-right no-print">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <?php $__currentLoopData = $consultations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr class="hover:bg-blue-50/20 transition-colors">
                                <td class="py-4 px-5 text-xs text-gray-600 whitespace-nowrap">
                                    <div class="font-bold text-gray-800"><?php echo e($item->consultation_date ? $item->consultation_date->format('M d, Y') : 'N/A'); ?></div>
                                    <div class="text-[11px] text-gray-400 mt-0.5">By <?php echo e($item->attending_nurse); ?></div>
                                </td>
                                <td class="py-4 px-5">
                                    <div class="font-bold text-gray-900"><?php echo e($item->patient_name); ?></div>
                                    <div class="text-[11px] text-gray-500 mt-0.5">
                                        <?php echo e($item->patient_age ? "{$item->patient_age} yrs old" : 'Age N/A'); ?> &bull; <?php echo e($item->patient_gender ?? 'N/A'); ?>

                                    </div>
                                    <?php if($item->address): ?>
                                        <div class="text-[11px] text-gray-400 flex items-center gap-1 mt-0.5">
                                            <span class="material-icons-outlined" style="font-size:12px;">location_on</span>
                                            <?php echo e($item->address); ?>

                                        </div>
                                    <?php endif; ?>
                                </td>
                                <td class="py-4 px-5 whitespace-nowrap">
                                    <?php if(strtolower($item->disease_case) === 'dengue'): ?>
                                        <span class="badge-dengue text-xs font-extrabold px-3 py-1 rounded-full inline-flex items-center gap-1">
                                            <span class="w-1.5 h-1.5 rounded-full bg-red-600"></span> Dengue Case
                                        </span>
                                    <?php elseif(strtolower($item->disease_case) === 'tuberculosis'): ?>
                                        <span class="badge-tb text-xs font-extrabold px-3 py-1 rounded-full inline-flex items-center gap-1">
                                            <span class="w-1.5 h-1.5 rounded-full bg-amber-600"></span> Tuberculosis (TB)
                                        </span>
                                    <?php elseif(strtolower($item->disease_case) === 'hiv'): ?>
                                        <span class="badge-hiv text-xs font-extrabold px-3 py-1 rounded-full inline-flex items-center gap-1">
                                            <span class="w-1.5 h-1.5 rounded-full bg-purple-600"></span> HIV Surveillance
                                        </span>
                                    <?php elseif(strtolower($item->disease_case) === 'measles'): ?>
                                        <span class="badge-measles text-xs font-extrabold px-3 py-1 rounded-full inline-flex items-center gap-1">
                                            <span class="w-1.5 h-1.5 rounded-full bg-orange-600"></span> Measles
                                        </span>
                                    <?php else: ?>
                                        <span class="badge-other text-xs font-extrabold px-3 py-1 rounded-full inline-flex items-center gap-1">
                                            <?php echo e($item->disease_case); ?>

                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td class="py-4 px-5 max-w-xs">
                                    <div class="text-xs font-semibold text-gray-800"><?php echo e($item->diagnosis ?? 'General Diagnosis'); ?></div>
                                    <?php if($item->symptoms): ?>
                                        <div class="text-[11px] text-gray-500 mt-0.5 line-clamp-2" title="<?php echo e($item->symptoms); ?>">
                                            Symptoms: <?php echo e($item->symptoms); ?>

                                        </div>
                                    <?php endif; ?>
                                </td>
                                <td class="py-4 px-5 max-w-xs text-xs text-gray-600">
                                    <?php echo e($item->treatment_notes ?? 'Standard clinic care.'); ?>

                                </td>
                                <td class="py-4 px-5 text-center whitespace-nowrap">
                                    <span class="px-2.5 py-1 rounded-full text-[11px] font-bold bg-green-50 text-green-700 border border-green-200">
                                        <?php echo e($item->status); ?>

                                    </span>
                                </td>
                                <td class="py-4 px-5 text-right whitespace-nowrap no-print">
                                    <form method="POST" action="<?php echo e(route('nurse.consultations.destroy', $item->id)); ?>" onsubmit="return confirm('Delete this consultation record?');">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('DELETE'); ?>
                                        <button type="submit" class="p-1.5 text-gray-400 hover:text-red-600 rounded-lg hover:bg-red-50 transition-colors" title="Delete record">
                                            <span class="material-icons-outlined" style="font-size:18px;">delete</span>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
            <div class="p-4 border-t border-gray-100 no-print">
                <?php echo e($consultations->links()); ?>

            </div>
        <?php endif; ?>
    </div>
</div>


<div id="recordConsultationModal" class="fixed inset-0 z-50 bg-black/50 backdrop-blur-sm hidden items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-lg overflow-hidden animate-fade-in">
        <div class="p-5 border-b border-gray-100 flex items-center justify-between bg-blue-50/40">
            <div class="flex items-center gap-2 text-[#0c2d48]">
                <span class="material-icons-outlined text-blue-600">add_circle</span>
                <h3 class="font-bold text-base">Record Patient Consultation</h3>
            </div>
            <button type="button" onclick="closeRecordModal()" class="text-gray-400 hover:text-gray-600 p-1">
                <span class="material-icons-outlined">close</span>
            </button>
        </div>

        <form method="POST" action="<?php echo e(route('nurse.consultations.store')); ?>" class="p-6 space-y-4">
            <?php echo csrf_field(); ?>

            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">Select Registered Resident (Optional)</label>
                <select name="user_id" onchange="autofillResident(this)" class="w-full border border-gray-200 rounded-xl px-3.5 py-2.5 text-xs focus:ring-2 focus:ring-blue-500/30">
                    <option value="">-- Non-registered or Walk-in Patient --</option>
                    <?php $__currentLoopData = $residents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $res): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($res->id); ?>" data-name="<?php echo e($res->first_name); ?> <?php echo e($res->last_name); ?>" data-age="<?php echo e($res->age); ?>" data-gender="<?php echo e($res->gender); ?>" data-address="<?php echo e($res->address); ?>">
                            <?php echo e($res->first_name); ?> <?php echo e($res->last_name); ?> (<?php echo e($res->age ? "{$res->age}y" : 'Age N/A'); ?>)
                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div class="col-span-2">
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">Patient Full Name *</label>
                    <input type="text" name="patient_name" id="modal_patient_name" required placeholder="e.g. Juan Dela Cruz" class="w-full border border-gray-200 rounded-xl px-3.5 py-2 text-xs focus:ring-2 focus:ring-blue-500/30">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">Age (Years)</label>
                    <input type="number" name="patient_age" id="modal_patient_age" min="0" max="130" placeholder="e.g. 28" class="w-full border border-gray-200 rounded-xl px-3.5 py-2 text-xs focus:ring-2 focus:ring-blue-500/30">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">Gender</label>
                    <select name="patient_gender" id="modal_patient_gender" class="w-full border border-gray-200 rounded-xl px-3.5 py-2 text-xs focus:ring-2 focus:ring-blue-500/30">
                        <option value="">Select...</option>
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                        <option value="Other">Other</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">Barangay Address / Zone</label>
                <input type="text" name="address" id="modal_patient_address" placeholder="e.g. Purok 4, Barangay Honorato" class="w-full border border-gray-200 rounded-xl px-3.5 py-2 text-xs focus:ring-2 focus:ring-blue-500/30">
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">Disease / Surveillance Case *</label>
                    <select name="disease_case" required class="w-full border border-gray-200 rounded-xl px-3.5 py-2 text-xs font-bold text-blue-700 focus:ring-2 focus:ring-blue-500/30">
                        <option value="Dengue">🦟 Dengue Fever</option>
                        <option value="Tuberculosis">🫁 Tuberculosis (TB)</option>
                        <option value="HIV">🩸 HIV Surveillance</option>
                        <option value="Measles">🔴 Measles</option>
                        <option value="Hypertension">❤️ Hypertension</option>
                        <option value="COVID-19">🦠 COVID-19</option>
                        <option value="General Consultation">🩺 General Consultation</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">Consultation Date *</label>
                    <input type="date" name="consultation_date" required value="<?php echo e(now()->toDateString()); ?>" class="w-full border border-gray-200 rounded-xl px-3.5 py-2 text-xs focus:ring-2 focus:ring-blue-500/30">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">Reported Symptoms</label>
                <textarea name="symptoms" rows="2" placeholder="e.g. High fever, retro-orbital pain, body rashes..." class="w-full border border-gray-200 rounded-xl px-3.5 py-2 text-xs focus:ring-2 focus:ring-blue-500/30 resize-none"></textarea>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">Diagnosis</label>
                    <input type="text" name="diagnosis" placeholder="e.g. Suspected Dengue NS1 Positive" class="w-full border border-gray-200 rounded-xl px-3.5 py-2 text-xs focus:ring-2 focus:ring-blue-500/30">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">Case Status *</label>
                    <select name="status" required class="w-full border border-gray-200 rounded-xl px-3.5 py-2 text-xs focus:ring-2 focus:ring-blue-500/30">
                        <option value="Active">Active Case</option>
                        <option value="Recovered">Recovered</option>
                        <option value="Referred">Referred to District Hospital</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">Treatment / Prescription Notes</label>
                <textarea name="treatment_notes" rows="2" placeholder="e.g. Oral rehydration therapy, paracetamol, bed rest advised." class="w-full border border-gray-200 rounded-xl px-3.5 py-2 text-xs focus:ring-2 focus:ring-blue-500/30 resize-none"></textarea>
            </div>

            <div class="pt-3 border-t border-gray-100 flex items-center justify-end gap-2.5">
                <button type="button" onclick="closeRecordModal()" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-bold rounded-xl transition-all">
                    Cancel
                </button>
                <button type="submit" class="px-5 py-2.5 bg-[#0c2d48] hover:bg-[#144976] text-white text-xs font-bold rounded-xl transition-all shadow">
                    Save Consultation Record
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function openRecordModal() {
        document.getElementById('recordConsultationModal').classList.remove('hidden');
        document.getElementById('recordConsultationModal').classList.add('flex');
    }
    function closeRecordModal() {
        document.getElementById('recordConsultationModal').classList.remove('flex');
        document.getElementById('recordConsultationModal').classList.add('hidden');
    }
    function autofillResident(select) {
        const opt = select.options[select.selectedIndex];
        if (opt.value) {
            document.getElementById('modal_patient_name').value = opt.getAttribute('data-name') || '';
            document.getElementById('modal_patient_age').value = opt.getAttribute('data-age') || '';
            document.getElementById('modal_patient_gender').value = opt.getAttribute('data-gender') || '';
            document.getElementById('modal_patient_address').value = opt.getAttribute('data-address') || '';
        }
    }
</script>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.nurse', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\techyknowloadeia1\resources\views/dashboards/nurse-consultations.blade.php ENDPATH**/ ?>