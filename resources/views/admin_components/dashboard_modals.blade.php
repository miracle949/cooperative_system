<!-- Pending Loans Modal -->
<div id="pendingLoansModal" class="modal-overlay hidden" style="display:none">
    <div class="modal max-w-2xl">
        <div class="p-6 border-b border-gray-100">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-warning-100 flex items-center justify-center">
                        <i data-lucide="file-text" class="w-5 h-5 text-warning-600"></i>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-gray-900">Pending Loan Applications</h2>
                        <p class="text-xs text-gray-500">{{ $pendingLoansCount }} application(s) awaiting review</p>
                    </div>
                </div>
                <button onclick="closeModal('pendingLoansModal')" class="p-2 hover:bg-gray-100 rounded-lg transition-colors">
                    <i data-lucide="x" class="w-5 h-5 text-gray-500"></i>
                </button>
            </div>
        </div>
        <div class="p-6 max-h-[55vh] overflow-y-auto">
            @forelse($pendingLoansList as $item)
            <div class="flex items-center gap-4 p-4 bg-gray-50 rounded-xl mb-3 border border-gray-100">
                <div class="w-10 h-10 bg-warning-100 rounded-full flex items-center justify-center flex-shrink-0">
                    <span class="text-sm font-semibold text-warning-600">{{ $item['initials'] }}</span>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-gray-900">{{ $item['name'] }}</p>
                    <p class="text-xs text-gray-500">₱{{ number_format($item['amount'], 2) }}</p>
                    <p class="text-xs text-gray-400 mt-0.5">{{ $item['time'] }}</p>
                </div>
                <span class="badge badge-warning text-xs">Pending</span>
            </div>
            @empty
            <div class="text-center py-8 text-gray-500">
                <i data-lucide="check-circle" class="w-12 h-12 mx-auto mb-2 text-gray-300"></i>
                <p>No pending loan applications</p>
            </div>
            @endforelse
        </div>
        <div class="p-6 border-t border-gray-100 flex justify-between items-center">
            <span class="text-xs text-gray-400">Showing up to 10 latest applications</span>
            <div class="flex gap-3">
                <button onclick="closeModal('pendingLoansModal')" class="px-5 py-2.5 text-gray-700 font-medium rounded-lg hover:bg-gray-100 transition-colors">Close</button>
                <a href="{{ route('lendings', ['filter' => 'pending']) }}" class="px-5 py-2.5 bg-primary-600 text-white font-medium rounded-lg hover:bg-primary-700 transition-colors flex items-center gap-2">
                    Go to Full Management Page
                    <i data-lucide="arrow-right" class="w-4 h-4"></i>
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Pending Resignations Modal -->
<div id="resignationsModal" class="modal-overlay hidden" style="display:none">
    <div class="modal max-w-2xl">
        <div class="p-6 border-b border-gray-100">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-danger-100 flex items-center justify-center">
                        <i data-lucide="log-out" class="w-5 h-5 text-danger-600"></i>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-gray-900">Pending Resignation Requests</h2>
                        <p class="text-xs text-gray-500">{{ $pendingResignationsCount }} request(s) awaiting admin review</p>
                    </div>
                </div>
                <button onclick="closeModal('resignationsModal')" class="p-2 hover:bg-gray-100 rounded-lg transition-colors">
                    <i data-lucide="x" class="w-5 h-5 text-gray-500"></i>
                </button>
            </div>
        </div>
        <div class="p-6 max-h-[55vh] overflow-y-auto">
            @forelse($pendingResignationsList as $item)
            <div class="flex items-center gap-4 p-4 bg-gray-50 rounded-xl mb-3 border border-gray-100">
                <div class="w-10 h-10 bg-danger-100 rounded-full flex items-center justify-center flex-shrink-0">
                    <span class="text-sm font-semibold text-danger-600">{{ strtoupper(substr($item['name'], 0, 1)) }}</span>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-gray-900">{{ $item['name'] }}</p>
                    <p class="text-xs text-gray-500">{{ $item['withdraw'] ? 'Withdrawing share capital' : 'Not withdrawing share capital' }}</p>
                    <p class="text-xs text-gray-400 mt-0.5">{{ $item['time'] }}</p>
                </div>
                <span class="badge badge-warning text-xs">Pending</span>
            </div>
            @empty
            <div class="text-center py-8 text-gray-500">
                <i data-lucide="check-circle" class="w-12 h-12 mx-auto mb-2 text-gray-300"></i>
                <p>No pending resignation requests</p>
            </div>
            @endforelse
        </div>
        <div class="p-6 border-t border-gray-100 flex justify-between items-center">
            <span class="text-xs text-gray-400">Showing up to 10 latest requests</span>
            <div class="flex gap-3">
                <button onclick="closeModal('resignationsModal')" class="px-5 py-2.5 text-gray-700 font-medium rounded-lg hover:bg-gray-100 transition-colors">Close</button>
                <a href="{{ route('dashboard.members') }}" class="px-5 py-2.5 bg-primary-600 text-white font-medium rounded-lg hover:bg-primary-700 transition-colors flex items-center gap-2">
                    Go to Full Management Page
                    <i data-lucide="arrow-right" class="w-4 h-4"></i>
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Upcoming Seminars Modal -->
<div id="seminarsModal" class="modal-overlay hidden" style="display:none">
    <div class="modal max-w-2xl">
        <div class="p-6 border-b border-gray-100">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-sky-100 flex items-center justify-center">
                        <i data-lucide="graduation-cap" class="w-5 h-5 text-sky-600"></i>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-gray-900">Upcoming Seminars</h2>
                        <p class="text-xs text-gray-500">{{ $upcomingSeminarsCount }} upcoming seminar(s) scheduled</p>
                    </div>
                </div>
                <button onclick="closeModal('seminarsModal')" class="p-2 hover:bg-gray-100 rounded-lg transition-colors">
                    <i data-lucide="x" class="w-5 h-5 text-gray-500"></i>
                </button>
            </div>
        </div>
        <div class="p-6 max-h-[55vh] overflow-y-auto">
            @forelse($upcomingSeminars as $item)
            <div class="flex items-start gap-4 p-4 bg-gray-50 rounded-xl mb-3 border border-gray-100">
                <div class="w-10 h-10 bg-sky-100 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                    <i data-lucide="calendar" class="w-5 h-5 text-sky-600"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-semibold text-gray-900">{{ $item['type'] }}</p>
                    <p class="text-xs text-gray-500 mt-0.5">{{ $item['schedule'] }}</p>
                    <p class="text-xs text-gray-400 mt-0.5">
                        {{ ucfirst($item['delivery']) }} &middot; {{ $item['venue'] }}
                    </p>
                </div>
            </div>
            @empty
            <div class="text-center py-8 text-gray-500">
                <i data-lucide="calendar" class="w-12 h-12 mx-auto mb-2 text-gray-300"></i>
                <p>No upcoming seminars scheduled</p>
            </div>
            @endforelse
        </div>
        <div class="p-6 border-t border-gray-100 flex justify-between items-center">
            <span class="text-xs text-gray-400">Showing next 5 upcoming seminars</span>
            <div class="flex gap-3">
                <button onclick="closeModal('seminarsModal')" class="px-5 py-2.5 text-gray-700 font-medium rounded-lg hover:bg-gray-100 transition-colors">Close</button>
                <a href="{{ route('seminars.index') }}" class="px-5 py-2.5 bg-primary-600 text-white font-medium rounded-lg hover:bg-primary-700 transition-colors flex items-center gap-2">
                    Go to Full Management Page
                    <i data-lucide="arrow-right" class="w-4 h-4"></i>
                </a>
            </div>
        </div>
    </div>
</div>

<script>
    function openPendingLoansModal() { openModal('pendingLoansModal'); }
    function openResignationsModal() { openModal('resignationsModal'); }
    function openSeminarsModal() { openModal('seminarsModal'); }
</script>
