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
</head>

<body>

    <div class="container-fluid p-0 m-0">
        @include("components.navbar2")
        @include("components.offcanvas")

        <main>
            @if ($username)
                <div class="main-header">
                    <div class="main-intro">
                        <p class="">Member Cooperative Portal</p>
                        <h3 class="">Hello Welcome, {{ $username }}! 👋</h3>
                        <p>Here's a summary of your cooperative account as of today.</p>
                    </div>
                </div>
            @endif

            <div class="line"></div>

            <div class="card-parent">
                {{-- Savings Balance --}}
                <div class="card-box" onclick="window.location='{{ route('savings.index') }}'">
                    <div class="card-transparent">
                        <div class="card-head"></div>
                        <div class="card-icon mt-2 d-flex justify-content-center align-items-center"
                            style="border-radius: 10px">
                            <i class="fa-solid fa-wallet"></i>
                        </div>
                        <p class="mt-4">Savings Balance</p>
                        <span>₱ {{ number_format($savingsAccount->balance ?? 0, 2) }}</span>
                    </div>
                    <div class="card-update">
                        <div class="update">
                            <i class="fa fa-arrow-up"></i>
                            <p>Active</p>
                        </div>
                    </div>
                </div>

                {{-- Active Loans --}}
                <div class="card-box" onclick="window.location='{{ route('LoanStatus') }}'">
                    <div class="card-transparent">
                        <div class="card-head"></div>
                        <div class="card-icon mt-2 d-flex justify-content-center align-items-center"
                            style="border-radius: 10px">
                            <i class="fa-solid fa-arrow-trend-up"></i>
                        </div>
                        <p class="mt-4">Active Loans</p>
                        <span>{{ $activeLoansCount }} Loan(s)</span>
                    </div>
                    <div class="card-update">
                        <div class="update">
                            <i class="fa fa-arrow-up"></i>
                            <p>Active</p>
                        </div>
                    </div>
                </div>

                {{-- Overdue Loans / Late Fees --}}
                <div class="card-box" onclick="window.location='{{ route('LoanStatus') }}'">
                    <div class="card-transparent">
                        <div class="card-head"></div>
                        <div class="card-icon mt-2 d-flex justify-content-center align-items-center" style="border-radius: 10px">
                            <i class="fa-solid fa-exclamation-triangle" style="color: {{ $overdueCount > 0 ? '#dc2626' : '#22c55e' }}"></i>
                        </div>
                        <p class="mt-4">Overdue Loans</p>
                        <span>{{ $overdueCount }} Loan(s)</span>
                    </div>
                    <div class="card-update">
                        <div class="update">
                            @if($overdueCount > 0)
                                <p style="color: #dc2626;">Total: ₱{{ number_format($totalLateFees, 2) }}</p>
                            @else
                                <p style="color: #22c55e;">No overdue</p>
                            @endif
                        </div>
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
                    <a href="{{ route("LoanApplication") }}">
                        <i class="fa fa-plus"></i>
                        <span>Apply for a Loan</span>
                    </a>
                </div>
            </div>

            <div class="card-box-parent">
                {{-- Loan Applications --}}
                <div class="loan-application">
                    <div class="loan-head">
                        <div>
                            <h4>Loan Application</h4>
                            <p>Track all your lending applications</p>
                        </div>
                        <div>
                            <a href="#">View all <i class="fa fa-arrow-right"></i></a>
                        </div>
                    </div>

                    <div class="buttons-parent">
                        <button class="tab-btn active" onclick="filterLoans('all')">
                            <span>All</span>
                            <p>{{ $loans->count() }}</p>
                        </button>
                        <button class="tab-btn" onclick="filterLoans('Approved')">
                            <span>Approved</span>
                            <p>{{ $loans->where('status', 'Approved')->count() }}</p>
                        </button>
                        <button class="tab-btn" onclick="filterLoans('Pending')">
                            <span>Pending</span>
                            <p>{{ $loans->where('status', 'Pending')->count() }}</p>
                        </button>
                        <button class="tab-btn" onclick="filterLoans('Rejected')">
                            <span>Rejected</span>
                            <p>{{ $loans->whereIn('status', ['Rejected', 'Declined'])->count() }}</p>
                        </button>
                    </div>

                    @if ($loans->isNotEmpty())
                        <div class="parent-loan">
                            @foreach ($loans as $loan)
                                @php
                                    $lendingStatus = \App\Models\lending_status_tbl::where('lending_id', $loan->id)->first();
                                    $loanAmount = $loan->lending_amount ?? 0;
                                    $remainingBalance = $lendingStatus->remaining_balance ?? $loanAmount;
                                    $monthlyPayment = $loan->monthly_payment ?? 0;
                                    $paid = $loanAmount > 0
                                        ? round((($loanAmount - $remainingBalance) / $loanAmount) * 100)
                                        : 0;

                                    $statusGroup = in_array($loan->status, ['Rejected', 'Declined']) ? 'Rejected' : $loan->status;

                                    $penaltyInfo = collect($penalizedLoans ?? [])->firstWhere('id', $loan->id);
                                @endphp
                                <div class="loan-box" data-status="{{ $statusGroup }}">
                                    <div class="box-head">
                                        <div class="box-text">
                                            <h5>{{ $loan->lending_type }}</h5>
                                            <p>Applied on {{ \Carbon\Carbon::parse($loan->created_at)->format('F d, Y') }}</p>
                                        </div>
                                        <div class="box-icon">
                                            @if($penaltyInfo)
                                                <div class="dot" style="background: #dc2626;"></div>
                                                <span style="color: #dc2626;">Overdue</span>
                                            @else
                                                <div class="dot"></div>
                                                <span>{{ $loan->status }}</span>
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
                                        <div class="parent-box" style="margin-top: 10px;">
                                            <div class="box remaining-balance" style="border: 1px solid #dc2626; background: #fef2f2;">
                                                <p style="color: #dc2626;">Late Fee ({{ $penaltyInfo['months_overdue'] }} month(s) overdue)</p>
                                                <h5 style="color: #dc2626;">₱{{ number_format($penaltyInfo['late_fee'], 2) }}</h5>
                                            </div>
                                        </div>
                                        @endif

                                        <div class="parent-progress">
                                            <div class="progress-head">
                                                <p>Repayment Progress</p>
                                                <span>{{ $paid }}% Paid</span>
                                            </div>
                                            <div class="progress-body">
                                                <div class="progress" style="width: {{ $paid }}%"></div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="box-footer">
                                        <div class="box-approved">
                                            @if ($loan->status === 'Approved')
                                                <i class="fa fa-check"></i>
                                                <p>Approved on {{ \Carbon\Carbon::parse($loan->updated_at)->format('F d, Y') }}</p>
                                            @elseif ($loan->status === 'Pending')
                                                <i class="fa fa-hourglass"></i>
                                                <p>Awaiting admin review</p>
                                            @else
                                                <i class="fa fa-xmark"></i>
                                                <p>Rejected on {{ \Carbon\Carbon::parse($loan->updated_at)->format('F d, Y') }}</p>
                                            @endif
                                        </div>

                                        <div class="box-link">
                                            @if ($loan->status === 'Rejected' || $loan->status === 'Declined')
                                                <a href="#">View Reason</a>
                                                <a href="#">Re-apply <i class="fa fa-arrow-right"></i></a>
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
                            <h3>No Loan Transaction yet</h3>
                        </div>
                    @endif
                </div>

                {{-- Right Sidebar --}}
                <div class="member-status">

                    {{-- Member Account --}}
                    <div class="member-account">
                        <div class="member-head">
                            <div class="icon">
                                <p>{{ strtoupper(substr($username, 0, 1)) }}</p>
                            </div>
                            <div class="text">
                                <h4>
                                    {{ $firstName }}
                                    {{ $middleName ? strtoupper(substr($middleName, 0, 1)) . '. ' : '' }}
                                    {{ $lastName }}
                                </h4>
                                <p>Member ID: {{ $member->member_id ?? 'N/A' }}</p>
                            </div>
                        </div>

                        <div class="member-body">
                            <div class="body-text">
                                <p>Membership:</p>
                                @if ($member->approval_status === "Pending")

                                    <span style="color: var(--gold)">{{ $member->approval_status ?? 'N/A' }}</span>

                                @elseif($member->approval_status === "Declined")

                                    <span style="color: #DC2626">{{ $member->approval_status ?? 'N/A' }}</span>

                                @else

                                    <span class="color: var(--green)">{{ $member->approval_status ?? 'N/A' }}</span>

                                @endif
                            </div>
                            <div class="body-text">
                                <p>Member Since:</p>
                                <span>{{ $member ? \Carbon\Carbon::parse($member->created_at)->format('F Y') : 'N/A' }}</span>
                            </div>
                            <div class="body-text">
                                <p>Status:</p>
                                <div class="membership_status">
                                    @if ($member->membership_status === "Unofficial")

                                        <div class="dot" style="width: 8px; height: 8px; background-color: var(--gold); border-radius: 50%"></div>
                                        <span style="color: var(--gold)">{{ $member->membership_status ?? 'N/A' }}</span>

                                    @elseif($member->membership_status === "Not Active")

                                        <div class="dot" style="width: 8px; height: 8px; background-color: #DC2626; border-radius: 50%"></div>
                                        <span style="color: #DC2626">{{ $member->membership_status ?? 'N/A' }}</span>

                                    @else

                                        <div class="dot" style="width: 8px; height: 8px; background-color: var(--green); border-radius: 50%"></div>
                                        <span style="color: (--green)">{{ $member->membership_status ?? 'N/A' }}</span>

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
                                <a href="{{ route('savings.index') }}">Deposit Savings</a>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </section>

        <script>
            function filterLoans(status) {
                // Update active button
                document.querySelectorAll('.tab-btn').forEach(btn => btn.classList.remove('active'));
                event.currentTarget.classList.add('active');

                // Show/hide loan boxes
                document.querySelectorAll('.loan-box').forEach(box => {
                    if (status === 'all' || box.dataset.status === status) {
                        box.style.display = 'block';
                    } else {
                        box.style.display = 'none';
                    }
                });
            }
        </script>

        @if (session("message"))
            <div class="message">
                <i class="fa fa-check-circle"></i>
                <div>
                    <p>{{ session("message") }}</p>
                </div>
            </div>

            <script>
                setTimeout(() => {
                    const msg = document.querySelector(".message");
                    if (msg) {
                        msg.classList.add("hide");
                        msg.addEventListener("animationend", () => msg.remove());
                    }
                }, 3000);
            </script>
        @endif
    </div>

    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>

</body>

</html>