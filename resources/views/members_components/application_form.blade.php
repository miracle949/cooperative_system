<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Application Form</title>
    <link rel="icon" href="images/websitelogo.png" type="image/png">
    <link rel="stylesheet" href="{{ asset('css_folder/application-form.css') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="{{ asset('font-awesome-icon/css/all.min.css') }}">
</head>

<body>
    <div class="container-fluid">
        <nav>
            <div class="nav-logo">
                <img src="../images/logo2.png" alt="">
                <div class="nav-text">
                    <h2 class="fw-bold">Membership Application Form</h2>

                    <p>Ready to become part of something special? We can't wait to welcome
                        you.
                </div>
            </div>
        </nav>

        <div class="form-container-body">
            <form id="form" action="{{ route('applicationFormButton', $user->id) }}" method="post" enctype="multipart/form-data">
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
                        <div class="body-card">
                            <div class="form-step active">

                            <div class="card-share-header">
                                <div class="card-logo">
                                    <i class="fa fa-file-lines"></i>
                                </div>
                                <p>KMPCATS</p>
                            </div>

                            <div class="header-card">
                                <h2>Membership Application Form</h2>

                                <p>Your membership application has been approved! You can now proceed to complete your information.</p>
                            </div>

                                {{-- PERSONAL INFORMATION --}}
                                <h3 class="mt-4">Personal Information</h3>
                                <div class="form-body">
                                    <div class="row">
                                        <div class="col-lg-4 mt-4">
                                            <label>Firstname</label>
                                            <input type="text" name="first_name" value="{{ $user->first_name ?? '' }}" class="form-control mt-1">
                                        </div>
                                        <div class="col-lg-4 mt-4">
                                            <label>Middle Initial</label>
                                            <input type="text" name="middle_name" value="{{ $user->middle_name ?? '' }}" class="form-control mt-1">
                                        </div>
                                        <div class="col-lg-4 mt-4">
                                            <label>Lastname</label>
                                            <input type="text" name="last_name" value="{{ $user->last_name ?? '' }}" class="form-control mt-1">
                                        </div>
                                        <div class="col-lg-4 mt-4">
                                            <label>Email</label>
                                            <input type="email" name="email" value="{{ $user->email ?? '' }}" class="form-control mt-1">
                                        </div>
                                        <div class="col-lg-4 mt-4">
                                            <label>Date of Birth</label>
                                            <input type="text" name="date_of_birth" value="{{ $user->date_of_birth ?? '' }}" class="form-control mt-1">
                                        </div>
                                        <div class="col-lg-4 mt-4">
                                            <label>Place of Birth</label>
                                            <input type="text" name="place_of_birth" value="{{ $user->place_of_birth ?? '' }}" class="form-control mt-1">
                                        </div>
                                        <div class="col-lg-4 mt-4">
                                            <label>Contact No</label>
                                            <input type="text" name="contact_no" value="{{ $user->contact_no ?? '' }}" class="form-control mt-1">
                                        </div>
                                        <div class="col-lg-4 mt-4">
                                            <label>Present Address</label>
                                            <input type="text" name="present_address" value="{{ $user->present_address ?? '' }}" class="form-control mt-1">
                                        </div>
                                        <div class="col-lg-4 mt-4">
                                            <label>Permanent Address</label>
                                            <input type="text" name="permanent_address" value="{{ $user->permanent_address ?? '' }}" class="form-control mt-1">
                                        </div>
                                    </div>
                                </div>

                                {{-- OTHER PERSONAL INFORMATION --}}
                                <h3 class="mt-5">Other Personal Information</h3>
                                <div class="form-body">
                                    <div class="row">
                                        <div class="col-lg-4 mt-4">
                                            <label>Sex</label>
                                            <input type="text" name="sex" value="{{ $user->sex ?? '' }}" class="form-control mt-1">
                                        </div>
                                        <div class="col-lg-4 mt-4">
                                            <label>Civil Status</label>
                                            <input type="text" name="civil_status" value="{{ $user->civil_status ?? '' }}" class="form-control mt-1">
                                        </div>
                                        <div class="col-lg-4 mt-4">
                                            <label>Citizenship</label>
                                            <input type="text" name="citizenship" value="{{ $user->citizenship ?? '' }}" class="form-control mt-1">
                                        </div>
                                        <div class="col-lg-4 mt-4">
                                            <label>Height</label>
                                            <input type="text" name="height" value="{{ $user->height ?? '' }}" class="form-control mt-1">
                                        </div>
                                        <div class="col-lg-4 mt-4">
                                            <label>Weight</label>
                                            <input type="text" name="weight" value="{{ $user->weight ?? '' }}" class="form-control mt-1">
                                        </div>
                                        <div class="col-lg-4 mt-4">
                                            <label>Blood Type</label>
                                            <input type="text" name="blood_type" value="{{ $user->blood_type ?? '' }}" class="form-control mt-1">
                                        </div>
                                        <div class="col-lg-4 mt-4">
                                            <label>Name of TSC</label>
                                            <input type="text" name="tsc_name" value="{{ $user->tsc_name ?? '' }}" class="form-control mt-1">
                                        </div>
                                        <div class="col-lg-4 mt-4">
                                            <label>Other Skills/Line of Expertise</label>
                                            <input type="text" name="skills" value="{{ $user->skills ?? '' }}" class="form-control mt-1">
                                        </div>
                                    </div>
                                </div>

                                {{-- FAMILY BACKGROUND --}}
                                <h3 class="mt-5">Family Background</h3>
                                <div class="form-body">
                                    <div class="row">
                                        <div class="col-lg-4 mt-4">
                                            <label>Spouse Name</label>
                                            <input type="text" name="spouse_name" value="{{ $spouse->spouse_name ?? '' }}" class="form-control mt-1">
                                        </div>
                                        <div class="col-lg-4 mt-4">
                                            <label>Date of Birth</label>
                                            <input type="date" name="spouse_date_birth" value="{{ $spouse->spouse_date_birth ?? '' }}" class="form-control mt-1">
                                        </div>
                                        <div class="col-lg-4 mt-4">
                                            <label>Place of Birth</label>
                                            <input type="text" name="spouse_place_birth" value="{{ $spouse->spouse_place_birth ?? '' }}" class="form-control mt-1">
                                        </div>
                                        <div class="col-lg-4 mt-4">
                                            <label>Son</label>
                                            <input type="number" name="number_son" value="{{ $user->number_son ?? '' }}" class="form-control mt-1">
                                        </div>
                                        <div class="col-lg-4 mt-4">
                                            <label>Daughter</label>
                                            <input type="number" name="number_daughter" value="{{ $user->number_daughter ?? '' }}" class="form-control mt-1">
                                        </div>
                                        <div class="col-lg-4 mt-4">
                                            <label>Other Specification</label>
                                            <input type="text" name="other_spec" value="{{ $user->other_spec ?? '' }}" class="form-control mt-1">
                                        </div>
                                    </div>
                                </div>

                                {{-- VEHICLES INFORMATION --}}
                                <h3 class="mt-5">Vehicles Information</h3>
                                <div class="form-body">
                                    <div class="vehicles-grid mt-5" id="vehiclesGrid"></div>
                                </div>

                                {{-- GOVERNMENT INFORMATION --}}
                                <h3 class="mt-5">Government Information</h3>
                                <div class="form-body">
                                    <div class="row">
                                        <div class="col-lg-6 mt-4">
                                            <label>SSS ID</label>
                                            <div class="card-box mt-2">
                                                <input type="file" name="sss_id" class="form-control mt-1" style="font-size: 14px">
                                                <span>No file selected</span>
                                            </div>
                                            @if(!empty($governmentIds->sss_id))
                                                <small class="mt-1 d-block">
                                                    Current: <a href="{{ asset('storage/' . $governmentIds->sss_id) }}" target="_blank">View file</a>
                                                </small>
                                            @endif
                                        </div>
                                        <div class="col-lg-6 mt-4">
                                            <label>Philhealth ID</label>
                                            <div class="card-box mt-2">
                                                <input type="file" name="philhealth_id" class="form-control mt-1" style="font-size: 14px">
                                                <span>No file selected</span>
                                            </div>
                                            @if(!empty($governmentIds->philhealth_id))
                                                <small class="mt-1 d-block">
                                                    Current: <a href="{{ asset('storage/' . $governmentIds->philhealth_id) }}" target="_blank">View file</a>
                                                </small>
                                            @endif
                                        </div>
                                        <div class="col-lg-6 mt-4">
                                            <label>Pagibig ID</label>
                                            <div class="card-box mt-2">
                                                <input type="file" name="pagibig_id" class="form-control mt-1" style="font-size: 14px">
                                                <span>No file selected</span>
                                            </div>
                                            @if(!empty($governmentIds->pagibig_id))
                                                <small class="mt-1 d-block">
                                                    Current: <a href="{{ asset('storage/' . $governmentIds->pagibig_id) }}" target="_blank">View file</a>
                                                </small>
                                            @endif
                                        </div>
                                        <div class="col-lg-6 mt-4">
                                            <label>Tin ID</label>
                                            <div class="card-box mt-2">
                                                <input type="file" name="tin_id" class="form-control mt-1" style="font-size: 14px">
                                                <span>No file selected</span>
                                            </div>
                                            @if(!empty($governmentIds->tin_id))
                                                <small class="mt-1 d-block">
                                                    Current: <a href="{{ asset('storage/' . $governmentIds->tin_id) }}" target="_blank">View file</a>
                                                </small>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                {{-- EDUCATIONAL BACKGROUND --}}
                                <h3 class="mt-5">Educational Background</h3>
                                <div class="form-body">
                                    <div class="row">
                                        @php
                                            $eduLevels = ['Elementary', 'Secondary', 'Vocational/Trade Course', 'College'];
                                            $eduData   = $education ?? collect();
                                        @endphp

                                        @foreach($eduLevels as $index => $level)
                                        <div class="col-lg-6">
                                            <label class="mt-4">{{ strtoupper($level) }}</label>
                                            <input type="hidden" name="educational_level[]" value="{{ $level }}">
                                            @php $eduRecord = $eduData->firstWhere('educational_level', $level); @endphp
                                            <div class="d-flex gap-4">
                                                <div class="mt-2">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio"
                                                            name="edu_status[{{ $index }}]"
                                                            value="Graduated"
                                                            {{ ($eduRecord->status ?? '') == 'Graduated' ? 'checked' : '' }}>
                                                        <label class="form-check-label">Graduated</label>
                                                    </div>
                                                </div>
                                                <div class="mt-2">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio"
                                                            name="edu_status[{{ $index }}]"
                                                            value="Undergraduate"
                                                            {{ ($eduRecord->status ?? '') == 'Undergraduate' ? 'checked' : '' }}>
                                                        <label class="form-check-label">Undergraduate</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-lg-12 mt-2">
                                                    <input type="text" name="edu_specify[{{ $index }}]"
                                                        value="{{ $eduRecord->specify ?? '' }}"
                                                        class="form-control" placeholder="if not grad pls specify">
                                                </div>
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                            <div class="form-step">

                                {{-- SEMINARS --}}
                                <h3 class="mt-3">Seminars/Training Information</h3>
                                <div class="form-body">
                                    <div class="row">
                                        <div class="col-lg-6 mt-4">
                                            <label>Title of Seminar</label>
                                            <input type="text" name="title_seminar"
                                                value="{{ $seminar->title_seminar ?? '' }}"
                                                class="form-control mt-1">
                                        </div>
                                        <div class="col-lg-6 mt-4">
                                            <label>Inclusive Dates of Attend (From / To)</label>
                                            <div class="row">
                                                <div class="col-lg-6">
                                                    <input type="date" name="attendance_from"
                                                        value="{{ $seminar->attendance_from ?? '' }}"
                                                        class="form-control mt-1">
                                                </div>
                                                <div class="col-lg-6">
                                                    <input type="date" name="attendance_to"
                                                        value="{{ $seminar->attendance_to ?? '' }}"
                                                        class="form-control mt-1">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 mt-4">
                                            <label>Conducted/Sponsored By (Write in full)</label>
                                            <input type="text" name="sponsored_by"
                                                value="{{ $seminar->sponsored_by ?? '' }}"
                                                class="form-control mt-1">
                                        </div>
                                    </div>
                                </div>

                                {{-- EMPLOYEE HISTORY --}}
                                <h3 class="mt-5">Work/Employee History</h3>
                                <div class="form-body">
                                    <div class="row">
                                        <div class="col-lg-4 mt-4">
                                            <label>Name of Cooperative</label>
                                            <input type="text" name="name_office"
                                                value="{{ $employeeHistory->name_office ?? '' }}"
                                                class="form-control mt-1">
                                        </div>
                                        <div class="col-lg-4 mt-4">
                                            <label>Position Title</label>
                                            <input type="text" name="position_title"
                                                value="{{ $employeeHistory->position_title ?? '' }}"
                                                class="form-control mt-1">
                                        </div>
                                        <div class="col-lg-4 mt-4">
                                            <label>Monthly Salary</label>
                                            <input type="number" name="monthly_salary"
                                                value="{{ $employeeHistory->monthly_salary ?? '' }}"
                                                class="form-control mt-1">
                                        </div>
                                        <div class="col-lg-6 mt-4">
                                            <label>Inclusive Dates (From/To)</label>
                                            <div class="row">
                                                <div class="col-lg-6">
                                                    <input type="date" name="employee_inclusive_from"
                                                        value="{{ $employeeHistory->employee_inclusive_from ?? '' }}"
                                                        class="form-control mt-1">
                                                </div>
                                                <div class="col-lg-6">
                                                    <input type="date" name="employee_inclusive_to"
                                                        value="{{ $employeeHistory->employee_inclusive_to ?? '' }}"
                                                        class="form-control mt-1">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- TC MEMBERSHIP INFORMATION --}}
                                <h3 class="mt-5">TC Membership Information</h3>
                                <div class="form-body">
                                    <div class="row">
                                        <div class="col-lg-6 mt-4">
                                            <label>Membership with Transport Cooperative</label>
                                            <input type="date" name="date_of_membership"
                                                value="{{ $membershipInfo->date_of_membership ?? '' }}"
                                                class="form-control mt-1">
                                        </div>
                                        <div class="col-lg-6 mt-4">
                                            <label>Date of CETOS</label>
                                            <input type="date" name="date_of_cetos"
                                                value="{{ $membershipInfo->date_of_cetos ?? '' }}"
                                                class="form-control mt-1">
                                        </div>
                                        <div class="col-lg-4 mt-4">
                                            <label>Membership Category</label>
                                            <input type="text" name="membership_category"
                                                value="{{ $membershipInfo->membership_category ?? '' }}"
                                                class="form-control mt-1">
                                        </div>
                                        <div class="col-lg-4 mt-4">
                                            <label>TC Member I.D. No.</label>
                                            <input type="text" name="tc_member_id_no"
                                                value="{{ $membershipInfo->tc_member_id_no ?? '' }}"
                                                class="form-control mt-1">
                                        </div>
                                        <div class="col-lg-4 mt-4">
                                            <label>No. of Units Owned</label>
                                            <input type="text" name="no_units_owned"
                                                value="{{ $membershipInfo->no_units_owned ?? '' }}"
                                                class="form-control mt-1">
                                        </div>
                                        <div class="col-lg-6 mt-4">
                                            <label>Type/Mode of Unit</label>
                                            <input type="text" name="type_mode_unit"
                                                value="{{ $membershipInfo->type_mode_unit ?? '' }}"
                                                class="form-control mt-1">
                                        </div>
                                        <div class="col-lg-6 mt-4">
                                            <label>Paid-Up Capital</label>
                                            <div class="row">
                                                <div class="col-lg-6">
                                                    <input type="text" name="paid_up_capital"
                                                        value="{{ $membershipInfo->paid_up_capital ?? '' }}"
                                                        class="form-control mt-1" placeholder="Capital amount">
                                                </div>
                                                <div class="col-lg-6">
                                                    <input type="number" name="paid_up_price"
                                                        value="{{ $membershipInfo->paid_up_price ?? '' }}"
                                                        class="form-control mt-1" placeholder="Price">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- TC MEMBERSHIP HISTORY --}}
                                <h3 class="mt-5">TC Membership History</h3>
                                <div class="form-body">
                                    <div class="row">
                                        <div class="col-lg-6 mt-4">
                                            <label>Inclusive Dates</label>
                                            <div class="row">
                                                <div class="col-lg-6">
                                                    <input type="date" name="members_inclusive_dates_from"
                                                        value="{{ $membershipHistory->members_inclusive_dates_from ?? '' }}"
                                                        class="form-control mt-1">
                                                </div>
                                                <div class="col-lg-6">
                                                    <input type="date" name="members_inclusive_dates_to"
                                                        value="{{ $membershipHistory->members_inclusive_dates_to ?? '' }}"
                                                        class="form-control mt-1">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 mt-4">
                                            <label>Membership Category</label>
                                            <input type="text" name="membership_category_history"
                                                value="{{ $membershipHistory->membership_category ?? '' }}"
                                                class="form-control mt-1">
                                        </div>
                                        <div class="col-lg-12 mt-4">
                                            <label>TC Held Positions</label>
                                            <div class="row">
                                                <div class="col-lg-6 mt-4">
                                                    <label>Inclusive Dates</label>
                                                    <div class="row">
                                                        <div class="col-lg-6">
                                                            <input type="date" name="tc_held_inclusive_dates_from"
                                                                value="{{ $membershipHistory->tc_held_inclusive_dates_from ?? '' }}"
                                                                class="form-control mt-1">
                                                        </div>
                                                        <div class="col-lg-6">
                                                            <input type="date" name="tc_held_inclusive_dates_to"
                                                                value="{{ $membershipHistory->tc_held_inclusive_dates_to ?? '' }}"
                                                                class="form-control mt-1">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 mt-4">
                                                    <label>Position Held</label>
                                                    <input type="text" name="position_held"
                                                        value="{{ $membershipHistory->position_held ?? '' }}"
                                                        class="form-control mt-1">
                                                </div>
                                                <div class="col-lg-6 mt-4">
                                                    <label>Monthly Salary/Allowance</label>
                                                    <input type="number" name="monthly_salary_allowance"
                                                        value="{{ $membershipHistory->monthly_salary_allowance ?? '' }}"
                                                        class="form-control mt-1">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- SPECIAL AWARDS --}}
                                <h3 class="mt-5">Special Awards/Recognition</h3>
                                <div class="form-body">
                                    <div class="row">
                                        <div class="col-lg-6 mt-4">
                                            <label>Title of Award/s</label>
                                            <input type="text" name="title_awards"
                                                value="{{ $specialAwards->title_awards ?? '' }}"
                                                class="form-control mt-1">
                                        </div>
                                        <div class="col-lg-6 mt-4">
                                            <label>Awarded By</label>
                                            <input type="text" name="awarded_by"
                                                value="{{ $specialAwards->awarded_by ?? '' }}"
                                                class="form-control mt-1">
                                        </div>
                                        <div class="col-lg-6 mt-4">
                                            <label>Membership in Other Association/Organization/Cooperative (Write in full)</label>
                                            <input type="text" name="membership_other_association"
                                                value="{{ $specialAwards->membership_other_association ?? '' }}"
                                                class="form-control mt-1">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- STEP 2 --}}
                        {{-- <div class="body-card">
                            
                        </div> --}}

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
    </div>

    @php
        $existingVehicles = [
            'UV'        => $vehicles->get('UV', collect())->pluck('plate_no')->toArray(),
            'TAXI'      => $vehicles->get('TAXI', collect())->pluck('plate_no')->toArray(),
            'BUS'       => $vehicles->get('BUS', collect())->pluck('plate_no')->toArray(),
            'MINI BUS'  => $vehicles->get('MINI BUS', collect())->pluck('plate_no')->toArray(),
            'JEEP'      => $vehicles->get('JEEP', collect())->pluck('plate_no')->toArray(),
            'MULTI-CAB' => $vehicles->get('MULTI-CAB', collect())->pluck('plate_no')->toArray(),
            'TRICYCLE'  => $vehicles->get('TRICYCLE', collect())->pluck('plate_no')->toArray(),
        ];
    @endphp

    <script>
        const EXISTING_VEHICLES = @json($existingVehicles);
    </script>

    <script src="{{ asset('js_folder/vehi_application.js') }}"></script>


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
            const requiredFields = currentFormStep.querySelectorAll("input[required], select[required]");
            for (let field of requiredFields) {
                if (!field.checkValidity()) {
                    field.reportValidity();
                    return;
                }
            }
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