<template>
  <div class="space-y-6">
    <!-- Header Metrics -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
      <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm flex items-center justify-between">
        <div>
          <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Pending Deliveries</p>
          <p class="text-2xl font-black text-amber-600">{{ counts.pending }}</p>
        </div>
        <div class="w-11 h-11 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center">
          <span class="material-icons-outlined" style="font-size: 22px;">pending_actions</span>
        </div>
      </div>

      <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm flex items-center justify-between">
        <div>
          <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Accepted (For Consultation)</p>
          <p class="text-2xl font-black text-blue-600">{{ counts.accepted }}</p>
        </div>
        <div class="w-11 h-11 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center">
          <span class="material-icons-outlined" style="font-size: 22px;">medical_services</span>
        </div>
      </div>

      <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm flex items-center justify-between">
        <div>
          <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Approved Deliveries</p>
          <p class="text-2xl font-black text-emerald-600">{{ counts.approved }}</p>
        </div>
        <div class="w-11 h-11 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
          <span class="material-icons-outlined" style="font-size: 22px;">check_circle</span>
        </div>
      </div>

      <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm flex items-center justify-between">
        <div>
          <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Rejected</p>
          <p class="text-2xl font-black text-rose-600">{{ counts.rejected }}</p>
        </div>
        <div class="w-11 h-11 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center">
          <span class="material-icons-outlined" style="font-size: 22px;">cancel</span>
        </div>
      </div>

      <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm flex items-center justify-between">
        <div>
          <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Total Deliveries</p>
          <p class="text-2xl font-black text-gray-800">{{ counts.all }}</p>
        </div>
        <div class="w-11 h-11 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center">
          <span class="material-icons-outlined" style="font-size: 22px;">local_shipping</span>
        </div>
      </div>
    </div>

    <!-- Alert / Toast -->
    <div v-if="feedbackMessage" 
         :class="['p-4 rounded-xl text-sm font-semibold flex items-center justify-between shadow-sm border transition-all', 
                  feedbackType === 'success' ? 'bg-emerald-50 text-emerald-800 border-emerald-200' : 'bg-rose-50 text-rose-800 border-rose-200']">
      <div class="flex items-center gap-2">
        <span class="material-icons-outlined" style="font-size: 20px;">
          {{ feedbackType === 'success' ? 'check_circle' : 'error_outline' }}
        </span>
        <span>{{ feedbackMessage }}</span>
      </div>
      <button @click="feedbackMessage = ''" class="text-gray-400 hover:text-gray-600">
        <span class="material-icons-outlined" style="font-size: 16px;">close</span>
      </button>
    </div>

    <!-- Main Card -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
      <!-- Toolbar: Tabs & Search -->
      <div class="p-6 border-b border-gray-100 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <!-- Status Tabs -->
        <div class="flex items-center gap-2 overflow-x-auto pb-1 md:pb-0">
          <button @click="currentFilter = 'Pending'"
                  :class="['px-4 py-2 rounded-xl text-xs font-bold transition-all flex items-center gap-1.5',
                           currentFilter === 'Pending' ? 'bg-[#0c2d48] text-white shadow-md' : 'bg-gray-100 text-gray-600 hover:bg-gray-200']">
            <span class="material-icons-outlined" style="font-size: 16px;">hourglass_top</span>
            <span>Pending</span>
            <span :class="['px-1.5 py-0.5 rounded-full text-[10px]', currentFilter === 'Pending' ? 'bg-white/20 text-white' : 'bg-white text-gray-700']">
              {{ counts.pending }}
            </span>
          </button>

          <button @click="currentFilter = 'Accepted'"
                  :class="['px-4 py-2 rounded-xl text-xs font-bold transition-all flex items-center gap-1.5',
                           currentFilter === 'Accepted' ? 'bg-[#0c2d48] text-white shadow-md' : 'bg-gray-100 text-gray-600 hover:bg-gray-200']">
            <span class="material-icons-outlined" style="font-size: 16px;">assignment_turned_in</span>
            <span>Accepted</span>
            <span :class="['px-1.5 py-0.5 rounded-full text-[10px]', currentFilter === 'Accepted' ? 'bg-white/20 text-white' : 'bg-white text-gray-700']">
              {{ counts.accepted }}
            </span>
          </button>

          <button @click="currentFilter = 'Approved'"
                  :class="['px-4 py-2 rounded-xl text-xs font-bold transition-all flex items-center gap-1.5',
                           currentFilter === 'Approved' ? 'bg-[#0c2d48] text-white shadow-md' : 'bg-gray-100 text-gray-600 hover:bg-gray-200']">
            <span class="material-icons-outlined" style="font-size: 16px;">task_alt</span>
            <span>Approved (Out for Delivery)</span>
            <span :class="['px-1.5 py-0.5 rounded-full text-[10px]', currentFilter === 'Approved' ? 'bg-white/20 text-white' : 'bg-white text-gray-700']">
              {{ counts.approved }}
            </span>
          </button>

          <button @click="currentFilter = 'Rejected'"
                  :class="['px-4 py-2 rounded-xl text-xs font-bold transition-all flex items-center gap-1.5',
                           currentFilter === 'Rejected' ? 'bg-[#0c2d48] text-white shadow-md' : 'bg-gray-100 text-gray-600 hover:bg-gray-200']">
            <span class="material-icons-outlined" style="font-size: 16px;">block</span>
            <span>Rejected</span>
            <span :class="['px-1.5 py-0.5 rounded-full text-[10px]', currentFilter === 'Rejected' ? 'bg-white/20 text-white' : 'bg-white text-gray-700']">
              {{ counts.rejected }}
            </span>
          </button>

          <button @click="currentFilter = 'all'"
                  :class="['px-4 py-2 rounded-xl text-xs font-bold transition-all flex items-center gap-1.5',
                           currentFilter === 'all' ? 'bg-[#0c2d48] text-white shadow-md' : 'bg-gray-100 text-gray-600 hover:bg-gray-200']">
            <span class="material-icons-outlined" style="font-size: 16px;">list_alt</span>
            <span>All Deliveries</span>
            <span :class="['px-1.5 py-0.5 rounded-full text-[10px]', currentFilter === 'all' ? 'bg-white/20 text-white' : 'bg-white text-gray-700']">
              {{ counts.all }}
            </span>
          </button>
        </div>

        <!-- Search input -->
        <div class="relative w-full md:w-72">
          <span class="material-icons-outlined absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" style="font-size: 18px;">search</span>
          <input type="text" 
                 v-model="searchQuery" 
                 placeholder="Search patient, medicine, address..." 
                 class="w-full pl-9 pr-8 py-2 bg-gray-50 border border-gray-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500 transition-all">
          <button v-if="searchQuery" @click="searchQuery = ''" class="absolute right-2.5 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
            <span class="material-icons-outlined" style="font-size: 16px;">close</span>
          </button>
        </div>
      </div>

      <!-- Notice Banner on Delivery Only Scope -->
      <div class="bg-indigo-50/70 border-b border-indigo-100 px-6 py-2.5 flex items-center justify-between text-xs text-indigo-900">
        <div class="flex items-center gap-2">
          <span class="material-icons-outlined text-indigo-600" style="font-size: 18px;">local_shipping</span>
          <span><strong>BHW View:</strong> Only medicine requests with <strong>Home Delivery</strong> are shown here for dispatch and neighborhood delivery.</span>
        </div>
        <span class="text-[11px] text-indigo-600 font-semibold bg-indigo-100/60 px-2 py-0.5 rounded-full">
          Pickup requests are handled at Center by Nurse
        </span>
      </div>

      <!-- Delivery Requests Table -->
      <div v-if="filteredRequests.length === 0" class="text-center py-16 text-gray-400">
        <span class="material-icons-outlined mb-2 text-gray-300" style="font-size: 56px;">local_shipping</span>
        <p class="font-semibold text-gray-700 text-base">No medicine deliveries found</p>
        <p class="text-xs text-gray-400 mt-1 max-w-sm mx-auto">
          <span v-if="searchQuery">No delivery requests matched "{{ searchQuery }}". Try clearing your search.</span>
          <span v-else>There are currently no {{ currentFilter === 'all' ? '' : currentFilter.toLowerCase() }} delivery requests.</span>
        </p>
      </div>

      <div v-else class="overflow-x-auto">
        <table class="w-full text-left text-xs">
          <thead>
            <tr class="border-b border-gray-100 bg-gray-50/60 text-gray-500 uppercase tracking-wider font-semibold">
              <th class="py-3.5 px-6">Request ID</th>
              <th class="py-3.5 px-6">Patient & Contact</th>
              <th class="py-3.5 px-6">Delivery Address</th>
              <th class="py-3.5 px-6">Medicine & Quantity</th>
              <th class="py-3.5 px-6">Fulfillment</th>
              <th class="py-3.5 px-6">Requested At</th>
              <th class="py-3.5 px-6">Status</th>
              <th class="py-3.5 px-6 text-right">Actions</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-100">
            <tr v-for="req in filteredRequests" :key="req.id" class="hover:bg-blue-50/20 transition-colors">
              <!-- ID -->
              <td class="py-4 px-6 font-bold text-blue-600 whitespace-nowrap">
                {{ req.request_id || `REQ-${String(req.id).padStart(3, '0')}` }}
              </td>

              <!-- Patient -->
              <td class="py-4 px-6">
                <div class="font-bold text-gray-800">
                  {{ req.user ? `${req.user.first_name} ${req.user.last_name}` : 'Unknown Resident' }}
                </div>
                <div v-if="req.patient_type === 'other' && req.patient_name" class="text-[11px] font-semibold text-purple-700 mt-0.5 flex items-center gap-1">
                  <span class="material-icons-outlined" style="font-size: 13px;">person</span>
                  <span>For: {{ req.patient_name }} ({{ req.patient_age }}y &bull; {{ req.patient_relationship }})</span>
                </div>
                <div v-else class="text-[11px] text-gray-500 mt-0.5">
                  <span>For: Self ({{ req.user?.age ? `${req.user.age} yrs` : 'Resident' }})</span>
                </div>
                <div class="text-[11px] text-gray-400 mt-0.5 flex items-center gap-1">
                  <span class="material-icons-outlined" style="font-size: 13px;">phone</span>
                  {{ req.user?.phone_number || req.user?.contact_number || 'No contact provided' }}
                </div>
              </td>

              <!-- Delivery Address -->
              <td class="py-4 px-6 max-w-xs">
                <div class="flex items-start gap-1.5 text-gray-700 font-medium">
                  <span class="material-icons-outlined text-rose-500 shrink-0 mt-0.5" style="font-size: 14px;">location_on</span>
                  <span class="line-clamp-2" :title="req.user?.address || 'No address specified'">
                    {{ req.user?.address || 'Address not registered' }}
                  </span>
                </div>
                <div v-if="req.notes" class="text-[11px] text-gray-500 italic mt-1 bg-amber-50/70 p-1.5 rounded border border-amber-100/60" :title="req.notes">
                  "{{ req.notes }}"
                </div>
              </td>

              <!-- Medicine & Quantity -->
              <td class="py-4 px-6">
                <div class="font-bold text-gray-800 flex items-center gap-1.5">
                  <span class="material-icons-outlined text-blue-500" style="font-size: 16px;">medication</span>
                  {{ req.item_service }}
                </div>
                <div class="text-[11px] text-gray-500 mt-0.5 flex items-center gap-1.5">
                  <span>Qty: <strong class="text-gray-800">{{ req.quantity ?? 1 }}</strong></span>
                  <span v-if="req.medicine" class="text-gray-400">({{ req.medicine.stock }} in stock)</span>
                  <button v-if="req.status === 'Pending' || req.status === 'Accepted'"
                          @click="openEditQtyModal(req)"
                          type="button"
                          class="text-gray-400 hover:text-blue-600 transition-colors p-0.5 rounded hover:bg-blue-50 cursor-pointer"
                          title="Edit Quantity">
                    <span class="material-icons-outlined" style="font-size: 14px;">edit</span>
                  </button>
                </div>
              </td>

              <!-- Fulfillment Badge (Strictly Delivery) -->
              <td class="py-4 px-6 whitespace-nowrap">
                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-bold bg-indigo-50 text-indigo-700 border border-indigo-200">
                  <span class="material-icons-outlined" style="font-size: 13px;">local_shipping</span>
                  Home Delivery
                </span>
              </td>

              <!-- Date -->
              <td class="py-4 px-6 text-gray-500 whitespace-nowrap">
                <div>{{ formatDate(req.created_at) }}</div>
                <div class="text-[11px] text-gray-400">{{ formatTime(req.created_at) }}</div>
              </td>

              <!-- Status Badge -->
              <td class="py-4 px-6 whitespace-nowrap">
                <span v-if="req.status === 'Pending'" class="px-2.5 py-1 rounded-full text-[11px] font-bold bg-amber-50 text-amber-700 border border-amber-200 inline-flex items-center gap-1">
                  <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                  Pending
                </span>
                <span v-else-if="req.status === 'Accepted'" class="px-2.5 py-1 rounded-full text-[11px] font-bold bg-blue-50 text-blue-700 border border-blue-200 inline-flex items-center gap-1">
                  <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span>
                  Accepted (For Consultation)
                </span>
                <span v-else-if="req.status === 'Approved'" class="px-2.5 py-1 rounded-full text-[11px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200 inline-flex items-center gap-1">
                  <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                  Approved (Delivery Ready)
                </span>
                <span v-else-if="req.status === 'Rejected'" class="px-2.5 py-1 rounded-full text-[11px] font-bold bg-rose-50 text-rose-700 border border-rose-200 inline-flex items-center gap-1">
                  <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                  Rejected
                </span>
                <span v-else class="px-2.5 py-1 rounded-full text-[11px] font-bold bg-gray-100 text-gray-700">
                  {{ req.status }}
                </span>
              </td>

              <!-- Actions -->
              <td class="py-4 px-6 text-right whitespace-nowrap">
                <!-- If Pending: Edit, Accept for consultation or Reject -->
                <div v-if="req.status === 'Pending'" class="flex items-center justify-end gap-2">
                  <button @click="openEditQtyModal(req)"
                          type="button"
                          class="px-2.5 py-1.5 bg-amber-50 hover:bg-amber-100 text-amber-700 font-bold rounded-lg transition-all border border-amber-200 flex items-center gap-1 cursor-pointer"
                          title="Edit quantity">
                    <span class="material-icons-outlined" style="font-size: 14px;">edit</span>
                    <span>Edit</span>
                  </button>

                  <button @click="handleAction(req, 'accept')"
                          :disabled="actionLoadingId === req.id"
                          class="px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-lg transition-all shadow-sm flex items-center gap-1 cursor-pointer disabled:opacity-50"
                          title="Accept delivery request and notify resident for consultation">
                    <span class="material-icons-outlined" style="font-size: 14px;">assignment_turned_in</span>
                    <span>Accept</span>
                  </button>

                  <button @click="handleAction(req, 'reject')"
                          :disabled="actionLoadingId === req.id"
                          class="px-2.5 py-1.5 bg-rose-50 hover:bg-rose-100 text-rose-600 font-bold rounded-lg transition-all border border-rose-200 flex items-center gap-1 cursor-pointer disabled:opacity-50"
                          title="Reject request">
                    <span class="material-icons-outlined" style="font-size: 14px;">close</span>
                    <span>Reject</span>
                  </button>
                </div>

                <!-- If Accepted: Edit, Approve for delivery or Reject -->
                <div v-else-if="req.status === 'Accepted'" class="flex items-center justify-end gap-2">
                  <button @click="openEditQtyModal(req)"
                          type="button"
                          class="px-2.5 py-1.5 bg-amber-50 hover:bg-amber-100 text-amber-700 font-bold rounded-lg transition-all border border-amber-200 flex items-center gap-1 cursor-pointer"
                          title="Edit quantity">
                    <span class="material-icons-outlined" style="font-size: 14px;">edit</span>
                    <span>Edit</span>
                  </button>

                  <button @click="handleAction(req, 'approve')"
                          :disabled="actionLoadingId === req.id"
                          class="px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-lg transition-all shadow-sm flex items-center gap-1 cursor-pointer disabled:opacity-50"
                          title="Approve and mark out for home delivery">
                    <span class="material-icons-outlined" style="font-size: 14px;">local_shipping</span>
                    <span>Approve Delivery</span>
                  </button>

                  <button @click="handleAction(req, 'reject')"
                          :disabled="actionLoadingId === req.id"
                          class="px-2.5 py-1.5 bg-rose-50 hover:bg-rose-100 text-rose-600 font-bold rounded-lg transition-all border border-rose-200 flex items-center gap-1 cursor-pointer disabled:opacity-50"
                          title="Reject request">
                    <span class="material-icons-outlined" style="font-size: 14px;">close</span>
                    <span>Reject</span>
                  </button>
                </div>

                <!-- If Approved: Ready for Home Delivery Completion / Claiming -->
                <div v-else-if="req.status === 'Approved'" class="flex items-center justify-end gap-2">
                  <button @click="openClaimModal(req)"
                          type="button"
                          class="px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-lg transition-all shadow-sm flex items-center gap-1 cursor-pointer"
                          title="Record claiming proof, who received, and print receipt">
                    <span class="material-icons-outlined" style="font-size: 14px;">task_alt</span>
                    <span>Complete Delivery</span>
                  </button>
                </div>

                <!-- Processed state: Completed / Claimed with Print Receipt -->
                <div v-else-if="req.status === 'Completed'" class="flex items-center justify-end gap-2">
                  <button @click="printReceipt(req)"
                          type="button"
                          class="px-2.5 py-1.5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold rounded-lg transition-all border border-gray-200 flex items-center gap-1 cursor-pointer"
                          title="Print delivery transaction receipt">
                    <span class="material-icons-outlined" style="font-size: 14px;">print</span>
                    <span>Receipt</span>
                  </button>
                  <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-green-100 text-green-800">Claimed</span>
                </div>

                <!-- Other state -->
                <div v-else class="text-gray-400 italic text-[11px]">
                  <span>{{ req.status }}</span>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Edit Quantity Modal -->
    <div v-if="editingRequest" class="fixed inset-0 z-50 bg-black/50 backdrop-blur-xs flex items-center justify-center p-4">
      <div class="bg-white rounded-2xl shadow-xl w-full max-w-sm overflow-hidden animate-in fade-in zoom-in-95 duration-150">
        <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
          <div class="flex items-center gap-2">
            <span class="material-icons-outlined text-amber-500" style="font-size:20px;">edit_note</span>
            <h3 class="font-bold text-gray-800 text-sm">Edit Requested Quantity</h3>
          </div>
          <button type="button" @click="closeEditQtyModal" class="text-gray-400 hover:text-gray-600 cursor-pointer">
            <span class="material-icons-outlined" style="font-size:18px;">close</span>
          </button>
        </div>

        <div class="p-5 space-y-4">
          <div>
            <p class="text-xs text-gray-500 mb-1">Medicine / Item</p>
            <p class="font-bold text-gray-800 text-sm">{{ editingRequest.item_service }}</p>
            <p class="text-[11px] text-gray-400 mt-0.5">
              Current limit: {{ editingRequest.medicine?.request_limit || 5 }} {{ editingRequest.medicine?.unit || 'units' }}
            </p>
          </div>

          <div>
            <label class="block text-xs font-semibold text-gray-700 mb-1">Quantity</label>
            <input type="number" 
                   v-model.number="editQtyValue" 
                   min="1" 
                   :max="maxAllowedQty"
                   class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500">
          </div>

          <div v-if="editQtyError" class="text-xs text-rose-600 font-medium">
            {{ editQtyError }}
          </div>

          <div class="flex items-center justify-end gap-2 pt-2 border-t border-gray-100">
            <button type="button" @click="closeEditQtyModal" class="px-3.5 py-1.5 text-xs font-semibold text-gray-600 hover:bg-gray-100 rounded-lg transition-colors cursor-pointer">
              Cancel
            </button>
            <button type="button" 
                    @click="saveQuantity"
                    :disabled="isSavingQty"
                    class="px-4 py-1.5 text-xs font-semibold bg-blue-600 hover:bg-blue-700 text-white rounded-lg shadow-sm transition-all cursor-pointer disabled:opacity-50">
              {{ isSavingQty ? 'Saving...' : 'Save Quantity' }}
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- COMPLETE DELIVERY & CLAIMING MODAL -->
    <div v-if="claimingRequest" class="fixed inset-0 z-50 bg-black/50 backdrop-blur-xs flex items-center justify-center p-4">
      <div class="bg-white rounded-2xl shadow-xl w-full max-w-md overflow-hidden animate-in fade-in zoom-in-95 duration-150">
        <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between bg-blue-50/50">
          <div class="flex items-center gap-2 text-[#0c2d48]">
            <span class="material-icons-outlined text-blue-600" style="font-size:20px;">verified</span>
            <h3 class="font-bold text-sm">Record Delivery Completion & Claiming</h3>
          </div>
          <button type="button" @click="closeClaimModal" class="text-gray-400 hover:text-gray-600 cursor-pointer">
            <span class="material-icons-outlined" style="font-size:18px;">close</span>
          </button>
        </div>

        <form @submit.prevent="submitClaimForm" class="p-5 space-y-4">
          <div class="bg-gray-50 p-3 rounded-xl border border-gray-100 text-xs text-gray-700 space-y-1">
            <div class="flex justify-between">
              <span class="text-gray-400">Medicine:</span>
              <span class="font-bold text-gray-800">{{ claimingRequest.item_service }} (x{{ claimingRequest.quantity }})</span>
            </div>
            <div class="flex justify-between">
              <span class="text-gray-400">Recipient Resident:</span>
              <span class="font-semibold">{{ claimingRequest.user?.first_name }} {{ claimingRequest.user?.last_name }}</span>
            </div>
            <div v-if="claimingRequest.patient_type === 'other'" class="flex justify-between text-purple-700">
              <span>Patient (Child / Elder):</span>
              <span class="font-bold">{{ claimingRequest.patient_name }} ({{ claimingRequest.patient_relationship }})</span>
            </div>
            <div class="flex justify-between">
              <span class="text-gray-400">Delivery Address:</span>
              <span class="text-right truncate max-w-[200px]" :title="claimingRequest.user?.address">{{ claimingRequest.user?.address || 'No address' }}</span>
            </div>
          </div>

          <div>
            <label class="block text-xs font-semibold text-gray-700 mb-1">Delivered By (BHW / Healthcare Worker) *</label>
            <input type="text" v-model="claimForm.delivered_by" required placeholder="e.g. BHW Elena Gomez"
                   class="w-full px-3 py-2 border border-gray-200 rounded-lg text-xs focus:ring-2 focus:ring-blue-500/30">
          </div>

          <div class="grid grid-cols-2 gap-3">
            <div>
              <label class="block text-xs font-semibold text-gray-700 mb-1">Received / Claimed By *</label>
              <input type="text" v-model="claimForm.claimed_by" required placeholder="e.g. Maria Santos"
                     class="w-full px-3 py-2 border border-gray-200 rounded-lg text-xs focus:ring-2 focus:ring-blue-500/30">
            </div>
            <div>
              <label class="block text-xs font-semibold text-gray-700 mb-1">Relationship to Patient *</label>
              <select v-model="claimForm.claimed_relationship" required
                      class="w-full px-3 py-2 border border-gray-200 rounded-lg text-xs focus:ring-2 focus:ring-blue-500/30">
                <option value="Self (Resident)">Self (Resident)</option>
                <option value="Mother / Parent">Mother / Parent</option>
                <option value="Father / Parent">Father / Parent</option>
                <option value="Spouse / Partner">Spouse / Partner</option>
                <option value="Child / Son / Daughter">Child / Son / Daughter</option>
                <option value="Authorized Representative / Relative">Authorized Relative</option>
              </select>
            </div>
          </div>

          <!-- Two Signature Pads Grid: Resident & Delivery Staff -->
          <div class="space-y-3">
            <!-- 1. Resident / Claimant Signature -->
            <div>
              <div class="flex items-center justify-between mb-1">
                <label class="block text-xs font-bold text-gray-700 flex items-center gap-1.5">
                  <span class="material-icons-outlined text-blue-600" style="font-size:15px;">draw</span>
                  <span>1. Resident / Claimant Signature *</span>
                </label>
                <button type="button" @click="clearResidentSig" class="text-[11px] font-bold text-rose-600 hover:text-rose-800 flex items-center gap-0.5 cursor-pointer">
                  <span class="material-icons-outlined" style="font-size:13px;">refresh</span>
                  <span>Clear</span>
                </button>
              </div>

              <div class="relative border-2 border-dashed border-gray-300 rounded-xl overflow-hidden bg-white hover:border-blue-400 transition-colors">
                <canvas ref="residentCanvas" 
                        width="380" 
                        height="100" 
                        class="w-full h-24 block touch-none cursor-crosshair bg-[#fcfdfd]"
                        @mousedown="startResDrawing" 
                        @mousemove="drawRes" 
                        @mouseup="stopResDrawing" 
                        @mouseleave="stopResDrawing"
                        @touchstart.prevent="startTouchResDrawing" 
                        @touchmove.prevent="touchDrawRes" 
                        @touchend.prevent="stopResDrawing">
                </canvas>
                
                <div v-if="!hasResidentSig" class="absolute inset-0 flex items-center justify-center pointer-events-none text-gray-400 text-xs gap-1 select-none">
                  <span class="material-icons-outlined" style="font-size:15px;">edit</span>
                  <span>Resident sign here</span>
                </div>
                <div class="absolute bottom-1 right-2 pointer-events-none text-[8px] text-gray-400 font-mono">
                  Claimant
                </div>
              </div>
            </div>

            <!-- 2. Delivery Personnel (BHW) Signature -->
            <div>
              <div class="flex items-center justify-between mb-1">
                <label class="block text-xs font-bold text-gray-700 flex items-center gap-1.5">
                  <span class="material-icons-outlined text-emerald-600" style="font-size:15px;">local_shipping</span>
                  <span>2. Delivery Personnel (BHW) Signature *</span>
                </label>
                <button type="button" @click="clearDeliverySig" class="text-[11px] font-bold text-rose-600 hover:text-rose-800 flex items-center gap-0.5 cursor-pointer">
                  <span class="material-icons-outlined" style="font-size:13px;">refresh</span>
                  <span>Clear</span>
                </button>
              </div>

              <div class="relative border-2 border-dashed border-emerald-200 rounded-xl overflow-hidden bg-white hover:border-emerald-400 transition-colors">
                <canvas ref="deliveryCanvas" 
                        width="380" 
                        height="100" 
                        class="w-full h-24 block touch-none cursor-crosshair bg-[#fcfdfd]"
                        @mousedown="startDelivDrawing" 
                        @mousemove="drawDeliv" 
                        @mouseup="stopDelivDrawing" 
                        @mouseleave="stopDelivDrawing"
                        @touchstart.prevent="startTouchDelivDrawing" 
                        @touchmove.prevent="touchDrawDeliv" 
                        @touchend.prevent="stopDelivDrawing">
                </canvas>
                
                <div v-if="!hasDeliverySig" class="absolute inset-0 flex items-center justify-center pointer-events-none text-gray-400 text-xs gap-1 select-none">
                  <span class="material-icons-outlined" style="font-size:15px;">edit</span>
                  <span>Delivery worker sign here</span>
                </div>
                <div class="absolute bottom-1 right-2 pointer-events-none text-[8px] text-gray-400 font-mono">
                  Delivered By
                </div>
              </div>
            </div>
          </div>

          <div class="flex items-center justify-end gap-2 pt-3 border-t border-gray-100">
            <button type="button" @click="closeClaimModal" class="px-3.5 py-2 text-xs font-semibold text-gray-600 hover:bg-gray-100 rounded-lg">
              Cancel
            </button>
            <button type="submit" :disabled="isSubmittingClaim"
                    class="px-4 py-2 text-xs font-bold bg-blue-600 hover:bg-blue-700 text-white rounded-lg shadow-sm flex items-center gap-1.5 cursor-pointer disabled:opacity-50">
              <span class="material-icons-outlined" style="font-size:16px;">check</span>
              <span>{{ isSubmittingClaim ? 'Submitting...' : 'Complete & Generate Receipt' }}</span>
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';

