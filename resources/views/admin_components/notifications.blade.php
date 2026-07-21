@extends('layouts.admin')

@section('title', 'Notifications - CoopAdmin')

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
                <span class="text-gray-900 font-medium">Notifications</span>
            </li>
        </ol>
    </nav>
</div>

<div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Notifications</h1>
        <p class="text-sm text-gray-500">Manage your notification preferences and alerts</p>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

    {{-- ═══════ Important Card ═══════ --}}
    <div class="card border-l-4 border-l-red-500 overflow-hidden">
        <div class="flex items-center justify-between p-4 border-b border-gray-100 bg-red-50">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-red-100 flex items-center justify-center">
                    <i data-lucide="star" class="w-5 h-5 text-red-600"></i>
                </div>
                <div>
                    <h3 class="font-semibold text-gray-900">Important</h3>
                    <p class="text-xs text-gray-500">{{ $important->count() }} notification{{ $important->count() !== 1 ? 's' : '' }}</p>
                </div>
            </div>
        </div>
        <div class="divide-y divide-gray-100 max-h-80 overflow-y-auto">
            @forelse($important as $n)
                <div class="flex items-start gap-3 p-4 hover:bg-red-50/50 transition-colors group" data-id="{{ $n->id }}">
                    <div class="flex-1 min-w-0">
                        <div class="flex items-start justify-between gap-2">
                            <p class="text-sm font-medium text-gray-900 {{ $n->is_read ? 'text-gray-500' : '' }}">{{ $n->title }}</p>
                            <span class="text-xs text-gray-400 whitespace-nowrap">{{ $n->created_at->diffForHumans() }}</span>
                        </div>
                        <p class="text-xs text-gray-500 mt-0.5">{{ $n->message }}</p>
                    </div>
                    <div class="flex items-center gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                        <button onclick="toggleImportant({{ $n->id }})" class="p-1.5 hover:bg-red-100 rounded transition-colors" title="Unmark important">
                            <i data-lucide="star" class="w-4 h-4 text-red-500 fill-red-500"></i>
                        </button>
                        @if(!$n->is_read)
                        <button onclick="markAsRead({{ $n->id }})" class="p-1.5 hover:bg-green-100 rounded transition-colors" title="Mark as read">
                            <i data-lucide="check" class="w-4 h-4 text-gray-400"></i>
                        </button>
                        @endif
                    </div>
                </div>
            @empty
                <div class="p-6 text-center text-gray-400">
                    <i data-lucide="inbox" class="w-10 h-10 mx-auto mb-2 opacity-50"></i>
                    <p class="text-sm">No important notifications</p>
                </div>
            @endforelse
        </div>
    </div>

    {{-- ═══════ Inbox Card ═══════ --}}
    <div class="card border-l-4 border-l-blue-500 overflow-hidden">
        <div class="flex items-center justify-between p-4 border-b border-gray-100 bg-blue-50">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center">
                    <i data-lucide="inbox" class="w-5 h-5 text-blue-600"></i>
                </div>
                <div>
                    <h3 class="font-semibold text-gray-900">Inbox</h3>
                    <p class="text-xs text-gray-500">{{ $inbox->count() }} notification{{ $inbox->count() !== 1 ? 's' : '' }}</p>
                </div>
            </div>
            @php $inboxMuted = $settings->mute_inbox; @endphp
            <label class="relative inline-flex items-center cursor-pointer">
                <input type="checkbox" class="sr-only peer" {{ $inboxMuted ? '' : 'checked' }} onchange="toggleMute('mute_inbox', this)">
                <div class="w-9 h-5 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-blue-600"></div>
                <span class="ms-2 text-xs {{ $inboxMuted ? 'text-gray-400' : 'text-blue-600' }} font-medium">{{ $inboxMuted ? 'Silent' : 'Active' }}</span>
            </label>
        </div>
        <div class="divide-y divide-gray-100 max-h-80 overflow-y-auto">
            @forelse($inbox as $n)
                <div class="flex items-start gap-3 p-4 hover:bg-blue-50/50 transition-colors group" data-id="{{ $n->id }}">
                    <div class="flex-1 min-w-0">
                        <div class="flex items-start justify-between gap-2">
                            <p class="text-sm font-medium {{ $n->is_read ? 'text-gray-500' : 'text-gray-900' }}">{{ $n->title }}</p>
                            <span class="text-xs text-gray-400 whitespace-nowrap">{{ $n->created_at->diffForHumans() }}</span>
                        </div>
                        <p class="text-xs text-gray-500 mt-0.5">{{ $n->message }}</p>
                    </div>
                    <div class="flex items-center gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                        <button onclick="toggleImportant({{ $n->id }})" class="p-1.5 hover:bg-yellow-100 rounded transition-colors" title="{{ $n->is_important ? 'Unmark important' : 'Mark important' }}">
                            <i data-lucide="star" class="w-4 h-4 {{ $n->is_important ? 'text-yellow-500 fill-yellow-500' : 'text-gray-300' }}"></i>
                        </button>
                        @if(!$n->is_read)
                        <button onclick="markAsRead({{ $n->id }})" class="p-1.5 hover:bg-green-100 rounded transition-colors" title="Mark as read">
                            <i data-lucide="check" class="w-4 h-4 text-gray-400"></i>
                        </button>
                        @endif
                    </div>
                </div>
            @empty
                <div class="p-6 text-center text-gray-400">
                    <i data-lucide="inbox" class="w-10 h-10 mx-auto mb-2 opacity-50"></i>
                    <p class="text-sm">No inbox notifications</p>
                </div>
            @endforelse
        </div>
    </div>

    {{-- ═══════ Spam Card ═══════ --}}
    <div class="card border-l-4 border-l-orange-500 overflow-hidden">
        <div class="flex items-center justify-between p-4 border-b border-gray-100 bg-orange-50">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-orange-100 flex items-center justify-center">
                    <i data-lucide="alert-triangle" class="w-5 h-5 text-orange-600"></i>
                </div>
                <div>
                    <h3 class="font-semibold text-gray-900">Spam</h3>
                    <p class="text-xs text-gray-500">{{ $spam->count() }} notification{{ $spam->count() !== 1 ? 's' : '' }}</p>
                </div>
            </div>
            @php $spamMuted = $settings->mute_spam; @endphp
            <label class="relative inline-flex items-center cursor-pointer">
                <input type="checkbox" class="sr-only peer" {{ $spamMuted ? '' : 'checked' }} onchange="toggleMute('mute_spam', this)">
                <div class="w-9 h-5 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-orange-500"></div>
                <span class="ms-2 text-xs {{ $spamMuted ? 'text-gray-400' : 'text-orange-600' }} font-medium">{{ $spamMuted ? 'Silent' : 'Active' }}</span>
            </label>
        </div>
        <div class="divide-y divide-gray-100 max-h-80 overflow-y-auto">
            @forelse($spam as $n)
                <div class="flex items-start gap-3 p-4 hover:bg-orange-50/50 transition-colors group" data-id="{{ $n->id }}">
                    <div class="flex-1 min-w-0">
                        <div class="flex items-start justify-between gap-2">
                            <p class="text-sm font-medium {{ $n->is_read ? 'text-gray-500' : 'text-gray-900' }}">{{ $n->title }}</p>
                            <span class="text-xs text-gray-400 whitespace-nowrap">{{ $n->created_at->diffForHumans() }}</span>
                        </div>
                        <p class="text-xs text-gray-500 mt-0.5">{{ $n->message }}</p>
                    </div>
                    <div class="flex items-center gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                        <button onclick="toggleImportant({{ $n->id }})" class="p-1.5 hover:bg-yellow-100 rounded transition-colors" title="{{ $n->is_important ? 'Unmark important' : 'Mark important' }}">
                            <i data-lucide="star" class="w-4 h-4 {{ $n->is_important ? 'text-yellow-500 fill-yellow-500' : 'text-gray-300' }}"></i>
                        </button>
                        @if(!$n->is_read)
                        <button onclick="markAsRead({{ $n->id }})" class="p-1.5 hover:bg-green-100 rounded transition-colors" title="Mark as read">
                            <i data-lucide="check" class="w-4 h-4 text-gray-400"></i>
                        </button>
                        @endif
                    </div>
                </div>
            @empty
                <div class="p-6 text-center text-gray-400">
                    <i data-lucide="alert-triangle" class="w-10 h-10 mx-auto mb-2 opacity-50"></i>
                    <p class="text-sm">No spam notifications</p>
                </div>
            @endforelse
        </div>
    </div>

    {{-- ═══════ Social Card ═══════ --}}
    <div class="card border-l-4 border-l-purple-500 overflow-hidden">
        <div class="flex items-center justify-between p-4 border-b border-gray-100 bg-purple-50">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-purple-100 flex items-center justify-center">
                    <i data-lucide="message-square" class="w-5 h-5 text-purple-600"></i>
                </div>
                <div>
                    <h3 class="font-semibold text-gray-900">Social</h3>
                    <p class="text-xs text-gray-500">{{ $social->count() }} notification{{ $social->count() !== 1 ? 's' : '' }}</p>
                </div>
            </div>
            @php $socialMuted = $settings->mute_social; @endphp
            <label class="relative inline-flex items-center cursor-pointer">
                <input type="checkbox" class="sr-only peer" {{ $socialMuted ? '' : 'checked' }} onchange="toggleMute('mute_social', this)">
                <div class="w-9 h-5 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-purple-500"></div>
                <span class="ms-2 text-xs {{ $socialMuted ? 'text-gray-400' : 'text-purple-600' }} font-medium">{{ $socialMuted ? 'Silent' : 'Active' }}</span>
            </label>
        </div>
        <div class="divide-y divide-gray-100 max-h-80 overflow-y-auto">
            @forelse($social as $n)
                <div class="flex items-start gap-3 p-4 hover:bg-purple-50/50 transition-colors group" data-id="{{ $n->id }}">
                    <div class="flex-1 min-w-0">
                        <div class="flex items-start justify-between gap-2">
                            <p class="text-sm font-medium {{ $n->is_read ? 'text-gray-500' : 'text-gray-900' }}">{{ $n->title }}</p>
                            <span class="text-xs text-gray-400 whitespace-nowrap">{{ $n->created_at->diffForHumans() }}</span>
                        </div>
                        <p class="text-xs text-gray-500 mt-0.5">{{ $n->message }}</p>
                    </div>
                    <div class="flex items-center gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                        <button onclick="toggleImportant({{ $n->id }})" class="p-1.5 hover:bg-yellow-100 rounded transition-colors" title="{{ $n->is_important ? 'Unmark important' : 'Mark important' }}">
                            <i data-lucide="star" class="w-4 h-4 {{ $n->is_important ? 'text-yellow-500 fill-yellow-500' : 'text-gray-300' }}"></i>
                        </button>
                        @if(!$n->is_read)
                        <button onclick="markAsRead({{ $n->id }})" class="p-1.5 hover:bg-green-100 rounded transition-colors" title="Mark as read">
                            <i data-lucide="check" class="w-4 h-4 text-gray-400"></i>
                        </button>
                        @endif
                    </div>
                </div>
            @empty
                <div class="p-6 text-center text-gray-400">
                    <i data-lucide="message-square" class="w-10 h-10 mx-auto mb-2 opacity-50"></i>
                    <p class="text-sm">No social notifications</p>
                </div>
            @endforelse
        </div>
    </div>

