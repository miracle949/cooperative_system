<style>
    .reminder {
        margin-top: 0.3rem;
    }

    .reminder span {
        font-size: 14px;
        color: #808080;
    }
</style>
<div class="form-step">
    <h3>Personal Data</h3>

    <div class="row">
        <div class="col-lg-6 mt-4">
            <label>Name *</label>
            <input type="text" name="name" id="name" class="form-control"
                oninput="this.value = this.value.replace(/[^A-Za-z ]/g, '')">
        </div>

        {{-- <div class="col-lg-4 mt-4">
            <label>Firstname</label>
            <input type="text" name="first_name" id="first_name" class="form-control"
                oninput="this.value = this.value.replace(/[^A-Za-z ]/g, '')">
        </div>

        <div class="col-lg-4 mt-4">
            <label>Middlename</label>
            <input type="text" name="middle_name" id="middle_name" class="form-control"
                oninput="this.value = this.value.replace(/[^A-Za-z ]/g, '')">
        </div>

        <div class="col-lg-4 mt-4">
            <label>Lastname</label>
            <input type="text" name="last_name" id="last_name" class="form-control"
                oninput="this.value = this.value.replace(/[^A-Za-z ]/g, '')">
        </div> --}}

        <div class="col-lg-6 mt-4">
            <label>Date of Birth *</label>
            <input type="date" name="date_birth" id="date_birth" class="form-control">
        </div>

        <div class="col-lg-6 mt-4">
            <label>Place of Birth *</label>
            <input type="text" name="place_birth" id="place_birth" class="form-control">
        </div>

        <div class="col-lg-6 mt-4">
            <label>Email *</label>
            <input type="email" name="email" id="email" class="form-control">
        </div>
    </div>

    <div class="row">
        <div class="col-lg-6 mt-4">
            <label class="text-start">What membership type are you?</label>
            <select name="membership_type" id="select_type" class="form-select">
                <option value="" disabled selected>Select type</option>
                <option value="Operator">Operator</option>
                <option value="Driver">Driver</option>
                <option value="Dispatcher">Dispatcher</option>
                <option value="Driver-Operator">Driver-Operator</option>
                <option value="Investor Associate">Investor Associate</option>
                <option value="Allied Workers">Allied Workers</option>
                <option value="Transport Entrepreneur">Transport Entrepreneur</option>
            </select>
        </div>
        <div class="col-lg-6 mt-4">
            <label>Civil Status *</label>
            <select name="civil_status" id="civil_status" class="form-select">
                <option value="">Select Status</option>
                <option value="Single">Single</option>
                <option value="Married">Married</option>
                <option value="Widowed">Widowed</option>
                <option value="Separated">Separated</option>
            </select>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-4 mt-4">
            <label>Spouse Name</label>
            <input type="text" name="spouse_name" id="spouse_name" class="form-control">
            <div class="reminder">
                <span>If not applicable, you may enter “N/A”</span>
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
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12 mt-4">
            <label>TIN NO: *</label>
            <input type="number" name="tin_no" id="tin_no" min="0" class="form-control"
                onkeydown=" if(event.key === 'e' || event.key === 'E' || event.key === '+') event.preventDefault();">
        </div>
    </div>
</div>