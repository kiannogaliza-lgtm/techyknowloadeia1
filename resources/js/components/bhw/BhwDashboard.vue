<template>
  <div class="space-y-6">
    <!-- Stats Row -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
      <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100 flex items-center justify-between">
        <div>
          <div class="text-sm text-gray-500 font-medium mb-1">Assigned Services Today</div>
          <div class="text-3xl font-bold text-gray-900">{{ dashboardData?.assignedServicesToday || 0 }}</div>
        </div>
        <div class="w-12 h-12 bg-[#3ac1eb] rounded-xl flex items-center justify-center shrink-0">
          <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z" />
            <path stroke-linecap="round" stroke-linejoin="round" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0" />
          </svg>
        </div>
      </div>
      <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100 flex items-center justify-between">
        <div>
          <div class="text-sm text-gray-500 font-medium mb-1">Completed Services</div>
          <div class="text-3xl font-bold text-gray-900">{{ dashboardData?.completedServices || 0 }}</div>
        </div>
        <div class="w-12 h-12 bg-[#10b981] rounded-xl flex items-center justify-center shrink-0">
          <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
          </svg>
        </div>
      </div>
      <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100 flex items-center justify-between">
        <div>
          <div class="text-sm text-gray-500 font-medium mb-1">Walk-in Patients Today</div>
          <div class="text-3xl font-bold text-gray-900">{{ dashboardData?.walkInPatientsToday || 0 }}</div>
        </div>
        <div class="w-12 h-12 bg-[#0c2d48] rounded-xl flex items-center justify-center shrink-0">
          <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
          </svg>
        </div>
      </div>
      <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100 flex items-center justify-between">
        <div>
          <div class="text-sm text-gray-500 font-medium mb-1">Pending Tasks</div>
          <div class="text-3xl font-bold text-gray-900">{{ dashboardData?.pendingTasks || 0 }}</div>
        </div>
        <div class="w-12 h-12 bg-[#f97316] rounded-xl flex items-center justify-center shrink-0">
          <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
          </svg>
        </div>
      </div>
    </div>

    <!-- Expired Medicine Alert -->
    <div v-if="dashboardData?.expiredMedicinesCount > 0" class="bg-red-50 border border-red-200 rounded-2xl p-5 flex items-center gap-4 shadow-sm">
      <div class="w-12 h-12 bg-red-600 rounded-xl flex items-center justify-center text-white shrink-0 shadow-lg shadow-red-200">
        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
          <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
        </svg>
      </div>
      <div class="flex-1">
        <p class="font-bold text-red-700 text-sm">Medicine Expiration Alert</p>
        <p class="text-red-600 text-sm mt-0.5">{{ dashboardData.expiredMedicinesCount }} medicine(s) have expired. Please report this to the nurse immediately.</p>
      </div>
      <span class="text-2xl font-extrabold text-red-700">{{ dashboardData.expiredMedicinesCount }}</span>
    </div>

    <!-- Main Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
      <!-- Assigned Services -->
      <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <h3 class="text-lg font-bold text-[#1a5a89] mb-4 flex items-center gap-2">
          <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
          </svg>
          My Assigned Services
        </h3>
        
        <div class="space-y-4">
          <div v-if="!myAssignedServices || myAssignedServices.length === 0" class="text-center py-6 text-gray-500 border border-dashed border-gray-200 rounded-xl bg-gray-50">
            No assigned services pending.
          </div>
          <div v-for="service in myAssignedServices" :key="service.id" class="border border-blue-50 rounded-xl p-4 bg-blue-50/30">
            <div class="flex justify-between items-start mb-2">
              <div>
                <h4 class="font-bold text-gray-900">{{ service.patient_name }}</h4>
                <p class="text-sm text-gray-500">
                  <span v-if="service.item_type === 'schedule'" class="inline-block w-2 h-2 rounded-full bg-purple-500 mr-1"></span>
                  <span v-else class="inline-block w-2 h-2 rounded-full bg-emerald-500 mr-1"></span>
                  {{ service.service_type || service.service }}
                </p>
                <div class="text-xs text-gray-400 mt-1 flex items-center gap-2">
                  <span>{{ formatDate(service.preferred_date) }}</span>
                  <span>&bull;</span>
                  <span>{{ formatTime(service.preferred_time) }}</span>
                </div>
              </div>
              <span class="px-3 py-1 rounded-full text-xs font-semibold capitalize" 
                    :class="service.item_type === 'schedule' ? 'bg-purple-100 text-purple-700' : 'bg-blue-100 text-blue-700'">
                {{ service.item_type === 'schedule' ? (service.assignment_status === 'confirmed' ? 'Confirmed' : 'Assigned') : service.status }}
              </span>
            </div>
            
            <!-- Action Buttons based on type -->
            <div class="grid grid-cols-2 gap-3 mt-4" v-if="service.item_type === 'schedule'">
              <button 
                v-if="service.assignment_status === 'assigned'"
                @click="confirmSchedule(service)"
                :disabled="isConfirming === service.id"
                class="bg-purple-600 hover:bg-purple-700 text-white font-medium py-2 rounded-lg text-sm transition-colors shadow-sm disabled:opacity-50">
                <span v-if="isConfirming === service.id">Confirming...</span>
                <span v-else>Confirm</span>
              </button>
              <button 
                v-else
                disabled
                class="bg-green-100 text-green-700 font-medium py-2 rounded-lg text-sm transition-colors shadow-sm opacity-80 cursor-not-allowed">
                Confirmed ✓
              </button>
              <button 
                @click="viewScheduleDetails(service)"
                class="bg-gray-500 hover:bg-gray-600 text-white font-medium py-2 rounded-lg text-sm transition-colors shadow-sm">
                View Details
              </button>
            </div>
            
            <div class="grid grid-cols-2 gap-3 mt-4" v-else>
              <button class="bg-[#10b981] hover:bg-emerald-600 text-white font-medium py-2 rounded-lg text-sm transition-colors shadow-sm">
                Start Service
              </button>
              <button class="bg-gray-500 hover:bg-gray-600 text-white font-medium py-2 rounded-lg text-sm transition-colors shadow-sm">
                View Details
              </button>
            </div>
          </div>
        </div>
      </div>

      <!-- Recent Activity -->
      <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <h3 class="text-lg font-bold text-[#1a5a89] mb-4 flex items-center gap-2">
          <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
          </svg>
          Recent Activity
        </h3>

        <div class="relative pl-4 border-l-2 border-gray-100 space-y-6 mt-4">
          <div v-if="!recentActivities || recentActivities.length === 0" class="text-sm text-gray-500 py-4">
            No recent activity found.
          </div>
          <div v-for="activity in recentActivities" :key="activity.id" class="relative">
            <div class="absolute -left-[21px] top-1 w-2.5 h-2.5 rounded-full bg-[#3ac1eb] ring-4 ring-white"></div>
            <p class="text-sm text-gray-800 font-medium">{{ activity.title }}</p>
            <p class="text-xs text-gray-500">{{ activity.message }}</p>
            <p class="text-[10px] text-gray-400 mt-1">{{ activity.time_ago }}</p>
          </div>
        </div>
      </div>
    </div>

    <!-- Quick Actions Bottom -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
      <div class="bg-[#1a4a6b] rounded-2xl p-6 text-white hover:bg-[#143a54] transition-colors cursor-pointer shadow-md">
        <svg class="w-8 h-8 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
          <path stroke-linecap="round" stroke-linejoin="round" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
        </svg>
        <h4 class="font-bold text-lg">Register Walk-in Patient</h4>
        <p class="text-white/70 text-sm mt-1">Quickly register patients who visit the health center</p>
      </div>

      <div class="bg-[#3ac1eb] rounded-2xl p-6 text-white hover:bg-[#2bb4e0] transition-colors cursor-pointer shadow-md">
        <svg class="w-8 h-8 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
          <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
        </svg>
        <h4 class="font-bold text-lg">Check Inventory</h4>
        <p class="text-white/70 text-sm mt-1">View current medicine stock levels</p>
      </div>

      <div class="bg-[#10b981] rounded-2xl p-6 text-white hover:bg-emerald-600 transition-colors cursor-pointer shadow-md">
        <svg class="w-8 h-8 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
          <path stroke-linecap="round" stroke-linejoin="round" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z" />
          <path stroke-linecap="round" stroke-linejoin="round" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0" />
        </svg>
        <h4 class="font-bold text-lg">Update Service Status</h4>
        <p class="text-white/70 text-sm mt-1">Mark home services as completed</p>
      </div>
    </div>
    
    <!-- View Schedule Details Modal -->
    <div v-if="selectedSchedule" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center p-4">
      <div class="bg-white rounded-2xl p-6 w-full max-w-md shadow-xl transform transition-all">
        <div class="flex justify-between items-center mb-5">
          <h3 class="text-xl font-bold text-gray-900 flex items-center gap-2">
            <span class="material-icons-outlined text-purple-600">event_note</span>
            Schedule Details
          </h3>
          <button @click="selectedSchedule = null" class="text-gray-400 hover:text-gray-600">
            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
          </button>
        </div>
        
        <div class="space-y-4">
          <div class="bg-purple-50 rounded-xl p-4 border border-purple-100">
            <p class="text-xs text-purple-600 font-bold uppercase tracking-wider mb-1">Appointment ID</p>
            <p class="font-mono font-medium text-gray-900">{{ selectedSchedule.formatted_id }}</p>
          </div>
          
          <div class="grid grid-cols-2 gap-4">
            <div>
              <p class="text-xs text-gray-500 font-medium mb-1">Patient Name</p>
              <p class="font-semibold text-gray-900">{{ selectedSchedule.patient_name }}</p>
            </div>
            <div>
              <p class="text-xs text-gray-500 font-medium mb-1">Service</p>
              <p class="font-semibold text-gray-900">{{ selectedSchedule.service }}</p>
            </div>
            <div>
              <p class="text-xs text-gray-500 font-medium mb-1">Date</p>
              <p class="font-semibold text-gray-900">{{ formatDate(selectedSchedule.preferred_date) }}</p>
            </div>
            <div>
              <p class="text-xs text-gray-500 font-medium mb-1">Time</p>
              <p class="font-semibold text-gray-900">{{ formatTime(selectedSchedule.preferred_time) }}</p>
            </div>
          </div>
        </div>
        
        <div class="mt-8 flex justify-end gap-3">
          <button @click="selectedSchedule = null" class="px-5 py-2.5 bg-gray-100 text-gray-700 rounded-lg font-medium text-sm hover:bg-gray-200 transition-colors">
            Close
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { computed, ref } from 'vue';

