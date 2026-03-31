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
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($totalMembers) }}</p>
                    <p class="text-xs text-success-500 mt-1 flex items-center">
                        <i data-lucide="trending-up" class="w-3 h-3 mr-1"></i>
                        Active members
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
                    <p class="text-2xl font-bold text-gray-900">₱{{ number_format($totalSavings, 2) }}</p>
                    <p class="text-xs text-success-500 mt-1 flex items-center">
                        <i data-lucide="trending-up" class="w-3 h-3 mr-1"></i>
                        Total balance
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
                    <p class="text-2xl font-bold text-gray-900">₱{{ number_format($activeLoans, 2) }}</p>
                    <p class="text-xs text-warning-500 mt-1 flex items-center">
                        <i data-lucide="clock" class="w-3 h-3 mr-1"></i>
                        Approved loans
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
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($pendingRequests) }}</p>
                    <p class="text-xs text-danger-500 mt-1 flex items-center">
                        <i data-lucide="alert-circle" class="w-3 h-3 mr-1"></i>
                        Awaiting approval
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
                @forelse($recentActivities as $activity)
                <div class="flex items-start gap-4 p-4 bg-gray-50 rounded-xl hover:bg-gray-100 transition-colors cursor-pointer">
                    @if($activity['type'] === 'savings')
                    <div class="w-10 h-10 bg-success-100 rounded-full flex items-center justify-center flex-shrink-0">
                        <i data-lucide="piggy-bank" class="w-5 h-5 text-success-500"></i>
                    </div>
                    @elseif($activity['type'] === 'loan')
                    <div class="w-10 h-10 {{ $activity['status'] === 'Approved' ? 'bg-success-100' : ($activity['status'] === 'Declined' ? 'bg-danger-100' : 'bg-warning-100') }} rounded-full flex items-center justify-center flex-shrink-0">
                        <i data-lucide="file-text" class="w-5 h-5 {{ $activity['status'] === 'Approved' ? 'text-success-500' : ($activity['status'] === 'Declined' ? 'text-danger-600' : 'text-warning-600') }}"></i>
                    </div>
                    @else
                    <div class="w-10 h-10 bg-primary-100 rounded-full flex items-center justify-center flex-shrink-0">
                        <i data-lucide="user-plus" class="w-5 h-5 text-primary-600"></i>
                    </div>
                    @endif
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-900">{{ $activity['title'] }}</p>
                        <p class="text-sm text-gray-500">{{ $activity['description'] }}</p>
                        <p class="text-xs text-gray-400 mt-1">{{ $activity['time'] }}</p>
                    </div>
                    <span class="badge badge-{{ $activity['status'] === 'Approved' || $activity['status'] === 'Completed' || $activity['status'] === 'Active' ? 'success' : ($activity['status'] === 'Declined' ? 'danger' : 'warning') }}">{{ $activity['status'] }}</span>
                </div>
                @empty
                <div class="text-center py-8 text-gray-500">
                    <i data-lucide="inbox" class="w-12 h-12 mx-auto mb-2 text-gray-300"></i>
                    <p>No recent activities</p>
                </div>
                @endforelse
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
                    <span class="text-sm text-gray-500">{{ $adminCount }}</span>
                </div>

                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 bg-success-100 rounded-full flex items-center justify-center">
                            <i data-lucide="user-check" class="w-4 h-4 text-success-500"></i>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-900">Member</p>
                            <p class="text-xs text-gray-500">Active members</p>
                        </div>
                    </div>
                    <span class="text-sm text-gray-500">{{ $memberCount }}</span>
                </div>

                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 bg-warning-100 rounded-full flex items-center justify-center">
                            <i data-lucide="clock" class="w-4 h-4 text-warning-600"></i>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-900">Pending</p>
                            <p class="text-xs text-gray-500">Awaiting approval</p>
                        </div>
                    </div>
                    <span class="text-sm text-gray-500">{{ $pendingCount }}</span>
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
                @forelse($savingsByMonth as $month => $value)
                    <div class="flex-1 flex flex-col items-center gap-2">
                        <div class="w-full bg-primary-100 rounded-t-lg hover:bg-primary-200 transition-colors cursor-pointer relative group"
                            style="height: {{ max($value * 2.5, 10) }}px">
                            <div
                                class="absolute -top-8 left-1/2 -translate-x-1/2 bg-gray-900 text-white text-xs px-2 py-1 rounded hidden group-hover:block whitespace-nowrap">
                                ₱{{ $value }}K
                            </div>
                        </div>
                        <span class="text-xs text-gray-500">{{ $month }}</span>
                    </div>
                @empty
                    @foreach(['Jan' => 0, 'Feb' => 0, 'Mar' => 0, 'Apr' => 0, 'May' => 0, 'Jun' => 0] as $month => $value)
                    <div class="flex-1 flex flex-col items-center gap-2">
                        <div class="w-full bg-primary-100 rounded-t-lg hover:bg-primary-200 transition-colors cursor-pointer relative group"
                            style="height: 10px">
                            <div
                                class="absolute -top-8 left-1/2 -translate-x-1/2 bg-gray-900 text-white text-xs px-2 py-1 rounded hidden group-hover:block whitespace-nowrap">
                                ₱0K
                            </div>
                        </div>
                        <span class="text-xs text-gray-500">{{ $month }}</span>
                    </div>
                    @endforeach
                @endforelse
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
                        @php
                            $totalPercentage = $memberActivity['active'] + $memberActivity['new'];
                            $activeDash = $totalPercentage > 0 ? ($memberActivity['active'] / 100) * 251.2 : 0;
                            $newDash = $totalPercentage > 0 ? ($memberActivity['new'] / 100) * 251.2 : 251.2;
                        @endphp
                        <circle cx="50" cy="50" r="40" fill="none" stroke="#1E4035" stroke-width="12"
                            stroke-dasharray="{{ $activeDash }} 251.2" stroke-dashoffset="0" />
                        <circle cx="50" cy="50" r="40" fill="none" stroke="#2A5C4E" stroke-width="12"
                            stroke-dasharray="{{ $newDash }} 251.2" stroke-dashoffset="{{ -$activeDash }}" />
                    </svg>
                    <div class="absolute inset-0 flex items-center justify-center">
                        <div class="text-center">
                            <p class="text-2xl font-bold text-gray-900">{{ number_format($totalMembers + $pendingCount) }}</p>
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
                    <span class="text-sm font-medium text-gray-900">{{ $memberActivity['active'] }}%</span>
                </div>
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <div class="w-3 h-3 bg-success-500 rounded-full"></div>
                        <span class="text-sm text-gray-600">Pending</span>
                    </div>
                    <span class="text-sm font-medium text-gray-900">{{ $memberActivity['new'] }}%</span>
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
                @php
                    $totalLoans = array_sum($loansByPurpose);
                    $colors = ['primary-600', 'success-500', 'warning-500', 'danger-500'];
                    $colorIndex = 0;
                @endphp
                @forelse($loansByPurpose as $purpose => $amount)
                @if($amount > 0)
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm text-gray-600">{{ $purpose }}</span>
                        <span class="text-sm font-medium text-gray-900">₱{{ number_format($amount, 2) }}</span>
                    </div>
                    <div class="w-full bg-gray-100 rounded-full h-3">
                        <div class="bg-{{ $colors[$colorIndex % 4] }} h-3 rounded-full" style="width: {{ $totalLoans > 0 ? ($amount / $totalLoans) * 100 : 0 }}%"></div>
                    </div>
                </div>
                @php $colorIndex++; @endphp
                @endif
                @empty
                <div class="text-center py-8 text-gray-500">
                    <i data-lucide="banknote" class="w-12 h-12 mx-auto mb-2 text-gray-300"></i>
                    <p>No loan data available</p>
                </div>
                @endforelse
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
                        @forelse($recentActivities->take(5) as $activity)
                        <tr>
                            <td>
                                <div class="flex items-center gap-2">
                                    <div class="w-6 h-6 rounded-full bg-primary-100 flex items-center justify-center">
                                        <span class="text-xs text-primary-600 font-medium">{{ $activity['initials'] }}</span>
                                    </div>
                                    <span class="text-sm text-gray-900">{{ explode(' - ', $activity['description'])[0] ?? 'Unknown' }}</span>
                                </div>
                            </td>
                            <td><span class="text-sm text-gray-600">{{ $activity['title'] }}</span></td>
                            <td><span class="text-xs text-gray-500">{{ $activity['time'] }}</span></td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="text-center py-4 text-gray-500">No audit logs available</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection