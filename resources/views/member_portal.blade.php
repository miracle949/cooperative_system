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
    <link rel="stylesheet" href="homepage.css">
    <link rel="stylesheet" href="loading.css">

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

        <main>
            <h3 class="ms-5 mt-5">Member Cooperative Portal</h3>

            <p class="mt-4 ms-5">Hello Welcome, Jhun!</p>

            <hr>

            <div class="mt-5 pb-5 d-flex justify-content-center align-items-center flex-wrap card-parent">
                <div class="card-box p-4">
                    <div class="tw:w-[55px] tw:h-[55px] card-icon mt-3 d-flex justify-content-center align-items-center" style="border-radius: 10px">
                        <i class="fa-solid fa-wallet"></i>
                    </div>

                    <p class="mt-4">Savings Balance</p>

                    <span>₱ 15,750.50</span>
                </div>

                <div class="card-box p-4">
                    <div class="tw:w-[55px] tw:h-[55px] card-icon mt-3 d-flex justify-content-center align-items-center" style="border-radius: 10px">
                        <i class="fa-solid fa-arrow-trend-up"></i>
                    </div>

                    <p class="mt-4">Active Loans</p>

                    <span>1 Loan(s)</span>
                </div>

                <div  class="card-box p-4">
                    <div class="tw:w-[55px] tw:h-[55px] card-icon mt-3 d-flex justify-content-center align-items-center" style="border-radius: 10px">
                        <i class="fa-solid fa-award"></i>
                    </div>

                    <p class="mt-4">Credit score</p>

                    <span>80 pts</span>
                </div>
            </div>
        </main>

        <section class="p-5">
            <div class="card-box-parent">
                <h3>Loan Application</h3>

                <div class="card-parent mt-4 d-flex flex-column gap-4">

                    <div class="card-box">
                        <div class="d-flex justify-content-between align-items-center">
                            <h4>Personal Loan</h4>

                            <div class="d-flex justify-content-center align-items-center gap-1 card-icon" style="background-color: #DBFCE7; border-radius: 28px; color: #128B4A; padding: 0.5rem;">
                                <i class="fa-solid fa-check-circle" style="font-size: 14px;"></i>
                                <p style="margin: 0; font-size: 13px;">Approved</p>
                            </div>
                        </div>

                        <p class="mt-2 tw:text-[#808080]">Applied on 11/15/2024</p>

                        <div class="row mt-5">
                            <div class="col-3">
                                <p class="tw:text-[#808080]">Loan Amount</p>

                                <span>₱ 50,000</span>
                            </div>

                            <div class="col-3">
                                <p class="tw:text-[#808080]">Purpose</p>

                                <span>Home Renovation</span>
                            </div>

                            <div class="col-3">
                                <p class="tw:text-[#808080]">Monthly Payment</p>

                                <span>₱ 4,580</span>
                            </div>

                            <div class="col-3">
                                <p class="tw:text-[#808080]">Remaining Balance</p>

                                <span>₱ 48,200</span>
                            </div>
                        </div>

                        <hr>

                        <p class="tw:text-[#808080]" style="margin: 2rem 0px 0px">Approved on 11/18/2024</p>
                    </div>

                    <div class="card-box">
                        <div class="d-flex justify-content-between align-items-center">
                            <h4>Personal Loan</h4>

                            <div class="d-flex justify-content-center align-items-center gap-1 card-icon" style="background-color: #FEF9C2; border-radius: 28px; color: #BA5F00; padding: 0.5rem;">
                                <i class="fa-solid fa-clock" style="font-size: 14px;"></i>
                                <p style="margin: 0; font-size: 13px;">Pending</p>
                            </div>
                        </div>

                        <p class="mt-2 tw:text-[#808080]">Applied on 11/15/2024</p>

                        <div class="row mt-5">
                            <div class="col-3">
                                <p class="tw:text-[#808080]">Loan Amount</p>

                                <span>₱ 50,000</span>
                            </div>

                            <div class="col-3">
                                <p class="tw:text-[#808080]">Purpose</p>

                                <span>Home Renovation</span>
                            </div>
                        </div>
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