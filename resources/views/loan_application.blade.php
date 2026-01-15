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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css"
        integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    {{-- bootstrap link --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
</head>

<body>

    <div class="container-fluid m-0 p-0">
        @include("navbar2")
        @include("offcanvas")

        <main>
            <div class="card-box">
                <div class="d-flex justify-content-left align-items-center gap-4">
                    <div class="tw:w-[55px] tw:h-[55px] card-icon"
                        style="border-radius: 10px">
                        <i class="fa-solid fa-file"></i>
                    </div>
                    <div>
                        <h3>Loan Applications</h3>
                        <p style="margin: 0px;" class="tw:text-[#808080]">Fill out the form below to apply for a loan
                        </p>
                    </div>
                </div>

                <div class="row form-parent">
                    <div class="col-lg-6 col-md-12 mt-4">
                        <label>Loan Type *</label>
                        <select name="" class="form-control mt-2" style="font-size: 15.5px;">
                            <option value="">Select loan type</option>
                            <option value="">Personal Loan</option>
                            <option value="">Emergency Loan</option>
                            <option value="">Home Loan</option>
                            <option value="">Car Loan</option>
                            <option value="">Education Loan</option>
                        </select>
                    </div>

                    <div class="col-lg-6 col-md-12 mt-md-4 mt-sm-4 loan-input">
                        <label>Loan Amount (₱) *</label>
                        <input type="text" name="" placeholder="Enter amount" class="form-control mt-2"
                            style="font-size: 15.5px;">
                    </div>

                    <div class="col-lg-6 col-md-12 mt-4">
                        <label>Loan Type *</label>
                        <select name="" class="form-control mt-2" style="font-size: 15.5px;">
                            <option value="">Select loan term</option>
                            <option value="">3 months</option>
                            <option value="">6 months</option>
                            <option value="">12 months</option>
                            <option value="">24 months</option>
                            <option value="">36 months</option>
                        </select>
                    </div>

                    <div class="col-lg-6 col-md-12 mt-4">
                        <label>Enter Monthly Income (₱) *</label>
                        <input type="text" name="" placeholder="Enter monthly income" class="form-control mt-2"
                            style="font-size: 15.5px;">
                    </div>

                    <div class="col-12 mt-5">
                        <label>Purpose of Loan *</label>
                        <textarea name="" class="form-control mt-2 p-3 tw:w-[100%]"
                            placeholder="Describe the purpose of your loan..." style="font-size: 15.5px;"></textarea>
                    </div>

                    <div class="col-12 mt-5 mt-md-4">
                        <button class="tw:w-[100%] btn btn-dark">Submit Application</button>
                    </div>
                </div>
            </div>
        </main>
    </div>

    {{-- AOS animation link js --}}
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>

    {{-- Bootstrap link --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
        crossorigin="anonymous"></script>

    <script>
        AOS.init();
    </script>
</body>

</html>