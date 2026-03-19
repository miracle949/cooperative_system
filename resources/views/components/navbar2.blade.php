<nav
    class="tw:flex justify-content-between align-items-center tw:w-[100%] tw:px-[2rem] tw:h-[70px] tw:border-b-1 tw:border-solid tw:border-[rgba(0,0,0,0.2)] tw:bg-[#ffffff] tw:p-[1rem]">
    <div class="nav-logo">
        {{-- <h2 class="m-0" style="font-size: 25px">LOGO</h2> --}}
        <img src="images/logo2.png" width="50px" height="50px" style="border-radius: 50%" alt="">
        <h3>KMPCATS</h3>
        {{-- <h2 class="mw-100 m-0" style="font-size: 14px; width: 200px;">Kingsland Pala-Pala MPC & Transport Service
        </h2> --}}
    </div>

    <div class="nav-list nav-list2">
        <ul class="tw:flex tw:gap-x-[4rem] m-0 p-0">
            <li class="tw:list-none">
                <a href="{{ route("MemberPortal") }}"
                    class="tw:no-underline tw:text-[15.5px] text-decoration-none">Home</a>
            </li>

            <li class="tw:list-none">
                <a href="{{ route("LoanApplication") }}"
                    class="tw:no-underline tw:text-[15.5px] text-decoration-none">Lending Application</a>
            </li>

            <li class="tw:list-none">
                <a href="{{ route("ShareCapital") }}"
                    class="tw:no-underline tw:text-[15.5px] text-decoration-none">Share Capital</a>
            </li>

            <li class="tw:list-none">
                <a href="{{ route("savings.index") }}"
                    class="tw:no-underline tw:text-[15.5px] text-decoration-none">Savings</a>
            </li>

            <li class="tw:list-none">
                <a href="{{ route("LoanStatus") }}" class="tw:no-underline tw:text-[15.5px] text-decoration-none">Loan
                    Status</a>
            </li>
        </ul>
    </div>

    <div class="nav-menu">
        <i class="fa fa-bars" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasMenu2"
            aria-controls="staticBackdrop"></i>
    </div>

    <div class="nav-acc2" id="nav-acc2">
        <ul class="m-0 p-0">
            <i class="fa fa-bell" style="font-size: 20px"></i>
            <li>
                @if ($first_name)
                    <a href="#" onclick="toggleDropdown(event)"
                        class="tw:flex tw:justify-center tw:items-center tw:gap-x-[0.7rem]">
                        <img src="images/unnamed.png" width="35px" height="35px" style="border-radius: 50%" alt="">
                        <p style="margin: 0">{{ $first_name }}</p>
                    </a>
                @endif

                <ul>
                    <li>
                        <div class="card-icon"><i class="fa fa-user"></i></div>
                        <a href="{{ route('ProfileMember') }}">Profile</a>
                    </li>
                    <li>
                        <div class="card-icon"><i class="fa fa-lock"></i></div>
                        <a href="#">Reset Password</a>
                    </li>
                    <hr>
                    <li>
                        <div class="card-icon"><i class="fa fa-truck"></i></div>
                        <a href="{{ route('DriverPortal') }}">Switch to Driver Portal</a>
                    </li>
                    <hr>
                    <li>
                        <div class="card-icon"><i class="fa fa-sign-out"></i></div>
                        <a href="{{ route('logout') }}">Logout</a>
                    </li>
                </ul>
            </li>
        </ul>
    </div>

    <script>
        function toggleDropdown(e) {
            e.preventDefault();
            e.stopPropagation();
            document.getElementById('nav-acc2').classList.toggle('open');
        }

        document.addEventListener('click', function (e) {
            const wrap = document.getElementById('nav-acc2');
            if (!wrap.contains(e.target)) {
                wrap.classList.remove('open');
            }
        });
    </script>
</nav>