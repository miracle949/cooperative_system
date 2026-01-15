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

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- font awesome cdn link --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css"
        integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    {{-- bootstrap link --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">

</head>

<body style="background-color: #ECF6FF">

    <div class="container-fluid p-0 m-0">

        @include("navbar")
        @include("offcanvas")

        <main class="z-1">
            <div id="carouselExampleAutoplaying" class="carousel slide" data-bs-ride="carousel" data-bs-interval="2000">
                <div class="carousel-indicators">
                    <button type="button" data-bs-target="#carouselExampleAutoplaying" data-bs-slide-to="0"
                        class="active" aria-current="true" aria-label="Slide 1"></button>
                    <button type="button" data-bs-target="#carouselExampleAutoplaying" data-bs-slide-to="1"
                        aria-label="Slide 2"></button>
                    <button type="button" data-bs-target="#carouselExampleAutoplaying" data-bs-slide-to="2"
                        aria-label="Slide 3"></button>
                    <button type="button" data-bs-target="#carouselExampleAutoplaying" data-bs-slide-to="3"
                        aria-label="Slide 4"></button>
                </div>
                <div class="carousel-inner">
                    <div class="carousel-item active">
                        <img src="images/1.jpg" class="d-block w-100" alt="...">
                        <div class="carousel-caption top-0 d-flex justify-content-center align-items-center flex-column gap-4">
                            <h5 class="fs-1" style="margin: 0">Built for Our Members</h5>
                            <p class="mw-100 w-75">Empowering every cooperative member through shared ownership, mutual support, and sustainable growth.</p>
                            <button class="btn btn-light text-black py-2 px-4">Discover More</button>
                        </div>
                    </div>
                    <div class="carousel-item">
                        <img src="images/2.jpg" class="d-block w-100" alt="...">
                        <div class="carousel-caption top-0 d-flex justify-content-center align-items-center flex-column gap-4">
                            <h5 class="fs-1" style="margin: 0">Stronger Together</h5>
                            <p class="mw-100 w-75">We grow as one community—where trust, transparency, and collaboration drive our success.</p>
                            <button class="btn btn-light text-black py-2 px-4">Discover More</button>
                        </div>
                    </div>
                    <div class="carousel-item">
                        <img src="images/3.jpg" class="d-block w-100" alt="...">
                        <div class="carousel-caption top-0 d-flex justify-content-center align-items-center flex-column gap-4">
                            <h5 class="fs-1" style="margin: 0">Supporting Member Progress</h5>
                            <p class="mw-100 w-75">Providing accessible financial services and opportunities that help members achieve their goals..</p>
                            <button class="btn btn-light text-black py-2 px-4">Discover More</button>
                        </div>
                    </div>
                    <div class="carousel-item">
                        <img src="images/4.jpg" class="d-block w-100" alt="...">
                        <div class="carousel-caption top-0 d-flex justify-content-center align-items-center flex-column gap-4">
                            <h5 class="fs-1" style="margin: 0">Growing With Purpose</h5>
                            <p class="mw-100 w-75">Committed to long-term development that benefits our members, families, and community.</p>
                            <button class="btn btn-light text-black py-2 px-4">Discover More</button>
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
            {{-- <div data-aos="fade-zoom-in" data-aos-easing="ease-in-back" data-aos-delay="800"
                id="carouselExampleAutoplaying" class="carousel slide" data-bs-ride="carousel">
                <div class="carousel-inner">
                    <div class="carousel-item active">
                        <img src="images/1.jpg" class="d-block w-100" alt="...">
                    </div>
                    <div class="carousel-item">
                        <img src="images/2.jpg" class="d-block w-100" alt="...">
                    </div>
                    <div class="carousel-item">
                        <img src="images/3.jpg" class="d-block w-100" alt="...">
                    </div>
                    <div class="carousel-item">
                        <img src="images/4.jpg" class="d-block w-100" alt="...">
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
            </div> --}}
        </main>

        <section class="d-flex justify-center align-items-center flex-column p-5 mb-5" id="section1">
            <h2 class="text-center mt-5 pt-5 fw-medium">Why Choose Our Cooperative?</h2>

            <p class="text-center mt-3 mw-100 tw:w-[650px] tw:text-[#808080]">We offer more than just financial services
                - we
                provide a supportive community
                dedicated to your financial well-being.</p>

            <div class="d-flex justify-content-center align-items-center column-gap-5 flex-wrap">

                <div data-aos="fade-up" data-aos-duration="1000" class="card tw:p-[1rem] tw:mt-[2rem]"
                    style="border-radius: 20px">
                    <div class="card-body tw:pb-[1.5rem]">

                        <div class="card-header d-flex justify-content-center align-items-center p-0 border-0 tw:w-[60px] tw:h-[60px]"
                            style="background-color: #DBEAFE; border-radius: 20px;">
                            <i class="fa fa-users tw:text-[28px]" style="color: #155DFC;"></i>
                        </div>

                        <h3 class="mt-4" style="font-size: 18px">Community Driven</h3>

                        <p class="tw:w-[250px] tw:text-base/8  tw:text-[14.5px] mt-3">Join a community of members
                            working together towards financial prosperity and mutual support.
                        </p>
                    </div>
                </div>

                <div data-aos="fade-up" data-aos-duration="1500" class="card tw:p-[1rem] tw:mt-[2rem]"
                    style="border-radius: 20px">
                    <div class="card-body tw:pb-[1.5rem]">

                        <div class="card-header d-flex justify-content-center align-items-center p-0 border-0 tw:w-[60px] tw:h-[60px]"
                            style="background-color: #DBEAFE; border-radius: 20px;">
                            <i class="fa fa-chart-line tw:text-[28px]" style="color: #155DFC;"></i>
                        </div>

                        <h3 class="mt-4" style="font-size: 18px">Flexible Loans</h3>

                        <p class="tw:w-[250px] tw:text-base/8  tw:text-[14.5px] mt-3">Access various loan types with
                            competitive rates designed to meet your personal and business
                            needs.
                        </p>
                    </div>
                </div>

                <div data-aos="fade-up" data-aos-duration="2000" class="card tw:p-[1rem] tw:mt-[2rem]"
                    style="border-radius: 20px">
                    <div class="card-body tw:pb-[1.5rem]">

                        <div class="card-header d-flex justify-content-center align-items-center p-0 border-0 tw:w-[60px] tw:h-[60px]"
                            style="background-color: #DBEAFE; border-radius: 20px;">
                            <i class="fa fa-shield tw:text-[28px]" style="color: #155DFC;"></i>
                        </div>

                        <h3 class="mt-4" style="font-size: 18px">Secure Savings</h3>

                        <p class="tw:w-[250px] tw:text-base/8  tw:text-[14.5px] mt-3">Build your savings with confidence
                            through our secure and transparent cooperative system.
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <section class="d-flex justify-center align-items-center flex-column mb-5 p-5" id="section2">
            <h2 class="text-center mt-5 pt-5 fw-medium">Loan Products</h2>

            <p class="text-center mt-3 mw-100 tw:text-[16px] tw:text-[#808080]">Choose from our variety of loan products
                designed to meet your financial needs.
            </p>

            <div class="d-flex flex-wrap justify-content-center align-items-center gap-3" style="margin-top: 32px">
                <div data-aos="fade-up" data-aos-duration="1000" class="card mw-100 tw:w-[520px]"
                    style="border-radius: 20px; padding: 1.8rem 1.5rem 1.8rem 1.5rem;">
                    <div class="card-body tw:flex tw:justify-start tw:flex-col tw:p-[8px]">
                        <div class="card-header border-0 p-0">
                            <h3>Personal Loan</h3>

                            <p>For Personal expenses and life events.</p>
                        </div>

                        <hr style="margin: 10px 0px">

                        <span class="tw:text-[#155DFC]">Starting at 8%</span>
                    </div>
                </div>

                <div data-aos="fade-up" data-aos-duration="1000" class="card mw-100 tw:w-[520px]"
                    style="border-radius: 20px; padding: 1.8rem 1.5rem 1.8rem 1.5rem;">
                    <div class="card-body tw:flex tw:justify-start tw:flex-col tw:p-[8px]">
                        <div class="card-header border-0 p-0">
                            <h3>Business Loan</h3>

                            <p>Grow your business with capital support</p>
                        </div>

                        <hr style="margin: 10px 0px">

                        <span class="tw:text-[#155DFC]">Starting at 10%</span>
                    </div>
                </div>

                <div data-aos="fade-up" data-aos-duration="1000" class="card mw-100 tw:w-[520px]"
                    style="border-radius: 20px; padding: 1.8rem 1.5rem 1.8rem 1.5rem;">
                    <div class="card-body tw:flex tw:justify-start tw:flex-col tw:p-[8px]">
                        <div class="card-header border-0 p-0">
                            <h3>Emergency Loan</h3>

                            <p>Quick access for urgent financial needs.</p>
                        </div>

                        <hr style="margin: 10px 0px">

                        <span class="tw:text-[#155DFC]">Starting at 6%</span>
                    </div>
                </div>

                <div data-aos="fade-up" data-aos-duration="1000" class="card mw-100 tw:w-[520px]"
                    style="border-radius: 20px; padding: 1.8rem 1.5rem 1.8rem 1.5rem;">
                    <div class="card-body tw:flex tw:justify-start tw:flex-col tw:p-[8px]">
                        <div class="card-header border-0 p-0">
                            <h3>Housing Loan</h3>

                            <p>Make your dream home a reality.</p>
                        </div>

                        <hr style="margin: 10px 0px">

                        <span class="tw:text-[#155DFC]">Starting at 9%</span>
                    </div>
                </div>
            </div>
        </section>

        <footer class="p-5" id="footer">
            <h2 class="text-center mt-5 pt-5 fw-medium">About Members Benefits</h2>

            <div class="d-flex justify-content-center align-items-center">
                <p class="text-center mw-100">Member benefits are designed to strengthen the entire community, not just
                    individuals. This creates loyalty and long-term stability.</p>
            </div>

            <div style="margin-top: 20px">
                <div data-aos="fade-up" data-aos-duration="1000" class="card-box">
                    <div class="card-box-body tw:p-[8px]">
                        <div class="card-box-header">
                            <h3>Members Benefits</h3>

                            <p class="tw:text-[#808080] mw-100">As a cooperative member, you enjoy exclusive
                                benefits and privileges designed to support
                                your financial journey.</p>
                        </div>
                    </div>

                    <div class="d-flex justify-content-start align-items-start gap-5 justify-content-md-start align-items-md-start justify-content-sm-start align-items-sm-start justify-content-xs-start align-items-xs-start flex-xl-row flex-md-column flex-sm-column flex-xs-column benefits-group"
                        style="padding: 8px">
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
        </footer>
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
    <script src="style.js"></script> --}}
</body>

</html>