<template>
  <div class="flex h-screen bg-[#f3f6f9] font-sans overflow-hidden relative">
    <!-- Mobile Sidebar Backdrop Overlay -->
    <div v-if="mobileMenuOpen"
         @click="mobileMenuOpen = false"
         class="fixed inset-0 bg-gray-900/50 backdrop-blur-xs z-40 lg:hidden transition-opacity"></div>

    <!-- Sidebar -->
    <aside :class="['w-[260px] text-white flex flex-col h-full shrink-0 z-50 fixed inset-y-0 left-0 lg:static transition-transform duration-300 ease-in-out', mobileMenuOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0']"
           style="background: linear-gradient(180deg, #0c2d48 0%, #0a2540 50%, #081d33 100%)">
      <div class="p-5 flex items-center justify-between h-[72px] border-b border-white/10">
        <div class="flex items-center gap-3">
          <img src="/ico.jpg" alt="MEDLINK Logo" class="w-10 h-10 rounded-xl object-cover shrink-0 shadow-lg">
          <div class="leading-tight">
            <div class="font-extrabold tracking-wider text-base text-white">MEDLINK</div>
            <div class="text-[10px] text-cyan-200 font-semibold tracking-widest uppercase">Patient Portal</div>
          </div>
        </div>
        <!-- Close Mobile Button -->
        <button @click="mobileMenuOpen = false" class="lg:hidden text-gray-400 hover:text-white p-1 rounded-lg">
          <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
          </svg>
        </button>
      </div>
      
      <nav class="flex-1 py-6 flex flex-col gap-2 px-4 overflow-y-auto">
        <button v-for="tab in tabs" :key="tab.id" @click="handleTabSelect(tab.id)"
                :class="['flex items-center justify-between px-4 py-3.5 rounded-xl text-sm font-medium transition-all duration-200 text-left w-full cursor-pointer', currentTab === tab.id ? 'text-white translate-x-0.5' : 'text-[#94a3b8] hover:bg-white/8 hover:text-[#e2e8f0] hover:translate-x-0.5']"
                :style="currentTab === tab.id ? 'background:#2563eb; box-shadow:0 4px 12px rgba(37,99,235,0.35)' : ''">
          <div class="flex items-center gap-3 min-w-0">
            <span v-html="tab.icon" class="w-5 h-5 flex items-center justify-center shrink-0"></span>
            <span class="truncate">{{ tab.name }}</span>
          </div>
        </button>
      </nav>

      <div class="p-6 text-xs text-white/40 mb-2 leading-relaxed">
        Barangay Honrado<br>Health System v1.0
      </div>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 flex flex-col overflow-hidden relative min-w-0">
      <!-- Header -->
      <header class="h-[64px] sm:h-[72px] bg-white flex items-center justify-between px-3 sm:px-8 shrink-0 relative z-10 border-b border-gray-100">
        <div class="flex items-center gap-3 min-w-0">
          <!-- Mobile Hamburger Button -->
          <button @click="mobileMenuOpen = true" class="lg:hidden p-2 rounded-lg text-gray-600 hover:text-gray-900 hover:bg-gray-100 focus:outline-none cursor-pointer" aria-label="Toggle Menu">
            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
          </button>
          <div class="min-w-0">
            <h2 class="text-lg sm:text-2xl font-semibold text-[#1a5a89] tracking-tight truncate">{{ activeTabName }}</h2>
            <p class="text-xs sm:text-sm text-gray-500 mt-0.5 hidden sm:block truncate">Welcome back, {{ currentUser.first_name }} {{ currentUser.last_name }}</p>
          </div>
        </div>
        
        <div class="flex items-center gap-2 sm:gap-6">
          <div class="relative hidden sm:block" id="resident-search-container">
            <svg class="w-4 h-4 absolute left-4 top-1/2 -translate-y-1/2 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
            <input type="text"
                   v-model="portalSearchQuery"
                   @input="handleSearchInput"
                   placeholder="Search..." 
                   class="pl-11 pr-8 py-2 bg-gray-50 border border-gray-200 rounded-full text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-[#3ac1eb]/50 focus:border-[#3ac1eb] w-48 lg:w-72 transition-all">
            <button v-if="portalSearchQuery"
                    @click="clearPortalSearch"
                    class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 cursor-pointer">
              <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
              </svg>
            </button>

            <!-- Search Results Dropdown -->
            <div v-if="showSearchResults"
                 class="absolute left-0 mt-2 w-96 bg-white rounded-2xl shadow-2xl border border-gray-100 overflow-hidden z-50">
              <div class="px-4 py-2.5 bg-gray-50/80 border-b border-gray-100 flex items-center justify-between text-xs text-gray-500 font-semibold">
                <span>Search Results</span>
                <span class="text-[#1a5a89] font-bold">{{ searchResults.length }} found</span>
              </div>
              <div class="max-h-80 overflow-y-auto divide-y divide-gray-50">
                <div v-if="isSearching" class="px-4 py-6 text-center text-gray-400">
                  <span class="material-icons-outlined animate-spin text-[#1a5a89] mb-1" style="font-size: 24px;">sync</span>
                  <p class="text-xs">Searching...</p>
                </div>
                <template v-else-if="searchResults.length > 0">
                  <button v-for="(item, idx) in searchResults"
                          :key="idx"
                          @click="selectSearchResult(item)"
                          class="w-full text-left px-4 py-3 hover:bg-blue-50/60 transition-colors group">
                    <div class="flex items-start gap-3">
                      <div class="w-8 h-8 rounded-lg bg-blue-50 text-[#1a5a89] flex items-center justify-center shrink-0 mt-0.5 group-hover:bg-[#1a5a89] group-hover:text-white transition-colors">
                        <span class="material-icons-outlined" style="font-size: 18px;">{{ item.icon || 'search' }}</span>
                      </div>
                      <div class="flex-1 min-w-0">
                        <div class="flex items-center justify-between gap-2">
                          <p class="text-xs font-bold text-gray-800 truncate group-hover:text-[#1a5a89]">{{ item.title }}</p>
                          <span class="text-[10px] bg-gray-100 text-gray-600 px-1.5 py-0.5 rounded font-medium shrink-0">{{ item.category }}</span>
                        </div>
                        <p class="text-[11px] text-gray-500 truncate mt-0.5">{{ item.subtitle }}</p>
                      </div>
                    </div>
                  </button>
                </template>
                <div v-else class="px-4 py-8 text-center text-gray-400">
                  <span class="material-icons-outlined mb-2 text-gray-300" style="font-size: 32px;">search_off</span>
                  <p class="text-xs font-semibold text-gray-600">No results found</p>
                  <p class="text-[11px] text-gray-400 mt-0.5">No match for "{{ portalSearchQuery }}"</p>
                </div>
              </div>
            </div>
          </div>
          
          <!-- Notifications Dropdown (Just like Nurse/BHW Portal) -->
          <div class="relative" id="resident-notification-dropdown-container">
            <button @click="toggleNotificationDropdown" class="relative text-gray-500 hover:text-[#1a5a89] transition-colors p-2 bg-gray-50 rounded-full border border-gray-100 focus:outline-none cursor-pointer">
              <span class="material-icons-outlined block" style="font-size:22px;">notifications</span>
              <span v-if="totalBadgeCount > 0" class="absolute -top-1 -right-1 w-5 h-5 bg-red-500 border-2 border-white rounded-full text-[10px] text-white flex items-center justify-center font-bold">
                {{ totalBadgeCount > 9 ? '9+' : totalBadgeCount }}
              </span>
            </button>

            <!-- Dropdown Panel -->
            <div v-if="showNotificationDropdown"
                 class="absolute right-0 mt-3 w-80 bg-white rounded-2xl shadow-2xl border border-gray-100 overflow-hidden z-50 transform origin-top-right transition-all">
              <div class="px-5 py-3.5 border-b border-gray-100 bg-gray-50/70 flex justify-between items-center">
                <div class="flex items-center gap-2">
                  <span class="material-icons-outlined text-[#1a5a89]" style="font-size:18px;">notifications</span>
                  <h3 class="text-sm font-bold text-gray-800">Notifications</h3>
                </div>
                <div class="flex items-center gap-2">
                  <span class="text-xs font-semibold bg-blue-100 text-blue-700 py-0.5 px-2.5 rounded-full">
                    {{ totalBadgeCount }} New
                  </span>
                  <button v-if="totalBadgeCount > 0" @click="markAllAsRead" class="text-[11px] text-blue-600 hover:text-blue-800 font-medium cursor-pointer">
                    Mark read
                  </button>
                </div>
              </div>

              <div class="max-h-96 overflow-y-auto divide-y divide-gray-50">
                <!-- Pending Medicine Requests -->
                <a v-if="pendingMedicineRequestsCount > 0"
                   href="#" @click.prevent="goToTab('medicine')"
                   class="block px-4 py-3.5 hover:bg-blue-50/60 transition-colors">
                  <div class="flex gap-3 items-start">
                    <div class="w-9 h-9 rounded-full bg-amber-100 text-amber-600 flex items-center justify-center shrink-0 mt-0.5">
                      <span class="material-icons-outlined" style="font-size: 18px;">pending_actions</span>
                    </div>
                    <div class="flex-1 min-w-0">
                      <div class="flex items-center justify-between">
                        <p class="text-xs font-bold text-gray-800">Pending Requests</p>
                        <span class="text-[10px] bg-amber-100 text-amber-700 px-1.5 py-0.2 rounded font-semibold">{{ pendingMedicineRequestsCount }}</span>
                      </div>
                      <p class="text-xs text-gray-500 mt-0.5 leading-relaxed">
                        You have {{ pendingMedicineRequestsCount }} medicine request(s) awaiting nurse review.
                      </p>
                    </div>
                  </div>
                </a>

                <!-- Accepted Medicine Requests (Ready for Consultation) -->
                <a v-if="acceptedMedicineRequestsCount > 0"
                   href="#" @click.prevent="goToTab('medicine')"
                   class="block px-4 py-3.5 hover:bg-blue-50/60 transition-colors">
                  <div class="flex gap-3 items-start">
                    <div class="w-9 h-9 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center shrink-0 mt-0.5">
                      <span class="material-icons-outlined" style="font-size: 18px;">local_hospital</span>
                    </div>
                    <div class="flex-1 min-w-0">
                      <div class="flex items-center justify-between">
                        <p class="text-xs font-bold text-gray-800">Ready for Consultation</p>
                        <span class="text-[10px] bg-blue-100 text-blue-700 px-1.5 py-0.2 rounded font-semibold">{{ acceptedMedicineRequestsCount }}</span>
                      </div>
                      <p class="text-xs text-gray-500 mt-0.5 leading-relaxed">
                        {{ acceptedMedicineRequestsCount }} request(s) accepted. Please visit the health center for consultation.
                      </p>
                    </div>
                  </div>
                </a>

                <!-- Approved Medicine Requests (Ready for Pickup) -->
                <a v-if="approvedMedicineRequestsCount > 0"
                   href="#" @click.prevent="goToTab('medicine')"
                   class="block px-4 py-3.5 hover:bg-blue-50/60 transition-colors">
                  <div class="flex gap-3 items-start">
                    <div class="w-9 h-9 rounded-full bg-green-100 text-green-600 flex items-center justify-center shrink-0 mt-0.5">
                      <span class="material-icons-outlined" style="font-size: 18px;">check_circle</span>
                    </div>
                    <div class="flex-1 min-w-0">
                      <div class="flex items-center justify-between">
                        <p class="text-xs font-bold text-gray-800">Ready for Pickup</p>
                        <span class="text-[10px] bg-green-100 text-green-700 px-1.5 py-0.2 rounded font-semibold">{{ approvedMedicineRequestsCount }}</span>
                      </div>
                      <p class="text-xs text-gray-500 mt-0.5 leading-relaxed">
                        {{ approvedMedicineRequestsCount }} request(s) approved! You can now pick up your medicine.
                      </p>
                    </div>
                  </div>
                </a>

                <!-- Returned / Broken Medicines -->
                <a v-if="returnedMedicinesCount > 0"
                   href="#" @click.prevent="goToTab('returned-medicines')"
                   class="block px-4 py-3.5 hover:bg-amber-50/60 transition-colors">
                  <div class="flex gap-3 items-start">
                    <div class="w-9 h-9 rounded-full bg-amber-100 text-amber-700 flex items-center justify-center shrink-0 mt-0.5">
                      <span class="material-icons-outlined" style="font-size: 18px;">assignment_return</span>
                    </div>
                    <div class="flex-1 min-w-0">
                      <div class="flex items-center justify-between">
                        <p class="text-xs font-bold text-gray-800">Returned Medicines</p>
                        <span class="text-[10px] bg-amber-100 text-amber-800 px-1.5 py-0.2 rounded font-semibold">{{ returnedMedicinesCount }}</span>
                      </div>
                      <p class="text-xs text-gray-500 mt-0.5 leading-relaxed">
                        {{ returnedMedicinesCount }} medicine item(s) logged as returned / damaged.
                      </p>
                    </div>
                  </div>
                </a>

                <!-- Pending Home Service Requests -->
                <a v-if="pendingHomeServicesCount > 0"
                   href="#" @click.prevent="goToTab('service')"
                   class="block px-4 py-3.5 hover:bg-blue-50/60 transition-colors">
                  <div class="flex gap-3 items-start">
                    <div class="w-9 h-9 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center shrink-0 mt-0.5">
                      <span class="material-icons-outlined" style="font-size: 18px;">home_health</span>
                    </div>
                    <div class="flex-1 min-w-0">
                      <div class="flex items-center justify-between">
                        <p class="text-xs font-bold text-gray-800">Home Services</p>
                        <span class="text-[10px] bg-emerald-100 text-emerald-700 px-1.5 py-0.2 rounded font-semibold">{{ pendingHomeServicesCount }}</span>
                      </div>
                      <p class="text-xs text-gray-500 mt-0.5 leading-relaxed">
                        You have {{ pendingHomeServicesCount }} active home service request(s).
                      </p>
                    </div>
                  </div>
                </a>

                <!-- Upcoming Schedules -->
                <a v-if="upcomingSchedulesCount > 0"
                   href="#" @click.prevent="goToTab('schedule')"
                   class="block px-4 py-3.5 hover:bg-blue-50/60 transition-colors">
                  <div class="flex gap-3 items-start">
                    <div class="w-9 h-9 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center shrink-0 mt-0.5">
                      <span class="material-icons-outlined" style="font-size: 18px;">calendar_month</span>
                    </div>
                    <div class="flex-1 min-w-0">
                      <div class="flex items-center justify-between">
                        <p class="text-xs font-bold text-gray-800">Upcoming Schedules</p>
                        <span class="text-[10px] bg-indigo-100 text-indigo-700 px-1.5 py-0.2 rounded font-semibold">{{ upcomingSchedulesCount }}</span>
                      </div>
                      <p class="text-xs text-gray-500 mt-0.5 leading-relaxed">
                        You have {{ upcomingSchedulesCount }} confirmed appointment(s) coming up.
                      </p>
                    </div>
                  </div>
                </a>

                <!-- Individual Notifications List -->
                <template v-if="localNotificationsList.length > 0">
                  <div v-for="notif in localNotificationsList.slice(0, 6)" :key="notif.id"
                       :class="['px-4 py-3 transition-colors', !notif.is_read ? 'bg-blue-50/40 hover:bg-blue-50/70' : 'hover:bg-gray-50']">
                    <div class="flex gap-3 items-start">
                      <div :class="['w-8 h-8 rounded-full flex items-center justify-center shrink-0 mt-0.5', notif.type === 'success' ? 'bg-green-100 text-green-600' : (notif.type === 'error' ? 'bg-red-100 text-red-600' : 'bg-blue-100 text-blue-600')]">
                        <span class="material-icons-outlined" style="font-size: 16px;">
                          {{ notif.type === 'success' ? 'check_circle' : (notif.type === 'error' ? 'cancel' : 'info') }}
                        </span>
                      </div>
                      <div class="flex-1 min-w-0">
                        <p class="text-xs font-bold text-gray-800 truncate">{{ notif.title }}</p>
                        <p class="text-xs text-gray-500 mt-0.5 leading-snug line-clamp-2">{{ notif.message }}</p>
                        <span class="text-[10px] text-gray-400 mt-1 block">{{ notif.time_ago || 'Recent' }}</span>
                      </div>
                      <span v-if="!notif.is_read" class="w-2 h-2 rounded-full bg-blue-500 shrink-0 mt-1.5"></span>
                    </div>
                  </div>
                </template>

                <!-- Empty State -->
                <div v-if="totalBadgeCount === 0 && localNotificationsList.length === 0" class="px-4 py-8 text-center text-gray-400">
                  <span class="material-icons-outlined mb-2 text-gray-300" style="font-size: 36px;">notifications_none</span>
                  <p class="text-xs font-semibold text-gray-600">You're all caught up!</p>
                  <p class="text-[11px] text-gray-400 mt-0.5">No pending tasks or unread notifications.</p>
                </div>
              </div>

              <!-- Footer View All -->
              <div class="p-2.5 bg-gray-50/80 border-t border-gray-100 text-center">
                <button @click="goToTab('notifications')" class="text-xs text-[#1a5a89] hover:text-[#0c2d48] font-bold transition-colors cursor-pointer">
                  View All Notifications &rarr;
                </button>
              </div>
            </div>
          </div>
          
          <div class="flex items-center gap-2 sm:gap-4 border-l border-gray-100 pl-3 sm:pl-6">
             <div class="text-right hidden md:block">
                <div class="text-xs sm:text-sm font-semibold text-gray-700">{{ currentUser.first_name }} {{ currentUser.last_name }}</div>
                <div class="text-[10px] sm:text-[11px] text-green-500 font-medium">Online</div>
             </div>
             <img v-if="currentUser.profile_photo" :src="'/storage/' + currentUser.profile_photo" :alt="currentUser.first_name" class="w-8 h-8 sm:w-10 sm:h-10 rounded-full object-cover shadow-md shadow-[#1a5a89]/20 border border-gray-200 shrink-0">
             <div v-else class="w-8 h-8 sm:w-10 sm:h-10 bg-[#1a5a89] text-white rounded-full flex items-center justify-center font-bold shadow-md shadow-[#1a5a89]/20 text-xs sm:text-sm shrink-0">
                {{ currentUser.first_name ? currentUser.first_name.charAt(0) : 'U' }}
             </div>
             <form method="POST" action="/logout" class="ml-1 sm:ml-2 border-l border-gray-100 pl-3 sm:pl-6">
               <input type="hidden" name="_token" :value="csrfToken">
               <button type="submit" class="text-gray-400 hover:text-red-500 transition-colors bg-gray-50 p-1.5 sm:p-2 rounded-full border border-gray-100 cursor-pointer" title="Logout">
                 <svg class="w-4 h-4 sm:w-5 sm:h-5 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                 </svg>
               </button>
             </form>
          </div>
        </div>
      </header>
      
      <!-- Content Area -->
      <div class="flex-1 overflow-auto p-3 sm:p-6 lg:p-8 relative z-0">
        <component
          :is="currentComponent"
          :user="currentUser"
          :dashboard-data="dashboardData"
          :my-medicine-requests="myMedicineRequests"
          :my-medicine-returns="myMedicineReturns"
          :medicine-request-store-url="medicineRequestStoreUrl"
          :home-service-store-url="homeServiceStoreUrl"
          :booked-home-services="bookedHomeServices"
          :my-schedules="mySchedules"
          :booked-schedules="bookedSchedules"
          :schedule-store-url="scheduleStoreUrl"
          :notifications="notifications"
          :mark-read-url="markReadUrl"
          :profile-update-url="profileUpdateUrl"
          @profile-updated="handleProfileUpdated"
        />
      </div>

      <!-- Floating help button -->
      <button class="fixed bottom-6 right-6 w-12 h-12 bg-gray-800 text-white rounded-full flex items-center justify-center shadow-lg hover:bg-gray-700 transition-colors z-50">
        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
      </button>
    </main>
  </div>
