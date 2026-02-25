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
    <h3>Other Information</h3>

    <div class="row driver-operator">
        <div class="col-lg-12 mt-4">
            <label class="fw-semibold">Number of Public Vehicle Owned:</label>
            <div class="row">
                <div class="col-lg-4 mt-4">
                    <label>UV's</label>
                    <input type="number" name="uv" id="uv" class="form-control" min="0" onkeydown=" if(event.key === 'e' || event.key === 'E' || event.key === '+' || event.key === '-') event.preventDefault();">
                </div>

                <div class="col-lg-4 mt-4">
                    <label>TAXI</label>
                    <input type="number" name="taxi" id="taxi" class="form-control" min="0" onkeydown=" if(event.key === 'e' || event.key === 'E' || event.key === '+' || event.key === '-') event.preventDefault();">
                </div>

                <div class="col-lg-4 mt-4">
                    <label>BUS</label>
                    <input type="number" name="bus" id="bus" class="form-control" min="0" onkeydown=" if(event.key === 'e' || event.key === 'E' || event.key === '+' || event.key === '-') event.preventDefault();">
                </div>

                <div class="col-lg-4 mt-4">
                    <label>MINI BUS</label>
                    <input type="number" name="mini_bus" id="mini_bus" class="form-control" min="0" onkeydown=" if(event.key === 'e' || event.key === 'E' || event.key === '+' || event.key === '-') event.preventDefault();">
                </div>

                <div class="col-lg-4 mt-4">
                    <label>JEEP</label>
                    <input type="number" name="jeep" id="jeep" class="form-control" min="0" onkeydown=" if(event.key === 'e' || event.key === 'E' || event.key === '+' || event.key === '-') event.preventDefault();">
                </div>

                <div class="col-lg-4 mt-4">
                    <label>MULTI-CAB</label>
                    <input type="number" name="multi_cab" id="multi_cab" class="form-control" min="0" onkeydown=" if(event.key === 'e' || event.key === 'E' || event.key === '+' || event.key === '-') event.preventDefault();">
                </div>

                <div class="col-lg-4 mt-4">
                    <label>TRICYCLE</label>
                    <input type="number" name="tricycle" id="tricycle" class="form-control" min="0" onkeydown=" if(event.key === 'e' || event.key === 'E' || event.key === '+' || event.key === '-') event.preventDefault();">
                </div>

                <div class="col-lg-4 mt-4">
                    <label>Other Specify</label>
                    <input type="text" name="other_info_specify" id="other_info_specify" class="form-control">
                </div>
            </div>
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
                        <input class="form-check-input" type="radio" name="question1" id="radioDefault1" value="Yes">
                        <label class="form-check-label" for="radioDefault1">
                            Yes
                        </label>
                    </div>
                </div>

                <div class="col-lg-2 mt-2">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="question1" id="radioDefault2" value="No">
                        <label class="form-check-label" for="radioDefault2">
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
                        <input class="form-check-input" type="radio" name="question2" id="radioDefault3" value="Yes">
                        <label class="form-check-label" for="radioDefault3">
                            Yes
                        </label>
                    </div>
                </div>

                <div class="col-lg-2 mt-2">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="question2" id="radioDefault4" value="No">
                        <label class="form-check-label" for="radioDefault4">
                            No
                        </label>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>