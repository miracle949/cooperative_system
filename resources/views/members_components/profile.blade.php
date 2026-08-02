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
        @include("components.navbar2", ["missingCount" => $missingCount])

        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show m-4" role="alert">
            <i class="fa fa-check-circle"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

        <main class="p-5">
            <div class="card-box-parent pt-5 ps-5 pe-5 pb-0">
                <div class="card-box">
                    <ul class="nav nav-pills d-flex justify-content-center align-items" id="pills-tab" role="tablist">
                        <li class="nav-item d-flex justify-content-center align-items-center" role="presentation">
                            <button class="nav-link active" id="pills-home-tab" data-bs-toggle="pill"
                                data-bs-target="#pills-home" type="button" role="tab" aria-controls="pills-home"
                                aria-selected="true">Personal Info</button>
                        </li>
                        <li class="nav-item d-flex justify-content-center align-items-center" role="presentation">
                            <button class="nav-link" id="pills-profile-tab" data-bs-toggle="pill"
                                data-bs-target="#pills-profile" type="button" role="tab" aria-controls="pills-profile"
                                aria-selected="false">Membership</button>
                        </li>
                        <li class="nav-item d-flex justify-content-center align-items-center" role="presentation">
                            <button class="nav-link" id="pills-contact-tab" data-bs-toggle="pill"
                                data-bs-target="#pills-contact" type="button" role="tab" aria-controls="pills-contact"
                                aria-selected="false">Transaction</button>
                        </li>
                        <li class="nav-item d-flex justify-content-center align-items-center" role="presentation">
                            <button class="nav-link" id="pills-contact-tab" data-bs-toggle="pill"
                                data-bs-target="#pills-settings" type="button" role="tab" aria-controls="pills-contact"
                                aria-selected="false">Settings</button>
                        </li>
                    </ul>
                </div>
                <div class="tab-content" id="pills-tabContent">
                    <div class="tab-pane fade show active" id="pills-home" role="tabpanel"
                        aria-labelledby="pills-home-tab" tabindex="0">

