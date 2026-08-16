@extends('layouts.admin')

@section('title', 'Payments - CoopAdmin')

@section('content')
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.bootstrap5.min.css" rel="stylesheet">

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
                    <span class="text-gray-900 font-medium">Payments</span>
                </li>
            </ol>
        </nav>
    </div>

    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Payments</h1>
            <p class="text-sm text-gray-500">View all loan repayment transactions made by members</p>
        </div>
        <div class="flex items-center gap-3">
            <button onclick="openRecordPaymentModal()" class="btn btn-primary">
                <i data-lucide="credit-card" class="w-4 h-4"></i>
                Record Payment
            </button>
            <select onchange="window.location.href = this.value"
                class="btn btn-outline cursor-pointer">
                <option value="{{ route('payments', ['method' => 'all']) }}" {{ $method === 'all' ? 'selected' : '' }}>All Methods</option>
                <option value="{{ route('payments', ['method' => 'Cash']) }}" {{ $method === 'Cash' ? 'selected' : '' }}>Cash</option>
                <option value="{{ route('payments', ['method' => 'GCash']) }}" {{ $method === 'GCash' ? 'selected' : '' }}>GCash</option>
            </select>
        </div>
    </div>

    <div class="card">
        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th>Member</th>
                        <th>Loan Reference</th>
                        <th>Loan Type</th>
                        <th>Payment #</th>
                        <th>Amount Payables</th>
                        <th>Payment Date</th>
                        <th>Method</th>
                        <th>Reference No.</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($payments as $payment)
                        <tr>
                            <td>
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full bg-primary-100 flex items-center justify-center">
                                        <span class="text-primary-600 font-semibold">
                                            {{ strtoupper(substr($payment->user->first_name ?? 'U', 0, 1) . substr($payment->user->last_name ?? '', 0, 1)) }}
                                        </span>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">
                                            {{ $payment->user->first_name ?? 'Unknown' }} {{ $payment->user->last_name ?? '' }}
                                        </p>
                                        <p class="text-xs text-gray-500">MEM-{{ sprintf('%03d', $payment->user_id ?? 0) }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="text-sm text-gray-900 font-medium">{{ $payment->lending->reference_no ?? 'N/A' }}</td>
                            <td class="text-sm text-gray-600">{{ $payment->lending->lending_type ?? 'N/A' }}</td>
                            <td class="text-sm text-gray-600">{{ $payment->payment_number }}</td>
                            <td class="text-sm font-semibold text-gray-900">₱{{ number_format($payment->amount_paid, 2) }}</td>
                            <td class="text-sm text-gray-600">{{ \Carbon\Carbon::parse($payment->payment_date)->format('M d, Y') }}</td>
                            <td>
                                @if($payment->payment_method === 'GCash')
                                    <span class="badge badge-primary">GCash</span>
                                @else
                                    <span class="badge badge-success">{{ $payment->payment_method }}</span>
                                @endif
                            </td>
                            <td class="text-sm text-gray-500">{{ $payment->reference_no ?? '—' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-8">
                                <div class="flex flex-col items-center text-gray-500">
                                    <i data-lucide="credit-card" class="w-12 h-12 mb-3 opacity-50"></i>
                                    <p>No payments found</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-gray-100 flex items-center justify-between">
            <p class="text-sm text-gray-500">
                Showing {{ $payments->firstItem() ?? 0 }}-{{ $payments->lastItem() ?? 0 }} of {{ $payments->total() }} payments
            </p>
            <div>
                @if($payments->hasPages())
                    <div class="flex items-center gap-1">
                        @if($payments->onFirstPage())
                            <button class="p-2 rounded-lg border border-gray-200 text-gray-400 cursor-not-allowed" disabled>
                                <i data-lucide="chevron-left" class="w-4 h-4"></i>
                            </button>
                        @else
                            <a href="{{ $payments->appends(['method' => $method])->previousPageUrl() }}" class="p-2 rounded-lg border border-gray-200 text-gray-600 hover:bg-gray-50 transition-colors">
                                <i data-lucide="chevron-left" class="w-4 h-4"></i>
                            </a>
                        @endif

                        @foreach($payments->appends(['method' => $method])->getUrlRange(max(1, $payments->currentPage() - 2), min($payments->lastPage(), $payments->currentPage() + 2)) as $page => $url)
                            @if($page == $payments->currentPage())
                                <span class="px-4 py-2 rounded-lg bg-primary-600 text-white font-medium">{{ $page }}</span>
                            @else
                                <a href="{{ $url }}" class="px-4 py-2 rounded-lg border border-gray-200 text-gray-600 hover:bg-gray-50 transition-colors">{{ $page }}</a>
                            @endif
                        @endforeach

                        @if($payments->hasMorePages())
                            <a href="{{ $payments->appends(['method' => $method])->nextPageUrl() }}" class="p-2 rounded-lg border border-gray-200 text-gray-600 hover:bg-gray-50 transition-colors">
                                <i data-lucide="chevron-right" class="w-4 h-4"></i>
                            </a>
                        @else
                            <button class="p-2 rounded-lg border border-gray-200 text-gray-400 cursor-not-allowed" disabled>
                                <i data-lucide="chevron-right" class="w-4 h-4"></i>
                            </button>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Record Payment Modal -->
    <div id="recordPaymentModal" class="modal-overlay hidden">
        <div class="modal max-w-lg" style="border-radius: 16px;">
            <div style="background: linear-gradient(135deg, #1E2A4A 0%, #25335A 100%); padding: 1.25rem 1.5rem;">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div style="width: 40px; height: 40px; background: rgba(255,255,255,0.15); border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                            <i data-lucide="credit-card" class="w-5 h-5" style="color: #fff;"></i>
                        </div>
                        <div>
                            <h2 class="text-lg font-semibold" style="color: #fff; margin: 0;">Record Payment</h2>
                            <p style="margin: 4px 0 0 0; color: rgba(255,255,255,0.7); font-size: 12px;">Manually record a loan repayment</p>
                        </div>
                    </div>
                    <button onclick="closeRecordPaymentModal()" style="background: none; border: none; width: 32px; height: 32px; border-radius: 8px; cursor: pointer; display: flex; align-items: center; justify-content: center; padding: 0; flex-shrink: 0;">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="rgba(255,255,255,0.8)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                    </button>
                </div>
            </div>

            <div style="padding: 1.25rem;">
                <form id="recordPaymentForm" class="space-y-4">
                    @csrf
                    <input type="hidden" name="reference_no" id="rpReferenceNo">

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Member <span class="text-red-500">*</span></label>
                        <select name="member_id" id="rpMemberSelect" class="select" style="width: 100%;" onchange="loadMemberLoans()" required>
                            <option value="">Select member</option>
                            @foreach($allMembers as $member)
                            <option value="{{ $member->id }}">{{ $member->first_name }} {{ $member->last_name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Active Loan <span class="text-red-500">*</span></label>
                        <select name="lending_id" id="rpLoanSelect" class="select" style="width: 100%;" required>
                            <option value="">Select a member first</option>
                        </select>
                        <p id="rpLoanInfo" class="text-xs text-gray-400 mt-1" style="display: none;"></p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Amount Payables</label>
                        <div class="mb-2">
                            <select id="rpPaymentType" class="select" style="width: 100%;" onchange="updatePayableAmount()">
                                <option value="monthly">Monthly Bill Only</option>
                                <option value="full">Full Payment</option>
                            </select>
                        </div>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500">₱</span>
                            <input type="number" name="amount_paid" id="rpAmount" class="input pl-10" placeholder="0.00" step="0.01" min="0" style="width: 100%; padding-left: 2.5rem;" readonly>
                        </div>
                        <p id="rpAmountBreakdown" class="text-xs text-gray-400 mt-1" style="display:none;"></p>
                    </div>
                </form>

                <div style="margin-top: 1.25rem; display: flex; flex-direction: column; gap: 8px;">
                    <button onclick="openRecordPaymentConfirm()"
                        style="width: 100%; padding: 0.7rem; background: #1E2A4A; color: #fff; border: none; border-radius: 10px; font-size: 14px; font-weight: 600; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 8px;">
                        <i data-lucide="check-circle" class="w-4 h-4"></i> Record Payment
                    </button>
                    <button onclick="closeRecordPaymentModal()"
                        style="width: 100%; padding: 0.65rem; background: #fff; color: #666; border: 1px solid #ddd; border-radius: 10px; font-size: 14px; cursor: pointer;">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Confirm Payment Modal -->
    <div id="confirmPaymentModal" class="modal-overlay hidden">
        <div class="modal max-w-md" style="border-radius: 16px;">
            <div style="background: linear-gradient(135deg, #14532D 0%, #166534 100%); padding: 1.25rem 1.5rem;">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div style="width: 40px; height: 40px; background: rgba(255,255,255,0.15); border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                            <i data-lucide="shield-check" class="w-5 h-5" style="color: #fff;"></i>
                        </div>
                        <div>
                            <h2 class="text-lg font-semibold" style="color: #fff; margin: 0;">Confirm Payment</h2>
                            <p style="margin: 4px 0 0 0; color: rgba(255,255,255,0.7); font-size: 12px;">Please review the payment details</p>
                        </div>
                    </div>
                    <button onclick="closeConfirmPaymentModal()" style="background: none; border: none; width: 32px; height: 32px; border-radius: 8px; cursor: pointer; display: flex; align-items: center; justify-content: center; padding: 0; flex-shrink: 0;">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="rgba(255,255,255,0.8)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                    </button>
                </div>
            </div>

            <div style="padding: 1.25rem;">
                <div class="space-y-3">
                    <div class="flex items-center justify-between py-2 border-b border-gray-100">
                        <span class="text-sm text-gray-500">Member</span>
                        <span id="cfMember" class="text-sm font-semibold text-gray-900"></span>
                    </div>
                    <div class="flex items-center justify-between py-2 border-b border-gray-100">
                        <span class="text-sm text-gray-500">Amount Payable</span>
                        <span id="cfAmount" class="text-sm font-bold text-gray-900"></span>
                    </div>
                    <div class="flex items-center justify-between py-2 border-b border-gray-100">
                        <span class="text-sm text-gray-500">Payment Date</span>
                        <span id="cfDate" class="text-sm font-semibold text-gray-900"></span>
                    </div>
                    <div class="flex items-center justify-between py-2">
                        <span class="text-sm text-gray-500">Reference Number</span>
                        <span id="cfReference" class="text-sm font-mono font-semibold text-gray-900"></span>
                    </div>
                </div>

                <div style="margin-top: 1.25rem; display: flex; flex-direction: column; gap: 8px;">
                    <button id="rpConfirmBtn" onclick="confirmRecordPayment()"
                        style="width: 100%; padding: 0.7rem; background: #15803D; color: #fff; border: none; border-radius: 10px; font-size: 14px; font-weight: 600; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 8px;">
                        <i data-lucide="check-circle" class="w-4 h-4"></i> Confirm Payment
                    </button>
                    <button onclick="closeConfirmPaymentModal()"
                        style="width: 100%; padding: 0.65rem; background: #fff; color: #666; border: 1px solid #ddd; border-radius: 10px; font-size: 14px; cursor: pointer;">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function openRecordPaymentModal() {
            const modal = document.getElementById('recordPaymentModal');
            modal.classList.remove('hidden');
            modal.style.display = 'flex';
            document.body.style.overflow = 'hidden';
            if (typeof lucide !== 'undefined') {
                setTimeout(() => lucide.createIcons(), 50);
            }
            const ts = document.getElementById('rpMemberSelect')?.tomselect;
            if (ts) ts.clear();
            document.getElementById('rpLoanSelect').innerHTML = '<option value="">Select a member first</option>';
            document.getElementById('rpLoanInfo').style.display = 'none';
            document.getElementById('rpAmount').value = '';
            document.getElementById('rpAmountBreakdown').style.display = 'none';
            document.getElementById('rpReferenceNo').value = '';
            rpPayable = null;
        }

        function closeRecordPaymentModal() {
            const modal = document.getElementById('recordPaymentModal');
            modal.classList.add('hidden');
            modal.style.display = '';
            document.body.style.overflow = '';
        }

        function loadMemberLoans() {
            const memberId = document.getElementById('rpMemberSelect').value;
            const loanSelect = document.getElementById('rpLoanSelect');
            const loanInfo = document.getElementById('rpLoanInfo');

            loanSelect.innerHTML = '<option value="">Loading...</option>';
            loanInfo.style.display = 'none';

            if (!memberId) {
                loanSelect.innerHTML = '<option value="">Select a member first</option>';
                return;
            }

            fetch('/loans/member/' + memberId + '/active')
                .then(r => r.json())
                .then(loans => {
                    if (loans.length === 0) {
                        loanSelect.innerHTML = '<option value="">No active loans found</option>';
                        return;
                    }
                    let html = '<option value="">Select loan</option>';
                    loans.forEach(loan => {
                        html += `<option value="${loan.id}" data-monthly="${loan.monthly_payment}" data-total="${loan.total_payment}" data-amount="${loan.lending_amount}">
                            ${loan.reference_no} — ${loan.lending_type} (₱${parseFloat(loan.lending_amount).toLocaleString()})
                        </option>`;
                    });
                    loanSelect.innerHTML = html;
                })
                .catch(() => {
                    loanSelect.innerHTML = '<option value="">Error loading loans</option>';
                });
        }

        document.getElementById('rpLoanSelect')?.addEventListener('change', function() {
            const loanInfo = document.getElementById('rpLoanInfo');
            const selected = this.options[this.selectedIndex];
            if (selected && selected.value) {
                const amount = parseFloat(selected.dataset.amount || 0).toLocaleString('en-PH', {minimumFractionDigits: 2});
                const monthly = parseFloat(selected.dataset.monthly || 0).toLocaleString('en-PH', {minimumFractionDigits: 2});
                loanInfo.textContent = 'Loan Amount: ₱' + amount + ' · Monthly Due: ₱' + monthly;
                loanInfo.style.display = 'block';

                // fetch computed payable (includes penalty if overdue)
                fetch('/loans/' + selected.value + '/payable')
                    .then(r => r.json())
                    .then(data => {
                        if (data.success) {
                            rpPayable = data;
                            updatePayableAmount();
                        } else {
                            rpPayable = null;
                            document.getElementById('rpAmount').value = '';
                            document.getElementById('rpAmountBreakdown').style.display = 'none';
                        }
                    })
                    .catch(() => {
                        rpPayable = null;
                        document.getElementById('rpAmount').value = '';
                        document.getElementById('rpAmountBreakdown').style.display = 'none';
                    });
            } else {
                loanInfo.style.display = 'none';
                rpPayable = null;
                document.getElementById('rpAmount').value = '';
                document.getElementById('rpAmountBreakdown').style.display = 'none';
            }
        });

        let rpPayable = null;

        function updatePayableAmount() {
            const type = document.getElementById('rpPaymentType').value;
            const amountEl = document.getElementById('rpAmount');
            const bdEl = document.getElementById('rpAmountBreakdown');

            if (!rpPayable) {
                amountEl.value = '';
                bdEl.style.display = 'none';
                return;
            }

            const isFull = type === 'full';
            const amount = isFull ? rpPayable.full_total : rpPayable.total;
            amountEl.value = amount;

            const baseLabel = isFull ? 'Remaining Balance' : 'Base';
            const baseValue = isFull ? rpPayable.remaining : rpPayable.base;
            let breakdown = baseLabel + ': ₱' + parseFloat(baseValue).toLocaleString(undefined, { minimumFractionDigits: 2 });
            if (rpPayable.penalty > 0) {
                breakdown += ' · Penalty: ₱' + parseFloat(rpPayable.penalty).toLocaleString(undefined, { minimumFractionDigits: 2 });
            }
            bdEl.textContent = breakdown;
            bdEl.style.display = 'block';
        }

        function generateReferenceNumber() {
            const d = new Date();
            const pad = n => String(n).padStart(2, '0');
            return 'ADMIN-' + d.getFullYear() + pad(d.getMonth() + 1) + pad(d.getDate()) + pad(d.getHours()) + pad(d.getMinutes()) + pad(d.getSeconds());
        }

        function openRecordPaymentConfirm() {
            const form = document.getElementById('recordPaymentForm');
            const formData = new FormData(form);

            if (!formData.get('member_id')) {
                showToast('Error', 'Please select a member');
                return;
            }
            if (!formData.get('lending_id')) {
                showToast('Error', 'Please select an active loan');
                return;
            }
            if (!formData.get('amount_paid') || parseFloat(formData.get('amount_paid')) < 0) {
                showToast('Error', 'Invalid amount payable');
                return;
            }

            const memberSel = document.getElementById('rpMemberSelect');
            const memberName = memberSel.options[memberSel.selectedIndex]?.text || '';
            const reference = generateReferenceNumber();

            document.getElementById('rpReferenceNo').value = reference;

            const amount = parseFloat(formData.get('amount_paid'));
            document.getElementById('cfMember').textContent = memberName;
            document.getElementById('cfAmount').textContent = '₱' + amount.toLocaleString('en-PH', { minimumFractionDigits: 2 });
            document.getElementById('cfDate').textContent = new Date().toLocaleDateString('en-PH', { year: 'numeric', month: 'long', day: 'numeric' });
            document.getElementById('cfReference').textContent = reference;

            const modal = document.getElementById('confirmPaymentModal');
            modal.classList.remove('hidden');
            modal.style.display = 'flex';
            document.body.style.overflow = 'hidden';
            if (typeof lucide !== 'undefined') {
                setTimeout(() => lucide.createIcons(), 50);
            }
        }

        function closeConfirmPaymentModal() {
            const modal = document.getElementById('confirmPaymentModal');
            modal.classList.add('hidden');
            modal.style.display = '';
            document.body.style.overflow = '';
        }

        function confirmRecordPayment() {
            const form = document.getElementById('recordPaymentForm');
            const formData = new FormData(form);

            const btn = document.getElementById('rpConfirmBtn');
            btn.disabled = true;
            btn.innerHTML = '<i data-lucide="loader" class="w-4 h-4 animate-spin"></i> Processing...';

            fetch('{{ route("payments.record") }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                }
            })
            .then(response => {
                if (!response.ok) {
                    return response.json().then(err => {
                        let msg = 'Failed to record payment.';
                        if (err.message) msg = err.message;
                        if (err.errors) msg = Object.values(err.errors).flat().join('\n');
                        return { success: false, message: msg };
                    }).catch(() => ({ success: false, message: 'Server error. Please try again.' }));
                }
                return response.json();
            })
            .then(data => {
                btn.disabled = false;
                btn.innerHTML = '<i data-lucide="check-circle" class="w-4 h-4"></i> Confirm Payment';
                if (data.success) {
                    closeRecordPaymentModal();
                    closeConfirmPaymentModal();
                    showToast('Success', data.message);
                    form.reset();
                    document.getElementById('rpLoanSelect').innerHTML = '<option value="">Select a member first</option>';
                    document.getElementById('rpLoanInfo').style.display = 'none';
                    document.getElementById('rpReferenceNo').value = '';
                    setTimeout(() => window.location.reload(), 1000);
                } else {
                    showToast('Error', data.message || 'Failed to record payment');
                }
            })
            .catch(error => {
                btn.disabled = false;
                btn.innerHTML = '<i data-lucide="check-circle" class="w-4 h-4"></i> Confirm Payment';
                showToast('Error', 'Something went wrong. Please try again.');
            });
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const el = document.querySelector('#rpMemberSelect');
            if (el) {
                new TomSelect('#rpMemberSelect', {
                    create: false,
                    sortField: { field: "text", direction: "asc" },
                    placeholder: 'Search for a member...',
                });
            }
        });
    </script>
@endsection