<template>
  <div class="grid grid-cols-1 lg:grid-cols-[1.1fr_1fr] gap-6 items-start">

    <!-- LEFT: New Medicine Request Form -->
    <div class="bg-white rounded-2xl shadow-[0_2px_10px_-3px_rgba(6,81,237,0.1)] border border-gray-100">
      <div class="p-6 border-b border-gray-100 flex items-center gap-2">
        <svg class="w-5 h-5 text-[#1a5a89]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
        </svg>
        <h3 class="font-bold text-[#1a5a89] text-lg">New Medicine Request</h3>
      </div>

      <!-- Success / Error Flash -->
      <div v-if="flash" :class="['mx-6 mt-5 px-4 py-3 rounded-xl text-sm font-medium flex items-center gap-2', flash.type === 'success' ? 'bg-green-50 text-green-700 border border-green-200' : 'bg-red-50 text-red-700 border border-red-200']">
        <svg v-if="flash.type === 'success'" class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
        <svg v-else class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
        {{ flash.message }}
      </div>

      <form @submit.prevent="submitRequest" class="p-6 flex flex-col gap-5">
        <!-- Who is this medicine for? (Patient / Recipient) -->
        <div class="p-4 rounded-xl bg-blue-50/60 border border-blue-100">
          <label class="block text-xs font-bold text-[#1a5a89] uppercase tracking-wider mb-2">
            Who is taking this medicine?
          </label>
          <div class="grid grid-cols-2 gap-2 mb-3">
            <label :class="['flex items-center gap-2 p-2.5 rounded-lg border text-xs font-semibold cursor-pointer transition-all', form.patient_type === 'self' ? 'bg-white border-[#1a5a89] text-[#1a5a89] shadow-sm' : 'bg-transparent border-gray-200 text-gray-600 hover:bg-white/60']">
              <input type="radio" v-model="form.patient_type" value="self" class="accent-[#1a5a89]">
              <span>Myself ({{ user.first_name }} {{ user.last_name }})</span>
            </label>
            <label :class="['flex items-center gap-2 p-2.5 rounded-lg border text-xs font-semibold cursor-pointer transition-all', form.patient_type === 'other' ? 'bg-white border-[#1a5a89] text-[#1a5a89] shadow-sm' : 'bg-transparent border-gray-200 text-gray-600 hover:bg-white/60']">
              <input type="radio" v-model="form.patient_type" value="other" class="accent-[#1a5a89]">
              <span>Family Member / Child / Elder</span>
            </label>
          </div>

          <!-- Self patient age display -->
          <div v-if="form.patient_type === 'self'" class="text-xs text-gray-600 flex items-center justify-between pt-1 border-t border-blue-100/70">
            <span>Requester Registered Age:</span>
            <span class="font-bold text-[#1a5a89]">
              {{ user.age !== null && user.age !== undefined ? `${user.age} years old` : 'Age not recorded' }}
              <span v-if="user.age_category" class="font-normal text-gray-500">({{ user.age_category }})</span>
            </span>
          </div>

          <!-- Other family member fields -->
          <div v-else class="flex flex-col gap-3 pt-2 border-t border-blue-100/70">
            <div>
              <label class="block text-xs font-semibold text-gray-700 mb-1">Patient Full Name <span class="text-red-500">*</span></label>
              <input v-model="form.patient_name" type="text" placeholder="e.g. Maria Santos" required
                     class="w-full border border-gray-200 bg-white rounded-lg px-3 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-[#3ac1eb]/50">
            </div>
            <div class="grid grid-cols-2 gap-3">
              <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1">Patient Age (Years) <span class="text-red-500">*</span></label>
                <input v-model.number="form.patient_age" type="number" min="0" max="130" placeholder="e.g. 8" required
                       class="w-full border border-gray-200 bg-white rounded-lg px-3 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-[#3ac1eb]/50">
              </div>
              <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1">Relationship <span class="text-red-500">*</span></label>
                <select v-model="form.patient_relationship" required
                        class="w-full border border-gray-200 bg-white rounded-lg px-3 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-[#3ac1eb]/50">
                  <option value="">Select...</option>
                  <option value="Child / Dependent">Child / Dependent</option>
                  <option value="Parent / Senior Citizen">Parent / Senior Citizen</option>
                  <option value="Spouse / Partner">Spouse / Partner</option>
                  <option value="Sibling">Sibling</option>
                  <option value="Other Family Member">Other Family Member</option>
                </select>
              </div>
            </div>
            <div>
              <label class="block text-xs font-semibold text-gray-700 mb-1">Gender</label>
              <div class="flex items-center gap-4 text-xs text-gray-700">
                <label class="flex items-center gap-1 cursor-pointer">
                  <input type="radio" v-model="form.patient_gender" value="Male" class="accent-[#1a5a89]"> Male
                </label>
                <label class="flex items-center gap-1 cursor-pointer">
                  <input type="radio" v-model="form.patient_gender" value="Female" class="accent-[#1a5a89]"> Female
                </label>
              </div>
            </div>
          </div>
        </div>

        <!-- Select Medicine -->
        <div>
          <div class="flex items-center justify-between mb-2">
            <label class="block text-sm font-semibold text-gray-700">Select Medicine</label>
            <span v-if="selectedMed && (selectedMed.min_age !== null || selectedMed.max_age !== null)"
                  :class="['text-xs font-bold px-2 py-0.5 rounded-full border', isCurrentPatientAgeValid ? 'bg-purple-50 text-purple-700 border-purple-200' : 'bg-red-50 text-red-700 border-red-200']">
              Target Age: {{ selectedMed.age_limit_label || 'All ages' }}
            </span>
          </div>
          <div class="relative">
            <select v-model="form.medicine_id" id="medicine_id" required
                    class="w-full appearance-none border border-gray-200 rounded-xl px-4 py-3 text-sm text-gray-700 bg-white focus:outline-none focus:ring-2 focus:ring-[#3ac1eb]/50 focus:border-[#3ac1eb] transition-all pr-10">
              <option value="">Choose a medicine...</option>
              <option v-for="med in availableMeds" :key="med.id" :value="med.id" :disabled="med.can_request === false || med.status === 'Critical'">
                {{ med.name }} — {{ med.stock }} {{ med.unit }}
                <template v-if="med.age_limit_label && med.age_limit_label !== 'All ages'">
                  [{{ med.age_limit_label }}]
                </template>
                <template v-if="med.can_request === false && med.days_remaining">
                  (Supply running • {{ med.days_remaining }}d left)
                </template>
                <template v-else-if="med.can_request === false && med.lock_reason">
                  (Locked: {{ med.lock_reason }})
                </template>
                <template v-else-if="med.status === 'Critical'">(Out of Stock)</template>
              </option>
            </select>
            <svg class="w-4 h-4 absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
            </svg>
          </div>

          <!-- Age mismatch warning alert -->
          <div v-if="selectedMed && !isCurrentPatientAgeValid" class="mt-2.5 p-3 rounded-xl bg-red-50 border border-red-200 text-red-800 text-xs flex items-start gap-2">
            <svg class="w-4 h-4 shrink-0 text-red-600 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
            </svg>
            <div>
              <p class="font-bold">⚠️ Age Restriction Warning</p>
              <p class="mt-0.5">
                The intended patient is <strong>{{ currentPatientEffectiveAge }} years old</strong>, but <strong>{{ selectedMed.name }}</strong> is restricted to patients aged <strong>{{ selectedMed.age_limit_label }}</strong>.
              </p>
            </div>
          </div>
          <!-- Cooldown or lock alert -->
          <div v-if="selectedMed && selectedMed.can_request === false" class="mt-2 p-3 rounded-xl bg-amber-50 border border-amber-200 text-amber-800 text-xs flex items-start gap-2">
            <svg class="w-4 h-4 shrink-0 text-amber-600 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
            </svg>
            <div>
              <p class="font-bold">Prescription / Refill Cooldown Active</p>
              <p class="mt-0.5">{{ selectedMed.lock_reason }}</p>
            </div>
          </div>
          <p v-else-if="selectedMed && selectedMed.status !== 'In Stock'" class="mt-1.5 text-xs font-medium" :class="selectedMed.status === 'Low Stock' ? 'text-yellow-600' : 'text-red-600'">
            ⚠️ {{ selectedMed.status }} — only {{ selectedMed.stock }} {{ selectedMed.unit }} remaining.
          </p>
        </div>

        <!-- Quantity -->
        <div>
          <div class="flex items-center justify-between mb-2">
            <label class="block text-sm font-semibold text-gray-700">Quantity</label>
            <span v-if="selectedMed" class="text-xs font-semibold px-2 py-0.5 rounded-full bg-blue-50 text-[#1a5a89] border border-blue-200">
              Request limit: max {{ currentMaxLimit }} {{ selectedMed.unit }}
            </span>
          </div>
          <input v-model.number="form.quantity" type="number" id="quantity" min="1" :max="currentMaxLimit" required
                 :placeholder="selectedMed ? `Enter quantity (1 - ${currentMaxLimit})` : 'Enter quantity'"
                 class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-[#3ac1eb]/50 focus:border-[#3ac1eb] transition-all">
          <p v-if="selectedMed && form.quantity > currentMaxLimit" class="mt-1.5 text-xs font-semibold text-red-600">
            ⚠️ You cannot request more than {{ currentMaxLimit }} {{ selectedMed.unit }} for this medicine.
          </p>
        </div>

        <!-- Delivery Method -->
        <div>
          <label class="block text-sm font-semibold text-gray-700 mb-3">Delivery Method</label>
          <div class="flex flex-col gap-3">

            <!-- Pickup Option -->
            <label :class="[
              'flex items-start gap-4 px-4 py-3.5 rounded-xl border-2 transition-all',
              isPickupBlocked
                ? 'opacity-60 bg-gray-50 border-gray-200 cursor-not-allowed pointer-events-none'
                : (form.delivery_method === 'pickup' ? 'border-[#3ac1eb] bg-[#f0fbff] cursor-pointer' : 'border-gray-200 hover:border-gray-300 cursor-pointer')
            ]">
              <input type="radio" v-model="form.delivery_method" value="pickup" :disabled="isPickupBlocked" class="mt-0.5 accent-[#3ac1eb] disabled:cursor-not-allowed">
              <div class="flex-1">
                <div class="flex items-center justify-between">
                  <p class="text-sm font-semibold text-gray-800">Pickup at Barangay Health Center</p>
                  <span v-if="isPickupBlocked" class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-red-100 text-red-700">Blocked</span>
                </div>
                <p class="text-xs text-gray-500 mt-0.5">Available during office hours</p>
                <p v-if="isPickupBlocked && pickupBlockReason" class="text-xs font-semibold text-red-600 mt-1 flex items-center gap-1">
                  <svg class="w-3.5 h-3.5 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                  <span>{{ pickupBlockReason }}</span>
                </p>
              </div>
            </label>

            <!-- Home Delivery Option -->
            <label :class="[
              'flex items-start gap-4 px-4 py-3.5 rounded-xl border-2 transition-all',
              isDeliveryBlocked
                ? 'opacity-60 bg-gray-50 border-gray-200 cursor-not-allowed pointer-events-none'
                : (form.delivery_method === 'delivery' ? 'border-[#3ac1eb] bg-[#f0fbff] cursor-pointer' : 'border-gray-200 hover:border-gray-300 cursor-pointer')
            ]">
              <input type="radio" v-model="form.delivery_method" value="delivery" :disabled="isDeliveryBlocked" class="mt-0.5 accent-[#3ac1eb] disabled:cursor-not-allowed">
              <div class="flex-1">
                <div class="flex items-center justify-between">
                  <p class="text-sm font-semibold text-gray-800">Home Delivery</p>
                  <span v-if="isDeliveryBlocked" class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-red-100 text-red-700">Blocked</span>
                </div>
                <p class="text-xs text-gray-500 mt-0.5">Delivery depends on time and distance or 1 to 2 days</p>
                <p v-if="isDeliveryBlocked && deliveryBlockReason" class="text-xs font-semibold text-red-600 mt-1 flex items-center gap-1">
                  <svg class="w-3.5 h-3.5 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                  <span>{{ deliveryBlockReason }}</span>
                </p>
              </div>
            </label>

          </div>
        </div>

        <!-- Additional Notes -->
        <div>
          <label class="block text-sm font-semibold text-gray-700 mb-2">Additional Notes <span class="font-normal text-gray-400">(Optional)</span></label>
          <textarea v-model="form.notes" id="notes" rows="4" placeholder="Any special instructions or concerns..."
                    class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm resize-none focus:outline-none focus:ring-2 focus:ring-[#3ac1eb]/50 focus:border-[#3ac1eb] transition-all"></textarea>
        </div>

        <!-- Submit -->
        <button type="submit" :disabled="submitting"
                class="w-full bg-[#1a5a89] hover:bg-[#154d78] text-white font-semibold py-3.5 rounded-xl transition-all duration-200 text-sm flex items-center justify-center gap-2 shadow-lg shadow-[#1a5a89]/20 disabled:opacity-60 disabled:cursor-not-allowed">
          <svg v-if="submitting" class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
          </svg>
          <span>{{ submitting ? 'Submitting…' : 'Submit Request' }}</span>
        </button>
      </form>
    </div>

    <!-- RIGHT: Available Medicines + My Requests -->
    <div class="flex flex-col gap-5">

      <!-- Available Medicines Panel -->
      <div class="bg-white rounded-2xl shadow-[0_2px_10px_-3px_rgba(6,81,237,0.1)] border border-gray-100">
        <div class="p-5 border-b border-gray-100 flex items-center gap-3">
          <svg class="w-5 h-5 text-[#3ac1eb]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
          </svg>
          <h3 class="font-bold text-[#1a5a89]">Available Medicines</h3>
        </div>

        <div class="px-5 pt-4 pb-2">
          <div class="relative">
            <svg class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
            <input v-model="medSearch" type="text" placeholder="Search medicines..."
                   class="w-full border border-gray-200 rounded-xl pl-9 pr-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#3ac1eb]/40 focus:border-[#3ac1eb] transition-all">
          </div>
        </div>

        <div class="px-5 pb-5 flex flex-col gap-2 max-h-72 overflow-y-auto">
          <div v-if="filteredMeds.length === 0" class="text-sm text-gray-500 text-center py-6">No medicines found.</div>
          <div v-for="med in filteredMeds" :key="med.id"
               @click="med.can_request !== false ? form.medicine_id = med.id : null"
               :class="[
                 'flex items-center justify-between px-4 py-3 rounded-xl border transition-all',
                 med.can_request === false
                   ? 'opacity-70 bg-gray-50 border-gray-200 cursor-not-allowed'
                   : (form.medicine_id === med.id ? 'border-[#3ac1eb] bg-[#f0fbff] cursor-pointer' : 'border-gray-100 bg-gray-50 hover:border-[#3ac1eb]/40 hover:bg-[#f8fdff] cursor-pointer')
               ]">
            <div>
              <div class="flex items-center gap-2">
                <p class="text-sm font-semibold text-gray-800">{{ med.name }}</p>
                <span v-if="med.can_request === false && med.days_remaining" class="px-2 py-0.5 text-[10px] font-bold rounded-full bg-amber-100 text-amber-800 border border-amber-200">
                  ⏳ Refill in {{ med.days_remaining }}d
                </span>
                <span v-else-if="med.can_request === false && med.lock_reason" class="px-2 py-0.5 text-[10px] font-bold rounded-full bg-blue-100 text-blue-800 border border-blue-200">
                  🔒 Locked
                </span>
              </div>
              <p class="text-xs text-gray-500 mt-0.5">
                {{ med.stock }} {{ med.unit }} available &bull; <span class="text-emerald-600 font-medium">Limit: max {{ med.request_limit ?? 5 }}</span>
                <span v-if="med.age_limit_label && med.age_limit_label !== 'All ages'" class="ml-1 text-purple-700 font-semibold">
                  &bull; Ages: {{ med.age_limit_label }}
                </span>
              </p>
              <p v-if="med.dosage" class="text-xs text-blue-600 font-medium mt-0.5 flex items-center gap-1">
                <svg class="w-3.5 h-3.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>Dosage: {{ med.dosage }}</span>
              </p>
              <p v-if="med.can_request === false && med.lock_reason" class="text-[11px] text-amber-700 font-medium mt-0.5">
                {{ med.lock_reason }}
              </p>
            </div>
            <span :class="['px-2.5 py-1 text-xs font-semibold rounded-full shrink-0', med.status === 'In Stock' ? 'bg-green-100 text-green-700' : (med.status === 'Low Stock' ? 'bg-yellow-100 text-yellow-700' : 'bg-red-100 text-red-700')]">
              {{ med.status }}
            </span>
          </div>
        </div>
      </div>

      <!-- My Medicine Requests & Returned Medicines History Tabs -->
      <div class="bg-white rounded-2xl shadow-[0_2px_10px_-3px_rgba(6,81,237,0.1)] border border-gray-100">
        <div class="p-3 border-b border-gray-100 flex items-center justify-between">
          <div class="flex items-center gap-1.5 p-1 bg-gray-100/80 rounded-xl">
            <button type="button" @click="activeSubTab = 'requests'"
                    :class="['flex items-center gap-2 px-3.5 py-1.5 rounded-lg text-xs font-bold transition-all cursor-pointer', activeSubTab === 'requests' ? 'bg-white text-[#1a5a89] shadow-sm' : 'text-gray-500 hover:text-gray-800']">
              <svg class="w-4 h-4 text-[#3ac1eb]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
              </svg>
              <span>My Requests</span>
              <span class="px-1.5 py-0.2 rounded-full text-[10px] bg-blue-50 text-[#1a5a89] font-bold">{{ myMedicineRequests.length }}</span>
            </button>
            <button type="button" @click="activeSubTab = 'returned'"
                    :class="['flex items-center gap-2 px-3.5 py-1.5 rounded-lg text-xs font-bold transition-all cursor-pointer', activeSubTab === 'returned' ? 'bg-white text-amber-700 shadow-sm' : 'text-gray-500 hover:text-gray-800']">
              <span class="material-icons-outlined text-amber-600" style="font-size: 16px;">assignment_return</span>
              <span>Returned / Damaged</span>
              <span v-if="myMedicineReturns.length > 0" class="px-1.5 py-0.2 rounded-full text-[10px] bg-amber-100 text-amber-800 font-bold">
                {{ myMedicineReturns.length }}
              </span>
            </button>
          </div>
        </div>

        <!-- SUBTAB 1: My Requests List -->
        <div v-if="activeSubTab === 'requests'" class="p-5 flex flex-col gap-2 max-h-72 overflow-y-auto">
          <div v-if="myMedicineRequests.length === 0" class="text-sm text-gray-500 text-center py-4">You have no medicine requests yet.</div>
          <div v-for="req in myMedicineRequests" :key="req.id"
               class="flex items-center justify-between px-4 py-3 rounded-xl bg-gray-50 border border-gray-100">
            <div>
              <p class="text-sm font-semibold text-gray-800">{{ req.medicine_name }}</p>
              <p class="text-xs text-gray-500 mt-0.5">{{ req.request_id }} &bull; Qty: {{ req.quantity }} &bull; {{ req.delivery_method === 'pickup' ? 'Pickup' : 'Home Delivery' }} &bull; {{ req.date }}</p>
            </div>
            <span :class="['px-2.5 py-1 text-xs font-semibold rounded-full capitalize whitespace-nowrap', getStatusClass(req.status)]">
              {{ req.status }}
            </span>
          </div>
        </div>

        <!-- SUBTAB 2: Returned & Broken Medicines Notification / Log -->
        <div v-else class="p-5 flex flex-col gap-3 max-h-72 overflow-y-auto">
          <div v-if="myMedicineReturns.length === 0" class="text-sm text-gray-400 text-center py-6 flex flex-col items-center">
            <span class="material-icons-outlined text-gray-300 mb-1" style="font-size: 32px;">check_circle_outline</span>
            <p class="font-medium text-gray-600 text-xs">No returned or damaged medicines.</p>
            <p class="text-[11px] text-gray-400 mt-0.5">All claimed medicine quantities matched your requested requests.</p>
          </div>
          <div v-for="item in myMedicineReturns" :key="item.id"
               class="p-3.5 rounded-xl bg-amber-50/60 border border-amber-200/80 transition-all hover:bg-amber-50">
            <div class="flex items-start justify-between gap-2">
              <div class="flex items-start gap-2.5">
                <div class="w-7 h-7 rounded-lg bg-amber-100 text-amber-700 flex items-center justify-center shrink-0 mt-0.5">
                  <span class="material-icons-outlined" style="font-size: 16px;">report_problem</span>
                </div>
                <div>
                  <h4 class="text-xs font-bold text-gray-800">{{ item.medicine_name }}</h4>
                  <p class="text-[11px] text-amber-800 font-medium mt-0.5">
                    <strong>{{ item.quantity_returned }} {{ item.unit }}</strong> marked as returned / damaged
                  </p>
                  <p class="text-[11px] text-gray-500 mt-0.5">
                    Requested: <span class="line-through">{{ item.previous_quantity }}</span> &rarr; Received: <strong class="text-emerald-700">{{ item.new_quantity }} {{ item.unit }}</strong>
                  </p>
                </div>
              </div>
              <span class="px-2 py-0.5 text-[10px] font-bold rounded-full bg-amber-100 text-amber-800 border border-amber-300 shrink-0">
                Returned
              </span>
            </div>
            
            <div class="mt-2.5 pt-2 border-t border-amber-200/50 flex flex-wrap items-center justify-between text-[10px] text-gray-500 gap-2">
              <span class="inline-flex items-center gap-1 text-gray-600">
                <span class="material-icons-outlined" style="font-size: 12px;">notes</span>
                Reason: <em class="text-gray-700">{{ item.reason }}</em>
              </span>
              <span class="text-gray-400">{{ item.date }}</span>
            </div>
          </div>
        </div>
      </div>

    </div>
  </div>
