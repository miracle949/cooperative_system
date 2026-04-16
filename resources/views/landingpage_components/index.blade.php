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

        @include("components.navbar")
        @include("components.offcanvas")

        <main class="z-1">

            <div id="carouselExampleAutoplaying" class="carousel slide carousel-fade" data-bs-ride="carousel"
                data-bs-interval="3000">
                <div class="carousel-inner">
                    <div class="carousel-item active">
                        <img src="../images/1.jpg" class="d-block" alt="..."
                            style="width: 100%; height: 100%; max-height: 520px;">
                        <div class="carousel-caption d-none d-md-block">
                            <h5>Trusted Cooperative Partner</h5>
                            <p>Empowering members with financial solutions.</p>
                            <a href="#">Learn More</a>
                        </div>
                    </div>
                    <div class="carousel-item">
                        <img src="../images/2.jpg" class="d-block" alt="..."
                            style="width: 100%; height: 100%; max-height: 520px;">
                        <div class="carousel-caption d-none d-md-block">
                            <h5>Services at Your Fingertips</h5>
                            <p>Access your cooperative services anytime.</p>
                            <a href="#">Learn More</a>
                        </div>
                    </div>
                    <div class="carousel-item">
                        <img src="../images/3.jpg" class="d-block" alt="..."
                            style="width: 100%; height: 100%; max-height: 520px;">
                        <div class="carousel-caption d-none d-md-block">
                            <h5>Your Loan, Approved!</h5>
                            <p>We make loan applications simple.</p>
                            <a href="#">Learn More</a>
                        </div>
                    </div>
                    <div class="carousel-item">
                        <img src="../images/4.jpg" class="d-block" alt="..."
                            style="width: 100%; height: 100%; max-height: 520px;">
                        <div class="carousel-caption d-none d-md-block">
                            <h5>Growing Together as One</h5>
                            <p>We are committed to uplifting every member through unity.</p>
                            <a href="#">Learn More</a>
                        </div>
                    </div>
                </div>
                <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleAutoplaying"
                    data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Previous</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleAutoplaying"
                    data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Next</span>
                </button>
            </div>

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

        <section class="d-flex justify-center align-items-center flex-column pe-5 ps-5 pb-5 pt-3" id="section1">
            <h2 class="text-center mt-5">Why Choose Our Cooperative?</h2>

            <p class="text-center mt-2 mw-100 tw:w-[650px] tw:text-[#808080]">We offer more than just financial services
                - we
                provide a supportive community
                dedicated to your financial well-being.</p>

            <div class="d-flex justify-content-center align-items-center column-gap-5 flex-wrap">

                <div class="card tw:p-[1rem] tw:mt-[2rem]" style="border-radius: 20px">
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

                <div class="card tw:p-[1rem] tw:mt-[2rem]" style="border-radius: 20px">
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

                <div class="card tw:p-[1rem] tw:mt-[2rem]" style="border-radius: 20px">
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
            </div>
        </section>

        <section class="d-flex justify-center align-items-center flex-column pe-5 ps-5 pt-3 pb-5" id="section2">
            <h2 class="text-center mt-5">Loan Products</h2>

            <p class="text-center mt-2 mw-100 tw:text-[16px] tw:text-[#808080]">Choose from our variety of loan products
                designed to meet your financial needs.
            </p>

            <div class="d-flex flex-wrap justify-content-center align-items-center gap-3 card-parent"
                style="margin-top: 32px;">
                <div class="card mw-100" style="border-radius: 10px;">
                    <div class="card-body tw:flex tw:justify-start tw:flex-col">
                        <div class="card-header border-0 p-0">
                            <h3>Personal Loan</h3>

                            <p>For Personal expenses and life events.</p>
                        </div>

                        <hr style="margin: 10px 0px">

                        <span>Starting at 2%</span>
                    </div>
                </div>

                <div class="card mw-100" style="border-radius: 10px;">
                    <div class="card-body tw:flex tw:justify-start tw:flex-col">
                        <div class="card-header border-0 p-0">
                            <h3>Business Loan</h3>

                            <p>Grow your business with capital support</p>
                        </div>

                        <hr style="margin: 10px 0px">

                        <span>Starting at 2%</span>
                    </div>
                </div>

                <div class="card mw-100" style="border-radius: 10px;">
                    <div class="card-body tw:flex tw:justify-start tw:flex-col">
                        <div class="card-header border-0 p-0">
                            <h3>Emergency Loan</h3>

                            <p>Quick access for urgent financial needs.</p>
                        </div>

                        <hr style="margin: 10px 0px">

                        <span>Starting at 2%</span>
                    </div>
                </div>

                <div class="card mw-100" style="border-radius: 10px;">
                    <div class="card-body tw:flex tw:justify-start tw:flex-col">
                        <div class="card-header border-0 p-0">
                            <h3>Education Loan</h3>

                            <p>Make your dream home a reality.</p>
                        </div>

                        <hr style="margin: 10px 0px">

                        <span>Starting at 2%</span>
                    </div>
                </div>
            </div>
        </section>

        <section class="p-5 mt-5" id="section3">
            <div class="section-image">
                <img src="images/benefits.jpg" alt="">
            </div>
            <div class="section-text">
                <h2 class="text-left">About Members Benefits</h2>

                <div class="">
                    <p class="text-left mw-100">Member benefits are designed to strengthen the entire community, not
                        just
                        individuals. This creates loyalty and long-term stability.</p>
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

                        <div
                            class="d-flex justify-content-start align-items-start gap-5 justify-content-md-start benefits-group">
                            <div class="d-flex flex-column row-gap-3">
                                <div class="d-flex column-gap-3">
                                    <i class="fa fa-check"></i>
                                    <span>Low interest rates on loans</span>
                                </div>

                                <div class="d-flex column-gap-3">
                                    <i class="fa fa-check"></i>
                                    <span>Financial education programs</span>
                                </div>

                                <div class="d-flex column-gap-3">
                                    <i class="fa fa-check"></i>
                                    <span>Easy online application process</span>
                                </div>
                            </div>

                            <div class="d-flex flex-column row-gap-3">
                                <div class="d-flex column-gap-3">
                                    <i class="fa fa-check"></i>
                                    <span>Competitive savings dividends</span>
                                </div>

                                <div class="d-flex column-gap-3">
                                    <i class="fa fa-check"></i>
                                    <span>Emergency loan assistance</span>
                                </div>

                                <div class="d-flex column-gap-3">
                                    <i class="fa fa-check"></i>
                                    <span>Transparent operations</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="p-5 mt-5" id="section4">
            <h2 class="text-center">Empowering Our Community</h2>

            <div class="d-flex justify-content-center align-items-center">
                <p class="text-center mw-100">Together, we create opportunities for growth and success through
                    collaboration and mutual support.</p>
            </div>

            <div class="card-box-parent">
                <div class="card-box-text">
                    <div class="parent-active">
                        <div class="active-members">
                            <span>5,000+</span>
                            <p>Active Members</p>
                        </div>

                        <div class="active-members">
                            <span>₱500M+</span>
                            <p>Total Savings</p>
                        </div>

                        <div class="active-members">
                            <span>₱300M+</span>
                            <p>Loan Disbursed</p>
                        </div>

                        <div class="active-members">
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
                </div>
            </div>
        </section>

        <section class="mt-5" id="section5">
            <h2 class="text-center">Ready to Join Our Community?</h2>

            <div class="d-flex justify-content-center align-items-center">
                <p class="text-center">Start your journey towards financial security and prosperity. Become a
                    member today and experience the cooperative difference.</p>
            </div>

            <div class="become-buttons">
                <a href="{{ route("RegisterPage") }}">Become a Member <i class="fa fa-arrow-right"></i></a>

                <a href="#">Member Login <i class="fa fa-arrow-right"></i></a>
            </div>
        </section>

        {{-- <footer>

        </footer> --}}
    </div>

    {{-- <script>
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
    {{-- <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>

    <script>
        AOS.init();
    </script> --}}

    {{--
    <script src="style.js"></script> --}}
</body>

</html>