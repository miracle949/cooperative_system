@extends('layouts.admin')

@section('title', 'Settings - CoopAdmin')

@section('content')
    <!-- Breadcrumb -->
    <div class="mb-6">
        <nav class="text-sm text-gray-500">
            <ol class="list-none p-0 inline-flex">
                <li class="flex items-center">
                    <a href="{{ route('dashboard') }}" class="hover:text-primary-600">
                        <i data-lucide="home" class="w-4 h-4 mr-2"></i>
                    </a>
                </li>
                <li class="flex items-center">
                    <i data-lucide="chevron-right" class="w-4 h-4 mx-2 text-gray-400"></i>
                    <span class="text-gray-900 font-medium">Settings</span>
                </li>
            </ol>
        </nav>
    </div>

    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Settings</h1>
        <p class="text-sm text-gray-500">Manage your account and application preferences</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
        <!-- Settings Navigation -->
        <div class="lg:col-span-1">
            <div class="card p-4">
                <nav class="space-y-1">
                    <a href="#profile"
                        class="flex items-center gap-3 px-4 py-3 text-primary-600 bg-primary-50 rounded-lg font-medium">
                        <i data-lucide="user" class="w-5 h-5"></i>
                        Profile
                    </a>
                    <a href="#security" class="flex items-center gap-3 px-4 py-3 text-gray-600 hover:bg-gray-50 rounded-lg">
                        <i data-lucide="shield" class="w-5 h-5"></i>
                        Security
                    </a>
                    <a href="#notifications"
                        class="flex items-center gap-3 px-4 py-3 text-gray-600 hover:bg-gray-50 rounded-lg">
                        <i data-lucide="bell" class="w-5 h-5"></i>
                        Notifications
                    </a>
                    <a href="#company" class="flex items-center gap-3 px-4 py-3 text-gray-600 hover:bg-gray-50 rounded-lg">
                        <i data-lucide="building-2" class="w-5 h-5"></i>
                        Company
                    </a>
                    @if(auth()->user()?->isMainAdmin())
                    <a href="#admin-management"
                        class="flex items-center gap-3 px-4 py-3 text-gray-600 hover:bg-gray-50 rounded-lg">
                        <i data-lucide="shield" class="w-5 h-5"></i>
                        Admin Management
                    </a>
                    @endif
                    <a href="#payment-methods"
                        class="flex items-center gap-3 px-4 py-3 text-gray-600 hover:bg-gray-50 rounded-lg">
                        <i data-lucide="credit-card" class="w-5 h-5"></i>
                        Payment Methods
                    </a>

                </nav>
            </div>
        </div>

        <!-- Settings Content -->
        <div class="lg:col-span-3 space-y-6">
            <!-- Profile Settings -->
            <div id="profile" class="card p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-6">Profile Settings</h2>

                <div class="flex items-center gap-6 mb-6">
                    <div class="w-20 h-20 rounded-full bg-primary-100 flex items-center justify-center">
                        <span class="text-primary-600 text-2xl font-bold">
                            {{ strtoupper(substr($adminUser->first_name ?? 'A', 0, 1) . substr($adminUser->last_name ?? '', 0, 1)) }}
                        </span>
                    </div>
                    <div>
                        <button class="btn btn-primary mb-2">Change Photo</button>
                        <p class="text-xs text-gray-500">JPG, PNG or GIF. Max size 2MB.</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">First Name</label>
                        <input type="text" class="input" value="{{ $adminUser->first_name ?? '' }}">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Last Name</label>
                        <input type="text" class="input" value="{{ $adminUser->last_name ?? '' }}">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                        <input type="email" class="input" value="{{ $adminUser->email ?? '' }}">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Phone Number</label>
                        <input type="tel" class="input" value="{{ $adminUser->contact_no ?? '' }}">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Address</label>
                        <textarea class="input" rows="2">{{ $adminUser->present_address ?? '' }}</textarea>
                    </div>
                </div>

                <div class="mt-6 flex justify-end">
                    <button onclick="showToast('Saved', 'Profile settings updated successfully')" class="btn btn-primary">
                        Save Changes
                    </button>
                </div>
            </div>

            <!-- Security Settings -->
            <div id="security" class="card p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-6">Security Settings</h2>

                <div class="space-y-6">
                    <div>
                        <h3 class="font-medium text-gray-900 mb-4">Change Password</h3>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Current Password</label>
                                <input type="password" class="input" placeholder="Enter current password">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">New Password</label>
                                <input type="password" class="input" placeholder="Enter new password">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Confirm New Password</label>
                                <input type="password" class="input" placeholder="Confirm new password">
                            </div>
                        </div>
                    </div>

                    <div class="border-t border-gray-200 pt-6">
                        <h3 class="font-medium text-gray-900 mb-4">Two-Factor Authentication</h3>
                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-xl">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-success-100 rounded-lg flex items-center justify-center">
                                    <i data-lucide="shield-check" class="w-5 h-5 text-success-500"></i>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-900">Two-Factor Authentication</p>
                                    <p class="text-sm text-gray-500">Add an extra layer of security to your account</p>
                                </div>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" class="sr-only peer">
                                <div
                                    class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-primary-100 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary-600">
                                </div>
                            </label>
                        </div>
                    </div>

                    <div class="border-t border-gray-200 pt-6">
                        <h3 class="font-medium text-gray-900 mb-4">Active Sessions</h3>
                        <div class="space-y-3">
                            <div class="flex items-center justify-between p-4 border border-gray-200 rounded-xl">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-primary-100 rounded-lg flex items-center justify-center">
                                        <i data-lucide="monitor" class="w-5 h-5 text-primary-600"></i>
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-900">Current Device</p>
                                        <p class="text-sm text-gray-500">This session</p>
                                    </div>
                                </div>
                                <span class="badge badge-success">Active</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-6 flex justify-end">
                    <button onclick="showToast('Saved', 'Security settings updated successfully')" class="btn btn-primary">
                        Save Changes
                    </button>
                </div>
            </div>

            <!-- Notification Settings -->
            <div id="notifications" class="card p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-6">Notification Settings</h2>

                <div class="space-y-4">
                    <div class="flex items-center justify-between p-4 border border-gray-200 rounded-xl">
                        <div>
                            <p class="font-medium text-gray-900">Email Notifications</p>
                            <p class="text-sm text-gray-500">Receive email updates about your account</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" class="sr-only peer" checked>
                            <div
                                class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-primary-100 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary-600">
                            </div>
                        </label>
                    </div>

                    <div class="flex items-center justify-between p-4 border border-gray-200 rounded-xl">
                        <div>
                            <p class="font-medium text-gray-900">New Loan Applications</p>
                            <p class="text-sm text-gray-500">Get notified when a member applies for a loan</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" class="sr-only peer" checked>
                            <div
                                class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-primary-100 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary-600">
                            </div>
                        </label>
                    </div>

                    <div class="flex items-center justify-between p-4 border border-gray-200 rounded-xl">
                        <div>
                            <p class="font-medium text-gray-900">New Member Registrations</p>
                            <p class="text-sm text-gray-500">Get notified when a new member joins</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" class="sr-only peer" checked>
                            <div
                                class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-primary-100 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary-600">
                            </div>
                        </label>
                    </div>

                    <div class="flex items-center justify-between p-4 border border-gray-200 rounded-xl">
                        <div>
                            <p class="font-medium text-gray-900">Large Transactions</p>
                            <p class="text-sm text-gray-500">Get notified for transactions above threshold</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" class="sr-only peer">
                            <div
                                class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-primary-100 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary-600">
                            </div>
                        </label>
                    </div>
                </div>

                <div class="mt-6 flex justify-end">
                    <button onclick="showToast('Saved', 'Notification preferences updated')" class="btn btn-primary">
                        Save Changes
                    </button>
                </div>
            </div>

            <!-- Payment Methods Management -->
            <div id="payment-methods" class="card p-6">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h2 class="text-lg font-semibold text-gray-900">Payment Methods Management</h2>
                        <p class="text-sm text-gray-500">Manage payment methods and QR codes for member payments</p>
                    </div>
                    <button onclick="openPaymentMethodModal()" class="btn btn-primary">
                        <i data-lucide="plus" class="w-4 h-4"></i>
                        Add Payment Method
                    </button>
                </div>

                <div class="table-container">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Method Name</th>
                                <th>QR Code</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="payment-methods-table-body">
                            @forelse($paymentMethods as $pm)
                            <tr id="pm-row-{{ $pm->id }}">
                                <td>
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-lg bg-primary-100 flex items-center justify-center">
                                            <i data-lucide="credit-card" class="w-4 h-4 text-primary-600"></i>
                                        </div>
                                        <span class="text-sm font-medium text-gray-900">{{ $pm->method_name }}</span>
                                    </div>
                                </td>
                                <td>
                                    @if($pm->has_qr_code && $pm->qr_code_image_path)
                                        <img src="{{ asset('storage/' . $pm->qr_code_image_path) }}" alt="QR Code" class="w-10 h-10 rounded-lg border border-gray-200 object-cover">
                                    @else
                                        <span class="text-xs text-gray-400">No QR Code</span>
                                    @endif
                                </td>
                                <td>
                                    <button onclick="togglePaymentMethod({{ $pm->id }})" class="relative inline-flex items-center cursor-pointer">
                                        <input type="checkbox" class="sr-only peer" {{ $pm->is_active ? 'checked' : '' }}>
                                        <div class="w-9 h-5 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-primary-600"></div>
                                    </button>
                                </td>
                                <td>
                                    <div class="flex items-center gap-2">
                                        <button onclick="editPaymentMethod({{ $pm->id }}, '{{ $pm->method_name }}', {{ $pm->has_qr_code ? 'true' : 'false' }})"
                                            class="px-3 py-1.5 text-xs font-medium text-primary-600 bg-primary-50 rounded-lg hover:bg-primary-100 transition-colors">
                                            Edit
                                        </button>
                                        <button onclick="deletePaymentMethod({{ $pm->id }}, '{{ $pm->method_name }}')"
                                            class="px-3 py-1.5 text-xs font-medium text-danger-600 bg-danger-50 rounded-lg hover:bg-danger-100 transition-colors">
                                            Delete
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr id="pm-empty-row">
                                <td colspan="4" class="text-center py-6 text-gray-500">No payment methods configured</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Add/Edit Payment Method Modal -->
            <div id="paymentMethodModal" class="modal-overlay hidden" style="display:none">
                <div class="modal max-w-lg">
                    <div class="p-6 border-b border-gray-100">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-primary-100 flex items-center justify-center">
                                    <i data-lucide="credit-card" class="w-5 h-5 text-primary-600"></i>
                                </div>
                                <div>
                                    <h2 id="pmModalTitle" class="text-xl font-bold text-gray-900">Add Payment Method</h2>
                                    <p class="text-xs text-gray-500">Configure a payment method for member transactions</p>
                                </div>
                            </div>
                            <button onclick="closePaymentMethodModal()" class="p-2 hover:bg-gray-100 rounded-lg transition-colors">
                                <i data-lucide="x" class="w-5 h-5 text-gray-500"></i>
                            </button>
                        </div>
                    </div>
                    <form id="paymentMethodForm" enctype="multipart/form-data" class="p-6 flex-1 flex flex-col">
                        @csrf
                        <input type="hidden" id="pm_edit_id" value="">

                        <div class="space-y-4 flex-1">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Payment Method Name <span class="text-red-500">*</span></label>
                                <input type="text" name="method_name" id="pm_method_name" class="input" placeholder="e.g. GCash, Cash, Bank Transfer" required>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Requires QR Code?</label>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" name="has_qr_code" id="pm_has_qr" value="1" class="sr-only peer" onchange="toggleQrUpload()">
                                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-primary-100 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary-600"></div>
                                    <span class="ml-3 text-sm font-medium text-gray-700">Yes, this method requires a QR code</span>
                                </label>
                            </div>

                            <div id="pm-qr-upload" class="hidden">
                                <label class="block text-sm font-medium text-gray-700 mb-1">QR Code Image</label>
                                <div class="border-2 border-dashed border-gray-300 rounded-xl p-6 text-center hover:border-primary-400 transition-colors">
                                    <input type="file" name="qr_code_image" id="pm_qr_image" accept="image/png,image/jpeg,image/jpg" class="hidden" onchange="previewQrImage(this)">
                                    <label for="pm_qr_image" class="cursor-pointer">
                                        <div id="pm-qr-preview" class="hidden mb-3">
                                            <img id="pm-qr-preview-img" class="w-32 h-32 mx-auto rounded-lg object-cover border border-gray-200">
                                        </div>
                                        <div id="pm-qr-placeholder">
                                            <i data-lucide="upload" class="w-10 h-10 mx-auto text-gray-400 mb-2"></i>
                                            <p class="text-sm text-gray-500">Click to upload QR code image</p>
                                            <p class="text-xs text-gray-400">PNG, JPG (max 2MB)</p>
                                        </div>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="mt-6 pt-4 border-t border-gray-100 flex justify-end gap-3">
                            <button type="button" onclick="closePaymentMethodModal()" class="btn btn-outline">Cancel</button>
                            <button type="submit" class="btn btn-primary">
                                <i data-lucide="save" class="w-4 h-4"></i>
                                <span id="pmSubmitText">Save Payment Method</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Admin Management -->
            @if(auth()->user()?->isMainAdmin())
            <div id="admin-management" class="card p-6">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h2 class="text-lg font-semibold text-gray-900">Admin Management</h2>
                        <p class="text-sm text-gray-500">Create and manage administrator accounts with custom sidebar permissions</p>
                    </div>
                </div>

                <form action="{{ route('admin.store') }}" method="POST">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">First Name <span class="text-red-500">*</span></label>
                            <input type="text" name="first_name" class="input" placeholder="Enter first name" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Last Name <span class="text-red-500">*</span></label>
                            <input type="text" name="last_name" class="input" placeholder="Enter last name" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Email <span class="text-red-500">*</span></label>
                            <input type="email" name="email" class="input" placeholder="Enter email address" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Password <span class="text-red-500">*</span></label>
                            <input type="password" name="password" class="input" placeholder="Enter password" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Role <span class="text-red-500">*</span></label>
                            <select name="role" class="input" required>
                                <option value="">Select role</option>
                                @foreach($roles as $role)
                                <option value="{{ $role->slug }}">{{ $role->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Sidebar Permissions -->
                    <div class="mb-6">
                        <h3 class="text-sm font-semibold text-gray-800 mb-3">Sidebar Access Permissions</h3>
                        <p class="text-xs text-gray-500 mb-3">Check the sections this admin will be allowed to access. Leave all unchecked for no sidebar access.</p>
                        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-3">
                            @php
                                $sidebarMenus = [
                                    'dashboard'           => ['label' => 'Dashboard',              'icon' => 'layout-dashboard'],
                                    'lendings'            => ['label' => 'Assistance Management',  'icon' => 'banknote'],
                                    'payments'            => ['label' => 'Payments',               'icon' => 'credit-card'],
                                    'members'             => ['label' => 'Members',                'icon' => 'users'],
                                    'seminars'            => ['label' => 'Seminars',               'icon' => 'graduation-cap'],
                                    'savings'             => ['label' => 'Savings',                'icon' => 'piggy-bank'],
                                    'sharecapitals'       => ['label' => 'Share Capitals',         'icon' => 'coins'],
                                    'notifications'       => ['label' => 'Notifications',          'icon' => 'bell'],
                                    'dividends'           => ['label' => 'Dividends',              'icon' => 'gift'],
                                    'reports'             => ['label' => 'Reports',                'icon' => 'bar-chart-3'],
                                    'finance'             => ['label' => 'Finance',                'icon' => 'wallet'],
                                    'archives'            => ['label' => 'Archives',               'icon' => 'archive'],
                                    'officers-committees' => ['label' => 'Officers & Committees',  'icon' => 'briefcase'],
                                    'announcements'       => ['label' => 'Announcements',          'icon' => 'megaphone'],
                                    'settings'            => ['label' => 'Settings',               'icon' => 'settings'],
                                ];
                            @endphp
                            @foreach($sidebarMenus as $key => $menu)
                            <label class="flex items-center gap-2 p-3 border border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50 transition-colors group">
                                <input type="checkbox" name="sidebar_permissions[]" value="{{ $key }}"
                                    class="rounded border-gray-300 text-primary-600 focus:ring-primary-500">
                                <span class="text-sm font-medium text-gray-800 group-hover:text-primary-600 transition-colors">{{ $menu['label'] }}</span>
                            </label>
                            @endforeach
                        </div>
                    </div>

                    <div class="flex justify-end gap-3 pt-4 border-t border-gray-100">
                        <button type="reset" class="btn btn-outline">Reset</button>
                        <button type="submit" class="btn btn-primary">
                            <i data-lucide="user-plus" class="w-4 h-4"></i>
                            Create Admin
                        </button>
                    </div>
                </form>
            </div>

            <!-- Existing Admins List -->
            <div class="card p-6 mt-6">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h2 class="text-lg font-semibold text-gray-900">Existing Admin & Officer Accounts</h2>
                        <p class="text-sm text-gray-500">View, edit, or deactivate admin/officer accounts</p>
                    </div>
                </div>

                <div class="table-container">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($adminList as $item)
                            <tr>
                                <td>
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-full bg-primary-100 flex items-center justify-center flex-shrink-0">
                                            <span class="text-xs font-semibold text-primary-600">{{ strtoupper(substr($item['first_name'], 0, 1) . substr($item['last_name'], 0, 1)) }}</span>
                                        </div>
                                        <div>
                                            <p class="text-sm font-medium text-gray-900">{{ $item['first_name'] }} {{ $item['last_name'] }}</p>
                                            <p class="text-xs text-gray-400">{{ $item['username'] }}</p>
                                        </div>
                                        @if($item['is_main'])
                                        <span class="badge badge-primary text-xs">Main Admin</span>
                                        @endif
                                    </div>
                                </td>
                                <td><span class="text-sm text-gray-600">{{ $item['email'] }}</span></td>
                                <td>
                                    @php $roleName = $roles->firstWhere('slug', $item['role'])?->name ?? ucfirst($item['role']); @endphp
                                    <span class="badge {{ $item['role'] === 'admin' ? 'badge-primary' : 'badge-info' }}">{{ $roleName }}</span>
                                </td>
                                <td>
                                    <span class="badge {{ $item['status'] === 'active' ? 'badge-success' : ($item['status'] === 'pending' ? 'badge-warning' : 'badge-danger') }}">{{ ucfirst($item['status'] ?? 'active') }}</span>
                                </td>
                                <td>
                                    <div class="flex items-center gap-2">
                                        <button onclick="openEditAdminModal({{ $item['id'] }})"
                                            class="px-3 py-1.5 text-xs font-medium text-primary-600 bg-primary-50 rounded-lg hover:bg-primary-100 transition-colors">
                                            Edit
                                        </button>
                                        @if(!$item['is_main'])
                                        <button onclick="confirmDeleteAdmin({{ $item['id'] }}, '{{ $item['first_name'] }} {{ $item['last_name'] }}')"
                                            class="px-3 py-1.5 text-xs font-medium text-danger-600 bg-danger-50 rounded-lg hover:bg-danger-100 transition-colors">
                                            Deactivate
                                        </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center py-6 text-gray-500">No admin or officer accounts found</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Manage Roles -->
            <div class="card p-6 mt-6">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h2 class="text-lg font-semibold text-gray-900">Manage Roles</h2>
                        <p class="text-sm text-gray-500">Create and delete custom roles (system roles cannot be removed)</p>
                    </div>
                </div>

                <form id="createRoleForm" class="mb-6">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Role Name <span class="text-red-500">*</span></label>
                            <input type="text" name="name" class="input" placeholder="e.g. Staff" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Slug <span class="text-red-500">*</span></label>
                            <input type="text" name="slug" class="input" placeholder="e.g. staff" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                            <input type="text" name="description" class="input" placeholder="Optional description">
                        </div>
                    </div>
                    <div class="mt-4 flex justify-end">
                        <button type="submit" class="btn btn-primary">
                            <i data-lucide="plus" class="w-4 h-4"></i>
                            Create Role
                        </button>
                    </div>
                </form>

                <div class="table-container">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Slug</th>
                                <th>Description</th>
                                <th>Type</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($roles as $role)
                            <tr>
                                <td><span class="text-sm font-medium text-gray-900">{{ $role->name }}</span></td>
                                <td><code class="text-xs bg-gray-100 px-2 py-1 rounded">{{ $role->slug }}</code></td>
                                <td><span class="text-sm text-gray-500">{{ $role->description ?? '—' }}</span></td>
                                <td>
                                    @if($role->is_system)
                                    <span class="badge badge-primary">System</span>
                                    @else
                                    <span class="badge badge-info">Custom</span>
                                    @endif
                                </td>
                                <td>
                                    @if(!$role->is_system)
                                    <button onclick="confirmDeleteRole({{ $role->id }}, '{{ $role->name }}')"
                                        class="px-3 py-1.5 text-xs font-medium text-danger-600 bg-danger-50 rounded-lg hover:bg-danger-100 transition-colors">
                                        Delete
                                    </button>
                                    @else
                                    <span class="text-xs text-gray-400">—</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center py-6 text-gray-500">No roles found</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Edit Admin Modal -->
            <div id="editAdminModal" class="modal-overlay hidden" style="display:none">
                <div class="modal max-w-2xl">
                    <div class="p-6 border-b border-gray-100">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-primary-100 flex items-center justify-center">
                                    <i data-lucide="user-cog" class="w-5 h-5 text-primary-600"></i>
                                </div>
                                <div>
                                    <h2 class="text-xl font-bold text-gray-900">Edit Admin Account</h2>
                                    <p class="text-xs text-gray-500">Update role, permissions, and account details</p>
                                </div>
                            </div>
                            <button onclick="closeModal('editAdminModal')" class="p-2 hover:bg-gray-100 rounded-lg transition-colors">
                                <i data-lucide="x" class="w-5 h-5 text-gray-500"></i>
                            </button>
                        </div>
                    </div>
                    <form id="editAdminForm" method="POST">
                        @csrf
                        <input type="hidden" name="id" id="edit_admin_id">
                        <div class="p-6 max-h-[60vh] overflow-y-auto">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">First Name</label>
                                    <input type="text" name="first_name" id="edit_first_name" class="input" required>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Last Name</label>
                                    <input type="text" name="last_name" id="edit_last_name" class="input" required>
                                </div>
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                                    <input type="email" name="email" id="edit_email" class="input" required>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Role</label>
                                    <select name="role" id="edit_role" class="input" required>
                                        @foreach($roles as $role)
                                        <option value="{{ $role->slug }}">{{ $role->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="mb-6">
                                <h3 class="text-sm font-semibold text-gray-800 mb-3">Sidebar Access Permissions</h3>
                                <p class="text-xs text-gray-500 mb-3">Check the sections this admin will be allowed to access.</p>
                                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-3" id="edit_permissions_container">
                                    @foreach($sidebarMenus as $key => $menu)
                                    <label class="flex items-center gap-2 p-3 border border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50 transition-colors group">
                                        <input type="checkbox" name="sidebar_permissions[]" value="{{ $key }}"
                                            class="edit-perm-checkbox rounded border-gray-300 text-primary-600 focus:ring-primary-500">
                                        <span class="text-sm font-medium text-gray-800 group-hover:text-primary-600 transition-colors">{{ $menu['label'] }}</span>
                                    </label>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        <div class="p-6 border-t border-gray-100 flex justify-end gap-3">
                            <button type="button" onclick="closeModal('editAdminModal')" class="btn btn-outline">Cancel</button>
                            <button type="submit" class="btn btn-primary">
                                <i data-lucide="save" class="w-4 h-4"></i>
                                Save Changes
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            <script type="application/json" id="admin-list-data">
                {!! json_encode($adminList) !!}
            </script>

            <!-- Company Settings -->
            <div id="company" class="card p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-6">Company Settings</h2>

                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Company Name</label>
                        <input type="text" class="input" value="{{ $companySettings['company_name'] }}">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Registration Number</label>
                        <input type="text" class="input" value="{{ $companySettings['registration_number'] }}">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Address</label>
                        <textarea class="input" rows="2">{{ $companySettings['company_address'] }}</textarea>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Phone</label>
                            <input type="tel" class="input" value="{{ $companySettings['company_phone'] }}">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                            <input type="email" class="input" value="{{ $companySettings['company_email'] }}">
                        </div>
                    </div>
                </div>

                <div class="mt-6 flex justify-end gap-3">
                    <a href="{{ route('logout') }}" class="btn btn-danger" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i data-lucide="log-out" class="w-4 h-4"></i>
                        Logout
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="GET" style="display: none;">
                    </form>
                    <button onclick="showToast('Saved', 'Company settings updated successfully')" class="btn btn-primary">
                        Save Changes
                    </button>
                </div>
            </div>

            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const adminList = JSON.parse(document.getElementById('admin-list-data').textContent);

                    document.getElementById('editAdminForm').addEventListener('submit', function(e) {
                        e.preventDefault();
                        const form = this;
                        const formData = new FormData(form);

                        fetch('{{ route('admin.update') }}', {
                            method: 'POST',
                            body: formData,
                            headers: { 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
                        })
                        .then(res => res.json())
                        .then(data => {
                            if (data.success) {
                                showToast('Success', data.message, 'success');
                                closeModal('editAdminModal');
                                setTimeout(() => location.reload(), 1000);
                            } else {
                                showToast('Error', data.message, 'error');
                            }
                        })
                        .catch(() => showToast('Error', 'Failed to update admin account', 'error'));
                    });
                });

                function openEditAdminModal(id) {
                    const adminList = JSON.parse(document.getElementById('admin-list-data').textContent);
                    const admin = adminList.find(a => a.id === id);
                    if (!admin) return;

                    document.getElementById('edit_admin_id').value = admin.id;
                    document.getElementById('edit_first_name').value = admin.first_name;
                    document.getElementById('edit_last_name').value = admin.last_name;
                    document.getElementById('edit_email').value = admin.email;
                    document.getElementById('edit_role').value = admin.role;

                    document.querySelectorAll('.edit-perm-checkbox').forEach(cb => cb.checked = false);
                    const perms = admin.sidebar_permissions;
                    if (Array.isArray(perms)) {
                        perms.forEach(p => {
                            const cb = document.querySelector(`.edit-perm-checkbox[value="${p}"]`);
                            if (cb) cb.checked = true;
                        });
                    }

                    openModal('editAdminModal');
                }

                function confirmDeleteAdmin(id, name) {
                    if (!confirm(`Are you sure you want to deactivate "${name}"? They will lose access to the system.`)) return;

                    const formData = new FormData();
                    formData.append('id', id);

                    fetch('{{ route('admin.delete') }}', {
                        method: 'POST',
                        body: formData,
                        headers: { 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            showToast('Success', data.message, 'success');
                            setTimeout(() => location.reload(), 1000);
                        } else {
                            showToast('Error', data.message, 'error');
                        }
                    })
                    .catch(() => showToast('Error', 'Failed to deactivate admin account', 'error'));
                }

                // Role Management
                document.getElementById('createRoleForm').addEventListener('submit', function(e) {
                    e.preventDefault();
                    const formData = new FormData(this);

                    fetch('{{ route('roles.store') }}', {
                        method: 'POST',
                        body: formData,
                        headers: { 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            showToast('Success', data.message, 'success');
                            setTimeout(() => location.reload(), 1000);
                        } else {
                            showToast('Error', data.message, 'error');
                        }
                    })
                    .catch(err => showToast('Error', 'Failed to create role', 'error'));
                });

                function confirmDeleteRole(id, name) {
                    if (!confirm(`Delete role "${name}"? Users assigned this role will need to be reassigned.`)) return;

                    const formData = new FormData();
                    formData.append('id', id);

                    fetch('{{ route('roles.delete') }}', {
                        method: 'POST',
                        body: formData,
                        headers: { 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            showToast('Success', data.message, 'success');
                            setTimeout(() => location.reload(), 1000);
                        } else {
                            showToast('Error', data.message, 'error');
                        }
                    })
                    .catch(() => showToast('Error', 'Failed to delete role', 'error'));
                }

                function toggleAdminStatus(id, status) {
                    const label = status.charAt(0).toUpperCase() + status.slice(1);
                    if (!confirm(`Set this admin account status to "${label}"?`)) return;

                    const formData = new FormData();
                    formData.append('id', id);
                    formData.append('status', status);

                    fetch('{{ route('admin.toggle-status') }}', {
                        method: 'POST',
                        body: formData,
                        headers: { 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            showToast('Success', data.message, 'success');
                            setTimeout(() => location.reload(), 1000);
                        } else {
                            showToast('Error', data.message, 'error');
                        }
                    })
                    .catch(() => showToast('Error', 'Failed to update admin status', 'error'));
                }
            </script>
            @endif

            @if(session('admin_created'))
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    showToast('Success', 'Admin account created successfully', 'success');
                });
            </script>
            @endif

            @if($errors->any())
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    showToast('Validation Error', '{{ $errors->first() }}', 'error');
                });
            </script>
            @endif

            <script>
                // Payment Methods Management
                function openPaymentMethodModal() {
                    document.getElementById('pmModalTitle').textContent = 'Add Payment Method';
                    document.getElementById('pmSubmitText').textContent = 'Save Payment Method';
                    document.getElementById('pm_edit_id').value = '';
                    document.getElementById('pm_method_name').value = '';
                    document.getElementById('pm_has_qr').checked = false;
                    document.getElementById('pm_qr_image').value = '';
                    document.getElementById('pm-qr-upload').classList.add('hidden');
                    document.getElementById('pm-qr-preview').classList.add('hidden');
                    document.getElementById('pm-qr-placeholder').classList.remove('hidden');
                    document.getElementById('paymentMethodModal').classList.remove('hidden');
                    document.getElementById('paymentMethodModal').style.display = 'flex';
                    document.body.style.overflow = 'hidden';
                    if (typeof lucide !== 'undefined') lucide.createIcons();
                }

                function closePaymentMethodModal() {
                    document.getElementById('paymentMethodModal').classList.add('hidden');
                    document.getElementById('paymentMethodModal').style.display = 'none';
                    document.body.style.overflow = 'auto';
                }

                function toggleQrUpload() {
                    const checked = document.getElementById('pm_has_qr').checked;
                    document.getElementById('pm-qr-upload').classList.toggle('hidden', !checked);
                }

                function previewQrImage(input) {
                    if (input.files && input.files[0]) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            document.getElementById('pm-qr-preview-img').src = e.target.result;
                            document.getElementById('pm-qr-preview').classList.remove('hidden');
                            document.getElementById('pm-qr-placeholder').classList.add('hidden');
                        };
                        reader.readAsDataURL(input.files[0]);
                    }
                }

                function editPaymentMethod(id, name, hasQr) {
                    document.getElementById('pmModalTitle').textContent = 'Edit Payment Method';
                    document.getElementById('pmSubmitText').textContent = 'Update Payment Method';
                    document.getElementById('pm_edit_id').value = id;
                    document.getElementById('pm_method_name').value = name;
                    document.getElementById('pm_has_qr').checked = hasQr;
                    document.getElementById('pm-qr-upload').classList.toggle('hidden', !hasQr);
                    document.getElementById('pm-qr-preview').classList.add('hidden');
                    document.getElementById('pm-qr-placeholder').classList.remove('hidden');
                    document.getElementById('pm_qr_image').value = '';
                    document.getElementById('paymentMethodModal').classList.remove('hidden');
                    document.getElementById('paymentMethodModal').style.display = 'flex';
                    document.body.style.overflow = 'hidden';
                    if (typeof lucide !== 'undefined') lucide.createIcons();
                }

                document.getElementById('paymentMethodForm').addEventListener('submit', function(e) {
                    e.preventDefault();
                    const form = this;
                    const editId = document.getElementById('pm_edit_id').value;
                    const formData = new FormData(form);
                    const url = editId ? '/admin/payment-methods/' + editId : '/admin/payment-methods';

                    if (editId) {
                        formData.append('_method', 'PUT');
                    }

                    fetch(url, {
                        method: 'POST',
                        body: formData,
                        headers: { 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            showToast('Success', data.message, 'success');
                            closePaymentMethodModal();
                            setTimeout(() => location.reload(), 1000);
                        } else {
                            showToast('Error', data.message || 'Failed to save payment method', 'error');
                        }
                    })
                    .catch(() => showToast('Error', 'An error occurred', 'error'));
                });

                function togglePaymentMethod(id) {
                    fetch('/admin/payment-methods/' + id + '/toggle', {
                        method: 'POST',
                        headers: { 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            showToast('Success', data.message, 'success');
                        } else {
                            showToast('Error', data.message, 'error');
                        }
                    })
                    .catch(() => showToast('Error', 'Failed to update status', 'error'));
                }

                function deletePaymentMethod(id, name) {
                    if (!confirm('Delete payment method "' + name + '"?')) return;

                    fetch('/admin/payment-methods/' + id, {
                        method: 'DELETE',
                        headers: { 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            showToast('Success', data.message, 'success');
                            const row = document.getElementById('pm-row-' + id);
                            if (row) row.remove();
                        } else {
                            showToast('Error', data.message, 'error');
                        }
                    })
                    .catch(() => showToast('Error', 'Failed to delete payment method', 'error'));
                }
            </script>

        </div>
    </div>
@endsection