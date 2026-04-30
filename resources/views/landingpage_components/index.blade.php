<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Kingsland Pala Pala Cooperative</title>

    <link rel="icon" href="images/websitelogo.png" type="image/png">

    {{-- AOS animation link css --}}
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

    {{-- css link --}}
    <link rel="stylesheet" href="css_folder/style.css">
    <link rel="stylesheet" href="css_folder/loading.css">

    <link rel="stylesheet" href="font-awesome-icon/css/all.min.css">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

</head>

<body style="background-color: #ffffff">

    <div class="container-fluid p-0 m-0">

        {{-- <div class="background-green"></div> --}}

        @include("components.navbar")
        @include("components.offcanvas")

        <main class="z-1">

            <section class="carousel" id="carousel" style="margin-top: 64px;">
                <div class="carousel-track" id="carouselTrack">

                    <!-- SLIDE 1 -->
                    <div class="slide active">
                        <img class="slide-bg" src="../images/1.jpg" alt="Trusted Cooperative Partner" />
                        <div class="slide-overlay"></div>
                        <div class="slide-chevron"></div>
                        <div class="slide-content">
                            <div class="slide-eyebrow">Trusted Cooperative Partner</div>
                            <h2 class="slide-title">Empowering Members<br /><em>with Financial Solutions</em></h2>
                            <div class="slide-rule"></div>
                            <p class="slide-body">A member-owned cooperative built on trust and mutual growth —
                                empowering every member with world-class financial tools.</p>
                            <a href="#" class="slide-btn">Learn More &nbsp;→</a>
                        </div>
                    </div>

                    <!-- SLIDE 2 -->
                    <div class="slide">
                        <img class="slide-bg" src="../images/2.jpg" alt="Services at Your Fingertips" />
                        <div class="slide-overlay"></div>
                        <div class="slide-chevron"></div>
                        <div class="slide-content">
                            <div class="slide-eyebrow">Digital Services</div>
                            <h2 class="slide-title">Services at Your<br /><em>Fingertips</em></h2>
                            <div class="slide-rule"></div>
                            <p class="slide-body">Access your cooperative services anytime, anywhere — loans, dividends,
                                voting, and more from your device.</p>
                            <a href="#" class="slide-btn">Learn More &nbsp;→</a>
                        </div>
                    </div>

                    <!-- SLIDE 3 -->
                    <div class="slide">
                        <img class="slide-bg" src="../images/3.jpg" alt="Your Loan, Approved!" />
                        <div class="slide-overlay"></div>
                        <div class="slide-chevron"></div>
                        <div class="slide-content">
                            <div class="slide-eyebrow">Loan Services</div>
                            <h2 class="slide-title">Your Loan,<br /><em>Approved!</em></h2>
                            <div class="slide-rule"></div>
                            <p class="slide-body">We make loan applications simple, fast, and stress-free — so you can
                                focus on what matters most.</p>
                            <a href="#" class="slide-btn">Learn More &nbsp;→</a>
                        </div>
                    </div>

                    <!-- SLIDE 4 -->
                    <div class="slide">
                        <img class="slide-bg" src="../images/4.jpg" alt="Growing Together as One" />
                        <div class="slide-overlay"></div>
                        <div class="slide-chevron"></div>
                        <div class="slide-content">
                            <div class="slide-eyebrow">Community</div>
                            <h2 class="slide-title">Growing Together<br /><em>as One</em></h2>
                            <div class="slide-rule"></div>
                            <p class="slide-body">We are committed to uplifting every member through unity, shared
                                values, and community-driven growth.</p>
                            <a href="#" class="slide-btn">Learn More &nbsp;→</a>
                        </div>
                    </div>

                </div><!-- /track -->

                <button class="carousel-prev" id="prevBtn">&#8592;</button>
                <button class="carousel-next" id="nextBtn">&#8594;</button>

                <div class="carousel-dots" id="carouselDots">
                    <button class="dot active"></button>
                    <button class="dot"></button>
                    <button class="dot"></button>
                    <button class="dot"></button>
                </div>

                <div class="carousel-counter"><span id="slideNum">01</span> / 04</div>

                <div class="carousel-progress" id="progress"></div>
            </section>

            {{-- <div class="main-text">
                <span>Established 2010 · 1,200 - Members</span>

                <h2>Built on <b>Trust,</b> Growing <b>Together</b></h2>

                <p>Members Cooperative empowers members through shared savings, affordable loans, and community-driven
                    livelihood programs.</p>

                <div class="main-buttons">
                    <a href="{{ route(" RegisterPage") }}">Become a Member <i class="fa fa-arrow-right"></i></a>

                    <a href="#">Learn More</a>
                </div>

                <div class="experience">
                    <div class="total-experience">
                        <h4>₱48M</h4>
                        <span>Total Capital Share</span>
                    </div>

                    <div class="total-experience">
                        <h4>1,240</h4>
                        <span>Active Members</span>
                    </div>

                    <div class="total-experience">
                        <h4>15+</h4>
                        <span>Years of Service</span>
                    </div>
                </div>
            </div>
            <div class="main-image">
                <div class="overlay"></div>
                <div class="image-wrapper wrapper1">
                    <img class="image1" src="images/coop1.jpg" alt="">
                </div>

                <div class="image-wrapper wrapper2">
                    <img class="image2" src="images/coop2.jpg" alt="">
                </div>

                <div class="image-wrapper wrapper3">
                    <img class="image3" src="images/coop3.jpg" alt="">
                </div>
            </div> --}}
        </main>

        <div class="marquee-bar">
            <div class="marquee-track">
                <span class="marquee-item">Member Governance <span class="marquee-sep">✦</span></span>
                <span class="marquee-item">Loan & Savings Management <span class="marquee-sep">✦</span></span>
                <span class="marquee-item">Financial Reporting <span class="marquee-sep">✦</span></span>
                <span class="marquee-item">Digital Voting <span class="marquee-sep">✦</span></span>
                <span class="marquee-item">Dividend Computation <span class="marquee-sep">✦</span></span>
                <span class="marquee-item">Compliance Tools <span class="marquee-sep">✦</span></span>
                <span class="marquee-item">Member Portal <span class="marquee-sep">✦</span></span>
                <span class="marquee-item">Audit Trails <span class="marquee-sep">✦</span></span>
                <span class="marquee-item">Member Governance <span class="marquee-sep">✦</span></span>
                <span class="marquee-item">Loan & Savings Management <span class="marquee-sep">✦</span></span>
                <span class="marquee-item">Financial Reporting <span class="marquee-sep">✦</span></span>
                <span class="marquee-item">Digital Voting <span class="marquee-sep">✦</span></span>
                <span class="marquee-item">Dividend Computation <span class="marquee-sep">✦</span></span>
                <span class="marquee-item">Compliance Tools <span class="marquee-sep">✦</span></span>
                <span class="marquee-item">Member Portal <span class="marquee-sep">✦</span></span>
                <span class="marquee-item">Audit Trails <span class="marquee-sep">✦</span></span>
            </div>
        </div>

        <section class="d-flex justify-content-between gap-3 pe-5 ps-5 pt-3" id="section1">

            <div class="card-side">
                <span>What We Offer</span>

                <h2 class="text-left mt-3">Tools Built for <b>Modern</b> Cooperatives</h2>

                <p class="text-left">We offer more than
                    just financial services
                    - we
                    provide a supportive community
                    dedicated to your financial well-being.</p>
            </div>

            <div class="card-box-parent">
                <div class="card-box reveal reveal-delay-1">
                    <div class="card-icon">
                        <p>01</p>
                    </div>
                    <div class="card-description">
                        <p>Digital Reporting</p>
                        <span>Automated financial reports and real-time dashboards, always accurate and on time.</span>
                    </div>
                </div>

                <div class="card-box reveal reveal-delay-2">
                    <div class="card-icon">
                        <p>02</p>
                    </div>
                    <div class="card-description">
                        <p>Loan Management</p>
                        <span>Automated financial reports and real-time dashboards, always accurate and on time.</span>
                    </div>
                </div>

                <div class="card-box reveal reveal-delay-1">
                    <div class="card-icon">
                        <p>03</p>
                    </div>
                    <div class="card-description">
                        <p>Member Portal</p>
                        <span>Automated financial reports and real-time dashboards, always accurate and on time.</span>
                    </div>
                </div>

                <div class="card-box reveal reveal-delay-2">
                    <div class="card-icon">
                        <p>04</p>
                    </div>
                    <div class="card-description">
                        <p>Secure Savings</p>
                        <span>Automated financial reports and real-time dashboards, always accurate and on time.</span>
                    </div>
                </div>

                <div class="card-box reveal reveal-delay-1">
                    <div class="card-icon">
                        <p>05</p>
                    </div>
                    <div class="card-description">
                        <p>Secure Savings</p>
                        <span>Automated financial reports and real-time dashboards, always accurate and on time.</span>
                    </div>
                </div>
            </div>

            {{-- <div class="d-flex justify-content-center align-items-center column-gap-5 flex-wrap">

                <div class="card reveal reveal-delay-1 tw:p-[1rem] tw:mt-[2rem]" style="border-radius: 20px">
                    <div class="card-body tw:pb-[1.5rem]">

                        <div class="card-icon d-flex justify-content-center align-items-center p-0 border-0"
                            style="border-radius: 20px;">
                            <i class="fa fa-users"></i>
                        </div>

                        <h3 class="mt-4" style="font-size: 18px">Community Driven</h3>

                        <p class="tw:w-[250px] tw:text-base/8  tw:text-[14.5px] mt-3">Join a community of members
                            working together towards financial prosperity and mutual support.
                        </p>
                    </div>
                </div>

                <div class="card reveal reveal-delay-2 tw:p-[1rem] tw:mt-[2rem]" style="border-radius: 20px">
                    <div class="card-body tw:pb-[1.5rem]">

                        <div class="card-icon d-flex justify-content-center align-items-center p-0 border-0"
                            style="border-radius: 20px;">
                            <i class="fa fa-chart-line"></i>
                        </div>

                        <h3 class="mt-4" style="font-size: 18px">Flexible Loans</h3>

                        <p class="tw:w-[250px] tw:text-base/8  tw:text-[14.5px] mt-3">Access various loan types with
                            competitive rates designed to meet your personal and business
                            needs.
                        </p>
                    </div>
                </div>

                <div class="card reveal reveal-delay-1 tw:p-[1rem] tw:mt-[2rem]" style="border-radius: 20px">
                    <div class="card-body tw:pb-[1.5rem]">

                        <div class="card-icon d-flex justify-content-center align-items-center p-0 border-0"
                            style="border-radius: 20px;">
                            <i class="fa fa-shield"></i>
                        </div>

                        <h3 class="mt-4" style="font-size: 18px">Secure Savings</h3>

                        <p class="tw:w-[250px] tw:text-base/8  tw:text-[14.5px] mt-3">Build your savings with confidence
                            through our secure and transparent cooperative system.
                        </p>
                    </div>
                </div>
            </div> --}}
        </section>

        <section class="d-flex justify-left align-items-left flex-column pe-5 ps-5" id="products_services">



            <div class="header">
                <span>Loan Products</span>

                <h2 class="text-left reveal reveal-delay-1">Choose a Loan Built
                    for <b>Your Needs</b></h2>

                <p class="text-left tw:text-[16px] reveal reveal-delay-2">Choose from our
                    variety of loan
                    products
                    designed to meet your financial needs.
                </p>
            </div>

            <div class="card-parent">

                <div class="card reveal reveal-delay-1">
                    <div class="popular">
                        <div class="popular-text">Most Popular</div>
                    </div>
                    <div class="card-icon">
                        <i class="fa fa-user"></i>
                    </div>

                    <span>Loan</span>

                    <h4>Personal Loan</h4>

                    <p>For personal expenses, life events, home improvements, and everything in between.</p>

                    <hr>

                    <div class="starting">
                        <p>Starting at</p>
                        <p><b>2%</b></p>
                        <p>/ monthly</p>
                    </div>

                    <div class="check-parent">
                        <div class="check-text">
                            <div class="check-icon">
                                <i class="fa fa-check"></i>
                            </div>
                            <p>Quick approval processs</p>
                        </div>
                        <div class="check-text">
                            <div class="check-icon">
                                <i class="fa fa-check"></i>
                            </div>
                            <p>Flexible repayment terms</p>
                        </div>
                        <div class="check-text">
                            <div class="check-icon">
                                <i class="fa fa-check"></i>
                            </div>
                            <p>No hidden charges</p>
                        </div>
                    </div>

                    <div class="apply">
                        <a href="{{ route("LoginPage") }}">
                            <p>Apply Now</p>
                            &nbsp;→
                        </a>

                    </div>
                </div>

                <div class="card reveal reveal-delay-2">
                    <div class="card-icon">
                        <i class="fa fa-briefcase"></i>
                    </div>

                    <span>Loan</span>

                    <h4>Business Loan</h4>

                    <p>For personal expenses, life events, home improvements, and everything in between.</p>

                    <hr>

                    <div class="starting">
                        <p>Starting at</p>
                        <p><b>2%</b></p>
                        <p>/ monthly</p>
                    </div>

                    <div class="check-parent">
                        <div class="check-text">
                            <div class="check-icon">
                                <i class="fa fa-check"></i>
                            </div>
                            <p>Quick approval processs</p>
                        </div>
                        <div class="check-text">
                            <div class="check-icon">
                                <i class="fa fa-check"></i>
                            </div>
                            <p>Flexible repayment terms</p>
                        </div>
                        <div class="check-text">
                            <div class="check-icon">
                                <i class="fa fa-check"></i>
                            </div>
                            <p>No hidden charges</p>
                        </div>
                    </div>

                    <div class="apply">
                        <a href="{{ route("LoginPage") }}">
                            <p>Apply Now</p>
                            &nbsp;→
                        </a>

                    </div>
                </div>

                <div class="card reveal reveal-delay-1">
                    <div class="card-icon">
                        <i class="fa fa-hand-holding-medical"></i>
                    </div>

                    <span>Loan</span>

                    <h4>Emergency Loan</h4>

                    <p>For personal expenses, life events, home improvements, and everything in between.</p>

                    <hr>

                    <div class="starting">
                        <p>Starting at</p>
                        <p><b>2%</b></p>
                        <p>/ monthly</p>
                    </div>

                    <div class="check-parent">
                        <div class="check-text">
                            <div class="check-icon">
                                <i class="fa fa-check"></i>
                            </div>
                            <p>Quick approval processs</p>
                        </div>
                        <div class="check-text">
                            <div class="check-icon">
                                <i class="fa fa-check"></i>
                            </div>
                            <p>Flexible repayment terms</p>
                        </div>
                        <div class="check-text">
                            <div class="check-icon">
                                <i class="fa fa-check"></i>
                            </div>
                            <p>No hidden charges</p>
                        </div>
                    </div>

                    <div class="apply">
                        <a href="{{ route("LoginPage") }}">
                            <p>Apply Now</p>
                            &nbsp;→
                        </a>

                    </div>
                </div>

                <div class="card reveal reveal-delay-2">
                    <div class="card-icon">
                        <i class="fa fa-graduation-cap"></i>
                    </div>

                    <span>Loan</span>

                    <h4>Education Loan</h4>

                    <p>For personal expenses, life events, home improvements, and everything in between.</p>

                    <hr>

                    <div class="starting">
                        <p>Starting at</p>
                        <p><b>2%</b></p>
                        <p>/ monthly</p>
                    </div>

                    <div class="check-parent">
                        <div class="check-text">
                            <div class="check-icon">
                                <i class="fa fa-check"></i>
                            </div>
                            <p>Quick approval processs</p>
                        </div>
                        <div class="check-text">
                            <div class="check-icon">
                                <i class="fa fa-check"></i>
                            </div>
                            <p>Flexible repayment terms</p>
                        </div>
                        <div class="check-text">
                            <div class="check-icon">
                                <i class="fa fa-check"></i>
                            </div>
                            <p>No hidden charges</p>
                        </div>
                    </div>

                    <div class="apply">
                        <a href="{{ route("LoginPage") }}">
                            <p>Apply Now</p>
                            &nbsp;→
                        </a>

                    </div>
                </div>
                {{-- <div class="card reveal reveal-delay-1 mw-100" style="border-radius: 10px;">
                    <div class="card-body tw:flex tw:justify-start tw:flex-col">
                        <div class="card-header border-0 p-0">
                            <h3>Personal Loan</h3>

                            <p>For Personal expenses and life events.</p>
                        </div>

                        <hr style="margin: 10px 0px">

                        <span>Starting at 2%</span>
                    </div>
                </div>

                <div class="card reveal reveal-delay-2 mw-100" style="border-radius: 10px;">
                    <div class="card-body tw:flex tw:justify-start tw:flex-col">
                        <div class="card-header border-0 p-0">
                            <h3>Business Loan</h3>

                            <p>Grow your business with capital support</p>
                        </div>

                        <hr style="margin: 10px 0px">

                        <span>Starting at 2%</span>
                    </div>
                </div>

                <div class="card reveal reveal-delay-1 mw-100" style="border-radius: 10px;">
                    <div class="card-body tw:flex tw:justify-start tw:flex-col">
                        <div class="card-header border-0 p-0">
                            <h3>Emergency Loan</h3>

                            <p>Quick access for urgent financial needs.</p>
                        </div>

                        <hr style="margin: 10px 0px">

                        <span>Starting at 2%</span>
                    </div>
                </div>

                <div class="card reveal reveal-delay-2 mw-100" style="border-radius: 10px;">
                    <div class="card-body tw:flex tw:justify-start tw:flex-col">
                        <div class="card-header border-0 p-0">
                            <h3>Education Loan</h3>

                            <p>Make your dream home a reality.</p>
                        </div>

                        <hr style="margin: 10px 0px">

                        <span>Starting at 2%</span>
                    </div>
                </div> --}}
            </div>
        </section>

        <section id="section6">
            <span>What We Offer</span>

            <h2>Everything a Cooperative</h2>
            <h3>Needs to Thrive</h3>

            <div class="card-parent-box">
                <div class="card-box reveal reveal-delay-1">
                    <span>01</span>

                    <i class="fa fa-users"></i>

                    <h4>Member Management</h4>

                    <p>Comprehensive member registry with share capital tracking, membership tiers, contribution
                        history, and automated status notifications for every member lifecycle event.</p>
                </div>

                <div class="card-box reveal reveal-delay-2">
                    <span>02</span>

                    <i class="fa fa-users"></i>

                    <h4>Loan & Savings</h4>

                    <p>Comprehensive member registry with share capital tracking, membership tiers, contribution
                        history, and automated status notifications for every member lifecycle event.</p>
                </div>

                <div class="card-box reveal reveal-delay-1">
                    <span>03</span>

                    <i class="fa fa-users"></i>

                    <h4>Financial Reports</h4>

                    <p>Comprehensive member registry with share capital tracking, membership tiers, contribution
                        history, and automated status notifications for every member lifecycle event.</p>
                </div>

                <div class="card-box reveal reveal-delay-2">
                    <span>04</span>

                    <i class="fa fa-users"></i>

                    <h4>Financial Reports</h4>

                    <p>Comprehensive member registry with share capital tracking, membership tiers, contribution
                        history, and automated status notifications for every member lifecycle event.</p>
                </div>

                <div class="card-box reveal reveal-delay-1">
                    <span>04</span>

                    <i class="fa fa-users"></i>

                    <h4>Financial Reports</h4>

                    <p>Comprehensive member registry with share capital tracking, membership tiers, contribution
                        history, and automated status notifications for every member lifecycle event.</p>
                </div>

                <div class="card-box reveal reveal-delay-2">
                    <span>04</span>

                    <i class="fa fa-users"></i>

                    <h4>Financial Reports</h4>

                    <p>Comprehensive member registry with share capital tracking, membership tiers, contribution
                        history, and automated status notifications for every member lifecycle event.</p>
                </div>
            </div>
        </section>

        <section id="section3">
            <div class="section-image reveal reveal-delay-1">
                <img src="images/benefits.jpg" alt="">
            </div>
            <div class="section-text reveal reveal-delay-2">
                <div class="header">
                    <span>About us</span>
                </div>
                <h2 class="text-left">Rooted in Community. Driven by Trust.</h2>

                <div class="">
                    <p class="text-left">For over two decades, KPMPCATS has been the financial backbone of its members —
                        offering not just services, but a genuine partnership built on shared values and mutual growth.
                    </p>
                </div>

                <div style="margin-top: 20px">
                    <div class="card-box">
                        <div class="card-box-body tw:p-[8px]">
                            <div class="card-box-header">
                                <h3>Members Benefits</h3>

                                <p class="mw-100">As a cooperative member, you enjoy exclusive
                                    benefits and privileges designed to support
                                    your financial journey.</p>
                            </div>
                        </div>

                        <div class="benefits-group">

                            <div class="d-flex align-items-center column-gap-3">
                                <div class="icon">
                                    <i class="fa fa-check"></i>
                                </div>
                                <span>Low interest rates on loans</span>
                            </div>

                            <div class="d-flex align-items-center column-gap-3">
                                <div class="icon">
                                    <i class="fa fa-check"></i>
                                </div>
                                <span>Financial education programs</span>
                            </div>

                            <div class="d-flex align-items-center column-gap-3">
                                <div class="icon">
                                    <i class="fa fa-check"></i>
                                </div>
                                <span>Easy online application process</span>
                            </div>

                            <div class="d-flex align-items-center column-gap-3">
                                <div class="icon">
                                    <i class="fa fa-check"></i>
                                </div>
                                <span>Emergency loan assistance</span>
                            </div>

                            <div class="d-flex align-items-center column-gap-3">
                                <div class="icon">
                                    <i class="fa fa-check"></i>
                                </div>
                                <span>Transparent operations</span>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section id="section4">
            <span>Member Stories</span>
            <h2 class="text-left">What Our Members Say</h2>

            <div class="header">
                <p class="text-left">Real experiences from the people who trust us with their financial future.</p>
            </div>

            <div class="card-box-parent">
                <div class="card-box reveal reveal-delay-1">
                    <span>
                        <i class="fa fa-star"></i>
                        <i class="fa fa-star"></i>
                        <i class="fa fa-star"></i>
                        <i class="fa fa-star"></i>
                        <i class="fa fa-star"></i>
                    </span>

                    <p>"The loan process was so simple and the rates are genuinely better than any bank. KPMPCATS has
                        helped my family through so many milestones."</p>

                    <div class="card-account">
                        <div class="card-icon">
                            <p>M</p>
                        </div>
                        <div class="card-text">
                            <p>Maria Santos</p>
                            <span>Member since 2011</span>
                        </div>
                    </div>
                </div>

                <div class="card-box reveal reveal-delay-2">
                    <span>
                        <i class="fa fa-star"></i>
                        <i class="fa fa-star"></i>
                        <i class="fa fa-star"></i>
                        <i class="fa fa-star"></i>
                        <i class="fa fa-star"></i>
                    </span>

                    <p>"The loan process was so simple and the rates are genuinely better than any bank. KPMPCATS has
                        helped my family through so many milestones."</p>

                    <div class="card-account">
                        <div class="card-icon">
                            <p>M</p>
                        </div>
                        <div class="card-text">
                            <p>Maria Santos</p>
                            <span>Member since 2011</span>
                        </div>
                    </div>
                </div>

                <div class="card-box reveal reveal-delay-1">
                    <span>
                        <i class="fa fa-star"></i>
                        <i class="fa fa-star"></i>
                        <i class="fa fa-star"></i>
                        <i class="fa fa-star"></i>
                        <i class="fa fa-star"></i>
                    </span>

                    <p>"The loan process was so simple and the rates are genuinely better than any bank. KPMPCATS has
                        helped my family through so many milestones."</p>

                    <div class="card-account">
                        <div class="card-icon">
                            <p>M</p>
                        </div>
                        <div class="card-text">
                            <p>Maria Santos</p>
                            <span>Member since 2011</span>
                        </div>
                    </div>
                </div>
                {{-- <div class="card-box-text">
                    <div class="parent-active">
                        <div class="active-members reveal-scale">
                            <span>5,000+</span>
                            <p>Active Members</p>
                        </div>

                        <div class="active-members reveal-scale reveal-delay-1">
                            <span>₱500M+</span>
                            <p>Total Savings</p>
                        </div>

                        <div class="active-members reveal-scale">
                            <span>₱300M+</span>
                            <p>Loan Disbursed</p>
                        </div>

                        <div class="active-members reveal-scale reveal-delay-1">
                            <span>15 Years</span>
                            <p>Serving Community</p>
                        </div>
                    </div>
                    <p>Our cooperative has been a pillar of financial stability for thousands of families,
                        providing
                        accessible financial services and fostering a culture of savings and mutual aid.</p>
                </div>
                <div class="card-box-image">
                    <img src="images/empowering.jpg" alt="">
                </div> --}}
            </div>
        </section>

        <section id="section5">
            <h2 class="text-center">Ready to Join Our Community?</h2>

            <div class="d-flex justify-content-center align-items-center">
                <p class="text-center">Start your journey towards financial security and prosperity. Become a
                    member today and experience the cooperative difference.</p>
            </div>

            <div class="become-buttons">
                <a href="{{ route("RegisterPage") }}">Become a Member <i class="fa fa-arrow-right"></i></a>

                <a href="{{ route("LoginPage") }}">Member Login <i class="fa fa-arrow-right"></i></a>
            </div>
        </section>

        {{-- <footer>

        </footer> --}}
    </div>

    <script>
        const track = document.getElementById('carouselTrack');
        const slides = document.querySelectorAll('.slide');
        const dots = document.querySelectorAll('.dot');
        const prevBtn = document.getElementById('prevBtn');
        const nextBtn = document.getElementById('nextBtn');
        const slideNum = document.getElementById('slideNum');
        const progress = document.getElementById('progress');
        const total = slides.length;
        let current = 0;
        let timer;

        function goTo(idx) {
            slides[current].classList.remove('active');
            dots[current].classList.remove('active');
            current = (idx + total) % total;
            slides[current].classList.add('active');
            dots[current].classList.add('active');
            track.style.transform = `translateX(-${current * 100}%)`;
            slideNum.textContent = String(current + 1).padStart(2, '0');
            progress.style.transition = 'none';
            progress.style.width = '0%';
            requestAnimationFrame(() => {
                requestAnimationFrame(() => {
                    progress.style.transition = 'width 5s linear';
                    progress.style.width = '100%';
                });
            });
        }

        function startAuto() {
            clearInterval(timer);
            timer = setInterval(() => goTo(current + 1), 5000);
        }

        prevBtn.addEventListener('click', () => { goTo(current - 1); startAuto(); });
        nextBtn.addEventListener('click', () => { goTo(current + 1); startAuto(); });
        dots.forEach((dot, i) => dot.addEventListener('click', () => { goTo(i); startAuto(); }));

        track.addEventListener('touchstart', e => { startX = e.touches[0].clientX; });
        track.addEventListener('touchend', e => {
            const diff = startX - e.changedTouches[0].clientX;
            if (Math.abs(diff) > 50) { goTo(diff > 0 ? current + 1 : current - 1); startAuto(); }
        });

        document.addEventListener('keydown', e => {
            if (e.key === 'ArrowLeft') { goTo(current - 1); startAuto(); }
            if (e.key === 'ArrowRight') { goTo(current + 1); startAuto(); }
        });

        goTo(0);
        startAuto();
    </script>

    <script>
        const revealEls = document.querySelectorAll('.reveal, .reveal-left, .reveal-right, .reveal-scale');

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('visible');
                    observer.unobserve(entry.target); // animate once only
                }
            });
        }, { threshold: 0.13 });

        revealEls.forEach(el => observer.observe(el));
    </script>

    {{--
    <script>
        const nav = document.querySelector(".navbar");

        let lastScrollY = window.scrollY;

        window.addEventListener('scroll', () => {
            if (lastScrollY < window.scrollY) {
                nav.classList.add('nav-hidden');
            } else {
                nav.classList.remove('nav-hidden');
            }

            lastScrollY = window.scrollY;
        });

    </script> --}}

    {{-- AOS animation link js --}}
    {{--
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>

    <script>
        AOS.init();
    </script> --}}

    {{--
    <script src="style.js"></script> --}}
</body>

</html>