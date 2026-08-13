<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Savings</title>
    <link rel="icon" href="images/websitelogo.png" type="image/png">

    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

    <link rel="stylesheet" href="css_folder/savings.css">
    <link rel="stylesheet" href="css_folder/savings_modal.css">
    <link rel="stylesheet" href="css_folder/loading.css">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link rel="stylesheet" href="font-awesome-icon/css/all.min.css">

    <style>
        .sm-ref-pill {
            display: flex;
            align-items: center;
            justify-content: space-between;
            background-color: var(--lavender-tint);
            border: 1px dashed var(--border);
            border-radius: 10px;
            padding: 0.65rem 1rem;
            margin: 0.75rem 0;
            gap: 0.5rem;
        }

        .sm-ref-label {
            font-size: 0.75rem;
            color: #000000;
            font-weight: 500;
            white-space: nowrap;
        }

        .sm-ref-value {
            font-family: monospace;
            font-size: 0.88rem;
            font-weight: 700;
            color: #1a1a1a;
            letter-spacing: 0.05em;
            word-break: break-all;
            text-align: right;
        }

        .sm-copy-btn {
            background: none;
            border: none;
            cursor: pointer;
            color: #6B7B74;
            font-size: 0.85rem;
            padding: 2px 6px;
            border-radius: 4px;
            transition: color 0.2s;
            flex-shrink: 0;
        }

        .sm-copy-btn:hover {
            color: #1a1a1a;
        }

        .sm-btn-download {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            width: 100%;
            padding: 0.6rem;
            border-radius: 10px;
            background: transparent;
            color: var(--teal);
            border: 1.5px solid var(--teal);
            font-family: inherit;
            font-size: 0.87rem;
            font-weight: 500;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.2s;
            margin-top: 0.5rem;
        }

        .sm-btn-download:hover {
            background: #1a1a1a;
            color: #fff;
        }

        .tx-ref {
            color: var(--muted);
            font-weight: 500;
        }
    </style>
</head>

