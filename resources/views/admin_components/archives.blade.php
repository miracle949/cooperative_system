@extends('layouts.admin')

@section('title', 'Archives - CoopAdmin')

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
                    <span class="text-gray-900 font-medium">Archives</span>
                </li>
            </ol>
        </nav>
    </div>

    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Archives</h1>
            <p class="text-sm text-gray-500">View archived transactions from all modules</p>
        </div>
    </div>

    <!-- Filter Panel -->
    <form method="GET" action="{{ route('archives') }}">
        <input type="hidden" name="tab" value="{{ $activeTab }}">
        <div class="card p-4 mb-6">
            <div class="flex flex-col md:flex-row gap-4">
                <div class="flex gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">From Date</label>
                        <input type="date" name="from_date" class="input" value="{{ $fromDate }}">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">To Date</label>
                        <input type="date" name="to_date" class="input" value="{{ $toDate }}">
                    </div>
                </div>
                <div class="flex items-end">
                    <button type="submit" class="btn btn-primary">
                        <i data-lucide="filter" class="w-4 h-4"></i>
                        Filter
                    </button>
                </div>
            </div>
        </div>
    </form>

    <!-- Tabs -->
    <div class="mb-6">
        <div class="border-b border-gray-200">
            <nav class="-mb-px flex gap-6">
                <a href="{{ route('archives', ['tab' => 'savings']) }}"
                    class="{{ $activeTab === 'savings' ? 'border-primary-500 text-primary-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}
                    whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm flex items-center gap-2">
                    <i data-lucide="piggy-bank" class="w-4 h-4"></i>
                    Savings
                    @if($savingsCount > 0)
                        <span class="ml-1 bg-gray-100 text-gray-600 py-0.5 px-2 rounded-full text-xs">{{ $savingsCount }}</span>
                    @endif
                </a>
                <a href="{{ route('archives', ['tab' => 'sharecapital']) }}"
                    class="{{ $activeTab === 'sharecapital' ? 'border-primary-500 text-primary-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}
                    whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm flex items-center gap-2">
                    <i data-lucide="coins" class="w-4 h-4"></i>
                    Share Capital
                    @if($shareCapitalCount > 0)
                        <span class="ml-1 bg-gray-100 text-gray-600 py-0.5 px-2 rounded-full text-xs">{{ $shareCapitalCount }}</span>
                    @endif
                </a>
                <a href="{{ route('archives', ['tab' => 'lending']) }}"
                    class="{{ $activeTab === 'lending' ? 'border-primary-500 text-primary-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}
                    whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm flex items-center gap-2">
                    <i data-lucide="banknote" class="w-4 h-4"></i>
                    Lending
                    @if($lendingCount > 0)
                        <span class="ml-1 bg-gray-100 text-gray-600 py-0.5 px-2 rounded-full text-xs">{{ $lendingCount }}</span>
                    @endif
                </a>
            </nav>
        </div>
    </div>

    <!-- Savings Archives -->
    @if($activeTab === 'savings')
    <div class="card">
        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Member Name</th>
                        <th>Type</th>
                        <th>Amount</th>
                        <th>Payment Method</th>
                        <th>Reference No.</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($savingsArchives as $tx)
                    <tr>
                        <td class="text-sm text-gray-900">{{ $tx->created_at->format('M d, Y') }}</td>
                        <td>
                            <div class="flex items-center gap-2">
                                <div class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center">
                                    <span class="text-xs text-gray-600 font-medium">
                                        {{ strtoupper(substr($tx->savingsAccount->user->first_name ?? 'U', 0, 1) . substr($tx->savingsAccount->user->last_name ?? '', 0, 1)) }}
                                    </span>
                                </div>
                                <span class="text-sm text-gray-900">
                                    {{ $tx->savingsAccount->user->first_name ?? 'Unknown' }} {{ $tx->savingsAccount->user->last_name ?? '' }}
                                </span>
                            </div>
                        </td>
                        <td>
                            @if($tx->type === 'deposit')
                                <span class="badge badge-success">Deposit</span>
                            @else
                                <span class="badge badge-danger">Withdrawal</span>
                            @endif
                        </td>
                        <td class="text-sm font-semibold text-gray-900">₱{{ number_format($tx->amount, 2) }}</td>
                        <td class="text-sm text-gray-600">{{ $tx->payment_method ?? 'N/A' }}</td>
                        <td class="text-sm text-gray-600">{{ $tx->reference_no ?? 'N/A' }}</td>
                        <td>
                            <form method="POST" action="{{ route('savings.unarchive', $tx->id) }}">
                                @csrf
                                <button type="submit" class="p-1.5 hover:bg-gray-100 rounded" title="Unarchive">
                                    <i data-lucide="archive-restore" class="w-4 h-4 text-gray-500"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-8">
                            <div class="flex flex-col items-center text-gray-500">
                                <i data-lucide="archive" class="w-12 h-12 mb-3 opacity-50"></i>
                                <p>No archived savings transactions</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($savingsArchives->hasPages())
        <div class="p-4 border-t border-gray-100 flex items-center justify-between">
            <p class="text-sm text-gray-500">
                Showing {{ $savingsArchives->firstItem() ?? 0 }}-{{ $savingsArchives->lastItem() ?? 0 }} of {{ $savingsArchives->total() }}
            </p>
            <div>
                {{ $savingsArchives->appends(request()->query())->links() }}
            </div>
        </div>
        @endif
    </div>
    @endif

    <!-- Share Capital Archives -->
    @if($activeTab === 'sharecapital')
    <div class="card">
        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Member Name</th>
                        <th>Type</th>
                        <th>Shares</th>
                        <th>Amount</th>
                        <th>Payment Method</th>
                        <th>Reference No.</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($shareCapitalArchives as $tx)
                    <tr>
                        <td class="text-sm text-gray-900">{{ $tx->created_at->format('M d, Y') }}</td>
                        <td>
                            <div class="flex items-center gap-2">
                                <div class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center">
                                    <span class="text-xs text-gray-600 font-medium">
                                        {{ strtoupper(substr($tx->shareCapitalAccount->user->first_name ?? 'U', 0, 1) . substr($tx->shareCapitalAccount->user->last_name ?? '', 0, 1)) }}
                                    </span>
                                </div>
                                <span class="text-sm text-gray-900">
                                    {{ $tx->shareCapitalAccount->user->first_name ?? 'Unknown' }} {{ $tx->shareCapitalAccount->user->last_name ?? '' }}
                                </span>
                            </div>
                        </td>
                        <td>
                            @if($tx->type === 'subscription')
                                <span class="badge badge-success">Subscription</span>
                            @else
                                <span class="badge badge-danger">Withdrawal</span>
                            @endif
                        </td>
                        <td class="text-sm font-medium text-gray-900">
                            @if($tx->shares > 0)
                                +{{ $tx->shares }}
                            @else
                                {{ $tx->shares }}
                            @endif
                        </td>
                        <td class="text-sm font-semibold text-gray-900">₱{{ number_format($tx->total_amount, 2) }}</td>
                        <td class="text-sm text-gray-600">{{ $tx->payment_method ?? 'N/A' }}</td>
                        <td class="text-sm text-gray-600">{{ $tx->reference_no ?? 'N/A' }}</td>
                        <td>
                            <form method="POST" action="{{ route('sharecapital.unarchive', $tx->id) }}">
                                @csrf
                                <button type="submit" class="p-1.5 hover:bg-gray-100 rounded" title="Unarchive">
                                    <i data-lucide="archive-restore" class="w-4 h-4 text-gray-500"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-8">
                            <div class="flex flex-col items-center text-gray-500">
                                <i data-lucide="archive" class="w-12 h-12 mb-3 opacity-50"></i>
                                <p>No archived share capital transactions</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($shareCapitalArchives->hasPages())
        <div class="p-4 border-t border-gray-100 flex items-center justify-between">
            <p class="text-sm text-gray-500">
                Showing {{ $shareCapitalArchives->firstItem() ?? 0 }}-{{ $shareCapitalArchives->lastItem() ?? 0 }} of {{ $shareCapitalArchives->total() }}
            </p>
            <div>
                {{ $shareCapitalArchives->appends(request()->query())->links() }}
            </div>
        </div>
        @endif
    </div>
    @endif

    <!-- Lending Archives -->
    @if($activeTab === 'lending')
    <div class="card">
        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Member Name</th>
                        <th>Loan Type</th>
                        <th>Amount</th>
                        <th>Term</th>
                        <th>Monthly Payment</th>
                        <th>Reference No.</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($lendingArchives as $loan)
                    <tr>
                        <td class="text-sm text-gray-900">{{ $loan->created_at->format('M d, Y') }}</td>
                        <td>
                            <div class="flex items-center gap-2">
                                <div class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center">
                                    <span class="text-xs text-gray-600 font-medium">
                                        {{ strtoupper(substr($loan->user->first_name ?? 'U', 0, 1) . substr($loan->user->last_name ?? '', 0, 1)) }}
                                    </span>
                                </div>
                                <span class="text-sm text-gray-900">
                                    {{ $loan->user->first_name ?? 'Unknown' }} {{ $loan->user->last_name ?? '' }}
                                </span>
                            </div>
                        </td>
                        <td class="text-sm text-gray-900">{{ $loan->lending_type ?? 'N/A' }}</td>
                        <td class="text-sm font-semibold text-gray-900">₱{{ number_format($loan->lending_amount, 2) }}</td>
                        <td class="text-sm text-gray-600">{{ $loan->lending_type_term ?? 'N/A' }}</td>
                        <td class="text-sm text-gray-600">₱{{ number_format($loan->monthly_payment, 2) }}</td>
                        <td class="text-sm text-gray-600">{{ $loan->reference_no ?? 'N/A' }}</td>
                        <td>
                            <form method="POST" action="{{ route('loan.unarchive', $loan->id) }}">
                                @csrf
                                <button type="submit" class="p-1.5 hover:bg-gray-100 rounded" title="Unarchive">
                                    <i data-lucide="archive-restore" class="w-4 h-4 text-gray-500"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-8">
                            <div class="flex flex-col items-center text-gray-500">
                                <i data-lucide="archive" class="w-12 h-12 mb-3 opacity-50"></i>
                                <p>No archived lending applications</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($lendingArchives->hasPages())
        <div class="p-4 border-t border-gray-100 flex items-center justify-between">
            <p class="text-sm text-gray-500">
                Showing {{ $lendingArchives->firstItem() ?? 0 }}-{{ $lendingArchives->lastItem() ?? 0 }} of {{ $lendingArchives->total() }}
            </p>
            <div>
                {{ $lendingArchives->appends(request()->query())->links() }}
            </div>
        </div>
        @endif
    </div>
    @endif
@endsection
