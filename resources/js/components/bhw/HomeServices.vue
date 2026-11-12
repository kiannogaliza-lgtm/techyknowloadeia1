<template>
  <div class="space-y-6">
    <!-- Main Container Card -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-8">

      <!-- Top Pill Tabs and Action Buttons -->
      <div class="flex items-center gap-3 mb-6 pb-2 border-b border-gray-100 flex-wrap">
        <button type="button" @click="currentTab = 'approved'" :class="['hs-tab', currentTab === 'approved' ? 'active' : '']">
          Approved ({{ approvedList.length }})
        </button>
        <button type="button" @click="currentTab = 'completed'" :class="['hs-tab', currentTab === 'completed' ? 'active' : '']">
          Completed ({{ completedList.length }})
        </button>
        <button v-if="rejectedList.length > 0" type="button" @click="currentTab = 'rejected'" :class="['hs-tab', currentTab === 'rejected' ? 'active' : '']">
          Rejected ({{ rejectedList.length }})
        </button>

        <div class="ml-auto flex items-center gap-2">
          <button type="button" @click="openClearModal" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-red-50 hover:bg-red-100 text-red-600 border border-red-200 rounded-lg text-xs font-semibold shadow-sm transition-colors cursor-pointer" title="Clear requests in list">
            <span class="material-icons-outlined text-sm">delete_sweep</span>
            <span>Clear List</span>
          </button>
          <button type="button" @click="openNewRequestModal" class="inline-flex items-center gap-1.5 px-3.5 py-1.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-xs font-semibold shadow-sm transition-colors cursor-pointer">
            <span class="material-icons-outlined text-sm">add</span>
            <span>New Request</span>
          </button>
        </div>
      </div>





      <!-- ── TAB 3: APPROVED REQUESTS ── -->
      <div v-if="currentTab === 'approved'" class="space-y-4">
        <div v-for="req in approvedList" :key="req.id" class="hs-card">
          <!-- Header Row -->
          <div class="flex items-start justify-between gap-4">
            <div>
              <div class="flex items-center gap-2.5">
                <h3 class="text-base font-bold text-gray-900">{{ req.patient_name }}</h3>
                <span class="badge-hs-approved">{{ req.status === 'Confirmed' ? 'Confirmed' : 'Approved' }}</span>
              </div>
              <p class="text-xs text-gray-400 font-medium mt-0.5">Request ID: {{ getFormattedId(req) }}</p>
            </div>

            <!-- Actions for Approved -->
            <div class="flex items-center gap-2 flex-wrap justify-end">
              <button type="button" @click="viewDetails(req)" class="btn-action-view">
                <span class="material-icons-outlined" style="font-size:15px;">visibility</span>
                <span>View</span>
              </button>
              <button type="button" 
                      @click="markCompleted(req)" 
                      class="btn-action-approve" 
                      :disabled="loadingAction === req.id">
                <span class="material-icons-outlined" style="font-size:15px;">task_alt</span>
                <span>Complete</span>
              </button>
            </div>
          </div>

          <!-- 3-Column Info Grid -->
          <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-4">
            <div>
              <div class="text-[11px] font-medium text-gray-400 uppercase tracking-wide">Service Type</div>
              <div class="text-sm font-semibold text-gray-800 mt-0.5">{{ req.service_type }}</div>

              <div class="text-[11px] font-medium text-gray-400 uppercase tracking-wide mt-3">Preferred Date</div>
              <div class="text-sm font-semibold text-gray-800 mt-0.5">{{ formatDate(req.preferred_date) }}</div>
            </div>

            <div>
              <div class="text-[11px] font-medium text-gray-400 uppercase tracking-wide flex items-center gap-1">
                <span class="material-icons-outlined text-gray-400" style="font-size:14px;">location_on</span>
                <span>Address</span>
              </div>
              <div class="text-sm font-semibold text-gray-800 mt-0.5">{{ req.address }}</div>

              <div class="text-[11px] font-medium text-gray-400 uppercase tracking-wide mt-3">Preferred Time</div>
              <div class="text-sm font-semibold text-gray-800 mt-0.5">{{ formatTime(req.preferred_time) }}</div>
            </div>

            <div>
              <div class="text-[11px] font-medium text-gray-400 uppercase tracking-wide flex items-center gap-1">
                <span class="material-icons-outlined text-gray-400" style="font-size:14px;">call</span>
                <span>Contact</span>
              </div>
              <div class="text-sm font-semibold text-gray-800 mt-0.5">{{ req.contact_number }}</div>

              <div class="text-[11px] font-medium text-gray-400 uppercase tracking-wide mt-3">Assigned To</div>
              <div class="text-sm font-semibold text-blue-600 mt-0.5">{{ req.assigned_name || req.assigned_bhw_name || 'Assigned to You' }}</div>
            </div>
          </div>

          <!-- Reason / Symptoms Box -->
          <div class="reason-box">
            <div class="text-[11px] font-medium text-gray-400">Reason / Symptoms</div>
            <div class="text-xs text-gray-700 font-medium mt-1">{{ req.reason_symptoms || 'Regular BP monitoring required' }}</div>
          </div>
        </div>

        <div v-if="approvedList.length === 0" class="text-center py-14 border border-dashed border-gray-200 rounded-xl bg-gray-50/50">
          <span class="material-icons-outlined text-gray-300 text-5xl mb-2">check_circle_outline</span>
          <p class="text-sm font-semibold text-gray-600">No Approved Requests</p>
          <p class="text-xs text-gray-400 mt-1">Approved requests ready for visit or assignment will appear here.</p>
        </div>
      </div>

      <!-- ── TAB 4: COMPLETED REQUESTS ── -->
      <div v-if="currentTab === 'completed'" class="space-y-4">
        <div v-for="req in completedList" :key="req.id" class="hs-card">
          <!-- Header Row -->
          <div class="flex items-start justify-between gap-4">
            <div>
              <div class="flex items-center gap-2.5">
                <h3 class="text-base font-bold text-gray-900">{{ req.patient_name }}</h3>
                <span class="badge-hs-completed">Completed</span>
              </div>
              <p class="text-xs text-gray-400 font-medium mt-0.5">Request ID: {{ getFormattedId(req) }}</p>
            </div>

            <!-- Actions for Completed -->
            <div class="flex items-center gap-2">
              <button type="button" @click="viewDetails(req)" class="btn-action-view">
                <span class="material-icons-outlined" style="font-size:15px;">visibility</span>
                <span>View</span>
              </button>
              <button type="button" @click="openDeleteModal(req)" class="btn-action-delete" title="Delete Request">
                <span class="material-icons-outlined" style="font-size:15px;">delete</span>
                <span>Delete</span>
              </button>
            </div>
          </div>

          <!-- 3-Column Info Grid -->
          <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-4">
            <div>
              <div class="text-[11px] font-medium text-gray-400 uppercase tracking-wide">Service Type</div>
              <div class="text-sm font-semibold text-gray-800 mt-0.5">{{ req.service_type }}</div>

              <div class="text-[11px] font-medium text-gray-400 uppercase tracking-wide mt-3">Preferred Date</div>
              <div class="text-sm font-semibold text-gray-800 mt-0.5">{{ formatDate(req.preferred_date) }}</div>
            </div>

            <div>
              <div class="text-[11px] font-medium text-gray-400 uppercase tracking-wide flex items-center gap-1">
                <span class="material-icons-outlined text-gray-400" style="font-size:14px;">location_on</span>
                <span>Address</span>
              </div>
              <div class="text-sm font-semibold text-gray-800 mt-0.5">{{ req.address }}</div>

              <div class="text-[11px] font-medium text-gray-400 uppercase tracking-wide mt-3">Preferred Time</div>
              <div class="text-sm font-semibold text-gray-800 mt-0.5">{{ formatTime(req.preferred_time) }}</div>
            </div>

            <div>
              <div class="text-[11px] font-medium text-gray-400 uppercase tracking-wide flex items-center gap-1">
                <span class="material-icons-outlined text-gray-400" style="font-size:14px;">call</span>
                <span>Contact</span>
              </div>
              <div class="text-sm font-semibold text-gray-800 mt-0.5">{{ req.contact_number }}</div>

              <div class="text-[11px] font-medium text-gray-400 uppercase tracking-wide mt-3">Assigned To</div>
              <div class="text-sm font-semibold text-blue-600 mt-0.5">{{ req.assigned_name || req.assigned_bhw_name || 'Assigned to You' }}</div>
            </div>
          </div>

          <!-- Reason / Symptoms Box -->
          <div class="reason-box">
            <div class="text-[11px] font-medium text-gray-400">Reason / Symptoms</div>
            <div class="text-xs text-gray-700 font-medium mt-1">{{ req.reason_symptoms || 'Post surgical wound dressing change' }}</div>
          </div>
        </div>

        <div v-if="completedList.length === 0" class="text-center py-14 border border-dashed border-gray-200 rounded-xl bg-gray-50/50">
          <span class="material-icons-outlined text-gray-300 text-5xl mb-2">task_alt</span>
          <p class="text-sm font-semibold text-gray-600">No Completed Requests</p>
          <p class="text-xs text-gray-400 mt-1">Completed home visits will be archived here.</p>
        </div>
      </div>

      <!-- ── TAB 5: REJECTED REQUESTS (IF ANY) ── -->
      <div v-if="currentTab === 'rejected'" class="space-y-4">
        <div v-for="req in rejectedList" :key="req.id" class="hs-card opacity-80">
          <div class="flex items-start justify-between gap-4">
            <div>
              <div class="flex items-center gap-2.5">
                <h3 class="text-base font-bold text-gray-900">{{ req.patient_name }}</h3>
                <span class="badge-hs-rejected">Rejected</span>
              </div>
              <p class="text-xs text-gray-400 font-medium mt-0.5">Request ID: {{ getFormattedId(req) }}</p>
            </div>
            <div class="flex items-center gap-2">
              <button type="button" @click="viewDetails(req)" class="btn-action-view">
                <span class="material-icons-outlined" style="font-size:15px;">visibility</span>
                <span>View</span>
              </button>
            </div>
          </div>
          <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-4">
            <div>
              <div class="text-[11px] font-medium text-gray-400 uppercase tracking-wide">Service Type</div>
              <div class="text-sm font-semibold text-gray-800 mt-0.5">{{ req.service_type }}</div>
            </div>
            <div>
              <div class="text-[11px] font-medium text-gray-400 uppercase tracking-wide">Address</div>
              <div class="text-sm font-semibold text-gray-800 mt-0.5">{{ req.address }}</div>
            </div>
            <div>
              <div class="text-[11px] font-medium text-gray-400 uppercase tracking-wide">Rejection Reason</div>
              <div class="text-sm font-semibold text-red-600 mt-0.5">{{ req.rejection_reason || 'None specified' }}</div>
            </div>
          </div>
        </div>
      </div>

    </div>

    <!-- ── MODAL: VIEW DETAILS ── -->
    <div v-if="activeModal === 'view'" class="hs-modal-overlay active">
      <div class="hs-modal-card" @click.stop>
        <!-- Header -->
        <div class="px-6 py-5 border-b border-gray-100 flex items-center justify-between">
          <div class="flex items-center gap-2.5">
            <div class="w-9 h-9 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center">
              <span class="material-icons-outlined text-xl">home_repair_service</span>
            </div>
            <div>
              <h3 class="font-bold text-gray-900 text-base">{{ selectedReq?.patient_name || 'Home Service Details' }}</h3>
              <p class="text-xs text-gray-400 font-medium">Request ID: {{ getFormattedId(selectedReq) }}</p>
            </div>
          </div>
          <button type="button" @click="closeModal" class="text-gray-400 hover:text-gray-600 transition-colors cursor-pointer">
            <span class="material-icons-outlined">close</span>
          </button>
        </div>

        <!-- Body -->
        <div class="p-6 space-y-4">
          <div class="flex items-center justify-between p-3 bg-gray-50 rounded-xl border border-gray-100">
            <span class="text-xs text-gray-500 font-medium">Request Status</span>
            <span :class="getStatusBadgeClass(selectedReq?.status)">{{ selectedReq?.status || 'Pending' }}</span>
          </div>

          <div class="grid grid-cols-2 gap-4">
            <div class="p-3 bg-gray-50 rounded-xl border border-gray-100">
              <span class="text-[11px] font-medium text-gray-400 uppercase tracking-wider block">Service Type</span>
              <span class="text-sm font-bold text-gray-800 mt-0.5 block">{{ selectedReq?.service_type || '—' }}</span>
            </div>
            <div class="p-3 bg-gray-50 rounded-xl border border-gray-100">
              <span class="text-[11px] font-medium text-gray-400 uppercase tracking-wider block">Contact Number</span>
              <span class="text-sm font-bold text-gray-800 mt-0.5 block">{{ selectedReq?.contact_number || '—' }}</span>
            </div>
          </div>

          <div class="grid grid-cols-2 gap-4">
            <div class="p-3 bg-gray-50 rounded-xl border border-gray-100">
              <span class="text-[11px] font-medium text-gray-400 uppercase tracking-wider block">Preferred Date</span>
              <span class="text-sm font-semibold text-gray-800 mt-0.5 block">{{ formatDate(selectedReq?.preferred_date) }}</span>
            </div>
            <div class="p-3 bg-gray-50 rounded-xl border border-gray-100">
              <span class="text-[11px] font-medium text-gray-400 uppercase tracking-wider block">Preferred Time</span>
              <span class="text-sm font-semibold text-gray-800 mt-0.5 block">{{ formatTime(selectedReq?.preferred_time) }}</span>
            </div>
          </div>

          <div class="p-3 bg-gray-50 rounded-xl border border-gray-100">
            <span class="text-[11px] font-medium text-gray-400 uppercase tracking-wider block">Address</span>
            <span class="text-sm font-semibold text-gray-800 mt-0.5 block">{{ selectedReq?.address || '—' }}</span>
          </div>

          <div class="p-3 bg-gray-50 rounded-xl border border-gray-100">
            <span class="text-[11px] font-medium text-gray-400 uppercase tracking-wider block">Assigned Health Worker</span>
            <span class="text-sm font-bold text-blue-600 mt-0.5 block">
              {{ selectedReq?.assigned_name || selectedReq?.assigned_bhw_name || (selectedReq?.status === 'Pending' ? 'Pending Assignment' : 'Unassigned') }}
            </span>
          </div>

          <div class="p-3 bg-gray-50 rounded-xl border border-gray-100">
            <span class="text-[11px] font-medium text-gray-400 uppercase tracking-wider block">Reason / Symptoms</span>
            <p class="text-xs text-gray-700 font-medium mt-1 leading-relaxed">{{ selectedReq?.reason_symptoms || 'None provided' }}</p>
          </div>
        </div>

        <!-- Footer -->
        <div class="px-6 py-4 border-t border-gray-100 flex items-center justify-between bg-gray-50/50 rounded-b-2xl">
          <div class="flex items-center gap-2">
            <template v-if="selectedReq?.status === 'Approved' || selectedReq?.status === 'Confirmed' || selectedReq?.status === 'assigned' || selectedReq?.status === 'Accepted'">
              <button type="button" @click="markCompleted(selectedReq)" class="btn-action-approve" style="background:#059669;">
                <span class="material-icons-outlined" style="font-size:15px;">task_alt</span>
                <span>Mark as Completed</span>
              </button>
            </template>
          </div>

          <div class="flex items-center gap-2 ml-auto">
            <button v-if="selectedReq?.status === 'Completed'" type="button" @click="openDeleteModal(selectedReq)" class="btn-action-delete">
              <span class="material-icons-outlined" style="font-size:15px;">delete</span>
              <span>Delete</span>
            </button>
            <button type="button" @click="closeModal" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 text-xs font-semibold rounded-lg transition-colors cursor-pointer">
              Close
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- ── MODAL: APPROVE / CLAIM REQUEST ── -->
    <div v-if="activeModal === 'approve'" class="hs-modal-overlay active">
      <div class="hs-modal-card" @click.stop>
        <div class="px-6 py-5 border-b border-gray-100 flex items-center justify-between">
          <div class="flex items-center gap-2.5">
            <div class="w-9 h-9 rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center">
              <span class="material-icons-outlined text-xl">check_circle</span>
            </div>
            <div>
              <h3 class="font-bold text-gray-900 text-base">Approve Home Service</h3>
              <p class="text-xs text-gray-400 font-medium">Patient: {{ selectedReq?.patient_name }} ({{ getFormattedId(selectedReq) }})</p>
            </div>
          </div>
          <button type="button" @click="closeModal" class="text-gray-400 hover:text-gray-600 transition-colors cursor-pointer">
            <span class="material-icons-outlined">close</span>
          </button>
        </div>

        <div class="p-6 space-y-4">
          <p class="text-sm text-gray-600 leading-relaxed">
            Are you sure you want to approve and claim this home service request? It will be assigned to you and moved to your approved list.
          </p>
          <div class="p-3.5 bg-gray-50 border border-gray-100 rounded-xl space-y-1.5 text-xs">
            <div class="flex justify-between"><span class="text-gray-400">Service:</span> <span class="font-bold text-gray-800">{{ selectedReq?.service_type }}</span></div>
            <div class="flex justify-between"><span class="text-gray-400">Date & Time:</span> <span class="font-semibold text-gray-800">{{ formatDate(selectedReq?.preferred_date) }} at {{ formatTime(selectedReq?.preferred_time) }}</span></div>
            <div class="flex justify-between"><span class="text-gray-400">Address:</span> <span class="font-medium text-gray-700">{{ selectedReq?.address }}</span></div>
          </div>
        </div>

        <div class="px-6 py-4 border-t border-gray-100 flex items-center justify-end gap-3 bg-gray-50/50 rounded-b-2xl">
          <button type="button" @click="closeModal" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 text-xs font-semibold rounded-lg transition-colors cursor-pointer">
            Cancel
          </button>
          <button type="button" @click="submitClaim" :disabled="loadingAction === selectedReq?.id" class="px-5 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold rounded-lg shadow-sm transition-colors cursor-pointer disabled:opacity-50">
            {{ loadingAction === selectedReq?.id ? 'Approving...' : 'Confirm Approval' }}
          </button>
        </div>
      </div>
    </div>

    <!-- ── MODAL: REJECT REQUEST ── -->
    <div v-if="activeModal === 'reject'" class="hs-modal-overlay active">
      <div class="hs-modal-card" @click.stop>
        <div class="px-6 py-5 border-b border-gray-100 flex items-center justify-between">
          <div class="flex items-center gap-2.5">
            <div class="w-9 h-9 rounded-full bg-red-50 text-red-600 flex items-center justify-center">
              <span class="material-icons-outlined text-xl">cancel</span>
            </div>
            <div>
              <h3 class="font-bold text-gray-900 text-base">Reject Request</h3>
              <p class="text-xs text-gray-400 font-medium">Patient: {{ selectedReq?.patient_name }} ({{ getFormattedId(selectedReq) }})</p>
            </div>
          </div>
          <button type="button" @click="closeModal" class="text-gray-400 hover:text-gray-600 transition-colors cursor-pointer">
            <span class="material-icons-outlined">close</span>
          </button>
        </div>

        <div class="p-6 space-y-4">
          <div>
            <label class="block text-xs font-semibold text-gray-700 mb-1">Reason for Rejection <span class="text-red-500">*</span></label>
            <textarea v-model="rejectReason" rows="3" required placeholder="State reason (e.g. resident unreachable, service not offered for home visit, schedule conflict)..." class="w-full px-3.5 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-red-500/20 focus:border-red-500"></textarea>
          </div>
        </div>

        <div class="px-6 py-4 border-t border-gray-100 flex items-center justify-end gap-3 bg-gray-50/50 rounded-b-2xl">
          <button type="button" @click="closeModal" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 text-xs font-semibold rounded-lg transition-colors cursor-pointer">
            Cancel
          </button>
          <button type="button" @click="submitReject" :disabled="loadingAction === selectedReq?.id" class="px-5 py-2 bg-red-600 hover:bg-red-700 text-white text-xs font-semibold rounded-lg shadow-sm transition-colors cursor-pointer disabled:opacity-50">
            {{ loadingAction === selectedReq?.id ? 'Rejecting...' : 'Reject Request' }}
          </button>
        </div>
      </div>
    </div>

    <!-- ── MODAL: DELETE INDIVIDUAL REQUEST ── -->
    <div v-if="activeModal === 'delete'" class="hs-modal-overlay active">
      <div class="hs-modal-card max-w-md" @click.stop>
        <div class="p-6 text-center space-y-3">
          <div class="w-12 h-12 rounded-full bg-red-100 text-red-600 flex items-center justify-center mx-auto">
            <span class="material-icons-outlined text-2xl">delete_forever</span>
          </div>
          <h3 class="font-bold text-gray-900 text-base">Delete Request {{ getFormattedId(selectedReq) }}</h3>
          <p class="text-xs text-gray-500 leading-relaxed">
            Are you sure you want to permanently delete the home service request for {{ selectedReq?.patient_name }}? This action cannot be undone.
          </p>
        </div>

        <div class="px-6 py-4 border-t border-gray-100 flex items-center justify-end gap-3 bg-gray-50/50 rounded-b-2xl">
          <button type="button" @click="closeModal" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 text-xs font-semibold rounded-lg transition-colors cursor-pointer">
            Cancel
          </button>
          <button type="button" @click="submitDelete" :disabled="loadingAction === selectedReq?.id" class="px-5 py-2 bg-red-600 hover:bg-red-700 text-white text-xs font-semibold rounded-lg shadow-sm transition-colors cursor-pointer disabled:opacity-50">
            {{ loadingAction === selectedReq?.id ? 'Deleting...' : 'Delete Request' }}
          </button>
        </div>
      </div>
    </div>

    <!-- ── MODAL: CLEAR LIST / BULK DELETE ── -->
    <div v-if="activeModal === 'clear'" class="hs-modal-overlay active">
      <div class="hs-modal-card max-w-md" @click.stop>
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
          <button type="button" @click="closeModal" class="text-gray-400 hover:text-gray-600 transition-colors cursor-pointer">
            <span class="material-icons-outlined">close</span>
          </button>
        </div>

        <div class="p-6 space-y-4">
          <div>
            <label class="block text-xs font-semibold text-gray-700 mb-2">Select which records to clear:</label>
            <div class="space-y-2">
              <label class="flex items-center gap-3 p-3 bg-gray-50 border border-gray-200 rounded-xl cursor-pointer hover:bg-gray-100 transition-colors">
                <input type="radio" v-model="clearScope" value="Completed" class="text-red-600 focus:ring-red-500">
                <div>
                  <div class="text-xs font-bold text-gray-800">Completed Requests Only ({{ completedList.length }})</div>
                  <div class="text-[11px] text-gray-400">Safely clear past completed visit records</div>
                </div>
              </label>
              <label class="flex items-center gap-3 p-3 bg-gray-50 border border-gray-200 rounded-xl cursor-pointer hover:bg-gray-100 transition-colors">
                <input type="radio" v-model="clearScope" value="Approved" class="text-red-600 focus:ring-red-500">
                <div>
                  <div class="text-xs font-bold text-gray-800">Approved Requests Only ({{ approvedList.length }})</div>
                  <div class="text-[11px] text-gray-400">Clear all approved schedule requests</div>
                </div>
              </label>
              <label class="flex items-center gap-3 p-3 bg-red-50/40 border border-red-200 rounded-xl cursor-pointer hover:bg-red-50 transition-colors">
                <input type="radio" v-model="clearScope" value="all" class="text-red-600 focus:ring-red-500">
                <div>
                  <div class="text-xs font-bold text-red-700">Clear ALL Requests ({{ approvedList.length + completedList.length + rejectedList.length }})</div>
                  <div class="text-[11px] text-red-500">Wipe all home service request records completely</div>
                </div>
              </label>
            </div>
          </div>
        </div>

        <div class="px-6 py-4 border-t border-gray-100 flex items-center justify-end gap-3 bg-gray-50/50 rounded-b-2xl">
          <button type="button" @click="closeModal" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 text-xs font-semibold rounded-lg transition-colors cursor-pointer">
            Cancel
          </button>
          <button type="button" @click="submitClear" :disabled="loadingAction === 'clear'" class="px-5 py-2 bg-red-600 hover:bg-red-700 text-white text-xs font-semibold rounded-lg shadow-sm transition-colors cursor-pointer disabled:opacity-50">
            {{ loadingAction === 'clear' ? 'Clearing...' : 'Clear Selected' }}
          </button>
        </div>
      </div>
    </div>

    <!-- ── MODAL: NEW HOME SERVICE REQUEST ── -->
    <div v-if="activeModal === 'newRequest'" class="hs-modal-overlay active">
      <div class="hs-modal-card" @click.stop>
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
          <button type="button" @click="closeModal" class="text-gray-400 hover:text-gray-600 transition-colors cursor-pointer">
            <span class="material-icons-outlined">close</span>
          </button>
        </div>

        <div class="p-6 space-y-4">
          <div>
            <label class="block text-xs font-semibold text-gray-700 mb-1">Patient Full Name <span class="text-red-500">*</span></label>
            <input type="text" v-model="newForm.patient_name" required placeholder="e.g. Juan Dela Cruz" class="w-full px-3.5 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500">
          </div>

          <div class="grid grid-cols-2 gap-4">
            <div>
              <label class="block text-xs font-semibold text-gray-700 mb-1">Service Type <span class="text-red-500">*</span></label>
              <select v-model="newForm.service_type" required class="w-full px-3.5 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500">
                <option value="Medical Check-up">Medical Check-up</option>
                <option value="Blood Pressure Monitoring">Blood Pressure Monitoring</option>
                <option value="Wound Care">Wound Care</option>
                <option value="Post-natal Care">Post-natal Care</option>
                <option value="Elderly Health Check">Elderly Health Check</option>
                <option value="Vaccination Assistance">Vaccination Assistance</option>
                <option value="Immunization">Immunization</option>
                <option value="Medication Delivery">Medication Delivery</option>
              </select>
            </div>
            <div>
              <label class="block text-xs font-semibold text-gray-700 mb-1">Contact Number <span class="text-red-500">*</span></label>
              <input type="tel" v-model="newForm.contact_number" required maxlength="11" inputmode="numeric" @input="handleNewContactInput($event)" placeholder="09123456789" class="w-full px-3.5 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500">
            </div>
          </div>

          <div class="grid grid-cols-2 gap-4">
            <div>
              <label class="block text-xs font-semibold text-gray-700 mb-1">Preferred Date <span class="text-red-500">*</span></label>
              <input type="date" v-model="newForm.preferred_date" required class="w-full px-3.5 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500">
            </div>
            <div>
              <label class="block text-xs font-semibold text-gray-700 mb-1">Preferred Time <span class="text-red-500">*</span></label>
              <input type="time" v-model="newForm.preferred_time" required class="w-full px-3.5 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500">
            </div>
          </div>

          <div>
            <label class="block text-xs font-semibold text-gray-700 mb-1">Home Address <span class="text-red-500">*</span></label>
            <input type="text" v-model="newForm.address" required placeholder="Purok, Street, Barangay" class="w-full px-3.5 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500">
          </div>

          <div>
            <label class="block text-xs font-semibold text-gray-700 mb-1">Reason / Symptoms (Optional)</label>
            <textarea v-model="newForm.reason_symptoms" rows="3" placeholder="Describe symptoms or reason for home visit..." class="w-full px-3.5 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500"></textarea>
          </div>
        </div>

        <div class="px-6 py-4 border-t border-gray-100 flex items-center justify-end gap-3 bg-gray-50/50 rounded-b-2xl">
          <button type="button" @click="closeModal" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 text-xs font-semibold rounded-lg transition-colors cursor-pointer">
            Cancel
          </button>
          <button type="button" @click="submitNewRequest" :disabled="loadingAction === 'new'" class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold rounded-lg shadow-sm transition-colors cursor-pointer disabled:opacity-50">
            {{ loadingAction === 'new' ? 'Creating...' : 'Create Request' }}
          </button>
        </div>
      </div>
    </div>

  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';

