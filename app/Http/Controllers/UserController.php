<?php

namespace App\Http\Controllers;

use App\Models\educational_tbl;
use App\Models\Otherinfo_tbl;
use App\Models\Spouse_tbl;
use App\Models\Users_tbl;
use App\Models\Membervehi_tbl;
use App\Models\savings_account_tbl;
use App\Models\savings_transaction_tbl;
use App\Models\lending_program_tbl;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
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

    public function sharingCapital()
    {
        return view("ShareCapitalForm.share_capital_form");
    }

    public function shareCapitalForm($id)
    {
        return view("ShareCapitalForm.share_capital_form");
    }

    public function applicationForm($id)
    {
        $user = Users_tbl::findOrFail($id);
        $vehicles = Membervehi_tbl::where('user_id', $id)->get()->groupBy('vehicle_type');
        $spouse = Spouse_tbl::where('user_id', $id)->first();
        $other = Otherinfo_tbl::where('user_id', $id)->first();
        $education = educational_tbl::where('user_id', $id)->get();
        $governmentIds = Membervehi_tbl::where('user_id', $id)->first();

        // Check if already submitted (has contact_no or any filled data)
        $alreadySubmitted = $other && !empty($other->contact_no);

        return view('members_components.application_form', compact(
            'user',
            'vehicles',
            'spouse',
            'other',
            'education',
            'governmentIds',
            'alreadySubmitted'
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

    public function messageAboutShare($id)
    {

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


public function dashboard_admin()
{
    // Stats Cards
    $totalMembers = Users_tbl::where('role', 'member')->count();
    $totalSavings = savings_account_tbl::sum('balance') ?? 0;
    $activeLoans = lending_program_tbl::where('status', 'Approved')->sum('lending_amount') ?? 0;
    $pendingRequests = Users_tbl::where('role', 'pending')->count();

    // User Roles
    $adminCount = Users_tbl::where('role', 'admin')->count();
    $memberCount = Users_tbl::where('role', 'member')->count();
    $pendingCount = Users_tbl::where('role', 'pending')->count();

    // Recent Activities - Combine savings, lending, and member registrations
    $recentSavings = savings_transaction_tbl::with('savingsAccount.user')
        ->orderBy('created_at', 'desc')
        ->limit(5)
        ->get()
        ->map(function ($tx) {
            return [
                'type' => 'savings',
                'title' => $tx->type === 'deposit' ? 'Savings Deposit' : 'Savings Withdrawal',
                'description' => ($tx->savingsAccount->user->first_name ?? 'Unknown') . ' ' . 
                                 ($tx->savingsAccount->user->last_name ?? '') . ' - ₱' . number_format($tx->amount, 2),
                'status' => $tx->type === 'deposit' ? 'Completed' : 'Completed',
                'time' => $tx->created_at->diffForHumans(),
                'initials' => strtoupper(substr($tx->savingsAccount->user->first_name ?? 'U', 0, 1)),
                'created_at' => $tx->created_at
            ];
        });

    $recentLoans = lending_program_tbl::with('user')
        ->orderBy('created_at', 'desc')
        ->limit(5)
        ->get()
        ->map(function ($loan) {
            return [
                'type' => 'loan',
                'title' => $loan->status === 'Approved' ? 'Loan Approved' : ($loan->status === 'Declined' ? 'Loan Declined' : 'Loan Request'),
                'description' => ($loan->user->first_name ?? 'Unknown') . ' ' . 
                                 ($loan->user->last_name ?? '') . ' - ₱' . number_format($loan->lending_amount, 2),
                'status' => $loan->status,
                'time' => $loan->created_at->diffForHumans(),
                'initials' => strtoupper(substr($loan->user->first_name ?? 'U', 0, 1)),
                'created_at' => $loan->created_at
            ];
        });

    $recentMembers = Users_tbl::whereIn('role', ['member', 'pending'])
        ->orderBy('created_at', 'desc')
        ->limit(5)
        ->get()
        ->map(function ($user) {
            return [
                'type' => 'member',
                'title' => $user->role === 'pending' ? 'New Member Registration' : 'Member Activated',
                'description' => $user->first_name . ' ' . $user->last_name . ' joined as a new member',
                'status' => $user->role === 'pending' ? 'Pending' : 'Active',
                'time' => $user->created_at->diffForHumans(),
                'initials' => strtoupper(substr($user->first_name, 0, 1)),
                'created_at' => $user->created_at
            ];
        });

    // Merge and sort all activities by date
    $recentActivities = $recentSavings->concat($recentLoans)->concat($recentMembers)
        ->sortByDesc('created_at')
        ->take(10)
        ->values();

    // Savings by Month (last 6 months)
    $savingsByMonth = [];
    for ($i = 5; $i >= 0; $i--) {
        $month = now()->subMonths($i)->format('M');
        $monthNum = now()->subMonths($i)->format('n');
        $year = now()->subMonths($i)->format('Y');
        
        $total = savings_transaction_tbl::whereYear('created_at', $year)
            ->whereMonth('created_at', $monthNum)
            ->where('type', 'deposit')
            ->sum('amount') ?? 0;
        
        $savingsByMonth[$month] = round($total / 1000, 1); // Convert to thousands
    }

    // Member Activity (for pie chart)
    $activeMembers = Users_tbl::where('role', 'member')->count();
    $pendingMembers = Users_tbl::where('role', 'pending')->count();
    $totalUsers = $activeMembers + $pendingMembers;
    
    $memberActivity = [
        'active' => $totalUsers > 0 ? round(($activeMembers / $totalUsers) * 100) : 0,
        'new' => $totalUsers > 0 ? round(($pendingMembers / $totalUsers) * 100) : 0,
        'inactive' => 0
    ];

    // Loans by Purpose (for loan distribution)
    $loansByPurpose = lending_program_tbl::where('status', 'Approved')
        ->select('purpose_loan', DB::raw('SUM(lending_amount) as total'))
        ->groupBy('purpose_loan')
        ->get()
        ->mapWithKeys(function ($item) {
            $purpose = $item->purpose_loan ?? 'Other';
            return [$purpose => $item->total];
        })
        ->toArray();

    // If no loans, use sample data structure
    if (empty($loansByPurpose)) {
        $loansByPurpose = [
            'Business Capital' => 0,
            'Personal Loan' => 0,
            'Emergency Loan' => 0,
            'Housing Loan' => 0
        ];
    }

    return view("admin_components.dashboard", compact(
        'totalMembers',
        'totalSavings',
        'activeLoans',
        'pendingRequests',
        'adminCount',
        'memberCount',
        'pendingCount',
        'recentActivities',
        'savingsByMonth',
        'memberActivity',
        'loansByPurpose'
    ));
}

    public function dashboard_members(Request $request)
    {
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

    public function dashboard_savings()
    {
        return view("admin_components.savings");
    }

    public function dashboard_lendings()
    {
        return view("admin_components.lending");
    }

    public function dashboard_sharecapitals()
    {
        return view("admin_components.sharecapitals");
    }

    public function dashboard_reports()
    {
        return view("admin_components.reports");
    }

    public function dashboard_settings()
    {
        return view("admin_components.settings");
    }
}
