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
    <div class="card p-4 mb-6">
        <div class="flex flex-col md:flex-row gap-4">
            <div class="flex-1">
                <div class="relative">
                    <i data-lucide="search" class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400"></i>
                    <input type="text" placeholder="Search by name, ID, or email..." class="input pl-10">
                </div>
            </div>
            <div class="flex gap-2">
                <button class="btn bg-primary-600 text-white hover:bg-primary-700"
                    onclick="filterMembers('all')">All</button>
                <button class="btn btn-outline" onclick="filterMembers('active')">Active</button>
                <button class="btn btn-outline" onclick="filterMembers('pending')">Pending</button>
                <button class="btn btn-outline" onclick="filterMembers('inactive')">Inactive</button>
            </div>
        </div>
    </div>

    <!-- Members Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        @php
            $members = [
                ['name' => 'Maria Santos', 'id' => 'MEM-001', 'join' => 'Jan 15, 2024', 'status' => 'Active', 'savings' => '₱45,000', 'loans' => 2, 'avatar' => 'MS'],
                ['name' => 'John Rivera', 'id' => 'MEM-002', 'join' => 'Feb 20, 2024', 'status' => 'Active', 'savings' => '₱32,500', 'loans' => 1, 'avatar' => 'JR'],
                ['name' => 'Ana Garcia', 'id' => 'MEM-003', 'join' => 'Mar 10, 2024', 'status' => 'Pending', 'savings' => '₱0', 'loans' => 0, 'avatar' => 'AG'],
                ['name' => 'Carlos Mendez', 'id' => 'MEM-004', 'join' => 'Apr 5, 2024', 'status' => 'Active', 'savings' => '₱67,000', 'loans' => 3, 'avatar' => 'CM'],
                ['name' => 'Sofia Lopez', 'id' => 'MEM-005', 'join' => 'May 12, 2024', 'status' => 'Active', 'savings' => '₱28,000', 'loans' => 1, 'avatar' => 'SL'],
                ['name' => 'Pedro Cruz', 'id' => 'MEM-006', 'join' => 'Jun 18, 2024', 'status' => 'Inactive', 'savings' => '₱12,000', 'loans' => 0, 'avatar' => 'PC'],
                ['name' => 'Liza Torres', 'id' => 'MEM-007', 'join' => 'Jul 22, 2024', 'status' => 'Active', 'savings' => '₱54,000', 'loans' => 2, 'avatar' => 'LT'],
                ['name' => 'Mark Diaz', 'id' => 'MEM-008', 'join' => 'Aug 30, 2024', 'status' => 'Pending', 'savings' => '₱0', 'loans' => 0, 'avatar' => 'MD'],
            ];
        @endphp

        @foreach($members as $member)
            <div class="card p-5 hover:shadow-md transition-shadow cursor-pointer" onclick="openModal('memberDetailModal')">
                <div class="flex items-start justify-between mb-4">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 rounded-full bg-primary-100 flex items-center justify-center">
                            <span class="text-primary-600 font-semibold">{{ $member['avatar'] }}</span>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-900">{{ $member['name'] }}</h3>
                            <p class="text-xs text-gray-500">{{ $member['id'] }}</p>
                        </div>
                    </div>
                    <div class="dropdown relative">
                        <button class="p-1 rounded hover:bg-gray-100"
                            onclick="event.stopPropagation(); toggleDropdown('menu-{{ $loop->index }}')">
                            <i data-lucide="more-vertical" class="w-4 h-4 text-gray-400"></i>
                        </button>
                        <div id="menu-{{ $loop->index }}" class="dropdown-menu hidden">
                            <div class="dropdown-item" onclick="event.stopPropagation(); openModal('memberDetailModal')">
                                <i data-lucide="eye" class="w-4 h-4"></i>
                                <span>View Profile</span>
                            </div>
                            <div class="dropdown-item" onclick="event.stopPropagation()">
                                <i data-lucide="edit" class="w-4 h-4"></i>
                                <span>Edit</span>
                            </div>
                            <div class="dropdown-item text-danger-600" onclick="event.stopPropagation()">
                                <i data-lucide="trash-2" class="w-4 h-4"></i>
                                <span>Delete</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-500">Join Date</span>
                        <span class="text-sm text-gray-900">{{ $member['join'] }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-500">Status</span>
                        @if($member['status'] === 'Active')
                            <span class="badge badge-success">{{ $member['status'] }}</span>
                        @elseif($member['status'] === 'Pending')
                            <span class="badge badge-warning">{{ $member['status'] }}</span>
                        @else
                            <span class="badge badge-gray">{{ $member['status'] }}</span>
                        @endif
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-500">Savings</span>
                        <span class="text-sm font-semibold text-gray-900">{{ $member['savings'] }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-500">Active Loans</span>
                        <span class="text-sm font-semibold text-gray-900">{{ $member['loans'] }}</span>
                    </div>
                </div>

                <div class="flex gap-2 mt-4 pt-4 border-t border-gray-100">
                    <button class="btn btn-outline flex-1 text-sm"
                        onclick="event.stopPropagation(); openModal('memberDetailModal')">
                        View Profile
                    </button>
                    <button class="btn btn-primary flex-1 text-sm" onclick="event.stopPropagation()">
                        Edit
                    </button>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Pagination -->
    <div class="flex items-center justify-between mt-6">
        <p class="text-sm text-gray-500">Showing 1-8 of 1,248 members</p>
        <div class="flex items-center gap-2">
            <button class="btn btn-outline px-3">
                <i data-lucide="chevron-left" class="w-4 h-4"></i>
            </button>
            <button class="btn btn-primary px-3">1</button>
            <button class="btn btn-outline px-3">2</button>
            <button class="btn btn-outline px-3">3</button>
            <span class="px-2">...</span>
            <button class="btn btn-outline px-3">156</button>
            <button class="btn btn-outline px-3">
                <i data-lucide="chevron-right" class="w-4 h-4"></i>
            </button>
        </div>
    </div>

    <!-- Add Member Modal -->
    <div id="addMemberModal" class="modal-overlay hidden">
        <div class="modal max-w-xl">
            <div class="p-6 border-b border-gray-100">
                <div class="flex items-center justify-between">
                    <h2 class="text-xl font-semibold text-gray-900">Add New Member</h2>
                    <button onclick="closeModal('addMemberModal')" class="p-1 hover:bg-gray-100 rounded-lg">
                        <i data-lucide="x" class="w-5 h-5 text-gray-500"></i>
                    </button>
                </div>
            </div>
            <form class="p-6 space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">First Name</label>
                        <input type="text" class="input" placeholder="Enter first name">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Last Name</label>
                        <input type="text" class="input" placeholder="Enter last name">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                    <input type="email" class="input" placeholder="Enter email address">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Phone Number</label>
                    <input type="tel" class="input" placeholder="Enter phone number">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Date of Birth</label>
                    <input type="date" class="input">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Address</label>
                    <textarea class="input" rows="2" placeholder="Enter address"></textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Initial Share Capital</label>
                    <input type="number" class="input" placeholder="Enter amount">
                </div>
            </form>
            <div class="p-6 border-t border-gray-100 flex justify-end gap-3">
                <button onclick="closeModal('addMemberModal')" class="btn btn-outline">Cancel</button>
                <button onclick="closeModal('addMemberModal'); showToast('Success', 'Member added successfully')"
                    class="btn btn-primary">Add Member</button>
            </div>
        </div>
    </div>

    <!-- Member Detail Modal -->
    <div id="memberDetailModal" class="modal-overlay hidden">
        <div class="modal max-w-2xl">
            <div class="p-6 border-b border-gray-100">
                <div class="flex items-center justify-between">
                    <h2 class="text-xl font-semibold text-gray-900">Member Profile</h2>
                    <button onclick="closeModal('memberDetailModal')" class="p-1 hover:bg-gray-100 rounded-lg">
                        <i data-lucide="x" class="w-5 h-5 text-gray-500"></i>
                    </button>
                </div>
            </div>
            <div class="p-6">
                <div class="flex items-start gap-6 mb-6">
                    <div class="w-20 h-20 rounded-full bg-primary-100 flex items-center justify-center">
                        <span class="text-primary-600 text-2xl font-bold">MS</span>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-xl font-semibold text-gray-900">Maria Santos</h3>
                        <p class="text-gray-500">MEM-001</p>
                        <div class="flex gap-2 mt-2">
                            <span class="badge badge-success">Active</span>
                            <span class="badge badge-primary">Verified</span>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-6 mb-6">
                    <div class="space-y-3">
                        <h4 class="font-medium text-gray-900">Personal Information</h4>
                        <div class="space-y-2 text-sm">
                            <div class="flex justify-between">
                                <span class="text-gray-500">Email</span>
                                <span class="text-gray-900">maria.santos@email.com</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-500">Phone</span>
                                <span class="text-gray-900">+63 912 345 6789</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-500">Date of Birth</span>
                                <span class="text-gray-900">March 15, 1985</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-500">Address</span>
                                <span class="text-gray-900">123 Main St, Manila</span>
                            </div>
                        </div>
                    </div>
                    <div class="space-y-3">
                        <h4 class="font-medium text-gray-900">Account Summary</h4>
                        <div class="space-y-2 text-sm">
                            <div class="flex justify-between">
                                <span class="text-gray-500">Member Since</span>
                                <span class="text-gray-900">January 15, 2024</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-500">Savings Balance</span>
                                <span class="text-gray-900 font-semibold text-success-500">₱45,000</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-500">Share Capital</span>
                                <span class="text-gray-900 font-semibold">₱10,000</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-500">Active Loans</span>
                                <span class="text-gray-900 font-semibold">2</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mb-6">
                    <h4 class="font-medium text-gray-900 mb-3">Savings History</h4>
                    <div class="h-32 flex items-end justify-between gap-2 px-4">
                        @foreach(['Jan' => 20, 'Feb' => 35, 'Mar' => 30, 'Apr' => 45, 'May' => 40, 'Jun' => 45] as $month => $value)
                            <div class="flex-1 flex flex-col items-center gap-2">
                                <div class="w-full bg-success-100 rounded-t-lg hover:bg-success-200 transition-colors"
                                    style="height: {{ $value * 2 }}px"></div>
                                <span class="text-xs text-gray-500">{{ $month }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div>
                    <h4 class="font-medium text-gray-900 mb-3">Loan History</h4>
                    <div class="table-container">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Loan ID</th>
                                    <th>Amount</th>
                                    <th>Date</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="text-sm">LN-2024-001</td>
                                    <td class="text-sm">₱50,000</td>
                                    <td class="text-sm">Mar 15, 2024</td>
                                    <td><span class="badge badge-success">Paid</span></td>
                                </tr>
                                <tr>
                                    <td class="text-sm">LN-2024-005</td>
                                    <td class="text-sm">₱30,000</td>
                                    <td class="text-sm">Jun 20, 2024</td>
                                    <td><span class="badge badge-primary">Active</span></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="p-6 border-t border-gray-100 flex justify-between">
                <div class="flex gap-2">
                    <button class="btn btn-danger">Deactivate Account</button>
                </div>
                <div class="flex gap-2">
                    <button onclick="closeModal('memberDetailModal')" class="btn btn-outline">Close</button>
                    <button class="btn btn-primary">Edit Record</button>
                </div>
            </div>
        </div>
    </div>
@endsection