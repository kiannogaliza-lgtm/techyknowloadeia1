<template>
  <div class="space-y-6">
    <!-- Header Banner -->
    <div class="bg-gradient-to-r from-[#0c2d48] to-[#1a5a89] rounded-2xl p-6 text-white shadow-lg flex flex-col md:flex-row md:items-center justify-between gap-4">
      <div>
        <div class="flex items-center gap-2 text-cyan-200 text-xs font-bold uppercase tracking-wider mb-1">
          <span class="material-icons-outlined text-sm">assignment_return</span>
          BHW Medicine Audit Ledger
        </div>
        <h2 class="text-2xl font-bold">Returned & Damaged Medicines</h2>
        <p class="text-sm text-cyan-100/80 mt-1 max-w-2xl">
          Medicines that were broken, damaged, or adjusted during delivery, consultation, or claiming.
        </p>
      </div>

      <div class="flex items-center gap-3">
        <div class="px-5 py-3 bg-white/10 backdrop-blur-md rounded-xl border border-white/15 text-center">
          <p class="text-[11px] text-cyan-200 uppercase font-semibold">Total Units Returned</p>
          <p class="text-2xl font-extrabold text-white">{{ totalReturnedCount }}</p>
        </div>
        <div class="px-5 py-3 bg-white/10 backdrop-blur-md rounded-xl border border-white/15 text-center">
          <p class="text-[11px] text-cyan-200 uppercase font-semibold">Total Logs</p>
          <p class="text-2xl font-extrabold text-white">{{ medicineReturns.length }}</p>
        </div>
      </div>
    </div>

    <!-- Main Content Cards -->
    <div class="bg-white rounded-2xl shadow-[0_2px_10px_-3px_rgba(6,81,237,0.1)] border border-gray-100 p-6">
      <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-5 border-b border-gray-100">
        <div>
          <h3 class="font-bold text-[#1a5a89] text-base">Return & Damage History</h3>
          <p class="text-xs text-gray-500 mt-0.5">List of all medicine quantity reductions documented by healthcare workers.</p>
        </div>

        <!-- Search / Filter -->
        <div class="relative w-full sm:w-80">
          <svg class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
          </svg>
          <input v-model="searchQuery" type="text" placeholder="Search patient, medicine, or reason..."
                 class="w-full pl-9 pr-4 py-2 bg-gray-50 border border-gray-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-[#3ac1eb]/40 focus:border-[#3ac1eb] transition-all">
        </div>
      </div>

      <!-- Empty State -->
      <div v-if="filteredReturns.length === 0" class="py-16 text-center text-gray-400">
        <div class="w-16 h-16 rounded-full bg-emerald-50 text-emerald-500 flex items-center justify-center mx-auto mb-3">
          <span class="material-icons-outlined" style="font-size: 32px;">verified</span>
        </div>
        <p class="text-base font-bold text-gray-700">No Returned or Damaged Medicines</p>
        <p class="text-xs text-gray-400 mt-1 max-w-sm mx-auto">
          {{ searchQuery ? 'No records match your search query.' : 'There are currently no damaged or returned medicine entries logged in the system.' }}
        </p>
      </div>

      <!-- List of Returns -->
      <div v-else class="divide-y divide-gray-100 mt-2">
        <div v-for="item in filteredReturns" :key="item.id"
             class="py-4.5 flex flex-col md:flex-row md:items-center justify-between gap-4 hover:bg-gray-50/60 p-3 rounded-xl transition-all">
          <div class="flex items-start gap-3.5">
            <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-600 border border-amber-200 flex items-center justify-center shrink-0 mt-0.5">
              <span class="material-icons-outlined" style="font-size: 22px;">broken_image</span>
            </div>
            <div>
              <div class="flex items-center gap-2 flex-wrap">
                <h4 class="text-sm font-bold text-gray-900">{{ item.medicine_name }}</h4>
                <span class="px-2 py-0.5 text-[10px] font-bold rounded-full bg-blue-50 text-blue-700 border border-blue-200">
                  {{ item.request_id }}
                </span>
                <span class="px-2 py-0.5 text-[10px] font-bold rounded-full bg-amber-100 text-amber-800 border border-amber-300">
                  {{ item.quantity_returned }} {{ item.unit }} Broken/Returned
                </span>
                <span v-if="item.patient_name" class="text-xs text-gray-500 font-medium">
                  • Patient: <strong class="text-gray-800">{{ item.patient_name }}</strong>
                </span>
              </div>

              <!-- Quantity Difference Details -->
              <p class="text-xs text-gray-600 mt-1.5 flex items-center gap-2 flex-wrap">
                <span>Requested: <strong class="text-gray-800">{{ item.previous_quantity }}</strong></span>
                <span class="text-gray-400">&rarr;</span>
                <span>Claimed/Received: <strong class="text-emerald-600">{{ item.new_quantity }} {{ item.unit }}</strong></span>
                <span class="text-gray-300">•</span>
                <span class="text-red-600 font-semibold">-{{ item.quantity_returned }} {{ item.unit }} deducted</span>
              </p>

              <!-- Reason -->
              <div class="mt-2 flex items-center gap-1.5 text-xs text-amber-900 bg-amber-50/80 px-2.5 py-1 rounded-lg border border-amber-200/60 w-fit">
                <span class="material-icons-outlined text-amber-600" style="font-size: 14px;">info</span>
                <span>Reason: <strong>{{ item.reason }}</strong></span>
              </div>
            </div>
          </div>

          <!-- Metadata & Recorded Note -->
          <div class="text-left md:text-right shrink-0 border-t md:border-t-0 pt-2 md:pt-0 border-gray-100">
            <div class="text-xs font-semibold text-gray-700">{{ item.date }}</div>
            <div class="text-[11px] text-gray-400 mt-0.5">Recorded by: {{ item.recorded_by }}</div>
            <div class="text-[10px] font-medium mt-1" :class="isRestocked(item.reason) ? 'text-emerald-700 font-semibold' : 'text-amber-700/80'">
              Status: {{ isRestocked(item.reason) ? 'Returned to Stock' : 'Not Returned to Stock' }}
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue';

const props = defineProps({
  medicineReturns: {
    type: Array,
    default: () => []
  }
});

const searchQuery = ref('');

const totalReturnedCount = computed(() => {
  return props.medicineReturns.reduce((sum, item) => sum + (Number(item.quantity_returned) || 0), 0);
});

const filteredReturns = computed(() => {
  if (!searchQuery.value) return props.medicineReturns;
  const q = searchQuery.value.toLowerCase();
  return props.medicineReturns.filter(item => {
    return (item.medicine_name && item.medicine_name.toLowerCase().includes(q)) ||
           (item.reason && item.reason.toLowerCase().includes(q)) ||
           (item.request_id && item.request_id.toLowerCase().includes(q)) ||
           (item.patient_name && item.patient_name.toLowerCase().includes(q));
  });
});

const restockedReasons = [
  'dosage adjusted during consultation',
  'dosage adjusted by nurse during consultation',
  'patient declined / partially returned'
];

function isRestocked(reason) {
  if (!reason) return false;
  return restockedReasons.includes(reason.toLowerCase().trim());
}
</script>