const props = defineProps({
  medicineRequests: {
    type: Array,
    default: () => []
  }
});

const requestsList = ref([]);
const currentFilter = ref('Pending');
const searchQuery = ref('');
const actionLoadingId = ref(null);
const feedbackMessage = ref('');
const feedbackType = ref('success');

const csrfToken = ref('');

// Two Signatures State: Resident & Delivery Staff
const claimingRequest = ref(null);
const isSubmittingClaim = ref(false);

const residentCanvas = ref(null);
const isResDrawing = ref(false);
const hasResidentSig = ref(false);

const deliveryCanvas = ref(null);
const isDelivDrawing = ref(false);
const hasDeliverySig = ref(false);

const claimForm = ref({
  delivered_by: '',
  claimed_by: '',
  claimed_relationship: 'Self (Resident)',
});

function initSignatureCanvas() {
  hasResidentSig.value = false;
  hasDeliverySig.value = false;

  setTimeout(() => {
    [residentCanvas.value, deliveryCanvas.value].forEach(canvas => {
      if (canvas) {
        const ctx = canvas.getContext('2d');
        ctx.clearRect(0, 0, canvas.width, canvas.height);
        ctx.strokeStyle = '#0c2d48';
        ctx.lineWidth = 2.2;
        ctx.lineCap = 'round';
        ctx.lineJoin = 'round';
      }
    });
  }, 100);
}

