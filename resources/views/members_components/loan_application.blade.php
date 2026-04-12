<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link rel="icon" href="images/websitelogo.png" type="image/png">

    {{-- AOS animation link css --}}
    {{--
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet"> --}}

    {{-- css link --}}
    <link rel="stylesheet" href="css_folder/loan_application.css">
    <link rel="stylesheet" href="css_folder/loading.css">

    {{-- bootstrap and tailwind link --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- font awesome cdn link --}}
    <link rel="stylesheet" href="../font-awesome-icon/css/all.min.css">

    <script>
        function closeSuccessModal() {
            document.getElementById('success-modal').style.display = 'none';
        }
    </script>

</head>

<body>

    <div class="container-fluid m-0 p-0">
        @include("components.navbar2")
        @include("components.offcanvas")

        <!-- Interest Rates Modal -->
        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <div class="modal-text">
                            <h1>Interest Rates</h1>
                            <p>Per lending type - monthly basis</p>
                        </div>
                        <button type="button" data-bs-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <div class="reminders">
                            <p>💡 Rates are indicative. Final terms subject to credit evaluation and cooperative
                                approval.</p>
                        </div>

                        <div class="lending-parent-box">
                            <div class="lending-parent">
                                <div class="lending-icon">
                                    <p>Personal Lending</p>
                                    <span>General personal expenses & needs</span>
                                </div>
                                <p>2% / mo</p>
                            </div>

                            <div class="lending-parent">
                                <div class="lending-icon">
                                    <p>Business Lending</p>
                                    <span>Livelihood & enterprise capital</span>
                                </div>
                                <p>2% / mo</p>
                            </div>

                            <div class="lending-parent">
                                <div class="lending-icon">
                                    <p>Emergency Lending</p>
                                    <span>Urgent medical, calamity & crisis needs</span>
                                </div>
                                <p>2% / mo</p>
                            </div>

                            <div class="lending-parent">
                                <div class="lending-icon">
                                    <p>Educational Lending</p>
                                    <span>Tuition, school fees & supplies</span>
                                </div>
                                <p>2% / mo</p>
                            </div>

                            <div class="lending-parent">
                                <div class="lending-icon">
                                    <p>Car Lending</p>
                                    <span>Tuition, school fees & supplies</span>
                                </div>
                                <p>2% / mo</p>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="modal-footer">
                        <button type="button" class="btn" data-bs-dismiss="modal">Got it, Close</button>
                    </div>
                </div>
            </div>
        </div>

        <main>
            {{-- Share Capital Requirement Alert --}}
            {{-- @if(session('loan_blocked'))
            <div class="loan-blocked-alert">
                <div class="alert-icon">
                    <i class="fa fa-triangle-exclamation"></i>
                </div>
                <div class="alert-content">
                    <h5>Cannot Submit Application</h5>
                    <p>{{ session('loan_blocked') }}</p>
                    <a href="{{ route('ShareCapitalMember') }}">
                        <i class="fa fa-coins"></i> Add Share Capital
                    </a>
                </div>
            </div>
            @endif --}}
            {{-- @if(session('loan_blocked'))
            <div class="loan-blocked-alert">
                <div class="alert-icon">
                    <i class="fa fa-triangle-exclamation"></i>
                </div>
                <div class="alert-content">
                    <h5>Cannot Submit Application</h5>
                    <p>{{ session('loan_blocked') }}</p>
                    <a href="{{ route('ShareCapitalMember') }}">
                        <i class="fa fa-coins"></i> Add Share Capital
                    </a>
                </div>
            </div>
            @endif --}}

            @if(!$canApplyLoan)
                <div class="sc-parent">
                    <div class="sc-requirement-alert">
                        <div class="alert-icon-wrap">
                            <i class="fa fa-ban"></i>
                        </div>
                        <div class="alert-body">
                            <h5>Share Capital Requirement</h5>
                            <p>
                                You need at least <strong>10 shares</strong> of Share Capital before you can apply for a
                                loan.
                                You currently have <strong>{{ $currentShares }} share(s)</strong> —
                                you need <strong>{{ 10 - $currentShares }} more</strong> to be eligible.
                            </p>
                        </div>
                    </div>
                </div>
            @endif

            <div class="card-parent-box">
                <div class="card-box">
                    <div class="d-flex justify-content-left align-items-center gap-4">
                        <div class="tw:w-[55px] tw:h-[55px] card-icon" style="border-radius: 10px">
                            <i class="fa-solid fa-file"></i>
                        </div>
                        <div class="d-flex justify-content-between align-items-center w-100">
                            <div class="card-header">
                                <h3>Lending Applications</h3>
                                <p style="margin: 0px;" class="tw:text-[#808080]">Fill out the form below to apply for a
                                    lending
                                </p>
                            </div>

                            <div class="alert-reminder">
                                {{-- <button style="button" data-bs-toggle="modal" data-bs-target="#exampleModal"><i
                                        class="fa-solid fa-triangle-exclamation"></i></button> --}}
                                <button style="button" data-bs-toggle="modal" data-bs-target="#exampleModal"><i
                                        class="fa-solid fa-question-circle"></i></button>
                            </div>
                        </div>
                    </div>


                    <form action="{{ route("lendingProgram") }}" method="post"
                        class="{{ !$canApplyLoan ? 'form-disabled' : '' }}" enctype="multipart/form-data" {{ !$canApplyLoan ? 'onsubmit=return false' : '' }}>
                        @csrf
                        <div class="row form-parent">
                            <div class="col-lg-6 col-md-12 mt-4">
                                <label>Lending Type *</label>
                                <select name="lending_type" class="form-select mt-2" onchange="updateTermOptions()"
                                    id="lending_type" required>
                                    <option value="">Select lending type</option>
                                    <option value="Personal Lending">Personal Lending</option>
                                    <option value="Emergency Lending">Emergency Lending</option>
                                    <option value="Business Lending">Business Lending</option>
                                    {{-- <option value="Car Lending">Car Lending</option> --}}
                                    <option value="Education Lending">Education Lending</option>
                                </select>
                            </div>

                            <div class="col-lg-6 col-md-12 mt-md-4 mt-sm-4 loan-input">
                                <label style="display:flex; align-items:center; gap:8px;">
                                    Lending Amount (₱) *
                                    <span style="
        background: #fff3cd; border: 1px solid #ffe082;
        color: #856404; font-size: 11px; font-weight: 600;
        padding: 2px 8px; border-radius: 20px; white-space: nowrap;
    ">Max: ₱{{ number_format($remainingLoanable, 2) }}</span>
                                </label>

                                <input type="number" name="lending_amount" id="lending_amount_input"
                                    placeholder="Enter amount (max ₱{{ number_format($remainingLoanable, 2) }})"
                                    class="form-control mt-2" min="1" max="{{ $remainingLoanable }}" {{ $hasFullyLoaned ? 'disabled' : '' }} oninput="recalculate(); checkLoanLimit(this);"
                                    onkeydown="if(event.key==='e'||event.key==='E'||event.key==='+'||event.key==='-') event.preventDefault();"
                                    required>

                                <div id="loan-limit-warning" style="
    display:none; margin-top:6px;
    background:#fef2f2; border:1.5px solid #fca5a5;
    border-radius:8px; padding:6px 10px;
    font-size:12px; color:#dc2626; font-weight:500;
">
                                    <i class="fa fa-circle-exclamation" style="margin-right:5px;"></i>
                                    You can only borrow up to
                                    <strong>₱{{ number_format($remainingLoanable, 2) }}</strong> more.
                                </div>
                            </div>

                            <div class="col-lg-6 col-md-12 mt-4">
                                <label>Lending Term *</label>
                                <select name="lending_type_term_nonbusiness" id="lending_type_term_nonbusiness" class="form-select mt-2" 
                                    onchange="recalculate()">
                                    <option value="">Select lending term</option>
                                    <option value="6 months">6 months</option>
                                </select>
                                <select name="lending_type_term_business" id="lending_type_term_business" class="form-select mt-2" 
                                    style="display:none;" onchange="recalculate()">
                                    <option value="">Select lending term</option>
                                    <option value="6 months">6 months</option>
                                    <option value="12 months">12 months</option>
                                </select>
                                <input type="hidden" name="lending_type_term" id="lending_type_term">
                            </div>

                            <div class="col-lg-6 col-md-12 mt-4">
                                <label>Enter Monthly Income (₱) *</label>
                                <input type="number" name="monthly_income" placeholder="Enter monthly income"
                                    class="form-control mt-2"
                                    onkeydown="if(event.key === 'e' || event.key === 'E' || event.key === '+' || event.key === '-') event.preventDefault();"
                                    required>
                            </div>

                            <div class="col-12 mt-5">
                                <label>Purpose of Lending *</label>
                                <textarea name="purpose_loan" class="form-control mt-2 p-3 tw:w-[100%]"
                                    placeholder="Describe the purpose of your lending..." style="font-size: 15.5px;"
                                    required></textarea>
                            </div>

                            <div id="docs-wrapper" style="display: none">

                                <div class="row" style="padding: 0 0 0 12px;">
                                    <div class="col-lg-12" style="padding: 0 0 0 12px">
                                        <div class="line"></div>
                                    </div>
                                </div>

                                <h4 class="docs-heading">Supporting Documents</h4>

                                <!-- Personal Lending -->
                                <div id="docs-personal" style="display:none;">
                                    <div class="upload-grid">

                                        <div class="upload-card" id="card-vid-personal">
                                            <div class="upload-icon">

                                                <svg class="upload-arrow" viewBox="0 0 24 24">
                                                    <path d="M12 16v-8M8 12l4-4 4 4" />
                                                    <rect x="3" y="3" width="18" height="18" rx="3" />
                                                </svg>
                                                <svg class="upload-check" viewBox="0 0 24 24" fill="none"
                                                    stroke-width="2">
                                                    <polyline points="20 6 9 17 4 12" />
                                                </svg>
                                            </div>
                                            <span class="upload-label">Valid ID</span>
                                            <span class="upload-sub">PDF, JPG or PNG · max 5MB</span>
                                            <span class="upload-badge badge-required">Required</span>
                                            <span class="upload-filename" id="name-vid-personal"></span>
                                            <input type="file" name="personal_valid_id" accept=".jpg,.jpeg,.png,.pdf"
                                                onchange="onFileSelected(this,'card-vid-personal','name-vid-personal')">
                                        </div>

                                        <div class="upload-card" id="card-poi-personal">
                                            <div class="upload-icon">
                                                <svg class="upload-arrow" viewBox="0 0 24 24">
                                                    <path d="M12 16v-8M8 12l4-4 4 4" />
                                                    <rect x="3" y="3" width="18" height="18" rx="3" />
                                                </svg>
                                                <svg class="upload-check" viewBox="0 0 24 24" fill="none"
                                                    stroke-width="2">
                                                    <polyline points="20 6 9 17 4 12" />
                                                </svg>
                                            </div>
                                            <span class="upload-label">Proof of Income</span>
                                            <span class="upload-sub">Payslip / COE · PDF or image</span>
                                            <span class="upload-badge badge-required">Required</span>
                                            <span class="upload-filename" id="name-poi-persona
         l                                      "></span>
                                            <input type="file" name="personal_proof_of_income"
                                                accept=".jpg,.jpeg,.png,.pdf"
                                                onchange="onFileSelected(this,'card-poi-personal','name-poi-personal')">
                                        </div>

                                    </div>
                                </div>

                                <!-- Emergency Lending -->
                                <div id="docs-emergency" style="display:none;">
                                    <div class="upload-grid">

                                        <div class="upload-card" id="card-vid-emergency">
                                            <div class="upload-icon">

                                                <svg class="upload-arrow" viewBox="0 0 24 24">
                                                    <path d="M12 16v-8M8 12l4-4 4 4" />
                                                    <rect x="3" y="3" width="18" height="18" rx="3" />
                                                </svg>
                                                <svg class="upload-check" viewBox="0 0 24 24" fill="none"
                                                    stroke-width="2">
                                                    <polyline points="20 6 9 17 4 12" />
                                                </svg>
                                            </div>
                                            <span class="upload-label">Valid ID</span>
                                            <span class="upload-sub">PDF, JPG or PNG · max 5MB</span>
                                            <span class="upload-badge badge-required">Required</span>
                                            <span class="upload-filename" id="name-vid-emergency"></span>
                                            <input type="file" name="emergency_valid_id" accept=".jpg,.jpeg,.png,.pdf"
                                                onchange="onFileSelected(this,'card-vid-emergency','name-vid-emergency')">
                                        </div>

                                        <div class="upload-card" id="card-poi-emergency">
                                            <div class="upload-icon">
                                                <svg class="upload-arrow" viewBox="0 0 24 24">
                                                    <path d="M12 16v-8M8 12l4-4 4 4" />
                                                    <rect x="3" y="3" width="18" height="18" rx="3" />
                                                </svg>
                                                <svg class="upload-check" viewBox="0 0 24 24" fill="none"
                                                    stroke-width="2">
                                                    <polyline points="20 6 9 17 4 12" />
                                                </svg>
                                            </div>
                                            <span class="upload-label">Proof of Income</span>
                                            <span class="upload-sub">PDF, JPG or PNG · max 5MB</span>
                                            <span class="upload-badge badge-required">Required< / span>
                                                    <span class="upload-filename" id="name-poi-emergency"></span>
                                                    <input type="file" name="emergency_proof_of_income"
                                                        accept=".jpg,.jpeg,.png,.pdf"
                                                        onchange="onFileSelected(this,'card-poi-emergency','name-poi-emergency')">
                                        </div>

                                        <div class="upload-card" id="card-poe-emergency">
                                            <div class="upload-icon">
                                                <svg class="upload-arrow" viewBox="0 0 24 24">
                                                    <path d="M12 16v-8M8 12l4-4 4 4" />
                                                    <rect x="3" y="3" width="18" height="18" rx="3" />
                                                </svg>
                                                <svg class="upload-check" viewBox="0 0 24 24" fill="none"
                                                    stroke-width="2">
                                                    <polyline points="20 6 9 17 4 12" />
                                                </svg>
                                            </div>
                                            <span class="upload-label">Proof of Emergency</span>
                                            <span class="upload-sub">Medical Cert, Hospital Bill, Police Report</span>
                                            <span class="upload-badge badge-required">Required</span>
                                            <span class="upload-filename" id="name-poe-emergency"></span>
                                            <input type="file" name="proof_of_emergency" accept=".jpg,.jpeg,.png,.pdf"
                                                onchange="onFileSelected(this,'card-poe-emergency','name-poe-emergency')">
                                        </div>
                                    </div>
                                </div>

                                <!-- Business Lending -->
                                <div id="docs-business" style="display:none;">
                                    <div class="upload-grid">
                                        <div class="upload-card" id="card-vid-business">
                                            <div class="upload-icon">

                                                <svg class="upload-arrow" viewBox="0 0 24 24">
                                                    <path d="M12 16v-8M8 12l4-4 4 4" />
                                                    <rect x="3" y="3" width="18" height="18" rx="3" />
                                                </svg>
                                                <svg class="upload-check" viewBox="0 0 24 24" fill="none"
                                                    stroke-width="2">
                                                    <polyline points="20 6 9 17 4 12" />
                                                </svg>
                                            </div>
                                            <span class="upload-label">Valid ID</span>
                                            <span class="upload-sub">PDF, JPG or PNG · max 5MB</span>
                                            <span class="upload-badge badge-required">Required</span>
                                            <span class="upload-filename" id="name-vid-business"></span>
                                            <input type="file" name="business_valid_id" accept=".jpg,.jpeg,.png,.pdf"
                                                onchange="onFileSelected(this,'card-vid-business','name-vid-business')">
                                        </div>

                                        <div class="upload-card" id="card-poi-business">
                                            <div class="upload-icon">
                                                <svg class="upload-arrow" viewBox="0 0 24 24">
                                                    <path d="M12 16v-8M8 12l4-4 4 4" />
                                                    <rect x="3" y="3" width="18" height="18" rx="3" />
                                                </svg>
                                                <svg class="upload-check" viewBox="0 0 24 24" fill="none"
                                                    stroke-width="2">
                                                    <polyline points="20 6 9 17 4 12" />
                                                </svg>
                                            </div>
                                            <span class="upload-label">Proof of Income</span>
                                            <span class="upload-sub">PDF, JPG or PNG · max 5MB
                                                < /span>
                                                    <span class="upload-badge badge-required">Required</span>
                                                    <span class="upload-filename" id="name-poi-business"></span>
                                                    <input type="file" name="business_proof_of_income"
                                                        accept=".jpg,.jpeg,.png,.pdf"
                                                        onchange="onFileSelected(this,'card-poi-business','name-poi-business')">
                                        </div>

                                        <div class="upload-card" id="card-bp-business">
                                            <div class="upload-icon">
                                                <svg class="upload-arrow" viewBox="0 0 24 24">
                                                    <path d="M12 16v-8M8 12l4-4 4 4" />
                                                    <rect x="3" y="3" width="18" height="18" rx="3" />
                                                </svg>
                                                <svg class="upload-check" viewBox="0 0 24 24" fill="none"
                                                    stroke-width="2">
                                                    <polyline points="20 6 9 17 4 12" />
                                                </svg>
                                            </div>
                                            <span class="upload-label">Business Permit / DTI Registration</span>
                                            <span class="upload-sub">PDF, JPG or PNG · max 5MB</span>
                                            <span class="upload-badge badge-required">Required</span>
                                            <span class="upload-filename" id="name-bp-business"></span>
                                            <input type="file" name="business_permit" accept=".jpg,.jpeg,.png,.pdf"
                                                onchange="onFileSelected(this,'card-bp-business','name-bp-business')">
                                        </div>

                                        <div class="upload-card" id="card-fs-business">
                                            <div class="upload-icon">
                                                <svg class="upload-arrow" viewBox="0 0 24 24">
                                                    <path d="M12 16v-8M8 12l4-4 4 4" />
                                                    <rect x="3" y="3" width="18" height="18" rx="3" />
                                                </svg>
                                                <svg class="upload-check" viewBox="0 0 24 24" fill="none"
                                                    stroke-width="2">
                                                    <polyline points="20 6 9 17 4 12" />
                                                </svg>
                                            </div>
                                            <span class="upload-label">Business Financial Statement</span>
                                            <span class="upload-sub">PDF, JPG or PNG · max 5MB</span>
                                            <span class="upload-badge badge-optional">Optional</span>
                                            <span class="upload-filename" id="name-fs-business"></span>
                                            <input type="file" name="financial_statement" accept=".jpg,.jpeg,.png,.pdf"
                                                onchange="onFileSelected(this,'card-fs-business','name-fs-business')">
                                        </div>

                                    </div>
                                </div>

                                <!-- Car Lending -->
                                {{-- <div id="docs-car" style="display:none;">
                                    <div class="upload-grid">

                                        <div class="upload-card" id="card-vid-car">
                                            <div class="upload-icon">
                                                <svg class="upload-arrow" viewBox="0 0 24 24">
                                                    <path d="M12 16v-8M8 12l4-4 4 4" />
                                                    <rect x="3" y="3" width="18" height="18" rx="3" />
                                                </svg>
                                                <svg class="upload-check" viewBox="0 0 24 24" fill="none"
                                                    stroke-width="2">
                                                    <polyline points="20 6 9 17 4 12" />
                                                </svg>
                                            </div>
                                            <span class="upload-label">Valid ID</span>
                                            <span class="upload-sub">PDF, JPG or PNG · max 5MB</span>
                                            <span class="upload-badge badge-required">Required</span>
                                            <span class="upload-filename" id="name-vid-car"></span>
                                            <input type="file" name="car_valid_id" accept=".jpg,.jpeg,.png,.pdf"
                                                onchange="onFileSelected(this,'card-vid-car','name-vid-car')">
                                        </div>

                                        <div class="upload-card" id="card-poi-car">
                                            <div class="upload-icon">
                                                <svg class="upload-arrow" viewBox="0 0 24 24">
                                                    <path d="M12 16v-8M8 12l4-4 4 4" />
                                                    <rect x="3" y="3" width="18" height="18" rx="3" />
                                                </svg>
                                                <svg class="upload-check" viewBox="0 0 24 24" fill="none"
                                                    stroke-width="2">
                                                    <polyline points="20 6 9 17 4 12" />
                                                </svg>
                                            </div>
                                            <span class="upload-label">Proof of Income</span>
                                            <span class="upload-sub">PDF, JPG or PNG · max 5MB</span>
                                            <span class="upload-badge badge-required">Required</span>
                                            <span class="upload-filename" id="name-poi-car"></span>
                                            <input type="file" name="car_proof_of_income" accept=".jpg,.jpeg,.png,.pdf"
                                                onchange="onFileSelected(this,'card-poi-car','name-poi-car')">
                                        </div>

                                        <div class="upload-card" id="card-vq-car">
                                            <div class="upload-icon">
                                                <svg class="upload-arrow" viewBox="0 0 24 24">
                                                    <path d="M12 16v-8M8 12l4-4 4 4" />
                                                    <rect x="3" y="3" width="18" height="18" rx="3" />
                                                </svg>
                                                <svg class="upload-check" viewBox="0 0 24 24" fill="none"
                                                    stroke-width="2">
                                                    <polyline points="20 6 9 17 4 12" />
                                                </svg>
                                            </div>
                                            <span class="upload-label">Quotation of Vehicle</span>
                                            <span class="upload-sub">From dealer · PDF or image</span>
                                            <span class="upload-badge badge-required">Required</span>
                                            <span class="upload-filename" id="name-vq-car"></span>
                                            <input type="file" name="vehicle_quotation" accept=".jpg,.jpeg,.png,.pdf"
                                                onchange="onFileSelected(this,'card-vq-car','name-vq-car')">
                                        </div>

                                        <div class="upload-card" id="card-dl-car">
                                            <div class="upload-icon">
                                                <svg class="upload-arrow" viewBox="0 0 24 24">
                                                    <path d="M12 16v-8M8 12l4-4 4 4" />
                                                    <rect x="3" y="3" width="18" height="18" rx="3" />
                                                </svg>
                                                <svg class="upload-check" viewBox="0 0 24 24" fill="none"
                                                    stroke-width="2">
                                                    <polyline points="20 6 9 17 4 12" />
                                                </svg>
                                            </div>
                                            <span class="upload-label">Driver's License</span>
                                            <span class="upload-sub">PDF, JPG or PNG · max 5MB</span>
                                            <span class="upload-badge badge-required">Required</span>
                                            <span class="upload-filename" id="name-dl-car"></span>
                                            <input type="file" name="drivers_license" accept=".jpg,.jpeg,.png,.pdf"
                                                onchange="onFileSelected(this,'card-dl-car','name-dl-car')">
                                        </div>

                                    </div>
                                </div> --}}

                                {{-- ── Loanable Amount Status Banner ── --}}
                                @if($hasFullyLoaned)
                                    {{-- Fully loaned out — block everything --}}
                                    <div style="
                                                    background: #fef2f2;
                                                    border: 1.5px solid #fca5a5;
                                                    border-radius: 12px;
                                                    padding: 1rem 1.25rem;
                                                    margin-top: 1.25rem;
                                                    display: flex;
                                                    align-items: flex-start;
                                                    gap: 12px;
                                                ">
                                        <div style="
                                                        width: 36px; height: 36px; flex-shrink: 0;
                                                        background: #fee2e2; border-radius: 50%;
                                                        display: flex; align-items: center; justify-content: center;
                                                    ">
                                            <i class="fa fa-ban" style="color:#dc2626; font-size:15px;"></i>
                                        </div>
                                        <div>
                                            <p style="margin:0 0 3px; font-size:13.5px; font-weight:700; color:#1a1a1a;">
                                                Loan Limit Reached
                                            </p>
                                            <p style="margin:0; font-size:12.5px; color:#dc2626; line-height:1.5;">
                                                You have an active loan totalling
                                                <strong>₱{{ number_format($totalActiveLoan, 2) }}</strong>
                                                — the maximum allowed is <strong>₱25,000.00</strong>.
                                                You must repay your existing loan before applying again.
                                            </p>
                                        </div>
                                    </div>

                                @elseif($totalActiveLoan > 0)
                                    {{-- Partially loaned — show remaining --}}
                                    <div style="
                                                    background: #fff8e1;
                                                    border: 1.5px solid #ffe082;
                                                    border-radius: 12px;
                                                    padding: 1rem 1.25rem;
                                                    margin-top: 1.25rem;
                                                    display: flex;
                                                    align-items: flex-start;
                                                    gap: 12px;
                                                ">
                                        <div style="
                                                        width: 36px; height: 36px; flex-shrink: 0;
                                                        background: #fff3cd; border-radius: 50%;
                                                        display: flex; align-items: center; justify-content: center;
                                                    ">
                                            <i class="fa fa-circle-info" style="color:#e6a817; font-size:15px;"></i>
                                        </div>
                                        <div style="width:100%;">
                                            <p style="margin:0 0 6px; font-size:13.5px; font-weight:700; color:#1a1a1a;">
                                                Remaining Loanable Amount
                                            </p>
                                            <p style="margin:0 0 10px; font-size:12.5px; color:#856404; line-height:1.5;">
                                                You currently have an active loan of
                                                <strong>₱{{ number_format($totalActiveLoan, 2) }}</strong>.
                                                You may still borrow up to:
                                            </p>
                                            {{-- Progress bar --}}
                                            <div
                                                style="background:#f5f5f5; border-radius:20px; height:10px; overflow:hidden; margin-bottom:6px;">
                                                <div style="
                                                                height:10px; border-radius:20px;
                                                                background: linear-gradient(90deg, #e6a817, #f59e0b);
                                                                width: {{ min(100, ($totalActiveLoan / 25000) * 100) }}%;
                                                                transition: width 0.4s;
                                                            "></div>
                                            </div>
                                            <div
                                                style="display:flex; justify-content:space-between; font-size:11.5px; color:#999; margin-bottom:8px;">
                                                <span>Used: ₱{{ number_format($totalActiveLoan, 2) }}</span>
                                                <span>Limit: ₱25,000.00</span>
                                            </div>
                                            <div style="
                                                            background: #fff;
                                                            border: 1.5px solid #ffe082;
                                                            border-radius: 8px;
                                                            padding: 8px 14px;
                                                            display: inline-flex;
                                                            align-items: center;
                                                            gap: 8px;
                                                            font-size: 14px;
                                                            font-weight: 700;
                                                            color: #1a4a3a;
                                                        ">
                                                <i class="fa fa-coins"></i>
                                                Available to borrow: ₱{{ number_format($remainingLoanable, 2) }}
                                            </div>
                                        </div>
                                    </div>

                                @else
                                    {{-- No active loans — show full limit --}}
                                    <div style="
                                                    background: #f0fdf4;
                                                    border: 1.5px solid #86efac;
                                                    border-radius: 10px;
                                                    padding: 0.75rem 1rem;
                                                    margin-top: 1.25rem;
                                                    display: flex;
                                                    align-items: flex-start;
                                                    gap: 10px;
                                                    font-size: 13px;
                                                    color: #166534;
                                                ">
                                        <i class="fa fa-circle-info" style="margin-top:2px; flex-shrink:0;"></i>
                                        <span>
                                            You may borrow up to <strong>₱25,000.00</strong>.
                                            Applications exceeding this limit will not be processed.
                                        </span>
                                    </div>
                                @endif

                                <!-- Education Lending -->
                                <div id="docs-education" style="display:none;">
                                    <div class="upload-grid">

                                        <div class="upload-card" id="card-sid-education">
                                            <div class="upload-icon">
                                                <svg class="upload-arrow" viewBox="0 0 24 24">
                                                    <path d="M12 16v-8M8 12l4-4 4 4" />
                                                    <rect x="3" y="3" width="18" height="18" rx="3" />
                                                </svg>
                                                <svg class="upload-check" viewBox="0 0 24 24" fill="none"
                                                    stroke-width="2">
                                                    <polyline points="20 6 9 17 4 12" />
                                                </svg>
                                            </div>
                                            <span class="upload-label">School ID</span>
                                            <span class="upload-sub">PDF, JPG or PNG · max 5MB</span>
                                            <span class="upload-badge badge-required">Required</span>
                                            <span class="upload-filename" id="name-sid-education"></span>
                                            <input type="file" name="school_id" accept=".jpg,.jpeg,.png,.pdf"
                                                onchange="onFileSelected(this,'card-sid-education','name-sid-education')">
                                        </div>

                                        <div class="upload-card" id="card-cor-education">
                                            <div class="upload-icon">
                                                <svg class="upload-arrow" viewBox="0 0 24 24">
                                                    <path d="M12 16v-8M8 12l4-4 4 4" />
                                                    <rect x="3" y="3" width="18" height="18" rx="3" />
                                                </svg>
                                                <svg class="upload-check" viewBox="0 0 24 24" fill="none"
                                                    stroke-width="2">
                                                    <polyline points="20 6 9 17 4 12" />
                                                </svg>
                                            </div>
                                            <span class="upload-label">COR (Certificate of Registration)</span>
                                            <span class="upload-sub">PDF, JPG or PNG · max 5MB</span>
                                            <span class="upload-badge badge-required">Required</span>
                                            <span class="upload-filename" id="name-cor-education"></span>
                                            <input type="file" name="cor" accept=".jpg,.jpeg,.png,.pdf"
                                                onchange="onFileSelected(this,'card-cor-education','name-cor-education')">
                                        </div>

                                        <div class="upload-card" id="card-cog-education">
                                            <div class="upload-icon">
                                                <svg class="upload-arrow" viewBox="0 0 24 24">
                                                    <path d="M12 16v-8M8 12l4-4 4 4" />
                                                    <rect x="3" y="3" width="18" height="18" rx="3" />
                                                </svg>
                                                <svg class="upload-check" viewBox="0 0 24 24" fill="none"
                                                    stroke-width="2">
                                                    <polyline points="20 6 9 17 4 12" />
                                                </svg>
                                            </div>
                                            <span class="upload-label">COG (Certificate of Grades)</span>
                                            <span class="upload-sub">PDF, JPG or PNG · max 5MB</span>
                                            <span class="upload-badge badge-required">Required</span>
                                            <span class="upload-filename" id="name-cog-education"></span>
                                            <input type="file" name="cog" accept=".jpg,.jpeg,.png,.pdf"
                                                onchange="onFileSelected(this,'card-cog-education','name-cog-education')">
                                        </div>

                                    </div>
                                </div>

                            </div>

                            <div class="row" style="padding: 0 0 0 12px;">
                                <div class="col-lg-12" style="padding: 0 0 0 12px">
                                    <div class="line"></div>
                                </div>
                            </div>

                            {{-- Hidden inputs INSIDE the form so calculated values are submitted --}}
                            <input type="hidden" name="monthly_payment" id="hidden-monthly">
                            <input type="hidden" name="total_payment" id="hidden-total">
                            <input type="hidden" name="total_interest" id="hidden-interest">

                            <div class="col-12 mt-5 mt-md-4">
                                <button type="submit" class="tw:w-[100%] btn">Submit Application</button>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="card-calculate-parent">
                    <div class="card-calculate">
                        <div class="calculate-header">
                            <h3>Lending Invoice</h3>
                            <p>Auto Computes as you fill the form</p>
                        </div>
                        <div class="calculate-body">
                            <div class="estimate">
                                <p>Estimated Monthly Payment</p>
                                <div class="payment">
                                    <h4 id="calc-monthly">₱-</h4>
                                </div>
                                <p id="calc-term-sub">Fill in amount & term to compute.</p>
                            </div>

                            <div class="parent-amount">
                                <div class="card-amount">
                                    <div class="text-amount">
                                        <p>Lending Type</p>
                                    </div>
                                    <div class="amount">
                                        <p id="calc-type">-</p>
                                    </div>
                                </div>

                                <div class="card-amount">
                                    <div class="text-amount">
                                        <p>Lending Amount</p>
                                    </div>
                                    <div class="amount">
                                        <p id="calc-amount">-</p>
                                    </div>
                                </div>

                                <div class="card-amount">
                                    <div class="text-amount">
                                        <p>Interest Rate (monthly)</p>
                                    </div>
                                    <div class="amount">
                                        <p id="calc-rate">-</p>
                                    </div>
                                </div>

                                <div class="card-amount">
                                    <div class="text-amount">
                                        <p>Lending Term</p>
                                    </div>
                                    <div class="amount">
                                        <p id="calc-term">-</p>
                                    </div>
                                </div>
                                <hr>
                                <div class="card-amount total-amount">
                                    <div class="text-amount">
                                        <p>Total Payment</p>
                                    </div>
                                    <div class="amount">
                                        <p id="calc-total">-</p>
                                    </div>
                                </div>

                                <div class="card-amount">
                                    <div class="text-amount">
                                        <p>Total Interest</p>
                                    </div>
                                    <div class="amount">
                                        <p id="calc-interest">-</p>
                                    </div>
                                </div>
                                <div class="reminders">
                                    <p>💡 Rates are indicative. Final terms subject to credit evaluation and cooperative
                                        approval.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>

        @if(session("ApplySuccess"))
            <div class="modal-overlay-success" id="success-modal">
                <div class="success-modal-box">
                    <div class="sm-head">
                        <div class="sm-icon">✅</div>
                    </div>
                    <div class="sm-body">
                        <h2>Application Submitted!</h2>
                        <p>Your lending application has been received and is now under review. We'll notify you within 3–5
                            business days.</p>
                        <div class="sm-details">
                            <div class="sm-row">
                                <span class="sm-label">Status</span>
                                <span class="sm-badge">Pending Review</span>
                            </div>
                            <div class="sm-row">
                                <span class="sm-label">Reference</span>
                                <span class="sm-val">#{{ session("ReferenceNo") }}</span>
                            </div>
                            <div class="sm-row">
                                <span class="sm-label">Date Filed</span>
                                <span class="sm-val">{{ session("DateFiled") }}</span>
                            </div>
                        </div>
                        <button class="sm-btn" onclick="closeSuccessModal()">Got it, Continue</button>
                    </div>
                </div>
            </div>
        @endif

    </div>

    {{-- AOS animation link js --}}
    {{--
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script> --}}

    {{--
    <script>
        AOS.init();
    </script> --}}

    <script>
        const MAX_REMAINING = {{ $remainingLoanable }};

        function checkLoanLimit(input) {
            const warning = document.getElementById('loan-limit-warning');
            const submitBtn = document.querySelector('button[type="submit"]');
            const val = parseFloat(input.value);

            if (val > MAX_REMAINING || val <= 0) {
                warning.style.display = 'block';
                input.style.borderColor = '#fca5a5';
                submitBtn.disabled = true;
                submitBtn.style.opacity = '0.5';
                submitBtn.style.cursor = 'not-allowed';
            } else {
                warning.style.display = 'none';
                input.style.borderColor = '';
                submitBtn.disabled = false;
                submitBtn.style.opacity = '';
                submitBtn.style.cursor = '';
            }
        }

        // Disable submit immediately on page load if fully loaned
        document.addEventListener('DOMContentLoaded', function () {
            @if($hasFullyLoaned)
                const submitBtn = document.querySelector('button[type="submit"]');
                if (submitBtn) {
                    submitBtn.disabled = true;
                    submitBtn.style.opacity = '0.5';
                    submitBtn.style.cursor = 'not-allowed';
                }
            @endif
});
    </script>

    <script>
        const lendingTypeSelect = document.getElementById('lending_type');

        const docsMap = {
            'Personal Lending': 'docs-personal',
            'Emergency Lending': 'docs-emergency',
            'Business Lending': 'docs-business',
            // 'Car Lending': 'docs-car',
            'Education Lending': 'docs-education',
        };

        const allDocIds = Object.values(docsMap);

        function showDocs(selectedType) {
            const wrapper = document.getElementById('docs-wrapper');
            // const allDocIds = ['docs-personal', 'docs-emergency', 'docs-business', 'docs-car', 'docs-education'];
            const allDocIds = ['docs-personal', 'docs-emergency', 'docs-business', 'docs-education'];

            // Hide all individual sections first
            allDocIds.forEach(function (id) {
                document.getElementById(id).style.display = 'none';
            });

            if (docsMap[selectedType]) {
                // Show the wrapper (heading + hr)
                wrapper.style.display = 'block';
                // Show only the matching doc section
                document.getElementById(docsMap[selectedType]).style.display = 'block';
            } else {
                // No type selected — hide everything
                wrapper.style.display = 'none';
            }
        }

        // Listen for change on the select
        lendingTypeSelect.addEventListener('change', function () {
            showDocs(this.value);
        });
    </script>

    <script>
        function onFileSelected(input, cardId, nameId) {
            const card = document.getElementById(cardId);
            const nameEl = document.getElementById(nameId);
            if (input.files && input.files[0]) {
                card.classList.add('has-file');
                if (nameEl) nameEl.textContent = input.files[0].name;
            } else {
                card.classList.remove('has-file');
                if (nameEl) nameEl.textContent = '';
            }
        }
    </script>

    <script>
        const RATES = @json($loanSettings);

        function updateTermOptions() {
            const type = document.querySelector('[name="lending_type"]').value;
            const termNonBusiness = document.getElementById('lending_type_term_nonbusiness');
            const termBusiness = document.getElementById('lending_type_term_business');
            const hiddenTerm = document.getElementById('lending_type_term');
            
            // Remove required from both first
            termNonBusiness.required = false;
            termBusiness.required = false;
            
            if (type === 'Business Lending') {
                termNonBusiness.style.display = 'none';
                termBusiness.style.display = 'block';
                termBusiness.required = true;
                if (termBusiness.options.length > 1) {
                    termBusiness.selectedIndex = 1;
                }
                hiddenTerm.value = termBusiness.value;
            } else if (type) {
                termNonBusiness.style.display = 'block';
                termBusiness.style.display = 'none';
                termNonBusiness.required = true;
                if (termNonBusiness.options.length > 1) {
                    termNonBusiness.selectedIndex = 1;
                }
                hiddenTerm.value = termNonBusiness.value;
            } else {
                termNonBusiness.style.display = 'block';
                termBusiness.style.display = 'none';
            }
            
            recalculate();
        }

        function recalculate() {
            const type = document.querySelector('[name="lending_type"]').value;
            const amount = parseFloat(document.querySelector('[name="lending_amount"]').value) || 0;
            const termNonBusiness = document.getElementById('lending_type_term_nonbusiness');
            const termBusiness = document.getElementById('lending_type_term_business');
            const hiddenTerm = document.getElementById('lending_type_term');
            
            // Get term from visible dropdown
            const term = termBusiness.style.display === 'block' ? termBusiness.value : termNonBusiness.value;
            hiddenTerm.value = term;
            
            const termMonths = term ? parseInt(term.split(' ')[0]) : 0;

            showDocs(type);

            document.getElementById('calc-type').textContent = type || '-';

            if (!type || !amount || !termMonths || amount <= 0) {
                document.getElementById('calc-monthly').textContent = '₱-';
                document.getElementById('calc-term-sub').textContent = 'Fill in amount & term to compute.';
                document.getElementById('calc-amount').textContent = amount > 0 ? '₱' + fmt(amount) : '-';
                document.getElementById('calc-rate').textContent = type && RATES[type] ? (RATES[type] * 100).toFixed(1) + '% / mo' : '-';
                document.getElementById('calc-term').textContent = term || '-';
                document.getElementById('calc-total').textContent = '-';
                document.getElementById('calc-interest').textContent = '-';
                return;
            }

            const r = RATES[type] ?? 0.015;

            const monthly = amount * r * Math.pow(1 + r, termMonths) / (Math.pow(1 + r, termMonths) - 1);
            const total = monthly * termMonths;
            const interest = total - amount;

            document.getElementById('calc-monthly').textContent = '₱' + fmt(monthly);
            document.getElementById('calc-term-sub').textContent = 'Over ' + term;
            document.getElementById('calc-amount').textContent = '₱' + fmt(amount);
            document.getElementById('calc-rate').textContent = (r * 100).toFixed(1) + '% / mo';
            document.getElementById('calc-term').textContent = term;
            document.getElementById('calc-total').textContent = '₱' + fmt(total);
            document.getElementById('calc-interest').textContent = '₱' + fmt(interest);

            document.getElementById('hidden-monthly').value = monthly.toFixed(2);
            document.getElementById('hidden-total').value = total.toFixed(2);
            document.getElementById('hidden-interest').value = interest.toFixed(2);
        }

        function fmt(n) {
            return n.toLocaleString('en-PH', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        }
    </script>

</body>

</html>