<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transactions</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Profile</title>

    {{-- AOS animation link css --}}
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

    {{-- css link --}}
    <link rel="stylesheet" href="css_folder/transactions.css">
    <link rel="stylesheet" href="css_folder/loading.css">

    {{-- bootstrap and tailwind link --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- font awesome cdn link --}}
    <link rel="stylesheet" href="font-awesome-icon/css/all.min.css">
</head>

<body>
    <div class="container-fluid p-0 m-0">
        @include("components.sidebar")

        <div class="rightbar">
            @include("components.navbar2")

            <div class="main-parent">
                <div class="main-header">
                    <h3>Transactions</h3>
                    <p>Every entry posted to your share capital, savings and loan accounts</p>
                </div>

                <div class="main-body">
                    <div class="card-box-parent">
                        <div class="card-box">
                            <div class="card-header">
                                <div class="sum-label">Total Deposits</div>
                                <div class="sum-icon"><i class="fa fa-wallet"></i></div>
                            </div>
                            <div class="card-body">
                                <div class="sum-value">₱5,500.00</div>
                                <div class="sum-stat"></div>
                            </div>
                        </div>

                        <div class="card-box">
                            <div class="card-header">
                                <div class="sum-label">Total Repayments</div>
                                <div class="sum-icon"><i class="fa fa-wallet"></i></div>
                            </div>
                            <div class="card-body">
                                <div class="sum-value">₱5,500.00</div>
                                <div class="sum-stat"></div>
                            </div>
                        </div>

                        <div class="card-box">
                            <div class="card-header">
                                <div class="sum-label">Transact this month</div>
                                <div class="sum-icon"><i class="fa fa-wallet"></i></div>
                            </div>
                            <div class="card-body">
                                <div class="sum-value">₱5,500.00</div>
                                <div class="sum-stat"></div>
                            </div>
                        </div>

                        <div class="card-box">
                            <div class="card-header">
                                <div class="sum-label">Net Change</div>
                                <div class="sum-icon"><i class="fa fa-wallet"></i></div>
                            </div>
                            <div class="card-body">
                                <div class="sum-value">₱5,500.00</div>
                                <div class="sum-stat"></div>
                            </div>
                        </div>
                    </div>

                    <div class="filters">
                        <div class="tab-group">
                            <div class="tab active">All</div>
                            <div class="tab">Share Capital</div>
                            <div class="tab">Savings</div>
                            <div class="tab">Loans</div>
                        </div>
                    </div>

                    <div class="toolbar">
                        <div class="search-box"><i class="fa-solid fa-magnifying-glass"></i><input type="text"
                                placeholder="Search by description or reference no."></div>
                        <select class="filter-select">
                            <option>Last 30 days</option>
                            <option>Last 90 days</option>
                            <option>This year</option>
                            <option>All time</option>
                        </select>
                        <select class="filter-select">
                            <option>All statuses</option>
                            <option>Completed</option>
                            <option>Pending</option>
                        </select>
                    </div>

                    <div class="ledger-page">
                        <table class="tx-table">
                            <thead>
                                <tr>
                                    <th>Description</th>
                                    <th>Reference No.</th>
                                    <th>Date</th>
                                    <th>Status</th>
                                    <th class="num">Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <div class="tx-desc-cell">
                                            <div class="tx-icon savings"><i class="fa-solid fa-piggy-bank"></i></div>
                                            <div class="tx-desc"><strong>Savings Deposit</strong><span>Regular
                                                    Savings</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="tx-ref">TX-88213</td>
                                    <td class="tx-date">Jul 20, 2026<br>9:14 AM</td>
                                    <td><span class="status-chip completed">Completed</span></td>
                                    <td class="tx-amt up">+₱1,500.00</td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="tx-desc-cell">
                                            <div class="tx-icon mint"><i class="fa-solid fa-hand-holding-dollar"></i>
                                            </div>
                                            <div class="tx-desc"><strong>Loan Repayment</strong><span>Installment 6 of
                                                    12</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="tx-ref">TX-88109</td>
                                    <td class="tx-date">Jul 05, 2026<br>2:30 PM</td>
                                    <td><span class="status-chip completed">Completed</span></td>
                                    <td class="tx-amt down">-₱1,650.00</td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="tx-desc-cell">
                                            <div class="tx-icon gold"><i class="fa-solid fa-layer-group"></i></div>
                                            <div class="tx-desc"><strong>Share Capital
                                                    Contribution</strong><span>Voluntary
                                                    top-up</span></div>
                                        </div>
                                    </td>
                                    <td class="tx-ref">TX-87990</td>
                                    <td class="tx-date">Jun 28, 2026<br>11:05 AM</td>
                                    <td><span class="status-chip completed">Completed</span></td>
                                    <td class="tx-amt up">+₱2,000.00</td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="tx-desc-cell">
                                            <div class="tx-icon savings"><i class="fa-solid fa-piggy-bank"></i></div>
                                            <div class="tx-desc"><strong>Savings Deposit</strong><span>Regular
                                                    Savings</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="tx-ref">TX-87814</td>
                                    <td class="tx-date">Jun 15, 2026<br>10:47 AM</td>
                                    <td><span class="status-chip completed">Completed</span></td>
                                    <td class="tx-amt up">+₱1,000.00</td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="tx-desc-cell">
                                            <div class="tx-icon mint"><i class="fa-solid fa-hand-holding-dollar"></i>
                                            </div>
                                            <div class="tx-desc"><strong>Loan Repayment</strong><span>Installment 5 of
                                                    12</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="tx-ref">TX-87650</td>
                                    <td class="tx-date">Jun 05, 2026<br>1:12 PM</td>
                                    <td><span class="status-chip pending">Pending</span></td>
                                    <td class="tx-amt down">-₱1,650.00</td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="tx-desc-cell">
                                            <div class="tx-icon coral"><i class="fa-solid fa-file-invoice-dollar"></i>
                                            </div>
                                            <div class="tx-desc"><strong>Loan Disbursement</strong><span>Multi-Purpose
                                                    Loan
                                                    approved</span></div>
                                        </div>
                                    </td>
                                    <td class="tx-ref">TX-81204</td>
                                    <td class="tx-date">Jan 12, 2026<br>10:00 AM</td>
                                    <td><span class="status-chip completed">Completed</span></td>
                                    <td class="tx-amt up">+₱18,000.00</td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="tx-desc-cell">
                                            <div class="tx-icon gold"><i class="fa-solid fa-layer-group"></i></div>
                                            <div class="tx-desc"><strong>Share Capital
                                                    Contribution</strong><span>Monthly
                                                    contribution</span></div>
                                        </div>
                                    </td>
                                    <td class="tx-ref">TX-80991</td>
                                    <td class="tx-date">Jan 08, 2026<br>9:30 AM</td>
                                    <td><span class="status-chip completed">Completed</span></td>
                                    <td class="tx-amt up">+₱1,500.00</td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="pagination">
                            <span>Showing 1–7 of 42 transactions</span>
                            <div class="page-btns">
                                <div class="page-btn"><i class="fa-solid fa-chevron-left"></i></div>
                                <div class="page-btn active">1</div>
                                <div class="page-btn">2</div>
                                <div class="page-btn">3</div>
                                <div class="page-btn"><i class="fa-solid fa-chevron-right"></i></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>

</html>