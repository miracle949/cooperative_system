<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Loan Status</title>
    <link rel="icon" href="images/websitelogo.png" type="image/png">

    {{-- AOS animation link css --}}
    {{--
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet"> --}}

    {{-- css link --}}
    <link rel="stylesheet" href="css_folder/loan_status.css">
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
        <div class="sidebar">
            {{-- <h3>Lending Applications</h3> --}}

            <div class="sidebar-parent-box">
                <div class="sidebar-box">
                    <div class="sidebar-header">
                        <h4>Personal Lending</h4>

                        <div class="parent-active">
                            <div class="dot"></div>
                            <p>Active</p>
                        </div>
                    </div>
                    <div class="sidebar-sub-header">
                        <p>Home Renovation</p>

                        <p>11/14/2026</p>
                    </div>
                    <div class="side-body">
                        <h5>₱50,000</h5>

                        <div class="parent-progress">
                            <div class="progress-box"></div>
                        </div>

                        <div class="paid">
                            <p>8% paid</p>

                            <p>1/2 payments</p>
                        </div>
                    </div>
                </div>

                <div class="sidebar-box">
                    <div class="sidebar-header">
                        <h4>Emergency Lending</h4>

                        <div class="parent-active">
                            <div class="dot"></div>
                            <p>Active</p>
                        </div>
                    </div>
                    <div class="sidebar-sub-header">
                        <p>Home Renovation</p>

                        <p>11/14/2026</p>
                    </div>
                    <div class="side-body">
                        <h5>₱50,000</h5>

                        <div class="parent-progress">
                            <div class="progress-box"></div>
                        </div>

                        <div class="paid">
                            <p>8% paid</p>

                            <p>1/2 payments</p>
                        </div>
                    </div>
                </div>

                <div class="sidebar-box">
                    <div class="sidebar-header">
                        <h4>Educational Lending</h4>

                        <div class="parent-active">
                            <div class="dot"></div>
                            <p>Pending</p>
                        </div>
                    </div>
                    <div class="sidebar-sub-header">
                        <p>Home Renovation</p>

                        <p>11/14/2026</p>
                    </div>
                    <div class="side-body">
                        <h5>₱50,000</h5>

                        <div class="parent-progress">
                            <div class="progress-box"></div>
                        </div>

                        <div class="paid">
                            <p>8% paid</p>

                            <p>1/2 payments</p>
                        </div>
                    </div>
                </div>

                <div class="sidebar-box">
                    <div class="sidebar-header">
                        <h4>Personal Lending</h4>

                        <div class="parent-active">
                            <div class="dot"></div>
                            <p>Active</p>
                        </div>
                    </div>
                    <div class="sidebar-sub-header">
                        <p>Home Renovation</p>

                        <p>11/14/2026</p>
                    </div>
                    <div class="side-body">
                        <h5>₱50,000</h5>

                        <div class="parent-progress">
                            <div class="progress-box"></div>
                        </div>

                        <div class="paid">
                            <p>8% paid</p>

                            <p>1/2 payments</p>
                        </div>
                    </div>
                </div>

                <div class="sidebar-box">
                    <div class="sidebar-header">
                        <h4>Personal Lending</h4>

                        <div class="parent-active">
                            <div class="dot"></div>
                            <p>Active</p>
                        </div>
                    </div>
                    <div class="sidebar-sub-header">
                        <p>Home Renovation</p>

                        <p>11/14/2026</p>
                    </div>
                    <div class="side-body">
                        <h5>₱50,000</h5>

                        <div class="parent-progress">
                            <div class="progress-box"></div>
                        </div>

                        <div class="paid">
                            <p>8% paid</p>

                            <p>1/2 payments</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="rightbar">
            <main>

                <div class="main-parent">
                    <div class="main-amount">
                        <p>Lending Amount</p>

                        <h3>₱ 50,000.00</h3>

                        <p>Active</p>
                        {{-- <div class="active-parent">
                            <div class="dot"></div>
                            <p>Active</p>
                        </div> --}}
                    </div>
                    <div class="main-text">
                        <p>Home Renovation</p>
                        
                        <h3>Personal Lending</h3>

                        <p>Applied: 11/14/2026</p>
                    </div>
                </div>

                <div class="card-box-parent">

                    <div class="card-box">                        

                        <p>Monthly Due</p>

                        <div class="d-flex justify-content-between align-items-center">
                            <h4>₱8,100</h4>
                        </div>

                        <span>Applied 11/14/2025</span>
                    </div>

                    <div class="card-box">

                        <p>Remaining Balance</p>

                        <div class="d-flex justify-content-between align-items-center">
                            <h4>₱27,154</h4>
                        </div>

                        <span>Applied 11/14/2025</span>
                    </div>

                    <div class="card-box">

                        <p>Interest Rate</p>

                        <div class="d-flex justify-content-between align-items-center">
                            <h4>6%</h4>
                        </div>

                        <span>Applied 11/14/2025</span>
                    </div>
                </div>
            </main>

            <section class="d-flex justify-content-center align-items-center flex-column gap-4">

                <div class="card-box-parent">
                    <h4>Repayment Progress</h4>

                    <div class="parent-progress">
                        <div class="progress-box"></div>
                    </div>

                    <div class="completed">
                        <p>8% completed</p>

                        <p>1 of 12 payments mode</p>
                    </div>
                </div>

                {{-- <button style="all: unset; width: 100%;" class="d-flex justify-content-center align-items-center">
                    <div class="card-box-parent row">

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
                </div> --}}

            </section>

            <section class="d-flex justify-content-center align-items-center" id="section2">
                <div class="card-box-parent">
                    <div class="row">
                        <div class="col-12">
                            <h4>Payment History</h4>

                            <div class="mt-3 overflow-x-auto">
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
                                                    style="background-color: #efefef; border-radius: 28px; color: var(--green); padding: 0.3rem; width: 100px;">
                                                    <div class="dot"></div>
                                                    <p style="margin: 0; font-size: 13px; font-weight: 600;">Active</p>
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
    </div>


    {{-- AOS animation link js --}}
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>


    <script>
        AOS.init();
    </script>
</body>

</html>