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

        <div class="nav-logo">
            <div class="logo">

                <img src="images/logo2.png" alt="">

                <div>
                    <h2 class="fw-bold">Membership Application Form</h2>

                    <p>Ready to become part of something special? We can't wait to welcome
                        you.
                </div>
            </div>

            <div class="logo-image">
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
                <form id="form">

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

    <script>
        const name = document.getElementById("name");
        const date_birth = document.getElementById("date_birth");
        const place_birth = document.getElementById("place_birth");
        const email = document.getElementById("email");
        const member_type = document.getElementById("select_type");
        const civil_status = document.getElementById("civil_status");
        const tin_no = document.getElementById("tin_no");

        const spouse_name = document.getElementById("spouse_name");
        const spouse_date_birth = document.getElementById("spouse_date_birth");
        const spouse_place_birth = document.getElementById("spouse_place_birth");

        const number_son = document.getElementById("number_son");
        const number_daughter = document.getElementById("number_daughter");
        const other_spec = document.getElementById("other_spec");

        const uv = document.getElementById("uv");
        const taxi = document.getElementById("taxi");
        const bus = document.getElementById("bus");
        const tricycle = document.getElementById("tricycle");
        const mini_bus = document.getElementById("mini_bus");
        const jeep = document.getElementById("jeep");
        const multi_cab = document.getElementById("multi_cab");
        const other_info_specify = document.getElementById("other_info_specify");
        

        // personal details

        name.addEventListener("input", () => {
            document.getElementById("name_display").textContent = name.value;
        });

        date_birth.addEventListener("input", () => {
            document.getElementById("date_birth_display").textContent = date_birth.value;
        });

        place_birth.addEventListener("input", () => {
            document.getElementById("place_birth_display").textContent = place_birth.value;
        });

        email.addEventListener("input", () => {
            document.getElementById("email_display").textContent = email.value;
        });

        member_type.addEventListener("input", () => {
            document.getElementById("membership_type_display").textContent = member_type.value;
        });

        civil_status.addEventListener("input", () => {
            document.getElementById("civil_status_display").textContent = civil_status.value;
        });

        tin_no.addEventListener("input", () => {
            document.getElementById("tin_no_display").textContent = tin_no.value;
        });

        // Spouse

        spouse_name.addEventListener("input", () => {
            document.getElementById("spouse_name_display").textContent = spouse_name.value;
        });

        spouse_date_birth.addEventListener("input", () => {
            document.getElementById("spouse_date_birth_display").textContent = spouse_date_birth.value;
        });

        spouse_place_birth.addEventListener("input", () => {
            document.getElementById("spouse_place_birth_display").textContent = spouse_place_birth.value;
        });

        number_son.addEventListener("input", () => {
            document.getElementById("son_display").textContent = number_son.value;
        });

        number_daughter.addEventListener("input", () => {
            document.getElementById("daughter_display").textContent = number_daughter.value;
        });

        other_spec.addEventListener("input", () => {
            document.getElementById("other_spec_display").textContent = other_spec.value;
        });

        // vehicle

        uv.addEventListener("input", () => {
            document.getElementById("uv_display").textContent = uv.value;
        });

        taxi.addEventListener("input", () => {
            document.getElementById("taxi_display").textContent = taxi.value;
        });

        bus.addEventListener("input", () => {
            document.getElementById("bus_display").textContent = bus.value;
        });

        tricycle.addEventListener("input", () => {
            document.getElementById("tricycle_display").textContent = tricycle.value;
        });

        mini_bus.addEventListener("input", () => {
            document.getElementById("mini_bus_display").textContent = mini_bus.value;
        });

        jeep.addEventListener("input", () => {
            document.getElementById("jeep_display").textContent = jeep.value;
        });

        multi_cab.addEventListener("input", () => {
            document.getElementById("multi_cab_display").textContent = multi_cab.value;
        });

        other_info_specify.addEventListener("input", () => {
            document.getElementById("vehi_other_spec").textContent = other_info_specify.value;
        });
    </script>


    <script>
        let currentStep = 0;
        const steps = document.querySelectorAll(".form-step");
        const stepper = document.querySelectorAll(".step");
        const nextBtn = document.querySelector(".btn-next");
        const submitBtn = document.querySelector(".btn-submit");

        function updateSteps() {
            steps.forEach((step, index) => {
                step.classList.toggle("active", index === currentStep);
                stepper[index].classList.toggle("active", index === currentStep);
            });

            if (currentStep === steps.length - 1) {
                nextBtn.style.display = "none";
                submitBtn.style.display = "inline-block";
            } else {
                nextBtn.style.display = "inline-block";
                submitBtn.style.display = "none";
            }
        }

        function nextStep() {
            const currentFormStep = steps[currentStep];

            // get all required inputs & selects in CURRENT step only
            const requiredFields = currentFormStep.querySelectorAll("input[required], select[required]");

            for (let field of requiredFields) {
                if (!field.checkValidity()) {
                    field.reportValidity(); // show browser validation message
                    return; // STOP going to next step
                }
            }

            // if all fields are valid → go next
            if (currentStep < steps.length - 1) {
                currentStep++;
                updateSteps();
            }
        }

        function prevStep() {
            if (currentStep > 0) {
                currentStep--;
                updateSteps();
            }
        }
    </script>

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
    </script>

</body>

</html>