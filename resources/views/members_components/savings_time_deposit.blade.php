<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Time Deposit</title>

    <link rel="stylesheet" href="css_folder/time_deposit.css">
    <link rel="stylesheet" href="css_folder/savings_modal.css">
    <link rel="stylesheet" href="css_folder/loading.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link rel="stylesheet" href="../font-awesome-icon/css/all.min.css">

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
    </style>
</head>

<body>

    <div class="container-fluid m-0 p-0">
        @include("components.offcanvas")
        @include("components.sidebar")

        <div class="rightbar">
            @include("components.navbar2")

            <div class="main-parent">
                <div class="bread-crambs">
                    <!-- <i class="fa fa-piggy-bank"></i> -->
                    <a href="{{ route('savings.index') }}">Savings</a>
                    <i class="fa fa-chevron-right"></i>
                    <p>Time Deposit</p>
                </div>
                <div class="main-header">
                    <h3>Time Deposit</h3>
                    <p>Set a savings goal, deposit toward it over time, and earn guaranteed interest once your term
                        matures.</p>
                </div>

                <div class="main-body">
                    <div class="main-card-box">
                        <div class="parent-card">
                            <div class="left-card">
                                <h3>Time Deposit Balance</h3>
                                <h2>₱ <b>{{ number_format($tdBalance, 2) }}</b></h2>
                                <div class="hero-sub">
                                    <i class="fa fa-calendar-days"></i>
                                    @if ($tdMatured)
                                        Matured {{ $tdMaturityDate->format('M d, Y') }} · Ready to claim
                                    @elseif ($hasActiveTimeDeposit && $goalReached)
                                        Goal reached · Matures {{ $tdMaturityDate->format('M d, Y') }}
                                    @elseif ($hasActiveTimeDeposit)
                                        Goal ₱{{ number_format($tdGoalAmount, 2) }} · Matures
                                        {{ $tdMaturityDate->format('M d, Y') }} · {{ number_format($tdRate, 2) }}% p.a.
                                    @else
                                        No active Time Deposit
                                    @endif
                                </div>

                                <div class="deposit-button">
                                    @if ($tdMatured)
                                        <form action="{{ route('savings.claimTimeDeposit') }}" method="POST"
                                            style="margin:0;">
                                            @csrf
                                            <button type="submit">
                                                <div class="card-icon">
                                                    <i class="fa-solid fa-hand-holding-dollar"
                                                        style="color:#fff;font-size:14px;"></i>
                                                </div>
                                                <div>Claim Time Deposit</div>
                                            </button>
                                        </form>
                                    @elseif ($hasActiveTimeDeposit && $goalReached)
                                        <button type="button" disabled>
                                            <div class="card-icon">
                                                <i class="fa-solid fa-circle-check" style="color:#fff;font-size:14px;"></i>
                                            </div>
                                            <div>Goal Reached — Awaiting Maturity</div>
                                        </button>
                                    @elseif ($hasActiveTimeDeposit)
                                        <button type="button" data-bs-toggle="modal" data-bs-target="#depositTdModal">
                                            <div class="card-icon">
                                                <img src="{{ asset('images/arrow-icon.png') }}" alt="">
                                            </div>
                                            <div>Deposit to Time Deposit</div>
                                        </button>
                                    @else
                                        <button type="button" data-bs-toggle="modal" data-bs-target="#openTdModal">
                                            <div class="card-icon">
                                                <i class="fa-solid fa-bullseye" style="color:#fff;font-size:14px;"></i>
                                            </div>
                                            <div>Open Time Deposit</div>
                                        </button>
                                    @endif
                                </div>
                            </div>
                            <div class="right-card">
                                <div class="parent-right-card">
                                    <div class="right-box-card time-deposit-amount">
                                        <div class="tag">Remaining to Goal</div>
                                        <p>₱{{ number_format($tdRemaining, 2) }}</p>
                                        <span>{{ $hasActiveTimeDeposit ? ($goalReached ? 'Fully Funded' : 'Remaining') : 'No Goal Set' }}</span>
                                    </div>
                                    <div class="right-box-card interest-parent">
                                        <div class="tag">Interest</div>
                                        <p>{{ $hasActiveTimeDeposit ? number_format($tdRate, 2) : '0.00' }}%</p>
                                        <span>Per Annum</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="parent-sub-progress">
                            <div class="parent-header">
                                <p>Goal Progress</p>
                                <p><strong>{{ $goalProgressPercent }}%</strong> - 100%</p>
                            </div>
                            <div class="parent-progress">
                                <div class="progress" style="width: {{ $goalProgressPercent }}%;"></div>
                            </div>
                        </div>
                    </div>

                    <div class="analytics-parent-card">
                        <div class="analytics-card">
                            <div class="analytics-header">
                                <p>Goal Amount</p>
                                <div class="analytics-icon">
                                    <i class="fa fa-bullseye"></i>
                                </div>
                            </div>
                            <h4>{{ $hasActiveTimeDeposit ? '₱' . number_format($tdGoalAmount, 2) : '—' }}</h4>
                            <span>Target for this Time Deposit</span>
                        </div>

                        <div class="analytics-card">
                            <div class="analytics-header">
                                <p>Interest</p>
                                <div class="analytics-icon">
                                    <i class="fa fa-arrow-trend-up"></i>
                                </div>
                            </div>
                            <h4>₱{{ number_format($interestEarnedSoFar, 2) }}</h4>
                            <span>Interest earned so far</span>
                        </div>

                        <div class="analytics-card">
                            <div class="analytics-header">
                                <p>Maturity</p>
                                <div class="analytics-icon">
                                    <i class="fa fa-arrow-trend-up"></i>
                                </div>
                            </div>
                            <h4>₱{{ number_format($projectedMaturityValue, 2) }}</h4>
                            <span>Projected value at maturity</span>
                        </div>
                    </div>

                    <div class="notification-mature-track">
                        <div class="body-box notifications">
                            <div class="body-header notifications-header">
                                <div class="notifications-text">
                                    <h3>Transactions Notifications</h3>
                                    <p>Keep track of maturity reminders.</p>
                                </div>
                                <div class="notifications-view">
                                    <button>View All</button>
                                </div>
                            </div>
                            <div class="body-body body-notifications"
                                style="align-items:stretch; justify-content:flex-start;">
                                @forelse ($notifications as $note)
                                    <div class="notif-item">
                                        <div class="notif-icon notif-{{ $note['color'] }}">
                                            <i class="fa-solid {{ $note['icon'] }}"></i>
                                        </div>
                                        <div class="notif-text">
                                            <h6>{{ $note['title'] }}</h6>
                                            <p>{{ $note['message'] }}</p>
                                            @if ($note['time'])
                                                <span class="notif-time">{{ $note['time'] }}</span>
                                            @endif
                                        </div>
                                    </div>
                                @empty
                                    <div style="text-align:center; padding:2rem 0; width:100%;">
                                        <i class="fa-solid fa-bell-slash fa-2x" style="color:var(--muted); opacity:.4;"></i>
                                        <p style="color:var(--muted); margin-top:0.75rem; font-size:13.5px;">No
                                            notifications yet.</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                        <div class="body-box mature-track">
                            <div class="body-body">
                                <div class="percent-parent">
                                    <p>{{ $daysToGo }}</p>
                                    <span>Days to go</span>
                                </div>

                                @if ($tdMatured)
                                    <h5>Matured</h5>
                                    <p>Your Time Deposit has matured. Claim your balance + interest above to move it back to
                                        Regular Savings.</p>
                                @elseif ($hasActiveTimeDeposit && $goalReached)
                                    <h5>Goal reached</h5>
                                    <p>You've fully funded your goal. It's now earning interest until maturity.</p>
                                @elseif ($hasActiveTimeDeposit)
                                    <h5>On track to your goal</h5>
                                    <p>Keep depositing to reach your ₱{{ number_format($tdGoalAmount, 2) }} goal before
                                        maturity.</p>
                                @else
                                    <h5>No active Time Deposit</h5>
                                    <p>Open a Time Deposit above to set a goal and start earning a higher fixed rate.</p>
                                @endif

                                <div class="maturity-card">
                                    <div class="maturity-left">
                                        <span>Maturity Date</span>
                                        <strong>{{ $tdMaturityDate ? $tdMaturityDate->format('M d, Y') : '—' }}</strong>
                                    </div>
                                    <div class="maturity-right">
                                        <i class="fa fa-calendar-days"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="main-footer">
                    <div class="card-box-parent">
                        <div class="card-box-title">
                            <h3>Transaction History</h3>
                            <p>All goal deposits and releases for this Time Deposit</p>
                        </div>
                        <form method="GET" action="{{ route('TimeDeposit') }}" class="sm-tx-toolbar"
                            id="td-tx-filter-form">
                            <div class="sm-search-box">
                                <i class="fa-solid fa-magnifying-glass"></i>
                                <input type="text" name="td_ref" value="{{ $tdRef }}"
                                    placeholder="Search by reference no.">
                            </div>
                            <input type="date" class="sm-filter-select" name="td_date" value="{{ $tdDate }}"
                                onchange="document.getElementById('td-tx-filter-form').submit()">

                            <select name="td_status" class="sm-filter-select" onchange="this.form.submit()">
                                <option value="all" {{ $tdStatus === 'all' ? 'selected' : '' }}>All Status</option>
                                @foreach($availableTdStatuses as $s)
                                    <option value="{{ strtolower($s) }}" {{ $tdStatus === strtolower($s) ? 'selected' : '' }}>
                                        {{ $s }}</option>
                                @endforeach
                            </select>

                            @if($tdRef !== '' || $tdDate !== '' || $tdStatus !== 'all')
                                <a href="{{ route('TimeDeposit') }}" class="sm-filter-clear">Clear filters</a>
                            @endif
                        </form>

                        <div class="card-box">
                            <div class="overflow-x-auto">
                                <table class="table table-scroll m-0">
                                    <thead>
                                        <tr>
                                            <th class="text-start">Type</th>
                                            <th class="text-start">Reference No.</th>
                                            <th class="text-start">Date</th>
                                            <th class="text-start">Amount</th>
                                            <th class="text-start">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($tdTransactions as $tx)
                                            <tr>
                                                <td class="text-start">
                                                    @if ($tx->type === 'td_open')
                                                        <div class="td-type td-lock"><i class="fa-solid fa-bullseye"></i> Opened
                                                        </div>
                                                    @elseif ($tx->type === 'td_lock')
                                                        <div class="td-type td-lock"><i class="fa-solid fa-piggy-bank"></i>
                                                            Deposit</div>
                                                    @else
                                                        <div class="td-type td-release"><i
                                                                class="fa-solid fa-hand-holding-dollar"></i> Released</div>
                                                    @endif
                                                </td>
                                                <td class="text-start">
                                                    <span class="tx-ref">{{ $tx->reference_no }}</span>
                                                </td>
                                                <td class="text-start">
                                                    {{ \Carbon\Carbon::parse($tx->transaction_date)->format('m/d/Y') }}
                                                </td>
                                                <td class="text-start"
                                                    style="font-weight:700; color:{{ $tx->type === 'td_lock' ? 'var(--muted)' : 'var(--green)' }}">
                                                    ₱ {{ number_format($tx->amount, 2) }}
                                                </td>
                                                <td>
                                                    @if ($tx->status === 'locked')
                                                        <span class="status locked">Locked</span>
                                                    @else
                                                        <span
                                                            class="status approved">{{ ucfirst($tx->status ?? 'completed') }}</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="text-center py-5">
                                                    <i class="fa-solid fa-folder-open fa-2x mb-3"
                                                        style="color:var(--muted);"></i>
                                                    <p style="color:var(--muted);margin-top:0.5rem;">No Time Deposit
                                                        transactions yet.</p>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            @if ($tdTransactions->total() > 0)
                                <div class="sm-pagination-wrap">
                                    <div class="sm-pagination-info">
                                        Showing <b>{{ $tdTransactions->lastItem() }}</b> of
                                        <b>{{ $tdTransactions->total() }}</b> transactions
                                    </div>

                                    @if ($tdTransactions->hasPages())
                                        <div class="sm-pagination">
                                            @if ($tdTransactions->onFirstPage())
                                                <span class="sm-page-btn disabled"><i class="fa-solid fa-chevron-left"></i></span>
                                            @else
                                                <a href="{{ $tdTransactions->previousPageUrl() }}" class="sm-page-btn">
                                                    <i class="fa-solid fa-chevron-left"></i>
                                                </a>
                                            @endif

                                            @for ($i = 1; $i <= $tdTransactions->lastPage(); $i++)
                                                <a href="{{ $tdTransactions->url($i) }}"
                                                    class="sm-page-btn {{ $i == $tdTransactions->currentPage() ? 'active' : '' }}">
                                                    {{ $i }}
                                                </a>
                                            @endfor

                                            @if ($tdTransactions->hasMorePages())
                                                <a href="{{ $tdTransactions->nextPageUrl() }}" class="sm-page-btn">
                                                    <i class="fa-solid fa-chevron-right"></i>
                                                </a>
                                            @else
                                                <span class="sm-page-btn disabled"><i class="fa-solid fa-chevron-right"></i></span>
                                            @endif
                                        </div>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ============================================================
    OPEN TIME DEPOSIT MODAL — sets the GOAL, no funds move yet
    ============================================================ --}}
    <div class="modal fade" id="openTdModal" tabindex="-1" aria-labelledby="openTdModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content sm-modal-content">

                <div class="modal-header sm-modal-header" style="padding: 24px 20px;">
                    <div class="modal-text">
                        <div class="sm-modal-icon sm-deposit-icon">
                            <i class="fa-solid fa-bullseye"></i>
                        </div>
                        <div class="sm-modal-text">
                            <h1 class="modal-title sm-modal-title" id="openTdModalLabel">Open Time Deposit</h1>
                            <p class="sm-modal-subtitle">Set your savings goal</p>
                        </div>
                    </div>
                    <button type="button" class="sm-modal-close" data-bs-dismiss="modal" aria-label="Close">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>

                <form action="{{ route('savings.openTimeDeposit') }}" method="POST">
                    @csrf

                    <div class="modal-body sm-modal-body">
                        <!-- <div class="sm-balance-pill">
                            <span class="sm-pill-label">Available Regular Savings</span>
                            <span class="sm-pill-value">₱ {{ number_format($regularSavingsBalance, 2) }}</span>
                        </div> -->

                        <div class="sm-form-group">
                            <label class="sm-form-label" for="tdTerm">Term</label>
                            <select class="sm-form-select form-select" style="background: transparent; height: 46px; font-size: 14px;" name="term_months" id="tdTerm" required disabled>
                                <option value="12" selected>12 months · 4.00% p.a.</option>
                            </select>
                            <input type="hidden" name="term_months" value="12">
                            <p style="font-size:0.75rem;color:var(--muted);margin:0.4rem 0;">Time Deposit is a fixed
                                12-month (1 year) term at 4.00% per annum.</p>
                        </div>

                        <div class="sm-form-group">
                            <label class="sm-form-label" for="tdAmount">Goal Amount</label>
                            <div class="sm-amount-wrap">
                                <span class="sm-amount-prefix">₱</span>
                                <!-- Deposit to Time Deposit modal -->
                                <input class="sm-form-input @error('td_amount') sm-input-error @enderror" type="number"
                                    id="tdDepositAmount" name="amount" placeholder="0.00" required min="1000"
                                    step="0.01" autocomplete="off" value="{{ old('amount') }}" />
                            </div>
                            <p style="font-size:0.75rem;color:var(--muted);margin:0.4rem 0;">
                                Minimum ₱1,000.00. This is your target — you'll deposit toward it over the 12-month
                                term.
                            </p>
                            @error('amount')
                                <div class="sm-error-msg show">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="modal-footer sm-modal-footer">
                        <button type="submit" class="sm-btn-confirm sm-deposit-confirm">
                            <i class="fa-solid fa-bullseye"></i> Set Goal
                        </button>
                        <button type="button" class="sm-btn-cancel" data-bs-dismiss="modal">Cancel</button>
                    </div>
                </form>

            </div>
        </div>
    </div>

    {{-- ============================================================
    DEPOSIT TO TIME DEPOSIT MODAL — funds a goal that's already active
    ============================================================ --}}
    <div class="modal fade" id="depositTdModal" tabindex="-1" aria-labelledby="depositTdModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content sm-modal-content">

                <div class="modal-header sm-modal-header" style="padding: 24px 20px;">
                    <div class="modal-text">
                        <div class="sm-modal-icon sm-deposit-icon">
                            <img src="{{ asset('images/arrow-icon.png') }}" alt="">
                        </div>
                        <div class="sm-modal-text">
                            <h1 class="modal-title sm-modal-title" id="depositTdModalLabel">Deposit to Time Deposit</h1>
                            <p class="sm-modal-subtitle">Move funds from Regular Savings toward your goal</p>
                        </div>
                    </div>
                    <button type="button" class="sm-modal-close" data-bs-dismiss="modal" aria-label="Close">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>

                <form action="{{ route('savings.depositTimeDeposit') }}" method="POST">
                    @csrf

                    <div class="modal-body sm-modal-body">
                        <!-- <div class="sm-balance-pill">
                            <span class="sm-pill-label">Time Deposit Balance</span>
                            <span class="sm-pill-value">₱ {{ number_format($tdBalance, 2) }}</span>
                        </div>

                        <div class="sm-balance-pill" style="margin-top: -0.5rem;">
                            <span class="sm-pill-label">Goal Amount</span>
                            <span class="sm-pill-value">₱ {{ number_format($tdGoalAmount, 2) }}</span>
                        </div> -->

                        <div class="sm-balance-pill">
                            <span class="sm-pill-label">Remaining to Goal</span>
                            <span class="sm-pill-value">₱ {{ number_format($tdRemaining, 2) }}</span>
                        </div>

                        <div class="sm-form-group">
                            <label class="sm-form-label label-time-deposit" for="tdDepositAmount">Amount to
                                Deposit</label>
                            <div class="sm-amount-wrap">
                                <span class="sm-amount-prefix">₱</span>
                                <input class="sm-form-input @error('td_amount') sm-input-error @enderror" type="number"
                                    id="tdDepositAmount" name="amount" placeholder="0.00" required min="1"
                                    max="{{ $tdRemaining }}" step="0.01" value="{{ old('amount') }}" />
                            </div>
                            <div class="sm-quick-amounts">
                                <button type="button" class="sm-quick-btn" onclick="setTdAmount(500)">₱500</button>
                                <button type="button" class="sm-quick-btn" onclick="setTdAmount(1000)">₱1,000</button>
                                <button type="button" class="sm-quick-btn" onclick="setTdAmount(2000)">₱2,000</button>
                                <button type="button" class="sm-quick-btn"
                                    onclick="setTdAmount({{ $tdRemaining }})">Remaining</button>
                            </div>
                            @error('td_amount')
                                <div class="sm-error-msg show">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="modal-footer sm-modal-footer">
                        <button type="submit" class="sm-btn-confirm sm-deposit-confirm">
                            <i class="fa-solid fa-circle-arrow-down"></i> Confirm Deposit
                        </button>
                        <button type="button" class="sm-btn-cancel" data-bs-dismiss="modal">Cancel</button>
                    </div>
                </form>

            </div>
        </div>
    </div>

    {{-- ============================================================
    SUCCESS MODAL — Goal Opened
    ============================================================ --}}
    <div class="modal fade" id="tdOpenSuccessModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content sm-modal-content">
                <div class="modal-body sm-success-body">
                    <div class="sm-success-icon sm-success-green">
                        <i class="fa-solid fa-check"></i>
                    </div>
                    <h5 class="sm-success-title">Time Deposit Goal Set!</h5>
                    <p class="sm-success-msg">
                        Your goal of
                        <strong>₱{{ session('td_goal') ? number_format(session('td_goal'), 2) : '0.00' }}</strong>
                        is now active, maturing <strong>{{ session('td_maturity') }}</strong>.
                        Deposit toward it anytime before then.
                    </p>
                    @if (session('td_reference'))
                        <div class="sm-ref-pill">
                            <span class="sm-ref-label">Reference No.</span>
                            <span class="sm-ref-value" id="tdopen-ref-no">{{ session('td_reference') }}</span>
                            <button class="sm-copy-btn" onclick="copyRef('tdopen-ref-no')" title="Copy">
                                <i class="fa-regular fa-copy"></i>
                            </button>
                        </div>
                    @endif
                    <button type="button" class="sm-btn-confirm sm-deposit-confirm w-100 mt-3" data-bs-dismiss="modal">
                        <i class="fa-solid fa-check"></i> Done
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- ============================================================
    SUCCESS MODAL — Deposit to Time Deposit
    ============================================================ --}}
    <div class="modal fade" id="tdDepositSuccessModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content sm-modal-content">
                <div class="modal-body sm-success-body">
                    <div class="sm-success-icon sm-success-green">
                        <i class="fa-solid fa-check"></i>
                    </div>
                    <h5 class="sm-success-title">Deposit Successful!</h5>
                    <p class="sm-success-msg">
                        <strong>₱{{ session('td_deposit_amount') ? number_format(session('td_deposit_amount'), 2) : '0.00' }}</strong>
                        has been added to your Time Deposit.
                    </p>
                    @if (session('td_deposit_reference'))
                        <div class="sm-ref-pill">
                            <span class="sm-ref-label">Reference No.</span>
                            <span class="sm-ref-value" id="tddeposit-ref-no">{{ session('td_deposit_reference') }}</span>
                            <button class="sm-copy-btn" onclick="copyRef('tddeposit-ref-no')" title="Copy">
                                <i class="fa-regular fa-copy"></i>
                            </button>
                        </div>
                    @endif
                    <div class="sm-success-balance-pill">
                        <span>New Time Deposit Balance</span>
                        <span>₱
                            {{ session('td_new_balance') ? number_format(session('td_new_balance'), 2) : number_format($tdBalance, 2) }}</span>
                    </div>
                    <button type="button" class="sm-btn-confirm sm-deposit-confirm w-100 mt-3" data-bs-dismiss="modal">
                        <i class="fa-solid fa-check"></i> Done
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- ============================================================
    SUCCESS MODAL — Claim Time Deposit
    ============================================================ --}}
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
                        <span>₱ {{ number_format($regularSavingsBalance, 2) }}</span>
                    </div>
                    <button type="button" class="sm-btn-confirm sm-deposit-confirm w-100 mt-3" data-bs-dismiss="modal">
                        <i class="fa-solid fa-check"></i> Done
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Hidden trigger buttons --}}
    <button id="triggerOpenTdModal" data-bs-toggle="modal" data-bs-target="#openTdModal" style="display:none;"></button>
    <button id="triggerDepositTdModal" data-bs-toggle="modal" data-bs-target="#depositTdModal"
        style="display:none;"></button>
    <button id="triggerTdOpenSuccess" data-bs-toggle="modal" data-bs-target="#tdOpenSuccessModal"
        style="display:none;"></button>
    <button id="triggerTdDepositSuccess" data-bs-toggle="modal" data-bs-target="#tdDepositSuccessModal"
        style="display:none;"></button>
    <button id="triggerTdClaimSuccess" data-bs-toggle="modal" data-bs-target="#tdClaimSuccessModal"
        style="display:none;"></button>

    <script>
        function copyRef(elementId) {
            const text = document.getElementById(elementId).textContent.trim();
            navigator.clipboard.writeText(text).then(() => {
                const btn = event.currentTarget;
                btn.innerHTML = '<i class="fa-solid fa-check"></i>';
                setTimeout(() => { btn.innerHTML = '<i class="fa-regular fa-copy"></i>'; }, 1500);
            });
        }

        function setTdAmount(val) {
            document.getElementById('tdDepositAmount').value = val;
        }

        // ★ NEW: clear both TD forms whenever their modal is closed
        ['openTdModal', 'depositTdModal'].forEach(function (id) {
            const modalEl = document.getElementById(id);
            if (!modalEl) return;
            modalEl.addEventListener('hidden.bs.modal', function () {
                const form = modalEl.querySelector('form');
                if (form) form.reset();
                // also clear any leftover error styling
                form?.querySelectorAll('.sm-input-error').forEach(el => el.classList.remove('sm-input-error'));
            });
        });

        window.addEventListener('DOMContentLoaded', function () {

            @if ($errors->any() && $errors->has('amount'))
                document.getElementById('triggerOpenTdModal').click();
            @endif

            @if ($errors->any() && $errors->has('td_amount'))
                document.getElementById('triggerDepositTdModal').click();
            @endif

            @if (session('td_success'))
                document.getElementById('triggerTdOpenSuccess').click();
            @endif

            @if (session('td_deposit_success'))
                document.getElementById('triggerTdDepositSuccess').click();
                // ★ NEW: make sure the amount field is empty behind the success modal
                document.getElementById('tdDepositAmount').value = '';
            @endif

            @if (session('td_claim_success'))
                document.getElementById('triggerTdClaimSuccess').click();
            @endif

        });
    </script>

</body>

</html>