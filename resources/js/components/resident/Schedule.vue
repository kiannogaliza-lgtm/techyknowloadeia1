<template>
  <div class="grid grid-cols-1 lg:grid-cols-[1.1fr_1fr] gap-6 items-start">

    <!-- LEFT: Schedule Appointment Form -->
    <div class="bg-white rounded-2xl shadow-[0_2px_10px_-3px_rgba(6,81,237,0.1)] border border-gray-100">
      <div class="p-6 border-b border-gray-100 flex items-center gap-3">
        <svg class="w-5 h-5 text-[#1a5a89]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
        </svg>
        <h3 class="font-bold text-[#1a5a89] text-lg">Schedule New Appointment</h3>
      </div>

      <div v-if="flash" :class="['mx-6 mt-5 px-4 py-3 rounded-xl text-sm font-medium flex items-center gap-2', flash.type === 'success' ? 'bg-green-50 text-green-700 border border-green-200' : 'bg-red-50 text-red-700 border border-red-200']">
        <svg v-if="flash.type === 'success'" class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
        <svg v-else class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
        {{ flash.message }}
      </div>

      <form @submit.prevent="submitRequest" class="p-6 flex flex-col gap-5">
        <!-- Appointment Type -->
        <div>
          <label class="block text-sm font-semibold text-gray-700 mb-2">Appointment Type</label>
          <div class="relative">
            <select v-model="form.type" required
                    class="w-full appearance-none border border-gray-200 rounded-xl px-4 py-3 text-sm text-gray-700 bg-white focus:outline-none focus:ring-2 focus:ring-[#3ac1eb]/50 focus:border-[#3ac1eb] transition-all pr-10">
              <option value="" disabled>Select type...</option>
              <option value="Immunization">Immunization</option>
              <option value="Ultrasound">Ultrasound</option>
            </select>
            <svg class="w-4 h-4 absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
            </svg>
          </div>
        </div>

        <!-- Date -->
        <div>
          <label class="block text-sm font-semibold text-gray-700 mb-2">Preferred Date</label>
          <input v-model="form.scheduled_date" type="date" required
                 class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-[#3ac1eb]/50 focus:border-[#3ac1eb] transition-all text-gray-700 bg-white">
        </div>

        <!-- Time -->
        <div>
          <label class="block text-sm font-semibold text-gray-700 mb-2">Preferred Time</label>
          <div class="relative">
            <select v-model="form.scheduled_time" required
                    class="w-full appearance-none border border-gray-200 rounded-xl px-4 py-3 text-sm text-gray-700 bg-white focus:outline-none focus:ring-2 focus:ring-[#3ac1eb]/50 focus:border-[#3ac1eb] transition-all pr-10">
              <option value="" disabled>Select time...</option>
              <option v-for="slot in availableTimeSlots" :key="slot.time" :value="slot.time" :disabled="slot.disabled">
                {{ slot.time }} {{ slot.disabled ? '(Already Booked)' : '' }}
              </option>
            </select>
            <svg class="w-4 h-4 absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
            </svg>
          </div>
        </div>

        <!-- Notes -->
        <div>
          <label class="block text-sm font-semibold text-gray-700 mb-2">Notes <span class="font-normal text-gray-400">(Optional)</span></label>
          <textarea v-model="form.notes" rows="4" placeholder="Any special requests or concerns..."
                    class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm resize-none focus:outline-none focus:ring-2 focus:ring-[#3ac1eb]/50 focus:border-[#3ac1eb] transition-all"></textarea>
        </div>

        <!-- Submit -->
        <button type="submit" :disabled="submitting"
                class="w-full bg-[#1a5a89] hover:bg-[#154d78] text-white font-semibold py-3.5 rounded-xl transition-all duration-200 text-sm flex items-center justify-center gap-2 shadow-lg shadow-[#1a5a89]/20 disabled:opacity-60 disabled:cursor-not-allowed">
          <svg v-if="submitting" class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
          </svg>
          <span>{{ submitting ? 'Scheduling…' : 'Schedule Appointment' }}</span>
        </button>
      </form>
    </div>

    <!-- RIGHT: Upcoming & Available Slots -->
    <div class="flex flex-col gap-6">

      <!-- Upcoming Appointments -->
      <div class="bg-white rounded-2xl shadow-[0_2px_10px_-3px_rgba(6,81,237,0.1)] border border-gray-100">
        <div class="p-5 border-b border-gray-100 flex items-center gap-3">
          <svg class="w-5 h-5 text-[#1a5a89]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
          </svg>
          <h3 class="font-bold text-[#1a5a89]">Upcoming Appointments</h3>
        </div>
        <div class="p-5 flex flex-col gap-3 max-h-64 overflow-y-auto">
          <div v-if="mySchedules.length === 0" class="text-sm text-gray-500 text-center py-4">No upcoming appointments.</div>
          
          <div v-for="sch in mySchedules" :key="sch.id" class="border border-[#e0f4fc] bg-[#f8fdff] rounded-xl p-4 flex flex-col gap-2">
            <div class="flex items-start justify-between gap-2">
              <div>
                <h4 class="font-semibold text-gray-800 text-sm">{{ sch.type === 'Immunization' ? 'COVID-19 Booster' : 'Prenatal Checkup' }}</h4>
                <p class="text-xs text-gray-500 mt-0.5">{{ sch.type }}</p>
              </div>
              <span :class="['px-2.5 py-1 text-[10px] font-semibold rounded-full uppercase tracking-wider', getStatusClass(sch.status)]">
                {{ sch.status }}
              </span>
            </div>
            <div class="flex items-center gap-4 text-xs text-gray-600 mt-1">
              <span class="flex items-center gap-1.5"><svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg> {{ sch.date }}</span>
              <span class="flex items-center gap-1.5"><svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg> {{ sch.time }}</span>
            </div>
          </div>
        </div>
      </div>

      <!-- Available Time Slots Demo -->
      <div class="bg-white rounded-2xl shadow-[0_2px_10px_-3px_rgba(6,81,237,0.1)] border border-gray-100">
        <div class="p-5 border-b border-gray-100">
          <h3 class="font-bold text-[#1a5a89]">Available Time Slots</h3>
        </div>
        <div class="p-5 flex flex-col gap-4 max-h-72 overflow-y-auto">
          <div v-for="day in nextAvailableDays" :key="day.date">
            <div class="text-sm font-semibold text-gray-700 mb-2 border-l-2 border-[#3ac1eb] pl-3">{{ day.date }}</div>
            <div class="flex flex-wrap gap-2 pl-3">
              <span v-for="time in day.slots" :key="time" 
                    class="px-3 py-1.5 bg-[#f0fbff] text-[#1a5a89] rounded-lg text-xs font-medium border border-[#bcecfc]">
                {{ time }}
              </span>
              <span v-if="day.slots.length === 0" class="text-xs text-gray-400 italic">Fully Booked</span>
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
  mySchedules: { type: Array, default: () => [] },
  bookedSchedules: { type: Array, default: () => [] },
  scheduleStoreUrl: { type: String, required: true }
});

