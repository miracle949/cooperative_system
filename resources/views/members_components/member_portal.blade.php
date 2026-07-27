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
                                    <div class="main-intro-icon"></div>
                                    <div class="main-intro-text">
                                        <span>Member Cooperative Assistant</span>
                                        <p>Your money are growing steadily. Every peso you save today builds a stronger
                                            tomorrow for you and the community.</p>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <div class="card-parent">

                            {{-- Savings Balance --}}
                            <div class="card-box" onclick="window.location='{{ route('savings.index') }}'">
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

                    <section>
                        <div class="section-card">
                            <div class="section-header">
                                <div>
                                    <div class="section-title">Financial Trends</div>
                                    <div class="section-sub">Savings &amp; loan repayments over time</div>
                                </div>
                            </div>
                            <div class="chart-wrap">
                                <div class="chart-tabs">
                                    <button class="chart-tab active"
                                        onclick="switchChart('monthly', this)">Monthly</button>
                                    <button class="chart-tab"
                                        onclick="switchChart('cumulative', this)">Cumulative</button>
                                </div>
                                <div class="chart-container">
                                    <canvas id="trendsChart"></canvas>
                                </div>
                                <div class="chart-legend">
                                    <div class="legend-item">
                                        <div class="legend-dot" style="background:#1e9e6b"></div>Savings
                                    </div>
                                    <div class="legend-item">
                                        <div class="legend-dot" style="background:#1560c0"></div>Loan Payments
                                    </div>
                                    <div class="legend-item">
                                        <div class="legend-dot" style="background:#f0a500"></div>Share Capital
                                    </div>
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
                                                <td>REF-052801</td>
                                                <td>
                                                    <span>Savings</span>
                                                </td>
                                                <td>+₱3,200.00</td>
                                            </tr>
                                            <tr>
                                                <td>May 28, 2026</td>
                                                <td>Loan Payment</td>
                                                <td>REF-052801</td>
                                                <td>
                                                    <span>Loan</span>
                                                </td>
                                                <td>+₱3,200.00</td>
                                            </tr>
                                            <tr>
                                                <td>May 28, 2026</td>
                                                <td>Loan Payment</td>
                                                <td>REF-052801</td>
                                                <td>Savings</td>
                                                <td>+₱3,200.00</td>
                                            </tr>
                                            <tr>
                                                <td>May 28, 2026</td>
                                                <td>Share Capital Contribution</td>
                                                <td>REF-052801</td>
                                                <td>Savings</td>
                                                <td>+₱3,200.00</td>
                                            </tr>
                                            <tr>
                                                <td>May 28, 2026</td>
                                                <td>Savings Deposit</td>
                                                <td>REF-052801</td>
                                                <td>Savings</td>
                                                <td>+₱3,200.00</td>
                                            </tr>
                                            <tr>
                                                <td>May 28, 2026</td>
                                                <td>Savings Deposit</td>
                                                <td>REF-052801</td>
                                                <td>Savings</td>
                                                <td>+₱3,200.00</td>
                                            </tr>
                                            <tr>
                                                <td>May 28, 2026</td>
                                                <td>Savings Deposit</td>
                                                <td>REF-052801</td>
                                                <td>Savings</td>
                                                <td>+₱3,200.00</td>
                                            </tr>
                                            <tr>
                                                <td>May 28, 2026</td>
                                                <td>Savings Deposit</td>
                                                <td>REF-052801</td>
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
                    <div class="ask-box" style="margin-top: 2rem; border: 1px solid #fecaca; background: #fef2f2;">
                        <div class="text-box">
                            <h4 style="color: #dc2626;">Leave the Cooperative?</h4>
                            <p>If you wish to resign from the cooperative, you may submit a resignation request. A 60-day holding period applies for share capital withdrawal.</p>
                        </div>
                        <div class="link-box">
                            <button onclick="document.getElementById('resignModal').style.display='flex'" style="background: #dc2626; color: #fff; border: none; padding: 10px 20px; border-radius: 8px; cursor: pointer; font-weight: 600; display: flex; align-items: center; gap: 8px;">
                                <i class="fa fa-sign-out-alt"></i>
                                <span>Request Resignation</span>
                            </button>
                        </div>
                    </div>

                    <!-- Resignation Modal -->
                    <div id="resignModal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.6); z-index:99999; align-items:center; justify-content:center;" onclick="if(event.target===this)this.style.display='none'">
                        <div style="background:#fff; border-radius:12px; max-width:450px; width:90%; padding:0; box-shadow:0 25px 60px rgba(0,0,0,0.3);">
                            <div style="padding:20px 24px; border-bottom:1px solid #e5e7eb; display:flex; justify-content:space-between; align-items:center;">
                                <h3 style="margin:0; font-size:18px; font-weight:700; color:#111827;">Request Resignation</h3>
                                <button onclick="document.getElementById('resignModal').style.display='none'" style="background:none; border:none; font-size:24px; cursor:pointer; color:#6b7280;">&times;</button>
                            </div>
                            <form method="POST" action="{{ route('resignation.request') }}" style="padding:24px;">
                                @csrf
                                <p style="font-size:14px; color:#6b7280; margin-bottom:20px;">Please select your preference for your share capital:</p>
                                <div style="display:flex; flex-direction:column; gap:12px; margin-bottom:24px;">
                                    <label class="resign-option" style="display:flex; align-items:center; gap:12px; padding:14px 16px; border:2px solid #e5e7eb; border-radius:10px; cursor:pointer; transition:all .2s;">
                                        <input type="radio" name="withdraw_share_capital" value="1" style="accent-color:#1E2A4A;" required onchange="document.querySelectorAll('.resign-option').forEach(l=>l.style.borderColor='#e5e7eb');this.closest('label').style.borderColor='#1E2A4A'">
                                        <div>
                                            <strong style="display:block; color:#111827; font-size:15px;">Withdraw Share Capital</strong>
                                            <span style="font-size:13px; color:#6b7280;">I want my share capital paid out after 60 days</span>
                                        </div>
                                    </label>
                                    <label class="resign-option" style="display:flex; align-items:center; gap:12px; padding:14px 16px; border:2px solid #e5e7eb; border-radius:10px; cursor:pointer; transition:all .2s;">
                                        <input type="radio" name="withdraw_share_capital" value="0" style="accent-color:#1E2A4A;" required onchange="document.querySelectorAll('.resign-option').forEach(l=>l.style.borderColor='#e5e7eb');this.closest('label').style.borderColor='#1E2A4A'">
                                        <div>
                                            <strong style="display:block; color:#111827; font-size:15px;">Leave Share Capital</strong>
                                            <span style="font-size:13px; color:#6b7280;">I leave my share capital with the cooperative</span>
                                        </div>
                                    </label>
                                </div>
                                <div style="display:flex; gap:12px;">
                                    <button type="button" onclick="document.getElementById('resignModal').style.display='none'" style="flex:1; padding:12px; background:#f3f4f6; color:#374151; border:none; border-radius:8px; cursor:pointer; font-weight:600;">Cancel</button>
                                    <button type="submit" style="flex:1; padding:12px; background:#dc2626; color:#fff; border:none; border-radius:8px; cursor:pointer; font-weight:600;" onclick="return confirm('Are you sure you want to submit a resignation request? This action will be reviewed by admin.')">Submit Request</button>
                                </div>
                            </form>
                        </div>
                    </div>
                    </section>
                </div>
            </div>
        </div>{{-- end #page-content --}}
    </div>{{-- end .container-fluid --}}

    {{-- ═══════════════════════════════════════════════
    FINANCIAL TRENDS CHART SCRIPT
    ═══════════════════════════════════════════════ --}}
    <script>
        const months = ['Nov', 'Dec', 'Jan', 'Feb', 'Mar', 'Apr', 'May'];

        const monthlyData = {
            savings: [1500, 2000, 0, 2000, 2500, 2000, 3200],
            loanPay: [0, 0, 1500, 850, 2350, 1500, 2350],
            capital: [500, 500, 500, 500, 500, 500, 500],
        };

        const cumulativeData = {
            savings: monthlyData.savings.reduce((a, v, i) => { a.push((a[i - 1] || 0) + v); return a; }, []),
            loanPay: monthlyData.loanPay.reduce((a, v, i) => { a.push((a[i - 1] || 0) + v); return a; }, []),
            capital: monthlyData.capital.reduce((a, v, i) => { a.push((a[i - 1] || 0) + v); return a; }, []),
        };

        const commonDataset = (data) => [
            {
                label: 'Savings',
                data: data.savings,
                borderColor: '#1e9e6b',
                backgroundColor: 'rgba(30,158,107,.10)',
                tension: .4,
                fill: true,
                pointRadius: 4,
                pointHoverRadius: 6,
                borderWidth: 2
            },
            {
                label: 'Loan Payments',
                data: data.loanPay,
                borderColor: '#1560c0',
                backgroundColor: 'rgba(21,96,192,.08)',
                tension: .4,
                fill: true,
                pointRadius: 4,
                pointHoverRadius: 6,
                borderWidth: 2
            },
            {
                label: 'Share Capital',
                data: data.capital,
                borderColor: '#f0a500',
                backgroundColor: 'rgba(240,165,0,.08)',
                tension: .4,
                fill: false,
                pointRadius: 4,
                pointHoverRadius: 6,
                borderWidth: 2,
                borderDash: [5, 4]
            },
        ];

        const ctx = document.getElementById('trendsChart').getContext('2d');
        const chart = new Chart(ctx, {
            type: 'line',
            data: { labels: months, datasets: commonDataset(monthlyData) },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: { mode: 'index', intersect: false },
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#0f1f45',
                        titleColor: 'rgba(255,255,255,.6)',
                        bodyColor: '#fff',
                        padding: 10,
                        cornerRadius: 8,
                        callbacks: {
                            label: ctx => ` ${ctx.dataset.label}: ₱${ctx.parsed.y.toLocaleString()}`
                        }
                    }
                },
                scales: {
                    x: {
                        grid: { display: false },
                        ticks: { font: { size: 11 }, color: '#8891ad' }
                    },
                    y: {
                        grid: { color: 'rgba(15,31,69,.06)' },
                        ticks: {
                            font: { size: 11 },
                            color: '#8891ad',
                            callback: v => '₱' + (v >= 1000 ? (v / 1000).toFixed(0) + 'k' : v)
                        }
                    }
                }
            }
        });

        function switchChart(type, btn) {
            document.querySelectorAll('.chart-tab').forEach(t => t.classList.remove('active'));
            btn.classList.add('active');
            const d = type === 'monthly' ? monthlyData : cumulativeData;
            chart.data.datasets = commonDataset(d);
            chart.update();
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