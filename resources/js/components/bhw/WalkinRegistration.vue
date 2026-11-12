<template>
  <div class="space-y-6">
    <!-- Registration Form -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
      <h3 class="text-xl font-bold text-[#1a5a89] mb-6 flex items-center gap-3">
        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
        </svg>
        Register Walk-in Patient
      </h3>

      <!-- Success Message -->
      <div v-if="successMessage" class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl p-4 flex items-center gap-3">
        <svg class="w-5 h-5 text-emerald-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <span class="text-sm font-medium">{{ successMessage }}</span>
      </div>

      <!-- Error Messages -->
      <div v-if="Object.keys(errors).length > 0" class="mb-6 bg-red-50 border border-red-200 text-red-800 rounded-xl p-4">
        <div class="flex items-center gap-2 mb-2">
          <svg class="w-5 h-5 text-red-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
          </svg>
          <span class="text-sm font-semibold">Please fix the following errors:</span>
        </div>
        <ul class="text-sm ml-7 space-y-1">
          <li v-for="(msgs, field) in errors" :key="field">{{ Array.isArray(msgs) ? msgs[0] : msgs }}</li>
        </ul>
      </div>

      <form @submit.prevent="submitForm" class="space-y-5">
        <!-- Row 1: Name -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">First Name</label>
            <div class="relative">
              <svg class="w-4 h-4 absolute left-4 top-1/2 -translate-y-1/2 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
              </svg>
              <input v-model="form.first_name" type="text" placeholder="First name" required
                     class="w-full pl-11 pr-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#3ac1eb]/50 focus:border-[#3ac1eb] transition-all bg-gray-50 focus:bg-white">
            </div>
          </div>
          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">Last Name</label>
            <div class="relative">
              <svg class="w-4 h-4 absolute left-4 top-1/2 -translate-y-1/2 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
              </svg>
              <input v-model="form.last_name" type="text" placeholder="Last name" required
                     class="w-full pl-11 pr-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#3ac1eb]/50 focus:border-[#3ac1eb] transition-all bg-gray-50 focus:bg-white">
            </div>
          </div>
        </div>

        <!-- Row 2: Birthdate, Age, Gender & Category -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">Birthdate</label>
            <div class="relative">
              <svg class="w-4 h-4 absolute left-4 top-1/2 -translate-y-1/2 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
              </svg>
              <input v-model="form.dob" @change="onDobChange" type="date" placeholder="mm/dd/yyyy"
                     class="w-full pl-11 pr-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#3ac1eb]/50 focus:border-[#3ac1eb] transition-all bg-gray-50 focus:bg-white">
            </div>
          </div>
          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">Age</label>
            <div class="relative">
              <input v-model="form.age" @input="onAgeInput" type="number" min="0" max="130" placeholder="e.g. 20"
                     class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#3ac1eb]/50 focus:border-[#3ac1eb] transition-all bg-gray-50 focus:bg-white">
            </div>
          </div>
          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">Gender</label>
            <select v-model="form.gender"
                    class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#3ac1eb]/50 focus:border-[#3ac1eb] transition-all bg-gray-50 focus:bg-white appearance-none cursor-pointer">
              <option value="">Select gender</option>
              <option value="Male">Male</option>
              <option value="Female">Female</option>
            </select>
          </div>
          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">Age Category</label>
            <select v-model="form.agecategory"
                    class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#3ac1eb]/50 focus:border-[#3ac1eb] transition-all bg-gray-50 focus:bg-white appearance-none cursor-pointer">
              <option value="">Select age category</option>     
              <option value="Senior citizen">Senior citizen (60+ yrs)</option>
              <option value="Adult">Adult (30–59 yrs)</option>
              <option value="Young adult">Young adult (18–29 yrs)</option>
              <option value="Teenager / Adolescent">Teenager / Adolescent (13–17 yrs)</option>
              <option value="Child">Child (3–12 yrs)</option>
              <option value="Toddler">Toddler (1–2 yrs)</option>
              <option value="Infant">Infant (1 mo–1 yr)</option>
              <option value="Newborn / Neonate">Newborn / Neonate (0–28 days)</option>
            </select>
          </div>
        </div>
          

        <!-- Row 3: Phone & Address -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">Phone Number</label>
            <div class="relative">
              <svg class="w-4 h-4 absolute left-4 top-1/2 -translate-y-1/2 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
              </svg>
              <input v-model="form.phone_number" type="tel" placeholder="09XXXXXXXXX"
                     maxlength="11"
                     inputmode="numeric"
                     @input="handlePhoneInput($event)"
                     class="w-full pl-11 pr-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#3ac1eb]/50 focus:border-[#3ac1eb] transition-all bg-gray-50 focus:bg-white">
            </div>
            <p v-if="phoneError" class="text-xs text-red-500 mt-1.5 flex items-center gap-1">
              <svg class="w-3.5 h-3.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
              </svg>
              <span>{{ phoneError }}</span>
            </p>
          </div>
          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">Address</label>
            <div class="relative">
              <svg class="w-4 h-4 absolute left-4 top-1/2 -translate-y-1/2 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
              </svg>
              <input v-model="form.address" type="text" placeholder="Barangay Honrado"
                     class="w-full pl-11 pr-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#3ac1eb]/50 focus:border-[#3ac1eb] transition-all bg-gray-50 focus:bg-white">
            </div>
          </div>
        </div>

        <!-- Row 4: Purpose -->
        <div>
          <label class="block text-sm font-semibold text-gray-700 mb-2">Purpose of Visit</label>
          <select v-model="form.purpose" required
                  class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#3ac1eb]/50 focus:border-[#3ac1eb] transition-all bg-gray-50 focus:bg-white appearance-none cursor-pointer">
            <option value="">Select purpose</option>
            <option value="General Consultation">General Consultation</option>
            <option value="Blood Pressure Monitoring">Blood Pressure Monitoring</option>
            <option value="Prenatal Checkup">Prenatal Checkup</option>
            <option value="Immunization">Immunization</option>
            <option value="Wound Care">Wound Care</option>
            <option value="Medicine Request">Medicine Request</option>
            <option value="Medical Certificate">Medical Certificate</option>
            <option value="Family Planning">Family Planning</option>
            <option value="Other">Other</option>
          </select>
        </div>

        <!-- Row 5: Symptoms -->
        <div>
          <label class="block text-sm font-semibold text-gray-700 mb-2">Symptoms / Chief Complaint</label>
          <textarea v-model="form.symptoms" rows="4" placeholder="Describe patient's symptoms or reason for visit..."
                    class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#3ac1eb]/50 focus:border-[#3ac1eb] transition-all bg-gray-50 focus:bg-white resize-y"></textarea>
        </div>

        <!-- Buttons -->
        <div class="flex gap-4 pt-2">
          <button type="submit" :disabled="submitting"
                  class="flex-1 bg-[#144976] hover:bg-[#0c2d48] text-white font-semibold py-3.5 rounded-xl text-sm transition-all duration-200 shadow-md hover:shadow-lg disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center gap-2">
            <svg v-if="submitting" class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
              <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
              <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            {{ submitting ? 'Registering...' : 'Register Patient' }}
          </button>
          <button type="button" @click="clearForm"
                  class="px-8 bg-gray-500 hover:bg-gray-600 text-white font-semibold py-3.5 rounded-xl text-sm transition-all duration-200 shadow-md hover:shadow-lg">
            Clear Form
          </button>
        </div>
      </form>
    </div>

    <!-- Recent Walk-in Registrations -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
      <h3 class="text-lg font-bold text-[#1a5a89] mb-4 flex items-center gap-2">
        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        Recent Walk-in Registrations
      </h3>

      <div v-if="walkins.length === 0" class="text-center py-8 text-gray-400">
        <svg class="w-12 h-12 mx-auto mb-3 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
          <path stroke-linecap="round" stroke-linejoin="round" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
        </svg>
        <p class="text-sm font-medium">No walk-in patients registered yet.</p>
      </div>

      <div v-else class="overflow-x-auto">
        <table class="w-full text-sm">
          <thead>
            <tr class="border-b border-gray-100">
              <th class="text-left py-3 px-4 text-xs font-bold text-gray-400 uppercase tracking-wider">Patient</th>
              <th class="text-left py-3 px-4 text-xs font-bold text-gray-400 uppercase tracking-wider">Purpose</th>
              <th class="text-left py-3 px-4 text-xs font-bold text-gray-400 uppercase tracking-wider">Date</th>
              <th class="text-left py-3 px-4 text-xs font-bold text-gray-400 uppercase tracking-wider">Time</th>
              <th class="text-left py-3 px-4 text-xs font-bold text-gray-400 uppercase tracking-wider">Status</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="walkin in walkins" :key="walkin.id" class="border-b border-gray-50 hover:bg-gray-50/50 transition-colors">
              <td class="py-3 px-4 font-medium text-gray-900">{{ walkin.patient_name }}</td>
              <td class="py-3 px-4 text-gray-600">{{ walkin.service }}</td>
              <td class="py-3 px-4 text-gray-500">{{ walkin.date }}</td>
              <td class="py-3 px-4 text-gray-500">{{ walkin.time }}</td>
              <td class="py-3 px-4">
                <span :class="statusClass(walkin.status)" class="px-2.5 py-1 rounded-full text-xs font-semibold capitalize">
                  {{ walkin.status }}
                </span>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, reactive, computed, onMounted } from 'vue';

