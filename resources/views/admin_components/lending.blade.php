@extends('layouts.admin')

@section('title', 'Lending Processing - CoopAdmin')

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
                    <span class="text-gray-900 font-medium">Lending Processing</span>
                </li>
            </ol>
        </nav>
    </div>

    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Lending Processing</h1>
            <p class="text-sm text-gray-500">Manage lending applications and approvals</p>
        </div>
        <div class="flex items-center gap-3">
            <div class="relative">
                <select id="statusFilter" class="btn btn-outline cursor-pointer" onchange="filterByStatus(this.value)">
                    <option value="all" {{ ($statusFilter ?? 'all') === 'all' ? 'selected' : '' }}>All Status</option>
                    <option value="pending" {{ ($statusFilter ?? '') === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="approved" {{ ($statusFilter ?? '') === 'approved' ? 'selected' : '' }}>Approved</option>
                    <option value="declined" {{ ($statusFilter ?? '') === 'declined' ? 'selected' : '' }}>Declined</option>
                </select>
            </div>
            <a href="{{ route('LoanApplication') }}" target="_blank" class="btn btn-primary">
                <i data-lucide="plus" class="w-4 h-4"></i>
                New Loan
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-4 p-4 bg-green-50 border border-green-200 rounded-lg flex items-center gap-3">
            <i data-lucide="check-circle" class="w-5 h-5 text-green-600"></i>
            <span class="text-green-800">{{ session('success') }}</span>
        </div>
    @endif

    @if(session('error'))
        <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-lg flex items-center gap-3">
            <i data-lucide="x-circle" class="w-5 h-5 text-red-600"></i>
            <span class="text-red-800">{{ session('error') }}</span>
        </div>
    @endif

    <!-- Loans Table -->
    <div class="card">
        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th>Member</th>
                        <th>Loan Amount</th>
                        <th>Purpose</th>
                        <th>Duration</th>
                        <th>Date Requested</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="loansTableBody">
                    @forelse($loans as $loan)
                        <tr class="cursor-pointer loan-row" data-status="{{ strtolower($loan->status) }}" onclick="openLoanModal({{ $loop->index }})">
                            <td>
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full bg-primary-100 flex items-center justify-center">
                                        <span class="text-primary-600 font-semibold">
                                            {{ strtoupper(substr($loan->user->first_name ?? 'U', 0, 1) . substr($loan->user->last_name ?? '', 0, 1)) }}
                                        </span>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">
                                            {{ $loan->user->first_name ?? 'Unknown' }} {{ $loan->user->last_name ?? '' }}
                                        </p>
                                        <p class="text-xs text-gray-500">MEM-{{ sprintf('%03d', $loan->user_id ?? 0) }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="text-sm font-semibold text-gray-900">₱{{ number_format($loan->lending_amount, 0) }}</td>
                            <td class="text-sm text-gray-600">{{ Str::limit($loan->purpose_loan, 30) }}</td>
                            <td class="text-sm text-gray-600">{{ $loan->lending_type_term }}</td>
                            <td class="text-sm text-gray-600">{{ $loan->created_at->format('M d, Y') }}</td>
                            <td>
                                @if($loan->status === 'Pending')
                                    <span class="badge badge-warning">Pending</span>
                                @elseif($loan->status === 'Approved')
                                    <span class="badge badge-success">Approved</span>
                                @else
                                    <span class="badge badge-danger">Declined</span>
                                @endif
                            </td>
                            <td>
                                <button class="btn btn-outline text-sm"
                                    onclick="event.stopPropagation(); openLoanModal({{ $loop->index }})">
                                    View Details
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-8">
                                <div class="flex flex-col items-center text-gray-500">
                                    <i data-lucide="inbox" class="w-12 h-12 mb-3 opacity-50"></i>
                                    <p>No loan applications found</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="p-4 border-t border-gray-100 flex items-center justify-between">
            <p class="text-sm text-gray-500">
                Showing {{ $loans->firstItem() ?? 0 }}-{{ $loans->lastItem() ?? 0 }} of {{ $loans->total() }} loan requests
            </p>
            <div>
                {{ $loans->appends(['status' => $statusFilter ?? 'all'])->links() }}
            </div>
        </div>
    </div>

    <!-- Loan Detail Modal -->
    <div id="loanDetailModal" class="modal-overlay hidden">
        <div class="modal max-w-2xl">
            <div class="p-6 border-b border-gray-100">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-xl font-semibold text-gray-900">Loan Application Details</h2>
                        <p class="text-sm text-gray-500" id="modalReferenceNo">LN-XXXX</p>
                    </div>
                    <button onclick="closeModal('loanDetailModal')" class="p-1 hover:bg-gray-100 rounded-lg">
                        <i data-lucide="x" class="w-5 h-5 text-gray-500"></i>
                    </button>
                </div>
            </div>

            <form id="loanActionForm" method="POST">
                @csrf
                <div class="p-6 space-y-6">
                    <!-- Member Info -->
                    <div class="flex items-center gap-4 p-4 bg-gray-50 rounded-xl">
                        <div class="w-16 h-16 rounded-full bg-primary-100 flex items-center justify-center">
                            <span class="text-primary-600 text-xl font-bold" id="modalInitials">--</span>
                        </div>
                        <div class="flex-1">
                            <h3 class="text-lg font-semibold text-gray-900" id="modalMemberName">Member Name</h3>
                            <p class="text-sm text-gray-500" id="modalMemberId">MEM-XXX</p>
                            <div class="flex gap-4 mt-2">
                                <span class="text-sm text-gray-600">
                                    <i data-lucide="phone" class="w-4 h-4 inline mr-1"></i>
                                    <span id="modalContact">N/A</span>
                                </span>
                                <span class="text-sm text-gray-600">
                                    <i data-lucide="mail" class="w-4 h-4 inline mr-1"></i>
                                    <span id="modalEmail">N/A</span>
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Loan Details -->
                    <div class="grid grid-cols-2 gap-4">
                        <div class="p-4 border border-gray-200 rounded-xl">
                            <p class="text-sm text-gray-500 mb-1">Loan Amount</p>
                            <p class="text-2xl font-bold text-gray-900" id="modalLoanAmount">₱0</p>
                        </div>
                        <div class="p-4 border border-gray-200 rounded-xl">
                            <p class="text-sm text-gray-500 mb-1">Interest Rate</p>
                            <p class="text-2xl font-bold text-gray-900" id="modalInterestRate">0%</p>
                        </div>
                        <div class="p-4 border border-gray-200 rounded-xl">
                            <p class="text-sm text-gray-500 mb-1">Duration</p>
                            <p class="text-2xl font-bold text-gray-900" id="modalDuration">0 months</p>
                        </div>
                        <div class="p-4 border border-gray-200 rounded-xl">
                            <p class="text-sm text-gray-500 mb-1">Monthly Payment</p>
                            <p class="text-2xl font-bold text-gray-900" id="modalMonthlyPayment">₱0</p>
                        </div>
                    </div>

                    <div>
                        <p class="text-sm text-gray-500 mb-2">Purpose</p>
                        <p class="text-gray-900" id="modalPurpose">Purpose description</p>
                    </div>

                    <div>
                        <p class="text-sm text-gray-500 mb-2">Monthly Income</p>
                        <p class="text-gray-900" id="modalMonthlyIncome">₱0</p>
                    </div>

                    <!-- Documents -->
                    <div>
                        <h4 class="font-medium text-gray-900 mb-3">Attached Documents</h4>
                        <div class="space-y-2" id="modalDocuments">
                            <!-- Documents will be populated by JS -->
                        </div>
                    </div>

                    <!-- Decline Reason (hidden by default) -->
                    <div id="declineReasonSection" class="hidden">
                        <h4 class="font-medium text-gray-900 mb-3">Decline Reason</h4>
                        <textarea name="decline_reason" id="declineReasonInput" class="form-control" rows="3" placeholder="Enter reason for declining this loan application..."></textarea>
                        @error('decline_reason')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Declined Reason Display (if already declined) -->
                    <div id="declinedReasonDisplay" class="hidden">
                        <h4 class="font-medium text-gray-900 mb-3">Decline Reason</h4>
                        <div class="p-4 bg-red-50 border border-red-200 rounded-lg">
                            <p class="text-red-800" id="declinedReasonText"></p>
                        </div>
                    </div>
                </div>

                <div class="p-6 border-t border-gray-100">
                    <div class="flex gap-3 justify-end" id="actionButtons">
                            <button type="button" id="declineBtn" onclick="showDeclineReason()"
                                class="btn btn-danger flex-1">
                                <i data-lucide="x-circle" class="w-4 h-4"></i>
                                Decline
                            </button>
                            <button type="submit" formaction="" id="approveBtn"
                                class="btn btn-success flex-1">
                                <i data-lucide="check-circle" class="w-4 h-4"></i>
                                Approve
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Image Preview Modal -->
    <div id="imagePreviewModal" class="fixed inset-0 bg-black/80 z-[60] hidden flex items-center justify-center p-4" onclick="closePreview()">
        <div class="relative max-w-4xl w-full" onclick="event.stopPropagation()">
            <button onclick="closePreview()" class="absolute -top-10 right-0 text-white hover:text-gray-300 transition-colors">
                <i data-lucide="x" class="w-8 h-8"></i>
            </button>
            <img id="previewImage" src="" class="w-full h-auto max-h-[85vh] object-contain rounded-lg shadow-2xl">
        </div>
    </div>

    <!-- Hidden data for JavaScript -->
    <script type="application/json" id="loansData">
        @json($loans->toArray()['data'])
    </script>

    <script>
    (function() {
    let loansData = [];
    
    try {
        const loansDataEl = document.getElementById('loansData');
        if (loansDataEl) {
            loansData = JSON.parse(loansDataEl.textContent || '[]');
            console.log('Loans data loaded:', loansData.length, 'loans');
        }
    } catch (e) {
        console.error('Error parsing loans data:', e);
    }

    window.filterByStatus = function(status) {
        window.location.href = '{{ route("lendings") }}?status=' + status;
    };

    // Debug: make openLoanModal globally accessible
    window.debugLoans = function() {
        console.log('loansData:', loansData);
        console.log('Number of loans:', loansData.length);
        if (loansData.length > 0) {
            console.log('First loan:', loansData[0]);
        }
    };

    window.openLoanModal = function(index) {
        const loan = loansData[index];
        if (!loan) {
            console.error('Loan not found at index:', index);
            return;
        }

        // Populate modal fields
        document.getElementById('modalReferenceNo').textContent = loan.reference_no || 'N/A';
        document.getElementById('modalMemberName').textContent = (loan.user?.first_name || 'Unknown') + ' ' + (loan.user?.last_name || '');
        document.getElementById('modalMemberId').textContent = 'MEM-' + String(loan.user_id || 'XXX').padStart(3, '0');
        document.getElementById('modalInitials').textContent = ((loan.user?.first_name || 'U')[0] + (loan.user?.last_name || '')[0]).toUpperCase();
        document.getElementById('modalContact').textContent = loan.user?.contact_no || 'N/A';
        document.getElementById('modalEmail').textContent = loan.user?.email || 'N/A';
        document.getElementById('modalLoanAmount').textContent = '₱' + parseFloat(loan.lending_amount || 0).toLocaleString('en-PH', {minimumFractionDigits: 0, maximumFractionDigits: 0});
        document.getElementById('modalDuration').textContent = loan.lending_type_term || 'N/A';
        document.getElementById('modalMonthlyPayment').textContent = '₱' + parseFloat(loan.monthly_payment || 0).toLocaleString('en-PH', {minimumFractionDigits: 2, maximumFractionDigits: 2});
        document.getElementById('modalPurpose').textContent = loan.purpose_loan || 'N/A';
        document.getElementById('modalMonthlyIncome').textContent = '₱' + parseFloat(loan.monthly_income || 0).toLocaleString('en-PH', {minimumFractionDigits: 2, maximumFractionDigits: 2});

        // Interest rate display
        const interestRates = {
            'Personal Lending': '1.5%',
            'Emergency Lending': '1.0%',
            'Business Lending': '1.2%',
            'Car Lending': '1.3%',
            'Education Lending': '0.8%'
        };
        document.getElementById('modalInterestRate').textContent = interestRates[loan.lending_type] || 'N/A';

        // Documents
        const docsContainer = document.getElementById('modalDocuments');
        docsContainer.innerHTML = '';

        if (loan.valid_id) {
            docsContainer.innerHTML += window.createDocRow('Valid ID', loan.valid_id);
        }
        if (loan.proof_of_income) {
            docsContainer.innerHTML += window.createDocRow('Proof of Income', loan.proof_of_income);
        }

        if (docsContainer.innerHTML === '') {
            docsContainer.innerHTML = '<p class="text-gray-500 text-sm">No documents attached</p>';
        }

        // Handle status-based UI
        const declineBtn = document.getElementById('declineBtn');
        const approveBtn = document.getElementById('approveBtn');
        const declineReasonSection = document.getElementById('declineReasonSection');
        const declinedReasonDisplay = document.getElementById('declinedReasonDisplay');
        const declineReasonInput = document.getElementById('declineReasonInput');

        declineReasonSection.classList.add('hidden');
        declinedReasonDisplay.classList.add('hidden');

        if (loan.status === 'Pending') {
            declineBtn.classList.remove('hidden');
            approveBtn.classList.remove('hidden');
            document.getElementById('actionButtons').classList.remove('hidden');
            declineBtn.onclick = window.showDeclineReason;
            approveBtn.formAction = '/loan/approve/' + loan.id;
            declineReasonInput.removeAttribute('required');
        } else {
            declineBtn.classList.add('hidden');
            approveBtn.classList.add('hidden');
            document.getElementById('actionButtons').classList.add('hidden');

            if (loan.status === 'Declined' && loan.decline_reason) {
                declinedReasonDisplay.classList.remove('hidden');
                document.getElementById('declinedReasonText').textContent = loan.decline_reason;
            }
        }

        // Show modal
        const modal = document.getElementById('loanDetailModal');
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
        
        // Re-init lucide icons
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }
    };

    window.createDocRow = function(name, path) {
        const extension = path.split('.').pop().toLowerCase();
        const isImage = ['jpg', 'jpeg', 'png', 'gif', 'webp'].includes(extension);
        
        if (isImage) {
            return `
                <div class="flex items-center justify-between p-3 border border-gray-200 rounded-lg hover:bg-gray-50">
                    <div class="flex items-center gap-3">
                        <i data-lucide="image" class="w-5 h-5 text-gray-500"></i>
                        <span class="text-sm text-gray-900">${name}</span>
                    </div>
                    <div class="flex gap-2">
                        <button type="button" onclick="previewImage('/storage/${path}')" class="btn btn-outline text-sm py-1">
                            <i data-lucide="eye" class="w-4 h-4"></i>
                            View
                        </button>
                        <a href="/storage/${path}" target="_blank" class="btn btn-outline text-sm py-1">
                            <i data-lucide="download" class="w-4 h-4"></i>
                        </a>
                    </div>
                </div>
            `;
        } else {
            return `
                <div class="flex items-center justify-between p-3 border border-gray-200 rounded-lg hover:bg-gray-50">
                    <div class="flex items-center gap-3">
                        <i data-lucide="file-text" class="w-5 h-5 text-gray-500"></i>
                        <span class="text-sm text-gray-900">${name}</span>
                    </div>
                    <a href="/storage/${path}" target="_blank" class="btn btn-outline text-sm py-1">
                        <i data-lucide="download" class="w-4 h-4"></i>
                        Download
                    </a>
                </div>
            `;
        }
    };

    window.previewImage = function(src) {
        document.getElementById('previewImage').src = src;
        document.getElementById('imagePreviewModal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }
    };

    window.closePreview = function() {
        document.getElementById('imagePreviewModal').classList.add('hidden');
        document.body.style.overflow = '';
    };

    window.showDeclineReason = function() {
        const declineReasonSection = document.getElementById('declineReasonSection');
        const declineReasonInput = document.getElementById('declineReasonInput');
        const declineBtn = document.getElementById('declineBtn');
        const approveBtn = document.getElementById('approveBtn');

        if (declineReasonSection.classList.contains('hidden')) {
            declineReasonSection.classList.remove('hidden');
            declineReasonInput.setAttribute('required', 'required');
            declineBtn.textContent = 'Confirm Decline';
            declineBtn.onclick = window.submitDecline;
            approveBtn.classList.add('hidden');
        } else {
            declineReasonSection.classList.add('hidden');
            declineReasonInput.removeAttribute('required');
            declineBtn.textContent = 'Decline';
            declineBtn.onclick = window.showDeclineReason;
            approveBtn.classList.remove('hidden');
        }
    };

    window.submitDecline = function() {
        const declineReasonInput = document.getElementById('declineReasonInput');
        if (!declineReasonInput.value.trim()) {
            alert('Please enter a reason for declining');
            declineReasonInput.focus();
            return;
        }

        // Find the loan being declined from the visible modal
        const memberName = document.getElementById('modalMemberName').textContent;
        const form = document.getElementById('loanActionForm');
        
        // Set action to the current loan (stored in approveBtn formaction)
        const approveBtn = document.getElementById('approveBtn');
        const loanId = approveBtn.formAction.split('/').pop();
        form.action = '/loan/decline/' + loanId;
        form.submit();
    };

    // Close preview on Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            const previewModal = document.getElementById('imagePreviewModal');
            if (!previewModal.classList.contains('hidden')) {
                closePreview();
            }
        }
    });
    })();
    </script>
@endsection