<div class="mt-5">
                            <div class="row">
                                <div class="col-6">
                                    <div class="d-flex justify-content-left align-items-center gap-4">
                                        <div>
                                            <img src="images/unnamed.png" width="150px" height="150px" alt=""
                                                style="border-radius: 50%">
                                        </div>

                                        <div>
                                            <h4 style="font-size: 20px">{{ $user->first_name }} {{ $user->middle_name ? $user->middle_name . ' ' : '' }}{{ $user->last_name }}</h4>

                                            <p class="tw:text-[#808080]">Member ID: COOP-{{ $user->id }}</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-6 text-end d-flex justify-content-end align-items-center edit">
                                    @php
                                    $missingCount = 0;
                                    if($otherinfo && empty($otherinfo->contact_no)) $missingCount++;
                                    if($otherinfo && empty($otherinfo->present_address)) $missingCount++;
                                    if($otherinfo && empty($otherinfo->permanent_address)) $missingCount++;
                                    if($otherinfo && empty($otherinfo->date_of_birth)) $missingCount++;
                                    if($otherinfo && empty($otherinfo->place_of_birth)) $missingCount++;
                                    if($otherinfo && empty($otherinfo->sex)) $missingCount++;
                                    if($otherinfo && empty($otherinfo->civil_status)) $missingCount++;
                                    if($otherinfo && empty($otherinfo->citizenship)) $missingCount++;
                                    if($otherinfo && empty($otherinfo->blood_type)) $missingCount++;
                                    if($otherinfo && empty($otherinfo->height)) $missingCount++;
                                    if($otherinfo && empty($otherinfo->weight)) $missingCount++;
                                    if($membergovernIds && empty($membergovernIds->sss_id)) $missingCount++;
                                    if($membergovernIds && empty($membergovernIds->philhealth_id)) $missingCount++;
                                    if($membergovernIds && empty($membergovernIds->pagibig_id)) $missingCount++;
                                    if($membergovernIds && empty($membergovernIds->tin_id)) $missingCount++;
                                    @endphp
                                    <button style="padding: 10px 30px; background-color: #1E4035; color: #ffffff; font-weight: 600;" type="button" class="btn position-relative" data-bs-toggle="modal" data-bs-target="#editProfileModal">
                                        <i class="fa fa-edit"></i> Edit Profile
                                        @if($missingCount > 0)
                                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 12px; padding: 4px 8px;">
                                            {{ $missingCount }}
                                        </span>
                                        @endif
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="mt-5">
                            <div class="row">
                                <div class="col-lg-4 col-md-6 mb-4" style="">
                                    <div class="profile-card" style="background: white; padding: 25px; border-radius: 15px; box-shadow: 0 1px 3px rgba(0, 0, 0, 0.06), 0 1px 2px rgba(0, 0, 0, 0.04);
    border: 1px solid #E2E8E5; ">
                                        <h5 style="margin-bottom: 20px; color: #1E4035; border-bottom: 2px solid #1E4035; padding-bottom: 10px;"><i class="fa fa-user-circle" style="color: #1E4035;"></i> Personal Details</h5>
                                        <div class="mb-3">
                                            <small style="color: #6b7280;">Fullname</small>
                                            <p style="margin: 0; font-weight: 600; color: #1f2937;">{{ $user->first_name }} {{ $user->middle_name ? $user->middle_name . ' ' : '' }}{{ $user->last_name }}</p>
                                        </div>
                                        <div class="mb-3">
                                            <small style="color: #6b7280;">Skills</small>
                                            <p style="margin: 0; font-weight: 600; color: #1f2937;">{{ $otherinfo->skills ?? 'N/A' }}</p>
                                        </div>
                                        <div class="mb-3">
                                            <small style="color: #6b7280;">Email</small>
                                            <p style="margin: 0; font-weight: 600; color: #1f2937;">{{ $email }}</p>
                                        </div>
                                        <div class="mb-3">
                                            <small style="color: #6b7280;">Date of Birth</small>
                                            <p style="margin: 0; font-weight: 600; color: #1f2937;">{{ $otherinfo->date_of_birth ?? 'N/A' }}</p>
                                        </div>
                                        <div class="mb-3">
                                            <small style="color: #6b7280;">Member Since</small>
                                            <p style="margin: 0; font-weight: 600; color: #1f2937;">{{ $memberSince }}</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-4 col-md-6 mb-4" style="">
                                    <div class="profile-card" style="background: white; padding: 25px; border-radius: 15px; box-shadow: 0 1px 3px rgba(0, 0, 0, 0.06), 0 1px 2px rgba(0, 0, 0, 0.04);
    border: 1px solid #E2E8E5; ">
                                        <h5 style="margin-bottom: 20px; color: #1E4035; border-bottom: 2px solid #1E4035; padding-bottom: 10px;"><i class="fa fa-map-marker" style="color: #1E4035;"></i> Location</h5>
                                        <div class="mb-3">
                                            <small style="color: #6b7280;">Present Address</small>
                                            <p style="margin: 0; font-weight: 600; color: #1f2937;">{{ $otherinfo->present_address ?? 'N/A' }}</p>
                                        </div>
                                        <div class="mb-3">
                                            <small style="color: #6b7280;">Permanent Address</small>
                                            <p style="margin: 0; font-weight: 600; color: #1f2937;">{{ $otherinfo->permanent_address ?? 'N/A' }}</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-4 col-md-6 mb-4">
                                    <div class="profile-card" style="background: white; padding: 25px; border-radius: 15px; box-shadow: 0 1px 3px rgba(0, 0, 0, 0.06), 0 1px 2px rgba(0, 0, 0, 0.04);
    border: 1px solid #E2E8E5;">
                                        <h5 style="margin-bottom: 20px; color: #1E4035; border-bottom: 2px solid #1E4035; padding-bottom: 10px;"><i class="fa fa-venus-mars" style="color: #1E4035;"></i> Personal Info</h5>
                                        <div class="mb-3">
                                            <small style="color: #6b7280;">Sex</small>
                                            <p style="margin: 0; font-weight: 600; color: #1f2937;">{{ $otherinfo->sex ?? 'N/A' }}</p>
                                        </div>
                                        <div class="mb-3">
                                            <small style="color: #6b7280;">Civil Status</small>
                                            <p style="margin: 0; font-weight: 600; color: #1f2937;">{{ $otherinfo->civil_status ?? 'N/A' }}</p>
                                        </div>
                                        <div class="mb-3">
                                            <small style="color: #6b7280;">Citizenship</small>
                                            <p style="margin: 0; font-weight: 600; color: #1f2937;">{{ $otherinfo->citizenship ?? 'N/A' }}</p>
                                        </div>
                                        <div class="mb-3">
                                            <small style="color: #6b7280;">Blood Type</small>
                                            <p style="margin: 0; font-weight: 600; color: #1f2937;">{{ $otherinfo->blood_type ?? 'N/A' }}</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-4 col-md-6 mb-4">
                                    <div class="profile-card" style="background: white; padding: 25px; border-radius: 15px; box-shadow: 0 1px 3px rgba(0, 0, 0, 0.06), 0 1px 2px rgba(0, 0, 0, 0.04);
    border: 1px solid #E2E8E5;">
                                        <h5 style="margin-bottom: 20px; color: #1E4035; border-bottom: 2px solid #1E4035; padding-bottom: 10px;"><i class="fa fa-ruler-vertical" style="color: #1E4035;"></i> Body Info</h5>
                                        <div class="mb-3">
                                            <small style="color: #6b7280;">Height</small>
                                            <p style="margin: 0; font-weight: 600; color: #1f2937;">{{ $otherinfo->height ?? 'N/A' }}</p>
                                        </div>
                                        <div class="mb-3">
                                            <small style="color: #6b7280;">Weight</small>
                                            <p style="margin: 0; font-weight: 600; color: #1f2937;">{{ $otherinfo->weight ?? 'N/A' }}</p>
                                        </div>
                                    </div>
                                </div>

                                @if($membergovernIds)
                                <div class="col-lg-4 col-md-6 mb-4">
                                    <div class="profile-card" style="background: white; padding: 25px; border-radius: 15px; box-shadow: 0 1px 3px rgba(0, 0, 0, 0.06), 0 1px 2px rgba(0, 0, 0, 0.04);
    border: 1px solid #E2E8E5;">
                                        <h5 style="margin-bottom: 20px; color: #1E4035; border-bottom: 2px solid #1E4035; padding-bottom: 10px;"><i class="fa fa-id-card" style="color: #1E4035;"></i> Government IDs</h5>
                                        <div class="mb-3">
                                            <small style="color: #6b7280;">SSS ID</small>
                                            <p style="margin: 0; font-weight: 600; color: #1f2937;">{{ $membergovernIds->sss_id ?? 'N/A' }}</p>
                                        </div>
                                        <div class="mb-3">
                                            <small style="color: #6b7280;">PhilHealth ID</small>
                                            <p style="margin: 0; font-weight: 600; color: #1f2937;">{{ $membergovernIds->philhealth_id ?? 'N/A' }}</p>
                                        </div>
                                        <div class="mb-3">
                                            <small style="color: #6b7280;">Pag-IBIG ID</small>
                                            <p style="margin: 0; font-weight: 600; color: #1f2937;">{{ $membergovernIds->pagibig_id ?? 'N/A' }}</p>
                                        </div>
                                        <div class="mb-3">
                                            <small style="color: #6b7280;">TIN ID</small>
                                            <p style="margin: 0; font-weight: 600; color: #1f2937;">{{ $membergovernIds->tin_id ?? 'N/A' }}</p>
                                        </div>
                                    </div>
                                </div>
                                @endif

                                @if($family)
                                <div class="col-lg-4 col-md-6 mb-4">
                                    <div class="profile-card" style="background: white; padding: 25px; border-radius: 15px; border: 1px solid #E2E8E5; box-shadow: 0 1px 3px rgba(0, 0, 0, 0.06), 0 1px 2px rgba(0, 0, 0, 0.04);">
                                        <h5 style="margin-bottom: 20px; color: #1E4035; border-bottom: 2px solid #1E4035; padding-bottom: 10px;"><i class="fa fa-users" style="color: #1E4035;"></i> Family</h5>
                                        <div class="mb-3">
                                            <small style="color: #6b7280;">Spouse Name</small>
                                            <p style="margin: 0; font-weight: 600; color: #1f2937;">{{ $family->spouse_name ?? 'N/A' }}</p>
                                        </div>
                                        <div class="mb-3">
                                            <small style="color: #6b7280;">Spouse Birthdate</small>
                                            <p style="margin: 0; font-weight: 600; color: #1f2937;">{{ $family->spouse_date_birth ?? 'N/A' }}</p>
                                        </div>
                                        <div class="mb-3">
                                            <small style="color: #6b7280;">Sons / Daughters</small>
                                            <p style="margin: 0; font-weight: 600; color: #1f2937;">{{ $family->number_son ?? 0 }} / {{ $family->number_daughter ?? 0 }}</p>
                                        </div>
                                    </div>
                                </div>
                                @endif

                                @if($vehicles->count() > 0)
                                <div class="col-lg-4 col-md-6 mb-4">
                                    <div class="profile-card" style="background: white; padding: 25px; border-radius: 15px; border: 1px solid #1E4035; box-shadow: 0 4px 6px rgba(59, 130, 246, 0.1);">
                                        <h5 style="margin-bottom: 20px; color: #1e40af; border-bottom: 2px solid #3b82f6; padding-bottom: 10px;"><i class="fa fa-car" style="color: #3b82f6;"></i> Vehicles</h5>
                                        @foreach($vehicles as $vehicle)
                                        <div class="mb-3">
                                            <small style="color: #6b7280;">{{ $vehicle->vehicle_type ?? 'Vehicle' }}</small>
                                            <p style="margin: 0; font-weight: 600; color: #1f2937;">{{ $vehicle->plate_no ?? 'N/A' }} (x{{ $vehicle->quantity ?? 0 }})</p>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                                @endif

                                @if($educational)
                                <div class="col-lg-4 col-md-6 mb-4">
                                    <div class="profile-card" style="background: white; padding: 25px; border-radius: 15px; border: 1px solid #E2E8E5;">
                                        <h5 style="margin-bottom: 20px; color: #1e40af; border-bottom: 2px solid #3b82f6; padding-bottom: 10px;"><i class="fa fa-graduation-cap" style="color: #3b82f6;"></i> Education</h5>
                                        <div class="mb-3">
                                            <small style="color: #6b7280;">Level</small>
                                            <p style="margin: 0; font-weight: 600; color: #1f2937;">{{ $educational->educational_level ?? 'N/A' }}</p>
                                        </div>
                                        <div class="mb-3">
                                            <small style="color: #6b7280;">Status</small>
                                            <p style="margin: 0; font-weight: 600; color: #1f2937;">{{ $educational->status ?? 'N/A' }}</p>
                                        </div>
                                        <div class="mb-3">
                                            <small style="color: #6b7280;">Specify</small>
                                            <p style="margin: 0; font-weight: 600; color: #1f2937;">{{ $educational->specify ?? 'N/A' }}</p>
                                        </div>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>

                    </div>
                    <div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab"
                        tabindex="0">

                        <div class="mt-5 mb-5">
                            <div class="d-flex justify-content-center align-items-center gap-5">
                                <div class="card-tab">
                                    <div class="d-flex justify-content-left align-items-center gap-3">
                                        <div class="d-flex justify-content-center align-items-center"
                                            style="width: 55px; height: 55px; background-color: #5295FF; border-radius: 10px;">
                                            <i class="fa fa-check-circle" style="color: white; font-size: 25px;"></i>
                                        </div>

                                        <div>
                                            <p style="margin: 0">Membership Status</p>
                                        </div>
                                    </div>


                                    <div class="row" style="margin-top: 2rem">
                                        <div class="col-6">
                                            <p class="tw:text-[#808080]">Status</p>
                                        </div>

                                        <div class="col-6 text-end">
                                            <span>{{ $otherinfo->membership_status ?? 'Active' }}</span>
                                        </div>
                                    </div>

                                    <div class="row" style="margin-top: 0.5rem">
                                        <div class="col-6">
                                            <p class="tw:text-[#808080]">Member ID</p>
                                        </div>

                                        <div class="col-6 text-end">
                                            <span>COOP-{{ $user->id }}</span>
                                        </div>
                                    </div>

                                    <div class="row" style="margin-top: 0.5rem">
                                        <div class="col-6">
                                            <p class="tw:text-[#808080]">Member Since</p>
                                        </div>

                                        <div class="col-6 text-end">
                                            <span>{{ $memberSince }}</span>
                                        </div>
                                    </div>

                                    <div class="row" style="margin-top: 0.5rem">
                                        <div class="col-6">
                                            <p class="tw:text-[#808080]">Membership Type</p>
                                        </div>

                                        <div class="col-6 text-end">
                                            <span>{{ $user->role }}</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="card-tab">
                                    <div class="d-flex justify-content-left align-items-center gap-3">
                                        <div class="d-flex justify-content-center align-items-center"
                                            style="width: 55px; height: 55px; background-color: #00A63E; border-radius: 10px;">
                                            <i class="fa fa-wallet" style="color: white; font-size: 25px;"></i>
                                        </div>

                                        <div>
                                            <p style="margin: 0">Share Capital</p>
                                        </div>
                                    </div>


                                    <div class="row" style="margin-top: 2rem">
                                        <div class="col-6">
                                            <p class="tw:text-[#808080]">Total Shares</p>
                                        </div>

                                        <div class="col-6 text-end">
                                            <span>{{ $shareCapitalAccount->total_shares ?? 0 }}</span>
                                        </div>
                                    </div>

                                    <div class="row" style="margin-top: 0.5rem">
                                        <div class="col-6">
                                            <p class="tw:text-[#808080]">Share Value</p>
                                        </div>

                                        <div class="col-6 text-end">
                                            <span>₱ 1000 per share</span>
                                        </div>
                                    </div>

                                    <div class="row" style="margin-top: 0.5rem">
                                        <div class="col-6">
                                            <p class="tw:text-[#808080]">Total Value</p>
                                        </div>

                                        <div class="col-6 text-end">
                                            <span>₱ {{ number_format($shareCapitalAccount->total_amount ?? 0, 2) }}</span>
                                        </div>
                                    </div>

                                    <div class="row" style="margin-top: 0.5rem">
                                        <div class="col-6">
                                            <p class="tw:text-[#808080]">Dividend Rate</p>
                                        </div>

                                        <div class="col-6 text-end">
                                            <span>{{ $dividendRate->rate ?? 0 }}% per annum</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-5">
                            <div class="d-flex justify-content-center align-items-center pb-5">
                                <div class="card-tab-benefits">
                                    <h4>Membership Benefits</h4>

                                    <div class="row mt-4">
                                        <div class="col-5">
                                            <div class="d-flex justify-content-left align-items-center gap-2">
                                                <i class="fa fa-check-circle"
                                                    style="color: #128B4A; font-size: 20px;"></i>
                                                <p style="margin: 0">Access to all loan products</p>
                                            </div>

                                            <div class="d-flex justify-content-left align-items-center gap-2 mt-3">
                                                <i class="fa fa-check-circle"
                                                    style="color: #128B4A; font-size: 20px;"></i>
                                                <p style="margin: 0">Annual patronage refund</p>
                                            </div>

                                            <div class="d-flex justify-content-left align-items-center gap-2 mt-3">
                                                <i class="fa fa-check-circle"
                                                    style="color: #128B4A; font-size: 20px;"></i>
                                                <p style="margin: 0">Member discount programs</p>
                                            </div>

                                            <div class="d-flex justify-content-left align-items-center gap-2 mt-3">
                                                <i class="fa fa-check-circle"
                                                    style="color: #128B4A; font-size: 20px;"></i>
                                                <p style="margin: 0">Emergency loan assistance</p>
                                            </div>
                                        </div>

                                        <div class="col-6">
                                            <div class="d-flex justify-content-left align-items-center gap-2">
                                                <i class="fa fa-check-circle"
                                                    style="color: #128B4A; font-size: 20px;"></i>
                                                <p style="margin: 0">Competitive savings dividends</p>
                                            </div>

                                            <div class="d-flex justify-content-left align-items-center gap-2 mt-3">
                                                <i class="fa fa-check-circle"
                                                    style="color: #128B4A; font-size: 20px;"></i>
                                                <p style="margin: 0">Free financial consultation</p>
                                            </div>

                                            <div class="d-flex justify-content-left align-items-center gap-2 mt-3">
                                                <i class="fa fa-check-circle"
                                                    style="color: #128B4A; font-size: 20px;"></i>
                                                <p style="margin: 0">Educational seminars</p>
                                            </div>

                                            <div class="d-flex justify-content-left align-items-center gap-2 mt-3">
                                                <i class="fa fa-check-circle"
                                                    style="color: #128B4A; font-size: 20px;"></i>
                                                <p style="margin: 0">Insurance coverage</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="tab-pane fade" id="pills-contact" role="tabpanel" aria-labelledby="pills-contact-tab"
                        tabindex="0">

