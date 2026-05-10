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

            <div class="left-bg"></div>
            <div class="left-diagonal"></div>

            <div class="form-nav">
                <h4>KPMPCATS</h4>

                <a href="{{ route("index") }}">
                    <i class="fa fa-chevron-left"></i>
                    <span>Home</span>
                </a>
            </div>

            <div class="form-body">
                <div class="form-portal">Member Portal</div>

                <h3>Welcome <b>Back, Member</b></h3>

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
                        <span>Uptime</span>
                    </div>

                    <div class="members">
                        <h4>256-bit</h4>
                        <span>Encryption</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="form-box-parent">
            <div class="form-parent">
                {{-- <div class="logo tw:flex tw:justify-center tw:items-center">
                    <a href="{{ route(" index") }}"><img src="images/logo2.png" alt=""></a>
                </div> --}}

                <div class="nav-form">

                    <div class="card-corner"></div>
                    <div class="card-corner-bl"></div>

                    <div class="nav-tag">Member Access</div>

                    <h2 class="text-left">Sign in to your <b>account</b></h2>

                    <p class="text-left">Enter your credentials to access the member portal.</p>

                    <div class="form-nav-divider"></div>

                </div>

                <form action="{{ route("UserLogin") }}" method="post">
                    @csrf
                    <div class="form-sub-parent">
                        @if ($errors->any())
                            <div
                                style="background:#fef0f0; border:1.5px solid #f5c6c6; border-radius:4px; padding:0.8rem 1rem; margin: 1rem 0 1rem; font-size:0.85rem; color:#e03131; font-weight:600;">
                                <i class="fa-solid fa-circle-xmark"></i>
                                {{ $errors->first('login') }}
                            </div>
                        @endif

                        <div class="form-input">
                            <label>Email Address</label>
                            <div style="position: relative;">
                                <div class="envelope"
                                    style="position: absolute; left: 16px; top: 36.2%; color: var(--blue);">
                                    <i class="fa fa-envelope"></i>
                                </div>
                                <input type="text" name="login" value="{{ old('login') }}"
                                    placeholder="Enter your email" class="mt-2" required>
                                <div class="focus-bar"></div>
                            </div>
                        </div>

                        <div class="form-input">
                            <label>Password</label>
                            <div style="position: relative;">
                                <div class="lock"
                                    style="position: absolute; left: 16px; top: 36.2%; color: var(--blue);">
                                    <i class="fa fa-lock"></i>
                                </div>
                                <input type="password" name="password" id="login-password"
                                    placeholder="Enter your password" class="mt-2" style="padding-right: 40px;"
                                    required>
                                <div class="focus-bar"></div>
                                <span onclick="toggleLoginPassword()"
                                    style="position: absolute; right: 12px; top: 60%; transform: translateY(-50%); cursor: pointer; color: #888;">
                                    <i class="fa fa-eye" id="eye-login"></i>
                                </span>
                            </div>
                        </div>

                        <div class="mt-4 tw:flex tw:justify-end form-forgot">
                            <a href="#">Forgot Password</a>
                        </div>

                        <div class="mt-4 form-button">
                            <button class="tw:w-full tw:py-1.5 tw:bg-black text-white fw-bold tw:hover:bg-gray-700"
                                id="login" type="submit">
                                <span>Sign In</span>
                                <i class="fa fa-arrow-right"></i>
                            </button>
                        </div>

                        <div class="form-divider">
                            or
                        </div>

                        <div class="text-center form-change">
                            <label>Don't have an account? <a href="{{ route("RegisterPage") }}">Apply for Membership <i
                                        class="fa fa-arrow-right"></i></a></label>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <script>
            function toggleLoginPassword() {
                const input = document.getElementById('login-password');
                const icon = document.getElementById('eye-login');
                if (input.type === 'password') {
                    input.type = 'text';
                    icon.classList.replace('fa-eye', 'fa-eye-slash');
                } else {
                    input.type = 'password';
                    icon.classList.replace('fa-eye-slash', 'fa-eye');
                }
            }
        </script>


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