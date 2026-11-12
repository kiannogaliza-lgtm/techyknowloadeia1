<template>
  <div class="max-w-4xl mx-auto flex flex-col gap-6 pb-12">
    
    <!-- Success Alert -->
    <div v-if="successMessage" class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-5 py-3.5 rounded-2xl flex items-center justify-between shadow-sm animate-fade-in">
      <div class="flex items-center gap-3">
        <div class="w-8 h-8 bg-emerald-500 text-white rounded-full flex items-center justify-center shrink-0">
          <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
          </svg>
        </div>
        <span class="text-sm font-semibold">{{ successMessage }}</span>
      </div>
      <button @click="successMessage = ''" class="text-emerald-600 hover:text-emerald-800 p-1">
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
        </svg>
      </button>
    </div>

    <!-- Error Alert -->
    <div v-if="errorMessage" class="bg-red-50 border border-red-200 text-red-800 px-5 py-3.5 rounded-2xl flex items-center justify-between shadow-sm animate-fade-in">
      <div class="flex items-center gap-3">
        <div class="w-8 h-8 bg-red-500 text-white rounded-full flex items-center justify-center shrink-0">
          <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
          </svg>
        </div>
        <span class="text-sm font-semibold">{{ errorMessage }}</span>
      </div>
      <button @click="errorMessage = ''" class="text-red-600 hover:text-red-800 p-1">
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
        </svg>
      </button>
    </div>

    <!-- Profile Header Card -->
    <div class="bg-white rounded-2xl shadow-[0_2px_10px_-3px_rgba(6,81,237,0.1)] border border-gray-100 p-6 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
      <div class="flex items-center gap-6">
        <img v-if="user.profile_photo" :src="'/storage/' + user.profile_photo" :alt="user.first_name" class="w-20 h-20 rounded-full object-cover shadow-lg shadow-[#1a5a89]/20 shrink-0 border border-gray-200">
        <div v-else class="w-20 h-20 bg-[#1a5a89] text-white rounded-full flex items-center justify-center font-bold shadow-lg shadow-[#1a5a89]/20 text-3xl shrink-0">
          {{ user.first_name ? user.first_name.charAt(0) : 'U' }}
        </div>
        <div>
          <h2 class="text-2xl font-bold text-gray-800">{{ user.first_name }} {{ user.last_name }}</h2>
          <p class="text-sm text-gray-500 mt-0.5 mb-2">Patient ID: P-{{ new Date().getFullYear() }}-{{ String(user.id).padStart(3, '0') }}</p>
          <div class="flex flex-wrap items-center gap-2">
            <span class="px-2.5 py-1 bg-green-100 text-green-700 text-xs font-semibold rounded-full">Verified Account</span>
            <span class="px-2.5 py-1 bg-blue-100 text-blue-700 text-xs font-semibold rounded-full">Barangay Honrado Resident</span>
            <span v-if="resolvedCategory" :class="categoryBadgeClass" class="px-2.5 py-1 text-xs font-semibold rounded-full border">
              {{ resolvedCategory }}
            </span>
          </div>
        </div>
      </div>
      <!-- Edit Profile Button -->
      <button 
        v-if="!isEditing"
        @click="startEditing" 
        class="bg-[#1a5a89] hover:bg-[#154d78] text-white px-5 py-2.5 rounded-xl font-semibold text-sm transition-colors shadow-lg shadow-[#1a5a89]/20 flex items-center gap-2 shrink-0 cursor-pointer"
      >
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
        </svg>
        Edit Profile
      </button>
      <div v-else class="flex items-center gap-2 shrink-0">
        <button 
          @click="cancelEditing" 
          type="button"
          class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2.5 rounded-xl font-semibold text-sm transition-colors cursor-pointer"
          :disabled="isSubmitting"
        >
          Cancel
        </button>
        <button 
          @click="submitProfile" 
          type="button"
          class="bg-[#1a5a89] hover:bg-[#154d78] text-white px-5 py-2.5 rounded-xl font-semibold text-sm transition-colors shadow-lg shadow-[#1a5a89]/20 flex items-center gap-2 cursor-pointer disabled:opacity-50"
          :disabled="isSubmitting"
        >
          <svg v-if="isSubmitting" class="animate-spin h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
          </svg>
          <span v-if="isSubmitting">Saving...</span>
          <span v-else>Save Changes</span>
        </button>
      </div>
    </div>

    <!-- VIEW MODE -->
    <template v-if="!isEditing">
      <!-- Personal Information -->
      <div class="bg-white rounded-2xl shadow-[0_2px_10px_-3px_rgba(6,81,237,0.1)] border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
          <h3 class="font-bold text-[#1a5a89]">Personal Information</h3>
        </div>
        <div class="p-6 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-y-6 gap-x-8">
          <div>
            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Full Name</p>
            <p class="text-sm font-medium text-gray-800">{{ user.first_name }} {{ user.last_name }}</p>
          </div>
          <div>
            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Age</p>
            <p class="text-sm font-medium text-gray-800">{{ user.age !== null && user.age !== undefined ? user.age + ' years old' : 'Not specified' }}</p>
          </div>
          <div>
            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Age Category</p>
            <p class="text-sm font-medium">
              <span v-if="resolvedCategory" :class="categoryBadgeClass" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold border">
                {{ resolvedCategory }}
              </span>
              <span v-else class="text-gray-400">Not specified</span>
            </p>
          </div>
          <div>
            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Date of Birth</p>
            <p class="text-sm font-medium text-gray-800">{{ formatDate(user.dob) }}</p>
          </div>
          <div>
            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Gender</p>
            <p class="text-sm font-medium text-gray-800">{{ user.gender || 'Not specified' }}</p>
          </div>
          <div>
            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Blood Type</p>
            <p class="text-sm font-medium text-gray-800">{{ user.blood_type || 'Not specified' }}</p>
          </div>
        </div>
      </div>

      <!-- Contact Information -->
      <div class="bg-white rounded-2xl shadow-[0_2px_10px_-3px_rgba(6,81,237,0.1)] border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100">
          <h3 class="font-bold text-[#1a5a89]">Contact Information</h3>
        </div>
        <div class="p-6 flex flex-col gap-5">
          <div class="flex items-start gap-4">
            <div class="w-10 h-10 bg-blue-50 text-[#3ac1eb] rounded-full flex items-center justify-center shrink-0">
              <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
              </svg>
            </div>
            <div>
              <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-0.5">Email Address</p>
              <p class="text-sm font-medium text-gray-800">{{ user.email || 'Not provided' }}</p>
            </div>
          </div>
          <div class="flex items-start gap-4">
            <div class="w-10 h-10 bg-blue-50 text-[#3ac1eb] rounded-full flex items-center justify-center shrink-0">
              <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
              </svg>
            </div>
            <div>
              <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-0.5">Phone Number</p>
              <p class="text-sm font-medium text-gray-800">{{ user.phone_number }}</p>
            </div>
          </div>
          <div class="flex items-start gap-4">
            <div class="w-10 h-10 bg-blue-50 text-[#3ac1eb] rounded-full flex items-center justify-center shrink-0">
              <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
              </svg>
            </div>
            <div>
              <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-0.5">Address</p>
              <p class="text-sm font-medium text-gray-800 leading-snug">{{ user.address || 'Not provided' }}</p>
            </div>
          </div>
        </div>
      </div>

      <!-- Medical History -->
      <div class="bg-white rounded-2xl shadow-[0_2px_10px_-3px_rgba(6,81,237,0.1)] border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100">
          <h3 class="font-bold text-[#1a5a89]">Medical History</h3>
        </div>
        <div class="p-6 flex flex-col gap-4">
          <div class="bg-blue-50/50 rounded-xl px-5 py-3 border border-blue-50">
            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Maintenance Medication</p>
            <div class="flex items-center gap-2">
              <span v-if="user.is_maintenance" class="inline-flex items-center gap-1 text-xs font-semibold px-2.5 py-1 rounded-full bg-amber-100 text-amber-800 border border-amber-200">
                <span class="material-icons-outlined text-sm">medication</span>
                On Maintenance Medicine
              </span>
              <span v-else class="text-sm font-medium text-gray-600">No maintenance medication recorded</span>
            </div>
          </div>
          <div class="bg-blue-50/50 rounded-xl px-5 py-3 border border-blue-50">
            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Allergies</p>
            <p class="text-sm font-medium text-gray-800">{{ user.allergies || 'None recorded' }}</p>
          </div>
          <div class="bg-blue-50/50 rounded-xl px-5 py-3 border border-blue-50">
            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Chronic Conditions</p>
            <p class="text-sm font-medium text-gray-800">{{ user.chronic_conditions || 'None recorded' }}</p>
          </div>
          <div class="bg-blue-50/50 rounded-xl px-5 py-3 border border-blue-50">
            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Last Checkup</p>
            <p class="text-sm font-medium text-gray-800">{{ formatDate(user.last_checkup) }}</p>
          </div>
        </div>
      </div>

      <!-- Emergency Contact -->
      <div class="bg-white rounded-2xl shadow-[0_2px_10px_-3px_rgba(6,81,237,0.1)] border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100">
          <h3 class="font-bold text-[#1a5a89]">Emergency Contact</h3>
        </div>
        <div class="p-6 grid grid-cols-1 sm:grid-cols-2 gap-y-6 gap-x-12">
          <div>
            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Contact Person</p>
            <p class="text-sm font-medium text-gray-800">{{ user.emergency_contact_name || 'Not provided' }}</p>
          </div>
          <div>
            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Relationship</p>
            <p class="text-sm font-medium text-gray-800">{{ user.emergency_contact_relation || 'Not provided' }}</p>
          </div>
          <div class="sm:col-span-2">
            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Phone Number</p>
            <p class="text-sm font-medium text-gray-800">{{ user.emergency_contact_phone || 'Not provided' }}</p>
          </div>
        </div>
      </div>
    </template>

    <!-- EDIT FORM MODE -->
    <form v-else @submit.prevent="submitProfile" class="flex flex-col gap-6">
      
      <!-- Personal Information Edit -->
      <div class="bg-white rounded-2xl shadow-[0_2px_10px_-3px_rgba(6,81,237,0.1)] border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between bg-blue-50/30">
          <div>
            <h3 class="font-bold text-[#1a5a89]">Personal Information</h3>
            <p class="text-xs text-gray-500">Update your basic details</p>
          </div>
          <span v-if="previewAgeCategory" :class="previewCategoryBadgeClass" class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold border">
            Auto-Category: {{ previewAgeCategory }}
          </span>
        </div>
        
        <div class="p-6 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
          <div>
            <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1">First Name *</label>
            <input 
              v-model="form.first_name" 
              type="text" 
              required
              class="w-full px-3.5 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#1a5a89]/30 focus:border-[#1a5a89] transition-all"
            >
            <span v-if="errors.first_name" class="text-xs text-red-500 mt-1 block">{{ errors.first_name[0] }}</span>
          </div>

          <div>
            <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1">Last Name *</label>
            <input 
              v-model="form.last_name" 
              type="text" 
              required
              class="w-full px-3.5 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#1a5a89]/30 focus:border-[#1a5a89] transition-all"
            >
            <span v-if="errors.last_name" class="text-xs text-red-500 mt-1 block">{{ errors.last_name[0] }}</span>
          </div>

          <div>
            <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1">Age</label>
            <input 
              v-model.number="form.age" 
              type="number" 
              min="0" 
              max="130"
              placeholder="e.g. 24"
              class="w-full px-3.5 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#1a5a89]/30 focus:border-[#1a5a89] transition-all"
            >
            <span v-if="errors.age" class="text-xs text-red-500 mt-1 block">{{ errors.age[0] }}</span>
          </div>

          <div>
            <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1">Date of Birth</label>
            <input 
              v-model="form.dob" 
              type="date" 
              class="w-full px-3.5 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#1a5a89]/30 focus:border-[#1a5a89] transition-all"
            >
            <span v-if="errors.dob" class="text-xs text-red-500 mt-1 block">{{ errors.dob[0] }}</span>
          </div>

          <div>
            <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1">Gender</label>
            <select 
              v-model="form.gender" 
              class="w-full px-3.5 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#1a5a89]/30 focus:border-[#1a5a89] transition-all"
            >
              <option value="">Select Gender</option>
              <option value="Male">Male</option>
              <option value="Female">Female</option>
              <option value="Other">Other</option>
            </select>
            <span v-if="errors.gender" class="text-xs text-red-500 mt-1 block">{{ errors.gender[0] }}</span>
          </div>

          <div>
            <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1">Blood Type</label>
            <select 
              v-model="form.blood_type" 
              class="w-full px-3.5 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#1a5a89]/30 focus:border-[#1a5a89] transition-all"
            >
              <option value="">Select Blood Type</option>
              <option value="A+">A+</option>
              <option value="A-">A-</option>
              <option value="B+">B+</option>
              <option value="B-">B-</option>
              <option value="AB+">AB+</option>
              <option value="AB-">AB-</option>
              <option value="O+">O+</option>
              <option value="O-">O-</option>
            </select>
            <span v-if="errors.blood_type" class="text-xs text-red-500 mt-1 block">{{ errors.blood_type[0] }}</span>
          </div>
        </div>
      </div>

      <!-- Contact Information Edit -->
      <div class="bg-white rounded-2xl shadow-[0_2px_10px_-3px_rgba(6,81,237,0.1)] border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 bg-blue-50/30">
          <h3 class="font-bold text-[#1a5a89]">Contact Information</h3>
          <p class="text-xs text-gray-500">How health workers and nurses can reach you</p>
        </div>
        <div class="p-6 grid grid-cols-1 sm:grid-cols-2 gap-5">
          <div>
            <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1">Email Address</label>
            <input 
              v-model="form.email" 
              type="email" 
              placeholder="e.g. resident@example.com"
              class="w-full px-3.5 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#1a5a89]/30 focus:border-[#1a5a89] transition-all"
            >
            <span v-if="errors.email" class="text-xs text-red-500 mt-1 block">{{ errors.email[0] }}</span>
          </div>

          <div>
            <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1">Phone Number *</label>
            <input 
              v-model="form.phone_number" 
              type="tel" 
              required
              minlength="11"
              maxlength="11"
              inputmode="numeric"
              @input="handlePhoneInput($event)"
              placeholder="e.g. 09123456789"
              class="w-full px-3.5 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#1a5a89]/30 focus:border-[#1a5a89] transition-all"
            >
            <span v-if="phoneError" class="text-xs text-red-500 mt-1 block">{{ phoneError }}</span>
          </div>

          <div class="sm:col-span-2">
            <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1">Full Address</label>
            <textarea 
              v-model="form.address" 
              rows="2" 
              placeholder="Purok, Barangay Honrado, San Jose, Antique"
              class="w-full px-3.5 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#1a5a89]/30 focus:border-[#1a5a89] transition-all resize-none"
            ></textarea>
            <span v-if="errors.address" class="text-xs text-red-500 mt-1 block">{{ errors.address[0] }}</span>
          </div>
        </div>
      </div>

      <!-- Medical History Edit -->
      <div class="bg-white rounded-2xl shadow-[0_2px_10px_-3px_rgba(6,81,237,0.1)] border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 bg-blue-50/30">
          <h3 class="font-bold text-[#1a5a89]">Medical History</h3>
          <p class="text-xs text-gray-500">Helps medical staff prescribe the right medication</p>
        </div>
        <div class="p-6 grid grid-cols-1 sm:grid-cols-2 gap-5">
          <div>
            <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1">Allergies</label>
            <input 
              v-model="form.allergies" 
              type="text" 
              placeholder="e.g. Penicillin, Peanuts (or None)"
              class="w-full px-3.5 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#1a5a89]/30 focus:border-[#1a5a89] transition-all"
            >
            <span v-if="errors.allergies" class="text-xs text-red-500 mt-1 block">{{ errors.allergies[0] }}</span>
          </div>

          <div>
            <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1">Chronic Conditions</label>
            <input 
              v-model="form.chronic_conditions" 
              type="text" 
              placeholder="e.g. Hypertension, Asthma (or None)"
              class="w-full px-3.5 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#1a5a89]/30 focus:border-[#1a5a89] transition-all"
            >
            <span v-if="errors.chronic_conditions" class="text-xs text-red-500 mt-1 block">{{ errors.chronic_conditions[0] }}</span>
          </div>

          <div>
            <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1">Last Checkup Date</label>
            <input 
              v-model="form.last_checkup" 
              type="date" 
              class="w-full px-3.5 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#1a5a89]/30 focus:border-[#1a5a89] transition-all"
            >
            <span v-if="errors.last_checkup" class="text-xs text-red-500 mt-1 block">{{ errors.last_checkup[0] }}</span>
          </div>
        </div>
      </div>

      <!-- Emergency Contact Edit -->
      <div class="bg-white rounded-2xl shadow-[0_2px_10px_-3px_rgba(6,81,237,0.1)] border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 bg-blue-50/30">
          <h3 class="font-bold text-[#1a5a89]">Emergency Contact</h3>
          <p class="text-xs text-gray-500">Person to reach in case of medical emergency</p>
        </div>
        <div class="p-6 grid grid-cols-1 sm:grid-cols-3 gap-5">
          <div>
            <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1">Contact Person</label>
            <input 
              v-model="form.emergency_contact_name" 
              type="text" 
              placeholder="e.g. Maria Nogaliza"
              class="w-full px-3.5 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#1a5a89]/30 focus:border-[#1a5a89] transition-all"
            >
            <span v-if="errors.emergency_contact_name" class="text-xs text-red-500 mt-1 block">{{ errors.emergency_contact_name[0] }}</span>
          </div>

          <div>
            <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1">Relationship</label>
            <input 
              v-model="form.emergency_contact_relation" 
              type="text" 
              placeholder="e.g. Mother, Spouse"
              class="w-full px-3.5 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#1a5a89]/30 focus:border-[#1a5a89] transition-all"
            >
            <span v-if="errors.emergency_contact_relation" class="text-xs text-red-500 mt-1 block">{{ errors.emergency_contact_relation[0] }}</span>
          </div>

          <div>
            <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1">Phone Number</label>
            <input 
              v-model="form.emergency_contact_phone" 
              type="tel" 
              maxlength="11"
              inputmode="numeric"
              @input="handleEmergencyPhoneInput($event)"
              placeholder="e.g. 09987654321"
              class="w-full px-3.5 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#1a5a89]/30 focus:border-[#1a5a89] transition-all"
            >
            <span v-if="emergencyPhoneError" class="text-xs text-red-500 mt-1 block">{{ emergencyPhoneError }}</span>
          </div>
        </div>
      </div>

      <!-- Action Buttons bottom -->
      <div class="flex items-center justify-end gap-3 pt-2">
        <button 
          @click="cancelEditing" 
          type="button"
          class="bg-white hover:bg-gray-100 text-gray-700 border border-gray-200 px-6 py-3 rounded-xl font-semibold text-sm transition-colors cursor-pointer"
          :disabled="isSubmitting"
        >
          Cancel
        </button>
        <button 
          type="submit"
          class="bg-[#1a5a89] hover:bg-[#154d78] text-white px-7 py-3 rounded-xl font-semibold text-sm transition-colors shadow-lg shadow-[#1a5a89]/20 flex items-center gap-2 cursor-pointer disabled:opacity-50"
          :disabled="isSubmitting"
        >
          <svg v-if="isSubmitting" class="animate-spin h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
          </svg>
          <span v-if="isSubmitting">Saving changes...</span>
          <span v-else>Save Changes</span>
        </button>
      </div>

    </form>

  </div>