</template>

<script setup>
import { ref, computed, watch } from 'vue';

const props = defineProps({
  user: { type: Object, required: true },
  dashboardData: { type: Object, default: () => ({}) },
  myMedicineRequests: { type: Array, default: () => [] },
  myMedicineReturns: { type: Array, default: () => [] },
  medicineRequestStoreUrl: { type: String, default: '' },
});

const activeSubTab = ref('requests');

// ── Form state ─────────────────────────────────────────────────────────
const form = ref({
  medicine_id: '',
  quantity: 1,
  delivery_method: 'pickup',
  patient_type: 'self',
  patient_name: '',
  patient_age: null,
  patient_gender: 'Male',
  patient_relationship: '',
  notes: '',
});

const submitting = ref(false);
const flash = ref(null);
const medSearch = ref('');

const availableMeds = computed(() => props.dashboardData?.medicines ?? []);

const filteredMeds = computed(() => {
  const q = medSearch.value.toLowerCase();
  return availableMeds.value.filter(m => m.name.toLowerCase().includes(q));
});

const selectedMed = computed(() => availableMeds.value.find(m => m.id === form.value.medicine_id) || null);

// Effective age of the patient who will actually take the medicine
const currentPatientEffectiveAge = computed(() => {
  if (form.value.patient_type === 'other') {
    return form.value.patient_age !== null && form.value.patient_age !== undefined && form.value.patient_age !== ''
      ? Number(form.value.patient_age)
      : null;
  }
  return props.user?.age !== null && props.user?.age !== undefined
    ? Number(props.user.age)
    : null;
});

// Check if the current patient's age matches the selected medicine's age range/limit
const isCurrentPatientAgeValid = computed(() => {
  if (!selectedMed.value) return true;
  const age = currentPatientEffectiveAge.value;
  if (age === null) return true; // If age not recorded, don't proactively block in UI

  const min = selectedMed.value.min_age !== null && selectedMed.value.min_age !== undefined ? Number(selectedMed.value.min_age) : null;
  const max = selectedMed.value.max_age !== null && selectedMed.value.max_age !== undefined ? Number(selectedMed.value.max_age) : null;

  if (min !== null && age < min) return false;
  if (max !== null && age > max) return false;
  return true;
});

// Check if the resident has ANY active request or running dosage under 'pickup' or 'delivery'
const activePickupRequest = computed(() => {
  return props.myMedicineRequests.find(r => 
    r.delivery_method === 'pickup' && ['Pending', 'Accepted'].includes(r.status)
  ) || null;
});

const activeDeliveryRequest = computed(() => {
  return props.myMedicineRequests.find(r => 
    r.delivery_method === 'delivery' && ['Pending', 'Accepted'].includes(r.status)
  ) || null;
});

// If the resident already requested Pickup (for ANY medicine), Home Delivery is blocked!
// If the resident already requested Home Delivery (for ANY medicine), Pickup is blocked!
const isPickupBlocked = computed(() => {
  // If specific medicine is on dosage cooldown or blocked
  if (selectedMed.value?.pickup_blocked) return true;
  // If user already requested Home Delivery for ANY medicine, Pickup is blocked
  if (activeDeliveryRequest.value) return true;
  return false;
});