function clearResidentSig() {
  if (residentCanvas.value) {
    const ctx = residentCanvas.value.getContext('2d');
    ctx.clearRect(0, 0, residentCanvas.value.width, residentCanvas.value.height);
    hasResidentSig.value = false;
  }
}

function clearDeliverySig() {
  if (deliveryCanvas.value) {
    const ctx = deliveryCanvas.value.getContext('2d');
    ctx.clearRect(0, 0, deliveryCanvas.value.width, deliveryCanvas.value.height);
    hasDeliverySig.value = false;
  }
}

function getCoords(e, canvas) {
  const rect = canvas.getBoundingClientRect();
  return {
    x: (e.clientX - rect.left) * (canvas.width / rect.width),
    y: (e.clientY - rect.top) * (canvas.height / rect.height),
  };
}

// 1. Resident Canvas drawing handlers
function startResDrawing(e) {
  if (!residentCanvas.value) return;
  const ctx = residentCanvas.value.getContext('2d');
  isResDrawing.value = true;
  hasResidentSig.value = true;
  const c = getCoords(e, residentCanvas.value);
  ctx.beginPath();
  ctx.moveTo(c.x, c.y);
}
function drawRes(e) {
  if (!isResDrawing.value || !residentCanvas.value) return;
  const ctx = residentCanvas.value.getContext('2d');
  const c = getCoords(e, residentCanvas.value);
  ctx.lineTo(c.x, c.y);
  ctx.stroke();
}
function stopResDrawing() { isResDrawing.value = false; }
function startTouchResDrawing(e) {
  if (e.touches.length > 0 && residentCanvas.value) startResDrawing(e.touches[0]);
}
function touchDrawRes(e) {
  if (e.touches.length > 0 && residentCanvas.value) drawRes(e.touches[0]);
}

