<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Homepage</title>
    <link rel="icon" href="images/websitelogo.png" type="image/png">

    {{-- AOS animation link css --}}
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

    {{-- css link --}}
    <link rel="stylesheet" href="css_folder/homepage.css">
    <link rel="stylesheet" href="css_folder/loading.css">

    {{-- bootstrap and tailwind link --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- font awesome cdn link --}}
    <link rel="stylesheet" href="font-awesome-icon/css/all.min.css">

    <style>
        /* ─── Toast ─────────────────────────────────────────── */
        .toast-message {
            position: fixed;
            right: 20px;
            top: 20px;
            padding: 1rem 1.5rem;
            color: var(--teal);
            background-color: #ffffff;
            box-shadow: 0 1px 3px rgba(0, 0, 0, .06), 0 1px 2px rgba(0, 0, 0, .04);
            border: 1px solid #E2E8E5;
            width: 250px;
            display: flex;
            align-items: center;
            border-radius: 10px;
            gap: 1rem;
            z-index: 99999;
            overflow: hidden;
            animation: toastSlideIn .4s cubic-bezier(.22, 1, .36, 1) forwards;
        }

        .toast-message.hide {
            animation: toastFadeOut .4s ease-in forwards;
        }

        .toast-message::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            background-color: var(--teal);
            height: 100%;
            width: 5px;
        }

        .toast-message p {
            margin: 0;
            font-weight: 600;
        }

        @keyframes toastSlideIn {
            from {
                opacity: 0;
                transform: translateX(60px);
            }

            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @keyframes toastFadeOut {
            from {
                opacity: 1;
                transform: translateX(0);
            }

            to {
                opacity: 0;
                transform: translateX(60px);
            }
        }

        /* ─── Skeleton overlay ───────────────────────────────── */
        #skeleton-overlay {
            position: fixed;
            top: 0;
            right: 0;
            bottom: 0;
            left: var(--sidebar-width, 250px);
            background: #fff;
            z-index: 9998;
            padding: 20px 32px 32px 32px;
            overflow: hidden;
            transition: opacity .45s ease;
        }

        @keyframes skshimmer {
            0% {
                background-position: -700px 0;
            }

            100% {
                background-position: 700px 0;
            }
        }

        .sk {
            background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
            background-size: 700px 100%;
            animation: skshimmer 1.4s infinite linear;
            border-radius: 6px;
        }

        .sk-round {
            border-radius: 50%;
        }

        .sk-pill {
            border-radius: 20px;
        }

        .sk-card {
            border-radius: 12px;
        }

        /* fade out skeleton */
        #skeleton-overlay.sk-hide {
            opacity: 0;
            pointer-events: none;
        }

        /* ─── Real content ───────────────────────────────────── */
        #page-content {
            transition: opacity .4s ease .1s;
            display: contents;
        }

        #page-content.sk-ready {
            opacity: 1 !important;
        }
    </style>
</head>

