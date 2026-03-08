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
    <link rel="stylesheet" href="font-awesome-icon/css/all.min.css">
</head>

<body>

    <div class="container-fluid">

        <form action="{{ route("registration") }}" id="form" method="post" class="needs-validation" novalidate
            enctype="multipart/form-data">
            @csrf

            <div class="nav-logo">
                <div class="logo">

                    <img src="images/logo2.png" alt="">

                    <div>
                        <h2 class="fw-bold">Membership Application Form</h2>

                        <p>Ready to become part of something special? We can't wait to welcome
                            you.
                    </div>
                </div>

                {{-- <div class="logo-image">
                    <div class="tw:w-[210px] tw:h-[160px] tw:bg-white tw:flex tw:justify-center tw:items-center tw:flex-col picture"
                        style="border: 1px solid rgba(0,0,0,0.3); border-radius: 10px;">
                        <img src="" alt="" class="tw:w-[210px] tw:h-[160px]" id="inputImage">
                        <p class="fw-semibold" id="text">2 x 2</p>

                        <p class="tw:text-[#808080]" id="text2">Click here!</p>

                        <input type="file" name="profile_picture" id="inputBox" class="form-control">
                    </div>
                </div> --}}
            </div>
            {{--
            <a href="{{ route(" applicationForm")}}">Go to joel</a> --}}

            <div class="tw:flex tw:justify-center tw:items-center line">
                <hr class="tw:w-[20%] border-2 tw:border-black">
            </div>

            <div class="choose-type">
                <div class="choose">
                    <div class="back">
                        <a href="{{ route('LoginPage') }}"><i class="fa fa-arrow-left"></i></a>
                    </div>
                </div>
            </div>

            <div class="form-box">
                <div class="stepper">
                    <div class="step active">
                        <div class="d-flex justify-content-center align-items-center flex-column">
                            <div class="circle">1</div>
                            <div class="label">Terms & Agreement</div>
                        </div>
                    </div>
                    <div class="step">
                        <div class="d-flex justify-content-center align-items-center flex-column">
                            <div class="circle">2</div>
                            <div class="label">Personal Data</div>
                        </div>
                    </div>
                    <div class="step">
                        <div class="d-flex justify-content-center align-items-center flex-column">
                            <div class="circle">3</div>
                            <div class="label">Other Information</div>
                        </div>
                    </div>

                    {{-- <div class="step">
                        <div class="d-flex justify-content-center align-items-center flex-column">
                            <div class="circle">4</div>
                            <div class="label">Personal Data Sheet</div>
                        </div>
                    </div> --}}

                    <div class="step">
                        <div class="d-flex justify-content-center align-items-center flex-column">
                            <div class="circle">4</div>
                            <div class="label">Review & Submit</div>
                        </div>
                    </div>
                </div>

                <!-- Form -->
                <div class="card">

                    <!-- Step 1 -->

                    <div class="form-step active">
                        <h3>Terms & Agreement</h3>
                        <div class="row">
                            <div class="col-lg-12">

                                <label class="lh-lg mt-3">The Undersigned hereby subscribed and agreed to take 100 share
                                    of the KPMPCATS authorized Share Capital with a par value of One Hundred Pesos
                                    (Php100.00) per share amounting to Ten Thousand (P10,000.00) Pesos payable that was
                                    scheduled within two (2) years, and agrees to pay atleast 25% as initial payment of
                                    subscription within a Membership Fee of Two Thousand (P2000.00) Pessos.</label>

                                <label class="lh-lg mt-4">The undersigned agrees further to pay Fifty Thousand
                                    (P50,000.00) Pesos (For Tourist Unit) as entrance fee for my vehicle and
                                    Mobilization Fee of Three Thousand (P3000.00) of the Transport / Vehicle Operation
                                    of KPMPCATS.</label>

                                <label class="lh-lg mt-4">The undersigned further pledge to undertake Regular Savings
                                    and / or Contributions to the Caplital Build-Up of the Cooperative or to its
                                    Programs and Services.</label>

                                <label class="mt-4">Declaration and Agreement</label>

                                <div class="form-check mt-3">
                                    <input class="form-check-input" type="checkbox" name="agree1" id="checkboxDefault1">
                                    <label class="form-check-label" for="checkboxDefault1">
                                        I hereby certify that the foregoing statements are true and correct to the best
                                        of my knowledge. I understand that any false information may result in the
                                        cancellation of my membership.
                                    </label>
                                </div>

                                <div class="form-check mt-3">
                                    <input class="form-check-input" type="checkbox" name="agree2" id="checkboxDefault2">
                                    <label class="form-check-label" for="checkboxDefault2">
                                        I agree to abide by the Constitution and By-Laws of the Cooperative and to
                                        accept the rights, responsibilities, and obligations of membership. I understand
                                        that my application is subject to approval by the Board of Directors.
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Step 2 -->
                    @include('membership_components.personal_data')

                    <!-- Step 3 -->
                    @include('membership_components.other_information')

                    {{-- @include('membership_components.personal_data_sheet') --}}

                    {{-- Step 4 --}}
                    @include('membership_components.review_submit')

                    <!-- Step 6 -->

                    <!-- Buttons -->
                    <div class="actions">
                        <button type="button" class="btn-prev" onclick="prevStep()">
                            <i class="fa fa-chevron-left"></i>
                            <span>Previous</span>
                        </button>
                        <button type="button" class="btn-next" onclick="nextStep()">
                            <span>Next Step</span>
                            <i class="fa fa-chevron-right"></i>
                        </button>
                        <button type="submit" class="btn-submit" style="display:none;">Submit</button>
                    </div>

        </form>
    </div>
    </div>
    </div>

    <script src="js_folder/vehicle_form.js"></script>

    <script src="js_folder/display_form.js"></script>

    <script src="js_folder/card_form.js"></script>

    <script src="js_folder/picture_display.js"></script>

    {{-- <script>

        const select_type = document.getElementById("select_type");

        select_type.addEventListener("change", function () {

            const driver_operator = document.querySelector(".driver-operator");

            if (this.value === "Driver") {

                driver_operator.style.display = "none";
            } else if (this.value === "Allied Workers") {

                driver_operator.style.display = "none";

            } else if (this.value !== "Driver") {

                driver_operator.style.display = "block";
            }

        });
    </script> --}}

    <script src="https://cdn.jsdelivr.net/npm/signature_pad@4.0.0/dist/signature_pad.umd.min.js"></script>


    <script>
        const canvas = document.getElementById('signature-pad');
        const signaturePad = new SignaturePad(canvas);

        document.querySelector("form").addEventListener("submit", function () {
            if (!signaturePad.isEmpty()) {
                document.getElementById('signature').value = signaturePad.toDataURL();
            }
        });

        document.getElementById('clear').addEventListener('click', function () {
            signaturePad.clear();
        });
    </script>

</body>

</html>