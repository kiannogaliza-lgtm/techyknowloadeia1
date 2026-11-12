<template>
  <div class="flex flex-col gap-6">
    <!-- Top Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
      
      <!-- Active Requests -->
      <div class="bg-white rounded-2xl p-6 shadow-[0_2px_10px_-3px_rgba(6,81,237,0.1)] border border-gray-100 flex items-center justify-between hover:-translate-y-1 transition-transform duration-300">
        <div>
          <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Active Requests</p>
          <h3 class="text-3xl font-bold text-gray-800">{{ dashboardData.activeRequestsCount }}</h3>
        </div>
        <div class="w-12 h-12 bg-[#3ac1eb] rounded-2xl flex items-center justify-center text-white shadow-lg shadow-cyan-200">
          <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
          </svg>
        </div>
      </div>

      <!-- Completed -->
      <div class="bg-white rounded-2xl p-6 shadow-[0_2px_10px_-3px_rgba(6,81,237,0.1)] border border-gray-100 flex items-center justify-between hover:-translate-y-1 transition-transform duration-300">
        <div>
          <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Completed</p>
          <h3 class="text-3xl font-bold text-gray-800">{{ dashboardData.completedCount }}</h3>
        </div>
        <div class="w-12 h-12 bg-[#10b981] rounded-2xl flex items-center justify-center text-white shadow-lg shadow-emerald-200">
          <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
          </svg>
        </div>
      </div>

      <!-- Upcoming Schedules -->
      <div class="bg-white rounded-2xl p-6 shadow-[0_2px_10px_-3px_rgba(6,81,237,0.1)] border border-gray-100 flex items-center justify-between hover:-translate-y-1 transition-transform duration-300">
        <div>
          <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Upcoming Schedules</p>
          <h3 class="text-3xl font-bold text-gray-800">{{ dashboardData.upcomingSchedulesCount }}</h3>
        </div>
        <div class="w-12 h-12 bg-[#1e3a8a] rounded-2xl flex items-center justify-center text-white shadow-lg shadow-blue-200">
          <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
          </svg>
        </div>
      </div>

      <!-- Notifications -->
      <div class="bg-white rounded-2xl p-6 shadow-[0_2px_10px_-3px_rgba(6,81,237,0.1)] border border-gray-100 flex items-center justify-between hover:-translate-y-1 transition-transform duration-300">
        <div>
          <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Notifications</p>
          <h3 class="text-3xl font-bold text-gray-800">0</h3>
        </div>
        <div class="w-12 h-12 bg-[#f97316] rounded-2xl flex items-center justify-center text-white shadow-lg shadow-orange-200">
          <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
          </svg>
        </div>
      </div>
      
    </div>

    <!-- Maintenance Status Banner -->
    <div v-if="user && user.is_maintenance" class="bg-gradient-to-r from-amber-50 to-orange-50 border border-amber-200 rounded-2xl p-5 flex items-center justify-between shadow-xs">
      <div class="flex items-center gap-4">
        <div class="w-12 h-12 bg-amber-500 rounded-xl flex items-center justify-center text-white shadow-md shadow-amber-200">
          <span class="material-icons-outlined text-2xl">medication</span>
        </div>
        <div>
          <div class="flex items-center gap-2">
            <h4 class="text-base font-bold text-amber-950">Maintenance Medication Program</h4>
            <span class="inline-flex items-center text-xs font-semibold px-2 py-0.5 rounded-full bg-amber-200/80 text-amber-900">Enrolled</span>
          </div>
          <p class="text-xs text-amber-800/80 mt-0.5">You are registered as a maintenance patient. Remember to request your regular refills on schedule.</p>
        </div>
      </div>
    </div>

    <!-- Main Panels -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
      
      <!-- Available Medicines -->
      <div class="bg-white rounded-2xl shadow-[0_2px_10px_-3px_rgba(6,81,237,0.1)] border border-gray-100 flex flex-col">
        <div class="p-6 border-b border-gray-100 flex items-center gap-3">
          <svg class="w-5 h-5 text-[#3ac1eb]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
          </svg>
          <h3 class="font-bold text-[#1a5a89] text-lg">Available Medicines</h3>
        </div>
        <div class="p-6 flex-1 flex flex-col gap-3">
          
          <div v-if="dashboardData.medicines.length === 0" class="text-sm text-gray-500 text-center py-4">No medicines available.</div>
          
          <div v-for="med in dashboardData.medicines" :key="med.id" class="flex items-center justify-between bg-[#f0f9ff] p-4 rounded-xl border border-blue-50/50 hover:bg-[#e0f2fe] transition-colors">
            <div>
              <h4 class="font-semibold text-gray-800 text-sm mb-0.5">{{ med.name }}</h4>
              <p class="text-xs text-gray-500">{{ med.stock }} {{ med.unit }}</p>
            </div>
            <span :class="['px-3 py-1 text-xs font-semibold rounded-full', med.status === 'In Stock' ? 'bg-green-100 text-green-700' : (med.status === 'Low Stock' ? 'bg-yellow-100 text-yellow-700' : 'bg-red-100 text-red-700')]">
              {{ med.status }}
            </span>
          </div>

        </div>
      </div>

      <!-- Recent Requests -->
      <div class="bg-white rounded-2xl shadow-[0_2px_10px_-3px_rgba(6,81,237,0.1)] border border-gray-100 flex flex-col">
        <div class="p-6 border-b border-gray-100 flex items-center gap-3">
          <svg class="w-5 h-5 text-[#3ac1eb]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z" />
          </svg>
          <h3 class="font-bold text-[#1a5a89] text-lg">Recent Requests</h3>
        </div>
        <div class="p-6 flex-1 flex flex-col gap-3">
          
          <div v-if="dashboardData.recentRequests.length === 0" class="text-sm text-gray-500 text-center py-4">No recent requests.</div>
          
          <div v-for="req in dashboardData.recentRequests" :key="req.id + req.type" class="flex items-center justify-between bg-[#f8fafc] p-4 rounded-xl border border-gray-50 hover:bg-gray-50 transition-colors">
            <div>
              <h4 class="font-semibold text-gray-800 text-sm mb-0.5">{{ req.name }}</h4>
              <p class="text-[11px] text-gray-500">{{ req.type }} &bull; {{ req.date }}</p>
            </div>
            <span :class="['px-3 py-1 text-xs font-semibold rounded-full capitalize', getStatusClass(req.status)]">
              {{ req.status }}
            </span>
          </div>

        </div>
      </div>

    </div>

    <!-- Announcements -->
    <div class="bg-white rounded-2xl shadow-[0_2px_10px_-3px_rgba(6,81,237,0.1)] border border-gray-100">
      <div class="p-6 border-b border-gray-100 flex items-center gap-3">
        <svg class="w-5 h-5 text-[#3ac1eb]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
        </svg>
        <h3 class="font-bold text-[#1a5a89] text-lg">Barangay Health Announcements</h3>
      </div>
      <div class="p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div v-if="dashboardData.announcements.length === 0" class="text-sm text-gray-500 py-4 col-span-2">No announcements at this time.</div>
          <div v-for="(ann, idx) in dashboardData.announcements" :key="idx" class="bg-[#f8fafc] p-5 rounded-xl border border-gray-100">
            <h4 class="font-bold text-gray-800 text-sm mb-1">{{ ann.title }}</h4>
            <p class="text-xs text-[#3ac1eb] font-medium mb-3">{{ ann.date }}</p>
            <p class="text-sm text-gray-600 leading-relaxed">{{ ann.content }}</p>
          </div>
        </div>
      </div>
    </div>
    
  </div>
</template>

<script setup>
defineProps({
  user: {
    type: Object,
    required: true
  },
  dashboardData: {
    type: Object,
    required: true
  }
});

const getStatusClass = (status) => {
  const s = status.toLowerCase();
  if (s === 'approved' || s === 'completed') return 'bg-green-100 text-green-700';
  if (s === 'accepted') return 'bg-blue-100 text-blue-700';
  if (s === 'pending') return 'bg-yellow-100 text-yellow-700';
  if (s === 'scheduled' || s === 'assigned') return 'bg-blue-100 text-blue-700';
  if (s === 'rejected') return 'bg-red-100 text-red-700';
  return 'bg-gray-100 text-gray-700';
};
</script>
