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
                            onkeydown=" if(event.key === 'e' || event.key === 'E' || event.key === '+' || event.key === '-') event.preventDefault();">
                    </div>

                    <div class="col-lg-4 mt-4">
                        <label>Daughter</label>
                        <input type="number" name="number_daughter" id="number_daughter" class="form-control" min="0"
                            onkeydown=" if(event.key === 'e' || event.key === 'E' || event.key === '+' || event.key === '-') event.preventDefault();">
                    </div>

                    <div class="col-lg-4 mt-4">
                        <label>Other-Specify</label>
                        <input type="text" name="other_spec" id="other_spec" class="form-control">
                        <div class="reminder">
                            <span>If not applicable, you may enter “None”</span>
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
                    <span>If not applicable, you may enter “None”</span>
                </div>
            </div>

            <div class="col-lg-4 mt-4">
                <label>Date of Birth</label>
                <input type="date" name="spouse_date_birth" id="spouse_date_birth" class="form-control">
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

        {{-- <div class="row">
            <div class="col-lg-12 mt-4">
                <label>Are you a member of any other Transport Cooperative or Association? if Yes; state
                    the name, location and type of Coop. Answer:</label>
                <textarea name="question_member" id="" class="form-control" rows="5"></textarea>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12 col-md-12 mt-4">
                <label>As provided for in KPMPCATS art of Cooperation and By-Laws, are you willing to
                    accept the liability
                    of the share holders up to the amount your subscription?</label>

                <div class="row">
                    <div class="col-lg-2 mt-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="are_you_willing_liability"
                                id="liabilityYes" value="1">

                            <label class="form-check-label" for="liabilityYes">
                                Yes
                            </label>
                        </div>
                    </div>

                    <div class="col-lg-2 mt-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="are_you_willing_liability"
                                id="liabilityNo" value="0">

                            <label class="form-check-label" for="liabilityNo">
                                No
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12 col-md-12 mt-4">
                <label>Are you willing to abide by the policies, rules and regulations that may be
                    imposed by the CDA, OTC
                    and KPMPCATS in accordance with the existing Laws and Articles of Cooperation and By
                    - Laws?</label>

                <div class="row">
                    <div class="col-lg-2 mt-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="are_you_willing_abide_policy"
                                id="abideYes" value="1">

                            <label class="form-check-label" for="abideYes">
                                Yes
                            </label>
                        </div>
                    </div>

                    <div class="col-lg-2 mt-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="are_you_willing_abide_policy"
                                id="abideNo" value="0">

                            <label class="form-check-label" for="abideNo">
                                No
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div> --}}
    </div>
</div>