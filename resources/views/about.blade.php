<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>About us</title>
    
    <link rel="icon" href="images/websitelogo.png" type="image/png">

    {{-- AOS animation link css --}}
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

    {{-- css link --}}
    <link rel="stylesheet" href="css_folder/about.css">
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

<body>

    <div class="container-fluid p-0 m-0">

        @include("navbar")
        @include("offcanvas")

        <main class="p-5">
            <div class="row">
                <div data-aos="fade-zoom-in" data-aos-easing="ease-in-back" data-aos-delay="800" data-aos-offset="0"
                    class="col-lg-6 col-md-12">
                    <h3 class="text-uppercase fw-semibold mt-3">About Our Cooperative</h3>

                    <h2 class="fw-bold mt-3 mw-100">Built on Trust, Grown by Community</h2>

                    <p class="mt-3 mw-100">For over 15 years, we have been building a community-driven financial institution
                        that truly puts members first. Our cooperative is founded on the principles of mutual support,
                        transparency, and shared prosperity.</p>

                    <p class="mt-3 mw-100">We work every day to empower our members through responsible financial services,
                        collaborative decision-making, and a shared commitment to long-term growth. By prioritizing
                        people over profit, we continue to create opportunities that strengthen not only individual
                        members, but the entire community we serve.</p>

                    <div class="mt-4">
                        <a href="#"
                            class="text-decoration-none btn btn-dark py-2 px-5 text-uppercase fw-semibold tw:hover:bg-gray-700"
                            style="font-size: 14.5px">Learn More</a>
                    </div>
                </div>

                <div data-aos="fade-zoom-in" data-aos-easing="ease-in-back" data-aos-delay="1000" data-aos-offset="0"
                    class="col-lg-6 col-md-12 tw:flex tw:justify-center tw:items-center about-image">
                    <img src="images/about_picture2.jpg" class="mw-100 object-fit-cover w-md-100" alt=""
                        style="border-radius: 10px; border: 1px solid rgba(0,0,0,0.3);">
                </div>
            </div>
        </main>

        <section class="mt-3 p-5" id="section1">

            <div data-aos="fade-up" data-aos-duration="1000" class="tw:flex tw:justify-center tw:items-center gap-5 parent-vision-mission">
                <div class="card tw:p-7 md:w-100"
                    style="border: 1px solid rgba(0,0,0,0.2); border-radius: 10px; background-color: #DBEAFE;">

                    <div class="p-3 tw:bg-[#155DFC] tw:w-[55px] tw:h-[55px] tw:flex tw:justify-center tw:items-center"
                        style="border-radius: 10px">
                        <i class="fa fa-bullseye tw:text-white" style="font-size: 20px"></i>
                    </div>

                    <h3 class="mt-4">Our Mission</h3>

                    <p class="tw:w-[95%]" style="line-height: 35px; margin: 8px 0px 0px;">To provide accessible,
                        affordable, and innovative financial services that empower our members to
                        achieve financial security and prosperity through cooperative principles of self-help,
                        self-responsibility, democracy, equality, and solidarity.</p>

                </div>

                <div data-aos="fade-up" data-aos-duration="1500" class="card tw:p-7 mw-100 hw-100"
                    style="border: 1px solid rgba(0,0,0,0.2); border-radius: 10px; background-color: #F0FDF4;">

                    <div class="p-3 tw:bg-[#00A63E] tw:w-[55px] tw:h-[55px] tw:flex tw:justify-center tw:items-center"
                        style="border-radius: 10px">
                        <i class="fa fa-eye tw:text-white" style="font-size: 20px"></i>
                    </div>

                    <h3 class="mt-4">Our Vision</h3>

                    <p class="tw:w-[95%]" style="line-height: 35px; margin: 8px 0px 0px;">To be the leading cooperative
                        financial institution known for excellence in member service, financial stability, and community
                        development, creating lasting positive impact in the lives of our members and their families equality, and solidarity.
                    </p>
                </div>
            </div>

        </section>

        <section class="pt-5 p-5" id="section2">

            <h3 class="text-center">Our Core Values</h3>

            <p class="tw:text-[#808080] text-center">These values guide every decision we make and every service we
                provide to our
                members.</p>


            <div class="row">
                <div class="col-lg-6 col-md-12 mt-5">
                    <div data-aos="fade-up" data-aos-duration="1000" class="card tw:p-7 rounded-lg">
                        <div class="p-3 tw:bg-[#DBEAFE] tw:w-[55px] tw:h-[55px] tw:flex tw:justify-center tw:items-center"
                            style="border-radius: 10px">
                            <i class="fa fa-users tw:text-[#155DFC]" style="font-size: 20px"></i>
                        </div>

                        <h4 class="mt-4">Community First</h4>

                        <p class="tw:text-[#808080]" style="margin: 8px 0px 0px">We prioritize the welfare and growth of
                            our member community
                            above all else.</p>
                    </div>
                </div>

                <div class="col-lg-6 col-md-12 mt-5">
                    <div data-aos="fade-up" data-aos-duration="1000" class="card tw:p-7 rounded-lg">
                        <div class="p-3 tw:bg-[#DBEAFE] tw:w-[55px] tw:h-[55px] tw:flex tw:justify-center tw:items-center"
                            style="border-radius: 10px">
                            <i class="fa fa-medal tw:text-[#155DFC]" style="font-size: 20px"></i>
                        </div>

                        <h4 class="mt-4">Integrity First</h4>

                        <p class="tw:text-[#808080]" style="margin: 8px 0px 0px">We operate with transparency, honesty,
                            and accountability in
                            everything we do.</p>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-6 col-md-12 mt-4">
                    <div data-aos="fade-up" data-aos-duration="1000" class="card tw:p-7 rounded-lg">
                        <div class="p-3 tw:bg-[#DBEAFE] tw:w-[55px] tw:h-[55px] tw:flex tw:justify-center tw:items-center"
                            style="border-radius: 10px">
                            <i class="fa fa-bullseye tw:text-[#155DFC]" style="font-size: 20px"></i>
                        </div>

                        <h4 class="mt-4">Excellence</h4>

                        <p class="tw:text-[#808080]" style="margin: 8px 0px 0px">We strive for excellence in service
                            delivery and member satisfaction.</p>
                    </div>
                </div>

                <div class="col-lg-6 col-md-12 mt-4">
                    <div data-aos="fade-up" data-aos-duration="1000" class="card tw:p-7 rounded-lg">
                        <div class="p-3 tw:bg-[#DBEAFE] tw:w-[55px] tw:h-[55px] tw:flex tw:justify-center tw:items-center"
                            style="border-radius: 10px">
                            <i class="fa fa-eye tw:text-[#155DFC]" style="font-size: 20px"></i>
                        </div>

                        <h4 class="mt-4">Inclusivity</h4>

                        <p class="tw:text-[#808080]" style="margin: 8px 0px 0px">We welcome all members regardless of
                            background, ensuring equal opportunities.</p>
                    </div>
                </div>
            </div>


        </section>

        <section class="pt-5 p-5" id="section3">
            <h3 class="text-center">Our Journey</h3>

            <p class="tw:text-[#808080] text-center">From humble beginnings to a thriving financial cooperative serving
                thousands of members.</p>

            <div class="parent-section">

                <div class="row mt-5 pt-5">
                    <div class="col-lg-4 col-md-12">
                        <div class="line">
                            <div class="dot"></div>
                        </div>
                        <div data-aos="fade-up" data-aos-duration="1000" class="card">

                            <div>
                                <i class="fa fa-calendar"> </i>
                                <span>2009</span>
                            </div>

                            <h4 class="mt-3">Cooperative Founded</h4>

                            <p>Started with 50 founding members</p>
                        </div>
                    </div>
                </div>

                <div class="row tw:flex tw:justify-end mt-5">
                    <div class="col-6">

                    </div>

                    <div class="col-lg-4 col-md-12">
                        <div class="line">
                            <div class="dot"></div>
                        </div>
                        <div data-aos="fade-up" data-aos-duration="1000"
                            class="card tw:flex tw:justify-end tw:items-end">
                            <div>
                                <span>2009</span>
                                <i class="fa fa-calendar"> </i>
                            </div>

                            <h4 class="mt-3">First Branch Opening</h4>

                            <p>Expanded to serve more communities</p>
                        </div>
                    </div>
                </div>

                <div class="row mt-5">
                    <div class="col-lg-4 col-md-12">
                        <div class="line">
                            <div class="dot"></div>
                        </div>
                        <div data-aos="fade-up" data-aos-duration="1000" class="card">
                            <div>
                                <i class="fa fa-calendar"> </i>
                                <span>2009</span>
                            </div>

                            <h4 class="mt-3">Digital Transformation</h4>

                            <p>Launched online member portal</p>
                        </div>
                    </div>
                </div>

                <div class="row tw:flex tw:justify-end mt-5">
                    <div class="col-6">

                    </div>

                    <div class="col-lg-4 col-md-12">
                        <div class="line">
                            <div class="dot"></div>
                        </div>
                        <div data-aos="fade-up" data-aos-duration="1000"
                            class="card tw:flex tw:justify-end tw:items-end">
                            <div>
                                <span>2009</span>
                                <i class="fa fa-calendar"> </i>
                            </div>

                            <h4 class="mt-3">5000+ Members</h4>

                            <p>Reached major membership milestone</p>
                        </div>
                    </div>
                </div>

                <div class="row mt-5">
                    <div class="col-lg-4 col-md-12">
                        <div class="line">
                            <div class="dot"></div>
                        </div>
                        <div data-aos="fade-up" data-aos-duration="1000" class="card">
                            <div>
                                <i class="fa fa-calendar"> </i>
                                <span>2009</span>
                            </div>

                            <h4 class="mt-3">₱500M in Assets</h4>

                            <p>Growing stronger together</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="pt-5 p-5" id="section4">

            <h3 class="text-center">Leadership Team</h3>

            <p class="tw:text-[#808080] text-center">Meet the dedicated leaders guiding our cooperative towards
                continued growth and success.</p>

            <div class="tw:flex tw:justify-center tw:items-center tw:flex-wrap gap-5 mt-5">
                <div data-aos="fade-up" data-aos-duration="1000" class="tw:w-[300px] tw:h-[300px] tw:bg-[white] tw:flex tw:justify-center tw:items-center tw:flex-col text-center p-4" style="border-radius: 10px; border: 1px solid rgba(0,0,0,0.3);">

                    <div class="tw:flex tw:justify-center tw:items-center" style="width: 90px; height: 90px; border-radius: 50%; background-color: #3E8FFF;">
                        <i class="fa fa-user"></i>
                    </div>

                    <h4 class="mt-3">Maria Rodriguez</h4>

                    <span>Board Chairperson</span>

                    <p>Leading with vision and integrity for 10 years</p>
                </div>

                <div data-aos="fade-up" data-aos-duration="1000" class="tw:w-[300px] tw:h-[300px] tw:bg-[white] tw:flex tw:justify-center tw:items-center tw:flex-col text-center p-4" style="border-radius: 10px; border: 1px solid rgba(0,0,0,0.3);">

                    <div class="tw:flex tw:justify-center tw:items-center" style="width: 90px; height: 90px; border-radius: 50%; background-color: #3E8FFF;">
                        <i class="fa fa-user"></i>
                    </div>

                    <h4 class="mt-3">Jose Hernandez</h4>

                    <span>General Manager</span>

                    <p>Driving innovation and member satisfaction</p>
                </div>

                <div data-aos="fade-up" data-aos-duration="1000" class="tw:w-[300px] tw:h-[300px] tw:bg-[white] tw:flex tw:justify-center tw:items-center tw:flex-col text-center p-4" style="border-radius: 10px; border: 1px solid rgba(0,0,0,0.3);">

                    <div class="tw:flex tw:justify-center tw:items-center" style="width: 90px; height: 90px; border-radius: 50%; background-color: #3E8FFF;">
                        <i class="fa fa-user"></i>
                    </div>

                    <h4 class="mt-3">Ana Reyes</h4>

                    <span>Finance Director</span>

                    <p>Ensuring financial stability and transparency</p>
                </div>

                <div data-aos="fade-up" data-aos-duration="1000" class="tw:w-[300px] tw:h-[300px] tw:bg-[white] tw:flex tw:justify-center tw:items-center tw:flex-col text-center p-4" style="border-radius: 10px; border: 1px solid rgba(0,0,0,0.3);">

                    <div class="tw:flex tw:justify-center tw:items-center" style="width: 90px; height: 90px; border-radius: 50%; background-color: #3E8FFF;">
                        <i class="fa fa-user"></i>
                    </div>

                    <h4 class="mt-3">Carlos Martinez</h4>

                    <span>Member Services Head</span>

                    <p>Committed to exceptional member experience</p>
                </div>

                <div data-aos="fade-up" data-aos-duration="1000" class="tw:w-[300px] tw:h-[300px] tw:bg-[white] tw:flex tw:justify-center tw:items-center tw:flex-col text-center p-4" style="border-radius: 10px; border: 1px solid rgba(0,0,0,0.3);">

                    <div class="tw:flex tw:justify-center tw:items-center" style="width: 90px; height: 90px; border-radius: 50%; background-color: #3E8FFF;">
                        <i class="fa fa-user"></i>
                    </div>

                    <h4 class="mt-3">Maria Rodriguez</h4>

                    <span>Board Chairperson</span>

                    <p>Leading with vision and integrity for 10 years</p>
                </div>

                <div data-aos="fade-up" data-aos-duration="1000" class="tw:w-[300px] tw:h-[300px] tw:bg-[white] tw:flex tw:justify-center tw:items-center tw:flex-col text-center p-4" style="border-radius: 10px; border: 1px solid rgba(0,0,0,0.3);">

                    <div class="tw:flex tw:justify-center tw:items-center" style="width: 90px; height: 90px; border-radius: 50%; background-color: #3E8FFF;">
                        <i class="fa fa-user"></i>
                    </div>

                    <h4 class="mt-3">Maria Rodriguez</h4>

                    <span>Board Chairperson</span>

                    <p>Leading with vision and integrity for 10 years</p>
                </div>
            </div>
        </section>

        <footer class="pt-5 mt-5 p-5 tw:flex tw:justify-center tw:items-center tw:flex-col" id="footer">
            <h3>Be Part of Our Story</h3>

            <p>Join thousands of members who have chosen to build their financial future with us.</p>

            <a href="{{ route("LoginPage") }}" class="mt-4 text-decoration-none tw:bg-[#3E8FFF] py-3 px-5 text-white fw-semibold" style="border: 1px solid rgba(0,0,0,0.3); border-radius: 10px;">Become a Member</a>
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
    <script>
        const dot = document.querySelector(".dot");

        for (const i = 0; i <= dot; i++) {
            dot.style.display = "block";
        }
    </script> --}}

</body>

</html>