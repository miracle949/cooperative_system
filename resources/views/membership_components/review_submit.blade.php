<style>
    .reminder {
        margin-top: 0.5rem;
    }

    .reminder span {
        font-size: 14px;
        color: #808080;
    }
</style>
<div class="form-step">
    <div class="row">
        <div class="col-lg-12">
            <h3 class="fw-semibold">Review & Submit</h3>
        </div>

        <div class="col-lg-12 mt-3">
            <p>Please review your information before submitting. You can go back to previous steps to make changes.</p>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12 mt-4">
            <div class="card">
                <h5 class="fw-semibold">Personal Data</h5>

                <div class="review-parent">
                    <div class="review-first">
                        {{-- <div class="review mt-3">
                            <p>Name:</p>
                            <p id="name_display"></p>
                        </div> --}}

                        <div class="review mt-3">
                            <p>Firstname:</p>
                            <p id="firstname_display"></p>
                        </div>

                        <div class="review mt-3">
                            <p>Middlename:</p>
                            <p id="middlename_display"></p>
                        </div>

                        <div class="review mt-3">
                            <p>Lastname:</p>
                            <p id="lastname_display"></p>
                        </div>

                        <div class="review mt-3">
                            <p>Date of Birth:</p>
                            <p id="date_birth_display"></p>
                        </div>

                        <div class="review mt-3">
                            <p>Place of Birth:</p>
                            <p id="place_birth_display"></p>
                        </div>

                        <div class="review mt-3">
                            <p>Email:</p>
                            <p id="email_display" style="width: 220px;"></p>
                        </div>
                    </div>
                    <div class="review-second">
                        <div class="review mt-3">
                            <p>Spouse Name:</p>
                            <p id="spouse_name_display"></p>
                        </div>

                        <div class="review mt-3">
                            <p>Date of Birth:</p>
                            <p id="spouse_date_birth_display"></p>
                        </div>

                        <div class="review mt-3">
                            <p>Place of Birth:</p>
                            <p id="spouse_place_birth_display"></p>
                        </div>

                        <div class="review mt-3">
                            <p>Tin No:</p>
                            <p id="tin_no_display"></p>
                        </div>

                        <div class="review mt-3">
                            <p>Civil Status:</p>
                            <p id="civil_status_display"></p>
                        </div>

                        <div class="review mt-3">
                            <p>Membership Category:</p>
                            <p id="membership_type_display"></p>
                        </div>
                    </div>
                </div>

                <hr>

                <h5 class="mt-3 fw-semibold">Number of Dependents</h5>

                <div class="review-parent">
                    <div class="review-first">
                        <div class="review mt-3">
                            <p>Son:</p>
                            <p id="son_display"></p>
                        </div>

                        <div class="review mt-3">
                            <p>Daughter:</p>
                            <p id="daughter_display"></p>
                        </div>

                        <div class="review mt-3">
                            <p>Other Specification:</p>
                            <p id="other_spec_display"></p>
                        </div>
                    </div>
                </div>

                {{-- <hr>

                <h5 class="mt-3 fw-semibold">Plate no & Number No of Public Vehicle Owned</h5>

                <div class="review-parent">
                    <div class="review-first">
                        <div class="review mt-3">
                            <p>UV's:</p>
                            <p id="uv_plate_display"></p>
                         
                        </div>

                        <div class="review mt-3">
                            <p>TAXI:</p>
                            <p id="taxi_plate_display"></p>
                            
                        </div>

                        <div class="review mt-3">
                            <p>BUS:</p>
                            <p id="bus_plate_display"></p>
                            
                        </div>

                        <div class="review mt-3">
                            <p>TRICYLE:</p>
                            <p id="tricycle_plate_display"></p>
                            
                        </div>
                    </div>  

                    <div class="review-second">
                        <div class="review mt-3">
                            <p>MINI BUS:</p>
                            <p id="mini_bus_plate_display"></p>
                         
                        </div>

                        <div class="review mt-3">
                            <p>JEEP:</p>
                            <p id="jeep_plate_display"></p>
                            
                        </div>

                        <div class="review mt-3">
                            <p>MULTI-CAB:</p>
                            <p id="multi_cab_plate_display"></p>
                           
                        </div>

                        <div class="review mt-3">
                            <p>Other Specification:</p>
                            <p id="vehi_other_spec"></p>
                        </div>
                    </div>
                </div>

                <hr> --}}


            </div>
        </div>
    </div>

    {{-- <div class="row">
        <div class="col-lg-12">
            <h3 class="fw-semibold">Family and Education Background</h3>
        </div>
        <div class="col-lg-12 mt-4">
            <label class="fw-semibold">Spouse Name: </label>
            <div class="row">
                <div class="col-lg-4 mt-4">
                    <label>Surname </label>
                    <input type="text" name="" id="" class="form-control">
                </div>

                <div class="col-lg-4 mt-4">
                    <label>Firstname </label>
                    <input type="text" name="" id="" class="form-control">
                </div>

                <div class="col-lg-4 mt-4">
                    <label>Middlename </label>
                    <input type="text" name="" id="" class="form-control">
                </div>

                <div class="col-lg-4 mt-4">
                    <label>Occupation </label>
                    <input type="text" name="" id="" class="form-control">
                </div>

                <div class="col-lg-4 mt-4">
                    <label>Employer/Bus Name </label>
                    <input type="text" name="" id="" class="form-control">
                </div>

                <div class="col-lg-4 mt-4">
                    <label>Telephone No. </label>
                    <input type="text" name="" id="" class="form-control">
                </div>

                <div class="col-lg-6 mt-4">
                    <label>Business Address </label>
                    <input type="text" name="" id="" class="form-control">
                </div>

                <div class="col-lg-6 mt-4">
                    <label>Special Skills or Line of Expertise </label>
                    <input type="text" name="" id="" class="form-control">
                </div>
            </div>
        </div>
    </div>

    <div class="mt-4 parent-name-date">
        <div class="name-child">
            <label class="fw-semibold">Name of Child (Write Fullname)</label>

            <div class="mt-2">
                <input type="text" name="" id="" class="form-control">
            </div>

            <div class="mt-4">
                <input type="text" name="" id="" class="form-control">
            </div>

            <div class="mt-4">
                <input type="text" name="" id="" class="form-control">
            </div>

            <div class="mt-4">
                <input type="text" name="" id="" class="form-control">
            </div>
        </div>
        <div class="birth-child">
            <label class="fw-semibold">Date of Birth (mm/dd/yyyy)</label>

            <div class="mt-2">
                <input type="text" name="" id="" class="form-control">
            </div>

            <div class="mt-4">
                <input type="text" name="" id="" class="form-control">
            </div>

            <div class="mt-4">
                <input type="text" name="" id="" class="form-control">
            </div>

            <div class="mt-4">
                <input type="text" name="" id="" class="form-control">
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <label class="fw-semibold">Education Level</label>

        <div class="row">
            <div class="col-lg-4">
                <label class="mt-4">Elementary</label>
                <div class="d-flex gap-4">
                    <div class="mt-1">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="question4" id="radioDefault5">
                            <label class="form-check-label" for="radioDefault5">
                                Graduated
                            </label>
                        </div>
                    </div>

                    <div class="mt-1">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="question4" id="radioDefault6">
                            <label class="form-check-label" for="radioDefault6">
                                Undergraduated
                            </label>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-12 mt-2">
                        <input type="text" name="" id="" class="form-control" placeholder="if not grad pls speficy">
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <label class="mt-4">Secondary</label>
                <div class="d-flex gap-4">
                    <div class="mt-1">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="question5" id="radioDefault7">
                            <label class="form-check-label" for="radioDefault7">
                                Graduated
                            </label>
                        </div>
                    </div>

                    <div class="mt-1">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="question5" id="radioDefault8">
                            <label class="form-check-label" for="radioDefault8">
                                Undergraduated
                            </label>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-12 mt-2">
                        <input type="text" name="" id="" class="form-control" placeholder="if not grad pls speficy">
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <label class="mt-4">Vocational/Trade Course</label>
                <div class="d-flex gap-4">
                    <div class="mt-1">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="question6" id="radioDefault9">
                            <label class="form-check-label" for="radioDefault9">
                                Graduated
                            </label>
                        </div>
                    </div>

                    <div class="mt-1">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="question6" id="radioDefault10">
                            <label class="form-check-label" for="radioDefault10">
                                Undergraduated
                            </label>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-12 mt-2">
                        <input type="text" name="" id="" class="form-control" placeholder="if not grad pls speficy">
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <label class="mt-4">College</label>
                <div class="d-flex gap-4">
                    <div class="mt-1">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="question7" id="radioDefault11">
                            <label class="form-check-label" for="radioDefault11">
                                Graduated
                            </label>
                        </div>
                    </div>

                    <div class="mt-1">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="question7" id="radioDefault12">
                            <label class="form-check-label" for="radioDefault12">
                                Undergraduated
                            </label>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-12 mt-2">
                        <input type="text" name="" id="" class="form-control" placeholder="if not grad pls speficy">
                    </div>
                </div>
            </div>
        </div> --}}

        <div class="row">
            <div class="col-lg-12 mt-4">
                <div class="card">
                    <div class="d-flex align-items-center gap-3 flex-wrap">
                        <label class="m-0">Submmited this</label>
                        <div class="col-lg-3">
                            <input type="date" name="submitted_at" id="submitted_at" class="form-control" style="font-size: 14.5px;" required>
                            {{-- <div class="reminder">
                                <span>Enter the date</span>
                            </div> --}}
                        </div>
                        <label class="m-0">Day of</label>
                        <div class="col-lg-3">
                            <input type="text" name="day_of" value="" class="form-control" placeholder="Enter the day e.g Thursday"
                                style="font-size: 14.5px;">
                            {{-- <div class="reminder">
                                <span>Enter the day e.g "Thursday"</span>
                            </div> --}}
                        </div>
                    </div>

                    <label class="mt-4"><b>I HEREBY CERTIFY</b> that the foregoing statements are
                        <b>True and Correct:</b></label>

                    <label class="mt-4">Applicant's Signature (Type Full Name)</label>

                    <div class="row">
                        <div class="col-lg-5 mt-2">
                            <div class="card-box">
                                {{-- <input type="file" name="" id="" class="form-control"> --}}
                                <canvas id="signature-pad"
                                    style="border:1px solid #ccc; border-radius:10px; width:100%; height:150px;">
                                </canvas>

                                <div class="mt-3 text-end">
                                    <button type="button" id="clear" class="btn btn-sm btn-danger">
                                        Clear
                                    </button>
                                </div>

                                <!-- Hidden input to store base64 image -->
                                <input type="hidden" name="signature" id="signature">
                            </div>
                        </div>
                    </div>

                    <label class="mt-3" style="color: #808080">(Signature Over Printed Name)</label>

                    <hr class="mt-4">

                    <div class="mt-2 d-flex align-items-center gap-3 flex-wrap">
                        <label class="m-0"><b>Note:</b> This Application was Approved / Disapproved
                            by the Board of
                            Directors in its
                        </label>
                        <div class="col-lg-3">
                            <input type="text" name="board_directors" id="" class="form-control" style="font-size: 14.5px;" disabled>
                        </div>
                        <label class="m-0">meeting held at</label>
                        <div class="col-lg-3">
                            <input type="text" name="meeting_held" id="" class="form-control" style="font-size: 14.5px;" disabled>
                        </div>
                        <label class="m-0">dated</label>
                        <div class="col-lg-3">
                            <input type="date" name="dated_of" id="dated_of" class="form-control" style="font-size: 14.5px;" disabled>
                        </div>
                    </div>

                    <div class="mt-3">
                        <label style="color: #808080">(To be filled by the cooperative
                            administration upon review)</label>
                    </div>
                </div>


            </div>
        </div>

        <div class="row">
            <div class="col-lg-12 mt-4">
                <div class="card alert alert-warning p-4">
                    <p class="m-0"><b>Note:</b> By submitting this application, you authorize the cooperative to verify
                        the information provided and to conduct necessary background checks as required by law.</p>
                </div>
            </div>
        </div>
    </div>
</div>

