<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link rel="icon" href="images/websitelogo.png" type="image/png">

    {{-- css link --}}
    {{--
    <link rel="stylesheet" href="css_folder/application-form.css"> --}}
    <link rel="stylesheet" href="{{ asset('css_folder/application-form.css') }}">

    {{-- bootstrap and tailwind link --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- font awesome cdn link --}}
    {{--
    <link rel="stylesheet" href="font-awesome-icon/css/all.min.css"> --}}

    <link rel="stylesheet" href="{{ asset('font-awesome-icon/css/all.min.css') }}">
</head>

<body>
    <div class="container-fluid">
        <form action="" method="post">
            @csrf

            <div class="form-box">
                <div class="stepper">
                    <div class="step active">
                        <div class="d-flex justify-content-center align-items-center flex-column">
                            <div class="circle">1</div>
                            <div class="label">First page</div>
                        </div>
                    </div>

                    <div class="step">
                        <div class="d-flex justify-content-center align-items-center flex-column">
                            <div class="circle">2</div>
                            <div class="label">Second page</div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="form-step active">

                        <div class="header-card">
                            <h2>Membership Application Form</h2>
                        </div>

                        <h3 class="mt-3">Personal Information</h3>

                        <div class="row">
                            <div class="col-lg-4 mt-4">
                                <label>Firstname</label>
                                <input type="text" name="first_name" value="{{ $user->first_name ?? '' }}"
                                    class="form-control mt-1">
                            </div>

                            <div class="col-lg-4 mt-4">
                                <label>Middle Initial</label>
                                <input type="text" name="middle_name" value="{{ $user->middle_name ?? '' }}"
                                    class="form-control mt-1">
                            </div>

                            <div class="col-lg-4 mt-4">
                                <label>Lastname</label>
                                <input type="text" name="last_name" value="{{ $user->last_name ?? '' }}"
                                    class="form-control mt-1">
                            </div>

                            <div class="col-lg-4 mt-4">
                                <label>Email</label>
                                <input type="email" name="email" value="{{ $user->email ?? '' }}"
                                    class="form-control mt-1">
                            </div>

                            <div class="col-lg-4 mt-4">
                                <label>Date of Birth</label>
                                <input type="text" name="date_of_birth" value="{{ $user->date_of_birth ?? '' }}"
                                    class="form-control mt-1">
                            </div>

                            <div class="col-lg-4 mt-4">
                                <label>Place of Birth</label>
                                <input type="text" name="place_of_birth" value="{{ $user->place_of_birth ?? '' }}"
                                    class="form-control mt-1">
                            </div>

                            <div class="col-lg-4 mt-4">
                                <label>Contact No</label>
                                <input type="text" name="contact_no" value="{{ $user->contact_no ?? '' }}"
                                    class="form-control mt-1">
                            </div>

                            <div class="col-lg-4 mt-4">
                                <label>Present Address</label>
                                <input type="text" name="present_address" value="{{ $user->present_address ?? '' }}"
                                    class="form-control mt-1">
                            </div>

                            <div class="col-lg-4 mt-4">
                                <label>Permanent Address</label>
                                <input type="text" name="permanent_address" value="{{ $user->permanent_address ?? '' }}"
                                    class="form-control mt-1">
                            </div>
                        </div>

                        <h3 class="mt-5">Other Personal Information</h3>

                        <div class="row">
                            <div class="col-lg-4 mt-4">
                                <label>Sex</label>
                                <input type="text" name="sex" id="" class="form-control mt-1">
                            </div>

                            <div class="col-lg-4 mt-4">
                                <label>Civil Status</label>
                                <input type="text" name="civil_status" id="" class="form-control mt-1">
                            </div>

                            <div class="col-lg-4 mt-4">
                                <label>Citizenship</label>
                                <input type="text" name="citizenship" id="" class="form-control mt-1">
                            </div>

                            <div class="col-lg-4 mt-4">
                                <label>Height</label>
                                <input type="text" name="height" id="" class="form-control mt-1">
                            </div>

                            <div class="col-lg-4 mt-4">
                                <label>Weight</label>
                                <input type="text" name="weight" id="" class="form-control mt-1">
                            </div>

                            <div class="col-lg-4 mt-4">
                                <label>Blood Type</label>
                                <input type="text" name="blood_type" id="" class="form-control mt-1">
                            </div>

                            <div class="col-lg-4 mt-4">
                                <label>Name of TSC</label>
                                <input type="text" name="tsc_name" id="" class="form-control mt-1">
                            </div>

                            <div class="col-lg-4 mt-4">
                                <label>Other Skills/Line of Expertise</label>
                                <input type="text" name="skills" id="" class="form-control mt-1">
                            </div>
                        </div>

                        <h3 class="mt-5">Family Background</h3>

                        <div class="row">
                            <div class="col-lg-4 mt-4">
                                <label>Spouse Name</label>
                                <input type="text" name="spouse_name" id="" class="form-control mt-1">
                            </div>

                            <div class="col-lg-4 mt-4">
                                <label>Date of Birth</label>
                                <input type="text" name="spouse_name" id="" class="form-control mt-1">
                            </div>

                            <div class="col-lg-4 mt-4">
                                <label>Place of Birth</label>
                                <input type="text" name="spouse_name" id="" class="form-control mt-1">
                            </div>

                            <div class="col-lg-4 mt-4">
                                <label>Son</label>
                                <input type="text" name="number_son" id="" class="form-control mt-1">
                            </div>

                            <div class="col-lg-4 mt-4">
                                <label>Daughter</label>
                                <input type="text" name="number_daughter" id="" class="form-control mt-1">
                            </div>

                            <div class="col-lg-4 mt-4">
                                <label>Other Specification</label>
                                <input type="text" name="number_daughter" id="" class="form-control mt-1">
                            </div>
                        </div>

                        <h3 class="mt-5">Vehicles Information</h3>

                        <div class="row">
                            <div class="col-lg-4 mt-4">
                                {{-- <label>UV's</label> --}}
                                <div class="row">
                                    <div class="col-lg-9" style="padding: 0 0 0 12px">
                                        <label>UV's</label>
                                        <input type="text" name="uv_plate_no" id="" class="form-control mt-1">
                                        <div class="reminder">
                                            <span>Enter plate number</span>
                                        </div>
                                    </div>
                                    <div class="col-lg-3">
                                        <label>#</label>
                                        <input type="text" name="total_uv" id="" class="form-control mt-1">
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-4 mt-4">
                                <div class="row">
                                    <div class="col-lg-9" style="padding: 0 0 0 12px">
                                        <label>TAXI</label>
                                        <input type="text" name="taxi_plate_no" id="" class="form-control mt-1">
                                        <div class="reminder">
                                            <span>Enter plate number</span>
                                        </div>
                                    </div>
                                    <div class="col-lg-3">
                                        <label>#</label>
                                        <input type="text" name="total_taxi" id="" class="form-control mt-1">
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-4 mt-4">
                                <div class="row">
                                    <div class="col-lg-9" style="padding: 0 0 0 12px">
                                        <label>BUS</label>
                                        <input type="text" name="bus_plate_no" id="" class="form-control mt-1">
                                        <div class="reminder">
                                            <span>Enter plate number</span>
                                        </div>
                                    </div>
                                    <div class="col-lg-3">
                                        <label>#</label>
                                        <input type="text" name="total_bus" id="" class="form-control mt-1">
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-4 mt-4">
                                <div class="row">
                                    <div class="col-lg-9" style="padding: 0 0 0 12px">
                                        <label>MINI BUS</label>
                                        <input type="text" name="mini_bus_plate_no" id="" class="form-control mt-1">
                                        <div class="reminder">
                                            <span>Enter plate number</span>
                                        </div>
                                    </div>
                                    <div class="col-lg-3">
                                        <label>#</label>
                                        <input type="text" name="total_mini_bus" id="" class="form-control mt-1">
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-4 mt-4">
                                <div class="row">
                                    <div class="col-lg-9" style="padding: 0 0 0 12px">
                                        <label>JEEP</label>
                                        <input type="text" name="jeep_plate_no" id="" class="form-control mt-1">
                                        <div class="reminder">
                                            <span>Enter plate number</span>
                                        </div>
                                    </div>
                                    <div class="col-lg-3">
                                        <label>#</label>
                                        <input type="text" name="total_jeep" id="" class="form-control mt-1">
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-4 mt-4">
                                <div class="row">
                                    <div class="col-lg-9" style="padding: 0 0 0 12px">
                                        <label>MULTI-CAB</label>
                                        <input type="text" name="multi_cab_plate_no" id="" class="form-control mt-1">
                                        <div class="reminder">
                                            <span>Enter plate number</span>
                                        </div>
                                    </div>
                                    <div class="col-lg-3">
                                        <label>#</label>
                                        <input type="text" name="total_multi_cab" id="" class="form-control mt-1">
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-4 mt-4">
                                <div class="row">
                                    <div class="col-lg-9" style="padding: 0 0 0 12px">
                                        <label>TRICYCLE</label>
                                        <input type="text" name="tricycle_plate_no" id="" class="form-control mt-1">
                                        <div class="reminder">
                                            <span>Enter plate number</span>
                                        </div>
                                    </div>
                                    <div class="col-lg-3">
                                        <label>#</label>
                                        <input type="text" name="total_tricycle" id="" class="form-control mt-1">
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-4 mt-4">
                                <label>Other Specify</label>
                                <input type="text" name="other_info_specify" id="" class="form-control mt-1">
                            </div>
                        </div>

                        <h3 class="mt-5">Government Information</h3>

                        <div class="row">
                            <div class="col-lg-4 mt-4">
                                <label>SSS ID</label>
                                <div class="card-box mt-2">
                                    <input type="file" name="sss_id" id="" class="form-control mt-1"
                                        style="font-size: 14px">
                                    <span>No file selected</span>
                                </div>
                            </div>

                            <div class="col-lg-4 mt-4">
                                <label>Philhealth ID</label>
                                <div class="card-box mt-2">
                                    <input type="file" name="philhealth_id" id="" class="form-control mt-1"
                                        style="font-size: 14px">
                                    <span>No file selected</span>
                                </div>
                            </div>

                            <div class="col-lg-4 mt-4">
                                <label>Pagibig ID</label>
                                <div class="card-box mt-2">
                                    <input type="file" name="pagibig_id" id="" class="form-control mt-1"
                                        style="font-size: 14px">
                                    <span>No file selected</span>
                                </div>
                            </div>

                            <div class="col-lg-4 mt-4">
                                <label>Driver License ID</label>
                                <div class="card-box mt-2">
                                    <input type="file" name="driver_license_id" id="" class="form-control mt-1"
                                        style="font-size: 14px">
                                    <span>No file selected</span>
                                </div>
                            </div>

                            <div class="col-lg-4 mt-4">
                                <label>Tin ID</label>
                                <div class="card-box mt-2">
                                    <input type="file" name="tin_id" id="" class="form-control mt-1"
                                        style="font-size: 14px">
                                    <span>No file selected</span>
                                </div>
                            </div>
                        </div>

                        <h3 class="mt-5">Educational Background</h3>

                        <div class="row">

                            <div class="col-lg-4">
                                <label class="mt-4">ELEMENTARY</label>
                                <div class="d-flex gap-4">
                                    <div class="mt-2">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="question4"
                                                id="radioDefault5">
                                            <label class="form-check-label" for="radioDefault5">
                                                Graduated
                                            </label>
                                        </div>
                                    </div>

                                    <div class="mt-2">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="question4"
                                                id="radioDefault6">
                                            <label class="form-check-label" for="radioDefault6">
                                                Undergraduate
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-12 mt-2">
                                        <input type="text" name="" id="" class="form-control"
                                            placeholder="if not grad pls speficy">
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-4">
                                <label class="mt-4">SECONDARY</label>
                                <div class="d-flex gap-4">
                                    <div class="mt-2">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="question4"
                                                id="radioDefault5">
                                            <label class="form-check-label" for="radioDefault5">
                                                Graduated
                                            </label>
                                        </div>
                                    </div>

                                    <div class="mt-2">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="question4"
                                                id="radioDefault6">
                                            <label class="form-check-label" for="radioDefault6">
                                                Undergraduate
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-12 mt-2">
                                        <input type="text" name="" id="" class="form-control"
                                            placeholder="if not grad pls speficy">
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-4">
                                <label class="mt-4">VOCATIONAL/TRADE COURSE</label>
                                <div class="d-flex gap-4">
                                    <div class="mt-2">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="question4"
                                                id="radioDefault5">
                                            <label class="form-check-label" for="radioDefault5">
                                                Graduated
                                            </label>
                                        </div>
                                    </div>

                                    <div class="mt-2">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="question4"
                                                id="radioDefault6">
                                            <label class="form-check-label" for="radioDefault6">
                                                Undergraduate
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-12 mt-2">
                                        <input type="text" name="" id="" class="form-control"
                                            placeholder="if not grad pls speficy">
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-4">
                                <label class="mt-4">COLLEGE</label>
                                <div class="d-flex gap-4">
                                    <div class="mt-2">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="question4"
                                                id="radioDefault5">
                                            <label class="form-check-label" for="radioDefault5">
                                                Graduated
                                            </label>
                                        </div>
                                    </div>

                                    <div class="mt-2">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="question4"
                                                id="radioDefault6">
                                            <label class="form-check-label" for="radioDefault6">
                                                Undergraduate
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-12 mt-2">
                                        <input type="text" name="" id="" class="form-control"
                                            placeholder="if not grad pls speficy">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-step">
                        <h3 class="mt-3">Seminars/Training Information</h3>

                        <div class="row">
                            <div class="col-lg-6 mt-4">
                                <label>Title of Seminar/Conference/Workshop/Short Courses Attended
                                </label>
                                <input type="text" name="title_seminar" id="" class="form-control mt-1">
                            </div>

                            <div class="col-lg-6 mt-4">
                                <label>Inclusive Dates of Attendance (From / To)
                                </label>
                                <div class="row">
                                    <div class="col-lg-6">
                                        <input type="date" name="date_of_attendance" id="" class="form-control mt-1">
                                    </div>

                                    <div class="col-lg-6">
                                        <input type="date" name="date_of_attendance" id="" class="form-control mt-1">
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-6 mt-4">
                                <label>Conducted/Sponsored By (Write in full)
                                </label>
                                <input type="text" name="date_of_attendance" id="" class="form-control mt-1">
                            </div>
                        </div>

                        <h3 class="mt-5">Work/Employee History</h3>

                        <div class="row">
                            <div class="col-lg-4 mt-4">
                                <label>Name of Office/Company/Cooperative
                                </label>
                                <input type="text" name="name_office" id="" class="form-control mt-1">
                            </div>

                            <div class="col-lg-4 mt-4">
                                <label>Position Title
                                </label>
                                <input type="text" name="position_title" id="" class="form-control mt-1">
                            </div>

                            <div class="col-lg-4 mt-4">
                                <label>Monthly Salary
                                </label>
                                <input type="number" name="monthly_salary" id="" class="form-control mt-1">
                            </div>

                            <div class="col-lg-6 mt-4">
                                <label>Inclusive Dates (From/To)
                                </label>
                                <div class="row">
                                    <div class="col-lg-6">
                                        <input type="date" name="from" id="" class="form-control mt-1">
                                    </div>
                                    <div class="col-lg-6">
                                        <input type="date" name="from" id="" class="form-control mt-1">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <h3 class="mt-5">TC Membership Information</h3>

                        <div class="row">
                            <div class="col-lg-6 mt-4">
                                <label>Date of Membership with Transport Cooperative (TC)</label>
                                <input type="date" name="date_of_membership" id="" class="form-control mt-1">
                            </div>

                            <div class="col-lg-6 mt-4">
                                <label>Date of CETOS</label>
                                <input type="date" name="date_of_cetos" id="" class="form-control mt-1">
                            </div>

                            <div class="col-lg-4 mt-4">
                                <label>Membership Category</label>
                                <input type="text" name="membership_category" id="" class="form-control mt-1">
                            </div>

                            <div class="col-lg-4 mt-4">
                                <label>TC Member I.D. No.</label>
                                <input type="text" name="tc_member_no" id="" class="form-control mt-1">
                            </div>

                            <div class="col-lg-4 mt-4">
                                <label>No. of Units Owned</label>
                                <input type="text" name="no_units_owned" id="" class="form-control mt-1">
                            </div>

                            <div class="col-lg-6 mt-4">
                                <label>Type/Mode of Unit</label>
                                <input type="text" name="type_of_unit" id="" class="form-control mt-1">
                            </div>

                            <div class="col-lg-6 mt-4">
                                <label>Paid-Up Capital</label>
                                <div class="row">
                                    <div class="col-lg-6">
                                        <input type="date" name="as_of_date" id="" class="form-control mt-1">
                                    </div>

                                    <div class="col-lg-6">
                                        <input type="number" name="paid_price" id="" class="form-control mt-1">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <h3 class="mt-5">TC Membership History</h3>

                        <div class="row">
                            <div class="col-lg-6 mt-4">
                                <label>Inclusive Dates</label>
                                <div class="row">
                                    <div class="col-lg-6">
                                        <input type="date" name="history_from" id="" class="form-control mt-1">
                                    </div>

                                    <div class="col-lg-6">
                                        <input type="date" name="history_to" id="" class="form-control mt-1">
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-6 mt-4">
                                <label>Membership Category</label>
                                <input type="text" name="membership_category_history" id="" class="form-control mt-1">
                            </div>

                            <div class="col-lg-12 mt-4">
                                <label>TC Held Positions</label>

                                <div class="row">
                                    <div class="col-lg-6 mt-4">
                                        <label>Inclusive Dates</label>
                                        <div class="row">
                                            <div class="col-lg-6">
                                                <input type="date" name="from_date" id="" class="form-control mt-1">
                                            </div>

                                            <div class="col-lg-6">
                                                <input type="date" name="to_date" id="" class="form-control mt-1">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-6 mt-4">
                                        <label>Position Held</label>
                                        <input type="text" name="position_held" id="" class="form-control mt-1">
                                    </div>

                                    <div class="col-lg-6 mt-4">
                                        <label>Monthly Salary/Allowance</label>
                                        <input type="number" name="monthly_salary_allowance" id=""
                                            class="form-control mt-1">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <h3 class="mt-5">Special Awards/Recognition</h3>

                        <div class="row">
                            <div class="col-lg-6 mt-4">
                                <label>Title of Award/s</label>
                                <input type="text" name="title_of_awards" id="" class="form-control mt-1">
                            </div>

                            <div class="col-lg-6 mt-4">
                                <label>Awarded By</label>
                                <input type="text" name="awarded_by" id="" class="form-control mt-1">
                            </div>

                            <div class="col-lg-6 mt-4">
                                <label>Membership in Other Association/Organization/Cooperative (Write in full)</label>
                                <input type="text" name="membership_other_association" id="" class="form-control mt-1">
                            </div>
                        </div>
                    </div>

                    <div class="actions">
                        <button type="button" class="btn-prev" onclick="prevStep()">
                            <i class="fa fa-chevron-left"></i>
                            <span>Previous</span>
                        </button>
                        <button type="button" class="btn-next" onclick="nextStep()">
                            <span>Next page</span>
                            <i class="fa fa-chevron-right"></i>
                        </button>
                        <button type="submit" class="btn-submit" style="display:none;">Submit</button>
                    </div>
                </div>
            </div>
        </form>
    </div>

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
</body>

</html>