const props = defineProps({
  pendingHomeServices: {
    type: Array,
    default: () => []
  },
  acceptedHomeServices: {
    type: Array,
    default: () => []
  },
  assignedHomeServices: {
    type: Array,
    default: () => []
  },
  completedHomeServices: {
    type: Array,
    default: () => []
  }
});

const currentTab = ref('approved');
const csrfToken = ref('');
const loadingAction = ref(null);

const pendingList = ref([...props.pendingHomeServices]);
const acceptedList = ref([...props.acceptedHomeServices]);
const approvedList = ref([...props.assignedHomeServices]);
const completedList = ref([...props.completedHomeServices]);
const rejectedList = ref([]);

const activeModal = ref(null);
const selectedReq = ref(null);
const rejectReason = ref('');
const clearScope = ref('Completed');

const newForm = ref({
  patient_name: '',
  service_type: 'Medical Check-up',
  contact_number: '',
  preferred_date: '',
  preferred_time: '',
  address: '',
  reason_symptoms: ''
});

onMounted(() => {
  const tokenMeta = document.querySelector('meta[name="csrf-token"]');
  if (tokenMeta) {
    csrfToken.value = tokenMeta.getAttribute('content');
  }
});

const getFormattedId = (req) => {
  if (!req) return 'HS-000';
  if (req.formatted_id) return req.formatted_id;
  return `HS-${String(req.id).padStart(3, '0')}`;
};

