<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Financial</title>

    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

    <link rel="stylesheet" href="css_folder/financial.css">
    <link rel="stylesheet" href="css_folder/loading.css">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link rel="stylesheet" href="font-awesome-icon/css/all.min.css">

    <style>
        .fin-tabs {
            display: flex;
            gap: 6px;
            /* background: #F1F3F5; */
            background-color: #ffffff;
            border: 1px solid var(--border);
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.06), 0 1px 2px rgba(0, 0, 0, 0.04);
            padding: 4px;
            border-radius: 10px;
            width: fit-content;
            margin-bottom: 1.5rem;
        }

        .fin-tab {
            padding: 8px 18px;
            border-radius: 7px;
            font-size: 13.5px;
            font-weight: 600;
            color: var(--muted, #6b7b74);
            text-decoration: none;
            transition: all 0.15s;
        }

        .fin-tab:hover {
            color: #1a1a1a;
        }

        .fin-tab.active {
            /* background: #fff; */
            background-color: var(--teal);
            /* color: #1a1a1a; */
            color: #ffffff;
            /* box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08); */
        }
    </style>
</head>

<body>

    <div class="container-fluid p-0 m-0">

        @include("components.offcanvas")
        @include("components.sidebar")

        <div class="rightbar">
            @include("components.navbar2")

            <div class="main-parent">
                <div class="main-header"
                    style="display:flex; justify-content:space-between; align-items:flex-start; flex-wrap:wrap; gap:1rem;">
                    <div>
                        <h3>Financial — Dividends &amp; Patronage Refund</h3>
                        <p>View your dividend earnings and patronage refunds by year.</p>
                    </div>
                    <form method="GET" action="{{ route('Financial') }}">
                        <input type="hidden" name="tab" value="{{ $activeTab }}">
                        <select name="year" class="fin-year-select" onchange="this.form.submit()">
                            @foreach($availableYears as $y)
                                <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                            @endforeach
                        </select>
                    </form>
                </div>

                {{-- ═══ Lifetime summary cards (always visible, above tabs) ═══ --}}
                <div class="fin-grid">
                    <div class="fin-card">
                        <p class="fin-label">Lifetime Dividends Received</p>
                        <p class="fin-amount">₱{{ number_format($lifetimeDividends, 2) }}</p>
                        <p class="fin-sub">All disbursed dividends</p>
                    </div>
                    <div class="fin-card">
                        <p class="fin-label">Lifetime Patronage Refunds</p>
                        <p class="fin-amount">₱{{ number_format($lifetimePatronage, 2) }}</p>
                        <p class="fin-sub">All disbursed patronage refunds</p>
                    </div>
                    <div class="fin-card">
                        <p class="fin-label">Dividends — {{ $year }}</p>
                        <p class="fin-amount">₱{{ number_format($totalDividendsApproved, 2) }}</p>
                        <p class="fin-sub">₱{{ number_format($totalDividendsDisbursed, 2) }} disbursed</p>
                    </div>
                    <div class="fin-card">
                        <p class="fin-label">Patronage Refunds — {{ $year }}</p>
                        <p class="fin-amount">₱{{ number_format($totalPatronageApproved, 2) }}</p>
                        <p class="fin-sub">₱{{ number_format($totalPatronageDisbursed, 2) }} disbursed</p>
                    </div>
                </div>

                {{-- ═══ Tabs ═══ --}}
                <div class="fin-tabs">
                    <a href="{{ route('Financial', ['tab' => 'dividends', 'year' => $year]) }}"
                        class="fin-tab {{ $activeTab === 'dividends' ? 'active' : '' }}">Dividends</a>
                    <a href="{{ route('Financial', ['tab' => 'patronage', 'year' => $year]) }}"
                        class="fin-tab {{ $activeTab === 'patronage' ? 'active' : '' }}">Patronage Refund</a>
                    <a href="{{ route('Financial', ['tab' => 'records', 'year' => $year]) }}"
                        class="fin-tab {{ $activeTab === 'records' ? 'active' : '' }}">Additional Patronage</a>
                </div>

                {{-- ═══ TAB: Dividends ═══ --}}
                @if ($activeTab === 'dividends')
                    <div class="fin-section">
                        <div class="fin-section-head">

                            <div class="fin-text">
                                <h4>My Dividends — {{ $year }}</h4>
                                <p>Based on your share capital contributions</p>
                            </div>

                            <form method="GET" action="{{ route('Financial') }}" class="fin-filter-bar">
                                <input type="hidden" name="tab" value="dividends">
                                <input type="hidden" name="year" value="{{ $year }}">

                                <input type="text" name="search" value="{{ $search }}" placeholder="Search amount..."
                                    class="fin-search-input" onchange="this.form.submit()">

                                <select name="status" onchange="this.form.submit()">
                                    <option value="all" {{ $statusFilter === 'all' ? 'selected' : '' }}>All Status
                                    </option>
                                    <option value="pending" {{ $statusFilter === 'pending' ? 'selected' : '' }}>Pending
                                    </option>
                                    <option value="approved" {{ $statusFilter === 'approved' ? 'selected' : '' }}>Approved
                                    </option>
                                    <option value="disbursed" {{ $statusFilter === 'disbursed' ? 'selected' : '' }}>
                                        Disbursed</option>
                                </select>

                                <input type="date" name="date" value="{{ $dateFilter }}" onchange="this.form.submit()">

                                @if ($statusFilter !== 'all' || $dateFilter || $search)
                                    <a href="{{ route('Financial', ['tab' => 'dividends', 'year' => $year]) }}"
                                        class="fin-filter-clear">
                                        Clear
                                    </a>
                                @endif
                            </form>

                        </div>
                        <div class="overflow-x-auto">
                            <table class="fin-table">
                                <thead>
                                    <tr>
                                        <th>Share Capital</th>
                                        <th>Recommended</th>
                                        <th>Approved Amount</th>
                                        <th>Status</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($myDividends as $d)
                                        <tr>
                                            <td>₱{{ number_format($d->share_capital_amount, 2) }}</td>
                                            <td>₱{{ number_format($d->recommended_amount, 2) }}</td>
                                            <td style="font-weight:700;">₱{{ number_format($d->approved_amount, 2) }}</td>
                                            <td>
                                                <span class="fin-badge {{ strtolower($d->status) }}">
                                                    {{ ucfirst($d->status) }}
                                                </span>
                                            </td>
                                            <td>{{ $d->updated_at?->format('M d, Y') }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5">
                                                <div class="fin-empty">
                                                    <i class="fa-solid fa-gift"></i>
                                                    No dividend records for {{ $year }} yet.
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        @if ($myDividends->total() > 0)
                            <div class="sm-pagination-wrap">
                                <div class="sm-pagination-info">
                                    Showing <b>{{ $myDividends->firstItem() }}</b> to <b>{{ $myDividends->lastItem() }}</b> of
                                    <b>{{ $myDividends->total() }}</b> records
                                </div>

                                @if ($myDividends->hasPages())
                                    <div class="sm-pagination">
                                        @if ($myDividends->onFirstPage())
                                            <span class="sm-page-btn disabled"><i class="fa-solid fa-chevron-left"></i></span>
                                        @else
                                            <a href="{{ $myDividends->previousPageUrl() }}" class="sm-page-btn">
                                                <i class="fa-solid fa-chevron-left"></i>
                                            </a>
                                        @endif

                                        @for ($i = 1; $i <= $myDividends->lastPage(); $i++)
                                            <a href="{{ $myDividends->url($i) }}"
                                                class="sm-page-btn {{ $i == $myDividends->currentPage() ? 'active' : '' }}">
                                                {{ $i }}
                                            </a>
                                        @endfor

                                        @if ($myDividends->hasMorePages())
                                            <a href="{{ $myDividends->nextPageUrl() }}" class="sm-page-btn">
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
                @endif

                {{-- ═══ TAB: Patronage Refund ═══ --}}
                @if ($activeTab === 'patronage')
                    <div class="fin-section">
                        <div class="fin-section-head">
                            <div class="fin-text">
                                <h4>My Patronage Refunds — {{ $year }}</h4>
                                <p>Based on your loan interest, service fees, and late fees paid</p>
                            </div>

                            <form method="GET" action="{{ route('Financial') }}" class="fin-filter-bar">
                                <input type="hidden" name="tab" value="patronage">
                                <input type="hidden" name="year" value="{{ $year }}">

                                <input type="text" name="search" value="{{ $search }}" placeholder="Search amount..."
                                    class="fin-search-input" onchange="this.form.submit()">

                                <select name="status" onchange="this.form.submit()">
                                    <option value="all" {{ $statusFilter === 'all' ? 'selected' : '' }}>All Status</option>
                                    <option value="pending" {{ $statusFilter === 'pending' ? 'selected' : '' }}>Pending
                                    </option>
                                    <option value="approved" {{ $statusFilter === 'approved' ? 'selected' : '' }}>Approved
                                    </option>
                                    <option value="disbursed" {{ $statusFilter === 'disbursed' ? 'selected' : '' }}>Disbursed
                                    </option>
                                </select>

                                <input type="date" name="date" value="{{ $dateFilter }}" onchange="this.form.submit()">

                                @if ($statusFilter !== 'all' || $dateFilter || $search)
                                    <a href="{{ route('Financial', ['tab' => 'dividends', 'year' => $year]) }}"
                                        class="fin-filter-clear">
                                        Clear
                                    </a>
                                @endif
                            </form>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="fin-table">
                                <thead>
                                    <tr>
                                        <th>Total Patronage</th>
                                        <th>Allocation Ratio</th>
                                        <th>Refund Amount</th>
                                        <th>Status</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($myPatronageRefunds as $p)
                                        <tr>
                                            <td>₱{{ number_format($p->total_patronage, 2) }}</td>
                                            <td>{{ number_format($p->allocation_ratio * 100, 2) }}%</td>
                                            <td style="font-weight:700;">₱{{ number_format($p->amount, 2) }}</td>
                                            <td>
                                                <span class="fin-badge {{ strtolower($p->status) }}">
                                                    {{ ucfirst($p->status) }}
                                                </span>
                                            </td>
                                            <td>{{ $p->updated_at?->format('M d, Y') }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5">
                                                <div class="fin-empty">
                                                    <i class="fa-solid fa-percent"></i>
                                                    No patronage refund records for {{ $year }} yet.
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        @if ($myPatronageRefunds->total() > 0)
                            <div class="sm-pagination-wrap">
                                <div class="sm-pagination-info">
                                    Showing <b>{{ $myPatronageRefunds->firstItem() }}</b> to
                                    <b>{{ $myPatronageRefunds->lastItem() }}</b> of
                                    <b>{{ $myPatronageRefunds->total() }}</b> records
                                </div>

                                @if ($myPatronageRefunds->hasPages())
                                    <div class="sm-pagination">
                                        @if ($myPatronageRefunds->onFirstPage())
                                            <span class="sm-page-btn disabled"><i class="fa-solid fa-chevron-left"></i></span>
                                        @else
                                            <a href="{{ $myPatronageRefunds->previousPageUrl() }}" class="sm-page-btn">
                                                <i class="fa-solid fa-chevron-left"></i>
                                            </a>
                                        @endif

                                        @for ($i = 1; $i <= $myPatronageRefunds->lastPage(); $i++)
                                            <a href="{{ $myPatronageRefunds->url($i) }}"
                                                class="sm-page-btn {{ $i == $myPatronageRefunds->currentPage() ? 'active' : '' }}">
                                                {{ $i }}
                                            </a>
                                        @endfor

                                        @if ($myPatronageRefunds->hasMorePages())
                                            <a href="{{ $myPatronageRefunds->nextPageUrl() }}" class="sm-page-btn">
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
                @endif

                {{-- ═══ TAB: Additional Patronage ═══ --}}
                @if ($activeTab === 'records')
                    <div class="fin-section">
                        <div class="fin-section-head">
                            <div class="fin-text">
                                <h4>Additional Patronage — {{ $year }}</h4>
                                <p>Patronage from services outside the lending system (gas, rice, oil, etc.)</p>
                            </div>

                            <div class="search-parent">
                                <form method="GET" action="{{ route('Financial') }}" class="fin-filter-bar">
                                    <input type="hidden" name="tab" value="records">
                                    <input type="hidden" name="year" value="{{ $year }}">

                                    <input type="text" name="search" value="{{ $search }}"
                                        placeholder="Search source or description..." class="fin-search-input"
                                        onchange="this.form.submit()">

                                    <input type="date" name="date" value="{{ $dateFilter }}" onchange="this.form.submit()">

                                    @if ($dateFilter)
                                        <a href="{{ route('Financial', ['tab' => 'records', 'year' => $year]) }}"
                                            class="fin-filter-clear">
                                            Clear
                                        </a>
                                    @endif
                                </form>

                                <!-- <div style="font-size:13px; font-weight:700; color:#1a1a1a; white-space:nowrap;">
                                    Total: ₱{{ number_format($totalAdditionalPatronage, 2) }}
                                </div> -->
                            </div>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="fin-table">
                                <thead>
                                    <tr>
                                        <th>Source</th>
                                        <th>Description</th>
                                        <th>Amount</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($myPatronageRecords as $r)
                                        <tr>
                                            <td>{{ $r->source }}</td>
                                            <td>{{ $r->description ?: '—' }}</td>
                                            <td style="font-weight:700;">₱{{ number_format($r->amount, 2) }}</td>
                                            <td>{{ $r->created_at?->format('M d, Y') }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4">
                                                <div class="fin-empty">
                                                    <i class="fa-solid fa-box-open"></i>
                                                    No additional patronage records for {{ $year }}.
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        @if ($myPatronageRecords->total() > 0)
                            <div class="sm-pagination-wrap">
                                <div class="sm-pagination-info">
                                    Showing <b>{{ $myPatronageRecords->firstItem() }}</b> to
                                    <b>{{ $myPatronageRecords->lastItem() }}</b> of
                                    <b>{{ $myPatronageRecords->total() }}</b> records
                                </div>

                                @if ($myPatronageRecords->hasPages())
                                    <div class="sm-pagination">
                                        @if ($myPatronageRecords->onFirstPage())
                                            <span class="sm-page-btn disabled"><i class="fa-solid fa-chevron-left"></i></span>
                                        @else
                                            <a href="{{ $myPatronageRecords->previousPageUrl() }}" class="sm-page-btn">
                                                <i class="fa-solid fa-chevron-left"></i>
                                            </a>
                                        @endif

                                        @for ($i = 1; $i <= $myPatronageRecords->lastPage(); $i++)
                                            <a href="{{ $myPatronageRecords->url($i) }}"
                                                class="sm-page-btn {{ $i == $myPatronageRecords->currentPage() ? 'active' : '' }}">
                                                {{ $i }}
                                            </a>
                                        @endfor

                                        @if ($myPatronageRecords->hasMorePages())
                                            <a href="{{ $myPatronageRecords->nextPageUrl() }}" class="sm-page-btn">
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
                @endif

            </div>
        </div>

    </div>

</body>

</html>