</template>

<script setup>
import { ref, computed, onMounted, onBeforeUnmount } from 'vue';
import ResidentDashboard from './ResidentDashboard.vue';
import RequestMedicine from './RequestMedicine.vue';
import ReturnedMedicines from './ReturnedMedicines.vue';
import HomeService from './HomeService.vue';
import ScheduleAnnouncements from './ScheduleAnnouncements.vue';
import Notifications from './Notifications.vue';
import Profile from './Profile.vue';

const props = defineProps({
  user: {
    type: Object,
    required: true
  },
  dashboardData: {
    type: Object,
    required: true
  },
  myMedicineRequests: {
    type: Array,
    default: () => []
  },
  myMedicineReturns: {
    type: Array,
    default: () => []
  },
  medicineRequestStoreUrl: {
    type: String,
    default: ''
  },
  homeServiceStoreUrl: {
    type: String,
    default: ''
  },
  bookedHomeServices: {
    type: Array,
    default: () => []
  },
  mySchedules: {
    type: Array,
    default: () => []
  },
  bookedSchedules: {
    type: Array,
    default: () => []
  },
  scheduleStoreUrl: {
    type: String,
    default: ''
  },
  notifications: {
    type: Array,
    default: () => []
  },
  notificationSummary: {
    type: Object,
    default: () => ({})
  },
  markReadUrl: {
    type: String,
    default: ''
  },
  profileUpdateUrl: {
    type: String,
    default: ''
  }
});

