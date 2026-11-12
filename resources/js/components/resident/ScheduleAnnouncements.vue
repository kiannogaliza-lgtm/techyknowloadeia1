
<template>
  <div class="space-y-6">
    <!-- Header banner -->
    <div class="bg-white rounded-2xl shadow-[0_2px_10px_-3px_rgba(6,81,237,0.1)] border border-gray-100 p-6 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
      <div>
        <h3 class="text-xl font-bold text-[#1a5a89] flex items-center gap-2">
          <svg class="w-6 h-6 text-[#3ac1eb]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
          </svg>
          Schedule Announcements
        </h3>
        <p class="text-xs text-gray-500 mt-1">Official barangay health announcements, ultrasound &amp; immunization schedules, and clinic updates.</p>
      </div>

      <div class="flex items-center gap-2 px-3 py-1.5 rounded-xl bg-blue-50 text-[#1a5a89] text-xs font-semibold border border-blue-100">
        <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
        <span>{{ announcementsList.length }} Active Announcement{{ announcementsList.length === 1 ? '' : 's' }}</span>
      </div>
    </div>

    <!-- Announcements Cards Grid -->
    <div v-if="announcementsList.length === 0" class="bg-white rounded-2xl shadow-sm border border-gray-100 p-12 text-center text-gray-400">
      <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
        <path stroke-linecap="round" stroke-linejoin="round" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
      </svg>
      <p class="text-base font-semibold text-gray-600">No schedule announcements at this time.</p>
      <p class="text-xs text-gray-400 mt-1">Check back later for updates on health center schedules and clinic programs.</p>
    </div>

    <div v-else class="grid grid-cols-1 md:grid-cols-2 gap-5">
      <div v-for="ann in announcementsList" :key="ann.id"
           class="bg-white rounded-2xl shadow-[0_2px_10px_-3px_rgba(6,81,237,0.1)] border border-gray-100 p-6 flex flex-col justify-between hover:border-[#3ac1eb]/40 hover:shadow-md transition-all">
        <div>
          <div class="flex items-start justify-between gap-3 mb-3">
            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold uppercase tracking-wider bg-emerald-50 text-emerald-700 border border-emerald-200">
              <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
              Official Schedule
            </span>
            <span class="text-xs text-gray-400 font-medium flex items-center gap-1">
              <svg class="w-3.5 h-3.5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
              </svg>
              {{ ann.date }}
            </span>
          </div>

          <h4 class="text-base font-bold text-gray-900 mb-2 leading-snug">{{ ann.title }}</h4>
          <p class="text-sm text-gray-600 leading-relaxed whitespace-pre-line">{{ ann.content }}</p>
        </div>

        <div class="mt-5 pt-4 border-t border-gray-100 flex items-center justify-between text-xs text-gray-500">
          <span class="flex items-center gap-1.5 font-medium text-[#1a5a89]">
            <svg class="w-4 h-4 text-[#3ac1eb]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
            </svg>
            {{ ann.author || 'Barangay Health Center' }}
          </span>
          <span class="text-[11px] text-gray-400">Barangay Honrado Health System</span>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue';

const props = defineProps({
  dashboardData: {
    type: Object,
    default: () => ({})
  }
});

const announcementsList = computed(() => {
  return props.dashboardData?.announcements || [];
});
</script>

