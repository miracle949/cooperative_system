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
            <div class="form-step-header">
                <h3 class="fw-semibold">Review & Submit</h3>
                <p>Please review your information before submitting. You can go back to previous steps to make changes.
                </p>
            </div>
        </div>

        {{-- <div class="col-lg-12 mt-3">
            <p>Please review your information before submitting. You can go back to previous steps to make changes.</p>
        </div> --}}
    </div>

    <div class="form-step-body">
        <div class="row">
            <div class="col-lg-12 mt-4">
                <div class="card card-body-box">
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
                </div>
            </div>
        </div>

            <div class="row">
                <div class="col-lg-12 mt-4">
                    <div class="card card-body-box">
                        <div class="d-flex align-items-center gap-3 flex-wrap">
                            <label class="m-0">Submmited this</label>
                            <div class="col-lg-3">
                                <input type="date" name="submitted_at" id="submitted_at" class="form-control"
                                    style="font-size: 14.5px;" required>
                            </div>
                            <label class="m-0">Day of</label>
                            <div class="col-lg-3">
                                <input type="text" name="day_of" value="" class="form-control"
                                    placeholder="Enter the day e.g Thursday" style="font-size: 14.5px;">
                            </div>
                        </div>

                        <label class="mt-4"><b>I HEREBY CERTIFY</b> that the foregoing statements are
                            <b>True and Correct:</b></label>

                        <label class="mt-4">Applicant's Signature (Type Full Name)</label>

                        <div class="row">
                            <div class="col-lg-5 mt-2">
                                <div class="card-box-signature">
                                    <canvas id="signature-pad"
                                        style="border:1px solid #ccc; border-radius:10px; width:100%; height:150px;">
                                    </canvas>

                                    <div class="mt-3 text-end">
                                        <button type="button" id="clear" class="">
                                            Clear
                                        </button>
                                    </div>

                                    <!-- Hidden input to store base64 image -->
                                    <input type="hidden" name="signature" id="signature">
                                </div>
                            </div>
                        </div>

                        <label class="mt-3" style="color: #808080">(Signature Over Printed Name)</label>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12 mt-4">
                    <div class="card alert alert-warning p-4">
                        <p class="m-0"><b>Note:</b> By submitting this application, you authorize the cooperative to
                            verify
                            the information provided and to conduct necessary background checks as required by law.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>