const props = defineProps({
  user: {
    type: Object,
    required: true
  },
  dashboardData: {
    type: Object,
    default: () => ({
      assignedServicesToday: 0,
      completedServices: 0,
      walkInPatientsToday: 0,
      pendingTasks: 0
    })
  },
  myAssignedServices: {
    type: Array,
    default: () => []
  },
  recentActivities: {
    type: Array,
    default: () => []
  },
  bhwConfirmScheduleUrl: {
    type: String,
    default: ''
  }
});

const isConfirming = ref(null);
const selectedSchedule = ref(null);

const confirmSchedule = async (schedule) => {
  if (!props.bhwConfirmScheduleUrl) return;
  
  isConfirming.value = schedule.id;
  try {
    const response = await fetch(`${props.bhwConfirmScheduleUrl}/${schedule.id}/confirm`, {
      method: 'PATCH',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
      }
    });
    
    if (response.ok) {
      schedule.assignment_status = 'confirmed';
    }
  } catch (error) {
    console.error("Error confirming schedule:", error);
  } finally {
    isConfirming.value = null;
  }
};

const viewScheduleDetails = (schedule) => {
  selectedSchedule.value = schedule;
};

const formatDate = (dateString) => {
  if (!dateString) return '';
  const d = new Date(dateString);
  return isNaN(d) ? dateString : d.toISOString().split('T')[0];
};

const formatTime = (timeString) => {
  if (!timeString) return '';
  // Try parsing to date if it's an ISO string
  if (timeString.includes('T')) {
    const d = new Date(timeString);
    if (!isNaN(d)) {
      return d.toLocaleTimeString([], { hour: 'numeric', minute: '2-digit' });
    }
  }
  // Otherwise it might just be HH:mm:ss
  let parts = timeString.split(':');
  if (parts.length >= 2) {
    let hours = parseInt(parts[0], 10);
    const minutes = parts[1];
    const ampm = hours >= 12 ? 'PM' : 'AM';
    hours = hours % 12;
    hours = hours ? hours : 12; // the hour '0' should be '12'
    return `${hours}:${minutes} ${ampm}`;
  }
  return timeString; // Fallback
};
</script>
