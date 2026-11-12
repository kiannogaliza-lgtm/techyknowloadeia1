<?php $__env->startSection('title', 'Medicine Requests'); ?>
<?php $__env->startSection('page-title', 'Medicine Requests'); ?>

<?php $__env->startSection('styles'); ?>
<style>
    .mr-tab {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 8px 14px;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        text-decoration: none;
        transition: all 0.2s ease;
        background: transparent;
        color: #64748b;
        white-space: nowrap;
    }
    .mr-tab:hover {
        background: #f1f5f9;
        color: #1e293b;
    }
    .mr-tab.active {
        background: #0c2d48;
        color: #ffffff;
        box-shadow: 0 4px 12px rgba(12, 45, 72, 0.25);
    }
    .mr-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 18px;
        height: 18px;
        border-radius: 9999px;
        font-size: 11px;
        font-weight: 700;
        padding: 0 5px;
    }
    .mr-tab.active .mr-badge {
        background: rgba(255, 255, 255, 0.25);
        color: #ffffff;
    }
    .mr-tab:not(.active) .mr-badge {
        background: #e2e8f0;
        color: #475569;
    }
    .badge-pending {
        background-color: #fef3c7;
        color: #d97706;
        border: 1px solid #fde68a;
    }
    .badge-accepted {
        background-color: #eff6ff;
        color: #2563eb;
        border: 1px solid #bfdbfe;
    }
    .badge-approved {
        background-color: #dcfce7;
        color: #15803d;
        border: 1px solid #bbf7d0;
    }
    .badge-rejected {
        background-color: #fee2e2;
        color: #b91c1c;
        border: 1px solid #fecaca;
    }
