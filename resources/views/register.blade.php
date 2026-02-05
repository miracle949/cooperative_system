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
    {{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css"
        integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" /> --}}

    {{-- bootstrap link --}}
    {{-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous"> --}}
</head>

<body>

    <div class="container-fluid p-4 pb-5">

        <div class="row nav-logo">
            <div class="col-lg-7 col-md-8 col-sm-7 tw:flex tw:justify-center tw:items-center logo">

                <img src="images/logo2.png" class="ms-5" alt="">

                <div>
                    <h2 class="fw-bold">Membership Application Form</h2>

                    <p>Ready to become part of something special? We can't wait to welcome
                        you.
                </div>
                </p>
            </div>

            <div class="col-lg-5 col-md-4 col-sm-5 tw:flex logo-image">
                <div class="tw:w-[210px] tw:h-[160px] tw:bg-white tw:flex tw:justify-center tw:items-center tw:flex-col picture"
                    style="border: 1px solid rgba(0,0,0,0.3); border-radius: 10px;">
                    <img src="" alt="" class="tw:w-[210px] tw:h-[160px]" id="inputImage">
                    <p class="fw-semibold" id="text">2 x 2</p>

                    <p class="tw:text-[#808080]" id="text2">Photo Here</p>
                </div>
            </div>
        </div>

        <div class="tw:flex tw:justify-center tw:items-center line">
            <hr class="tw:w-[20%] border-2 tw:border-black">
        </div>

        <section>


            <div class="row">
                <div class="col-lg-3 col-md-6">
                    <div class="choose-type">
                        <label>What membership type are you?</label>
                        {{-- <select name="" class="form-select mt-2" id="select_type" required>
                            <option value="" disabled selected>Select type</option>
                            <option value="Operator">Operator</option>
                            <option value="Driver">Driver</option>
                            <option value="Driver-Operator">Driver-Operator</option>
                            <option value="Investor Associate">Investor Associate</option>
                            <option value="Allied Workers">Allied Workers</option>
                            <option value="Transport Entrepreneur">Transport Entrepreneur</option>
                        </select> --}}
                        <select name="" id="select_type" class="form-select mt-2" required>
                            <option value="" disabled selected>Select type</option>
                            <option value="Operator">Operator</option>
                            <option value="Driver">Driver</option>
                            <option value="Driver-Operator">Driver-Operator</option>
                            <option value="Investor Associate">Investor Associate</option>
                            <option value="Allied Workers">Allied Workers</option>
                            <option value="Transport Entrepreneur">Transport Entrepreneur</option>
                        </select>
                    </div>
                </div>
            </div>

            <h2>Personal Data</h2>

            <div class="row">
                <div class="col-lg-4 mt-4">
                    <label>Name:</label>
                    <input type="text" name="name" id="" class="form-control mt-2">
                </div>

                <div class="col-lg-4 mt-4">
                    <label>Date of Birth:</label>
                    <input type="date" name="date_birth" id="" class="form-control mt-2">
                </div>

                <div class="col-lg-4 mt-4">
                    <label>Place of Birth:</label>
                    <input type="text" name="place_birth" id="" class="form-control mt-2">
                </div>

                <div class="col-lg-6 mt-4">
                    <label>Civil Status:</label>
                    <input type="text" name="civil_status" id="" class="form-control mt-2">
                </div>

                <div class="col-lg-6 mt-4">
                    <label>Address:</label>
                    <input type="text" name="address" id="" class="form-control mt-2">
                </div>

                <div class="col-lg-4 mt-4">
                    <label>Spouse Name:</label>
                    <input type="text" name="spouse_name" id="" class="form-control mt-2">
                </div>

                <div class="col-lg-4 mt-4">
                    <label>Date of Birth:</label>
                    <input type="date" name="date_birth" id="" class="form-control mt-2">
                </div>

                <div class="col-lg-4 mt-4">
                    <label>Place of Birth:</label>
                    <input type="text" name="place_birth" id="" class="form-control mt-2">
                </div>

                <div class="col-lg-6 mt-4">
                    <label>Educational Attachment:</label>
                    <input type="text" name="education_attachment" id=""
                        class="form-control mt-2">
                </div>

                <div class="col-lg-6 mt-4">
                    <label>Course:</label>
                    <input type="text" name="course" id="" class="form-control mt-2">
                </div>

                <div class="col-lg-12 mt-4">
                    <label>Number of Dependents:</label>
                    <div class="row">
                        <div class="col-lg-4 col-md-12 mt-2">
                            <label>Son</label>
                            <input type="text" name="son" id="" class="form-control mt-2">
                        </div>

                        <div class="col-lg-4 col-md-12 mt-2">
                            <label>Daughter</label>
                            <input type="text" name="son" id="" class="form-control mt-2">
                        </div>

                        <div class="col-lg-4 col-md-12 mt-2">
                            <label>Other-Specify</label>
                            <input type="text" name="son" id="" class="form-control mt-2">
                        </div>
                    </div>
                </div>

                <div class="col-lg-6 mt-4">
                    <label>TIN NO:</label>
                    <input type="number" name="tin_no" id="" class="form-control mt-2">
                </div>
            </div>

            <h2 class="mt-5">Other Information</h2>

            {{-- <div class="driver row">
                <div class="col-lg-8 col-md-12">
                    <label>Are your a member of any other Transport Cooperative or Association? if Yes; state the name, location and type of Coop. Answer :</label>
                </div>

                <div class="col-lg-8 col-md-12">
                    <textarea id="exampleFormControlTextarea1" required  class="form-control mt-2" rows="8">

                    </textarea>
                </div>
            </div> --}}

            <div class="driver-operator">
                <div class="row">
                    <div class="col-lg-12">
                        <label>Number of Public Utility Vehicle Owned:</label>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-4 mt-4">
                        <label>UV's</label>
                        <input type="text" name="uv" id="" class="form-control mt-2">
                    </div>

                    <div class="col-lg-4 mt-4">
                        <label>TAXI</label>
                        <input type="text" name="uv" id="" class="form-control mt-2">
                    </div>

                    <div class="col-lg-4 mt-4">
                        <label>BUS</label>
                        <input type="text" name="uv" id="" class="form-control mt-2">
                    </div>

                    <div class="col-lg-4 mt-4">
                        <label>MULTI-CAB</label>
                        <input type="text" name="uv" id="" class="form-control mt-2">
                    </div>

                    <div class="col-lg-4 mt-4">
                        <label>TRICYCLE</label>
                        <input type="text" name="uv" id="" class="form-control mt-2">
                    </div>

                    <div class="col-lg-4 mt-4">
                        <label>Other Specify</label>
                        <input type="text" name="uv" id="" class="form-control mt-2">
                    </div>
                </div>
            </div>

            <div class="row mt-4">
                <div class="col-lg-8 col-md-12">
                    <label for="exampleFormControlTextarea1">Are you a member of any other Transport Cooperative or Association? if Yes; state the name,
                        location and type of Coop. Answer:</label>
                </div>
                <div class="col-lg-8 col-md-12">
                    <textarea name="" id="exampleFormControlTextarea1" required  class="form-control mt-2" rows="8"></textarea>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-8 col-md-12 mt-5">
                    <label>As provided for in KPMPCATS art of Cooperation and By-Laws, are you willing to accept the liability of the share holders up to the amount your subscription?</label>

                    <div class="row">
                       <div class="col-lg-2 mt-2">
                            <div class="form-check d-flex align-items-center gap-2">
                                <input class="form-check-input" type="radio" name="question1" id="radioDefault1">
                                <label class="form-check-label" for="radioDefault1">
                                    Yes
                                </label>
                            </div>
                        </div>

                        <div class="col-lg-2 mt-2">
                            <div class="form-check d-flex align-items-center gap-2">
                                <input class="form-check-input" type="radio" name="question1" id="radioDefault2">
                                <label class="form-check-label" for="radioDefault2">
                                    No
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mt-5">
                <div class="col-lg-8">
                    <label>Are you willing to abide by the policies, rules and regulations that may be imposed by the CDA, OTC and KPMPCATS in accordance with the existing Laws and Articles of Cooperation and By - Laws?</label>

                    <div class="row">
                        <div class="col-lg-2 mt-2">
                            <div class="form-check d-flex align-items-center gap-2">
                                <input class="form-check-input" type="radio" name="question2" id="radioDefault3">
                                <label class="form-check-label" for="radioDefault3">
                                    Yes
                                </label>
                            </div>
                        </div>

                        <div class="col-lg-2 mt-2">
                            <div class="form-check d-flex align-items-center gap-2">
                                <input class="form-check-input" type="radio" name="question2" id="radioDefault4">
                                <label class="form-check-label" for="radioDefault4">
                                    No
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-4 d-flex justify-content-bottom align-items-center gap-2">
                <label>Submitted this</label>
                <div class="col-lg-2">
                    <input type="text" name="" id="" class="form-control">
                </div>
                
                <label>day of</label>

                <div class="col-lg-2">
                    <input type="text" name="" id="" class="form-control">
                </div>

                <label>20</label>

                <div class="col-lg-2">
                    <input type="text" name="" id="" class="form-control">
                </div>
            </div>

            <div class="row mt-4">
                <div class="col-lg-12">
                    <label>I HEREBY CERTIFY that the foregoing statetments are True and Correct:</label>
                </div>
            </div>
            
            {{-- <div class="row mt-4">

                <div class="col-lg-12 d-flex justify-content-end align-items-end flex-column gap-3">
                    <div class="signature-picture">

                    </div>
                    <label>(Signature Over Printed Name)</label>
                </div>
            </div> --}}

            <div class="mt-5 d-flex justify-content-center align-items-end flex-column gap-3">
                <div class="signature-picture">
                    <input type="file" name="" id="" class="form-control">
                </div>
                <label>(Signature Over Printed Name)</label>
            </div>

            <div class="mt-4">
                <hr>
            </div>

            <div class="mt-4">
                <span>Note:</span>
                <div class="d-flex gap-3 align-items-center">
                    <label>This Application was Approved / Disapproved by the Board of Directors in</label>
                    <div class="col-lg-2">
                        <input type="text" name="" class="form-control" id="">
                    </div>
                    <label>its meeting held</label>
                </div>
            </div>

            <div class="row mt-4">
                <div class="col-lg-12 text-center">
                    <button class="btn btn-dark w-25">Submit</button>
                </div>
            </div>
        </section>
        

        {{-- <section class="pt-3">
            <div class="row tw:flex tw:justify-left tw:items-center fullname">
                <div class="col-6 label-title">
                    <label>Name *</label>
                </div>

                <div class="col-lg-2 col-md-4">
                    <input type="text" name="username" placeholder="Enter firstname" class="form-control">
                </div>

                <div class="col-lg-2 col-md-4">
                    <input type="text" name="username" placeholder="Enter lastname" class="form-control">
                </div>

                <div class="col-lg-2 col-md-4">
                    <input type="text" name="username" placeholder="Enter lastname" class="form-control">
                </div>
            </div>

            <div class="row tw:flex tw:justify-left tw:items-center mt-4">
                <div class="col-6 label-title">
                    <label>Username <span>*</span></label>
                </div>

                <div class="col-lg-6 col-md-12">

                    <input type="text" name="username" placeholder="Enter username" class="form-control">
                </div>
            </div>

            <div class="row tw:flex tw:justify-left tw:items-center mt-4">
                <div class="col-6 label-title">
                    <label>Email <span>*</span></label>
                </div>

                <div class="col-lg-6 col-md-12">
                    <input type="text" name="username" placeholder="Enter email" class="form-control">
                </div>
            </div>

            <div class="row tw:flex tw:justify-left tw:items-center mt-4">
                <div class="col-6 label-title">
                    <label>Password <span>*</span></label>
                </div>

                <div class="col-lg-6 col-md-12">
                    <input type="text" name="username" placeholder="Enter password" class="form-control">
                </div>
            </div>

            <div class="row tw:flex tw:justify-left tw:items-center mt-4">
                <div class="col-6 label-title">
                    <label>Confirm Password <span>*</span></label>
                </div>

                <div class="col-lg-6 col-md-12">
                    <input type="text" name="username" placeholder="Confirm Password" class="form-control">
                </div>
            </div>

            <div class="row tw:flex tw:justify-left tw:items-center mt-4">
                <div class="col-6 label-title">
                    <label>Phone number <span>*</span></label>
                </div>

                <div class="col-lg-6 col-md-12">
                    <input type="text" name="username" placeholder="Enter phone number" class="form-control">
                </div>
            </div>

            <div class="row tw:flex tw:justify-left tw:items-center mt-4">
                <div class="col-6 label-title">
                    <label>Address <span>*</span></label>
                </div>

                <div class="col-lg-6 col-md-12">
                    <input type="text" name="username" placeholder="Enter address" class="form-control">
                </div>
            </div>

            <div class="row tw:flex tw:justify-left tw:items-center mt-4">
                <div class="col-6 label-title">
                    <label>Upload picture <span>*</span></label>
                </div>

                <div class="col-lg-6 col-md-12">
                    <input type="file" name="username" placeholder="Confirm Password" class="form-control"
                        id="inputBox">
                </div>
            </div>

            <div class="row mt-4">
                <div class="col-lg-6 col-md-12 label-title">

                </div>

                <div class="col-lg-6 col-md-12 tw:flex tw:justify-left tw:items-center terms-agreements">
                    <label><input type="checkbox" name="" id=""> I agree to the <a href="#" class="text-black">Terms &
                            Condition</a>
                    </label>
                </div>
            </div>

            <div class="row mt-4">
                <div class="col-lg-6 col-md-12 label-title">

                </div>
                <div class="col-lg-6 col-md-12 text-center">
                    <button class="tw:w-full tw:hover:bg-gray-700 tw:bg-black tw:text-white" id="register">
                        <div class="loading"></div>Submit
                    </button>
                </div>
            </div>

            <div class="row mt-5">
                <div class="col-6 label-title">

                </div>

                <div class="col-lg-6 col-md-12">
                    <div class="text-center">
                        <label>Already have an account? <a href="{{ route(" LoginPage") }}"
                                class="text-black">Login</a></label>
                    </div>
                </div>
            </div>
        </section> --}}

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
        // const other_page = document.querySelector(".other-page");
        // const other_page_1 = document.querySelector(".other-page-1");
        const select_type = document.getElementById("select_type");

        select_type.addEventListener("change", function () {

            // const driver = document.querySelector(".driver");
            const driver_operator = document.querySelector(".driver-operator");

            if(this.value === "Driver"){
                // driver.style.display = "block";
                
                driver_operator.style.display = "none";
            }else if(this.value === "Allied Workers"){

                driver_operator.style.display = "none";
                
            }else if(this.value !== "Driver"){
                // driver.style.display = "none";
                
                driver_operator.style.display = "block";
            }

        });

        // select_type.addEventListener("change", function () {
        //     console.log(this.value);
        // });

        

        // if(select_type.value == "Driver"){
        //     other_page.style.display = "flex";
        //     other_page_1.style.display = "none";
        // }

        // const selectedValue = select_type.value;

        // console.log(selectedValue);

        
    </script>

    {{--
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

    </script> --}}
</body>

</html>