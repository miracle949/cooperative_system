<?php

use App\Http\Controllers\PaymentController;
use App\Http\Controllers\lendingController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UsersHandle;
use App\Http\Controllers\SavingsController;
use Illuminate\Support\Facades\Route;

Route::get("/", [UserController::class, "UserDirection"]);

Route::get("/landing-page", [UserController::class, "Landingpage"])->name("Landingpage");

// Login page GET

Route::get("/login-page", [UserController::class, "LoginPage"])->name("LoginPage");

// Register page GET

Route::get("/register-page", [UserController::class, "RegisterPage"])->name("RegisterPage");

// About us page GET

Route::get("/about-us", [UserController::class, "AboutUs"])->name("AboutUs");

// Services page GET

Route::get("/services", [UserController::class, "ServicesPage"])->name("ServicesPage");

// // Blogs page GET

Route::get("/blogs", [UserController::class, "BlogsPage"])->name("BlogsPage");

// Contact page GET

Route::get("/contact", [UserController::class, "ContactPage"])->name("ContactPage");

Route::get("/navbar", [UserController::class, "Navbar"]);

// Static page GET

Route::get("/static-page", [UserController::class, "StaticPage"])->name("StaticPage");

// Member Portal page GET

Route::get("/member-portal", [UsersHandle::class, "MemberPortal"])->name("MemberPortal")->middleware("auth");

// Lending Program page GET

Route::get("/loan_application", [UsersHandle::class, "LoanApplication"])->name("LoanApplication");

// Savings page GET

// Route::get("/savings-page", [UsersHandle::class, "Savings"])->name("Savings");

// NEW — savings routes
Route::get("/savings-page", [SavingsController::class, "index"])->name("savings.index")->middleware("auth");

Route::post("/savings/deposit", [SavingsController::class, "deposit"])->name("savings.deposit")->middleware("auth");

Route::post("/savings/withdraw", [SavingsController::class, "withdraw"])->name("savings.withdraw")->middleware("auth");

// Share Capital page GET

Route::get('/share-capital', [UsersHandle::class, "ShareCapital"])->name("ShareCapital");

// Loan Status page GET

Route::get("/loan-status", [UsersHandle::class, "LoanStatus"])->name("LoanStatus");

// Profile Member page GET

Route::get("/profile-member", [UsersHandle::class, "ProfileMember"])->name("ProfileMember");

// Driver Portal page GET

Route::get("/driver-portal", [UserController::class, "DriverPortal"])->name("DriverPortal");

// Login Handle page POST
Route::post("/login-handle", [UsersHandle::class, "login"])->name("UserLogin");

// User Handle page GET

Route::get("/user-handle", [UsersHandle::class, "UserHandle"])->name("UserHandle")->middleware("auth");

// Registration page POST

Route::post("/registration", [UsersHandle::class, "registration"])->name("registration");

// Lending Program page GET

Route::get("/login", [UsersHandle::class, "login"])->name("login");

Route::get('/approve-user/{id}', [UserController::class, 'approveUser'])->name('approve.user');

// Application form - only ONE GET, ONE POST
Route::get('/application-form/{id}', [UserController::class, 'showForm'])->name('applicationForm');

Route::post('/application-form/{id}', [UsersHandle::class, 'applicationFormButton'])->name('applicationFormButton');

Route::get("/nav-bar2", [UsersHandle::class, "Navbar2"])->name("Navbar2");

Route::get("/logout", [UsersHandle::class, "logout"])->name("logout");

Route::post("/lending-program", [lendingController::class, "lendingProgram"])
    ->name("lendingProgram")
    ->middleware("auth");

Route::get('/savings/receipt/{referenceNo}', [SavingsController::class, 'downloadReceipt'])
    ->name('savings.receipt');

Route::get('/loan-status', [lendingController::class, 'loanStatus'])
    ->name('LoanStatus')
    ->middleware('auth');

Route::post('/repayment/store', [lendingController::class, 'storeRepayment'])
    ->name('repayment.store')
    ->middleware('auth');

// Gcash API 

Route::middleware('auth')->group(function () {
    Route::post('/repayment/gcash', [PaymentController::class, 'payViaGcash'])
        ->name('repayment.gcash');

    Route::get('/repayment/gcash/success', [PaymentController::class, 'gcashSuccess'])
        ->name('repayment.gcash.success');

    Route::get('/repayment/gcash/failed', [PaymentController::class, 'gcashFailed'])
        ->name('repayment.gcash.failed');
});


// Jhun Code

Route::get("/dashboard-admin", [UserController::class, "dashboard_admin"])->name("dashboard");

Route::get("/dashboard-members", [UserController::class, "dashboard_members"])->name("dashboard.members");

Route::put("/dashboard-members/update", [UserController::class, "updateMember"])->name("update.member");

Route::delete("/dashboard-members/decline/{id}", [UserController::class, "declineUser"])->name("decline.user");

Route::get("/dashboard-savings", [UserController::class, "dashboard_savings"])->name("savings");

Route::get("/dashboard-lendings", [UserController::class, "dashboard_lendings"])->name("lendings");

Route::get("/dashboard-sharecapitals", [UserController::class, "dashboard_sharecapitals"])->name("sharecapitals");

Route::get("/dashboard-reports", [UserController::class, "dashboard_reports"])->name("reports");

Route::get("/dashboard-settings", [UserController::class, "dashboard_settings"])->name("settings");