const currentUser = ref({ ...props.user });

const handleProfileUpdated = (updatedUser) => {
  currentUser.value = { ...currentUser.value, ...updatedUser };
};

// ── Notification dropdown state ──
const showNotificationDropdown = ref(false);
const localNotificationsList = ref([]);
const mobileMenuOpen = ref(false);
const csrfToken = ref('');

function handleTabSelect(tabId) {
  currentTab.value = tabId;
  mobileMenuOpen.value = false;
}

onMounted(() => {
  const tokenMeta = document.querySelector('meta[name="csrf-token"]');
  if (tokenMeta) {
    csrfToken.value = tokenMeta.getAttribute('content');
  }
  // Initialise local notifications list from props
  localNotificationsList.value = [...props.notifications];
  // Close dropdown when clicking outside
  document.addEventListener('click', handleClickOutside);
  window.addEventListener('notifications-read', markAllAsRead);
});

onBeforeUnmount(() => {
  document.removeEventListener('click', handleClickOutside);
  window.removeEventListener('notifications-read', markAllAsRead);
});

function handleClickOutside(e) {
  const container = document.getElementById('resident-notification-dropdown-container');
  if (container && !container.contains(e.target)) {
    showNotificationDropdown.value = false;
  }
  const searchContainer = document.getElementById('resident-search-container');
  if (searchContainer && !searchContainer.contains(e.target)) {
    showSearchResults.value = false;
  }
}

