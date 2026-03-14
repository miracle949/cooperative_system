<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Registration</title>
    <link rel="icon" href="images/websitelogo.png" type="image/png">

    {{-- css link --}}
    <link rel="stylesheet" href="css_folder/register.css">

    {{-- bootstrap and tailwind link --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- font awesome cdn link --}}
    <link rel="stylesheet" href="font-awesome-icon/css/all.min.css">
</head>

<body>

    <div class="container-fluid">

        <div class="nav-logo">
            <div class="logo">

                <img src="images/logo2.png" alt="">

                <div>
                    <h2 class="fw-bold">Membership Application Form</h2>

                    <p>Ready to become part of something special? We can't wait to welcome
                        you.</p>
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

        <form action="{{ route("registration") }}" id="form" method="post" class="needs-validation" novalidate
            enctype="multipart/form-data">
            @csrf
            {{--
            <a href="{{ route(" applicationForm")}}">Go to joel</a> --}}

            <div class="tw:flex tw:justify-center tw:items-center line">
                <hr class="tw:w-[20%] border-2">
            </div>

            <div class="choose-type">
                <div class="choose">
                    <div class="back">
                        <a href="{{ route('LoginPage') }}"><i class="fa fa-arrow-left"></i></a>
                    </div>
                </div>
            </div>

            {{-- <button data-bs-toggle="modal" data-bs-target="#successModal" style="display:block;">Open</button>

            <div class="modal fade" id="successModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static"
                data-bs-keyboard="false">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content border-0 shadow-lg overflow-hidden" style="border-radius: 16px;">

                        <div class="modal-header border-0 d-flex flex-column align-items-center text-center py-4"
                            style="background: #1E4035;">

                            <div class="d-flex justify-content-center align-items-center mb-3"
                                style="width: 72px; height: 72px; border-radius: 50%; background: rgba(255,255,255,0.2); border: 3px solid rgba(255,255,255,0.6);">
                                <i class="fa fa-check" style="font-size: 32px; color: #fff;"></i>
                            </div>

                            <h4 class="modal-title fw-bold text-white mb-1">Registration Successful!</h4>
                            <p class="text-white mb-0" style="opacity: 0.85; font-size: 0.9rem;">Your application has
                                been
                                submitted</p>
                        </div>

                        <div class="modal-body text-center px-4 py-4">

                            <p class="fw-semibold mb-2" style="color: #2d2d2d; font-size: 1rem;">
                                Create account successfully!
                            </p>

                            <hr style="border-color: #e9ecef;">

                            <div class="d-flex align-items-start gap-3 text-start mt-3 p-3 rounded-3"
                                style="background: #f0fdf4; border: 1px solid #bbf7d0;">
                                <i class="fa fa-envelope mt-1" style="color: #16a34a; font-size: 1.1rem;"></i>
                                <p class="mb-0" style="color: #166534; font-size: 0.9rem; line-height: 1.6;">
                                    You will receive an <strong>email notification</strong> once your application
                                    has been reviewed and approved by the Board of Directors. Please check your Gmail
                                    inbox.
                                </p>
                            </div>

                            <div class="d-flex align-items-start gap-3 text-start mt-3 p-3 rounded-3"
                                style="background: #fffbeb; border: 1px solid #fde68a;">
                                <i class="fa fa-clock mt-1" style="color: #d97706; font-size: 1.1rem;"></i>
                                <p class="mb-0" style="color: #92400e; font-size: 0.9rem; line-height: 1.6;">
                                    Processing may take a few business days. Please be patient while we review your
                                    membership
                                    application.
                                </p>
                            </div>
                        </div>


                        <div class="modal-footer border-0 justify-content-center pb-4">
                            <button type="button" class="btn px-5 py-2 fw-semibold text-white" data-bs-dismiss="modal"
                                style="background: #1E4035; border-radius: 50px; border: none; font-size: 1rem; letter-spacing: 0.5px;">
                                OK, Got it!
                            </button>
                        </div>

                    </div>
                </div>
            </div> --}}

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
                <div class="card-box">

                    <!-- Step 1 -->

                    <div class="form-step active">
                        <div class="form-step-header">
                            <h3>Terms & Agreement</h3>
                            <p>Please read the following terms carefully before proceeding.</p>
                        </div>
                        <div class="form-step-body">
                            <div class="row">
                                <div class="col-lg-12">

                                    <label class="lh-lg mt-3">The Undersigned hereby subscribed and agreed to take 100
                                        share
                                        of the KPMPCATS authorized Share Capital with a par value of One Hundred Pesos
                                        (Php100.00) per share amounting to Ten Thousand (P10,000.00) Pesos payable that
                                        was
                                        scheduled within two (2) years, and agrees to pay atleast 25% as initial payment
                                        of
                                        subscription within a Membership Fee of Two Thousand (P2000.00) Pessos.</label>

                                    <label class="lh-lg mt-4">The undersigned agrees further to pay Fifty Thousand
                                        (P50,000.00) Pesos (For Tourist Unit) as entrance fee for my vehicle and
                                        Mobilization Fee of Three Thousand (P3000.00) of the Transport / Vehicle
                                        Operation
                                        of KPMPCATS.</label>

                                    <label class="lh-lg mt-4">The undersigned further pledge to undertake Regular
                                        Savings
                                        and / or Contributions to the Caplital Build-Up of the Cooperative or to its
                                        Programs and Services.</label>

                                    <label class="mt-4">Declaration and Agreement</label>

                                    <div class="form-check mt-3">
                                        <input class="form-check-input" type="checkbox" name="agree1"
                                            id="checkboxDefault1">
                                        <label class="form-check-label" for="checkboxDefault1">
                                            I hereby certify that the foregoing statements are true and correct to the
                                            best
                                            of my knowledge. I understand that any false information may result in the
                                            cancellation of my membership.
                                        </label>
                                    </div>

                                    <div class="form-check mt-3">
                                        <input class="form-check-input" type="checkbox" name="agree2"
                                            id="checkboxDefault2">
                                        <label class="form-check-label" for="checkboxDefault2">
                                            I agree to abide by the Constitution and By-Laws of the Cooperative and to
                                            accept the rights, responsibilities, and obligations of membership. I
                                            understand
                                            that my application is subject to approval by the Board of Directors.
                                        </label>
                                    </div>
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

                </div>

        </form>
    </div>

    {{--
    <script>

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

    <script src="js_folder/vehicle_form.js"></script>
    <script src="js_folder/display_form.js"></script>
    <script src="js_folder/card_form.js"></script>
    <script src="js_folder/picture_display.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/signature_pad@4.0.0/dist/signature_pad.umd.min.js"></script>

    <script>
        window.addEventListener('load', function () {
        const cardBox = document.querySelector('.card-box');
        cardBox.style.animation = 'none';
        cardBox.offsetHeight; // force reflow
        cardBox.style.animation = '';
    });
    </script>

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

    @if (session("success"))
        <button id="triggerModal" data-bs-toggle="modal" data-bs-target="#successModal" style="display:none;"></button>

        <div class="modal fade" id="successModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static"
            data-bs-keyboard="false">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 shadow-lg overflow-hidden" style="border-radius: 16px;">

                    <div class="modal-header border-0 d-flex flex-column align-items-center text-center py-4"
                        style="background: #1E4035;">

                        <div class="d-flex justify-content-center align-items-center mb-3"
                            style="width: 72px; height: 72px; border-radius: 50%; background: rgba(255,255,255,0.2); border: 3px solid rgba(255,255,255,0.6);">
                            <i class="fa fa-check" style="font-size: 32px; color: #fff;"></i>
                        </div>

                        <h4 class="modal-title fw-bold text-white mb-1">Registration Successful!</h4>
                        <p class="text-white mb-0" style="opacity: 0.85; font-size: 0.9rem;">Your application has been
                            submitted</p>
                    </div>

                    <div class="modal-body text-center px-4 py-4">

                        <p class="fw-semibold mb-2" style="color: #2d2d2d; font-size: 1rem;">
                            {{ session("success") }}
                        </p>

                        <hr style="border-color: #e9ecef;">

                        <div class="d-flex align-items-start gap-3 text-start mt-3 p-3 rounded-3"
                            style="background: #f0fdf4; border: 1px solid #bbf7d0;">
                            <i class="fa fa-envelope mt-1" style="color: #16a34a; font-size: 1.1rem;"></i>
                            <p class="mb-0" style="color: #166534; font-size: 0.9rem; line-height: 1.6;">
                                You will receive an <strong>email notification</strong> once your application
                                has been reviewed and approved by the Board of Directors. Please check your Gmail inbox.
                            </p>
                        </div>

                        <div class="d-flex align-items-start gap-3 text-start mt-3 p-3 rounded-3"
                            style="background: #fffbeb; border: 1px solid #fde68a;">
                            <i class="fa fa-clock mt-1" style="color: #d97706; font-size: 1.1rem;"></i>
                            <p class="mb-0" style="color: #92400e; font-size: 0.9rem; line-height: 1.6;">
                                Processing may take a few business days. Please be patient while we review your membership
                                application.
                            </p>
                        </div>
                    </div>


                    <div class="modal-footer border-0 justify-content-center pb-4">
                        <button type="button" class="btn px-5 py-2 fw-semibold text-white" data-bs-dismiss="modal"
                            style="background: #1E4035; border-radius: 50px; border: none; font-size: 1rem; letter-spacing: 0.5px; box-shadow: 0 4px 12px rgba(40,167,69,0.4);">
                            OK, Got it!
                        </button>
                    </div>

                </div>
            </div>
        </div>


        <script>
            window.addEventListener('load', function () {
                document.getElementById('triggerModal').click();

                document.getElementById('successModal').addEventListener('hidden.bs.modal', function () {
                    window.location.href = "{{ route('LoginPage') }}";
                });
            });
        </script>
    @endif

</body>

</html>