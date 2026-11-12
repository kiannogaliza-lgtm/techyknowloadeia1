<?php $__env->startSection('title', 'Patient Verification'); ?>
<?php $__env->startSection('page-title', 'Patient Verification'); ?>

<?php $__env->startSection('styles'); ?>
<style>
    /* ── Tabs ─────────────────────────────────────────────────── */
    .pv-tab {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 8px 20px;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        border: none;
        transition: all 0.2s ease;
        background: transparent;
        color: #64748b;
    }
    .pv-tab.active {
        background: #0c2d48;
        color: #ffffff;
        box-shadow: 0 4px 12px rgba(12,45,72,0.3);
    }
    .pv-tab:not(.active):hover {
        background: #f1f5f9;
        color: #334155;
    }
    .tab-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 22px;
        height: 22px;
        border-radius: 11px;
        font-size: 11px;
        font-weight: 700;
        padding: 0 6px;
        background: rgba(255,255,255,0.2);
        color: inherit;
    }
    .pv-tab:not(.active) .tab-badge {
        background: #e2e8f0;
        color: #475569;
    }
    .pv-tab.active.pending-tab .tab-badge { background: #f59e0b; color: #fff; }
    .pv-tab.active.verified-tab .tab-badge { background: #059669; color: #fff; }

    /* ── Patient Card ─────────────────────────────────────────── */
    .patient-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 14px;
        padding: 22px 24px;
        display: flex;
        align-items: flex-start;
        gap: 18px;
        transition: box-shadow 0.2s ease, border-color 0.2s ease;
    }
    .patient-card:hover {
        box-shadow: 0 4px 20px rgba(0,0,0,0.07);
        border-color: #cbd5e1;
    }
    .patient-avatar {
        width: 52px;
        height: 52px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
        font-weight: 800;
        color: #ffffff;
        flex-shrink: 0;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }
    .patient-info { flex: 1; min-width: 0; }
    .patient-name {
        font-size: 16px;
        font-weight: 700;
        color: #0f172a;
        margin-bottom: 2px;
    }
    .patient-id {
        font-size: 12px;
        color: #64748b;
        font-weight: 500;
        margin-bottom: 12px;
    }
    .patient-meta-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 12px 24px;
        margin-bottom: 10px;
    }
    .meta-item label {
        display: block;
        font-size: 10px;
        font-weight: 700;
        color: #94a3b8;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        margin-bottom: 2px;
    }
    .meta-item span {
        font-size: 13px;
        color: #334155;
        font-weight: 500;
    }
    .meta-item span.link-style {
        color: #2563eb;
        text-decoration: underline;
        text-decoration-style: dotted;
        text-underline-offset: 2px;
    }

    /* Status badges */
    .badge-pending-verification {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 3px 10px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 700;
        background: #fffbeb;
        color: #b45309;
        border: 1px solid #fde68a;
    }
    .badge-verified {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 3px 10px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 700;
        background: #ecfdf5;
        color: #059669;
        border: 1px solid #a7f3d0;
    }

    /* Action buttons */
    .card-actions {
        display: flex;
        flex-direction: column;
        gap: 8px;
        flex-shrink: 0;
    }
    .btn-view-details {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 7px 14px;
        border-radius: 7px;
        font-size: 12px;
        font-weight: 600;
        background: #2563eb;
        color: #ffffff;
        border: none;
        cursor: pointer;
        transition: all 0.2s;
        white-space: nowrap;
    }
    .btn-view-details:hover { background: #1d4ed8; box-shadow: 0 4px 12px rgba(37,99,235,0.3); }
    .btn-verify {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 7px 14px;
        border-radius: 7px;
        font-size: 12px;
        font-weight: 600;
        background: #059669;
        color: #ffffff;
        border: none;
        cursor: pointer;
        transition: all 0.2s;
        white-space: nowrap;
    }
    .btn-verify:hover { background: #047857; box-shadow: 0 4px 12px rgba(5,150,105,0.3); }
    .btn-reject {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 7px 14px;
        border-radius: 7px;
        font-size: 12px;
        font-weight: 600;
        background: #dc2626;
        color: #ffffff;
        border: none;
        cursor: pointer;
        transition: all 0.2s;
        white-space: nowrap;
    }
    .btn-reject:hover { background: #b91c1c; box-shadow: 0 4px 12px rgba(220,38,38,0.3); }

    /* ── Detail Modal ─────────────────────────────────────────── */
    .pv-modal-overlay {
        position: fixed;
        inset: 0;
        background: rgba(0,0,0,0.55);
        backdrop-filter: blur(4px);
        z-index: 60;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 24px;
        opacity: 0;
        pointer-events: none;
        transition: opacity 0.3s ease;
    }
    .pv-modal-overlay.active {
        opacity: 1;
        pointer-events: auto;
    }
    .pv-modal {
        background: #ffffff;
        border-radius: 18px;
        width: 100%;
        max-width: 560px;
        max-height: 90vh;
        overflow-y: auto;
        box-shadow: 0 30px 80px rgba(0,0,0,0.2);
        transform: translateY(20px);
        transition: transform 0.3s ease;
    }
    .pv-modal-overlay.active .pv-modal { transform: translateY(0); }
    .pv-modal-header {
        padding: 22px 26px 18px;
        border-bottom: 1px solid #f1f5f9;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .pv-modal-body { padding: 24px 26px; }
    .pv-modal-footer {
        padding: 18px 26px;
        border-top: 1px solid #f1f5f9;
        display: flex;
        gap: 10px;
        justify-content: flex-end;
    }
    .pv-modal-footer.hidden {
        display: none !important;
    }
    .id-photo-box {
        background: #f8fafc;
        border: 1px dashed #cbd5e1;
        border-radius: 12px;
        overflow: hidden;
        min-height: 160px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .id-photo-box img {
        width: 100%;
        max-height: 280px;
        object-fit: contain;
        cursor: pointer;
    }

    /* Lightbox */
    .lightbox {
        position: fixed;
        inset: 0;
        background: rgba(0,0,0,0.9);
        z-index: 100;
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0;
        pointer-events: none;
        transition: opacity 0.3s;
    }
    .lightbox.active { opacity: 1; pointer-events: auto; }
    .lightbox img { max-width: 92vw; max-height: 90vh; object-fit: contain; border-radius: 8px; }

    /* Empty state */
    .empty-state {
        text-align: center;
        padding: 60px 24px;
        color: #94a3b8;
    }
    .empty-state .material-icons-outlined { font-size: 52px; margin-bottom: 12px; opacity: 0.5; }
</style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
        
        <div class="flex items-center gap-2 bg-white border border-gray-200 rounded-xl p-1.5 shadow-sm">
            <button id="tab-pending" class="pv-tab pending-tab active" onclick="switchTab('pending')">
                Pending
                <span class="tab-badge"><?php echo e($pendingCount); ?></span>
            </button>
            <button id="tab-verified" class="pv-tab verified-tab" onclick="switchTab('verified')">
                Verified
                <span class="tab-badge"><?php echo e($verifiedCount); ?></span>
            </button>
        </div>

        
        <form method="GET" action="<?php echo e(route('nurse.patient-verification')); ?>" class="relative">
            <span class="material-icons-outlined absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" style="font-size:18px;">search</span>
            <input type="text" name="search" id="searchInput" placeholder="Search patients..."
                   value="<?php echo e($search); ?>"
                   class="pl-10 pr-10 py-2.5 bg-white border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-400 w-64 transition-all shadow-sm">
            <?php if($search): ?>
                <a href="<?php echo e(route('nurse.patient-verification')); ?>" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                    <span class="material-icons-outlined" style="font-size:16px;">close</span>
                </a>
            <?php endif; ?>
        </form>
    </div>

    
    <div id="content-pending">
        <div class="bg-white border border-gray-200 rounded-xl p-6">
            <?php if($pendingResidents->isEmpty()): ?>
                <div class="empty-state">
                    <span class="material-icons-outlined block">how_to_reg</span>
                    <p class="font-semibold text-gray-500">No pending patients</p>
                    <p class="text-sm mt-1">All residents have been verified.</p>
                </div>
            <?php else: ?>
                <div class="space-y-4">
                    <?php $__currentLoopData = $pendingResidents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $resident): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php
                            $colors = ['#2563eb','#7c3aed','#0891b2','#059669','#dc2626','#d97706'];
                            $color  = $colors[$index % count($colors)];
                            $initials = strtoupper(substr($resident->first_name,0,1).substr($resident->last_name,0,1));
                            $patientId = 'P-' . now()->format('Y') . '-' . str_pad($resident->id, 3, '0', STR_PAD_LEFT);
                        ?>
                        <div class="patient-card" id="card-<?php echo e($resident->id); ?>">
                            
                            <div class="patient-avatar" style="background: linear-gradient(135deg, <?php echo e($color); ?>, <?php echo e($color); ?>cc);">
                                <?php echo e($initials); ?>

                            </div>

                            
                            <div class="patient-info">
                                <div class="patient-name"><?php echo e($resident->first_name); ?> <?php echo e($resident->last_name); ?></div>
                                <div class="patient-id"><?php echo e($patientId); ?></div>
                                <div class="patient-meta-grid">
                                    <div class="meta-item">
                                        <label>Phone</label>
                                        <span><?php echo e($resident->phone_number); ?></span>
                                    </div>
                                    <div class="meta-item">
                                        <label>Age / Category</label>
                                        <span>
                                            <?php if($resident->age !== null): ?>
                                                <strong><?php echo e($resident->age); ?> yrs</strong> &middot; 
                                                <span class="inline-block px-2 py-0.5 rounded text-[11px] font-semibold bg-blue-50 text-blue-700 border border-blue-200">
                                                    <?php echo e($resident->age_category ?: \App\Models\User::resolveAgeCategory($resident->age)); ?>

                                                </span>
                                            <?php else: ?>
                                                <span class="text-gray-400">—</span>
                                            <?php endif; ?>
                                        </span>
                                    </div>
                                    <div class="meta-item">
                                        <label>Address</label>
                                        <span>Barangay Honrado</span>
                                    </div>
                                    <div class="meta-item">
                                        <label>Registration Date</label>
                                        <span><?php echo e($resident->created_at->format('Y-m-d')); ?></span>
                                    </div>
                                    <div class="meta-item">
                                        <label>Status</label>
                                        <span class="badge-pending-verification">
                                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500 inline-block"></span>
                                            Pending Verification
                                        </span>
                                    </div>
                                    <div class="meta-item">
                                        <label>Maintenance</label>
                                        <?php if($resident->is_maintenance): ?>
                                            <span class="inline-flex items-center gap-1 text-[11px] font-semibold px-2 py-0.5 rounded-full bg-amber-50 text-amber-700 border border-amber-200">
                                                <span class="material-icons-outlined" style="font-size:12px;">medication</span> Yes
                                            </span>
                                        <?php else: ?>
                                            <span class="text-gray-400 font-medium">—</span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>

                            
                            <div class="card-actions">
                                <button class="btn-view-details"
                                        onclick="openDetailModal(
                                            '<?php echo e($resident->first_name); ?> <?php echo e($resident->last_name); ?>',
                                            '<?php echo e($patientId); ?>',
                                            '<?php echo e($resident->email ?: ''); ?>',
                                            '<?php echo e($resident->phone_number); ?>',
                                            '<?php echo e($resident->created_at->format('Y-m-d')); ?>',
                                            '<?php echo e($resident->id_photo ? asset('storage/'.$resident->id_photo) : ''); ?>',
                                            <?php echo e($resident->id); ?>,
                                            'pending',
                                            '<?php echo e($initials); ?>',
                                            '<?php echo e($color); ?>',
                                            '<?php echo e($resident->age !== null ? $resident->age . ' years old' : '—'); ?>',
                                            '<?php echo e($resident->age !== null ? ($resident->age_category ?: \App\Models\User::resolveAgeCategory($resident->age)) : '—'); ?>'
                                        )">
                                    <span class="material-icons-outlined" style="font-size:14px;">visibility</span>
                                    View Details
                                </button>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            <?php endif; ?>
        </div>
    </div>

    
    <div id="content-verified" class="hidden">
        <div class="bg-white border border-gray-200 rounded-xl p-6">
            <?php if($verifiedResidents->isEmpty()): ?>
                <div class="empty-state">
                    <span class="material-icons-outlined block">verified_user</span>
                    <p class="font-semibold text-gray-500">No verified patients yet</p>
                    <p class="text-sm mt-1">Verified residents will appear here.</p>
                </div>
            <?php else: ?>
                <div class="space-y-4">
                    <?php $__currentLoopData = $verifiedResidents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $resident): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php
                            $colors   = ['#2563eb','#7c3aed','#0891b2','#059669','#dc2626','#d97706'];
                            $color    = $colors[$index % count($colors)];
                            $initials = strtoupper(substr($resident->first_name,0,1).substr($resident->last_name,0,1));
                            $patientId = 'P-' . now()->format('Y') . '-' . str_pad($resident->id, 3, '0', STR_PAD_LEFT);
                        ?>
                        <div class="patient-card">
                            <div class="patient-avatar" style="background: linear-gradient(135deg, <?php echo e($color); ?>, <?php echo e($color); ?>cc);">
                                <?php echo e($initials); ?>

                            </div>
                            <div class="patient-info">
                                <div class="patient-name"><?php echo e($resident->first_name); ?> <?php echo e($resident->last_name); ?></div>
                                <div class="patient-id"><?php echo e($patientId); ?></div>
                                <div class="patient-meta-grid">
                                    <div class="meta-item">
                                        <label>Phone</label>
                                        <span><?php echo e($resident->phone_number); ?></span>
                                    </div>
                                    <div class="meta-item">
                                        <label>Age / Category</label>
                                        <span>
                                            <?php if($resident->age !== null): ?>
                                                <strong><?php echo e($resident->age); ?> yrs</strong> &middot; 
                                                <span class="inline-block px-2 py-0.5 rounded text-[11px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                                    <?php echo e($resident->age_category ?: \App\Models\User::resolveAgeCategory($resident->age)); ?>

                                                </span>
                                            <?php else: ?>
                                                <span class="text-gray-400">—</span>
                                            <?php endif; ?>
                                        </span>
                                    </div>
                                    <div class="meta-item">
                                        <label>Address</label>
                                        <span>Barangay Honrado</span>
                                    </div>
                                    <div class="meta-item">
                                        <label>Registration Date</label>
                                        <span><?php echo e($resident->created_at->format('Y-m-d')); ?></span>
                                    </div>
                                    <div class="meta-item">
                                        <label>Status</label>
                                        <span class="badge-verified">
                                            <span class="w-1.5 h-1.5 rounded-full bg-green-500 inline-block"></span>
                                            Verified
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="card-actions">
                                <button class="btn-view-details"
                                        onclick="openDetailModal(
                                            '<?php echo e($resident->first_name); ?> <?php echo e($resident->last_name); ?>',
                                            '<?php echo e($patientId); ?>',
                                            '<?php echo e($resident->email ?: ''); ?>',
                                            '<?php echo e($resident->phone_number); ?>',
                                            '<?php echo e($resident->created_at->format('Y-m-d')); ?>',
                                            '<?php echo e($resident->id_photo ? asset('storage/'.$resident->id_photo) : ''); ?>',
                                            <?php echo e($resident->id); ?>,
                                            'verified',
                                            '<?php echo e($initials); ?>',
                                            '<?php echo e($color); ?>',
                                            '<?php echo e($resident->age !== null ? $resident->age . ' years old' : '—'); ?>',
                                            '<?php echo e($resident->age !== null ? ($resident->age_category ?: \App\Models\User::resolveAgeCategory($resident->age)) : '—'); ?>'
                                        )">
                                    <span class="material-icons-outlined" style="font-size:14px;">visibility</span>
                                    View Details
                                </button>
                                <form method="POST" action="<?php echo e(route('patient.destroy', $resident)); ?>" onsubmit="return confirm('Are you sure you want to delete this verified patient?')">
                                    <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                    <button type="submit" class="btn-reject w-full">
                                        <span class="material-icons-outlined" style="font-size:14px;">delete</span>
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            <?php endif; ?>
        </div>
    </div>

    
    <div id="detailModal" class="pv-modal-overlay" onclick="closeDetailModal(event)">
        <div class="pv-modal" onclick="event.stopPropagation()">
            <div class="pv-modal-header">
                <div class="flex items-center gap-3">
                    <div id="modal-avatar" class="patient-avatar" style="width:44px;height:44px;font-size:15px;"></div>
                    <div>
                        <h3 id="modal-name" class="text-base font-bold text-gray-900"></h3>
                        <p id="modal-patient-id" class="text-xs text-gray-500 font-medium"></p>
                    </div>
                </div>
                <button onclick="closeDetailModalBtn()" class="text-gray-400 hover:text-gray-600 transition-colors">
                    <span class="material-icons-outlined" style="font-size:22px;">close</span>
                </button>
            </div>

            <div class="pv-modal-body space-y-5">
                
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Phone</p>
                        <p id="modal-phone" class="text-sm text-gray-700 font-medium"></p>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Age</p>
                        <p id="modal-age" class="text-sm text-gray-700 font-medium"></p>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Age Category</p>
                        <p id="modal-category" class="text-sm text-gray-700 font-medium"></p>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Address</p>
                        <p class="text-sm text-gray-700 font-medium">Barangay Honrado</p>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Registered</p>
                        <p id="modal-date" class="text-sm text-gray-700 font-medium"></p>
                    </div>
                </div>

                
                <div>
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Government ID Photo</p>
                    <div class="id-photo-box" id="modal-id-box">
                        <img id="modal-id-photo" src="" alt="Government ID" onclick="openLightbox(this.src)" title="Click to enlarge">
                        <div id="modal-no-id" class="text-center py-10 text-gray-400 hidden">
                            <span class="material-icons-outlined mb-2 block" style="font-size:36px;">image_not_supported</span>
                            <p class="text-sm">No ID photo uploaded</p>
                        </div>
                    </div>
                    <p class="text-xs text-gray-400 mt-1.5 text-center">Click on the photo to enlarge</p>
                </div>
            </div>

            
            <div id="modal-footer-pending" class="pv-modal-footer">
                <button onclick="closeDetailModalBtn()" class="px-4 py-2 text-sm font-semibold text-gray-600 hover:text-gray-800 border border-gray-200 rounded-lg hover:bg-gray-50 transition-all">Close</button>
                <form id="modal-reject-form" method="POST">
                    <?php echo csrf_field(); ?> <?php echo method_field('PATCH'); ?>
                    <button type="submit" class="btn-reject" onclick="return confirm('Reject this patient?')">
                        <span class="material-icons-outlined" style="font-size:14px;">cancel</span>
                        Reject
                    </button>
                </form>
                <form id="modal-verify-form" method="POST">
                    <?php echo csrf_field(); ?> <?php echo method_field('PATCH'); ?>
                    <button type="submit" class="btn-verify" onclick="return confirm('Verify and approve this patient?')">
                        <span class="material-icons-outlined" style="font-size:14px;">check_circle</span>
                        Verify Patient
                    </button>
                </form>
            </div>
            <div id="modal-footer-verified" class="pv-modal-footer hidden">
                <button onclick="closeDetailModalBtn()" class="px-4 py-2 text-sm font-semibold text-gray-700 border border-gray-200 rounded-lg hover:bg-gray-50 transition-all">Close</button>
                <form id="modal-delete-form" method="POST">
                    <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                    <button type="submit" class="btn-reject" onclick="return confirm('Are you sure you want to delete this verified patient?')">
                        <span class="material-icons-outlined" style="font-size:14px;">delete</span>
                        Delete
                    </button>
                </form>
            </div>
        </div>
    </div>

    
    <div id="lightbox" class="lightbox" onclick="closeLightbox()">
        <img id="lightbox-img" src="" alt="ID Photo enlarged">
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script>
    // ── Tab Switching ─────────────────────────────────────────────
    function switchTab(tab) {
        const tabs    = ['pending', 'verified'];
        const allBtns = { pending: document.getElementById('tab-pending'), verified: document.getElementById('tab-verified') };
        const allContent = { pending: document.getElementById('content-pending'), verified: document.getElementById('content-verified') };

        tabs.forEach(t => {
            if (t === tab) {
                allBtns[t].classList.add('active');
                allContent[t].classList.remove('hidden');
            } else {
                allBtns[t].classList.remove('active');
                allContent[t].classList.add('hidden');
            }
        });
    }

    // Keep active tab after page reload via URL hash
    const hashTab = window.location.hash === '#verified' ? 'verified' : 'pending';
    switchTab(hashTab);
    document.getElementById('tab-verified').addEventListener('click', () => { history.replaceState(null,'','#verified'); });
    document.getElementById('tab-pending').addEventListener('click',  () => { history.replaceState(null,'','#pending'); });

    // ── Live search (submits on Enter or auto after 500ms) ────────
    let searchTimer;
    document.getElementById('searchInput').addEventListener('input', function() {
        clearTimeout(searchTimer);
        searchTimer = setTimeout(() => this.closest('form').submit(), 500);
    });

    // ── Detail Modal ──────────────────────────────────────────────
    function openDetailModal(name, patientId, email, phone, date, idPhotoUrl, userId, status, initials, color, age, category) {
        const modal = document.getElementById('detailModal');

        document.getElementById('modal-name').textContent      = name;
        document.getElementById('modal-patient-id').textContent = patientId;
        document.getElementById('modal-phone').textContent     = phone;
        document.getElementById('modal-age').textContent       = age || '—';
        document.getElementById('modal-category').textContent  = category || '—';
        document.getElementById('modal-date').textContent      = date;

        // Avatar
        const avatar = document.getElementById('modal-avatar');
        avatar.textContent        = initials;
        avatar.style.background   = `linear-gradient(135deg, ${color}, ${color}cc)`;

        // ID Photo
        const photo   = document.getElementById('modal-id-photo');
        const noPhoto = document.getElementById('modal-no-id');
        if (idPhotoUrl) {
            photo.src = idPhotoUrl;
            photo.classList.remove('hidden');
            noPhoto.classList.add('hidden');
        } else {
            photo.classList.add('hidden');
            noPhoto.classList.remove('hidden');
        }

        // Footer
        const pendingFooter  = document.getElementById('modal-footer-pending');
        const verifiedFooter = document.getElementById('modal-footer-verified');
        if (status === 'pending') {
            pendingFooter.classList.remove('hidden');
            verifiedFooter.classList.add('hidden');
            document.getElementById('modal-verify-form').action = `/nurse/patient-verification/${userId}/verify`;
            document.getElementById('modal-reject-form').action = `/nurse/patient-verification/${userId}/reject`;
        } else {
            pendingFooter.classList.add('hidden');
            verifiedFooter.classList.remove('hidden');
            document.getElementById('modal-delete-form').action = `/nurse/patient-verification/${userId}`;
        }

        modal.classList.add('active');
        document.body.style.overflow = 'hidden';
    }

    function closeDetailModal(e) {
        if (e.target === document.getElementById('detailModal')) closeDetailModalBtn();
    }
    function closeDetailModalBtn() {
        document.getElementById('detailModal').classList.remove('active');
        document.body.style.overflow = '';
    }

    // ── Lightbox ──────────────────────────────────────────────────
    function openLightbox(src) {
        const lb = document.getElementById('lightbox');
        document.getElementById('lightbox-img').src = src;
        lb.classList.add('active');
    }
    function closeLightbox() {
        document.getElementById('lightbox').classList.remove('active');
    }
    document.addEventListener('keydown', e => {
        if (e.key === 'Escape') { closeDetailModalBtn(); closeLightbox(); }
    });
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.nurse', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\techyknowloadeia1\resources\views/dashboards/nurse-patient-verification.blade.php ENDPATH**/ ?>