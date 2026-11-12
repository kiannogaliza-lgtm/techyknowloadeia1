<template>
  <div class="max-w-3xl mx-auto">
    <div class="bg-white rounded-2xl shadow-[0_2px_10px_-3px_rgba(6,81,237,0.1)] border border-gray-100">

      <!-- Header -->
      <div class="p-6 border-b border-gray-100 flex items-center justify-between">
        <div class="flex items-center gap-3">
          <svg class="w-5 h-5 text-[#1a5a89]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
          </svg>
          <h3 class="font-bold text-[#1a5a89] text-lg">All Notifications</h3>
        </div>
        <button v-if="hasUnread" @click="markAllRead" class="text-sm text-[#3ac1eb] hover:text-[#1a5a89] font-semibold transition-colors">
          Mark all as read
        </button>
      </div>

      <!-- Notification List -->
      <div class="divide-y divide-gray-50">
        <div v-if="localNotifications.length === 0" class="p-12 text-center">
          <svg class="w-16 h-16 mx-auto text-gray-200 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1">
            <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
          </svg>
          <p class="text-gray-500 text-sm font-medium">No notifications yet</p>
          <p class="text-gray-400 text-xs mt-1">We'll notify you when something happens</p>
        </div>

        <div v-for="notif in localNotifications" :key="notif.id"
             :class="['flex items-start gap-4 p-5 transition-colors', !notif.is_read ? 'bg-blue-50/30' : 'bg-white hover:bg-gray-50/50']">
          
          <!-- Icon -->
          <div :class="['w-9 h-9 rounded-full flex items-center justify-center shrink-0 mt-0.5', iconBg(notif.type)]">
            <!-- Success (green check) -->
            <svg v-if="notif.type === 'success'" class="w-5 h-5" :class="iconColor(notif.type)" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
              <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
            </svg>
            <!-- Info (blue i) -->
            <svg v-else-if="notif.type === 'info'" class="w-5 h-5" :class="iconColor(notif.type)" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <!-- Warning (orange !) -->
            <svg v-else-if="notif.type === 'warning'" class="w-5 h-5" :class="iconColor(notif.type)" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
            <!-- Error (red x) -->
            <svg v-else class="w-5 h-5" :class="iconColor(notif.type)" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
              <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
            </svg>
          </div>

          <!-- Content -->
          <div class="flex-1 min-w-0">
            <h4 class="text-sm font-semibold text-gray-800">{{ notif.title }}</h4>
            <p class="text-sm text-gray-500 mt-0.5 leading-relaxed">{{ notif.message }}</p>
            <div class="flex items-center gap-1.5 mt-2 text-xs text-gray-400">
              <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
              {{ notif.time_ago }}
            </div>
          </div>

          <!-- Unread dot -->
          <div v-if="!notif.is_read" class="w-2.5 h-2.5 bg-[#3ac1eb] rounded-full shrink-0 mt-2"></div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue';

const props = defineProps({
  notifications: { type: Array, default: () => [] },
  markReadUrl: { type: String, default: '' }
});

// Local reactive copy so we can update read status in the UI
const localNotifications = ref([...props.notifications]);

import { watch } from 'vue';
watch(() => props.notifications, (newVal) => {
  localNotifications.value = [...newVal];
}, { deep: true });

const hasUnread = computed(() => localNotifications.value.some(n => !n.is_read));

const getCsrf = () => document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') ?? '';

const iconBg = (type) => {
  const map = {
    success: 'bg-green-100',
    info: 'bg-blue-100',
    warning: 'bg-orange-100',
    error: 'bg-red-100',
  };
  return map[type] || 'bg-gray-100';
};

const iconColor = (type) => {
  const map = {
    success: 'text-green-600',
    info: 'text-blue-600',
    warning: 'text-orange-500',
    error: 'text-red-500',
  };
  return map[type] || 'text-gray-500';
};

const emit = defineEmits(['all-read']);

const markAllRead = async () => {
  try {
    await fetch(props.markReadUrl, {
      method: 'POST',
      headers: {
        'X-Requested-With': 'XMLHttpRequest',
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        'X-CSRF-TOKEN': getCsrf(),
      },
      body: JSON.stringify({}),
    });
    // Update local state
    localNotifications.value = localNotifications.value.map(n => ({ ...n, is_read: true }));
    emit('all-read');
    window.dispatchEvent(new CustomEvent('notifications-read'));
  } catch (err) {
    console.error('Failed to mark notifications as read:', err);
  }
};
</script>