const formatDate = (dateString) => {
  if (!dateString) return '—';
  const d = new Date(dateString);
  return isNaN(d) ? dateString : d.toISOString().split('T')[0];
};

const formatTime = (timeString) => {
  if (!timeString) return '—';
  if (timeString.includes('T')) {
    const d = new Date(timeString);
    if (!isNaN(d)) {
      return d.toLocaleTimeString([], { hour: 'numeric', minute: '2-digit' });
    }
  }
  let parts = timeString.split(':');
  if (parts.length >= 2) {
    let hours = parseInt(parts[0], 10);
    const minutes = parts[1];
    const ampm = hours >= 12 ? 'PM' : 'AM';
    hours = hours % 12;
    hours = hours ? hours : 12;
    return `${hours}:${minutes} ${ampm}`;
  }
  return timeString;
};

const getStatusBadgeClass = (status) => {
  if (status === 'Pending') return 'badge-hs-pending';
  if (status === 'Accepted') return 'badge-hs-accepted';
  if (status === 'Approved' || status === 'Confirmed' || status === 'assigned') return 'badge-hs-approved';
  if (status === 'Completed') return 'badge-hs-completed';
  return 'badge-hs-rejected';
};

const closeModal = () => {
  activeModal.value = null;
  selectedReq.value = null;
  rejectReason.value = '';
};