</div>

<script>
    function toggleImportant(id) {
        fetch('/admin/notifications/' + id + '/toggle-important', {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Content-Type': 'application/json' }
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) window.location.reload();
        });
    }

    function markAsRead(id) {
        fetch('/admin/notifications/' + id + '/read', {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Content-Type': 'application/json' }
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                const el = document.querySelector('[data-id="' + id + '"]');
                if (el) {
                    el.querySelector('.font-medium').classList.add('text-gray-500');
                    el.querySelector('.font-medium').classList.remove('text-gray-900');
                    const btn = el.querySelector('button[onclick*="markAsRead"]');
                    if (btn) btn.remove();
                }
            }
        });
    }

    function toggleMute(field, cb) {
        fetch('{{ route("notifications.toggle-mute") }}', {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Content-Type': 'application/json' },
            body: JSON.stringify({ field: field })
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                const label = cb.closest('label');
                const span = label.querySelector('span:last-child');
                if (data.value) {
                    span.textContent = 'Silent';
                    span.className = 'ms-2 text-xs text-gray-400 font-medium';
                } else {
                    span.textContent = 'Active';
                    const color = field === 'mute_spam' ? 'text-orange-600' : field === 'mute_social' ? 'text-purple-600' : 'text-blue-600';
                    span.className = 'ms-2 text-xs ' + color + ' font-medium';
                }
            } else {
                cb.checked = !cb.checked;
            }
        })
        .catch(() => {
            cb.checked = !cb.checked;
        });
    }
</script>
@endsection