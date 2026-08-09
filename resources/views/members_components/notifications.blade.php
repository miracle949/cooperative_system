<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifications</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Profile</title>

    {{-- AOS animation link css --}}
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

    {{-- css link --}}
    <link rel="stylesheet" href="css_folder/notifications.css">
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
                    <div>
                        <h3>Notifications</h3>
                        <p>Loan reminders, account activity and cooperative announcements</p>
                    </div>
                    <div>
                        <button>
                            <i class="fa fa-envelope-open"></i>

                            Mark all as read
                        </button>
                    </div>
                </div>

                <div class="main-body">
                    <div class="filters">
                        <div class="tab-group">
                            <div class="tab active">All <div class="tab-count">5</div>
                            </div>
                            <div class="tab">Unread <div class="tab-count">3</div>
                            </div>
                            <div class="tab">Loans</div>
                            <div class="tab">Share Capital</div>
                            <div class="tab">Savings</div>
                            <div class="tab">Announcements</div>
                        </div>
                    </div>

                    <div class="notif-group">
                        <div class="group-label">Today · Jul 25, 2026</div>
                        <div class="ledger-page">
                            <div class="notif-row">
                                <div class="notif-icon coral">
                                    <i class="fa fa-hand-holding-dollar"></i>
                                </div>
                                <div class="notif-body">
                                    <div class="notif-top">
                                        <div class="notif-title">Personal loan due soon</div>
                                        <div class="notif-time">9:02 AM</div>
                                    </div>
                                    <div class="notif-desc">Your personal loan 7 out of 12 is due on Aug 05, 2026</div>
                                    <div class="notif-meta-row">
                                        <div class="notif-chip due">Due in 12 days</div>
                                        <div class="notif-action">Pay Now</div>
                                    </div>
                                </div>
                            </div>
                            <div class="notif-row">
                                <div class="notif-icon blue">
                                    <i class="fa fa-piggy-bank"></i>
                                </div>
                                <div class="notif-body">
                                    <div class="notif-top">
                                        <div class="notif-title">Savings deposit received</div>
                                        <div class="notif-time">8:41 AM</div>
                                    </div>
                                    <div class="notif-desc">A deposit of ₱1,500.00 was posted to your Regular Savings account.</div>
                                    <div class="notif-meta-row">
                                        <!-- <div class="notif-chip due">Due in 12 days</div> -->
                                        <div class="notif-action">View transaction</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="notif-group">
                        <div class="group-label">Earlier this week</div>
                        <div class="ledger-page">
                            <div class="notif-row">
                                <div class="notif-icon coral">
                                    <i class="fa fa-hand-holding-dollar"></i>
                                </div>
                                <div class="notif-body">
                                    <div class="notif-top">
                                        <div class="notif-title">Personal loan due soon</div>
                                        <div class="notif-time">9:02 AM</div>
                                    </div>
                                    <div class="notif-desc">Your personal loan 7 out of 12 is due on Aug 05, 2026</div>
                                    <div class="notif-meta-row">
                                        <div class="notif-chip due">Due in 12 days</div>
                                        <div class="notif-action">Pay Now</div>
                                    </div>
                                </div>
                            </div>
                            <div class="notif-row">
                                <div class="notif-icon blue">
                                    <i class="fa fa-piggy-bank"></i>
                                </div>
                                <div class="notif-body">
                                    <div class="notif-top">
                                        <div class="notif-title">Savings deposit received</div>
                                        <div class="notif-time">8:41 AM</div>
                                    </div>
                                    <div class="notif-desc">A deposit of ₱1,500.00 was posted to your Regular Savings account.</div>
                                    <div class="notif-meta-row">
                                        <!-- <div class="notif-chip due">Due in 12 days</div> -->
                                        <div class="notif-action">View transaction</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>

</html>