const viewDetails = (req) => {
  selectedReq.value = req;
  activeModal.value = 'view';
};

const openClaimModal = (req) => {
  selectedReq.value = req;
  activeModal.value = 'approve';
};

const openRejectModal = (req) => {
  selectedReq.value = req;
  rejectReason.value = '';
  activeModal.value = 'reject';
};

const openDeleteModal = (req) => {
  selectedReq.value = req;
  activeModal.value = 'delete';
};

const openClearModal = () => {
  activeModal.value = 'clear';
};

const openNewRequestModal = () => {
  newForm.value = {
    patient_name: '',
    service_type: 'Medical Check-up',
    contact_number: '',
    preferred_date: new Date().toISOString().split('T')[0],
    preferred_time: '09:00',
    address: '',
    reason_symptoms: ''
  };
  activeModal.value = 'newRequest';
};

// Accept Request (team is coming)
const acceptRequest = async (req) => {
  loadingAction.value = req.id;

  try {
    let response = await fetch(`/bhw/home-services/${req.id}/accept`, {
      method: 'PATCH',
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        'X-CSRF-TOKEN': csrfToken.value,
      },
    });

    if (!response.ok) {
      response = await fetch(`/nurse/home-services/${req.id}/accept`, {
        method: 'PATCH',
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json',
          'X-CSRF-TOKEN': csrfToken.value,
        },
      });
    }

    const data = await response.json().catch(() => ({}));
    if (response.ok) {
      const pIdx = pendingList.value.findIndex(s => s.id === req.id);
      if (pIdx !== -1) {
        const item = pendingList.value.splice(pIdx, 1)[0];
        item.status = 'Accepted';
        acceptedList.value.unshift(item);
        currentTab.value = 'accepted';
      }
      closeModal();
    } else {
      alert(data.message || 'Something went wrong.');
    }
  } catch (err) {
    alert('Network error. Please try again.');
  } finally {
    loadingAction.value = null;
  }
};

