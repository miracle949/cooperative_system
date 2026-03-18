@extends('layouts.admin')

@section('title', 'Reports - CoopAdmin')

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
                    <span class="text-gray-900 font-medium">Reports</span>
                </li>
            </ol>
        </nav>
    </div>

    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Reports</h1>
            <p class="text-sm text-gray-500">Generate and export financial reports</p>
        </div>
    </div>

    <!-- Filter Panel -->
    <div class="card p-6 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">From Date</label>
                <input type="date" class="input" value="2026-01-01">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">To Date</label>
                <input type="date" class="input" value="2026-03-13">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Report Type</label>
                <select class="select">
                    <option>All Reports</option>
                    <option>Savings Report</option>
                    <option>Loan Report</option>
                    <option>Income & Expenses</option>
                    <option>Member Report</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                <select class="select">
                    <option>All Categories</option>
                    <option>Deposits</option>
                    <option>Withdrawals</option>
                    <option>Loans Issued</option>
                    <option>Loan Payments</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                <select class="select">
                    <option>All Status</option>
                    <option>Completed</option>
                    <option>Pending</option>
                    <option>Failed</option>
                </select>
            </div>
        </div>
        <div class="mt-4 flex justify-end">
            <button class="btn btn-primary">
                <i data-lucide="file-text" class="w-4 h-4"></i>
                Generate Report
            </button>
        </div>
    </div>

    <!-- Summary Statistics -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
        <div class="stat-card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 mb-1">Total Deposits</p>
                    <p class="text-2xl font-bold text-gray-900">₱2,450,000</p>
                </div>
                <div class="w-12 h-12 bg-success-100 rounded-xl flex items-center justify-center">
                    <i data-lucide="arrow-down-circle" class="w-6 h-6 text-success-500"></i>
                </div>
            </div>
        </div>

        <div class="stat-card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 mb-1">Total Withdrawals</p>
                    <p class="text-2xl font-bold text-gray-900">₱850,000</p>
                </div>
                <div class="w-12 h-12 bg-danger-100 rounded-xl flex items-center justify-center">
                    <i data-lucide="arrow-up-circle" class="w-6 h-6 text-danger-600"></i>
                </div>
            </div>
        </div>

        <div class="stat-card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 mb-1">Loans Issued</p>
                    <p class="text-2xl font-bold text-gray-900">₱1,200,000</p>
                </div>
                <div class="w-12 h-12 bg-warning-100 rounded-xl flex items-center justify-center">
                    <i data-lucide="banknote" class="w-6 h-6 text-warning-600"></i>
                </div>
            </div>
        </div>

        <div class="stat-card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 mb-1">Net Income</p>
                    <p class="text-2xl font-bold text-gray-900">₱680,000</p>
                </div>
                <div class="w-12 h-12 bg-primary-100 rounded-xl flex items-center justify-center">
                    <i data-lucide="trending-up" class="w-6 h-6 text-primary-600"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Data Visualization -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <!-- Monthly Trend Chart -->
        <div class="card p-6">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h2 class="text-lg font-semibold text-gray-900">Monthly Trend</h2>
                    <p class="text-sm text-gray-500">Savings and loan comparison</p>
                </div>
            </div>

            <div class="h-64 flex items-end justify-between gap-2 px-4">
                @foreach(['Jan' => ['savings' => 380, 'loans' => 200], 'Feb' => ['savings' => 420, 'loans' => 180], 'Mar' => ['savings' => 350, 'loans' => 250], 'Apr' => ['savings' => 480, 'loans' => 220], 'May' => ['savings' => 420, 'loans' => 190], 'Jun' => ['savings' => 520, 'loans' => 280]] as $month => $data)
                    <div class="flex-1 flex flex-col items-center gap-1">
                        <div class="w-full flex gap-1 items-end" style="height: 160px">
                            <div class="flex-1 bg-primary-200 rounded-t hover:bg-primary-300 transition-colors"
                                style="height: {{ $data['savings'] * 0.3 }}px"></div>
                            <div class="flex-1 bg-warning-200 rounded-t hover:bg-warning-300 transition-colors"
                                style="height: {{ $data['loans'] * 0.3 }}px"></div>
                        </div>
                        <span class="text-xs text-gray-500">{{ $month }}</span>
                    </div>
                @endforeach
            </div>

            <div class="flex justify-center gap-6 mt-4">
                <div class="flex items-center gap-2">
                    <div class="w-3 h-3 bg-primary-300 rounded"></div>
                    <span class="text-sm text-gray-600">Savings</span>
                </div>
                <div class="flex items-center gap-2">
                    <div class="w-3 h-3 bg-warning-300 rounded"></div>
                    <span class="text-sm text-gray-600">Loans</span>
                </div>
            </div>
        </div>

        <!-- Transaction Distribution -->
        <div class="card p-6">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h2 class="text-lg font-semibold text-gray-900">Transaction Distribution</h2>
                    <p class="text-sm text-gray-500">By category</p>
                </div>
            </div>

            <div class="space-y-4">
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm text-gray-600">Savings Deposits</span>
                        <span class="text-sm font-medium text-gray-900">45%</span>
                    </div>
                    <div class="w-full bg-gray-100 rounded-full h-4">
                        <div class="bg-success-500 h-4 rounded-full" style="width: 45%"></div>
                    </div>
                </div>

                <div>
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm text-gray-600">Loan Repayments</span>
                        <span class="text-sm font-medium text-gray-900">30%</span>
                    </div>
                    <div class="w-full bg-gray-100 rounded-full h-4">
                        <div class="bg-primary-500 h-4 rounded-full" style="width: 30%"></div>
                    </div>
                </div>

                <div>
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm text-gray-600">Share Capital</span>
                        <span class="text-sm font-medium text-gray-900">15%</span>
                    </div>
                    <div class="w-full bg-gray-100 rounded-full h-4">
                        <div class="bg-warning-500 h-4 rounded-full" style="width: 15%"></div>
                    </div>
                </div>

                <div>
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm text-gray-600">Other Income</span>
                        <span class="text-sm font-medium text-gray-900">10%</span>
                    </div>
                    <div class="w-full bg-gray-100 rounded-full h-4">
                        <div class="bg-gray-400 h-4 rounded-full" style="width: 10%"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Detailed Table -->
    <div class="card mb-6">
        <div class="p-4 border-b border-gray-100">
            <h2 class="text-lg font-semibold text-gray-900">Detailed Transactions</h2>
        </div>
        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Reference No.</th>
                        <th>Description</th>
                        <th>Category</th>
                        <th>Amount</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="text-sm text-gray-900">Mar 13, 2026</td>
                        <td class="text-sm text-gray-600">TRX-001245</td>
                        <td class="text-sm text-gray-900">Savings deposit - Maria Santos</td>
                        <td><span class="badge badge-success">Deposit</span></td>
                        <td class="text-sm font-semibold text-success-500">+₱5,000</td>
                        <td><span class="badge badge-success">Completed</span></td>
                    </tr>
                    <tr>
                        <td class="text-sm text-gray-900">Mar 12, 2026</td>
                        <td class="text-sm text-gray-600">TRX-001244</td>
                        <td class="text-sm text-gray-900">Loan repayment - John Rivera</td>
                        <td><span class="badge badge-primary">Loan Payment</span></td>
                        <td class="text-sm font-semibold text-success-500">+₱8,500</td>
                        <td><span class="badge badge-success">Completed</span></td>
                    </tr>
                    <tr>
                        <td class="text-sm text-gray-900">Mar 12, 2026</td>
                        <td class="text-sm text-gray-600">TRX-001243</td>
                        <td class="text-sm text-gray-900">Savings withdrawal - Ana Garcia</td>
                        <td><span class="badge badge-danger">Withdrawal</span></td>
                        <td class="text-sm font-semibold text-danger-500">-₱2,000</td>
                        <td><span class="badge badge-success">Completed</span></td>
                    </tr>
                    <tr>
                        <td class="text-sm text-gray-900">Mar 11, 2026</td>
                        <td class="text-sm text-gray-600">TRX-001242</td>
                        <td class="text-sm text-gray-900">Share capital - Carlos Mendez</td>
                        <td><span class="badge badge-warning">Share Capital</span></td>
                        <td class="text-sm font-semibold text-success-500">+₱10,000</td>
                        <td><span class="badge badge-success">Completed</span></td>
                    </tr>
                    <tr>
                        <td class="text-sm text-gray-900">Mar 10, 2026</td>
                        <td class="text-sm text-gray-600">TRX-001241</td>
                        <td class="text-sm text-gray-900">Loan disbursement - Sofia Lopez</td>
                        <td><span class="badge badge-primary">Loan Issued</span></td>
                        <td class="text-sm font-semibold text-danger-500">-₱30,000</td>
                        <td><span class="badge badge-success">Completed</span></td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-gray-100">
            <button class="btn btn-outline w-full">View All Transactions</button>
        </div>
    </div>

    <!-- Export Actions -->
    <div class="card p-6 mb-6">
        <div class="flex flex-col md:flex-row items-center justify-between gap-4">
            <div>
                <h3 class="font-semibold text-gray-900">Export Report</h3>
                <p class="text-sm text-gray-500">Download this report in your preferred format</p>
            </div>
            <div class="flex gap-3">
                <button class="btn btn-outline" onclick="showToast('PDF Generated', 'Report has been downloaded as PDF')">
                    <i data-lucide="file-text" class="w-4 h-4"></i>
                    Export PDF
                </button>
                <button class="btn btn-outline"
                    onclick="showToast('Excel Generated', 'Report has been downloaded as Excel')">
                    <i data-lucide="table" class="w-4 h-4"></i>
                    Export Excel
                </button>
                <button class="btn btn-outline" onclick="showToast('Print', 'Preparing print preview')">
                    <i data-lucide="printer" class="w-4 h-4"></i>
                    Print
                </button>
            </div>
        </div>
    </div>

    <!-- Recent Reports -->
    <div class="card p-6">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h2 class="text-lg font-semibold text-gray-900">Recent Reports</h2>
                <p class="text-sm text-gray-500">Previously generated reports</p>
            </div>
        </div>

        <div class="space-y-3">
            <div
                class="flex items-center justify-between p-4 border border-gray-200 rounded-xl hover:bg-gray-50 transition-colors">
                <div class="flex items-center gap-4">
                    <div class="w-10 h-10 bg-danger-100 rounded-lg flex items-center justify-center">
                        <i data-lucide="file-text" class="w-5 h-5 text-danger-600"></i>
                    </div>
                    <div>
                        <p class="font-medium text-gray-900">Monthly Savings Report</p>
                        <p class="text-sm text-gray-500">February 2026</p>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <span class="text-sm text-gray-500">Mar 1, 2026</span>
                    <button class="p-2 hover:bg-gray-100 rounded-lg" title="Download">
                        <i data-lucide="download" class="w-4 h-4 text-gray-500"></i>
                    </button>
                    <button class="p-2 hover:bg-gray-100 rounded-lg" title="Delete">
                        <i data-lucide="trash-2" class="w-4 h-4 text-gray-500"></i>
                    </button>
                </div>
            </div>

            <div
                class="flex items-center justify-between p-4 border border-gray-200 rounded-xl hover:bg-gray-50 transition-colors">
                <div class="flex items-center gap-4">
                    <div class="w-10 h-10 bg-warning-100 rounded-lg flex items-center justify-center">
                        <i data-lucide="file-text" class="w-5 h-5 text-warning-600"></i>
                    </div>
                    <div>
                        <p class="font-medium text-gray-900">Loan Portfolio Summary</p>
                        <p class="text-sm text-gray-500">Q1 2026</p>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <span class="text-sm text-gray-500">Feb 28, 2026</span>
                    <button class="p-2 hover:bg-gray-100 rounded-lg" title="Download">
                        <i data-lucide="download" class="w-4 h-4 text-gray-500"></i>
                    </button>
                    <button class="p-2 hover:bg-gray-100 rounded-lg" title="Delete">
                        <i data-lucide="trash-2" class="w-4 h-4 text-gray-500"></i>
                    </button>
                </div>
            </div>

            <div
                class="flex items-center justify-between p-4 border border-gray-200 rounded-xl hover:bg-gray-50 transition-colors">
                <div class="flex items-center gap-4">
                    <div class="w-10 h-10 bg-primary-100 rounded-lg flex items-center justify-center">
                        <i data-lucide="file-text" class="w-5 h-5 text-primary-600"></i>
                    </div>
                    <div>
                        <p class="font-medium text-gray-900">Income & Expenses Report</p>
                        <p class="text-sm text-gray-500">January 2026</p>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <span class="text-sm text-gray-500">Feb 1, 2026</span>
                    <button class="p-2 hover:bg-gray-100 rounded-lg" title="Download">
                        <i data-lucide="download" class="w-4 h-4 text-gray-500"></i>
                    </button>
                    <button class="p-2 hover:bg-gray-100 rounded-lg" title="Delete">
                        <i data-lucide="trash-2" class="w-4 h-4 text-gray-500"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection