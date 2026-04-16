<nav
    class="tw:flex justify-content-between align-items-center tw:w-[100%] tw:px-[2rem] tw:h-[70px] tw:bg-[#ffffff] tw:p-[1rem]" style="position: fixed; top: 0;">
    <div class="nav-logo">
        {{-- <h2 class="m-0" style="font-size: 25px">LOGO</h2> --}}
        <img src="images/logo2.png" width="50px" height="50px" style="border-radius: 50%" alt="">
        <h3>KMPCATS</h3>
        {{-- <h2 class="mw-100 m-0" style="font-size: 14px; width: 200px;">Kingsland Pala-Pala MPC & Transport Service
        </h2> --}}
    </div>



    <div class="nav-menu">
        <i class="fa fa-bars" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasMenu1"
            aria-controls="staticBackdrop"></i>
    </div>

    <div class="nav-list">
        <ul class="tw:flex tw:gap-x-[4rem] m-0 p-0">
            <li class="tw:list-none">
                <a href="{{ route("index") }}" class="tw:no-underline text-decoration-none">Home</a>
            </li>

            <li class="tw:list-none">
                <a href="{{ route("AboutUs") }}" class="tw:no-underline text-decoration-none">About
                    Us</a>
            </li>

            <li class="tw:list-none">
                <a href="{{ route("ServicesPage") }}" class="tw:no-underline text-decoration-none">Services</a>
            </li>

            {{-- <li class="tw:list-none">
                <a href="{{ route(" BlogsPage") }}"
                    class="tw:no-underline tw:text-[15.5px] text-decoration-none text-black">Blogs</a>
            </li> --}}

            <li class="tw:list-none">
                <a href="{{ route("ContactPage") }}" class="tw:no-underline text-decoration-none">Contact Us</a>
            </li>

            {{-- <li class="tw:list-none">
                <a href="{{ route("StaticPage") }}"
                    class="tw:no-underline tw:text-[14.5px] text-decoration-none">Static Page</a>
            </li> --}}
        </ul>
    </div>

    <div class="nav-acc tw:flex tw:gap-x-[0.7rem]">

        <div class="nav-button">
            <a href="{{ route("LoginPage") }}" class="text-decoration-none tw:hover:bg-gray-700 fw-semibold"
                style="border-radius: 10px">Get Started <i class="fa fa-arrow-right"></i></a>
        </div>
    </div>
</nav>