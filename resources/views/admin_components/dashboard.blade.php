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
        <a href="{{ route('dashboard.members') }}" class="stat-card cursor-pointer hover:shadow-lg hover:border-primary-200 transition-all group">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 mb-1">Total Members</p>
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($totalMembers) }}</p>
                    <p class="text-xs text-success-500 mt-1 flex items-center">
                        <i data-lucide="trending-up" class="w-3 h-3 mr-1"></i>
                        Active members
                    </p>
                </div>
                <div class="w-12 h-12 bg-primary-100 rounded-xl flex items-center justify-center group-hover:bg-primary-200 transition-colors">
                    <i data-lucide="users" class="w-6 h-6 text-primary-600"></i>
                </div>
            </div>
        </a>

        <div class="stat-card cursor-pointer hover:shadow-lg hover:border-success-200 transition-all group" onclick="openSavingsHistoryModal()">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 mb-1">Total Savings</p>
                    <p class="text-2xl font-bold text-gray-900">₱{{ number_format($totalSavings, 2) }}</p>
                    <p class="text-xs text-success-500 mt-1 flex items-center">
                        <i data-lucide="trending-up" class="w-3 h-3 mr-1"></i>
                        Total balance
                    </p>
                </div>
                <div class="w-12 h-12 bg-success-100 rounded-xl flex items-center justify-center group-hover:bg-success-200 transition-colors">
                    <i data-lucide="piggy-bank" class="w-6 h-6 text-success-500"></i>
                </div>
            </div>
        </div>

        <a href="{{ route('lendings') }}" class="stat-card cursor-pointer hover:shadow-lg hover:border-warning-200 transition-all group">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 mb-1">Total Loan Receivables</p>
                    <p class="text-2xl font-bold text-gray-900">₱{{ number_format($activeLoans, 2) }}</p>
                    <p class="text-xs text-warning-500 mt-1 flex items-center">
                        <i data-lucide="clock" class="w-3 h-3 mr-1"></i>
                        Approved loans
                    </p>
                </div>
                <div class="w-12 h-12 bg-warning-100 rounded-xl flex items-center justify-center group-hover:bg-warning-200 transition-colors">
                    <i data-lucide="banknote" class="w-6 h-6 text-warning-600"></i>
                </div>
            </div>
        </a>

        <div class="stat-card hover:shadow-lg hover:border-success-200 transition-all group">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 mb-1">Earned Interests</p>
                    <p class="text-2xl font-bold text-gray-900">₱{{ number_format($earnedInterests, 2) }}</p>
                    <p class="text-xs text-success-500 mt-1 flex items-center">
                        <i data-lucide="trending-up" class="w-3 h-3 mr-1"></i>
                        From paid up loans
                    </p>
                </div>
                <div class="w-12 h-12 bg-success-100 rounded-xl flex items-center justify-center group-hover:bg-success-200 transition-colors">
                    <i data-lucide="calculator" class="w-6 h-6 text-success-500"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- To-Do List & Recent Activity Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- To-Do List -->
        <div class="card p-6">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-lg font-semibold text-gray-900">To-Do List</h2>
            </div>

            <!-- Tabs -->
            <div class="flex flex-wrap gap-2 mb-6">
                <button onclick="switchTodoTab('members')" id="todo-tab-members" class="todo-tab-btn active px-4 py-2 bg-primary-600 text-white rounded-lg font-medium text-sm shadow-md">
                    Member Approvals <span class="ml-1 px-2 py-0.5 bg-white/20 rounded-full">{{ $pendingMembersCount }}</span>
                </button>
                <button onclick="switchTodoTab('loans')" id="todo-tab-loans" class="todo-tab-btn px-4 py-2 bg-gray-100 text-gray-600 rounded-lg font-medium text-sm hover:bg-gray-200">
                    Loan Applications <span class="ml-1 px-2 py-0.5 bg-gray-200 rounded-full">{{ $pendingLoansCount }}</span>
                </button>
                <button onclick="switchTodoTab('withdrawals')" id="todo-tab-withdrawals" class="todo-tab-btn px-4 py-2 bg-gray-100 text-gray-600 rounded-lg font-medium text-sm hover:bg-gray-200">
                    Withdraw SC <span class="ml-1 px-2 py-0.5 bg-gray-200 rounded-full">{{ $pendingWithdrawalsCount }}</span>
                </button>
            </div>

            <!-- Tab Content -->
            <div id="todo-content-members" class="todo-content max-h-[350px] overflow-y-auto scrollbar-hide">
                @forelse($pendingMembersList as $item)
                <a href="{{ route('dashboard.members', ['filter' => 'pending']) }}" class="block p-4 bg-gray-50 rounded-xl hover:bg-gray-100 transition-colors mb-3">
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 bg-primary-100 rounded-full flex items-center justify-center flex-shrink-0">
                            <span class="text-sm font-semibold text-primary-600">{{ $item['initials'] }}</span>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-900">{{ $item['name'] }}</p>
                            <p class="text-sm text-gray-500">{{ $item['type'] }}</p>
                            <p class="text-xs text-gray-400 mt-1">{{ $item['time'] }}</p>
                        </div>
                        <span class="badge badge-warning">Pending</span>
                    </div>
                </a>
                @empty
                <div class="text-center py-8 text-gray-500">
                    <i data-lucide="check-circle" class="w-12 h-12 mx-auto mb-2 text-gray-300"></i>
                    <p>No pending member approvals</p>
                </div>
                @endforelse
            </div>

            <div id="todo-content-loans" class="todo-content hidden max-h-[350px] overflow-y-auto scrollbar-hide">
                @forelse($pendingLoansList as $item)
                <a href="{{ route('lendings', ['filter' => 'pending']) }}" class="block p-4 bg-gray-50 rounded-xl hover:bg-gray-100 transition-colors mb-3">
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 bg-warning-100 rounded-full flex items-center justify-center flex-shrink-0">
                            <i data-lucide="file-text" class="w-5 h-5 text-warning-600"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-900">{{ $item['name'] }}</p>
                            <p class="text-sm text-gray-500">{{ $item['type'] }} - ₱{{ number_format($item['amount'], 2) }}</p>
                            <p class="text-xs text-gray-400 mt-1">{{ $item['time'] }}</p>
                        </div>
                        <span class="badge badge-warning">Pending</span>
                    </div>
                </a>
                @empty
                <div class="text-center py-8 text-gray-500">
                    <i data-lucide="check-circle" class="w-12 h-12 mx-auto mb-2 text-gray-300"></i>
                    <p>No pending loan applications</p>
                </div>
                @endforelse
            </div>

            <div id="todo-content-withdrawals" class="todo-content hidden max-h-[350px] overflow-y-auto scrollbar-hide">
                @forelse($pendingWithdrawalsList as $item)
                <a href="{{ route('sharecapitals', ['filter' => 'withdrawal', 'status' => 'pending']) }}" class="block p-4 bg-gray-50 rounded-xl hover:bg-gray-100 transition-colors mb-3">
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 bg-danger-100 rounded-full flex items-center justify-center flex-shrink-0">
                            <i data-lucide="banknote" class="w-5 h-5 text-danger-600"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-900">{{ $item['name'] }}</p>
                            <p class="text-sm text-gray-500">{{ $item['type'] }} - ₱{{ number_format($item['amount'], 2) }}</p>
                            <p class="text-xs text-gray-400 mt-1">{{ $item['time'] }}</p>
                        </div>
                        <span class="badge badge-warning">Pending</span>
                    </div>
                </a>
                @empty
                <div class="text-center py-8 text-gray-500">
                    <i data-lucide="check-circle" class="w-12 h-12 mx-auto mb-2 text-gray-300"></i>
                    <p>No pending withdrawal requests</p>
                </div>
                @endforelse
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="card p-6">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-lg font-semibold text-gray-900">Recent Activity</h2>
            </div>

            <!-- Tabs -->
            <div class="flex flex-wrap gap-2 mb-6">
                <button onclick="switchActivityTab('transactions')" id="activity-tab-transactions" class="activity-tab-btn active px-4 py-2 bg-primary-600 text-white rounded-lg font-medium text-sm shadow-md">
                    Transactions
                </button>
                <button onclick="switchActivityTab('approvals')" id="activity-tab-approvals" class="activity-tab-btn px-4 py-2 bg-gray-100 text-gray-600 rounded-lg font-medium text-sm hover:bg-gray-200">
                    Approvals
                </button>
            </div>

            <!-- Transactions Tab Content -->
            <div id="activity-content-transactions" class="activity-content max-h-[350px] overflow-y-auto scrollbar-hide">
                @forelse($recentTransactions as $activity)
                <div class="flex items-start gap-4 p-4 bg-gray-50 rounded-xl mb-3">
                    @if($activity['type'] === 'savings')
                    <div class="w-10 h-10 {{ $activity['subtype'] === 'deposit' ? 'bg-success-100' : 'bg-danger-100' }} rounded-full flex items-center justify-center flex-shrink-0">
                        <i data-lucide="{{ $activity['subtype'] === 'deposit' ? 'arrow-down-circle' : 'arrow-up-circle' }}" class="w-5 h-5 {{ $activity['subtype'] === 'deposit' ? 'text-success-500' : 'text-danger-600' }}"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-900">{{ $activity['title'] }}</p>
                        <p class="text-sm text-gray-500">{{ $activity['user'] }}</p>
                        <p class="text-xs text-gray-400 mt-1">{{ $activity['time'] }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-sm font-semibold {{ $activity['subtype'] === 'deposit' ? 'text-success-600' : 'text-danger-600' }}">{{ $activity['subtype'] === 'deposit' ? '+' : '-' }}₱{{ number_format($activity['amount'], 2) }}</p>
                        <span class="badge badge-success text-xs">{{ $activity['status'] }}</span>
                    </div>
                    @else
                    <div class="w-10 h-10 {{ $activity['subtype'] === 'subscription' ? 'bg-primary-100' : ($activity['subtype'] === 'deposit' ? 'bg-success-100' : 'bg-warning-100') }} rounded-full flex items-center justify-center flex-shrink-0">
                        <i data-lucide="coins" class="w-5 h-5 {{ $activity['subtype'] === 'subscription' ? 'text-primary-600' : ($activity['subtype'] === 'deposit' ? 'text-success-500' : 'text-warning-600') }}"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-900">{{ $activity['title'] }}</p>
                        <p class="text-sm text-gray-500">{{ $activity['user'] }}</p>
                        <p class="text-xs text-gray-400 mt-1">{{ $activity['time'] }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-sm font-semibold text-gray-900">₱{{ number_format($activity['amount'], 2) }}</p>
                        <span class="badge badge-success text-xs">{{ $activity['status'] }}</span>
                    </div>
                    @endif
                </div>
                @empty
                <div class="text-center py-8 text-gray-500">
                    <i data-lucide="inbox" class="w-12 h-12 mx-auto mb-2 text-gray-300"></i>
                    <p>No recent transactions</p>
                </div>
                @endforelse
            </div>

            <!-- Approvals Tab Content -->
            <div id="activity-content-approvals" class="activity-content hidden max-h-[350px] overflow-y-auto scrollbar-hide">
                @forelse($recentMemberApprovals as $activity)
                <div class="flex items-start gap-4 p-4 bg-gray-50 rounded-xl mb-3">
                    <div class="w-10 h-10 bg-primary-100 rounded-full flex items-center justify-center flex-shrink-0">
                        <i data-lucide="user-{{ $activity['status'] === 'Active' ? 'check' : 'plus' }}" class="w-5 h-5 text-primary-600"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-900">{{ $activity['title'] }}</p>
                        <p class="text-sm text-gray-500">{{ $activity['user'] }}</p>
                        <p class="text-xs text-gray-400 mt-1">{{ $activity['time'] }}</p>
                    </div>
                    <span class="badge badge-{{ $activity['status'] === 'Active' ? 'success' : 'warning' }}">{{ $activity['status'] }}</span>
                </div>
                @empty
                <div class="text-center py-8 text-gray-500">
                    <i data-lucide="inbox" class="w-12 h-12 mx-auto mb-2 text-gray-300"></i>
                    <p>No recent approvals</p>
                </div>
                @endforelse
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
                @php
                    $maxValue = max($savingsByMonth) ?: 1;
                @endphp
                @forelse($savingsByMonth as $month => $value)
                    <div class="flex-1 flex flex-col items-center gap-2 group/savings-bar">
                        <div class="w-full bg-primary-100 rounded-t-lg hover:bg-primary-200 transition-colors cursor-pointer relative overflow-visible"
                            style="height: {{ max(($value / $maxValue) * 200, 5) }}px">
                            <div class="absolute -top-8 left-1/2 -translate-x-1/2 bg-gray-900 text-white text-xs px-2 py-1 rounded whitespace-nowrap opacity-0 group-hover/savings-bar:opacity-100 transition-opacity pointer-events-none">
                                ₱{{ number_format($value, 2) }}
                            </div>
                        </div>
                        <span class="text-xs text-gray-500">{{ $month }}</span>
                    </div>
                @empty
                    @foreach(['Jan' => 0, 'Feb' => 0, 'Mar' => 0, 'Apr' => 0, 'May' => 0, 'Jun' => 0] as $month => $value)
                    <div class="flex-1 flex flex-col items-center gap-2">
                        <div class="w-full bg-primary-100 rounded-t-lg hover:bg-primary-200 transition-colors cursor-pointer relative group"
                            style="height: 5px">
                            <div
                                class="absolute -top-8 left-1/2 -translate-x-1/2 bg-gray-900 text-white text-xs px-2 py-1 rounded hidden group-hover:block whitespace-nowrap">
                                ₱0
                            </div>
                        </div>
                        <span class="text-xs text-gray-500">{{ $month }}</span>
                    </div>
                    @endforeach
                @endforelse
            </div>
        </div>

        <!-- Savings Activity -->
        <div class="card p-6">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-lg font-semibold text-gray-900">Savings Activity</h2>
            </div>

            <div class="flex items-center justify-center">
                <div class="relative w-40 h-40">
                    @php
                        $totalSavingsTx = $totalDeposits + $totalWithdrawals;
                        $depositPercent = $totalSavingsTx > 0 ? ($totalDeposits / $totalSavingsTx) * 100 : 50;
                        $withdrawalPercent = $totalSavingsTx > 0 ? ($totalWithdrawals / $totalSavingsTx) * 100 : 50;
                        $depositDash = ($depositPercent / 100) * 251.2;
                        $withdrawalDash = ($withdrawalPercent / 100) * 251.2;
                    @endphp
                    <svg viewBox="0 0 100 100" class="transform -rotate-90 w-40 h-40">
                        <circle cx="50" cy="50" r="40" fill="none" stroke="#fecaca" stroke-width="12" />
                        <circle cx="50" cy="50" r="40" fill="none" stroke="#22c55e" stroke-width="12"
                            stroke-dasharray="{{ $depositDash }} 251.2" stroke-dashoffset="0" />
                        <circle cx="50" cy="50" r="40" fill="none" stroke="#ef4444" stroke-width="12"
                            stroke-dasharray="{{ $withdrawalDash }} 251.2" stroke-dashoffset="{{ -$depositDash }}" />
                    </svg>
                    <div class="absolute inset-0 flex items-center justify-center">
                        <div class="text-center">
                            <p class="text-2xl font-bold text-gray-900">{{ number_format($totalSavingsTx) }}</p>
                            <p class="text-xs text-gray-500">Total</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-6 space-y-2">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <div class="w-3 h-3 bg-success-500 rounded-full"></div>
                        <span class="text-sm text-gray-600">Deposits</span>
                    </div>
                    <span class="text-sm font-medium text-gray-900">{{ $totalDeposits }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <div class="w-3 h-3 bg-danger-500 rounded-full"></div>
                        <span class="text-sm text-gray-600">Withdrawals</span>
                    </div>
                    <span class="text-sm font-medium text-gray-900">{{ $totalWithdrawals }}</span>
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
                    <p class="text-sm text-gray-500">By loan type</p>
                </div>
                
            </div>

            <div class="grid grid-cols-2 gap-3">
                @php
                    $totalLoanCount = array_sum($loanTypeCounts);
                    $colors = ['primary-600', 'success-500', 'warning-500', 'danger-500'];
                    $types = ['Personal', 'Education', 'Emergency', 'Business'];
                    $colorIndex = 0;
                @endphp
                @forelse($types as $type)
                <div class="bg-gray-50 rounded-lg p-4">
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">{{ $type }}</span>
                        <span class="text-lg font-bold text-gray-900">{{ $loanTypeCounts[$type] ?? 0 }}</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2 mt-2">
                        <div class="bg-{{ $colors[$colorIndex] }} h-2 rounded-full" style="width: {{ $totalLoanCount > 0 ? (($loanTypeCounts[$type] ?? 0) / $totalLoanCount) * 100 : 0 }}%"></div>
                    </div>
                </div>
                @php $colorIndex++; @endphp
                @empty
                <div class="col-span-2 text-center py-8 text-gray-500">
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
                        @forelse($recentTransactions->take(5) as $activity)
                        <tr>
                            <td>
                                <div class="flex items-center gap-2">
                                    <div class="w-6 h-6 rounded-full bg-primary-100 flex items-center justify-center">
                                        <span class="text-xs text-primary-600 font-medium">{{ $activity['initials'] }}</span>
                                    </div>
                                    <span class="text-sm text-gray-900">{{ $activity['user'] }}</span>
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

    <!-- Savings Transaction History Modal -->
    <div id="savingsHistoryModal" class="modal-overlay hidden" style="display:none">
        <div class="modal max-w-4xl">
            <div class="p-6 border-b border-gray-100">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-success-100 flex items-center justify-center">
                            <i data-lucide="piggy-bank" class="w-5 h-5 text-success-600"></i>
                        </div>
                        <div>
                            <h2 class="text-xl font-bold text-gray-900">Savings Overview</h2>
                            <p class="text-xs text-gray-500">Recent savings transactions</p>
                        </div>
                    </div>
                    <button onclick="closeModal('savingsHistoryModal')" class="p-2 hover:bg-gray-100 rounded-lg transition-colors">
                        <i data-lucide="x" class="w-5 h-5 text-gray-500"></i>
                    </button>
                </div>
            </div>
            <div class="p-6 max-h-[60vh] overflow-y-auto">
                <!-- Summary Stats -->
                <div class="grid grid-cols-3 gap-4 mb-6">
                    <div class="bg-success-50 rounded-lg p-4 border border-success-100 text-center">
                        <p class="text-xs text-gray-500 mb-1">Total Savings</p>
                        <p id="savings-modal-total" class="text-xl font-bold text-gray-900">₱0.00</p>
                    </div>
                    <div class="bg-primary-50 rounded-lg p-4 border border-primary-100 text-center">
                        <p class="text-xs text-gray-500 mb-1">Total Transactions</p>
                        <p id="savings-modal-count" class="text-xl font-bold text-gray-900">0</p>
                    </div>
                    <div class="bg-blue-50 rounded-lg p-4 border border-blue-100 text-center">
                        <p class="text-xs text-gray-500 mb-1">Average</p>
                        <p id="savings-modal-avg" class="text-xl font-bold text-gray-900">₱0.00</p>
                    </div>
                </div>

                <!-- Quick Links -->
                <div class="flex gap-3 mb-6">
                    <a href="{{ route('savings') }}" class="flex-1 px-4 py-2.5 bg-success-600 text-white text-sm font-medium rounded-lg hover:bg-success-700 transition-colors flex items-center justify-center gap-2">
                        <i data-lucide="external-link" class="w-4 h-4"></i>
                        View Full Savings Page
                    </a>
                </div>

                <!-- Recent Transactions -->
                <h4 class="font-semibold text-gray-900 mb-4">Recent Transactions</h4>
                <div id="savings-modal-transactions" class="space-y-3">
                    <!-- Transactions will be loaded here -->
                </div>
                @if(isset($recentSavingsTransactions) && $recentSavingsTransactions->count() > 0)
                    <div class="hidden" id="savings-transactions-data">
                        {!! json_encode($recentSavingsTransactions) !!}
                    </div>
                @endif
            </div>
            <div class="p-6 border-t border-gray-100 flex justify-end gap-3">
                <button onclick="closeModal('savingsHistoryModal')" class="px-5 py-2.5 text-gray-700 font-medium rounded-lg hover:bg-gray-100 transition-colors">Close</button>
                <a href="{{ route('savings') }}" class="px-5 py-2.5 bg-primary-600 text-white font-medium rounded-lg hover:bg-primary-700 transition-colors flex items-center gap-2">
                    <i data-lucide="arrow-right" class="w-4 h-4"></i>
                    Go to Savings
                </a>
            </div>
        </div>
    </div>

    <!-- Savings Transactions Data -->
    @php
        $savingsData = isset($recentSavingsTransactions) ? $recentSavingsTransactions : collect();
    @endphp
    <script type="application/json" id="savings-modal-data">
        {!! json_encode([
            'total' => $totalSavings,
            'transactions' => $recentSavingsTransactions ?? collect()
        ]) !!}
    </script>

    <script>
        const savingsModalData = JSON.parse(document.getElementById('savings-modal-data').textContent);
        
        function openSavingsHistoryModal() {
            const transactions = savingsModalData.transactions || [];
            const total = savingsModalData.total || 0;
            
            document.getElementById('savings-modal-total').textContent = '₱' + total.toLocaleString('en-PH', {minimumFractionDigits: 2});
            document.getElementById('savings-modal-count').textContent = transactions.length || 0;
            document.getElementById('savings-modal-avg').textContent = transactions.length > 0 ? '₱' + (total / transactions.length).toLocaleString('en-PH', {minimumFractionDigits: 2}) : '₱0.00';
            
            const container = document.getElementById('savings-modal-transactions');
            if (transactions.length > 0) {
                container.innerHTML = transactions.slice(0, 10).map(tx => {
                    const isDeposit = tx.type === 'deposit' || tx.type === 'Deposit';
                    const bgColor = isDeposit ? 'bg-success-50 border-success-100' : 'bg-danger-50 border-danger-100';
                    const iconBg = isDeposit ? 'bg-success-100' : 'bg-danger-100';
                    const iconColor = isDeposit ? 'text-success-600' : 'text-danger-600';
                    const amountColor = isDeposit ? 'text-success-600' : 'text-danger-600';
                    const amountPrefix = isDeposit ? '+' : '-';
                    const date = new Date(tx.created_at).toLocaleDateString('en-PH', {month: 'short', day: 'numeric', year: 'numeric'});
                    return `
                        <div class="flex items-center gap-4 p-4 ${bgColor} rounded-lg border hover:bg-opacity-70 transition-colors">
                            <div class="w-10 h-10 ${iconBg} rounded-full flex items-center justify-center flex-shrink-0">
                                <i data-lucide="${isDeposit ? 'arrow-down-circle' : 'arrow-up-circle'}" class="w-5 h-5 ${iconColor}"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-900">${tx.user_name || 'Member'}</p>
                                <p class="text-xs text-gray-500">${date} ${tx.reference_no ? '• ' + tx.reference_no : ''}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-semibold ${amountColor}">${amountPrefix}₱${parseFloat(tx.amount || tx.total_amount || 0).toLocaleString('en-PH', {minimumFractionDigits: 2})}</p>
                                <p class="text-xs text-gray-500">${tx.type || 'Transaction'}</p>
                            </div>
                        </div>
                    `;
                }).join('');
            } else {
                container.innerHTML = `
                    <div class="text-center py-8 text-gray-500">
                        <i data-lucide="inbox" class="w-12 h-12 mx-auto mb-2 text-gray-300"></i>
                        <p>No savings transactions found</p>
                    </div>
                `;
            }
            
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }
            
            openModal('savingsHistoryModal');
        }

        

        <!-- To-Do List Tab Switching
        function switchTodoTab(tab) {
            const contents = document.querySelectorAll('.todo-content');
            const buttons = document.querySelectorAll('.todo-tab-btn');
            
            contents.forEach(content => content.classList.add('hidden'));
            buttons.forEach(btn => {
                btn.classList.remove('bg-primary-600', 'text-white', 'shadow-md');
                btn.classList.add('bg-gray-100', 'text-gray-600', 'hover:bg-gray-200');
            });
            
            document.getElementById('todo-content-' + tab).classList.remove('hidden');
            document.getElementById('todo-tab-' + tab).classList.remove('bg-gray-100', 'text-gray-600', 'hover:bg-gray-200');
            document.getElementById('todo-tab-' + tab).classList.add('bg-primary-600', 'text-white', 'shadow-md');
        }

        // Recent Activity Tab Switching
        function switchActivityTab(tab) {
            const contents = document.querySelectorAll('.activity-content');
            const buttons = document.querySelectorAll('.activity-tab-btn');
            
            contents.forEach(content => content.classList.add('hidden'));
            buttons.forEach(btn => {
                btn.classList.remove('bg-primary-600', 'text-white', 'shadow-md');
                btn.classList.add('bg-gray-100', 'text-gray-600', 'hover:bg-gray-200');
            });
            
            document.getElementById('activity-content-' + tab).classList.remove('hidden');
            document.getElementById('activity-tab-' + tab).classList.remove('bg-gray-100', 'text-gray-600', 'hover:bg-gray-200');
            document.getElementById('activity-tab-' + tab).classList.add('bg-primary-600', 'text-white', 'shadow-md');
        }

        
    </script>

    <style>
        .scrollbar-hide::-webkit-scrollbar {
            display: none;
        }
        .scrollbar-hide {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
    </style>
@endsection