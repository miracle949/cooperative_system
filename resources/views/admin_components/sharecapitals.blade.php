@extends('layouts.admin')

@section('title', 'Share Capitals - CoopAdmin')

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
                    <span class="text-gray-900 font-medium">Share Capitals</span>
                </li>
            </ol>
        </nav>
    </div>

    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Share Capitals</h1>
            <p class="text-sm text-gray-500">Manage member share capital contributions</p>
        </div>
        <div class="flex items-center gap-3">
            <button onclick="openModal('addContributionModal')" class="btn btn-primary">
                <i data-lucide="plus" class="w-4 h-4"></i>
Manage Share-capital
            </button>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <a href="{{ route('sharecapitals') }}" class="stat-card cursor-pointer hover:shadow-lg hover:border-primary-200 transition-all group">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 mb-1">Total Contributions</p>
                    <p class="text-2xl font-bold text-gray-900">₱{{ number_format($totalContributions, 2) }}</p>
                    <p class="text-xs text-success-500 mt-1 flex items-center">
                        <i data-lucide="trending-up" class="w-3 h-3 mr-1"></i>
                        Total subscriptions
                    </p>
                </div>
                <div class="w-12 h-12 bg-primary-100 rounded-xl flex items-center justify-center group-hover:bg-primary-200 transition-colors">
                    <i data-lucide="coins" class="w-6 h-6 text-primary-600"></i>
                </div>
            </div>
        </a>

        <a href="{{ route('sharecapitals') }}" class="stat-card cursor-pointer hover:shadow-lg hover:border-success-200 transition-all group">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 mb-1">Current Value</p>
                    <p class="text-2xl font-bold text-gray-900">₱{{ number_format($currentValue, 2) }}</p>
                    <p class="text-xs text-gray-500 mt-1">Per share: ₱{{ $perShareValue }}</p>
                </div>
                <div class="w-12 h-12 bg-success-100 rounded-xl flex items-center justify-center group-hover:bg-success-200 transition-colors">
                    <i data-lucide="trending-up" class="w-6 h-6 text-success-500"></i>
                </div>
            </div>
        </a>

        <div class="stat-card cursor-pointer hover:shadow-lg hover:border-warning-200 transition-all group" onclick="openLastContributionModal()">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 mb-1">Last Contribution</p>
                    <p class="text-2xl font-bold text-gray-900">₱{{ number_format($lastContribution->total_amount ?? 0, 2) }}</p>
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
    <form method="GET" action="{{ route('sharecapitals') }}">
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
                        <option value="Deposit" {{ request('type') === 'Deposit' ? 'selected' : '' }}>Deposit</option>
                        <option value="Withdrawal" {{ request('type') === 'Withdrawal' ? 'selected' : '' }}>Withdrawal</option>
                    </select>
                    <select name="status" class="select w-32" onchange="this.form.submit()">
                        <option value="all">All Status</option>
                        <option value="Completed" {{ request('status') === 'Completed' ? 'selected' : '' }}>Completed</option>
                        <option value="Pending" {{ request('status') === 'Pending' ? 'selected' : '' }}>Pending</option>
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
                        <th>Time</th>
                        <th>Member Name</th>
                        <th>Shares</th>
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
                        <td class="text-sm text-gray-900">{{ $tx->created_at->addHours(8)->format('M d, Y') }}</td>
                        <td class="text-sm text-gray-600">{{ $tx->created_at->addHours(8)->format('g:i A') }}</td>
                        <td>
                            <div class="flex items-center gap-2">
                                <div class="w-8 h-8 rounded-full bg-primary-100 flex items-center justify-center">
                                    <span class="text-xs text-primary-600 font-medium">
                                        {{ strtoupper(substr($tx->shareCapitalAccount->user->first_name ?? 'U', 0, 1) . substr($tx->shareCapitalAccount->user->last_name ?? '', 0, 1)) }}
                                    </span>
                                </div>
                                <span class="text-sm text-gray-900">
                                    {{ $tx->shareCapitalAccount->user->first_name ?? 'Unknown' }} {{ $tx->shareCapitalAccount->user->last_name ?? '' }}
                                </span>
                            </div>
                        </td>
                        <td class="text-sm font-medium text-gray-900">
                            @if($tx->type === 'Deposit')
                                +{{ $tx->shares }}
                            @else
                                -{{ $tx->shares }}
                            @endif
                        </td>
                        <td class="text-sm font-semibold text-gray-900">₱{{ number_format($tx->total_amount, 2) }}</td>
                        <td>
                            @if($tx->type === 'Deposit')
                                <span class="badge badge-success">Deposit</span>
                            @else
                                <span class="badge badge-danger">Withdrawal</span>
                            @endif
                        </td>
                        <td class="text-sm text-gray-600">{{ $tx->payment_method ?? 'N/A' }}</td>
                        <td>
                            @if($tx->status === 'Completed')
                                <span class="badge badge-success">Completed</span>
                            @elseif($tx->status === 'Pending')
                                <span class="badge badge-warning">Pending</span>
                            @elseif($tx->status === 'Approved')
                                <span class="badge badge-success">Approved</span>
                            @elseif($tx->status === 'Rejected')
                                <span class="badge badge-danger">Rejected</span>
                            @else
                                <span class="badge badge-danger">Failed</span>
                            @endif
                        </td>
                        <td>
                            <div class="flex items-center gap-1">
                                <button class="p-1.5 hover:bg-gray-100 rounded" title="View Details" onclick="viewShareCapitalDetail('{{ $tx->id }}', '{{ $tx->type }}', '{{ $tx->status }}', '{{ $tx->shareCapitalAccount->user->first_name ?? '' }} {{ $tx->shareCapitalAccount->user->last_name ?? '' }}', '{{ $tx->shares }}', '{{ $tx->total_amount }}', '{{ $tx->payment_method ?? 'N/A' }}', '{{ $tx->reference_no ?? 'N/A' }}', '{{ $tx->transaction_date }}')">
                                    <i data-lucide="file-text" class="w-4 h-4 text-gray-500"></i>
                                </button>
                                <form method="POST" action="{{ route('sharecapital.archive', $tx->id) }}">
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
                        <td colspan="8" class="text-center py-8">
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
        @if($transactions->hasPages())
        <div class="flex items-center justify-between mt-8 bg-white rounded-xl shadow-sm border border-gray-100 p-4">
            <p class="text-sm text-gray-500">
                Showing {{ $transactions->firstItem() ?? 1 }} to {{ $transactions->lastItem() ?? $transactions->count() }} of {{ $transactions->total() }} transactions
            </p>
            <div class="flex items-center gap-1">
                @if($transactions->onFirstPage())
                    <button class="p-2 rounded-lg border border-gray-200 text-gray-400 cursor-not-allowed" disabled>
                        <i data-lucide="chevron-left" class="w-4 h-4"></i>
                    </button>
                @else
                    <a href="{{ $transactions->previousPageUrl() }}" class="p-2 rounded-lg border border-gray-200 text-gray-600 hover:bg-gray-50 transition-colors">
                        <i data-lucide="chevron-left" class="w-4 h-4"></i>
                    </a>
                @endif

                @foreach($transactions->getUrlRange(max(1, $transactions->currentPage() - 2), min($transactions->lastPage(), $transactions->currentPage() + 2)) as $page => $url)
                    @if($page == $transactions->currentPage())
                        <span class="px-4 py-2 rounded-lg bg-primary-600 text-white font-medium">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}" class="px-4 py-2 rounded-lg border border-gray-200 text-gray-600 hover:bg-gray-50 transition-colors">{{ $page }}</a>
                    @endif
                @endforeach

                @if($transactions->hasMorePages())
                    <a href="{{ $transactions->nextPageUrl() }}" class="p-2 rounded-lg border border-gray-200 text-gray-600 hover:bg-gray-50 transition-colors">
                        <i data-lucide="chevron-right" class="w-4 h-4"></i>
                    </a>
                @else
                    <button class="p-2 rounded-lg border border-gray-200 text-gray-400 cursor-not-allowed" disabled>
                        <i data-lucide="chevron-right" class="w-4 h-4"></i>
                    </button>
                @endif
            </div>
        </div>
        @endif
    </div>

    <!-- Add Contribution Modal -->
    <div id="addContributionModal" class="modal-overlay hidden">
        <div class="modal max-w-lg" style="border-radius: 16px;">
            <!-- Header -->
            <div style="background: linear-gradient(135deg, #1a4a3a 0%, #2d6a4f 100%); padding: 1.25rem 1.5rem;">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div style="width: 40px; height: 40px; background: rgba(255,255,255,0.15); border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                            <i data-lucide="coins" class="w-5 h-5" style="color: #fff;"></i>
                        </div>
                        <div>
                            <h2 class="text-lg font-semibold" style="color: #fff; margin: 0;">Manage Share-capital</h2>
                            <p style="margin: 4px 0 0 0; color: rgba(255,255,255,0.7); font-size: 12px;">Purchase or withdraw shares</p>
                        </div>
                    </div>
                    <button onclick="closeModal('addContributionModal')" style="background: rgba(255,255,255,0.1); border: none; width: 32px; height: 32px; border-radius: 8px; cursor: pointer; display: flex; align-items: center; justify-content: center;">
                        <i data-lucide="x" class="w-5 h-5" style="color: #fff;"></i>
                    </button>
                </div>
            </div>

            <div style="padding: 1.25rem;">
                <form id="adminShareCapitalForm">
                    @csrf
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Member</label>
                        <select name="member_id" id="shareMemberSelect" class="select" style="width: 100%;" onchange="updateMemberShares()" required>
                            <option value="">Select member</option>
                            @foreach($allMembers as $member)
                            <option value="{{ $member->id }}">{{ $member->first_name }} {{ $member->last_name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Balance Pill -->
                    <div style="background: #f8f9f8; border-radius: 10px; padding: 0.75rem 1rem; display: flex; justify-content: space-between; align-items: center; border: 1px dashed #1a4a3a;">
                        <span style="font-size: 13px; color: #666;">Current Shares</span>
                        <span id="currentSharesDisplay" style="font-size: 14px; font-weight: 700; color: #1a4a3a;">0 shares · ₱0.00</span>
                    </div>

                    <!-- Shares counter -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Number of shares</label>
                        <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 8px;">
                            <button type="button" onclick="this.nextElementSibling.value = Math.max(1, parseInt(this.nextElementSibling.value) - 1); updateShareCost()" style="width: 32px; height: 32px; border-radius: 50%; border: 1px solid #ddd; background: #fff; font-size: 16px; cursor: pointer; display: flex; align-items: center; justify-content: center; color: #333;">−</button>
                            <input type="number" name="shares" id="adminSharesInput" value="1" min="1" readonly style="width: 50px; text-align: center; font-size: 14px; font-weight: 600; color: #1a4a3a; border: 1px solid #ddd; border-radius: 8px; padding: 4px;">
                            <button type="button" onclick="this.previousElementSibling.value = parseInt(this.previousElementSibling.value) + 1; updateShareCost()" style="width: 32px; height: 32px; border-radius: 50%; border: 1px solid #ddd; background: #fff; font-size: 16px; cursor: pointer; display: flex; align-items: center; justify-content: center; color: #333;">+</button>
                        </div>
                        <!-- Quick select -->
                        <div style="display: flex; gap: 6px; flex-wrap: wrap; margin-bottom: 6px;">
                            <button type="button" onclick="document.getElementById('adminSharesInput').value = 1; updateShareCost()" style="padding: 4px 10px; border-radius: 16px; font-size: 11px; font-weight: 600; cursor: pointer; background: #1a4a3a; color: #fff; border: 1px solid #1a4a3a;">1</button>
                            <button type="button" onclick="document.getElementById('adminSharesInput').value = 5; updateShareCost()" style="padding: 4px 10px; border-radius: 16px; font-size: 11px; font-weight: 600; cursor: pointer; background: #fff; color: #555; border: 1px solid #ddd;">5</button>
                            <button type="button" onclick="document.getElementById('adminSharesInput').value = 10; updateShareCost()" style="padding: 4px 10px; border-radius: 16px; font-size: 11px; font-weight: 600; cursor: pointer; background: #fff; color: #555; border: 1px solid #ddd;">10</button>
                            <button type="button" onclick="document.getElementById('adminSharesInput').value = 25; updateShareCost()" style="padding: 4px 10px; border-radius: 16px; font-size: 11px; font-weight: 600; cursor: pointer; background: #fff; color: #555; border: 1px solid #ddd;">25</button>
                        </div>
                        <p style="font-size: 12px; color: #888; margin: 0;">Cost: <strong id="adminShareCost" style="color: #1a4a3a;">₱{{ number_format($perShareValue, 0) }}</strong> · ₱{{ number_format($perShareValue, 0) }}/share</p>
                    </div>

                    <!-- Amount (calculated) -->
                    <input type="hidden" name="amount" id="adminTotalAmount">

                    <!-- Type -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Type</label>
                        <select name="type" id="shareTypeSelect" class="select" style="width: 100%;" required>
                            <option value="">Select type...</option>
                            <option value="Deposit">Deposit</option>
                            <option value="Withdrawal">Withdrawal</option>
                        </select>
                    </div>

                    <!-- Payment Method -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Payment Method</label>
                        <select name="payment_method" class="select" style="width: 100%;" required>
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
                    <button onclick="submitAdminShareCapital()" 
                        style="width: 100%; padding: 0.7rem; background: #1a4a3a; color: #fff; border: none; border-radius: 10px; font-size: 14px; font-weight: 600; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 8px;">
                        <i data-lucide="check-circle" class="w-4 h-4"></i> Confirm Transaction
                    </button>
                    <button onclick="closeModal('addContributionModal')" 
                        style="width: 100%; padding: 0.65rem; background: #fff; color: #666; border: 1px solid #ddd; border-radius: 10px; font-size: 14px; cursor: pointer;">
                        Cancel
                    </button>
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
                            <p class="text-xs text-gray-500">Most recent share capital transaction</p>
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
                        <p class="text-3xl font-bold text-gray-900">₱{{ number_format($lastContribution->total_amount, 2) }}</p>
                        <p class="text-sm text-gray-500 mt-1">{{ $lastContribution->shares ?? 0 }} shares</p>
                        <span class="inline-block mt-2 px-3 py-1 bg-primary-100 text-primary-700 text-sm font-semibold rounded-full">
                            {{ ucfirst($lastContribution->type) }}
                        </span>
                    </div>
                </div>

                <div class="space-y-4">
                    <div class="flex justify-between items-center py-3 border-b border-gray-100">
                        <span class="text-sm text-gray-500">Member</span>
                        <span class="text-sm font-medium text-gray-900">
                            {{ $lastContribution->shareCapitalAccount && $lastContribution->shareCapitalAccount->user ? $lastContribution->shareCapitalAccount->user->first_name . ' ' . $lastContribution->shareCapitalAccount->user->last_name : 'Unknown' }}
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
                        <span class="badge badge-success">{{ ucfirst($lastContribution->status ?? 'Completed') }}</span>
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
                <a href="{{ route('sharecapitals') }}" class="px-5 py-2.5 bg-primary-600 text-white font-medium rounded-lg hover:bg-primary-700 transition-colors flex items-center gap-2">
                    <i data-lucide="list" class="w-4 h-4"></i>
                    View All Transactions
                </a>
                @endif
            </div>
        </div>
    </div>

    <script>
        function openLastContributionModal() {
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }
            openModal('lastContributionModal');
        }

        // Share counter functionality
        document.getElementById('addContributionModal')?.addEventListener('transitionend', function() {
            if (!this.classList.contains('hidden')) {
                if (typeof lucide !== 'undefined') {
                    lucide.createIcons();
                }
            }
        });

        // Update cost when shares change
        function updateShareCost() {
            const shares = parseInt(document.getElementById('adminSharesInput')?.value) || 1;
            const perShare = {{ $perShareValue }};
            document.getElementById('adminShareCost').textContent = '₱' + (shares * perShare).toLocaleString();
            document.getElementById('adminTotalAmount').value = shares * perShare;
        }

        // Update member shares display
        window.updateMemberShares = function() {
            const memberId = document.getElementById('shareMemberSelect').value;
            const display = document.getElementById('currentSharesDisplay');
            
            if (!memberId) {
                display.textContent = '0 shares · ₱0.00';
                return;
            }

            fetch('/sharecapital/member/' + memberId + '/balance')
                .then(response => response.json())
                .then(data => {
                    const shares = data.total_shares || 0;
                    const amount = data.total_amount || 0;
                    display.textContent = shares + ' shares · ₱' + amount.toLocaleString('en-PH', {minimumFractionDigits: 2, maximumFractionDigits: 2});
                })
                .catch(error => {
                    console.error('Error:', error);
                    display.textContent = '0 shares · ₱0.00';
                });
        };

        // Submit admin share capital form
        window.submitAdminShareCapital = function() {
            const form = document.getElementById('adminShareCapitalForm');
            const formData = new FormData(form);
            
            if (!formData.get('member_id')) {
                showToast('Error', 'Please select a member');
                return;
            }
            if (!formData.get('shares') || parseInt(formData.get('shares')) <= 0) {
                showToast('Error', 'Please enter valid shares');
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

            fetch('/sharecapital/admin/store', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    closeModal('addContributionModal');
                    showToast('Success', data.message);
                    form.reset();
                    document.getElementById('currentSharesDisplay').textContent = '0 shares · ₱0.00';
                    document.getElementById('adminSharesInput').value = 1;
                    updateShareCost();
                    setTimeout(() => window.location.reload(), 1500);
                } else {
                    showToast('Error', data.message || 'Transaction failed');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('Error', 'An error occurred. Please try again.');
            });
        };

        // Transaction Detail Modal Functions
        let currentTransactionId = null;

        window.viewShareCapitalDetail = function(id, type, status, memberName, shares, amount, paymentMethod, referenceNo, transactionDate) {
            const existingModal = document.getElementById('scTransactionModal');
            if (existingModal) {
                existingModal.remove();
            }
            
            const modal = document.createElement('div');
            modal.id = 'scTransactionModal';
            modal.style.cssText = 'display:flex; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.7); z-index:999999; align-items:center; justify-content:center;';
            
            currentTransactionId = id;
            const isWithdrawal = type === 'Withdrawal';
            const isPending = status === 'Pending';
            
            let badge = '';
            if (status === 'Completed' || status === 'Approved') badge = '<span style="background:#dcfce7;color:#166534;padding:4px 8px;border-radius:4px;font-size:12px;">Completed</span>';
            else if (status === 'Pending') badge = '<span style="background:#fef3c7;color:#92400e;padding:4px 8px;border-radius:4px;font-size:12px;">Pending</span>';
            else if (status === 'Rejected') badge = '<span style="background:#fee2e2;color:#991b1b;padding:4px 8px;border-radius:4px;font-size:12px;">Rejected</span>';
            else badge = '<span style="background:#f3f4f6;color:#374151;padding:4px 8px;border-radius:4px;font-size:12px;">' + status + '</span>';
            
            let actionsHtml = '';
            if (isWithdrawal && isPending) {
                actionsHtml = '<div style="display:flex;gap:12px;margin-top:24px;"><button onclick="processWithdrawalSC(\'accept\')" style="flex:1;padding:10px;background:#16a34a;color:white;border:none;border-radius:8px;cursor:pointer;font-weight:500;">Accept</button><button onclick="processWithdrawalSC(\'reject\')" style="flex:1;padding:10px;background:#dc2626;color:white;border:none;border-radius:8px;cursor:pointer;font-weight:500;">Reject</button></div>';
            } else {
                actionsHtml = '<div style="margin-top:24px;"><button onclick="document.getElementById(\'scTransactionModal\').remove();document.body.style.overflow=\'auto\';" style="width:100%;padding:10px;background:#e5e7eb;color:#374151;border:none;border-radius:8px;cursor:pointer;font-weight:500;">Close</button></div>';
            }
            
            modal.innerHTML = `
                <div style="background:white; border-radius:12px; max-width:450px; width:90%; max-height:90vh; overflow:auto; box-shadow:0 25px 50px rgba(0,0,0,0.25);">
                    <div style="padding:16px; border-bottom:1px solid #eee; display:flex; justify-content:space-between; align-items:center; background:linear-gradient(135deg,#1a4a3a,#2d6a4f); border-radius:12px 12px 0 0;">
                        <h3 style="margin:0; font-size:18px; font-weight:600; color:white;">Transaction Details</h3>
                        <button onclick="document.getElementById('scTransactionModal').remove();document.body.style.overflow='auto';" style="background:rgba(255,255,255,0.1); border:none; width:32px; height:32px; border-radius:8px; cursor:pointer; color:white; font-size:18px;">&times;</button>
                    </div>
                    <div style="padding:20px;">
                        <div style="display:grid;gap:12px;">
                            <div style="display:flex;justify-content:space-between;">
                                <span style="color:#6b7280;font-size:14px;">Member</span>
                                <span style="color:#111827;font-size:14px;font-weight:500;">${memberName}</span>
                            </div>
                            <div style="display:flex;justify-content:space-between;">
                                <span style="color:#6b7280;font-size:14px;">Type</span>
                                <span style="color:${isWithdrawal ? '#dc2626' : '#16a34a'};font-size:14px;font-weight:500;">${type}</span>
                            </div>
                            <div style="display:flex;justify-content:space-between;">
                                <span style="color:#6b7280;font-size:14px;">Shares</span>
                                <span style="color:#111827;font-size:14px;font-weight:500;">${shares} shares</span>
                            </div>
                            <div style="display:flex;justify-content:space-between;">
                                <span style="color:#6b7280;font-size:14px;">Amount</span>
                                <span style="color:#111827;font-size:14px;font-weight:700;">₱${parseFloat(amount).toLocaleString('en-PH', {minimumFractionDigits:2})}</span>
                            </div>
                            <div style="display:flex;justify-content:space-between;">
                                <span style="color:#6b7280;font-size:14px;">Payment</span>
                                <span style="color:#111827;font-size:14px;">${paymentMethod}</span>
                            </div>
                            <div style="display:flex;justify-content:space-between;">
                                <span style="color:#6b7280;font-size:14px;">Reference</span>
                                <span style="color:#111827;font-size:14px;font-family:monospace;">${referenceNo}</span>
                            </div>
                            <div style="display:flex;justify-content:space-between;">
                                <span style="color:#6b7280;font-size:14px;">Date</span>
                                <span style="color:#111827;font-size:14px;">${transactionDate}</span>
                            </div>
                            <div style="display:flex;justify-content:space-between;padding-top:8px;border-top:1px solid #e5e7eb;">
                                <span style="color:#6b7280;font-size:14px;">Status</span>
                                ${badge}
                            </div>
                        </div>
                        ${actionsHtml}
                    </div>
                </div>
            `;
            
            document.body.appendChild(modal);
            document.body.style.overflow = 'hidden';
        };

        window.processWithdrawalSC = function(action) {
            if (!currentTransactionId) return;

            fetch('/sharecapital/withdrawal/' + currentTransactionId + '/status', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                },
                body: JSON.stringify({ action: action })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showToast('Success', data.message);
                    document.getElementById('scTransactionModal').remove();
                    document.body.style.overflow = 'auto';
                    setTimeout(() => window.location.reload(), 1000);
                } else {
                    showToast('Error', data.message || 'Failed to process withdrawal');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('Error', 'An error occurred. Please try again.');
            });
        };
    </script>
@endsection