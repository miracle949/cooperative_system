<?php

namespace App\Http\Controllers;

use App\Models\Users_tbl;
use App\Models\Membervehi_tbl;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\ApprovedMail;

class UserHandling extends Controller
{
    public $getUser;

    public function __construct()
    {
        $this->getUser = new Users_tbl();
    }

    public function showForm($id)
    {
        $user = Users_tbl::findOrFail($id);
        $vehicles = Membervehi_tbl::where('member_id', $id)->get()->groupBy('vehicle_type');
        $spouse = \App\Models\Spouse_tbl::where('member_id', $id)->first();
        $education = \App\Models\educational_tbl::where('member_id', $id)->get();
        $seminar = \App\Models\seminars_training_tbl::where('member_id', $id)->first();
        $employeeHistory = \App\Models\employee_history_tbl::where('member_id', $id)->first();
        $membershipInfo = \App\Models\employee_membership_information::where('member_id', $id)->first();
        $membershipHistory = \App\Models\tc_membership_history_tbl::where('member_id', $id)->first();
        $specialAwards = \App\Models\special_awards_tbl::where('member_id', $id)->first();

        return view('members_components.application_form', compact(
            'user',
            'vehicles',
            'spouse',
            'education',
            'seminar',
            'employeeHistory',
            'membershipInfo',
            'membershipHistory',
            'specialAwards'
        ));
    }

    public function approveUser($id)
    {
        $user = Users_tbl::findOrFail($id);

        // Send email notification
        Mail::to($user->email)->send(new ApprovedMail($user));

        return redirect()->back()->with('success', 'Member approved and email sent!');
    }

    public function StaticPage()
    {

        $getTheUsers = $this->getUser->getAllUser();

        return view("landingpage_components.static", compact("getTheUsers"));
    }

    public function applicationForm()
    {
        return view("members_components.application_form");
    }

    public function UserDirection()
    {
        return view("landingpage_components.landingpage");
    }

    public function LoginPage()
    {
        return view("login");
    }

    public function Landingpage()
    {
        return view("landingpage_components.landingpage");
    }

    public function RegisterPage()
    {
        return view("register");
    }

    public function AboutUs()
    {
        return view("landingpage_components.about");
    }

    public function ServicesPage()
    {
        return view("landingpage_components.services");
    }

    public function BlogsPage()
    {
        return view("landingpage_components.blogs");
    }

    public function ContactPage()
    {
        return view("landingpage_components.contact");
    }

    public function Navbar()
    {
        return view("navbar");
    }

    public function LoanApplication()
    {
        return view("members_components.loan_application");
    }

    public function Savings()
    {
        return view("members_components.savings");
    }

    public function ShareCapital()
    {
        return view("members_components.share_capital");
    }

    public function LoanStatus()
    {
        return view("members_components.loan_status");
    }

    public function ProfileMember()
    {
        return view("members_components.profile");
    }

    public function DriverPortal()
    {
        return view("members_components.driver_portal");
    }
}
