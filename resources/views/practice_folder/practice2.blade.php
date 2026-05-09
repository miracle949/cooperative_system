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
        @include("components.navbar2")
        @include("components.offcanvas")

        {{-- SIDEBAR --}}
        <div class="sidebar">
            <div class="sidebar-parent-box">
                @forelse($loans as $loan)
                    <a href="{{ route('LoanStatus', ['loan_id' => $loan->id]) }}"
                        style="text-decoration: none; color: inherit;">

                        <div class="sidebar-box {{ $selectedLoan && $selectedLoan->id == $loan->id ? 'active-sidebar' : '' }}">
                            <div class="sidebar-header">
                                <h4>{{ $loan->lending_type }}</h4>
                                <div class="parent-active">
                                    <div class="dot"></div>
                                    <p>{{ $loan->status }}</p>
                                </div>
                            </div>
                            <div class="sidebar-sub-header">
                                <p>{{ $loan->purpose_loan }}</p>
                                <p>{{ \Carbon\Carbon::parse($loan->created_at)->format('M d, Y') }}</p>
                            </div>
                            <div class="side-body">
                                <h5>₱{{ number_format($loan->lending_amount, 2) }}</h5>
                                @php
                                    $status = \App\Models\lending_status_tbl::where('lending_id', $loan->id)->first();
                                    $pct = $status && $loan->total_payment > 0
                                        ? round(($status->total_paid / $loan->total_payment) * 100)
                                        : 0;
                                    $paid = $status ? $status->payments_made : 0;
                                    $total = $status ? $status->total_payments : 0;
                                @endphp
                                <div class="parent-progress">
                                    <div class="progress-box" style="width: {{ $pct }}%;"></div>
                                </div>
                                <div class="paid">
                                    <p>{{ $pct }}% paid</p>
                                    <p>{{ $paid }}/{{ $total }} payments</p>
                                </div>
                            </div>
                        </div>

                    </a>
                @empty
                    <p style="padding: 1rem; color: gray; font-size: 13px;">No active loans found.</p>
                @endforelse
            </div>
        </div>

        {{-- RIGHTBAR --}}
        <div class="rightbar">
            @if($selectedLoan)
                @if($lendingStatus)

                    {{-- GCash Success Modal --}}
                    @if(session('success'))
                    <div class="modal fade show" id="successModal" tabindex="-1" style="display: block; background: rgba(0,0,0,0.5);">
                        <div class="modal-dialog modal-dialog-centered" style="max-width: 400px;">
                            <div class="modal-content" style="border: none; border-radius: 20px; overflow: hidden; text-align: center;">
                                <div class="modal-body" style="padding: 2.5rem 2rem;">
                                    <div style="width: 80px; height: 80px; background: #e8f8f0; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.2rem;">
                                        <div style="width: 56px; height: 56px; background: #1a4a3a; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                            <i class="fa-solid fa-check" style="color: white; font-size: 22px;"></i>
                                        </div>
                                    </div>
                                    <h4 style="font-size: 20px; font-weight: 700; color: #1a4a3a; margin-bottom: 8px;">Payment Successful!</h4>
                                    <p style="font-size: 14px; color: #777; margin-bottom: 0.5rem;">{{ session('success') }}</p>
                                    @if($lendingStatus)
                                    <div style="background: #f8fffe; border: 1.5px solid #d0ede3; border-radius: 12px; padding: 1rem; margin: 1.2rem 0; text-align: left;">
                                        <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                                            <span style="font-size: 13px; color: #888;">Loan Type</span>
                                            <span style="font-size: 13px; font-weight: 600; color: #333;">{{ $selectedLoan->lending_type }}</span>
                                        </div>
                                        <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                                            <span style="font-size: 13px; color: #888;">Amount Paid</span>
                                            <span style="font-size: 13px; font-weight: 700; color: #1a4a3a;">₱{{ number_format($selectedLoan->monthly_payment, 2) }}</span>
                                        </div>
                                        <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                                            <span style="font-size: 13px; color: #888;">Remaining Balance</span>
                                            <span style="font-size: 13px; font-weight: 700; color: #1a4a3a;">
                                                ₱{{ number_format($lendingStatus->remaining_balance, 2) }}
                                            </span>
                                        </div>
                                        <div style="display: flex; justify-content: space-between;">
                                            <span style="font-size: 13px; color: #888;">Payment Date & Time</span>
                                            <span style="font-size: 13px; font-weight: 600; color: #333;">
                                                {{ now()->timezone('Asia/Manila')->format('M d, Y h:i A') }}
                                            </span>
                                        </div>
                                    </div>
                                    @endif
                                    <button onclick="closeSuccessModal()"
                                        style="background: #1a4a3a; color: white; border: none; border-radius: 10px; padding: 12px 40px; font-size: 14px; font-weight: 600; cursor: pointer; width: 100%;">
                                        Done
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    {{-- Error Modal --}}
                    @if(session('error'))
                    <div class="modal fade show" id="errorModal" tabindex="-1" style="display: block; background: rgba(0,0,0,0.5);">
                        <div class="modal-dialog modal-dialog-centered" style="max-width: 400px;">
                            <div class="modal-content" style="border: none; border-radius: 20px; overflow: hidden; text-align: center;">
                                <div class="modal-body" style="padding: 2.5rem 2rem;">
                                    <div style="width: 80px; height: 80px; background: #fef0f0; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.2rem;">
                                        <div style="width: 56px; height: 56px; background: #e03131; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                            <i class="fa-solid fa-xmark" style="color: white; font-size: 22px;"></i>
                                        </div>
                                    </div>
                                    <h4 style="font-size: 20px; font-weight: 700; color: #c0392b; margin-bottom: 8px;">Payment Failed</h4>
                                    <p style="font-size: 14px; color: #777; margin-bottom: 1.5rem;">{{ session('error') }}</p>
                                    <button onclick="closeErrorModal()"
                                        style="background: #e03131; color: white; border: none; border-radius: 10px; padding: 12px 40px; font-size: 14px; font-weight: 600; cursor: pointer; width: 100%;">
                                        Try Again
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    <main>
                        <div class="main-parent">
                            <div class="main-amount">
                                <p>Lending Amount</p>
                                <h3>₱ {{ number_format($selectedLoan->lending_amount, 2) }}</h3>
                                @if($lendingStatus->status !== 'Completed' && $lendingStatus->remaining_balance > 0)
                                    @php
                                        $daysLeft = (int) now()->startOfDay()->diffInDays(\Carbon\Carbon::parse($lendingStatus->due_date)->startOfDay(), false);
                                    @endphp
                                    @if($daysLeft < 0)

                                        <p style="color: #e03131; font-weight: 600;"> Due date: {{ \Carbon\Carbon::parse($lendingStatus->due_date)->format('M d, Y') }}</p>

                                    @elseif($daysLeft === 0)

                                        <p style="color: #e03131; font-weight: 600;"> Due date: {{ \Carbon\Carbon::parse($lendingStatus->due_date)->format('M d, Y') }}</p>

                                    @elseif($daysLeft <= 7)

                                        <p style="color: #e6a817; font-weight: 600;"> Due date: {{ \Carbon\Carbon::parse($lendingStatus->due_date)->format('M d, Y') }}</p>

                                    @else

                                        <p style="color: #888; font-weight: 500;"> Due date: {{ \Carbon\Carbon::parse($lendingStatus->due_date)->format('M d, Y') }}</p>

                                    @endif
                                @endif
                                
                            </div>
                            <div class="main-text">
                                <p>{{ $selectedLoan->purpose_loan }}</p>
                                <h3>{{ $selectedLoan->lending_type }}</h3>
                                <p>Applied: {{ \Carbon\Carbon::parse($selectedLoan->created_at)->format('M d, Y') }}</p>
                            </div>
                        </div>

                        {{-- AFTER --}}
                        <div class="card-box-parent">
                            <div class="card-box">
                                <p>Monthly Due</p>
                                <h4>₱{{ number_format($selectedLoan->monthly_payment, 2) }}</h4>
                                <span>Applied {{ \Carbon\Carbon::parse($selectedLoan->created_at)->format('M d, Y') }}</span>
                            </div>
                            <div class="card-box">
                                <p>Remaining Balance</p>
                                <h4>₱{{ number_format($lendingStatus->remaining_balance, 2) }}</h4>
                                <span>Applied {{ \Carbon\Carbon::parse($selectedLoan->created_at)->format('M d, Y') }}</span>
                            </div>
                            <div class="card-box">
                                <p>Interest Rate</p>
                                <h4>{{ $lendingStatus->interest_rate }}%</h4>
                                <span>Applied {{ \Carbon\Carbon::parse($selectedLoan->created_at)->format('M d, Y') }}</span>
                            </div>

                    
                        </div>
                    </main>

                    <section class="d-flex justify-content-center align-items-center flex-column gap-4">
                        <div class="card-box-parent" style="width: 100%;">
                            <h4>Repayment Progress</h4>
                            @php
                                $pct = $selectedLoan->total_payment > 0
                                    ? round(($lendingStatus->total_paid / $selectedLoan->total_payment) * 100)
                                    : 0;
                            @endphp
                            <div class="parent-progress">
                                <div class="progress-box" style="width: {{ $pct }}%;"></div>
                            </div>
                            <div class="completed">
                                <p>{{ $pct }}% completed</p>
                                <p>{{ $lendingStatus->payments_made }} of {{ $lendingStatus->total_payments }} payments made</p>
                            </div>

                            @if(
                                    (
                                        in_array($lendingStatus->status, ['Active', 'Overdue', 'Approved']) ||
                                        empty($lendingStatus->status)
                                    ) &&
                                    $lendingStatus->remaining_balance > 0 &&
                                    $lendingStatus->total_payments > 0 &&
                                    $lendingStatus->payments_made < $lendingStatus->total_payments
                                )
                                    <div class="mt-3 d-flex gap-2">
                                        <button class="btn-repay" onclick="openRepayModal('monthly')">
                                            Make a Payment
                                        </button>
                                        <button class="btn-repay" 
                                            style="background: #c8860a; border-color: #c8860a;"
                                            onclick="openRepayModal('full')">
                                            <i class="fa-solid fa-circle-check" style="font-size: 12px;"></i>
                                            Repay All (₱{{ number_format($lendingStatus->remaining_balance, 2) }})
                                        </button>
                                    </div>
                            @elseif(
                                $lendingStatus->total_payments > 0 &&
                                ($lendingStatus->status === 'Completed' ||
                                $lendingStatus->remaining_balance <= 0 ||
                                $lendingStatus->payments_made >= $lendingStatus->total_payments)
                            )
                                <div class="mt-3">
                                    <div style="display: flex; align-items: center; gap: 8px; background: var(--green); border: 1.5px solid var(--green); border-radius: 10px; padding: 10px 16px; width: fit-content;">
                                        <i class="fa-solid fa-circle-check" style="color: #ffffff; font-size: 16px;"></i>
                                        <p style="margin: 0; font-size: 13px; font-weight: 600; color: #ffffff;">Lending fully paid</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </section>

                    {{-- PAYMENT HISTORY --}}
                    <section class="d-flex justify-content-center align-items-center" id="section2">
                        <div class="card-box-parent" style="width: 100%;">
                            <div class="row">
                                <div class="col-12">
                                    <h4>Payment History</h4>
                                    <div class="mt-3 overflow-x-auto">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th>Payment Date & Time</th>
                                                    <th>Amount</th>
                                                    <th>Method</th>
                                                    <th>Reference No.</th>
                                                    {{-- <th>Recorded By</th> --}}
                                                    <th>Status</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($paymentHistory as $payment)
                                                    <tr>
                                                        <td>{{ \Carbon\Carbon::parse($payment->created_at)->format('m/d/Y h:i A') }}</td>
                                                        <td>₱ {{ number_format($payment->amount_paid, 2) }}</td>
                                                        <td>{{ $payment->payment_method }}</td>
                                                        <td>{{ $payment->reference_no ?? '—' }}</td>
                                                        {{-- <td>{{ $payment->recordedBy->name ?? 'Self' }}</td> --}}
                                                        <td>
                                                            <div class="d-flex align-items-center gap-1 card-icon"
                                                                style="background-color: var(--green); border-radius: 28px; padding: 0.3rem 0.6rem; width: fit-content;">
                                                                <div class="dot" style="background-color: #ffffff;"></div>
                                                                <p style="margin: 0; font-size: 13px; font-weight: 500; color: #ffffff;">Paid</p>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="6" class="text-center" style="color: gray; padding: 1rem;">
                                                            No payments yet.
                                                        </td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                @else
                    <div class="d-flex justify-content-center align-items-center" style="height: 100vh;">
                        <p style="color: gray;">Loan status is being processed. Please check back later.</p>
                    </div>
                @endif

            @else
                <div class="d-flex justify-content-center align-items-center" style="height: 70vh;">
                    <p style="color: gray;">No loan selected or no approved loans found.</p>
                </div>
            @endif
        </div>

        {{-- REPAYMENT MODAL --}}
        @if($selectedLoan && $lendingStatus)
            <div class="modal fade" id="repayModal" tabindex="-1">
                <div class="modal-dialog modal-dialog-centered" style="max-width: 480px;">
                    <div class="modal-content"
                        style="border: none; border-radius: 16px; overflow: hidden; box-shadow: 0 20px 60px rgba(0,0,0,0.15);">

                        {{-- Modal Header --}}
                        <div class="modal-header" style="background: #1a4a3a; padding: 1.4rem 1.6rem; border: none;">
                            <div>
                                <h5 class="modal-title" style="color: #fff; font-size: 17px; font-weight: 600; margin: 0;">
                                    Make a Payment</h5>
                                <p style="color: rgba(255,255,255,0.6); font-size: 12px; margin: 2px 0 0;">
                                    {{ $selectedLoan->lending_type }} —
                                    ₱{{ number_format($selectedLoan->lending_amount, 2) }}</p>
                            </div>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                        </div>

                        <form action="{{ route('repayment.store') }}" method="POST" id="cash-repay-form">
                            @csrf
                            <input type="hidden" name="lending_id" value="{{ $selectedLoan->id }}">
                            <input type="hidden" name="member_id" value="{{ auth()->id() }}">
                            <input type="hidden" name="payment_number" value="{{ $lendingStatus->payments_made + 1 }}">

                            <div class="modal-body" style="padding: 1.6rem; background: #fff;">

                                {{-- Amount --}}
                                <div style="margin-bottom: 1.1rem;">
                                    <label style="font-size: 12px; font-weight: 600; color: #888; text-transform: uppercase; letter-spacing: 0.5px; display: block; margin-bottom: 6px;">
                                        Amount to Pay (₱)
                                    </label>
                                    <div style="position: relative;">
                                        <span style="position: absolute; left: 14px; top: 50%; transform: translateY(-50%); color: #1a4a3a; font-weight: 700; font-size: 15px;">₱</span>
                                        <input type="number" name="amount_paid" id="repay-amount-input" class="form-control"
                                        value="{{ $selectedLoan->monthly_payment }}" required
                                        style="padding-left: 28px; border-radius: 10px; border: 1.5px solid #e0e0e0; font-size: 15px; font-weight: 600; color: #1a4a3a; height: 46px;" readonly>
                                    </div>
                                </div>

                                {{-- Payment Method — now includes GCash --}}
                                <div style="margin-bottom: 1.1rem;">
                                    <label style="font-size: 12px; font-weight: 600; color: #888; text-transform: uppercase; letter-spacing: 0.5px; display: block; margin-bottom: 6px;">
                                        Payment Method
                                    </label>
                                    <select name="payment_method" id="repay-method" class="form-select"
                                        style="border-radius: 10px; border: 1.5px solid #e0e0e0; height: 46px; font-size: 14px; color: #333;">
                                        <option value="Cash">Cash</option>
                                        <option value="GCash">GCash</option>
                                    </select>
                                </div>

                                {{-- Reference No (hidden when GCash — GCash auto-generates) --}}
                                <div id="ref-no-section" style="margin-bottom: 1.1rem;">
                                    <label style="font-size: 12px; font-weight: 600; color: #888; text-transform: uppercase; letter-spacing: 0.5px; display: block; margin-bottom: 6px;">
                                        Reference / Receipt No.
                                        <span style="color: #aaa; font-weight: 400; font-size: 11px; text-transform: none;">(optional — auto-generated if blank)</span>
                                    </label>
                                    <input type="text" name="reference_no" class="form-control"
                                        placeholder="Leave blank to auto-generate"
                                        style="border-radius: 10px; border: 1.5px solid #e0e0e0; height: 46px; font-size: 14px; color: #333;">
                                </div>

                                {{-- Notes (hidden when GCash) --}}
                                <div id="notes-section" style="margin-bottom: 0.5rem;">
                                    <label style="font-size: 12px; font-weight: 600; color: #888; text-transform: uppercase; letter-spacing: 0.5px; display: block; margin-bottom: 6px;">
                                        Notes (optional)
                                    </label>
                                    <textarea name="notes" class="form-control" rows="2"
                                        placeholder="Additional remarks..."
                                        style="border-radius: 10px; border: 1.5px solid #e0e0e0; font-size: 14px; color: #333; resize: none;"></textarea>
                                </div>

                                {{-- GCash section — shown only when GCash is selected --}}
                                <div id="gcash-section" style="display: none;">
                                    <div style="border-top: 1.5px dashed #e8e8e8; margin: 1.2rem 0;"></div>
                                    <div style="background: linear-gradient(135deg, #f0f7ff 0%, #e8f4ff 100%); border: 1.5px solid #c2deff; border-radius: 12px; padding: 1rem 1.2rem;">
                                        <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 10px;">
                                            <div style="display: flex; align-items: center; gap: 10px;">
                                                <div style="width: 40px; height: 40px; background: #007DFF; border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                                                    <i class="fa-solid fa-mobile-screen-button" style="color: white; font-size: 18px;"></i>
                                                </div>
                                                <div>
                                                    <p style="margin: 0; font-size: 14px; font-weight: 700; color: #0056b3;">Pay via GCash</p>
                                                    <p style="margin: 0; font-size: 11px; color: #5a8ac4;">Fast & secure online payment</p>
                                                </div>
                                            </div>
                                            <button type="button" onclick="submitGcash()"
                                                style="background: #007DFF; color: white; border: none; border-radius: 8px; padding: 8px 18px; font-size: 13px; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 6px; white-space: nowrap;">
                                                <i class="fa-solid fa-arrow-right" style="font-size: 12px;"></i>
                                                Pay Now
                                            </button>
                                        </div>
                                    </div>
                                </div>

                            </div>

                            {{-- Modal Footer --}}
                            <div class="modal-footer"
                                style="background: #f8f9fa; border-top: 1px solid rgba(0,0,0,0.1); padding: 1rem 1.6rem; display: flex; justify-content: center; align-items: center; flex-direction: column;">
                                <button type="submit" id="confirm-pay-btn" class="btn w-100"
                                    style="background: #1a4a3a; color: white; border-radius: 8px; font-size: 13px; font-weight: 600; padding: 8px 22px; border: none; display: flex; align-items: center; gap: 6px; justify-content: center;">
                                    <i class="fa-solid fa-check" style="font-size: 12px;"></i>
                                    Confirm Payment
                                </button>
                                <button type="button" class="btn btn-secondary w-100 text-center" data-bs-dismiss="modal"
                                    style="border-radius: 8px; font-size: 13px; padding: 8px 18px; background: #e0e0e0; border: none; color: #555;">
                                    Cancel
                                </button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>

            {{-- Hidden GCash form --}}
            <form id="gcash-form" action="{{ route('repayment.gcash') }}" method="POST" style="display:none;">
                @csrf
                <input type="hidden" name="lending_id" value="{{ $selectedLoan->id }}">
                <input type="hidden" name="payment_type" id="gcash-payment-type" value="monthly"> {{-- ADD THIS --}}
            </form>

            {{-- Full balance data for JS --}}
            <span id="full-balance-value" data-amount="{{ $lendingStatus->remaining_balance }}" style="display:none;"></span>
        @endif

    </div>

    {{-- AOS animation link js --}}
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>

    <script>
    AOS.init();

    /* ══════════════════════════════════════
        PAYMENT METHOD TOGGLE
    ══════════════════════════════════════ */
    document.getElementById('repay-method')?.addEventListener('change', function () {
        const isGcash = this.value === 'GCash';

        // Show/hide GCash panel
        document.getElementById('gcash-section').style.display   = isGcash ? 'block' : 'none';

        // Show/hide Confirm Payment button
        document.getElementById('confirm-pay-btn').style.display = isGcash ? 'none'  : 'flex';

        // Hide reference & notes fields when GCash (they're auto-handled server-side)
        document.getElementById('ref-no-section').style.display  = isGcash ? 'none'  : 'block';
        document.getElementById('notes-section').style.display   = isGcash ? 'none'  : 'block';
    });

    /* ══════════════════════════════════════
        MODAL UTILITIES
    ══════════════════════════════════════ */
    function cleanupBootstrapModal() {
        document.querySelectorAll('.modal-backdrop').forEach(el => el.remove());
        document.querySelectorAll('.modal-backdrop-custom').forEach(el => el.remove());
        document.body.classList.remove('modal-open');
        document.body.style.removeProperty('overflow');
        document.body.style.removeProperty('padding-right');
    }

    function openRepayModal(mode = 'monthly') {
        cleanupBootstrapModal();

        const methodSelect = document.getElementById('repay-method');
        const amountInput  = document.getElementById('repay-amount-input');
        const fullBalance  = parseFloat(document.getElementById('full-balance-value').dataset.amount);
        const monthlyAmt   = amountInput.defaultValue; // original monthly value

        document.getElementById('gcash-payment-type').value = mode;

        if (mode === 'full') {
            amountInput.value = fullBalance;
            // Update the modal header label to make it clear
            document.querySelector('#repayModal .modal-title').textContent = 'Repay Full Balance';
        } else {
            amountInput.value = monthlyAmt;
            document.querySelector('#repayModal .modal-title').textContent = 'Make a Payment';
        }

        if (methodSelect) {
            methodSelect.value = 'Cash';
            document.getElementById('gcash-section').style.display   = 'none';
            document.getElementById('confirm-pay-btn').style.display = 'flex';
            document.getElementById('ref-no-section').style.display  = 'block';
            document.getElementById('notes-section').style.display   = 'block';
        }

        var repayModalEl = document.getElementById('repayModal');
        var backdrop = document.createElement('div');
        backdrop.className = 'modal-backdrop-custom';
        backdrop.id = 'repay-backdrop';
        document.body.appendChild(backdrop);
        document.body.classList.add('modal-open');

        repayModalEl.classList.add('show', 'modal-animate-in');
        repayModalEl.style.display = 'block';
        repayModalEl.removeAttribute('aria-hidden');

        setTimeout(() => repayModalEl.classList.remove('modal-animate-in'), 400);
        backdrop.addEventListener('click', () => closeRepayModal());
    }

    function closeRepayModal() {
        var repayModalEl = document.getElementById('repayModal');
        repayModalEl.classList.add('modal-animate-out');
        setTimeout(() => {
            repayModalEl.classList.remove('show', 'modal-animate-out');
            repayModalEl.style.display = 'none';
            cleanupBootstrapModal();
        }, 250);
    }

    function submitGcash() {
        document.getElementById('gcash-form').submit();
    }

    function closeSuccessModal() {
        var modal = document.getElementById('successModal');
        if (modal) {
            modal.classList.add('modal-animate-out');
            setTimeout(() => {
                modal.style.display = 'none';
                modal.classList.remove('modal-animate-out');
                cleanupBootstrapModal();
            }, 250);
        }
    }

    function closeErrorModal() {
        var modal = document.getElementById('errorModal');
        if (modal) {
            modal.classList.add('modal-animate-out');
            setTimeout(() => {
                modal.style.display = 'none';
                modal.classList.remove('modal-animate-out');
                cleanupBootstrapModal();
            }, 250);
        }
    }

    document.addEventListener('DOMContentLoaded', function () {

        var successModal = document.getElementById('successModal');
        if (successModal) {
            cleanupBootstrapModal();
            successModal.querySelector('.modal-content').style.animation =
                'modalSlideIn 0.4s cubic-bezier(0.34, 1.56, 0.64, 1) forwards';
            var outerCircle = successModal.querySelector('.modal-body > div');
            if (outerCircle) outerCircle.classList.add('success-icon-animate');
            var checkIcon = successModal.querySelector('.fa-check');
            if (checkIcon) checkIcon.closest('div').classList.add('success-check-animate');
            var h4 = successModal.querySelector('h4');
            if (h4) h4.classList.add('success-content-animate');
            successModal.addEventListener('click', function(e) {
                if (e.target === successModal) closeSuccessModal();
            });
        }

        var errorModal = document.getElementById('errorModal');
        if (errorModal) {
            cleanupBootstrapModal();
            errorModal.querySelector('.modal-content').style.animation =
                'modalSlideIn 0.4s cubic-bezier(0.34, 1.56, 0.64, 1) forwards';
            var outerCircle = errorModal.querySelector('.modal-body > div');
            if (outerCircle) outerCircle.classList.add('success-icon-animate');
            errorModal.addEventListener('click', function(e) {
                if (e.target === errorModal) closeErrorModal();
            });
        }

        var repayModalEl = document.getElementById('repayModal');
        if (repayModalEl) {
            repayModalEl.querySelectorAll('[data-bs-dismiss="modal"]').forEach(function(btn) {
                btn.addEventListener('click', function () {
                    closeRepayModal();
                });
            });
        }
    });
    </script>
</body>

</html>