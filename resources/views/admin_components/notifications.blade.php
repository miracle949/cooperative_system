@extends('layouts.admin')


@section('title', 'Communication - CoopAdmin')


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
                    <span class="text-gray-900 font-medium">Communication</span>
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


    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Communication</h1>
        <p class="text-sm text-gray-500">Manage your notifications, announcements, and alerts</p>
    </div>


    @if($currentUser && $currentUser->role === 'admin')
        <div id="createAnnouncementModal" class="modal-overlay hidden">
            <div class="modal max-w-xl">
                <div style="background: linear-gradient(135deg, #1E2A4A 0%, #25335A 100%); padding: 1.25rem 1.5rem;">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div
                                style="width: 40px; height: 40px; background: rgba(255,255,255,0.15); border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                                <i data-lucide="megaphone" class="w-5 h-5" style="color: #fff;"></i>
                            </div>
                            <div>
                                <h2 class="text-xl font-bold" style="color: #fff; margin: 0;">Create Announcement</h2>
                                <p style="margin: 4px 0 0 0; color: rgba(255,255,255,0.7); font-size: 12px;">Publish a new
                                    announcement to all members</p>
                            </div>
                        </div>
                        <button onclick="closeModal('createAnnouncementModal')"
                            style="background: rgba(255,255,255,0.1); border: none; width: 32px; height: 32px; border-radius: 8px; cursor: pointer; display: flex; align-items: center; justify-content: center;">
                            <i data-lucide="x" class="w-5 h-5" style="color: #fff;"></i>
                        </button>
                    </div>
                </div>
                <form method="POST" action="{{ route('announcements.store') }}" class="p-6 space-y-4">
                    @csrf
                    <div>
                        <input type="text" name="title" class="input" placeholder="Announcement title..." required>
                    </div>
                    <div>
                        <textarea name="content" class="input" rows="5" placeholder="Write your announcement content here..."
                            required></textarea>
                    </div>
                    <div class="flex justify-end gap-3 pt-2">
                        <button type="button" onclick="closeModal('createAnnouncementModal')"
                            class="px-5 py-2.5 text-gray-700 font-medium rounded-lg hover:bg-gray-100 transition-colors">Cancel</button>
                        <button type="submit"
                            class="px-5 py-2.5 bg-primary-600 text-white font-medium rounded-lg hover:bg-primary-700 transition-colors flex items-center gap-2">
                            <i data-lucide="send" class="w-4 h-4"></i>
                            Publish
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif


    <div id="notificationDetailModal" class="modal-overlay hidden">
        <div class="modal max-w-xl">
            <div id="notifDetailHeader"
                style="background: linear-gradient(135deg, #1E2A4A 0%, #25335A 100%); padding: 1.25rem 1.5rem;">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div
                            style="width: 40px; height: 40px; background: rgba(255,255,255,0.15); border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                            <i data-lucide="bell" class="w-5 h-5" style="color: #fff;"></i>
                        </div>
                        <div>
                            <h2 id="notifDetailTitle" class="text-xl font-bold" style="color: #fff; margin: 0;">Notification
                            </h2>
                            <p id="notifDetailTime"
                                style="margin: 4px 0 0 0; color: rgba(255,255,255,0.7); font-size: 12px;"></p>
                        </div>
                    </div>
                    <button onclick="closeModal('notificationDetailModal')"
                        style="background: rgba(255,255,255,0.1); border: none; width: 32px; height: 32px; border-radius: 8px; cursor: pointer; display: flex; align-items: center; justify-content: center;">
                        <i data-lucide="x" class="w-5 h-5" style="color: #fff;"></i>
                    </button>
                </div>
            </div>
            <div class="p-6">
                <div id="notifDetailBody" class="text-sm text-gray-700 leading-relaxed whitespace-pre-wrap"></div>
                <div id="notifDetailFooter" class="mt-6 pt-4 border-t border-gray-100 flex items-center gap-3">
                    <span id="notifDetailCategory"
                        class="inline-flex items-center px-2.5 py-1 text-xs font-semibold rounded-full"></span>
                    <span id="notifDetailRead" class="text-xs text-gray-400"></span>
                </div>
            </div>
        </div>
    </div>


    {{-- ═══════════ Notifications Section ═══════════ --}}
    <div class="card mb-8">
        <div class="p-5 border-b border-gray-100">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-primary-100 flex items-center justify-center">
                    <i data-lucide="bell" class="w-5 h-5 text-primary-600"></i>
                </div>
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">Notifications</h2>
                    <p class="text-sm text-gray-500">Your alerts and system notifications</p>
                </div>
            </div>
        </div>
        <div class="p-5">
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
                                <p class="text-xs text-gray-500">{{ $important->count() }}
                                    notification{{ $important->count() !== 1 ? 's' : '' }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="divide-y divide-gray-100 max-h-80 overflow-y-auto">
                        @forelse($important as $n)
                            <div class="flex items-start gap-3 p-4 hover:bg-red-50/50 transition-colors group cursor-pointer"
                                data-id="{{ $n->id }}"
                                onclick="openNotificationDetail({{ $n->id }}, '{{ addslashes($n->title) }}', '{{ addslashes($n->message) }}', '{{ $n->created_at->format('M d, Y h:i A') }}', 'important', {{ $n->is_read ? 'true' : 'false' }})">
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-start justify-between gap-2">
                                        <p class="text-sm font-medium text-gray-900 {{ $n->is_read ? 'text-gray-500' : '' }}">
                                            {{ $n->title }}</p>
                                        <span
                                            class="text-xs text-gray-400 whitespace-nowrap">{{ $n->created_at->diffForHumans() }}</span>
                                    </div>
                                    <p class="text-xs text-gray-500 mt-0.5">{{ $n->message }}</p>
                                </div>
                                <div class="flex items-center gap-1 opacity-0 group-hover:opacity-100 transition-opacity"
                                    onclick="event.stopPropagation()">
                                    <button onclick="event.stopPropagation(); toggleImportant({{ $n->id }})"
                                        class="p-1.5 hover:bg-red-100 rounded transition-colors" title="Unmark important">
                                        <i data-lucide="star" class="w-4 h-4 text-red-500 fill-red-500"></i>
                                    </button>
                                    @if(!$n->is_read)
                                        <button onclick="event.stopPropagation(); markAsRead({{ $n->id }})"
                                            class="p-1.5 hover:bg-green-100 rounded transition-colors" title="Mark as read">
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
                                <p class="text-xs text-gray-500">{{ $inboxTotal }} item{{ $inboxTotal !== 1 ? 's' : '' }}
                                </p>
                            </div>
                        </div>
                        @php $inboxMuted = $settings->mute_inbox; @endphp
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" class="sr-only peer" {{ $inboxMuted ? '' : 'checked' }}
                                onchange="toggleMute('mute_inbox', this)">
                            <div
                                class="w-9 h-5 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-blue-600">
                            </div>
                            <span
                                class="ms-2 text-xs {{ $inboxMuted ? 'text-gray-400' : 'text-blue-600' }} font-medium">{{ $inboxMuted ? 'Silent' : 'Active' }}</span>
                        </label>
                    </div>
                    <div class="divide-y divide-gray-100 max-h-80 overflow-y-auto">
                        @forelse($inboxPaginated as $entry)
                            @if($entry->type === 'notification')
                                @php $n = $entry->item; @endphp
                                <div class="flex items-start gap-3 p-4 hover:bg-blue-50/50 transition-colors group cursor-pointer"
                                    data-id="{{ $n->id }}"
                                    onclick="openNotificationDetail({{ $n->id }}, '{{ addslashes($n->title) }}', '{{ addslashes($n->message) }}', '{{ $n->created_at->format('M d, Y h:i A') }}', 'inbox', {{ $n->is_read ? 'true' : 'false' }})">
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-start justify-between gap-2">
                                            <p class="text-sm font-medium {{ $n->is_read ? 'text-gray-500' : 'text-gray-900' }}">
                                                {{ $n->title }}</p>
                                            <span
                                                class="text-xs text-gray-400 whitespace-nowrap">{{ $n->created_at->diffForHumans() }}</span>
                                        </div>
                                        <p class="text-xs text-gray-500 mt-0.5">{{ $n->message }}</p>
                                    </div>
                                    <div class="flex items-center gap-1 opacity-0 group-hover:opacity-100 transition-opacity"
                                        onclick="event.stopPropagation()">
                                        <button onclick="event.stopPropagation(); toggleImportant({{ $n->id }})"
                                            class="p-1.5 hover:bg-yellow-100 rounded transition-colors"
                                            title="{{ $n->is_important ? 'Unmark important' : 'Mark important' }}">
                                            <i data-lucide="star"
                                                class="w-4 h-4 {{ $n->is_important ? 'text-yellow-500 fill-yellow-500' : 'text-gray-300' }}"></i>
                                        </button>
                                        @if(!$n->is_read)
                                            <button onclick="event.stopPropagation(); markAsRead({{ $n->id }})"
                                                class="p-1.5 hover:bg-green-100 rounded transition-colors" title="Mark as read">
                                                <i data-lucide="check" class="w-4 h-4 text-gray-400"></i>
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            @elseif($entry->type === 'resignation')
                                @php $r = $entry->item; @endphp
                                <div class="flex items-start gap-3 p-4 hover:bg-blue-50/50 transition-colors group cursor-pointer"
                                    onclick="window.location.href='{{ route('dashboard.members') }}'">
                                    <div
                                        class="w-9 h-9 rounded-full bg-gradient-to-br from-red-400 to-red-600 flex items-center justify-center flex-shrink-0">
                                        <span class="text-white font-bold text-xs">
                                            {{ strtoupper(substr($r->user->first_name ?? '?', 0, 1)) }}{{ strtoupper(substr($r->user->last_name ?? '?', 0, 1)) }}
                                        </span>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-start justify-between gap-2">
                                            <p class="text-sm font-medium text-gray-900">{{ $r->user->first_name ?? '' }}
                                                {{ $r->user->last_name ?? '' }}</p>
                                            <span
                                                class="text-xs text-gray-400 whitespace-nowrap">{{ $r->created_at->diffForHumans() }}</span>
                                        </div>
                                        <p class="text-xs text-gray-500 mt-0.5">Submitted a resignation request</p>
                                        <div class="mt-1.5">
                                            @if($r->status === 'pending')
                                                <span
                                                    class="inline-flex items-center px-2 py-0.5 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-700">
                                                    <i data-lucide="clock" class="w-3 h-3 mr-1"></i>Pending
                                                </span>
                                            @elseif($r->status === 'approved')
                                                <span
                                                    class="inline-flex items-center px-2 py-0.5 text-xs font-semibold rounded-full bg-green-100 text-green-700">
                                                    <i data-lucide="check-circle" class="w-3 h-3 mr-1"></i>Approved
                                                </span>
                                            @elseif($r->status === 'rejected')
                                                <span
                                                    class="inline-flex items-center px-2 py-0.5 text-xs font-semibold rounded-full bg-red-100 text-red-700">
                                                    <i data-lucide="x-circle" class="w-3 h-3 mr-1"></i>Rejected
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @empty
                            <div class="p-6 text-center text-gray-400">
                                <i data-lucide="inbox" class="w-10 h-10 mx-auto mb-2 opacity-50"></i>
                                <p class="text-sm">No inbox notifications</p>
                            </div>
                        @endforelse
                    </div>
                    @if($inboxLastPage > 1)
                        <div class="flex items-center justify-between p-3 border-t border-gray-100 bg-gray-50/50">
                            <a href="?inbox_page={{ max(1, $inboxPage - 1) }}&social_page={{ $socialPage }}"
                                class="text-xs font-medium text-gray-500 hover:text-primary-600 px-3 py-1.5 rounded-lg hover:bg-white transition-colors {{ $inboxPage <= 1 ? 'pointer-events-none opacity-40' : '' }}">
                                <i data-lucide="chevron-left" class="w-3 h-3 inline"></i> Prev
                            </a>
                            <span class="text-xs text-gray-400">{{ $inboxPage }} / {{ $inboxLastPage }}</span>
                            <a href="?inbox_page={{ min($inboxLastPage, $inboxPage + 1) }}&social_page={{ $socialPage }}"
                                class="text-xs font-medium text-gray-500 hover:text-primary-600 px-3 py-1.5 rounded-lg hover:bg-white transition-colors {{ $inboxPage >= $inboxLastPage ? 'pointer-events-none opacity-40' : '' }}">
                                Next <i data-lucide="chevron-right" class="w-3 h-3 inline"></i>
                            </a>
                        </div>
                    @endif
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
                                <p class="text-xs text-gray-500">{{ $spam->count() }}
                                    notification{{ $spam->count() !== 1 ? 's' : '' }}</p>
                            </div>
                        </div>
                        @php $spamMuted = $settings->mute_spam; @endphp
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" class="sr-only peer" {{ $spamMuted ? '' : 'checked' }}
                                onchange="toggleMute('mute_spam', this)">
                            <div
                                class="w-9 h-5 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-orange-500">
                            </div>
                            <span
                                class="ms-2 text-xs {{ $spamMuted ? 'text-gray-400' : 'text-orange-600' }} font-medium">{{ $spamMuted ? 'Silent' : 'Active' }}</span>
                        </label>
                    </div>
                    <div class="divide-y divide-gray-100 max-h-80 overflow-y-auto">
                        @forelse($spam as $n)
                            <div class="flex items-start gap-3 p-4 hover:bg-orange-50/50 transition-colors group cursor-pointer"
                                data-id="{{ $n->id }}"
                                onclick="openNotificationDetail({{ $n->id }}, '{{ addslashes($n->title) }}', '{{ addslashes($n->message) }}', '{{ $n->created_at->format('M d, Y h:i A') }}', 'spam', {{ $n->is_read ? 'true' : 'false' }})">
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-start justify-between gap-2">
                                        <p class="text-sm font-medium {{ $n->is_read ? 'text-gray-500' : 'text-gray-900' }}">
                                            {{ $n->title }}</p>
                                        <span
                                            class="text-xs text-gray-400 whitespace-nowrap">{{ $n->created_at->diffForHumans() }}</span>
                                    </div>
                                    <p class="text-xs text-gray-500 mt-0.5">{{ $n->message }}</p>
                                </div>
                                <div class="flex items-center gap-1 opacity-0 group-hover:opacity-100 transition-opacity"
                                    onclick="event.stopPropagation()">
                                    <button onclick="event.stopPropagation(); toggleImportant({{ $n->id }})"
                                        class="p-1.5 hover:bg-yellow-100 rounded transition-colors"
                                        title="{{ $n->is_important ? 'Unmark important' : 'Mark important' }}">
                                        <i data-lucide="star"
                                            class="w-4 h-4 {{ $n->is_important ? 'text-yellow-500 fill-yellow-500' : 'text-gray-300' }}"></i>
                                    </button>
                                    @if(!$n->is_read)
                                        <button onclick="event.stopPropagation(); markAsRead({{ $n->id }})"
                                            class="p-1.5 hover:bg-green-100 rounded transition-colors" title="Mark as read">
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
                                <p class="text-xs text-gray-500">{{ $socialTotal }} item{{ $socialTotal !== 1 ? 's' : '' }}
                                </p>
                            </div>
                        </div>
                        @php $socialMuted = $settings->mute_social; @endphp
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" class="sr-only peer" {{ $socialMuted ? '' : 'checked' }}
                                onchange="toggleMute('mute_social', this)">
                            <div
                                class="w-9 h-5 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-purple-500">
                            </div>
                            <span
                                class="ms-2 text-xs {{ $socialMuted ? 'text-gray-400' : 'text-purple-600' }} font-medium">{{ $socialMuted ? 'Silent' : 'Active' }}</span>
                        </label>
                    </div>
                    <div class="divide-y divide-gray-100 max-h-80 overflow-y-auto">
                        @forelse($socialPaginated as $entry)
                            @if($entry->type === 'notification')
                                @php $n = $entry->item; @endphp
                                <div class="flex items-start gap-3 p-4 hover:bg-purple-50/50 transition-colors group cursor-pointer"
                                    data-id="{{ $n->id }}"
                                    onclick="openNotificationDetail({{ $n->id }}, '{{ addslashes($n->title) }}', '{{ addslashes($n->message) }}', '{{ $n->created_at->format('M d, Y h:i A') }}', 'social', {{ $n->is_read ? 'true' : 'false' }})">
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-start justify-between gap-2">
                                            <p class="text-sm font-medium {{ $n->is_read ? 'text-gray-500' : 'text-gray-900' }}">
                                                {{ $n->title }}</p>
                                            <span
                                                class="text-xs text-gray-400 whitespace-nowrap">{{ $n->created_at->diffForHumans() }}</span>
                                        </div>
                                        <p class="text-xs text-gray-500 mt-0.5">{{ $n->message }}</p>
                                    </div>
                                    <div class="flex items-center gap-1 opacity-0 group-hover:opacity-100 transition-opacity"
                                        onclick="event.stopPropagation()">
                                        <button onclick="event.stopPropagation(); toggleImportant({{ $n->id }})"
                                            class="p-1.5 hover:bg-yellow-100 rounded transition-colors"
                                            title="{{ $n->is_important ? 'Unmark important' : 'Mark important' }}">
                                            <i data-lucide="star"
                                                class="w-4 h-4 {{ $n->is_important ? 'text-yellow-500 fill-yellow-500' : 'text-gray-300' }}"></i>
                                        </button>
                                        @if(!$n->is_read)
                                            <button onclick="event.stopPropagation(); markAsRead({{ $n->id }})"
                                                class="p-1.5 hover:bg-green-100 rounded transition-colors" title="Mark as read">
                                                <i data-lucide="check" class="w-4 h-4 text-gray-400"></i>
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            @elseif($entry->type === 'comment')
                                @php $c = $entry->item; @endphp
                                <div class="flex items-start gap-3 p-4 hover:bg-purple-50/50 transition-colors group cursor-pointer"
                                    onclick="scrollToAnnouncement({{ $c->announcement_id }})">
                                    <div
                                        class="w-9 h-9 rounded-full bg-gradient-to-br from-purple-400 to-purple-600 flex items-center justify-center flex-shrink-0">
                                        <span class="text-white font-bold text-xs">
                                            {{ strtoupper(substr($c->user->first_name ?? '?', 0, 1)) }}{{ strtoupper(substr($c->user->last_name ?? '?', 0, 1)) }}
                                        </span>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-start justify-between gap-2">
                                            <p class="text-sm font-medium text-gray-900">{{ $c->user->first_name ?? '' }}
                                                {{ $c->user->last_name ?? '' }}</p>
                                            <span
                                                class="text-xs text-gray-400 whitespace-nowrap">{{ $c->created_at->diffForHumans() }}</span>
                                        </div>
                                        <p class="text-xs text-gray-500 mt-0.5">Commented on <span
                                                class="font-medium text-gray-700">{{ $c->announcement->title ?? 'an announcement' }}</span>
                                        </p>
                                        <p class="text-xs text-gray-600 mt-1 bg-purple-50 rounded-lg px-3 py-2">
                                            {{ Str::limit($c->comment, 100) }}</p>
                                    </div>
                                </div>
                            @endif
                        @empty
                            <div class="p-6 text-center text-gray-400">
                                <i data-lucide="message-square" class="w-10 h-10 mx-auto mb-2 opacity-50"></i>
                                <p class="text-sm">No social notifications</p>
                            </div>
                        @endforelse
                    </div>
                    @if($socialLastPage > 1)
                        <div class="flex items-center justify-between p-3 border-t border-gray-100 bg-gray-50/50">
                            <a href="?inbox_page={{ $inboxPage }}&social_page={{ max(1, $socialPage - 1) }}"
                                class="text-xs font-medium text-gray-500 hover:text-primary-600 px-3 py-1.5 rounded-lg hover:bg-white transition-colors {{ $socialPage <= 1 ? 'pointer-events-none opacity-40' : '' }}">
                                <i data-lucide="chevron-left" class="w-3 h-3 inline"></i> Prev
                            </a>
                            <span class="text-xs text-gray-400">{{ $socialPage }} / {{ $socialLastPage }}</span>
                            <a href="?inbox_page={{ $inboxPage }}&social_page={{ min($socialLastPage, $socialPage + 1) }}"
                                class="text-xs font-medium text-gray-500 hover:text-primary-600 px-3 py-1.5 rounded-lg hover:bg-white transition-colors {{ $socialPage >= $socialLastPage ? 'pointer-events-none opacity-40' : '' }}">
                                Next <i data-lucide="chevron-right" class="w-3 h-3 inline"></i>
                            </a>
                        </div>
                    @endif
                </div>


            </div>
        </div>
    </div>


    {{-- ═══════════ Announcements Section ═══════════ --}}
    <div class="card">
        <div class="p-5 border-b border-gray-100">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-primary-100 flex items-center justify-center">
                        <i data-lucide="megaphone" class="w-5 h-5 text-primary-600"></i>
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900">Announcements</h2>
                        <p class="text-sm text-gray-500">Cooperative announcements and updates</p>
                    </div>
                </div>
                @if($currentUser && $currentUser->role === 'admin')
                    <button onclick="openModal('createAnnouncementModal')" class="btn btn-primary">
                        <i data-lucide="megaphone" class="w-4 h-4"></i>
                        Create Announcement
                    </button>
                @endif
            </div>
        </div>
        <div class="p-5 space-y-4" id="announcements-feed">
            @forelse($announcements as $announcement)
                <div class="card overflow-hidden" id="announcement-{{ $announcement->id }}">
                    <div class="p-5">
                        <div class="flex items-start gap-4">
                            <div
                                class="w-12 h-12 rounded-xl bg-gradient-to-br from-primary-400 to-primary-600 flex items-center justify-center flex-shrink-0 shadow-md">
                                <span class="text-white font-bold text-lg">
                                    {{ strtoupper(substr($announcement->user->first_name ?? '', 0, 1)) }}{{ strtoupper(substr($announcement->user->last_name ?? '', 0, 1)) }}
                                </span>
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-2 mb-1 flex-wrap">
                                    <span
                                        class="text-sm font-semibold text-gray-900">{{ $announcement->user->first_name ?? '' }}
                                        {{ $announcement->user->last_name ?? '' }}</span>
                                    @if($announcement->user && $announcement->user->role === 'admin')
                                        <span
                                            class="inline-flex items-center px-2 py-0.5 text-xs font-semibold rounded-full bg-blue-100 text-blue-800 border border-blue-200">
                                            <i data-lucide="shield" class="w-3 h-3 mr-1"></i>
                                            Admin
                                        </span>
                                    @endif
                                    <span class="text-xs text-gray-400">
                                        <i data-lucide="clock" class="w-3 h-3 inline mr-1"></i>
                                        {{ $announcement->created_at->format('M d, Y h:i A') }}
                                    </span>
                                    @if($currentUser && $currentUser->role === 'admin')
                                        <button onclick="deleteAnnouncement({{ $announcement->id }}, this)"
                                            class="ml-auto p-1.5 rounded-lg text-gray-400 hover:text-red-600 hover:bg-red-50 transition-all"
                                            title="Delete announcement">
                                            <i data-lucide="trash-2" class="w-4 h-4"></i>
                                        </button>
                                    @endif
                                </div>
                                <h3 class="text-lg font-bold text-gray-900 mb-2">{{ $announcement->title }}</h3>
                                <p class="text-sm text-gray-700 leading-relaxed whitespace-pre-wrap">{{ trim($announcement->content) }}</p>


                                <div class="flex items-center gap-4 mt-4 pt-3 border-t border-gray-100">
                                    <button onclick="toggleLike({{ $announcement->id }}, this)"
                                        class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-sm font-medium transition-all duration-200
                                    {{ $announcement->likes->contains('user_id', $currentUser->id ?? 0) ? 'bg-red-50 text-red-600 hover:bg-red-100' : 'bg-gray-50 text-gray-600 hover:bg-gray-100' }}">
                                        <i data-lucide="heart" class="w-4 h-4"></i>
                                        <span class="like-count">{{ $announcement->likes_count }}</span>
                                    </button>
                                    <button onclick="toggleComments({{ $announcement->id }})"
                                        class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-sm font-medium bg-gray-50 text-gray-600 hover:bg-gray-100 transition-all duration-200">
                                        <i data-lucide="message-circle" class="w-4 h-4"></i>
                                        <span>{{ $announcement->comments_count }}</span>
                                    </button>
                                </div>


                                <div id="comments-{{ $announcement->id }}" class="mt-4 space-y-3" style="display: none;">
                                    <div class="space-y-3">
                                        @foreach($announcement->comments as $comment)
                                            <div class="flex gap-3 bg-gray-50 rounded-lg p-3 group" id="comment-{{ $comment->id }}">
                                                <div
                                                    class="w-8 h-8 rounded-full {{ $comment->user && $comment->user->role === 'admin' ? 'bg-gradient-to-br from-blue-400 to-blue-600' : 'bg-gradient-to-br from-primary-300 to-primary-500' }} flex items-center justify-center flex-shrink-0">
                                                    <span class="text-white font-bold text-xs">
                                                        {{ $comment->user ? strtoupper(substr($comment->user->first_name ?? '', 0, 1)) . strtoupper(substr($comment->user->last_name ?? '', 0, 1)) : '??' }}
                                                    </span>
                                                </div>
                                                <div class="flex-1 min-w-0">
                                                    <div class="flex items-center gap-2 mb-1">
                                                        <span
                                                            class="text-sm font-semibold text-gray-900">{{ $comment->user->first_name ?? '' }}
                                                            {{ $comment->user->last_name ?? '' }}</span>
                                                        @if($comment->user && $comment->user->role === 'admin')
                                                            <span
                                                                class="inline-flex items-center px-1.5 py-0.5 text-xs font-semibold rounded bg-blue-100 text-blue-700">Admin</span>
                                                        @endif
                                                        <span
                                                            class="text-xs text-gray-400">{{ $comment->created_at->diffForHumans() }}</span>
                                                    </div>
                                                    <p class="text-sm text-gray-700">{{ $comment->comment }}</p>
                                                </div>
                                                @if($currentUser && $currentUser->role === 'admin')
                                                    <button onclick="deleteComment({{ $announcement->id }}, {{ $comment->id }}, this)"
                                                        class="flex-shrink-0 p-1.5 rounded-lg text-gray-400 hover:text-red-600 hover:bg-red-50 opacity-0 group-hover:opacity-100 transition-all"
                                                        title="Delete comment">
                                                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                                                    </button>
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                    <form onsubmit="postComment(event, {{ $announcement->id }})" class="flex gap-2">
                                        @csrf
                                        <input type="text" class="input flex-1" placeholder="Write a comment..." required>
                                        <button type="submit" class="btn btn-primary btn-sm px-4">
                                            <i data-lucide="send" class="w-4 h-4"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="p-8 text-center">
                    <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-gray-100 flex items-center justify-center">
                        <i data-lucide="megaphone" class="w-8 h-8 text-gray-300"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-1">No announcements yet</h3>
                    <p class="text-sm text-gray-500">There are no announcements to display.</p>
                </div>
            @endforelse
        </div>
    </div>


    <script>
        const categoryColors = {
            important: { bg: 'bg-red-100', text: 'text-red-700', label: 'Important' },
            inbox: { bg: 'bg-blue-100', text: 'text-blue-700', label: 'Inbox' },
            spam: { bg: 'bg-orange-100', text: 'text-orange-700', label: 'Spam' },
            social: { bg: 'bg-purple-100', text: 'text-purple-700', label: 'Social' }
        };


        function openNotificationDetail(id, title, message, time, category, isRead) {
            document.getElementById('notifDetailTitle').textContent = title;
            document.getElementById('notifDetailTime').textContent = time;
            document.getElementById('notifDetailBody').textContent = message;


            const cat = categoryColors[category] || categoryColors.inbox;
            const catBadge = document.getElementById('notifDetailCategory');
            catBadge.className = 'inline-flex items-center px-2.5 py-1 text-xs font-semibold rounded-full ' + cat.bg + ' ' + cat.text;
            catBadge.textContent = cat.label;


            document.getElementById('notifDetailRead').textContent = isRead ? 'Read' : 'Unread';


            openModal('notificationDetailModal');


            if (!isRead) {
                markAsRead(id);
            }
        }


        function scrollToAnnouncement(announcementId) {
            const el = document.getElementById('announcement-' + announcementId);
            if (el) {
                el.scrollIntoView({ behavior: 'smooth', block: 'center' });
                el.classList.add('ring-2', 'ring-purple-400', 'ring-offset-2');
                setTimeout(() => {
                    el.classList.remove('ring-2', 'ring-purple-400', 'ring-offset-2');
                }, 2000);
            }
        }


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


        function toggleLike(announcementId, btn) {
            fetch('/announcements/' + announcementId + '/like', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                },
            })
                .then(r => r.json())
                .then(data => {
                    if (data.success) {
                        const countSpan = btn.querySelector('.like-count');
                        countSpan.textContent = data.count;
                        if (data.liked) {
                            btn.classList.remove('bg-gray-50', 'text-gray-600', 'hover:bg-gray-100');
                            btn.classList.add('bg-red-50', 'text-red-600', 'hover:bg-red-100');
                        } else {
                            btn.classList.remove('bg-red-50', 'text-red-600', 'hover:bg-red-100');
                            btn.classList.add('bg-gray-50', 'text-gray-600', 'hover:bg-gray-100');
                        }
                    }
                })
                .catch(() => {
                    showToast('Error', 'Failed to update like.', 'error');
                });
        }


        function toggleComments(announcementId) {
            const el = document.getElementById('comments-' + announcementId);
            el.style.display = el.style.display === 'none' ? 'block' : 'none';
        }


        function postComment(event, announcementId) {
            event.preventDefault();
            const form = event.target;
            const input = form.querySelector('input[type="text"]');
            const comment = input.value.trim();
            if (!comment) return;


            fetch('/announcements/' + announcementId + '/comment', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                },
                body: JSON.stringify({ comment: comment }),
            })
                .then(r => r.json())
                .then(data => {
                    if (data.success) {
                        const c = data.comment;
                        const isAdmin = c.user.role === 'admin';
                        const badge = isAdmin ? '<span class="inline-flex items-center px-1.5 py-0.5 text-xs font-semibold rounded bg-blue-100 text-blue-700">Admin</span>' : '';
                        const avatarBg = isAdmin ? 'bg-gradient-to-br from-blue-400 to-blue-600' : 'bg-gradient-to-br from-primary-300 to-primary-500';
                        const initials = (c.user.first_name?.[0] || '') + (c.user.last_name?.[0] || '');


                        const isCurrentAdmin = {{ $currentUser && $currentUser->role === 'admin' ? 'true' : 'false' }};
                        const deleteBtn = isCurrentAdmin
                            ? `<button onclick="deleteComment(${announcementId}, ${c.id}, this)" class="flex-shrink-0 p-1.5 rounded-lg text-gray-400 hover:text-red-600 hover:bg-red-50 opacity-0 group-hover:opacity-100 transition-all" title="Delete comment"><i data-lucide="trash-2" class="w-4 h-4"></i></button>`
                            : '';


                        const div = document.createElement('div');
                        div.className = 'flex gap-3 bg-gray-50 rounded-lg p-3 group';
                        div.id = 'comment-' + c.id;
                        div.innerHTML = `
                        <div class="w-8 h-8 rounded-full ${avatarBg} flex items-center justify-center flex-shrink-0">
                            <span class="text-white font-bold text-xs">${initials.toUpperCase()}</span>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2 mb-1">
                                <span class="text-sm font-semibold text-gray-900">${c.user.first_name} ${c.user.last_name}</span>
                                ${badge}
                                <span class="text-xs text-gray-400">${c.created_at}</span>
                            </div>
                            <p class="text-sm text-gray-700">${c.comment}</p>
                        </div>
                        ${deleteBtn}
                    `;


                        const container = form.closest('#comments-' + announcementId).querySelector('.space-y-3');
                        container.appendChild(div);
                        input.value = '';


                        const countEl = form.closest('.card').querySelector('button[onclick*="toggleComments"] span:last-child');
                        if (countEl) countEl.textContent = parseInt(countEl.textContent) + 1;


                        lucide.createIcons();
                    }
                })
                .catch(() => {
                    showToast('Error', 'Failed to post comment.', 'error');
                });
        }


        function deleteComment(announcementId, commentId, btn) {
            if (!confirm('Delete this comment?')) return;


            const card = btn.closest('.card');


            fetch('/announcements/' + announcementId + '/comment/' + commentId + '/delete', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                },
            })
                .then(r => r.json())
                .then(data => {
                    if (data.success) {
                        const el = document.getElementById('comment-' + commentId);
                        if (el) el.remove();


                        if (card) {
                            const countEl = card.querySelector('button[onclick*="toggleComments"] span:last-child');
                            if (countEl) countEl.textContent = data.count;
                        }


                        showToast('Deleted', 'Comment removed.', 'success');
                    }
                })
                .catch(() => {
                    showToast('Error', 'Failed to delete comment.', 'error');
                });
        }


        function deleteAnnouncement(announcementId, btn) {
            if (!confirm('Delete this announcement and all its comments?')) return;


            fetch('/announcements/' + announcementId + '/delete', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                },
            })
                .then(r => r.json())
                .then(data => {
                    if (data.success) {
                        const el = document.getElementById('announcement-' + announcementId);
                        if (el) el.remove();
                        showToast('Deleted', 'Announcement removed.', 'success');
                    }
                })
                .catch(() => {
                    showToast('Error', 'Failed to delete announcement.', 'error');
                });
        }
    </script>
@endsection