<?php $__env->startSection('title', 'Medicine Inventory'); ?>
<?php $__env->startSection('page-title', 'Medicine Inventory'); ?>

<?php $__env->startSection('content'); ?>
    
    <div class="bg-white rounded-xl border border-gray-200 p-6 mb-6">
        <div class="flex items-center justify-between mb-5">
            <h2 class="text-base font-bold text-gray-900 flex items-center gap-2">
                <span class="material-icons-outlined text-blue-500" style="font-size:22px;">inventory_2</span>
                Medicine Inventory Management
            </h2>
            <button onclick="openAddModal()" id="btn-add-medicine" class="flex items-center gap-2 px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-lg transition-all shadow-sm hover:shadow-md">
                <span class="material-icons-outlined" style="font-size:18px;">add</span>
                Add Medicine
            </button>
        </div>

        
        <div class="flex flex-wrap items-center gap-4 mb-6">
            <form method="GET" action="<?php echo e(route('nurse.inventory')); ?>" class="flex flex-wrap items-center gap-4 w-full" id="filter-form">
                <div class="relative flex-1 min-w-[250px]">
                    <span class="material-icons-outlined absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" style="font-size:18px;">search</span>
                    <input type="text" name="search" value="<?php echo e(request('search')); ?>" placeholder="Search medicines..."
                        class="w-full pl-10 pr-4 py-2.5 bg-gray-50 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-400 transition-all">
                </div>
                <select name="category" onchange="document.getElementById('filter-form').submit()"
                    class="px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/30 cursor-pointer">
                    <option value="all" <?php echo e(request('category') == 'all' || !request('category') ? 'selected' : ''); ?>>All Categories</option>
                    <?php $__currentLoopData = \App\Http\Controllers\MedicineController::categories(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($cat); ?>" <?php echo e(request('category') == $cat ? 'selected' : ''); ?>><?php echo e($cat); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
                <select name="status" onchange="document.getElementById('filter-form').submit()"
                    class="px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/30 cursor-pointer">
                    <option value="all" <?php echo e(request('status') == 'all' || !request('status') ? 'selected' : ''); ?>>All Status</option>
                    <option value="In Stock" <?php echo e(request('status') == 'In Stock' ? 'selected' : ''); ?>>In Stock</option>
                    <option value="Low Stock" <?php echo e(request('status') == 'Low Stock' ? 'selected' : ''); ?>>Low Stock</option>
                    <option value="Critical" <?php echo e(request('status') == 'Critical' ? 'selected' : ''); ?>>Critical</option>
                </select>
            </form>
        </div>

        
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b-2 border-gray-100">
                        <th class="text-left py-3 px-4 font-bold text-blue-700 text-xs uppercase tracking-wider">Medicine Name</th>
                        <th class="text-left py-3 px-4 font-bold text-blue-700 text-xs uppercase tracking-wider">Category</th>
                        <th class="text-left py-3 px-4 font-bold text-blue-700 text-xs uppercase tracking-wider">Stock</th>
                        <th class="text-left py-3 px-4 font-bold text-blue-700 text-xs uppercase tracking-wider">Unit</th>
                        <th class="text-left py-3 px-4 font-bold text-blue-700 text-xs uppercase tracking-wider">Dosage</th>
                        <th class="text-left py-3 px-4 font-bold text-blue-700 text-xs uppercase tracking-wider">Age Limit</th>
                        <th class="text-left py-3 px-4 font-bold text-blue-700 text-xs uppercase tracking-wider">Reorder Level</th>
                        <th class="text-left py-3 px-4 font-bold text-blue-700 text-xs uppercase tracking-wider">Request Limit</th>
                        <th class="text-left py-3 px-4 font-bold text-blue-700 text-xs uppercase tracking-wider">Expiration Date</th>
                        <th class="text-left py-3 px-4 font-bold text-blue-700 text-xs uppercase tracking-wider">Status</th>
                        <th class="text-left py-3 px-4 font-bold text-blue-700 text-xs uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $medicines; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $medicine): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr class="border-b border-gray-50 hover:bg-blue-50/30 transition-colors">
                            <td class="py-3.5 px-4 font-semibold text-blue-600"><?php echo e($medicine->name); ?></td>
                            <td class="py-3.5 px-4 text-blue-500"><?php echo e($medicine->category); ?></td>
                            <td class="py-3.5 px-4 font-semibold text-gray-800"><?php echo e($medicine->stock); ?></td>
                            <td class="py-3.5 px-4 text-gray-500"><?php echo e($medicine->unit); ?></td>
                            <td class="py-3.5 px-4 text-gray-600 text-xs font-medium">
                                <?php if($medicine->dosage): ?>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded bg-blue-50 text-blue-700 border border-blue-100">
                                        <?php echo e($medicine->dosage); ?>

                                    </span>
                                <?php else: ?>
                                    <span class="text-gray-400">—</span>
                                <?php endif; ?>
                            </td>
                            <td class="py-3.5 px-4 text-xs font-semibold">
                                <?php if($medicine->min_age !== null || $medicine->max_age !== null): ?>
                                    <span class="inline-flex items-center gap-1 bg-purple-50 text-purple-700 border border-purple-200 px-2.5 py-0.5 rounded-full">
                                        <span class="material-icons-outlined" style="font-size:12px;">person</span>
                                        <?php echo e($medicine->age_limit_label); ?>

                                    </span>
                                <?php else: ?>
                                    <span class="text-gray-400 font-normal">All ages</span>
                                <?php endif; ?>
                            </td>
                            <td class="py-3.5 px-4 text-gray-500"><?php echo e($medicine->reorder_level); ?></td>
                            <td class="py-3.5 px-4 font-semibold text-emerald-700">
                                <span class="inline-flex items-center gap-1 bg-emerald-50 border border-emerald-200 px-2.5 py-0.5 rounded-full text-xs">
                                    Max <?php echo e($medicine->request_limit ?? 5); ?>

                                </span>
                            </td>
                            <td class="py-3.5 px-4">
                                <?php if($medicine->expiration_date): ?>
                                    <?php
                                        $expDate = \Carbon\Carbon::parse($medicine->expiration_date);
                                        $isExpired = $expDate->isPast();
                                        $isExpiringSoon = $expDate->isBetween(now(), now()->addMonths(3));
                                    ?>
                                    <?php if($isExpired): ?>
                                        <span class="text-red-600 font-semibold text-xs"><?php echo e($expDate->format('Y-m-d')); ?> (Expired)</span>
                                    <?php elseif($isExpiringSoon): ?>
                                        <span class="text-amber-600 font-semibold text-xs"><?php echo e($expDate->format('Y-m-d')); ?> (Expiring Soon)</span>
                                    <?php else: ?>
                                        <span class="text-gray-500 text-xs"><?php echo e($expDate->format('Y-m-d')); ?></span>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <span class="text-gray-400 text-xs">N/A</span>
                                <?php endif; ?>
                            </td>
                            <td class="py-3.5 px-4">
                                <?php if($medicine->status === 'In Stock'): ?>
                                    <span class="badge-in-stock text-xs font-semibold px-3 py-1 rounded-full">In Stock</span>
                                <?php elseif($medicine->status === 'Low Stock'): ?>
                                    <span class="badge-low-stock text-xs font-semibold px-3 py-1 rounded-full">Low Stock</span>
                                <?php else: ?>
                                    <span class="badge-critical text-xs font-semibold px-3 py-1 rounded-full">Critical</span>
                                <?php endif; ?>
                            </td>
                            <td class="py-3.5 px-4">
                                <div class="flex items-center gap-2">
                                    <button onclick="openEditModal(<?php echo e($medicine->id); ?>, '<?php echo e(addslashes($medicine->name)); ?>', '<?php echo e(addslashes($medicine->category)); ?>', <?php echo e($medicine->stock); ?>, '<?php echo e($medicine->unit); ?>', <?php echo e($medicine->reorder_level); ?>, '<?php echo e($medicine->release_date); ?>', '<?php echo e($medicine->expiration_date); ?>', <?php echo e($medicine->request_limit ?? 5); ?>, '<?php echo e(addslashes($medicine->dosage ?? '')); ?>', <?php echo e($medicine->dosage_times_per_day ?? 'null'); ?>, <?php echo e($medicine->dosage_days ?? 'null'); ?>, <?php echo e($medicine->min_age ?? 'null'); ?>, <?php echo e($medicine->max_age ?? 'null'); ?>)"
                                        class="w-8 h-8 rounded-md bg-blue-50 hover:bg-blue-100 flex items-center justify-center text-blue-600 transition-colors" title="Edit">
                                        <span class="material-icons-outlined" style="font-size:17px;">edit</span>
                                    </button>
                                    <button onclick="openDeleteModal(<?php echo e($medicine->id); ?>, '<?php echo e(addslashes($medicine->name)); ?>')"
                                        class="w-8 h-8 rounded-md bg-red-50 hover:bg-red-100 flex items-center justify-center text-red-500 transition-colors" title="Delete">
                                        <span class="material-icons-outlined" style="font-size:17px;">delete</span>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="10" class="text-center py-12 text-gray-400">
                                <span class="material-icons-outlined mb-2" style="font-size:48px;">medication</span>
                                <p class="font-medium">No medicines found</p>
                                <p class="text-sm mt-1">Add your first medicine to get started.</p>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-5">
        <div class="bg-white rounded-xl border border-gray-200 p-5 border-l-4 border-l-green-500">
            <p class="text-xs font-semibold text-green-600 uppercase tracking-wider mb-1">In Stock</p>
            <p class="text-2xl font-extrabold text-gray-900"><?php echo e($inStockCount); ?></p>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 p-5 border-l-4 border-l-amber-500">
            <p class="text-xs font-semibold text-amber-600 uppercase tracking-wider mb-1">Low Stock</p>
            <p class="text-2xl font-extrabold text-gray-900"><?php echo e($lowStockCount); ?></p>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 p-5 border-l-4 border-l-red-500">
            <p class="text-xs font-semibold text-red-600 uppercase tracking-wider mb-1">Critical Stock</p>
            <p class="text-2xl font-extrabold text-gray-900"><?php echo e($criticalCount); ?></p>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 p-5 border-l-4 border-l-blue-500">
            <p class="text-xs font-semibold text-blue-600 uppercase tracking-wider mb-1">Total Items</p>
            <p class="text-2xl font-extrabold text-gray-900"><?php echo e($totalItems); ?></p>
        </div>
    </div>

    
    <div class="modal-overlay" id="add-modal">
        <div class="modal-content">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-bold text-gray-900">Add New Medicine</h3>
                <button onclick="closeAddModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                    <span class="material-icons-outlined">close</span>
                </button>
            </div>
            <form method="POST" action="<?php echo e(route('medicines.store')); ?>">
                <?php echo csrf_field(); ?>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Medicine Name</label>
                        <input type="text" name="name" required placeholder="e.g. Paracetamol 500mg"
                            class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-400">
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Category</label>
                            <select name="category" required class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/30 cursor-pointer">
                                <option value="">Select...</option>
                                <?php $__currentLoopData = \App\Http\Controllers\MedicineController::categories(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($cat); ?>"><?php echo e($cat); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Unit</label>
                            <select name="unit" required class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/30 cursor-pointer">
                                <option value="">Select...</option>
                                <option value="tablets">Tablets</option>
                                <option value="capsules">Capsules</option>
                                <option value="bottles">Bottles</option>
                                <option value="tubes">Tubes</option>
                                <option value="pieces">Pieces</option>
                            </select>
                        </div>
                    </div>
                    <div>
                        <div class="flex items-center justify-between mb-1">
                            <label class="block text-sm font-semibold text-gray-700">Dosage & Frequency</label>
                            <span class="text-[11px] text-blue-600 font-medium">Controls patient refill cooldown</span>
                        </div>
                        <div class="grid grid-cols-2 gap-3 mb-2">
                            <div>
                                <label class="block text-xs text-gray-500 mb-1">Times per day (e.g. 3)</label>
                                <div class="relative">
                                    <input type="number" name="dosage_times_per_day" id="add-dosage-times" min="1" max="24" placeholder="3"
                                        class="w-full pl-3 pr-14 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/30"
                                        oninput="syncAddDosageText()">
                                    <span class="absolute right-3 top-1/2 -translate-y-1/2 text-xs text-gray-400 pointer-events-none">times/day</span>
                                </div>
                            </div>
                            <div>
                                <label class="block text-xs text-gray-500 mb-1">Days cycle (default 1)</label>
                                <div class="relative">
                                    <input type="number" name="dosage_days" id="add-dosage-days" min="1" max="365" value="1" placeholder="1"
                                        class="w-full pl-3 pr-12 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/30"
                                        oninput="syncAddDosageText()">
                                    <span class="absolute right-3 top-1/2 -translate-y-1/2 text-xs text-gray-400 pointer-events-none">day(s)</span>
                                </div>
                            </div>
                        </div>
                        <label class="block text-xs text-gray-500 mb-1">Dosage Instructions / Label</label>
                        <input type="text" name="dosage" id="add-dosage-text" placeholder="e.g. 1 tablet 3 times a day"
                            class="w-full px-4 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-400">
                    </div>
                    <div>
                        <div class="flex items-center justify-between mb-1">
                            <label class="block text-sm font-semibold text-gray-700">Patient Age Range / Limit</label>
                            <span class="text-[11px] text-purple-600 font-medium">Leave blank for all ages</span>
                        </div>
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-xs text-gray-500 mb-1">Minimum Age (years)</label>
                                <input type="number" name="min_age" id="add-min-age" min="0" max="130" placeholder="e.g. 12"
                                    class="w-full px-4 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/30">
                            </div>
                            <div>
                                <label class="block text-xs text-gray-500 mb-1">Maximum Age (years)</label>
                                <input type="number" name="max_age" id="add-max-age" min="0" max="130" placeholder="e.g. 60"
                                    class="w-full px-4 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/30">
                            </div>
                        </div>
                    </div>
                    <div class="grid grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Stock</label>
                            <input type="number" name="stock" required min="0" placeholder="0"
                                class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/30">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Reorder Level</label>
                            <input type="number" name="reorder_level" required min="1" placeholder="30"
                                class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/30">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Request Limit / Patient</label>
                            <input type="number" name="request_limit" required min="1" placeholder="5" value="5"
                                class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/30"
                                title="Maximum quantity a resident can request at one time">
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Release Date</label>
                            <input type="date" name="release_date"
                                class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/30">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Expiration Date</label>
                            <input type="date" name="expiration_date"
                                class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/30">
                        </div>
                    </div>
                </div>
                <div class="flex justify-end gap-3 mt-6">
                    <button type="button" onclick="closeAddModal()" class="px-4 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-semibold rounded-lg transition-colors">
                        Cancel
                    </button>
                    <button type="submit" class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-lg transition-all shadow-sm hover:shadow-md">
                        Add Medicine
                    </button>
                </div>
            </form>
        </div>
    </div>

    
    <div class="modal-overlay" id="edit-modal">
        <div class="modal-content">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-bold text-gray-900">Edit Medicine</h3>
                <button onclick="closeEditModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                    <span class="material-icons-outlined">close</span>
                </button>
            </div>
            <form method="POST" id="edit-form">
                <?php echo csrf_field(); ?>
                <?php echo method_field('PUT'); ?>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Medicine Name</label>
                        <input type="text" name="name" id="edit-name" required
                            class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-400">
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Category</label>
                            <select name="category" id="edit-category" required class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/30 cursor-pointer">
                                <?php $__currentLoopData = \App\Http\Controllers\MedicineController::categories(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($cat); ?>"><?php echo e($cat); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Unit</label>
                            <select name="unit" id="edit-unit" required class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/30 cursor-pointer">
                                <option value="tablets">Tablets</option>
                                <option value="capsules">Capsules</option>
                                <option value="bottles">Bottles</option>
                                <option value="tubes">Tubes</option>
                                <option value="pieces">Pieces</option>
                            </select>
                        </div>
                    </div>
                    <div>
                        <div class="flex items-center justify-between mb-1">
                            <label class="block text-sm font-semibold text-gray-700">Dosage & Frequency</label>
                            <span class="text-[11px] text-blue-600 font-medium">Controls patient refill cooldown</span>
                        </div>
                        <div class="grid grid-cols-2 gap-3 mb-2">
                            <div>
                                <label class="block text-xs text-gray-500 mb-1">Times per day (e.g. 3)</label>
                                <div class="relative">
                                    <input type="number" name="dosage_times_per_day" id="edit-dosage-times" min="1" max="24" placeholder="3"
                                        class="w-full pl-3 pr-14 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/30"
                                        oninput="syncEditDosageText()">
                                    <span class="absolute right-3 top-1/2 -translate-y-1/2 text-xs text-gray-400 pointer-events-none">times/day</span>
                                </div>
                            </div>
                            <div>
                                <label class="block text-xs text-gray-500 mb-1">Days cycle (default 1)</label>
                                <div class="relative">
                                    <input type="number" name="dosage_days" id="edit-dosage-days" min="1" max="365" placeholder="1"
                                        class="w-full pl-3 pr-12 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/30"
                                        oninput="syncEditDosageText()">
                                    <span class="absolute right-3 top-1/2 -translate-y-1/2 text-xs text-gray-400 pointer-events-none">day(s)</span>
                                </div>
                            </div>
                        </div>
                        <label class="block text-xs text-gray-500 mb-1">Dosage Instructions / Label</label>
                        <input type="text" name="dosage" id="edit-dosage" placeholder="e.g. 1 tablet 3 times a day after meals"
                            class="w-full px-4 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-400">
                    </div>
                    <div>
                        <div class="flex items-center justify-between mb-1">
                            <label class="block text-sm font-semibold text-gray-700">Patient Age Range / Limit</label>
                            <span class="text-[11px] text-purple-600 font-medium">Leave blank for all ages</span>
                        </div>
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-xs text-gray-500 mb-1">Minimum Age (years)</label>
                                <input type="number" name="min_age" id="edit-min-age" min="0" max="130" placeholder="e.g. 12"
                                    class="w-full px-4 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/30">
                            </div>
                            <div>
                                <label class="block text-xs text-gray-500 mb-1">Maximum Age (years)</label>
                                <input type="number" name="max_age" id="edit-max-age" min="0" max="130" placeholder="e.g. 60"
                                    class="w-full px-4 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/30">
                            </div>
                        </div>
                    </div>
                    <div class="grid grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Stock</label>
                            <input type="number" name="stock" id="edit-stock" required min="0"
                                class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/30">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Reorder Level</label>
                            <input type="number" name="reorder_level" id="edit-reorder" required min="1"
                                class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/30">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Request Limit / Patient</label>
                            <input type="number" name="request_limit" id="edit-request-limit" required min="1" placeholder="5"
                                class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/30"
                                title="Maximum quantity a resident can request at one time">
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Release Date</label>
                            <input type="date" name="release_date" id="edit-release-date"
                                class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/30">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Expiration Date</label>
                            <input type="date" name="expiration_date" id="edit-expiration-date"
                                class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/30">
                        </div>
                    </div>
                </div>
                <div class="flex justify-end gap-3 mt-6">
                    <button type="button" onclick="closeEditModal()" class="px-4 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-semibold rounded-lg transition-colors">
                        Cancel
                    </button>
                    <button type="submit" class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-lg transition-all shadow-sm hover:shadow-md">
                        Update Medicine
                    </button>
                </div>
            </form>
        </div>
    </div>

    
    <div class="modal-overlay" id="delete-modal">
        <div class="modal-content">
            <div class="text-center">
                <div class="w-16 h-16 rounded-full bg-red-50 flex items-center justify-center mx-auto mb-4">
                    <span class="material-icons-outlined text-red-500" style="font-size:32px;">delete_forever</span>
                </div>
                <h3 class="text-lg font-bold text-gray-900 mb-2">Delete Medicine</h3>
                <p class="text-sm text-gray-500 mb-6">Are you sure you want to delete <strong id="delete-name" class="text-gray-800"></strong>? This action cannot be undone.</p>
                <div class="flex justify-center gap-3">
                    <button type="button" onclick="closeDeleteModal()" class="px-5 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-semibold rounded-lg transition-colors">
                        Cancel
                    </button>
                    <form method="POST" id="delete-form">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('DELETE'); ?>
                        <button type="submit" class="px-5 py-2.5 bg-red-500 hover:bg-red-600 text-white text-sm font-semibold rounded-lg transition-all shadow-sm hover:shadow-md">
                            Delete
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script>
    // ADD Modal
    function openAddModal() {
        document.getElementById('add-modal').classList.add('active');
    }
    function closeAddModal() {
        document.getElementById('add-modal').classList.remove('active');
    }

    function syncAddDosageText() {
        const times = document.getElementById('add-dosage-times').value;
        const days = document.getElementById('add-dosage-days').value || 1;
        const textInput = document.getElementById('add-dosage-text');
        if (times && times > 0) {
            textInput.value = `${days} day(s) ${times} times a day`;
        }
    }

    function syncEditDosageText() {
        const times = document.getElementById('edit-dosage-times').value;
        const days = document.getElementById('edit-dosage-days').value || 1;
        const textInput = document.getElementById('edit-dosage');
        if (times && times > 0) {
            textInput.value = `${days} day(s) ${times} times a day`;
        }
    }

    // EDIT Modal
    function openEditModal(id, name, category, stock, unit, reorder, release_date, expiration_date, request_limit, dosage, times_per_day, dosage_days, min_age, max_age) {
        document.getElementById('edit-form').action = '/nurse/medicines/' + id;
        document.getElementById('edit-name').value = name;
        document.getElementById('edit-category').value = category;
        document.getElementById('edit-stock').value = stock;
        document.getElementById('edit-unit').value = unit;
        document.getElementById('edit-dosage-times').value = times_per_day || '';
        document.getElementById('edit-dosage-days').value = dosage_days || 1;
        document.getElementById('edit-dosage').value = dosage || '';
        document.getElementById('edit-min-age').value = min_age !== null && min_age !== undefined ? min_age : '';
        document.getElementById('edit-max-age').value = max_age !== null && max_age !== undefined ? max_age : '';
        document.getElementById('edit-reorder').value = reorder;
        document.getElementById('edit-request-limit').value = request_limit || 5;
        document.getElementById('edit-release-date').value = release_date || '';
        document.getElementById('edit-expiration-date').value = expiration_date || '';
        document.getElementById('edit-modal').classList.add('active');
    }
    function closeEditModal() {
        document.getElementById('edit-modal').classList.remove('active');
    }

    // DELETE Modal
    function openDeleteModal(id, name) {
        document.getElementById('delete-form').action = '/nurse/medicines/' + id;
        document.getElementById('delete-name').textContent = name;
        document.getElementById('delete-modal').classList.add('active');
    }
    function closeDeleteModal() {
        document.getElementById('delete-modal').classList.remove('active');
    }

    // Close modals on overlay click
    document.querySelectorAll('.modal-overlay').forEach(overlay => {
        overlay.addEventListener('click', function(e) {
            if (e.target === this) {
                this.classList.remove('active');
            }
        });
    });

    // Close modals on Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            document.querySelectorAll('.modal-overlay.active').forEach(m => m.classList.remove('active'));
        }
    });
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.nurse', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\techyknowloadeia1\resources\views/dashboards/nurse-inventory.blade.php ENDPATH**/ ?>