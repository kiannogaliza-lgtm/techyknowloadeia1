<template>
  <div class="space-y-6">
    <!-- Stat Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
      <!-- This Month Total Services -->
      <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 flex items-start justify-between relative overflow-hidden group hover:shadow-md transition-shadow duration-300">
        <div>
          <div class="w-11 h-11 bg-[#e8f4fd] rounded-xl flex items-center justify-center mb-3">
            <svg class="w-6 h-6 text-[#1a5a89]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
              <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
            </svg>
          </div>
          <div class="text-xs font-semibold text-[#3ac1eb] uppercase tracking-wider mb-1">This Month</div>
          <div class="text-3xl font-bold text-gray-900 leading-none">{{ reportData?.totalServicesThisMonth || 0 }}</div>
          <div class="text-sm text-gray-500 mt-1">Total Services</div>
        </div>
        <div class="text-[#3ac1eb] opacity-60 group-hover:opacity-100 transition-opacity duration-300">
          <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18L9 11.25l4.306 4.307a11.95 11.95 0 015.814-5.519l2.74-1.22m0 0l-5.94-2.28m5.94 2.28l-2.28 5.941" />
          </svg>
        </div>
      </div>

      <!-- Walk-in Patients -->
      <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 flex items-start justify-between relative overflow-hidden group hover:shadow-md transition-shadow duration-300">
        <div>
          <div class="w-11 h-11 bg-[#e0f7ef] rounded-xl flex items-center justify-center mb-3">
            <svg class="w-6 h-6 text-[#10b981]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
              <path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z" />
            </svg>
          </div>
          <div class="text-xs font-semibold text-[#10b981] uppercase tracking-wider mb-1">Walk-in Patients</div>
          <div class="text-3xl font-bold text-gray-900 leading-none">{{ reportData?.walkInThisMonth || 0 }}</div>
          <div class="text-sm text-gray-500 mt-1">This Month</div>
        </div>
        <div class="text-[#10b981] opacity-60 group-hover:opacity-100 transition-opacity duration-300">
          <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18L9 11.25l4.306 4.307a11.95 11.95 0 015.814-5.519l2.74-1.22m0 0l-5.94-2.28m5.94 2.28l-2.28 5.941" />
          </svg>
        </div>
      </div>

      <!-- Home Services -->
      <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 flex items-start justify-between relative overflow-hidden group hover:shadow-md transition-shadow duration-300">
        <div>
          <div class="w-11 h-11 bg-[#e0f0ff] rounded-xl flex items-center justify-center mb-3">
            <svg class="w-6 h-6 text-[#0c6e3d]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
              <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
            </svg>
          </div>
          <div class="text-xs font-semibold text-[#0c6e3d] uppercase tracking-wider mb-1">Home Services</div>
          <div class="text-3xl font-bold text-gray-900 leading-none">{{ reportData?.completedHomeServicesThisMonth || 0 }}</div>
          <div class="text-sm text-gray-500 mt-1">Completed</div>
        </div>
        <div class="text-[#0c6e3d] opacity-60 group-hover:opacity-100 transition-opacity duration-300">
          <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18L9 11.25l4.306 4.307a11.95 11.95 0 015.814-5.519l2.74-1.22m0 0l-5.94-2.28m5.94 2.28l-2.28 5.941" />
          </svg>
        </div>
      </div>
    </div>

    <!-- Monthly Performance -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
      <h3 class="text-lg font-bold text-[#1a5a89] mb-5 flex items-center gap-2">
        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
        </svg>
        Monthly Performance
      </h3>

      <div class="overflow-x-auto">
        <table class="w-full text-left">
          <thead>
            <tr class="bg-[#144976] text-white text-sm">
              <th class="py-3.5 px-5 font-semibold rounded-tl-xl">Month</th>
              <th class="py-3.5 px-5 font-semibold">Walk-in Patients</th>
              <th class="py-3.5 px-5 font-semibold">Home Services</th>
              <th class="py-3.5 px-5 font-semibold rounded-tr-xl">Total</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(row, index) in monthlyPerformance" :key="index"
                class="border-b border-gray-100 hover:bg-blue-50/30 transition-colors duration-150">
              <td class="py-3.5 px-5">
                <span class="text-sm font-semibold text-[#1a5a89]">{{ row.month }}</span>
              </td>
              <td class="py-3.5 px-5">
                <span class="text-sm font-medium text-[#3ac1eb]">{{ row.walk_in_patients }}</span>
              </td>
              <td class="py-3.5 px-5">
                <span class="text-sm font-medium text-[#10b981]">{{ row.home_services }}</span>
              </td>
              <td class="py-3.5 px-5">
                <span class="text-sm font-bold text-[#1a5a89]">{{ row.total }}</span>
              </td>
            </tr>
            <tr v-if="!monthlyPerformance || monthlyPerformance.length === 0">
              <td colspan="4" class="py-8 text-center text-gray-400 text-sm">
                No performance data available yet.
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Recent Activities -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
      <h3 class="text-lg font-bold text-[#1a5a89] mb-5 flex items-center gap-2">
        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
        </svg>
        Recent Activities
      </h3>

      <div class="space-y-3">
        <div v-for="(activity, index) in activities" :key="index"
             class="bg-gray-50 rounded-xl p-4 border border-gray-100 hover:border-[#3ac1eb]/30 hover:shadow-sm transition-all duration-200">
          <div class="flex items-start justify-between">
            <div>
              <h4 class="text-sm font-bold text-gray-900">{{ activity.patient_name }}</h4>
              <p :class="[
                'text-xs font-semibold mt-0.5',
                activity.activity_type === 'Walk-in Registration' ? 'text-[#3ac1eb]' : (activity.activity_type === 'Child Vaccination' ? 'text-purple-600' : 'text-[#10b981]')
              ]">
                {{ activity.activity_type }}
              </p>
              <p class="text-xs text-gray-500 mt-1">{{ activity.service }}</p>
            </div>
            <div class="text-xs text-gray-400 font-medium whitespace-nowrap ml-4 pt-0.5">
              {{ activity.date }}
            </div>
          </div>
        </div>
        <div v-if="!activities || activities.length === 0"
             class="text-center py-8 text-gray-400 text-sm border border-dashed border-gray-200 rounded-xl bg-gray-50">
          No recent activities found.
        </div>
      </div>
    </div>

    <!-- Footer Note -->
    <div class="bg-[#e8f4fd] rounded-2xl border border-[#b8dff5] p-4 flex items-center gap-3">
      <div class="shrink-0">
        <span class="text-[#c0392b] font-bold text-sm">Note:</span>
      </div>
      <p class="text-sm text-gray-700">
        For detailed analytics and comprehensive reports, please contact the Nurse or check the main reporting system.
      </p>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue';

const props = defineProps({
  user: {
    type: Object,
    default: () => ({})
  },
  reportData: {
    type: Object,
    default: () => ({})
  }
});

const monthlyPerformance = computed(() => {
  return props.reportData?.monthlyPerformance || [];
});

const activities = computed(() => {
  return props.reportData?.reportActivities || [];
});
</script>
