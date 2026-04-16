<style>
    .reminder {
        margin-top: 0.3rem;
    }

    .reminder span {
        font-size: 14px;
        color: #808080;
    }

    /* ── Password Strength Indicator ── */
    .password-wrapper {
        position: relative;
    }

    .password-toggle {
        position: absolute;
        right: 12px;
        top: 50%;
        transform: translateY(-50%);
        cursor: pointer;
        color: #888;
        background: none;
        border: none;
        padding: 0;
        line-height: 1;
    }

    .strength-bar-wrap {
        margin-top: 8px;
        display: flex;
        gap: 4px;
    }

    .strength-bar-wrap .bar {
        flex: 1;
        height: 5px;
        border-radius: 99px;
        background: #e5e7eb;
        transition: background 0.3s ease;
    }

    .strength-label {
        margin-top: 5px;
        font-size: 12px;
        font-weight: 600;
        min-height: 16px;
        transition: color 0.3s ease;
    }

    /* strength levels */
    .strength-0 .bar {
        background: #e5e7eb;
    }

    .strength-1 .bar:nth-child(1) {
        background: #ef4444;
    }

    .strength-1 .bar:nth-child(n+2) {
        background: #e5e7eb;
    }

    .strength-2 .bar:nth-child(-n+2) {
        background: #f97316;
    }

    .strength-2 .bar:nth-child(n+3) {
        background: #e5e7eb;
    }

    .strength-3 .bar:nth-child(-n+3) {
        background: #eab308;
    }

    .strength-3 .bar:nth-child(n+4) {
        background: #e5e7eb;
    }

    .strength-4 .bar:nth-child(-n+4) {
        background: #22c55e;
    }

    .strength-4 .bar:nth-child(5) {
        background: #e5e7eb;
    }

    .strength-5 .bar {
        background: #16a34a;
    }

    /* strength label colors */
    .label-0 {
        color: transparent;
    }

    .label-1 {
        color: #ef4444;
    }

    .label-2 {
        color: #f97316;
    }

    .label-3 {
        color: #eab308;
    }

    .label-4 {
        color: #22c55e;
    }

    .label-5 {
        color: #16a34a;
    }

    /* password match hint */
    .match-hint {
        margin-top: 5px;
        font-size: 12px;
        font-weight: 600;
        min-height: 16px;
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
                    <input type="text" name="first_name" id="first_name" class="form-control" oninput="this.value = this.value
            .replace(/[^A-Za-z ]/g, '')
            .replace(/\b\w/g, c => c.toUpperCase())" required>
                </div>

                <div class="col-lg-3 col-md-4 mt-4">
                    <label>Middlename</label>
                    <input type="text" name="middle_name" id="middle_name" class="form-control" oninput="this.value = this.value
            .replace(/[^A-Za-z ]/g, '')
            .replace(/\b\w/g, c => c.toUpperCase())">
                    <div class="reminder">
                        <span>(Optional)</span>
                    </div>
                </div>

                <div class="col-lg-4 col-md-4 mt-4">
                    <label>Lastname *</label>
                    <input type="text" name="last_name" id="last_name" class="form-control" oninput="this.value = this.value
            .replace(/[^A-Za-z ]/g, '')
            .replace(/\b\w/g, c => c.toUpperCase())" required>
                </div>

            </div>

            <div class="row">

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

                <div class="col-lg-4 mt-4">
                    <label>Email *</label>
                    <input type="email" name="email" id="email" class="form-control"
                        pattern="^[a-zA-Z0-9._%+\-]+@gmail\.com$"
                        title="Only Gmail addresses are allowed (e.g. example@gmail.com)" required>
                    <div class="reminder">
                        <span>Enter existing email address</span>
                    </div>
                </div>

                {{-- ── Password with strength indicator ── --}}
                <div class="col-lg-4 mt-4">
                    <label>Password *</label>
                    <div class="password-wrapper">
                        <input type="password" name="password" id="password" class="form-control"
                            style="padding-right: 40px;" autocomplete="new-password"
                            oninput="checkPasswordStrength(this.value); checkPasswordMatch();" required>
                        {{-- <button type="button" class="password-toggle"
                            onclick="togglePassword('password', 'eye-password')" tabindex="-1">
                            <i class="fa fa-eye" id="eye-password"></i>
                        </button> --}}
                    </div>
                    <div class="strength-bar-wrap strength-0" id="strength-bars">
                        <div class="bar"></div>
                        <div class="bar"></div>
                        <div class="bar"></div>
                        <div class="bar"></div>
                        <div class="bar"></div>
                    </div>
                    <div class="strength-label label-0" id="strength-label"></div>
                </div>

                {{-- ── Confirm Password with match hint ── --}}
                <div class="col-lg-4 mt-4">
                    <label>Confirm Password *</label>
                    <div class="password-wrapper">
                        <input type="password" name="password_confirmation" id="password_confirmation"
                            class="form-control" style="padding-right: 40px;" autocomplete="new-password"
                            oninput="checkPasswordMatch();" required>
                        {{-- <button type="button" class="password-toggle"
                            onclick="togglePassword('password_confirmation', 'eye-confirm')" tabindex="-1">
                            <i class="fa fa-eye" id="eye-confirm"></i>
                        </button> --}}
                    </div>
                    <div class="match-hint" id="match-hint"></div>
                </div>

            </div>
        </div>

        <div class="logo-image">
            <div class="tw:bg-white tw:flex tw:justify-center tw:items-center tw:flex-col picture"
                style="border: 1px solid rgba(0,0,0,0.3); border-radius: 10px;">
                <img src="" alt="" class="" id="inputImage">
                <p class="fw-semibold" id="text">Photo</p>
                <p class="tw:text-[#808080]" id="text2">Click here!</p>
                <input type="file" name="profile_picture" id="inputBox" class="form-control">
            </div>
        </div>
    </div>

    <div class="line-body"></div>

    <div class="form-step-body">

        <div class="row">
            <div class="col-lg-6 col-md-12 mt-4">
                <label>Username *</label>
                <input type="text" name="username" id="username" class="form-control" required>
            </div>

            <div class="col-lg-6 mt-4">
                <label class="text-start">Membership category?</label>
                <select name="membership_category" id="select_type" class="form-select" required>
                    <option value="" disabled selected>Select category</option>
                    <option value="Operator">Operator - (Manages transport operations.)</option>
                    <option value="Driver">Driver - (Drives vehicles and transports passengers)</option>
                    <option value="Dispatcher">Dispatcher - (Assigns trips and coordinates drivers.)</option>
                    <option value="Driver-Operator">Driver-Operator - (Drives and manages their own operations.)
                    </option>
                    <option value="Allied Workers">Allied Workers - (Supports operations (e.g., mechanic, staff,
                        helper).)</option>
                    <option value="Transport Entrepreneur">Transport Entrepreneur - (Owns or manages multiple transport
                        units.)</option>
                    <option value="Investor Associate">Investor Associate - (Provides capital but not involved in daily
                        operations.)</option>
                </select>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-4 mt-4">
                <label>Sex</label>
                <select name="sex" class="form-select" id="sex" required>
                    <option value="">Choose sex</option>
                    <option value="Male">Male</option>
                    <option value="Female">Female</option>
                </select>
            </div>

            {{-- ── Citizenship — standard <select>, options populated by JS ── --}}
                <div class="col-lg-4 mt-4">
                    <label>Citizenship</label>
                    <select name="citizenship" id="citizenship" class="form-select" required>
                        <option value="">Select citizenship</option>
                        {{-- JS will inject all options below --}}
                    </select>
                </div>

                <div class="col-lg-4 mt-4">
                    <label>Other Skills/Expertise</label>
                    <input type="text" name="skills_expertise" class="form-control" id="skills_expertise">
                    <div class="reminder">
                        <span>(Optional)</span>
                    </div>
                </div>
        </div>
    </div>

    <div id="otp-modal-overlay"
        style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.5); z-index:9999; align-items:center; justify-content:center;">
        <div
            style="background:#fff; border-radius:12px; padding:2rem; width:100%; max-width:400px; border: 0.5px solid rgba(0,0,0,0.1);">

            {{-- Icon --}}
            <div
                style="width:44px; height:44px; border-radius:50%; background: var(--green); display:flex; align-items:center; justify-content:center; margin-bottom:1rem;">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2"
                    stroke-linecap="round" stroke-linejoin="round">
                    <rect x="2" y="4" width="20" height="16" rx="2" />
                    <path d="M2 8l10 7 10-7" />
                </svg>
            </div>

            <h5 style="font-size:18px; font-weight:500; margin:0 0 6px;">Verify your email</h5>
            <p style="font-size:13px; color:#6b7280; margin:0 0 1.5rem; line-height:1.5;">
                We sent a 6-digit code to <strong style="color:#111;" id="otp-email-display"></strong>. Enter it below
                to continue.
            </p>

            <input type="text" id="otp-input" maxlength="6" class="form-control text-center fw-bold"
                style="font-size:1.6rem; letter-spacing:0.6rem; height:56px; border:1.5px solid #d1d5db;"
                placeholder="— — — — — —">
            <small id="otp-error" class="text-danger d-block mt-2" style="min-height:18px;"></small>

            <div class="d-flex gap-2 mt-3">
                <button id="otp-confirm-btn" type="button"
                    style="flex:1; height:40px; background: var(--green); color:#fff; border:none; border-radius:8px; font-size:14px; font-weight:600; cursor:pointer;">
                    Confirm
                </button>
                {{-- <button id="otp-resend-btn" type="button"
                    style="height:40px; padding:0 16px; background:transparent; border:0.5px solid #d1d5db; border-radius:8px; font-size:14px; cursor:pointer;">
                    Resend
                </button> --}}
                <button id="otp-cancel-btn" type="button"
                    style="height:40px; flex: 1; padding:0 16px; background:transparent; color:var(--green); border:0.5px solid var(--green); border-radius:8px; font-size:14px; cursor:pointer;">
                    Cancel
                </button>
            </div>

            <hr style="margin:1.25rem 0; border-color:#f3f4f6;">
            <p style="font-size:12px; color:#9ca3af; text-align:center; margin:0;">
                Didn't receive the code? <span id="otp-resend-link"
                    style="color:#111; font-weight:500; cursor:pointer; text-decoration:underline;">Send again</span>
            </p>
        </div>
    </div>