const props = defineProps({
  user: {
    type: Object,
    required: true
  },
  walkinStoreUrl: {
    type: String,
    default: ''
  },
  recentWalkins: {
    type: Array,
    default: () => []
  }
});

const csrfToken = ref('');
const submitting = ref(false);
const successMessage = ref('');
const errors = ref({});
const walkins = ref(Array.isArray(props.recentWalkins) ? [...props.recentWalkins] : []);

const form = reactive({
  first_name: '',
  last_name: '',
  dob: '',
  age: '',
  gender: '',
  agecategory: '',
  phone_number: '',
  address: 'Barangay Honrado',
  purpose: '',
  symptoms: '',
});

const resolveCategoryFromAge = (num) => {
  if (num === null || num === undefined || isNaN(num) || num === '') return '';
  const age = Number(num);
  if (age < 0) return '';
  if (age >= 60) return 'Senior citizen';
  if (age >= 30) return 'Adult';
  if (age >= 18) return 'Young adult';
  if (age >= 13) return 'Teenager / Adolescent';
  if (age >= 3) return 'Child';
  if (age >= 1) return 'Toddler';
  return 'Infant';
};

const onAgeInput = () => {
  if (form.age !== '' && !isNaN(form.age)) {
    form.agecategory = resolveCategoryFromAge(form.age);
  }
};