// 2. Delivery Canvas drawing handlers
function startDelivDrawing(e) {
  if (!deliveryCanvas.value) return;
  const ctx = deliveryCanvas.value.getContext('2d');
  isDelivDrawing.value = true;
  hasDeliverySig.value = true;
  const c = getCoords(e, deliveryCanvas.value);
  ctx.beginPath();
  ctx.moveTo(c.x, c.y);
}
function drawDeliv(e) {
  if (!isDelivDrawing.value || !deliveryCanvas.value) return;
  const ctx = deliveryCanvas.value.getContext('2d');
  const c = getCoords(e, deliveryCanvas.value);
  ctx.lineTo(c.x, c.y);
  ctx.stroke();
}
function stopDelivDrawing() { isDelivDrawing.value = false; }
function startTouchDelivDrawing(e) {
  if (e.touches.length > 0 && deliveryCanvas.value) startDelivDrawing(e.touches[0]);
}
function touchDrawDeliv(e) {
  if (e.touches.length > 0 && deliveryCanvas.value) drawDeliv(e.touches[0]);
}

function openClaimModal(req) {
  claimingRequest.value = req;
  const defaultClaimant = req.patient_type === 'other' && req.patient_name
    ? req.patient_name
    : `${req.user?.first_name || ''} ${req.user?.last_name || ''}`.trim();

  claimForm.value = {
    delivered_by: 'BHW Elena Gomez',
    claimed_by: defaultClaimant,
    claimed_relationship: req.patient_type === 'other' ? (req.patient_relationship || 'Parent') : 'Self (Resident)',
  };
  initSignatureCanvas();
}

