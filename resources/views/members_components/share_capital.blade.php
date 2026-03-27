<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Share Capital</title>
    <link rel="icon" href="images/websitelogo.png" type="image/png">

    {{-- css link --}}
    <link rel="stylesheet" href="css_folder/share_capital.css">
    <link rel="stylesheet" href="css_folder/loading.css">


    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- {{-- font awesome cdn link --}}

    <link rel="stylesheet" href="font-awesome-icon/css/all.min.css">
</head>

<body>

    <div class="container-fluid p-0 m-0">
        @include("components.navbar2")
        @include("components.offcanvas")

        {{-- <div class="text-center">
            <div class="spinner-border text-primary" role="status"></div>
            <p>Loading loan requests...</p>
        </div> --}}

        <main>
            {{-- <h3>Share Capital</h3>

            <p>Manage your share capital contributions and track your dividends</p> --}}

            {{-- <div class="parent-main">
                <div class="main-intro">
                    <p class="">Member Account</p>

                    <h3 class="">Share Capital</h3>

                    <span>Manage your share capital contributions and track your dividends</span>
                </div>

                <div class="download">
                    <button>
                        <i class="fa fa-download"></i>
                        <span>Download Statement</span>
                    </button>
                </div>
            </div> --}}

            <div class="modal fade" id="shareCapital" tabindex="-1" aria-labelledby="exampleModalLabel"
                aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <div class="title">
                                <div class="modal-icon">
                                    <i class="fa fa-circle-arrow-down"></i>
                                </div>
                                <h5 class="modal-title sm-modal-title" id="exampleModalLabel fw-bold">Add Share Capital
                                </h5>
                                <p>Purchase additional shares to increase your capital</p>
                            </div>
                            <div class="exit">
                                <button type="button" class="btn-close m-0" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                        </div>
                        <div class="modal-body">
                            <div class="current-balance">
                                <p>Current Balance</p>

                                <p>₱25,000 · 25 shares</p>
                            </div>

                            <p>Amount to Deposit</p>


                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-primary">Save changes</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="parent-main">

                <div class="download">
                    <button>
                        <i class="fa fa-arrow-down"></i>
                        <span>Download Statement</span>
                    </button>
                </div>

                <div class="share">
                    <button data-bs-toggle="modal" data-bs-target="#shareCapital">
                        <i class="fa fa-plus"></i>
                        <span>Add Share Capital</span>
                    </button>
                </div>
            </div>

            <div class="parent-share">
                <div class="card">
                    <div class="card-accent"></div>
                    <div class="card-icon">
                        <div class="fa fa-dollar-sign"></div>
                    </div>

                    <span>Current Balance</span>

                    <h4>P25,000</h4>

                    <article>25 share</article>
                </div>

                <div class="card">
                    <div class="card-accent"></div>
                    <div class="card-icon">
                        <div class="fa fa-arrow-trend-up"></div>
                    </div>

                    <span>Dividend Rate</span>

                    <h4>8.5% p.a</h4>

                    <article>Annual return</article>
                </div>

                <div class="card">
                    <div class="card-accent"></div>
                    <div class="card-icon">
                        <div class="fa fa-chart-pie"></div>
                    </div>

                    <span>Last Dividend</span>

                    <h4>P2,125</h4>

                    <article>Dec 2024</article>
                </div>

                <div class="card">
                    <div class="card-accent"></div>
                    <div class="card-icon">
                        <div class="fa fa-calendar-days"></div>
                    </div>

                    <span>Next Dividend</span>

                    <h4>June 15, 2025</h4>

                    <article>Expected date</article>
                </div>
            </div>

            <div class="contribution-parent">
                <h3>Contribution History</h3>

                <div class="parent-table">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Type</th>
                                <th>Reference No.</th>
                                <th>Shares</th>
                                <th>Amount</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Jan 15, 2025</td>
                                <td>Additional Contribution</td>
                                <td>SC-2024-001</td>
                                <td>5</td>
                                <td>P5,000</td>
                                <td>
                                    <i class="fa fa-check-circle"></i>
                                    <span>Completed</span>
                                </td>
                            </tr>

                            <tr>
                                <td>Jan 15, 2025</td>
                                <td>Additional Contribution</td>
                                <td>SC-2024-001</td>
                                <td>5</td>
                                <td>P5,000</td>
                                <td>
                                    <i class="fa fa-check-circle"></i>
                                    <span>Completed</span>
                                </td>
                            </tr>

                            <tr>
                                <td>Jan 15, 2025</td>
                                <td>Additional Contribution</td>
                                <td>SC-2024-001</td>
                                <td>5</td>
                                <td>P5,000</td>
                                <td>
                                    <i class="fa fa-check-circle"></i>
                                    <span>Completed</span>
                                </td>
                            </tr>

                            <tr>
                                <td>Jan 15, 2025</td>
                                <td>Additional Contribution</td>
                                <td>SC-2024-001</td>
                                <td>5</td>
                                <td>P5,000</td>
                                <td>
                                    <i class="fa fa-check-circle"></i>
                                    <span>Completed</span>
                                </td>
                            </tr>

                            <tr>
                                <td>Jan 15, 2025</td>
                                <td>Additional Contribution</td>
                                <td>SC-2024-001</td>
                                <td>5</td>
                                <td>P5,000</td>
                                <td>
                                    <i class="fa fa-check-circle"></i>
                                    <span>Completed</span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="dividend-parent">
                <h3>Dividend History</h3>

                <div class="parent-table">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Period</th>
                                <th>Dividend Rate</th>
                                <th>Share Capital</th>
                                <th>Dividend Amount</th>
                                <th>Date Paid</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>2nd Semester 2024</td>
                                <td>8.5%</td>
                                <td>P25,000</td>
                                <td>P2,125</td>
                                <td>Dec 15,2024</td>
                            </tr>

                            <tr>
                                <td>1st Semester 2024</td>
                                <td>8.5%</td>
                                <td>P25,000</td>
                                <td>P2,125</td>
                                <td>Dec 15,2024</td>
                            </tr>

                            <tr>
                                <td>2nd Semester 2023</td>
                                <td>8.5%</td>
                                <td>P25,000</td>
                                <td>P2,125</td>
                                <td>Dec 15,2024</td>
                            </tr>

                            <tr>
                                <td>1st Semester 2023</td>
                                <td>8.5%</td>
                                <td>P25,000</td>
                                <td>P2,125</td>
                                <td>Dec 15,2024</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>

    {{-- AOS animation link js --}}
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>

    <script>
        AOS.init();
    </script>
</body>

</html>