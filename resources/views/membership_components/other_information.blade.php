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
    <div class="form-step-header">
        <h3>Other Information</h3>
        <p>Employment, beneficiary, and membership details.</p>
    </div>

    <div class="form-step-body">
        <div class="row">
            <div class="col-lg-12 mt-4">
                <label class="fw-semibold">Number of Dependents:</label>
                <div class="row">
                    <div class="col-lg-4 mt-4">
                        <label>Son</label>
                        <input type="number" name="number_son" id="number_son" class="form-control" min="0"
                            onkeydown="if(event.key === 'e' || event.key === 'E' || event.key === '+' || event.key === '-') event.preventDefault();">
                    </div>

                    <div class="col-lg-4 mt-4">
                        <label>Daughter</label>
                        <input type="number" name="number_daughter" id="number_daughter" class="form-control" min="0"
                            onkeydown="if(event.key === 'e' || event.key === 'E' || event.key === '+' || event.key === '-') event.preventDefault();">
                    </div>

                    <div class="col-lg-4 mt-4">
                        <label>Other-Specify</label>
                        <input type="text" name="other_spec" id="other_spec" class="form-control">
                        <div class="reminder">
                            <span>If not applicable, you may enter "None"</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-4 mt-4">
                <label>Spouse Name</label>
                <input type="text" name="spouse_name" id="spouse_name" class="form-control">
                <div class="reminder">
                    <span>If not applicable, you may enter "None"</span>
                </div>
            </div>

            <div class="col-lg-4 mt-4">
                <label>Date of Birth</label>
                <input type="date" name="spouse_date_birth" id="spouse_date_birth" class="form-control">
                {{-- Error message element for spouse DOB age validation --}}
                <small id="spouse_dob_error" class="text-danger"></small>
            </div>

            <div class="col-lg-4 mt-4">
                <label>Place of Birth</label>
                <input type="text" name="spouse_place_birth" id="spouse_place_birth" class="form-control">
            </div>
        </div>

        <div class="row driver-operator">
            <div class="col-lg-12 mt-5">
                <div class="d-flex align-items-center gap-2">
                    <label class="fw-semibold">Number of Public Vehicle Owned:</label>
                    <label id="members_category"></label>
                </div>

                <div class="vehicles-grid mt-2" id="vehiclesGrid"></div>
            </div>
        </div>
    </div>
</div>