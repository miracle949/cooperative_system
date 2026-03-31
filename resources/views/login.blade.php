<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Login</title>
    <link rel="icon" href="images/websitelogo.png" type="image/png">

    {{-- css link --}}
    <link rel="stylesheet" href="css_folder/login.css">
    <link rel="stylesheet" href="css_folder/loading.css">

    {{-- bootstrap and tailwind link --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- font awesome cdn link --}}
    <link rel="stylesheet" href="font-awesome-icon/css/all.min.css">

    {{-- bootstrap link --}}
    {{--
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    --}}
</head>

<body>

    <div class="container-fluid">

        <div class="loading-screen">

            <div class="load"></div>

        </div>

        {{-- <nav>
            <div class="logo">

                <img src="images/logo2.png" alt="">

                <div>
                    <h2 class="fw-bold">Membership Application Form</h2>

                    <p>Ready to become part of something special? We can't wait to welcome
                        you.
                </div>
            </div>
        </nav> --}}

        {{-- <div class="back-to-landing">
            <a href="{{ route(" Landingpage") }}">
                <i class="fa fa-arrow-left"></i>
            </a>
        </div> --}}

        <div class="form-image">
            <span>Secure Member Portal</span>

            <h3>Your gateway to <b>smarter</b> management starts here.</h3>

            <div class="line"></div>

            <p>Access your account to manage records, track progress, and collaborate with your team — all in one
                secure, centralized platform built for excellence.</p>

            <div class="total-members">
                <div class="members">
                    <h4>12k+</h4>
                    <span>Members</span>
                </div>

                <div class="members">
                    <h4>99.9%</h4>
                    <span>Upptime</span>
                </div>

                <div class="members">
                    <h4>256-bit</h4>
                    <span>Encryption</span>
                </div>
            </div>
        </div>
        <div class="form-box-parent">
            <div class="form-parent">
                <div class="logo tw:flex tw:justify-center tw:items-center">
                    <a href="{{ route("Landingpage") }}"><img src="images/logo2.png" alt=""></a>
                </div>

                <h2 class="fw-semibold mt-4 text-center">Login your account</h2>

                <p class="mt-3 text-center">Welcome back! Please log in to securely manage your account.
                </p>

                <form action="{{ route("UserLogin") }}" method="post">
                    @csrf
                    <div class="form-sub-parent">
                        @if ($errors->any())
                            <div style="background:#fef0f0; border:1.5px solid #f5c6c6; border-radius:10px; padding:0.8rem 1rem; margin-bottom:1rem; font-size:0.85rem; color:#e03131; font-weight:600;">
                                <i class="fa-solid fa-circle-xmark"></i>
                                {{ $errors->first('login') }}
                            </div>
                        @endif
                        
                        <div class="mt-3 form-input">
                            <label>Email or Username</label>
                            <input type="text" name="login" value="{{ old('login') }}" placeholder="Enter your email" class="form-control mt-2"
                                required>
                        </div>

                        <div class="mt-3 form-input">
                            <label>Password</label>
                            <input type="password" name="password" placeholder="Enter your password"
                                class="form-control mt-2" required>
                        </div>

                        <div class="mt-4 tw:flex tw:justify-end form-forgot">
                            <a href="#" class="text-black">Forgot Password</a>
                        </div>

                        <div class="mt-4 form-button">
                            <button class="tw:w-full tw:py-1.5 tw:bg-black text-white fw-bold tw:hover:bg-gray-700"
                                id="login" type="submit">
                                <div class="loading"></div>Login
                            </button>
                        </div>

                        <div class="text-center form-change" style="margin-top: 1.8rem">
                            <label>Don't have an account? <a href="{{ route("RegisterPage") }}"
                                    class="text-black">Register</a></label>
                        </div>
                    </div>
                </form>
            </div>
        </div>


        {{-- <div class="tw:w-[100%] tw:h-[100vh] tw:bg-white tw:relative login-image">
            <div class="overlay"></div>
            <img src="images/loginbg.jpg" class="tw:h-[100%]" alt="">
        </div> --}}
    </div>

    {{--
    <script>
        const loading = document.querySelector(".loading");
        const login = document.getElementById("login");

        login.addEventListener("click", () => {

            loading.style.display = "block";
            login.style.backgroundColor = "#364153";

            setTimeout(() => {
                loading.style.display = "none";
                login.style.backgroundColor = "";

                window.location.href = "/member-portal";
            }, 3000);

        });

    </script> --}}

    {{--
    <script src="style.js"></script> --}}
</body>

</html>