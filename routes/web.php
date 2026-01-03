<?php

use App\Http\Controllers\UserHandling;
use Illuminate\Support\Facades\Route;

// Main page routing

Route::get("/", [UserHandling::class, "UserDirection"]);

// Landing page get

Route::get("/landing-page", [UserHandling::class, "Landingpage"])->name("Landingpage");

// Login page get

Route::get("/login-page", [UserHandling::class, "LoginPage"])->name("LoginPage");

// Register page get

Route::get("/register-page", [UserHandling::class, "RegisterPage"])->name("RegisterPage");

// About us page get

Route::get("/about-us", [UserHandling::class, "AboutUs"])->name(("AboutUs"));

// Services page get

Route::get("/services", [UserHandling::class, "ServicesPage"])->name(("ServicesPage"));

// Blogs page get

Route::get("/blogs", [UserHandling::class, "BlogsPage"])->name(("BlogsPage"));

// Contact page get

Route::get("/contact", [UserHandling::class, "ContactPage"])->name(("ContactPage"));

// Navbar

Route::get("/navbar", [UserHandling::class, "Navbar"]);

// Homepage (Member Side)

Route::get("/member-portal", [UserHandling::class, "MemberPortal"])->name("MemberPortal");

// Loan application (Member Side)

Route::get("/loan_application", [UserHandling::class, "LoanApplication"])->name("LoanApplication");

// Savings (Member Side)

Route::get("/savings-page", [UserHandling::class, "Savings"])->name("Savings");

// Loan Status (Member Side)

Route::get("/loan-status", [UserHandling::class, "LoanStatus"])->name("LoanStatus");

// Profile (Member Side)

Route::get("/profile-member", [UserHandling::class, "ProfileMember"])->name("ProfileMember");

// Homepage (Driver Side)

Route::get("/driver-portal", [UserHandling::class, "DriverPortal"])->name("DriverPortal");