<body>

    {{-- ═══════════════════════════════════════════════
    SKELETON OVERLAY — only shown right after login
    ═══════════════════════════════════════════════ --}}
    @if (session('just_logged_in'))
        <div id="skeleton-overlay" aria-hidden="true">

            {{-- Navbar bar --}}
            <div class="sk sk-card" style="height:70px; margin-bottom:28px;"></div>

            {{-- Welcome banner --}}
            <div class="sk sk-card" style="height:150px; margin-bottom:28px;"></div>

            {{-- 3 summary cards --}}
            <div style="display:grid; grid-template-columns:repeat(3,1fr); gap:14px; margin-bottom:24px;">
                <div class="sk sk-card" style="height:165px;"></div>
                <div class="sk sk-card" style="height:165px;"></div>
                <div class="sk sk-card" style="height:165px;"></div>
            </div>

            {{-- Apply banner --}}
            <div class="sk sk-card" style="height:114px; margin-bottom:24px;"></div>

            {{-- Loans + right sidebar --}}
            <div style="display:grid; grid-template-columns:1fr 300px; gap:18px;">

                {{-- Loan panel --}}
                <div>
                    {{-- Tab buttons --}}
                    <div style="display:flex; gap:8px; margin-bottom:14px;">
                        <div class="sk sk-pill" style="width:64px; height:34px;"></div>
                        <div class="sk sk-pill" style="width:84px; height:34px;"></div>
                        <div class="sk sk-pill" style="width:74px; height:34px;"></div>
                        <div class="sk sk-pill" style="width:78px; height:34px;"></div>
                    </div>
                    {{-- Loan card 1 --}}
                    <div class="sk sk-card" style="height:170px; margin-bottom:14px;"></div>
                    {{-- Loan card 2 --}}
                    <div class="sk sk-card" style="height:170px;"></div>
                </div>

                {{-- Right sidebar --}}
                <div style="display:flex; flex-direction:column; gap:14px;">
                    <div class="sk sk-card" style="height:148px;"></div>
                    <div class="sk sk-card" style="height:168px;"></div>
                    <div class="sk sk-card" style="height:148px;"></div>
                </div>
            </div>

        </div>
    @endif
    {{-- end #skeleton-overlay --}}


    {{-- Sidebar always visible — outside the fading wrapper --}}
    <div class="container-fluid p-0 m-0">
        @include("components.offcanvas")
        @include("components.sidebar")

        {{-- ═══════════════════════════════════════════════
        REAL PAGE CONTENT
        — hidden on first login load, visible otherwise
        ═══════════════════════════════════════════════ --}}
        <div id="page-content" @if(session('just_logged_in')) style="opacity:0;" @endif>
            <div class="rightbar">
                @include("components.navbar2")

                <div class="main-parent">
                    <main>

                        <!-- <p>April 25, 2026</p> -->
                        <h2>Good day, {{ $username }}! <span>Here's your overview</span></h2>

                        @if ($username)
                            <div class="main-header">
                                <div class="main-intro">
                                    <div class="main-intro-icon"></div>
                                    <div class="main-intro-text">
                                        <span>Member Cooperative Assistant</span>
                                        <!-- <h3>Hello Welcome, {{ $username }}!</h3> -->
                                        <!-- <p>Here's a summary of your cooperative account as of today.</p> -->
                                        <p>Your money are growing steadily. Every peso you save today builds a stronger
                                            tomorrow for you and the community.</p>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <div class="card-parent">

                            {{-- Savings Balance --}}
                            <div class="card-box" onclick="window.location='{{ route('savings.index') }}'">
                                <!-- <div class="card-transparent">
                                    <p>Savings Balance</p>
                                    <h5>₱ {{ number_format($savingsAccount->balance ?? 0, 2) }}</h5>
                                    <p>↑ +₱3,200 this month</p>
                                </div> -->
                                <div class="card-header">
                                    <p>Savings Balance</p>

                                    <div class="update">
                                        <i class="fa fa-arrow-up"></i>
                                        <p>Active</p>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <h5>₱ {{ number_format($savingsAccount->balance ?? 0, 2) }}</h5>
                                    <p>↑ +₱3,200 this month</p>
                                </div>
                            </div>

                            {{-- Active Loans --}}
                            <div class="card-box" onclick="window.location='{{ route('LoanStatus') }}'">
                                <!-- <div class="card-transparent">
                                    <p>Active Loans</p>
                                    <h5>{{ $activeLoansCount }} Loan(s)</h5>
                                    <p>2 active loans</p>
                                </div>
                                <div class="card-update">
                                    <div class="update">
                                        <i class="fa fa-arrow-up"></i>
                                        <p>Active</p>
                                    </div>
                                </div> -->

                                <div class="card-header">
                                    <p>Active Loans</p>

                                    <div class="update">
                                        <i class="fa fa-arrow-up"></i>
                                        <p>Active</p>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <h5>{{ $activeLoansCount }} Loan(s)</h5>
                                    <p>2 active loans</p>
                                </div>
                            </div>

                            {{-- Overdue Loans --}}
                            <div class="card-box" onclick="window.location='{{ route('LoanStatus') }}'">
                                <!-- <div class="card-transparent">
                                    <p>Overdue Loans</p>
                                    <h5>{{ $overdueCount }} Loan(s)</h5>
                                    <p>⚠ Due May 15</p>
                                </div>
                                <div class="card-update">
                                    <div class="update">
                                        @if($overdueCount > 0)
                                            <p style="color:#dc2626;">Total: ₱{{ number_format($totalLateFees, 2) }}</p>
                                        @else
                                            <p style="color:#22c55e;">No overdue</p>
                                        @endif
                                    </div>
                                </div> -->

                                <div class="card-header">
                                    <p>Overdue Loans</p>

                                    <div class="update">
                                        @if($overdueCount > 0)
                                            <p style="color:#dc2626;">Total: ₱{{ number_format($totalLateFees, 2) }}</p>
                                        @else
                                            <p style="color:#0f6e56;">No overdue</p>
                                        @endif
                                    </div>
                                </div>
                                <div class="card-body">
                                    <h5>{{ $overdueCount }} Loan(s)</h5>
                                    <p>⚠ Due May 15</p>
                                </div>
                            </div>

                        </div>
                    </main>

                    <section>
                        <div class="ask-box">
                            <div class="text-box">
                                <h4>Need financial assistance?</h4>
                                <p>Apply for a lending today — fast processing, exclusive member rates.</p>
                            </div>
                            <div class="link-box">
                                <a href="{{ route('LoanApplication') }}">
                                    <i class="fa fa-plus"></i>
                                    <span>Apply for a Loan</span>
                                </a>
                            </div>
                        </div>

                        <h3>Quick Summary</h3>

                        <div class="card-box-summary">

                            <div class="recent-transaction">
                                <div class="recent-header">
                                    <div>
                                        <h4>Recent Transactions</h4>
                                        <p>Latest account activity across all accounts</p>
                                    </div>

                                    <div>
                                        <a href="#">View all</a>
                                    </div>
                                </div>
                                <div class="recent-body">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>Date</th>
                                                <th>Description</th>
                                                <th>Reference</th>
                                                <th>Type</th>
                                                <th>Amount</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>May 28, 2026</td>
                                                <td>Savings Deposit</td>
                                                <td>
                                                    REF-052801</td>
                                                <td>Savings</td>
                                                <td>+₱3,200.00</td>
                                            </tr>

                                            <tr>
                                                <td>May 28, 2026</td>
                                                <td>Loan Payment</td>
                                                <td>
                                                    REF-052801</td>
                                                <td>Savings</td>
                                                <td>+₱3,200.00</td>
                                            </tr>

                                            <tr>
                                                <td>May 28, 2026</td>
                                                <td>Loan Payment</td>
                                                <td>
                                                    REF-052801</td>
                                                <td>Savings</td>
                                                <td>+₱3,200.00</td>
                                            </tr>

                                            <tr>
                                                <td>May 28, 2026</td>
                                                <td>Share Capital Contribution</td>
                                                <td>
                                                    REF-052801</td>
                                                <td>Savings</td>
                                                <td>+₱3,200.00</td>
                                            </tr>

                                            <tr>
                                                <td>May 28, 2026</td>
                                                <td>Savings Deposit</td>
                                                <td>
                                                    REF-052801</td>
                                                <td>Savings</td>
                                                <td>+₱3,200.00</td>
                                            </tr>

                                            <tr>
                                                <td>May 28, 2026</td>
                                                <td>Savings Deposit</td>
                                                <td>
                                                    REF-052801</td>
                                                <td>Savings</td>
                                                <td>+₱3,200.00</td>
                                            </tr>

                                            <tr>
                                                <td>May 28, 2026</td>
                                                <td>Savings Deposit</td>
                                                <td>
                                                    REF-052801</td>
                                                <td>Savings</td>
                                                <td>+₱3,200.00</td>
                                            </tr>

                                            <tr>
                                                <td>May 28, 2026</td>
                                                <td>Savings Deposit</td>
                                                <td>
                                                    REF-052801</td>
                                                <td>Savings</td>
                                                <td>+₱3,200.00</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="loan-overview">
                                <div class="loan-header">
                                    <div>
                                        <h4>Loan Overview</h4>
                                        <p>Your loan details and repayment progress</p>
                                    </div>

                                    <div>
                                        <a href="#">View all</a>
                                    </div>
                                </div>
                                <div class="loan-body">
                                    <table class="table">
                                        <thead>

                                            <tr>
                                                <th>Loan #</th>
                                                <th>Type</th>
                                                <th>Amount</th>
                                                <th>Balance</th>
                                                <th>Next Due</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                            <tr>
                                                <td>
                                                    <p>LN-2024-001</p>

                                                    <p>Released Jan 10</p>
                                                </td>
                                                <td>Emergency Loan</td>
                                                <td>₱15,000</td>
                                                <td>
                                                    <p>₱9,200</p>

                                                    <div class="parent-progress">
                                                        <div class="progress"></div>
                                                    </div>

                                                    <p>39% paid</p>
                                                </td>
                                                <td>June 15</td>
                                                <td>Active</td>
                                            </tr>

                                            <tr>
                                                <td>
                                                    <p>LN-2024-001</p>

                                                    <p>Released Jan 10</p>
                                                </td>
                                                <td>Personal Loan</td>
                                                <td>₱15,000</td>
                                                <td>
                                                    <p>₱9,200</p>

                                                    <div class="parent-progress">
                                                        <div class="progress"></div>
                                                    </div>

                                                    <p>39% paid</p>
                                                </td>
                                                <td>June 15</td>
                                                <td>Active</td>
                                            </tr>

                                            <tr>
                                                <td>
                                                    <p>LN-2024-001</p>

                                                    <p>Released Jan 10</p>
                                                </td>
                                                <td>Education Loan</td>
                                                <td>₱15,000</td>
                                                <td>
                                                    <p>₱9,200</p>

                                                    <div class="parent-progress">
                                                        <div class="progress"></div>
                                                    </div>

                                                    <p>39% paid</p>
                                                </td>
                                                <td>June 15</td>
                                                <td>Active</td>
                                            </tr>

                                            <tr>
                                                <td>
                                                    <p>LN-2024-001</p>

                                                    <p>Released Jan 10</p>
                                                </td>
                                                <td>Education Loan</td>
                                                <td>₱15,000</td>
                                                <td>
                                                    <p>₱9,200</p>

                                                    <div class="parent-progress">
                                                        <div class="progress"></div>
                                                    </div>

                                                    <p>39% paid</p>
                                                </td>
                                                <td>June 15</td>
                                                <td>Active</td>
                                            </tr>

                                            <tr>
                                                <td>
                                                    <p>LN-2024-001</p>

                                                    <p>Released Jan 10</p>
                                                </td>
                                                <td>Education Loan</td>
                                                <td>₱15,000</td>
                                                <td>
                                                    <p>₱9,200</p>

                                                    <div class="parent-progress">
                                                        <div class="progress"></div>
                                                    </div>

                                                    <p>39% paid</p>
                                                </td>
                                                <td>June 15</td>
                                                <td>Active</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <!-- <div class="card-box-parent">

                            <div class="loan-application">
                                <div class="loan-head">
                                    <div>
                                        <h4>Loan Application</h4>
                                        <p>Track all your lending applications</p>
                                    </div>
                                    <div>
                                        <a href="{{ route('LoanStatus') }}">View all <i class="fa fa-arrow-right"></i></a>
                                    </div>
                                </div>

                                <div class="buttons-parent">
                                    <button class="tab-btn active" onclick="filterLoans('all')">
                                        <span>All</span>
                                        <p>{{ $loans->count() }}</p>
                                    </button>
                                    <button class="tab-btn" onclick="filterLoans('Approved')">
                                        <span>Approved</span>
                                        <p>{{ $loans->where('status','Approved')->count() }}</p>
                                    </button>
                                    <button class="tab-btn" onclick="filterLoans('Pending')">
                                        <span>Pending</span>
                                        <p>{{ $loans->where('status','Pending')->count() }}</p>
                                    </button>
                                    <button class="tab-btn" onclick="filterLoans('Rejected')">
                                        <span>Rejected</span>
                                        <p>{{ $loans->whereIn('status',['Rejected','Declined'])->count() }}</p>
                                    </button>
                                </div>

                                @if ($loans->isNotEmpty())
                                    <div class="parent-loan">
                                        @foreach ($loans as $loan)
                                            @php
                                                $lendingStatus   = \App\Models\lending_status_tbl::where('lending_id', $loan->id)->first();
                                                $loanAmount      = $loan->lending_amount ?? 0;
                                                $remainingBalance = $lendingStatus->remaining_balance ?? $loanAmount;
                                                $monthlyPayment  = $loan->monthly_payment ?? 0;
                                                $paid = ($loanAmount > 0 && $lendingStatus)
                                                    ? max(0, round(($lendingStatus->total_paid / $loan->total_payment) * 100))
                                                    : 0;
                                                $statusGroup = in_array($loan->status, ['Rejected','Declined']) ? 'Rejected' : $loan->status;
                                                $penaltyInfo = collect($penalizedLoans ?? [])->firstWhere('id', $loan->id);
                                                $daysLeft = ($lendingStatus && $lendingStatus->next_due_date)
                                                    ? (int) now()->startOfDay()->diffInDays(
                                                        \Carbon\Carbon::parse($lendingStatus->next_due_date)->startOfDay(), false)
                                                    : null;
                                            @endphp

                                            <div class="loan-box" data-status="{{ $statusGroup }}">
                                                <div class="box-head">
                                                    <div class="box-text">
                                                        <h5>{{ $loan->lending_type }}</h5>
                                                        <p>Applied on {{ \Carbon\Carbon::parse($loan->created_at)->format('F d, Y') }}</p>
                                                    </div>

                                                    @php
                                                        $statusColor = match($loan->status) {
                                                            'Approved'            => '#1a4a3a',
                                                            'Pending'             => '#e6a817',
                                                            'Rejected','Declined' => '#e03131',
                                                            default               => '#888',
                                                        };
                                                        $statusBg = match($loan->status) {
                                                            'Approved'            => '#e8f5e9',
                                                            'Pending'             => '#fff8e1',
                                                            'Rejected','Declined' => '#fef2f2',
                                                            default               => '#f5f5f5',
                                                        };
                                                    @endphp

                                                    <div style="display:flex; flex-direction:column; align-items:flex-end; gap:6px;">
                                                        @if($penaltyInfo)
                                                            <div class="box-icon" style="background:#fef2f2; border:1.5px solid #dc2626; border-radius:20px; padding:4px 12px; display:flex; align-items:center; gap:6px;">
                                                                <div class="dot" style="width:7px; height:7px; border-radius:50%; background:#dc2626; flex-shrink:0;"></div>
                                                                <span style="color:#dc2626; font-size:12px; font-weight:700;">Overdue</span>
                                                            </div>
                                                        @else
                                                            <div class="box-icon" style="background:{{ $statusBg }}; border:1.5px solid {{ $statusColor }}; border-radius:20px; padding:4px 12px; display:flex; align-items:center; gap:6px;">
                                                                <div class="dot" style="width:7px; height:7px; border-radius:50%; background:{{ $statusColor }}; flex-shrink:0;"></div>
                                                                <span style="color:{{ $statusColor }}; font-size:12px; font-weight:700;">{{ $loan->status }}</span>
                                                            </div>
                                                        @endif

                                                        @if($loan->status === 'Approved' && $lendingStatus && $lendingStatus->next_due_date)
                                                            <p style="margin:0; font-size:12px; color:#888;">
                                                                Next Due: <strong style="color:{{ $daysLeft === null ? '#1a4a3a' : ($daysLeft < 0 ? '#e03131' : ($daysLeft <= 7 ? '#e6a817' : '#1a4a3a')) }};">
                                                                    {{ \Carbon\Carbon::parse($lendingStatus->next_due_date)->format('M d, Y') }}
                                                                </strong>
                                                            </p>
                                                        @endif
                                                    </div>
                                                </div>

                                                <div class="box-body">
                                                    <div class="parent-box">
                                                        <div class="box amount">
                                                            <p>Loan Amount</p>
                                                            <h5>₱{{ number_format($loanAmount, 2) }}</h5>
                                                        </div>
                                                        <div class="box purpose">
                                                            <p>Purpose</p>
                                                            <h5>{{ $loan->purpose_loan }}</h5>
                                                        </div>
                                                        <div class="box monthly-payment">
                                                            <p>Monthly Payment</p>
                                                            <h5>₱{{ number_format($monthlyPayment, 2) }}</h5>
                                                        </div>
                                                        <div class="box remaining-balance">
                                                            <p>Remaining Balance</p>
                                                            <h5>₱{{ number_format($remainingBalance, 2) }}</h5>
                                                        </div>
                                                    </div>

                                                    @if($penaltyInfo)
                                                        <div class="parent-box" style="margin-top:10px;">
                                                            <div class="box remaining-balance" style="border:1px solid #dc2626; background:#fef2f2;">
                                                                <p style="color:#dc2626;">Late Fee ({{ $penaltyInfo['months_overdue'] }} month(s) overdue)</p>
                                                                <h5 style="color:#dc2626;">₱{{ number_format($penaltyInfo['late_fee'], 2) }}</h5>
                                                            </div>
                                                        </div>
                                                    @endif

                                                    <div class="parent-progress">
                                                        <div class="progress-head">
                                                            <p>Repayment Progress</p>
                                                            <span>{{ $paid }}% Paid</span>
                                                        </div>
                                                        <div class="progress-body">
                                                            <div class="progress" style="width:{{ max(0, $paid) }}%"></div>
                                                        </div>
                                                        @if($loan->status === 'Approved' && $lendingStatus)
                                                            <div style="display:flex; justify-content:space-between; margin-top:4px; font-size:11.5px; color:#999;">
                                                                <span>{{ $lendingStatus->payments_made }} of {{ $lendingStatus->total_payments }} payments made</span>
                                                                <span>₱{{ number_format($lendingStatus->total_paid, 2) }} paid</span>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>

                                                <div class="box-footer">
                                                    <div class="box-approved">
                                                        @if($loan->status === 'Approved')
                                                            <i class="fa fa-check" style="color:#1a4a3a;"></i>
                                                            <p style="color:#1a4a3a; font-weight:600; margin:0;">Approved on {{ \Carbon\Carbon::parse($loan->updated_at)->format('F d, Y') }}</p>
                                                        @elseif($loan->status === 'Pending')
                                                            <i class="fa fa-hourglass" style="color:#e6a817;"></i>
                                                            <p style="color:#e6a817; font-weight:600; margin:0;">Awaiting admin review</p>
                                                        @elseif($loan->status === 'Rejected')
                                                            <i class="fa fa-xmark" style="color:#e03131;"></i>
                                                            <p style="color:#e03131; font-weight:600; margin:0;">Rejected on {{ \Carbon\Carbon::parse($loan->updated_at)->format('F d, Y') }}</p>
                                                        @endif
                                                    </div>
                                                    <div class="box-link">
                                                        @if($loan->status === 'Rejected' || $loan->status === 'Declined')
                                                            <a href="#">View Reason</a>
                                                            <a href="{{ route('LoanApplication') }}">Re-apply <i class="fa fa-arrow-right"></i></a>
                                                        @elseif($loan->status === 'Pending')
                                                        @else
                                                            <a href="#">View Loan <i class="fa fa-arrow-right"></i></a>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="parent-loan-empty">
                                        <i class="fa fa-clipboard-list"></i>
                                        <h3>No Loan Transaction yet</h3>
                                    </div>
                                @endif
                            </div>

                            {{-- Right Sidebar --}}
                            <div class="right-main">
                                <div class="member-status">

                                    {{-- Member Account --}}
                                    <div class="member-account">
                                        <div class="member-head">
                                            <div class="icon">
                                                <p>{{ strtoupper(substr($firstName ?: ($username ?? 'U'), 0, 1)) }}</p>
                                            </div>
                                            <div class="text">
                                                <h4>
                                                    {{ $firstName ?: $username }}
                                                    {{ $middleName ? strtoupper(substr($middleName, 0, 1)) . '. ' : '' }}
                                                    {{ $lastName }}
                                                </h4>
                                                <p>Member ID: {{ $member->member_id ?? 'N/A' }}</p>
                                            </div>
                                        </div>
                                        <div class="member-body">
                                            <div class="body-text">
                                                <p>Membership:</p>
                                                @if($member->approval_status === 'Pending')
                                                    <span style="color:var(--gold)">{{ $member->approval_status ?? 'N/A' }}</span>
                                                @elseif($member->approval_status === 'Declined')
                                                    <span style="color:#DC2626">{{ $member->approval_status ?? 'N/A' }}</span>
                                                @else
                                                    <span style="color:#1a4a3a; font-weight:600;">{{ $member->approval_status ?? 'N/A' }}</span>
                                                @endif
                                            </div>
                                            <div class="body-text">
                                                <p>Member Since:</p>
                                                <span>{{ $member ? \Carbon\Carbon::parse($member->created_at)->format('F Y') : 'N/A' }}</span>
                                            </div>
                                            <div class="body-text">
                                                <p>Status:</p>
                                                <div class="membership_status">
                                                    @if($member->membership_status === 'Unofficial')
                                                        <div class="dot" style="width:8px; height:8px; background-color:var(--gold); border-radius:50%"></div>
                                                        <span style="color:var(--gold)">{{ $member->membership_status ?? 'N/A' }}</span>
                                                    @elseif($member->membership_status === 'Not Active')
                                                        <div class="dot" style="width:8px; height:8px; background-color:#DC2626; border-radius:50%"></div>
                                                        <span style="color:#DC2626">{{ $member->membership_status ?? 'N/A' }}</span>
                                                    @else
                                                        <div class="dot" style="width:8px; height:8px; background-color:#1a4a3a; border-radius:50%"></div>
                                                        <span style="color:#1a4a3a; font-weight:600;">{{ $member->membership_status ?? 'N/A' }}</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Share Capital --}}
                                    <div class="share-capital">
                                        <div class="share-head">
                                            <h4>Share Capital</h4>
                                            <p>Your equity summary</p>
                                        </div>
                                        <div class="share-body">
                                            <div class="body-text">
                                                <p>Total Balance:</p>
                                                <span>₱{{ number_format($shareCapitalBalance, 2) }}</span>
                                            </div>
                                            <div class="body-text">
                                                <p>Total Shares:</p>
                                                <span>{{ $shareCapitalShares }} shares</span>
                                            </div>
                                            <div class="body-text">
                                                <p>Dividend Rate:</p>
                                                <span>{{ $dividendRate }}% p.a</span>
                                            </div>
                                            <div class="body-text">
                                                <p>Next Dividend:</p>
                                                <span>{{ $nextDividendDate->format('F d, Y') }}</span>
                                            </div>
                                            <div class="body-footer">
                                                <a href="{{ route('ShareCapitalMember') }}">
                                                    <i class="fa fa-coins"></i> Manage Share Capital
                                                </a>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Savings Account --}}
                                    <div class="savings-account">
                                        <div class="savings-head">
                                            <h4>Savings Account</h4>
                                            <p>Regular savings</p>
                                        </div>
                                        <div class="savings-body">
                                            <div class="body-text">
                                                <p>Balance:</p>
                                                <span>₱{{ number_format($savingsAccount->balance ?? 0, 2) }}</span>
                                            </div>
                                            <div class="body-text">
                                                <p>Interest Rate:</p>
                                                <span>2.5% p.a.</span>
                                            </div>
                                            <div class="body-text">
                                                <p>Last Deposit:</p>
                                                <span>
                                                    @php
                                                        $lastDeposit = \App\Models\savings_transaction_tbl::where('savings_account_id', $savingsAccount->id ?? 0)
                                                            ->where('type', 'deposit')
                                                            ->orderByDesc('transaction_date')
                                                            ->value('transaction_date');
                                                    @endphp
                                                    {{ $lastDeposit ? \Carbon\Carbon::parse($lastDeposit)->format('F d, Y') : 'No deposits yet' }}
                                                </span>
                                            </div>
                                            <div class="body-text">
                                                <p>Account Status:</p>
                                                <span>{{ ucfirst($savingsAccount->status ?? 'Active') }}</span>
                                            </div>
                                            <div class="body-footer">
                                                <a href="{{ route('savings.index') }}">
                                                    <i class="fa fa-plus"></i>
                                                    <span>Deposit Savings</span>
                                                </a>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>

                        </div>  -->
                    </section>
                </div>
            </div>
        </div>{{-- end #page-content --}}
    </div>{{-- end .container-fluid --}}


    {{-- Toast --}}
    @if (session("message"))
        <div class="toast-message">
            <i class="fa fa-check-circle"></i>
            <div>
                <p>{{ session("message") }}</p>
            </div>
        </div>
        <script>
            setTimeout(() => {
                const msg = document.querySelector(".toast-message");
                if (msg) {
                    msg.classList.add("hide");
                    msg.addEventListener("animationend", () => msg.remove());
                }
            }, 3000);
        </script>
    @endif


    {{-- ─── Skeleton dismiss logic — only runs after login ─── --}}
    @if (session('just_logged_in'))
        <script>
            (function () {
                var MIN_DISPLAY = 2000; // ms to show skeleton after login
                var startTime = Date.now();
                var pageLoaded = false;
                var dismissed = false;

                function dismissSkeleton() {
                    if (dismissed) return;
                    dismissed = true;

                    var overlay = document.getElementById('skeleton-overlay');
                    var content = document.getElementById('page-content');

                    if (overlay) {
                        overlay.classList.add('sk-hide');
                        overlay.addEventListener('transitionend', function () {
                            overlay.remove();
                        }, { once: true });
                    }

                    if (content) {
                        content.classList.add('sk-ready');
                    }
                }

                function tryDismiss() {
                    if (!pageLoaded) return;
                    var elapsed = Date.now() - startTime;
                    var remaining = MIN_DISPLAY - elapsed;
                    if (remaining <= 0) {
                        dismissSkeleton();
                    } else {
                        setTimeout(dismissSkeleton, remaining);
                    }
                }

                if (document.readyState === 'complete') {
                    pageLoaded = true;
                    tryDismiss();
                } else {
                    window.addEventListener('load', function () {
                        pageLoaded = true;
                        tryDismiss();
                    });
                }

                // Hard fallback — never stay stuck beyond 6s
                setTimeout(dismissSkeleton, 6000);
            })();
        </script>
    @endif


    <script>
        function filterLoans(status) {
            document.querySelectorAll('.tab-btn').forEach(btn => btn.classList.remove('active'));
            event.currentTarget.classList.add('active');
            document.querySelectorAll('.loan-box').forEach(box => {
                box.style.display = (status === 'all' || box.dataset.status === status) ? 'block' : 'none';
            });
        }
    </script>

    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>

</body>

</html>