function closeClaimModal() {
  claimingRequest.value = null;
  clearResidentSig();
  clearDeliverySig();
}

async function submitClaimForm() {
  if (!claimingRequest.value) return;

  const residentSigBase64 = (residentCanvas.value && hasResidentSig.value) 
    ? residentCanvas.value.toDataURL('image/png') 
    : null;

  const deliverySigBase64 = (deliveryCanvas.value && hasDeliverySig.value)
    ? deliveryCanvas.value.toDataURL('image/png')
    : null;

  isSubmittingClaim.value = true;

  try {
    const formData = new FormData();
    formData.append('_token', csrfToken.value);
    formData.append('delivered_by', claimForm.value.delivered_by);
    formData.append('claimed_by', claimForm.value.claimed_by);
    formData.append('claimed_relationship', claimForm.value.claimed_relationship);
    if (residentSigBase64) {
      formData.append('signature_data', residentSigBase64);
    }
    if (deliverySigBase64) {
      formData.append('delivery_signature', deliverySigBase64);
    }

    const res = await fetch(`/nurse/requests/${claimingRequest.value.id}/complete`, {
      method: 'POST',
      headers: {
        'Accept': 'application/json',
        'X-CSRF-TOKEN': csrfToken.value,
      },
      body: formData,
    });

    const data = await res.json();
    if (res.ok && data.success) {
      feedbackType.value = 'success';
      feedbackMessage.value = data.message || 'Delivery marked as completed & claimed!';

      const targetId = claimingRequest.value.id;
      const updatedItem = data.data || {
        ...claimingRequest.value,
        status: 'Completed',
        delivered_by: claimForm.value.delivered_by,
        claimed_by: claimForm.value.claimed_by,
        claimed_relationship: claimForm.value.claimed_relationship,
        claimed_at: new Date().toISOString(),
        receipt_number: `RCPT-${new Date().getFullYear()}-${targetId}`,
        proof_photo: residentSigBase64,
        delivery_signature: deliverySigBase64,
      };

      const idx = requestsList.value.findIndex(r => r.id === targetId);
      if (idx !== -1) {
        requestsList.value[idx] = updatedItem;
      }

      const completedReq = { 
        ...updatedItem, 
        signature_image: residentSigBase64,
        delivery_sig_image: deliverySigBase64 
      };
      closeClaimModal();

      // Trigger printable receipt with both signatures
      if (confirm('Delivery recorded with both Resident and Delivery signatures! Would you like to print the official delivery receipt now?')) {
        printReceipt(completedReq);
      }
    } else {
      alert(data.message || 'Failed to complete delivery.');
    }
  } catch (err) {
    console.error(err);
    alert('An error occurred while completing delivery.');
  } finally {
    isSubmittingClaim.value = false;
  }
}