// ── Search functionality ──
const portalSearchQuery = ref('');
const showSearchResults = ref(false);
const searchResults = ref([]);
const isSearching = ref(false);
let searchDebounceTimer = null;

function handleSearchInput() {
  const q = portalSearchQuery.value.trim();
  if (!q) {
    showSearchResults.value = false;
    searchResults.value = [];
    return;
  }
  showSearchResults.value = true;
  clearTimeout(searchDebounceTimer);
  searchDebounceTimer = setTimeout(() => {
    performGlobalSearch(q);
  }, 250);
}

function clearPortalSearch() {
  portalSearchQuery.value = '';
  showSearchResults.value = false;
  searchResults.value = [];
}

async function performGlobalSearch(q) {
  isSearching.value = true;
  try {
    const res = await fetch(`/api/search?q=${encodeURIComponent(q)}`, {
      headers: {
        'Accept': 'application/json',
        'X-Requested-With': 'XMLHttpRequest'
      }
    });
    if (res.ok) {
      const data = await res.json();
      searchResults.value = data.results || [];
    }
  } catch (err) {
    console.error('Resident global search error', err);
    searchResults.value = [];
  } finally {
    isSearching.value = false;
  }
}

function selectSearchResult(item) {
  if (item.tab) {
    goToTab(item.tab);
  }
  showSearchResults.value = false;
}

function toggleNotificationDropdown() {
  showNotificationDropdown.value = !showNotificationDropdown.value;
}

// ── Operational notification counts (from notificationSummary prop) ──
const dismissedCategories = ref(false);

const pendingMedicineRequestsCount = computed(() => dismissedCategories.value ? 0 : (props.notificationSummary?.pendingMedicineRequests || 0));
const acceptedMedicineRequestsCount = computed(() => dismissedCategories.value ? 0 : (props.notificationSummary?.acceptedMedicineRequests || 0));
const approvedMedicineRequestsCount = computed(() => dismissedCategories.value ? 0 : (props.notificationSummary?.approvedMedicineRequests || 0));
const returnedMedicinesCount = computed(() => dismissedCategories.value ? 0 : (props.notificationSummary?.returnedMedicines || (props.myMedicineReturns?.length || 0)));
const pendingHomeServicesCount = computed(() => dismissedCategories.value ? 0 : (props.notificationSummary?.pendingHomeServices || 0));
const upcomingSchedulesCount = computed(() => dismissedCategories.value ? 0 : (props.notificationSummary?.upcomingSchedules || 0));

const unreadCount = computed(() => {
  return localNotificationsList.value.filter(n => !n.is_read).length;
});

const totalBadgeCount = computed(() => {
  return pendingMedicineRequestsCount.value
       + acceptedMedicineRequestsCount.value
       + approvedMedicineRequestsCount.value
       + returnedMedicinesCount.value
       + pendingHomeServicesCount.value
       + upcomingSchedulesCount.value
       + unreadCount.value;
});

