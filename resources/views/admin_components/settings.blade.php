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
        </div>
    </div>
@endsection