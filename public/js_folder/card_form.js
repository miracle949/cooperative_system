let currentStep = 0;
const steps = document.querySelectorAll(".form-step");
const stepper = document.querySelectorAll(".step");
const nextBtn = document.querySelector(".btn-next");
const submitBtn = document.querySelector(".btn-submit");

function updateSteps() {
    steps.forEach((step, index) => {
        step.classList.toggle("active", index === currentStep);
        stepper[index].classList.toggle("active", index === currentStep);
    });

    if (currentStep === steps.length - 1) {
        nextBtn.style.display = "none";
        submitBtn.style.display = "inline-block";
    } else {
        nextBtn.style.display = "inline-block";
        submitBtn.style.display = "none";
    }
}

function nextStep() {
    const currentFormStep = steps[currentStep];

    // get all required inputs & selects in CURRENT step only
    const requiredFields = currentFormStep.querySelectorAll("input[required], select[required]");

    for (let field of requiredFields) {
        if (!field.checkValidity()) {
            field.reportValidity(); // show browser validation message
            return; // STOP going to next step
        }
    }

    // if all fields are valid → go next
    if (currentStep < steps.length - 1) {
        currentStep++;
        updateSteps();
    }
}

function prevStep() {
    if (currentStep > 0) {
        currentStep--;
        updateSteps();
    }
}