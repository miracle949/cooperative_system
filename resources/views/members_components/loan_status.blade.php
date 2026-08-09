<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
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
    </style>
</head>

<body>

    <div class="container-fluid p-0 m-0">
        @include("components.offcanvas")

        @include("components.sidebar")

        <div class="rightbar">
            @include("components.navbar2")

            <main>
                <div class="parent-main">
                    <div class="header-main">
                        <h3>Loan Repayments</h3>
                        <p>Manage your loan repayments by tracking payment history, upcoming due dates, and outstanding balances.</p>
                    </div>

                    <div class="parent-header">
                        <div class="filter-parent">

                            <div class="search-parent">
                                <i class="fa fa-search"></i>
                                <input type="search" id="loan-search" placeholder="Search by reference or type of loan">
                            </div>

                            <div class="loan-type">
                                <select id="loan-type-filter" class="form-select">
                                    <option value="" disabled {{ !$selectedLoan ? 'selected' : '' }}>-- Select Loan Type --</option>
                                    @foreach($loans->pluck('display_type')->unique() as $type)
                                        <option value="{{ $type }}"
                                            {{ $selectedLoan && $selectedLoan->display_type === $type ? 'selected' : '' }}>
                                            {{ $type }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="reference">
                                <select id="loan-reference-filter" class="form-select">
                                    <option value="" disabled {{ !$selectedLoan ? 'selected' : '' }}>-- Select Reference --</option>
                                    @foreach($loans as $loan)
                                        <option value="{{ $loan->reference_no }}"
                                            data-id="{{ $loan->id }}"
                                            data-type="{{ $loan->display_type }}"
                                            {{ $selectedLoan && $selectedLoan->id === $loan->id ? 'selected' : '' }}
                                            {{ (!$selectedLoan || $selectedLoan->display_type !== $loan->display_type) ? 'hidden' : '' }}>
                                            {{ $loan->reference_no }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    @if($loans->isEmpty())
                        <!-- <div class="loan-hero" style="display:flex;align-items:center;justify-content:center;padding:40px;">
                            <p style="color:var(--teal);margin:0;">You have no approved loans yet.</p>
                        </div> -->
                        <div class="no-approved-loans" style=" ">
                            <div style="width:56px;height:56px;border-radius:50%;background:#eef2fa;display:flex;align-items:center;justify-content:center;margin-bottom:1rem;">
                                <i class="fa fa-hourglass-half" style="color:var(--blue,#2b3a67);font-size:22px;"></i>
                            </div>
                            <p style="color:var(--muted);margin:0; font-size: 14px;">You have no approved loans yet.</p>
                        </div>
                    @elseif(!$selectedLoan)
                        <div class="no-selected-loans" style=" ">
                            <div style="width:56px;height:56px;border-radius:50%;background:#eef2fa;display:flex;align-items:center;justify-content:center;margin-bottom:1rem;">
                                <i class="fa fa-hand-pointer" style="color:var(--blue,#2b3a67);font-size:22px;"></i>
                            </div>
                            <h5 style="color:#ffffff;margin:0 0 10px;font-weight:600;">No loan selected</h5>
                            <p style="color:#8a8f98;margin:0;max-width:360px;font-size:14px;line-height:1.5;">
                                Choose a <strong>Loan Type</strong> above first, then pick the matching
                                <strong>Reference</strong> number to view its repayment details.
                            </p>
                        </div>
                    @else
                        {{-- HERO --}}
                        <div class="loan-hero">
                            <div class="loan-hero-parent">
                                <div class="left-hero">
                                    <div class="left-text">
                                        <div class="status">Active Loan</div>
                                        <h3>{{ $selectedLoan->display_type }}</h3>
                                        <p><b>{{ $selectedLoan->reference_no }}</b> · Active
                                            {{ \Carbon\Carbon::parse($selectedLoan->created_at)->format('F d, Y') }}
                                        </p>
                                    </div>
                                    <div class="payment-button">
                                        <button onclick="openRepayModal('monthly')" {{ $fullBalanceRemaining <= 0 ? 'disabled style=opacity:.5;cursor:not-allowed;' : '' }}>
                                            <i class="fa fa-peso-sign"></i>
                                            <span>Make a Payment</span>
                                        </button>
                                        <a href="#">View Full Schedule</a>
                                    </div>
                                </div>
                                <div class="right-hero">
                                    <div class="alh-parent">
                                        <div class="alh-stat">
                                            <span>Monthly Due</span>
                                            <h5>₱{{ number_format($monthlyDue, 2) }}</h5>
                                            <p>Every {{ \Carbon\Carbon::parse($selectedLoan->created_at)->format('jS') }}</p>
                                        </div>
                                        <div class="alh-stat">
                                            <span>Next Due</span>
                                            <h5>{{ $nextDueDate ? $nextDueDate->format('F d') : '—' }}</h5>
                                            <p>
                                                @if($daysAway === null) Fully paid
                                                @elseif($daysAway < 0) {{ abs($daysAway) }} days overdue
                                                @elseif($daysAway === 0) Due today
                                                @else {{ $daysAway }} days away
                                                @endif
                                            </p>
                                        </div>
                                        <div class="alh-stat">
                                            <span>Balance</span>
                                            <h5>₱{{ number_format($fullBalanceRemaining, 2) }}</h5>
                                            <p>Remaining</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="parent-progress">
                                <div class="progress-header">
                                    <p>Repayment Progress</p>
                                    <span>{{ $lendingStatus->payments_made ?? 0 }} of
                                        {{ $lendingStatus->total_payments ?? 0 }} payments made</span>
                                </div>
                                <div class="progress-body">
                                    <div class="progress-sub">
                                        <div class="progress" style="width: {{ $progressPercent }}%;"></div>
                                    </div>
                                </div>
                                <p>₱{{ number_format($remainingPrincipal, 0) }} remaining of
                                    ₱{{ number_format($selectedLoan->lending_amount, 0) }} principal</p>
                            </div>
                        </div>

                        {{-- 3 SUMMARY BOXES --}}
                        <div class="loan-parent-box">
                            <div class="loan-box">
                                <div class="loan-header">
                                    <h5>Principal Amount</h5>
                                    <div class="loan-icon"><i class="fa fa-file-lines"></i></div>
                                </div>
                                <p>₱{{ number_format($selectedLoan->lending_amount, 2) }}</p>
                                <span>Applied
                                    {{ \Carbon\Carbon::parse($selectedLoan->created_at)->format('F d, Y') }}</span>
                            </div>
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
                                <span>Principal + interest</span>
                            </div>
                            <div class="loan-box">
                                <div class="loan-header">
                                    <h5>Remaining Balance</h5>
                                    <div class="loan-icon"><i class="fa fa-triangle-exclamation"></i></div>
                                </div>
                                <p>₱{{ number_format($fullBalanceRemaining, 2) }}</p>
                                <span>{{ $monthsRemaining }} payment{{ $monthsRemaining == 1 ? '' : 's' }} remaining</span>
                            </div>
                        </div>

                        {{-- SCHEDULE & CHARGES --}}
                        <div class="schedule-charges">
                            <div class="schedule-parent">
                                <div class="schedule-header">
                                    <div class="header-tag">Payment Schedule</div>
                                    <span>{{ $lendingStatus->payments_made ?? 0 }} of
                                        {{ $lendingStatus->total_payments ?? 0 }} paid</span>
                                </div>
                                <div class="schedule-body">
                                    @forelse($paymentSchedule as $row)
                                        <div class="pay-item">
                                            <div class="item"><span>#{{ $row['number'] }}</span>
                                                <p>{{ $row['date'] }}</p>
                                            </div>
                                            <div class="item-amount">
                                                <p>₱{{ number_format($row['amount'], 2) }}</p>
                                                @if($row['paid'])
                                                <p><i class="fa fa-check"></i> Paid</p>@endif
                                            </div>
                                        </div>
                                    @empty
                                        <p style="color:#999;padding:1rem;">No schedule available.</p>
                                    @endforelse
                                </div>
                            </div>
                            <div class="charges-parent">
                                <div class="charges-header">
                                    <div class="header-tag">Loan Charges</div>
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
                                    <div class="total-charges">
                                        <span>Total Charges</span>
                                        <p>₱{{ number_format($totalInterest + $processingFee + $serviceFee + $loanProtectionFee + $retentionFee, 2) }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- PAYMENT HISTORY --}}
                        <div class="loan-history">
                            <div class="loan-header">
                                <div class="header-tag">Payment History</div>
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
                                                <th>Method</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($paymentHistory as $payment)
                                                <tr>
                                                    <td>{{ $payment->reference_no }}</td>
                                                    <td>{{ \Carbon\Carbon::parse($payment->payment_date)->format('M d, Y') }}
                                                    </td>
                                                    <td>{{ \Carbon\Carbon::parse($payment->created_at)->format('h:i A') }}</td>
                                                    <td>₱{{ number_format($payment->amount_paid, 2) }}</td>
                                                    <td>{{ $payment->payment_method }}</td>
                                                    <td>Paid</td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="6" style="text-align:center;color:#999;padding:1.5rem;">No
                                                        payment history yet.</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </main>
        </div>

        {{-- REPAYMENT MODAL --}}
        <div class="modal fade" id="repayModal" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered" style="max-width: 480px;">
                <div class="modal-content"
                    style="border: none; border-radius: 16px; overflow: hidden; box-shadow: 0 20px 60px rgba(0,0,0,0.15);">

                    {{-- Modal Header --}}
                    <div class="modal-header" style="background: var(--blue); padding: 1.4rem 1.6rem; border: none;">
                        <div>
                            <h5 class="modal-title" style="color: #fff; font-size: 17px; font-weight: 600; margin: 0;">
                                Make a Payment
                            </h5>
                            <p style="color: rgba(255,255,255,0.6); font-size: 12px; margin: 2px 0 0;">
                                {{ $selectedLoan->display_type ?? '' }} —
                                ₱{{ number_format($selectedLoan->lending_amount ?? 0, 2) }}
                            </p>
                        </div>
                        <button type="button" class="btn-close btn-close-white" onclick="closeRepayModal()"></button>
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

                        <form action="{{ route('repayment.store') }}" method="POST" id="cash-repay-form">
                            @csrf
                            <input type="hidden" name="lending_id" value="{{ $selectedLoan->id ?? '' }}">
                            <input type="hidden" name="member_id" value="{{ auth()->id() }}">
                            <input type="hidden" name="payment_number" value="{{ ($lendingStatus->payments_made ?? 0) + 1 }}">
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
                                <input type="number" id="repay-amount-input" class="form-control"
                                    value="{{ $monthlyDue }}"
                                    style="padding-left: 28px; border-radius: 10px; border: 1.5px solid #e0e0e0; font-size: 15px; font-weight: 500; color: var(--teal); height: 46px;"
                                    readonly>
                            </div>
                        </div>

                        {{-- Payment Method --}}
                        <div style="margin-bottom: 1.1rem;">
                            <label
                                style="font-size: 12px; font-weight: 600; color: #888; text-transform: uppercase; letter-spacing: 0.5px; display: block; margin-bottom: 6px;">
                                Payment Method
                            </label>
                            <select id="repay-method" class="form-select" onchange="handleMethodChange(this.value)"
                                style="border-radius: 10px; border: 1.5px solid #e0e0e0; height: 46px; font-size: 14px; color: #333;">
                                <option value="Cash">Cash</option>
                                <option value="GCash">GCash</option>
                            </select>
                        </div>

                        {{-- Reference No --}}
                        <div id="ref-no-section" style="margin-bottom: 1.1rem;">
                            <label
                                style="font-size: 12px; font-weight: 600; color: #888; text-transform: uppercase; letter-spacing: 0.5px; display: block; margin-bottom: 6px;">
                                Reference / Receipt No.
                                <span
                                    style="color: #aaa; font-weight: 400; font-size: 11px; text-transform: none;">(optional
                                    — auto-generated if blank)</span>
                            </label>
                            <input type="text" id="repay-reference-input" class="form-control"
                                placeholder="Leave blank to auto-generate"
                                style="border-radius: 10px; border: 1.5px solid #e0e0e0; height: 46px; font-size: 14px; color: #333;">
                        </div>

                        {{-- Notes --}}
                        <div id="notes-section" style="margin-bottom: 0.5rem;">
                            <label
                                style="font-size: 12px; font-weight: 600; color: #888; text-transform: uppercase; letter-spacing: 0.5px; display: block; margin-bottom: 6px;">
                                Notes (optional)
                            </label>
                            <textarea id="repay-notes-input" class="form-control" rows="2"
                                placeholder="Additional remarks..."
                                style="border-radius: 10px; border: 1.5px solid #e0e0e0; font-size: 14px; color: #333; resize: none;"></textarea>
                        </div>

                        {{-- GCash section --}}
                        <div id="gcash-section" style="display: none;">
                            <div style="border-top: 1.5px dashed #e8e8e8; margin: 1.2rem 0;"></div>
                            <div
                                style="background: linear-gradient(135deg, #f0f7ff 0%, #e8f4ff 100%); border: 1.5px solid #c2deff; border-radius: 12px; padding: 1rem 1.2rem;">
                                <div
                                    style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 10px;">
                                    <div style="display: flex; align-items: center; gap: 10px;">
                                        <div
                                            style="width: 40px; height: 40px; background: #007DFF; border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                                            <i class="fa-solid fa-mobile-screen-button"
                                                style="color: white; font-size: 18px;"></i>
                                        </div>
                                        <div>
                                            <p style="margin: 0; font-size: 14px; font-weight: 700; color: #0056b3;">Pay
                                                via GCash</p>
                                            <p style="margin: 0; font-size: 11px; color: #5a8ac4;">Fast & secure online
                                                payment</p>
                                        </div>
                                    </div>
                                    <button type="button"
                                        style="background: #007DFF; color: white; border: none; border-radius: 8px; padding: 8px 18px; font-size: 13px; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 6px;">
                                        <i class="fa-solid fa-arrow-right" style="font-size: 12px;"></i>
                                        Pay Now
                                    </button>
                                </div>
                            </div>
                        </div>

                    </div>

                    {{-- Modal Footer --}}
                    <div class="modal-footer"
                        style="background: #f8f9fa; border-top: 1px solid rgba(0,0,0,0.1); padding: 1rem 1.6rem; display: flex; justify-content: center; align-items: center; flex-direction: column; gap: 8px;">
                        <button type="submit" id="confirm-pay-btn" class="btn w-100"
                            style="background: var(--blue); color: white; border-radius: 8px; font-size: 13px; font-weight: 600; padding: 10px 22px; border: none; display: flex; align-items: center; gap: 6px; justify-content: center;">
                            <i class="fa-solid fa-check" style="font-size: 12px;"></i>
                            Confirm Payment
                        </button>
                        <button type="button" class="btn w-100 text-center" onclick="closeRepayModal()"
                            style="border-radius: 8px; font-size: 13px; padding: 10px 18px; background: #e0e0e0; border: none; color: #555;">
                            Cancel
                        </button>
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

    </div>

    {{-- AOS animation link js --}}
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>

    <script>
        function navigateToLoan(loanId) {
            if (!loanId) return;
            const url = new URL(window.location.href);
            url.searchParams.set('loan_id', loanId);
            window.location.href = url.toString();
        }

        function applyLoanFilters() {
            const search = document.getElementById('loan-search').value.toLowerCase().trim();
            const type = document.getElementById('loan-type-filter').value;
            const refSelect = document.getElementById('loan-reference-filter');

            // Only filter which references are shown — never auto-select or navigate.
            Array.from(refSelect.options).forEach(opt => {
                if (!opt.value) return; // skip the placeholder option

                if (!type) {
                    // No loan type chosen yet — no reference should be visible.
                    opt.hidden = true;
                    return;
                }

                const optType = opt.dataset.type || '';
                const matchesType = optType === type;
                const matchesSearch = !search || opt.value.toLowerCase().includes(search);
                opt.hidden = !(matchesType && matchesSearch);
            });

            // If the currently selected reference no longer matches the type/search,
            // reset the dropdown back to the placeholder rather than showing a stale pick.
            const selectedOption = refSelect.options[refSelect.selectedIndex];
            if (selectedOption && selectedOption.hidden) {
                refSelect.value = '';
            }
        }

        document.getElementById('loan-search').addEventListener('input', applyLoanFilters);
        document.getElementById('loan-type-filter').addEventListener('change', applyLoanFilters);

        // Loan only loads once the person explicitly picks a reference.
        document.getElementById('loan-reference-filter').addEventListener('change', function () {
            const opt = this.options[this.selectedIndex];
            if (opt && opt.dataset.id) navigateToLoan(opt.dataset.id);
        });

        // Keep the reference list correctly filtered on initial page load
        document.addEventListener('DOMContentLoaded', applyLoanFilters);
    </script>

    <script>
        AOS.init();

        // Real values from controller
        const MONTHLY_AMOUNT = {{ $monthlyDue ?? 0 }};
        const FULL_BALANCE = {{ $fullBalanceRemaining ?? 0 }};

        function handlePaymentTypeChange(type) {
            const input = document.getElementById('repay-amount-input');
            input.value = (type === 'full') ? FULL_BALANCE : MONTHLY_AMOUNT;
        }

        function handleMethodChange(method) {
            const isGcash = method === 'GCash';
            document.getElementById('gcash-section').style.display = isGcash ? 'block' : 'none';
            document.getElementById('confirm-pay-btn').style.display = isGcash ? 'none' : 'flex';
            document.getElementById('ref-no-section').style.display = isGcash ? 'none' : 'block';
            document.getElementById('notes-section').style.display = isGcash ? 'none' : 'block';
        }

        function openRepayModal(type = 'monthly') {
            // Reset to defaults (or requested type)
            document.getElementById('payment-type-select').value = type;
            document.getElementById('repay-amount-input').value = (type === 'full') ? FULL_BALANCE : MONTHLY_AMOUNT;
            document.getElementById('repay-method').value = 'Cash';
            document.getElementById('gcash-section').style.display = 'none';
            document.getElementById('confirm-pay-btn').style.display = 'flex';
            document.getElementById('ref-no-section').style.display = 'block';
            document.getElementById('notes-section').style.display = 'block';

            // Clear ref/notes inputs from any previous open
            document.querySelector('#ref-no-section input').value = '';
            document.querySelector('#notes-section textarea').value = '';

            const modal = document.getElementById('repayModal');
            const backdrop = document.getElementById('repay-backdrop');

            modal.style.display = 'block';
            backdrop.style.display = 'block';
            document.body.classList.add('modal-open');

            // Force a reflow so the transition actually plays instead of
            // jumping straight to the "show" state.
            void modal.offsetWidth;

            modal.classList.add('show');
            backdrop.classList.add('show');
        }

        function closeRepayModal() {
            const modal = document.getElementById('repayModal');
            const backdrop = document.getElementById('repay-backdrop');

            modal.classList.remove('show');
            backdrop.classList.remove('show');

            // Wait for the fade/scale-out transition to finish before hiding
            setTimeout(() => {
                modal.style.display = 'none';
                backdrop.style.display = 'none';
                document.body.classList.remove('modal-open');
            }, 250);
        }

        // Close when clicking the dimmed backdrop
        document.getElementById('repay-backdrop').addEventListener('click', closeRepayModal);

        // Close when clicking outside the dialog card (the modal wrapper itself
        // sits on top of the backdrop and was swallowing the click before —
        // this listener catches it directly).
        document.getElementById('repayModal').addEventListener('click', function (e) {
            if (e.target === this) closeRepayModal();
        });

        // Submit payment to controller
        document.getElementById('confirm-pay-btn').addEventListener('click', function () {
            const amount = document.getElementById('repay-amount-input').value;
            const method = document.getElementById('repay-method').value;
            const type = document.getElementById('payment-type-select').value; // 'monthly' or 'full'
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