// Actions
const submitClaim = async () => {
  if (!selectedReq.value) return;
  const req = selectedReq.value;
  loadingAction.value = req.id;

  try {
    const response = await fetch(`/bhw/home-services/${req.id}/approve`, {
      method: 'PATCH',
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        'X-CSRF-TOKEN': csrfToken.value,
      },
    });

    const data = await response.json();
    if (response.ok && data.success) {
      // Remove from pending or accepted list
      let pIdx = pendingList.value.findIndex(s => s.id === req.id);
      if (pIdx !== -1) {
        const item = pendingList.value.splice(pIdx, 1)[0];
        item.status = 'Approved';
        item.assigned_name = 'Assigned to You';
        approvedList.value.unshift(item);
      } else {
        let aIdx = acceptedList.value.findIndex(s => s.id === req.id);
        if (aIdx !== -1) {
          const item = acceptedList.value.splice(aIdx, 1)[0];
          item.status = 'Approved';
          item.assigned_name = 'Assigned to You';
          approvedList.value.unshift(item);
        }
      }
      currentTab.value = 'approved';
      closeModal();
    } else {
      alert(data.message || 'Something went wrong.');
    }
  } catch (err) {
    alert('Network error. Please try again.');
  } finally {
    loadingAction.value = null;
  }
};

