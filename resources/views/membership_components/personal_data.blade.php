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
    <div class="form-step-header">
        <h3>Personal Data</h3>
        <p>Please fill out all required fields marked with *</p>
    </div>

    <div class="form-step-parents">

        <div class="step-personal">

            <div class="row">

                <div class="col-lg-4 col-md-4 mt-4">
                    <label>Firstname *</label>
                    <input type="text" name="first_name" id="first_name" class="form-control"
                        oninput="this.value = this.value.replace(/[^A-Za-z ]/g, '')" required>
                </div>

                <div class="col-lg-3 col-md-4 mt-4">
                    <label>Middlename</label>
                    <input type="text" name="middle_name" id="middle_name" class="form-control" maxlength="1" oninput="this.value = this.value
            .replace(/[^A-Za-z]/g, '')
            .toUpperCase()
            .slice(0,1)">
                    <div class="reminder">
                        <span>(Optional)</span>
                    </div>
                </div>

                <div class="col-lg-4 col-md-4 mt-4">
                    <label>Lastname *</label>
                    <input type="text" name="last_name" id="last_name" class="form-control"
                        oninput="this.value = this.value.replace(/[^A-Za-z ]/g, '')" required>
                </div>

            </div>

            <div class="row">

                {{-- <div class="col-lg-5 col-md-12 mt-4">
                    <label>Date of Birth *</label>
                    <input type="date" name="date_of_birth" id="date_birth" class="form-control">
                </div> --}}

                <div class="col-lg-4 col-md-12 mt-4">
                    <label>Date of Birth *</label>
                    <input type="date" name="date_of_birth" id="date_birth" class="form-control" required>
                    <small id="dob_error" class="text-danger"></small>
                </div>

                <div class="col-lg-3 mt-4">
                    <label>Civil Status *</label>
                    <select name="civil_status" id="civil_status" class="form-select" required>
                        <option value="">Select Status</option>
                        <option value="Single">Single</option>
                        <option value="Married">Married</option>
                        <option value="Widowed">Widowed</option>
                        <option value="Separated">Separated</option>
                    </select>
                </div>

                <div class="col-lg-4 mt-4">
                    <label>Place of Birth *</label>
                    <input type="text" name="place_of_birth" id="place_birth" class="form-control" required>
                </div>

                <div class="col-lg-6 col-md-12 mt-4">
                    <label>Username *</label>
                    <input type="text" name="username" id="username" class="form-control" required>
                </div>

                <div class="col-lg-6 mt-4">
                    <label class="text-start">Membership category?</label>
                    <select name="membership_category" id="select_type" class="form-select" required>
                        <option value="" disabled selected>Select category</option>
                        <option value="Operator">Operator</option>
                        <option value="Driver">Driver</option>
                        <option value="Dispatcher">Dispatcher</option>
                        <option value="Driver-Operator">Driver-Operator</option>
                        <option value="Investor Associate">Investor Associate</option>
                        <option value="Allied Workers">Allied Workers</option>
                        <option value="Transport Entrepreneur">Transport Entrepreneur</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="logo-image">
            <div class=" tw:bg-white tw:flex tw:justify-center tw:items-center tw:flex-col picture"
                style="border: 1px solid rgba(0,0,0,0.3); border-radius: 10px;">
                <img src="" alt="" class="" id="inputImage">
                <p class="fw-semibold" id="text">Photo</p>

                <p class="tw:text-[#808080]" id="text2">Click here!</p>

                <input type="file" name="profile_picture" id="inputBox" class="form-control">
            </div>
        </div>
    </div>

    <div class="line-body">

    </div>

    <div class="form-step-body">
        <div class="row">
            <div class="col-lg-4 mt-4">
                <label>Email *</label>
                <input type="email" name="email" id="email" class="form-control" required>
                <div class="reminder">
                    <span>Enter a valid existing email address</span>
                </div>
            </div>

            <div class="col-lg-4 mt-4">
                <label>Password *</label>
                <input type="password" name="password" id="password" maxlength="8" class="form-control" required>
                <div class="reminder">
                    <span>Must contain 8 characters with number and special characters</span>
                </div>
            </div>

            <div class="col-lg-4 mt-4">
                <label>Confirm Password *</label>
                <input type="password" name="password_confirmation" id="password_confirmation" maxlength="8"
                    class="form-control" required>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-4 mt-4">
                <label>Sex</label>
                {{-- <input type="text" name="sex" class="form-control" id="sex"> --}}
                <select name="sex" class="form-select" id="sex" required>
                    <option value="">Choose sex</option>
                    <option value="Male">Male</option>
                    <option value="Female">Female</option>
                </select>
            </div>


            <div class="col-lg-4 mt-4">
                <label>Citizenship</label>
                <input type="text" name="citizenship" class="form-control" id="citizenship" required>
            </div>

            <div class="col-lg-4 mt-4">
                <label>Other Skills/Expertise</label>
                <input type="text" name="skills_expertise" class="form-control" id="skills_expertise" required>
            </div>
        </div>
    </div>

    {{-- <div class="row">
        <div class="col-lg-12 mt-4">
            <label>TIN NO: *</label>
            <input type="text" name="tin_no" id="tin_no" class="form-control"
                oninput="this.value = this.value.replace(/[^0-9-]/g, '')">
        </div>
    </div> --}}
</div>