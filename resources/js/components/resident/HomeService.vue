<template>
  <div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-2xl shadow-[0_2px_10px_-3px_rgba(6,81,237,0.1)] border border-gray-100">
      
      <!-- Header -->
      <div class="p-6 border-b border-gray-100 flex items-center gap-3">
        <svg class="w-6 h-6 text-[#1a5a89]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" />
        </svg>
        <h3 class="font-bold text-[#1a5a89] text-xl">Request Home Health Service</h3>
      </div>

      <!-- Flash message -->
      <div v-if="flash" :class="['mx-6 mt-6 px-4 py-3 rounded-xl text-sm font-medium flex items-center gap-2', flash.type === 'success' ? 'bg-green-50 text-green-700 border border-green-200' : 'bg-red-50 text-red-700 border border-red-200']">
        <svg v-if="flash.type === 'success'" class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
        <svg v-else class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
        {{ flash.message }}
      </div>

      <!-- Form -->
      <form @submit.prevent="submitRequest" class="p-6 flex flex-col gap-6">
        
        <!-- Service Type -->
        <div>
          <label class="block text-sm font-semibold text-gray-700 mb-2">Service Type</label>
          <select v-model="form.service_type" required
                  class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-[#3ac1eb]/50 focus:border-[#3ac1eb] transition-all bg-white">
            <option value="" disabled>Select a service...</option>
            <option value="Maternal Care">Maternal Care</option>
            <option value="Immunization">Immunization</option>
            <option value="Wound Care">Wound Care</option>
            <option value="Vital Signs Monitoring">Vital Signs Monitoring</option>
            <option value="Bedridden Patient Care">Bedridden Patient Care</option>
            <option value="Other">Other</option>
          </select>
        </div>

        <!-- Date & Time -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">Preferred Date</label>
            <input v-model="form.preferred_date" type="date" required
                   class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-[#3ac1eb]/50 focus:border-[#3ac1eb] transition-all bg-white text-gray-700">
          </div>
          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">Preferred Time</label>
            <select v-model="form.preferred_time" required
                    class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-[#3ac1eb]/50 focus:border-[#3ac1eb] transition-all bg-white">
              <option value="" disabled>Select time...</option>
              <option v-for="slot in availableTimeSlots" :key="slot.time" :value="slot.time" :disabled="slot.disabled">
                {{ slot.time }} {{ slot.disabled ? '(Already Booked)' : '' }}
              </option>
            </select>
          </div>
        </div>

        <!-- Home Address -->
        <div>
          <label class="block text-sm font-semibold text-gray-700 mb-2">Home Address</label>
          <div class="relative">
             <svg class="w-5 h-5 absolute left-3 top-3.5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
               <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
               <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
             </svg>
            <textarea v-model="form.address" rows="2" required placeholder="Complete address with landmarks"
                      class="w-full border border-gray-200 rounded-xl pl-10 pr-4 py-3 text-sm resize-none focus:outline-none focus:ring-2 focus:ring-[#3ac1eb]/50 focus:border-[#3ac1eb] transition-all"></textarea>
          </div>
        </div>

        <!-- Contact Number -->
        <div>
          <label class="block text-sm font-semibold text-gray-700 mb-2">Contact Number</label>
          <input v-model="form.contact_number" type="tel" required placeholder="09XXXXXXXXX"
                 minlength="11"
                 maxlength="11"
                 inputmode="numeric"
                 @input="handleContactInput($event)"
                 class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-[#3ac1eb]/50 focus:border-[#3ac1eb] transition-all">
          <p v-if="contactError" class="text-xs text-red-500 mt-1.5 flex items-center gap-1">
            <svg class="w-3.5 h-3.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
              <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
            </svg>
            <span>{{ contactError }}</span>
          </p>
        </div>

        <!-- Reason -->
        <div>
          <label class="block text-sm font-semibold text-gray-700 mb-2">Reason for Request / Symptoms</label>
          <textarea v-model="form.reason_symptoms" rows="4" required placeholder="Describe your health concern or reason for requesting home service..."
                    class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm resize-none focus:outline-none focus:ring-2 focus:ring-[#3ac1eb]/50 focus:border-[#3ac1eb] transition-all"></textarea>
        </div>

        <!-- Info Note -->
        <div class="bg-blue-50/50 border border-blue-100 rounded-xl p-4 text-sm text-blue-800 leading-relaxed">
          <strong>Note:</strong> Our Barangay Health Worker will contact you within 24 hours to confirm your appointment. Please ensure someone is available at home during the preferred time.
        </div>

        <!-- Submit -->
        <button type="submit" :disabled="submitting"
                class="w-full bg-[#1a5a89] hover:bg-[#154d78] text-white font-semibold py-4 rounded-xl transition-all duration-200 flex items-center justify-center gap-2 shadow-lg shadow-[#1a5a89]/20 mt-2 disabled:opacity-60 disabled:cursor-not-allowed">
          <svg v-if="submitting" class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
          </svg>
          <span>{{ submitting ? 'Submitting Request…' : 'Submit Home Service Request' }}</span>
        </button>

      </form>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, watch } from 'vue';

const props = defineProps({
  user: { type: Object, required: true },
  homeServiceStoreUrl: { type: String, required: true },
  bookedHomeServices: { type: Array, default: () => [] }
});

const form = ref({
  service_type: '',
  preferred_date: '',
  preferred_time: '',
  address: '',
  contact_number: props.user.phone || '',
  reason_symptoms: ''
});

// All possible time slots
const timeSlots = [
  '08:00 AM', '09:00 AM', '10:00 AM', '11:00 AM', 
  '01:00 PM', '02:00 PM', '03:00 PM', '04:00 PM'
];

// Computed list of slots, mapping to an object with `time` and `disabled` status
const availableTimeSlots = computed(() => {
  return timeSlots.map(time => {
    // Check if there is a booking for the selected date and this time
    const isBooked = props.bookedHomeServices.some(
      booking => booking.date === form.value.preferred_date && booking.time === time
    );
    return {
      time,
      disabled: isBooked
    };
  });
});

// Clear selected time if it becomes disabled after changing the date
watch(() => form.value.preferred_date, () => {
  if (form.value.preferred_time) {
    const selectedSlot = availableTimeSlots.value.find(s => s.time === form.value.preferred_time);
    if (selectedSlot && selectedSlot.disabled) {
      form.value.preferred_time = '';
    }
  }
});

const submitting = ref(false);
const flash = ref(null);

const handleContactInput = (event) => {
  let val = (event.target.value || '').replace(/\D/g, '');
  if (val.length >= 1 && val[0] !== '0') {
    val = '';
  } else if (val.length >= 2 && val.substring(0, 2) !== '09') {
    val = '0';
  }
  form.value.contact_number = val.slice(0, 11);
};

const contactError = computed(() => {
  if (form.value.contact_number && form.value.contact_number.length > 0) {
    if (!form.value.contact_number.startsWith('09')) {
      return 'Contact number must start with 09.';
    }
    if (form.value.contact_number.length < 11) {
      return 'Contact number must be exactly 11 digits (currently ' + form.value.contact_number.length + '/11).';
    }
  }
  return '';
});

const getCsrf = () => document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') ?? '';

const submitRequest = async () => {
  if (contactError.value) {
    flash.value = { type: 'error', message: contactError.value };
    window.scrollTo({ top: 0, behavior: 'smooth' });
    return;
  }

  submitting.value = true;
  flash.value = null;

  try {
    const body = new FormData();
    body.append('_token', getCsrf());
    Object.keys(form.value).forEach(key => {
      body.append(key, form.value[key]);
    });

    const res = await fetch(props.homeServiceStoreUrl, {
      method: 'POST',
      headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json, text/html' },
      body,
    });

    if (res.ok || res.redirected) {
      flash.value = { type: 'success', message: 'Home service request submitted successfully! We will contact you soon.' };
      
      // Reset form
      form.value = {
        service_type: '',
        preferred_date: '',
        preferred_time: '',
        address: '',
        contact_number: props.user.phone || '',
        reason_symptoms: ''
      };
      
      // We don't have a way to automatically update booked times without a reload here easily,
      // so reloading after a short delay is best to refresh the `bookedHomeServices` array.
      setTimeout(() => {
        window.location.reload();
      }, 1500);
      
      window.scrollTo({ top: 0, behavior: 'smooth' });
    } else {
      const json = await res.json().catch(() => null);
      const errorMsg = json?.message || Object.values(json?.errors ?? {})[0]?.[0] || 'Something went wrong. Please try again.';
      flash.value = { type: 'error', message: errorMsg };
      window.scrollTo({ top: 0, behavior: 'smooth' });
    }
  } catch (err) {
    flash.value = { type: 'error', message: 'Network error. Please check your connection.' };
    window.scrollTo({ top: 0, behavior: 'smooth' });
  } finally {
    submitting.value = false;
  }
};
</script>