</template>

<script setup>
import { ref, computed } from 'vue';

const props = defineProps({
  user: {
    type: Object,
    required: true
  },
  profileUpdateUrl: {
    type: String,
    default: '/resident/profile'
  }
});

const emit = defineEmits(['profile-updated']);

const isEditing = ref(false);
const isSubmitting = ref(false);
const successMessage = ref('');
const errorMessage = ref('');
const errors = ref({});

const form = ref({
  first_name: '',
  last_name: '',
  phone_number: '',
  email: '',
  age: null,
  dob: '',
  gender: '',
  blood_type: '',
  address: '',
  allergies: '',
  chronic_conditions: '',
  last_checkup: '',
  emergency_contact_name: '',
  emergency_contact_relation: '',
  emergency_contact_phone: ''
});

const startEditing = () => {
  // Format dob & last_checkup to YYYY-MM-DD for date inputs
  const formatInputDate = (d) => {
    if (!d) return '';
    try {
      const date = new Date(d);
      return !isNaN(date.getTime()) ? date.toISOString().split('T')[0] : '';
    } catch {
      return '';
    }
  };

  form.value = {
    first_name: props.user.first_name || '',
    last_name: props.user.last_name || '',
    phone_number: props.user.phone_number || '',
    email: props.user.email || '',
    age: props.user.age !== null && props.user.age !== undefined ? Number(props.user.age) : null,
    dob: formatInputDate(props.user.dob),
    gender: props.user.gender || '',
    blood_type: props.user.blood_type || '',
    address: props.user.address || '',
    allergies: props.user.allergies || '',
    chronic_conditions: props.user.chronic_conditions || '',
    last_checkup: formatInputDate(props.user.last_checkup),
    emergency_contact_name: props.user.emergency_contact_name || '',
    emergency_contact_relation: props.user.emergency_contact_relation || '',
    emergency_contact_phone: props.user.emergency_contact_phone || ''
  };

  errors.value = {};
  errorMessage.value = '';
  isEditing.value = true;
};

