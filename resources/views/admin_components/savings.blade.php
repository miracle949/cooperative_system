@extends('layouts.admin')

@section('title', 'Savings - CoopAdmin')

@php
    // Calculate monthly data directly in Blade to bypass controller issue
    $monthlyData = [];
    $maxAmount = 0;
    for ($i = 5; $i >= 0; $i--) {
        $monthDate = now()->subMonths($i);
        $amount = (float) \App\Models\savings_transaction_tbl::where('type', 'deposit')
            ->whereYear('created_at', $monthDate->format('Y'))
            ->whereMonth('created_at', $monthDate->format('m'))
            ->sum('amount');
        $monthlyData[] = [
            'name' => $monthDate->format('F'),
            'year' => $monthDate->format('Y'),
            'amount' => $amount
        ];
        if ($amount > $maxAmount) {
            $maxAmount = $amount;
        }
    }
    foreach ($monthlyData as &$data) {
        $data['bar_height'] = $maxAmount > 0 ? ($data['amount'] / $maxAmount) * 150 : 0;
    }
    
    // Calculate highest month
    $highestMonth = ['name' => 'N/A', 'amount' => 0];
    foreach ($monthlyData as $m) {
        if ($m['amount'] > $highestMonth['amount']) {
            $highestMonth = $m;
        }
    }
@endphp