const confirmAssignment = async (req) => {
  if (!confirm('Are you sure you want to confirm this assignment? The resident will be notified.')) return;
  loadingAction.value = req.id;

  try {
    const response = await fetch(`/bhw/home-services/${req.id}/confirm`, {
      method: 'PATCH',
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        'X-CSRF-TOKEN': csrfToken.value,
      },
    });

    const data = await response.json();
    if (response.ok && data.success) {
      const idx = approvedList.value.findIndex(s => s.id === req.id);
      if (idx !== -1) {
        approvedList.value[idx].status = 'Confirmed';
      }
    } else {
      alert(data.message || 'Something went wrong.');
    }
  } catch (err) {
    alert('Network error. Please try again.');
  } finally {
    loadingAction.value = null;
  }
};

const markCompleted = async (req) => {
  if (!confirm('Are you sure you want to mark this service as completed?')) return;
  loadingAction.value = req.id;

  try {
    let response = await fetch(`/bhw/home-services/${req.id}/complete`, {
      method: 'PATCH',
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        'X-CSRF-TOKEN': csrfToken.value,
      },
    });

    if (!response.ok) {
      response = await fetch(`/nurse/home-services/${req.id}/complete`, {
        method: 'PATCH',
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json',
          'X-CSRF-TOKEN': csrfToken.value,
        },
      });
    }

    const data = await response.json().catch(() => ({}));
    if (response.ok) {
      let aIdx = acceptedList.value.findIndex(s => s.id === req.id);
      if (aIdx !== -1) {
        const item = acceptedList.value.splice(aIdx, 1)[0];
        item.status = 'Completed';
        completedList.value.unshift(item);
      } else {
        const idx = approvedList.value.findIndex(s => s.id === req.id);
        if (idx !== -1) {
          const item = approvedList.value.splice(idx, 1)[0];
          item.status = 'Completed';
          completedList.value.unshift(item);
        }
      }
      currentTab.value = 'completed';
      closeModal();
    } else {
      alert(data.message || 'Something went wrong.');
    }
  } catch (err) {
    alert('Network error. Please try again.');
  } finally {
    loadingAction.value = null;
  }
};

