<?php

namespace App\Http\Controllers;

use App\Models\Users_tbl;
use App\Models\Membervehi_tbl;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\ApprovedMail;
use App\Mail\ShareCapital;
use App\Mail\DeclinedMail;

class UserController extends Controller
{
    public $getUser;

    public function __construct()
    {
        $this->getUser = new Users_tbl();
    }

    public function sharingCapital(){
        return view("ShareCapitalForm.share_capital_form");
    }

    public function shareCapitalForm($id){
        return view("ShareCapitalForm.share_capital_form");
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
        $user->role = 'Member';
        $user->save();

        Mail::to($user->email)->send(new ApprovedMail($user));

        return redirect()->back()->with('success', 'Member approved and email sent!');
    }

    public function messageAboutShare($id){

        $user = Users_tbl::findOrFail($id);
        $user->role = 'Member';
        $user->save();

        Mail::to($user->email)->sendNow(new ShareCapital($user));

        return redirect()->back()->with('success', 'Member share capital application!');
    }

    public function declineUser($id)
    {
        $user = Users_tbl::findOrFail($id);

        Mail::to($user->email)->send(new DeclinedMail($user));

        $user->delete();

        return redirect()->back()->with('error', 'Member request declined and removed.');
    }

    public function updateMember(Request $request)
    {
        $validated = $request->validate([
            'id' => 'required',
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email',
        ]);

        $user = Users_tbl::findOrFail($request->id);

        $user->update($request->only([
            'first_name',
            'middle_name',
            'last_name',
            'email',
            'contact_no',
            'date_of_birth',
            'present_address',
            'permanent_address',
            'sex',
            'civil_status',
            'citizenship',
            'place_of_birth',
            'blood_type',
            'height',
            'weight',
            'role',
        ]));

        return redirect()->back()->with('success', 'Member updated successfully.');
    }

    public function StaticPage()
    {

        $getTheUsers = $this->getUser->getAllUser();

        return view("landingpage_components.static", compact("getTheUsers"));
    }

    // public function applicationForm()
    // {
    //     return view("members_components.application_form");
    // }

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

    public function dashboard_admin(){
        return view("admin_components.dashboard");
    }

    public function dashboard_members(Request $request){
        $query = Users_tbl::query();
        
        $status = $request->get('filter', 'all');
        if ($status === 'pending') {
            $query->where('role', 'pending');
        } elseif ($status === 'active') {
            $query->where('role', 'member');
        } elseif ($status === 'inactive') {
            $query->where('role', 'inactive');
        }
        
        $members = $query->paginate(12);
        $pendingRequests = Users_tbl::where('role', 'pending')->get();
        
        return view("admin_components.members", compact('members', 'pendingRequests'));
    }

    public function dashboard_savings(){
        return view("admin_components.savings");
    }

    public function dashboard_lendings(){
        return view("admin_components.lending");
    }

    public function dashboard_sharecapitals(){
        return view("admin_components.sharecapitals");
    }

    public function dashboard_reports(){
        return view("admin_components.reports");
    }

    public function dashboard_settings(){
        return view("admin_components.settings");
    }
}
