(function () {
    'use strict';

    let currentStep_form = 0;
    const steps_form = document.querySelectorAll(".form-step");
    const stepper = document.querySelectorAll(".step");
    const nextBtn = document.querySelector(".btn-next");
    const submitBtn = document.querySelector(".btn-submit");
    const OTP_SEND_URL = window.location.origin + '/otp/send';
    const OTP_VERIFY_URL = window.location.origin + '/otp/verify';
    let emailOtpVerified = false;
    let otpAlreadySentEmail = null;

    function updateSteps() {
        steps_form.forEach((step, index) => {
            step.classList.toggle("active", index === currentStep_form);
        });

        stepper.forEach((step, index) => {
            step.classList.remove("active", "completed");
            if (index === currentStep_form) {
                step.classList.add("active");
            } else if (index < currentStep_form) {
                step.classList.add("completed");
            }
        });

        if (currentStep_form === steps_form.length - 1) {
            nextBtn.style.display = "none";
            submitBtn.style.display = "inline-block";
            setTimeout(initSignaturePad, 80);
        } else {
            nextBtn.style.display = "inline-block";
            submitBtn.style.display = "none";
        }
    }

    function goToNextStep() {
        if (currentStep_form < steps_form.length - 1) {
            currentStep_form++;
            updateSteps();
            document.querySelector('.form-box')?.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
    }

    async function sendOtpAndVerify(email) {

        // ✅ Already verified this email — skip entirely
        if (emailOtpVerified && otpAlreadySentEmail === email) {
            return true;
        }

        const overlay = document.getElementById('otp-modal-overlay');
        const confirmBtn = document.getElementById('otp-confirm-btn');
        const resendBtn = document.getElementById('otp-resend-btn');
        const cancelBtn = document.getElementById('otp-cancel-btn');
        const input = document.getElementById('otp-input');
        const error = document.getElementById('otp-error');
        const emailDisp = document.getElementById('otp-email-display');

        if (!overlay || !confirmBtn || !cancelBtn || !input) {
            console.error('OTP modal elements not found in DOM.');
            alert('OTP verification UI is missing. Please contact support.');
            return false;
        }

        // ✅ Send OTP only if not already sent for this email
        if (otpAlreadySentEmail !== email) {
            try {
                const sendRes = await fetch(OTP_SEND_URL, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content
                            || document.querySelector('input[name="_token"]')?.value
                    },
                    body: JSON.stringify({ email })
                });

                if (!sendRes.ok) {
                    const errText = await sendRes.text().catch(() => '');
                    console.error('OTP send failed — status:', sendRes.status, errText);
                    alert('Failed to send verification code (' + sendRes.status + '). Please try again or contact support.');
                    return false;
                }

                otpAlreadySentEmail = email;
            } catch (err) {
                console.error('Network error sending OTP:', err);
                alert('Network error while sending verification code. Please check your connection.');
                return false;
            }
        }

        // ✅ Show modal and wait for user input
        return new Promise((resolve) => {
            if (emailDisp) emailDisp.textContent = email;
            input.value = '';
            if (error) error.textContent = '';
            overlay.style.display = 'flex';

            // ── Confirm ──────────────────────────────────────────────────────
            // Clone and replace to remove any stale onclick listeners
            const freshConfirm = confirmBtn.cloneNode(true);
            confirmBtn.parentNode.replaceChild(freshConfirm, confirmBtn);

            freshConfirm.onclick = async function () {
                const code = input.value.trim();
                if (!code) {
                    if (error) error.textContent = 'Please enter the code.';
                    return;
                }

                freshConfirm.disabled = true;
                freshConfirm.textContent = 'Verifying…';

                try {
                    const res = await fetch(OTP_VERIFY_URL, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content
                                || document.querySelector('input[name="_token"]')?.value
                        },
                        body: JSON.stringify({ otp: code, email })
                    });
                    const data = await res.json();

                    if (data.valid) {
                        emailOtpVerified = true;
                        overlay.style.display = 'none';
                        freshConfirm.disabled = false;
                        freshConfirm.textContent = 'Confirm';
                        resolve(true);
                    } else {
                        if (error) error.textContent = data.message || 'Invalid code.';
                        freshConfirm.disabled = false;
                        freshConfirm.textContent = 'Confirm';
                    }
                } catch (err) {
                    if (error) error.textContent = 'Verification failed. Please try again.';
                    freshConfirm.disabled = false;
                    freshConfirm.textContent = 'Confirm';
                }
            };

            // ── Resend ───────────────────────────────────────────────────────
            const currentResendBtn = document.getElementById('otp-resend-btn');
            if (currentResendBtn) {
                currentResendBtn.onclick = async function () {
                    if (error) { error.style.color = ''; error.textContent = ''; }
                    input.value = '';
                    try {
                        const resendRes = await fetch(OTP_SEND_URL, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content
                                    || document.querySelector('input[name="_token"]')?.value
                            },
                            body: JSON.stringify({ email })
                        });

                        if (!resendRes.ok) {
                            if (error) error.textContent = 'Failed to resend. Server error (' + resendRes.status + ').';
                            return;
                        }

                        otpAlreadySentEmail = email;
                        if (error) {
                            error.style.color = '#16a34a';
                            error.textContent = 'A new code has been sent!';
                            setTimeout(() => { error.style.color = ''; error.textContent = ''; }, 3000);
                        }
                    } catch (e) {
                        if (error) error.textContent = 'Failed to resend. Please try again.';
                    }
                };
            }

            // ── Cancel ───────────────────────────────────────────────────────
            const currentCancelBtn = document.getElementById('otp-cancel-btn');
            currentCancelBtn.onclick = function () {
                overlay.style.display = 'none';
                resolve(false);
            };
        });
    }

    // Call on page load to set initial state
    updateSteps();

    // ── Helper: compute age from a date string ──────────────────────────────
    function computeAge(dateString) {
        if (!dateString) return null;
        const birth = new Date(dateString);
        const today = new Date();
        let age = today.getFullYear() - birth.getFullYear();
        const m = today.getMonth() - birth.getMonth();
        if (m < 0 || (m === 0 && today.getDate() < birth.getDate())) age--;
        return age;
    }

    async function nextStep() {
        const currentFormStep = steps_form[currentStep_form];
        const requiredFields = currentFormStep.querySelectorAll("input[required], select[required]");

        for (let field of requiredFields) {
            if (!field.checkValidity()) {
                field.reportValidity();
                return;
            }
        }

        // ✅ PERSONAL DOB VALIDATION — must be 18+
        const dobInput = currentFormStep.querySelector('#date_birth');
        if (dobInput && dobInput.value) {
            const dobError = document.getElementById('dob_error');
            const age = computeAge(dobInput.value);
            if (age < 18) {
                if (dobError) dobError.textContent = 'You must be at least 18 years old to register.';
                dobInput.classList.add('is-invalid');
                dobInput.scrollIntoView({ behavior: 'smooth', block: 'center' });
                return;
            } else {
                if (dobError) dobError.textContent = '';
                dobInput.classList.remove('is-invalid');
            }
        }

        // ✅ SPOUSE DOB VALIDATION
        const spouseDobInput = currentFormStep.querySelector('#spouse_date_birth');
        if (spouseDobInput && spouseDobInput.value) {
            const spouseDobError = document.getElementById('spouse_dob_error');
            const spouseAge = computeAge(spouseDobInput.value);
            if (spouseAge < 18) {
                if (spouseDobError) spouseDobError.textContent = 'Spouse must be at least 18 years old.';
                spouseDobInput.classList.add('is-invalid');
                spouseDobInput.scrollIntoView({ behavior: 'smooth', block: 'center' });
                return;
            } else {
                if (spouseDobError) spouseDobError.textContent = '';
                spouseDobInput.classList.remove('is-invalid');
            }
        }

        // ✅ PASSWORD MATCH VALIDATION
        const passwordInput = currentFormStep.querySelector('#password');
        const confirmInput = currentFormStep.querySelector('#password_confirmation');
        if (passwordInput && confirmInput) {
            const existingError = currentFormStep.querySelector('#password-match-error');
            if (existingError) existingError.remove();

            if (passwordInput.value !== confirmInput.value) {
                const errorMsg = document.createElement('small');
                errorMsg.id = 'password-match-error';
                errorMsg.style.cssText = 'color: #dc2626; font-size: 12.5px; margin-top: 4px; display: block;';
                errorMsg.textContent = 'Passwords do not match. Please re-enter.';
                confirmInput.classList.add('is-invalid');
                confirmInput.parentElement.insertAdjacentElement('afterend', errorMsg);
                confirmInput.scrollIntoView({ behavior: 'smooth', block: 'center' });
                return;
            } else {
                confirmInput.classList.remove('is-invalid');
            }
        }

        // ✅ PASSWORD STRENGTH VALIDATION
        const strengthBars = currentFormStep.querySelector('#strength-bars');
        if (passwordInput && strengthBars) {
            const score = parseInt(strengthBars.dataset.score || '0', 10);
            const existingStrengthError = currentFormStep.querySelector('#password-strength-error');
            if (existingStrengthError) existingStrengthError.remove();

            if (score < 4) {
                const errorMsg = document.createElement('small');
                errorMsg.id = 'password-strength-error';
                errorMsg.style.cssText = 'color: #dc2626; font-size: 12.5px; margin-top: 4px; display: block;';
                errorMsg.textContent = 'Password is too weak. Please use a Strong or Very Strong password.';
                passwordInput.classList.add('is-invalid');
                const strengthLabel = currentFormStep.querySelector('#strength-label');
                (strengthLabel || passwordInput).insertAdjacentElement('afterend', errorMsg);
                passwordInput.scrollIntoView({ behavior: 'smooth', block: 'center' });
                return;
            } else {
                passwordInput.classList.remove('is-invalid');
            }
        }

        // ✅ GMAIL ONLY CHECK
        const emailInput = currentFormStep.querySelector('#email');
        if (emailInput && emailInput.value) {
            const existingGmailError = currentFormStep.querySelector('#email-gmail-error');
            if (existingGmailError) existingGmailError.remove();

            if (!emailInput.value.toLowerCase().endsWith('@gmail.com')) {
                const errorMsg = document.createElement('small');
                errorMsg.id = 'email-gmail-error';
                errorMsg.style.cssText = 'color: #dc2626; font-size: 12.5px; margin-top: 4px; display: block;';
                errorMsg.textContent = 'Only Gmail addresses are allowed (e.g. example@gmail.com).';
                emailInput.classList.add('is-invalid');
                emailInput.insertAdjacentElement('afterend', errorMsg);
                emailInput.scrollIntoView({ behavior: 'smooth', block: 'center' });
                return;
            }
        }

        // ✅ EMAIL + OTP VERIFICATION — then check if email already exists
        if (emailInput && emailInput.value) {
            const existingEmailError = currentFormStep.querySelector('#email-exists-error');
            if (existingEmailError) existingEmailError.remove();

            // 1️⃣ Verify email via OTP (skipped automatically if already verified)
            const otpVerified = await sendOtpAndVerify(emailInput.value);
            if (!otpVerified) return;   // user cancelled or OTP failed — stay on this step

            // 2️⃣ Check if email already registered
            try {
                const response = await fetch('/check-email', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content
                            || document.querySelector('input[name="_token"]')?.value
                    },
                    body: JSON.stringify({ email: emailInput.value })
                });

                const data = await response.json();

                if (data.exists) {
                    const errorMsg = document.createElement('small');
                    errorMsg.id = 'email-exists-error';
                    errorMsg.style.cssText = 'color: #dc2626; font-size: 12.5px; margin-top: 4px; display: block;';
                    errorMsg.textContent = 'This email is already registered. Please use a different email.';
                    emailInput.classList.add('is-invalid');
                    emailInput.insertAdjacentElement('afterend', errorMsg);
                    emailInput.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    return;
                } else {
                    emailInput.classList.remove('is-invalid');
                }
            } catch (err) {
                console.error('Email check failed:', err);
            }

            // ✅ OTP verified + email is free — advance immediately
            goToNextStep();
            return; // <-- KEY FIX: stop here, don't fall through to signature check
        }

        // ✅ SIGNATURE VALIDATION (only reached on non-email steps)
        const signatureInput = currentFormStep.querySelector('#signature');
        const signatureError = document.getElementById('signature-error');
        if (signatureInput !== null && signatureInput.value === '') {
            if (signatureError) {
                signatureError.style.display = 'block';
                signatureError.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
            return;
        } else if (signatureError) {
            signatureError.style.display = 'none';
        }

        // ✅ All validations passed — go to next step
        goToNextStep();
    }

    function prevStep() {
        if (currentStep_form > 0) {
            currentStep_form--;
            updateSteps();
            document.querySelector('.form-box')?.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
    }

    // ✅ Block form submit if no signature
    document.querySelector('form')?.addEventListener('submit', function (e) {
        const signatureInput = document.getElementById('signature');
        const signatureError = document.getElementById('signature-error');
        if (signatureInput && signatureInput.value === '') {
            e.preventDefault();
            if (signatureError) {
                signatureError.style.display = 'block';
                signatureError.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
            return false;
        }
    });

    /* ══════════════════════════════════════
       LIVE INPUT ERROR CLEARING
    ══════════════════════════════════════ */
    document.addEventListener('input', function (e) {

        if (e.target.id === 'password') {
            const score = parseInt(document.getElementById('strength-bars')?.dataset.score || '0', 10);
            const strengthErr = document.getElementById('password-strength-error');
            if (score >= 4 || e.target.value.length === 0) {
                e.target.classList.remove('is-invalid');
                if (strengthErr) strengthErr.remove();
            }
            const pw = document.getElementById('password');
            const cpw = document.getElementById('password_confirmation');
            const matchErr = document.getElementById('password-match-error');
            if (pw && cpw && pw.value === cpw.value) {
                cpw.classList.remove('is-invalid');
                if (matchErr) matchErr.remove();
            }
        }

        if (e.target.id === 'password_confirmation') {
            const pw = document.getElementById('password');
            const cpw = document.getElementById('password_confirmation');
            const err = document.getElementById('password-match-error');
            if (pw && cpw && pw.value === cpw.value) {
                cpw.classList.remove('is-invalid');
                if (err) err.remove();
            }
        }

        if (e.target.id === 'spouse_date_birth') {
            const spouseDobError = document.getElementById('spouse_dob_error');
            const age = computeAge(e.target.value);
            if (!e.target.value || age >= 18) {
                if (spouseDobError) spouseDobError.textContent = '';
                e.target.classList.remove('is-invalid');
            }
        }

        if (e.target.id === 'date_birth') {
            const dobError = document.getElementById('dob_error');
            const age = computeAge(e.target.value);
            if (!e.target.value || age >= 18) {
                if (dobError) dobError.textContent = '';
                e.target.classList.remove('is-invalid');
            }
        }

        if (e.target.id === 'email') {
            emailOtpVerified = false;
            otpAlreadySentEmail = null;
            const emailVal = e.target.value;
            const existsErr = document.getElementById('email-exists-error');
            let gmailErr = document.getElementById('email-gmail-error');

            if (existsErr) existsErr.remove();
            e.target.classList.remove('is-invalid');

            if (emailVal && !emailVal.toLowerCase().endsWith('@gmail.com')) {
                if (!gmailErr) {
                    gmailErr = document.createElement('small');
                    gmailErr.id = 'email-gmail-error';
                    gmailErr.style.cssText = 'color: #dc2626; font-size: 12.5px; margin-top: 4px; display: block;';
                    gmailErr.textContent = 'Only Gmail addresses are allowed (e.g. example@gmail.com).';
                    e.target.insertAdjacentElement('afterend', gmailErr);
                }
                e.target.classList.add('is-invalid');
            } else {
                if (gmailErr) gmailErr.remove();
                e.target.classList.remove('is-invalid');
            }
        }
    });

    /* ══════════════════════════════════════
       SIGNATURE PAD — using SignaturePad library
    ══════════════════════════════════════ */
    let signaturePadMain = null;
    let signaturePadModal = null;

    function initSignaturePad() {
        const canvas = document.getElementById('signature-pad');
        if (!canvas || canvas._sigInitialized) return;
        canvas._sigInitialized = true;

        canvas.width = canvas.offsetWidth;
        canvas.height = canvas.offsetHeight || 160;

        signaturePadMain = new SignaturePad(canvas, {
            minWidth: 1,
            maxWidth: 3,
            penColor: '#1a1a1a',
        });

        signaturePadMain.addEventListener('endStroke', () => {
            document.getElementById('signature').value = signaturePadMain.toDataURL();
            const err = document.getElementById('signature-error');
            if (err) err.style.display = 'none';
        });

        document.getElementById('clear')?.addEventListener('click', () => {
            signaturePadMain.clear();
            document.getElementById('signature').value = '';
        });
    }

    /* ══════════════════════════════════════
       SIGNATURE MODAL — fullscreen
    ══════════════════════════════════════ */
    function openSignatureModal() {
        const overlay = document.getElementById('signature-modal-overlay');
        overlay.style.display = 'flex';

        setTimeout(() => {
            const canvas = document.getElementById('signature-pad-modal');
            canvas.width = canvas.offsetWidth;
            canvas.height = canvas.offsetHeight || 300;

            signaturePadModal = new SignaturePad(canvas, {
                minWidth: 1.5,
                maxWidth: 3.5,
                penColor: '#1a1a1a',
            });

            const existing = document.getElementById('signature').value;
            if (existing && existing.startsWith('data:')) {
                signaturePadModal.fromDataURL(existing);
            }
        }, 80);
    }

    function clearModalSignature() {
        if (signaturePadModal) signaturePadModal.clear();
    }

    function saveModalSignature() {
        if (!signaturePadModal || signaturePadModal.isEmpty()) {
            alert('Please draw your signature first.');
            return;
        }

        const dataUrl = signaturePadModal.toDataURL();
        if (signaturePadMain) signaturePadMain.fromDataURL(dataUrl);

        document.getElementById('signature').value = dataUrl;

        const err = document.getElementById('signature-error');
        if (err) err.style.display = 'none';

        document.getElementById('signature-modal-overlay').style.display = 'none';
    }

    document.getElementById('signature-modal-overlay')?.addEventListener('click', function (e) {
        if (e.target === this) this.style.display = 'none';
    });

    // ✅ Expose functions called via HTML onclick="..."
    window.nextStep = nextStep;
    window.prevStep = prevStep;
    window.openSignatureModal = openSignatureModal;
    window.clearModalSignature = clearModalSignature;
    window.saveModalSignature = saveModalSignature;

})();