const isDeliveryBlocked = computed(() => {
  // If specific medicine is on dosage cooldown or blocked
  if (selectedMed.value?.delivery_blocked) return true;
  // If user already requested Pickup for ANY medicine, Home Delivery is blocked
  if (activePickupRequest.value) return true;
  return false;
});

const pickupBlockReason = computed(() => {
  if (selectedMed.value?.pickup_block_reason) return selectedMed.value.pickup_block_reason;
  if (activeDeliveryRequest.value) {
    return `Home Delivery was already requested (${activeDeliveryRequest.value.medicine_name}). Pickup is blocked until delivery and dosage are completed.`;
  }
  return '';
});

const deliveryBlockReason = computed(() => {
  if (selectedMed.value?.delivery_block_reason) return selectedMed.value.delivery_block_reason;
  if (activePickupRequest.value) {
    return `Pickup at Health Center was already requested (${activePickupRequest.value.medicine_name}). Home Delivery is blocked until dosage is finished.`;
  }
  return '';
});

// Auto adjust selected delivery method
watch([isPickupBlocked, isDeliveryBlocked], ([pickupBlocked, deliveryBlocked]) => {
  if (pickupBlocked && deliveryBlocked) {
    form.value.delivery_method = '';
  } else if (pickupBlocked && form.value.delivery_method === 'pickup') {
    form.value.delivery_method = 'delivery';
  } else if (deliveryBlocked && form.value.delivery_method === 'delivery') {
    form.value.delivery_method = 'pickup';
  } else if (!form.value.delivery_method) {
    form.value.delivery_method = pickupBlocked ? 'delivery' : 'pickup';
  }
}, { immediate: true });

