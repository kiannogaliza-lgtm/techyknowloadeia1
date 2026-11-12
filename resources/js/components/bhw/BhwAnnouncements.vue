<template>
  <div class="space-y-6">
    <!-- Header with Action -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
      <div>
        <h3 class="text-xl font-bold text-[#1a5a89] flex items-center gap-2">
          <svg class="w-6 h-6 text-[#3ac1eb]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
          </svg>
          Schedule Announcements
        </h3>
        <p class="text-xs text-gray-500 mt-1">Post updates that synchronize immediately to resident dashboards and the nurse portal.</p>
      </div>

      <button 
        @click="openCreateModal" 
        class="bg-[#1a5a89] hover:bg-[#154d78] text-white px-5 py-2.5 rounded-xl font-semibold text-sm transition-all shadow-md shadow-[#1a5a89]/20 flex items-center gap-2 cursor-pointer"
      >
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
        </svg>
        Post Schedule Announcement
      </button>
    </div>

    <!-- Feedback alerts -->
    <div v-if="successMsg" class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-5 py-3.5 rounded-2xl flex items-center justify-between shadow-sm">
      <div class="flex items-center gap-3">
        <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
          <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
        </svg>
        <span class="text-sm font-semibold">{{ successMsg }}</span>
      </div>
      <button @click="successMsg = ''" class="text-emerald-600 hover:text-emerald-800 p-1">
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
        </svg>
      </button>
    </div>

    <!-- Announcement list -->
    <div class="space-y-4">
      <div v-for="ann in items" :key="ann.id" class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition-shadow">
        <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-4">
          <div class="flex-1">
            <div class="flex flex-wrap items-center gap-2 mb-2">
              <span :class="ann.status === 'active' ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : 'bg-gray-100 text-gray-600 border-gray-200'" class="px-2.5 py-0.5 rounded-full text-xs font-bold uppercase tracking-wider border">
                {{ ann.status }}
              </span>
              <span class="text-xs font-semibold text-[#1a5a89] bg-blue-50 px-2.5 py-0.5 rounded-full border border-blue-100">
                {{ formatDate(ann.date || ann.created_at) }}
              </span>
              <span class="text-xs text-gray-400">
                Author: <strong class="text-gray-700">{{ ann.author_name || 'BHW / Nurse' }}</strong>
              </span>
            </div>

            <h4 class="text-lg font-bold text-gray-900 mb-1.5">{{ ann.title }}</h4>
            <p class="text-sm text-gray-600 leading-relaxed whitespace-pre-line">{{ ann.content }}</p>
          </div>

          <div class="flex items-center gap-2 shrink-0 border-t sm:border-t-0 sm:border-l border-gray-100 pt-3 sm:pt-0 sm:pl-4">
            <button 
              @click="openEditModal(ann)"
              class="px-3.5 py-2 rounded-xl text-xs font-semibold bg-gray-50 hover:bg-blue-50 text-gray-700 hover:text-[#1a5a89] border border-gray-200 transition-colors flex items-center gap-1.5 cursor-pointer"
            >
              <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
              </svg>
              Edit
            </button>
            <button 
              @click="deleteAnnouncement(ann.id)"
              class="px-3.5 py-2 rounded-xl text-xs font-semibold bg-gray-50 hover:bg-red-50 text-gray-700 hover:text-red-600 border border-gray-200 transition-colors flex items-center gap-1.5 cursor-pointer"
            >
              <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
              </svg>
              Delete
            </button>
          </div>
        </div>
      </div>

      <div v-if="items.length === 0" class="bg-white rounded-2xl border border-gray-100 p-12 text-center text-gray-400">
        <svg class="w-12 h-12 mx-auto text-blue-200 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
        </svg>
        <p class="font-bold text-gray-700">No Announcements</p>
        <p class="text-xs text-gray-500 mt-1">Click the button above to publish health reminders for residents.</p>
      </div>
    </div>

    <!-- Modal Form -->
    <div v-if="showModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center p-4">
      <div class="bg-white rounded-2xl w-full max-w-lg p-6 shadow-2xl animate-fade-in">
        <div class="flex items-center justify-between pb-3 border-b border-gray-100 mb-4">
          <h4 class="font-bold text-[#1a5a89] text-base">{{ isEditing ? 'Edit Announcement' : 'Post New Announcement' }}</h4>
          <button @click="showModal = false" class="text-gray-400 hover:text-gray-600">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
            </svg>
          </button>
        </div>

        <form @submit.prevent="saveAnnouncement" class="space-y-4">
          <div>
            <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1">Title *</label>
            <input 
              v-model="form.title" 
              type="text" 
              required 
              placeholder="e.g. Polio Immunization Schedule" 
              class="w-full px-3.5 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#1a5a89]/30 focus:border-[#1a5a89]"
            >
          </div>

          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
              <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1">Event Date</label>
              <input 
                v-model="form.date" 
                type="date" 
                class="w-full px-3.5 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#1a5a89]/30 focus:border-[#1a5a89]"
              >
            </div>
            <div>
              <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1">Status</label>
              <select 
                v-model="form.status" 
                class="w-full px-3.5 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#1a5a89]/30 focus:border-[#1a5a89]"
              >
                <option value="active">Active (Visible)</option>
                <option value="archived">Archived (Hidden)</option>
              </select>
            </div>
          </div>

          <div>
            <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1">Content / Details *</label>
            <textarea 
              v-model="form.content" 
              rows="4" 
              required 
              placeholder="Provide instructions, requirements, location, etc." 
              class="w-full px-3.5 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#1a5a89]/30 focus:border-[#1a5a89] resize-none"
            ></textarea>
          </div>

          <div class="flex items-center justify-end gap-3 pt-3 border-t border-gray-100">
            <button 
              type="button" 
              @click="showModal = false" 
              class="px-4 py-2.5 rounded-xl text-sm font-semibold text-gray-600 hover:bg-gray-100"
            >
              Cancel
            </button>
            <button 
              type="submit" 
              :disabled="isSubmitting"
              class="px-5 py-2.5 rounded-xl text-sm font-semibold bg-[#1a5a89] hover:bg-[#154d78] text-white shadow-md disabled:opacity-50"
            >
              {{ isSubmitting ? 'Saving...' : (isEditing ? 'Update Announcement' : 'Publish Announcement') }}
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';

