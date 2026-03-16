<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link rel="icon" href="images/websitelogo.png" type="image/png">

    {{-- AOS animation link css --}}
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

    {{-- css link --}}
    <link rel="stylesheet" href="css_folder/loan_application.css">
    <link rel="stylesheet" href="css_folder/loading.css">

    {{-- bootstrap and tailwind link --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- font awesome cdn link --}}
    <link rel="stylesheet" href="../font-awesome-icon/css/all.min.css">

    <script>
        function closeSuccessModal() {
            document.getElementById('success-modal').style.display = 'none';
        }
    </script>

</head>

<body>

    <div class="container-fluid m-0 p-0">
        @include("components.navbar2")
        @include("components.offcanvas")

        <!-- Interest Rates Modal -->
        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <div class="modal-text">
                            <h1>Interest Rates</h1>
                            <p>Per loan type - monthly basis</p>
                        </div>
                        <button type="button" data-bs-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <div class="reminders">
                            <p>💡 Rates are indicative. Final terms subject to credit evaluation and cooperative
                                approval.</p>
                        </div>

                        <div class="lending-parent">
                            <div class="lending-icon">
                                <p>Personal Lending</p>
                                <span>General personal expenses & needs</span>
                            </div>
                            <p>1.5% / mo</p>
                        </div>

                        <div class="lending-parent">
                            <div class="lending-icon">
                                <p>Business Lending</p>
                                <span>Livelihood & enterprise capital</span>
                            </div>
                            <p>1.2% / mo</p>
                        </div>

                        <div class="lending-parent">
                            <div class="lending-icon">
                                <p>Emergency Lending</p>
                                <span>Urgent medical, calamity & crisis needs</span>
                            </div>
                            <p>1.0% / mo</p>
                        </div>

                        <div class="lending-parent">
                            <div class="lending-icon">
                                <p>Educational Lending</p>
                                <span>Tuition, school fees & supplies</span>
                            </div>
                            <p>0.8% / mo</p>
                        </div>
                    </div>
                    <hr>
                    <div class="modal-footer">
                        <button type="button" class="btn" data-bs-dismiss="modal">Got it, Close</button>
                    </div>
                </div>
            </div>
        </div>

        <main>
            <div class="card-box">
                <div class="d-flex justify-content-left align-items-center gap-4">
                    <div class="tw:w-[55px] tw:h-[55px] card-icon" style="border-radius: 10px">
                        <i class="fa-solid fa-file"></i>
                    </div>
                    <div class="d-flex justify-content-between align-items-center w-100">
                        <div>
                            <h3>Lending Applications</h3>
                            <p style="margin: 0px;" class="tw:text-[#808080]">Fill out the form below to apply for a
                                lending
                            </p>
                        </div>

                        <div class="alert-reminder">
                            <button style="button" data-bs-toggle="modal" data-bs-target="#exampleModal"><i
                                    class="fa-solid fa-triangle-exclamation"></i></button>
                        </div>
                    </div>
                </div>

                <form action="{{ route("lendingProgram") }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="row form-parent">
                        <div class="col-lg-6 col-md-12 mt-4">
                            <label>Lending Type *</label>
                            <select name="lending_type" class="form-select mt-2" onchange="recalculate()" required>
                                <option value="">Select lending type</option>
                                <option value="Personal Loan">Personal Loan</option>
                                <option value="Emergency Loan">Emergency Loan</option>
                                <option value="Home Loan">Home Loan</option>
                                <option value="Car Loan">Car Loan</option>
                                <option value="Education Loan">Education Loan</option>
                            </select>
                        </div>

                        <div class="col-lg-6 col-md-12 mt-md-4 mt-sm-4 loan-input">
                            <label>Lending Amount (₱) *</label>
                            <input type="number" name="lending_amount" placeholder="Enter amount"
                                class="form-control mt-2" oninput="recalculate()"
                                onkeydown="if(event.key === 'e' || event.key === 'E' || event.key === '+' || event.key === '-') event.preventDefault();"
                                required>
                        </div>

                        <div class="col-lg-6 col-md-12 mt-4">
                            <label>Lending Term *</label>
                            <select name="lending_type_term" class="form-select mt-2" onchange="recalculate()" required>
                                <option value="">Select lending term</option>
                                <option value="3 months">3 months</option>
                                <option value="6 months">6 months</option>
                                <option value="12 months">12 months</option>
                                <option value="24 months">24 months</option>
                                <option value="36 months">36 months</option>
                            </select>
                        </div>

                        <div class="col-lg-6 col-md-12 mt-4">
                            <label>Enter Monthly Income (₱) *</label>
                            <input type="number" name="monthly_income" placeholder="Enter monthly income"
                                class="form-control mt-2"
                                onkeydown="if(event.key === 'e' || event.key === 'E' || event.key === '+' || event.key === '-') event.preventDefault();"
                                required>
                        </div>

                        <div class="col-12 mt-5">
                            <label>Purpose of Lending *</label>
                            <textarea name="purpose_loan" class="form-control mt-2 p-3 tw:w-[100%]"
                                placeholder="Describe the purpose of your lending..." style="font-size: 15.5px;"
                                required></textarea>
                        </div>

                        <div class="row" style="padding: 0 0 0 12px;">
                            <div class="col-lg-12" style="padding: 0 0 0 12px">
                                <div class="line"></div>
                            </div>
                        </div>

                        <h4>Supporting Documents</h4>

                        <div class="col-lg-6 mt-4">
                            <label>Valid ID</label>
                            <input type="file" name="valid_id" id="" class="form-control mt-2">
                        </div>

                        <div class="col-lg-6 mt-4">
                            <label>Proof of Income</label>
                            <input type="file" name="proof_of_income" id="" class="form-control mt-2">
                        </div>

                        <div class="row" style="padding: 0 0 0 12px;">
                            <div class="col-lg-12" style="padding: 0 0 0 12px">
                                <div class="line"></div>
                            </div>
                        </div>

                        {{-- Hidden inputs INSIDE the form so calculated values are submitted --}}
                        <input type="hidden" name="monthly_payment" id="hidden-monthly">
                        <input type="hidden" name="total_payment" id="hidden-total">
                        <input type="hidden" name="total_interest" id="hidden-interest">

                        <div class="col-12 mt-5 mt-md-4">
                            <button type="submit" class="tw:w-[100%] btn">Submit Application</button>
                        </div>
                    </div>
                </form>
            </div>

            <div class="card-calculate-parent">
                <div class="card-calculate">
                    <div class="calculate-header">
                        <h3>Lending Calculator</h3>
                        <p>Auto Computes as you fill the form</p>
                    </div>
                    <div class="calculate-body">
                        <div class="estimate">
                            <p>Estimated Monthly Payment</p>
                            <div class="payment">
                                <h4 id="calc-monthly">₱-</h4>
                            </div>
                            <p id="calc-term-sub">Fill in amount & term to compute.</p>
                        </div>

                        <div class="parent-amount">
                            <div class="card-amount">
                                <div class="text-amount">
                                    <p>Lending Type</p>
                                </div>
                                <div class="amount">
                                    <p id="calc-type">-</p>
                                </div>
                            </div>

                            <div class="card-amount">
                                <div class="text-amount">
                                    <p>Lending Amount</p>
                                </div>
                                <div class="amount">
                                    <p id="calc-amount">-</p>
                                </div>
                            </div>

                            <div class="card-amount">
                                <div class="text-amount">
                                    <p>Interest Rate (monthly)</p>
                                </div>
                                <div class="amount">
                                    <p id="calc-rate">-</p>
                                </div>
                            </div>

                            <div class="card-amount">
                                <div class="text-amount">
                                    <p>Lending Term</p>
                                </div>
                                <div class="amount">
                                    <p id="calc-term">-</p>
                                </div>
                            </div>

                            <hr>

                            <div class="card-amount total-amount">
                                <div class="text-amount">
                                    <p>Total Payment</p>
                                </div>
                                <div class="amount">
                                    <p id="calc-total">-</p>
                                </div>
                            </div>

                            <div class="card-amount">
                                <div class="text-amount">
                                    <p>Total Interest</p>
                                </div>
                                <div class="amount">
                                    <p id="calc-interest">-</p>
                                </div>
                            </div>

                            <div class="reminders">
                                <p>💡 Rates are indicative. Final terms subject to credit evaluation and cooperative
                                    approval.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>

        @if(session("ApplySuccess"))
            <div class="modal-overlay-success" id="success-modal">
                <div class="success-modal-box">
                    <div class="sm-head">
                        <div class="sm-icon">✅</div>
                    </div>
                    <div class="sm-body">
                        <h2>Application Submitted!</h2>
                        <p>Your lending application has been received and is now under review. We'll notify you within 3–5
                            business days.</p>
                        <div class="sm-details">
                            <div class="sm-row">
                                <span class="sm-label">Status</span>
                                <span class="sm-badge">Pending Review</span>
                            </div>
                            <div class="sm-row">
                                <span class="sm-label">Reference</span>
                                <span class="sm-val">#{{ session("ReferenceNo") }}</span>
                            </div>
                            <div class="sm-row">
                                <span class="sm-label">Date Filed</span>
                                <span class="sm-val">{{ session("DateFiled") }}</span>
                            </div>
                        </div>
                        <button class="sm-btn" onclick="closeSuccessModal()">Got it, Continue</button>
                    </div>
                </div>
            </div>
        @endif

    </div>

    {{-- AOS animation link js --}}
    {{-- <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script> --}}

    <script>
        AOS.init();
    </script>

    <script>
        const RATES = {
            'Personal Loan':  0.015,
            'Emergency Loan': 0.010,
            'Home Loan':      0.013,
            'Car Loan':       0.013,
            'Education Loan': 0.008,
        };

        function recalculate() {
            const type    = document.querySelector('[name="lending_type"]').value;
            const amount  = parseFloat(document.querySelector('[name="lending_amount"]').value);
            const termRaw = document.querySelector('[name="lending_type_term"]').value;
            const term    = parseInt(termRaw);


            document.getElementById('calc-type').textContent = type || '-';


            if (!type || !amount || !term || amount <= 0) {
                document.getElementById('calc-monthly').textContent  = '₱-';
                document.getElementById('calc-term-sub').textContent = 'Fill in amount & term to compute.';
                document.getElementById('calc-amount').textContent   = amount > 0 ? '₱' + fmt(amount) : '-';
                document.getElementById('calc-rate').textContent     = type && RATES[type] ? (RATES[type] * 100).toFixed(1) + '% / mo' : '-';
                document.getElementById('calc-term').textContent     = term ? termRaw : '-';
                document.getElementById('calc-total').textContent    = '-';
                document.getElementById('calc-interest').textContent = '-';
                return;
            }

            const r       = RATES[type] ?? 0.015;

            const monthly  = amount * r * Math.pow(1 + r, term) / (Math.pow(1 + r, term) - 1);
            const total    = monthly * term;
            const interest = total - amount;

            document.getElementById('calc-monthly').textContent  = '₱' + fmt(monthly);
            document.getElementById('calc-term-sub').textContent = 'Over ' + termRaw;
            document.getElementById('calc-amount').textContent   = '₱' + fmt(amount);
            document.getElementById('calc-rate').textContent     = (r * 100).toFixed(1) + '% / mo';
            document.getElementById('calc-term').textContent     = termRaw;
            document.getElementById('calc-total').textContent    = '₱' + fmt(total);
            document.getElementById('calc-interest').textContent = '₱' + fmt(interest);


            document.getElementById('hidden-monthly').value  = monthly.toFixed(2);
            document.getElementById('hidden-total').value    = total.toFixed(2);
            document.getElementById('hidden-interest').value = interest.toFixed(2);
        }

        function fmt(n) {
            return n.toLocaleString('en-PH', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        }
    </script>

</body>

</html>