const cancelEditing = () => {
  isEditing.value = false;
  errors.value = {};
  errorMessage.value = '';
};

// Auto compute category based on current form age in edit mode
const previewAgeCategory = computed(() => {
  const age = form.value.age;
  if (age === null || age === undefined || age === '' || isNaN(age)) {
    return null;
  }
  const numericAge = Number(age);
  if (numericAge >= 60) return 'Senior citizen';
  if (numericAge >= 30) return 'Adult';
  if (numericAge >= 18) return 'Young adult';
  if (numericAge >= 13) return 'Teenager / Adolescent';
  if (numericAge >= 3) return 'Child';
  if (numericAge >= 1) return 'Toddler';
  return 'Infant';
});

const previewCategoryBadgeClass = computed(() => {
  const cat = previewAgeCategory.value;
  switch (cat) {
    case 'Senior citizen':
      return 'bg-amber-100 text-amber-800 border-amber-300';
    case 'Adult':
      return 'bg-emerald-100 text-emerald-800 border-emerald-300';
    case 'Young adult':
      return 'bg-blue-100 text-blue-800 border-blue-300';
    case 'Teenager / Adolescent':
      return 'bg-purple-100 text-purple-800 border-purple-300';
    case 'Child':
      return 'bg-cyan-100 text-cyan-800 border-cyan-300';
    case 'Toddler':
      return 'bg-yellow-100 text-yellow-800 border-yellow-300';
    case 'Infant':
    case 'Newborn / Neonate':
      return 'bg-pink-100 text-pink-800 border-pink-300';
    default:
      return 'bg-gray-100 text-gray-800 border-gray-300';
  }
});

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
  form.value.phone_number = formatPhPhone(event.target.value);
};