// ── Mark all personal notifications as read ──
async function markAllAsRead() {
  dismissedCategories.value = true;
  if (!props.markReadUrl) {
    localNotificationsList.value = localNotificationsList.value.map(n => ({ ...n, is_read: true }));
    return;
  }
  try {
    const res = await fetch(props.markReadUrl, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': csrfToken.value,
      },
      body: JSON.stringify({}),
    });
    if (res.ok) {
      localNotificationsList.value = localNotificationsList.value.map(n => ({ ...n, is_read: true }));
    }
  } catch (e) {
    console.error('Failed to mark notifications as read', e);
  }
}

// ── Navigate to a sidebar tab ──
function goToTab(tabId) {
  currentTab.value = tabId;
  showNotificationDropdown.value = false;
}

const currentTab = ref('dashboard');

const tabs = [
  { id: 'dashboard', name: 'Dashboard', icon: '<svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" /></svg>', component: ResidentDashboard },
  { id: 'medicine', name: 'Request Medicine', icon: '<svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" /></svg>', component: RequestMedicine },
  { id: 'service', name: 'Home Service', icon: '<svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7" /></svg>', component: HomeService },
  { id: 'schedule', name: 'Schedule Announcement', icon: '<svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>', component: ScheduleAnnouncements },
  { id: 'notifications', name: 'Notifications', icon: '<svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" /></svg>', component: Notifications },
  { id: 'profile', name: 'Profile', icon: '<svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>', component: Profile },
];

const currentComponent = computed(() => {
  return tabs.find(t => t.id === currentTab.value)?.component || ResidentDashboard;
});

const activeTabName = computed(() => {
  return tabs.find(t => t.id === currentTab.value)?.name || 'Dashboard';
});
</script>
