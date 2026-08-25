<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Loan Status</title>
    <link rel="icon" href="images/websitelogo.png" type="image/png">

    {{-- css link --}}
    <link rel="stylesheet" href="css_folder/loan_status.css">
    <link rel="stylesheet" href="css_folder/loading.css">

    {{-- bootstrap and tailwind link --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- font awesome cdn link --}}
    <link rel="stylesheet" href="font-awesome-icon/css/all.min.css">

    {{-- Modal open/close animation --}}
    <style>
        #repayModal {
            display: none;
        }

        #repayModal .modal-dialog {
            opacity: 0;
            transform: translateY(-24px) scale(0.96);
            transition: opacity 0.25s ease, transform 0.25s ease;
        }

        #repayModal.show .modal-dialog {
            opacity: 1;
            transform: translateY(0) scale(1);
        }

        #repay-backdrop {
            opacity: 0;
            transition: opacity 0.25s ease;
        }

        #repay-backdrop.show {
            opacity: 1;
        }

        .mobile-modal {
            display: none;
        }

        .mobile-modal .modal-dialog {
            opacity: 0;
            transform: translateY(-24px) scale(0.96);
            transition: opacity 0.25s ease, transform 0.25s ease;
        }

        .mobile-modal.show .modal-dialog {
            opacity: 1;
            transform: translateY(0) scale(1);
        }

        #repay-backdrop {
            opacity: 0;
            transition: opacity 0.25s ease;
        }

        #repay-backdrop.show {
            opacity: 1;
        }
    </style>
</head>

