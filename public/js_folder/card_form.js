let currentStep_form = 0;
const steps_form = document.querySelectorAll(".form-step");
const stepper = document.querySelectorAll(".step");
const nextBtn = document.querySelector(".btn-next");
const submitBtn = document.querySelector(".btn-submit");

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
    } else {
        nextBtn.style.display = "inline-block";
        submitBtn.style.display = "none";
    }
}

// Call on page load to set initial state
updateSteps();

function nextStep() {
    const currentFormStep = steps_form[currentStep_form];
    const requiredFields = currentFormStep.querySelectorAll("input[required], select[required]");

    for (let field of requiredFields) {
        if (!field.checkValidity()) {
            field.reportValidity();
            return;
        }
    }

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