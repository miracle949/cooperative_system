let currentStep_form = 0;
const steps_form = document.querySelectorAll(".form-step");
const stepper = document.querySelectorAll(".step");
const nextBtn = document.querySelector(".btn-next");
const submitBtn = document.querySelector(".btn-submit");
const OTP_SEND_URL   = window.location.origin + '/otp/send';
const OTP_VERIFY_URL = window.location.origin + '/otp/verify';
// let emailOtpVerified = false;      
// let otpAlreadySentEmail = null;

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
        // ✅ Init signature pad when last step becomes visible
        setTimeout(initSignaturePad, 80);
    } else {
        nextBtn.style.display = "inline-block";
        submitBtn.style.display = "none";
    }
}

async function sendOtpAndVerify(email) {

    // ✅ Already verified this email — skip entirely
    if (emailOtpVerified && otpAlreadySentEmail === email) {
        return true;
    }

    // ✅ Send OTP only if not already sent for this email
    if (otpAlreadySentEmail !== email) {
        try {
            await fetch(OTP_SEND_URL, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content
                        || document.querySelector('input[name="_token"]')?.value
                },
                body: JSON.stringify({ email })
            });
            otpAlreadySentEmail = email;  // mark as sent
        } catch (err) {
            console.error('Failed to send OTP:', err);
            return false;
        }
    }

    // ✅ Show modal and wait for user to confirm
    return new Promise((resolve) => {
        const overlay = document.getElementById('otp-modal-overlay');
        const input = document.getElementById('otp-input');
        const error = document.getElementById('otp-error');
        const emailDisp = document.getElementById('otp-email-display');

        if (emailDisp) emailDisp.textContent = email;
        if (input) input.value = '';
        if (error) error.textContent = '';
        overlay.style.display = 'flex';

        // Confirm
        document.getElementById('otp-confirm-btn').onclick = async function () {
            const code = input.value.trim();
            if (!code) { error.textContent = 'Please enter the code.'; return; }

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
                    emailOtpVerified = true;   // ✅ mark verified
                    overlay.style.display = 'none';
                    resolve(true);
                } else {
                    error.textContent = data.message || 'Invalid code.';
                }
            } catch (err) {
                error.textContent = 'Verification failed. Please try again.';
            }
        };

        // Resend — allowed within the same modal session
        document.getElementById('otp-resend-btn').onclick = async function () {
            error.style.color = '';
            error.textContent = '';
            input.value = '';
            try {
                await fetch(OTP_SEND_URL, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content
                            || document.querySelector('input[name="_token"]')?.value
                    },
                    body: JSON.stringify({ email })
                });
                error.style.color = '#16a34a';
                error.textContent = 'A new code has been sent!';
                setTimeout(() => { error.style.color = ''; error.textContent = ''; }, 3000);
            } catch (e) {
                error.textContent = 'Failed to resend. Please try again.';
            }
        };

        // Cancel
        document.getElementById('otp-cancel-btn').onclick = function () {
            overlay.style.display = 'none';
            resolve(false);
        };
    });
}

// Call on page load to set initial state
updateSteps();

// ── Helper: compute age from a date string ─────────────────────────────────
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

    // ✅ Check required fields first
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

    // ✅ SPOUSE DOB VALIDATION — if filled, must also be 18+
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

    // ✅ PASSWORD STRENGTH VALIDATION — must be Strong or Very Strong (score >= 4)
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

    // ✅ EMAIL EXISTS VALIDATION
    if (emailInput && emailInput.value) {
        const existingEmailError = currentFormStep.querySelector('#email-exists-error');
        if (existingEmailError) existingEmailError.remove();

        // Send OTP and show modal
        const otpVerified = await sendOtpAndVerify(emailInput.value);
        if (!otpVerified) return;

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
    }

    // ✅ SIGNATURE VALIDATION
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

    // ✅ Proceed to next step
    if (currentStep_form < steps_form.length - 1) {
        currentStep_form++;
        updateSteps();
    }
}

function prevStep() {
    if (currentStep_form > 0) {
        currentStep_form--;
        updateSteps();
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

    // Clear password strength error + password match error when password changes
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

    // Clear password match error when confirm password changes
    if (e.target.id === 'password_confirmation') {
        const pw = document.getElementById('password');
        const cpw = document.getElementById('password_confirmation');
        const err = document.getElementById('password-match-error');
        if (pw && cpw && pw.value === cpw.value) {
            cpw.classList.remove('is-invalid');
            if (err) err.remove();
        }
    }

    // Clear spouse DOB error on change
    if (e.target.id === 'spouse_date_birth') {
        const spouseDobError = document.getElementById('spouse_dob_error');
        const age = computeAge(e.target.value);
        if (!e.target.value || age >= 18) {
            if (spouseDobError) spouseDobError.textContent = '';
            e.target.classList.remove('is-invalid');
        }
    }

    // Clear personal DOB error on change
    if (e.target.id === 'date_birth') {
        const dobError = document.getElementById('dob_error');
        const age = computeAge(e.target.value);
        if (!e.target.value || age >= 18) {
            if (dobError) dobError.textContent = '';
            e.target.classList.remove('is-invalid');
        }
    }

    // Clear email errors + live gmail check
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

    if (signaturePadMain) {
        signaturePadMain.fromDataURL(dataUrl);
    }

    document.getElementById('signature').value = dataUrl;

    const err = document.getElementById('signature-error');
    if (err) err.style.display = 'none';

    document.getElementById('signature-modal-overlay').style.display = 'none';
}

// Close modal on overlay background click
document.getElementById('signature-modal-overlay')?.addEventListener('click', function (e) {
    if (e.target === this) this.style.display = 'none';
});