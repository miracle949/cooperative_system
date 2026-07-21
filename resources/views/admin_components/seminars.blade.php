@extends('layouts.admin')

@section('title', 'Seminars - CoopAdmin')

@section('content')
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
                    <span class="text-gray-900 font-medium">Seminars</span>
                </li>
            </ol>
        </nav>
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

    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Seminars</h1>
            <p class="text-sm text-gray-500">Manage pre-membership seminars and completion tracking</p>
        </div>
        <div class="flex items-center gap-3">
            <button onclick="openModal('scheduleSeminarModal')" class="btn btn-primary">
                <i data-lucide="calendar-plus" class="w-4 h-4"></i>
                Schedule Seminar
            </button>
        </div>
    </div>

    <!-- Seminar Completion Checker Table -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden mb-8">
        <div class="px-6 py-4 border-b border-gray-100">
            <h2 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                <i data-lucide="graduation-cap" class="w-5 h-5 text-primary-500"></i>
                Seminar Completion Checker
            </h2>
            <p class="text-sm text-gray-500">Toggle checkboxes to manually mark seminars as complete. All three must be completed for membership upgrade.</p>
        </div>
        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th>Member</th>
                        <th class="text-center">PMES</th>
                        <th class="text-center">Fundamentals of Coops</th>
                        <th class="text-center">Cooperative Finance</th>
                        <th class="text-center">Completed At</th>
                        <th class="text-center">Membership Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                    <tr>
                        <td>
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full {{ $user->role === 'pending' ? 'bg-gradient-to-br from-yellow-400 to-orange-400' : 'bg-gradient-to-br from-primary-400 to-primary-600' }} flex items-center justify-center">
                                    <span class="text-white font-bold text-sm">
                                        {{ strtoupper(substr($user->first_name, 0, 1)) }}{{ strtoupper(substr($user->last_name ?? '', 0, 1)) }}
                                    </span>
                                </div>
                                <div>
                                    <span class="text-sm font-medium text-gray-900">{{ $user->first_name }} {{ $user->last_name }}</span>
                                    <p class="text-xs text-gray-400">{{ $user->email }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="text-center">
                            <button onclick="toggleCompletion({{ $user->id }}, 'pmes_completed', this)"
                                class="w-8 h-8 rounded-lg border-2 transition-all duration-200 flex items-center justify-center mx-auto
                                {{ $user->completion->pmes_completed ? 'bg-green-500 border-green-500 text-white' : 'border-gray-300 hover:border-green-300' }}">
                                <i data-lucide="{{ $user->completion->pmes_completed ? 'check' : 'minus' }}" class="w-4 h-4"></i>
                            </button>
                        </td>
                        <td class="text-center">
                            <button onclick="toggleCompletion({{ $user->id }}, 'fundamentals_completed', this)"
                                class="w-8 h-8 rounded-lg border-2 transition-all duration-200 flex items-center justify-center mx-auto
                                {{ $user->completion->fundamentals_completed ? 'bg-green-500 border-green-500 text-white' : 'border-gray-300 hover:border-green-300' }}">
                                <i data-lucide="{{ $user->completion->fundamentals_completed ? 'check' : 'minus' }}" class="w-4 h-4"></i>
                            </button>
                        </td>
                        <td class="text-center">
                            <button onclick="toggleCompletion({{ $user->id }}, 'finance_completed', this)"
                                class="w-8 h-8 rounded-lg border-2 transition-all duration-200 flex items-center justify-center mx-auto
                                {{ $user->completion->finance_completed ? 'bg-green-500 border-green-500 text-white' : 'border-gray-300 hover:border-green-300' }}">
                                <i data-lucide="{{ $user->completion->finance_completed ? 'check' : 'minus' }}" class="w-4 h-4"></i>
                            </button>
                        </td>
                        <td class="text-center">
                            @if($user->completion->completed_at)
                                <span class="text-xs text-gray-600">{{ \Carbon\Carbon::parse($user->completion->completed_at)->format('M d, Y') }}</span>
                            @else
                                <span class="text-xs text-gray-400">--</span>
                            @endif
                        </td>
                        <td class="text-center">
                            @if($user->completion->pmes_completed && $user->completion->fundamentals_completed && $user->completion->finance_completed)
                                <span class="badge badge-success">Upgraded</span>
                            @elseif($user->role === 'pending')
                                <span class="badge badge-warning">Pending</span>
                            @elseif($user->role === 'member')
                                <span class="badge badge-success">Member</span>
                            @else
                                <span class="badge badge-gray">{{ ucfirst($user->role) }}</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-12">
                            <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-gray-100 flex items-center justify-center">
                                <i data-lucide="users" class="w-8 h-8 text-gray-300"></i>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-1">No users found</h3>
                            <p class="text-sm text-gray-500">There are no members or applicants in the system.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Upcoming Seminars -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden mb-8">
        <div class="px-6 py-4 border-b border-gray-100">
            <h2 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                <i data-lucide="calendar" class="w-5 h-5 text-blue-500"></i>
                Upcoming Seminars
                @if($upcomingSeminars->count() > 0)
                    <span class="ml-2 px-2 py-0.5 bg-blue-100 text-blue-700 text-xs font-semibold rounded-full">{{ $upcomingSeminars->count() }}</span>
                @endif
            </h2>
        </div>
        @forelse($upcomingSeminars as $seminar)
        <div class="p-4 border-b border-gray-50 last:border-b-0 hover:bg-gray-50 transition-colors">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl {{ $seminar->seminar_type === 'pmes' ? 'bg-purple-100' : ($seminar->seminar_type === 'fundamentals' ? 'bg-blue-100' : 'bg-emerald-100') }} flex items-center justify-center">
                        <i data-lucide="{{ $seminar->seminar_type === 'pmes' ? 'clipboard-list' : ($seminar->seminar_type === 'fundamentals' ? 'book-open' : 'trending-up') }}" class="w-6 h-6 {{ $seminar->seminar_type === 'pmes' ? 'text-purple-600' : ($seminar->seminar_type === 'fundamentals' ? 'text-blue-600' : 'text-emerald-600') }}"></i>
                    </div>
                    <div>
                        <h4 class="text-sm font-semibold text-gray-900 capitalize">{{ str_replace('_', ' ', $seminar->seminar_type) }}</h4>
                        <p class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($seminar->schedule_datetime)->format('M d, Y h:i A') }}</p>
                        <div class="flex items-center gap-2 mt-1">
                            @if($seminar->delivery_type === 'online')
                                <span class="badge badge-info">Online</span>
                                <a href="{{ $seminar->online_link }}" target="_blank" class="text-xs text-primary-600 hover:underline flex items-center gap-1">
                                    <i data-lucide="external-link" class="w-3 h-3"></i>
                                    Join Link
                                </a>
                            @else
                                <span class="badge badge-info">F2F</span>
                                <span class="text-xs text-gray-500">{{ $seminar->meetup_place }}{{ $seminar->exact_venue ? ' - ' . $seminar->exact_venue : '' }}</span>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <span class="text-xs text-gray-400">{{ $seminar->attendees->count() }} attendee(s)</span>
                    <button onclick="openAttendanceModal({{ $seminar->id }})" class="btn btn-primary btn-xs px-3 py-1.5">
                        <i data-lucide="clipboard-check" class="w-3.5 h-3.5"></i>
                        Manage Attendance
                    </button>
                </div>
            </div>
        </div>
        @empty
        <div class="p-8 text-center">
            <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-gray-100 flex items-center justify-center">
                <i data-lucide="calendar" class="w-8 h-8 text-gray-300"></i>
            </div>
            <h3 class="text-lg font-semibold text-gray-900 mb-1">No upcoming seminars</h3>
            <p class="text-sm text-gray-500">Schedule a seminar using the button above.</p>
        </div>
        @endforelse
    </div>

    <!-- Past Seminars -->
    @if($pastSeminars->count() > 0)
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden mb-8">
        <div class="px-6 py-4 border-b border-gray-100">
            <h2 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                <i data-lucide="archive" class="w-5 h-5 text-gray-400"></i>
                Past Seminars
                <span class="ml-2 px-2 py-0.5 bg-gray-100 text-gray-600 text-xs font-semibold rounded-full">{{ $pastSeminars->count() }}</span>
            </h2>
        </div>
        @foreach($pastSeminars as $seminar)
        <div class="p-4 border-b border-gray-50 last:border-b-0 hover:bg-gray-50 transition-colors">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl {{ $seminar->seminar_type === 'pmes' ? 'bg-purple-100' : ($seminar->seminar_type === 'fundamentals' ? 'bg-blue-100' : 'bg-emerald-100') }} flex items-center justify-center opacity-60">
                        <i data-lucide="{{ $seminar->seminar_type === 'pmes' ? 'clipboard-list' : ($seminar->seminar_type === 'fundamentals' ? 'book-open' : 'trending-up') }}" class="w-6 h-6 {{ $seminar->seminar_type === 'pmes' ? 'text-purple-600' : ($seminar->seminar_type === 'fundamentals' ? 'text-blue-600' : 'text-emerald-600') }}"></i>
                    </div>
                    <div>
                        <h4 class="text-sm font-semibold text-gray-700 capitalize">{{ str_replace('_', ' ', $seminar->seminar_type) }}</h4>
                        <p class="text-xs text-gray-400">{{ \Carbon\Carbon::parse($seminar->schedule_datetime)->format('M d, Y h:i A') }}</p>
                        <div class="flex items-center gap-2 mt-1">
                            @if($seminar->delivery_type === 'online')
                                <span class="badge badge-info">Online</span>
                                <a href="{{ $seminar->online_link }}" target="_blank" class="text-xs text-primary-600 hover:underline flex items-center gap-1">
                                    <i data-lucide="external-link" class="w-3 h-3"></i>
                                    Join Link
                                </a>
                            @else
                                <span class="badge badge-info">F2F</span>
                                <span class="text-xs text-gray-500">{{ $seminar->meetup_place }}{{ $seminar->exact_venue ? ' - ' . $seminar->exact_venue : '' }}</span>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    @php
                        $attended = $seminar->attendees->where('status', 'attended')->count();
                        $total = $seminar->attendees->count();
                    @endphp
                    <span class="text-xs {{ $attended === $total && $total > 0 ? 'text-green-600' : 'text-gray-400' }}">
                        {{ $attended }}/{{ $total }} attended
                    </span>
                    <button onclick="openAttendanceModal({{ $seminar->id }})" class="btn btn-secondary btn-xs px-3 py-1.5">
                        <i data-lucide="eye" class="w-3.5 h-3.5"></i>
                        View
                    </button>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @endif

    <!-- Schedule Seminar Modal -->
    <div id="scheduleSeminarModal" class="modal-overlay hidden">
        <div class="modal max-w-2xl">
            <div style="background: linear-gradient(135deg, #1E2A4A 0%, #25335A 100%); padding: 1.25rem 1.5rem;">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div style="width: 40px; height: 40px; background: rgba(255,255,255,0.15); border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                            <i data-lucide="calendar-plus" class="w-5 h-5" style="color: #fff;"></i>
                        </div>
                        <div>
                            <h2 class="text-xl font-bold" style="color: #fff; margin: 0;">Schedule Seminar</h2>
                            <p style="margin: 4px 0 0 0; color: rgba(255,255,255,0.7); font-size: 12px;">Create a new seminar session</p>
                        </div>
                    </div>
                    <button onclick="closeModal('scheduleSeminarModal')" style="background: rgba(255,255,255,0.1); border: none; width: 32px; height: 32px; border-radius: 8px; cursor: pointer; display: flex; align-items: center; justify-content: center;">
                        <i data-lucide="x" class="w-5 h-5" style="color: #fff;"></i>
                    </button>
                </div>
            </div>
            <form method="POST" action="{{ route('seminars.schedule') }}" class="p-6 space-y-6">
                @csrf
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Seminar Type <span class="text-red-500">*</span></label>
                        <select name="seminar_type" class="input" required>
                            <option value="">Select...</option>
                            <option value="pmes">PMES</option>
                            <option value="fundamentals">Fundamentals of Coops</option>
                            <option value="finance">Cooperative Finance</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Schedule Date & Time <span class="text-red-500">*</span></label>
                        <input type="datetime-local" name="schedule_datetime" class="input" required>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Delivery Type <span class="text-red-500">*</span></label>
                    <div class="flex gap-4">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="delivery_type" value="online" class="w-4 h-4 text-primary-600" onchange="toggleDeliveryFields()" checked>
                            <span class="text-sm text-gray-700">Online</span>
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="delivery_type" value="f2f" class="w-4 h-4 text-primary-600" onchange="toggleDeliveryFields()">
                            <span class="text-sm text-gray-700">Face-to-Face</span>
                        </label>
                    </div>
                </div>

                <div id="onlineFields" class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Online Link <span class="text-red-500">*</span></label>
                        <input type="url" name="online_link" class="input" placeholder="https://meet.google.com/...">
                    </div>
                </div>

                <div id="f2fFields" class="space-y-4" style="display:none">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Meetup Place <span class="text-red-500">*</span></label>
                        <input type="text" name="meetup_place" class="input" placeholder="e.g. Coop Hall, Main Office">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Exact Venue <span class="text-red-500">*</span></label>
                        <input type="text" name="exact_venue" class="input" placeholder="e.g. Room 201, 2nd Floor">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Select Attendees <span class="text-red-500">*</span></label>
                    <div class="max-h-48 overflow-y-auto border border-gray-200 rounded-lg p-3 space-y-2">
                        @foreach($users as $user)
                        <label class="flex items-center gap-3 cursor-pointer p-2 rounded-lg hover:bg-gray-50 transition-colors">
                            <input type="checkbox" name="attendees[]" value="{{ $user->id }}" class="w-4 h-4 text-primary-600 rounded border-gray-300">
                            <div class="flex items-center gap-2">
                                <div class="w-8 h-8 rounded-full {{ $user->role === 'pending' ? 'bg-gradient-to-br from-yellow-400 to-orange-400' : 'bg-gradient-to-br from-primary-400 to-primary-600' }} flex items-center justify-center">
                                    <span class="text-white font-bold text-xs">{{ strtoupper(substr($user->first_name, 0, 1)) }}{{ strtoupper(substr($user->last_name ?? '', 0, 1)) }}</span>
                                </div>
                                <span class="text-sm text-gray-700">{{ $user->first_name }} {{ $user->last_name }}</span>
                            </div>
                        </label>
                        @endforeach
                    </div>
                </div>

                <div class="flex justify-end gap-3 pt-4 border-t border-gray-200">
                    <button type="button" onclick="closeModal('scheduleSeminarModal')" class="px-5 py-2.5 text-gray-700 font-medium rounded-lg hover:bg-gray-100 transition-colors">Cancel</button>
                    <button type="submit" class="px-5 py-2.5 bg-primary-600 text-white font-medium rounded-lg hover:bg-primary-700 transition-colors flex items-center gap-2">
                        <i data-lucide="calendar-plus" class="w-4 h-4"></i>
                        Schedule Seminar
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Manage Attendance Modal -->
    <div id="attendanceModal" class="modal-overlay hidden">
        <div class="modal max-w-lg">
            <div style="background: linear-gradient(135deg, #1E2A4A 0%, #25335A 100%); padding: 1.25rem 1.5rem;">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div style="width: 40px; height: 40px; background: rgba(255,255,255,0.15); border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                            <i data-lucide="clipboard-check" class="w-5 h-5" style="color: #fff;"></i>
                        </div>
                        <div>
                            <h2 class="text-xl font-bold" style="color: #fff; margin: 0;">Manage Attendance</h2>
                            <p id="attendanceModalSubtitle" style="margin: 4px 0 0 0; color: rgba(255,255,255,0.7); font-size: 12px;">Mark attendees for this seminar</p>
                        </div>
                    </div>
                    <button onclick="closeModal('attendanceModal')" style="background: rgba(255,255,255,0.1); border: none; width: 32px; height: 32px; border-radius: 8px; cursor: pointer; display: flex; align-items: center; justify-content: center;">
                        <i data-lucide="x" class="w-5 h-5" style="color: #fff;"></i>
                    </button>
                </div>
            </div>
            <div class="p-6">
                <div id="attendanceList" class="space-y-3">
                    <div class="text-center py-8 text-gray-400">
                        <i data-lucide="loader" class="w-8 h-8 mx-auto mb-2 animate-spin"></i>
                        <p class="text-sm">Loading attendees...</p>
                    </div>
                </div>
            </div>
            <div class="p-6 border-t border-gray-100 flex justify-end">
                <button onclick="closeModal('attendanceModal')" class="px-5 py-2.5 text-gray-700 font-medium rounded-lg hover:bg-gray-100 transition-colors">Close</button>
            </div>
        </div>
    </div>

    <script>
        function toggleDeliveryFields() {
            const online = document.querySelector('input[name="delivery_type"][value="online"]').checked;
            document.getElementById('onlineFields').style.display = online ? 'block' : 'none';
            document.getElementById('f2fFields').style.display = online ? 'none' : 'block';

            const onlineLink = document.querySelector('input[name="online_link"]');
            const meetupPlace = document.querySelector('input[name="meetup_place"]');
            const exactVenue = document.querySelector('input[name="exact_venue"]');
            onlineLink.required = online;
            meetupPlace.required = !online;
            exactVenue.required = !online;
        }

        function toggleCompletion(userId, field, btn) {
            fetch('{{ route("seminars.manual-toggle") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                },
                body: JSON.stringify({ user_id: userId, field: field }),
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    if (data.value) {
                        btn.classList.remove('border-gray-300', 'hover:border-green-300');
                        btn.classList.add('bg-green-500', 'border-green-500', 'text-white');
                        btn.innerHTML = '<i data-lucide="check" class="w-4 h-4"></i>';
                    } else {
                        btn.classList.remove('bg-green-500', 'border-green-500', 'text-white');
                        btn.classList.add('border-gray-300', 'hover:border-green-300');
                        btn.innerHTML = '<i data-lucide="minus" class="w-4 h-4"></i>';
                    }
                    lucide.createIcons();

                    if (data.all_complete) {
                        showToast('Success', 'All seminars complete! User upgraded to member.', 'success');
                        location.reload();
                    }
                }
            })
            .catch(() => {
                showToast('Error', 'Failed to update completion status.', 'error');
            });
        }

        function openAttendanceModal(seminarId) {
            const modal = document.getElementById('attendanceModal');
            modal.classList.remove('hidden');
            modal.style.display = 'flex';
            document.getElementById('attendanceList').innerHTML =
                '<div class="text-center py-8 text-gray-400"><i data-lucide="loader" class="w-8 h-8 mx-auto mb-2 animate-spin"></i><p class="text-sm">Loading attendees...</p></div>';

            fetch('{{ route("seminars.index") }}', {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(r => r.text())
            .then(() => {
                const seminars = @json($upcomingSeminars->merge($pastSeminars));
                const seminar = seminars.find(s => s.id === seminarId);
                if (!seminar) {
                    document.getElementById('attendanceList').innerHTML =
                        '<div class="text-center py-8 text-gray-400"><p class="text-sm">Seminar not found.</p></div>';
                    return;
                }
                document.getElementById('attendanceModalSubtitle').textContent =
                    seminar.seminar_type.replace('_', ' ').replace(/\b\w/g, l => l.toUpperCase()) +
                    ' - ' + new Date(seminar.schedule_datetime).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric', hour: 'numeric', minute: '2-digit' });

                if (seminar.attendees.length === 0) {
                    document.getElementById('attendanceList').innerHTML =
                        '<div class="text-center py-8 text-gray-400"><i data-lucide="user-x" class="w-8 h-8 mx-auto mb-2"></i><p class="text-sm">No attendees assigned.</p></div>';
                    lucide.createIcons();
                    return;
                }

                let html = '';
                seminar.attendees.forEach(a => {
                    const name = a.user ? a.user.first_name + ' ' + (a.user.last_name || '') : 'Unknown';
                    const initials = a.user ? (a.user.first_name?.[0] || '') + (a.user.last_name?.[0] || '') : '??';
                    const statusColor = a.status === 'attended' ? 'bg-green-500' : a.status === 'absent' ? 'bg-red-500' : 'bg-yellow-400';
                    const statusLabel = a.status === 'attended' ? 'Attended' : a.status === 'absent' ? 'Absent' : 'Pending';

                    html += `
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-primary-400 to-primary-600 flex items-center justify-center">
                                    <span class="text-white font-bold text-sm">${initials}</span>
                                </div>
                                <div>
                                    <span class="text-sm font-medium text-gray-900">${name}</span>
                                    <div class="flex items-center gap-1 mt-0.5">
                                        <span class="w-2 h-2 rounded-full ${statusColor}"></span>
                                        <span class="text-xs text-gray-500">${statusLabel}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="flex items-center gap-2">
                                <button onclick="markAttendance(${seminar.id}, ${a.user_id}, 'attended')" class="btn btn-success btn-xs px-2.5 py-1.5 ${a.status === 'attended' ? 'opacity-50 cursor-not-allowed' : ''}" ${a.status === 'attended' ? 'disabled' : ''}>
                                    <i data-lucide="check" class="w-3 h-3"></i>
                                    Attended
                                </button>
                                <button onclick="markAttendance(${seminar.id}, ${a.user_id}, 'absent')" class="btn btn-danger btn-xs px-2.5 py-1.5 ${a.status === 'absent' ? 'opacity-50 cursor-not-allowed' : ''}" ${a.status === 'absent' ? 'disabled' : ''}>
                                    <i data-lucide="x" class="w-3 h-3"></i>
                                    Absent
                                </button>
                            </div>
                        </div>
                    `;
                });
                document.getElementById('attendanceList').innerHTML = html;
                lucide.createIcons();
            });
        }

        function markAttendance(seminarId, userId, status) {
            fetch('{{ route("seminars.attendance") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                },
                body: JSON.stringify({ seminar_id: seminarId, user_id: userId, status: status }),
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    showToast('Success', data.message, 'success');
                    openAttendanceModal(seminarId);
                }
            })
            .catch(() => {
                showToast('Error', 'Failed to update attendance.', 'error');
            });
        }

        document.addEventListener('DOMContentLoaded', function() {
            toggleDeliveryFields();
        });
    </script>
@endsection