<body>

    <div class="container-fluid p-0 m-0">

        @include("components.offcanvas")
        @include("components.sidebar")

        <div class="rightbar">
            @include("components.navbar2")
            <div class="main-sub-parent">
                <div class="main-parent">
                    <div class="main-header">
                        <h3>Savings Overview</h3>
                        <p>Last updated {{ $lastUpdated }} ·
                            {{ $monthsActive == 0 ? 'Less than a month' : $monthsActive . ' ' . ($monthsActive == 1 ? 'month' : 'months') }}
                            active
                        </p>
                    </div>

                    {{-- ══ GATED WRAPPER — blurs & locks all savings stats when Share Capital isn't met ══ --}}
                    <main>
                        <div class="card-box-parent">
                            <div class="card-box-text">
                                <h3>My Savings Balance</h3>
                                <h2>₱ <b>{{ number_format($totalSavingsBalance, 2) }}</b></h2>
                                <div class="hero-sub">
                                    <!-- Includes Time Deposit + 
                                            <span class="delta">
                                                <i class="fa fa-arrow-up"></i>
                                                ₱{{ number_format($timeDepositBalance, 2) }}
                                            </span> -->
                                    Last updated {{ $lastUpdated }} ·
                                    {{ $monthsActive == 0 ? 'Less than a month' : $monthsActive . ' ' . ($monthsActive == 1 ? 'month' : 'months') }}
                                    active
                                </div>
                            </div>
                            <div class="{{ !$hasShareCapital ? 'gated' : '' }}">
                                <div class="card-box-buttons">

                                    {{-- Deposit --}}
                                    @if($hasShareCapital)
                                        <div class="card-box" data-bs-toggle="modal" data-bs-target="#depositModal"
                                            style="cursor:pointer;">
                                            <div class="card-icon">
                                                <img src="{{ asset('images/arrow-icon.png') }}" alt="">
                                            </div>
                                            <div>
                                                <p>Deposit</p>
                                            </div>
                                        </div>
                                    @else
                                        <div class="card-box card-box-disabled"
                                            title="You must have active share capital to use savings.">
                                            <div class="card-icon">
                                                <img src="{{ asset('images/arrow-icon.png') }}" alt="">
                                            </div>
                                            <div>
                                                <p>Deposit</p>
                                            </div>
                                        </div>
                                    @endif

                                    {{-- Withdraw --}}
                                    @if($hasShareCapital)
                                        <div class="card-box" data-bs-toggle="modal" data-bs-target="#withdrawModal"
                                            style="cursor:pointer;">
                                            <div class="card-icon">
                                                <img src="{{ asset('images/arrow-icon.png') }}" alt="">
                                            </div>
                                            <div>
                                                <p>Withdraw</p>
                                            </div>
                                        </div>
                                    @else
                                        <div class="card-box card-box-disabled"
                                            title="You must have active share capital to use savings.">
                                            <div class="card-icon">
                                                <img src="{{ asset('images/arrow-icon.png') }}" alt="">
                                            </div>
                                            <div>
                                                <p>Withdraw</p>
                                            </div>
                                        </div>
                                    @endif

                                    {{-- ★ MOVED: "Open TD" now lives on the Time Deposit page --}}
                                    <!-- <a href="{{ route('TimeDeposit') }}" style="text-decoration:none;">
                                                <div class="card-box" style="cursor:pointer;">
                                                    <div class="card-icon">
                                                        <i class="fa-solid fa-lock"></i>
                                                    </div>
                                                    <div>
                                                        <p>Open TD</p>
                                                    </div>
                                                </div>
                                            </a> -->

                                </div>
                            </div>
                        </div>

                        <!-- @if(!$hasShareCapital)
                                    <div class="gate-shield">
                                        <div class="gate-lock"><i class="fa-solid fa-lock"></i></div>
                                        <div class="gate-msg">Savings stats are locked</div>
                                        <div class="gate-sub">
                                            Please <a href="{{ route('ShareCapitalMember') }}">subscribe to Share Capital</a>
                                            first to unlock your savings stats.
                                        </div>
                                    </div>
                                @endif -->
                    </main>

                    {{-- ══ STATS CARDS — its own gated/hover-lock block ══ --}}
                    <div class="{{ !$hasShareCapital ? 'gated' : '' }}">
                        <section id="section1">
                            <div class="main-card-box">
                                <div class="card-box tw:bg-white">
                                    <div class="card-header-icon">
                                        <p>Interest Accrued</p>
                                        <div class="card-icon d-flex justify-content-center align-items-center">
                                            <i class="fa-solid fa-percent"></i>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <h4>₱ {{ number_format($estimatedQuarterInterest, 2) }}</h4>
                                        <span>{{ number_format($regularSavingsRate, 2) }}% p.a. · credited
                                            {{ $regularSavingsFrequency }}</span>
                                    </div>
                                </div>

                                <div class="card-box tw:bg-white">
                                    <div class="card-header-icon">
                                        <p>Monthly Average</p>
                                        <div class="card-icon d-flex justify-content-center align-items-center">
                                            <i class="fa-solid fa-arrow-trend-up"></i>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <h4>₱ {{ number_format($monthlyAverage, 2) }}</h4>
                                        <span>Per month average</span>
                                    </div>
                                </div>

                                <div class="card-box tw:bg-white">
                                    <div class="card-header-icon">
                                        <p>Total Months</p>
                                        <div class="card-icon d-flex justify-content-center align-items-center">
                                            <i class="fa-solid fa-calendar-days"></i>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <h4>{{ $totalMonths }} Months</h4>
                                        <span>Months saving</span>
                                    </div>
                                </div>
                            </div>
                        </section>

                        @if(!$hasShareCapital)
                            <div class="gate-shield">
                                <div class="gate-lock"><i class="fa-solid fa-lock"></i></div>
                                <div class="gate-msg">Savings stats are locked</div>
                                <div class="gate-sub">
                                    Please <a href="{{ route('ShareCapitalMember') }}">subscribe to Share Capital</a>
                                    first to unlock your savings stats.
                                </div>
                            </div>
                        @endif
                    </div>


                    <div class="ask-box">
                        <div class="ask-body">
                            <div class="ask-card">
                                <div class="ask-card-text">
                                    <h3>Secure your savings with Time Deposit</h3>
                                    <p>Grow your money with higher returns and guaranteed earnings over a fixed term.
                                    </p>
                                </div>
                                <div class="{{ !$hasShareCapital ? 'gated' : '' }}">
                                    <div
                                        class="ask-card-button {{ request()->routeIs('TimeDeposit') ? 'active' : '' }}">
                                        <a href="{{ route("TimeDeposit") }}">

                                            Time Deposit
                                            <i class="fa fa-arrow-right"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- ══ BREAKDOWN + GROWTH GRAPH — its own gated/hover-lock block ══ --}}
                    <div class="{{ !$hasShareCapital ? 'gated' : '' }}">
                        <div class="parent-panel">
                            <div class="panel">
                                <div class="panel-head">
                                    <div class="panel-text">
                                        <h3>Time Deposit Accounts</h3>
                                        <p>Time Deposit history</p>
                                    </div>
                                    <div class="panel-view">
                                        <button type="button" data-bs-toggle="modal" data-bs-target="#tdHistoryModal">
                                            View all
                                        </button>
                                    </div>
                                </div>
                                <div class="panel-body">
                                    @forelse ($tdHistory as $td)
                                        <div class="panel-card">
                                            <div class="panel-icon">
                                                @if ($td->display_status === 'completed')
                                                    <i class="fa fa-circle-check"></i>
                                                @elseif ($td->display_status === 'matured')
                                                    <i class="fa fa-hourglass-end"></i>
                                                @elseif ($td->display_status === 'goal_reached')
                                                    <i class="fa fa-bullseye"></i>
                                                @else
                                                    <i class="fa fa-lock"></i>
                                                @endif
                                            </div>
                                            <div class="panel-text">
                                                <div class="text">
                                                    <h4>₱{{ number_format($td->goal_amount, 2) }} Goal</h4>
                                                    <p>
                                                        Opened {{ \Carbon\Carbon::parse($td->opened_at)->format('M d, Y') }}
                                                        · {{ number_format($td->interest_rate, 2) }}% p.a.
                                                    </p>
                                                </div>
                                                <div class="price">
                                                    <h4>₱{{ number_format($td->display_balance, 2) }}</h4>
                                                    @if ($td->display_status === 'completed')
                                                        <p style="color:var(--green);font-weight:700;">Completed</p>
                                                    @elseif ($td->display_status === 'matured')
                                                        <p style="color:var(--green);font-weight:700;">Ready to Claim</p>
                                                    @elseif ($td->display_status === 'goal_reached')
                                                        <p style="color:var(--blue, #1e56a0);font-weight:700;">Fully Funded</p>
                                                    @else
                                                        <p style="color:#AB7817;font-weight:700;">In Progress</p>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    @empty
                                        <div style="text-align:center; padding:2.5rem 1rem;">
                                            <i class="fa-solid fa-piggy-bank fa-2x"
                                                style="color:var(--muted); opacity:.4;"></i>
                                            <p style="color:var(--muted); margin-top:0.75rem; font-size:13.5px;">
                                                No Time Deposits opened yet.
                                            </p>
                                        </div>
                                    @endforelse
                                </div>
                            </div>

                            <div class="panel graph">
                                <div class="panel-head"
                                    style="display:flex; justify-content:space-between; align-items:flex-start; gap:10px;">
                                    <div>
                                        <h3>Savings Growth</h3>
                                        <p>{{ $growthYear === now()->year ? 'Net deposits over the last 6 months' : "Net deposits for {$growthYear}" }}
                                        </p>
                                    </div>
                                    <select class="sm-filter-select" id="growthYearSelect"
                                        onchange="changeGrowthYear(this.value)">
                                        @foreach($availableGrowthYears as $y)
                                            <option value="{{ $y }}" {{ $growthYear == $y ? 'selected' : '' }}>{{ $y }}
                                            </option>
                                        @endforeach
                                    </select>
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
                                    <div class="chart-legend">
                                        <div class="legend-item"><span class="legend-dot"
                                                style="background:var(--blue);"></span>Prior months</div>
                                        <div class="legend-item"><span class="legend-dot"
                                                style="background:var(--gold);"></span>Current month</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        @if(!$hasShareCapital)
                            <div class="gate-shield">
                                <div class="gate-lock"><i class="fa-solid fa-lock"></i></div>
                                <div class="gate-msg">Savings breakdown is locked</div>
                                <div class="gate-sub">
                                    Please <a href="{{ route('ShareCapitalMember') }}">subscribe to Share Capital</a>
                                    first to unlock your breakdown and growth chart.
                                </div>
                            </div>
                        @endif
                    </div>

                    {{-- ══ TRANSACTION HISTORY — its own gated/hover-lock block ══ --}}
                    <div class="{{ !$hasShareCapital ? 'gated' : '' }}">
                        <section id="section2">
                            <div class="card-box-parent">
                                <div class="d-flex justify-content-between align-items-center card-box-title">
                                    <div class="title">
                                        <h3>Transaction History</h3>
                                        <p>View your monthly transactions breakdown</p>
                                    </div>
                                    <div class="gap-3 print">
                                        <button class="py-2 px-3 tw:text-white" style="border-radius: 10px">
                                            <i class="fa-solid fa-download"></i> CSV
                                        </button>
                                        <button class="py-2 px-3 tw:text-white" style="border-radius: 10px">
                                            <i class="fa fa-solid fa-download"></i> PDF
                                        </button>
                                    </div>
                                </div>

                                <div class="sm-tab-group">
                                    <a href="{{ route('savings.index', array_merge(request()->except('type', 'page'), ['type' => 'all'])) }}"
                                        class="sm-tab {{ $type === 'all' ? 'active' : '' }}">All</a>
                                    <a href="{{ route('savings.index', array_merge(request()->except('type', 'page'), ['type' => 'deposit'])) }}"
                                        class="sm-tab {{ $type === 'deposit' ? 'active' : '' }}">Deposits</a>
                                    <a href="{{ route('savings.index', array_merge(request()->except('type', 'page'), ['type' => 'withdrawal'])) }}"
                                        class="sm-tab {{ $type === 'withdrawal' ? 'active' : '' }}">Withdrawals</a>
                                </div>

                                <form method="GET" action="{{ route('savings.index') }}" class="sm-tx-toolbar"
                                    id="sm-tx-filter-form">
                                    <input type="hidden" name="type" value="{{ $type }}">
                                    <div class="sm-search-box">
                                        <i class="fa-solid fa-magnifying-glass"></i>
                                        <input type="text" name="ref" value="{{ $ref }}"
                                            placeholder="Search by reference no.">
                                    </div>
                                    <input type="date" class="sm-filter-select" name="date" value="{{ $date }}"
                                        onchange="document.getElementById('sm-tx-filter-form').submit()">

                                    <select name="status" class="sm-filter-select" onchange="this.form.submit()">
                                        <option value="all" {{ $status === 'all' ? 'selected' : '' }}>All Status</option>
                                        @foreach($availableStatuses as $s)
                                            <option value="{{ strtolower($s) }}" {{ $status === strtolower($s) ? 'selected' : '' }}>{{ $s }}</option>
                                        @endforeach
                                    </select>

                                    @if($ref !== '' || $date !== '' || $status !== 'all')
                                        <a href="{{ route('savings.index', ['type' => $type]) }}"
                                            class="sm-filter-clear">Clear filters</a>
                                    @endif
                                </form>

                                <div class="card-box">
                                    <div class="overflow-x-auto">
                                        <table class="table table-scroll m-0">
                                            <thead>
                                                <tr style="border-bottom: 1px solid rgba(0,0,0,0.2);">
                                                    <th class="text-start">Type</th>
                                                    <th class="text-start">Reference No.</th>
                                                    <th class="text-start">Date</th>
                                                    <th class="text-start">Amount</th>
                                                    <th class="text-start">Status</th>
                                                    <th class="text-start">Receipt</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse ($transactions as $tx)
                                                    <tr>
                                                        <td class="text-start">
                                                            @if($tx->type === 'deposit' && str_starts_with($tx->reference_no ?? '', 'DISB'))
                                                                <div class="deposit">Loan Disbursement</div>
                                                            @elseif($tx->type === 'deposit' && str_starts_with($tx->reference_no ?? '', 'PAT'))
                                                                <div class="deposit">Patronage Refund</div>
                                                            @elseif($tx->type === 'deposit')
                                                                <div class="deposit">Deposit</div>
                                                            @elseif($tx->type === 'td_release')
                                                                <div class="deposit">Time Deposit Claimed</div>
                                                            @elseif(str_starts_with($tx->reference_no ?? '', 'LNPAY'))
                                                                <div class="withdraw">Loan Repay</div>
                                                            @else
                                                                <div class="withdraw">Withdrawal</div>
                                                            @endif
                                                        </td>
                                                        <td class="text-start">
                                                            @if ($tx->reference_no)
                                                                <span class="tx-ref">{{ $tx->reference_no }}</span>
                                                            @else
                                                                <span style="color:#000000;font-size:0.78rem">—</span>
                                                            @endif
                                                        </td>
                                                        <td class="text-start">
                                                            {{ \Carbon\Carbon::parse($tx->transaction_date)->format('m/d/Y') }}
                                                        </td>
                                                        <td class="text-start"
                                                            style="font-weight:700; color:{{ $tx->type === 'withdrawal' ? 'var(--red)' : 'var(--green)' }}">
                                                            {{ $tx->type === 'withdrawal' ? '-' : '+' }} ₱
                                                            {{ number_format($tx->amount, 2) }}
                                                        </td>
                                                        <td>
                                                            @php
                                                                $displayStatus = $tx->status ?: match ($tx->type) {
                                                                    'deposit', 'withdrawal' => 'completed',
                                                                    'interest_credit' => 'credited',
                                                                    'td_lock' => 'locked',
                                                                    default => 'completed',
                                                                };
                                                            @endphp

                                                            @php
                                                                $displayStatus = $tx->status ?? 'completed';
                                                            @endphp

                                                            @if ($displayStatus === 'pending')
                                                                <span class="status pending">Pending</span>
                                                            @elseif (in_array($displayStatus, ['approved', 'completed']))
                                                                <span
                                                                    class="status approved">{{ ucfirst($displayStatus) }}</span>
                                                            @elseif ($displayStatus === 'released')
                                                                <span class="status released">Released</span>
                                                            @elseif ($displayStatus === 'deducted')
                                                                <span class="status deducted">Deducted</span>
                                                            @elseif ($displayStatus === 'rejected')
                                                                <span class="status rejected">Rejected</span>
                                                            @elseif ($displayStatus === 'credited')
                                                                <span class="status credited">Credited</span>
                                                            @elseif ($displayStatus === 'locked')
                                                                <span class="status locked">Locked</span>
                                                            @else
                                                                <span class="status">{{ ucfirst($displayStatus) }}</span>
                                                            @endif
                                                        </td>
                                                        <td class="text-start">
                                                            @if ($tx->reference_no && in_array($tx->type, ['deposit', 'withdrawal']))
                                                                <a href="{{ route('savings.receipt', $tx->reference_no) }}"
                                                                    title="Download Receipt"
                                                                    style="color: var(--teal);font-size: 18px;">
                                                                    <i class="fa-solid fa-file-arrow-down"></i>
                                                                </a>
                                                            @else
                                                                <span style="color:#c4c4c4;font-size:0.78rem">—</span>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="6" class="text-center py-5">
                                                            <i class="fa-solid fa-folder-open fa-2x mb-3"
                                                                style="color: var(--muted);"></i>
                                                            <p style="color:var(--muted);margin-top:0.5rem;">No
                                                                transactions yet.</p>
                                                        </td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>

                                    @if ($transactions->total() > 0)
                                        <div class="sm-pagination-wrap">
                                            <div class="sm-pagination-info">
                                                Showing <b>{{ $transactions->lastItem() }}</b> of
                                                <b>{{ $transactions->total() }}</b> transactions
                                            </div>

                                            @if ($transactions->hasPages())
                                                <div class="sm-pagination">
                                                    @if ($transactions->onFirstPage())
                                                        <span class="sm-page-btn disabled"><i
                                                                class="fa-solid fa-chevron-left"></i></span>
                                                    @else
                                                        <a href="{{ $transactions->previousPageUrl() }}" class="sm-page-btn">
                                                            <i class="fa-solid fa-chevron-left"></i>
                                                        </a>
                                                    @endif

                                                    @for ($i = 1; $i <= $transactions->lastPage(); $i++)
                                                        <a href="{{ $transactions->url($i) }}"
                                                            class="sm-page-btn {{ $i == $transactions->currentPage() ? 'active' : '' }}">
                                                            {{ $i }}
                                                        </a>
                                                    @endfor

                                                    @if ($transactions->hasMorePages())
                                                        <a href="{{ $transactions->nextPageUrl() }}" class="sm-page-btn">
                                                            <i class="fa-solid fa-chevron-right"></i>
                                                        </a>
                                                    @else
                                                        <span class="sm-page-btn disabled"><i
                                                                class="fa-solid fa-chevron-right"></i></span>
                                                    @endif
                                                </div>
                                            @endif
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </section>

                        @if(!$hasShareCapital)
                            <div class="gate-shield">
                                <div class="gate-lock"><i class="fa-solid fa-lock"></i></div>
                                <div class="gate-msg">Transaction history is locked</div>
                                <div class="gate-sub">
                                    Please <a href="{{ route('ShareCapitalMember') }}">subscribe to Share Capital</a>
                                    first to unlock your transaction history.
                                </div>
                            </div>
                        @endif
                    </div>

                    {{-- ══ /GATED WRAPPER ══ --}}

                </div>
            </div>
        </div>


        {{-- ============================================================
        DEPOSIT MODAL
        ============================================================ --}}
        <div class="modal fade" id="depositModal" tabindex="-1" aria-labelledby="depositModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content sm-modal-content">

                    <div class="modal-header sm-modal-header" style="padding: 24px 20px;">
                        <div class="modal-text">
                            <div class="sm-modal-icon sm-deposit-icon">
                                <img src="images/arrow-icon.png" alt="">
                            </div>
                            <div class="sm-modal-text">
                                <h1 class="modal-title sm-modal-title" id="depositModalLabel">Deposit Savings</h1>
                                <p class="sm-modal-subtitle">Add funds to your savings account</p>
                            </div>
                        </div>
                        <button type="button" class="sm-modal-close" data-bs-dismiss="modal" aria-label="Close">
                            <i class="fa-solid fa-xmark"></i>
                        </button>
                    </div>

                    <form action="{{ route('savings.deposit') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="_form" value="deposit">

                        <div class="modal-body sm-modal-body" style="padding: 1.25rem 1.5rem;">
                            <div class="sm-balance-pill">
                                <span class="sm-pill-label">My Savings Balance</span>
                                <span class="sm-pill-value">₱ {{ number_format($totalSavingsBalance, 2) }}</span>
                            </div>

                            <div class="sm-form-group">
                                <label class="sm-form-label" for="depositAmount">Amount to Deposit</label>
                                <div class="sm-amount-wrap">
                                    <span class="sm-amount-prefix">₱</span>
                                    <input class="form-input sm-form-input @error('amount') sm-input-error @enderror"
                                        type="number" id="depositAmount" name="amount" placeholder="0.00" min="1"
                                        step="0.01" value="{{ old('amount') }}" required />
                                </div>
                                <div class="sm-quick-amounts">
                                    <button type="button" class="sm-quick-btn"
                                        onclick="setSavingsAmount('depositAmount', 500)">₱500</button>
                                    <button type="button" class="sm-quick-btn"
                                        onclick="setSavingsAmount('depositAmount', 1000)">₱1,000</button>
                                    <button type="button" class="sm-quick-btn"
                                        onclick="setSavingsAmount('depositAmount', 1500)">₱1,500</button>
                                    <button type="button" class="sm-quick-btn"
                                        onclick="setSavingsAmount('depositAmount', 2000)">₱2,000</button>
                                    <button type="button" class="sm-quick-btn"
                                        onclick="setSavingsAmount('depositAmount', 5000)">₱5,000</button>
                                </div>
                                @error('amount')
                                    <div class="sm-error-msg show">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="sm-form-group">
                                <label class="sm-form-label" for="depositPaymentMethod">Payment Method</label>
                                <select class="form-select" name="payment_method" id="depositPaymentMethod"
                                    style="border-radius: 10px; border: 1.5px solid #e0e0e0; height: 46px;  font-size: 14px; color: #333;"
                                    required>
                                    <option value="" disabled selected>Select payment method...</option>
                                    <option value="cash" {{ old('payment_method') === 'cash' ? 'selected' : '' }}>Cash
                                    </option>
                                    <option value="gcash" {{ old('payment_method') === 'gcash' ? 'selected' : '' }}>GCash
                                    </option>
                                </select>
                            </div>

                            <div id="deposit-gcash-box" style="display:none; margin: 1rem 0;">
                                @if($gcashPaymentMethod && $gcashPaymentMethod->has_qr_code && $gcashPaymentMethod->qr_code_image_path)
                                    <div
                                        style="background: linear-gradient(135deg, #f0f7ff 0%, #e8f4ff 100%); border: 1.5px solid #c2deff; border-radius: 12px; padding: 1rem 1.2rem; text-align: center;">
                                        <p style="margin: 0 0 10px; font-size: 14px; font-weight: 700; color: #0056b3;">
                                            <i class="fa-solid fa-mobile-screen-button"></i> Scan to Pay via GCash
                                        </p>
                                        <img src="{{ asset('storage/' . $gcashPaymentMethod->qr_code_image_path) }}"
                                            alt="GCash QR Code"
                                            style="width: 220px; height: 220px; max-width: 100%; object-fit: contain; border-radius: 10px; border: 1px solid #c2deff; background: #fff; padding: 12px; display: block; margin: 0 auto;">
                                        <p style="margin: 10px 0 0; font-size: 11px; color: #5a8ac4;">
                                            Scan this using your GCash app, then upload your payment screenshot below.
                                        </p>
                                        <p style="margin: 6px 0 0; font-size: 11px;">
                                            <a href="#"
                                                onclick="openQrLightbox('{{ asset('storage/' . $gcashPaymentMethod->qr_code_image_path) }}'); return false;"
                                                style="color: #0056b3; font-weight: 600;">
                                                <i class="fa fa-up-right-and-down-left-from-center"></i> View full-size QR
                                            </a>
                                        </p>
                                    </div>
                                @else
                                    <div
                                        style="background: #fff3cd; border: 1.5px solid #ffe08a; border-radius: 12px; padding: 1rem 1.2rem;">
                                        <p style="margin: 0; font-size: 13px; color: #856404;">
                                            <i class="fa fa-triangle-exclamation"></i> No GCash QR code has been set up yet.
                                            Please contact the admin.
                                        </p>
                                    </div>
                                @endif

                                <div style="margin-top: 1rem;">
                                    <label
                                        style="font-size: 12px; text-transform: uppercase; font-weight: 600; color: #888888; display: block; margin-bottom: 6px;">
                                        Upload Payment Screenshot <span style="font-size: 11px; color: #bbb;">(GCash
                                            proof)</span>
                                    </label>
                                    <input type="file" name="gcash_proof" id="deposit-gcash-proof-input"
                                        accept="image/png,image/jpeg,image/jpg"
                                        style="width: 100%; padding: 8px 10px; border-radius: 10px; border: 1.5px solid #ddd; font-size: 14px; box-sizing: border-box;"
                                        class="form-control">
                                    <div id="deposit-gcash-proof-preview" style="display:none; margin-top:10px;">
                                        <img id="deposit-gcash-proof-preview-img"
                                            style="width:100%; height:180px; object-fit:cover; border-radius:8px; border:1px solid #e0e0e0;">
                                    </div>
                                </div>
                            </div>

                            <div class="sm-form-group">
                                <label class="sm-form-label" for="depositNote">Note (optional)</label>
                                <input class="sm-form-input" type="text" id="depositNote" name="note"
                                    style="width: 100%;padding: 8px 10px; border-radius: 10px; border: 1.5px solid #ddd; font-size: 14px;color: #333;  box-sizing: border-box; height: 46px;"
                                    placeholder="e.g. Monthly contribution" value="{{ old('note') }}" />
                            </div>
                        </div>

                        <div class="modal-footer sm-modal-footer"
                            style="background: #f8f9fa; border-top: 1px solid rgba(0, 0, 0, 0.1); padding: 1rem 1.6rem; display: flex;justify-content: center;align-items: center; gap: 8px;">
                            <div id="deposit-confirm-btn-wrap">
                                <button type="submit" class="sm-btn-confirm sm-deposit-confirm">
                                    <i class="fa-solid fa-circle-arrow-down"></i> Confirm Deposit
                                </button>
                            </div>
                            <button type="button" class="sm-btn-cancel done" data-bs-dismiss="modal">Cancel</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>


        {{-- ============================================================
        WITHDRAW MODAL
        ============================================================ --}}
        <div class="modal fade" id="withdrawModal" tabindex="-1" aria-labelledby="withdrawModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content sm-modal-content">

                    <div class="modal-header sm-modal-header" style="padding: 24px 20px;">
                        <div class="modal-text">
                            <div class="sm-modal-icon sm-withdraw-icon">
                                <img src="images/arrow-icon.png" alt="">
                            </div>
                            <div class="sm-modal-text">
                                <h1 class="modal-title sm-modal-title" id="withdrawModalLabel">Withdraw Savings</h1>
                                <p class="sm-modal-subtitle">Withdraw funds from your savings account</p>
                            </div>
                        </div>
                        <button type="button" class="sm-modal-close" data-bs-dismiss="modal" aria-label="Close">
                            <i class="fa-solid fa-xmark"></i>
                        </button>
                    </div>

                    <form action="{{ route('savings.withdraw') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="_form" value="withdraw">

                        <div class="modal-body sm-modal-body">
                            <div class="sm-balance-pill">
                                <span class="sm-pill-label">My Savings Balance</span>
                                <span class="sm-pill-value">₱ {{ number_format($totalSavingsBalance, 2) }}</span>
                            </div>

                            <div class="sm-form-group">
                                <label class="sm-form-label" for="withdrawAmount">Amount to Withdraw</label>
                                <div class="sm-amount-wrap">
                                    <span class="sm-amount-prefix">₱</span>
                                    <input class="sm-form-input @error('amount') sm-input-error @enderror" type="number"
                                        id="withdrawAmount" name="amount" placeholder="0.00" min="1" step="0.01"
                                        value="{{ old('amount') }}" required />
                                </div>
                                <div class="sm-quick-amounts">
                                    <button type="button" class="sm-quick-btn"
                                        onclick="setSavingsAmount('withdrawAmount', 500)">₱500</button>
                                    <button type="button" class="sm-quick-btn"
                                        onclick="setSavingsAmount('withdrawAmount', 1000)">₱1,000</button>
                                    <button type="button" class="sm-quick-btn"
                                        onclick="setSavingsAmount('withdrawAmount', 1500)">₱1,500</button>
                                    <button type="button" class="sm-quick-btn"
                                        onclick="setSavingsAmount('withdrawAmount', 2000)">₱2,000</button>
                                    <button type="button" class="sm-quick-btn"
                                        onclick="setSavingsAmount('withdrawAmount', {{ $totalSavingsBalance }})">All</button>
                                </div>
                                @error('amount')
                                    <div class="sm-error-msg show">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="sm-form-group">
                                <label class="sm-form-label" for="withdrawPaymentMethod">Payment Method</label>
                                <select class="sm-form-select form-select" name="payment_method"
                                    id="withdrawPaymentMethod"
                                    style="border-radius: 10px; border: 1.5px solid #e0e0e0;  height: 46px;  font-size: 14px; color: #333;"
                                    required>
                                    <option value="" disabled selected>Select payment method...</option>
                                    <option value="cash" {{ old('payment_method') === 'cash' ? 'selected' : '' }}>Cash
                                    </option>
                                    <option value="gcash" {{ old('payment_method') === 'gcash' ? 'selected' : '' }}>GCash
                                    </option>
                                </select>
                            </div>

                            <div id="withdraw-gcash-box" style="display:none; margin: 1rem 0;">
                                @if($gcashPaymentMethod && $gcashPaymentMethod->has_qr_code && $gcashPaymentMethod->qr_code_image_path)
                                    <div
                                        style="background: linear-gradient(135deg, #f0f7ff 0%, #e8f4ff 100%); border: 1.5px solid #c2deff; border-radius: 12px; padding: 1rem 1.2rem; text-align: center;">
                                        <p style="margin: 0 0 10px; font-size: 14px; font-weight: 700; color: #0056b3;">
                                            <i class="fa-solid fa-mobile-screen-button"></i> Scan to Pay via GCash
                                        </p>
                                        <img src="{{ asset('storage/' . $gcashPaymentMethod->qr_code_image_path) }}"
                                            alt="GCash QR Code"
                                            style="width: 220px; height: 220px; max-width: 100%; object-fit: contain; border-radius: 10px; border: 1px solid #c2deff; background: #fff; padding: 12px; display: block; margin: 0 auto;">
                                        <p style="margin: 10px 0 0; font-size: 11px; color: #5a8ac4;">
                                            Scan this using your GCash app, then upload your payment screenshot below.
                                        </p>
                                        <p style="margin: 6px 0 0; font-size: 11px;">
                                            <a href="#"
                                                onclick="openQrLightbox('{{ asset('storage/' . $gcashPaymentMethod->qr_code_image_path) }}'); return false;"
                                                style="color: #0056b3; font-weight: 600;">
                                                <i class="fa fa-up-right-and-down-left-from-center"></i> View full-size QR
                                            </a>
                                        </p>
                                    </div>
                                @else
                                    <div
                                        style="background: #fff3cd; border: 1.5px solid #ffe08a; border-radius: 12px; padding: 1rem 1.2rem;">
                                        <p style="margin: 0; font-size: 13px; color: #856404;">
                                            <i class="fa fa-triangle-exclamation"></i> No GCash QR code has been set up yet.
                                            Please contact the admin.
                                        </p>
                                    </div>
                                @endif

                                <div style="margin-top: 1rem;">
                                    <label
                                        style="font-size: 12px; text-transform: uppercase; font-weight: 600; color: #888888; display: block; margin-bottom: 6px;">
                                        Upload Payment Screenshot <span style="font-size: 11px; color: #bbb;">(GCash
                                            proof)</span>
                                    </label>
                                    <input type="file" name="gcash_proof" id="withdraw-gcash-proof-input"
                                        accept="image/png,image/jpeg,image/jpg"
                                        style="width: 100%; padding: 8px 10px; border-radius: 10px; border: 1.5px solid #ddd; font-size: 14px; box-sizing: border-box;"
                                        class="form-control">
                                    <div id="withdraw-gcash-proof-preview" style="display:none; margin-top:10px;">
                                        <img id="withdraw-gcash-proof-preview-img"
                                            style="width:100%; height:180px; object-fit:cover; border-radius:8px; border:1px solid #e0e0e0;">
                                    </div>
                                </div>
                            </div>

                            <div class="sm-form-group">
                                <label class="sm-form-label" for="withdrawNote">Note (optional)</label>
                                <input class="sm-form-input" type="text" id="withdrawNote" name="note"
                                    style="width: 100%; padding: 8px 10px; border-radius: 10px; border: 1.5px solid #ddd; font-size: 14px; color: #333;  box-sizing: border-box;  height: 46px;"
                                    placeholder="e.g. Emergency expense" value="{{ old('note') }}" />
                            </div>
                        </div>

                        <div class="modal-footer sm-modal-footer">
                            <div id="withdraw-confirm-btn-wrap">
                                <button type="submit" class="sm-btn-confirm sm-withdraw-confirm">
                                    <i class="fa-solid fa-circle-arrow-up"></i> Confirm Withdraw
                                </button>
                            </div>
                            <button type="button" class="sm-btn-cancel" data-bs-dismiss="modal">Cancel</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>


        {{-- ============================================================
        SUCCESS MODAL — Deposit
        ============================================================ --}}
        <div class="modal fade" id="depositSuccessModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-sm">
                <div class="modal-content sm-modal-content">
                    <div class="modal-body sm-success-body">

                        <div class="sm-success-icon sm-success-green">
                            <i class="fa-solid fa-check"></i>
                        </div>

                        <h5 class="sm-success-title">Deposit Successful!</h5>

                        <p class="sm-success-msg">
                            Your deposit of
                            <strong>₱
                                {{ session('deposit_amount') ? number_format(session('deposit_amount'), 2) : '0.00' }}</strong>
                            has been added to your savings account.
                        </p>

                        @if (session('deposit_reference'))
                            <div class="sm-ref-pill">
                                <span class="sm-ref-label">Reference No.</span>
                                <span class="sm-ref-value" id="deposit-ref-no">{{ session('deposit_reference') }}</span>
                                <button class="sm-copy-btn" onclick="copyRef('deposit-ref-no')" title="Copy">
                                    <i class="fa-regular fa-copy"></i>
                                </button>
                            </div>
                        @endif

                        <div class="sm-success-balance-pill">
                            <span>New Balance</span>
                            <span>₱ {{ number_format($savingsAccount->balance, 2) }}</span>
                        </div>

                        @if (session('deposit_reference'))
                            <a href="{{ route('savings.receipt', session('deposit_reference')) }}" class="sm-btn-download">
                                <i class="fa-solid fa-file-arrow-down"></i> Download Receipt
                            </a>
                        @endif

                        <button type="button" class="sm-btn-confirm sm-deposit-confirm w-100 mt-3"
                            data-bs-dismiss="modal">
                            <i class="fa-solid fa-check"></i> Done
                        </button>

                    </div>
                </div>
            </div>
        </div>


        {{-- ============================================================
        SUCCESS MODAL — Withdraw
        ============================================================ --}}
        <div class="modal fade" id="withdrawSuccessModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-sm">
                <div class="modal-content sm-modal-content">
                    <div class="modal-body sm-success-body">

                        <div class="sm-success-icon sm-success-red">
                            <i class="fa-solid fa-check"></i>
                        </div>

                        <h5 class="sm-success-title">Withdraw Successful!</h5>

                        <p class="sm-success-msg">
                            Your withdrawal of
                            <strong>₱
                                {{ session('withdraw_amount') ? number_format(session('withdraw_amount'), 2) : '0.00' }}</strong>
                            has been deducted from your savings account.
                        </p>

                        @if (session('withdraw_reference'))
                            <div class="sm-ref-pill">
                                <span class="sm-ref-label">Reference No.</span>
                                <span class="sm-ref-value" id="withdraw-ref-no">{{ session('withdraw_reference') }}</span>
                                <button class="sm-copy-btn" onclick="copyRef('withdraw-ref-no')" title="Copy">
                                    <i class="fa-regular fa-copy"></i>
                                </button>
                            </div>
                        @endif

                        <div class="sm-success-balance-pill">
                            <span>New Balance</span>
                            <span>₱ {{ number_format($savingsAccount->balance, 2) }}</span>
                        </div>

                        @if (session('withdraw_reference'))
                            <a href="{{ route('savings.receipt', session('withdraw_reference')) }}" class="sm-btn-download">
                                <i class="fa-solid fa-file-arrow-down"></i> Download Receipt
                            </a>
                        @endif

                        <button type="button" class="sm-btn-confirm sm-withdraw-confirm w-100 mt-3"
                            data-bs-dismiss="modal">
                            <i class="fa-solid fa-check"></i> Done
                        </button>

                    </div>
                </div>
            </div>
        </div>

        {{-- ============================================================
        TIME DEPOSIT HISTORY MODAL — view all, search & filter
        ============================================================ --}}
        <div class="modal fade" id="tdHistoryModal" tabindex="-1" aria-labelledby="tdHistoryModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content sm-modal-content">

                    <div class="modal-header sm-modal-header" style="padding: 24px 20px;">
                        <div class="modal-text">
                            <div class="sm-modal-icon sm-deposit-icon">
                                <i class="fa-solid fa-lock" style="color:#fff;"></i>
                            </div>
                            <div class="sm-modal-text">
                                <h1 class="modal-title sm-modal-title" id="tdHistoryModalLabel">Time Deposit Accounts
                                </h1>
                                <p class="sm-modal-subtitle">All your Time Deposit history</p>
                            </div>
                        </div>
                        <button type="button" class="sm-modal-close" data-bs-dismiss="modal" aria-label="Close">
                            <i class="fa-solid fa-xmark"></i>
                        </button>
                    </div>

                    <div class="modal-body sm-modal-body" style="padding: 1.25rem 1.5rem;">

                        <div class="sm-tx-toolbar" style="margin:0 0 1rem;">
                            <div class="sm-search-box">
                                <i class="fa-solid fa-magnifying-glass"></i>
                                <input type="text" id="tdSearchInput"
                                    placeholder="Search by reference no. or goal amount">
                            </div>
                            <input type="date" class="sm-filter-select" id="tdDateFilter">
                            <select class="sm-filter-select" id="tdStatusFilter">
                                <option value="all">All Status</option>
                                <option value="completed">Completed</option>
                                <option value="matured">Ready to Claim</option>
                                <option value="goal_reached">Fully Funded</option>
                                <option value="in_progress">In Progress</option>
                            </select>
                            <a href="#" id="tdClearFilters" class="sm-filter-clear">Clear filters</a>
                        </div>

                        <div id="tdHistoryList">
                            @forelse ($tdHistory as $td)
                                <div class="td-modal-row" data-ref="{{ strtolower($td->reference_no ?? '') }}"
                                    data-goal="{{ $td->goal_amount }}"
                                    data-date="{{ \Carbon\Carbon::parse($td->opened_at)->format('Y-m-d') }}"
                                    data-status="{{ $td->display_status }}">
                                    <div class="td-modal-icon td-icon-{{ $td->display_status }}">
                                        @if ($td->display_status === 'completed')
                                            <i class="fa fa-circle-check"></i>
                                        @elseif ($td->display_status === 'matured')
                                            <i class="fa fa-hourglass-end"></i>
                                        @elseif ($td->display_status === 'goal_reached')
                                            <i class="fa fa-bullseye"></i>
                                        @else
                                            <i class="fa fa-lock"></i>
                                        @endif
                                    </div>
                                    <div class="td-modal-info">
                                        <h4>₱{{ number_format($td->goal_amount, 2) }} Goal</h4>
                                        <p>
                                            Ref: {{ $td->reference_no ?? '—' }} ·
                                            Opened {{ \Carbon\Carbon::parse($td->opened_at)->format('M d, Y') }}
                                            · {{ number_format($td->interest_rate, 2) }}% p.a.
                                        </p>
                                    </div>
                                    <div class="td-modal-amount">
                                        <h4>₱{{ number_format($td->display_balance, 2) }}</h4>
                                        @if ($td->display_status === 'completed')
                                            <span class="td-status-badge td-status-completed">Completed</span>
                                        @elseif ($td->display_status === 'matured')
                                            <span class="td-status-badge td-status-matured">Ready to Claim</span>
                                        @elseif ($td->display_status === 'goal_reached')
                                            <span class="td-status-badge td-status-goal_reached">Fully Funded</span>
                                        @else
                                            <span class="td-status-badge td-status-in_progress">In Progress</span>
                                        @endif
                                    </div>
                                </div>
                            @empty
                                <div style="text-align:center; padding:2.5rem 1rem;">
                                    <i class="fa-solid fa-piggy-bank fa-2x" style="color:var(--muted); opacity:.4;"></i>
                                    <p style="color:var(--muted); margin-top:0.75rem; font-size:13.5px;">
                                        No Time Deposits opened yet.
                                    </p>
                                </div>
                            @endforelse

                            <div id="tdNoResults" style="display:none; text-align:center; padding:2.5rem 1rem;">
                                <i class="fa-solid fa-magnifying-glass fa-2x"
                                    style="color:var(--muted); opacity:.4;"></i>
                                <p style="color:var(--muted); margin-top:0.75rem; font-size:13.5px;">
                                    No matching Time Deposits found.
                                </p>
                            </div>
                        </div>

                        @if($tdHistory->count() > 0)
                            <div class="td-pagination-wrap">
                                <div class="sm-pagination-info" id="tdPaginationInfo"></div>
                                <div class="sm-pagination" id="tdPaginationBtns"></div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- ★ KEPT: Claiming a matured TD (initiated from the Time Deposit page)
        still redirects here, so this confirmation modal stays. --}}
        <div class="modal fade" id="tdClaimSuccessModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-sm">
                <div class="modal-content sm-modal-content">
                    <div class="modal-body sm-success-body">
                        <div class="sm-success-icon sm-success-green">
                            <i class="fa-solid fa-check"></i>
                        </div>
                        <h5 class="sm-success-title">Time Deposit Claimed!</h5>
                        <p class="sm-success-msg">
                            <strong>₱{{ session('td_claim_amount') ? number_format(session('td_claim_amount'), 2) : '0.00' }}</strong>
                            (principal + interest) has been added to your Regular Savings.
                        </p>
                        @if (session('td_claim_reference'))
                            <div class="sm-ref-pill">
                                <span class="sm-ref-label">Reference No.</span>
                                <span class="sm-ref-value" id="tdclaim-ref-no">{{ session('td_claim_reference') }}</span>
                                <button class="sm-copy-btn" onclick="copyRef('tdclaim-ref-no')" title="Copy">
                                    <i class="fa-regular fa-copy"></i>
                                </button>
                            </div>
                        @endif
                        <div class="sm-success-balance-pill">
                            <span>New Regular Savings Balance</span>
                            <span>₱ {{ number_format($savingsAccount->balance, 2) }}</span>
                        </div>
                        <button type="button" class="sm-btn-confirm sm-deposit-confirm w-100 mt-3"
                            data-bs-dismiss="modal">
                            <i class="fa-solid fa-check"></i> Done
                        </button>
                    </div>
                </div>
            </div>
        </div>


        {{-- Hidden trigger buttons --}}
        <button id="triggerDepositSuccess" data-bs-toggle="modal" data-bs-target="#depositSuccessModal"
            style="display:none;"></button>
        <button id="triggerWithdrawSuccess" data-bs-toggle="modal" data-bs-target="#withdrawSuccessModal"
            style="display:none;"></button>
        <button id="triggerDepositModal" data-bs-toggle="modal" data-bs-target="#depositModal"
            style="display:none;"></button>
        <button id="triggerWithdrawModal" data-bs-toggle="modal" data-bs-target="#withdrawModal"
            style="display:none;"></button>
        <button id="triggerTdClaimSuccess" data-bs-toggle="modal" data-bs-target="#tdClaimSuccessModal"
            style="display:none;"></button>

        <form id="savings-withdraw-gcash-form" action="{{ route('savings.gcash') }}" method="POST"
            style="display:none;">
            @csrf
            <input type="hidden" name="transaction_type" value="withdraw">
            <input type="hidden" name="amount" id="savings-withdraw-gcash-amount">
            <input type="hidden" name="note" id="savings-withdraw-gcash-note">
        </form>

    </div>{{-- end container-fluid --}}

    {{-- QR Lightbox --}}
    <div id="qr-lightbox-overlay"
        style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.8); z-index:100000; align-items:center; justify-content:center;">
        <button type="button" onclick="closeQrLightbox()"
            style="position:absolute; top:20px; right:24px; background:#fff; border:none; width:40px; height:40px; border-radius:50%; font-size:20px; color:#333; cursor:pointer; display:flex; align-items:center; justify-content:center;">
            <i class="fa fa-times"></i>
        </button>
        <img id="qr-lightbox-img" src="" alt="GCash QR Code"
            style="max-width:90%; max-height:85vh; border-radius:12px;">
    </div>

    @error('amount')
        <div class="sm-error-msg show">{{ $message }}</div>
    @enderror

    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init();
    </script>

    <script>
        @if ($errors->any() && old('_form') === 'deposit')
            document.getElementById('triggerDepositModal').click();
        @endif
    </script>

    <script>
        const smRefInput = document.querySelector('.sm-search-box input[name="ref"]');
        const smFilterForm = document.getElementById('sm-tx-filter-form');
        let smSearchDebounce;

        if (smRefInput) {
            smRefInput.addEventListener('input', function () {
                clearTimeout(smSearchDebounce);
                smSearchDebounce = setTimeout(() => smFilterForm.submit(), 500);
            });

            if (smRefInput.value) {
                smRefInput.focus();
                const val = smRefInput.value;
                smRefInput.value = '';
                smRefInput.value = val;
            }
        }

        function changeGrowthYear(year) {
            const url = new URL(window.location.href);
            url.searchParams.set('growth_year', year);
            window.location.href = url.toString();
        }

        function openQrLightbox(src) {
            document.getElementById('qr-lightbox-img').src = src;
            document.getElementById('qr-lightbox-overlay').style.display = 'flex';
        }

        function closeQrLightbox() {
            document.getElementById('qr-lightbox-overlay').style.display = 'none';
        }

        document.getElementById('qr-lightbox-overlay')?.addEventListener('click', function (e) {
            if (e.target === this) closeQrLightbox();
        });
    </script>

    <script>
        function setSavingsAmount(inputId, val) {
            document.getElementById(inputId).value = val;
        }

        function copyRef(elementId) {
            const text = document.getElementById(elementId).textContent.trim();
            navigator.clipboard.writeText(text).then(() => {
                const btn = event.currentTarget;
                btn.innerHTML = '<i class="fa-solid fa-check"></i>';
                setTimeout(() => { btn.innerHTML = '<i class="fa-regular fa-copy"></i>'; }, 1500);
            });
        }

        document.getElementById('depositPaymentMethod')?.addEventListener('change', function () {
            const isGcash = this.value === 'gcash';
            document.getElementById('deposit-gcash-box').style.display = isGcash ? 'block' : 'none';
            document.getElementById('deposit-gcash-proof-input').required = isGcash;
            // Confirm button stays visible — GCash now submits through the same form.
        });

        document.getElementById('withdrawPaymentMethod')?.addEventListener('change', function () {
            const isGcash = this.value === 'gcash';
            document.getElementById('withdraw-gcash-box').style.display = isGcash ? 'block' : 'none';
            document.getElementById('withdraw-gcash-proof-input').required = isGcash;
        });

        document.getElementById('deposit-gcash-proof-input')?.addEventListener('change', function () {
            if (this.files && this.files[0]) {
                const reader = new FileReader();
                reader.onload = e => {
                    document.getElementById('deposit-gcash-proof-preview-img').src = e.target.result;
                    document.getElementById('deposit-gcash-proof-preview').style.display = 'block';
                };
                reader.readAsDataURL(this.files[0]);
            }
        });

        document.getElementById('withdraw-gcash-proof-input')?.addEventListener('change', function () {
            if (this.files && this.files[0]) {
                const reader = new FileReader();
                reader.onload = e => {
                    document.getElementById('withdraw-gcash-proof-preview-img').src = e.target.result;
                    document.getElementById('withdraw-gcash-proof-preview').style.display = 'block';
                };
                reader.readAsDataURL(this.files[0]);
            }
        });

        document.getElementById('depositModal')?.addEventListener('show.bs.modal', function () {
            document.getElementById('depositPaymentMethod').value = '';
            document.getElementById('deposit-gcash-box').style.display = 'none';
            document.getElementById('deposit-confirm-btn-wrap').style.display = 'block';
        });

        document.getElementById('withdrawModal')?.addEventListener('show.bs.modal', function () {
            document.getElementById('withdrawPaymentMethod').value = '';
            document.getElementById('withdraw-gcash-box').style.display = 'none';
            document.getElementById('withdraw-confirm-btn-wrap').style.display = 'block';
        });

        function submitSavingsGcash(type) {
            const amountInput = document.getElementById(type === 'deposit' ? 'depositAmount' : 'withdrawAmount');
            const noteInput = document.getElementById(type === 'deposit' ? 'depositNote' : 'withdrawNote');
            const amount = amountInput.value;

            if (!amount || parseFloat(amount) < 1) {
                alert('Please enter a valid amount first.');
                return;
            }

            document.getElementById(`savings-${type}-gcash-amount`).value = amount;
            document.getElementById(`savings-${type}-gcash-note`).value = noteInput.value;
            document.getElementById(`savings-${type}-gcash-form`).submit();
        }

        window.addEventListener('DOMContentLoaded', function () {

            @if ($errors->any() && old('_form') === 'deposit')
                document.getElementById('triggerDepositModal').click();
            @endif

            @if ($errors->any() && old('_form') === 'withdraw')
                document.getElementById('triggerWithdrawModal').click();
            @endif

            @if (session('deposit_success'))
                document.getElementById('triggerDepositSuccess').click();
            @endif

            @if (session('withdraw_success'))
                document.getElementById('triggerWithdrawSuccess').click();
            @endif

            @if (session('td_claim_success'))
                document.getElementById('triggerTdClaimSuccess').click();
            @endif

        });

        // Reset the Time Deposit mini-panel scroll on load (fixes the "cut off row" look)
        document.querySelectorAll('.parent-panel .panel-body').forEach(el => el.scrollTop = 0);

        // Time Deposit modal — search, filter & pagination (10 per page)
        (function () {
            const searchInput = document.getElementById('tdSearchInput');
            const dateFilter = document.getElementById('tdDateFilter');
            const statusFilter = document.getElementById('tdStatusFilter');
            const clearBtn = document.getElementById('tdClearFilters');
            const noResults = document.getElementById('tdNoResults');
            const paginationInfo = document.getElementById('tdPaginationInfo');
            const paginationBtns = document.getElementById('tdPaginationBtns');
            const allRows = Array.from(document.querySelectorAll('.td-modal-row'));
            const PAGE_SIZE = 10;
            let currentPage = 1;

            function getFilteredRows() {
                const q = (searchInput?.value || '').trim().toLowerCase();
                const date = dateFilter?.value || '';
                const status = statusFilter?.value || 'all';

                return allRows.filter(row => {
                    const matchesSearch = !q || row.dataset.ref.includes(q) || row.dataset.goal.includes(q);
                    const matchesDate = !date || row.dataset.date === date;
                    const matchesStatus = status === 'all' || row.dataset.status === status;
                    return matchesSearch && matchesDate && matchesStatus;
                });
            }

            function renderPage() {
                const filtered = getFilteredRows();
                const totalPages = Math.max(1, Math.ceil(filtered.length / PAGE_SIZE));
                currentPage = Math.min(currentPage, totalPages);

                // Hide every row first
                allRows.forEach(row => row.style.display = 'none');

                // Show only the current page's slice of the filtered set
                const start = (currentPage - 1) * PAGE_SIZE;
                const pageRows = filtered.slice(start, start + PAGE_SIZE);
                pageRows.forEach(row => row.style.display = 'flex');

                if (noResults) noResults.style.display = filtered.length === 0 ? 'block' : 'none';

                // Pagination info
                if (paginationInfo) {
                    paginationInfo.innerHTML = filtered.length === 0
                        ? ''
                        : `Showing <b>${start + 1}–${Math.min(start + PAGE_SIZE, filtered.length)}</b> of <b>${filtered.length}</b> Time Deposits`;
                }

                // Pagination buttons
                if (paginationBtns) {
                    paginationBtns.innerHTML = '';
                    if (filtered.length > PAGE_SIZE) {
                        const prevBtn = document.createElement('span');
                        prevBtn.className = 'sm-page-btn' + (currentPage === 1 ? ' disabled' : '');
                        prevBtn.innerHTML = '<i class="fa-solid fa-chevron-left"></i>';
                        prevBtn.onclick = () => { if (currentPage > 1) { currentPage--; renderPage(); } };
                        paginationBtns.appendChild(prevBtn);

                        for (let i = 1; i <= totalPages; i++) {
                            const btn = document.createElement('a');
                            btn.href = '#';
                            btn.className = 'sm-page-btn' + (i === currentPage ? ' active' : '');
                            btn.textContent = i;
                            btn.onclick = (e) => { e.preventDefault(); currentPage = i; renderPage(); };
                            paginationBtns.appendChild(btn);
                        }

                        const nextBtn = document.createElement('span');
                        nextBtn.className = 'sm-page-btn' + (currentPage === totalPages ? ' disabled' : '');
                        nextBtn.innerHTML = '<i class="fa-solid fa-chevron-right"></i>';
                        nextBtn.onclick = () => { if (currentPage < totalPages) { currentPage++; renderPage(); } };
                        paginationBtns.appendChild(nextBtn);
                    }
                }
            }

            function applyTdFilters() {
                currentPage = 1;
                renderPage();
            }

            searchInput?.addEventListener('input', applyTdFilters);
            dateFilter?.addEventListener('change', applyTdFilters);
            statusFilter?.addEventListener('change', applyTdFilters);
            clearBtn?.addEventListener('click', function (e) {
                e.preventDefault();
                searchInput.value = '';
                dateFilter.value = '';
                statusFilter.value = 'all';
                applyTdFilters();
            });

            // Re-render fresh every time the modal opens (in case data changed)
            document.getElementById('tdHistoryModal')?.addEventListener('show.bs.modal', () => {
                currentPage = 1;
                renderPage();
            });

            renderPage();
        })();
    </script>

</body>

</html>