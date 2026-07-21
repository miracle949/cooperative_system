@extends('layouts.admin')

@section('title', 'Announcements - CoopAdmin')

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
                    <span class="text-gray-900 font-medium">Announcements</span>
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
            <h1 class="text-2xl font-bold text-gray-900">Announcements</h1>
            <p class="text-sm text-gray-500">Cooperative announcements and updates</p>
        </div>
        @if($currentUser && $currentUser->role === 'admin')
        <button onclick="openModal('createAnnouncementModal')" class="btn btn-primary">
            <i data-lucide="megaphone" class="w-4 h-4"></i>
            Create Announcement
        </button>
        @endif
    </div>

    @if($currentUser && $currentUser->role === 'admin')
    <div id="createAnnouncementModal" class="modal-overlay hidden">
        <div class="modal max-w-xl">
            <div style="background: linear-gradient(135deg, #1E2A4A 0%, #25335A 100%); padding: 1.25rem 1.5rem;">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div style="width: 40px; height: 40px; background: rgba(255,255,255,0.15); border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                            <i data-lucide="megaphone" class="w-5 h-5" style="color: #fff;"></i>
                        </div>
                        <div>
                            <h2 class="text-xl font-bold" style="color: #fff; margin: 0;">Create Announcement</h2>
                            <p style="margin: 4px 0 0 0; color: rgba(255,255,255,0.7); font-size: 12px;">Publish a new announcement to all members</p>
                        </div>
                    </div>
                    <button onclick="closeModal('createAnnouncementModal')" style="background: rgba(255,255,255,0.1); border: none; width: 32px; height: 32px; border-radius: 8px; cursor: pointer; display: flex; align-items: center; justify-content: center;">
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
                    <textarea name="content" class="input" rows="5" placeholder="Write your announcement content here..." required></textarea>
                </div>
                <div class="flex justify-end gap-3 pt-2">
                    <button type="button" onclick="closeModal('createAnnouncementModal')" class="px-5 py-2.5 text-gray-700 font-medium rounded-lg hover:bg-gray-100 transition-colors">Cancel</button>
                    <button type="submit" class="px-5 py-2.5 bg-primary-600 text-white font-medium rounded-lg hover:bg-primary-700 transition-colors flex items-center gap-2">
                        <i data-lucide="send" class="w-4 h-4"></i>
                        Publish
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endif

    <div class="space-y-4" id="announcements-feed">
        @forelse($announcements as $announcement)
        <div class="card overflow-hidden" id="announcement-{{ $announcement->id }}">
            <div class="p-5">
                <div class="flex items-start gap-4">
                    <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-primary-400 to-primary-600 flex items-center justify-center flex-shrink-0 shadow-md">
                        <span class="text-white font-bold text-lg">
                            {{ strtoupper(substr($announcement->user->first_name ?? '', 0, 1)) }}{{ strtoupper(substr($announcement->user->last_name ?? '', 0, 1)) }}
                        </span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-2 mb-1 flex-wrap">
                            <span class="text-sm font-semibold text-gray-900">{{ $announcement->user->first_name ?? '' }} {{ $announcement->user->last_name ?? '' }}</span>
                            @if($announcement->user && $announcement->user->role === 'admin')
                                <span class="inline-flex items-center px-2 py-0.5 text-xs font-semibold rounded-full bg-blue-100 text-blue-800 border border-blue-200">
                                    <i data-lucide="shield" class="w-3 h-3 mr-1"></i>
                                    Admin
                                </span>
                            @endif
                            <span class="text-xs text-gray-400">
                                <i data-lucide="clock" class="w-3 h-3 inline mr-1"></i>
                                {{ $announcement->created_at->format('M d, Y h:i A') }}
                            </span>
                            @if($currentUser && $currentUser->role === 'admin')
                            <button onclick="deleteAnnouncement({{ $announcement->id }}, this)" class="ml-auto p-1.5 rounded-lg text-gray-400 hover:text-red-600 hover:bg-red-50 transition-all" title="Delete announcement">
                                <i data-lucide="trash-2" class="w-4 h-4"></i>
                            </button>
                            @endif
                        </div>
                        <h3 class="text-lg font-bold text-gray-900 mb-2">{{ $announcement->title }}</h3>
                        <p class="text-sm text-gray-700 leading-relaxed whitespace-pre-wrap">{{ $announcement->content }}</p>

                        <div class="flex items-center gap-4 mt-4 pt-3 border-t border-gray-100">
                            <button onclick="toggleLike({{ $announcement->id }}, this)"
                                class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-sm font-medium transition-all duration-200
                                {{ $announcement->likes->contains('user_id', $currentUser->id ?? 0) ? 'bg-red-50 text-red-600 hover:bg-red-100' : 'bg-gray-50 text-gray-600 hover:bg-gray-100' }}">
                                <i data-lucide="{{ $announcement->likes->contains('user_id', $currentUser->id ?? 0) ? 'heart' : 'heart' }}" class="w-4 h-4"></i>
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
                                    <div class="w-8 h-8 rounded-full {{ $comment->user && $comment->user->role === 'admin' ? 'bg-gradient-to-br from-blue-400 to-blue-600' : 'bg-gradient-to-br from-primary-300 to-primary-500' }} flex items-center justify-center flex-shrink-0">
                                        <span class="text-white font-bold text-xs">
                                            {{ $comment->user ? strtoupper(substr($comment->user->first_name ?? '', 0, 1)) . strtoupper(substr($comment->user->last_name ?? '', 0, 1)) : '??' }}
                                        </span>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center gap-2 mb-1">
                                            <span class="text-sm font-semibold text-gray-900">{{ $comment->user->first_name ?? '' }} {{ $comment->user->last_name ?? '' }}</span>
                                            @if($comment->user && $comment->user->role === 'admin')
                                                <span class="inline-flex items-center px-1.5 py-0.5 text-xs font-semibold rounded bg-blue-100 text-blue-700">Admin</span>
                                            @endif
                                            <span class="text-xs text-gray-400">{{ $comment->created_at->diffForHumans() }}</span>
                                        </div>
                                        <p class="text-sm text-gray-700">{{ $comment->comment }}</p>
                                    </div>
                                    @if($currentUser && $currentUser->role === 'admin')
                                    <button onclick="deleteComment({{ $announcement->id }}, {{ $comment->id }}, this)" class="flex-shrink-0 p-1.5 rounded-lg text-gray-400 hover:text-red-600 hover:bg-red-50 opacity-0 group-hover:opacity-100 transition-all" title="Delete comment">
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
        <div class="card">
            <div class="p-8 text-center">
                <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-gray-100 flex items-center justify-center">
                    <i data-lucide="megaphone" class="w-8 h-8 text-gray-300"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-1">No announcements yet</h3>
                <p class="text-sm text-gray-500">There are no announcements to display.</p>
            </div>
        </div>
        @endforelse
    </div>

    <script>
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