const currentMaxLimit = computed(() => {
  if (!selectedMed.value) return 50;
  return Number(selectedMed.value.request_limit) || 5;
});

const getStatusClass = (status) => {
  const s = (status || '').toLowerCase();
  if (s === 'approved' || s === 'completed') return 'bg-green-100 text-green-700';
  if (s === 'accepted') return 'bg-blue-100 text-blue-700';
  if (s === 'pending') return 'bg-yellow-100 text-yellow-700';
  if (s === 'rejected') return 'bg-red-100 text-red-700';
  return 'bg-gray-100 text-gray-700';
};

const getCsrf = () => document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') ?? '';

const submitRequest = async () => {
  if (!form.value.medicine_id) {
    flash.value = { type: 'error', message: 'Please select a medicine.' };
    return;
  }

  if (selectedMed.value && selectedMed.value.can_request === false) {
    flash.value = {
      type: 'error',
      message: selectedMed.value.lock_reason || 'You cannot request this medicine at this time.'
    };
    return;
  }

  if (form.value.delivery_method === 'pickup' && isPickupBlocked.value) {
    flash.value = {
      type: 'error',
      message: pickupBlockReason.value || 'Pickup is blocked.'
    };
    return;
  }

  if (form.value.delivery_method === 'delivery' && isDeliveryBlocked.value) {
    flash.value = {
      type: 'error',
      message: deliveryBlockReason.value || 'Home delivery is blocked.'
    };
    return;
  }

  if (selectedMed.value && form.value.quantity > currentMaxLimit.value) {
    flash.value = { 
      type: 'error', 
      message: `You can only request up to ${currentMaxLimit.value} ${selectedMed.value.unit} for ${selectedMed.value.name}.` 
    };
    return;
  }

  if (selectedMed.value && !isCurrentPatientAgeValid.value) {
    flash.value = {
      type: 'error',
      message: `Patient age mismatch: The patient is ${currentPatientEffectiveAge.value} years old, but ${selectedMed.value.name} is only suitable for patients aged ${selectedMed.value.age_limit_label}.`
    };
    return;
  }

  if (form.value.patient_type === 'other') {
    if (!form.value.patient_name || !form.value.patient_relationship || form.value.patient_age === null || form.value.patient_age === undefined) {
      flash.value = {
        type: 'error',
        message: 'Please fill in all required patient recipient details (Name, Age, and Relationship).'
      };
      return;
    }
  }

  submitting.value = true;
  flash.value = null;

  try {
    const body = new FormData();
    body.append('_token', getCsrf());
    body.append('medicine_id', form.value.medicine_id);
    body.append('quantity', form.value.quantity);
    body.append('delivery_method', form.value.delivery_method);
    body.append('patient_type', form.value.patient_type);
    if (form.value.patient_type === 'other') {
      body.append('patient_name', form.value.patient_name || '');
      body.append('patient_age', form.value.patient_age || '');
      body.append('patient_gender', form.value.patient_gender || '');
      body.append('patient_relationship', form.value.patient_relationship || '');
    }
    body.append('notes', form.value.notes ?? '');

    const res = await fetch(props.medicineRequestStoreUrl, {
      method: 'POST',
      headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json, text/html' },
      body,
    });

    if (res.ok || res.redirected) {
      flash.value = { type: 'success', message: '✅ Request submitted! Stock has been reserved.' };
      // Reset form
      form.value = {
        medicine_id: '',
        quantity: 1,
        delivery_method: 'pickup',
        patient_type: 'self',
        patient_name: '',
        patient_age: null,
        patient_gender: 'Male',
        patient_relationship: '',
        notes: ''
      };
      // Reload after 1.5s so the available stock and "My Requests" list refresh
      setTimeout(() => window.location.reload(), 1500);
    } else {
      const json = await res.json().catch(() => null);
      const errorMsg = json?.message || Object.values(json?.errors ?? {})[0]?.[0] || 'Something went wrong. Please try again.';
      flash.value = { type: 'error', message: errorMsg };
    }
  } catch (err) {
    flash.value = { type: 'error', message: 'Network error. Please check your connection.' };
  } finally {
    submitting.value = false;
  }
};
</script>