const handleEmergencyPhoneInput = (event) => {
  form.value.emergency_contact_phone = formatPhPhone(event.target.value);
};

const phoneError = computed(() => {
  if (errors.value?.phone_number?.[0]) return errors.value.phone_number[0];
  if (form.value.phone_number && form.value.phone_number.length > 0) {
    if (!form.value.phone_number.startsWith('09')) {
      return 'Phone number must start with 09.';
    }
    if (form.value.phone_number.length < 11) {
      return 'Phone number must be exactly 11 digits (currently ' + form.value.phone_number.length + '/11).';
    }
  }
  return '';
});

const emergencyPhoneError = computed(() => {
  if (errors.value?.emergency_contact_phone?.[0]) return errors.value.emergency_contact_phone[0];
  if (form.value.emergency_contact_phone && form.value.emergency_contact_phone.length > 0) {
    if (!form.value.emergency_contact_phone.startsWith('09')) {
      return 'Emergency contact phone must start with 09.';
    }
    if (form.value.emergency_contact_phone.length < 11) {
      return 'Emergency contact phone must be exactly 11 digits (currently ' + form.value.emergency_contact_phone.length + '/11).';
    }
  }
  return '';
});

const submitProfile = async () => {
  if (phoneError.value || emergencyPhoneError.value) {
    errorMessage.value = 'Please correct the highlighted errors in the form.';
    return;
  }

  isSubmitting.value = true;
  errors.value = {};
  errorMessage.value = '';
  successMessage.value = '';

  try {
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
    const targetUrl = props.profileUpdateUrl || '/resident/profile';

    const response = await fetch(targetUrl, {
      method: 'PUT',
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        'X-CSRF-TOKEN': csrfToken,
        'X-Requested-With': 'XMLHttpRequest'
      },
      body: JSON.stringify(form.value)
    });

    const data = await response.json();

    if (!response.ok) {
      if (response.status === 422 && data.errors) {
        errors.value = data.errors;
        errorMessage.value = 'Please correct the highlighted errors in the form.';
      } else {
        errorMessage.value = data.message || 'An error occurred while updating profile.';
      }
      return;
    }

    if (data.success) {
      successMessage.value = data.message || 'Profile updated successfully!';
      isEditing.value = false;
      if (data.user) {
        emit('profile-updated', data.user);
      }
      // Scroll smoothly to top of view
      window.scrollTo({ top: 0, behavior: 'smooth' });
    }
  } catch (err) {
    console.error('Profile update failed:', err);
    errorMessage.value = 'Failed to update profile. Please try again.';
  } finally {
    isSubmitting.value = false;
  }
};

