<?php $__env->startSection('title', 'Child Vaccinations (Baby Book)'); ?>
<?php $__env->startSection('page-title', 'Child Vaccinations (Baby Book)'); ?>

<?php $__env->startSection('styles'); ?>
<style>
    .dose-tab {
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
    .dose-tab.active {
        background: #0c2d48;
        color: #ffffff;
        box-shadow: 0 4px 12px rgba(12, 45, 72, 0.2);
    }
    .dose-tab:not(.active) {
        background: #f1f5f9;
        color: #475569;
    }
    .dose-tab:not(.active):hover {
        background: #e2e8f0;
        color: #0f172a;
    }

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
        <h2 class="text-base font-bold text-gray-700 mt-1">Child Immunization Masterlist & Dose Tracking Report</h2>
        <p class="text-xs text-gray-500 mt-0.5">Municipal Health Office National Immunization Program &bull; Date: <?php echo e(now()->format('F d, Y')); ?></p>
    </div>

    
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
        <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Total Vaccinations</p>
                <p class="text-2xl font-black text-gray-800"><?php echo e($totalVaccinations); ?></p>
            </div>
            <div class="w-11 h-11 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center">
                <span class="material-icons-outlined" style="font-size:22px;">vaccines</span>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-emerald-600 uppercase tracking-wider mb-1">1st Dose Given</p>
                <p class="text-2xl font-black text-emerald-600"><?php echo e($firstDoseCount); ?></p>
            </div>
            <div class="w-11 h-11 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                <span class="material-icons-outlined" style="font-size:22px;">looks_one</span>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-purple-600 uppercase tracking-wider mb-1">2nd Dose Given</p>
                <p class="text-2xl font-black text-purple-600"><?php echo e($secondDoseCount); ?></p>
            </div>
            <div class="w-11 h-11 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center">
                <span class="material-icons-outlined" style="font-size:22px;">looks_two</span>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-indigo-600 uppercase tracking-wider mb-1">3rd Dose & Boosters</p>
                <p class="text-2xl font-black text-indigo-600"><?php echo e($succeedingCount); ?></p>
            </div>
            <div class="w-11 h-11 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center">
                <span class="material-icons-outlined" style="font-size:22px;">verified</span>
            </div>
        </div>
    </div>

    
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        
        <div class="p-6 border-b border-gray-100 flex flex-col md:flex-row md:items-center md:justify-between gap-4 no-print">
            <div class="flex flex-wrap items-center gap-2">
                <a href="<?php echo e(route('nurse.vaccinations')); ?>" class="dose-tab <?php echo e($doseFilter === 'all' ? 'active' : ''); ?>">
                    <span>All Doses</span>
                    <span class="px-1.5 py-0.2 rounded-full text-[10px] bg-white/20"><?php echo e($totalVaccinations); ?></span>
                </a>
                <a href="<?php echo e(route('nurse.vaccinations', ['dose' => '1st Dose'])); ?>" class="dose-tab <?php echo e($doseFilter === '1st Dose' ? 'active' : ''); ?>">
                    <span>1st Dose</span>
                    <span class="px-1.5 py-0.2 rounded-full text-[10px] <?php echo e($doseFilter === '1st Dose' ? 'bg-white/20' : 'bg-emerald-100 text-emerald-800'); ?>"><?php echo e($firstDoseCount); ?></span>
                </a>
                <a href="<?php echo e(route('nurse.vaccinations', ['dose' => '2nd Dose'])); ?>" class="dose-tab <?php echo e($doseFilter === '2nd Dose' ? 'active' : ''); ?>">
                    <span>2nd Dose</span>
                    <span class="px-1.5 py-0.2 rounded-full text-[10px] <?php echo e($doseFilter === '2nd Dose' ? 'bg-white/20' : 'bg-purple-100 text-purple-800'); ?>"><?php echo e($secondDoseCount); ?></span>
                </a>
                <a href="<?php echo e(route('nurse.vaccinations', ['dose' => '3rd Dose'])); ?>" class="dose-tab <?php echo e($doseFilter === '3rd Dose' ? 'active' : ''); ?>">
                    <span>3rd Dose & Succeeding</span>
                </a>
            </div>

            <div class="flex items-center gap-2.5">
                <button onclick="window.print()" class="px-3.5 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-bold rounded-xl transition-all flex items-center gap-1.5 cursor-pointer">
                    <span class="material-icons-outlined" style="font-size:16px;">print</span>
                    <span>Print Masterlist</span>
                </button>
                <button onclick="openVaccineModal()" class="px-4 py-2.5 bg-[#0c2d48] hover:bg-[#144976] text-white text-xs font-bold rounded-xl transition-all shadow flex items-center gap-1.5 cursor-pointer">
                    <span class="material-icons-outlined" style="font-size:16px;">add_circle</span>
                    <span>Record Vaccine Dose</span>
                </button>
            </div>
        </div>

        
        <?php if($vaccinations->isEmpty()): ?>
            <div class="text-center py-16 text-gray-400">
                <span class="material-icons-outlined mb-2 text-gray-300" style="font-size:56px;">child_care</span>
                <p class="font-semibold text-gray-600 text-base">No child vaccination records found</p>
                <p class="text-xs text-gray-400 mt-1">Click "Record Vaccine Dose" above to add immunization records for infants, toddlers, and children.</p>
            </div>
        <?php else: ?>
            <div class="overflow-x-auto w-full">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-gray-100 bg-gray-50/60 text-xs uppercase tracking-wider text-gray-500 font-semibold">
                            <th class="py-3.5 px-5 text-left">Child Patient</th>
                            <th class="py-3.5 px-5 text-left">Age / Classification</th>
                            <th class="py-3.5 px-5 text-left">Parent / Resident Account</th>
                            <th class="py-3.5 px-5 text-left">Vaccine Administered</th>
                            <th class="py-3.5 px-5 text-center">Dose</th>
                            <th class="py-3.5 px-5 text-left">Date Given</th>
                            <th class="py-3.5 px-5 text-left">Provider</th>
                            <th class="py-3.5 px-5 text-center">Status</th>
                            <th class="py-3.5 px-5 text-right no-print">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <?php $__currentLoopData = $vaccinations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $v): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr class="hover:bg-blue-50/20 transition-colors">
                                <td class="py-4 px-5 font-bold text-gray-900">
                                    <div class="flex items-center gap-2">
                                        <div class="w-7 h-7 rounded-full bg-blue-100 text-blue-700 flex items-center justify-center text-xs font-bold shrink-0">
                                            <?php echo e(strtoupper(substr($v->child_name, 0, 1))); ?>

                                        </div>
                                        <span><?php echo e($v->child_name); ?></span>
                                    </div>
                                    <div class="text-[11px] text-gray-400 font-normal mt-0.5">
                                        <?php echo e($v->child_gender ?? 'Gender unspecified'); ?> &bull; DOB: <?php echo e($v->child_dob ? $v->child_dob->format('M d, Y') : 'N/A'); ?>

                                    </div>
                                </td>
                                <td class="py-4 px-5">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-purple-50 text-purple-700 border border-purple-200">
                                        <?php echo e($v->age_category); ?>

                                    </span>
                                </td>
                                <td class="py-4 px-5">
                                    <?php if($v->parent): ?>
                                        <div class="font-semibold text-blue-700 text-xs"><?php echo e($v->parent->first_name); ?> <?php echo e($v->parent->last_name); ?></div>
                                        <div class="text-[11px] text-gray-400"><?php echo e($v->parent->phone_number ?? 'Resident'); ?></div>
                                    <?php else: ?>
                                        <span class="text-xs text-gray-400 italic">Walk-in Child</span>
                                    <?php endif; ?>
                                </td>
                                <td class="py-4 px-5 font-bold text-gray-800">
                                    <?php echo e($v->vaccine_name); ?>

                                    <?php if($v->notes): ?>
                                        <div class="text-[11px] text-gray-400 font-normal italic mt-0.5">"<?php echo e($v->notes); ?>"</div>
                                    <?php endif; ?>
                                </td>
                                <td class="py-4 px-5 text-center whitespace-nowrap">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-extrabold bg-blue-50 text-blue-800 border border-blue-200">
                                        <?php echo e($v->dose_number); ?>

                                    </span>
                                </td>
                                <td class="py-4 px-5 text-xs text-gray-600 whitespace-nowrap">
                                    <?php echo e($v->date_administered ? $v->date_administered->format('M d, Y') : 'Scheduled'); ?>

                                </td>
                                <td class="py-4 px-5 text-xs text-gray-600">
                                    <?php echo e($v->administered_by ?? 'Health Center Staff'); ?>

                                </td>
                                <td class="py-4 px-5 text-center whitespace-nowrap">
                                    <span class="px-2.5 py-1 rounded-full text-[11px] font-bold bg-green-50 text-green-700 border border-green-200">
                                        <?php echo e($v->status); ?>

                                    </span>
                                </td>
                                <td class="py-4 px-5 text-right whitespace-nowrap no-print">
                                    <form method="POST" action="<?php echo e(route('nurse.vaccinations.destroy', $v->id)); ?>" onsubmit="return confirm('Delete this vaccination record?');">
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
                <?php echo e($vaccinations->links()); ?>

            </div>
        <?php endif; ?>
    </div>
