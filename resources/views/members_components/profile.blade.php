<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Profile</title>

    {{-- AOS animation link css --}}
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

    {{-- css link --}}
    <link rel="stylesheet" href="css_folder/profile.css">
    <link rel="stylesheet" href="css_folder/loading.css">

    {{-- bootstrap and tailwind link --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- font awesome cdn link --}}
    <link rel="stylesheet" href="font-awesome-icon/css/all.min.css">

    <style>
        .modal-body::-webkit-scrollbar {
            display: none;
        }

        .modal-body {
            scrollbar-width: none;
            -ms-overflow-style: none;
        }
    </style>
</head>

<body>

    <div class="container-fluid p-0 m-0">
        @include("components.sidebar")

        <div class="rightbar">
            @include("components.navbar2")
            <div class="main-parent">
                <div class="main-header">
                    <div>
                        <h3>My Profile</h3>
                        <p>Your Personal, membership and account information</p>
                    </div>
                    <!-- <div>
                        <button>
                            <i class="fa fa-pencil"></i>
                            Edit Profile
                        </button>
                    </div> -->
                </div>

                <div class="main-personal-card">
                    <div class="personal-header">
                        <div class="header-parent">
                            <div class="header-icon">
                                <i class="fa fa-user"></i>
                            </div>
                            <div class="header-text">
                                <h5>KPMPCATS</h5>
                                <p>Cooperative Membership</p>
                            </div>
                        </div>
                        <div class="active">
                            Active Member
                        </div>
                    </div>

                    <div class="personal-body">
                        <div class="personal-sub-body">
                            <div class="body-icon">
                                {{ strtoupper(substr(Auth::user()->first_name, 0, 1)) }}
                            </div>
                            <div class="body-text">
                                <h2>{{ $user->first_name }}
                                    {{ $user->middle_name ? $user->middle_name . ' ' : '' }}{{ $user->last_name }}
                                </h2>
                                <p>{{ $user->role }} · {{ $otherinfo->present_address ?? 'N/A' }}</p>
                            </div>
                        </div>
                        <div class="since-sub-parent">
                            <div class="since-parent">
                                <div class="member member-no">
                                    <span>Member No</span>
                                    <strong>#48291</strong>
                                </div>
                                <div class="member member-since">
                                    <span>Member Since</span>
                                    <strong>{{ $memberSince }}</strong>
                                </div>
                                <div class="member member-id">
                                    <span>Tax ID</span>
                                    <strong>••• •• 7742</strong>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="perforation"></div>
                </div>

                <div class="parent-information">
                    <div class="personal-sub-information">
                        <div class="personal-information-1">
                            <div class="personal-information-header">
                                <div class="header-text">
                                    <i class="fa fa-user"></i>
                                    <h4>Personal Information</h4>
                                </div>
                                <div>
                                    <a href="#">
                                        <i class="fa fa-pencil"></i>
                                        Edit
                                    </a>
                                </div>
                            </div>
                            <div class="personal-information-body">
                                <div class="information">
                                    <span>Full name</span>
                                    <strong>{{ $user->first_name }}
                                        {{ $user->middle_name ? $user->middle_name . ' ' : '' }}{{ $user->last_name }}</strong>
                                </div>

                                <div class="information">
                                    <span>Date of Birth</span>
                                    <strong>{{ $otherinfo->date_of_birth ?? 'N/A' }}</strong>
                                </div>

                                <div class="information">
                                    <span>Sex</span>
                                    <strong>{{ $otherinfo->sex ?? 'N/A' }}</strong>
                                </div>

                                <div class="information">
                                    <span>Civil Status</span>
                                    <strong>{{ $otherinfo->civil_status ?? 'N/A' }}</strong>
                                </div>

                                <div class="information">
                                    <span>Mobile Number</span>
                                    <strong>{{ $otherinfo->contact_no ?? 'N/A' }}</strong>
                                </div>

                                <div class="information">
                                    <span>Email</span>
                                    <strong>{{ $email }}</strong>
                                </div>

                                <div class="information">
                                    <span>Present Address</span>
                                    <strong>{{ $otherinfo->present_address ?? 'N/A' }}</strong>
                                </div>

                                <div class="information">
                                    <span>Permanent Address</span>
                                    <strong>{{ $otherinfo->permanent_address ?? 'N/A' }}</strong>
                                </div>

                                <div class="information">
                                    <span>Weight</span>
                                    <strong>{{ $otherinfo->weight ?? 'N/A' }}</strong>
                                </div>

                                <div class="information">
                                    <span>Height</span>
                                    <strong>{{ $otherinfo->height ?? 'N/A' }}</strong>
                                </div>

                                <div class="information">
                                    <span>Blood Type</span>
                                    <strong>{{ $otherinfo->blood_type ?? 'N/A' }}</strong>
                                </div>

                                <div class="information">
                                    <span>Citizenship</span>
                                    <strong>{{ $otherinfo->citizenship ?? 'N/A' }}</strong>
                                </div>
                            </div>
                            <!-- <div class="more-button">
                                <button>
                                    View More
                                    <i class="fa fa-arrow-right"></i>
                                </button>
                            </div> -->
                        </div>

                        <div class="personal-information-1">
                            <div class="personal-information-header">
                                <div class="header-text">
                                    <i class="fa fa-briefcase"></i>
                                    <h4>Employment & Membership</h4>
                                </div>
                                <div>
                                    <a href="#">
                                        <i class="fa fa-pencil"></i>
                                        Edit
                                    </a>
                                </div>
                            </div>
                            <div class="personal-information-body">
                                <div class="information">
                                    <span>Role</span>
                                    <strong>{{ $user->role ?? 'N/A' }}</strong>
                                </div>

                                <div class="information">
                                    <span>Monthly Income</span>
                                    <strong>{{ $otherinfo->height ?? 'N/A' }}</strong>
                                </div>

                                <div class="information">
                                    <span>Member Category</span>
                                    <strong>{{ $otherinfo->membership_category ?? 'N/A' }}</strong>
                                </div>

                                <div class="information">
                                    <span>Status</span>
                                    <strong>{{ $otherinfo->blood_type ?? 'N/A' }}</strong>
                                </div>

                                <div class="information">
                                    <span>Employer</span>
                                    <strong>{{ $otherinfo->citizenship ?? 'N/A' }}</strong>
                                </div>

                                <div class="information">
                                    <span>Standing</span>
                                    <strong>{{ $otherinfo->citizenship ?? 'N/A' }}</strong>
                                </div>
                            </div>
                        </div>

                        <div class="personal-information-1">
                            <div class="personal-information-header">
                                <div class="header-text">
                                    <i class="fa fa-folder-open"></i>
                                    <h4>Documents on File</h4>
                                </div>
                                <div>
                                    <a href="#">
                                        <i class="fa fa-upload"></i>
                                        Upload
                                    </a>
                                </div>
                            </div>
                            <div class="personal-information-body document-body">
                                <div class="doc-row">
                                    <i class="fa fa-file-lines doc-icon"></i>

                                    <div class="doc-name">SSS ID</div>

                                    <div class="doc-meta">Uploaded Mar 2, 2026</div>
                                </div>

                                <div class="doc-row">
                                    <i class="fa fa-file-lines doc-icon"></i>

                                    <div class="doc-name">Philhealth ID</div>

                                    <div class="doc-meta">Uploaded Mar 2, 2026</div>
                                </div>

                                <div class="doc-row">
                                    <i class="fa fa-file-lines doc-icon"></i>

                                    <div class="doc-name">Pag Ibig ID</div>

                                    <div class="doc-meta">Uploaded Mar 2, 2026</div>
                                </div>

                                <div class="doc-row">
                                    <i class="fa fa-file-lines doc-icon"></i>

                                    <div class="doc-name">Tin ID</div>

                                    <div class="doc-meta">Uploaded Mar 2, 2026</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="personal-sub-information">
                        <div class="personal-information-2">
                            <div class="personal-information-header">
                                <div class="header-text">
                                    <i class="fa fa-wallet"></i>
                                    <h4>Account Balance</h4>
                                </div>
                                <div>
                                    <a href="#">
                                        <i class="fa fa-pencil"></i>
                                        Edit
                                    </a>
                                </div>
                            </div>
                            <div class="personal-information-body-2">
                                <!-- <div class="personal-head">
                                    <p>Description</p>
                                    <p>Balance</p>
                                </div> -->
                                <div class="personal-parent">
                                    <div class="item item-share">
                                        <div class="stat-icon">
                                            <i class="fa fa-layer-group"></i>
                                        </div>
                                        <div class="fw-bold item-value item-value-share">
                                            <span>Share Capital Account</span>

                                            <strong>₱42,300.00</strong>
                                        </div>
                                        <div class="stat-delta">+4.2%</div>
                                    </div>

                                    <div class="item item-savings">
                                        <div class="stat-icon">
                                            <i class="fa fa-piggy-bank"></i>
                                        </div>
                                        <div class="fw-bold item-value item-category-savings">
                                            <span>Savings Account</span>

                                            <strong>₱18,750.00</strong>
                                        </div>
                                        <div class="stat-delta">+4.2%</div>
                                    </div>

                                    <div class="item item-loan">
                                        <div class="stat-icon">
                                            <i class="fa fa-hand-holding-dollar"></i>
                                        </div>
                                        <div class="fw-bold item-value item-category-loan">
                                            <span>Loan Balance</span>

                                            <strong>₱9,120.00</strong>
                                        </div>
                                        <div class="stat-delta">+4.2%</div>
                                    </div>

                                    <div class="item item-net">
                                        <!-- <div class="stat-icon">
                                            <i class="fa fa-layer-group"></i>
                                        </div> -->
                                        <div class="fw-bold item-value item-category-net">
                                            <span>Net Standing</span>

                                            <!-- <strong>₱51,930.00</strong> -->
                                        </div>
                                        <div class="stat-delta">₱51,930.00</div>
                                    </div>
                                </div>
                                <!-- <div class="personal-footer">
                                    <div class="item item-net"> 
                                        <div class="fw-bold item-category-net">
                                            <span>Net Standing</span>

                                            <strong>₱51,930.00</strong>
                                        </div>
                                        <div class="fw-bold item-value-net">₱51,930.00</div>
                                    </div>
                                </div> -->
                            </div>
                        </div>

                        <div class="personal-information-2">
                            <div class="personal-information-header">
                                <div class="header-text">
                                    <i class="fa fa-chart-simple"></i>
                                    <h4>Loan Repayment Progress</h4>
                                </div>
                                <div>
                                    <a href="#">
                                        <i class="fa fa-eye"></i>
                                        View
                                    </a>
                                </div>
                            </div>
                            <div class="personal-information-body-2">

                                <div class="parent-sub-progress">
                                    <div class="progress-repay progress-personal">
                                        <div class="progress-header">
                                            <strong>Personal Loan</strong>
                                            <span>₱18,000.00</span>
                                        </div>
                                        <div class="progress-body">
                                            <div class="parent-progress">
                                                <div class="progress"></div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="progress-repay progress-business">
                                        <div class="progress-header">
                                            <strong>Business Loan</strong>
                                            <span>₱18,000.00</span>
                                        </div>
                                        <div class="progress-body">
                                            <div class="parent-progress">
                                                <div class="progress"></div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="progress-repay progress-personal">
                                        <div class="progress-header">
                                            <strong>Emergency Loan</strong>
                                            <span>₱18,000.00</span>
                                        </div>
                                        <div class="progress-body">
                                            <div class="parent-progress">
                                                <div class="progress"></div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="progress-repay progress-personal">
                                        <div class="progress-header">
                                            <strong>Education Loan</strong>
                                            <span>₱18,000.00</span>
                                        </div>
                                        <div class="progress-body">
                                            <div class="parent-progress">
                                                <div class="progress"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Edit Profile Modal --}}
    <div class="modal fade" id="editProfileModal" tabindex="-1" aria-labelledby="editProfileModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg"
            style="height: 100vh; max-height: 100vh; margin: auto; display: flex; align-items: center; justify-content: center;">
            <div class="modal-content" style="max-height: 90vh; width: 100%;">
                <div class="modal-header">
                    <h5 class="modal-title" id="editProfileModalLabel"><i class="fa fa-edit"></i> Edit Profile</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" style="overflow-y: auto; max-height: 78vh;">
                    <form action="{{ route('UpdateProfileMember') }}" method="POST">
                        @csrf
                        <h6 class="mb-3" style="color: #1e40af; border-bottom: 2px solid #3b82f6; padding-bottom: 5px;">
                            Personal Information</h6>
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label">First Name</label>
                                <input type="text" name="first_name" class="form-control"
                                    value="{{ $user->first_name ?? '' }}">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Middle Name</label>
                                <input type="text" name="middle_name" class="form-control"
                                    value="{{ $user->middle_name ?? '' }}">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Last Name</label>
                                <input type="text" name="last_name" class="form-control"
                                    value="{{ $user->last_name ?? '' }}">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Contact Number</label>
                                <input type="text" name="contact_no" class="form-control"
                                    value="{{ $otherinfo->contact_no ?? '' }}">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Date of Birth</label>
                                <input type="date" name="date_of_birth" class="form-control"
                                    value="{{ $otherinfo->date_of_birth ?? '' }}">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Sex</label>
                                <select name="sex" class="form-control">
                                    <option value="">Select</option>
                                    <option value="Male" {{ ($otherinfo->sex ?? '') == 'Male' ? 'selected' : '' }}>Male
                                    </option>
                                    <option value="Female" {{ ($otherinfo->sex ?? '') == 'Female' ? 'selected' : '' }}>
                                        Female</option>
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Civil Status</label>
                                <select name="civil_status" class="form-control">
                                    <option value="">Select</option>
                                    <option value="Single" {{ ($otherinfo->civil_status ?? '') == 'Single' ? 'selected' : '' }}>Single</option>
                                    <option value="Married" {{ ($otherinfo->civil_status ?? '') == 'Married' ? 'selected' : '' }}>Married</option>
                                    <option value="Widowed" {{ ($otherinfo->civil_status ?? '') == 'Widowed' ? 'selected' : '' }}>Widowed</option>
                                    <option value="Divorced" {{ ($otherinfo->civil_status ?? '') == 'Divorced' ? 'selected' : '' }}>Divorced</option>
                                </select>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Citizenship</label>
                                <select name="citizenship" class="form-control">
                                    <option value="">Select</option>
                                    <option value="Filipino" {{ ($otherinfo->citizenship ?? '') == 'Filipino' ? 'selected' : '' }}>Filipino</option>
                                    <option value="American" {{ ($otherinfo->citizenship ?? '') == 'American' ? 'selected' : '' }}>American</option>
                                    <option value="Chinese" {{ ($otherinfo->citizenship ?? '') == 'Chinese' ? 'selected' : '' }}>Chinese</option>
                                    <option value="Japanese" {{ ($otherinfo->citizenship ?? '') == 'Japanese' ? 'selected' : '' }}>Japanese</option>
                                    <option value="Korean" {{ ($otherinfo->citizenship ?? '') == 'Korean' ? 'selected' : '' }}>Korean</option>
                                    <option value="Indian" {{ ($otherinfo->citizenship ?? '') == 'Indian' ? 'selected' : '' }}>Indian</option>
                                    <option value="Other" {{ ($otherinfo->citizenship ?? '') == 'Other' ? 'selected' : '' }}>Other</option>
                                </select>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Blood Type</label>
                                <input type="text" name="blood_type" class="form-control"
                                    value="{{ $otherinfo->blood_type ?? '' }}">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Height</label>
                                <input type="text" name="height" class="form-control"
                                    value="{{ $otherinfo->height ?? '' }}">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Weight</label>
                                <input type="text" name="weight" class="form-control"
                                    value="{{ $otherinfo->weight ?? '' }}">
                            </div>
                        </div>

                        <h6 class="mb-3 mt-4"
                            style="color: #1e40af; border-bottom: 2px solid #3b82f6; padding-bottom: 5px;">Address
                            Information</h6>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Present Address</label>
                                <textarea name="present_address" class="form-control"
                                    rows="3">{{ $otherinfo->present_address ?? '' }}</textarea>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Permanent Address</label>
                                <textarea name="permanent_address" class="form-control"
                                    rows="3">{{ $otherinfo->permanent_address ?? '' }}</textarea>
                            </div>
                        </div>

                        <h6 class="mb-3 mt-4"
                            style="color: #1e40af; border-bottom: 2px solid #3b82f6; padding-bottom: 5px;">Government
                            IDs</h6>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">SSS ID</label>
                                <input type="file" name="sss_id" class="form-control" accept="image/*">
                                @if(!empty($membergovernIds->sss_id))<small class="text-muted">Current:
                                {{ $membergovernIds->sss_id }}</small>@endif
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">PhilHealth ID</label>
                                <input type="file" name="philhealth_id" class="form-control" accept="image/*">
                                @if(!empty($membergovernIds->philhealth_id))<small class="text-muted">Current:
                                {{ $membergovernIds->philhealth_id }}</small>@endif
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Pag-IBIG ID</label>
                                <input type="file" name="pagibig_id" class="form-control" accept="image/*">
                                @if(!empty($membergovernIds->pagibig_id))<small class="text-muted">Current:
                                {{ $membergovernIds->pagibig_id }}</small>@endif
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">TIN ID</label>
                                <input type="file" name="tin_id" class="form-control" accept="image/*">
                                @if(!empty($membergovernIds->tin_id))<small class="text-muted">Current:
                                {{ $membergovernIds->tin_id }}</small>@endif
                            </div>
                        </div>

                        <h6 class="mb-3 mt-4"
                            style="color: #1e40af; border-bottom: 2px solid #3b82f6; padding-bottom: 5px;">Family
                            Background</h6>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Spouse Name</label>
                                <input type="text" name="spouse_name" class="form-control"
                                    value="{{ $family->spouse_name ?? '' }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Spouse Birthdate</label>
                                <input type="date" name="spouse_date_birth" class="form-control"
                                    value="{{ $family->spouse_date_birth ?? '' }}">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Number of Sons</label>
                                <input type="number" name="number_son" class="form-control"
                                    value="{{ $family->number_son ?? '' }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Number of Daughters</label>
                                <input type="number" name="number_daughter" class="form-control"
                                    value="{{ $family->number_daughter ?? '' }}">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">
                                <i class="fa fa-save"></i> Save Changes
                            </button>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- AOS animation link js --}}
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>

    <script>
        AOS.init();
    </script>
</body>

</html>