const props = defineProps({
  user: {
    type: Object,
    default: () => ({})
  },
  announcements: {
    type: Array,
    default: () => []
  },
  announcementsStoreUrl: {
    type: String,
    default: '/announcements'
  }
});

const items = ref([...props.announcements]);
const showModal = ref(false);
const isEditing = ref(false);
const isSubmitting = ref(false);
const successMsg = ref('');
const currentId = ref(null);

const form = ref({
  title: '',
  date: '',
  status: 'active',
  content: ''
});

const formatDate = (d) => {
  if (!d) return '';
  const dt = new Date(d);
  return !isNaN(dt.getTime()) ? dt.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' }) : d;
};

const openCreateModal = () => {
  isEditing.value = false;
  currentId.value = null;
  form.value = {
    title: '',
    date: new Date().toISOString().split('T')[0],
    status: 'active',
    content: ''
  };
  showModal.value = true;
};

const openEditModal = (ann) => {
  isEditing.value = true;
  currentId.value = ann.id;
  form.value = {
    title: ann.title || '',
    date: ann.date ? ann.date.split('T')[0] : (ann.created_at ? ann.created_at.split('T')[0] : ''),
    status: ann.status || 'active',
    content: ann.content || ''
  };
  showModal.value = true;
};

const saveAnnouncement = async () => {
  isSubmitting.value = true;
  try {
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
    const url = isEditing.value ? `/announcements/${currentId.value}` : (props.announcementsStoreUrl || '/announcements');
    const method = isEditing.value ? 'PUT' : 'POST';

    const res = await fetch(url, {
      method,
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        'X-CSRF-TOKEN': csrfToken,
        'X-Requested-With': 'XMLHttpRequest'
      },
      body: JSON.stringify(form.value)
    });

    const data = await res.json();
    if (data.success) {
      successMsg.value = data.message || 'Saved successfully!';
      showModal.value = false;
      // Reload or update locally
      if (isEditing.value) {
        const idx = items.value.findIndex(a => a.id === currentId.value);
        if (idx !== -1) {
          items.value[idx] = { ...items.value[idx], ...form.value };
        }
      } else {
        items.value.unshift({
          id: Date.now(),
          ...form.value,
          author_name: `${props.user.first_name || 'BHW'} ${props.user.last_name || ''}`,
          author_role: 'bhw'
        });
      }
    } else {
      alert(data.message || 'Failed to save announcement');
    }
  } catch (err) {
    console.error('Error saving announcement:', err);
    alert('An error occurred while saving.');
  } finally {
    isSubmitting.value = false;
  }
};

const deleteAnnouncement = async (id) => {
  if (!confirm('Are you sure you want to delete this announcement?')) return;
  try {
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
    const res = await fetch(`/announcements/${id}`, {
      method: 'DELETE',
      headers: {
        'Accept': 'application/json',
        'X-CSRF-TOKEN': csrfToken,
        'X-Requested-With': 'XMLHttpRequest'
      }
    });
    const data = await res.json();
    if (data.success) {
      items.value = items.value.filter(a => a.id !== id);
      successMsg.value = 'Announcement deleted successfully.';
    }
  } catch (err) {
    console.error('Error deleting announcement:', err);
  }
};
</script>
