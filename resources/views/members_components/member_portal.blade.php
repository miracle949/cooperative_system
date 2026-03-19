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
            @if ($first_name)

                <div class="main-intro">
                    <p class="">Member Cooperative Portal</p>

                    <h3 class="">Hello Welcome, {{ $first_name }}! 👋</h3>
                </div>

            @endif

            {{--
            <hr style="z-index: -1"> --}}
            <div class="line"></div>

            <div class="card-parent">
                <div class="card-box">
                    <div class="card-transparent">
                        <div class="card-head"></div>
                        <div class="card-icon mt-2 d-flex justify-content-center align-items-center"
                            style="border-radius: 10px">
                            <i class="fa-solid fa-wallet"></i>
                        </div>

                        <p class="mt-4">Savings Balance</p>

                        <span>₱ 15,750.50</span>
                    </div>
                    <div class="card-update">
                        <div class="update">
                            <i class="fa fa-arrow-up"></i>
                            <p>Active</p>
                        </div>
                    </div>
                </div>

                <div class="card-box">
                    <div class="card-transparent">
                        <div class="card-head"></div>
                        <div class="card-icon mt-2 d-flex justify-content-center align-items-center"
                            style="border-radius: 10px">
                            <i class="fa-solid fa-arrow-trend-up"></i>
                        </div>

                        <p class="mt-4">Active Loans</p>

                        <span>1 Loan(s)</span>
                    </div>
                    <div class="card-update">
                        <div class="update">
                            <i class="fa fa-arrow-up"></i>
                            <p>Active</p>
                        </div>
                    </div>
                </div>

                <div class="card-box">
                    <div class="card-transparent">
                        <div class="card-head"></div>
                        <div class="card-icon mt-2 d-flex justify-content-center align-items-center"
                            style="border-radius: 10px">
                            <i class="fa-solid fa-award"></i>
                        </div>

                        <p class="mt-4">Credit score</p>

                        <span>80 pts</span>
                    </div>
                    <div class="card-update">
                        <div class="update">
                            <p>Good Standing</p>
                        </div>
                    </div>
                </div>
            </div>
        </main>

        <section>
            <div class="card-box-parent">
                <h3>Loan Application</h3>

                <div class="card-parent mt-4 d-flex flex-column gap-4">

                    <div class="card-box">
                        <div class="d-flex justify-content-between align-items-center card-status">
                            <h4>Personal Loan</h4>

                            <div class="d-flex justify-content-center align-items-center gap-1 card-icon"
                                style="background-color: #DBFCE7; border-radius: 28px; color: #128B4A; padding: 0.5rem;">
                                <i class="fa-solid fa-check-circle"></i>
                                <p style="margin: 0; font-size: 13px;">Approved</p>
                            </div>
                        </div>

                        <p class="mt-2 tw:text-[#808080]">Applied on 11/15/2024</p>

                        <div class="w-100 mt-5 loan-status">
                            <div class="loan-value">
                                <p class="tw:text-[#808080]">Loan Amount</p>

                                <span>₱ 50,000</span>
                            </div>

                            <div class="loan-value">
                                <p class="tw:text-[#808080]">Purpose</p>

                                <span>Home Renovation</span>
                            </div>

                            <div class="loan-value">
                                <p class="tw:text-[#808080]">Monthly Payment</p>

                                <span>₱ 4,580</span>
                            </div>

                            <div class="loan-value">
                                <p class="tw:text-[#808080]">Remaining Balance</p>

                                <span>₱ 48,200</span>
                            </div>
                        </div>

                        <hr>

                        <p class="tw:text-[#808080]" style="margin: 2rem 0px 0px">Approved on 11/18/2024</p>
                    </div>

                    <div class="card-box">
                        <div class="d-flex justify-content-between align-items-center card-status">
                            <h4>Personal Loan</h4>

                            <div class="d-flex justify-content-center align-items-center gap-1 card-icon"
                                style="background-color: #FEF9C2; border-radius: 28px; color: #BA5F00; padding: 0.5rem;">
                                <i class="fa-solid fa-clock"></i>
                                <p style="margin: 0; font-size: 13px;">Pending</p>
                            </div>
                        </div>

                        <p class="mt-2 tw:text-[#808080]">Applied on 11/15/2024</p>

                        <div class="w-100 mt-5 loan-status">
                            <div class="loan-value">
                                <p class="tw:text-[#808080]">Loan Amount</p>

                                <span>₱ 50,000</span>
                            </div>

                            <div class="loan-value">
                                <p class="tw:text-[#808080]">Purpose</p>

                                <span>Home Renovation</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        @if (session("message"))
            <div class="message">
                <i class="fa fa-check-circle"></i>
                <div>
                    <p>{{ session("message") }}</p>
                </div>
            </div>

            <script>
                setTimeout(() => {
                    document.querySelector(".message").style.display = "none";
                }, 2000);
            </script>

        @endif
    </div>



    {{-- AOS animation link js --}}
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>

    {{-- Bootstrap link --}}
    {{--
    <script defer src="../bootstrap_folder/js/bootstrap.bundle.min.js"></script> --}}

    {{--
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
        crossorigin="anonymous"></script> --}}

    {{--
    <script>
        AOS.init();
    </script> --}}

    <script>
        // const message = document.querySelector(".message");

        // setTimeout(() => {
        //     document.querySelector(".message").style.display = "none";
        // }, 2000);
    </script>
</body>

</html>