<nav
    class="tw:flex justify-content-between align-items-center tw:w-[100%] tw:px-[2rem] tw:h-[70px] tw:border-b-1 tw:border-solid tw:border-[rgba(0,0,0,0.2)] tw:bg-[#ffffff] tw:p-[1rem]">
    <div class="nav-logo">
        {{-- <h2 class="m-0" style="font-size: 25px">LOGO</h2> --}}
        <img src="images/logo2.png" width="50px" height="50px" style="border-radius: 50%" alt="">
        {{-- <h2 class="mw-100 m-0" style="font-size: 14px; width: 200px;">Kingsland Pala-Pala MPC & Transport Service</h2> --}}
    </div>

    <div class="nav-list">
        <ul class="tw:flex tw:gap-x-[4rem] m-0 p-0">
            <li class="tw:list-none">
                <a href="{{ route("Landingpage") }}" class="tw:no-underline tw:text-[15.5px] text-decoration-none text-black">Home</a>
            </li>

            <li class="tw:list-none">
                <a href="{{ route("AboutUs") }}"
                    class="tw:no-underline tw:text-[15.5px] text-decoration-none text-black">About
                    us</a>
            </li>

            <li class="tw:list-none">
                <a href="#" class="tw:no-underline tw:text-[15.5px] text-decoration-none text-black">Services</a>
            </li>

            {{-- <li class="tw:list-none">
                <a href="{{ route("BlogsPage") }}" class="tw:no-underline tw:text-[15.5px] text-decoration-none text-black">Blogs</a>
            </li> --}}

            <li class="tw:list-none">
                <a href="{{ route("ContactPage") }}" class="tw:no-underline tw:text-[15.5px] text-decoration-none text-black">Contact</a>
            </li>
        </ul>
    </div>

    <div class="nav-menu">
        <i class="fa fa-bars" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasMenu1" aria-controls="staticBackdrop"></i>
    </div>

    <div class="nav-acc tw:flex tw:gap-x-[0.7rem]">
        {{-- <a href="#" class="text-decoration-none text-black tw:text-[20px]">
            <i class="fa fa-bell"></i>
        </a>

        <a id="loginButton" href="{{ route("LoginPage") }}" class="text-decoration-none text-black tw:text-[20px]">
            <i class="fa fa-user"></i>
        </a>

        <a href="#" class="text-decoration-none text-black tw:text-[20px]">
            <i class="fa fa-search"></i>
        </a> --}}

        <a href="{{ route("LoginPage") }}" class="py-2 px-5 text-decoration-none tw:hover:bg-gray-700 tw:text-[15.5px] fw-semibold"  style="border-radius: 10px">Login</a>
    </div>
</nav>