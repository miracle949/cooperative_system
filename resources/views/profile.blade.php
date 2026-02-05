<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Profile</title>

    {{-- AOS animation link css --}}
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

    {{-- css link --}}
    <link rel="stylesheet" href="css_folder/profile.css">
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
            <div class="card-box-parent pt-5 ps-5 pe-5 pb-0">
                <div class="card-box">
                    <ul class="nav nav-pills d-flex justify-content-center align-items" id="pills-tab" role="tablist">
                        <li class="nav-item d-flex justify-content-center align-items-center" role="presentation">
                            <button class="nav-link active" id="pills-home-tab" data-bs-toggle="pill"
                                data-bs-target="#pills-home" type="button" role="tab" aria-controls="pills-home"
                                aria-selected="true">Personal Info</button>
                        </li>
                        <li class="nav-item d-flex justify-content-center align-items-center" role="presentation">
                            <button class="nav-link" id="pills-profile-tab" data-bs-toggle="pill"
                                data-bs-target="#pills-profile" type="button" role="tab" aria-controls="pills-profile"
                                aria-selected="false">Membership</button>
                        </li>
                        <li class="nav-item d-flex justify-content-center align-items-center" role="presentation">
                            <button class="nav-link" id="pills-contact-tab" data-bs-toggle="pill"
                                data-bs-target="#pills-contact" type="button" role="tab" aria-controls="pills-contact"
                                aria-selected="false">Transaction</button>
                        </li>
                        <li class="nav-item d-flex justify-content-center align-items-center" role="presentation">
                            <button class="nav-link" id="pills-contact-tab" data-bs-toggle="pill"
                                data-bs-target="#pills-settings" type="button" role="tab" aria-controls="pills-contact"
                                aria-selected="false">Settings</button>
                        </li>
                    </ul>
                </div>
                <div class="tab-content" id="pills-tabContent">
                    <div class="tab-pane fade show active" id="pills-home" role="tabpanel"
                        aria-labelledby="pills-home-tab" tabindex="0">

                        <div class="mt-5">
                            <div class="row">
                                <div class="col-6">
                                    <div class="d-flex justify-content-left align-items-center gap-4">
                                        <div>
                                            <img src="images/unnamed.png" width="150px" height="150px" alt=""
                                                style="border-radius: 50%">
                                        </div>

                                        <div>
                                            <h4 style="font-size: 20px">Jhun Gerald Amihan</h4>

                                            <p class="tw:text-[#808080]">Member ID: COOP-001</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-6 text-end d-flex justify-content-end align-items-center">
                                    <button class="btn btn-primary"><i class="fa fa-edit"></i> Edit Profile</button>
                                </div>
                            </div>
                        </div>

                        <div class="mt-5">
                            <div class="row">
                                <div class="col-lg-6 col-md-12">
                                    <div class="label-text" style="font-size: 17px;">
                                        <label><i class="fa fa-user"></i> Fullname:</label>

                                        <p>Jhun Gerald Amihan</p>
                                    </div>

                                    <div class="label-text" style="font-size: 17px;">
                                        <label><i class="fa fa-phone"></i> Phone number:</label>

                                        <p>+63 912 345 6789</p>
                                    </div>

                                    <div class="label-text" style="font-size: 17px;">
                                        <label><i class="fa fa-location-pin"></i> Address:</label>

                                        <p>123 Main Street, Quezon City, Metro Manila</p>
                                    </div>
                                </div>

                                <div class="col-lg-6 col-md-12">
                                    <div class="label-text" style="font-size: 17px;">
                                        <label><i class="fa fa-envelope"></i> Email Address:</label>

                                        <p>jhungerald@gmail.com</p>
                                    </div>

                                    <div class="label-text" style="font-size: 17px;">
                                        <label><i class="fa fa-calendar-days"></i> Member Since:</label>

                                        <p>January 2023</p>
                                    </div>

                                    <div class="label-text" style="font-size: 17px;">
                                        <label><i class="fa fa-award"></i> Credit Score:</label>

                                        <p>80 points</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab"
                        tabindex="0">

                        <div class="mt-5 mb-5">
                            <div class="d-flex justify-content-center align-items-center gap-5">
                                <div class="card-tab">
                                    <div class="d-flex justify-content-left align-items-center gap-3">
                                        <div class="d-flex justify-content-center align-items-center"
                                            style="width: 55px; height: 55px; background-color: #5295FF; border-radius: 10px;">
                                            <i class="fa fa-check-circle" style="color: white; font-size: 25px;"></i>
                                        </div>

                                        <div>
                                            <p style="margin: 0">Membership Status</p>
                                        </div>
                                    </div>


                                    <div class="row" style="margin-top: 2rem">
                                        <div class="col-6">
                                            <p class="tw:text-[#808080]">Status</p>
                                        </div>

                                        <div class="col-6 text-end">
                                            <span>Active</span>
                                        </div>
                                    </div>

                                    <div class="row" style="margin-top: 0.5rem">
                                        <div class="col-6">
                                            <p class="tw:text-[#808080]">Member ID</p>
                                        </div>

                                        <div class="col-6 text-end">
                                            <span>COOP-001</span>
                                        </div>
                                    </div>

                                    <div class="row" style="margin-top: 0.5rem">
                                        <div class="col-6">
                                            <p class="tw:text-[#808080]">Member Since</p>
                                        </div>

                                        <div class="col-6 text-end">
                                            <span>January 2023</span>
                                        </div>
                                    </div>

                                    <div class="row" style="margin-top: 0.5rem">
                                        <div class="col-6">
                                            <p class="tw:text-[#808080]">Membership Type</p>
                                        </div>

                                        <div class="col-6 text-end">
                                            <span>Regular</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="card-tab">
                                    <div class="d-flex justify-content-left align-items-center gap-3">
                                        <div class="d-flex justify-content-center align-items-center"
                                            style="width: 55px; height: 55px; background-color: #00A63E; border-radius: 10px;">
                                            <i class="fa fa-wallet" style="color: white; font-size: 25px;"></i>
                                        </div>

                                        <div>
                                            <p style="margin: 0">Savings</p>
                                        </div>
                                    </div>


                                    <div class="row" style="margin-top: 2rem">
                                        <div class="col-6">
                                            <p class="tw:text-[#808080]">Total Shares</p>
                                        </div>

                                        <div class="col-6 text-end">
                                            <span>Active</span>
                                        </div>
                                    </div>

                                    <div class="row" style="margin-top: 0.5rem">
                                        <div class="col-6">
                                            <p class="tw:text-[#808080]">Share Value</p>
                                        </div>

                                        <div class="col-6 text-end">
                                            <span>₱ 100 per share</span>
                                        </div>
                                    </div>

                                    <div class="row" style="margin-top: 0.5rem">
                                        <div class="col-6">
                                            <p class="tw:text-[#808080]">Total Value</p>
                                        </div>

                                        <div class="col-6 text-end">
                                            <span>₱ 15,000</span>
                                        </div>
                                    </div>

                                    <div class="row" style="margin-top: 0.5rem">
                                        <div class="col-6">
                                            <p class="tw:text-[#808080]">Dividend Rate</p>
                                        </div>

                                        <div class="col-6 text-end">
                                            <span>8% per annum</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-5">
                            <div class="d-flex justify-content-center align-items-center pb-5">
                                <div class="card-tab-benefits">
                                    <h4>Membership Benefits</h4>

                                    <div class="row mt-4">
                                        <div class="col-5">
                                            <div class="d-flex justify-content-left align-items-center gap-2">
                                                <i class="fa fa-check-circle"
                                                    style="color: #128B4A; font-size: 20px;"></i>
                                                <p style="margin: 0">Access to all loan products</p>
                                            </div>

                                            <div class="d-flex justify-content-left align-items-center gap-2 mt-3">
                                                <i class="fa fa-check-circle"
                                                    style="color: #128B4A; font-size: 20px;"></i>
                                                <p style="margin: 0">Annual patronage refund</p>
                                            </div>

                                            <div class="d-flex justify-content-left align-items-center gap-2 mt-3">
                                                <i class="fa fa-check-circle"
                                                    style="color: #128B4A; font-size: 20px;"></i>
                                                <p style="margin: 0">Member discount programs</p>
                                            </div>

                                            <div class="d-flex justify-content-left align-items-center gap-2 mt-3">
                                                <i class="fa fa-check-circle"
                                                    style="color: #128B4A; font-size: 20px;"></i>
                                                <p style="margin: 0">Emergency loan assistance</p>
                                            </div>
                                        </div>

                                        <div class="col-6">
                                            <div class="d-flex justify-content-left align-items-center gap-2">
                                                <i class="fa fa-check-circle"
                                                    style="color: #128B4A; font-size: 20px;"></i>
                                                <p style="margin: 0">Competitive savings dividends</p>
                                            </div>

                                            <div class="d-flex justify-content-left align-items-center gap-2 mt-3">
                                                <i class="fa fa-check-circle"
                                                    style="color: #128B4A; font-size: 20px;"></i>
                                                <p style="margin: 0">Free financial consultation</p>
                                            </div>

                                            <div class="d-flex justify-content-left align-items-center gap-2 mt-3">
                                                <i class="fa fa-check-circle"
                                                    style="color: #128B4A; font-size: 20px;"></i>
                                                <p style="margin: 0">Educational seminars</p>
                                            </div>

                                            <div class="d-flex justify-content-left align-items-center gap-2 mt-3">
                                                <i class="fa fa-check-circle"
                                                    style="color: #128B4A; font-size: 20px;"></i>
                                                <p style="margin: 0">Insurance coverage</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="tab-pane fade" id="pills-contact" role="tabpanel" aria-labelledby="pills-contact-tab"
                        tabindex="0">

                        <div class="mt-5">
                            <div class="transaction-tab">
                                <h4>Transaction History</h4>

                                <p class="tw:text-[#808080]">View all your account transactions</p>

                                <div class="mt-4">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>Date</th>
                                                <th>Type</th>
                                                <th>Description</th>
                                                <th>Amount</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>11/5/2025</td>
                                                <td>
                                                    <div class="d-flex justify-content-center align-items-center gap-1 card-icon"
                                                        style="background-color: #DBEAFE; border-radius: 28px; color: #1447E6; padding: 0.5rem; border: 1px solid rgba(0,0,0,0.3); width: 120px;">
                                                        <p style="margin: 0; font-size: 13px; font-weight: semibold;">
                                                            Loan payment</p>
                                                    </div>
                                                </td>
                                                <td style="width: 250px; line-height: 35px;">Personal Loan - Monthly
                                                    Payment</td>
                                                <td style="color: #E7000B">₱ 4,580</td>
                                                <td>
                                                    <div class="d-flex justify-content-center align-items-center gap-1 card-icon"
                                                        style="background-color: #DBFCE7; border-radius: 28px; color: #128B4A; padding: 0.5rem; border: 1px solid rgba(0,0,0,0.3); width: 100px;">
                                                        <p style="margin: 0; font-size: 13px; font-weight: semibold;">
                                                            Completed</p>
                                                    </div>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td>11/5/2025</td>
                                                <td>
                                                    <div class="d-flex justify-content-center align-items-center gap-1 card-icon"
                                                        style="background-color: #DBEAFE; border-radius: 28px; color: #1447E6; padding: 0.5rem; border: 1px solid rgba(0,0,0,0.3); width: 120px;">
                                                        <p style="margin: 0; font-size: 13px; font-weight: semibold;">
                                                            Contribution</p>
                                                    </div>
                                                </td>
                                                <td style="width: 250px; line-height: 35px; height: 70px;">Regular
                                                    Savings</td>
                                                <td style="color: #00A63E">+₱ 1,500</td>
                                                <td>
                                                    <div class="d-flex justify-content-center align-items-center gap-1 card-icon"
                                                        style="background-color: #DBFCE7; border-radius: 28px; color: #128B4A; padding: 0.5rem; border: 1px solid rgba(0,0,0,0.3); width: 100px;">
                                                        <p style="margin: 0; font-size: 13px; font-weight: semibold;">
                                                            Completed</p>
                                                    </div>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td>11/5/2025</td>
                                                <td>
                                                    <div class="d-flex justify-content-center align-items-center gap-1 card-icon"
                                                        style="background-color: #DBEAFE; border-radius: 28px; color: #1447E6; padding: 0.5rem; border: 1px solid rgba(0,0,0,0.3); width: 120px;">
                                                        <p style="margin: 0; font-size: 13px; font-weight: semibold;">
                                                            Contribution</p>
                                                    </div>
                                                </td>
                                                <td style="height: 70px;">Share Capital</td>
                                                <td style="color: #00A63E">+₱ 500</td>
                                                <td>
                                                    <div class="d-flex justify-content-center align-items-center gap-1 card-icon"
                                                        style="background-color: #DBFCE7; border-radius: 28px; color: #128B4A; padding: 0.5rem; border: 1px solid rgba(0,0,0,0.3); width: 100px;">
                                                        <p style="margin: 0; font-size: 13px; font-weight: semibold;">
                                                            Completed</p>
                                                    </div>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td>11/5/2025</td>
                                                <td>
                                                    <div class="d-flex justify-content-center align-items-center gap-1 card-icon"
                                                        style="background-color: #DBEAFE; border-radius: 28px; color: #1447E6; padding: 0.5rem; border: 1px solid rgba(0,0,0,0.3); width: 120px;">
                                                        <p style="margin: 0; font-size: 13px; font-weight: semibold;">
                                                            Withdrawal</p>
                                                    </div>
                                                </td>
                                                <td style="width: 250px; line-height: 35px; height: 70px;">Partial
                                                    Savings Withdrawal</td>
                                                <td style="color: #E7000B">₱ 2,000</td>
                                                <td>
                                                    <div class="d-flex justify-content-center align-items-center gap-1 card-icon"
                                                        style="background-color: #DBFCE7; border-radius: 28px; color: #128B4A; padding: 0.5rem; border: 1px solid rgba(0,0,0,0.3); width: 100px;">
                                                        <p style="margin: 0; font-size: 13px; font-weight: semibold;">
                                                            Completed</p>
                                                    </div>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td>11/5/2025</td>
                                                <td>
                                                    <div class="d-flex justify-content-center align-items-center gap-1 card-icon"
                                                        style="background-color: #DBEAFE; border-radius: 28px; color: #1447E6; padding: 0.5rem; border: 1px solid rgba(0,0,0,0.3); width: 120px;">
                                                        <p style="margin: 0; font-size: 13px; font-weight: semibold;">
                                                            Contribution</p>
                                                    </div>
                                                </td>
                                                <td style="width: 250px; line-height: 35px; height: 70px;">Regular
                                                    Savings</td>
                                                <td style="color: #00A63E">+₱ 1,500</td>
                                                <td>
                                                    <div class="d-flex justify-content-center align-items-center gap-1 card-icon"
                                                        style="background-color: #DBFCE7; border-radius: 28px; color: #128B4A; padding: 0.5rem; border: 1px solid rgba(0,0,0,0.3); width: 100px;">
                                                        <p style="margin: 0; font-size: 13px; font-weight: semibold;">
                                                            Completed</p>
                                                    </div>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td>11/5/2025</td>
                                                <td>
                                                    <div class="d-flex justify-content-center align-items-center gap-1 card-icon"
                                                        style="background-color: #DBEAFE; border-radius: 28px; color: #1447E6; padding: 0.5rem; border: 1px solid rgba(0,0,0,0.3); width: 120px;">
                                                        <p style="margin: 0; font-size: 13px; font-weight: semibold;">
                                                            Contribution</p>
                                                    </div>
                                                </td>
                                                <td style="width: 250px; line-height: 35px; height: 70px;">Regular
                                                    Savings</td>
                                                <td style="color: #00A63E">+₱ 1,500</td>
                                                <td>
                                                    <div class="d-flex justify-content-center align-items-center gap-1 card-icon"
                                                        style="background-color: #DBFCE7; border-radius: 28px; color: #128B4A; padding: 0.5rem; border: 1px solid rgba(0,0,0,0.3); width: 100px;">
                                                        <p style="margin: 0; font-size: 13px; font-weight: semibold;">
                                                            Completed</p>
                                                    </div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="tab-pane fade" id="pills-settings" role="tabpanel" aria-labelledby="pills-disabled-tab"
                        tabindex="0">

                        <div class="mt-5 mb-5">
                            <div class="settings-tab">
                                <h4>Notification Preferences</h4>

                                <div class="row">
                                    <div class="mt-4">
                                        <div class="col-6">
                                            <p>Email Notifications</p>

                                            <span>Receive updates via Email</span>
                                        </div>
                                    </div>

                                    <div class="mt-3">
                                        <div class="col-6">
                                            <p>SMS Notifications</p>

                                            <span>Receive updates via SMS</span>
                                        </div>
                                    </div>

                                    <div class="mt-3">
                                        <div class="col-6">
                                            <p>Monthly Statements</p>

                                            <span>Receive monthly account statements</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="security-tab mt-4">
                                <h4>Security</h4>

                                <div class="mt-4 security-box">
                                    <span>Change Password</span>

                                    <p class="tw:text-[#808080]" style="margin: 0">Update your account password</p>
                                </div>

                                <div class="mt-3 security-box">
                                    <span>Two-Factor Authentication</span>

                                    <p class="tw:text-[#808080]" style="margin: 0">Add extra security to your account</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
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