</div>


<div id="recordVaccineModal" class="fixed inset-0 z-50 bg-black/50 backdrop-blur-sm hidden items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-lg overflow-hidden animate-fade-in">
        <div class="p-5 border-b border-gray-100 flex items-center justify-between bg-blue-50/40">
            <div class="flex items-center gap-2 text-[#0c2d48]">
                <span class="material-icons-outlined text-blue-600">child_care</span>
                <h3 class="font-bold text-base">Record Child Vaccine Dose</h3>
            </div>
            <button type="button" onclick="closeVaccineModal()" class="text-gray-400 hover:text-gray-600 p-1">
                <span class="material-icons-outlined">close</span>
            </button>
        </div>

        <form method="POST" action="<?php echo e(route('nurse.vaccinations.store')); ?>" class="p-6 space-y-4">
            <?php echo csrf_field(); ?>

            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">Parent / Guardian Resident Account</label>
                <select name="user_id" class="w-full border border-gray-200 rounded-xl px-3.5 py-2.5 text-xs focus:ring-2 focus:ring-blue-500/30">
                    <option value="">-- Walk-in without Resident Account --</option>
                    <?php $__currentLoopData = $parents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($p->id); ?>">
                            <?php echo e($p->first_name); ?> <?php echo e($p->last_name); ?> (<?php echo e($p->phone_number ?? 'No contact'); ?>)
                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
                <p class="text-[11px] text-gray-400 mt-0.5">Linking the parent will automatically reflect this vaccine record in the resident's portal account!</p>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div class="col-span-2">
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">Child Patient Full Name *</label>
                    <input type="text" name="child_name" required placeholder="e.g. Baby Lucas Santos" class="w-full border border-gray-200 rounded-xl px-3.5 py-2 text-xs focus:ring-2 focus:ring-blue-500/30">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">Child Date of Birth</label>
                    <input type="date" name="child_dob" class="w-full border border-gray-200 rounded-xl px-3.5 py-2 text-xs focus:ring-2 focus:ring-blue-500/30">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">Gender</label>
                    <select name="child_gender" class="w-full border border-gray-200 rounded-xl px-3.5 py-2 text-xs focus:ring-2 focus:ring-blue-500/30">
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">Vaccine Name *</label>
                    <select name="vaccine_name" required class="w-full border border-gray-200 rounded-xl px-3.5 py-2 text-xs font-bold text-blue-700 focus:ring-2 focus:ring-blue-500/30">
                        <option value="BCG (Tuberculosis)">BCG (At Birth)</option>
                        <option value="Hepatitis B">Hepatitis B (At Birth)</option>
                        <option value="Pentavalent (DTP-HepB-Hib)">Pentavalent (DTP-HepB-Hib)</option>
                        <option value="Oral Polio Vaccine (OPV)">Oral Polio Vaccine (OPV)</option>
                        <option value="Inactivated Polio (IPV)">Inactivated Polio (IPV)</option>
                        <option value="Pneumococcal Conjugate (PCV)">Pneumococcal Conjugate (PCV)</option>
                        <option value="Measles, Mumps, Rubella (MMR)">Measles, Mumps, Rubella (MMR)</option>
                        <option value="Flu Vaccine">Flu Vaccine (Pediatric)</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">Dose Applicable *</label>
                    <select name="dose_number" required class="w-full border border-gray-200 rounded-xl px-3.5 py-2 text-xs font-bold text-purple-700 focus:ring-2 focus:ring-blue-500/30">
                        <option value="1st Dose">1st Dose</option>
                        <option value="2nd Dose">2nd Dose</option>
                        <option value="3rd Dose">3rd Dose</option>
                        <option value="Booster 1">Booster 1</option>
                        <option value="Booster 2">Booster 2</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">Date Administered *</label>
                    <input type="date" name="date_administered" required value="<?php echo e(now()->toDateString()); ?>" class="w-full border border-gray-200 rounded-xl px-3.5 py-2 text-xs focus:ring-2 focus:ring-blue-500/30">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">Dose Status *</label>
                    <select name="status" required class="w-full border border-gray-200 rounded-xl px-3.5 py-2 text-xs focus:ring-2 focus:ring-blue-500/30">
                        <option value="Completed">Completed</option>
                        <option value="Scheduled">Scheduled</option>
                        <option value="Missed">Missed</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">Immunization / Reaction Notes</label>
                <textarea name="notes" rows="2" placeholder="e.g. Tolerated well, right thigh injection, baby book card signed." class="w-full border border-gray-200 rounded-xl px-3.5 py-2 text-xs focus:ring-2 focus:ring-blue-500/30 resize-none"></textarea>
            </div>

            <div class="pt-3 border-t border-gray-100 flex items-center justify-end gap-2.5">
                <button type="button" onclick="closeVaccineModal()" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-bold rounded-xl transition-all">
                    Cancel
                </button>
                <button type="submit" class="px-5 py-2.5 bg-[#0c2d48] hover:bg-[#144976] text-white text-xs font-bold rounded-xl transition-all shadow">
                    Record Vaccine Dose
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function openVaccineModal() {
        document.getElementById('recordVaccineModal').classList.remove('hidden');
        document.getElementById('recordVaccineModal').classList.add('flex');
    }
    function closeVaccineModal() {
        document.getElementById('recordVaccineModal').classList.remove('flex');
        document.getElementById('recordVaccineModal').classList.add('hidden');
    }
</script>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.nurse', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\techyknowloadeia1\resources\views/dashboards/nurse-vaccinations.blade.php ENDPATH**/ ?>