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

<!-- Category Cards -->
    <div class="grid grid-cols-2 gap-6 mb-6">
        <div class="stat-card cursor-pointer hover:shadow-lg hover:border-primary-200 transition-all group" onclick="var m=document.getElementById('memberCategoryModal');m.classList.remove('hidden');m.style.display='flex'">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 mb-1">Total Members</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $memberCategoryCounts->sum() }}</p>
                    <p class="text-xs text-primary-500 mt-1 flex items-center">
                        <i data-lucide="users" class="w-3 h-3 mr-1"></i>
                        Click to view breakdown
                    </p>
                </div>
                <div class="w-12 h-12 bg-primary-100 rounded-xl flex items-center justify-center group-hover:bg-primary-200 transition-colors">
                    <i data-lucide="users" class="w-6 h-6 text-primary-600"></i>
                </div>
            </div>
        </div>

        <div class="stat-card cursor-pointer hover:shadow-lg hover:border-blue-200 transition-all group" onclick="var m=document.getElementById('adminCategoryModal');m.classList.remove('hidden');m.style.display='flex'">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 mb-1">Total Admins</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $admins->count() }}</p>
                    <p class="text-xs text-blue-500 mt-1 flex items-center">
                        <i data-lucide="shield" class="w-3 h-3 mr-1"></i>
                        Click to view breakdown
                    </p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center group-hover:bg-blue-200 transition-colors">
                    <i data-lucide="shield" class="w-6 h-6 text-blue-600"></i>
                </div>
            </div>
        </div>
    </div>



    <!-- Members Table -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Category</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($members as $member)
                    <tr>
                        <td class="text-sm font-medium text-gray-900">MEM-{{ str_pad($member->id, 4, '0', STR_PAD_LEFT) }}</td>
                        <td>
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full {{ $member->role === 'pending' ? 'bg-gradient-to-br from-yellow-400 to-orange-400' : 'bg-gradient-to-br from-primary-400 to-primary-600' }} flex items-center justify-center">
                                    <span class="text-white font-bold text-sm">
                                        {{ strtoupper(substr($member->first_name, 0, 1)) }}{{ strtoupper(substr($member->last_name ?? '', 0, 1)) }}
                                    </span>
                                </div>
                                <span class="text-sm font-medium text-gray-900">{{ $member->first_name }} {{ $member->last_name }}</span>
                            </div>
                        </td>
                        <td class="text-sm text-gray-600">{{ $member->email }}</td>
                        <td class="text-sm text-gray-600">{{ $member->membership_category ?? 'Investor Associate' }}</td>
                        <td>
                            @if($member->role === 'Member' || $member->role === 'member' || $member->role === 'active')
                                <span class="badge badge-success">Active</span>
                            @elseif($member->role === 'pending' || $member->role === 'Pending')
                                <span class="badge badge-warning">Pending</span>
                            @elseif($member->role === 'inactive')
                                <span class="badge badge-gray">Inactive</span>
                            @else
                                <span class="badge badge-gray">{{ ucfirst($member->role) }}</span>
                            @endif
                        </td>
                        <td>
                            @if($member->role === 'pending' || $member->role === 'Pending')
                                <div class="flex items-center gap-1">
                                    <button class="btn btn-primary btn-xs px-2 py-1" onclick="openMemberDetailModal({{ $member->id }})">
                                        <i data-lucide="eye" class="w-3 h-3"></i>
                                        View
                                    </button>
                                    <a href="{{ route('approve.user', $member->id) }}" class="btn btn-success btn-xs px-2 py-1">
                                        <i data-lucide="check" class="w-3 h-3"></i>
                                        Approve
                                    </a>
                                    <form action="{{ route('decline.user', $member->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-xs px-2 py-1" onclick="return confirm('Are you sure you want to decline this request?')">
                                            <i data-lucide="x" class="w-3 h-3"></i>
                                            Decline
                                        </button>
                                    </form>
                                </div>
                            @else
                                <button class="btn btn-primary btn-xs px-2 py-1" onclick="openMemberDetailModal({{ $member->id }})">
                                    <i data-lucide="eye" class="w-3 h-3"></i>
                                    View
                                </button>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-12">
                            <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-gray-100 flex items-center justify-center">
                                <i data-lucide="users" class="w-8 h-8 text-gray-300"></i>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-1">No members found</h3>
                            <p class="text-sm text-gray-500">Try adjusting your search or filter.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
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

    <!-- Admin Table -->
    <div class="mt-8">
        <h2 class="text-xl font-bold text-gray-900 mb-4">Admin</h2>
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="table-container">
                <table class="table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Category</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($admins as $admin)
                        <tr>
                            <td class="text-sm font-medium text-gray-900">ADM-{{ str_pad($admin->id, 4, '0', STR_PAD_LEFT) }}</td>
                            <td>
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center">
                                        <span class="text-white font-bold text-sm">
                                            {{ strtoupper(substr($admin->first_name, 0, 1)) }}{{ strtoupper(substr($admin->last_name ?? '', 0, 1)) }}
                                        </span>
                                    </div>
                                    <span class="text-sm font-medium text-gray-900">{{ $admin->first_name }} {{ $admin->last_name }}</span>
                                </div>
                            </td>
                            <td class="text-sm text-gray-600">{{ $admin->email }}</td>
                            <td class="text-sm text-gray-600">{{ $admin->membership_category ?? 'General Manager' }}</td>
                            <td>
                                <span class="badge badge-info">Admin</span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-12">
                                <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-gray-100 flex items-center justify-center">
                                    <i data-lucide="shield" class="w-8 h-8 text-gray-300"></i>
                                </div>
                                <h3 class="text-lg font-semibold text-gray-900 mb-1">No admin accounts found</h3>
                                <p class="text-sm text-gray-500">There are no admin accounts in the system.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Member Category Modal -->
    <div id="memberCategoryModal" class="modal-overlay hidden" style="display:none">
        <div class="modal max-w-md">
            <div class="p-6 border-b border-gray-100">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-primary-100 flex items-center justify-center">
                            <i data-lucide="users" class="w-5 h-5 text-primary-600"></i>
                        </div>
                        <div>
                            <h2 class="text-xl font-bold text-gray-900">Member Categories</h2>
                            <p class="text-xs text-gray-500">Category breakdown</p>
                        </div>
                    </div>
                    <button onclick="document.getElementById('memberCategoryModal').style.display='none';" class="p-2 hover:bg-gray-100 rounded-lg transition-colors">
                        <i data-lucide="x" class="w-5 h-5 text-gray-500"></i>
                    </button>
                </div>
            </div>
            <div class="p-6 space-y-3">
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                    <span class="text-sm font-medium text-gray-900">Investor Associate</span>
                    <span class="text-sm font-bold text-primary-600">{{ $memberCategoryCounts['Investor Associate'] ?? 0 }}</span>
                </div>
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                    <span class="text-sm font-medium text-gray-900">Driver</span>
                    <span class="text-sm font-bold text-primary-600">{{ $memberCategoryCounts['Driver'] ?? 0 }}</span>
                </div>
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                    <span class="text-sm font-medium text-gray-900">Dispatcher</span>
                    <span class="text-sm font-bold text-primary-600">{{ $memberCategoryCounts['Dispatcher'] ?? 0 }}</span>
                </div>
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                    <span class="text-sm font-medium text-gray-900">Driver-Operator</span>
                    <span class="text-sm font-bold text-primary-600">{{ $memberCategoryCounts['Driver-Operator'] ?? 0 }}</span>
                </div>
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                    <span class="text-sm font-medium text-gray-900">Allied Worker</span>
                    <span class="text-sm font-bold text-primary-600">{{ $memberCategoryCounts['Allied Worker'] ?? 0 }}</span>
                </div>
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                    <span class="text-sm font-medium text-gray-900">Transport Entrepreneur</span>
                    <span class="text-sm font-bold text-primary-600">{{ $memberCategoryCounts['Transport Entrepreneur'] ?? 0 }}</span>
                </div>
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                    <span class="text-sm font-medium text-gray-900">Operator</span>
                    <span class="text-sm font-bold text-primary-600">{{ $memberCategoryCounts['Operator'] ?? 0 }}</span>
                </div>
            </div>
            <div class="p-6 border-t border-gray-100 flex justify-end">
                <button onclick="document.getElementById('memberCategoryModal').style.display='none';" class="px-5 py-2.5 text-gray-700 font-medium rounded-lg hover:bg-gray-100 transition-colors">Close</button>
            </div>
        </div>
    </div>

    <!-- Admin Category Modal -->
    <div id="adminCategoryModal" class="modal-overlay hidden" style="display:none">
        <div class="modal max-w-md">
            <div class="p-6 border-b border-gray-100">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center">
                            <i data-lucide="shield" class="w-5 h-5 text-blue-600"></i>
                        </div>
                        <div>
                            <h2 class="text-xl font-bold text-gray-900">Admin Categories</h2>
                            <p class="text-xs text-gray-500">Category breakdown</p>
                        </div>
                    </div>
                    <button onclick="document.getElementById('adminCategoryModal').style.display='none';" class="p-2 hover:bg-gray-100 rounded-lg transition-colors">
                        <i data-lucide="x" class="w-5 h-5 text-gray-500"></i>
                    </button>
                </div>
            </div>
            <div class="p-6 space-y-3">
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                    <span class="text-sm font-medium text-gray-900">General Manager</span>
                    <span class="text-sm font-bold text-blue-600">{{ $adminCategoryCounts['General Manager'] ?? 0 }}</span>
                </div>
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                    <span class="text-sm font-medium text-gray-900">Office Manager</span>
                    <span class="text-sm font-bold text-blue-600">{{ $adminCategoryCounts['Office Manager'] ?? 0 }}</span>
                </div>
            </div>
            <div class="p-6 border-t border-gray-100 flex justify-end">
                <button onclick="document.getElementById('adminCategoryModal').style.display='none';" class="px-5 py-2.5 text-gray-700 font-medium rounded-lg hover:bg-gray-100 transition-colors">Close</button>
            </div>
        </div>
    </div>

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
        <div class="modal max-w-3xl">
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
            <div class="p-6 max-h-[70vh] overflow-y-auto">
                <!-- Profile Header -->
                <div class="flex items-center gap-5 mb-6 p-4 bg-gradient-to-r from-primary-50 to-primary-100 rounded-xl">
                    <div class="w-20 h-20 rounded-full bg-gradient-to-br from-primary-400 to-primary-600 flex items-center justify-center shadow-lg overflow-hidden">
                        <img id="detail-profile-pic" src="" alt="" class="w-full h-full object-cover hidden">
                        <span id="detail-avatar" class="text-white text-2xl font-bold">--</span>
                    </div>
                    <div class="flex-1">
                        <h3 id="detail-name" class="text-xl font-bold text-gray-900">--</h3>
                        <p id="detail-middle-name" class="text-sm text-gray-500">--</p>
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
                </div>

                <!-- Family Information -->
                <div class="mt-6 space-y-4">
                    <h4 class="font-semibold text-gray-900 flex items-center gap-2">
                        <i data-lucide="heart" class="w-4 h-4 text-pink-500"></i>
                        Family Information
                    </h4>
                    <div class="bg-pink-50 rounded-lg p-4 border border-pink-100">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <p class="text-xs text-gray-500 mb-1">Spouse Name</p>
                                <p id="detail-spouse-name" class="text-sm font-medium text-gray-900">Not specified</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 mb-1">Spouse Date of Birth</p>
                                <p id="detail-spouse-dob" class="text-sm font-medium text-gray-900">Not specified</p>
                            </div>
                        </div>
                        <div class="mt-3 pt-3 border-t border-pink-100">
                            <p class="text-xs text-gray-500 mb-2">Children</p>
                            <div class="flex gap-4">
                                <div class="flex items-center gap-2">
                                    <span class="w-8 h-8 rounded-full bg-pink-200 flex items-center justify-center">
                                        <i data-lucide="baby" class="w-4 h-4 text-pink-600"></i>
                                    </span>
                                    <div>
                                        <p class="text-xs text-gray-500">Sons</p>
                                        <p id="detail-number-son" class="text-sm font-semibold text-gray-900">0</p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-2">
                                    <span class="w-8 h-8 rounded-full bg-pink-200 flex items-center justify-center">
                                        <i data-lucide="baby" class="w-4 h-4 text-pink-600"></i>
                                    </span>
                                    <div>
                                        <p class="text-xs text-gray-500">Daughters</p>
                                        <p id="detail-number-daughter" class="text-sm font-semibold text-gray-900">0</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Skills & Expertise -->
                <div class="mt-6 space-y-4">
                    <h4 class="font-semibold text-gray-900 flex items-center gap-2">
                        <i data-lucide="star" class="w-4 h-4 text-yellow-500"></i>
                        Skills & Expertise
                    </h4>
                    <div id="detail-skills-container" class="bg-yellow-50 rounded-lg p-4 border border-yellow-100">
                        <p id="detail-skills" class="text-sm font-medium text-gray-900">No skills specified</p>
                    </div>
                </div>

                <!-- Government IDs -->
                <div class="mt-6 space-y-4">
                    <h4 class="font-semibold text-gray-900 flex items-center gap-2">
                        <i data-lucide="id-card" class="w-4 h-4 text-blue-500"></i>
                        Government IDs
                    </h4>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="bg-blue-50 rounded-lg p-4 border border-blue-100">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-lg bg-blue-100 flex items-center justify-center">
                                    <span class="text-blue-600 font-bold text-xs">SSS</span>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500">SSS ID</p>
                                    <p id="detail-sss-id" class="text-sm font-medium text-gray-900">Not provided</p>
                                </div>
                            </div>
                        </div>
                        <div class="bg-blue-50 rounded-lg p-4 border border-blue-100">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-lg bg-blue-100 flex items-center justify-center">
                                    <span class="text-blue-600 font-bold text-xs">PH</span>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500">PhilHealth ID</p>
                                    <p id="detail-philhealth-id" class="text-sm font-medium text-gray-900">Not provided</p>
                                </div>
                            </div>
                        </div>
                        <div class="bg-blue-50 rounded-lg p-4 border border-blue-100">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-lg bg-blue-100 flex items-center justify-center">
                                    <span class="text-blue-600 font-bold text-xs">PAG</span>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500">PAG-IBIG ID</p>
                                    <p id="detail-pagibig-id" class="text-sm font-medium text-gray-900">Not provided</p>
                                </div>
                            </div>
                        </div>
                        <div class="bg-blue-50 rounded-lg p-4 border border-blue-100">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-lg bg-blue-100 flex items-center justify-center">
                                    <span class="text-blue-600 font-bold text-xs">TIN</span>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500">TIN ID</p>
                                    <p id="detail-tin-id" class="text-sm font-medium text-gray-900">Not provided</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Vehicles -->
                <div class="mt-6 space-y-4">
                    <h4 class="font-semibold text-gray-900 flex items-center gap-2">
                        <i data-lucide="car" class="w-4 h-4 text-green-500"></i>
                        Vehicle Information
                    </h4>
                    <div class="bg-green-50 rounded-lg border border-green-100 overflow-hidden">
                        <div id="detail-vehicles-container">
                            <table class="w-full">
                                <thead class="bg-green-100">
                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Vehicle Type</th>
                                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Plate Number</th>
                                        <th class="px-4 py-3 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Quantity</th>
                                    </tr>
                                </thead>
                                <tbody id="detail-vehicles-body">
                                    <tr>
                                        <td colspan="3" class="px-4 py-6 text-center text-sm text-gray-500">No vehicles registered</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Share Capital -->
                <div class="mt-6 space-y-4">
                    <h4 class="font-semibold text-gray-900 flex items-center gap-2">
                        <i data-lucide="coins" class="w-4 h-4 text-amber-500"></i>
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

                <!-- Account Settings -->
                <div class="mt-6 space-y-4">
                    <h4 class="font-semibold text-gray-900 flex items-center gap-2">
                        <i data-lucide="settings" class="w-4 h-4 text-gray-500"></i>
                        Account Settings
                    </h4>
                    <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                        <div class="grid grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                                <select name="role" id="detail-role" class="input">
                                    <option value="Pending">Pending</option>
                                    <option value="Member">Member</option>
                                    <option value="inactive">Inactive</option>
                                </select>
                            </div>
                        </div>
                        <div class="border-t border-gray-200 pt-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Email Actions</label>
                            <a id="detail-send-sc-email" href="#" class="inline-flex px-4 py-2.5 bg-amber-500 text-white text-sm font-medium rounded-lg hover:bg-amber-600 transition-colors items-center gap-2">
                                <i data-lucide="mail" class="w-4 h-4"></i>
                                Send Share Capital Email
                            </a>
                            <p class="text-xs text-gray-500 mt-2">Send an email to this member reminding them to complete their share capital contribution.</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="p-6 border-t border-gray-100 flex justify-end gap-3">
                <button onclick="closeModal('memberDetailModal')" class="px-5 py-2.5 text-gray-700 font-medium rounded-lg hover:bg-gray-100 transition-colors">Close</button>
                <button id="detail-save-btn" class="px-5 py-2.5 bg-primary-600 text-white font-medium rounded-lg hover:bg-primary-700 transition-colors flex items-center gap-2">
                    <i data-lucide="save" class="w-4 h-4"></i>
                    Save Changes
                </button>
            </div>
        </div>
    </div>

    <script type="application/json" id="members-data">
        {!! json_encode($members) !!}
    </script>
    
    <script>
        const membersData = {!! json_encode($members->items()) !!};
        const adminsData = {!! json_encode($admins) !!};
        
        function closeAllDropdowns() {
            document.querySelectorAll('.dropdown-menu').forEach(el => el.classList.add('hidden'));
        }
        
        document.addEventListener('click', function(e) {
            if (!e.target.closest('.dropdown')) {
                closeAllDropdowns();
            }
        });
        
        function openMemberDetailModal(memberId) {
            let member = membersData.find(m => m.id === memberId);
            if (!member) {
                member = adminsData.find(m => m.id === memberId);
            }
            if (!member) return;
            
            const initials = (member.first_name?.charAt(0) || '') + (member.last_name?.charAt(0) || '');
            const fullName = (member.first_name || '') + ' ' + (member.last_name || '');
            
            document.getElementById('detail-avatar').textContent = initials.toUpperCase();
            document.getElementById('detail-name').textContent = fullName;
            document.getElementById('detail-middle-name').textContent = member.middle_name ? member.middle_name : '';
            const isAdmin = member.role === 'admin';
            document.getElementById('detail-member-id').textContent = (isAdmin ? 'ADM-' : 'MEM-') + String(member.id).padStart(4, '0');
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
            
            const profilePic = document.getElementById('detail-profile-pic');
            const avatar = document.getElementById('detail-avatar');
            if (member.profile_picture) {
                profilePic.src = '/' + member.profile_picture;
                profilePic.classList.remove('hidden');
                avatar.classList.add('hidden');
            } else {
                profilePic.classList.add('hidden');
                avatar.classList.remove('hidden');
            }
            
            document.getElementById('detail-spouse-name').textContent = member.spouse_name || 'Not specified';
            document.getElementById('detail-spouse-dob').textContent = member.spouse_date_birth || 'Not specified';
            document.getElementById('detail-number-son').textContent = member.number_son || 0;
            document.getElementById('detail-number-daughter').textContent = member.number_daughter || 0;
            
            const skillsEl = document.getElementById('detail-skills');
            if (member.skills && member.skills.trim()) {
                const skillsArray = member.skills.split(',').map(s => s.trim()).filter(s => s);
                if (skillsArray.length > 0) {
                    skillsEl.innerHTML = skillsArray.map(skill => 
                        `<span class="inline-block px-3 py-1 bg-yellow-100 text-yellow-800 text-xs font-medium rounded-full mr-2 mb-1">${skill}</span>`
                    ).join('');
                } else {
                    skillsEl.textContent = 'No skills specified';
                }
            } else {
                skillsEl.textContent = 'No skills specified';
            }
            
            document.getElementById('detail-sss-id').textContent = member.sss_id || 'Not provided';
            document.getElementById('detail-philhealth-id').textContent = member.philhealth_id || 'Not provided';
            document.getElementById('detail-pagibig-id').textContent = member.pagibig_id || 'Not provided';
            document.getElementById('detail-tin-id').textContent = member.tin_id || 'Not provided';
            
            const vehiclesBody = document.getElementById('detail-vehicles-body');
            if (member.vehicles && member.vehicles.length > 0) {
                vehiclesBody.innerHTML = member.vehicles.map(v => `
                    <tr class="border-t border-green-100">
                        <td class="px-4 py-3 text-sm text-gray-900">${v.vehicle_type || 'N/A'}</td>
                        <td class="px-4 py-3 text-sm text-gray-900 font-mono">${v.plate_no || 'N/A'}</td>
                        <td class="px-4 py-3 text-sm text-gray-900 text-center">${v.quantity || 1}</td>
                    </tr>
                `).join('');
            } else {
                vehiclesBody.innerHTML = '<tr><td colspan="3" class="px-4 py-6 text-center text-sm text-gray-500">No vehicles registered</td></tr>';
            }
            
            const statusEl = document.getElementById('detail-status');
            if (member.role === 'admin') {
                statusEl.className = 'px-3 py-1 bg-blue-100 text-blue-700 text-xs font-semibold rounded-full';
                statusEl.textContent = 'Admin';
            } else if (member.role === 'Member' || member.role === 'member' || member.role === 'active') {
                statusEl.className = 'px-3 py-1 bg-green-100 text-green-700 text-xs font-semibold rounded-full';
                statusEl.textContent = 'Active';
            } else if (member.role === 'pending' || member.role === 'Pending') {
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
            
            document.getElementById('detail-send-sc-email').href = '/dashboard-members/send-share-email/' + member.id;
            
            // Populate Account Settings
            const roleSelect = document.getElementById('detail-role');
            roleSelect.value = member.role || 'member';
            
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }
            
            openModal('memberDetailModal');
            
            // Store member ID for save button
            window.currentMemberId = member.id;
        }
        
        document.getElementById('detail-save-btn').addEventListener('click', function() {
            const memberId = window.currentMemberId;
            const role = document.getElementById('detail-role').value;
            
            fetch('/dashboard-members/update', {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({
                    id: memberId,
                    role: role
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showToast('Success', 'Member updated successfully');
                    closeModal('memberDetailModal');
                    location.reload();
                } else {
                    showToast('Error', data.message || 'Failed to update member');
                }
            })
            .catch(error => {
                console.error('Update error:', error);
                showToast('Error', 'Failed to update member');
            });
        });
    </script>
@endsection
