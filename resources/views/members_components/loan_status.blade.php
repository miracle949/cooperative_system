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

                    <h3>My Loan Histories <span>- history & status</span></h3>

                    <div class="parent-header">
                        <div class="filter-parent">

                            <div class="search-parent">
                                <i class="fa fa-search"></i>
                                <input type="search" name="search" placeholder="Search by reference or type of loan">
                            </div>

                            <div class="loan-type">
                                <select name="loan_type" id="" class="form-select" required>
                                    <option value="" selected disabled style="color: #c4c0b8">Choose loan type</option>
                                    <option value="Personal Loan">Personal Loan</option>
                                    <option value="Business Loan">Business Loan</option>
                                    <option value="Emergency Loan">Emergency Loan</option>
                                    <option value="Education Loan">Education Loan</option>
                                </select>
                            </div>

                            <div class="reference">
                                <select name="reference" id="" class="form-select" required>
                                    <option value="" selected disabled style="color: #c4c0b8">Choose reference</option>
                                    <option value="KPM-2026-48291">KPM-2026-48291</option>
                                    <option value="KPM-2026-48291">KPM-2026-48291</option>
                                    <option value="KPM-2026-48291">KPM-2026-48291</option>
                                    <option value="KPM-2026-48291">KPM-2026-48291</option>
                                    <option value="KPM-2026-48291">KPM-2026-48291</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="loan-hero">
                        <div class="alh-bg1"></div>
                        <div class="alh-bg2"></div>
                        <div class="alh-bg3"></div>
                        <div class="left-hero">
                            <span>Active Loan</span>

                            <h3>Personal Loan</h3>

                            <p style="color: #808080; font-weight: 500; margin-top: 0.3rem;">KPM-2026-48291 · Active
                                April 15, 2026</p>

                            <div class="parent-progress">
                                <div class="progress-header">
                                    <p>Repayment Progress</p>

                                    <span>1 of 12 months paid</span>
                                </div>
                                <div class="progress-body">
                                    <div class="progress-sub">
                                        <div class="progress"></div>
                                    </div>
                                </div>

                                <p>₱12,500 remaining of ₱15,000 principal</p>
                            </div>

                        </div>
                        <div class="right-hero">
                            <div class="alh-parent">
                                <div class="alh-stat">
                                    <span>Monthly Due</span>
                                    <h5>₱2,083.00</h5>
                                    <p>Every 15th</p>
                                </div>
                                <div class="alh-stat">
                                    <span>Next Due</span>
                                    <h5>June 15</h5>
                                    <p>9 days away</p>
                                </div>
                                <div class="alh-stat">
                                    <span>Balance</span>
                                    <h5>₱15,500.00</h5>
                                    <p>Remaining</p>
                                </div>
                            </div>
                            {{-- <div class="hero-parent">

                            </div> --}}
                        </div>
                    </div>

                    <div class="status-alert">
                        <i class="fa fa-check-circle"></i>
                        <div class="status-alert-text">
                            Your first payment of <strong>₱2,083.33</strong> was received on <strong>May 15, 2026</strong>. Your account is in good standing. Next payment due <strong>June 15, 2026</strong>.
                        </div>
                    </div>

                    <div class="payment-breakdown">
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
                    </div>
                </div>
            </main>
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
                                    ₱{{ number_format($selectedLoan->lending_amount, 2) }}
                                </p>
                            </div>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                        </div>

                        <form action="{{ route('repayment.store') }}" method="POST" id="cash-repay-form">
                            @csrf
                            <input type="hidden" name="lending_id" value="{{ $selectedLoan->id }}">
                            <input type=" hidden" name="member_id" value="{{ auth()->id() }}">
                            <input type="hidden" name="payment_number" value="{{ $lendingStatus->payments_made + 1 }}">

                            <div class="modal-body" style="padding: 1.6rem; background: #fff;">

                                {{-- Amount --}}
                                <div style="margin-bottom: 1.1rem;">
                                    <label
                                        style="font-size: 12px; font-weight: 600; color: #888; text-transform: uppercase; letter-spacing: 0.5px; display: block; margin-bottom: 6px;">
                                        Amount to Pay (₱)
                                    </label>
                                    <div style="position: relative;">
                                        <span
                                            style="position: absolute; left: 14px; top: 50%; transform: translateY(-50%); color: #1a4a3a; font-weight: 700; font-size: 15px;">₱</span>
                                        <input type="number" name="amount_paid" id="repay-amount-input" class="form-control"
                                            value="{{ $selectedLoan->monthly_payment }}" required
                                            style="padding-left: 28px; border-radius: 10px; border: 1.5px solid #e0e0e0; font-size: 15px; font-weight: 600; color: #1a4a3a; height: 46px;"
                                            readonly>
                                    </div>
                                </div>

                                {{-- Payment Method — now includes GCash --}}
                                <div style="margin-bottom: 1.1rem;">
                                    <label
                                        style="font-size: 12px; font-weight: 600; color: #888; text-transform: uppercase; letter-spacing: 0.5px; display: block; margin-bottom: 6px;">
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
                                    <label
                                        style="font-size: 12px; font-weight: 600; color: #888; text-transform: uppercase; letter-spacing: 0.5px; display: block; margin-bottom: 6px;">
                                        Reference / Receipt No.
                                        <span
                                            style="color: #aaa; font-weight: 400; font-size: 11px; text-transform: none;">(optional
                                            — auto-generated if blank)</span>
                                    </label>
                                    <input type="text" name="reference_no" class="form-control"
                                        placeholder="Leave blank to auto-generate"
                                        style="border-radius: 10px; border: 1.5px solid #e0e0e0; height: 46px; font-size: 14px; color: #333;">
                                </div>

                                {{-- Notes (hidden when GCash) --}}
                                <div id="notes-section" style="margin-bottom: 0.5rem;">
                                    <label
                                        style="font-size: 12px; font-weight: 600; color: #888; text-transform: uppercase; letter-spacing: 0.5px; display: block; margin-bottom: 6px;">
                                        Notes (optional)
                                    </label>
                                    <textarea name="notes" class="form-control" rows="2" placeholder="Additional remarks..."
                                        style="border-radius: 10px; border: 1.5px solid #e0e0e0; font-size: 14px; color: #333; resize: none;"></textarea>
                                </div>

                                {{-- GCash section — shown only when GCash is selected --}}
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
                                                    <p
                                                        style="margin: 0; font-size: 14px; font-weight: 700; color: #0056b3;">
                                                        Pay via GCash</p>
                                                    <p style="margin: 0; font-size: 11px; color: #5a8ac4;">Fast & secure
                                                        online payment</p>
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
            <span id="full-balance-value" data-amount="{{ $lendingStatus->remaining_balance }}"
                style="display:none;"></span>
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
            document.getElementById('gcash-section').style.display = isGcash ? 'block' : 'none';

            // Show/hide Confirm Payment button
            document.getElementById('confirm-pay-btn').style.display = isGcash ? 'none' : 'flex';

            // Hide reference & notes fields when GCash (they're auto-handled server-side)
            document.getElementById('ref-no-section').style.display = isGcash ? 'none' : 'block';
            document.getElementById('notes-section').style.display = isGcash ? 'none' : 'block';
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
            const amountInput = document.getElementById('repay-amount-input');
            const fullBalance = parseFloat(document.getElementById('full-balance-value').dataset.amount);
            const monthlyAmt = amountInput.defaultValue; // original monthly value

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
                document.getElementById('gcash-section').style.display = 'none';
                document.getElementById('confirm-pay-btn').style.display = 'flex';
                document.getElementById('ref-no-section').style.display = 'block';
                document.getElementById('notes-section').style.display = 'block';
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
                successModal.addEventListener('click', function (e) {
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
                errorModal.addEventListener('click', function (e) {
                    if (e.target === errorModal) closeErrorModal();
                });
            }

            var repayModalEl = document.getElementById('repayModal');
            if (repayModalEl) {
                repayModalEl.querySelectorAll('[data-bs-dismiss="modal"]').forEach(function (btn) {
                    btn.addEventListener('click', function () {
                        closeRepayModal();
                    });
                });
            }
        });
    </script>
</body>

</html>