<div class="mt-5">
                            <div class="transaction-tab">
                                <h4>Transaction History</h4>

                                <p class="tw:text-[#808080]">View all your account transactions</p>

                                <div class="mt-4">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>Date</th>
                                                <th>Type</th>
                                                <th>Description</th>
                                                <th>Amount</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($transactions as $transaction)
                                            <tr>
                                                <td>{{ \Carbon\Carbon::parse($transaction['date'])->format('m/d/Y') }}</td>
                                                <td>
                                                    <div class="d-flex justify-content-center align-items-center gap-1 card-icon"
                                                        style="background-color: #DBEAFE; border-radius: 28px; color: #1447E6; padding: 0.5rem; border: 1px solid rgba(0,0,0,0.3); width: 120px;">
                                                        <p style="margin: 0; font-size: 13px; font-weight: semibold;">
                                                            {{ $transaction['type'] }}</p>
                                                    </div>
                                                </td>
                                                <td style="width: 250px; line-height: 35px;">{{ $transaction['description'] }}</td>
                                                <td style="color: {{ $transaction['amount'] >= 0 ? '#00A63E' : '#E7000B' }}">
                                                    {{ $transaction['amount'] >= 0 ? '+' : '' }}₱ {{ number_format(abs($transaction['amount']), 2) }}</td>
                                                <td>
                                                    <div class="d-flex justify-content-center align-items-center gap-1 card-icon"
                                                        style="background-color: #DBFCE7; border-radius: 28px; color: #128B4A; padding: 0.5rem; border: 1px solid rgba(0,0,0,0.3); width: 100px;">
                                                        <p style="margin: 0; font-size: 13px; font-weight: semibold;">
                                                            {{ $transaction['status'] }}</p>
                                                    </div>
                                                </td>
                                            </tr>
                                            @empty
                                            <tr>
                                                <td colspan="5" class="text-center">No transactions yet</td>
                                            </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    <div class="tab-pane fade" id="pills-settings" role="tabpanel" aria-labelledby="pills-disabled-tab"
                        tabindex="0">

                        <div class="mt-5 mb-5">
                            <div class="settings-tab">
                                <h4>Notification Preferences</h4>

                                <div class="row">
                                    <div class="mt-4">
                                        <div class="col-6">
                                            <p>Email Notifications</p>

                                            <span>Receive updates via Email</span>
                                        </div>
                                    </div>

                                    <div class="mt-3">
                                        <div class="col-6">
                                            <p>SMS Notifications</p>

                                            <span>Receive updates via SMS</span>
                                        </div>
                                    </div>

                                    <div class="mt-3">
                                        <div class="col-6">
                                            <p>Monthly Statements</p>

                                            <span>Receive monthly account statements</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="security-tab mt-4">
                                <h4>Security</h4>

                                <div class="mt-4 security-box">
                                    <span>Change Password</span>

                                    <p class="tw:text-[#808080]" style="margin: 0">Update your account password</p>
                                </div>

                                <div class="mt-3 security-box">
                                    <span>Two-Factor Authentication</span>

                                    <p class="tw:text-[#808080]" style="margin: 0">Add extra security to your account</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    {{-- Edit Profile Modal --}}
    <div class="modal fade" id="editProfileModal" tabindex="-1" aria-labelledby="editProfileModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" style="height: 100vh; max-height: 100vh; margin: auto; display: flex; align-items: center; justify-content: center;">
            <div class="modal-content" style="max-height: 90vh; width: 100%;">
                <div class="modal-header">
                    <h5 class="modal-title" id="editProfileModalLabel"><i class="fa fa-edit"></i> Edit Profile</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" style="overflow-y: auto; max-height: 78vh;">
                    <form action="{{ route('UpdateProfileMember') }}" method="POST">
                        @csrf
                        <h6 class="mb-3" style="color: #1e40af; border-bottom: 2px solid #3b82f6; padding-bottom: 5px;">Personal Information</h6>
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label">First Name</label>
                                <input type="text" name="first_name" class="form-control" value="{{ $user->first_name ?? '' }}">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Middle Name</label>
                                <input type="text" name="middle_name" class="form-control" value="{{ $user->middle_name ?? '' }}">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Last Name</label>
                                <input type="text" name="last_name" class="form-control" value="{{ $user->last_name ?? '' }}">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Contact Number</label>
                                <input type="text" name="contact_no" class="form-control" value="{{ $otherinfo->contact_no ?? '' }}">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Date of Birth</label>
                                <input type="date" name="date_of_birth" class="form-control" value="{{ $otherinfo->date_of_birth ?? '' }}">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Sex</label>
                                <select name="sex" class="form-control">
                                    <option value="">Select</option>
                                    <option value="Male" {{ ($otherinfo->sex ?? '') == 'Male' ? 'selected' : '' }}>Male</option>
                                    <option value="Female" {{ ($otherinfo->sex ?? '') == 'Female' ? 'selected' : '' }}>Female</option>
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
                                <input type="text" name="blood_type" class="form-control" value="{{ $otherinfo->blood_type ?? '' }}">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Height</label>
                                <input type="text" name="height" class="form-control" value="{{ $otherinfo->height ?? '' }}">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Weight</label>
                                <input type="text" name="weight" class="form-control" value="{{ $otherinfo->weight ?? '' }}">
                            </div>
                        </div>

                        <h6 class="mb-3 mt-4" style="color: #1e40af; border-bottom: 2px solid #3b82f6; padding-bottom: 5px;">Address Information</h6>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Present Address</label>
                                <textarea name="present_address" class="form-control" rows="3">{{ $otherinfo->present_address ?? '' }}</textarea>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Permanent Address</label>
                                <textarea name="permanent_address" class="form-control" rows="3">{{ $otherinfo->permanent_address ?? '' }}</textarea>
                            </div>
                        </div>

                        <h6 class="mb-3 mt-4" style="color: #1e40af; border-bottom: 2px solid #3b82f6; padding-bottom: 5px;">Government IDs</h6>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">SSS ID</label>
                                <input type="file" name="sss_id" class="form-control" accept="image/*">
                                @if(!empty($membergovernIds->sss_id))<small class="text-muted">Current: {{ $membergovernIds->sss_id }}</small>@endif
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">PhilHealth ID</label>
                                <input type="file" name="philhealth_id" class="form-control" accept="image/*">
                                @if(!empty($membergovernIds->philhealth_id))<small class="text-muted">Current: {{ $membergovernIds->philhealth_id }}</small>@endif
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Pag-IBIG ID</label>
                                <input type="file" name="pagibig_id" class="form-control" accept="image/*">
                                @if(!empty($membergovernIds->pagibig_id))<small class="text-muted">Current: {{ $membergovernIds->pagibig_id }}</small>@endif
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">TIN ID</label>
                                <input type="file" name="tin_id" class="form-control" accept="image/*">
                                @if(!empty($membergovernIds->tin_id))<small class="text-muted">Current: {{ $membergovernIds->tin_id }}</small>@endif
                            </div>
                        </div>

                        <h6 class="mb-3 mt-4" style="color: #1e40af; border-bottom: 2px solid #3b82f6; padding-bottom: 5px;">Family Background</h6>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Spouse Name</label>
                                <input type="text" name="spouse_name" class="form-control" value="{{ $family->spouse_name ?? '' }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Spouse Birthdate</label>
                                <input type="date" name="spouse_date_birth" class="form-control" value="{{ $family->spouse_date_birth ?? '' }}">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Number of Sons</label>
                                <input type="number" name="number_son" class="form-control" value="{{ $family->number_son ?? '' }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Number of Daughters</label>
                                <input type="number" name="number_daughter" class="form-control" value="{{ $family->number_daughter ?? '' }}">
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