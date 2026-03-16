<?php

use App\Http\Controllers\lendingController;
use App\Http\Controllers\UserHandling;
use App\Http\Controllers\UsersHandle;
use Illuminate\Support\Facades\Route;

Route::get("/", [UserHandling::class, "UserDirection"]);

Route::get("/landing-page", [UserHandling::class, "Landingpage"])->name("Landingpage");

// Login page GET

Route::get("/login-page", [UserHandling::class, "LoginPage"])->name("LoginPage");

// Register page GET

Route::get("/register-page", [UserHandling::class, "RegisterPage"])->name("RegisterPage");

// About us page GET

Route::get("/about-us", [UserHandling::class, "AboutUs"])->name("AboutUs");

// Services page GET

Route::get("/services", [UserHandling::class, "ServicesPage"])->name("ServicesPage");

// // Blogs page GET

Route::get("/blogs", [UserHandling::class, "BlogsPage"])->name("BlogsPage");

// Contact page GET

Route::get("/contact", [UserHandling::class, "ContactPage"])->name("ContactPage");

Route::get("/navbar", [UserHandling::class, "Navbar"]);

// Static page GET

Route::get("/static-page", [UserHandling::class, "StaticPage"])->name("StaticPage");

// Member Portal page GET

Route::get("/member-portal", [UsersHandle::class, "MemberPortal"])->name("MemberPortal")->middleware("auth");

// Lending Program page GET

Route::get("/loan_application", [UsersHandle::class, "LoanApplication"])->name("LoanApplication");

// Savings page GET

Route::get("/savings-page", [UsersHandle::class, "Savings"])->name("Savings");

// Share Capital page GET

Route::get('/share-capital', [UsersHandle::class, "ShareCapital"])->name("ShareCapital");

// Loan Status page GET

Route::get("/loan-status", [UsersHandle::class, "LoanStatus"])->name("LoanStatus");

// Profile Member page GET

Route::get("/profile-member", [UsersHandle::class, "ProfileMember"])->name("ProfileMember");

// Driver Portal page GET

Route::get("/driver-portal", [UserHandling::class, "DriverPortal"])->name("DriverPortal");

// Login Handle page POST
Route::post("/login-handle", [UsersHandle::class, "login"])->name("UserLogin");

// User Handle page GET

Route::get("/user-handle", [UsersHandle::class, "UserHandle"])->name("UserHandle")->middleware("auth");

// Registration page POST

Route::post("/registration", [UsersHandle::class, "registration"])->name("registration");

// Lending Program page GET

Route::get("/login", [UsersHandle::class, "login"])->name("login");

// Route::post("/login-page", [UsersHandle::class, "login"])->name("UserLogin");

Route::get('/approve-user/{id}', [UserHandling::class, 'approveUser'])->name('approve.user');

// Application form - only ONE GET, ONE POST
Route::get('/application-form/{id}', [UserHandling::class, 'showForm'])->name('applicationForm');

Route::post('/application-form/{id}', [UsersHandle::class, 'applicationFormButton'])->name('applicationFormButton');

Route::get("/nav-bar2", [UsersHandle::class, "Navbar2"])->name("Navbar2");

Route::get("/logout", [UsersHandle::class, "logout"])->name("logout");

Route::post("/lending-program", [lendingController::class, "lendingProgram"])
    ->name("lendingProgram")
    ->middleware("auth");

// Route::get("/lending-program", [lendingController::class, "lendingProgram"])->name("lendingProgram");