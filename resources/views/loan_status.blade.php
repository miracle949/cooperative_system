<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Loan Status</title>
    <link rel="icon" href="images/websitelogo.png" type="image/png">

    {{-- AOS animation link css --}}
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

    {{-- css link --}}
    <link rel="stylesheet" href="css_folder/loan_status.css">
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

        <main class="p-5">
            <div class="card-box-parent d-flex justify-content-center align-items-center flex-wrap">
                <div data-aos="fade-zoom-in" data-aos-easing="ease-in-back" data-aos-delay="800" class="card-box">
                    <div class="d-flex justify-content-between align-items-center mt-2">
                        <h4>Personal Loan</h4>

                        <div class="d-flex justify-content-center align-items-center gap-1 card-icon"
                            style="background-color: #DBFCE7; border-radius: 28px; color: #128B4A; padding: 0.5rem; border: 1px solid rgba(0,0,0,0.3);">
                            <i class="fa-solid fa-check-circle"></i>
                            <p style="margin: 0; font-size: 13px;">Active</p>
                        </div>
                    </div>

                    <p class="mt-5">₱ 50,000</p>

                    <span class="tw:text-[#808080]">Applied 11/14/2025</span>
                </div>

                <div data-aos="fade-zoom-in" data-aos-easing="ease-in-back" data-aos-delay="1000" class="card-box">
                    <div class="d-flex justify-content-between align-items-center mt-2">
                        <h4>Emergency Loan</h4>

                        <div class="d-flex justify-content-center align-items-center gap-1 card-icon"
                            style="background-color: #FEF9C2; border-radius: 28px; color: #BA5F00; padding: 0.5rem; border: 1px solid rgba(0,0,0,0.3);">
                            <i class="fa-solid fa-check-circle"></i>
                            <p style="margin: 0; font-size: 13px;">Pending Review</p>
                        </div>
                    </div>

                    <p class="mt-5">₱ 10,000</p>

                    <span class="tw:text-[#808080]">Applied 11/14/2025</span>
                </div>
            </div>
        </main>

        <section class="ps-5 pe-5 pt-2 pb-5 d-flex justify-content-center align-items-center flex-column gap-4">

            <button style="all: unset; width: 100%;" class="d-flex justify-content-center align-items-center">
                <div data-aos="fade-zoom-in" data-aos-easing="ease-in-back" data-aos-delay="800" class="card-box-parent row">

                    <div class="col-6">
                        <h4>Personal Loan</h4>
                        <p class="tw:text-[#808080] m-0 mt-3">Home Renovation</p>
                    </div>

                    <div class="col-6">
                        <div class="d-flex justify-content-between align-items-center">
                            <span style="font-size: 15px;">Loan Progress</span>

                            <span style="font-size: 15px;">1 of 12 payments made</span>
                        </div>
                        <div class="tw:w-[100%] mt-3"
                            style="background-color: #D9D9D9; border-radius: 28px; padding: 13px 10px; position: relative;">
                            <div
                                style="width: 30%; height: 100%; background-color: #155DFC; position: absolute; border-radius: 28px; left: 15%; top: 50%; transform: translate(-50%, -50%); text-align: center;">

                                <span style="font-size: 13px; color: white;">8.3% completed</span>
                            </div>
                        </div>
                    </div>
                </div>
            </button>

            <div class="card-box-parent row">
                <div class="col-6">
                    <h4>Emergency Loan</h4>
                    <p class="tw:text-[#808080] m-0 mt-3">Home Renovation</p>
                </div>

                <div class="col-6">
                    <div class="d-flex justify-content-between align-items-center">
                        <span style="font-size: 15px;">Loan Progress</span>

                        <span style="font-size: 15px;">4 of 12 payments made</span>
                    </div>
                    <div class="tw:w-[100%] mt-3"
                        style="background-color: #D9D9D9; border-radius: 28px; padding: 13px 10px; position: relative;">
                        <div
                            style="width: 50%; height: 100%; background-color: #155DFC; position: absolute; border-radius: 28px; left: 25%; top: 50%; transform: translate(-50%, -50%); text-align: center;">

                            <span style="font-size: 13px; color: white;">25.5% completed</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-box-parent row">
                <div class="col-6">
                    <h4>House Loan</h4>
                    <p class="tw:text-[#808080] m-0 mt-3">Home Renovation</p>
                </div>

                <div class="col-6">
                    <div class="d-flex justify-content-between align-items-center">
                        <span style="font-size: 15px;">Loan Progress</span>

                        <span style="font-size: 15px;">7 of 12 payments made</span>
                    </div>
                    <div class="tw:w-[100%] mt-3"
                        style="background-color: #D9D9D9; border-radius: 28px; padding: 13px 10px; position: relative;">
                        <div
                            style="width: 70%; height: 100%; background-color: #155DFC; position: absolute; border-radius: 28px; left: 35%; top: 50%; transform: translate(-50%, -50%); text-align: center;">

                            <span style="font-size: 13px; color: white;">50.5% completed</span>
                        </div>
                    </div>
                </div>
            </div>

        </section>

        <section class="ps-5 pe-5 pt-2 pb-5 d-flex justify-content-center align-items-center" id="section2">
            <div class="card-box-parent">
                <div class="row">
                    <div class="col-12">
                        <h4>Payment History</h4>

                        <div class="mt-3">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Payment Date</th>
                                        <th>Amount</th>
                                        <th>Principal</th>
                                        <th>Interest</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>11/5/2025</td>
                                        <td>₱ 4,580</td>
                                        <td>₱ 4,247</td>
                                        <td>₱ 333</td>
                                        <td style="width: 179px;">
                                            <div class="d-flex justify-content-center align-items-center gap-1 card-icon"
                                                style="background-color: #DBFCE7; border-radius: 28px; color: #128B4A; padding: 0.5rem; border: 1px solid rgba(0,0,0,0.3); width: 100px;">
                                                <i class="fa-solid fa-check-circle"></i>
                                                <p style="margin: 0; font-size: 13px;">Active</p>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
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

    {{--
    <script>
        const 
    </script> --}}
</body>

</html>