<template>
  <div class="space-y-6">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
      <h3 class="text-xl font-bold text-[#1a5a89] mb-6 flex items-center gap-3">
        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
        </svg>
        Current Medicine Stock
      </h3>

      <!-- Expired Medicines Alert Banner -->
      <div v-if="summaryCounts.expired > 0" class="mb-6 bg-red-50 border border-red-200 rounded-xl p-4 flex items-center gap-3">
        <svg class="w-6 h-6 text-red-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
          <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
        </svg>
        <p class="text-red-700 text-sm font-semibold">
          {{ summaryCounts.expired }} medicine(s) have expired! Please notify the nurse immediately.
        </p>
      </div>

      <!-- Search -->
      <div class="relative mb-8">
        <svg class="w-5 h-5 absolute left-4 top-1/2 -translate-y-1/2 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
        </svg>
        <input v-model="searchQuery" type="text" placeholder="Search medicines..."
               class="w-full pl-12 pr-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#3ac1eb]/50 focus:border-[#3ac1eb] transition-all bg-gray-50 focus:bg-white">
      </div>

      <!-- Medicine Grid -->
      <div v-if="filteredMedicines.length === 0" class="text-center py-12 text-gray-400">
        <svg class="w-12 h-12 mx-auto mb-3 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
          <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
        </svg>
        <p class="text-sm font-medium">No medicines found matching "{{ searchQuery }}".</p>
      </div>
      
      <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5 mb-8">
        <div v-for="medicine in filteredMedicines" :key="medicine.id" 
             :class="['border rounded-2xl p-5 hover:shadow-md transition-all flex flex-col justify-between', isExpired(medicine) ? 'border-red-300 bg-red-50' : isExpiringSoon(medicine) ? 'border-amber-200 bg-amber-50/40' : 'border-gray-100 bg-gray-50/50 hover:bg-white']">
          <div class="flex justify-between items-start mb-2">
            <h4 class="font-bold text-gray-800 text-sm w-3/4 truncate pr-2" :title="medicine.name">{{ medicine.name }}</h4>
            <span :class="badgeClass(medicine.status)" class="px-2 py-0.5 rounded text-[10px] font-bold whitespace-nowrap">
              {{ medicine.status }}
            </span>
          </div>

          <!-- Expiration info -->
          <div v-if="medicine.expiration_date" class="text-[11px] mt-1 font-semibold" :class="isExpired(medicine) ? 'text-red-600' : isExpiringSoon(medicine) ? 'text-amber-600' : 'text-gray-400'">
            <span v-if="isExpired(medicine)">⚠ Expired: {{ medicine.expiration_date }}</span>
            <span v-else-if="isExpiringSoon(medicine)">⏰ Expiring Soon: {{ medicine.expiration_date }}</span>
            <span v-else class="text-gray-400">Exp: {{ medicine.expiration_date }}</span>
          </div>

          <!-- Dosage info -->
          <div v-if="medicine.dosage" class="text-[11px] mt-1 text-blue-600 font-medium flex items-center gap-1">
            <span>💊 Dosage: {{ medicine.dosage }}</span>
          </div>
          
          <div class="flex justify-between items-end mt-4">
            <div>
              <div class="text-3xl font-bold text-[#144976] leading-none mb-1">{{ medicine.stock }}</div>
              <div class="text-[11px] text-gray-500 font-medium">{{ medicine.unit }} available</div>
              <div class="text-[10px] text-emerald-700 font-semibold mt-1">
                Limit: max {{ medicine.request_limit ?? 5 }} / request
              </div>
            </div>
            <svg class="w-8 h-8 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
              <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
            </svg>
          </div>
        </div>
      </div>

      <!-- Summary Boxes -->
      <div class="grid grid-cols-2 md:grid-cols-4 gap-5 mb-8">
        <!-- In Stock -->
        <div class="border border-emerald-200 bg-emerald-50/30 rounded-xl p-4 flex items-center justify-between">
          <div>
            <div class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">In Stock</div>
            <div class="text-2xl font-bold text-emerald-600">{{ summaryCounts.inStock }}</div>
          </div>
        </div>
        <!-- Low Stock -->
        <div class="border border-amber-200 bg-amber-50/30 rounded-xl p-4 flex items-center justify-between">
          <div>
            <div class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Low Stock</div>
            <div class="text-2xl font-bold text-amber-600">{{ summaryCounts.lowStock }}</div>
          </div>
        </div>
        <!-- Critical -->
        <div class="border border-red-200 bg-red-50/30 rounded-xl p-4 flex items-center justify-between">
          <div>
            <div class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Critical</div>
            <div class="text-2xl font-bold text-red-600">{{ summaryCounts.critical }}</div>
          </div>
        </div>
        <!-- Expired -->
        <div class="border border-red-300 bg-red-100/40 rounded-xl p-4 flex items-center justify-between">
          <div>
            <div class="text-xs font-semibold text-red-500 uppercase tracking-wide mb-1">Expired</div>
            <div class="text-2xl font-bold text-red-700">{{ summaryCounts.expired }}</div>
          </div>
        </div>
      </div>

      <!-- Note -->
      <div class="bg-blue-50 text-blue-800 rounded-xl p-4 text-sm font-medium border border-blue-100 flex items-start gap-2">
        <strong class="font-bold text-[#144976]">Note:</strong> 
        <span class="text-blue-700">This is a read-only view. For stock updates and medicine management, please contact the Nurse.</span>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue';

const props = defineProps({
  medicines: {
    type: Array,
    default: () => []
  }
});

const searchQuery = ref('');

const today = new Date();
today.setHours(0, 0, 0, 0);
const threeMonthsLater = new Date(today);
threeMonthsLater.setMonth(threeMonthsLater.getMonth() + 3);

const isExpired = (medicine) => {
  if (!medicine.expiration_date) return false;
  const exp = new Date(medicine.expiration_date);
  exp.setHours(0, 0, 0, 0);
  return exp <= today;
};

const isExpiringSoon = (medicine) => {
  if (!medicine.expiration_date) return false;
  const exp = new Date(medicine.expiration_date);
  exp.setHours(0, 0, 0, 0);
  return exp > today && exp <= threeMonthsLater;
};

const filteredMedicines = computed(() => {
  if (!searchQuery.value) return props.medicines;
  const q = searchQuery.value.toLowerCase();
  return props.medicines.filter(m => m.name.toLowerCase().includes(q));
});

const summaryCounts = computed(() => {
  let inStock = 0;
  let lowStock = 0;
  let critical = 0;
  let expired = 0;

  props.medicines.forEach(m => {
    if (isExpired(m)) expired++;
    if (m.status === 'In Stock') inStock++;
    else if (m.status === 'Low Stock') lowStock++;
    else if (m.status === 'Critical') critical++;
  });

  return { inStock, lowStock, critical, expired };
});

const badgeClass = (status) => {
  switch (status) {
    case 'In Stock': return 'bg-emerald-100 text-emerald-700';
    case 'Low Stock': return 'bg-amber-100 text-amber-700';
    case 'Critical': return 'bg-red-100 text-red-700';
    default: return 'bg-gray-100 text-gray-700';
  }
};
</script>