const onDobChange = () => {
  if (!form.dob) return;
  const birthDate = new Date(form.dob);
  const today = new Date();
  if (isNaN(birthDate.getTime())) return;

  let years = today.getFullYear() - birthDate.getFullYear();
  const m = today.getMonth() - birthDate.getMonth();
  if (m < 0 || (m === 0 && today.getDate() < birthDate.getDate())) {
    years--;
  }

  if (years >= 0) {
    form.age = years;
    form.agecategory = resolveCategoryFromAge(years);
  }
};

onMounted(() => {
  const tokenMeta = document.querySelector('meta[name="csrf-token"]');
  if (tokenMeta) {
    csrfToken.value = tokenMeta.getAttribute('content');
  }
});

const clearForm = () => {
  form.first_name = '';
  form.last_name = '';
  form.dob = '';
  form.age = '';
  form.gender = '';
  form.phone_number = '';
  form.agecategory = '';
  form.address = 'Barangay Honrado';
  form.purpose = '';
  form.symptoms = '';
  errors.value = {};
  successMessage.value = '';
};

const formatPhPhone = (val) => {
  let cleaned = (val || '').replace(/\D/g, '');
  if (cleaned.length >= 1 && cleaned[0] !== '0') {
    return '';
  }
  if (cleaned.length >= 2 && cleaned.substring(0, 2) !== '09') {
    return '0';
  }
  return cleaned.slice(0, 11);
};

const handlePhoneInput = (event) => {
  form.phone_number = formatPhPhone(event.target.value);
};

const phoneError = computed(() => {
  if (errors.value?.phone_number?.[0]) return errors.value.phone_number[0];
  if (form.phone_number && form.phone_number.length > 0) {
    if (!form.phone_number.startsWith('09')) {
      return 'Phone number must start with 09.';
    }
    if (form.phone_number.length < 11) {
      return 'Phone number must be exactly 11 digits (currently ' + form.phone_number.length + '/11).';
    }
  }
  return '';
});

const submitForm = async () => {
  if (submitting.value) return;

  if (form.phone_number && form.phone_number.length > 0) {
    if (!form.phone_number.startsWith('09') || form.phone_number.length < 11) {
      errors.value = { phone_number: ['Phone number must be an 11-digit Philippine number starting with 09.'] };
      return;
    }
  }

  submitting.value = true;
  errors.value = {};
  successMessage.value = '';

  try {
    const response = await fetch(props.walkinStoreUrl, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        'X-CSRF-TOKEN': csrfToken.value,
      },
      body: JSON.stringify({ ...form }),
    });

    const data = await response.json();

    if (response.ok && data.success) {
      successMessage.value = `${data.patient.name} has been registered successfully!`;

      // Add to the top of recent walkins list
      const now = new Date();
      walkins.value.unshift({
        id: Date.now(),
        patient_name: data.patient.name,
        service: form.purpose,
        date: now.toISOString().split('T')[0],
        time: now.toLocaleTimeString([], { hour: 'numeric', minute: '2-digit' }),
        status: 'Pending',
      });

      // Reset form
      form.first_name = '';
      form.last_name = '';
      form.dob = '';
      form.age = '';
      form.gender = '';
      form.agecategory = '';
      form.phone_number = '';
      form.address = 'Barangay Honrado';
      form.purpose = '';
      form.symptoms = '';

      // Auto-hide success message after 5s
      setTimeout(() => { successMessage.value = ''; }, 5000);
    } else if (data.errors) {
      errors.value = data.errors;
    } else {
      errors.value = { general: [data.message || 'Something went wrong.'] };
    }
  } catch (err) {
    errors.value = { general: ['Network error. Please try again.'] };
  } finally {
    submitting.value = false;
  }
};

const statusClass = (status) => {
  switch (status?.toLowerCase()) {
    case 'confirmed': return 'bg-emerald-50 text-emerald-700 border border-emerald-200';
    case 'pending':   return 'bg-amber-50 text-amber-700 border border-amber-200';
    case 'cancelled': return 'bg-red-50 text-red-700 border border-red-200';
    default:          return 'bg-gray-50 text-gray-700 border border-gray-200';
  }
};
</script>
