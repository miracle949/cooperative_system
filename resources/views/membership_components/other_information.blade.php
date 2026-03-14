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
        <div class="row driver-operator">
            <div class="col-lg-12 mt-4">
                <label class="fw-semibold">Number of Public Vehicle Owned:</label>
                {{-- <div class="row">
                    <div class="col-lg-4 mt-4">
                        <div class="row">
                            <div class="col-lg-9" style="padding: 0 0 0 12px">
                                <label>UV's</label>
                                <input type="text" name="uv_plate_no" id="" class="form-control mt-1"
                                    onkeydown=" if (event.key === '+' || event.key === '=' || event.key === '?' || event.key === '.' || event.key === ',' || event.key === '(' || event.key === ')') event.preventDefault();">
                                <div class="reminder">
                                    <span>Enter plate number</span>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <label>#</label>
                                <input type="number" name="total_uv" id="" class="form-control mt-1" min="0"
                                    onkeydown=" if(event.key === 'e' || event.key === 'E' || event.key === '+' || event.key === '-') event.preventDefault();">
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4 mt-4">
                        <div class="row">
                            <div class="col-lg-9" style="padding: 0 0 0 12px">
                                <label>TAXI</label>
                                <input type="text" name="taxi_plate_no" id="" class="form-control mt-1"
                                    onkeydown=" if (event.key === '+' || event.key === '=' || event.key === '?' || event.key === '.' || event.key === ',' || event.key === '(' || event.key === ')') event.preventDefault();">
                                <div class="reminder">
                                    <span>Enter plate number</span>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <label>#</label>
                                <input type="number" name="total_taxi" id="" class="form-control mt-1"
                                    onkeydown=" if(event.key === 'e' || event.key === 'E' || event.key === '+' || event.key === '-') event.preventDefault();">
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4 mt-4">
                        <div class="row">
                            <div class="col-lg-9" style="padding: 0 0 0 12px">
                                <label>BUS</label>
                                <input type="text" name="bus_plate_no" id="" class="form-control mt-1"
                                    onkeydown=" if (event.key === '+' || event.key === '=' || event.key === '?' || event.key === '.' || event.key === ',' || event.key === '(' || event.key === ')') event.preventDefault();">
                                <div class="reminder">
                                    <span>Enter plate number</span>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <label>#</label>
                                <input type="number" name="total_bus" id="" class="form-control mt-1"
                                    onkeydown=" if(event.key === 'e' || event.key === 'E' || event.key === '+' || event.key === '-') event.preventDefault();">
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4 mt-4">
                        <div class="row">
                            <div class="col-lg-9" style="padding: 0 0 0 12px">
                                <label>MINI BUS</label>
                                <input type="text" name="mini_bus_plate_no" id="" class="form-control mt-1"
                                    onkeydown=" if (event.key === '+' || event.key === '=' || event.key === '?' || event.key === '.' || event.key === ',' || event.key === '(' || event.key === ')') event.preventDefault();">
                                <div class="reminder">
                                    <span>Enter plate number</span>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <label>#</label>
                                <input type="number" name="total_mini_bus" id="" class="form-control mt-1" min="0"
                                    onkeydown=" if(event.key === 'e' || event.key === 'E' || event.key === '+' || event.key === '-') event.preventDefault();">
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4 mt-4">
                        <div class="row">
                            <div class="col-lg-9" style="padding: 0 0 0 12px">
                                <label>JEEP</label>
                                <input type="text" name="jeep_plate_no" id="" class="form-control mt-1"
                                    onkeydown=" if (event.key === '+' || event.key === '=' || event.key === '?' || event.key === '.' || event.key === ',' || event.key === '(' || event.key === ')') event.preventDefault();">
                                <div class="reminder">
                                    <span>Enter plate number</span>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <label>#</label>
                                <input type="number" name="total_jeep" id="" class="form-control mt-1" min="0"
                                    onkeydown=" if(event.key === 'e' || event.key === 'E' || event.key === '+' || event.key === '-') event.preventDefault();">
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4 mt-4">
                        <div class="row">
                            <div class="col-lg-9" style="padding: 0 0 0 12px">
                                <label>MULTI-CAB</label>
                                <input type="text" name="multi_cab_plate_no" id="" class="form-control mt-1"
                                    onkeydown=" if (event.key === '+' || event.key === '=' || event.key === '?' || event.key === '.' || event.key === ',' || event.key === '(' || event.key === ')') event.preventDefault();">
                                <div class="reminder">
                                    <span>Enter plate number</span>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <label>#</label>
                                <input type="number" name="total_multi_cab" id="" class="form-control mt-1" min="0"
                                    onkeydown=" if(event.key === 'e' || event.key === 'E' || event.key === '+' || event.key === '-') event.preventDefault();">
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4 mt-4">
                        <div class="row">
                            <div class="col-lg-9" style="padding: 0 0 0 12px">
                                <label>TRICYCLE</label>
                                <input type="text" name="tricycle_plate_no" id="" class="form-control mt-1"
                                    onkeydown=" if (event.key === '+' || event.key === '=' || event.key === '?' || event.key === '.' || event.key === ',' || event.key === '(' || event.key === ')') event.preventDefault();">
                                <div class="reminder">
                                    <span>Enter plate number</span>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <label>#</label>
                                <input type="number" name="total_tricycle" id="" class="form-control mt-1" min="0"
                                    onkeydown=" if(event.key === 'e' || event.key === 'E' || event.key === '+' || event.key === '-') event.preventDefault();">
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4 mt-4">
                        <label>Other Specify</label>
                        <input type="text" name="other_info_specify" id="other_info_specify" class="form-control">
                        <div class="reminder">
                            <span>If not applicable, you may enter “None”</span>
                        </div>
                    </div>
                </div> --}}

                <div class="vehicles-grid" id="vehiclesGrid"></div>

            </div>
        </div>

        <div class="row">
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
        </div>
    </div>
</div>