const submitReject = async () => {
  if (!selectedReq.value) return;
  if (!rejectReason.value.trim()) {
    alert('Please enter a rejection reason.');
    return;
  }

  const req = selectedReq.value;
  loadingAction.value = req.id;

  try {
    const formData = new FormData();
    formData.append('_method', 'PATCH');
    formData.append('_token', csrfToken.value);
    formData.append('rejection_reason', rejectReason.value);

    const response = await fetch(`/nurse/home-services/${req.id}/reject`, {
      method: 'POST',
      headers: {
        'Accept': 'application/json',
        'X-CSRF-TOKEN': csrfToken.value,
      },
      body: formData
    });

    const data = await response.json().catch(() => ({}));
    if (response.ok) {
      const pIdx = pendingList.value.findIndex(s => s.id === req.id);
      if (pIdx !== -1) pendingList.value.splice(pIdx, 1);

      const aIdx = acceptedList.value.findIndex(s => s.id === req.id);
      if (aIdx !== -1) acceptedList.value.splice(aIdx, 1);

      req.status = 'Rejected';
      req.rejection_reason = rejectReason.value;
      rejectedList.value.unshift(req);
      currentTab.value = 'rejected';
      closeModal();
    } else {
      alert(data.message || 'Could not reject request.');
    }
  } catch (err) {
    alert('Network error. Please try again.');
  } finally {
    loadingAction.value = null;
  }
};