</div>

<script>
    let emailOtpVerified = false;

    document.getElementById('otp-confirm-btn')?.addEventListener('click', function () {
        const otp = document.getElementById('otp-input').value.trim();
        const email = document.getElementById('email').value.trim();

        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content
            || document.querySelector('input[name="_token"]')?.value
            || '';

        fetch('{{ url("/otp/verify") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify({ otp, email })
        })
            .then(res => res.json())
            .then(data => {
                if (data.valid) {
                    emailOtpVerified = true;
                    document.getElementById('otp-modal-overlay').style.display = 'none';
                } else {
                    document.getElementById('otp-error').textContent = data.message;
                }
            })
            .catch(err => console.error('OTP verify failed:', err));
    });

    document.getElementById('otp-cancel-btn')?.addEventListener('click', function () {
        document.getElementById('otp-modal-overlay').style.display = 'none';
    });

    // otp-resend-btn is commented out in HTML — this is safely skipped if null
    document.getElementById('otp-resend-btn')?.addEventListener('click', function () {
        const email = document.getElementById('email').value.trim();
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content
            || document.querySelector('input[name="_token"]')?.value
            || '';

        fetch('{{ url("/otp/send") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify({ email })
        }).catch(err => console.error('OTP resend failed:', err));
    });

    // otp-resend-link (the "Send again" text link)
    document.getElementById('otp-resend-link')?.addEventListener('click', function () {
        const email = document.getElementById('email').value.trim();
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content
            || document.querySelector('input[name="_token"]')?.value
            || '';

        fetch('{{ url("/otp/send") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify({ email })
        }).catch(err => console.error('OTP resend failed:', err));
    });
