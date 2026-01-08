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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css"
        integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    {{-- bootstrap link --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
</head>

<body>

    <div class="container-fluid p-0 m-0 tw:flex tw:justify-between tw:items-center">

        <div class="loading-screen">

            <div class="load"></div>

        </div>
        
        <div class="tw:w-[100%] tw:h-[100vh] tw:bg-white tw:relative login-image">
            <div class="overlay"></div>
            <img src="images/loginbg.jpg" class="tw:h-[100%]" alt="">
        </div>
        <div class="tw:flex tw:justify-center tw:items-center tw:w-[100%] tw:h-[100%]" style="position: relative">

            <div class="back-to-landing">
                <a href="{{ route("Landingpage") }}">
                    <i class="fa fa-arrow-left"></i>
                </a>
            </div>

            <div class="form-parent">
                <div class="logo tw:flex tw:justify-center tw:items-center">
                    <a href="{{ route("Landingpage") }}"><img src="images/logo2.png" alt=""></a>
                </div>

                <h2 class="fw-semibold mt-4 text-center">Login your account</h2>

                <p class="mt-3 tw:w-[400px] text-center">Welcome back! Please log in to securely manage your account.
                </p>

                <div class="mt-3">
                    <label>Username or Email</label>
                    <div class="input-group mt-2">
                        <span class="input-group-text"><i class="fa fa-user"></i></span>
                        <input type="text" name="username_email" placeholder="Enter your username or email"
                            class="form-control" required>
                    </div>
                </div>

                <div class="mt-3">
                    <label>Password</label>
                    <div class="input-group mt-2">
                        <span class="input-group-text"><i class="fa fa-lock"></i></span>
                        <input type="password" name="password" placeholder="Enter your password" class="form-control"
                            required>
                    </div>
                </div>

                <div class="mt-4 tw:flex tw:justify-end">
                    <a href="#" class="text-black">Forgot Password</a>
                </div>

                <div class="mt-4">
                    <button class="tw:w-full tw:py-1.5 tw:bg-black text-white fw-bold tw:hover:bg-gray-700" id="login"
                        type="submit">
                        <div class="loading"></div>Login
                    </button>
                </div>

                <div class="text-center" style="margin-top: 1.8rem">
                    <label>Don't have an account? <a href="{{ route("RegisterPage") }}" class="text-black">Register</a></label>
                </div>
            </div>
        </div>
    </div>

    {{-- Bootstrap link --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
        crossorigin="anonymous"></script>

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

    </script>

     {{-- <script src="style.js"></script> --}}
</body>

</html>