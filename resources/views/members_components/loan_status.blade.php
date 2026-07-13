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
</head>

<body>

    <div class="container-fluid p-0 m-0">
        @include("components.offcanvas")

        @include("components.sidebar")

        <div class="rightbar">
            @include("components.navbar2")

            <main>
                <div class="parent-main">
                    {{-- <div class="main-header">
                        <div class="main-badge">
                            <a href="#">Home</a>
                            <span>></span>
                            <span>Loan Status</span>
                        </div>
                        <h2>Loan Status & History</h2>
                        <p>Track your active loan, view payment history, and manage documents.</p>
                    </div>

                    <h4>Filter transaction:</h4> --}}

                    <h3>Loan Repayments <span>- history & status</span></h3>

                    <div class="parent-header">
                        <div class="filter-parent">

                            <div class="search-parent">
                                <i class="fa fa-search"></i>
                                <input type="search" id="loan-search" placeholder="Search by reference or type of loan">
                            </div>

                            <div class="loan-type">
                                <select id="loan-type-filter" class="form-select">
                                    <option value="" selected>All loan types</option>
                                    @foreach($loans->pluck('display_type')->unique() as $type)
                                        <option value="{{ $type }}">{{ $type }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="reference">
                                <select id="loan-reference-filter" class="form-select">
                                    <option value="" selected>All references</option>
                                    @foreach($loans as $loan)
                                        <option value="{{ $loan->reference_no }}" {{ $selectedLoan && $selectedLoan->id === $loan->id ? 'selected' : '' }}>
                                            {{ $loan->reference_no }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    @if($loans->isEmpty())
                        <div class="loan-hero" style="display:flex;align-items:center;justify-content:center;padding:40px;">
                            <p style="color:var(--teal);margin:0;">You have no approved loans yet.</p>
                        </div>
                    @else
                        @foreach($loans as $loan)
                            @php
                                $isSelected = $selectedLoan && $selectedLoan->id === $loan->id;
                            @endphp

                            {{-- HERO --}}
                            <div class="loan-hero loan-record" data-reference="{{ $loan->reference_no }}"
                                data-type="{{ $loan->display_type }}" style="{{ $isSelected ? '' : 'display:none;' }}">
                                <div class="left-hero">
                                    <span>Active Loan</span>
                                    <h3>{{ $loan->display_type }}</h3>
                                    <p style="color: #808080; font-weight: 500; margin-top: 0.3rem;">
                                        {{ $loan->reference_no }} · Active
                                        {{ \Carbon\Carbon::parse($loan->created_at)->format('F d, Y') }}
                                    </p>

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
                                            ₱{{ number_format($loan->lending_amount, 0) }} principal</p>
                                    </div>
                                </div>

                                <div class="right-hero">
                                    <div class="alh-parent">
                                        <div class="alh-stat">
                                            <span>Monthly Due</span>
                                            <h5>₱{{ number_format($monthlyDue, 2) }}</h5>
                                            <p>Every {{ \Carbon\Carbon::parse($loan->created_at)->format('jS') }}</p>
                                        </div>
                                        <div class="alh-stat">
                                            <span>Next Due</span>
                                            <h5>{{ $nextDueDate ? $nextDueDate->format('F d') : '—' }}</h5>
                                            <p>
                                                @if($daysAway === null)
                                                    Fully paid
                                                @elseif($daysAway < 0)
                                                    {{ abs($daysAway) }} days overdue
                                                @elseif($daysAway === 0)
                                                    Due today
                                                @else
                                                    {{ $daysAway }} days away
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

                            {{-- LOAN SETTLEMENT --}}
                            <div class="payment-parent loan-record" data-reference="{{ $loan->reference_no }}"
                                data-type="{{ $loan->display_type }}" style="{{ $isSelected ? '' : 'display:none;' }}">
                                <div class="payment-text">
                                    <h5>Loan Settlement</h5>
                                    <p>Pay the entire remaining balance</p>
                                    <span>Remaining: <b>₱{{ number_format($fullBalanceRemaining, 2) }}</b> ·
                                        {{ $monthsRemaining }} months remaining</span>
                                </div>
                                <div class="payment-button">
                                    <button onclick="openRepayModal('monthly')" {{ $fullBalanceRemaining <= 0 ? 'disabled style=opacity:.5;cursor:not-allowed;' : '' }}>
                                        Make a Payment
                                    </button>
                                </div>
                            </div>

                            {{-- 3 SUMMARY BOXES --}}
                            <div class="loan-parent-box loan-record" data-reference="{{ $loan->reference_no }}"
                                data-type="{{ $loan->display_type }}" style="{{ $isSelected ? '' : 'display:none;' }}">
                                <div class="loan-box">
                                    <h5>Principal Amount</h5>
                                    <p>₱{{ number_format($loan->lending_amount, 2) }}</p>
                                    <span>Applied {{ \Carbon\Carbon::parse($loan->created_at)->format('F d, Y') }}</span>
                                </div>

                                <div class="loan-box">
                                    <h5>Total Interest</h5>
                                    <p>₱{{ number_format($totalInterest, 2) }}</p>
                                    <span>Applied {{ \Carbon\Carbon::parse($loan->created_at)->format('F d, Y') }}</span>
                                </div>

                                <div class="loan-box">
                                    <h5>Total Payable</h5>
                                    <p>₱{{ number_format($loan->total_payment ?? 0, 2) }}</p>
                                    <span>Applied {{ \Carbon\Carbon::parse($loan->created_at)->format('F d, Y') }}</span>
                                </div>
                            </div>

                            {{-- SCHEDULE & CHARGES --}}
                            <div class="schedule-charges loan-record" data-reference="{{ $loan->reference_no }}"
                                data-type="{{ $loan->display_type }}" style="{{ $isSelected ? '' : 'display:none;' }}">
                                <div class="schedule-parent">
                                    <div class="schedule-header">
                                        <div class="header-tag">Payment Schedule</div>
                                        <span>{{ $lendingStatus->payments_made ?? 0 }} of
                                            {{ $lendingStatus->total_payments ?? 0 }} paid</span>
                                    </div>
                                    <div class="schedule-body">
                                        @forelse($paymentSchedule as $row)
                                            <div class="pay-item">
                                                <div class="item">
                                                    <span>#{{ $row['number'] }}</span>
                                                    <p>{{ $row['date'] }}</p>
                                                </div>
                                                <div class="item-amount">
                                                    <p>₱{{ number_format($row['amount'], 2) }}</p>
                                                    @if($row['paid'])
                                                        <p>
                                                            <i class="fa fa-check"></i>
                                                            Paid
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
                                    <div class="charges-header">
                                        <div class="header-tag">Loan Charges</div>
                                    </div>
                                    <div class="charges-body">
                                        <div class="pay-item">
                                            <div class="parent-item">
                                                <div class="item">
                                                    <div class="icon">
                                                        <i class="fa fa-coins"></i>
                                                    </div>
                                                    <div>
                                                        <span>Interest Rate</span>
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
                                                    <div class="icon">
                                                        <i class="fa fa-receipt"></i>
                                                    </div>
                                                    <div>
                                                        <span>Total Interest</span>
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
                                                    <div class="icon">
                                                        <i class="fa fa-calculator"></i>
                                                    </div>
                                                    <div>
                                                        <span>Service Fee</span>
                                                        <p>1% one-time fee</p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="item-amount">
                                                <p>₱{{ number_format($serviceFee, 2) }}</p>
                                            </div>
                                        </div>

                                        <div class="total-charges">
                                            <span>Total Charges</span>

                                            <p>₱1,950.00</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- PAYMENT HISTORY --}}
                            <div class="loan-history loan-record" data-reference="{{ $loan->reference_no }}"
                                data-type="{{ $loan->display_type }}" style="{{ $isSelected ? '' : 'display:none;' }}">
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
                                                @forelse($isSelected ? $paymentHistory : [] as $payment)
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

                        @endforeach
                    @endif

                    {{-- <div class="status-alert">
                        <i class="fa fa-check-circle"></i>
                        <div class="status-alert-text">
                            Your first payment of <strong>₱2,083.33</strong> was received on <strong>May 15,
                                2026</strong>. Your account is in good standing. Next payment due <strong>June 15,
                                2026</strong>.
                        </div>
                    </div> --}}

                    {{-- <div class="payment-breakdown">
                        <div class="payment">
                            <div class="payment-header">
                                <h3>Payment Schedule</h3>

                                <span>1 of 12 paid</span>
                            </div>

                            <table>

                            </table>
                        </div>
                        <div class="loan">

                        </div>
                    </div> --}}
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
                        <button type="button" id="confirm-pay-btn" class="btn w-100"
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
        // ── Client-side filter for loan type / reference / search ──────────────
        function applyLoanFilters() {
            const search = document.getElementById('loan-search').value.toLowerCase().trim();
            const type = document.getElementById('loan-type-filter').value;
            const reference = document.getElementById('loan-reference-filter').value;

            // Pick which reference to show: explicit dropdown choice wins,
            // otherwise fall back to the first record matching type+search.
            const groups = {};
            document.querySelectorAll('.loan-record').forEach(el => {
                const ref = el.dataset.reference;
                (groups[ref] = groups[ref] || []).push(el);
            });

            let matchedRef = null;

            Object.keys(groups).forEach(ref => {
                const sampleEl = groups[ref][0];
                const elType = sampleEl.dataset.type;
                const haystack = (ref + ' ' + elType).toLowerCase();

                const matchesType = !type || elType === type;
                const matchesRef = !reference || ref === reference;
                const matchesSearch = !search || haystack.includes(search);

                if (matchesType && matchesRef && matchesSearch && !matchedRef) {
                    matchedRef = ref;
                }
            });

            Object.keys(groups).forEach(ref => {
                groups[ref].forEach(el => {
                    el.style.display = (ref === matchedRef) ? '' : 'none';
                });
            });
        }

        document.getElementById('loan-search').addEventListener('input', applyLoanFilters);
        document.getElementById('loan-type-filter').addEventListener('change', applyLoanFilters);
        document.getElementById('loan-reference-filter').addEventListener('change', applyLoanFilters);
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

            document.getElementById('repayModal').style.display = 'block';
            document.getElementById('repayModal').classList.add('show');
            document.getElementById('repay-backdrop').style.display = 'block';
            document.body.classList.add('modal-open');
        }

        function closeRepayModal() {
            document.getElementById('repayModal').style.display = 'none';
            document.getElementById('repayModal').classList.remove('show');
            document.getElementById('repay-backdrop').style.display = 'none';
            document.body.classList.remove('modal-open');
        }

        // Close on backdrop click
        document.getElementById('repay-backdrop').addEventListener('click', closeRepayModal);

        // Submit payment to controller
        document.getElementById('confirm-pay-btn').addEventListener('click', function () {
            const amount = document.getElementById('repay-amount-input').value;
            const method = document.getElementById('repay-method').value;
            const ref = document.querySelector('#ref-no-section input').value;
            const notes = document.querySelector('#notes-section textarea').value;

            document.getElementById('form-amount-paid').value = amount;
            document.getElementById('form-payment-method').value = method;
            document.getElementById('form-reference-no').value = ref;
            document.getElementById('form-notes').value = notes;

            document.getElementById('repay-form').submit();
        });
    </script>


</body>

</html>