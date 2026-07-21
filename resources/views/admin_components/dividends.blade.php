@extends('layouts.admin')

@section('title', 'Dividend Management - CoopAdmin')

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
                    <span class="text-gray-900 font-medium">Dividends</span>
                </li>
            </ol>
        </nav>
    </div>

    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Dividend Management</h1>
            <p class="text-sm text-gray-500">RA 9520 compliant dividend distribution system</p>
        </div>
        <div class="flex items-center gap-3">
            <form method="GET" action="{{ route('dividends.index') }}" class="flex items-center gap-2">
                <select name="year" onchange="this.form.submit()" class="select w-32">
                    @for ($y = $currentYear; $y >= $currentYear - 10; $y--)
                        <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endfor
                    @foreach ($years as $y)
                        @if ($y < $currentYear - 10)
                            <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                        @endif
                    @endforeach
                </select>
            </form>
        </div>
    </div>

    @if (session('success'))
        <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg text-green-800 text-sm flex items-center gap-2">
            <i data-lucide="check-circle" class="w-5 h-5 text-green-600 flex-shrink-0"></i>
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg text-red-800 text-sm flex items-center gap-2">
            <i data-lucide="alert-circle" class="w-5 h-5 text-red-600 flex-shrink-0"></i>
            {{ session('error') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg text-red-800 text-sm">
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if (!$distribution)
        <!-- Section 1: Net Surplus Input -->
        <div class="card p-6 mb-6">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-10 h-10 rounded-full bg-primary-100 flex items-center justify-center">
                    <i data-lucide="calculator" class="w-5 h-5 text-primary-600"></i>
                </div>
                <div>
                    <h2 class="text-lg font-bold text-gray-900">Generate Dividend Calculations</h2>
                    <p class="text-sm text-gray-500">Enter the annual net surplus to compute member dividends per RA 9520</p>
                </div>
            </div>

            <form method="POST" action="{{ route('dividends.calculate') }}" class="max-w-xl">
                @csrf
                <input type="hidden" name="year" value="{{ $year }}">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Annual Net Surplus (₱)</label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500 font-semibold">₱</span>
                        <input type="number" name="net_surplus" id="netSurplusInput" step="0.01" min="1"
                            class="input pl-10 text-lg font-bold text-gray-900" placeholder="0.00"
                            oninput="updateBreakdownPreview()" required>
                    </div>
                    <p class="text-xs text-gray-400 mt-1">The net surplus for fiscal year {{ $year }}</p>
                </div>

                <!-- Live breakdown preview -->
                <div id="breakdownPreview" class="bg-gray-50 rounded-lg p-4 mb-4 hidden">
                    <h4 class="text-sm font-semibold text-gray-700 mb-3">Statutory Breakdown Preview (RA 9520)</h4>
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span>Reserve Fund (10%)</span>
                            <span class="font-semibold" id="previewReserve">₱0.00</span>
                        </div>
                        <div class="flex justify-between">
                            <span>CETF (10%)</span>
                            <span class="font-semibold" id="previewCETF">₱0.00</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Community Dev. Fund (3%)</span>
                            <span class="font-semibold" id="previewCDF">₱0.00</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Optional Fund (7%)</span>
                            <span class="font-semibold" id="previewOptional">₱0.00</span>
                        </div>
                        <hr class="border-gray-300">
                        <div class="flex justify-between text-gray-500">
                            <span>Statutory Total (30%)</span>
                            <span class="font-semibold" id="previewStatutoryTotal">₱0.00</span>
                        </div>
                        <hr class="border-gray-300">
                        <div class="flex justify-between text-success-700 font-semibold">
                            <span>Dividend Pool (60% of remaining)</span>
                            <span id="previewDividendPool">₱0.00</span>
                        </div>
                        <div class="flex justify-between text-warning-700 font-semibold">
                            <span>Patronage Refund Pool (40% of remaining)</span>
                            <span id="previewPatronage">₱0.00</span>
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary">
                    <i data-lucide="wand-2" class="w-4 h-4"></i>
                    Generate Calculations
                </button>
            </form>
        </div>
    @else
        <!-- Section 1: Statutory Breakdown -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-6">
            <div class="stat-card">
                <div class="flex items-center justify-between mb-2">
                    <p class="text-sm text-gray-500">Net Surplus</p>
                    <div class="w-8 h-8 rounded-lg bg-primary-100 flex items-center justify-center">
                        <i data-lucide="pie-chart" class="w-4 h-4 text-primary-600"></i>
                    </div>
                </div>
                <p class="text-2xl font-bold text-gray-900">₱{{ number_format($distribution->net_surplus, 2) }}</p>
                <p class="text-xs text-gray-400 mt-1">Fiscal Year {{ $year }}</p>
            </div>

            <div class="stat-card">
                <div class="flex items-center justify-between mb-2">
                    <p class="text-sm text-gray-500">Dividend Fund</p>
                    <div class="flex items-center gap-1">
                        <button onclick="openDividendFundModal()" class="p-1 rounded hover:bg-gray-100 transition-colors" title="Edit Dividend Fund Percentage">
                            <i data-lucide="pencil" class="w-3.5 h-3.5 text-gray-400"></i>
                        </button>
                        <div class="w-8 h-8 rounded-lg bg-success-100 flex items-center justify-center">
                            <i data-lucide="gift" class="w-4 h-4 text-success-600"></i>
                        </div>
                    </div>
                </div>
                <p id="fund-amount" class="text-2xl font-bold text-success-700">₱{{ number_format($distribution->dividend_pool, 2) }}</p>
                <p id="fund-pct-label" class="text-xs text-gray-400 mt-1">{{ $dividendFundPercentage }}% of remaining surplus</p>
            </div>

            <div class="stat-card">
                <div class="flex items-center justify-between mb-2">
                    <p class="text-sm text-gray-500">Distribution Status</p>
                    <div class="w-8 h-8 rounded-lg bg-{{ $distribution->status === 'released' ? 'success' : 'warning' }}-100 flex items-center justify-center">
                        <i data-lucide="{{ $distribution->status === 'released' ? 'check-circle' : 'clock' }}" class="w-4 h-4 text-{{ $distribution->status === 'released' ? 'success' : 'warning' }}-600"></i>
                    </div>
                </div>
                @if ($distribution->status === 'draft')
                    <span class="badge badge-warning">Draft</span>
                @elseif ($distribution->status === 'released')
                    <span class="badge badge-success">Released</span>
                @endif
                <p class="text-xs text-gray-400 mt-1">
                    {{ $approvedCount }} approved ·
                    {{ $disbursedCount }} disbursed
                </p>
            </div>

            <div class="stat-card">
                <div class="flex items-center justify-between mb-2">
                    <p class="text-sm text-gray-500">Reserve Fund</p>
                </div>
                <p class="text-2xl font-bold text-gray-900">₱{{ number_format($distribution->reserve_fund, 2) }}</p>
                <p class="text-xs text-gray-400">10% of net surplus</p>
            </div>

            <div class="stat-card">
                <div class="flex items-center justify-between mb-2">
                    <p class="text-sm text-gray-500">CETF</p>
                </div>
                <p class="text-2xl font-bold text-gray-900">₱{{ number_format($distribution->education_fund, 2) }}</p>
                <p class="text-xs text-gray-400">10% of net surplus</p>
            </div>

            <div class="stat-card">
                <div class="flex items-center justify-between mb-2">
                    <p class="text-sm text-gray-500">Community Dev. Fund</p>
                </div>
                <p class="text-2xl font-bold text-gray-900">₱{{ number_format($distribution->community_fund, 2) }}</p>
                <p class="text-xs text-gray-400">3% of net surplus</p>
            </div>

            <div class="stat-card">
                <div class="flex items-center justify-between mb-2">
                    <p class="text-sm text-gray-500">Optional Fund</p>
                </div>
                <p class="text-2xl font-bold text-gray-900">₱{{ number_format($distribution->optional_fund, 2) }}</p>
                <p class="text-xs text-gray-400">7% of net surplus</p>
            </div>

            <div class="stat-card">
                <div class="flex items-center justify-between mb-2">
                    <p class="text-sm text-gray-500">Patronage Refund Pool</p>
                </div>
                <p id="patronage-amount" class="text-2xl font-bold text-warning-700">₱{{ number_format($distribution->patronage_refund_pool, 2) }}</p>
                <p id="patronage-pct-label" class="text-xs text-gray-400">{{ 100 - $dividendFundPercentage }}% of remaining surplus</p>
            </div>
        </div>

        <!-- Section 3: Disbursement Action -->
        <div id="disburse-card" class="card p-4 mb-6">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-primary-100 flex items-center justify-center">
                        <i data-lucide="send" class="w-5 h-5 text-primary-600"></i>
                    </div>
                    <div>
                        <h3 class="text-sm font-semibold text-gray-900">Disburse Dividends</h3>
                        <p id="disburse-count-text" class="text-xs text-gray-500">
                            {{ $approvedCount }} approved dividend(s) ready for disbursement
                        </p>
                    </div>
                </div>
                <div id="disburse-btn-container">
                    @if ($distribution->status !== 'released' && $approvedCount > 0)
                        <button onclick="disburseAllDividends()" id="disburse-all-btn" class="btn btn-primary">
                            <i data-lucide="send" class="w-4 h-4"></i>
                            Disburse All
                        </button>
                    @endif
                </div>
            </div>
        </div>

        <!-- Section 2: Members Dividend Table -->
        <div id="dividends-table-container">
            <div class="card">
                <div class="p-8 text-center text-gray-400">
                    <i data-lucide="loader" class="w-6 h-6 mx-auto mb-2 animate-spin"></i>
                    <p class="text-sm">Loading dividends table...</p>
                </div>
            </div>
        </div>
    @endif

    <!-- Disbursement Modal -->
    <div id="disburseModal" class="modal-overlay hidden">
        <div class="modal max-w-lg">
            <div class="p-6 border-b border-gray-100">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-success-100 flex items-center justify-center">
                            <i data-lucide="send" class="w-5 h-5 text-success-600"></i>
                        </div>
                        <div>
                            <h2 class="text-xl font-bold text-gray-900">Disburse Dividends</h2>
                            <p class="text-xs text-gray-500">Release all approved dividends for {{ $year }}</p>
                        </div>
                    </div>
                    <button onclick="closeDisburseModal()" class="p-2 hover:bg-gray-100 rounded-lg transition-colors">
                        <i data-lucide="x" class="w-5 h-5 text-gray-500"></i>
                    </button>
                </div>
            </div>

            <form id="disburseForm" method="POST" action="{{ route('dividends.disburse') }}" class="p-6">
                @csrf
                <input type="hidden" name="year" value="{{ $year }}">

                <div class="mb-6">
                    <p class="text-sm text-gray-600 mb-4">
                        You are about to disburse <strong>{{ $approvedCount }}</strong> approved dividend(s)
                        totaling <strong>₱{{ number_format($totalSumApproved, 2) }}</strong>.
                    </p>

                    <label class="block text-sm font-medium text-gray-700 mb-2">Disbursement Type</label>
                    <div class="space-y-3">
                        <label class="flex items-center gap-3 p-3 border border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50">
                            <input type="radio" name="disbursement_type" value="savings" checked class="accent-primary-600">
                            <div>
                                <span class="text-sm font-medium text-gray-900">Add to Savings Account</span>
                                <p class="text-xs text-gray-500">The dividend amount will be deposited to each member's savings balance</p>
                            </div>
                        </label>
                        <label class="flex items-center gap-3 p-3 border border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50">
                            <input type="radio" name="disbursement_type" value="share_capital" class="accent-primary-600">
                            <div>
                                <span class="text-sm font-medium text-gray-900">Add to Share Capital</span>
                                <p class="text-xs text-gray-500">The dividend amount will be added to each member's share capital</p>
                            </div>
                        </label>
                    </div>
                </div>

                <div class="flex justify-end gap-3">
                    <button type="button" onclick="closeDisburseModal()" class="px-5 py-2.5 text-gray-700 font-medium rounded-lg hover:bg-gray-100 transition-colors">
                        Cancel
                    </button>
                    <button type="submit" class="px-5 py-2.5 bg-success-600 text-white font-medium rounded-lg hover:bg-success-700 transition-colors flex items-center gap-2">
                        <i data-lucide="check-circle" class="w-4 h-4"></i>
                        Confirm Disbursement
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Dividend Fund Percentage Modal -->
    <div id="dividendFundModal" class="modal-overlay hidden">
        <div class="modal max-w-md">
            <div class="p-6 border-b border-gray-100">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-success-100 flex items-center justify-center">
                            <i data-lucide="percent" class="w-5 h-5 text-success-600"></i>
                        </div>
                        <div>
                            <h2 class="text-xl font-bold text-gray-900">Edit Dividend Fund</h2>
                            <p class="text-xs text-gray-500">Adjust the dividend fund percentage for {{ $year }}</p>
                        </div>
                    </div>
                    <button onclick="closeDividendFundModal()" class="p-2 hover:bg-gray-100 rounded-lg transition-colors">
                        <i data-lucide="x" class="w-5 h-5 text-gray-500"></i>
                    </button>
                </div>
            </div>
            <form id="dividendFundForm" class="p-6">
                @csrf
                <input type="hidden" name="year" value="{{ $year }}">
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Dividend Fund Percentage (%)</label>
                    <div class="relative">
                        <input type="number" name="dividend_fund_percentage" id="fundPercentageInput"
                            step="0.01" min="1" max="99"
                            value="{{ $dividendFundPercentage }}"
                            class="input pr-8" required>
                        <span class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 font-semibold">%</span>
                    </div>
                    <p class="text-xs text-gray-400 mt-1">Remaining {{ 100 - $dividendFundPercentage }}% goes to Patronage Refund Pool</p>
                </div>
                <div class="flex justify-end gap-3">
                    <button type="button" onclick="closeDividendFundModal()" class="px-5 py-2.5 text-gray-700 font-medium rounded-lg hover:bg-gray-100 transition-colors">
                        Cancel
                    </button>
                    <button type="submit" class="px-5 py-2.5 bg-success-600 text-white font-medium rounded-lg hover:bg-success-700 transition-colors flex items-center gap-2">
                        <i data-lucide="check" class="w-4 h-4"></i>
                        Save & Recalculate
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Load dividends table via AJAX on page load
        document.addEventListener('DOMContentLoaded', function() {
            loadDividendsTable();
        });

        function loadDividendsTable() {
            const year = '{{ $year }}';
            const url = '{{ route("dividends.table-partial") }}?year=' + encodeURIComponent(year);
            loadDividendsPage(url);
        }

        function loadDividendsPage(url) {
            const container = document.getElementById('dividends-table-container');
            fetch(url, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.text())
            .then(html => {
                container.innerHTML = html;
                if (typeof lucide !== 'undefined') {
                    lucide.createIcons();
                }
            })
            .catch(error => {
                console.error('Error loading dividends table:', error);
                container.innerHTML = '<div class="card p-6 text-center text-red-500"><p>Failed to load dividends table.</p></div>';
            });
        }

        // Breakdown preview on net surplus input
        function updateBreakdownPreview() {
            const input = document.getElementById('netSurplusInput');
            const preview = document.getElementById('breakdownPreview');
            const val = parseFloat(input.value) || 0;

            if (val > 0) {
                preview.classList.remove('hidden');

                const reserve = val * 0.10;
                const cetf = val * 0.10;
                const cdf = val * 0.03;
                const optional = val * 0.07;
                const statutoryTotal = reserve + cetf + cdf + optional;
                const remaining = val - statutoryTotal;
                const dividendPool = remaining * 0.60;
                const patronage = remaining * 0.40;

                document.getElementById('previewReserve').textContent = '₱' + reserve.toLocaleString('en-PH', {minimumFractionDigits: 2});
                document.getElementById('previewCETF').textContent = '₱' + cetf.toLocaleString('en-PH', {minimumFractionDigits: 2});
                document.getElementById('previewCDF').textContent = '₱' + cdf.toLocaleString('en-PH', {minimumFractionDigits: 2});
                document.getElementById('previewOptional').textContent = '₱' + optional.toLocaleString('en-PH', {minimumFractionDigits: 2});
                document.getElementById('previewStatutoryTotal').textContent = '₱' + statutoryTotal.toLocaleString('en-PH', {minimumFractionDigits: 2});
                document.getElementById('previewDividendPool').textContent = '₱' + dividendPool.toLocaleString('en-PH', {minimumFractionDigits: 2});
                document.getElementById('previewPatronage').textContent = '₱' + patronage.toLocaleString('en-PH', {minimumFractionDigits: 2});
            } else {
                preview.classList.add('hidden');
            }
        }

        // Update dividend amount via AJAX
        function updateDividendAmount(id, value) {
            const amount = parseFloat(value);
            if (isNaN(amount) || amount < 0) return;

            fetch('/admin/dividends/' + id + '/update', {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]')?.value || '{{ csrf_token() }}'
                },
                body: JSON.stringify({ approved_amount: amount })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showToast('Success', data.message);
                } else {
                    showToast('Error', data.message || 'Update failed', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('Error', 'An error occurred while updating', 'error');
            });
        }

        // Approve a single dividend
        function approveDividend(id) {
            if (!confirm('Approve this dividend?')) return;

            fetch('/admin/dividends/' + id + '/approve', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]')?.value || '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showToast('Success', data.message);
                    loadDividendsTable();
                } else {
                    showToast('Error', data.message || 'Approval failed', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('Error', 'An error occurred', 'error');
            });
        }

        // Disburse a single dividend
        function disburseDividend(id) {
            if (!confirm('Disburse this dividend to savings account?')) return;

            fetch('/admin/dividends/' + id + '/disburse', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]')?.value || '{{ csrf_token() }}',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: new URLSearchParams({ disbursement_type: 'savings' })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showToast('Success', data.message);
                    if (data.html) {
                        document.getElementById('dividends-table-container').innerHTML = data.html;
                        if (typeof lucide !== 'undefined') {
                            lucide.createIcons();
                        }
                    } else {
                        loadDividendsTable();
                    }
                } else {
                    showToast('Error', data.message || 'Disbursement failed', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('Error', 'An error occurred during disbursement', 'error');
            });
        }

        // Disburse All Dividends via AJAX
        function disburseAllDividends() {
            if (!confirm('Disburse all approved dividends to savings accounts? This action cannot be undone.')) return;

            const btn = document.getElementById('disburse-all-btn');
            if (btn) {
                btn.disabled = true;
                btn.innerHTML = '<i data-lucide="loader" class="w-4 h-4 animate-spin"></i> Disbursing...';
                if (typeof lucide !== 'undefined') lucide.createIcons();
            }

            fetch('/admin/dividends/disburse-all/{{ $year }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]')?.value || '{{ csrf_token() }}',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: new URLSearchParams({
                    disbursement_type: 'savings'
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showToast('Success', data.message);

                    var countText = document.getElementById('disburse-count-text');
                    if (countText) {
                        countText.textContent = data.approvedCount + ' approved dividend(s) ready for disbursement';
                    }

                    var btnContainer = document.getElementById('disburse-btn-container');
                    if (btnContainer) {
                        btnContainer.innerHTML = '<span class="text-sm text-green-600 font-medium"><i data-lucide="check-circle" class="w-4 h-4 inline"></i> All dividends disbursed</span>';
                        if (typeof lucide !== 'undefined') lucide.createIcons();
                    }

                    if (data.html) {
                        document.getElementById('dividends-table-container').innerHTML = data.html;
                        if (typeof lucide !== 'undefined') {
                            lucide.createIcons();
                        }
                    } else {
                        loadDividendsTable();
                    }
                } else {
                    showToast('Error', data.message || 'Disbursement failed', 'error');
                    if (btn) {
                        btn.disabled = false;
                        btn.innerHTML = '<i data-lucide="send" class="w-4 h-4"></i> Disburse All';
                        if (typeof lucide !== 'undefined') lucide.createIcons();
                    }
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('Error', 'An error occurred during disbursement', 'error');
                if (btn) {
                    btn.disabled = false;
                    btn.innerHTML = '<i data-lucide="send" class="w-4 h-4"></i> Disburse All';
                    if (typeof lucide !== 'undefined') lucide.createIcons();
                }
            });
        }

        // Disbursement modal
        function openDisburseModal() {
            document.getElementById('disburseModal').classList.remove('hidden');
            document.getElementById('disburseModal').style.display = 'flex';
            document.body.style.overflow = 'hidden';
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }
        }

        function closeDisburseModal() {
            document.getElementById('disburseModal').classList.add('hidden');
            document.getElementById('disburseModal').style.display = 'none';
            document.body.style.overflow = 'auto';
        }

        // AJAX Disburse form handler
        document.getElementById('disburseForm')?.addEventListener('submit', function(e) {
            e.preventDefault();
            const form = this;
            const formData = new FormData(form);

            fetch(form.action, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]')?.value || '{{ csrf_token() }}',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    closeDisburseModal();
                    showToast('Success', data.message);
                    if (data.html) {
                        document.getElementById('dividends-table-container').innerHTML = data.html;
                        if (typeof lucide !== 'undefined') {
                            lucide.createIcons();
                        }
                    } else {
                        loadDividendsTable();
                    }
                } else {
                    showToast('Error', data.message || 'Disbursement failed', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('Error', 'An error occurred during disbursement', 'error');
            });
        });

        // Dividend Fund Percentage Modal
        function openDividendFundModal() {
            document.getElementById('dividendFundModal').classList.remove('hidden');
            document.getElementById('dividendFundModal').style.display = 'flex';
            document.body.style.overflow = 'hidden';
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }
        }

        function closeDividendFundModal() {
            document.getElementById('dividendFundModal').classList.add('hidden');
            document.getElementById('dividendFundModal').style.display = 'none';
            document.body.style.overflow = 'auto';
        }

        document.getElementById('dividendFundForm')?.addEventListener('submit', function(e) {
            e.preventDefault();
            const form = this;
            const formData = new FormData(form);

            fetch('{{ route("dividends.update-fund-percentage") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]')?.value || '{{ csrf_token() }}',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    closeDividendFundModal();
                    showToast('Success', data.message);

                    document.getElementById('fund-amount').textContent = '₱' + data.dividend_pool;
                    document.getElementById('fund-pct-label').textContent = data.dividend_fund_percentage + '% of remaining surplus';
                    document.getElementById('patronage-amount').textContent = '₱' + data.patronage_refund_pool;
                    document.getElementById('patronage-pct-label').textContent = data.patronage_refund_percentage + '% of remaining surplus';

                    loadDividendsTable();
                } else {
                    showToast('Error', data.message || 'Update failed', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('Error', 'An error occurred while updating', 'error');
            });
        });
    </script>
@endsection
