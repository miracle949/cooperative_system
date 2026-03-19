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
            <div class="main-intro">
                <p class="">Member Savings</p>

                <h3 class="">My Savings</h3>

                <span>Manage your share capital contributions and track your dividends</span>
            </div>
            <div class="card-box-parent">
                <div class="card-box-text">
                    <h3>Total Savings Balance</h3>

                    <h2 class="mt-3 mb-3">₱ 12,000.00</h2>

                    <span>Last updated today · 6 months active</span>
                </div>

                <div class="d-flex justify-content-left align-items-center flex-wrap gap-4">
                    <div class="card-box">
                        <div class="card-icon"> 
                            <i class="fa-solid fa-circle-arrow-down"></i>
                        </div>
                        <div>
                            <p>Deposit</p>
                        </div>
                    </div>

                    <div class="card-box">
                        <div class="card-icon">
                            <i class="fa-solid fa-circle-arrow-up"></i>
                        </div>
                        <div>
                            <p>Withdraw</p>
                        </div>
                    </div>
                </div>
            </div>
        </main>

        <section>
            <div class="d-flex justify-content-between align-items-center card-box-parent flex-wrap">
                <div class="card-box tw:bg-white">
                    <div class="card-accent"></div>
                    <div
                        class="card-icon d-flex justify-content-center align-items-center card-icon">
                        <i class="fa-solid fa-peso-sign"></i>
                    </div>

                    <p>Total Savings</p>

                    <h4>₱ 12,000</h4>

                    <span>All time contributions</span>
                </div>

                <div class="card-box tw:bg-white">
                    <div class="card-accent"></div>
                    <div
                        class="card-icon d-flex justify-content-center align-items-center card-icon">
                        <i class="fa-solid fa-arrow-trend-up"></i>
                    </div>

                    <p>Monthly Average</p>

                    <h4>₱ 2,000</h4>

                    <span>Per month average</span>
                </div>

                <div class="card-box tw:bg-white">
                    <div class="card-accent"></div>
                    <div
                        class="card-icon d-flex justify-content-center align-items-center card-icon">
                        <i class="fa-solid fa-calendar-days"></i>
                    </div>

                    <p>Total Months</p>

                    <h4>6 Months</h4>

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
                        <button class="py-2 px-3 tw:text-white" style="border-radius: 10px"><i
                                class="fa-solid fa-download"></i> CSV</button>

                        <button class="py-2 px-3 tw:text-white" style="border-radius: 10px"><i
                                class="fa fa-solid fa-download"></i> PDF</button>
                    </div>
                </div>

                <div class="card-box">
                    <h4>November 2024</h4>

                    <div class="mt-4 overflow-x-auto">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr style="border-bottom: 1px solid black;">
                                    <th>Date</th>
                                    <th>Type</th>
                                    <th class="text-end">Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>11/1/2025</td>
                                    <td>Regular Savings</td>
                                    <td class="text-end">₱ 1,500</td>
                                </tr>

                                <tr>
                                    <td>11/1/2025</td>
                                    <td>Share Capital</td>
                                    <td class="text-end">₱ 500</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="card-box">
                    <h4>October 2024</h4>

                    <div class="mt-4 overflow-x-auto">
                        <table class="table table-striped table-hover overflow-x-auto">
                            <thead>
                                <tr style="border-bottom: 1px solid black;">
                                    <th>Date</th>
                                    <th>Type</th>
                                    <th class="text-end">Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>10/1/2025</td>
                                    <td>Regular Savings</td>
                                    <td class="text-end">₱ 1,500</td>
                                </tr>

                                <tr>
                                    <td>10/1/2025</td>
                                    <td>Share Capital</td>
                                    <td class="text-end">₱ 500</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="card-box">
                    <h4>September 2024</h4>

                    <div class="mt-4 overflow-x-auto">
                        <table class="table table-striped table-hover overflow-x-auto">
                            <thead>
                                <tr style="border-bottom: 1px solid black;">
                                    <th>Date</th>
                                    <th>Type</th>
                                    <th class="text-end">Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>09/1/2025</td>
                                    <td>Regular Savings</td>
                                    <td class="text-end">₱ 1,500</td>
                                </tr>

                                <tr>
                                    <td>09/1/2025</td>
                                    <td>Share Capital</td>
                                    <td class="text-end">₱ 500</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </section>
    </div>

    {{-- AOS animation link js --}}
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>

    <script>
        AOS.init();
    </script>
</body>

</html>