@extends('layouts.admin')

@section('title', 'Savings - CoopAdmin')

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
                    <span class="text-gray-900 font-medium">Savings</span>
                </li>
            </ol>
        </nav>
    </div>

    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Savings</h1>
            <p class="text-sm text-gray-500">Manage member savings and contributions</p>
        </div>
        <div class="flex items-center gap-3">
            <button onclick="openModal('addContributionModal')" class="btn btn-primary">
                <i data-lucide="plus" class="w-4 h-4"></i>
                Add Contribution
            </button>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <div class="stat-card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 mb-1">Current Balance</p>
                    <p class="text-2xl font-bold text-gray-900">₱4,250,000</p>
                    <p class="text-xs text-success-500 mt-1 flex items-center">
                        <i data-lucide="trending-up" class="w-3 h-3 mr-1"></i>
                        +12% from last month
                    </p>
                </div>
                <div class="w-12 h-12 bg-success-100 rounded-xl flex items-center justify-center">
                    <i data-lucide="wallet" class="w-6 h-6 text-success-500"></i>
                </div>
            </div>
        </div>

        <div class="stat-card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 mb-1">Monthly Average</p>
                    <p class="text-2xl font-bold text-gray-900">₱425,000</p>
                    <p class="text-xs text-gray-500 mt-1">Last 6 months</p>
                </div>
                <div class="w-12 h-12 bg-primary-100 rounded-xl flex items-center justify-center">
                    <i data-lucide="bar-chart-2" class="w-6 h-6 text-primary-600"></i>
                </div>
            </div>
        </div>

        <div class="stat-card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 mb-1">Last Contribution</p>
                    <p class="text-2xl font-bold text-gray-900">₱15,000</p>
                    <p class="text-xs text-gray-500 mt-1">Today, 10:30 AM</p>
                </div>
                <div class="w-12 h-12 bg-warning-100 rounded-xl flex items-center justify-center">
                    <i data-lucide="calendar" class="w-6 h-6 text-warning-600"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card p-4 mb-6">
        <div class="flex flex-col md:flex-row gap-4">
            <div class="flex-1">
                <div class="relative">
                    <i data-lucide="search" class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400"></i>
                    <input type="text" placeholder="Search transactions..." class="input pl-10">
                </div>
            </div>
            <div class="flex gap-2">
                <select class="select w-40">
                    <option>All Members</option>
                    <option>Maria Santos</option>
                    <option>John Rivera</option>
                    <option>Ana Garcia</option>
                    <option>Carlos Mendez</option>
                </select>
                <select class="select w-32">
                    <option>All Types</option>
                    <option>Deposit</option>
                    <option>Withdrawal</option>
                </select>
                <select class="select w-32">
                    <option>All Status</option>
                    <option>Completed</option>
                    <option>Pending</option>
                    <option>Failed</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Transactions Table -->
    <div class="card">
        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Member Name</th>
                        <th>Amount</th>
                        <th>Type</th>
                        <th>Payment Method</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="text-sm text-gray-900">Mar 13, 2026</td>
                        <td>
                            <div class="flex items-center gap-2">
                                <div class="w-8 h-8 rounded-full bg-primary-100 flex items-center justify-center">
                                    <span class="text-xs text-primary-600 font-medium">MS</span>
                                </div>
                                <span class="text-sm text-gray-900">Maria Santos</span>
                            </div>
                        </td>
                        <td class="text-sm font-semibold text-gray-900">₱5,000</td>
                        <td><span class="badge badge-success">Deposit</span></td>
                        <td class="text-sm text-gray-600">Cash</td>
                        <td><span class="badge badge-success">Completed</span></td>
                        <td>
                            <div class="flex items-center gap-1">
                                <button class="p-1.5 hover:bg-gray-100 rounded" title="Edit">
                                    <i data-lucide="edit-2" class="w-4 h-4 text-gray-500"></i>
                                </button>
                                <button class="p-1.5 hover:bg-gray-100 rounded" title="Delete">
                                    <i data-lucide="trash-2" class="w-4 h-4 text-gray-500"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td class="text-sm text-gray-900">Mar 12, 2026</td>
                        <td>
                            <div class="flex items-center gap-2">
                                <div class="w-8 h-8 rounded-full bg-success-100 flex items-center justify-center">
                                    <span class="text-xs text-success-600 font-medium">JR</span>
                                </div>
                                <span class="text-sm text-gray-900">John Rivera</span>
                            </div>
                        </td>
                        <td class="text-sm font-semibold text-gray-900">₱3,000</td>
                        <td><span class="badge badge-success">Deposit</span></td>
                        <td class="text-sm text-gray-600">Bank Transfer</td>
                        <td><span class="badge badge-success">Completed</span></td>
                        <td>
                            <div class="flex items-center gap-1">
                                <button class="p-1.5 hover:bg-gray-100 rounded" title="Edit">
                                    <i data-lucide="edit-2" class="w-4 h-4 text-gray-500"></i>
                                </button>
                                <button class="p-1.5 hover:bg-gray-100 rounded" title="Delete">
                                    <i data-lucide="trash-2" class="w-4 h-4 text-gray-500"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td class="text-sm text-gray-900">Mar 12, 2026</td>
                        <td>
                            <div class="flex items-center gap-2">
                                <div class="w-8 h-8 rounded-full bg-warning-100 flex items-center justify-center">
                                    <span class="text-xs text-warning-600 font-medium">AG</span>
                                </div>
                                <span class="text-sm text-gray-900">Ana Garcia</span>
                            </div>
                        </td>
                        <td class="text-sm font-semibold text-gray-900">₱2,000</td>
                        <td><span class="badge badge-danger">Withdrawal</span></td>
                        <td class="text-sm text-gray-600">Cash</td>
                        <td><span class="badge badge-success">Completed</span></td>
                        <td>
                            <div class="flex items-center gap-1">
                                <button class="p-1.5 hover:bg-gray-100 rounded" title="Edit">
                                    <i data-lucide="edit-2" class="w-4 h-4 text-gray-500"></i>
                                </button>
                                <button class="p-1.5 hover:bg-gray-100 rounded" title="Delete">
                                    <i data-lucide="trash-2" class="w-4 h-4 text-gray-500"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td class="text-sm text-gray-900">Mar 11, 2026</td>
                        <td>
                            <div class="flex items-center gap-2">
                                <div class="w-8 h-8 rounded-full bg-danger-100 flex items-center justify-center">
                                    <span class="text-xs text-danger-600 font-medium">CM</span>
                                </div>
                                <span class="text-sm text-gray-900">Carlos Mendez</span>
                            </div>
                        </td>
                        <td class="text-sm font-semibold text-gray-900">₱10,000</td>
                        <td><span class="badge badge-success">Deposit</span></td>
                        <td class="text-sm text-gray-600">GCash</td>
                        <td><span class="badge badge-warning">Pending</span></td>
                        <td>
                            <div class="flex items-center gap-1">
                                <button class="p-1.5 hover:bg-gray-100 rounded" title="Edit">
                                    <i data-lucide="edit-2" class="w-4 h-4 text-gray-500"></i>
                                </button>
                                <button class="p-1.5 hover:bg-gray-100 rounded" title="Delete">
                                    <i data-lucide="trash-2" class="w-4 h-4 text-gray-500"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td class="text-sm text-gray-900">Mar 10, 2026</td>
                        <td>
                            <div class="flex items-center gap-2">
                                <div class="w-8 h-8 rounded-full bg-primary-100 flex items-center justify-center">
                                    <span class="text-xs text-primary-600 font-medium">SL</span>
                                </div>
                                <span class="text-sm text-gray-900">Sofia Lopez</span>
                            </div>
                        </td>
                        <td class="text-sm font-semibold text-gray-900">₱5,000</td>
                        <td><span class="badge badge-success">Deposit</span></td>
                        <td class="text-sm text-gray-600">Bank Transfer</td>
                        <td><span class="badge badge-success">Completed</span></td>
                        <td>
                            <div class="flex items-center gap-1">
                                <button class="p-1.5 hover:bg-gray-100 rounded" title="Edit">
                                    <i data-lucide="edit-2" class="w-4 h-4 text-gray-500"></i>
                                </button>
                                <button class="p-1.5 hover:bg-gray-100 rounded" title="Delete">
                                    <i data-lucide="trash-2" class="w-4 h-4 text-gray-500"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td class="text-sm text-gray-900">Mar 10, 2026</td>
                        <td>
                            <div class="flex items-center gap-2">
                                <div class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center">
                                    <span class="text-xs text-gray-600 font-medium">PC</span>
                                </div>
                                <span class="text-sm text-gray-900">Pedro Cruz</span>
                            </div>
                        </td>
                        <td class="text-sm font-semibold text-gray-900">₱1,500</td>
                        <td><span class="badge badge-danger">Withdrawal</span></td>
                        <td class="text-sm text-gray-600">Cash</td>
                        <td><span class="badge badge-success">Completed</span></td>
                        <td>
                            <div class="flex items-center gap-1">
                                <button class="p-1.5 hover:bg-gray-100 rounded" title="Edit">
                                    <i data-lucide="edit-2" class="w-4 h-4 text-gray-500"></i>
                                </button>
                                <button class="p-1.5 hover:bg-gray-100 rounded" title="Delete">
                                    <i data-lucide="trash-2" class="w-4 h-4 text-gray-500"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="p-4 border-t border-gray-100 flex items-center justify-between">
            <p class="text-sm text-gray-500">Showing 1-6 of 1,245 transactions</p>
            <div class="flex items-center gap-2">
                <button class="btn btn-outline px-3">
                    <i data-lucide="chevron-left" class="w-4 h-4"></i>
                </button>
                <button class="btn btn-primary px-3">1</button>
                <button class="btn btn-outline px-3">2</button>
                <button class="btn btn-outline px-3">3</button>
                <span class="px-2">...</span>
                <button class="btn btn-outline px-3">208</button>
                <button class="btn btn-outline px-3">
                    <i data-lucide="chevron-right" class="w-4 h-4"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- Automatic Deductions Toggle -->
    <div class="card p-6 mt-6">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="font-semibold text-gray-900">Automatic Monthly Deductions</h3>
                <p class="text-sm text-gray-500">Enable automatic deduction from member salaries</p>
            </div>
            <label class="relative inline-flex items-center cursor-pointer">
                <input type="checkbox" class="sr-only peer" checked>
                <div
                    class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-primary-100 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary-600">
                </div>
            </label>
        </div>
    </div>

    <!-- Add Contribution Modal -->
    <div id="addContributionModal" class="modal-overlay hidden">
        <div class="modal max-w-lg">
            <div class="p-6 border-b border-gray-100">
                <div class="flex items-center justify-between">
                    <h2 class="text-xl font-semibold text-gray-900">Add Contribution</h2>
                    <button onclick="closeModal('addContributionModal')" class="p-1 hover:bg-gray-100 rounded-lg">
                        <i data-lucide="x" class="w-5 h-5 text-gray-500"></i>
                    </button>
                </div>
            </div>
            <form class="p-6 space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Member</label>
                    <select class="select">
                        <option>Select member</option>
                        <option>Maria Santos</option>
                        <option>John Rivera</option>
                        <option>Ana Garcia</option>
                        <option>Carlos Mendez</option>
                        <option>Sofia Lopez</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Amount</label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500">₱</span>
                        <input type="number" class="input pl-8" placeholder="0.00">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Date</label>
                    <input type="date" class="input">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Type</label>
                    <select class="select">
                        <option>Deposit</option>
                        <option>Withdrawal</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Payment Method</label>
                    <select class="select">
                        <option>Cash</option>
                        <option>Bank Transfer</option>
                        <option>GCash</option>
                        <option>Check</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Notes</label>
                    <textarea class="input" rows="3" placeholder="Add any notes..."></textarea>
                </div>
            </form>
            <div class="p-6 border-t border-gray-100 flex justify-end gap-3">
                <button onclick="closeModal('addContributionModal')" class="btn btn-outline">Cancel</button>
                <button
                    onclick="closeModal('addContributionModal'); showToast('Success', 'Contribution added successfully')"
                    class="btn btn-primary">Save</button>
            </div>
        </div>
    </div>
@endsection