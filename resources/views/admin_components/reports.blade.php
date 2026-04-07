@extends('layouts.admin')

@section('title', 'Reports - CoopAdmin')

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
                    <span class="text-gray-900 font-medium">Reports</span>
                </li>
            </ol>
        </nav>
    </div>

    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Reports</h1>
            <p class="text-sm text-gray-500">Generate and export financial reports</p>
        </div>
    </div>

    <!-- Filter Panel -->
    <form method="GET" action="{{ route('reports') }}">
        <div class="card p-6 mb-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">From Date</label>
                    <input type="date" name="from_date" class="input" value="{{ $fromDate }}">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">To Date</label>
                    <input type="date" name="to_date" class="input" value="{{ $toDate }}">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Report Type</label>
                    <select name="report_type" class="select">
                        <option value="all" {{ $reportType === 'all' ? 'selected' : '' }}>All Reports</option>
                        <option value="savings" {{ $reportType === 'savings' ? 'selected' : '' }}>Savings Report</option>
                        <option value="loans" {{ $reportType === 'loans' ? 'selected' : '' }}>Loan Report</option>
                        <option value="share_capital" {{ $reportType === 'share_capital' ? 'selected' : '' }}>Share Capital Report</option>
                    </select>
                </div>
                <div class="lg:col-span-2 flex items-end">
                    <button type="submit" class="btn btn-primary w-full">
                        <i data-lucide="file-text" class="w-4 h-4"></i>
                        Generate Report
                    </button>
                </div>
            </div>
        </div>
    </form>

    <!-- Summary Statistics -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
        <div class="stat-card cursor-pointer hover:shadow-lg hover:border-success-200 transition-all group" onclick="openDepositsModal()">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 mb-1">Total Deposits</p>
                    <p class="text-2xl font-bold text-gray-900">₱{{ number_format($totalDeposits, 2) }}</p>
                    <p class="text-xs text-gray-400 mt-1 flex items-center">
                        <i data-lucide="mouse-pointer-click" class="w-3 h-3 mr-1"></i>
                        Click for details
                    </p>
                </div>
                <div class="w-12 h-12 bg-success-100 rounded-xl flex items-center justify-center group-hover:bg-success-200 transition-colors">
                    <i data-lucide="arrow-down-circle" class="w-6 h-6 text-success-500"></i>
                </div>
            </div>
        </div>

        <div class="stat-card cursor-pointer hover:shadow-lg hover:border-danger-200 transition-all group" onclick="openWithdrawalsModal()">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 mb-1">Total Withdrawals</p>
                    <p class="text-2xl font-bold text-gray-900">₱{{ number_format($totalWithdrawals, 2) }}</p>
                    <p class="text-xs text-gray-400 mt-1 flex items-center">
                        <i data-lucide="mouse-pointer-click" class="w-3 h-3 mr-1"></i>
                        Click for details
                    </p>
                </div>
                <div class="w-12 h-12 bg-danger-100 rounded-xl flex items-center justify-center group-hover:bg-danger-200 transition-colors">
                    <i data-lucide="arrow-up-circle" class="w-6 h-6 text-danger-600"></i>
                </div>
            </div>
        </div>

        <div class="stat-card cursor-pointer hover:shadow-lg hover:border-warning-200 transition-all group" onclick="openLoansModal()">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 mb-1">Loans Issued</p>
                    <p class="text-2xl font-bold text-gray-900">₱{{ number_format($loansIssued, 2) }}</p>
                    <p class="text-xs text-gray-400 mt-1 flex items-center">
                        <i data-lucide="mouse-pointer-click" class="w-3 h-3 mr-1"></i>
                        Click for details
                    </p>
                </div>
                <div class="w-12 h-12 bg-warning-100 rounded-xl flex items-center justify-center group-hover:bg-warning-200 transition-colors">
                    <i data-lucide="banknote" class="w-6 h-6 text-warning-600"></i>
                </div>
            </div>
        </div>

        <div class="stat-card cursor-pointer hover:shadow-lg hover:border-primary-200 transition-all group" onclick="openNetIncomeModal()">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 mb-1">Net Income</p>
                    <p class="text-2xl font-bold text-gray-900">₱{{ number_format($netIncome, 2) }}</p>
                    <p class="text-xs text-gray-400 mt-1 flex items-center">
                        <i data-lucide="mouse-pointer-click" class="w-3 h-3 mr-1"></i>
                        Click for breakdown
                    </p>
                </div>
                <div class="w-12 h-12 bg-primary-100 rounded-xl flex items-center justify-center group-hover:bg-primary-200 transition-colors">
                    <i data-lucide="trending-up" class="w-6 h-6 text-primary-600"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Data Visualization -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <!-- Monthly Trend Chart -->
        <div class="card p-6">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h2 class="text-lg font-semibold text-gray-900">Monthly Trend</h2>
                    <p class="text-sm text-gray-500">Transaction trends over time</p>
                </div>
            </div>

            <!-- Tabs -->
            <div class="flex gap-1 mb-4 border-b border-gray-200">
                <a href="{{ route('reports', array_merge(request()->query(), ['chart' => 'all'])) }}"
                    class="px-4 py-2 text-sm font-medium rounded-t-lg transition-colors {{ $chartType === 'all' ? 'bg-primary-50 text-primary-600 border-b-2 border-primary-500' : 'text-gray-500 hover:text-gray-700' }}">
                    All
                </a>
                <a href="{{ route('reports', array_merge(request()->query(), ['chart' => 'savings'])) }}"
                    class="px-4 py-2 text-sm font-medium rounded-t-lg transition-colors {{ $chartType === 'savings' ? 'bg-primary-50 text-primary-600 border-b-2 border-primary-500' : 'text-gray-500 hover:text-gray-700' }}">
                    Savings
                </a>
                <a href="{{ route('reports', array_merge(request()->query(), ['chart' => 'lending'])) }}"
                    class="px-4 py-2 text-sm font-medium rounded-t-lg transition-colors {{ $chartType === 'lending' ? 'bg-warning-50 text-warning-600 border-b-2 border-warning-500' : 'text-gray-500 hover:text-gray-700' }}">
                    Lending
                </a>
                <a href="{{ route('reports', array_merge(request()->query(), ['chart' => 'sharecapital'])) }}"
                    class="px-4 py-2 text-sm font-medium rounded-t-lg transition-colors {{ $chartType === 'sharecapital' ? 'bg-success-50 text-success-600 border-b-2 border-success-500' : 'text-gray-500 hover:text-gray-700' }}">
                    Share Capital
                </a>
            </div>

            <!-- Chart Canvas -->
            <div class="relative h-64">
                <canvas id="trendChart"></canvas>
            </div>
        </div>

        <!-- Transaction Distribution -->
        <div class="card p-6">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h2 class="text-lg font-semibold text-gray-900">Transaction Distribution</h2>
                    <p class="text-sm text-gray-500">By category</p>
                </div>
            </div>

            @php
                $totalAmount = $totalDeposits + $totalWithdrawals + $loansIssued;
                $savingsPct = $totalAmount > 0 ? round(($totalDeposits / $totalAmount) * 100) : 0;
                $loansPct = $totalAmount > 0 ? round(($loansIssued / $totalAmount) * 100) : 0;
                $withdrawsPct = $totalAmount > 0 ? round(($totalWithdrawals / $totalAmount) * 100) : 0;
            @endphp

            <div class="space-y-4">
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm text-gray-600">Savings Deposits</span>
                        <span class="text-sm font-medium text-gray-900">{{ $savingsPct }}%</span>
                    </div>
                    <div class="w-full bg-gray-100 rounded-full h-4">
                        <div class="bg-success-500 h-4 rounded-full" style="width: {{ $savingsPct }}%"></div>
                    </div>
                </div>

                <div>
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm text-gray-600">Loan Disbursements</span>
                        <span class="text-sm font-medium text-gray-900">{{ $loansPct }}%</span>
                    </div>
                    <div class="w-full bg-gray-100 rounded-full h-4">
                        <div class="bg-primary-500 h-4 rounded-full" style="width: {{ $loansPct }}%"></div>
                    </div>
                </div>

                <div>
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm text-gray-600">Withdrawals</span>
                        <span class="text-sm font-medium text-gray-900">{{ $withdrawsPct }}%</span>
                    </div>
                    <div class="w-full bg-gray-100 rounded-full h-4">
                        <div class="bg-warning-500 h-4 rounded-full" style="width: {{ $withdrawsPct }}%"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Detailed Table -->
    <div class="card mb-6">
        <div class="p-4 border-b border-gray-100">
            <h2 class="text-lg font-semibold text-gray-900">Detailed Transactions</h2>
        </div>
        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Reference No.</th>
                        <th>Member Name</th>
                        <th>Category</th>
                        <th>Amount</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transactions as $tx)
                    <tr>
                        <td class="text-sm text-gray-900">{{ \Carbon\Carbon::parse($tx->created_at)->format('M d, Y') }}</td>
                        <td class="text-sm text-gray-600">{{ $tx->reference_no ?? 'N/A' }}</td>
                        <td class="text-sm text-gray-900">{{ $tx->member_name ?? 'Unknown' }}</td>
                        <td>
                            @if($tx->type === 'deposit')
                                <span class="badge badge-success">Deposit</span>
                            @elseif($tx->type === 'withdraw')
                                <span class="badge badge-danger">Withdrawal</span>
                            @else
                                <span class="badge badge-primary">{{ $tx->category ?? 'N/A' }}</span>
                            @endif
                        </td>
                        <td class="text-sm font-semibold {{ $tx->type === 'deposit' ? 'text-success-500' : 'text-danger-500' }}">
                            {{ $tx->type === 'deposit' ? '+' : '-' }}₱{{ number_format($tx->amount, 2) }}
                        </td>
                        <td>
                            @if($tx->type === 'deposit')
                                <span class="badge badge-success">Completed</span>
                            @elseif($tx->type === 'withdraw')
                                <span class="badge badge-danger">Withdrawal</span>
                            @else
                                <span class="badge badge-warning">{{ ucfirst($tx->type ?? 'N/A') }}</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-8">
                            <div class="flex flex-col items-center text-gray-500">
                                <i data-lucide="inbox" class="w-12 h-12 mb-3 opacity-50"></i>
                                <p>No transactions found for the selected date range</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Export Actions -->
    <div class="card p-6 mb-6">
        <div class="flex flex-col md:flex-row items-center justify-between gap-4">
            <div>
                <h3 class="font-semibold text-gray-900">Export Report</h3>
                <p class="text-sm text-gray-500">Download this report in your preferred format</p>
            </div>
            <div class="flex gap-3">
                <button class="btn btn-outline" onclick="showToast('PDF Generated', 'Report has been downloaded as PDF')">
                    <i data-lucide="file-text" class="w-4 h-4"></i>
                    Export PDF
                </button>
                <button class="btn btn-outline"
                    onclick="showToast('Excel Generated', 'Report has been downloaded as Excel')">
                    <i data-lucide="table" class="w-4 h-4"></i>
                    Export Excel
                </button>
                <button class="btn btn-outline" onclick="showToast('Print', 'Preparing print preview')">
                    <i data-lucide="printer" class="w-4 h-4"></i>
                    Print
                </button>
            </div>
        </div>
    </div>

    <!-- Deposits Modal -->
    <div id="depositsModal" class="modal-overlay hidden">
        <div class="modal max-w-4xl">
            <div class="p-6 border-b border-gray-100">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-success-100 flex items-center justify-center">
                            <i data-lucide="arrow-down-circle" class="w-5 h-5 text-success-600"></i>
                        </div>
                        <div>
                            <h2 class="text-xl font-bold text-gray-900">Savings Deposits</h2>
                            <p class="text-xs text-gray-500">All deposit transactions</p>
                        </div>
                    </div>
                    <button onclick="closeModal('depositsModal')" class="p-2 hover:bg-gray-100 rounded-lg transition-colors">
                        <i data-lucide="x" class="w-5 h-5 text-gray-500"></i>
                    </button>
                </div>
            </div>
            <div class="p-6 max-h-[60vh] overflow-y-auto">
                <div class="grid grid-cols-3 gap-4 mb-6">
                    <div class="bg-success-50 rounded-lg p-4 border border-success-100 text-center">
                        <p class="text-xs text-gray-500 mb-1">Total Deposits</p>
                        <p class="text-xl font-bold text-gray-900">₱{{ number_format($totalDeposits, 2) }}</p>
                    </div>
                    <div class="bg-primary-50 rounded-lg p-4 border border-primary-100 text-center">
                        <p class="text-xs text-gray-500 mb-1">Transactions</p>
                        <p class="text-xl font-bold text-gray-900">{{ $depositsCount ?? 0 }}</p>
                    </div>
                    <div class="bg-blue-50 rounded-lg p-4 border border-blue-100 text-center">
                        <p class="text-xs text-gray-500 mb-1">Average</p>
                        <p class="text-xl font-bold text-gray-900">₱{{ $depositsCount > 0 ? number_format($totalDeposits / $depositsCount, 2) : 0 }}</p>
                    </div>
                </div>
                <h4 class="font-semibold text-gray-900 mb-4">Recent Deposits</h4>
                <div class="space-y-3">
                    @forelse($deposits ?? [] as $deposit)
                    <div class="flex items-center gap-4 p-4 bg-success-50 rounded-lg border border-success-100">
                        <div class="w-10 h-10 bg-success-100 rounded-full flex items-center justify-center flex-shrink-0">
                            <i data-lucide="arrow-down-circle" class="w-5 h-5 text-success-600"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-900">{{ $deposit->member_name ?? 'Member' }}</p>
                            <p class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($deposit->created_at)->format('M d, Y') }} {{ $deposit->reference_no ? '• ' . $deposit->reference_no : '' }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-sm font-semibold text-success-600">+₱{{ number_format($deposit->amount, 2) }}</p>
                            <span class="badge badge-success">Completed</span>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-8 text-gray-500">
                        <i data-lucide="inbox" class="w-12 h-12 mx-auto mb-2 text-gray-300"></i>
                        <p>No deposits found</p>
                    </div>
                    @endforelse
                </div>
            </div>
            <div class="p-6 border-t border-gray-100 flex justify-end gap-3">
                <button onclick="closeModal('depositsModal')" class="px-5 py-2.5 text-gray-700 font-medium rounded-lg hover:bg-gray-100 transition-colors">Close</button>
                <a href="{{ route('savings') }}" class="px-5 py-2.5 bg-primary-600 text-white font-medium rounded-lg hover:bg-primary-700 transition-colors flex items-center gap-2">
                    <i data-lucide="external-link" class="w-4 h-4"></i>
                    Go to Savings
                </a>
            </div>
        </div>
    </div>

    <!-- Withdrawals Modal -->
    <div id="withdrawalsModal" class="modal-overlay hidden">
        <div class="modal max-w-4xl">
            <div class="p-6 border-b border-gray-100">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-danger-100 flex items-center justify-center">
                            <i data-lucide="arrow-up-circle" class="w-5 h-5 text-danger-600"></i>
                        </div>
                        <div>
                            <h2 class="text-xl font-bold text-gray-900">Withdrawals</h2>
                            <p class="text-xs text-gray-500">All withdrawal transactions</p>
                        </div>
                    </div>
                    <button onclick="closeModal('withdrawalsModal')" class="p-2 hover:bg-gray-100 rounded-lg transition-colors">
                        <i data-lucide="x" class="w-5 h-5 text-gray-500"></i>
                    </button>
                </div>
            </div>
            <div class="p-6 max-h-[60vh] overflow-y-auto">
                <div class="grid grid-cols-3 gap-4 mb-6">
                    <div class="bg-danger-50 rounded-lg p-4 border border-danger-100 text-center">
                        <p class="text-xs text-gray-500 mb-1">Total Withdrawals</p>
                        <p class="text-xl font-bold text-gray-900">₱{{ number_format($totalWithdrawals, 2) }}</p>
                    </div>
                    <div class="bg-primary-50 rounded-lg p-4 border border-primary-100 text-center">
                        <p class="text-xs text-gray-500 mb-1">Transactions</p>
                        <p class="text-xl font-bold text-gray-900">{{ $withdrawalsCount ?? 0 }}</p>
                    </div>
                    <div class="bg-blue-50 rounded-lg p-4 border border-blue-100 text-center">
                        <p class="text-xs text-gray-500 mb-1">Average</p>
                        <p class="text-xl font-bold text-gray-900">₱{{ $withdrawalsCount > 0 ? number_format($totalWithdrawals / $withdrawalsCount, 2) : 0 }}</p>
                    </div>
                </div>
                <h4 class="font-semibold text-gray-900 mb-4">Recent Withdrawals</h4>
                <div class="space-y-3">
                    @forelse($withdrawals ?? [] as $withdrawal)
                    <div class="flex items-center gap-4 p-4 bg-danger-50 rounded-lg border border-danger-100">
                        <div class="w-10 h-10 bg-danger-100 rounded-full flex items-center justify-center flex-shrink-0">
                            <i data-lucide="arrow-up-circle" class="w-5 h-5 text-danger-600"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-900">{{ $withdrawal->member_name ?? 'Member' }}</p>
                            <p class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($withdrawal->created_at)->format('M d, Y') }} {{ $withdrawal->reference_no ? '• ' . $withdrawal->reference_no : '' }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-sm font-semibold text-danger-600">-₱{{ number_format($withdrawal->amount, 2) }}</p>
                            <span class="badge badge-danger">Completed</span>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-8 text-gray-500">
                        <i data-lucide="inbox" class="w-12 h-12 mx-auto mb-2 text-gray-300"></i>
                        <p>No withdrawals found</p>
                    </div>
                    @endforelse
                </div>
            </div>
            <div class="p-6 border-t border-gray-100 flex justify-end gap-3">
                <button onclick="closeModal('withdrawalsModal')" class="px-5 py-2.5 text-gray-700 font-medium rounded-lg hover:bg-gray-100 transition-colors">Close</button>
                <a href="{{ route('savings') }}" class="px-5 py-2.5 bg-primary-600 text-white font-medium rounded-lg hover:bg-primary-700 transition-colors flex items-center gap-2">
                    <i data-lucide="external-link" class="w-4 h-4"></i>
                    Go to Savings
                </a>
            </div>
        </div>
    </div>

    <!-- Loans Modal -->
    <div id="loansModal" class="modal-overlay hidden">
        <div class="modal max-w-4xl">
            <div class="p-6 border-b border-gray-100">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-warning-100 flex items-center justify-center">
                            <i data-lucide="banknote" class="w-5 h-5 text-warning-600"></i>
                        </div>
                        <div>
                            <h2 class="text-xl font-bold text-gray-900">Loans Issued</h2>
                            <p class="text-xs text-gray-500">All loan disbursements</p>
                        </div>
                    </div>
                    <button onclick="closeModal('loansModal')" class="p-2 hover:bg-gray-100 rounded-lg transition-colors">
                        <i data-lucide="x" class="w-5 h-5 text-gray-500"></i>
                    </button>
                </div>
            </div>
            <div class="p-6 max-h-[60vh] overflow-y-auto">
                <div class="grid grid-cols-3 gap-4 mb-6">
                    <div class="bg-warning-50 rounded-lg p-4 border border-warning-100 text-center">
                        <p class="text-xs text-gray-500 mb-1">Total Loans</p>
                        <p class="text-xl font-bold text-gray-900">₱{{ number_format($loansIssued, 2) }}</p>
                    </div>
                    <div class="bg-primary-50 rounded-lg p-4 border border-primary-100 text-center">
                        <p class="text-xs text-gray-500 mb-1">Loans Count</p>
                        <p class="text-xl font-bold text-gray-900">{{ $loansCount ?? 0 }}</p>
                    </div>
                    <div class="bg-blue-50 rounded-lg p-4 border border-blue-100 text-center">
                        <p class="text-xs text-gray-500 mb-1">Average Loan</p>
                        <p class="text-xl font-bold text-gray-900">₱{{ $loansCount > 0 ? number_format($loansIssued / $loansCount, 2) : 0 }}</p>
                    </div>
                </div>
                <h4 class="font-semibold text-gray-900 mb-4">Recent Loans</h4>
                <div class="space-y-3">
                    @forelse($loans ?? [] as $loan)
                    <div class="flex items-center gap-4 p-4 bg-warning-50 rounded-lg border border-warning-100">
                        <div class="w-10 h-10 bg-warning-100 rounded-full flex items-center justify-center flex-shrink-0">
                            <i data-lucide="banknote" class="w-5 h-5 text-warning-600"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-900">{{ $loan['member_name'] ?? 'Member' }}</p>
                            <p class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($loan['created_at'])->format('M d, Y') }} {{ isset($loan['reference_no']) ? '• ' . $loan['reference_no'] : '' }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-sm font-semibold text-warning-600">₱{{ number_format($loan['amount'], 2) }}</p>
                            <span class="badge badge-warning">{{ $loan['purpose'] ?? 'Loan' }}</span>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-8 text-gray-500">
                        <i data-lucide="inbox" class="w-12 h-12 mx-auto mb-2 text-gray-300"></i>
                        <p>No loans found</p>
                    </div>
                    @endforelse
                </div>
            </div>
            <div class="p-6 border-t border-gray-100 flex justify-end gap-3">
                <button onclick="closeModal('loansModal')" class="px-5 py-2.5 text-gray-700 font-medium rounded-lg hover:bg-gray-100 transition-colors">Close</button>
                <a href="{{ route('lendings') }}" class="px-5 py-2.5 bg-primary-600 text-white font-medium rounded-lg hover:bg-primary-700 transition-colors flex items-center gap-2">
                    <i data-lucide="external-link" class="w-4 h-4"></i>
                    Go to Lending
                </a>
            </div>
        </div>
    </div>

    <!-- Net Income Breakdown Modal -->
    <div id="netIncomeModal" class="modal-overlay hidden">
        <div class="modal max-w-lg">
            <div class="p-6 border-b border-gray-100">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-primary-100 flex items-center justify-center">
                            <i data-lucide="trending-up" class="w-5 h-5 text-primary-600"></i>
                        </div>
                        <div>
                            <h2 class="text-xl font-bold text-gray-900">Net Income Breakdown</h2>
                            <p class="text-xs text-gray-500">Financial summary</p>
                        </div>
                    </div>
                    <button onclick="closeModal('netIncomeModal')" class="p-2 hover:bg-gray-100 rounded-lg transition-colors">
                        <i data-lucide="x" class="w-5 h-5 text-gray-500"></i>
                    </button>
                </div>
            </div>
            <div class="p-6 max-h-[60vh] overflow-y-auto">
                <div class="bg-gradient-to-r from-primary-50 to-success-50 rounded-xl p-6 border border-primary-100 mb-6 text-center">
                    <p class="text-xs text-gray-500 mb-1">Net Income</p>
                    <p class="text-3xl font-bold text-gray-900">₱{{ number_format($netIncome, 2) }}</p>
                </div>

                <h4 class="font-semibold text-gray-900 mb-4">Income Sources</h4>
                <div class="space-y-4">
                    <div class="flex items-center justify-between p-4 bg-success-50 rounded-lg border border-success-100">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-success-100 rounded-full flex items-center justify-center">
                                <i data-lucide="arrow-down-circle" class="w-5 h-5 text-success-600"></i>
                            </div>
                            <span class="text-sm font-medium text-gray-900">Total Deposits</span>
                        </div>
                        <span class="text-sm font-semibold text-success-600">+₱{{ number_format($totalDeposits, 2) }}</span>
                    </div>
                    <div class="flex items-center justify-between p-4 bg-danger-50 rounded-lg border border-danger-100">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-danger-100 rounded-full flex items-center justify-center">
                                <i data-lucide="arrow-up-circle" class="w-5 h-5 text-danger-600"></i>
                            </div>
                            <span class="text-sm font-medium text-gray-900">Total Withdrawals</span>
                        </div>
                        <span class="text-sm font-semibold text-danger-600">-₱{{ number_format($totalWithdrawals, 2) }}</span>
                    </div>
                    <div class="flex items-center justify-between p-4 bg-warning-50 rounded-lg border border-warning-100">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-warning-100 rounded-full flex items-center justify-center">
                                <i data-lucide="banknote" class="w-5 h-5 text-warning-600"></i>
                            </div>
                            <span class="text-sm font-medium text-gray-900">Loans Issued</span>
                        </div>
                        <span class="text-sm font-semibold text-warning-600">₱{{ number_format($loansIssued, 2) }}</span>
                    </div>
                </div>

                <div class="border-t border-gray-200 mt-6 pt-6">
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-medium text-gray-900">Net Income</span>
                        <span class="text-lg font-bold text-primary-600">₱{{ number_format($netIncome, 2) }}</span>
                    </div>
                </div>
            </div>
            <div class="p-6 border-t border-gray-100 flex justify-end gap-3">
                <button onclick="closeModal('netIncomeModal')" class="px-5 py-2.5 text-gray-700 font-medium rounded-lg hover:bg-gray-100 transition-colors">Close</button>
                <button onclick="showToast('Report Generated', 'Net income report has been generated', 'success')" class="px-5 py-2.5 bg-primary-600 text-white font-medium rounded-lg hover:bg-primary-700 transition-colors flex items-center gap-2">
                    <i data-lucide="file-text" class="w-4 h-4"></i>
                    Export Report
                </button>
            </div>
        </div>
    </div>

    <script>
        function openDepositsModal() {
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }
            openModal('depositsModal');
        }

        function openWithdrawalsModal() {
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }
            openModal('withdrawalsModal');
        }

        function openLoansModal() {
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }
            openModal('loansModal');
        }

        function openNetIncomeModal() {
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }
            openModal('netIncomeModal');
        }
    </script>

    <script>
        const chartType = '{{ $chartType }}';
        const months = @json($months);
        const savingsData = @json($savingsTrend);
        const lendingData = @json($lendingTrend);
        const shareCapitalData = @json($shareCapitalTrend);

        const ctx = document.getElementById('trendChart').getContext('2d');
        
        let datasets = [];
        
        if (chartType === 'all') {
            datasets = [
                {
                    label: 'Savings',
                    data: savingsData,
                    borderColor: '#4F46E5',
                    backgroundColor: 'rgba(79, 70, 229, 0.1)',
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: '#4F46E5',
                    pointBorderColor: '#fff',
                    pointRadius: 4,
                    pointHoverRadius: 6
                },
                {
                    label: 'Lending',
                    data: lendingData,
                    borderColor: '#F59E0B',
                    backgroundColor: 'rgba(245, 158, 11, 0.1)',
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: '#F59E0B',
                    pointBorderColor: '#fff',
                    pointRadius: 4,
                    pointHoverRadius: 6
                },
                {
                    label: 'Share Capital',
                    data: shareCapitalData,
                    borderColor: '#10B981',
                    backgroundColor: 'rgba(16, 185, 129, 0.1)',
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: '#10B981',
                    pointBorderColor: '#fff',
                    pointRadius: 4,
                    pointHoverRadius: 6
                }
            ];
        } else if (chartType === 'savings') {
            datasets = [{
                label: 'Savings',
                data: savingsData,
                borderColor: '#4F46E5',
                backgroundColor: 'rgba(79, 70, 229, 0.1)',
                fill: true,
                tension: 0.4,
                pointBackgroundColor: '#4F46E5',
                pointBorderColor: '#fff',
                pointRadius: 5,
                pointHoverRadius: 7
            }];
        } else if (chartType === 'lending') {
            datasets = [{
                label: 'Lending',
                data: lendingData,
                borderColor: '#F59E0B',
                backgroundColor: 'rgba(245, 158, 11, 0.1)',
                fill: true,
                tension: 0.4,
                pointBackgroundColor: '#F59E0B',
                pointBorderColor: '#fff',
                pointRadius: 5,
                pointHoverRadius: 7
            }];
        } else if (chartType === 'sharecapital') {
            datasets = [{
                label: 'Share Capital',
                data: shareCapitalData,
                borderColor: '#10B981',
                backgroundColor: 'rgba(16, 185, 129, 0.1)',
                fill: true,
                tension: 0.4,
                pointBackgroundColor: '#10B981',
                pointBorderColor: '#fff',
                pointRadius: 5,
                pointHoverRadius: 7
            }];
        }

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: months,
                datasets: datasets
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: {
                    mode: 'index',
                    intersect: false,
                },
                plugins: {
                    legend: {
                        display: chartType === 'all',
                        position: 'bottom',
                        labels: {
                            usePointStyle: true,
                            padding: 20,
                            font: {
                                size: 12
                            }
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        titleColor: '#fff',
                        bodyColor: '#fff',
                        padding: 12,
                        cornerRadius: 8,
                        callbacks: {
                            label: function(context) {
                                return context.dataset.label + ': ₱' + context.raw.toLocaleString() + 'K';
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            font: {
                                size: 11
                            }
                        }
                    },
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)'
                        },
                        ticks: {
                            font: {
                                size: 11
                            },
                            callback: function(value) {
                                return '₱' + value + 'K';
                            }
                        }
                    }
                }
            }
        });
    </script>
@endsection