<body>

    <div class="container-fluid p-0 m-0">
        @include("components.offcanvas")

        @include("components.sidebar")

        <div class="rightbar">
            @include("components.navbar2")
            @include("components.footer")

            <main>
                <div class="parent-main">
                    @if(!$selectedLoan && $loans->isNotEmpty())
                        <div class="header-main">
                            <h3>Loan Repayments</h3>
                            <p>Manage your loan repayments by tracking payment history, upcoming due dates, and outstanding
                                balances.</p>
                        </div>
                    @endif

                    {{-- @if(!$selectedLoan && $loans->isNotEmpty())
                    <div class="parent-header">
                        <div class="filter-parent">

                            <div class="search-parent">
                                <i class="fa fa-search"></i>
                                <input type="search" id="loan-search" placeholder="Search by reference or type of loan">
                            </div>

                            <div class="filters-parent">
                                <div class="loan-type">
                                    <select id="loan-type-filter" class="form-select">
                                        <option value="" selected>-- Select Loan Type --</option>
                                        @foreach($loans->pluck('display_type')->unique() as $type)
                                        <option value="{{ $type }}">{{ $type }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="reference">
                                    <input type="date" id="loan-date-filter" class="form-select"
                                        title="Filter by date applied" style="font-size: 13.5px;">
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif --}}

                    @if($loans->isEmpty())
                        <!-- <div class="loan-hero" style="display:flex;align-items:center;justify-content:center;padding:40px;">
                                                        <p style="color:var(--teal);margin:0;">You have no approved loans yet.</p>
                                                    </div> -->
                        <div class="header-main">
                            <h3>Loan Repayments</h3>
                            <p>Manage your loan repayments by tracking payment history, upcoming due dates, and outstanding
                                balances.</p>
                        </div>
                        <div class="no-approved-loans" style=" ">
                            <div
                                style="width:56px;height:56px;border-radius:50%;background: #EDF0F5;display:flex;align-items:center;justify-content:center;margin-bottom:1rem;">
                                <i class="fa fa-hourglass-half" style="color:var(--teal);font-size:22px;"></i>
                            </div>
                            <p style="color:var(--muted);margin:0; font-size: 14px;">You have no approved loans yet.</p>
                        </div>
                    @elseif(!$selectedLoan)
                        <div class="loan-select-section">
                            {{-- <div class="loan-grid-pagination-bar">
                                <span id="loan-grid-count">Showing {{ $loans->count() }} of {{ $loans->count() }}
                                    loans</span>
                                <div class="pg-controls" id="loan-grid-pg"></div>
                            </div> --}}
                            <div class="loan-tabs-bar">
                                <div class="loan-tabs">
                                    <button type="button" class="loan-tab-btn active" data-status-filter="all"
                                        onclick="filterLoanStatusTab('all', this)">All</button>
                                    <button type="button" class="loan-tab-btn" data-status-filter="active"
                                        onclick="filterLoanStatusTab('active', this)">Active</button>
                                    <button type="button" class="loan-tab-btn" data-status-filter="completed"
                                        onclick="filterLoanStatusTab('completed', this)">Completed</button>
                                    <button type="button" class="loan-tab-btn" data-status-filter="overdue"
                                        onclick="filterLoanStatusTab('overdue', this)">Overdue</button>
                                </div>
                                {{-- <div class="pg-controls" id="loan-grid-pg"></div> --}}
                            </div>

                            <div class="loan-select-grid" id="loan-select-grid">
                                @foreach($loans as $loan)
                                    <div class="loan-select-card" data-ref="{{ strtolower($loan->reference_no) }}"
                                        data-type="{{ $loan->display_type }}"
                                        data-date="{{ \Carbon\Carbon::parse($loan->created_at)->format('Y-m-d') }}"
                                        data-status="{{ strtolower($loan->card_status) }}"
                                        onclick="navigateToLoan('{{ $loan->id }}')">
                                        <div class="lsc-top">
                                            <div class="lsc-icon"><i class="fa fa-file-invoice-dollar"></i></div>
                                            <span class="lsc-status lsc-status-{{ strtolower($loan->card_status) }}">
                                                {{ $loan->card_status }}
                                            </span>
                                        </div>

                                        <div class="parent-type-status">
                                            <div>
                                                <h6 class="lsc-type">{{ $loan->display_type }}</h6>
                                                <p class="lsc-ref">{{ $loan->reference_no }}</p>
                                            </div>
                                            <div>
                                                <span class="lsc-status lsc-status-{{ strtolower($loan->card_status) }}">
                                                    {{ $loan->card_status }}
                                                </span>
                                            </div>
                                        </div>

                                        <div class="lsc-amount">
                                            <span>Principal</span>
                                            <p>₱{{ number_format($loan->lending_amount, 2) }}</p>
                                        </div>

                                        <div class="lsc-progress">
                                            <div class="lsc-progress-bar">
                                                <div class="lsc-progress-fill"
                                                    style="width:{{ $loan->progress_percent ?? 0 }}%;"></div>
                                            </div>
                                            <div class="amount-to-paid">
                                                <span>{{ $loan->payments_made ?? 0 }}/{{ $loan->total_payments ?? 0 }}
                                                    paid</span>
                                                <i class="fa fa-arrow-right lsc-arrow"></i>
                                            </div>
                                        </div>

                                        <div class="lsc-footer">
                                            <span><i class="fa fa-calendar"></i>
                                                {{ \Carbon\Carbon::parse($loan->created_at)->format('M d, Y') }}</span>
                                            <i class="fa fa-arrow-right lsc-arrow"></i>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <div id="loan-grid-empty" class="no-selected-loans" style="display:none;">
                                <div
                                    style="width:56px;height:56px;border-radius:50%;background:#EDF0F5;display:flex;align-items:center;justify-content:center;margin-bottom:1rem;">
                                    <i class="fa fa-inbox" style="color:var(--teal);font-size:22px;"></i>
                                </div>
                                <p id="loan-grid-empty-text" style="color:var(--muted);margin:0;font-size:14px;"></p>
                            </div>

                            <div class="loan-grid-pagination-bar">

                                <span id="loan-grid-count">Showing {{ $loans->count() }} of {{ $loans->count() }}
                                    loans</span>
                                <div class="pg-controls" id="loan-grid-pg"></div>

                            </div>
                        </div>
                    @else
                        {{-- HERO --}}
                        <div class="back-loan-status">
                            <a href="{{ route('LoanStatus') }}">
                                <i class="fa fa-arrow-left"></i>
                            </a>
                        </div>
                        <div class="loan-hero" id="loan-hero-section">
                            <div class="loan-hero-parent">
                                <div class="left-hero">
                                    <div class="left-text">
                                        <div class="status status-{{ strtolower($loanStatusLabel) }}">{{ $loanStatusLabel }}
                                        </div>
                                        <h3>{{ $selectedLoan->display_type }}</h3>
                                        <p><b>{{ $selectedLoan->reference_no }}</b> · Active
                                            {{ \Carbon\Carbon::parse($selectedLoan->created_at)->format('F d, Y') }}
                                        </p>
                                    </div>

                                    <h5>₱{{ number_format($fullBalanceRemaining, 2) }}</h5>

                                </div>
                                <div class="right-hero">
                                    <div class="payment-button payment-desktop">
                                        <button onclick="handleMakePaymentClick('monthly')" {{ $fullBalanceRemaining <= 0 ? 'disabled style=opacity:.5;cursor:not-allowed;' : '' }}>
                                            <i class="fa fa-peso-sign"></i>
                                            <span>Make a Payment</span>
                                        </button>

                                        <!-- @if($selectedLoan->disbursed_at)
                                                                        <button disabled style="opacity:.6;cursor:not-allowed;background:#e8f5ee;color:#1e7a4e;border:1px solid rgba(30,122,78,.3);">
                                                                            <i class="fa fa-circle-check"></i>
                                                                            <span>Disbursed</span>
                                                                        </button>
                                                                    @else
                                                                        <form action="{{ route('loan.disburse') }}" method="POST" style="margin:0;">
                                                                            @csrf
                                                                            <input type="hidden" name="lending_id" value="{{ $selectedLoan->id }}">
                                                                            <button type="submit">
                                                                                <i class="fa fa-hand-holding-dollar"></i>
                                                                                <span>Disburse Loan</span>
                                                                            </button>
                                                                        </form>
                                                                    @endif -->
                                    </div>
                                </div>
                            </div>
                            <div class="parent-progress">
                                <div class="progress-header">
                                    {{-- <p>Repayment Progress</p>
                                    <span>{{ $lendingStatus->payments_made ?? 0 }} of
                                        {{ $lendingStatus->total_payments ?? 0 }} payments made</span> --}}
                                </div>
                                <div class="progress-body">
                                    <div class="progress-sub">
                                        <div class="progress" style="width: {{ $progressPercent }}%;"></div>
                                    </div>
                                </div>
                                <div class="progress-footer">
                                    {{-- <span>₱{{ number_format($selectedLoan->lending_amount, 0) }} principal</span> --}}
                                    <span>{{ $lendingStatus->payments_made ?? 0 }} of
                                        {{ $lendingStatus->total_payments ?? 0 }} payments made</span>
                                    <span><strong>₱{{ number_format($remainingPrincipal, 0) }}</strong> remaining </span>
                                </div>
                            </div>
                            <div class="perforation"></div>
                            <div class="alh-parent">
                                <div class="alh-stat">
                                    <span>Principal Amount</span>
                                    <h5>₱{{ number_format($selectedLoan->lending_amount, 2) }}</h5>
                                    <p>Applied
                                        {{ \Carbon\Carbon::parse($selectedLoan->created_at)->format('F d, Y') }}
                                    </p>
                                </div>
                                <div class="alh-stat" id="due-date-stat">
                                    <span>Due Date</span>
                                    @if($overdueDate)
                                        <h5 class="stat-value">{{ $overdueDate->format('F d') }}</h5>
                                        <p class="stat-sub badge-overdue">
                                            <i class="fa fa-triangle-exclamation"></i>
                                            #{{ $overdueInstallmentNumber }} · {{ $overdueDaysCount }}
                                            day{{ $overdueDaysCount == 1 ? '' : 's' }} overdue
                                        </p>
                                    @else
                                        <h5 class="stat-value">—</h5>
                                        <p class="stat-sub">No overdue payments</p>
                                    @endif
                                </div>

                                <div class="alh-stat" id="next-due-stat">
                                    <span>Next Due</span>
                                    <h5 class="stat-value">
                                        {{ $displayNextDueDate ? $displayNextDueDate->format('F d') : '—' }}
                                    </h5>
                                    @if($displayDaysAway === null)
                                        <p class="stat-sub">Fully paid</p>
                                    @elseif($displayDaysAway == 0)
                                        <p class="stat-sub badge-upcoming-next" style="padding: 0;">Due today</p>
                                    @else
                                        <p class="stat-sub badge-upcoming-next" style="padding: 0;">{{ $displayDaysAway }} days
                                            away</p>
                                    @endif
                                </div>

                                <div class="alh-stat">
                                    <span>Monthly Due</span>
                                    <h5 id="monthly-due-value">₱{{ number_format($monthlyDue + $currentOverduePenalty, 2) }}
                                    </h5>
                                    <p>Every {{ \Carbon\Carbon::parse($selectedLoan->created_at)->format('jS') }}</p>
                                </div>

                            </div>
                            <div class="payment-button payment-mobile">
                                <button onclick="handleMakePaymentClick('monthly')" {{ $fullBalanceRemaining <= 0 ? 'disabled style=opacity:.5;cursor:not-allowed;' : '' }}>
                                    <i class="fa fa-peso-sign"></i>
                                    <span>Make a Payment</span>
                                </button>

                                <!-- @if($selectedLoan->disbursed_at)
                                                                <button disabled style="opacity:.6;cursor:not-allowed;background:#e8f5ee;color:#1e7a4e;border:1px solid rgba(30,122,78,.3);">
                                                                    <i class="fa fa-circle-check"></i>
                                                                    <span>Disbursed</span>
                                                                </button>
                                                            @else
                                                                <form action="{{ route('loan.disburse') }}" method="POST" style="margin:0;">
                                                                    @csrf
                                                                    <input type="hidden" name="lending_id" value="{{ $selectedLoan->id }}">
                                                                    <button type="submit">
                                                                        <i class="fa fa-hand-holding-dollar"></i>
                                                                        <span>Disburse Loan</span>
                                                                    </button>
                                                                </form>
                                                            @endif -->
                            </div>

                        </div>

                        {{-- 3 SUMMARY BOXES --}}
                        <div class="loan-parent-box" style="padding: 0">
                            {{-- <div class="loan-box">
                                <div class="loan-header">
                                    <h5>Principal Amount</h5>
                                    <div class="loan-icon"><i class="fa fa-file-lines"></i></div>
                                </div>
                                <p>₱{{ number_format($selectedLoan->lending_amount, 2) }}</p>
                                <span>Applied
                                    {{ \Carbon\Carbon::parse($selectedLoan->created_at)->format('F d, Y') }}</span>
                            </div> --}}
                            <div class="loan-box">
                                <div class="loan-header">
                                    <h5>Total Interest</h5>
                                    <div class="loan-icon"><i class="fa fa-clock"></i></div>
                                </div>
                                <p>₱{{ number_format($totalInterest, 2) }}</p>
                                <span>{{ number_format($interestRate, 2) }}% rate · cost</span>
                            </div>
                            <div class="loan-box">
                                <div class="loan-header">
                                    <h5>Total Payable</h5>
                                    <div class="loan-icon"><i class="fa fa-check"></i></div>
                                </div>
                                <p>₱{{ number_format($selectedLoan->total_payment ?? 0, 2) }}</p>
                                <span>Principal + interest + Charges</span> {{-- ← change this line --}}
                            </div>
                            <div class="loan-box" id="penalty-box">
                                <div class="loan-header">
                                    <h5>Penalty</h5>
                                    <div class="loan-icon"><i class="fa fa-triangle-exclamation"></i></div>
                                </div>
                                <p class="penalty-value" style="{{ $penaltyAmount > 0 ? 'color: var(--coral);' : '' }}">
                                    ₱{{ number_format($penaltyAmount, 2) }}</p>
                                <span class="penalty-caption">
                                    @if($penaltyAmount > 0 && ($lendingStatus->penalty_amount ?? 0) == 0)
                                        Will apply on next payment
                                    @elseif($penaltyAmount > 0)
                                        Overdue penalty applied
                                    @else
                                        No penalties applied
                                    @endif
                                </span>
                            </div>
                        </div>

                        {{-- SCHEDULE & CHARGES --}}
                        <div class="schedule-charges">
                            <div class="schedule-parent">
                                <div class="schedule-header" onclick="openScheduleModal()" style="cursor:pointer;">
                                    <!-- <div class="header-tag">
                                                                    <div class="header-icon">
                                                                        <i class="fa fa-calendar-check"></i> 
                                                                    </div>

                                                                Payment Schedule</div> -->
                                    <div>
                                        <div class="header-tag">Payment Schedule</div>
                                        <p>View your upcoming loan payment</p>
                                    </div>
                                    <span>{{ $lendingStatus->payments_made ?? 0 }} of
                                        {{ $lendingStatus->total_payments ?? 0 }} paid</span>

                                    <i class="fa fa-arrow-right"></i>
                                </div>
                                <div class="schedule-body">
                                    @forelse($paymentSchedule as $row)
                                        <div class="pay-item schedule-row"
                                            data-status="{{ $row['paid'] ? 'paid' : ($row['overdue'] ? 'overdue' : ($row['is_next'] ? 'active' : 'upcoming')) }}"
                                            data-number="{{ $row['number'] }}" data-date="{{ $row['date'] }}"
                                            data-amount="{{ $row['amount'] + ($row['penalty'] ?? 0) }}"
                                            onclick="selectScheduleRow(this)">
                                            <div class="item">
                                                <div class="item-icon">
                                                    <span>{{ $row['number'] }}</span>
                                                </div>
                                                <p>{{ $row['date'] }}</p>
                                            </div>
                                            <div class="item-amount">
                                                <p>₱{{ number_format($row['amount'] + ($row['penalty'] ?? 0), 2) }}</p>
                                                @if($row['paid'])
                                                    <p class="paid"><i class="fa fa-check"></i> Paid</p>
                                                @elseif($row['overdue'])
                                                    <p class="badge-overdue-item"><i class="fa fa-triangle-exclamation"></i> Overdue
                                                    </p>
                                                @else
                                                    <p class="badge-upcoming"><i class="fa fa-clock"></i> Upcoming</p>
                                                @endif

                                                @if(($row['penalty'] ?? 0) > 0)
                                                    <p
                                                        style="margin:2px 0 0; font-size:12px; color:var(--red); background-color: var(--red-tint);">
                                                        + ₱{{ number_format($row['penalty'], 2) }}
                                                    </p>
                                                @endif
                                            </div>
                                        </div>
                                    @empty
                                        <p style="color:#999;padding:1rem;">No schedule available.</p>
                                    @endforelse
                                </div>
                            </div>
                            <div class="charges-parent">
                                <div class="charges-header" onclick="openChargesModal()" style="cursor:pointer;">
                                    <!-- <div class="header-tag">
                                                                    <div class="header-icon">
                                                                        <i class="fa fa-money-check-dollar"></i> 
                                                                    </div>

                                                                    Loan Charges</div> -->
                                    <div>
                                        <div class="header-tag">Loan Charges</div>
                                        <p>View your breakdown loan charges</p>
                                    </div>
                                    <div>
                                        <i class="fa fa-arrow-right"></i>
                                    </div>
                                </div>
                                <div class="charges-body">
                                    <div class="pay-item">
                                        <div class="parent-item">
                                            <div class="item">
                                                <div class="icon"><i class="fa fa-coins"></i></div>
                                                <div><span>Interest Rate</span>
                                                    <p>Total interest applied</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="item-amount">
                                            <p>{{ number_format($interestRate, 2) }}%</p>
                                        </div>
                                    </div>
                                    <div class="pay-item">
                                        <div class="parent-item">
                                            <div class="item">
                                                <div class="icon"><i class="fa fa-receipt"></i></div>
                                                <div><span>Total Interest</span>
                                                    <p>Cost of borrowing</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="item-amount">
                                            <p>₱{{ number_format($totalInterest, 2) }}</p>
                                        </div>
                                    </div>
                                    <div class="pay-item">
                                        <div class="parent-item">
                                            <div class="item">
                                                <div class="icon"><i class="fa fa-calculator"></i></div>
                                                <div><span>Processing Fee</span>
                                                    <p>Processing & collection</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="item-amount">
                                            <p>₱{{ number_format($processingFee, 2) }}</p>
                                        </div>
                                    </div>
                                    <div class="pay-item">
                                        <div class="parent-item">
                                            <div class="item">
                                                <div class="icon"><i class="fa fa-file-contract"></i></div>
                                                <div><span>Service & Legal Fee</span>
                                                    <p>One-time fee</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="item-amount">
                                            <p>₱{{ number_format($serviceFee, 2) }}</p>
                                        </div>
                                    </div>
                                    <div class="pay-item">
                                        <div class="parent-item">
                                            <div class="item">
                                                <div class="icon"><i class="fa fa-shield-halved"></i></div>
                                                <div><span>Loan Protection Plan</span>
                                                    <p>Per month of term</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="item-amount">
                                            <p>₱{{ number_format($loanProtectionFee, 2) }}</p>
                                        </div>
                                    </div>
                                    <div class="pay-item">
                                        <div class="parent-item">
                                            <div class="item">
                                                <div class="icon"><i class="fa fa-piggy-bank"></i></div>
                                                <div><span>Retention / CBU</span>
                                                    <p>Held as capital build-up</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="item-amount">
                                            <p>₱{{ number_format($retentionFee, 2) }}</p>
                                        </div>
                                    </div>
                                    <div class="pay-item">
                                        <div class="parent-item">
                                            <div class="item">
                                                <div class="icon"><i class="fa fa-hand-holding-dollar"></i></div>
                                                <div><span>Net Proceeds</span>
                                                    <p>Amount released to you</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="item-amount">
                                            <p>₱{{ number_format($netProceeds, 2) }}</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="charges-footer">
                                    <div class="total-charges">
                                        <span>Total Charges</span>
                                        <p>₱{{ number_format($totalInterest + $processingFee + $serviceFee + $loanProtectionFee + $retentionFee, 2) }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- PAYMENT HISTORY Table--}}
                        <div class="loan-history-mobile">
                            <div class="loan-header-mobile">
                                <div class="header-tag">Payment History</div>
                            </div>
                            <div class="loan-body-mobile">
                                <div class="loan-history-mobile-list" id="ph-mobile-list">
                                    @forelse($paymentHistory as $payment)
                                        @php
                                            $wasLate = $payment->due_date && \Carbon\Carbon::parse($payment->payment_date)->gt(\Carbon\Carbon::parse($payment->due_date));
                                        @endphp
                                        <div class="ph-card ph-row-mobile">
                                            <div class="ph-card-top">
                                                <div class="ph-card-icon"><i class="fa fa-receipt"></i></div>
                                                <div class="ph-card-info">
                                                    <p class="ph-card-ref">{{ $payment->reference_no }}</p>
                                                    <p class="ph-card-meta">
                                                        {{ \Carbon\Carbon::parse($payment->payment_date)->format('M d, Y') }} ·
                                                        {{ \Carbon\Carbon::parse($payment->created_at)->format('h:i A') }}
                                                    </p>
                                                </div>
                                                @if($wasLate)
                                                    <span class="badge-overdue"><i class="fa fa-triangle-exclamation"></i>
                                                        Overdue</span>
                                                @else
                                                    <span class="badge-paid">Paid</span>
                                                @endif
                                            </div>
                                            <div class="ph-card-bottom">
                                                <div class="ph-card-detail">
                                                    <span>Amount</span>
                                                    <p>₱{{ number_format($payment->amount_paid, 2) }}</p>
                                                </div>
                                                <div class="ph-card-detail">
                                                    <span>Penalty</span>
                                                    <p>
                                                        @if(($payment->late_fee ?? 0) > 0)
                                                            <span
                                                                style="color:var(--coral);font-weight:600;">₱{{ number_format($payment->late_fee, 2) }}</span>
                                                        @else
                                                            —
                                                        @endif
                                                    </p>
                                                </div>
                                                <div class="ph-card-detail">
                                                    <span>Method</span>
                                                    <p>{{ $payment->payment_method }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    @empty
                                        <p style="color:#999;padding:1.5rem;text-align:center;">No payment history yet.</p>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                        <div class="loan-history">
                            <div class="loan-header">
                                <div class="header-tag">Payment History</div>
                                <p>View your payment history below.</p>
                            </div>
                            <div class="loan-body">
                                <div class="parent-table">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>Reference No.</th>
                                                <th>Payment Date</th>
                                                <th>Time</th>
                                                <th>Amount</th>
                                                <th>Penalty</th>
                                                <th>Penalty Date</th>
                                                <th>Method</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody id="ph-tbody">
                                            @forelse($paymentHistory as $payment)
                                                <tr class="ph-row">
                                                    <td>{{ $payment->reference_no }}</td>
                                                    <td>{{ \Carbon\Carbon::parse($payment->payment_date)->format('M d, Y') }}
                                                    </td>
                                                    <td>{{ \Carbon\Carbon::parse($payment->created_at)->format('h:i A') }}</td>
                                                    <td>₱{{ number_format($payment->amount_paid, 2) }}</td>
                                                    <td>
                                                        @if(($payment->late_fee ?? 0) > 0)
                                                            <span style="color: var(--coral); font-weight: 600;">
                                                                ₱{{ number_format($payment->late_fee, 2) }}
                                                            </span>
                                                        @else
                                                            <span style="color: #aaa;">—</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if($payment->penalty_applied_at)
                                                            {{ \Carbon\Carbon::parse($payment->penalty_applied_at)->format('M d, Y · h:i A') }}
                                                        @else
                                                            <span style="color: #aaa;">—</span>
                                                        @endif
                                                    </td>
                                                    <td>{{ $payment->payment_method }}</td>
                                                    <td>
                                                        @php
                                                            $wasLate = $payment->due_date && \Carbon\Carbon::parse($payment->payment_date)->gt(\Carbon\Carbon::parse($payment->due_date));
                                                        @endphp
                                                        @if($wasLate)
                                                            <span class="badge-overdue"><i class="fa fa-triangle-exclamation"></i>
                                                                Paid (Overdue)</span>
                                                        @else
                                                            <span class="badge-paid"> Paid</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="8" style="text-align:center;color:#999;padding:1.5rem;">No
                                                        payment history yet.</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>

                                @if($paymentHistory->isNotEmpty())
                                    <div class="sc-table-footer">
                                        <span id="ph-footer-count">Showing {{ min(10, $paymentHistory->count()) }} of
                                            {{ $paymentHistory->count() }} payments</span>
                                        <div class="sc-pagination" id="ph-pagination"></div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>
            </main>
        </div>

        {{-- REPAYMENT MODAL --}}
        <div class="modal fade mobile-modal" id="repayModal" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered" style="max-width: 480px;">
                <div class="modal-content"
                    style="border: none; border-radius: 16px; overflow: hidden; box-shadow: 0 20px 60px rgba(0,0,0,0.15);">

                    {{-- Modal Header --}}
                    <div class="modal-header"
                        style="background: #ffffff; padding: 1.4rem 1.6rem; border-bottom: 1px solid var(--line);">
                        <div class="modal-parent">
                            <div class="modal-icon">
                                <i class="fa fa-money-bill-wave"></i>
                            </div>
                            <div>
                                <h5 class="modal-title"
                                    style="color: #1a1a1a; font-size: 17px; font-weight: 600; margin: 0;">
                                    Make a Payment
                                </h5>
                                <p style="color: var(--muted); font-size: 13.5px; margin: 3.2px 0 0;">
                                    {{ $selectedLoan->display_type ?? '' }} —
                                    ₱{{ number_format($selectedLoan->lending_amount ?? 0, 2) }}
                                </p>
                            </div>
                        </div>
                        <button type="button" class="btn-close" style="color: var(--muted); font-size: 16px;"
                            onclick="closeRepayModal()"></button>
                    </div>

                    <div class="modal-body" style="padding: 1.6rem; background: #fff;">

                        {{-- Payment Type Toggle --}}
                        <div style="margin-bottom: 1.1rem;">
                            <label
                                style="font-size: 12px; font-weight: 600; color: #888; text-transform: uppercase; letter-spacing: 0.5px; display: block; margin-bottom: 6px;">
                                Payment Type
                            </label>
                            <select id="payment-type-select" class="form-select"
                                onchange="handlePaymentTypeChange(this.value)"
                                style="border-radius: 10px; border: 1.5px solid #e0e0e0; height: 46px; font-size: 14px; color: #333;">
                                <option value="monthly">Monthly Payment — ₱{{ number_format($monthlyDue, 2) }}</option>
                                <option value="full">Full Balance — ₱{{ number_format($fullBalanceRemaining, 2) }}
                                </option>
                            </select>
                        </div>

                        <form action="{{ route('repayment.store') }}" method="POST" id="cash-repay-form"
                            enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="lending_id" value="{{ $selectedLoan->id ?? '' }}">
                            <input type="hidden" name="member_id" value="{{ auth()->id() }}">
                            <input type="hidden" name="payment_number"
                                value="{{ ($lendingStatus->payments_made ?? 0) + 1 }}">
                            <input type="hidden" name="payment_type" id="cash-payment-type" value="monthly">

                            {{-- Amount --}}
                            <div style="margin-bottom: 1.1rem;">
                                <label
                                    style="font-size: 12px; font-weight: 600; color: #888; text-transform: uppercase; letter-spacing: 0.5px; display: block; margin-bottom: 6px;">
                                    Amount to Pay (₱)
                                </label>
                                <div style="position: relative;">
                                    <span
                                        style="position: absolute; left: 14px; top: 50%; transform: translateY(-50%); color: var(--teal); font-weight: 500; font-size: 15px;">₱</span>
                                    <input type="number" name="amount_paid" id="repay-amount-input" class="form-control"
                                        value="{{ $monthlyDue }}"
                                        style="padding-left: 28px; border-radius: 10px; border: 1.5px solid #e0e0e0; font-size: 14px; font-weight: 500; color: var(--teal); height: 46px;"
                                        readonly>
                                </div>
                            </div>

                            {{-- Payment Method --}}
                            <div style="margin-bottom: 1.1rem;">
                                <label
                                    style="font-size: 12px; font-weight: 600; color: #888; text-transform: uppercase; letter-spacing: 0.5px; display: block; margin-bottom: 6px;">
                                    Payment Method
                                </label>
                                <select name="payment_method" id="repay-method" class="form-select"
                                    onchange="handleMethodChange(this.value)"
                                    style="border-radius: 10px; border: 1.5px solid #e0e0e0; height: 46px; font-size: 14px; color: #333;">
                                    <option value="Cash">Cash</option>
                                    <option value="GCash">GCash</option>
                                </select>
                            </div>

                            {{-- GCash section --}}
                            <div id="gcash-section" style="display: none;">
                                <div style="border-top: 1.5px dashed #e8e8e8; margin: 1.2rem 0;"></div>

                                @if($gcashPaymentMethod && $gcashPaymentMethod->has_qr_code && $gcashPaymentMethod->qr_code_image_path)
                                    <div
                                        style="background: linear-gradient(135deg, #f0f7ff 0%, #e8f4ff 100%); border: 1.5px solid #c2deff; border-radius: 12px; padding: 1rem 1.2rem; text-align: center;">
                                        <p style="margin: 0 0 10px; font-size: 14px; font-weight: 700; color: #0056b3;">
                                            <i class="fa-solid fa-mobile-screen-button"></i> Scan to Pay via GCash
                                        </p>
                                        <img src="{{ asset('storage/' . $gcashPaymentMethod->qr_code_image_path) }}"
                                            alt="GCash QR Code" loading="lazy"
                                            style="width: 320px; height: 320px; max-width: 100%; object-fit: contain; border-radius: 10px; border: 1px solid #c2deff; background: #fff; padding: 14px; display: block; margin: 0 auto;"
                                            ondblclick="openQrLightbox(this.src)">
                                        <p style="margin: 10px 0 0; font-size: 11px; color: #5a8ac4;">
                                            Scan this using your GCash app, then upload your payment screenshot below.
                                        </p>
                                        <p style="margin: 6px 0 0; font-size: 11px;">
                                            <a href="#"
                                                onclick="openQrLightbox('{{ asset('storage/' . $gcashPaymentMethod->qr_code_image_path) }}'); return false;"
                                                style="color: #0056b3; font-weight: 600;">
                                                <i class="fa fa-up-right-and-down-left-from-center"></i> View full-size QR
                                            </a>
                                        </p>
                                    </div>

                                @else
                                    <div
                                        style="background: #fff3cd; border: 1.5px solid #ffe08a; border-radius: 12px; padding: 1rem 1.2rem;">
                                        <p style="margin: 0; font-size: 13px; color: #856404;">
                                            <i class="fa fa-triangle-exclamation"></i> No GCash QR code has been set up yet.
                                            Please contact the admin.
                                        </p>
                                    </div>
                                @endif

                                <div style="margin-top: 1.1rem;">
                                    <label
                                        style="font-size: 12px; font-weight: 600; color: #888; text-transform: uppercase; letter-spacing: 0.5px; display: block; margin-bottom: 6px;">
                                        Upload Payment Screenshot <span
                                            style="color:#aaa; font-weight:400; text-transform:none;">(GCash
                                            proof)</span>
                                    </label>
                                    <input type="file" name="gcash_proof" id="gcash-proof-input"
                                        accept="image/png,image/jpeg,image/jpg" class="form-control"
                                        style="border-radius: 10px; border: 1.5px solid #e0e0e0; font-size: 14px; padding: 8px;">
                                    <div id="gcash-proof-preview" style="display:none; margin-top:10px;">
                                        <img id="gcash-proof-preview-img"
                                            style="width:100%; height:180px; object-fit:cover; border-radius:8px; border:1px solid #e0e0e0;">
                                    </div>
                                </div>
                            </div>

                            {{-- Reference No --}}
                            <div id="ref-no-section" style="margin-top: 1.1rem;">
                                <label
                                    style="font-size: 12px; font-weight: 600; color: #888; text-transform: uppercase; letter-spacing: 0.5px; display: block; margin-bottom: 6px;">
                                    Reference / Receipt No.
                                    <span
                                        style="color: #aaa; font-weight: 400; font-size: 11px; text-transform: none;">(optional
                                        — auto-generated if blank)</span>
                                </label>
                                <input type="text" name="reference_no" id="repay-reference-input" class="form-control"
                                    placeholder="Leave blank to auto-generate"
                                    style="border-radius: 10px; border: 1.5px solid #e0e0e0; height: 46px; font-size: 14px; color: #333;">
                            </div>

                            {{-- Notes --}}
                            <div id="notes-section" style="margin-top: 1.1rem;">
                                <label
                                    style="font-size: 12px; font-weight: 600; color: #888; text-transform: uppercase; letter-spacing: 0.5px; display: block; margin-bottom: 6px;">
                                    Notes (optional)
                                </label>
                                <textarea name="notes" id="repay-notes-input" class="form-control" rows="2"
                                    placeholder="Additional remarks..."
                                    style="border-radius: 10px; border: 1.5px solid #e0e0e0; font-size: 14px; color: #333; resize: none;"></textarea>
                            </div>
                        </form>

                    </div>

                    {{-- Modal Footer --}}
                    <div class="modal-footer"
                        style="background: #f8f9fa; border-top: 1px solid rgba(0,0,0,0.1); padding: 1rem 1.6rem; display: flex; justify-content: center; align-items: center; flex-direction: column; gap: 8px;">
                        <button type="submit" form="cash-repay-form" id="confirm-pay-btn" class="btn w-100"
                            style="background: var(--teal); color: white; border-radius: 8px; font-size: 14px; font-weight: 600; padding: 10px 22px; border: none; display: flex; align-items: center; gap: 6px; justify-content: center;">
                            <i class="fa-solid fa-check" style="font-size: 12px;"></i>
                            Confirm Payment
                        </button>
                        <button type="button" class="btn w-100 text-center" onclick="closeRepayModal()"
                            style="border-radius: 8px; font-size: 14px; padding: 10px 18px; border: 1.5px solid #e0e0e0; color: var(--muted);">
                            Cancel
                        </button>
                    </div>

                </div>
            </div>
        </div>

        {{-- Payment Schedule Modal (mobile) --}}
        <div class="modal fade mobile-modal" id="scheduleModal" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered" style="max-width: 480px;">
                <div class="modal-content"
                    style="border:none;border-radius:16px;overflow:hidden;box-shadow:0 20px 60px rgba(0,0,0,0.15);">
                    <div class="modal-header"
                        style="background:#fff;padding:1.4rem 1.6rem;border-bottom:1px solid var(--line);">
                        <div class="modal-parent">
                            <div class="modal-icon"><i class="fa fa-calendar-check"></i></div>
                            <div>
                                <h5 class="modal-title" style="color:#1a1a1a;font-size:17px;font-weight:600;margin:0;">
                                    Payment Schedule</h5>
                                <p style="color:var(--muted);font-size:13.5px;margin:3.2px 0 0;">
                                    {{ $lendingStatus->payments_made ?? 0 }} of
                                    {{ $lendingStatus->total_payments ?? 0 }} paid
                                </p>
                            </div>
                        </div>
                        <button type="button" class="btn-close" style="color:var(--muted);font-size:16px;"
                            onclick="closeModal('scheduleModal')"></button>
                    </div>
                    <div class="modal-body" style="padding:0;background:#fff;max-height:70vh;overflow-y:auto;">
                        @forelse($paymentSchedule as $row)
                            <div class="pay-item schedule-row"
                                data-status="{{ $row['paid'] ? 'paid' : ($row['overdue'] ? 'overdue' : ($row['is_next'] ? 'active' : 'upcoming')) }}"
                                data-number="{{ $row['number'] }}" data-date="{{ $row['date'] }}"
                                data-amount="{{ $row['amount'] + ($row['penalty'] ?? 0) }}"
                                onclick="selectScheduleRow(this); closeModal('scheduleModal');">
                                <div class="item">
                                    <div class="item-icon"><span>{{ $row['number'] }}</span></div>
                                    <p>{{ $row['date'] }}</p>
                                </div>
                                <div class="item-amount">
                                    <p>₱{{ number_format($row['amount'] + ($row['penalty'] ?? 0), 2) }}</p>
                                    @if($row['paid'])
                                        <p class="paid"><i class="fa fa-check"></i> Paid</p>
                                    @elseif($row['overdue'])
                                        <p class="badge-overdue-item"><i class="fa fa-triangle-exclamation"></i> Overdue</p>
                                    @else
                                        <p class="badge-upcoming"><i class="fa fa-clock"></i> Upcoming</p>
                                    @endif
                                    @if(($row['penalty'] ?? 0) > 0)
                                        <p
                                            style="margin:2px 0 0;font-size:12px;color:var(--red);background-color:var(--red-tint);">
                                            + ₱{{ number_format($row['penalty'], 2) }}
                                        </p>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <p style="color:#999;padding:1rem;">No schedule available.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        {{-- Loan Charges Modal (mobile) --}}
        <div class="modal fade mobile-modal" id="chargesModal" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered" style="max-width:480px;">
                <div class="modal-content"
                    style="border:none;border-radius:16px;overflow:hidden;box-shadow:0 20px 60px rgba(0,0,0,0.15);">
                    <div class="modal-header"
                        style="background:#fff;padding:1.4rem 1.6rem;border-bottom:1px solid var(--line);">
                        <div class="modal-parent">
                            <div class="modal-icon"><i class="fa fa-money-check-dollar"></i></div>
                            <div>
                                <h5 class="modal-title" style="color:#1a1a1a;font-size:17px;font-weight:600;margin:0;">
                                    Loan Charges</h5>
                                <p style="color:var(--muted);font-size:13.5px;margin:3.2px 0 0;">Breakdown of your loan
                                    charges</p>
                            </div>
                        </div>
                        <button type="button" class="btn-close" style="color:var(--muted);font-size:16px;"
                            onclick="closeModal('chargesModal')"></button>
                    </div>
                    <div class="modal-body" style="padding:0;background:#fff;max-height:70vh;overflow-y:auto;">
                        <div class="pay-item">
                            <div class="parent-item">
                                <div class="item">
                                    <div class="icon"><i class="fa fa-coins"></i></div>
                                    <div><span>Interest Rate</span>
                                        <p>Total interest applied</p>
                                    </div>
                                </div>
                            </div>
                            <div class="item-amount">
                                <p>{{ number_format($interestRate, 2) }}%</p>
                            </div>
                        </div>
                        <div class="pay-item">
                            <div class="parent-item">
                                <div class="item">
                                    <div class="icon"><i class="fa fa-receipt"></i></div>
                                    <div><span>Total Interest</span>
                                        <p>Cost of borrowing</p>
                                    </div>
                                </div>
                            </div>
                            <div class="item-amount">
                                <p>₱{{ number_format($totalInterest, 2) }}</p>
                            </div>
                        </div>
                        <div class="pay-item">
                            <div class="parent-item">
                                <div class="item">
                                    <div class="icon"><i class="fa fa-calculator"></i></div>
                                    <div><span>Processing Fee</span>
                                        <p>Processing & collection</p>
                                    </div>
                                </div>
                            </div>
                            <div class="item-amount">
                                <p>₱{{ number_format($processingFee, 2) }}</p>
                            </div>
                        </div>
                        <div class="pay-item">
                            <div class="parent-item">
                                <div class="item">
                                    <div class="icon"><i class="fa fa-file-contract"></i></div>
                                    <div><span>Service & Legal Fee</span>
                                        <p>One-time fee</p>
                                    </div>
                                </div>
                            </div>
                            <div class="item-amount">
                                <p>₱{{ number_format($serviceFee, 2) }}</p>
                            </div>
                        </div>
                        <div class="pay-item">
                            <div class="parent-item">
                                <div class="item">
                                    <div class="icon"><i class="fa fa-shield-halved"></i></div>
                                    <div><span>Loan Protection Plan</span>
                                        <p>Per month of term</p>
                                    </div>
                                </div>
                            </div>
                            <div class="item-amount">
                                <p>₱{{ number_format($loanProtectionFee, 2) }}</p>
                            </div>
                        </div>
                        <div class="pay-item">
                            <div class="parent-item">
                                <div class="item">
                                    <div class="icon"><i class="fa fa-piggy-bank"></i></div>
                                    <div><span>Retention / CBU</span>
                                        <p>Held as capital build-up</p>
                                    </div>
                                </div>
                            </div>
                            <div class="item-amount">
                                <p>₱{{ number_format($retentionFee, 2) }}</p>
                            </div>
                        </div>
                        <div class="pay-item">
                            <div class="parent-item">
                                <div class="item">
                                    <div class="icon"><i class="fa fa-hand-holding-dollar"></i></div>
                                    <div><span>Net Proceeds</span>
                                        <p>Amount released to you</p>
                                    </div>
                                </div>
                            </div>
                            <div class="item-amount">
                                <p>₱{{ number_format($netProceeds, 2) }}</p>
                            </div>
                        </div>
                        <div class="total-charges"
                            style="padding:18px 20px;display:flex;justify-content:space-between;align-items:center;background-color:var(--lavender-tint);">
                            <span style="color:#1a1a1a;font-weight:600;font-size:13.5px;">Total Charges</span>
                            <p style="color:var(--coral);font-weight:700;font-size:13.5px;margin:0;">
                                ₱{{ number_format($totalInterest + $processingFee + $serviceFee + $loanProtectionFee + $retentionFee, 2) }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Hidden form that submits to storeRepayment --}}
        <form id="repay-form" action="{{ route('repayment.store') }}" method="POST" style="display:none;">
            @csrf
            <input type="hidden" name="lending_id" value="{{ $selectedLoan->id ?? '' }}">
            <input type="hidden" name="payment_number" value="{{ ($lendingStatus->payments_made ?? 0) + 1 }}">
            <input type="hidden" name="amount_paid" id="form-amount-paid">
            <input type="hidden" name="payment_method" id="form-payment-method">
            <input type="hidden" name="payment_type" id="form-payment-type">
            <input type="hidden" name="reference_no" id="form-reference-no">
            <input type="hidden" name="notes" id="form-notes">
        </form>

        {{-- Backdrop --}}
        <div id="repay-backdrop"
            style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.5); z-index:1040;"></div>

        {{-- REPAYMENT MODAL --}}

        @if(session('success'))
            <div class="status-alert" style="border-color:#1e7a4e;">
                <i class="fa fa-circle-check"></i>
                <div class="status-alert-text" style="color:#1e7a4e;">{{ session('success') }}</div>
            </div>
        @endif
        @if(session('error'))
            <div class="status-alert" style="border-color:#dc3545;">
                <i class="fa fa-triangle-exclamation"></i>
                <div class="status-alert-text" style="color:#dc3545;">{{ session('error') }}</div>
            </div>
        @endif

    </div>

    {{-- QR Lightbox --}}
    <div id="qr-lightbox-overlay"
        style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.8); z-index:100000; align-items:center; justify-content:center;">
        <button type="button" onclick="closeQrLightbox()"
            style="position:absolute; top:20px; right:24px; background:#fff; border:none; width:40px; height:40px; border-radius:50%; font-size:20px; color:#333; cursor:pointer; display:flex; align-items:center; justify-content:center;">
            <i class="fa fa-times"></i>
        </button>
        <img id="qr-lightbox-img" src="" alt="GCash QR Code"
            style="max-width:90%; max-height:85vh; border-radius:12px;">
    </div>

    {{-- AOS animation link js --}}
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>

    <script>
        (function () {
            const tbody = document.getElementById('ph-tbody');
            if (!tbody) return;

            const rows = Array.from(tbody.querySelectorAll('tr.ph-row'));
            if (rows.length === 0) return;

            const footerCount = document.getElementById('ph-footer-count');
            const pagination = document.getElementById('ph-pagination');
            const PAGE_SIZE = 10;
            const total = rows.length;
            const totalPages = Math.max(1, Math.ceil(total / PAGE_SIZE));
            let currentPage = 1;

            function renderPagination() {
                pagination.innerHTML = '';

                const prevBtn = document.createElement('button');
                prevBtn.type = 'button';
                prevBtn.className = 'sc-page-btn nav';
                prevBtn.innerHTML = '<i class="fa fa-chevron-left"></i>';
                prevBtn.disabled = currentPage === 1;
                prevBtn.addEventListener('click', () => { currentPage--; render(); });
                pagination.appendChild(prevBtn);

                for (let p = 1; p <= totalPages; p++) {
                    const btn = document.createElement('button');
                    btn.type = 'button';
                    btn.className = 'sc-page-btn' + (p === currentPage ? ' active' : '');
                    btn.textContent = p;
                    btn.addEventListener('click', () => { currentPage = p; render(); });
                    pagination.appendChild(btn);
                }

                const nextBtn = document.createElement('button');
                nextBtn.type = 'button';
                nextBtn.className = 'sc-page-btn nav';
                nextBtn.innerHTML = '<i class="fa fa-chevron-right"></i>';
                nextBtn.disabled = currentPage === totalPages;
                nextBtn.addEventListener('click', () => { currentPage++; render(); });
                pagination.appendChild(nextBtn);
            }

            function render() {
                const startIdx = (currentPage - 1) * PAGE_SIZE;
                const endIdx = startIdx + PAGE_SIZE;

                rows.forEach(row => { row.style.display = 'none'; });
                rows.slice(startIdx, endIdx).forEach(row => { row.style.display = ''; });

                if (footerCount) {
                    const shownCount = Math.min(endIdx, total) - startIdx;
                    footerCount.textContent = `Showing ${shownCount} of ${total} payments`;
                }

                renderPagination();
            }

            render();
        })();

        function openQrLightbox(src) {
            document.getElementById('qr-lightbox-img').src = src;
            document.getElementById('qr-lightbox-overlay').style.display = 'flex';
        }

        function closeQrLightbox() {
            document.getElementById('qr-lightbox-overlay').style.display = 'none';
        }

        document.getElementById('qr-lightbox-overlay')?.addEventListener('click', function (e) {
            if (e.target === this) closeQrLightbox();
        });
    </script>

    <script>
        function navigateToLoan(loanId) {
            if (!loanId) return;
            const url = new URL(window.location.href);
            url.searchParams.set('loan_id', loanId);
            window.location.href = url.toString();
        }

        // ══════════════════════════════════════════════════════════
        //  LOAN GRID — search / type / date filtering + pagination
        //  (only relevant on the "no loan selected" grid view)
        // ══════════════════════════════════════════════════════════
        const LOAN_GRID_PAGE_SIZE = 10;
        let loanGridPage = 1;

        function getLoanGridCards() {
            const grid = document.getElementById('loan-select-grid');
            return grid ? Array.from(grid.querySelectorAll('.loan-select-card')) : [];
        }

        let loanStatusFilter = 'all';

        function filterLoanStatusTab(status, btn) {
            document.querySelectorAll('.loan-tab-btn').forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
            loanStatusFilter = status;
            applyLoanFilters();
        }

        function applyLoanFilters() {
            const cards = getLoanGridCards();
            if (cards.length === 0) return;

            const search = (document.getElementById('loan-search')?.value || '').toLowerCase().trim();
            const type = document.getElementById('loan-type-filter')?.value || '';
            const date = document.getElementById('loan-date-filter')?.value || '';

            cards.forEach(card => {
                const cardType = card.dataset.type || '';
                const cardRef = (card.dataset.ref || '').toLowerCase();
                const cardDate = card.dataset.date || '';
                const cardStatus = card.dataset.status || '';

                const matchesSearch = !search || cardRef.includes(search) || cardType.toLowerCase().includes(search);
                const matchesType = !type || cardType === type;
                const matchesDate = !date || cardDate === date;
                const matchesStatus = loanStatusFilter === 'all' || cardStatus === loanStatusFilter;

                card.dataset.filteredOut = (matchesSearch && matchesType && matchesDate && matchesStatus) ? 'false' : 'true';
            });

            loanGridPage = 1;
            paginateLoanGrid();
        }

        function paginateLoanGrid() {
            const cards = getLoanGridCards();
            if (cards.length === 0) return;

            const filtered = cards.filter(card => card.dataset.filteredOut !== 'true');
            const totalPages = Math.max(1, Math.ceil(filtered.length / LOAN_GRID_PAGE_SIZE));
            if (loanGridPage > totalPages) loanGridPage = totalPages;

            cards.forEach(card => {
                if (card.dataset.filteredOut === 'true') card.style.display = 'none';
            });

            filtered.forEach((card, i) => {
                const onPage = Math.floor(i / LOAN_GRID_PAGE_SIZE) + 1 === loanGridPage;
                card.style.display = onPage ? '' : 'none';
            });

            // ── Empty state ──
            const grid = document.getElementById('loan-select-grid');
            const emptyBlock = document.getElementById('loan-grid-empty');
            const emptyText = document.getElementById('loan-grid-empty-text');
            const paginationBar = document.querySelector('.loan-grid-pagination-bar');

            if (filtered.length === 0) {
                if (grid) grid.style.display = 'none';
                if (paginationBar) paginationBar.style.display = 'none';
                if (emptyBlock) emptyBlock.style.display = 'flex';

                const messages = {
                    all: 'You have no loans yet.',
                    active: 'You have no active loans.',
                    completed: 'You have no completed loans yet.',
                    overdue: 'You have no overdue loans. Great job staying on track!'
                };
                if (emptyText) emptyText.textContent = messages[loanStatusFilter] || 'No loans found.';
            } else {
                if (grid) grid.style.display = '';
                if (paginationBar) paginationBar.style.display = '';
                if (emptyBlock) emptyBlock.style.display = 'none';
            }

            renderLoanGridPagination(loanGridPage, totalPages, filtered.length);
        }

        function renderLoanGridPagination(page, totalPages, total) {
            const countEl = document.getElementById('loan-grid-count');

            if (countEl) {
                const shown = total === 0 ? 0 : Math.min(LOAN_GRID_PAGE_SIZE, total - (page - 1) * LOAN_GRID_PAGE_SIZE);
                countEl.textContent = `Showing ${shown} of ${total} loan${total == 1 ? '' : 's'}`;
            }

            const container = document.getElementById('loan-grid-pg');
            if (!container) return;
            container.innerHTML = '';
            if (totalPages <= 1) return;

            const makeBtn = (label, targetPage, opts = {}) => {
                const btn = document.createElement('button');
                btn.type = 'button';
                btn.className = 'pg-btn' + (opts.active ? ' active' : '');
                btn.innerHTML = label;
                btn.disabled = !!opts.disabled;
                btn.addEventListener('click', () => { loanGridPage = targetPage; paginateLoanGrid(); });
                return btn;
            };

            container.appendChild(makeBtn('<i class="fa fa-chevron-left"></i>', page - 1, { disabled: page === 1 }));

            const addEllipsis = () => {
                const span = document.createElement('span');
                span.className = 'pg-ellipsis';
                span.textContent = '…';
                container.appendChild(span);
            };

            const pages = new Set([1, totalPages, page, page - 1, page + 1]);
            let prev = 0;
            Array.from(pages).filter(p => p >= 1 && p <= totalPages).sort((a, b) => a - b).forEach(p => {
                if (prev && p - prev > 1) addEllipsis();
                container.appendChild(makeBtn(String(p), p, { active: p === page }));
                prev = p;
            });

            container.appendChild(makeBtn('<i class="fa fa-chevron-right"></i>', page + 1, { disabled: page === totalPages }));
        }

        document.getElementById('loan-search')?.addEventListener('input', applyLoanFilters);
        document.getElementById('loan-type-filter')?.addEventListener('change', applyLoanFilters);
        document.getElementById('loan-date-filter')?.addEventListener('change', applyLoanFilters);

        document.addEventListener('DOMContentLoaded', applyLoanFilters);
    </script>

    <script>
        AOS.init();

        // Real values from controller
        const MONTHLY_AMOUNT = {{ $monthlyDue ?? 0 }};
        const FULL_BALANCE = {{ $fullBalanceRemaining ?? 0 }};
        // IMPORTANT: this is the penalty tied to the installment that is
        // ACTUALLY overdue right now — 0 whenever nothing is overdue. It is
        // NOT $penaltyAmount (that's a lifetime running total that never
        // resets, and was incorrectly leaking into Upcoming installments'
        // payment amounts before this fix).
        const PENALTY_PREVIEW = {{ $currentOverduePenalty ?? 0 }};

        // Tracks whichever Payment Schedule row the member last clicked, so
        // the "Make a Payment" button can react to it. Starts null (nothing
        // selected yet) — in that state Make a Payment behaves as before.
        let selectedRowStatus = null;
        let selectedRowNumber = null;

        // Only ever include a penalty amount when it's genuinely tied to an
        // overdue installment — never for Upcoming/Active/Paid rows, and
        // never when nothing is actually overdue right now (PENALTY_PREVIEW
        // itself will just be 0 in that case).
        function getPenaltyForSelection() {
            if (selectedRowStatus === 'upcoming' || selectedRowStatus === 'active' || selectedRowStatus === 'paid') {
                return 0;
            }
            // selectedRowStatus is 'overdue', or null (no row explicitly
            // selected yet — falls back to whatever is genuinely due now).
            return PENALTY_PREVIEW;
        }

        function handlePaymentTypeChange(type) {
            const input = document.getElementById('repay-amount-input');
            input.value = (type === 'full')
                ? FULL_BALANCE
                : (MONTHLY_AMOUNT + getPenaltyForSelection()).toFixed(2);
        }

        function handleMethodChange(method) {
            const isGcash = method === 'GCash';
            document.getElementById('gcash-section').style.display = isGcash ? 'block' : 'none';
            document.getElementById('gcash-proof-input').required = isGcash;
        }

        document.getElementById('gcash-proof-input').addEventListener('change', function () {
            if (this.files && this.files[0]) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    document.getElementById('gcash-proof-preview-img').src = e.target.result;
                    document.getElementById('gcash-proof-preview').style.display = 'block';
                };
                reader.readAsDataURL(this.files[0]);
            }
        });

        // Defensive fallback only — the button itself gets disabled in
        // selectScheduleRow() the moment a Paid row is picked, so this
        // shouldn't normally even be reachable while disabled.
        function handleMakePaymentClick(type = 'monthly') {
            if (selectedRowStatus === 'paid') {
                return;
            }
            openRepayModal(type);
        }

        function openRepayModal(type = 'monthly') {
            activeModalId = 'repayModal';
            document.getElementById('payment-type-select').value = type;
            document.getElementById('repay-amount-input').value = (type === 'full')
                ? FULL_BALANCE
                : (MONTHLY_AMOUNT + getPenaltyForSelection()).toFixed(2);
            document.getElementById('repay-method').value = 'Cash';
            document.getElementById('gcash-section').style.display = 'none';
            document.getElementById('confirm-pay-btn').style.display = 'flex';
            document.getElementById('ref-no-section').style.display = 'block';
            document.getElementById('notes-section').style.display = 'block';

            document.querySelector('#ref-no-section input').value = '';
            document.querySelector('#notes-section textarea').value = '';

            const modal = document.getElementById('repayModal');
            const backdrop = document.getElementById('repay-backdrop');

            modal.style.display = 'block';
            backdrop.style.display = 'block';
            document.body.classList.add('modal-open');

            void modal.offsetWidth;

            modal.classList.add('show');
            backdrop.classList.add('show');
        }

        function selectScheduleRow(el) {
            document.querySelectorAll('.schedule-row').forEach(r => r.classList.remove('row-selected'));
            el.classList.add('row-selected');

            const number = el.dataset.number;
            const date = el.dataset.date;
            const status = el.dataset.status;
            const amount = parseFloat(el.dataset.amount || 0);

            selectedRowStatus = status;
            selectedRowNumber = number;

            // Keep the "Monthly Due" hero box in sync with whichever schedule row
            // was clicked — it should always show exactly what that row's total is
            // (base installment + penalty, if any), matching the Payment Schedule
            // list and the repayment modal's prefilled amount.
            const monthlyDueEl = document.getElementById('monthly-due-value');
            if (monthlyDueEl) {
                monthlyDueEl.textContent = `₱${amount.toFixed(2)}`;
            }

            // Disable "Make a Payment" outright the moment a Paid row is
            // selected — no need for a click + alert. Re-enable for any
            // other status, as long as the loan itself still has a balance
            // to pay (that base disabled state is rendered server-side).
            const payBtn = document.querySelector('.payment-button button');
            if (payBtn && FULL_BALANCE > 0) {
                if (status === 'paid') {
                    payBtn.disabled = true;
                    payBtn.style.opacity = '.5';
                    payBtn.style.cursor = 'not-allowed';
                } else {
                    payBtn.disabled = false;
                    payBtn.style.opacity = '';
                    payBtn.style.cursor = '';
                }
            }

            const nextDueStat = document.getElementById('next-due-stat');
            if (nextDueStat) {
                const statusLabels = {
                    paid: 'Already paid',
                    overdue: 'Overdue',
                    active: 'Upcoming — due next',
                    upcoming: 'Upcoming'
                };

                const statSub = nextDueStat.querySelector('.stat-sub');
                statSub.textContent = `Installment #${number} · ${statusLabels[status] || ''}`;

                statSub.className = 'stat-sub';
                if (status === 'overdue') {
                    statSub.classList.add('badge-overdue');
                } else if (status === 'active' || status === 'upcoming') {
                    statSub.classList.add('badge-upcoming');
                }

                nextDueStat.querySelector('.stat-value').textContent = date;
            }

            // Penalty card only shows an actual amount when the SELECTED row is overdue.
            const penaltyBox = document.getElementById('penalty-box');
            if (penaltyBox) {
                const penaltyValue = penaltyBox.querySelector('.penalty-value');
                const penaltyCaption = penaltyBox.querySelector('.penalty-caption');

                if (status === 'overdue') {
                    penaltyValue.textContent = `₱${PENALTY_PREVIEW.toFixed(2)}`;
                    penaltyValue.style.color = 'var(--coral)';
                    penaltyCaption.textContent = `Applies to installment #${number} — overdue`;
                } else if (status === 'paid') {
                    penaltyValue.textContent = '₱0.00';
                    penaltyValue.style.color = '';
                    penaltyCaption.textContent = `Installment #${number} — already paid, no penalty`;
                } else {
                    penaltyValue.textContent = '₱0.00';
                    penaltyValue.style.color = '';
                    penaltyCaption.textContent = `Installment #${number} — not yet due, no penalty`;
                }
            }

            document.getElementById('loan-hero-section')?.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }

        function closeRepayModal() {
            const modal = document.getElementById('repayModal');
            const backdrop = document.getElementById('repay-backdrop');

            modal.classList.remove('show');
            backdrop.classList.remove('show');

            setTimeout(() => {
                modal.style.display = 'none';
                backdrop.style.display = 'none';
                document.body.classList.remove('modal-open');
            }, 250);

            activeModalId = null;
        }

        let activeModalId = null;

        function openModal(modalId) {
            // Only meaningful on the collapsed (mobile/tablet) breakpoint —
            // matches the 880px max-width media query that hides the inline body.
            if (window.innerWidth > 880) return;

            const modal = document.getElementById(modalId);
            const backdrop = document.getElementById('repay-backdrop');
            if (!modal || !backdrop) return;

            activeModalId = modalId;

            modal.style.display = 'block';
            backdrop.style.display = 'block';
            document.body.classList.add('modal-open');

            void modal.offsetWidth; // force reflow so the transition plays

            modal.classList.add('show');
            backdrop.classList.add('show');
        }

        function closeModal(modalId) {
            const modal = document.getElementById(modalId);
            const backdrop = document.getElementById('repay-backdrop');
            if (!modal || !backdrop) return;

            modal.classList.remove('show');
            backdrop.classList.remove('show');

            setTimeout(() => {
                modal.style.display = 'none';
                backdrop.style.display = 'none';
                document.body.classList.remove('modal-open');
            }, 250);

            activeModalId = null;
        }

        function openScheduleModal() { openModal('scheduleModal'); }
        function openChargesModal() { openModal('chargesModal'); }

        // Click-outside-to-close for both new modals
        document.getElementById('scheduleModal')?.addEventListener('click', function (e) {
            if (e.target === this) closeModal('scheduleModal');
        });
        document.getElementById('chargesModal')?.addEventListener('click', function (e) {
            if (e.target === this) closeModal('chargesModal');
        });

        document.getElementById('repay-backdrop').addEventListener('click', function () {
            if (activeModalId === 'repayModal') {
                closeRepayModal();
            } else if (activeModalId) {
                closeModal(activeModalId);
            }
        });

        document.getElementById('repayModal').addEventListener('click', function (e) {
            if (e.target === this) closeRepayModal();
        });

        document.getElementById('confirm-pay-btn').addEventListener('click', function () {
            const amount = document.getElementById('repay-amount-input').value;
            const method = document.getElementById('repay-method').value;
            const type = document.getElementById('payment-type-select').value;
            const ref = document.querySelector('#ref-no-section input').value;
            const notes = document.querySelector('#notes-section textarea').value;

            document.getElementById('form-amount-paid').value = amount;
            document.getElementById('form-payment-method').value = method;
            document.getElementById('form-payment-type').value = type;
            document.getElementById('form-reference-no').value = ref;
            document.getElementById('form-notes').value = notes;

            document.getElementById('repay-form').submit();
        });
    </script>


</body>

</html>