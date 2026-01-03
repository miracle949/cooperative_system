<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserHandling extends Controller
{
    public function UserDirection(){
        return view("landingpage");
    }

    public function LoginPage(){
        return view("login");
    }

    public function Landingpage(){
        return view("landingpage");
    }

    public function RegisterPage(){
        return view("register");
    }

    public function AboutUs(){
        return view("about");
    }

    public function ServicesPage(){
        return view("services");
    }

    public function BlogsPage(){
        return view("blogs");
    }

    public function ContactPage(){
        return view("contact");
    }

    public function Navbar(){
        return view("navbar");
    }

    public function MemberPortal(){
        return view("member_portal");
    }

    public function LoanApplication(){
        return view("loan_application");
    }

    public function Savings(){
        return view("savings");
    }

    public function LoanStatus(){
        return view("loan_status");
    }

    public function ProfileMember(){
        return view("profile");
    }

    public function DriverPortal(){
        return view("driver_portal");
    }
}