const submitDelete = async () => {
  if (!selectedReq.value) return;
  const req = selectedReq.value;
  loadingAction.value = req.id;

  try {
    const formData = new FormData();
    formData.append('_method', 'DELETE');
    formData.append('_token', csrfToken.value);

    const response = await fetch(`/nurse/home-services/${req.id}`, {
      method: 'POST',
      headers: {
        'Accept': 'application/json',
        'X-CSRF-TOKEN': csrfToken.value,
      },
      body: formData
    });

    if (response.ok) {
      pendingList.value = pendingList.value.filter(s => s.id !== req.id);
      acceptedList.value = acceptedList.value.filter(s => s.id !== req.id);
      approvedList.value = approvedList.value.filter(s => s.id !== req.id);
      completedList.value = completedList.value.filter(s => s.id !== req.id);
      rejectedList.value = rejectedList.value.filter(s => s.id !== req.id);
      closeModal();
    } else {
      const data = await response.json().catch(() => ({}));
      alert(data.message || 'Could not delete request.');
    }
  } catch (err) {
    alert('Network error. Please try again.');
  } finally {
    loadingAction.value = null;
  }
};

const submitClear = async () => {
  loadingAction.value = 'clear';

  try {
    const formData = new FormData();
    formData.append('_token', csrfToken.value);
    formData.append('status', clearScope.value);

    const response = await fetch(`/nurse/home-services/clear`, {
      method: 'POST',
      headers: {
        'Accept': 'application/json',
        'X-CSRF-TOKEN': csrfToken.value,
      },
      body: formData
    });

    if (response.ok) {
      if (clearScope.value === 'Pending') {
        pendingList.value = [];
      } else if (clearScope.value === 'Accepted') {
        acceptedList.value = [];
      } else if (clearScope.value === 'Approved') {
        approvedList.value = [];
      } else if (clearScope.value === 'Completed') {
        completedList.value = [];
      } else if (clearScope.value === 'all') {
        pendingList.value = [];
        acceptedList.value = [];
        approvedList.value = [];
        completedList.value = [];
        rejectedList.value = [];
      }
      closeModal();
    } else {
      const data = await response.json().catch(() => ({}));
      alert(data.message || 'Could not clear requests.');
    }
  } catch (err) {
    alert('Network error. Please try again.');
  } finally {
    loadingAction.value = null;
  }
};

const handleNewContactInput = (event) => {
  let val = (event.target.value || '').replace(/\D/g, '');
  if (val.length >= 1 && val[0] !== '0') {
    val = '';
  } else if (val.length >= 2 && val.substring(0, 2) !== '09') {
    val = '0';
  }
  newForm.value.contact_number = val.slice(0, 11);
};

const submitNewRequest = async () => {
  if (!newForm.value.patient_name || !newForm.value.contact_number || !newForm.value.preferred_date || !newForm.value.preferred_time || !newForm.value.address) {
    alert('Please fill in all required fields.');
    return;
  }

  if (!newForm.value.contact_number.startsWith('09') || newForm.value.contact_number.length !== 11) {
    alert('Contact number must be an 11-digit Philippine phone number starting with 09 (e.g. 09123456789).');
    return;
  }

  loadingAction.value = 'new';

  try {
    const formData = new FormData();
    formData.append('_token', csrfToken.value);
    formData.append('patient_name', newForm.value.patient_name);
    formData.append('service_type', newForm.value.service_type);
    formData.append('contact_number', newForm.value.contact_number);
    formData.append('preferred_date', newForm.value.preferred_date);
    formData.append('preferred_time', newForm.value.preferred_time);
    formData.append('address', newForm.value.address);
    formData.append('reason_symptoms', newForm.value.reason_symptoms || '');

    const response = await fetch(`/nurse/home-services`, {
      method: 'POST',
      headers: {
        'Accept': 'application/json',
        'X-CSRF-TOKEN': csrfToken.value,
      },
      body: formData
    });

    if (response.ok) {
      const createdItem = {
        id: Date.now(),
        patient_name: newForm.value.patient_name,
        service_type: newForm.value.service_type,
        contact_number: newForm.value.contact_number,
        preferred_date: newForm.value.preferred_date,
        preferred_time: newForm.value.preferred_time,
        address: newForm.value.address,
        reason_symptoms: newForm.value.reason_symptoms,
        status: 'Pending',
        formatted_id: `HS-${String(Date.now()).slice(-3)}`
      };
      pendingList.value.unshift(createdItem);
      currentTab.value = 'pending';
      closeModal();
    } else {
      const data = await response.json().catch(() => ({}));
      alert(data.message || 'Could not create home service request.');
    }
  } catch (err) {
    alert('Network error. Please try again.');
  } finally {
    loadingAction.value = null;
  }
};
</script>

<style scoped>
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
}
.hs-modal-card {
  background: #ffffff;
  border-radius: 16px;
  width: 100%;
  max-width: 580px;
  max-height: 90vh;
  overflow-y: auto;
  box-shadow: 0 25px 60px rgba(0, 0, 0, 0.2);
}
</style>