// Print Official Transaction Receipt
function printReceipt(req) {
  const receiptNo = req.receipt_number || `RCPT-${req.id}`;
  const deliveredBy = req.delivered_by || 'BHW / Health Center Staff';
  const claimedBy = req.claimed_by || `${req.user?.first_name || ''} ${req.user?.last_name || ''}`;
  const relation = req.claimed_relationship || 'Self';
  const med = req.item_service || 'Medicine';
  const qty = req.quantity || 1;
  const address = req.user?.address || 'Barangay Honorato';
  const dateStr = req.claimed_at ? new Date(req.claimed_at).toLocaleString() : new Date().toLocaleString();

  const printWindow = window.open('', '_blank', 'width=650,height=750');
  if (!printWindow) return;

  printWindow.document.write(`
    <!DOCTYPE html>
    <html>
      <head>
        <title>Delivery Receipt - ${receiptNo}</title>
        <style>
          body { font-family: 'Helvetica Neue', Arial, sans-serif; padding: 25px; color: #1e293b; }
          .receipt-box { border: 2px dashed #0c2d48; padding: 25px; border-radius: 12px; max-width: 550px; margin: auto; }
          .header { text-align: center; border-bottom: 2px solid #0c2d48; padding-bottom: 15px; margin-bottom: 20px; }
          .header h2 { margin: 0; font-size: 20px; color: #0c2d48; text-transform: uppercase; }
          .header p { margin: 3px 0 0; font-size: 12px; color: #64748b; }
          .badge { display: inline-block; background: #ecfdf5; color: #059669; padding: 4px 10px; border-radius: 20px; font-weight: bold; font-size: 11px; margin-top: 8px; border: 1px solid #a7f3d0; }
          .row { display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid #f1f5f9; font-size: 13px; }
          .row .label { color: #64748b; font-weight: 500; }
          .row .value { font-weight: 700; color: #0f172a; text-align: right; }
          .med-section { background: #f8fafc; padding: 12px 15px; border-radius: 8px; margin: 15px 0; border: 1px solid #e2e8f0; }
          .signatures { display: flex; justify-content: space-between; margin-top: 35px; padding-top: 20px; }
          .sig-box { width: 45%; text-align: center; }
          .sig-line { border-top: 1px solid #94a3b8; margin-top: 40px; padding-top: 5px; font-size: 11px; font-weight: bold; }
          .footer { text-align: center; font-size: 10px; color: #94a3b8; margin-top: 25px; }
        </style>
      </head>
      <body>
        <div class="receipt-box">
          <div class="header">
            <h2>Barangay Honorato Health Center</h2>
            <p>Official Medicine Home Delivery & Claiming Receipt</p>
            <div class="badge">OFFICIALLY CLAIMED / DELIVERED</div>
          </div>

          <div class="row">
            <span class="label">Receipt Number:</span>
            <span class="value">${receiptNo}</span>
          </div>
          <div class="row">
            <span class="label">Transaction Date & Time:</span>
            <span class="value">${dateStr}</span>
          </div>
          <div class="row">
            <span class="label">Delivery Address:</span>
            <span class="value">${address}</span>
          </div>

          <div class="med-section">
            <div class="row" style="border:none; padding:0;">
              <span class="label">Medicine Delivered:</span>
              <span class="value" style="font-size:15px; color:#0c2d48;">${med}</span>
            </div>
            <div class="row" style="border:none; padding:4px 0 0;">
              <span class="label">Quantity Delivered:</span>
              <span class="value">${qty} units</span>
            </div>
          </div>

          <div class="row">
            <span class="label">Delivered By (Healthcare Staff):</span>
            <span class="value">${deliveredBy}</span>
          </div>
          <div class="row">
            <span class="label">Received / Claimed By:</span>
            <span class="value">${claimedBy}</span>
          </div>
          <div class="row">
            <span class="label">Claimant Relationship:</span>
            <span class="value">${relation}</span>
          </div>

          <div class="signatures">
            <div class="sig-box">
              ${req.delivery_sig_image || (req.delivery_signature && req.delivery_signature.startsWith('data:image'))
                ? `<img src="${req.delivery_sig_image || req.delivery_signature}" style="max-height: 42px; margin-bottom: -15px; display: block; margin-left: auto; margin-right: auto;">`
                : (req.delivery_signature ? `<img src="/storage/${req.delivery_signature}" style="max-height: 42px; margin-bottom: -15px; display: block; margin-left: auto; margin-right: auto;">` : '')}
              <div class="sig-line">${deliveredBy}<br><span style="font-weight:normal;color:#64748b;">Delivered By (Healthcare Staff)</span></div>
            </div>
            <div class="sig-box">
              ${req.signature_image || (req.proof_photo && req.proof_photo.startsWith('data:image')) 
                ? `<img src="${req.signature_image || req.proof_photo}" style="max-height: 42px; margin-bottom: -15px; display: block; margin-left: auto; margin-right: auto;">` 
                : (req.proof_photo ? `<img src="/storage/${req.proof_photo}" style="max-height: 42px; margin-bottom: -15px; display: block; margin-left: auto; margin-right: auto;">` : '')}
              <div class="sig-line">${claimedBy}<br><span style="font-weight:normal;color:#64748b;">Received & Claimed By</span></div>
            </div>
          </div>

          <div class="footer">
            Electronic Proof of Delivery &bull; Barangay Honorato Health Information System &bull; Keep for your records
          </div>
        </div>
        <script>
          window.onload = function() { window.print(); }
        <\/script>
      </body>
    </html>
  `);
  printWindow.document.close();
}

