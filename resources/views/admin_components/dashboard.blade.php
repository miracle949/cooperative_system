@extends('layouts.admin')

@section('title', 'Dashboard - CoopAdmin')

@section('content')
    <!-- Breadcrumb -->
    <div class="mb-6">
        <nav class="text-sm text-gray-500">
            <ol class="list-none p-0 inline-flex">
                <li class="flex items-center">
                    <i data-lucide="home" class="w-4 h-4 mr-2"></i>
                    <span class="text-gray-900 font-medium">Dashboard</span>
                </li>
            </ol>
        </nav>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="stat-card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 mb-1">Total Members</p>
                    <p class="text-2xl font-bold text-gray-900">1,248</p>
                    <p class="text-xs text-success-500 mt-1 flex items-center">
                        <i data-lucide="trending-up" class="w-3 h-3 mr-1"></i>
                        +12% from last month
                    </p>
                </div>
                <div class="w-12 h-12 bg-primary-100 rounded-xl flex items-center justify-center">
                    <i data-lucide="users" class="w-6 h-6 text-primary-600"></i>
                </div>
            </div>
        </div>

        <div class="stat-card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 mb-1">Total Savings</p>
                    <p class="text-2xl font-bold text-gray-900">₱4.2M</p>
                    <p class="text-xs text-success-500 mt-1 flex items-center">
                        <i data-lucide="trending-up" class="w-3 h-3 mr-1"></i>
                        +8.5% from last month
                    </p>
                </div>
                <div class="w-12 h-12 bg-success-100 rounded-xl flex items-center justify-center">
                    <i data-lucide="piggy-bank" class="w-6 h-6 text-success-500"></i>
                </div>
            </div>
        </div>

        <div class="stat-card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 mb-1">Active Loans</p>
                    <p class="text-2xl font-bold text-gray-900">₱2.8M</p>
                    <p class="text-xs text-warning-500 mt-1 flex items-center">
                        <i data-lucide="clock" class="w-3 h-3 mr-1"></i>
                        45 loans in progress
                    </p>
                </div>
                <div class="w-12 h-12 bg-warning-100 rounded-xl flex items-center justify-center">
                    <i data-lucide="banknote" class="w-6 h-6 text-warning-600"></i>
                </div>
            </div>
        </div>

        <div class="stat-card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 mb-1">Pending Requests</p>
                    <p class="text-2xl font-bold text-gray-900">18</p>
                    <p class="text-xs text-danger-500 mt-1 flex items-center">
                        <i data-lucide="alert-circle" class="w-3 h-3 mr-1"></i>
                        5 need attention
                    </p>
                </div>
                <div class="w-12 h-12 bg-danger-100 rounded-xl flex items-center justify-center">
                    <i data-lucide="hourglass" class="w-6 h-6 text-danger-600"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activity & Two Column Layout -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        <!-- Recent Activity Feed -->
        <div class="lg:col-span-2 card p-6">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-lg font-semibold text-gray-900">Recent Activity</h2>
                <a href="#" class="text-sm text-primary-600 hover:text-primary-700 font-medium">View All</a>
            </div>

            <div class="space-y-4">
                <div
                    class="flex items-start gap-4 p-4 bg-gray-50 rounded-xl hover:bg-gray-100 transition-colors cursor-pointer">
                    <div class="w-10 h-10 bg-primary-100 rounded-full flex items-center justify-center flex-shrink-0">
                        <i data-lucide="user-plus" class="w-5 h-5 text-primary-600"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-900">New member registration</p>
                        <p class="text-sm text-gray-500">Maria Santos has joined as a new member</p>
                        <p class="text-xs text-gray-400 mt-1">2 minutes ago</p>
                    </div>
                    <span class="badge badge-primary">New</span>
                </div>

                <div
                    class="flex items-start gap-4 p-4 bg-gray-50 rounded-xl hover:bg-gray-100 transition-colors cursor-pointer">
                    <div class="w-10 h-10 bg-warning-100 rounded-full flex items-center justify-center flex-shrink-0">
                        <i data-lucide="file-text" class="w-5 h-5 text-warning-600"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-900">Loan request submitted</p>
                        <p class="text-sm text-gray-500">John Rivera requested ₱50,000 for business capital</p>
                        <p class="text-xs text-gray-400 mt-1">15 minutes ago</p>
                    </div>
                    <span class="badge badge-warning">Pending</span>
                </div>

                <div
                    class="flex items-start gap-4 p-4 bg-gray-50 rounded-xl hover:bg-gray-100 transition-colors cursor-pointer">
                    <div class="w-10 h-10 bg-success-100 rounded-full flex items-center justify-center flex-shrink-0">
                        <i data-lucide="piggy-bank" class="w-5 h-5 text-success-500"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-900">Savings deposit</p>
                        <p class="text-sm text-gray-500">Ana Garcia deposited ₱5,000 to her savings account</p>
                        <p class="text-xs text-gray-400 mt-1">1 hour ago</p>
                    </div>
                    <span class="badge badge-success">Completed</span>
                </div>

                <div
                    class="flex items-start gap-4 p-4 bg-gray-50 rounded-xl hover:bg-gray-100 transition-colors cursor-pointer">
                    <div class="w-10 h-10 bg-success-100 rounded-full flex items-center justify-center flex-shrink-0">
                        <i data-lucide="check-circle" class="w-5 h-5 text-success-500"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-900">Loan approved</p>
                        <p class="text-sm text-gray-500">Loan request for Carlos Mendez approved - ₱100,000</p>
                        <p class="text-xs text-gray-400 mt-1">2 hours ago</p>
                    </div>
                    <span class="badge badge-success">Approved</span>
                </div>

                <div
                    class="flex items-start gap-4 p-4 bg-gray-50 rounded-xl hover:bg-gray-100 transition-colors cursor-pointer">
                    <div class="w-10 h-10 bg-danger-100 rounded-full flex items-center justify-center flex-shrink-0">
                        <i data-lucide="x-circle" class="w-5 h-5 text-danger-600"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-900">Loan declined</p>
                        <p class="text-sm text-gray-500">Loan request for Peter Pan declined - insufficient requirements</p>
                        <p class="text-xs text-gray-400 mt-1">3 hours ago</p>
                    </div>
                    <span class="badge badge-danger">Declined</span>
                </div>
            </div>
        </div>

        <!-- User Roles -->
        <div class="card p-6">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-lg font-semibold text-gray-900">User Roles</h2>
                <button class="text-primary-600 hover:text-primary-700">
                    <i data-lucide="plus" class="w-5 h-5"></i>
                </button>
            </div>

            <div class="space-y-3">
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 bg-primary-100 rounded-full flex items-center justify-center">
                            <i data-lucide="shield" class="w-4 h-4 text-primary-600"></i>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-900">Administrator</p>
                            <p class="text-xs text-gray-500">Full access</p>
                        </div>
                    </div>
                    <span class="text-sm text-gray-500">2</span>
                </div>

                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 bg-success-100 rounded-full flex items-center justify-center">
                            <i data-lucide="user-check" class="w-4 h-4 text-success-500"></i>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-900">Loan Officer</p>
                            <p class="text-xs text-gray-500">Loan management</p>
                        </div>
                    </div>
                    <span class="text-sm text-gray-500">4</span>
                </div>

                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 bg-warning-100 rounded-full flex items-center justify-center">
                            <i data-lucide="calculator" class="w-4 h-4 text-warning-600"></i>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-900">Accountant</p>
                            <p class="text-xs text-gray-500">Financial access</p>
                        </div>
                    </div>
                    <span class="text-sm text-gray-500">2</span>
                </div>

                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 bg-gray-100 rounded-full flex items-center justify-center">
                            <i data-lucide="user" class="w-4 h-4 text-gray-600"></i>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-900">Member</p>
                            <p class="text-xs text-gray-500">Basic access</p>
                        </div>
                    </div>
                    <span class="text-sm text-gray-500">1,240</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Analytics Section -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        <!-- Savings Growth Chart -->
        <div class="lg:col-span-2 card p-6">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h2 class="text-lg font-semibold text-gray-900">Savings Growth</h2>
                    <p class="text-sm text-gray-500">Monthly savings trend</p>
                </div>
                <select class="select w-32">
                    <option>Last 6 months</option>
                    <option>Last 12 months</option>
                    <option>This year</option>
                </select>
            </div>

            <div class="h-64 flex items-end justify-between gap-2 px-4">
                @foreach(['Jan' => 45, 'Feb' => 52, 'Mar' => 48, 'Apr' => 65, 'May' => 58, 'Jun' => 72] as $month => $value)
                    <div class="flex-1 flex flex-col items-center gap-2">
                        <div class="w-full bg-primary-100 rounded-t-lg hover:bg-primary-200 transition-colors cursor-pointer relative group"
                            style="height: {{ $value * 2.5 }}px">
                            <div
                                class="absolute -top-8 left-1/2 -translate-x-1/2 bg-gray-900 text-white text-xs px-2 py-1 rounded hidden group-hover:block whitespace-nowrap">
                                ₱{{ $value }}K
                            </div>
                        </div>
                        <span class="text-xs text-gray-500">{{ $month }}</span>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Member Activity Pie Chart -->
        <div class="card p-6">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-lg font-semibold text-gray-900">Member Activity</h2>
            </div>

            <div class="flex items-center justify-center">
                <div class="relative w-40 h-40">
                    <svg viewBox="0 0 100 100" class="transform -rotate-90 w-40 h-40">
                        <circle cx="50" cy="50" r="40" fill="none" stroke="#e5e7eb" stroke-width="12" />
                        <circle cx="50" cy="50" r="40" fill="none" stroke="#2563eb" stroke-width="12"
                            stroke-dasharray="175.9 251.2" stroke-dashoffset="0" />
                        <circle cx="50" cy="50" r="40" fill="none" stroke="#10b981" stroke-width="12"
                            stroke-dasharray="50.2 251.2" stroke-dashoffset="-175.9" />
                        <circle cx="50" cy="50" r="40" fill="none" stroke="#f59e0b" stroke-width="12"
                            stroke-dasharray="25.1 251.2" stroke-dashoffset="-226.1" />
                    </svg>
                    <div class="absolute inset-0 flex items-center justify-center">
                        <div class="text-center">
                            <p class="text-2xl font-bold text-gray-900">1,248</p>
                            <p class="text-xs text-gray-500">Total</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-6 space-y-2">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <div class="w-3 h-3 bg-primary-600 rounded-full"></div>
                        <span class="text-sm text-gray-600">Active</span>
                    </div>
                    <span class="text-sm font-medium text-gray-900">70%</span>
                </div>
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <div class="w-3 h-3 bg-success-500 rounded-full"></div>
                        <span class="text-sm text-gray-600">New</span>
                    </div>
                    <span class="text-sm font-medium text-gray-900">20%</span>
                </div>
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <div class="w-3 h-3 bg-warning-500 rounded-full"></div>
                        <span class="text-sm text-gray-600">Inactive</span>
                    </div>
                    <span class="text-sm font-medium text-gray-900">10%</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Loan Distribution & Audit Logs -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Loan Distribution -->
        <div class="card p-6">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h2 class="text-lg font-semibold text-gray-900">Loan Distribution</h2>
                    <p class="text-sm text-gray-500">By purpose category</p>
                </div>
            </div>

            <div class="space-y-4">
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm text-gray-600">Business Capital</span>
                        <span class="text-sm font-medium text-gray-900">₱1.2M</span>
                    </div>
                    <div class="w-full bg-gray-100 rounded-full h-3">
                        <div class="bg-primary-600 h-3 rounded-full" style="width: 43%"></div>
                    </div>
                </div>

                <div>
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm text-gray-600">Personal Loan</span>
                        <span class="text-sm font-medium text-gray-900">₱800K</span>
                    </div>
                    <div class="w-full bg-gray-100 rounded-full h-3">
                        <div class="bg-success-500 h-3 rounded-full" style="width: 29%"></div>
                    </div>
                </div>

                <div>
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm text-gray-600">Emergency Loan</span>
                        <span class="text-sm font-medium text-gray-900">₱400K</span>
                    </div>
                    <div class="w-full bg-gray-100 rounded-full h-3">
                        <div class="bg-warning-500 h-3 rounded-full" style="width: 14%"></div>
                    </div>
                </div>

                <div>
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm text-gray-600">Housing Loan</span>
                        <span class="text-sm font-medium text-gray-900">₱400K</span>
                    </div>
                    <div class="w-full bg-gray-100 rounded-full h-3">
                        <div class="bg-danger-500 h-3 rounded-full" style="width: 14%"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Audit Logs -->
        <div class="card p-6">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h2 class="text-lg font-semibold text-gray-900">Audit Logs</h2>
                    <p class="text-sm text-gray-500">Recent system activities</p>
                </div>
                <a href="#" class="text-sm text-primary-600 hover:text-primary-700 font-medium">View All</a>
            </div>

            <div class="table-container">
                <table class="table">
                    <thead>
                        <tr>
                            <th>User</th>
                            <th>Action</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <div class="flex items-center gap-2">
                                    <div class="w-6 h-6 rounded-full bg-primary-100 flex items-center justify-center">
                                        <span class="text-xs text-primary-600 font-medium">JD</span>
                                    </div>
                                    <span class="text-sm text-gray-900">John Doe</span>
                                </div>
                            </td>
                            <td><span class="text-sm text-gray-600">Approved loan #1234</span></td>
                            <td><span class="text-xs text-gray-500">Today, 10:30 AM</span></td>
                        </tr>
                        <tr>
                            <td>
                                <div class="flex items-center gap-2">
                                    <div class="w-6 h-6 rounded-full bg-success-100 flex items-center justify-center">
                                        <span class="text-xs text-success-600 font-medium">SM</span>
                                    </div>
                                    <span class="text-sm text-gray-900">Sarah Miller</span>
                                </div>
                            </td>
                            <td><span class="text-sm text-gray-600">Added new member</span></td>
                            <td><span class="text-xs text-gray-500">Today, 9:15 AM</span></td>
                        </tr>
                        <tr>
                            <td>
                                <div class="flex items-center gap-2">
                                    <div class="w-6 h-6 rounded-full bg-warning-100 flex items-center justify-center">
                                        <span class="text-xs text-warning-600 font-medium">RJ</span>
                                    </div>
                                    <span class="text-sm text-gray-900">Robert Johnson</span>
                                </div>
                            </td>
                            <td><span class="text-sm text-gray-600">Updated savings record</span></td>
                            <td><span class="text-xs text-gray-500">Yesterday, 4:45 PM</span></td>
                        </tr>
                        <tr>
                            <td>
                                <div class="flex items-center gap-2">
                                    <div class="w-6 h-6 rounded-full bg-danger-100 flex items-center justify-center">
                                        <span class="text-xs text-danger-600 font-medium">EM</span>
                                    </div>
                                    <span class="text-sm text-gray-900">Emily Martinez</span>
                                </div>
                            </td>
                            <td><span class="text-sm text-gray-600">Generated report</span></td>
                            <td><span class="text-xs text-gray-500">Yesterday, 2:20 PM</span></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection