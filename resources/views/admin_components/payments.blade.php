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
                        <th>Amount Paid</th>
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
                {{ $payments->appends(['method' => $method])->links() }}
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
                        <label class="block text-sm font-medium text-gray-700 mb-1">Amount Paid <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500">₱</span>
                            <input type="number" name="amount_paid" id="rpAmount" class="input pl-10" placeholder="0.00" step="0.01" min="1" style="width: 100%; padding-left: 2.5rem;" required>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Payment Method <span class="text-red-500">*</span></label>
                        <select name="payment_method" id="rpPaymentMethod" class="select" style="width: 100%;" required onchange="checkPaymentMethodQr()">
                            <option value="">Select method...</option>
                            @foreach($paymentMethods as $pm)
                                <option value="{{ $pm->method_name }}" data-id="{{ $pm->id }}">{{ $pm->method_name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div id="rpQrCodeDisplay" class="hidden">
                        <div class="p-3 bg-gray-50 rounded-xl border border-gray-200 text-center">
                            <p class="text-xs text-gray-500 mb-2 font-medium">Scan QR Code to Pay</p>
                            <img id="rpQrCodeImg" class="w-40 h-40 mx-auto rounded-lg object-cover border border-gray-200" src="" alt="Payment QR Code">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Reference No.</label>
                        <input type="text" name="reference_no" class="input" placeholder="e.g. OR-12345 (optional)" style="width: 100%;">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Payment Date <span class="text-red-500">*</span></label>
                        <input type="date" name="payment_date" class="input" value="{{ date('Y-m-d') }}" style="width: 100%;" required>
                    </div>
                </form>

                <div style="margin-top: 1.25rem; display: flex; flex-direction: column; gap: 8px;">
                    <button onclick="submitRecordPayment()"
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

    <script>
        function checkPaymentMethodQr() {
            const select = document.getElementById('rpPaymentMethod');
            const qrDisplay = document.getElementById('rpQrCodeDisplay');
            const qrImg = document.getElementById('rpQrCodeImg');
            const selected = select.options[select.selectedIndex];
            const pmId = selected ? selected.dataset.id : null;

            if (!pmId) {
                qrDisplay.classList.add('hidden');
                return;
            }

            fetch('/admin/payment-methods/' + pmId + '/qr')
                .then(r => r.json())
                .then(data => {
                    if (data.success && data.has_qr) {
                        qrImg.src = data.qr_url;
                        qrDisplay.classList.remove('hidden');
                    } else {
                        qrDisplay.classList.add('hidden');
                    }
                })
                .catch(() => {
                    qrDisplay.classList.add('hidden');
                });
        }

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
            document.getElementById('rpQrCodeDisplay').classList.add('hidden');
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
            } else {
                loanInfo.style.display = 'none';
            }
        });

        function submitRecordPayment() {
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
            if (!formData.get('amount_paid') || parseFloat(formData.get('amount_paid')) <= 0) {
                showToast('Error', 'Please enter a valid amount');
                return;
            }
            if (!formData.get('payment_method')) {
                showToast('Error', 'Please select a payment method');
                return;
            }
            if (!formData.get('payment_date')) {
                showToast('Error', 'Please select a payment date');
                return;
            }

            const btn = document.querySelector('#recordPaymentModal button:first-of-type');
            btn.disabled = true;
            btn.innerHTML = '<i data-lucide="loader" class="w-4 h-4 animate-spin"></i> Recording...';

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
                btn.innerHTML = '<i data-lucide="check-circle" class="w-4 h-4"></i> Record Payment';
                if (data.success) {
                    closeRecordPaymentModal();
                    showToast('Success', data.message);
                    form.reset();
                    document.getElementById('rpLoanSelect').innerHTML = '<option value="">Select a member first</option>';
                    document.getElementById('rpLoanInfo').style.display = 'none';
                    setTimeout(() => window.location.reload(), 1000);
                } else {
                    showToast('Error', data.message || 'Failed to record payment');
                }
            })
            .catch(error => {
                btn.disabled = false;
                btn.innerHTML = '<i data-lucide="check-circle" class="w-4 h-4"></i> Record Payment';
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