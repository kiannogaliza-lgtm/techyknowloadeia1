@extends('layouts.nurse')

@section('title', 'User Management')
@section('page-title', 'User Management')

@section('styles')
<style>
    /* Role tab buttons */
    .role-tab {
        padding: 8px 20px;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 600;
        border: 1px solid #e2e8f0;
        background: #ffffff;
        color: #64748b;
        cursor: pointer;
        transition: all 0.2s ease;
    }
    .role-tab:hover {
        background: #f1f5f9;
        color: #334155;
    }
    .role-tab.active {
        background: #1e40af;
        color: #ffffff;
        border-color: #1e40af;
        box-shadow: 0 2px 8px rgba(30, 64, 175, 0.3);
    }

    /* Role badges */
    .badge-bhw { background: #eff6ff; color: #2563eb; border: 1px solid #bfdbfe; }
    .badge-nurse { background: #faf5ff; color: #7c3aed; border: 1px solid #ddd6fe; }
    .badge-resident { background: #f0fdf4; color: #16a34a; border: 1px solid #bbf7d0; }

    /* Status badges */
    .badge-active { background: #ecfdf5; color: #059669; border: 1px solid #a7f3d0; }
    .badge-inactive { background: #fef2f2; color: #dc2626; border: 1px solid #fecaca; }

    /* Avatar colors per role */
    .avatar-bhw { background: linear-gradient(135deg, #3b82f6, #1d4ed8); }
    .avatar-nurse { background: linear-gradient(135deg, #8b5cf6, #6d28d9); }
    .avatar-resident { background: linear-gradient(135deg, #22c55e, #16a34a); }
</style>
@endsection

@section('content')
    {{-- Header --}}
    <div class="bg-white rounded-xl border border-gray-200 p-6 mb-6">
        <div class="flex items-center justify-between mb-5">
            <h2 class="text-base font-bold text-gray-900 flex items-center gap-2">
                <span class="material-icons-outlined text-blue-500" style="font-size:22px;">manage_accounts</span>
                User Account Management
            </h2>
            <button onclick="openAddModal()" id="btn-add-user" class="flex items-center gap-2 px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-lg transition-all shadow-sm hover:shadow-md">
                <span class="material-icons-outlined" style="font-size:18px;">add</span>
                Add User
            </button>
        </div>

        {{-- Role Tabs & Search --}}
        <div class="flex flex-wrap items-center gap-3 mb-6">
            <div class="flex items-center gap-2">
                <a href="{{ route('nurse.users') }}"
                   class="role-tab {{ !request('role') || request('role') == 'all' ? 'active' : '' }}">
                    All Users
                </a>
                <a href="{{ route('nurse.users', ['role' => 'nurse', 'search' => request('search')]) }}"
                   class="role-tab {{ request('role') == 'nurse' ? 'active' : '' }}">
                    Nurses
                </a>
                <a href="{{ route('nurse.users', ['role' => 'bhw', 'search' => request('search')]) }}"
                   class="role-tab {{ request('role') == 'bhw' ? 'active' : '' }}">
                    BHW
                </a>
                <a href="{{ route('nurse.users', ['role' => 'resident', 'search' => request('search')]) }}"
                   class="role-tab {{ request('role') == 'resident' ? 'active' : '' }}">
                    Residents
                </a>
            </div>
            <form method="GET" action="{{ route('nurse.users') }}" class="relative flex-1 min-w-[250px]" id="search-form">
                @if(request('role'))
                    <input type="hidden" name="role" value="{{ request('role') }}">
                @endif
                <span class="material-icons-outlined absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" style="font-size:18px;">search</span>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search users..."
                    class="w-full pl-10 pr-4 py-2.5 bg-gray-50 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-400 transition-all">
            </form>
        </div>

        {{-- Validation Errors --}}
        @if($errors->any())
            <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-lg">
                <div class="flex items-center gap-2 mb-2">
                    <span class="material-icons-outlined text-red-500" style="font-size:18px;">error</span>
                    <span class="text-sm font-semibold text-red-700">Please fix the following errors:</span>
                </div>
                <ul class="list-disc list-inside text-sm text-red-600 space-y-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Users Table --}}
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b-2 border-gray-100">
                        <th class="text-left py-3 px-4 font-bold text-blue-700 text-xs uppercase tracking-wider">Name</th>
                        <th class="text-left py-3 px-4 font-bold text-blue-700 text-xs uppercase tracking-wider">Phone Number</th>
                        <th class="text-left py-3 px-4 font-bold text-blue-700 text-xs uppercase tracking-wider">Age / Category</th>
                        <th class="text-left py-3 px-4 font-bold text-blue-700 text-xs uppercase tracking-wider">Role</th>
                        <th class="text-left py-3 px-4 font-bold text-blue-700 text-xs uppercase tracking-wider">Maintenance</th>
                        <th class="text-left py-3 px-4 font-bold text-blue-700 text-xs uppercase tracking-wider">Status</th>
                        <th class="text-left py-3 px-4 font-bold text-blue-700 text-xs uppercase tracking-wider">Last Login</th>
                        <th class="text-left py-3 px-4 font-bold text-blue-700 text-xs uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                        <tr class="border-b border-gray-50 hover:bg-blue-50/30 transition-colors">
                            {{-- Name with avatar --}}
                            <td class="py-3.5 px-4">
                                <div class="flex items-center gap-3">
                                    @if($user->profile_photo)
                                        <img src="{{ asset('storage/' . $user->profile_photo) }}" alt="{{ $user->first_name }}" class="w-9 h-9 rounded-full object-cover border border-gray-200 shadow-xs">
                                    @else
                                        <div class="w-9 h-9 rounded-full flex items-center justify-center text-white font-bold text-xs avatar-{{ $user->role }}">
                                            {{ strtoupper(substr($user->first_name, 0, 1)) }}{{ strtoupper(substr($user->last_name, 0, 1)) }}
                                        </div>
                                    @endif
                                    <span class="font-semibold text-gray-800">{{ $user->first_name }} {{ $user->last_name }}</span>
                                </div>
                            </td>
                            {{-- Phone --}}
                            <td class="py-3.5 px-4 text-gray-500">{{ $user->phone_number }}</td>
                            {{-- Age / Category --}}
                            <td class="py-3.5 px-4">
                                @if($user->age !== null)
                                    <span class="text-xs font-semibold text-gray-700">{{ $user->age }} yrs</span>
                                    <span class="block text-[11px] font-medium text-gray-500">
                                        {{ $user->age_category ?: \App\Models\User::resolveAgeCategory($user->age) }}
                                    </span>
                                @else
                                    <span class="text-xs text-gray-400">—</span>
                                @endif
                            </td>
                            {{-- Role badge --}}
                            <td class="py-3.5 px-4">
                                <span class="badge-{{ $user->role }} text-xs font-semibold px-3 py-1 rounded-full capitalize">
                                    {{ strtoupper($user->role) }}
                                </span>
                            </td>
                            {{-- Maintenance badge --}}
                            <td class="py-3.5 px-4">
                                @if($user->is_maintenance)
                                    <span class="inline-flex items-center gap-1 text-xs font-semibold px-2.5 py-1 rounded-full bg-amber-50 text-amber-700 border border-amber-200">
                                        <span class="material-icons-outlined" style="font-size:14px;">medication</span>
                                        Maintenance
                                    </span>
                                @else
                                    <span class="text-xs text-gray-400 font-medium">—</span>
                                @endif
                            </td>
                            {{-- Status badge --}}
                            <td class="py-3.5 px-4">
                                <span class="badge-{{ $user->status ?? 'active' }} text-xs font-semibold px-3 py-1 rounded-full capitalize">
                                    {{ ucfirst($user->status ?? 'active') }}
                                </span>
                            </td>
                            {{-- Last Login --}}
                            <td class="py-3.5 px-4 text-gray-500">
                                {{ $user->last_login_at ? $user->last_login_at->format('Y-m-d') : 'Never' }}
                            </td>
                            {{-- Actions --}}
                            <td class="py-3.5 px-4">
                                <div class="flex items-center gap-2">
                                    <button onclick="openEditModal({{ $user->id }}, '{{ addslashes($user->first_name) }}', '{{ addslashes($user->last_name) }}', '{{ $user->phone_number }}', '{{ $user->role }}', '{{ $user->status ?? 'active' }}', '{{ $user->profile_photo ? asset('storage/' . $user->profile_photo) : '' }}', {{ $user->age !== null ? $user->age : 'null' }}, {{ $user->is_maintenance ? 1 : 0 }})"
                                        class="w-8 h-8 rounded-md bg-blue-50 hover:bg-blue-100 flex items-center justify-center text-blue-600 transition-colors" title="Edit">
                                        <span class="material-icons-outlined" style="font-size:17px;">edit</span>
                                    </button>
                                    @if($user->id !== auth()->id())
                                        <button onclick="openDeleteModal({{ $user->id }}, '{{ addslashes($user->first_name) }} {{ addslashes($user->last_name) }}')"
                                            class="w-8 h-8 rounded-md bg-red-50 hover:bg-red-100 flex items-center justify-center text-red-500 transition-colors" title="Delete">
                                            <span class="material-icons-outlined" style="font-size:17px;">delete</span>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-12 text-gray-400">
                                <span class="material-icons-outlined mb-2" style="font-size:48px;">people_outline</span>
                                <p class="font-medium">No users found</p>
                                <p class="text-sm mt-1">Add a new user to get started.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Summary Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-5">
        <div class="bg-white rounded-xl border border-gray-200 p-5 border-l-4 border-l-purple-500">
            <p class="text-xs font-semibold text-purple-600 uppercase tracking-wider mb-1">Total Nurses</p>
            <p class="text-2xl font-extrabold text-gray-900">{{ $totalNurses }}</p>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 p-5 border-l-4 border-l-blue-500">
            <p class="text-xs font-semibold text-blue-600 uppercase tracking-wider mb-1">Total BHW</p>
            <p class="text-2xl font-extrabold text-gray-900">{{ $totalBhw }}</p>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 p-5 border-l-4 border-l-green-500">
            <p class="text-xs font-semibold text-green-600 uppercase tracking-wider mb-1">Total Residents</p>
            <p class="text-2xl font-extrabold text-gray-900">{{ $totalResidents }}</p>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 p-5 border-l-4 border-l-emerald-500">
            <p class="text-xs font-semibold text-emerald-600 uppercase tracking-wider mb-1">Active Users</p>
            <p class="text-2xl font-extrabold text-gray-900">{{ $activeUsers }}</p>
        </div>
    </div>

    {{-- ADD User Modal --}}
    <div class="modal-overlay" id="add-modal">
        <div class="modal-content">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-bold text-gray-900">Add New User</h3>
                <button onclick="closeAddModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                    <span class="material-icons-outlined">close</span>
                </button>
            </div>
            <form method="POST" action="{{ route('users.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="space-y-4">
                    {{-- Profile Photo Upload --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Profile Photo (Optional)</label>
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-full bg-gray-100 border border-gray-200 flex items-center justify-center overflow-hidden shrink-0">
                                <span class="material-icons-outlined text-gray-400" style="font-size:24px;" id="add-photo-icon">person</span>
                                <img id="add-photo-preview" class="w-full h-full object-cover hidden">
                            </div>
                            <input type="file" name="profile_photo" id="add-photo-input" accept="image/*" onchange="previewImage(this, 'add-photo-preview', 'add-photo-icon')"
                                class="w-full text-xs text-gray-500 file:mr-3 file:py-2 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 cursor-pointer">
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">First Name</label>
                            <input type="text" name="first_name" required placeholder="e.g. Juan" value="{{ old('first_name') }}"
                                class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-400">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Last Name</label>
                            <input type="text" name="last_name" required placeholder="e.g. Dela Cruz" value="{{ old('last_name') }}"
                                class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-400">
                        </div>
                    </div>
                    <div class="grid grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Phone Number</label>
                            <input type="tel" name="phone_number" required maxlength="11" pattern="09[0-9]{9}" inputmode="numeric" oninput="validatePhPhone(this)" placeholder="e.g. 09123456789" value="{{ old('phone_number') }}" title="Please enter an 11-digit Philippine number starting with 09 (e.g. 09123456789)"
                                class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-400">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Age (Optional)</label>
                            <input type="number" name="age" min="0" max="130" placeholder="e.g. 25" value="{{ old('age') }}"
                                class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-400">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Role</label>
                            <select name="role" required class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/30 cursor-pointer">
                                <option value="">Select role...</option>
                                <option value="bhw" {{ old('role') == 'bhw' ? 'selected' : '' }}>BHW</option>
                                <option value="nurse" {{ old('role') == 'nurse' ? 'selected' : '' }}>Nurse</option>
                                <option value="resident" {{ old('role') == 'resident' ? 'selected' : '' }}>Resident</option>
                            </select>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Password</label>
                            <input type="password" name="password" required placeholder="Min. 8 characters"
                                class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-400">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Confirm Password</label>
                            <input type="password" name="password_confirmation" required placeholder="Confirm password"
                                class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-400">
                        </div>
                    </div>
                </div>
                <div class="flex justify-end gap-3 mt-6">
                    <button type="button" onclick="closeAddModal()" class="px-4 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-semibold rounded-lg transition-colors">
                        Cancel
                    </button>
                    <button type="submit" class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-lg transition-all shadow-sm hover:shadow-md">
                        Create User
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- EDIT User Modal --}}
    <div class="modal-overlay" id="edit-modal">
        <div class="modal-content">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-bold text-gray-900">Edit User</h3>
                <button onclick="closeEditModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                    <span class="material-icons-outlined">close</span>
                </button>
            </div>
            <form method="POST" id="edit-form" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="space-y-4">
                    {{-- Profile Photo Upload --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Profile Photo (Optional)</label>
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-full bg-gray-100 border border-gray-200 flex items-center justify-center overflow-hidden shrink-0">
                                <span class="material-icons-outlined text-gray-400" style="font-size:24px;" id="edit-photo-icon">person</span>
                                <img id="edit-photo-preview" class="w-full h-full object-cover hidden">
                            </div>
                            <input type="file" name="profile_photo" id="edit-photo-input" accept="image/*" onchange="previewImage(this, 'edit-photo-preview', 'edit-photo-icon')"
                                class="w-full text-xs text-gray-500 file:mr-3 file:py-2 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 cursor-pointer">
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">First Name</label>
                            <input type="text" name="first_name" id="edit-first-name" required
                                class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-400">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Last Name</label>
                            <input type="text" name="last_name" id="edit-last-name" required
                                class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-400">
                        </div>
                    </div>
                    <div class="grid grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Phone Number</label>
                            <input type="tel" name="phone_number" id="edit-phone" required maxlength="11" pattern="09[0-9]{9}" inputmode="numeric" oninput="validatePhPhone(this)" placeholder="e.g. 09123456789" title="Please enter an 11-digit Philippine number starting with 09 (e.g. 09123456789)"
                                class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-400">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Age (Optional)</label>
                            <input type="number" name="age" id="edit-age" min="0" max="130" placeholder="e.g. 25"
                                class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-400">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Role</label>
                            <select name="role" id="edit-role" required class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/30 cursor-pointer">
                                <option value="bhw">BHW</option>
                                <option value="nurse">Nurse</option>
                                <option value="resident">Resident</option>
                            </select>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Status</label>
                            <select name="status" id="edit-status" required class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/30 cursor-pointer">
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Maintenance Medicine</label>
                            <select name="is_maintenance" id="edit-maintenance" required class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/30 cursor-pointer">
                                <option value="0">Not in Maintenance</option>
                                <option value="1">On Maintenance</option>
                            </select>
                        </div>
                    </div>
                    <div class="border-t border-gray-100 pt-4">
                        <p class="text-xs text-gray-400 mb-3">Leave password fields blank to keep the current password.</p>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1">New Password</label>
                                <input type="password" name="password" placeholder="New password (optional)"
                                    class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-400">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1">Confirm Password</label>
                                <input type="password" name="password_confirmation" placeholder="Confirm new password"
                                    class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-400">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="flex justify-end gap-3 mt-6">
                    <button type="button" onclick="closeEditModal()" class="px-4 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-semibold rounded-lg transition-colors">
                        Cancel
                    </button>
                    <button type="submit" class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-lg transition-all shadow-sm hover:shadow-md">
                        Update User
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- DELETE Confirmation Modal --}}
    <div class="modal-overlay" id="delete-modal">
        <div class="modal-content">
            <div class="text-center">
                <div class="w-16 h-16 rounded-full bg-red-50 flex items-center justify-center mx-auto mb-4">
                    <span class="material-icons-outlined text-red-500" style="font-size:32px;">person_remove</span>
                </div>
                <h3 class="text-lg font-bold text-gray-900 mb-2">Delete User</h3>
                <p class="text-sm text-gray-500 mb-6">Are you sure you want to delete <strong id="delete-name" class="text-gray-800"></strong>? This action cannot be undone.</p>
                <div class="flex justify-center gap-3">
                    <button type="button" onclick="closeDeleteModal()" class="px-5 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-semibold rounded-lg transition-colors">
                        Cancel
                    </button>
                    <form method="POST" id="delete-form">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="px-5 py-2.5 bg-red-500 hover:bg-red-600 text-white text-sm font-semibold rounded-lg transition-all shadow-sm hover:shadow-md">
                            Delete
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script>
    // Image Preview helper
    function previewImage(input, previewId, iconId) {
        const preview = document.getElementById(previewId);
        const icon = document.getElementById(iconId);

        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.classList.remove('hidden');
                icon.classList.add('hidden');
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    // ADD Modal
    function openAddModal() {
        document.getElementById('add-photo-input').value = '';
        document.getElementById('add-photo-preview').src = '';
        document.getElementById('add-photo-preview').classList.add('hidden');
        document.getElementById('add-photo-icon').classList.remove('hidden');
        document.getElementById('add-modal').classList.add('active');
    }
    function closeAddModal() {
        document.getElementById('add-modal').classList.remove('active');
    }

    // EDIT Modal
    function openEditModal(id, firstName, lastName, phone, role, status, photoUrl, age, isMaintenance) {
        document.getElementById('edit-form').action = '/nurse/users/' + id;
        document.getElementById('edit-first-name').value = firstName;
        document.getElementById('edit-last-name').value = lastName;
        document.getElementById('edit-phone').value = phone;
        document.getElementById('edit-age').value = age !== undefined && age !== null ? age : '';
        document.getElementById('edit-role').value = role;
        document.getElementById('edit-status').value = status;
        document.getElementById('edit-maintenance').value = isMaintenance ? '1' : '0';
        document.getElementById('edit-photo-input').value = '';

        const editPreview = document.getElementById('edit-photo-preview');
        const editIcon = document.getElementById('edit-photo-icon');
        if (photoUrl) {
            editPreview.src = photoUrl;
            editPreview.classList.remove('hidden');
            editIcon.classList.add('hidden');
        } else {
            editPreview.src = '';
            editPreview.classList.add('hidden');
            editIcon.classList.remove('hidden');
        }

        document.getElementById('edit-modal').classList.add('active');
    }
    function closeEditModal() {
        document.getElementById('edit-modal').classList.remove('active');
    }

    // DELETE Modal
    function openDeleteModal(id, name) {
        document.getElementById('delete-form').action = '/nurse/users/' + id;
        document.getElementById('delete-name').textContent = name;
        document.getElementById('delete-modal').classList.add('active');
    }
    function closeDeleteModal() {
        document.getElementById('delete-modal').classList.remove('active');
    }

    function validatePhPhone(input) {
        let val = input.value.replace(/[^0-9]/g, '');
        if (val.length >= 1 && val[0] !== '0') {
            val = '';
        } else if (val.length >= 2 && val.substring(0, 2) !== '09') {
            val = '0';
        }
        input.value = val.slice(0, 11);
        if (input.value.length > 0 && (!input.value.startsWith('09') || input.value.length < 11)) {
            input.setCustomValidity('Must be an 11-digit Philippine phone number starting with 09 (e.g. 09123456789)');
        } else {
            input.setCustomValidity('');
        }
    }

    // Close modals on overlay click
    document.querySelectorAll('.modal-overlay').forEach(overlay => {
        overlay.addEventListener('click', function(e) {
            if (e.target === this) {
                this.classList.remove('active');
            }
        });
    });

    // Close modals on Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            document.querySelectorAll('.modal-overlay.active').forEach(m => m.classList.remove('active'));
        }
    });

    // Auto-open add modal if there were validation errors on store
    @if($errors->any() && old('_method') === null)
        openAddModal();
    @endif
</script>
@endsection
