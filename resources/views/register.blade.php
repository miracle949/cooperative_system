<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>

    {{-- css link --}}
    <link rel="stylesheet" href="css_folder/register.css">

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

    <div class="container-fluid p-4 pb-5">

        <div class="row">
            <div class="col-6 tw:flex tw:justify-center tw:items-center logo">

                <img src="images/logo2.png" class="ms-5" alt="">

                <div>
                    <h2 class="mt-3 ms-4 fw-bold">Membership Application Form</h2>

                    <p class="ms-4 mt-3 tw:w-[85%]">Ready to become part of something special? We can't wait to welcome
                        you.
                </div>
                </p>
            </div>

            <div class="col-6 tw:flex tw:justify-end">
                <div class="tw:w-[210px] tw:h-[160px] tw:bg-white tw:flex tw:justify-center tw:items-center tw:flex-col picture"
                    style="border: 1px solid rgba(0,0,0,0.3); border-radius: 10px;">
                    <img src="" alt="" class="tw:w-[210px] tw:h-[160px]" id="inputImage">
                    <p class="fw-semibold" id="text">2 x 2</p>

                    <p class="tw:text-[#808080]" id="text2">Photo Here</p>
                </div>
            </div>
        </div>

        <div class="tw:flex tw:justify-center tw:items-center mt-4">
            <hr class="tw:w-[20%] border-2 tw:border-black">
        </div>

        <section class="mt-5 pt-3 ms-5">
            <div class="row tw:flex tw:justify-left tw:items-center">
                <div class="col-6">
                    <label>Name</label>
                </div>

                <div class="col-2">
                    <input type="text" name="username" placeholder="Enter firstname" class="form-control">
                </div>

                <div class="col-2">
                    <input type="text" name="username" placeholder="Enter lastname" class="form-control">
                </div>

                <div class="col-2">
                    <input type="text" name="username" placeholder="Enter lastname" class="form-control">
                </div>
            </div>

            <div class="row tw:flex tw:justify-left tw:items-center mt-4">
                <div class="col-6">
                    <label>Username <span>*</span></label>
                </div>

                <div class="col-6">

                    <input type="text" name="username" placeholder="Enter username" class="form-control">
                </div>
            </div>

            <div class="row tw:flex tw:justify-left tw:items-center mt-4">
                <div class="col-6">
                    <label>Email <span>*</span></label>
                </div>

                <div class="col-6">
                    <input type="text" name="username" placeholder="Enter email" class="form-control">
                </div>
            </div>

            <div class="row tw:flex tw:justify-left tw:items-center mt-4">
                <div class="col-6">
                    <label>Password <span>*</span></label>
                </div>

                <div class="col-6">
                    <input type="text" name="username" placeholder="Enter password" class="form-control">
                </div>
            </div>

            <div class="row tw:flex tw:justify-left tw:items-center mt-4">
                <div class="col-6">
                    <label>Confirm Password <span>*</span></label>
                </div>

                <div class="col-6">
                    <input type="text" name="username" placeholder="Confirm Password" class="form-control">
                </div>
            </div>

            <div class="row tw:flex tw:justify-left tw:items-center mt-4">
                <div class="col-6">
                    <label>Phone number <span>*</span></label>
                </div>

                <div class="col-6">
                    <input type="text" name="username" placeholder="Enter phone number" class="form-control">
                </div>
            </div>

            <div class="row tw:flex tw:justify-left tw:items-center mt-4">
                <div class="col-6">
                    <label>Address <span>*</span></label>
                </div>

                <div class="col-6">
                    <input type="text" name="username" placeholder="Enter address" class="form-control">
                </div>
            </div>

            <div class="row tw:flex tw:justify-left tw:items-center mt-4">
                <div class="col-6">
                    <label>Upload picture <span>*</span></label>
                </div>

                <div class="col-6">
                    <input type="file" name="username" placeholder="Confirm Password" class="form-control"
                        id="inputBox">
                </div>
            </div>

            <div class="row mt-4">
                <div class="col-6">

                </div>

                <div class="col-6 tw:flex tw:justify-left tw:items-center terms-agreements">
                    <label><input type="checkbox" name="" id=""> I agree to the <a href="#" class="text-black">Terms &
                            Condition</a>
                    </label>
                </div>
            </div>

            <div class="row mt-4">
                <div class="col-6">

                </div>
                <div class="col-6 text-center">
                    <button class="tw:w-full tw:hover:bg-gray-700 tw:bg-black tw:text-white" id="register">
                        <div class="loading"></div>Submit
                    </button>
                </div>
            </div>

            <div class="row mt-5">
                <div class="col-6">

                </div>

                <div class="col-6">
                    <div class="text-center">
                        <label>Already have an account? <a href="{{ route("LoginPage") }}"
                                class="text-black">Login</a></label>
                    </div>
                </div>
            </div>
        </section>

    </div>

    {{-- Bootstrap link --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
        crossorigin="anonymous"></script>

    <script>
        const imageFile = document.querySelector('#inputImage');
        const inputBox = document.querySelector('#inputBox');

        inputBox.addEventListener('change', function () {

            imageFile.style.display = "block";
            document.getElementById("text").style.display = "none";
            document.getElementById("text2").style.display = "none";
            imageFile.src = window.URL.createObjectURL(this.files[0]);

        });
    </script>

    <script>
        const loading = document.querySelector(".loading");
        const register = document.getElementById("register");

        register.addEventListener("click", () => {

            loading.style.display = "block";
            register.style.backgroundColor = "#364153";

            setTimeout(() => {
                loading.style.display = "none";
                register.style.backgroundColor = "";

                window.location.href = "/login-page";
            }, 3000);

        });

    </script>
</body>

</html>