// Edit Quantity Modal state
const editingRequest = ref(null);
const editQtyValue = ref(1);
const editQtyError = ref('');
const isSavingQty = ref(false);

const maxAllowedQty = computed(() => {
  if (!editingRequest.value) return 99;
  const limit = editingRequest.value.medicine?.request_limit || 99;
  const stock = editingRequest.value.medicine ? (editingRequest.value.medicine.stock + (editingRequest.value.quantity || 1)) : 99;
  return Math.min(limit, stock);
});

function openEditQtyModal(req) {
  editingRequest.value = req;
  editQtyValue.value = req.quantity ?? 1;
  editQtyError.value = '';
}

function closeEditQtyModal() {
  editingRequest.value = null;
  editQtyValue.value = 1;
  editQtyError.value = '';
}

async function saveQuantity() {
  if (!editingRequest.value) return;
  
  if (!editQtyValue.value || editQtyValue.value < 1) {
    editQtyError.value = 'Please enter a valid quantity of at least 1.';
    return;
  }

  isSavingQty.value = true;
  editQtyError.value = '';

  try {
    const res = await fetch(`/nurse/requests/${editingRequest.value.id}/quantity`, {
      method: 'PATCH',
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        'X-CSRF-TOKEN': csrfToken.value,
      },
      body: JSON.stringify({
        quantity: editQtyValue.value,
      }),
    });

    const data = await res.json();

    if (res.ok && data.success) {
      feedbackType.value = 'success';
      feedbackMessage.value = data.message || 'Quantity updated successfully.';

      const idx = requestsList.value.findIndex(r => r.id === editingRequest.value.id);
      if (idx !== -1) {
        if (data.data) {
          requestsList.value[idx] = data.data;
        } else {
          requestsList.value[idx].quantity = editQtyValue.value;
        }
      }

      closeEditQtyModal();
    } else {
      editQtyError.value = data.message || 'Failed to update quantity.';
    }
  } catch (err) {
    console.error(err);
    editQtyError.value = 'An error occurred while updating quantity.';
  } finally {
    isSavingQty.value = false;
  }
}

