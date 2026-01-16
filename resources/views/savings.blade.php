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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css"
        integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    {{-- bootstrap link --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
</head>

<body>

    <div class="container-fluid p-0 m-0">
        @include("navbar2")
        @include("offcanvas")

        <main>
            <div class="card-box-parent">
                <h3>My Savings</h3>

                <h2 class="mt-3">₱ 12,000.00</h2>

                <div class="mt-4 d-flex justify-content-left align-items-center flex-wrap gap-3">
                    <div class="card-box">
                        <div
                            class="tw:w-[55px] tw:h-[55px] card-icon">
                            <i class="fa-solid fa-circle-arrow-down"></i>
                        </div>
                        <div>
                            <p style="margin: 0; color: #2CAF49;">Deposit</p>
                        </div>
                    </div>

                    <div class="card-box">
                        <div
                            class="tw:w-[55px] tw:h-[55px] card-icon">
                            <i class="fa-solid fa-circle-arrow-up"></i>
                        </div>
                        <div>
                            <p style="margin: 0; color: #5840DF;">Withdraw</p>
                        </div>
                    </div>
                </div>
            </div>
        </main>

        <section>
            <div class="d-flex justify-content-between align-items-center card-box-parent flex-wrap">
                <div class="card-box tw:bg-white">
                    <div
                        class="tw:w-[55px] tw:h-[55px] card-icon d-flex justify-content-center align-items-center card-icon">
                        <i class="fa-solid fa-peso-sign" style="color: #155DFC"></i>
                    </div>

                    <p>Total Savings</p>

                    <span>₱ 12,000</span>
                </div>

                <div class="card-box tw:bg-white">
                    <div
                        class="tw:w-[55px] tw:h-[55px] card-icon d-flex justify-content-center align-items-center card-icon">
                        <i class="fa-solid fa-arrow-trend-up" style="color: #fff"></i>
                    </div>

                    <p>Monthly Average</p>

                    <span>₱ 2,000</span>
                </div>

                <div class="card-box tw:bg-white">
                    <div
                        class="tw:w-[55px] tw:h-[55px] card-icon d-flex justify-content-center align-items-center card-icon">
                        <i class="fa-solid fa-calendar-days" style="color: #9810FA"></i>
                    </div>

                    <p>Total Months</p>

                    <span>6 Months</span>
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
                        <button class="py-2 px-3 tw:bg-[#00A63E] tw:text-white" style="border-radius: 10px"><i class="fa-solid fa-download"></i> CSV</button>

                        <button class="py-2 px-3 tw:bg-[#155DFC] tw:text-white" style="border-radius: 10px"><i class="fa fa-solid fa-download"></i> PDF</button>
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

    {{-- Bootstrap link --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
        crossorigin="anonymous"></script>

    <script>
        AOS.init();
    </script>
</body>

</html>