const resolvedCategory = computed(() => {
  if (props.user.age_category) {
    return props.user.age_category;
  }
  const age = props.user.age;
  if (age === null || age === undefined || isNaN(age)) {
    return null;
  }
  const numericAge = Number(age);
  if (numericAge >= 60) return 'Senior citizen';
  if (numericAge >= 30) return 'Adult';
  if (numericAge >= 18) return 'Young adult';
  if (numericAge >= 13) return 'Teenager / Adolescent';
  if (numericAge >= 3) return 'Child';
  if (numericAge >= 1) return 'Toddler';
  return 'Infant';
});

const categoryBadgeClass = computed(() => {
  const cat = resolvedCategory.value;
  switch (cat) {
    case 'Senior citizen':
      return 'bg-amber-100 text-amber-800 border-amber-300';
    case 'Adult':
      return 'bg-emerald-100 text-emerald-800 border-emerald-300';
    case 'Young adult':
      return 'bg-blue-100 text-blue-800 border-blue-300';
    case 'Teenager / Adolescent':
      return 'bg-purple-100 text-purple-800 border-purple-300';
    case 'Child':
      return 'bg-cyan-100 text-cyan-800 border-cyan-300';
    case 'Toddler':
      return 'bg-yellow-100 text-yellow-800 border-yellow-300';
    case 'Infant':
    case 'Newborn / Neonate':
      return 'bg-pink-100 text-pink-800 border-pink-300';
    default:
      return 'bg-gray-100 text-gray-800 border-gray-300';
  }
});

const formatDate = (dateString) => {
  if (!dateString) return 'Not specified';
  const options = { year: 'numeric', month: 'long', day: 'numeric' };
  return new Date(dateString).toLocaleDateString('en-US', options);
};
</script>
