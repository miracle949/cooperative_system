@extends('layouts.admin')

@section('title', 'Members - CoopAdmin')

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
                    <span class="text-gray-900 font-medium">Members</span>
                </li>
            </ol>
        </nav>
    </div>

    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Members</h1>
            <p class="text-sm text-gray-500">Manage your cooperative members</p>
        </div>
        <div class="flex items-center gap-3">
            <button onclick="openModal('addMemberModal')" class="btn btn-primary">
                <i data-lucide="plus" class="w-4 h-4"></i>
                Add Member
            </button>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 mb-6">
        <div class="flex flex-col md:flex-row gap-4 items-center">
            <div class="flex-1 w-full">
                <form action="{{ route('dashboard.members') }}" method="GET">
                    <div class="relative">
                        <i data-lucide="search" class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400"></i>
                        <input type="text" name="search" placeholder="Search by name, ID, or email..." class="input pl-10 w-full" value="{{ request('search') }}">
                    </div>
                </form>
            </div>
            <div class="flex gap-1 bg-gray-100 p-1 rounded-lg">
                <a href="{{ route('dashboard.members', ['filter' => 'all']) }}" 
                    class="px-4 py-2 text-sm font-medium rounded-md transition-all {{ request('filter', 'all') === 'all' ? 'bg-white text-gray-900 shadow-sm' : 'text-gray-600 hover:text-gray-900' }}">
                    All
                </a>
                <a href="{{ route('dashboard.members', ['filter' => 'active']) }}" 
                    class="px-4 py-2 text-sm font-medium rounded-md transition-all {{ request('filter') === 'active' ? 'bg-white text-green-600 shadow-sm' : 'text-gray-600 hover:text-gray-900' }}">
                    Active
                </a>
                <a href="{{ route('dashboard.members', ['filter' => 'pending']) }}" 
                    class="px-4 py-2 text-sm font-medium rounded-md transition-all {{ request('filter') === 'pending' ? 'bg-white text-yellow-600 shadow-sm' : 'text-gray-600 hover:text-gray-900' }}">
                    Pending
                    @if($pendingRequests->count() > 0)
                        <span class="ml-1 px-1.5 py-0.5 bg-yellow-100 text-yellow-700 text-xs font-semibold rounded-full">{{ $pendingRequests->count() }}</span>
                    @endif
                </a>
                <a href="{{ route('dashboard.members', ['filter' => 'inactive']) }}" 
                    class="px-4 py-2 text-sm font-medium rounded-md transition-all {{ request('filter') === 'inactive' ? 'bg-white text-gray-600 shadow-sm' : 'text-gray-600 hover:text-gray-900' }}">
                    Inactive
                </a>
            </div>
        </div>
    </div>



    <!-- Members Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5">
        @forelse($members as $member)
            <div class="group bg-white rounded-xl shadow-sm border border-gray-100 hover:shadow-lg hover:border-primary-200 transition-all duration-300 overflow-hidden {{ $member->role === 'pending' ? 'border-yellow-200 bg-gradient-to-br from-white to-yellow-50' : '' }}">
                <!-- Card Header with Avatar -->
                <div class="relative p-5 pb-4">
                    <div class="absolute top-4 right-4">
                        <div class="dropdown relative">
                            <button class="p-2 rounded-lg hover:bg-gray-100 transition-colors opacity-0 group-hover:opacity-100" onclick="event.stopPropagation(); toggleDropdown('menu-{{ $member->id }}')">
                                <i data-lucide="more-horizontal" class="w-5 h-5 text-gray-400"></i>
                            </button>
                            <div id="menu-{{ $member->id }}" class="dropdown-menu hidden right-0 top-full min-w-[140px] bg-white rounded-lg shadow-lg border border-gray-100 py-1 z-10">
                                <button class="w-full px-4 py-2 text-left text-sm hover:bg-gray-50 flex items-center gap-2 text-gray-700" onclick="event.stopPropagation(); openMemberDetailModal({{ $member->id }}); closeAllDropdowns();">
                                    <i data-lucide="eye" class="w-4 h-4"></i>
                                    View Profile
                                </button>
                                <button class="w-full px-4 py-2 text-left text-sm hover:bg-gray-50 flex items-center gap-2 text-gray-700" onclick="event.stopPropagation(); openEditMemberModal({{ $member->id }}); closeAllDropdowns();">
                                    <i data-lucide="edit-2" class="w-4 h-4"></i>
                                    Edit Info
                                </button>
                                <hr class="my-1 border-gray-100">
                                <button class="w-full px-4 py-2 text-left text-sm hover:bg-gray-50 flex items-center gap-2 text-red-600" onclick="event.stopPropagation(); closeAllDropdowns();">
                                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                                    Delete
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center gap-4">
                        <div class="w-14 h-14 rounded-full {{ $member->role === 'pending' ? 'bg-gradient-to-br from-yellow-400 to-orange-400' : 'bg-gradient-to-br from-primary-400 to-primary-600' }} flex items-center justify-center shadow-md">
                            <span class="text-white font-bold text-lg">
                                {{ strtoupper(substr($member->first_name, 0, 1)) }}{{ strtoupper(substr($member->last_name ?? '', 0, 1)) }}
                            </span>
                        </div>
                        <div class="flex-1 min-w-0">
                            <h3 class="font-bold text-gray-900 truncate">{{ $member->first_name }} {{ $member->last_name }}</h3>
                            <p class="text-xs text-gray-400 font-medium">MEM-{{ str_pad($member->id, 4, '0', STR_PAD_LEFT) }}</p>
                        </div>
                    </div>
                </div>

                <!-- Card Body -->
                <div class="px-5 pb-4">
                    <div class="space-y-2.5">
                        <div class="flex items-center gap-2">
                            <i data-lucide="mail" class="w-4 h-4 text-gray-300"></i>
                            <span class="text-sm text-gray-600 truncate flex-1" title="{{ $member->email }}">{{ $member->email }}</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <i data-lucide="phone" class="w-4 h-4 text-gray-300"></i>
                            <span class="text-sm text-gray-600">{{ $member->contact_no ?? 'No phone' }}</span>
                        </div>
                        <div class="flex items-center justify-between pt-1">
                            <div class="flex items-center gap-2">
                                <i data-lucide="tag" class="w-4 h-4 text-gray-300"></i>
                                <span class="text-xs text-gray-500">{{ $member->membership_category ?? 'Regular' }}</span>
                            </div>
                            @if($member->role === 'member' || $member->role === 'active')
                                <span class="px-2.5 py-1 bg-green-100 text-green-700 text-xs font-semibold rounded-full">Active</span>
                            @elseif($member->role === 'pending')
                                <span class="px-2.5 py-1 bg-yellow-100 text-yellow-700 text-xs font-semibold rounded-full">Pending</span>
                            @elseif($member->role === 'inactive')
                                <span class="px-2.5 py-1 bg-gray-100 text-gray-600 text-xs font-semibold rounded-full">Inactive</span>
                            @else
                                <span class="px-2.5 py-1 bg-gray-100 text-gray-600 text-xs font-semibold rounded-full">{{ ucfirst($member->role) }}</span>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Card Footer -->
                <div class="px-5 pb-5 pt-2">
                    @if($member->role === 'pending')
                        <div class="flex gap-2">
                            <a href="{{ route('approve.user', $member->id) }}" class="flex-1 px-4 py-2.5 bg-green-500 text-white text-sm font-medium rounded-lg hover:bg-green-600 transition-colors flex items-center justify-center gap-2">
                                <i data-lucide="check" class="w-4 h-4"></i>
                                Approve
                            </a>
                            <form action="{{ route('decline.user', $member->id) }}" method="POST" class="flex-1 inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="w-full px-4 py-2.5 bg-red-500 text-white text-sm font-medium rounded-lg hover:bg-red-600 transition-colors flex items-center justify-center gap-2" onclick="return confirm('Are you sure you want to decline this request?')">
                                    <i data-lucide="x" class="w-4 h-4"></i>
                                    Decline
                                </button>
                            </form>
                        </div>
                    @else
                        <div class="flex gap-2">
                            <button class="flex-1 px-4 py-2.5 bg-gray-50 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-100 transition-colors border border-gray-200 flex items-center justify-center gap-2"
                                onclick="openMemberDetailModal({{ $member->id }})">
                                <i data-lucide="user" class="w-4 h-4"></i>
                                View Profile
                            </button>
                            <button class="flex-1 px-4 py-2.5 bg-primary-600 text-white text-sm font-medium rounded-lg hover:bg-primary-700 transition-colors flex items-center justify-center gap-2"
                                onclick="openEditMemberModal({{ $member->id }})">
                                <i data-lucide="settings" class="w-4 h-4"></i>
                                Manage Member
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        @empty
            <div class="col-span-full">
                <div class="text-center py-16">
                    <div class="w-20 h-20 mx-auto mb-4 rounded-full bg-gray-100 flex items-center justify-center">
                        <i data-lucide="users" class="w-10 h-10 text-gray-300"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-1">No members found</h3>
                    <p class="text-sm text-gray-500">Try adjusting your search or filter to find what you're looking for.</p>
                </div>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($members->hasPages())
    <div class="flex items-center justify-between mt-8 bg-white rounded-xl shadow-sm border border-gray-100 p-4">
        <p class="text-sm text-gray-500">
            Showing {{ $members->firstItem() ?? 1 }} to {{ $members->lastItem() ?? $members->count() }} of {{ $members->total() }} members
        </p>
        <div class="flex items-center gap-1">
            @if($members->onFirstPage())
                <button class="p-2 rounded-lg border border-gray-200 text-gray-400 cursor-not-allowed" disabled>
                    <i data-lucide="chevron-left" class="w-4 h-4"></i>
                </button>
            @else
                <a href="{{ $members->previousPageUrl() }}" class="p-2 rounded-lg border border-gray-200 text-gray-600 hover:bg-gray-50 transition-colors">
                    <i data-lucide="chevron-left" class="w-4 h-4"></i>
                </a>
            @endif

            @foreach($members->getUrlRange(max(1, $members->currentPage() - 2), min($members->lastPage(), $members->currentPage() + 2)) as $page => $url)
                @if($page == $members->currentPage())
                    <span class="px-4 py-2 rounded-lg bg-primary-600 text-white font-medium">{{ $page }}</span>
                @else
                    <a href="{{ $url }}" class="px-4 py-2 rounded-lg border border-gray-200 text-gray-600 hover:bg-gray-50 transition-colors">{{ $page }}</a>
                @endif
            @endforeach

            @if($members->hasMorePages())
                <a href="{{ $members->nextPageUrl() }}" class="p-2 rounded-lg border border-gray-200 text-gray-600 hover:bg-gray-50 transition-colors">
                    <i data-lucide="chevron-right" class="w-4 h-4"></i>
                </a>
            @else
                <button class="p-2 rounded-lg border border-gray-200 text-gray-400 cursor-not-allowed" disabled>
                    <i data-lucide="chevron-right" class="w-4 h-4"></i>
                </button>
            @endif
        </div>
    </div>
    @endif

    <!-- Add Member Modal -->
    <div id="addMemberModal" class="modal-overlay hidden">
        <div class="modal max-w-lg">
            <div class="p-6 border-b border-gray-100">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-primary-100 flex items-center justify-center">
                            <i data-lucide="user-plus" class="w-5 h-5 text-primary-600"></i>
                        </div>
                        <div>
                            <h2 class="text-xl font-bold text-gray-900">Add New Member</h2>
                            <p class="text-xs text-gray-500">Create a new cooperative member</p>
                        </div>
                    </div>
                    <button onclick="closeModal('addMemberModal')" class="p-2 hover:bg-gray-100 rounded-lg transition-colors">
                        <i data-lucide="x" class="w-5 h-5 text-gray-500"></i>
                    </button>
                </div>
            </div>
            <form class="p-6 space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">First Name</label>
                        <input type="text" class="input" placeholder="Enter first name">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Last Name</label>
                        <input type="text" class="input" placeholder="Enter last name">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Email Address</label>
                    <input type="email" class="input" placeholder="Enter email address">
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Phone Number</label>
                        <input type="tel" class="input" placeholder="Enter phone number">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Date of Birth</label>
                        <input type="date" class="input">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Address</label>
                    <textarea class="input" rows="2" placeholder="Enter address"></textarea>
                </div>
            </form>
            <div class="p-6 border-t border-gray-100 flex justify-end gap-3">
                <button onclick="closeModal('addMemberModal')" class="px-5 py-2.5 text-gray-700 font-medium rounded-lg hover:bg-gray-100 transition-colors">Cancel</button>
                <button onclick="closeModal('addMemberModal'); showToast('Success', 'Member added successfully')"
                    class="px-5 py-2.5 bg-primary-600 text-white font-medium rounded-lg hover:bg-primary-700 transition-colors">Add Member</button>
            </div>
        </div>
    </div>

    <!-- Member Detail Modal -->
    <div id="memberDetailModal" class="modal-overlay hidden">
        <div class="modal max-w-2xl">
            <div class="p-6 border-b border-gray-100">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-primary-100 flex items-center justify-center">
                            <i data-lucide="user" class="w-5 h-5 text-primary-600"></i>
                        </div>
                        <div>
                            <h2 class="text-xl font-bold text-gray-900">Member Profile</h2>
                            <p class="text-xs text-gray-500">View member information</p>
                        </div>
                    </div>
                    <button onclick="closeModal('memberDetailModal')" class="p-2 hover:bg-gray-100 rounded-lg transition-colors">
                        <i data-lucide="x" class="w-5 h-5 text-gray-500"></i>
                    </button>
                </div>
            </div>
            <div class="p-6 max-h-[60vh] overflow-y-auto">
                <!-- Profile Header -->
                <div class="flex items-center gap-5 mb-6 p-4 bg-gradient-to-r from-primary-50 to-primary-100 rounded-xl">
                    <div class="w-20 h-20 rounded-full bg-gradient-to-br from-primary-400 to-primary-600 flex items-center justify-center shadow-lg">
                        <span id="detail-avatar" class="text-white text-2xl font-bold">--</span>
                    </div>
                    <div class="flex-1">
                        <h3 id="detail-name" class="text-xl font-bold text-gray-900">--</h3>
                        <p id="detail-member-id" class="text-sm text-gray-500 font-medium">--</p>
                        <div class="flex gap-2 mt-2">
                            <span id="detail-status" class="px-3 py-1 bg-green-100 text-green-700 text-xs font-semibold rounded-full">--</span>
                            <span id="detail-category" class="px-3 py-1 bg-primary-100 text-primary-700 text-xs font-semibold rounded-full">--</span>
                        </div>
                    </div>
                </div>

                <!-- Info Grid -->
                <div class="grid grid-cols-2 gap-6">
                    <div class="space-y-4">
                        <h4 class="font-semibold text-gray-900 flex items-center gap-2">
                            <i data-lucide="user" class="w-4 h-4 text-primary-500"></i>
                            Personal Information
                        </h4>
                        <div class="space-y-3">
                            <div class="flex justify-between items-center py-2 border-b border-gray-100">
                                <span class="text-sm text-gray-500">Email</span>
                                <span id="detail-email" class="text-sm font-medium text-gray-900">--</span>
                            </div>
                            <div class="flex justify-between items-center py-2 border-b border-gray-100">
                                <span class="text-sm text-gray-500">Phone</span>
                                <span id="detail-phone" class="text-sm font-medium text-gray-900">--</span>
                            </div>
                            <div class="flex justify-between items-center py-2 border-b border-gray-100">
                                <span class="text-sm text-gray-500">Date of Birth</span>
                                <span id="detail-dob" class="text-sm font-medium text-gray-900">--</span>
                            </div>
                            <div class="flex justify-between items-center py-2 border-b border-gray-100">
                                <span class="text-sm text-gray-500">Sex</span>
                                <span id="detail-sex" class="text-sm font-medium text-gray-900">--</span>
                            </div>
                            <div class="flex justify-between items-center py-2 border-b border-gray-100">
                                <span class="text-sm text-gray-500">Civil Status</span>
                                <span id="detail-civil-status" class="text-sm font-medium text-gray-900">--</span>
                            </div>
                            <div class="flex justify-between items-center py-2">
                                <span class="text-sm text-gray-500">Address</span>
                                <span id="detail-address" class="text-sm font-medium text-gray-900 text-right max-w-[180px]">--</span>
                            </div>
                        </div>
                    </div>
                    <div class="space-y-4">
                        <h4 class="font-semibold text-gray-900 flex items-center gap-2">
                            <i data-lucide="credit-card" class="w-4 h-4 text-primary-500"></i>
                            Account Information
                        </h4>
                        <div class="space-y-3">
                            <div class="flex justify-between items-center py-2 border-b border-gray-100">
                                <span class="text-sm text-gray-500">Membership</span>
                                <span id="detail-membership" class="text-sm font-medium text-gray-900">--</span>
                            </div>
                            <div class="flex justify-between items-center py-2 border-b border-gray-100">
                                <span class="text-sm text-gray-500">Citizenship</span>
                                <span id="detail-citizenship" class="text-sm font-medium text-gray-900">--</span>
                            </div>
                            <div class="flex justify-between items-center py-2 border-b border-gray-100">
                                <span class="text-sm text-gray-500">Place of Birth</span>
                                <span id="detail-pob" class="text-sm font-medium text-gray-900">--</span>
                            </div>
                            <div class="flex justify-between items-center py-2 border-b border-gray-100">
                                <span class="text-sm text-gray-500">Blood Type</span>
                                <span id="detail-blood-type" class="text-sm font-medium text-gray-900">--</span>
                            </div>
                            <div class="flex justify-between items-center py-2 border-b border-gray-100">
                                <span class="text-sm text-gray-500">Height</span>
                                <span id="detail-height" class="text-sm font-medium text-gray-900">--</span>
                            </div>
                            <div class="flex justify-between items-center py-2">
                                <span class="text-sm text-gray-500">Weight</span>
                                <span id="detail-weight" class="text-sm font-medium text-gray-900">--</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="space-y-4">
                        <h4 class="font-semibold text-gray-900 flex items-center gap-2">
                            <i data-lucide="coins" class="w-4 h-4 text-primary-500"></i>
                            Share Capital
                        </h4>
                        <div class="bg-gradient-to-r from-amber-50 to-orange-50 rounded-lg p-4 border border-amber-100">
                            <div class="grid grid-cols-3 gap-4 mb-4">
                                <div class="text-center">
                                    <p class="text-xs text-gray-500 mb-1">Total Amount</p>
                                    <p id="detail-sc-amount" class="text-lg font-bold text-gray-900">₱0.00</p>
                                </div>
                                <div class="text-center">
                                    <p class="text-xs text-gray-500 mb-1">Total Shares</p>
                                    <p id="detail-sc-shares" class="text-lg font-bold text-gray-900">0</p>
                                </div>
                                <div class="text-center">
                                    <p class="text-xs text-gray-500 mb-1">Status</p>
                                    <span id="detail-sc-status" class="inline-block px-2 py-1 bg-gray-100 text-gray-600 text-xs font-semibold rounded-full">No Account</span>
                                </div>
                            </div>
                            <div class="flex gap-2">
                                <a id="detail-sc-see-more" href="#" target="_blank" class="flex-1 px-4 py-2 bg-primary-600 text-white text-sm font-medium rounded-lg hover:bg-primary-700 transition-colors flex items-center justify-center gap-2">
                                    <i data-lucide="external-link" class="w-4 h-4"></i>
                                    See More in Share Capital
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="p-6 border-t border-gray-100 flex justify-end gap-3">
                <button onclick="closeModal('memberDetailModal')" class="px-5 py-2.5 text-gray-700 font-medium rounded-lg hover:bg-gray-100 transition-colors">Close</button>
                <button id="detail-edit-btn" class="px-5 py-2.5 bg-primary-600 text-white font-medium rounded-lg hover:bg-primary-700 transition-colors flex items-center gap-2">
                    <i data-lucide="settings" class="w-4 h-4"></i>
                    Manage Member
                </button>
            </div>
        </div>
    </div>

    <!-- Edit Member Modal -->
    <div id="editMemberModal" class="modal-overlay hidden">
        <div class="modal max-w-2xl">
            <div class="p-6 border-b border-gray-100">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-primary-100 flex items-center justify-center">
                            <i data-lucide="settings" class="w-5 h-5 text-primary-600"></i>
                        </div>
                        <div>
                            <h2 class="text-xl font-bold text-gray-900">Manage Member</h2>
                            <p class="text-xs text-gray-500">Update member information and send emails</p>
                        </div>
                    </div>
                    <button onclick="closeModal('editMemberModal')" class="p-2 hover:bg-gray-100 rounded-lg transition-colors">
                        <i data-lucide="x" class="w-5 h-5 text-gray-500"></i>
                    </button>
                </div>
            </div>
            <form id="editMemberForm" action="{{ route('update.member') }}" method="POST">
                @csrf
                @method('PUT')
                <input type="hidden" name="id" id="edit-id">
                <div class="p-6 max-h-[55vh] overflow-y-auto space-y-5">
                    <div class="bg-blue-50 p-4 rounded-lg border border-blue-100">
                        <h4 class="font-semibold text-gray-900 mb-3 flex items-center gap-2">
                            <i data-lucide="user" class="w-4 h-4 text-blue-500"></i>
                            Basic Information
                        </h4>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">First Name</label>
                                <input type="text" name="first_name" id="edit-first_name" class="input">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Middle Name</label>
                                <input type="text" name="middle_name" id="edit-middle_name" class="input">
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-4 mt-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Last Name</label>
                                <input type="text" name="last_name" id="edit-last_name" class="input">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                                <input type="email" name="email" id="edit-email" class="input">
                            </div>
                        </div>
                    </div>

                    <div class="bg-green-50 p-4 rounded-lg border border-green-100">
                        <h4 class="font-semibold text-gray-900 mb-3 flex items-center gap-2">
                            <i data-lucide="phone" class="w-4 h-4 text-green-500"></i>
                            Contact Details
                        </h4>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Contact Number</label>
                                <input type="text" name="contact_no" id="edit-contact_no" class="input">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Date of Birth</label>
                                <input type="date" name="date_of_birth" id="edit-date_of_birth" class="input">
                            </div>
                        </div>
                        <div class="mt-4">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Present Address</label>
                            <input type="text" name="present_address" id="edit-present_address" class="input">
                        </div>
                        <div class="mt-4">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Permanent Address</label>
                            <input type="text" name="permanent_address" id="edit-permanent_address" class="input">
                        </div>
                    </div>

                    <div class="bg-purple-50 p-4 rounded-lg border border-purple-100">
                        <h4 class="font-semibold text-gray-900 mb-3 flex items-center gap-2">
                            <i data-lucide="heart" class="w-4 h-4 text-purple-500"></i>
                            Personal Details
                        </h4>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Sex</label>
                                <select name="sex" id="edit-sex" class="input">
                                    <option value="">Select</option>
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Civil Status</label>
                                <select name="civil_status" id="edit-civil_status" class="input">
                                    <option value="">Select</option>
                                    <option value="Single">Single</option>
                                    <option value="Married">Married</option>
                                    <option value="Widowed">Widowed</option>
                                    <option value="Separated">Separated</option>
                                </select>
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-4 mt-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Citizenship</label>
                                <input type="text" name="citizenship" id="edit-citizenship" class="input">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Place of Birth</label>
                                <input type="text" name="place_of_birth" id="edit-place_of_birth" class="input">
                            </div>
                        </div>
                    </div>

                    <div class="bg-orange-50 p-4 rounded-lg border border-orange-100">
                        <h4 class="font-semibold text-gray-900 mb-3 flex items-center gap-2">
                            <i data-lucide="activity" class="w-4 h-4 text-orange-500"></i>
                            Health Information
                        </h4>
                        <div class="grid grid-cols-3 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Blood Type</label>
                                <select name="blood_type" id="edit-blood_type" class="input">
                                    <option value="">Select</option>
                                    <option value="A+">A+</option>
                                    <option value="A-">A-</option>
                                    <option value="B+">B+</option>
                                    <option value="B-">B-</option>
                                    <option value="AB+">AB+</option>
                                    <option value="AB-">AB-</option>
                                    <option value="O+">O+</option>
                                    <option value="O-">O-</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Height</label>
                                <input type="text" name="height" id="edit-height" class="input">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Weight</label>
                                <input type="text" name="weight" id="edit-weight" class="input">
                            </div>
                        </div>
                    </div>

                    <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                        <h4 class="font-semibold text-gray-900 mb-3 flex items-center gap-2">
                            <i data-lucide="settings" class="w-4 h-4 text-gray-500"></i>
                            Account Settings
                        </h4>
                        <div class="grid grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Role</label>
                                <select name="role" id="edit-role" class="input">
                                    <option value="pending">Pending</option>
                                    <option value="member">Member</option>
                                    <option value="inactive">Inactive</option>
                                    <option value="admin">Admin</option>
                                </select>
                            </div>
                        </div>
                        <div class="border-t border-gray-200 pt-4 mt-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Email Actions</label>
                            <div class="flex gap-2">
                                <a id="edit-send-sc-email" href="#" class="flex-1 px-4 py-2.5 bg-amber-500 text-white text-sm font-medium rounded-lg hover:bg-amber-600 transition-colors flex items-center justify-center gap-2">
                                    <i data-lucide="mail" class="w-4 h-4"></i>
                                    Send Share Capital Email
                                </a>
                            </div>
                            <p class="text-xs text-gray-500 mt-2">Send an email to this member reminding them to complete their share capital contribution.</p>
                        </div>
                    </div>
                </div>
                <div class="p-6 border-t border-gray-100 flex justify-end gap-3">
                    <button type="button" onclick="closeModal('editMemberModal')" class="px-5 py-2.5 text-gray-700 font-medium rounded-lg hover:bg-gray-100 transition-colors">Cancel</button>
                    <button type="submit" class="px-5 py-2.5 bg-primary-600 text-white font-medium rounded-lg hover:bg-primary-700 transition-colors flex items-center gap-2">
                        <i data-lucide="save" class="w-4 h-4"></i>
                        Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script type="application/json" id="members-data">
        {!! json_encode($members) !!}
    </script>
    
    <script>
        const membersData = {!! json_encode($members->items()) !!};
        
        function closeAllDropdowns() {
            document.querySelectorAll('.dropdown-menu').forEach(el => el.classList.add('hidden'));
        }
        
        document.addEventListener('click', function(e) {
            if (!e.target.closest('.dropdown')) {
                closeAllDropdowns();
            }
        });
        
        function openMemberDetailModal(memberId) {
            const member = membersData.find(m => m.id === memberId);
            if (!member) return;
            
            document.getElementById('detail-avatar').textContent = (member.first_name?.charAt(0) || '') + (member.last_name?.charAt(0) || '');
            document.getElementById('detail-name').textContent = (member.first_name || '') + ' ' + (member.last_name || '');
            document.getElementById('detail-member-id').textContent = 'MEM-' + String(member.id).padStart(4, '0');
            document.getElementById('detail-email').textContent = member.email || 'N/A';
            document.getElementById('detail-phone').textContent = member.contact_no || 'N/A';
            document.getElementById('detail-dob').textContent = member.date_of_birth || 'N/A';
            document.getElementById('detail-sex').textContent = member.sex || 'N/A';
            document.getElementById('detail-civil-status').textContent = member.civil_status || 'N/A';
            document.getElementById('detail-address').textContent = member.present_address || 'N/A';
            document.getElementById('detail-membership').textContent = member.membership_category || 'N/A';
            document.getElementById('detail-citizenship').textContent = member.citizenship || 'N/A';
            document.getElementById('detail-pob').textContent = member.place_of_birth || 'N/A';
            document.getElementById('detail-blood-type').textContent = member.blood_type || 'N/A';
            document.getElementById('detail-height').textContent = member.height || 'N/A';
            document.getElementById('detail-weight').textContent = member.weight || 'N/A';
            
            const statusEl = document.getElementById('detail-status');
            if (member.role === 'member' || member.role === 'active') {
                statusEl.className = 'px-3 py-1 bg-green-100 text-green-700 text-xs font-semibold rounded-full';
                statusEl.textContent = 'Active';
            } else if (member.role === 'pending') {
                statusEl.className = 'px-3 py-1 bg-yellow-100 text-yellow-700 text-xs font-semibold rounded-full';
                statusEl.textContent = 'Pending';
            } else {
                statusEl.className = 'px-3 py-1 bg-gray-100 text-gray-600 text-xs font-semibold rounded-full';
                statusEl.textContent = member.role || 'N/A';
            }
            
            const categoryEl = document.getElementById('detail-category');
            categoryEl.textContent = member.membership_category || 'N/A';
            
            document.getElementById('detail-sc-amount').textContent = '₱' + (member.sc_total_amount || 0).toLocaleString('en-PH', {minimumFractionDigits: 2});
            document.getElementById('detail-sc-shares').textContent = member.sc_total_shares || 0;
            
            const scStatusEl = document.getElementById('detail-sc-status');
            if (member.sc_status === 'Active') {
                scStatusEl.className = 'inline-block px-2 py-1 bg-green-100 text-green-700 text-xs font-semibold rounded-full';
                scStatusEl.textContent = 'Active';
            } else if (member.sc_status === 'No Account') {
                scStatusEl.className = 'inline-block px-2 py-1 bg-gray-100 text-gray-600 text-xs font-semibold rounded-full';
                scStatusEl.textContent = 'No Account';
            } else {
                scStatusEl.className = 'inline-block px-2 py-1 bg-amber-100 text-amber-700 text-xs font-semibold rounded-full';
                scStatusEl.textContent = member.sc_status || 'Inactive';
            }
            
            const scSeeMore = document.getElementById('detail-sc-see-more');
            scSeeMore.href = '/dashboard-sharecapitals?member=' + member.id;
            
            document.getElementById('detail-edit-btn').onclick = function() {
                closeModal('memberDetailModal');
                openEditMemberModal(memberId);
            };
            
            openModal('memberDetailModal');
        }
        
        function openEditMemberModal(memberId) {
            const member = membersData.find(m => m.id === memberId);
            if (!member) return;
            
            document.getElementById('edit-id').value = member.id;
            document.getElementById('edit-first_name').value = member.first_name || '';
            document.getElementById('edit-middle_name').value = member.middle_name || '';
            document.getElementById('edit-last_name').value = member.last_name || '';
            document.getElementById('edit-email').value = member.email || '';
            document.getElementById('edit-contact_no').value = member.contact_no || '';
            document.getElementById('edit-date_of_birth').value = member.date_of_birth || '';
            document.getElementById('edit-sex').value = member.sex || '';
            document.getElementById('edit-civil_status').value = member.civil_status || '';
            document.getElementById('edit-present_address').value = member.present_address || '';
            document.getElementById('edit-permanent_address').value = member.permanent_address || '';
            document.getElementById('edit-citizenship').value = member.citizenship || '';
            document.getElementById('edit-place_of_birth').value = member.place_of_birth || '';
            document.getElementById('edit-blood_type').value = member.blood_type || '';
            document.getElementById('edit-height').value = member.height || '';
            document.getElementById('edit-weight').value = member.weight || '';
            document.getElementById('edit-role').value = member.role || '';
            
            const sendScEmailBtn = document.getElementById('edit-send-sc-email');
            sendScEmailBtn.href = '/dashboard-members/send-share-email/' + member.id;
            
            openModal('editMemberModal');
        }
    </script>
@endsection