@section('content')
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
                Manage Savings
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
                                <button class="p-1.5 hover:bg-gray-100 rounded" title="View Receipt" onclick="viewReceipt('{{ $tx->reference_no }}')">
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

    <!-- Manage Savings Modal -->
    <div id="addContributionModal" class="modal-overlay hidden">
        <div class="modal max-w-lg" style="border-radius: 16px;">
            <!-- Header -->
            <div style="background: linear-gradient(135deg, #1a4a3a 0%, #2d6a4f 100%); padding: 1.25rem 1.5rem;">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div style="width: 40px; height: 40px; background: rgba(255,255,255,0.15); border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                            <i data-lucide="piggy-bank" class="w-5 h-5" style="color: #fff;"></i>
                        </div>
                        <div>
                            <h2 class="text-lg font-semibold" style="color: #fff; margin: 0;">Manage Savings</h2>
                            <p style="margin: 4px 0 0 0; color: rgba(255,255,255,0.7); font-size: 12px;">Add or withdraw funds</p>
                        </div>
                    </div>
                    <button onclick="closeAddContributionModal()" style="background: rgba(255,255,255,0.1); border: none; width: 32px; height: 32px; border-radius: 8px; cursor: pointer; display: flex; align-items: center; justify-content: center;">
                        <i data-lucide="x" class="w-5 h-5" style="color: #fff;"></i>
                    </button>
                </div>
            </div>

            <div style="padding: 1.25rem;">
                <form id="adminSavingsForm" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Member</label>
                        <select name="member_id" id="memberSelect" class="select" style="width: 100%;" onchange="updateMemberBalance()">
                            <option value="">Select member</option>
                            @foreach($allMembers as $member)
                            <option value="{{ $member->id }}">{{ $member->first_name }} {{ $member->last_name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Balance Pill -->
                    <div style="background: #f8f9f8; border-radius: 10px; padding: 0.75rem 1rem; display: flex; justify-content: space-between; align-items: center; border: 1px dashed #1a4a3a;">
                        <span style="font-size: 13px; color: #666;">Current Balance</span>
                        <span id="currentBalance" style="font-size: 14px; font-weight: 700; color: #1a4a3a;">₱0.00</span>
                    </div>

                    <!-- Amount -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Amount</label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500">₱</span>
                            <input type="number" name="amount" id="savingsAmount" class="input pl-8" placeholder="0.00" style="width: 100%;">
                        </div>
                        <!-- Quick Amounts -->
                        <div style="display: flex; gap: 6px; flex-wrap: wrap; margin-top: 8px;">
                            <button type="button" onclick="document.getElementById('savingsAmount').value = 500" style="padding: 4px 12px; border-radius: 16px; font-size: 11px; font-weight: 600; cursor: pointer; background: #fff; color: #555; border: 1px solid #ddd;">₱500</button>
                            <button type="button" onclick="document.getElementById('savingsAmount').value = 1000" style="padding: 4px 12px; border-radius: 16px; font-size: 11px; font-weight: 600; cursor: pointer; background: #fff; color: #555; border: 1px solid #ddd;">₱1,000</button>
                            <button type="button" onclick="document.getElementById('savingsAmount').value = 2000" style="padding: 4px 12px; border-radius: 16px; font-size: 11px; font-weight: 600; cursor: pointer; background: #fff; color: #555; border: 1px solid #ddd;">₱2,000</button>
                            <button type="button" onclick="document.getElementById('savingsAmount').value = 5000" style="padding: 4px 12px; border-radius: 16px; font-size: 11px; font-weight: 600; cursor: pointer; background: #fff; color: #555; border: 1px solid #ddd;">₱5,000</button>
                        </div>
                    </div>

                    <!-- Type -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Type</label>
                        <select name="type" id="savingsType" class="select" style="width: 100%;">
                            <option value="">Select type...</option>
                            <option value="deposit">Deposit</option>
                            <option value="withdrawal">Withdrawal</option>
                        </select>
                    </div>

                    <!-- Payment Method -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Payment Method</label>
                        <select name="payment_method" class="select" style="width: 100%;">
                            <option value="">Select payment method...</option>
                            <option value="cash">Cash</option>
                            <option value="bank_transfer">Bank Transfer</option>
                            <option value="gcash">GCash</option>
                            <option value="check">Check</option>
                        </select>
                    </div>

                    <!-- Notes -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Note <span style="color: #999;">(optional)</span></label>
                        <textarea name="note" class="input" rows="2" placeholder="Add any notes..." style="width: 100%;"></textarea>
                    </div>
                </form>

                <div style="margin-top: 1.25rem; display: flex; flex-direction: column; gap: 8px;">
                    <button onclick="submitAdminSavings()" 
                        style="width: 100%; padding: 0.7rem; background: #1a4a3a; color: #fff; border: none; border-radius: 10px; font-size: 14px; font-weight: 600; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 8px;">
                        <i data-lucide="check-circle" class="w-4 h-4"></i> Confirm Transaction
                    </button>
                    <button onclick="closeAddContributionModal()" 
                        style="width: 100%; padding: 0.65rem; background: #fff; color: #666; border: 1px solid #ddd; border-radius: 10px; font-size: 14px; cursor: pointer;">
                        Cancel
                    </button>
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
                    <button onclick="closeMonthlyBreakdownModal()" class="p-2 hover:bg-gray-100 rounded-lg transition-colors">
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
                            $barHeight = $month['bar_height'] ?? 0;
                            $hasAmount = $month['amount'] > 0;
                        @endphp
                        <div class="flex-1 flex flex-col items-center gap-2">
                            <div class="w-full {{ $hasAmount ? 'bg-primary-100 hover:bg-primary-200' : 'bg-gray-100 border-2 border-dashed border-gray-300' }} rounded-t-lg transition-colors cursor-pointer relative group"
                                style="height: {{ max($barHeight, 1) }}px" onclick="showMonthDetail('{{ $month['name'] }}', {{ $month['amount'] }})">
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
                <button onclick="closeMonthlyBreakdownModal()" class="px-5 py-2.5 text-gray-700 font-medium rounded-lg hover:bg-gray-100 transition-colors">Close</button>
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
                    <button onclick="closeLastContributionModal()" class="p-2 hover:bg-gray-100 rounded-lg transition-colors">
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
                <button onclick="closeLastContributionModal()" class="px-5 py-2.5 text-gray-700 font-medium rounded-lg hover:bg-gray-100 transition-colors">Close</button>
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
            const existing = document.getElementById('monthlyBreakdownDynamic');
            if (existing) existing.remove();
            
            // Clean monthlyData to remove debug fields
            const rawData = @json($monthlyData);
            console.log('rawData:', rawData);
            const monthlyData = rawData.map(m => ({
                name: m.name || '',
                year: m.year || new Date().getFullYear().toString(),
                amount: parseFloat(m.amount) || 0,
                bar_height: parseFloat(m.bar_height) || 0
            }));
            const monthlyAvg = {{ $monthlyAvg ?? 0 }};
            const highestMonthRaw = @json($highestMonth);
            const highestMonth = {
                name: highestMonthRaw.name || '',
                amount: parseFloat(highestMonthRaw.amount) || 0
            };
            
            console.log('monthlyData:', monthlyData);
            console.log('monthlyAvg:', monthlyAvg);
            console.log('highestMonth:', highestMonth);
            
            let chartHtml = '';
            const maxAmount = Math.max(...monthlyData.map(m => m.amount), 1);
            monthlyData.forEach(month => {
                const height = (month.amount / maxAmount) * 150;
                const hasAmount = month.amount > 0;
                chartHtml += `
                    <div style="flex:1;display:flex;flex-direction:column;align-items:center;gap:0.5rem;">
                        <div style="width:100%;${hasAmount ? 'background:#d1fae5;cursor:pointer;' : 'background:#f3f4f6;border:2px dashed #d1d5db;'}border-radius:0.5rem;height:${Math.max(height, 5)}px;" title="₱${month.amount.toLocaleString()}"></div>
                        <span style="font-size:0.75rem;color:#6b7280;">${month.name.substring(0,3)}</span>
                    </div>
                `;
            });

            const modal = document.createElement('div');
            modal.id = 'monthlyBreakdownDynamic';
            modal.style.cssText = 'position:fixed;inset:0;background:rgba(0,0,0,0.7);z-index:99999;display:flex;align-items:center;justify-content:center;';
            modal.innerHTML = `
                <div style="background:white;border-radius:12px;max-width:42rem;width:90%;max-height:90vh;overflow:auto;">
                    <div style="padding:1.5rem;border-bottom:1px solid #eee;display:flex;justify-content:space-between;align-items:center;">
                        <div style="display:flex;align-items:center;gap:0.75rem;">
                            <div style="width:2.5rem;height:2.5rem;border-radius:9999px;background:#ecfdf5;display:flex;align-items:center;justify-content:center;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#059669" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 3v18h18"/><path d="m19 9-5 5-4-4-3 3"/></svg>
                            </div>
                            <div>
                                <h2 style="font-size:1.25rem;font-weight:700;color:#111;">Monthly Breakdown</h2>
                                <p style="font-size:0.75rem;color:#6b7280;">Savings by month (Last 6 months)</p>
                            </div>
                        </div>
                        <button onclick="this.closest('#monthlyBreakdownDynamic').remove()" style="background:none;border:none;font-size:1.5rem;cursor:pointer;color:#666;">&times;</button>
                    </div>
                    <div style="padding:1.5rem;max-height:60vh;overflow-y:auto;">
                        <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;margin-bottom:1.5rem;">
                            <div style="background:#ecfdf5;border-radius:0.5rem;padding:1rem;border:1px solid #d1fae5;text-align:center;">
                                <p style="font-size:0.75rem;color:#6b7280;margin-bottom:0.25rem;">Average Monthly</p>
                                <p style="font-size:1.25rem;font-weight:700;color:#111;">₱${monthlyAvg.toLocaleString('en-PH', {minimumFractionDigits:2})}</p>
                            </div>
                            <div style="background:#d1fae5;border-radius:0.5rem;padding:1rem;border:1px solid #a7f3d0;text-align:center;">
                                <p style="font-size:0.75rem;color:#6b7280;margin-bottom:0.25rem;">Highest Month</p>
                                <p style="font-size:1.25rem;font-weight:700;color:#111;">₱${(highestMonth.amount || 0).toLocaleString('en-PH', {minimumFractionDigits:2})}</p>
                                <p style="font-size:0.75rem;color:#6b7280;">${highestMonth.name || 'N/A'}</p>
                            </div>
                        </div>
                        <h4 style="font-weight:600;color:#111;margin-bottom:1rem;">Monthly Savings</h4>
                        <div style="height:12rem;display:flex;align-items:flex-end;justify-content:space-between;gap:0.5rem;padding:0 1rem;margin-bottom:1rem;">
                            ${chartHtml}
                        </div>
                        <div style="display:flex;flex-direction:column;gap:0.5rem;">
                            ${monthlyData.map(month => `
                                <div style="display:flex;justify-content:space-between;align-items:center;padding:0.75rem;background:#f9fafb;border-radius:0.5rem;margin-bottom:0.5rem;">
                                    <div style="display:flex;align-items:center;gap:0.75rem;">
                                        <div style="width:2rem;height:2rem;background:#d1fae5;border-radius:0.5rem;display:flex;align-items:center;justify-content:center;">
                                            <span style="font-size:0.75rem;font-weight:600;color:#059669;">${month.name.substring(0,3)}</span>
                                        </div>
                                        <span style="font-size:0.875rem;font-weight:500;color:#111;">${month.name} ${month.year || new Date().getFullYear()}</span>
                                    </div>
                                    <span style="font-size:0.875rem;font-weight:600;${month.amount > 0 ? 'color:#111;' : 'color:#9ca3af;'}">₱${month.amount.toLocaleString('en-PH', {minimumFractionDigits:2})}</span>
                                </div>
                            `).join('')}
                        </div>
                    </div>
                </div>
            `;
            document.body.appendChild(modal);
        }

        function openLastContributionModal() {
            const existing = document.getElementById('lastContributionDynamic');
            if (existing) existing.remove();
            
            const lastContribution = @json($lastContribution);
            
            const memberName = lastContribution?.savings_account?.user 
                ? lastContribution.savings_account.user.first_name + ' ' + lastContribution.savings_account.user.last_name 
                : 'Unknown';
            
            const modal = document.createElement('div');
            modal.id = 'lastContributionDynamic';
            modal.style.cssText = 'position:fixed;inset:0;background:rgba(0,0,0,0.7);z-index:99999;display:flex;align-items:center;justify-content:center;';
            modal.innerHTML = `
                <div style="background:white;border-radius:12px;max-width:28rem;width:90%;max-height:90vh;overflow:auto;">
                    <div style="padding:1.5rem;border-bottom:1px solid #eee;display:flex;justify-content:space-between;align-items:center;">
                        <div style="display:flex;align-items:center;gap:0.75rem;">
                            <div style="width:2.5rem;height:2.5rem;border-radius:9999px;background:#fef3c7;display:flex;align-items:center;justify-content:center;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#d97706" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="4" rx="2" ry="2"/><line x1="16" x2="16" y1="2" y2="6"/><line x1="8" x2="8" y1="2" y2="6"/><line x1="3" x2="21" y1="10" y2="10"/></svg>
                            </div>
                            <div>
                                <h2 style="font-size:1.25rem;font-weight:700;color:#111;">Last Contribution</h2>
                                <p style="font-size:0.75rem;color:#6b7280;">Most recent transaction details</p>
                            </div>
                        </div>
                        <button onclick="this.closest('#lastContributionDynamic').remove()" style="background:none;border:none;font-size:1.5rem;cursor:pointer;color:#666;">&times;</button>
                    </div>
                    <div style="padding:1.5rem;max-height:60vh;overflow-y:auto;">
                        ${lastContribution ? `
                            <div style="background:linear-gradient(135deg, #fefce8 0%, #fef9c3 100%);border-radius:0.75rem;padding:1.5rem;border:1px solid #fef9c3;margin-bottom:1.5rem;text-align:center;">
                                <p style="font-size:1.875rem;font-weight:700;color:#111;">₱${parseFloat(lastContribution.amount).toLocaleString('en-PH', {minimumFractionDigits:2})}</p>
                                <span style="display:inline-block;margin-top:0.5rem;padding:0.25rem 0.75rem;background:#d1fae5;color:#047857;font-size:0.875rem;font-weight:600;border-radius:9999px;text-transform:capitalize;">
                                    ${lastContribution.type}
                                </span>
                            </div>
                            <div style="display:flex;flex-direction:column;gap:0.75rem;">
                                <div style="display:flex;justify-content:space-between;padding:0.75rem;border-bottom:1px solid #f3f4f6;">
                                    <span style="font-size:0.875rem;color:#6b7280;">Member</span>
                                    <span style="font-size:0.875rem;font-weight:500;color:#111;">${memberName}</span>
                                </div>
                                <div style="display:flex;justify-content:space-between;padding:0.75rem;border-bottom:1px solid #f3f4f6;">
                                    <span style="font-size:0.875rem;color:#6b7280;">Reference No.</span>
                                    <span style="font-size:0.875rem;font-weight:500;color:#111;font-family:monospace;">${lastContribution.reference_no || 'N/A'}</span>
                                </div>
                                <div style="display:flex;justify-content:space-between;padding:0.75rem;border-bottom:1px solid #f3f4f6;">
                                    <span style="font-size:0.875rem;color:#6b7280;">Date & Time</span>
                                    <span style="font-size:0.875rem;font-weight:500;color:#111;">${new Date(lastContribution.created_at).toLocaleString('en-PH')}</span>
                                </div>
                                <div style="display:flex;justify-content:space-between;padding:0.75rem;border-bottom:1px solid #f3f4f6;">
                                    <span style="font-size:0.875rem;color:#6b7280;">Payment Method</span>
                                    <span style="font-size:0.875rem;font-weight:500;color:#111;">${lastContribution.payment_method || 'N/A'}</span>
                                </div>
                                <div style="display:flex;justify-content:space-between;padding:0.75rem;">
                                    <span style="font-size:0.875rem;color:#6b7280;">Status</span>
                                    <span style="font-size:0.875rem;font-weight:500;color:#059670;">Completed</span>
                                </div>
                            </div>
                        ` : `
                            <div style="text-align:center;padding:3rem;color:#9ca3af;">
                                <p>No contributions found</p>
                            </div>
                        `}
                    </div>
                </div>
            `;
            document.body.appendChild(modal);
        }

        function closeMonthlyBreakdownModal() {
            document.getElementById('monthlyBreakdownDynamic')?.remove();
            document.body.style.overflow = '';
        }

        function closeLastContributionModal() {
            document.getElementById('lastContributionDynamic')?.remove();
            document.body.style.overflow = '';
        }

        function closeAddContributionModal() {
            const modal = document.getElementById('addContributionModal');
            modal.classList.add('hidden');
            modal.style.cssText = '';
            document.body.style.overflow = '';
        }

        function showMonthDetail(monthName, amount) {
            const formattedAmount = amount.toLocaleString('en-PH', {minimumFractionDigits: 2, maximumFractionDigits: 2});
            showToast(monthName + ' ' + new Date().getFullYear(), 'Total Savings: ₱' + formattedAmount, 'info');
        }

        // Initialize icons when modal opens
        document.getElementById('addContributionModal')?.addEventListener('transitionend', function() {
            if (!this.classList.contains('hidden') && typeof lucide !== 'undefined') {
                lucide.createIcons();
            }
        });

        // View Receipt Modal Functions
        window.viewReceipt = function(referenceNo) {
            // Create modal dynamically and append to body
            const existingModal = document.getElementById('receiptModal');
            if (existingModal) {
                existingModal.remove();
            }
            
            const modal = document.createElement('div');
            modal.id = 'receiptModal';
            modal.style.cssText = 'display:flex; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.7); z-index:999999; align-items:center; justify-content:center;';
            
            modal.innerHTML = `
                <div style="background:white; border-radius:12px; max-width:650px; width:90%; max-height:90vh; overflow:auto;">
                    <div style="padding:16px; border-bottom:1px solid #eee; display:flex; justify-content:space-between; align-items:center;">
                        <h3 style="margin:0; font-size:18px; font-weight:600;">Transaction Receipt</h3>
                        <button onclick="document.getElementById('receiptModal').remove()" style="background:none; border:none; font-size:24px; cursor:pointer; color:#666;">&times;</button>
                    </div>
                    <div style="padding:16px; min-height:300px;" id="receiptImageContainer">
                        Loading...
                    </div>
                    <div style="padding:16px; border-top:1px solid #eee; display:flex; justify-content:flex-end; gap:10px;">
                        <button onclick="document.getElementById('receiptModal').remove()" style="padding:8px 16px; border:1px solid #ddd; background:white; border-radius:6px; cursor:pointer;">Close</button>
                        <a id="receiptDownloadBtn" href="/savings/receipt/${referenceNo}" download style="padding:8px 16px; background:#1a4a3a; color:white; border-radius:6px; text-decoration:none; display:flex; align-items:center; gap:6px;">Download</a>
                    </div>
                </div>
            `;
            
            document.body.appendChild(modal);
            
            // Fetch receipt image
            fetch("/savings/receipt/" + referenceNo + "?view=inline")
                .then(response => response.json())
                .then(data => {
                    document.getElementById('receiptImageContainer').innerHTML = '<img src="' + data.image + '" alt="Receipt" style="max-width:100%; border-radius:8px;">';
                })
                .catch(error => {
                    console.error('Error fetching receipt:', error);
                    document.getElementById('receiptImageContainer').innerHTML = '<div style="text-align:center; padding:40px; color:red;">Failed to load receipt</div>';
                });
        };

        // Get member balance via AJAX
        window.updateMemberBalance = function() {
            const memberId = document.getElementById('memberSelect').value;
            const balanceEl = document.getElementById('currentBalance');
            
            if (!memberId) {
                balanceEl.textContent = '₱0.00';
                return;
            }
            
            fetch('/savings/admin/balance/' + memberId)
                .then(response => response.json())
                .then(data => {
                    balanceEl.textContent = '₱' + parseFloat(data.balance || 0).toLocaleString('en-PH', {minimumFractionDigits: 2, maximumFractionDigits: 2});
                })
                .catch(error => {
                    console.error('Error fetching balance:', error);
                    balanceEl.textContent = '₱0.00';
                });
        };

        // Submit admin savings form
        window.submitAdminSavings = function() {
            const form = document.getElementById('adminSavingsForm');
            const formData = new FormData(form);
            
            // Validate required fields
            if (!formData.get('member_id')) {
                showToast('Error', 'Please select a member');
                return;
            }
            if (!formData.get('amount') || parseFloat(formData.get('amount')) <= 0) {
                showToast('Error', 'Please enter a valid amount');
                return;
            }
            if (!formData.get('type')) {
                showToast('Error', 'Please select transaction type');
                return;
            }
            if (!formData.get('payment_method')) {
                showToast('Error', 'Please select payment method');
                return;
            }

            fetch('/savings/admin/store', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    closeAddContributionModal();
                    showToast('Success', data.message);
                    // Reset form
                    form.reset();
                    document.getElementById('currentBalance').textContent = '₱0.00';
                    // Reload page to show new transaction
                    setTimeout(() => window.location.reload(), 1000);
                } else {
                    showToast('Error', data.message || 'Transaction failed');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('Error', 'An error occurred. Please try again.');
            });
        };
    </script>
@endsection