onMounted(() => {
  const tokenMeta = document.querySelector('meta[name="csrf-token"]');
  if (tokenMeta) {
    csrfToken.value = tokenMeta.getAttribute('content');
  }
  // Initialize and strictly ensure ONLY delivery requests are kept
  requestsList.value = (props.medicineRequests || []).filter(r => r.delivery_method === 'delivery');
});

// Counts for stat cards and badges (Strictly delivery only)
const counts = computed(() => {
  const list = requestsList.value;
  return {
    pending: list.filter(r => r.status === 'Pending').length,
    accepted: list.filter(r => r.status === 'Accepted').length,
    approved: list.filter(r => r.status === 'Approved').length,
    rejected: list.filter(r => r.status === 'Rejected').length,
    all: list.length,
  };
});

// Filtered and searched list
const filteredRequests = computed(() => {
  return requestsList.value.filter(req => {
    // Safety check: Never show pickup in BHW
    if (req.delivery_method !== 'delivery') return false;

    // Status filter
    if (currentFilter.value !== 'all' && req.status !== currentFilter.value) {
      return false;
    }

    // Search query filter
    if (searchQuery.value.trim() !== '') {
      const q = searchQuery.value.toLowerCase();
      const patientName = `${req.user?.first_name || ''} ${req.user?.last_name || ''}`.toLowerCase();
      const medicine = (req.item_service || '').toLowerCase();
      const reqId = (req.request_id || `REQ-${req.id}`).toLowerCase();
      const address = (req.user?.address || '').toLowerCase();
      const phone = (req.user?.phone_number || req.user?.contact_number || '').toLowerCase();

      return patientName.includes(q) ||
             medicine.includes(q) ||
             reqId.includes(q) ||
             address.includes(q) ||
             phone.includes(q);
    }

    return true;
  });
});

// Format helpers
function formatDate(dateStr) {
  if (!dateStr) return '';
  const d = new Date(dateStr);
  return d.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
}

function formatTime(dateStr) {
  if (!dateStr) return '';
  const d = new Date(dateStr);
  return d.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit' });
}

// Action handlers (Accept, Approve, Reject)
async function handleAction(req, action) {
  const actionText = action === 'accept' 
    ? 'Accept this delivery request and notify patient to come for consultation?' 
    : action === 'approve'
      ? `Approve delivery of ${req.item_service} (x${req.quantity || 1}) to ${req.user?.address || 'patient address'}?`
      : 'Reject this request? The reserved quantity will be returned to inventory stock.';

  if (!confirm(actionText)) return;

  actionLoadingId.value = req.id;
  feedbackMessage.value = '';

  try {
    const res = await fetch(`/nurse/requests/${req.id}/${action}`, {
      method: 'PATCH',
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        'X-CSRF-TOKEN': csrfToken.value,
      },
      body: JSON.stringify({}),
    });

    const data = await res.json();

    if (res.ok && data.success) {
      feedbackType.value = 'success';
      feedbackMessage.value = data.message || `Request ${action}ed successfully.`;

      // Update local state item
      const idx = requestsList.value.findIndex(r => r.id === req.id);
      if (idx !== -1) {
        if (data.data) {
          requestsList.value[idx] = data.data;
        } else {
          const newStatus = action === 'accept' ? 'Accepted' : action === 'approve' ? 'Approved' : 'Rejected';
          requestsList.value[idx].status = newStatus;
        }
      }
    } else {
      feedbackType.value = 'error';
      feedbackMessage.value = data.message || `Failed to ${action} request.`;
    }
  } catch (err) {
    console.error(err);
    feedbackType.value = 'error';
    feedbackMessage.value = `An error occurred while trying to ${action} request.`;
  } finally {
    actionLoadingId.value = null;
  }
}
</script>
