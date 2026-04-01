const first_name = document.getElementById("first_name");
const middle_name = document.getElementById("middle_name");
const last_name = document.getElementById("last_name");
const username = document.getElementById("username");
const date_birth = document.getElementById("date_birth");
const place_birth = document.getElementById("place_birth");
const email = document.getElementById("email");
const member_type = document.getElementById("select_type");
const sex = document.getElementById("sex");
const citizenship = document.getElementById("citizenship");
const skills_expertise = document.getElementById("skills_expertise");
// const tin_no = document.getElementById("tin_no");

const spouse_name = document.getElementById("spouse_name");
const spouse_date_birth = document.getElementById("spouse_date_birth");
const spouse_place_birth = document.getElementById("spouse_place_birth");

const number_son = document.getElementById("number_son");
const number_daughter = document.getElementById("number_daughter");
const other_spec = document.getElementById("other_spec");

// const uv = document.getElementById("uv");
// const taxi = document.getElementById("taxi");
// const bus = document.getElementById("bus");
// const tricycle = document.getElementById("tricycle");
// const mini_bus = document.getElementById("mini_bus");
// const jeep = document.getElementById("jeep");
// const multi_cab = document.getElementById("multi_cab");
// const other_info_specify = document.getElementById("other_info_specify");


// personal details

// let currentStep = 0;
// const steps = document.querySelectorAll('.step');
// const formSteps = document.querySelectorAll('.form-step');

// function updateStepper() {
//     steps.forEach((step, index) => {
//         step.classList.remove('active', 'completed');
//         if (index === currentStep) {
//             step.classList.add('active');
//         } else if (index < currentStep) {
//             step.classList.add('completed');
//         }
//     });
// }

// function nextStep() {
//     if (currentStep < steps.length - 1) {
//         formSteps[currentStep].classList.remove('active');
//         currentStep++;
//         formSteps[currentStep].classList.add('active');
//         updateStepper();
//     }
// }

// function prevStep() {
//     if (currentStep > 0) {
//         formSteps[currentStep].classList.remove('active');
//         currentStep--;
//         formSteps[currentStep].classList.add('active');
//         updateStepper();
//     }
// }

first_name.addEventListener("input", () => {
    document.getElementById("firstname_display").textContent = first_name.value;
});

middle_name.addEventListener("input", () => {
    document.getElementById("middlename_display").textContent = middle_name.value;
});

last_name.addEventListener("input", () => {
    document.getElementById("lastname_display").textContent = last_name.value;
});

username.addEventListener("input", () => {
    document.getElementById("username_display").textContent = username.value;
});

date_birth.addEventListener("input", () => {
    document.getElementById("date_birth_display").textContent = date_birth.value;
});

place_birth.addEventListener("input", () => {
    document.getElementById("place_birth_display").textContent = place_birth.value;
});

email.addEventListener("input", () => {
    document.getElementById("email_display").textContent = email.value;
});

member_type.addEventListener("input", () => {
    document.getElementById("membership_type_display").textContent = member_type.value;
});

sex.addEventListener("input", () => {
    document.getElementById("sex_display").textContent = sex.value;
});


citizenship.addEventListener("input", () => {
    document.getElementById("citizenship_display").textContent = citizenship.value;
});

skills_expertise.addEventListener("input", () => {
    document.getElementById("skills_display").textContent = skills_expertise.value;
});

// Spouse

spouse_name.addEventListener("input", () => {
    document.getElementById("spouse_name_display").textContent = spouse_name.value;
});

spouse_date_birth.addEventListener("input", () => {
    document.getElementById("spouse_date_birth_display").textContent = spouse_date_birth.value;
});

spouse_place_birth.addEventListener("input", () => {
    document.getElementById("spouse_place_birth_display").textContent = spouse_place_birth.value;
});

number_son.addEventListener("input", () => {
    document.getElementById("son_display").textContent = number_son.value;
});

number_daughter.addEventListener("input", () => {
    document.getElementById("daughter_display").textContent = number_daughter.value;
});

other_spec.addEventListener("input", () => {
    document.getElementById("other_spec_display").textContent = other_spec.value;
});

// vehicle

// uv.addEventListener("input", () => {
//     document.getElementById("uv_display").textContent = uv.value;
// });

// taxi.addEventListener("input", () => {
//     document.getElementById("taxi_display").textContent = taxi.value;
// });

// bus.addEventListener("input", () => {
//     document.getElementById("bus_display").textContent = bus.value;
// });

// tricycle.addEventListener("input", () => {
//     document.getElementById("tricycle_display").textContent = tricycle.value;
// });

// mini_bus.addEventListener("input", () => {
//     document.getElementById("mini_bus_display").textContent = mini_bus.value;
// });

// jeep.addEventListener("input", () => {
//     document.getElementById("jeep_display").textContent = jeep.value;
// });

// multi_cab.addEventListener("input", () => {
//     document.getElementById("multi_cab_display").textContent = multi_cab.value;
// });

// other_info_specify.addEventListener("input", () => {
//     document.getElementById("vehi_other_spec").textContent = other_info_specify.value;
// });