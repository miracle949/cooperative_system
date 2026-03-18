@extends('layouts.admin')

@section('title', 'Loan Processing - CoopAdmin')

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
                    <span class="text-gray-900 font-medium">Loan Processing</span>
                </li>
            </ol>
        </nav>
    </div>

    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Loan Processing</h1>
            <p class="text-sm text-gray-500">Manage loan applications and approvals</p>
        </div>
        <div class="flex items-center gap-3">
            <button class="btn btn-outline">
                <i data-lucide="filter" class="w-4 h-4"></i>
                Filter
            </button>
            <button class="btn btn-primary">
                <i data-lucide="plus" class="w-4 h-4"></i>
                New Loan
            </button>
        </div>
    </div>

    <!-- Status Tabs -->
    <div class="flex gap-2 mb-6">
        <button class="tab-btn btn bg-primary-600 text-white px-4" onclick="switchTab('tab-all', this)">
            All
        </button>
        <button class="tab-btn btn bg-gray-100 text-gray-600 px-4" onclick="switchTab('tab-pending', this)">
            Pending
        </button>
        <button class="tab-btn btn bg-gray-100 text-gray-600 px-4" onclick="switchTab('tab-approved', this)">
            Approved
        </button>
        <button class="tab-btn btn bg-gray-100 text-gray-600 px-4" onclick="switchTab('tab-declined', this)">
            Declined
        </button>
    </div>

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
                <tbody>
                    <tr class="cursor-pointer" onclick="openModal('loanDetailModal')">
                        <td>
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-primary-100 flex items-center justify-center">
                                    <span class="text-primary-600 font-semibold">MS</span>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900">Maria Santos</p>
                                    <p class="text-xs text-gray-500">MEM-001</p>
                                </div>
                            </div>
                        </td>
                        <td class="text-sm font-semibold text-gray-900">₱50,000</td>
                        <td class="text-sm text-gray-600">Business Capital</td>
                        <td class="text-sm text-gray-600">12 months</td>
                        <td class="text-sm text-gray-600">Mar 10, 2026</td>
                        <td><span class="badge badge-warning">Pending</span></td>
                        <td>
                            <button class="btn btn-outline text-sm"
                                onclick="event.stopPropagation(); openModal('loanDetailModal')">
                                View Details
                            </button>
                        </td>
                    </tr>
                    <tr class="cursor-pointer" onclick="openModal('loanDetailModal')">
                        <td>
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-success-100 flex items-center justify-center">
                                    <span class="text-success-600 font-semibold">JR</span>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900">John Rivera</p>
                                    <p class="text-xs text-gray-500">MEM-002</p>
                                </div>
                            </div>
                        </td>
                        <td class="text-sm font-semibold text-gray-900">₱100,000</td>
                        <td class="text-sm text-gray-600">Housing Loan</td>
                        <td class="text-sm text-gray-600">24 months</td>
                        <td class="text-sm text-gray-600">Mar 8, 2026</td>
                        <td><span class="badge badge-warning">Pending</span></td>
                        <td>
                            <button class="btn btn-outline text-sm"
                                onclick="event.stopPropagation(); openModal('loanDetailModal')">
                                View Details
                            </button>
                        </td>
                    </tr>
                    <tr class="cursor-pointer" onclick="openModal('loanDetailModal')">
                        <td>
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-warning-100 flex items-center justify-center">
                                    <span class="text-warning-600 font-semibold">AG</span>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900">Ana Garcia</p>
                                    <p class="text-xs text-gray-500">MEM-003</p>
                                </div>
                            </div>
                        </td>
                        <td class="text-sm font-semibold text-gray-900">₱25,000</td>
                        <td class="text-sm text-gray-600">Personal Loan</td>
                        <td class="text-sm text-gray-600">6 months</td>
                        <td class="text-sm text-gray-600">Mar 5, 2026</td>
                        <td><span class="badge badge-success">Approved</span></td>
                        <td>
                            <button class="btn btn-outline text-sm"
                                onclick="event.stopPropagation(); openModal('loanDetailModal')">
                                View Details
                            </button>
                        </td>
                    </tr>
                    <tr class="cursor-pointer" onclick="openModal('loanDetailModal')">
                        <td>
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-danger-100 flex items-center justify-center">
                                    <span class="text-danger-600 font-semibold">CM</span>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900">Carlos Mendez</p>
                                    <p class="text-xs text-gray-500">MEM-004</p>
                                </div>
                            </div>
                        </td>
                        <td class="text-sm font-semibold text-gray-900">₱75,000</td>
                        <td class="text-sm text-gray-600">Emergency Loan</td>
                        <td class="text-sm text-gray-600">12 months</td>
                        <td class="text-sm text-gray-600">Mar 3, 2026</td>
                        <td><span class="badge badge-danger">Declined</span></td>
                        <td>
                            <button class="btn btn-outline text-sm"
                                onclick="event.stopPropagation(); openModal('loanDetailModal')">
                                View Details
                            </button>
                        </td>
                    </tr>
                    <tr class="cursor-pointer" onclick="openModal('loanDetailModal')">
                        <td>
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-primary-100 flex items-center justify-center">
                                    <span class="text-primary-600 font-semibold">SL</span>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900">Sofia Lopez</p>
                                    <p class="text-xs text-gray-500">MEM-005</p>
                                </div>
                            </div>
                        </td>
                        <td class="text-sm font-semibold text-gray-900">₱30,000</td>
                        <td class="text-sm text-gray-600">Business Capital</td>
                        <td class="text-sm text-gray-600">18 months</td>
                        <td class="text-sm text-gray-600">Feb 28, 2026</td>
                        <td><span class="badge badge-success">Approved</span></td>
                        <td>
                            <button class="btn btn-outline text-sm"
                                onclick="event.stopPropagation(); openModal('loanDetailModal')">
                                View Details
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="p-4 border-t border-gray-100 flex items-center justify-between">
            <p class="text-sm text-gray-500">Showing 1-5 of 18 loan requests</p>
            <div class="flex items-center gap-2">
                <button class="btn btn-outline px-3">
                    <i data-lucide="chevron-left" class="w-4 h-4"></i>
                </button>
                <button class="btn btn-primary px-3">1</button>
                <button class="btn btn-outline px-3">2</button>
                <button class="btn btn-outline px-3">3</button>
                <button class="btn btn-outline px-3">
                    <i data-lucide="chevron-right" class="w-4 h-4"></i>
                </button>
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
                        <p class="text-sm text-gray-500">LN-2026-003</p>
                    </div>
                    <button onclick="closeModal('loanDetailModal')" class="p-1 hover:bg-gray-100 rounded-lg">
                        <i data-lucide="x" class="w-5 h-5 text-gray-500"></i>
                    </button>
                </div>
            </div>

            <div class="p-6 space-y-6">
                <!-- Member Info -->
                <div class="flex items-center gap-4 p-4 bg-gray-50 rounded-xl">
                    <div class="w-16 h-16 rounded-full bg-primary-100 flex items-center justify-center">
                        <span class="text-primary-600 text-xl font-bold">MS</span>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-lg font-semibold text-gray-900">Maria Santos</h3>
                        <p class="text-sm text-gray-500">MEM-001</p>
                        <div class="flex gap-4 mt-2">
                            <span class="text-sm text-gray-600">
                                <i data-lucide="phone" class="w-4 h-4 inline mr-1"></i>
                                +63 912 345 6789
                            </span>
                            <span class="text-sm text-gray-600">
                                <i data-lucide="mail" class="w-4 h-4 inline mr-1"></i>
                                maria.santos@email.com
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Loan Details -->
                <div class="grid grid-cols-2 gap-4">
                    <div class="p-4 border border-gray-200 rounded-xl">
                        <p class="text-sm text-gray-500 mb-1">Loan Amount</p>
                        <p class="text-2xl font-bold text-gray-900">₱50,000</p>
                    </div>
                    <div class="p-4 border border-gray-200 rounded-xl">
                        <p class="text-sm text-gray-500 mb-1">Interest Rate</p>
                        <p class="text-2xl font-bold text-gray-900">12%</p>
                    </div>
                    <div class="p-4 border border-gray-200 rounded-xl">
                        <p class="text-sm text-gray-500 mb-1">Duration</p>
                        <p class="text-2xl font-bold text-gray-900">12 months</p>
                    </div>
                    <div class="p-4 border border-gray-200 rounded-xl">
                        <p class="text-sm text-gray-500 mb-1">Monthly Payment</p>
                        <p class="text-2xl font-bold text-gray-900">₱4,667</p>
                    </div>
                </div>

                <div>
                    <p class="text-sm text-gray-500 mb-2">Purpose</p>
                    <p class="text-gray-900">Business Capital - For expansion of sari-sari store</p>
                </div>

                <!-- Documents -->
                <div>
                    <h4 class="font-medium text-gray-900 mb-3">Attached Documents</h4>
                    <div class="space-y-2">
                        <div
                            class="flex items-center justify-between p-3 border border-gray-200 rounded-lg hover:bg-gray-50">
                            <div class="flex items-center gap-3">
                                <i data-lucide="file-text" class="w-5 h-5 text-gray-500"></i>
                                <span class="text-sm text-gray-900">Valid ID</span>
                            </div>
                            <button class="btn btn-outline text-sm py-1">
                                <i data-lucide="download" class="w-4 h-4"></i>
                                Download
                            </button>
                        </div>
                        <div
                            class="flex items-center justify-between p-3 border border-gray-200 rounded-lg hover:bg-gray-50">
                            <div class="flex items-center gap-3">
                                <i data-lucide="file-text" class="w-5 h-5 text-gray-500"></i>
                                <span class="text-sm text-gray-900">Proof of Income</span>
                            </div>
                            <button class="btn btn-outline text-sm py-1">
                                <i data-lucide="download" class="w-4 h-4"></i>
                                Download
                            </button>
                        </div>
                        <div
                            class="flex items-center justify-between p-3 border border-gray-200 rounded-lg hover:bg-gray-50">
                            <div class="flex items-center gap-3">
                                <i data-lucide="file-text" class="w-5 h-5 text-gray-500"></i>
                                <span class="text-sm text-gray-900">Business Permit</span>
                            </div>
                            <button class="btn btn-outline text-sm py-1">
                                <i data-lucide="download" class="w-4 h-4"></i>
                                Download
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Payment Schedule -->
                <div>
                    <h4 class="font-medium text-gray-900 mb-3">Payment Schedule Preview</h4>
                    <div class="table-container">
                        <table class="table text-sm">
                            <thead>
                                <tr>
                                    <th>Month</th>
                                    <th>Payment</th>
                                    <th>Principal</th>
                                    <th>Interest</th>
                                    <th>Balance</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>1</td>
                                    <td>₱4,667</td>
                                    <td>₱4,167</td>
                                    <td>₱500</td>
                                    <td>₱45,833</td>
                                </tr>
                                <tr>
                                    <td>2</td>
                                    <td>₱4,667</td>
                                    <td>₱4,208</td>
                                    <td>₱459</td>
                                    <td>₱41,625</td>
                                </tr>
                                <tr>
                                    <td>3</td>
                                    <td>₱4,667</td>
                                    <td>₱4,250</td>
                                    <td>₱417</td>
                                    <td>₱37,375</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="p-6 border-t border-gray-100">
                <div class="flex flex-col sm:flex-row gap-3 justify-between">
                    <button class="btn btn-outline">
                        <i data-lucide="calculator" class="w-4 h-4"></i>
                        Generate Schedule
                    </button>
                    <div class="flex gap-3">
                        <button
                            onclick="closeModal('loanDetailModal'); showToast('Declined', 'Loan application has been declined', 'error')"
                            class="btn btn-danger flex-1">
                            <i data-lucide="x-circle" class="w-4 h-4"></i>
                            Decline
                        </button>
                        <button
                            onclick="closeModal('loanDetailModal'); showToast('Approved', 'Loan application has been approved', 'success')"
                            class="btn btn-success flex-1">
                            <i data-lucide="check-circle" class="w-4 h-4"></i>
                            Approve
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection