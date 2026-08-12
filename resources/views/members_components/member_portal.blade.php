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
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

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

        #skeleton-overlay.sk-hide {
            opacity: 0;
            pointer-events: none;
        }

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
                <div>
                    <div style="display:flex; gap:8px; margin-bottom:14px;">
                        <div class="sk sk-pill" style="width:64px; height:34px;"></div>
                        <div class="sk sk-pill" style="width:84px; height:34px;"></div>
                        <div class="sk sk-pill" style="width:74px; height:34px;"></div>
                        <div class="sk sk-pill" style="width:78px; height:34px;"></div>
                    </div>
                    <div class="sk sk-card" style="height:170px; margin-bottom:14px;"></div>
                    <div class="sk sk-card" style="height:170px;"></div>
                </div>
                <div style="display:flex; flex-direction:column; gap:14px;">
                    <div class="sk sk-card" style="height:148px;"></div>
                    <div class="sk sk-card" style="height:168px;"></div>
                    <div class="sk sk-card" style="height:148px;"></div>
                </div>
            </div>

        </div>
    @endif


    {{-- Sidebar always visible — outside the fading wrapper --}}
    <div class="container-fluid p-0 m-0">
        @include("components.offcanvas")
        @include("components.sidebar")

        <div id="page-content" @if(session('just_logged_in')) style="opacity:0;" @endif>
            <div class="rightbar">
                @include("components.navbar2")

                <div class="main-parent">
                    <main>

                        <h2>Good day, {{ $username }}! <span>Here's your overview</span></h2>

                        @if ($username)
                            <div class="main-header">
                                <div class="main-intro">
                                    <!-- <div class="main-left">
                                        <div class="left-icon">

                                        </div>
                                        <div class="left-text">
                                            <span>Member Cooperative Assistant</span>
                                            <p>Your money are growing steadily. Every peso you save today builds a stronger
                                            tomorrow for you and the community.</p>
                                        </div>
                                    </div>
                                    <div class="main-right">

                                    </div> -->
                                    <div class="main-intro-icon"></div>
                                    <div class="main-intro-text">
                                        <span>Member Cooperative Assistant</span>
                                        <p>Your money are growing steadily. Every peso you save today builds a stronger
                                            tomorrow for you.</p>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <div class="card-parent">

                            {{-- Savings Balance --}}
                            <div class="card-box" onclick="window.location='{{ route('savings.index') }}'">
                                <div class="card-header">
                                    <p>Savings Balance</p>
                                    <div class="update"><i class="fa fa-layer-group"></i></div>
                                </div>
                                <div class="card-body">
                                    <h5>₱ {{ number_format($savingsAccount->balance ?? 0, 2) }}</h5>
                                    <p>{{ $netSavingsThisMonth >= 0 ? '↑ +' : '↓ -' }}₱{{ number_format(abs($netSavingsThisMonth), 2) }} this month</p>
                                </div>
                            </div>

                            {{-- Active Loans --}}
                            <div class="card-box" onclick="window.location='{{ route('LoanStatus') }}'">
                                <div class="card-header">
                                    <p>Active Loans</p>
                                    <div class="update"><i class="fa fa-piggy-bank"></i></div>
                                </div>
                                <div class="card-body">
                                    <h5>{{ $activeLoansCount }} Loan(s)</h5>
                                    <p>{{ $nextDueDisplay ? "Next due {$nextDueDisplay}" : 'No upcoming dues' }}</p>
                                </div>
                            </div>

                            {{-- Overdue Loans --}}
                            <div class="card-box" onclick="window.location='{{ route('LoanStatus') }}'">
                                <div class="card-header">
                                    <p>Overdue Loans</p>
                                    <div class="update"><i class="fa fa-triangle-exclamation"></i></div>
                                </div>
                                <div class="card-body">
                                    <h5>{{ $overdueCount }} Loan(s)</h5>
                                    <p>{{ $earliestOverdueDisplay ? "⚠ Due {$earliestOverdueDisplay}" : 'No overdue' }}</p>
                                </div>
                            </div>

                            <div class="card-box" onclick="document.getElementById('netStandingModal').style.display='flex'">
                                <div class="card-header">
                                    <p>Net Standing</p>
                                    <div class="update"><i class="fa fa-wallet"></i></div>
                                </div>
                                <div class="card-body">
                                    <h5>₱{{ number_format($netStandingTotal, 2) }}</h5>
                                    <p>Overall financial position</p>
                                </div>
                            </div>

                        </div>
                    </main>

                    <!-- <div class="ask-box">
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

                    <h3>Quick Summary</h3> -->

                    <section>
                        <div class="parent-panel panel-1">
                            <div class="panel graph" style="width:100%;">
                                <div class="panel-head"
                                    style="display:flex; justify-content:space-between; align-items:flex-start; gap:10px;">
                                    <div>
                                        <h3>Savings Growth</h3>
                                        <p>Net deposits over the last 6 months</p>
                                    </div>
                                    <div class="year-filter">
                                        <select onchange="window.location='{{ url()->current() }}?year='+this.value">
                                            @foreach ($availableYears as $year)
                                                <option value="{{ $year }}" {{ $year == $selectedYear ? 'selected' : '' }}>
                                                    {{ $year }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="panel-body">
                                    <div class="chart-wrap">
                                        @foreach ($savingsGrowth as $month)
                                            <div class="bar-col {{ $month['is_current'] ? 'active' : '' }}">
                                                <div class="bar" style="height:{{ $month['height_percent'] }}%">
                                                    <div class="bar-tooltip">
                                                        <div class="bar-tooltip-title">{{ $month['label'] }}</div>
                                                        <div class="bar-tooltip-row">
                                                            <span
                                                                class="bar-tooltip-dot {{ $month['is_current'] ? 'dot-gold' : 'dot-blue' }}"></span>
                                                            <span class="bar-tooltip-label">Net Savings:</span>
                                                            <span class="bar-tooltip-value">
                                                                {{ $month['net'] >= 0 ? '₱' : '-₱' }}{{ number_format(abs($month['net']), 2) }}
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <span class="bar-month">{{ $month['label'] }}</span>
                                            </div>
                                        @endforeach
                                    </div>
                                    <!-- <div class="chart-legend">
                                        <div class="legend-item"><span class="legend-dot"
                                                style="background:var(--blue);"></span>Prior months</div>
                                        <div class="legend-item"><span class="legend-dot"
                                                style="background:var(--gold);"></span>Current month</div>
                                    </div> -->
                                </div>
                            </div>

                            <div class="panel graph announcements">
                                <div class="panel-head"
                                style="display:flex; justify-content:space-between; align-items:flex-start; gap:10px;">
                                <div>
                                    <h3>Announcements</h3>
                                    <p>Latest updates from your cooperative</p>
                                </div>
                                <div class="balance-date">
                                    <select id="announcementMonthSelect" onchange="updateAnnouncementMonth()">
                                        @foreach (range(1, 12) as $m)
                                            <option value="{{ str_pad($m, 2, '0', STR_PAD_LEFT) }}"
                                                {{ (int) explode('-', $announcementMonth)[1] === $m ? 'selected' : '' }}>
                                                {{ \Carbon\Carbon::create()->month($m)->format('F') }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <select id="announcementYearSelect" onchange="updateAnnouncementMonth()">
                                        @foreach ($availableYears as $year)
                                            <option value="{{ $year }}"
                                                {{ (int) explode('-', $announcementMonth)[0] === $year ? 'selected' : '' }}>
                                                {{ $year }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            </div>
                            
                        </div>

                        <div class="parent-panel panel-2" style="margin-top:1.5rem;">
                            <div class="panel graph">
                                <div class="panel-head"
                                    style="display:flex; justify-content:space-between; align-items:flex-start; gap:10px;">
                                    <div>
                                        <h3>Account Balance</h3>
                                        <p>Share capital, savings &amp; loan balance overview</p>
                                    </div>
                                    <div class="balance-date">
                                        <select id="balanceMonthSelect" onchange="updateBalanceMonth()">
                                            @foreach (range(1, 12) as $m)
                                                <option value="{{ str_pad($m, 2, '0', STR_PAD_LEFT) }}"
                                                    {{ (int) explode('-', $balanceMonth)[1] === $m ? 'selected' : '' }}>
                                                    {{ \Carbon\Carbon::create()->month($m)->format('F') }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <select id="balanceYearSelect" onchange="updateBalanceMonth()">
                                            @foreach ($availableYears as $year)
                                                <option value="{{ $year }}"
                                                    {{ (int) explode('-', $balanceMonth)[0] === $year ? 'selected' : '' }}>
                                                    {{ $year }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="panel-body">
                                    <div class="pie-chart-wrap">
                                        <canvas id="accountBalancePie" width="220" height="220"></canvas>
                                    </div>
                                    <div class="chart-legend">
                                        <div class="legend-item"><span class="legend-dot"
                                                style="background:var(--gold);"></span>Share Capital</div>
                                        <div class="legend-item"><span class="legend-dot"
                                                style="background:var(--blue);"></span>Savings</div>
                                        <div class="legend-item"><span class="legend-dot"
                                                style="background:var(--coral);"></span>Loan Balance</div>
                                    </div>
                                </div>
                            </div>
                            <div class="panel graph">
                                <div class="panel-head"
                                    style="display:flex; justify-content:space-between; align-items:flex-start; gap:10px;">
                                    <div>
                                        <h3>Share Capital Growth</h3>
                                        <p>Net contributions over the last 6 months</p>
                                    </div>
                                    <div class="year-filter">
                                        <select onchange="window.location='{{ url()->current() }}?year='+this.value">
                                            @foreach ($availableYears as $year)
                                                <option value="{{ $year }}" {{ $year == $selectedYear ? 'selected' : '' }}>
                                                    {{ $year }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="panel-body">
                                    <div class="chart-wrap">
                                        @foreach ($shareCapitalGrowth as $month)
                                            <div class="bar-col {{ $month['is_current'] ? 'active' : '' }}">
                                                <div class="bar" style="height:{{ $month['height_percent'] }}%">
                                                    <div class="bar-tooltip">
                                                        <div class="bar-tooltip-title">{{ $month['label'] }}</div>
                                                        <div class="bar-tooltip-row">
                                                            <span
                                                                class="bar-tooltip-dot {{ $month['is_current'] ? 'dot-gold' : 'dot-blue' }}"></span>
                                                            <span class="bar-tooltip-label">Net Contribution:</span>
                                                            <span class="bar-tooltip-value">
                                                                {{ $month['net'] >= 0 ? '₱' : '-₱' }}{{ number_format(abs($month['net']), 2) }}
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <span class="bar-month">{{ $month['label'] }}</span>
                                            </div>
                                        @endforeach
                                    </div>
                                    <!-- <div class="chart-legend">
                                        <div class="legend-item"><span class="legend-dot"
                                                style="background:var(--blue);"></span>Prior months</div>
                                        <div class="legend-item"><span class="legend-dot"
                                                style="background:var(--gold);"></span>Current month</div>
                                    </div> -->
                                </div>
                            </div>
                        </div>

                        <div class="card-box-summary">

                            <div class="recent-transaction">
                                <div class="recent-header">
                                    <div>
                                        <h4>Recent Transactions</h4>
                                        <p>Latest account activity across all accounts</p>
                                    </div>
                                    <div>
                                        <a href="{{ route('transactions') }}">View all</a>
                                    </div>
                                </div>
                                <div class="recent-body">
                                    <div class="tx-list">
                                        @forelse ($recentTransactions as $tx)
                                            <div class="tx-list-item">
                                                <div class="tx-icon {{ $tx['icon'] }}"><i class="fa-solid {{ $tx['icon_fa'] }}"></i></div>
                                                <div class="tx-list-info">
                                                    <strong>{{ $tx['title'] }}</strong>
                                                    <span>{{ $tx['date_display'] }} · {{ $tx['time_display'] }}</span>
                                                </div>
                                                <div class="tx-list-amt {{ $tx['amount'] >= 0 ? 'up' : 'down' }}">
                                                    {{ $tx['amount'] >= 0 ? '+' : '-' }}₱{{ number_format(abs($tx['amount']), 2) }}
                                                </div>
                                            </div>
                                        @empty
                                            <div style="text-align:center; color:#aaa; padding:2rem; font-size:13px;">
                                                <i class="fa fa-inbox" style="font-size:24px; display:block; margin-bottom:8px;"></i>
                                                No transactions found.
                                            </div>
                                        @endforelse
                                    </div>
                                </div>
                            </div>

                            <div class="loan-overview">
                                <div class="loan-header">
                                    <div>
                                        <h4>Upcoming Dues</h4>
                                        <p>Your next loan payments across all accounts</p>
                                    </div>
                                    <div>
                                        <a href="{{ route('LoanStatus') }}">View all</a>
                                    </div>
                                </div>
                                <div class="recent-body">
                                    <div class="tx-list">
                                        @forelse ($upcomingDues as $due)
                                            <div class="tx-list-item">
                                                <div class="tx-icon {{ $due['icon'] }}"><i class="fa-solid {{ $due['icon_fa'] }}"></i></div>
                                                <div class="tx-list-info">
                                                    <strong>{{ $due['title'] }}</strong>
                                                    <span>{{ $due['date_display'] }} · {{ $due['subtitle'] }}</span>
                                                </div>
                                                <div class="tx-list-amt down">
                                                    ₱{{ number_format($due['amount'], 2) }}
                                                </div>
                                            </div>
                                        @empty
                                            <div style="text-align:center; color:#aaa; padding:2rem; font-size:13px;">
                                                <i class="fa fa-circle-check" style="font-size:24px; display:block; margin-bottom:8px;"></i>
                                                No upcoming dues.
                                            </div>
                                        @endforelse
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- ═══════════════════════════════════════════════
                        FINANCIAL TRENDS CHART
                        Copied from newCoop4.html sample dashboard
                        ═══════════════════════════════════════════════ --}}
                        <!-- <div class="share-graph">
                            <div class="share-parent">
                                <h2>Text</h2>
                            </div>

                        </div> -->
                        {{-- end Financial Trends Chart --}}

                        <!-- Resign from Cooperative -->
                        <!-- <div class="ask-box" style="margin-top: 2rem; border: 1px solid #fecaca; background: #fef2f2;">
                        <div class="text-box">
                            <h4 style="color: #dc2626;">Leave the Cooperative?</h4>
                            <p style="color: #1e293b;">If you wish to resign from the cooperative, you may submit a resignation request. A 60-day holding period applies for share capital withdrawal.</p>
                        </div>
                        <div class="link-box">
                            <button onclick="document.getElementById('resignModal').style.display='flex'" style="background: #dc2626; color: #fff; border: none; padding: 10px 20px; border-radius: 8px; cursor: pointer; font-weight: 600; display: flex; align-items: center; gap: 8px;">
                                <i class="fa fa-sign-out-alt"></i>
                                <span>Request Resignation</span>
                            </button>
                        </div>
                    </div> -->
                    

                        <!-- Resignation Modal -->
                        <div id="resignModal"
                            style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.6); z-index:99999; align-items:center; justify-content:center;"
                            onclick="if(event.target===this)this.style.display='none'">
                            <div
                                style="background:#fff; border-radius:12px; max-width:450px; width:90%; padding:0; box-shadow:0 25px 60px rgba(0,0,0,0.3);">
                                <div
                                    style="padding:20px 24px; border-bottom:1px solid #e5e7eb; display:flex; justify-content:space-between; align-items:center;">
                                    <h3 style="margin:0; font-size:18px; font-weight:700; color:#111827;">Request
                                        Resignation</h3>
                                    <button onclick="document.getElementById('resignModal').style.display='none'"
                                        style="background:none; border:none; font-size:24px; cursor:pointer; color:#6b7280;">&times;</button>
                                </div>
                                <form method="POST" action="{{ route('resignation.request') }}" style="padding:24px;">
                                    @csrf
                                    <p style="font-size:14px; color:#6b7280; margin-bottom:20px;">Please select your
                                        preference for your share capital:</p>
                                    <div style="display:flex; flex-direction:column; gap:12px; margin-bottom:24px;">
                                        <label class="resign-option"
                                            style="display:flex; align-items:center; gap:12px; padding:14px 16px; border:2px solid #e5e7eb; border-radius:10px; cursor:pointer; transition:all .2s;">
                                            <input type="radio" name="withdraw_share_capital" value="1"
                                                style="accent-color:#1E2A4A;" required
                                                onchange="document.querySelectorAll('.resign-option').forEach(l=>l.style.borderColor='#e5e7eb');this.closest('label').style.borderColor='#1E2A4A'">
                                            <div>
                                                <strong style="display:block; color:#111827; font-size:15px;">Withdraw
                                                    Share Capital</strong>
                                                <span style="font-size:13px; color:#6b7280;">I want my share capital
                                                    paid out after 60 days</span>
                                            </div>
                                        </label>
                                        <label class="resign-option"
                                            style="display:flex; align-items:center; gap:12px; padding:14px 16px; border:2px solid #e5e7eb; border-radius:10px; cursor:pointer; transition:all .2s;">
                                            <input type="radio" name="withdraw_share_capital" value="0"
                                                style="accent-color:#1E2A4A;" required
                                                onchange="document.querySelectorAll('.resign-option').forEach(l=>l.style.borderColor='#e5e7eb');this.closest('label').style.borderColor='#1E2A4A'">
                                            <div>
                                                <strong style="display:block; color:#111827; font-size:15px;">Leave
                                                    Share Capital</strong>
                                                <span style="font-size:13px; color:#6b7280;">I leave my share capital
                                                    with the cooperative</span>
                                            </div>
                                        </label>
                                    </div>
                                    <div style="display:flex; gap:12px;">
                                        <button type="button"
                                            onclick="document.getElementById('resignModal').style.display='none'"
                                            style="flex:1; padding:12px; background:#f3f4f6; color:#374151; border:none; border-radius:8px; cursor:pointer; font-weight:600;">Cancel</button>
                                        <button type="submit"
                                            style="flex:1; padding:12px; background:#dc2626; color:#fff; border:none; border-radius:8px; cursor:pointer; font-weight:600;"
                                            onclick="return confirm('Are you sure you want to submit a resignation request? This action will be reviewed by admin.')">Submit
                                            Request</button>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <!-- Net Standing Modal -->
                        <div id="netStandingModal"
                            style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.6); z-index:99999; align-items:center; justify-content:center;"
                            onclick="if(event.target===this)this.style.display='none'">
                            <div
                                style="background:#fff; border-radius:16px; max-width:420px; width:90%; padding:0; box-shadow:0 25px 60px rgba(0,0,0,0.3);">
                                <div
                                    style="padding:20px 24px; border-bottom:1px solid #e5e7eb; display:flex; justify-content:space-between; align-items:center;">
                                    <div>
                                        <h3 style="margin:0; font-size:15px; font-weight:700; color:#111827;">Net Standing</h3>
                                        <p style="margin: 3.2px 0 0; font-size: 13.5px;color: var(--muted);">Overall Financial Position</p>
                                    </div>
                                    <button onclick="document.getElementById('netStandingModal').style.display='none'"
                                        style="background:none; border:none; font-size:24px; cursor:pointer; color:#6b7280;">&times;</button>
                                </div>

                                <div style="padding:24px;">

                                    <div style="display:flex; gap:8px; margin-bottom:15px;">
                                        <select id="standingMonthSelect" class="form-select" onchange="updateStandingMonth()" style="flex:1; padding:8px 12px; border:1px solid #e5e7eb; border-radius:10px; font-size:13px; font-weight:600; color:#111827;">
                                            @foreach (range(1, 12) as $m)
                                                <option value="{{ str_pad($m, 2, '0', STR_PAD_LEFT) }}"
                                                    {{ (int) explode('-', $standingMonth)[1] === $m ? 'selected' : '' }}>
                                                    {{ \Carbon\Carbon::create()->month($m)->format('F') }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <select id="standingYearSelect" class="form-select" onchange="updateStandingMonth()" style="flex:1; padding:8px 12px; border:1px solid #e5e7eb; border-radius:10px; font-size:13px; font-weight:600; color:#111827;">
                                            @foreach ($availableYears as $year)
                                                <option value="{{ $year }}"
                                                    {{ (int) explode('-', $standingMonth)[0] === $year ? 'selected' : '' }}>
                                                    {{ $year }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div style="text-align:center; padding:16px 0 20px; border-bottom:1px dashed var(--border); margin-bottom:16px;">
                                        <p style="margin:0; font-size:12.5px; color:#808080; font-weight:600; text-transform:uppercase; letter-spacing:.5px;">Net Standing</p>
                                        <h2 style="margin:6px 0 0; font-size:32px; font-weight:800; color:{{ $netStandingAsOf >= 0 ? 'var(--teal)' : '#DC2626' }};">
                                            ₱{{ number_format($netStandingAsOf, 2) }}
                                        </h2>
                                    </div>

                                    <div style="display:flex; flex-direction:column; gap:12px;">
                                        <div style="display:flex; justify-content:space-between; align-items:center; font-size:14px;">
                                            <span style="display:flex; align-items:center; gap:8px; color:var(--muted); font-weight: 600;">
                                                <span style="width:10px; height:10px; border-radius:50%; background:var(--gold);"></span>
                                                Share Capital
                                            </span>
                                            <strong style="color:#1a1a1a;">₱{{ number_format($shareCapitalStandingAsOf, 2) }}</strong>
                                        </div>
                                        <div style="display:flex; justify-content:space-between; align-items:center; font-size:14px;">
                                            <span style="display:flex; align-items:center; gap:8px; color:var(--muted); font-weight: 600;">
                                                <span style="width:10px; height:10px; border-radius:50%; background:var(--blue);"></span>
                                                Savings
                                            </span>
                                            <strong style="color:#1a1a1a;">+ ₱{{ number_format($savingsStandingAsOf, 2) }}</strong>
                                        </div>
                                        <div style="display:flex; justify-content:space-between; align-items:center; font-size:14px; padding-bottom:12px; border-bottom:1px solid #f0f0f0;">
                                            <span style="display:flex; align-items:center; gap:8px; color:var(--muted); font-weight: 600;">
                                                <span style="width:10px; height:10px; border-radius:50%; background:var(--coral);"></span>
                                                Loan Balance
                                            </span>
                                            <strong style="color:#DC2626;">− ₱{{ number_format($loanStandingAsOf, 2) }}</strong>
                                        </div>
                                        <div style="display:flex; justify-content:space-between; align-items:center; font-size:14.5px;">
                                            <span style="font-weight:700; color:#1a1a1a;">Total</span>
                                            <strong style="color:{{ $netStandingAsOf >= 0 ? 'var(--teal)' : '#DC2626' }};">₱{{ number_format($netStandingAsOf, 2) }}</strong>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </section>
                </div>
            </div>
        </div>{{-- end #page-content --}}
    </div>{{-- end .container-fluid --}}

    <script>
        function updateBalanceMonth() {
            var month = document.getElementById('balanceMonthSelect').value;
            var year = document.getElementById('balanceYearSelect').value;
            window.location = '{{ url()->current() }}?balance_month=' + year + '-' + month;
        }

        function updateAnnouncementMonth() {
            var month = document.getElementById('announcementMonthSelect').value;
            var year = document.getElementById('announcementYearSelect').value;
            var url = new URL(window.location.href);
            url.searchParams.set('announcement_month', year + '-' + month);
            window.location = url.toString();
        }

        function updateStandingMonth() {
            var month = document.getElementById('standingMonthSelect').value;
            var year = document.getElementById('standingYearSelect').value;
            var url = new URL(window.location.href);
            url.searchParams.set('standing_month', year + '-' + month);
            url.searchParams.set('open_standing_modal', '1');
            window.location = url.toString();
        }
    </script>

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

    @if (request('open_standing_modal'))
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                document.getElementById('netStandingModal').style.display = 'flex';
            });
        </script>
    @endif


    {{-- ─── Skeleton dismiss logic — only runs after login ─── --}}
    @if (session('just_logged_in'))
        <script>
            (function () {
                var MIN_DISPLAY = 2000;
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

                setTimeout(dismissSkeleton, 6000);
            })();
        </script>
    @endif

    <script>
        (function () {
            const style = getComputedStyle(document.documentElement);
            const colors = {
                gold: style.getPropertyValue('--gold').trim() || '#C9A84C',
                blue: style.getPropertyValue('--blue').trim() || '#5B8DEF',
                coral: style.getPropertyValue('--coral').trim() || '#FF8A75',
            };

            const ctx = document.getElementById('accountBalancePie');
            if (ctx) {
                new Chart(ctx, {
                    type: 'pie',
                    data: {
                        labels: ['Share Capital', 'Savings', 'Loan Balance'],
                        datasets: [{
                            data: [
                            {{ $accountBalanceChart[0]['value'] ?? 0 }},
                            {{ $accountBalanceChart[1]['value'] ?? 0 }},
                            {{ $accountBalanceChart[2]['value'] ?? 0 }},
                            ],
                            backgroundColor: [colors.gold, colors.blue, colors.coral],
                            borderWidth: 0,
                        }],
                    },
                    options: {
                        plugins: { legend: { display: false } },
                        responsive: false,
                    },
                });
            }
        })();
    </script>


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