const form = ref({
  type: '',
  scheduled_date: '',
  scheduled_time: '',
  notes: ''
});

const timeSlots = [
  '9:00 AM', '10:00 AM', '11:00 AM',
  '1:00 PM', '2:00 PM', '3:00 PM', '4:00 PM', '5:00 PM'
];

// Calculate available times for the currently selected date in the form
const availableTimeSlots = computed(() => {
  return timeSlots.map(time => {
    const isBooked = props.bookedSchedules.some(
      booking => booking.date === form.value.scheduled_date && booking.time === time
    );
    return {
      time,
      disabled: isBooked
    };
  });
});

// Clear time if it becomes booked when date changes
watch(() => form.value.scheduled_date, () => {
  if (form.value.scheduled_time) {
    const selectedSlot = availableTimeSlots.value.find(s => s.time === form.value.scheduled_time);
    if (selectedSlot && selectedSlot.disabled) {
      form.value.scheduled_time = '';
    }
  }
});

// Calculate next few days for the right panel preview
const nextAvailableDays = computed(() => {
  const days = [];
  const today = new Date();
  
  for (let i = 1; i <= 3; i++) {
    const d = new Date(today);
    d.setDate(today.getDate() + i);
    // Format YYYY-MM-DD
    const dateStr = d.toISOString().split('T')[0];
    
    // Find available slots for this day
    const available = timeSlots.filter(time => {
      return !props.bookedSchedules.some(booking => booking.date === dateStr && booking.time === time);
    });
    
    // Show only the first 4 for UI neatness
    days.push({
      date: dateStr,
      slots: available.slice(0, 4)
    });
  }
  return days;
});

const getStatusClass = (status) => {
  const s = (status || '').toLowerCase();
  if (s === 'confirmed') return 'bg-green-100 text-green-700';
  if (s === 'pending') return 'bg-yellow-100 text-yellow-700';
  if (s === 'cancelled') return 'bg-red-100 text-red-700';
  return 'bg-gray-100 text-gray-700';
};

const submitting = ref(false);
const flash = ref(null);

const getCsrf = () => document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') ?? '';

const submitRequest = async () => {
  submitting.value = true;
  flash.value = null;

  try {
    const body = new FormData();
    body.append('_token', getCsrf());
    
    // Map 'scheduled_time' back to 24h format for the backend if needed, 
    // or backend can parse standard strings. The backend currently uses datetime:H:i but Carbon can parse "9:00 AM".
    Object.keys(form.value).forEach(key => {
      body.append(key, form.value[key]);
    });

    const res = await fetch(props.scheduleStoreUrl, {
      method: 'POST',
      headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json, text/html' },
      body,
    });

    if (res.ok || res.redirected) {
      flash.value = { type: 'success', message: 'Appointment scheduled successfully!' };
      
      form.value = { type: '', scheduled_date: '', scheduled_time: '', notes: '' };
      
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