</style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="space-y-6">
    
    <div class="grid grid-cols-2 sm:grid-cols-3 xl:grid-cols-5 gap-3.5 sm:gap-4">
        <div class="stat-card p-4 sm:p-5">
            <div class="min-w-0 flex-1 mr-2">
                <p class="text-[11px] sm:text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1 truncate">Pending Requests</p>
                <p class="text-2xl sm:text-3xl font-extrabold text-amber-600"><?php echo e($counts['pending']); ?></p>
            </div>
            <div class="w-10 h-10 sm:w-11 sm:h-11 rounded-full bg-amber-50 flex items-center justify-center shrink-0">
                <span class="material-icons-outlined text-amber-500" style="font-size:22px;">pending_actions</span>
            </div>
        </div>

        <div class="stat-card p-4 sm:p-5">
            <div class="min-w-0 flex-1 mr-2">
                <p class="text-[11px] sm:text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1 truncate" title="Accepted (For Consultation)">Accepted (Consult)</p>
                <p class="text-2xl sm:text-3xl font-extrabold text-blue-600"><?php echo e($counts['accepted']); ?></p>
            </div>
            <div class="w-10 h-10 sm:w-11 sm:h-11 rounded-full bg-blue-50 flex items-center justify-center shrink-0">
                <span class="material-icons-outlined text-blue-500" style="font-size:22px;">medical_services</span>
            </div>
        </div>

        <div class="stat-card p-4 sm:p-5">
            <div class="min-w-0 flex-1 mr-2">
                <p class="text-[11px] sm:text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1 truncate">Approved Requests</p>
                <p class="text-2xl sm:text-3xl font-extrabold text-emerald-600"><?php echo e($counts['approved']); ?></p>
            </div>
            <div class="w-10 h-10 sm:w-11 sm:h-11 rounded-full bg-emerald-50 flex items-center justify-center shrink-0">
                <span class="material-icons-outlined text-emerald-500" style="font-size:22px;">check_circle</span>
            </div>
        </div>

        <div class="stat-card p-4 sm:p-5">
            <div class="min-w-0 flex-1 mr-2">
                <p class="text-[11px] sm:text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1 truncate">Rejected Requests</p>
                <p class="text-2xl sm:text-3xl font-extrabold text-rose-600"><?php echo e($counts['rejected']); ?></p>
            </div>
            <div class="w-10 h-10 sm:w-11 sm:h-11 rounded-full bg-rose-50 flex items-center justify-center shrink-0">
                <span class="material-icons-outlined text-rose-500" style="font-size:22px;">cancel</span>
            </div>
        </div>

        <div class="stat-card p-4 sm:p-5 col-span-2 sm:col-span-1 xl:col-span-1">
            <div class="min-w-0 flex-1 mr-2">
                <p class="text-[11px] sm:text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1 truncate">Total Requests</p>
                <p class="text-2xl sm:text-3xl font-extrabold text-gray-800"><?php echo e($counts['all']); ?></p>
            </div>
            <div class="w-10 h-10 sm:w-11 sm:h-11 rounded-full bg-gray-100 flex items-center justify-center shrink-0">
                <span class="material-icons-outlined text-gray-600" style="font-size:22px;">medication_liquid</span>
            </div>
        </div>
    </div>

    
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
        
        <div class="p-4 sm:p-5 border-b border-gray-100 flex flex-col xl:flex-row xl:items-center xl:justify-between gap-4">
            
            <div class="flex items-center gap-1.5 sm:gap-2 overflow-x-auto pb-1 xl:pb-0 scrollbar-thin">
                <a href="<?php echo e(route('nurse.medicine-requests', ['status' => 'Pending', 'search' => $search])); ?>"
                   class="mr-tab <?php echo e($status === 'Pending' ? 'active' : ''); ?>">
                    <span class="material-icons-outlined" style="font-size:18px;">hourglass_top</span>
                    <span>Pending</span>
                    <span class="mr-badge"><?php echo e($counts['pending']); ?></span>
                </a>

                <a href="<?php echo e(route('nurse.medicine-requests', ['status' => 'Accepted', 'search' => $search])); ?>"
                   class="mr-tab <?php echo e($status === 'Accepted' ? 'active' : ''); ?>">
                    <span class="material-icons-outlined" style="font-size:18px;">assignment_turned_in</span>
                    <span>Accepted</span>
                    <span class="mr-badge"><?php echo e($counts['accepted']); ?></span>
                </a>

                <a href="<?php echo e(route('nurse.medicine-requests', ['status' => 'Approved', 'search' => $search])); ?>"
                   class="mr-tab <?php echo e($status === 'Approved' ? 'active' : ''); ?>">
                    <span class="material-icons-outlined" style="font-size:18px;">task_alt</span>
                    <span>Approved</span>
                    <span class="mr-badge"><?php echo e($counts['approved']); ?></span>
                </a>

                <a href="<?php echo e(route('nurse.medicine-requests', ['status' => 'Rejected', 'search' => $search])); ?>"
                   class="mr-tab <?php echo e($status === 'Rejected' ? 'active' : ''); ?>">
                    <span class="material-icons-outlined" style="font-size:18px;">block</span>
                    <span>Rejected</span>
                    <span class="mr-badge"><?php echo e($counts['rejected']); ?></span>
                </a>

                <a href="<?php echo e(route('nurse.medicine-requests', ['status' => 'all', 'search' => $search])); ?>"
                   class="mr-tab <?php echo e($status === 'all' ? 'active' : ''); ?>">
                    <span class="material-icons-outlined" style="font-size:18px;">list_alt</span>
                    <span>All Requests</span>
                    <span class="mr-badge"><?php echo e($counts['all']); ?></span>
                </a>
            </div>

            
            <form method="GET" action="<?php echo e(route('nurse.medicine-requests')); ?>" class="flex items-center gap-2 shrink-0">
                <input type="hidden" name="status" value="<?php echo e($status); ?>">
                <div class="relative w-full sm:w-60">
                    <span class="material-icons-outlined absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" style="font-size:18px;">search</span>
                    <input type="text" name="search" value="<?php echo e($search); ?>" placeholder="Search patient or medicine..."
                           class="w-full pl-9 pr-8 py-2 bg-gray-50 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-400 transition-all">
                    <?php if(!empty($search)): ?>
                        <a href="<?php echo e(route('nurse.medicine-requests', ['status' => $status])); ?>" class="absolute right-2.5 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                            <span class="material-icons-outlined" style="font-size:16px;">close</span>
                        </a>
                    <?php endif; ?>
                </div>
                <button type="submit" class="px-3.5 py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold rounded-lg transition-colors shadow-sm cursor-pointer shrink-0">
                    Search
                </button>
            </form>
        </div>

        
        <?php if($requests->isEmpty()): ?>
            <div class="text-center py-16 text-gray-400">
                <span class="material-icons-outlined mb-2 text-gray-300" style="font-size:56px;">inventory_2</span>
                <p class="font-semibold text-gray-600 text-base">No medicine requests found</p>
                <p class="text-sm text-gray-400 mt-1">
                    <?php if(!empty($search)): ?>
                        Try searching with different keywords or clear the filter.
                    <?php else: ?>
                        There are currently no <?php echo e(strtolower($status === 'all' ? '' : $status)); ?> medicine requests.
                    <?php endif; ?>
                </p>
            </div>
        <?php else: ?>
            <div class="overflow-x-auto w-full">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-gray-100 bg-gray-50/50">
                            <th class="text-left py-3.5 px-4 font-semibold text-gray-500 text-xs uppercase tracking-wider whitespace-nowrap">Request ID</th>
                            <th class="text-left py-3.5 px-4 font-semibold text-gray-500 text-xs uppercase tracking-wider whitespace-nowrap">Patient</th>
                            <th class="text-left py-3.5 px-3 font-semibold text-gray-500 text-xs uppercase tracking-wider whitespace-nowrap">Type</th>
                            <th class="text-left py-3.5 px-4 font-semibold text-gray-500 text-xs uppercase tracking-wider">Medicine / Item</th>
                            <th class="text-center py-3.5 px-3 font-semibold text-gray-500 text-xs uppercase tracking-wider whitespace-nowrap">Qty</th>
                            <th class="text-left py-3.5 px-3 font-semibold text-gray-500 text-xs uppercase tracking-wider whitespace-nowrap">Fulfillment</th>
                            <th class="text-left py-3.5 px-4 font-semibold text-gray-500 text-xs uppercase tracking-wider whitespace-nowrap">Date</th>
                            <th class="text-left py-3.5 px-4 font-semibold text-gray-500 text-xs uppercase tracking-wider whitespace-nowrap">Status</th>
                            <th class="text-right py-3.5 px-4 font-semibold text-gray-500 text-xs uppercase tracking-wider whitespace-nowrap">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <?php $__currentLoopData = $requests; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $request): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr class="hover:bg-blue-50/30 transition-colors">
                                <td class="py-3.5 px-4 font-semibold text-blue-600 whitespace-nowrap"><?php echo e($request->request_id); ?></td>
                                <td class="py-3.5 px-4">
                                    <div class="font-medium text-gray-800 whitespace-nowrap">
                                        <?php if($request->user): ?>
                                            <?php echo e($request->user->first_name); ?> <?php echo e($request->user->last_name); ?>

                                        <?php else: ?>
                                            <span class="text-gray-400 italic">User not found</span>
                                        <?php endif; ?>
                                    </div>
                                    <?php if($request->patient_type === 'other' && $request->patient_name): ?>
                                        <div class="text-[11px] font-semibold text-purple-700 mt-0.5 whitespace-nowrap flex items-center gap-1">
                                            <span class="material-icons-outlined" style="font-size:12px;">person</span>
                                            For: <?php echo e($request->patient_name); ?> (<?php echo e($request->patient_age); ?>y &bull; <?php echo e($request->patient_relationship); ?>)
                                        </div>
                                    <?php else: ?>
                                        <div class="text-[11px] text-gray-400 mt-0.5 whitespace-nowrap">
                                            For: Self (<?php echo e($request->user ? ($request->user->age ? $request->user->age.' yrs' : '') : ''); ?>)
                                        </div>
                                    <?php endif; ?>
                                    <?php if($request->user && $request->user->phone_number): ?>
                                        <div class="text-xs text-gray-400 whitespace-nowrap"><?php echo e($request->user->phone_number); ?></div>
                                    <?php endif; ?>
                                </td>
                                <td class="py-3.5 px-3 whitespace-nowrap">
                                    <span class="text-blue-600 font-medium text-xs bg-blue-50 px-2 py-0.5 rounded border border-blue-100"><?php echo e($request->type); ?></span>
                                </td>
                                <td class="py-3.5 px-4 min-w-[180px]">
                                    <span class="font-medium text-gray-800"><?php echo e($request->item_service); ?></span>
                                    <?php if($request->medicine): ?>
                                        <div class="text-[11px] text-gray-400 mt-0.5">Current Stock: <?php echo e($request->medicine->stock); ?> <?php echo e($request->medicine->unit); ?></div>
                                    <?php endif; ?>
                                    <?php if($request->notes): ?>
                                        <div class="text-xs text-gray-500 italic mt-0.5" title="<?php echo e($request->notes); ?>">
                                            "<?php echo e(Str::limit($request->notes, 30)); ?>"
                                        </div>
                                    <?php endif; ?>
                                </td>
                                <td class="py-3.5 px-3 text-gray-700 font-semibold text-center whitespace-nowrap">
                                    <div class="inline-flex items-center gap-1">
                                        <span><?php echo e($request->quantity ?? 1); ?></span>
                                        <?php if(in_array($request->status, ['Pending', 'Accepted'])): ?>
                                            <button type="button" 
                                                    onclick="openNurseEditQtyModal(<?php echo e($request->id); ?>, '<?php echo e(addslashes($request->item_service)); ?>', <?php echo e($request->quantity ?? 1); ?>, <?php echo e($request->medicine ? ($request->medicine->stock + ($request->quantity ?? 1)) : 999); ?>, <?php echo e($request->medicine ? ($request->medicine->request_limit ?? 99) : 99); ?>)"
                                                    class="text-gray-400 hover:text-blue-600 transition-colors p-0.5 rounded hover:bg-blue-50 cursor-pointer" 
                                                    title="Edit Quantity">
                                                <span class="material-icons-outlined" style="font-size:15px;">edit</span>
                                            </button>
                                        <?php endif; ?>
                                    </div>
                                </td>
                                <td class="py-3.5 px-3 whitespace-nowrap">
                                    <?php if($request->delivery_method === 'delivery'): ?>
                                        <span class="inline-flex items-center gap-1 text-xs font-semibold text-indigo-700 bg-indigo-50 px-2 py-0.5 rounded">
                                            <span class="material-icons-outlined" style="font-size:13px;">local_shipping</span> Delivery
                                        </span>
                                    <?php else: ?>
                                        <span class="inline-flex items-center gap-1 text-xs font-semibold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded">
                                            <span class="material-icons-outlined" style="font-size:13px;">storefront</span> Pickup
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td class="py-3.5 px-4 text-gray-500 whitespace-nowrap">
                                    <div class="text-xs font-medium"><?php echo e($request->created_at->format('Y-m-d')); ?></div>
                                    <div class="text-[11px] text-gray-400"><?php echo e($request->created_at->format('h:i A')); ?></div>
                                </td>
                                <td class="py-3.5 px-4 whitespace-nowrap">
                                    <?php if($request->status === 'Pending'): ?>
                                        <span class="badge-pending text-xs font-semibold px-2.5 py-1 rounded-full">Pending</span>
                                    <?php elseif($request->status === 'Accepted'): ?>
                                        <span class="badge-accepted text-xs font-semibold px-2.5 py-1 rounded-full">Consultation</span>
                                    <?php elseif($request->status === 'Approved'): ?>
                                        <span class="badge-approved text-xs font-semibold px-2.5 py-1 rounded-full">Approved</span>
                                    <?php elseif($request->status === 'Rejected'): ?>
                                        <span class="badge-rejected text-xs font-semibold px-2.5 py-1 rounded-full">Rejected</span>
                                    <?php else: ?>
                                        <span class="bg-gray-100 text-gray-600 text-xs font-semibold px-2.5 py-1 rounded-full"><?php echo e($request->status); ?></span>
                                    <?php endif; ?>
                                </td>
                                <td class="py-3.5 px-4 whitespace-nowrap text-right">
                                    <?php if($request->status === 'Pending'): ?> 
                                        
                                        <div class="inline-flex items-center justify-end gap-1.5">
                                            <form method="POST" action="<?php echo e(route('requests.accept', $request->id)); ?>" onsubmit="return confirm('Accept request and notify patient to come for Consultation?')">
                                                <?php echo csrf_field(); ?>
                                                <?php echo method_field('PATCH'); ?>
                                                <button type="submit" class="px-2.5 py-1 bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold rounded-md transition-all shadow-sm hover:shadow-md inline-flex items-center gap-1 cursor-pointer" title="Accept and notify patient to visit center for Consultation">
                                                    <span class="material-icons-outlined" style="font-size:13px;">assignment_turned_in</span>
                                                    Accept
                                                </button>
                                            </form>
                                            <form method="POST" action="<?php echo e(route('requests.reject', $request->id)); ?>" onsubmit="return confirm('Are you sure you want to reject this request?')">
                                                <?php echo csrf_field(); ?>
                                                <?php echo method_field('PATCH'); ?>
                                                <button type="submit" class="px-2.5 py-1 bg-red-500 hover:bg-red-600 text-white text-xs font-semibold rounded-md transition-all shadow-sm hover:shadow-md inline-flex items-center gap-1 cursor-pointer">
                                                    <span class="material-icons-outlined" style="font-size:13px;">close</span>
                                                    Reject
                                                </button>
                                            </form>
                                        </div>
                                    <?php elseif($request->status === 'Accepted'): ?>
                                        
                                        <div class="inline-flex items-center justify-end gap-1.5">
                                            <form method="POST" action="<?php echo e(route('requests.approve', $request->id)); ?>" onsubmit="return confirm('Approve request after Consultation for <?php echo e(addslashes($request->item_service)); ?>?')">
                                                <?php echo csrf_field(); ?>
                                                <?php echo method_field('PATCH'); ?>
                                                <button type="submit" class="px-2.5 py-1 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold rounded-md transition-all shadow-sm hover:shadow-md inline-flex items-center gap-1 cursor-pointer" title="Approve medicine for pickup after Consultation">
                                                    <span class="material-icons-outlined" style="font-size:13px;">check</span>
                                                    Approve
                                                </button>
                                            </form>
                                            <form method="POST" action="<?php echo e(route('requests.reject', $request->id)); ?>" onsubmit="return confirm('Reject request after Consultation?')">
                                                <?php echo csrf_field(); ?>
                                                <?php echo method_field('PATCH'); ?>
                                                <button type="submit" class="px-2.5 py-1 bg-red-500 hover:bg-red-600 text-white text-xs font-semibold rounded-md transition-all shadow-sm hover:shadow-md inline-flex items-center gap-1 cursor-pointer" title="Reject after Consultation">
                                                    <span class="material-icons-outlined" style="font-size:13px;">close</span>
                                                    Reject
                                                </button>
                                            </form>
                                        </div>
                                    <?php else: ?>
                                        <span class="text-xs text-gray-400 italic">Processed</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>

            <?php if($requests->hasPages()): ?>
                <div class="p-4 border-t border-gray-100">
                    <?php echo e($requests->links()); ?>

                </div>  
                
            <?php endif; ?>
        <?php endif; ?>
    </div>
