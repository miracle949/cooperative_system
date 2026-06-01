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

        /* ─── Financial Trends Chart Card ───────────────────── */
        .section-card {
            background: #ffffff;
            border: 1px solid rgba(15, 31, 69, 0.10);
            border-radius: 12px;
            overflow: hidden;
            /* margin: 20px 0 32px 0; */
            margin: 25px 0 0;
        }

        .section-header {
            padding: 16px 20px 14px;
            border-bottom: 1px solid rgba(15, 31, 69, 0.10);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .section-title {
            font-size: 14px;
            font-weight: 600;
            color: #0f1f45;
        }

        .section-sub {
            font-size: 11px;
            color: #8891ad;
            margin-top: 2px;
        }

        .chart-wrap {
            padding: 16px 16px 12px;
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .chart-tabs {
            display: flex;
            gap: 4px;
        }

        .chart-tab {
            font-size: 11px;
            font-weight: 500;
            padding: 5px 12px;
            border-radius: 99px;
            border: 1px solid rgba(15, 31, 69, 0.10);
            background: none;
            color: #8891ad;
            cursor: pointer;
            transition: all .15s;
        }

        .chart-tab.active {
            background: #0f1f45;
            color: #fff;
            border-color: #0f1f45;
        }

        .chart-container {
            position: relative;
            height: 260px;
        }

        .chart-legend {
            display: flex;
            gap: 14px;
            flex-wrap: wrap;
        }

        .legend-item {
            display: flex;
            align-items: center;
            gap: 5px;
            font-size: 11px;
            color: #8891ad;
        }

        .legend-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            flex-shrink: 0;
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
                                                <td>REF-052801</td>
                                                <td>Savings</td>
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
                        <div class="section-card">
                            <div class="section-header">
                                <div>
                                    <div class="section-title">Financial Trends</div>
                                    <div class="section-sub">Savings &amp; loan repayments over time</div>
                                </div>
                            </div>
                            <div class="chart-wrap">
                                <div class="chart-tabs">
                                    <button class="chart-tab active" onclick="switchChart('monthly', this)">Monthly</button>
                                    <button class="chart-tab" onclick="switchChart('cumulative', this)">Cumulative</button>
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
                        {{-- end Financial Trends Chart --}}

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