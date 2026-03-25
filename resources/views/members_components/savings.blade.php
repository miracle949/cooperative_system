<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Savings</title>
    <link rel="icon" href="images/websitelogo.png" type="image/png">

    {{-- AOS animation link css --}}
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

    {{-- css link --}}
    <link rel="stylesheet" href="css_folder/savings.css">
    <link rel="stylesheet" href="css_folder/savings_modal.css">
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
            {{-- <div class="main-intro">
                <p class="">Member Savings</p>
                <h3 class="">My Savings</h3>
                <span>Manage your share capital contributions and track your dividends</span>
            </div> --}}

            <div class="card-box-parent">
                <div class="card-box-text">
                    <h3>Total Savings Balance</h3>
                    <h2 class="mt-3 mb-3">₱ {{ number_format($savingsAccount->balance, 2) }}</h2>
                    {{-- ✅ fixed --}}
                    <span>Last updated {{ $lastUpdated }} · 
                        {{ $monthsActive == 0 ? 'Less than a month' : $monthsActive . ' ' . ($monthsActive == 1 ? 'month' : 'months') }} active
                    </span>
                </div>

                <div class="card-box-buttons d-flex justify-content-left align-items-center flex-wrap gap-4">

                    {{-- Deposit --}}
                    <div class="card-box" data-bs-toggle="modal" data-bs-target="#depositModal" style="cursor:pointer;">
                        <div class="card-icon">
                            <i class="fa-solid fa-circle-arrow-down"></i>
                        </div>
                        <div><p>Deposit</p></div>
                    </div>

                    {{-- Withdraw --}}
                    <div class="card-box" data-bs-toggle="modal" data-bs-target="#withdrawModal" style="cursor:pointer;">
                        <div class="card-icon">
                            <i class="fa-solid fa-circle-arrow-up"></i>
                        </div>
                        <div><p>Withdraw</p></div>
                    </div>

                </div>
            </div>
        </main>

        <section>
            <div class="d-flex justify-content-between align-items-center card-box-parent flex-wrap">
                <div class="card-box tw:bg-white">
                    <div class="card-accent"></div>
                    <div class="card-icon d-flex justify-content-center align-items-center">
                        <i class="fa-solid fa-peso-sign"></i>
                    </div>
                    <p>Total Savings</p>
                    <h4>₱ {{ number_format($savingsAccount->balance, 2) }}</h4>
                    <span>All time contributions</span>
                </div>

                <div class="card-box tw:bg-white">
                    <div class="card-accent"></div>
                    <div class="card-icon d-flex justify-content-center align-items-center">
                        <i class="fa-solid fa-arrow-trend-up"></i>
                    </div>
                    <p>Monthly Average</p>
                    <h4>₱ {{ number_format($monthlyAverage, 2) }}</h4>
                    <span>Per month average</span>
                </div>

                <div class="card-box tw:bg-white">
                    <div class="card-accent"></div>
                    <div class="card-icon d-flex justify-content-center align-items-center">
                        <i class="fa-solid fa-calendar-days"></i>
                    </div>
                    <p>Total Months</p>
                    <h4>{{ $totalMonths }} Months</h4>
                    <span>Months saving</span>
                </div>
            </div>
        </section>

        <section id="section2">
            <div class="card-box-parent">
                <div class="d-flex justify-content-between align-items-center card-box-title">
                    <div class="title">
                        <h3>Contribution History</h3>
                        <p class="tw:text-[#808080]">View your monthly contributions breakdown</p>
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

                {{-- Grouped transactions by month --}}
                @forelse ($groupedTransactions as $monthYear => $transactions)
                    <div class="card-box">
                        <h4>{{ $monthYear }}</h4>
                        <div class="mt-4 overflow-x-auto">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr style="border-bottom: 1px solid rgba(0,0,0,0.2);">
                                        <th>Date</th>
                                        <th>Type</th>
                                        <th class="text-end">Amount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($transactions as $tx)
                                        <tr>
                                            <td>{{ \Carbon\Carbon::parse($tx->transaction_date)->format('m/d/Y') }}</td>
                                            <td>{{ ucfirst(str_replace('_', ' ', $tx->type)) }}</td>
                                            <td class="text-end"
                                                style="font-weight: 600; color: {{ $tx->type === 'withdrawal' ? '#DC2626' : '#1E4035' }}">
                                                {{ $tx->type === 'withdrawal' ? '-' : '+' }} ₱
                                                {{ number_format($tx->amount, 2) }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @empty
                    <div class="card-box text-center py-5">
                        <i class="fa-solid fa-folder-open fa-2x mb-3" style="color: #d0d0d0;"></i>
                        <p style="color: #808080; margin-top: 0.5rem;">No transactions yet.</p>
                    </div>
                @endforelse

            </div>
        </section>


        {{-- ============================================================
             DEPOSIT MODAL
        ============================================================ --}}
        <div class="modal fade" id="depositModal" tabindex="-1" aria-labelledby="depositModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content sm-modal-content">

                    <div class="modal-header sm-modal-header">
                        <div>
                            <div class="sm-modal-icon sm-deposit-icon">
                                <i class="fa-solid fa-circle-arrow-down"></i>
                            </div>
                            <h5 class="modal-title sm-modal-title" id="depositModalLabel">Deposit Savings</h5>
                            <p class="sm-modal-subtitle">Add funds to your savings account</p>
                        </div>
                        <button type="button" class="sm-modal-close" data-bs-dismiss="modal" aria-label="Close">
                            <i class="fa-solid fa-xmark"></i>
                        </button>
                    </div>

                    <form action="{{ route('savings.deposit') }}" method="POST">
                        @csrf
                        <input type="hidden" name="_form" value="deposit">

                        <div class="modal-body sm-modal-body">
                            <div class="sm-balance-pill">
                                <span class="sm-pill-label">Current Balance</span>
                                <span class="sm-pill-value">₱ {{ number_format($savingsAccount->balance, 2) }}</span>
                            </div>

                            <div class="sm-form-group">
                                <label class="sm-form-label" for="depositAmount">Amount to Deposit</label>
                                <div class="sm-amount-wrap">
                                    <span class="sm-amount-prefix">₱</span>
                                    <input class="sm-form-input @error('amount') sm-input-error @enderror"
                                        type="number" id="depositAmount" name="amount" placeholder="0.00" min="1"
                                        step="0.01" value="{{ old('amount') }}" />
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
                                <label class="sm-form-label" for="depositNote">Note (optional)</label>
                                <input class="sm-form-input" type="text" id="depositNote" name="note"
                                    placeholder="e.g. Monthly contribution" value="{{ old('note') }}" />
                            </div>
                        </div>

                        <div class="modal-footer sm-modal-footer">
                            <button type="button" class="sm-btn-cancel" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="sm-btn-confirm sm-deposit-confirm">
                                <i class="fa-solid fa-circle-arrow-down"></i> Confirm Deposit
                            </button>
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

                    <div class="modal-header sm-modal-header">
                        <div>
                            <div class="sm-modal-icon sm-withdraw-icon">
                                <i class="fa-solid fa-circle-arrow-up"></i>
                            </div>
                            <h5 class="modal-title sm-modal-title" id="withdrawModalLabel">Withdraw Savings</h5>
                            <p class="sm-modal-subtitle">Withdraw funds from your savings account</p>
                        </div>
                        <button type="button" class="sm-modal-close" data-bs-dismiss="modal" aria-label="Close">
                            <i class="fa-solid fa-xmark"></i>
                        </button>
                    </div>

                    <form action="{{ route('savings.withdraw') }}" method="POST">
                        @csrf
                        <input type="hidden" name="_form" value="withdraw">

                        <div class="modal-body sm-modal-body">
                            <div class="sm-balance-pill">
                                <span class="sm-pill-label">Available Balance</span>
                                <span class="sm-pill-value">₱ {{ number_format($savingsAccount->balance, 2) }}</span>
                            </div>

                            <div class="sm-form-group">
                                <label class="sm-form-label" for="withdrawAmount">Amount to Withdraw</label>
                                <div class="sm-amount-wrap">
                                    <span class="sm-amount-prefix">₱</span>
                                    <input class="sm-form-input @error('amount') sm-input-error @enderror"
                                        type="number" id="withdrawAmount" name="amount" placeholder="0.00" min="1"
                                        step="0.01" value="{{ old('amount') }}" />
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
                                        onclick="setSavingsAmount('withdrawAmount', {{ $savingsAccount->balance }})">All</button>
                                </div>
                                @error('amount')
                                    <div class="sm-error-msg show">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="sm-form-group">
                                <label class="sm-form-label" for="withdrawNote">Note (optional)</label>
                                <input class="sm-form-input" type="text" id="withdrawNote" name="note"
                                    placeholder="e.g. Emergency expense" value="{{ old('note') }}" />
                            </div>
                        </div>

                        <div class="modal-footer sm-modal-footer">
                            <button type="button" class="sm-btn-cancel" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="sm-btn-confirm sm-withdraw-confirm">
                                <i class="fa-solid fa-circle-arrow-up"></i> Confirm Withdraw
                            </button>
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
                            <i class="fa-solid fa-circle-check"></i>
                        </div>
                        <h5 class="sm-success-title">Deposit Successful!</h5>
                        <p class="sm-success-msg">
                            Your deposit of
                            <strong>₱
                                {{ session('deposit_amount') ? number_format(session('deposit_amount'), 2) : '0.00' }}</strong>
                            has been added to your savings account.
                        </p>
                        <div class="sm-success-balance-pill">
                            <span>New Balance</span>
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


        {{-- ============================================================
             SUCCESS MODAL — Withdraw
        ============================================================ --}}
        <div class="modal fade" id="withdrawSuccessModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-sm">
                <div class="modal-content sm-modal-content">
                    <div class="modal-body sm-success-body">
                        <div class="sm-success-icon sm-success-red">
                            <i class="fa-solid fa-circle-check"></i>
                        </div>
                        <h5 class="sm-success-title">Withdraw Successful!</h5>
                        <p class="sm-success-msg">
                            Your withdrawal of
                            <strong>₱
                                {{ session('withdraw_amount') ? number_format(session('withdraw_amount'), 2) : '0.00' }}</strong>
                            has been deducted from your savings account.
                        </p>
                        <div class="sm-success-balance-pill">
                            <span>New Balance</span>
                            <span>₱ {{ number_format($savingsAccount->balance, 2) }}</span>
                        </div>
                        <button type="button" class="sm-btn-confirm sm-withdraw-confirm w-100 mt-3"
                            data-bs-dismiss="modal">
                            <i class="fa-solid fa-check"></i> Done
                        </button>
                    </div>
                </div>
            </div>
        </div>


        {{-- ============================================================
             HIDDEN TRIGGER BUTTONS
             These bypass the "bootstrap is not defined" timing issue.
             Bootstrap handles the data-bs-toggle itself after Vite loads.
        ============================================================ --}}
        <button id="triggerDepositSuccess"   data-bs-toggle="modal" data-bs-target="#depositSuccessModal"  style="display:none;"></button>
        <button id="triggerWithdrawSuccess"  data-bs-toggle="modal" data-bs-target="#withdrawSuccessModal" style="display:none;"></button>
        <button id="triggerDepositModal"     data-bs-toggle="modal" data-bs-target="#depositModal"         style="display:none;"></button>
        <button id="triggerWithdrawModal"    data-bs-toggle="modal" data-bs-target="#withdrawModal"        style="display:none;"></button>

    </div>{{-- end container-fluid --}}


    {{-- AOS --}}
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init();
    </script>

    <script>
        /* ---- Quick amount helpers ---- */
        function setSavingsAmount(inputId, val) {
            document.getElementById(inputId).value = val;
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

        });
    </script>

</body>

</html>