</div>


<div id="nurseEditQtyModal" class="fixed inset-0 z-50 hidden bg-black/50 backdrop-blur-xs flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-sm overflow-hidden animate-in fade-in zoom-in-95 duration-150">
        <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <span class="material-icons-outlined text-amber-500" style="font-size:20px;">edit_note</span>
                <h3 class="font-bold text-gray-800 text-sm">Edit Requested Quantity</h3>
            </div>
            <button type="button" onclick="closeNurseEditQtyModal()" class="text-gray-400 hover:text-gray-600 cursor-pointer">
                <span class="material-icons-outlined" style="font-size:18px;">close</span>
            </button>
        </div>
        <form id="nurseEditQtyForm" method="POST" action="" class="p-5 space-y-4">
            <?php echo csrf_field(); ?>
            <?php echo method_field('PATCH'); ?>
            <div>
                <p class="text-xs text-gray-500 mb-1">Medicine / Item</p>
                <p id="modalMedicineName" class="font-bold text-gray-800 text-sm"></p>
                <p id="modalStockInfo" class="text-[11px] text-gray-400 mt-0.5"></p>
            </div>
            <div>
                <label for="modalQtyInput" class="block text-xs font-semibold text-gray-700 mb-1">Quantity</label>
                <input type="number" 
                       id="modalQtyInput" 
                       name="quantity" 
                       min="1" 
                       required
                       oninput="handleQtyChange(this.value)"
                       class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500">
            </div>

            
            <div id="modalReasonContainer" class="hidden space-y-1.5 p-3 rounded-xl bg-amber-50 border border-amber-200">
                <div class="flex items-center gap-1.5 text-amber-800 text-xs font-semibold">
                    <span class="material-icons-outlined" style="font-size:16px;">report_problem</span>
                    <span>Reason for Medicine Return</span>
                </div>
                <p id="modalReasonStockNotice" class="text-[11px] text-amber-700 leading-snug">
                    Decreased medicine will <strong>not</strong> go back to stock; it will be recorded on the Returned Medicines log.
                </p>
                <select name="reason" id="modalReasonSelect" onchange="updateReasonStockNotice(this.value)" class="w-full mt-1 px-2.5 py-1.5 text-xs bg-white border border-amber-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-400 text-gray-800">
                    <option value="Broken / Damaged upon claiming">Broken / Damaged upon claiming</option>
                    <option value="Damaged packaging / seal broken">Damaged packaging / seal broken</option>
                    <option value="Expired / Defective item">Expired / Defective item</option>
                    <option value="Dosage adjusted by nurse during consultation">Dosage adjusted during consultation</option>
                    <option value="Patient declined / partially returned">Patient declined / partially returned</option>
                </select>
            </div>

            <div class="flex items-center justify-end gap-2 pt-2 border-t border-gray-100">
                <button type="button" onclick="closeNurseEditQtyModal()" class="px-3.5 py-1.5 text-xs font-semibold text-gray-600 hover:bg-gray-100 rounded-lg transition-colors cursor-pointer">
                    Cancel
                </button>
                <button type="submit" class="px-4 py-1.5 text-xs font-semibold bg-blue-600 hover:bg-blue-700 text-white rounded-lg shadow-sm transition-all cursor-pointer">
                    Save Quantity
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    let currentInitialQty = 1;

    function openNurseEditQtyModal(requestId, medicineName, currentQty, maxStock, limit) {
        const modal = document.getElementById('nurseEditQtyModal');
        const form = document.getElementById('nurseEditQtyForm');
        const nameEl = document.getElementById('modalMedicineName');
        const stockEl = document.getElementById('modalStockInfo');
        const input = document.getElementById('modalQtyInput');

        currentInitialQty = parseInt(currentQty, 10);
        form.action = `/nurse/requests/${requestId}/quantity`;
        nameEl.textContent = medicineName;
        stockEl.textContent = `Limit per request: ${limit}`;
        input.value = currentQty;
        input.max = Math.min(maxStock, limit);
        
        handleQtyChange(currentQty);

        modal.classList.remove('hidden');
        input.focus();
    }

    function handleQtyChange(val) {
        const parsed = parseInt(val, 10);
        const reasonBox = document.getElementById('modalReasonContainer');
        const reasonSelect = document.getElementById('modalReasonSelect');
        if (!isNaN(parsed) && parsed < currentInitialQty) {
            reasonBox.classList.remove('hidden');
            if (reasonSelect) {
                updateReasonStockNotice(reasonSelect.value);
            }
        } else {
            reasonBox.classList.add('hidden');
        }
    }

    function updateReasonStockNotice(selectedReason) {
        const noticeEl = document.getElementById('modalReasonStockNotice');
        if (!noticeEl) return;

        const restockedReasons = [
            'Dosage adjusted by nurse during consultation',
            'Dosage adjusted during consultation',
            'Patient declined / partially returned'
        ];

        if (restockedReasons.includes(selectedReason)) {
            noticeEl.innerHTML = 'Decreased medicine <strong class="text-emerald-700">will be returned to stock</strong> and recorded on the Returned Medicines log.';
        } else {
            noticeEl.innerHTML = 'Decreased medicine will <strong>not</strong> go back to stock; it will be recorded on the Returned Medicines log.';
        }
    }

    function closeNurseEditQtyModal() {
        const modal = document.getElementById('nurseEditQtyModal');
        modal.classList.add('hidden');
    }

    // Close on escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeNurseEditQtyModal();
        }
    });
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.nurse', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\techyknowloadeia1\resources\views/dashboards/nurse-medicine-requests.blade.php ENDPATH**/ ?>