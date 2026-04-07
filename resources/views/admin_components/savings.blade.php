@extends('layouts.admin')

@section('title', 'Savings - CoopAdmin')

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
                    <span class="text-gray-900 font-medium">Savings</span>
                </li>
            </ol>
        </nav>
    </div>

    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Savings</h1>
            <p class="text-sm text-gray-500">Manage member savings and contributions</p>
        </div>
        <div class="flex items-center gap-3">
            <button onclick="openModal('addContributionModal')" class="btn btn-primary">
                <i data-lucide="plus" class="w-4 h-4"></i>
                Add Contribution
            </button>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <a href="{{ route('savings') }}" class="stat-card cursor-pointer hover:shadow-lg hover:border-success-200 transition-all group">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 mb-1">Current Balance</p>
                    <p class="text-2xl font-bold text-gray-900">₱{{ number_format($currentBalance, 2) }}</p>
                    <p class="text-xs text-success-500 mt-1 flex items-center">
                        <i data-lucide="trending-up" class="w-3 h-3 mr-1"></i>
                        Total balance
                    </p>
                </div>
                <div class="w-12 h-12 bg-success-100 rounded-xl flex items-center justify-center group-hover:bg-success-200 transition-colors">
                    <i data-lucide="wallet" class="w-6 h-6 text-success-500"></i>
                </div>
            </div>
        </a>

        <div class="stat-card cursor-pointer hover:shadow-lg hover:border-primary-200 transition-all group" onclick="openMonthlyBreakdownModal()">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 mb-1">Monthly Average</p>
                    <p class="text-2xl font-bold text-gray-900">₱{{ number_format($monthlyAvg, 2) }}</p>
                    <p class="text-xs text-gray-500 mt-1 flex items-center">
                        <i data-lucide="mouse-pointer-click" class="w-3 h-3 mr-1"></i>
                        Click for breakdown
                    </p>
                </div>
                <div class="w-12 h-12 bg-primary-100 rounded-xl flex items-center justify-center group-hover:bg-primary-200 transition-colors">
                    <i data-lucide="bar-chart-2" class="w-6 h-6 text-primary-600"></i>
                </div>
            </div>
        </div>

        <div class="stat-card cursor-pointer hover:shadow-lg hover:border-warning-200 transition-all group" onclick="openLastContributionModal()">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 mb-1">Last Contribution</p>
                    <p class="text-2xl font-bold text-gray-900">₱{{ number_format($lastContribution->amount ?? 0, 2) }}</p>
                    <p class="text-xs text-gray-500 mt-1 flex items-center">
                        <i data-lucide="mouse-pointer-click" class="w-3 h-3 mr-1"></i>
                        {{ $lastContribution ? $lastContribution->created_at->format('M d, Y g:i A') : 'No transactions' }}
                    </p>
                </div>
                <div class="w-12 h-12 bg-warning-100 rounded-xl flex items-center justify-center group-hover:bg-warning-200 transition-colors">
                    <i data-lucide="calendar" class="w-6 h-6 text-warning-600"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <form method="GET" action="{{ route('savings') }}">
        <div class="card p-4 mb-6">
            <div class="flex flex-col md:flex-row gap-4">
                <div class="flex-1">
                    <div class="relative">
                        <i data-lucide="search" class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400"></i>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by member name..." class="input pl-10">
                    </div>
                </div>
                <div class="flex gap-2">
                    <select name="type" class="select w-40" onchange="this.form.submit()">
                        <option value="all">All Types</option>
                        <option value="deposit" {{ request('type') === 'deposit' ? 'selected' : '' }}>Deposit</option>
                        <option value="withdraw" {{ request('type') === 'withdraw' ? 'selected' : '' }}>Withdrawal</option>
                    </select>
                    <select name="status" class="select w-32" onchange="this.form.submit()">
                        <option value="all">All Status</option>
                        <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed</option>
                        <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                    </select>
                </div>
            </div>
        </div>
    </form>

    <!-- Transactions Table -->
    <div class="card">
        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Member Name</th>
                        <th>Amount</th>
                        <th>Type</th>
                        <th>Payment Method</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transactions as $tx)
                    <tr>
                        <td class="text-sm text-gray-900">{{ $tx->created_at->format('M d, Y') }}</td>
                        <td>
                            <div class="flex items-center gap-2">
                                <div class="w-8 h-8 rounded-full bg-primary-100 flex items-center justify-center">
                                    <span class="text-xs text-primary-600 font-medium">
                                        {{ strtoupper(substr($tx->savingsAccount->user->first_name ?? 'U', 0, 1) . substr($tx->savingsAccount->user->last_name ?? '', 0, 1)) }}
                                    </span>
                                </div>
                                <span class="text-sm text-gray-900">
                                    {{ $tx->savingsAccount->user->first_name ?? 'Unknown' }} {{ $tx->savingsAccount->user->last_name ?? '' }}
                                </span>
                            </div>
                        </td>
                        <td class="text-sm font-semibold text-gray-900">₱{{ number_format($tx->amount, 2) }}</td>
                        <td>
                            @if($tx->type === 'deposit')
                                <span class="badge badge-success">Deposit</span>
                            @else
                                <span class="badge badge-danger">Withdrawal</span>
                            @endif
                        </td>
                        <td class="text-sm text-gray-600">{{ $tx->payment_method ?? 'N/A' }}</td>
                        <td>
                            @if($tx->status === 'completed' || $tx->type === 'deposit')
                                <span class="badge badge-success">Completed</span>
                            @elseif($tx->status === 'pending')
                                <span class="badge badge-warning">Pending</span>
                            @else
                                <span class="badge badge-success">Completed</span>
                            @endif
                        </td>
                        <td>
                            <div class="flex items-center gap-1">
                                <button class="p-1.5 hover:bg-gray-100 rounded" title="View Receipt">
                                    <i data-lucide="file-text" class="w-4 h-4 text-gray-500"></i>
                                </button>
                                <form method="POST" action="{{ route('savings.archive', $tx->id) }}">
                                    @csrf
                                    <button type="submit" class="p-1.5 hover:bg-gray-100 rounded" title="Archive">
                                        <i data-lucide="archive" class="w-4 h-4 text-gray-500"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-8">
                            <div class="flex flex-col items-center text-gray-500">
                                <i data-lucide="inbox" class="w-12 h-12 mb-3 opacity-50"></i>
                                <p>No transactions found</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="p-4 border-t border-gray-100 flex items-center justify-between">
            <p class="text-sm text-gray-500">
                Showing {{ $transactions->firstItem() ?? 0 }}-{{ $transactions->lastItem() ?? 0 }} of {{ $transactions->total() }} transactions
            </p>
            <div>
                {{ $transactions->appends(request()->query())->links() }}
            </div>
        </div>
    </div>

    <!-- Add Contribution Modal -->
    <div id="addContributionModal" class="modal-overlay hidden">
        <div class="modal max-w-lg">
            <div class="p-6 border-b border-gray-100">
                <div class="flex items-center justify-between">
                    <h2 class="text-xl font-semibold text-gray-900">Add Contribution</h2>
                    <button onclick="closeModal('addContributionModal')" class="p-1 hover:bg-gray-100 rounded-lg">
                        <i data-lucide="x" class="w-5 h-5 text-gray-500"></i>
                    </button>
                </div>
            </div>
            <form class="p-6 space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Member</label>
                    <select class="select">
                        <option>Select member</option>
                        @foreach($allMembers as $member)
                        <option value="{{ $member->id }}">{{ $member->first_name }} {{ $member->last_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Amount</label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500">₱</span>
                        <input type="number" class="input pl-8" placeholder="0.00">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Date</label>
                    <input type="date" class="input">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Type</label>
                    <select class="select">
                        <option>Deposit</option>
                        <option>Withdrawal</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Payment Method</label>
                    <select class="select">
                        <option>Cash</option>
                        <option>Bank Transfer</option>
                        <option>GCash</option>
                        <option>Check</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Notes</label>
                    <textarea class="input" rows="3" placeholder="Add any notes..."></textarea>
                </div>
            </form>
            <div class="p-6 border-t border-gray-100 flex justify-end gap-3">
                <button onclick="closeModal('addContributionModal')" class="btn btn-outline">Cancel</button>
                <button
                    onclick="closeModal('addContributionModal'); showToast('Success', 'Contribution added successfully')"
                    class="btn btn-primary">Save</button>
            </div>
        </div>
    </div>

    <!-- Monthly Breakdown Modal -->
    <div id="monthlyBreakdownModal" class="modal-overlay hidden">
        <div class="modal max-w-2xl">
            <div class="p-6 border-b border-gray-100">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-primary-100 flex items-center justify-center">
                            <i data-lucide="bar-chart-2" class="w-5 h-5 text-primary-600"></i>
                        </div>
                        <div>
                            <h2 class="text-xl font-bold text-gray-900">Monthly Breakdown</h2>
                            <p class="text-xs text-gray-500">Savings by month (Last 6 months)</p>
                        </div>
                    </div>
                    <button onclick="closeModal('monthlyBreakdownModal')" class="p-2 hover:bg-gray-100 rounded-lg transition-colors">
                        <i data-lucide="x" class="w-5 h-5 text-gray-500"></i>
                    </button>
                </div>
            </div>
            <div class="p-6 max-h-[60vh] overflow-y-auto">
                <!-- Summary -->
                <div class="grid grid-cols-2 gap-4 mb-6">
                    <div class="bg-primary-50 rounded-lg p-4 border border-primary-100 text-center">
                        <p class="text-xs text-gray-500 mb-1">Average Monthly</p>
                        <p class="text-xl font-bold text-gray-900">₱{{ number_format($monthlyAvg, 2) }}</p>
                    </div>
                    <div class="bg-success-50 rounded-lg p-4 border border-success-100 text-center">
                        <p class="text-xs text-gray-500 mb-1">Highest Month</p>
                        <p class="text-xl font-bold text-gray-900">₱{{ number_format($highestMonth['amount'] ?? 0, 2) }}</p>
                        <p class="text-xs text-gray-500">{{ $highestMonth['name'] ?? 'N/A' }}</p>
                    </div>
                </div>

                <!-- Monthly Chart -->
                <h4 class="font-semibold text-gray-900 mb-4">Monthly Savings</h4>
                @php
                    $hasData = collect($monthlyData)->sum('amount') > 0;
                @endphp
                @if($hasData)
                <div class="h-48 flex items-end justify-between gap-3 px-4 mb-4">
                    @foreach($monthlyData as $month)
                        @php
                            $barHeight = $month['bar_height'];
                            $hasAmount = $month['amount'] > 0;
                        @endphp
                        <div class="flex-1 flex flex-col items-center gap-2">
                            <div class="w-full {{ $hasAmount ? 'bg-primary-100 hover:bg-primary-200' : 'bg-gray-100 border-2 border-dashed border-gray-300' }} rounded-t-lg transition-colors cursor-pointer relative group"
                                style="height: {{ max($barHeight, 10) }}px" onclick="showMonthDetail('{{ $month['name'] }}', {{ $month['amount'] }})">
                                @if($hasAmount)
                                <div class="absolute -top-8 left-1/2 -translate-x-1/2 bg-gray-900 text-white text-xs px-2 py-1 rounded hidden group-hover:block whitespace-nowrap z-10">
                                    ₱{{ number_format($month['amount'], 2) }}
                                </div>
                                @endif
                            </div>
                            <span class="text-xs text-gray-500">{{ substr($month['name'], 0, 3) }}</span>
                        </div>
                    @endforeach
                </div>
                @else
                <div class="h-32 flex flex-col items-center justify-center text-gray-400 mb-4">
                    <i data-lucide="bar-chart-2" class="w-12 h-12 mb-2 opacity-50"></i>
                    <p class="text-sm">No savings data in the last 6 months</p>
                </div>
                @endif

                <!-- Month List -->
                <div class="space-y-2">
                    @foreach($monthlyData as $month)
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors cursor-pointer" onclick="showMonthDetail('{{ $month['name'] }}', {{ $month['amount'] }})">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 bg-primary-100 rounded-lg flex items-center justify-center">
                                <span class="text-xs text-primary-600 font-semibold">{{ substr($month['name'], 0, 3) }}</span>
                            </div>
                            <span class="text-sm font-medium text-gray-900">{{ $month['name'] }} {{ $month['year'] ?? date('Y') }}</span>
                        </div>
                        <span class="text-sm font-semibold {{ $month['amount'] > 0 ? 'text-gray-900' : 'text-gray-400' }}">₱{{ number_format($month['amount'], 2) }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
            <div class="p-6 border-t border-gray-100 flex justify-end gap-3">
                <button onclick="closeModal('monthlyBreakdownModal')" class="px-5 py-2.5 text-gray-700 font-medium rounded-lg hover:bg-gray-100 transition-colors">Close</button>
            </div>
        </div>
    </div>

    <!-- Last Contribution Detail Modal -->
    <div id="lastContributionModal" class="modal-overlay hidden">
        <div class="modal max-w-lg">
            <div class="p-6 border-b border-gray-100">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-warning-100 flex items-center justify-center">
                            <i data-lucide="calendar" class="w-5 h-5 text-warning-600"></i>
                        </div>
                        <div>
                            <h2 class="text-xl font-bold text-gray-900">Last Contribution</h2>
                            <p class="text-xs text-gray-500">Most recent transaction details</p>
                        </div>
                    </div>
                    <button onclick="closeModal('lastContributionModal')" class="p-2 hover:bg-gray-100 rounded-lg transition-colors">
                        <i data-lucide="x" class="w-5 h-5 text-gray-500"></i>
                    </button>
                </div>
            </div>
            <div class="p-6 max-h-[60vh] overflow-y-auto">
                @if($lastContribution)
                <div class="bg-gradient-to-r from-warning-50 to-orange-50 rounded-xl p-6 border border-warning-100 mb-6">
                    <div class="text-center mb-4">
                        <p class="text-3xl font-bold text-gray-900">₱{{ number_format($lastContribution->amount, 2) }}</p>
                        <span class="inline-block mt-2 px-3 py-1 bg-success-100 text-success-700 text-sm font-semibold rounded-full">
                            {{ ucfirst($lastContribution->type) }}
                        </span>
                    </div>
                </div>

                <div class="space-y-4">
                    <div class="flex justify-between items-center py-3 border-b border-gray-100">
                        <span class="text-sm text-gray-500">Member</span>
                        <span class="text-sm font-medium text-gray-900">
                            {{ $lastContribution->savingsAccount && $lastContribution->savingsAccount->user ? $lastContribution->savingsAccount->user->first_name . ' ' . $lastContribution->savingsAccount->user->last_name : 'Unknown' }}
                        </span>
                    </div>
                    <div class="flex justify-between items-center py-3 border-b border-gray-100">
                        <span class="text-sm text-gray-500">Reference No.</span>
                        <span class="text-sm font-medium text-gray-900 font-mono">{{ $lastContribution->reference_no ?? 'N/A' }}</span>
                    </div>
                    <div class="flex justify-between items-center py-3 border-b border-gray-100">
                        <span class="text-sm text-gray-500">Date & Time</span>
                        <span class="text-sm font-medium text-gray-900">{{ $lastContribution->created_at->format('M d, Y g:i A') }}</span>
                    </div>
                    <div class="flex justify-between items-center py-3 border-b border-gray-100">
                        <span class="text-sm text-gray-500">Payment Method</span>
                        <span class="text-sm font-medium text-gray-900">{{ $lastContribution->payment_method ?? 'N/A' }}</span>
                    </div>
                    <div class="flex justify-between items-center py-3 border-b border-gray-100">
                        <span class="text-sm text-gray-500">Status</span>
                        <span class="badge badge-success">Completed</span>
                    </div>
                </div>
                @else
                <div class="text-center py-12">
                    <i data-lucide="inbox" class="w-16 h-16 mx-auto mb-4 text-gray-300"></i>
                    <p class="text-gray-500">No contributions found</p>
                </div>
                @endif
            </div>
            <div class="p-6 border-t border-gray-100 flex justify-end gap-3">
                <button onclick="closeModal('lastContributionModal')" class="px-5 py-2.5 text-gray-700 font-medium rounded-lg hover:bg-gray-100 transition-colors">Close</button>
                @if($lastContribution)
                <a href="{{ route('savings') }}" class="px-5 py-2.5 bg-primary-600 text-white font-medium rounded-lg hover:bg-primary-700 transition-colors flex items-center gap-2">
                    <i data-lucide="list" class="w-4 h-4"></i>
                    View All Transactions
                </a>
                @endif
            </div>
        </div>
    </div>

    <script>
        function openMonthlyBreakdownModal() {
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }
            openModal('monthlyBreakdownModal');
        }

        function openLastContributionModal() {
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }
            openModal('lastContributionModal');
        }

        function showMonthDetail(monthName, amount) {
            showToast(monthName + ' ' + new Date().getFullYear(), 'Total Savings: ₱' + amount.toLocaleString('en-PH', {minimumFractionDigits: 2}), 'info');
        }
    </script>
@endsection