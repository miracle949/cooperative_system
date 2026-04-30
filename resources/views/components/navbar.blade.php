<nav id="main-nav"
    class="tw:flex justify-content-between align-items-center tw:w-[100%] tw:px-[2rem] tw:bg-[#ffffff] tw:p-[1rem]"
    style="position: fixed; top: 0; z-index: 1000; height: 64px; transition: height 0.3s ease, box-shadow 0.3s ease;">
    <div class="nav-logo">
        <img src="images/logo2.png" width="45px" height="45px" style="border-radius: 50%; transition: all 0.3s ease;"
            alt="">
        <h3 style="transition: font-size 0.3s ease;">KPMPCATS</h3>
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
                <a href="#products_services" class="tw:no-underline text-decoration-none">Products &
                    Services</a>
            </li>
            <li class="tw:list-none">
                <a href="#" class="tw:no-underline text-decoration-none">Membership</a>
            </li>
            <li class="tw:list-none">
                <a href="{{ route("ContactPage") }}" class="tw:no-underline text-decoration-none">Contact Us</a>
            </li>
            <li class="tw:list-none">
                <a href="{{ route("AboutUs") }}" class="tw:no-underline text-decoration-none">About Us</a>
            </li>
        </ul>
    </div>

    <div class="nav-acc tw:flex tw:gap-x-[0.7rem]">

        <div class="nav-button">
            <a href="{{ route("LoginPage") }}" class="text-decoration-none tw:hover:bg-gray-700 fw-semibold"
                style="border-radius: 28px">Get Started <i class="fa fa-arrow-right"></i></a>
        </div>
    </div>
</nav>

{{-- <script>
    const nav = document.getElementById('main-nav');
    const navLogo = nav.querySelector('.nav-logo img');
    const navTitle = nav.querySelector('.nav-logo h3');

    window.addEventListener('scroll', () => {
        if (window.scrollY > 50) {
            nav.style.height = '60px';
            nav.style.boxShadow = '0 2px 16px rgba(0,0,0,0.10)';
            navLogo.style.width = '40px';
            navLogo.style.height = '40px';
            navTitle.style.fontSize = '18px';
        } else {
            nav.style.height = '70px';
            nav.style.boxShadow = 'none';
            navLogo.style.width = '45px';
            navLogo.style.height = '45px';
            navTitle.style.fontSize = '18px';
        }
    });
</script> --}}