</script>

<script>
    // ═══════════════════════════════════════════════════
    //  PASSWORD TOGGLE
    // ═══════════════════════════════════════════════════
    function togglePassword(inputId, iconId) {
        const input = document.getElementById(inputId);
        const icon = document.getElementById(iconId);
        if (!input || !icon) return;
        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.replace('fa-eye', 'fa-eye-slash');
        } else {
            input.type = 'password';
            icon.classList.replace('fa-eye-slash', 'fa-eye');
        }
    }

    // ═══════════════════════════════════════════════════
    //  PASSWORD STRENGTH
    // ═══════════════════════════════════════════════════
    function checkPasswordStrength(value) {
        const bars = document.getElementById('strength-bars');
        const label = document.getElementById('strength-label');
        if (!bars || !label) return;

        let score = 0;
        if (value.length >= 8) score++;
        if (/[a-z]/.test(value)) score++;
        if (/[A-Z]/.test(value)) score++;
        if (/[0-9]/.test(value)) score++;
        if (/[^A-Za-z0-9]/.test(value)) score++;

        const levelMap = ['', 'Very Weak', 'Weak', 'Fair', 'Strong', 'Very Strong'];
        bars.className = `strength-bar-wrap ${value.length === 0 ? 'strength-0' : 'strength-' + score}`;
        label.className = `strength-label ${value.length === 0 ? 'label-0' : 'label-' + score}`;
        label.textContent = value.length === 0 ? '' : levelMap[score] || '';
        bars.dataset.score = value.length === 0 ? '0' : String(score);
    }

    // ═══════════════════════════════════════════════════
    //  PASSWORD MATCH
    // ═══════════════════════════════════════════════════
    function checkPasswordMatch() {
        const pw = document.getElementById('password');
        const confirm = document.getElementById('password_confirmation');
        const hint = document.getElementById('match-hint');
        if (!pw || !confirm || !hint) return;
        const cfVal = confirm.value;
        if (cfVal.length === 0) { hint.textContent = ''; hint.style.color = ''; return; }
        if (pw.value === cfVal) {
            hint.textContent = 'Passwords match';
            hint.style.color = '#16a34a';
        } else {
            hint.textContent = 'Passwords do not match';
            hint.style.color = '#ef4444';
        }
    }

    // ═══════════════════════════════════════════════════
    //  CITIZENSHIP — populate <select> via JS
    // ═══════════════════════════════════════════════════
    (function populateCitizenship() {
        const citizenships = [
            'Afghan', 'Albanian', 'Algerian', 'American', 'Andorran', 'Angolan', 'Antiguan', 'Argentine',
            'Armenian', 'Australian', 'Austrian', 'Azerbaijani', 'Bahamian', 'Bahraini', 'Bangladeshi',
            'Barbadian', 'Belarusian', 'Belgian', 'Belizean', 'Beninese', 'Bhutanese', 'Bolivian',
            'Bosnian', 'Botswanan', 'Brazilian', 'Bruneian', 'Bulgarian', 'Burkinabé', 'Burundian',
            'Cambodian', 'Cameroonian', 'Canadian', 'Cape Verdean', 'Central African', 'Chadian',
            'Chilean', 'Chinese', 'Colombian', 'Comorian', 'Congolese', 'Costa Rican', 'Croatian',
            'Cuban', 'Cypriot', 'Czech', 'Danish', 'Djiboutian', 'Dominican', 'Dutch', 'East Timorese',
            'Ecuadorian', 'Egyptian', 'Emirati', 'Equatorial Guinean', 'Eritrean', 'Estonian',
            'Eswatini', 'Ethiopian', 'Fijian', 'Finnish', 'French', 'Gabonese', 'Gambian', 'Georgian',
            'German', 'Ghanaian', 'Greek', 'Grenadian', 'Guatemalan', 'Guinean', 'Guinea-Bissauan',
            'Guyanese', 'Haitian', 'Honduran', 'Hungarian', 'I-Kiribati', 'Icelandic', 'Indian',
            'Indonesian', 'Iranian', 'Iraqi', 'Irish', 'Israeli', 'Italian', 'Ivorian', 'Jamaican',
            'Japanese', 'Jordanian', 'Kazakhstani', 'Kenyan', 'Kittitian', 'Korean (North)',
            'Korean (South)', 'Kosovar', 'Kuwaiti', 'Kyrgyzstani', 'Laotian', 'Latvian', 'Lebanese',
            'Lesothan', 'Liberian', 'Libyan', 'Liechtensteiner', 'Lithuanian', 'Luxembourgish',
            'Macedonian', 'Malagasy', 'Malawian', 'Malaysian', 'Maldivian', 'Malian', 'Maltese',
            'Marshallese', 'Mauritanian', 'Mauritian', 'Mexican', 'Micronesian', 'Moldovan',
            'Monégasque', 'Mongolian', 'Montenegrin', 'Moroccan', 'Mozambican', 'Myanmarese',
            'Namibian', 'Nauruan', 'Nepalese', 'New Zealander', 'Nicaraguan', 'Nigerien', 'Nigerian',
            'Norwegian', 'Omani', 'Pakistani', 'Palauan', 'Palestinian', 'Panamanian', 'Papua New Guinean',
            'Paraguayan', 'Peruvian', 'Philippine', 'Polish', 'Portuguese', 'Qatari', 'Romanian',
            'Russian', 'Rwandan', 'Saint Lucian', 'Salvadoran', 'Samoan', 'San Marinese', 'São Toméan',
            'Saudi', 'Senegalese', 'Serbian', 'Seychellois', 'Sierra Leonean', 'Singaporean', 'Slovak',
            'Slovenian', 'Solomon Islander', 'Somali', 'South African', 'South Sudanese', 'Spanish',
            'Sri Lankan', 'Sudanese', 'Surinamese', 'Swedish', 'Swiss', 'Syrian', 'Taiwanese', 'Tajik',
            'Tanzanian', 'Thai', 'Togolese', 'Tongan', 'Trinidadian', 'Tunisian', 'Turkish', 'Turkmen',
            'Tuvaluan', 'Ugandan', 'Ukrainian', 'Uruguayan', 'Uzbekistani', 'Vanuatuan', 'Venezuelan',
            'Vietnamese', 'Vincentian', 'Yemeni', 'Zambian', 'Zimbabwean'
        ];

        const select = document.getElementById('citizenship');
        if (!select) return;

        citizenships.forEach(function (c) {
            const opt = document.createElement('option');
            opt.value = c;
            opt.textContent = c;
            if (c === 'Philippine') opt.selected = true; // default for PH cooperative
            select.appendChild(opt);
        });
    })();
</script>