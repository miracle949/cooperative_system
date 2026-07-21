<?php

namespace App\Http\Controllers;

use App\Models\educational_tbl;
use App\Models\Otherinfo_tbl;
use App\Models\Spouse_tbl;
use App\Models\Users_tbl;
use App\Models\Membervehi_tbl;
use App\Models\Family_tbl;
use App\Models\Membergovern_ids_tbl;
use App\Models\savings_account_tbl;
use App\Models\savings_transaction_tbl;
use App\Models\share_capital_account_tbl;
use App\Models\share_capital_transaction_tbl;
use App\Models\lending_program_tbl;
use App\Models\lending_status_tbl;
use App\Models\lending_repayments_tbl;
use App\Models\Loan_settings_tbl;
use App\Models\Dividend;
use App\Models\CooperativeTransaction;
use App\Models\system_settings_tbl;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
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
        $user->role = 'member';
        $user->save();

        $otherInfo = DB::table('otherinfo_tbls')->where('user_id', $id)->first();
        
        if ($otherInfo) {
            DB::table('otherinfo_tbls')
                ->where('user_id', $id)
                ->update([
                    'membership_status' => 'Active',
                    'approval_status' => 'Approved',
                ]);
        } else {
            DB::table('otherinfo_tbls')->insert([
                'user_id' => $id,
                'membership_category' => 'Investor Associate',
                'signature' => '',
                'membership_status' => 'Active',
                'approval_status' => 'Approved',
            ]);
        }

        AuditLog::log(
            'Approved Member',
            "Approved member {$user->first_name} {$user->last_name} (ID: {$id})",
            'user',
            $id
        );

        Mail::to($user->email)->send(new ApprovedMail($user));

        return redirect()->back()->with('success', 'Member approved and email sent!');
    }
    
    public function messageAboutShare($id)
    {
        $user = Users_tbl::findOrFail($id);
        $user->role = 'Member';
        $user->save();

        $otherInfo = DB::table('otherinfo_tbls')->where('user_id', $id)->first();
        
        if ($otherInfo) {
            DB::table('otherinfo_tbls')
                ->where('user_id', $id)
                ->update(['approval_status' => 'Approved']);
        } else {
            DB::table('otherinfo_tbls')->insert([
                'user_id' => $id,
                'membership_category' => 'Investor Associate',
                'signature' => '',
                'approval_status' => 'Approved',
            ]);
        }

        AuditLog::log(
            'Sent Share Capital Invitation',
            "Sent share capital invitation email to {$user->first_name} {$user->last_name} (ID: {$id})",
            'user',
            $id
        );

        Mail::to($user->email)->sendNow(new ShareCapital($user));

        return redirect()->back()->with('success', 'Share capital invitation sent!');
    }

    public function declineUser($id)
    {
        $user = Users_tbl::findOrFail($id);

        AuditLog::log(
            'Declined Member',
            "Declined and removed member {$user->first_name} {$user->last_name} (ID: {$id})",
            'user',
            $id
        );

        Mail::to($user->email)->send(new DeclinedMail($user));

        $user->delete();

        return redirect()->back()->with('error', 'Member request declined and removed.');
    }

    public function updateMember(Request $request)
    {
        try {
            $request->validate([
                'id' => 'required',
            ]);

            $user = Users_tbl::findOrFail($request->id);
            $previousRole = $user->role;

            if ($request->has('role') && in_array($request->role, ['admin', 'officer']) && !auth()->user()->isMainAdmin()) {
                return response()->json(['success' => false, 'message' => 'Only the main admin can assign admin or officer roles.'], 403);
            }

            $fillable = ['first_name', 'middle_name', 'last_name', 'email', 'contact_no', 'date_of_birth', 'present_address', 'permanent_address', 'sex', 'civil_status', 'citizenship', 'place_of_birth', 'blood_type', 'height', 'weight', 'role'];
            
            $data = $request->only($fillable);
            $data = array_filter($data, fn($value) => $value !== null && $value !== '');
            
            $user->update($data);

            if (isset($request->role)) {
                $otherInfo = DB::table('otherinfo_tbls')->where('user_id', $request->id)->first();
                
                if ($request->role === 'Member') {
                    if ($otherInfo) {
                        DB::table('otherinfo_tbls')
                            ->where('user_id', $request->id)
                            ->update([
                                'membership_status' => 'Active',
                                'approval_status' => 'Approved',
                                'signature' => null,
                            ]);
                    } else {
                        DB::table('otherinfo_tbls')->insert([
                            'user_id' => $request->id,
                            'membership_category' => 'Investor Associate',
                            'membership_status' => 'Active',
                            'approval_status' => 'Approved',
                        ]);
                    }
                } elseif ($request->role === 'inactive' && $otherInfo) {
                    DB::table('otherinfo_tbls')
                        ->where('user_id', $request->id)
                        ->update([
                            'membership_status' => 'Inactive',
                        ]);
                }
            }

            AuditLog::log(
                'Updated Member',
                "Updated member {$user->first_name} {$user->last_name} (ID: {$request->id})",
                'user',
                $request->id
            );

            return response()->json(['success' => true, 'message' => 'Member updated successfully']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function storeMember(Request $request)
    {
        try {
            $request->validate([
                'first_name' => 'required',
                'last_name' => 'required',
                'email' => 'required|email|unique:users_tbls,email',
                'username' => 'required|unique:users_tbls,username',
                'password' => 'required|min:6',
                'membership_category' => 'required',
                'date_of_birth' => 'required|date',
                'civil_status' => 'required',
            ]);

            $user = Users_tbl::create([
                'first_name' => $request->first_name,
                'middle_name' => $request->middle_name,
                'last_name' => $request->last_name,
                'username' => $request->username,
                'email' => $request->email,
                'password' => bcrypt($request->password),
                'role' => 'member',
            ]);

            Otherinfo_tbl::create([
                'user_id' => $user->id,
                'membership_category' => $request->membership_category,
                'date_of_birth' => $request->date_of_birth,
                'place_of_birth' => $request->place_of_birth,
                'sex' => $request->sex,
                'civil_status' => $request->civil_status,
                'citizenship' => $request->citizenship,
                'contact_no' => $request->contact_no,
                'present_address' => $request->present_address,
                'skills' => $request->skills,
                'approval_status' => 'Approved',
                'membership_status' => 'Active',
            ]);

            Family_tbl::create([
                'user_id' => $user->id,
                'spouse_name' => $request->spouse_name,
                'spouse_date_birth' => $request->spouse_date_birth,
                'spouse_place_birth' => $request->spouse_place_birth,
                'number_son' => $request->number_son ?? 0,
                'number_daughter' => $request->number_daughter ?? 0,
            ]);

            Membergovern_ids_tbl::create([
                'user_id' => $user->id,
            ]);

            AuditLog::log(
                'Added Member',
                "Added new member {$request->first_name} {$request->last_name} (ID: {$user->id})",
                'user',
                $user->id
            );

            return redirect()->back()->with('success', 'Member added successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error adding member: ' . $e->getMessage());
        }
    }

    public function sendShareCapitalEmail($id)
    {
        $user = Users_tbl::findOrFail($id);
        AuditLog::log(
            'Sent Share Capital Email',
            "Sent share capital email to {$user->first_name} {$user->last_name} (ID: {$id})",
            'user',
            $id
        );
        Mail::to($user->email)->sendNow(new ShareCapital($user));
        return redirect()->back()->with('success', 'Share capital email sent to member!');
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
        return view("landingpage_components.index");
    }

    public function LoginPage()
    {
        return view("login");
    }

    public function index()
    {
        return view("landingpage_components.index");
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
        $totalShareCapital = \App\Models\share_capital_account_tbl::sum('total_amount') ?? 0;
        $activeLoans = lending_program_tbl::where('status', 'Approved')->sum('lending_amount') ?? 0;
        $earnedInterests = lending_program_tbl::where('status', 'Completed')->sum('total_interest') ?? 0;

        // Savings Activity counts
        $totalDeposits = savings_transaction_tbl::where('type', 'deposit')->count();
        $totalWithdrawals = savings_transaction_tbl::where('type', 'withdrawal')->count();

        // Pending counts
        $pendingMembersCount = Users_tbl::where('role', 'pending')->count();
        $pendingLoansCount = lending_program_tbl::where('status', 'Pending')->count();
        $pendingWithdrawalsCount = share_capital_transaction_tbl::where('type', 'Withdrawal')
            ->whereIn('status', ['Pending', 'pending'])->count();

        // Pending Resignations
        $pendingResignationsCount = \App\Models\ResignationRequest_tbl::where('status', 'pending')->count();
        $pendingResignationsList = \App\Models\ResignationRequest_tbl::with('user')
            ->where('status', 'pending')
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get()
            ->map(function ($req) {
                return [
                    'id' => $req->id,
                    'name' => $req->user ? $req->user->first_name . ' ' . $req->user->last_name : 'Unknown',
                    'withdraw' => $req->withdraw_share_capital,
                    'time' => $req->created_at ? $req->created_at->diffForHumans() : 'N/A',
                    'created_at' => $req->created_at,
                ];
            });

        // Upcoming Seminars
        $upcomingSeminars = \App\Models\Seminars_tbl::where('schedule_datetime', '>=', now())
            ->orderBy('schedule_datetime')
            ->take(5)
            ->get()
            ->map(function ($seminar) {
                return [
                    'id' => $seminar->id,
                    'type' => $seminar->seminar_type,
                    'schedule' => $seminar->schedule_datetime ? $seminar->schedule_datetime->format('M d, Y g:i A') : 'N/A',
                    'delivery' => $seminar->delivery_type,
                    'venue' => $seminar->delivery_type === 'online' ? $seminar->online_link : ($seminar->exact_venue ?? $seminar->meetup_place ?? 'N/A'),
                ];
            });
        $upcomingSeminarsCount = $upcomingSeminars->count();

        // === TO-DO LISTS ===

        // 1. Member Approvals (pending registrations)
        $pendingMembersList = Users_tbl::where('role', 'pending')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get()
            ->map(function ($user) {
                return [
                    'id' => $user->id,
                    'name' => $user->first_name . ' ' . $user->last_name,
                    'initials' => strtoupper(substr($user->first_name, 0, 1)),
                    'type' => 'Member Registration',
                    'amount' => null,
                    'time' => $user->created_at ? $user->created_at->diffForHumans() : 'N/A',
                    'created_at' => $user->created_at,
                    'route' => 'dashboard.members'
                ];
            });

        // 2. Loan Applications (pending loans)
        $pendingLoansList = lending_program_tbl::with('user')
            ->where('status', 'Pending')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get()
            ->map(function ($loan) {
                return [
                    'id' => $loan->id,
                    'name' => ($loan->user->first_name ?? 'Unknown') . ' ' . ($loan->user->last_name ?? ''),
                    'initials' => strtoupper(substr($loan->user->first_name ?? 'U', 0, 1)),
                    'type' => 'Loan Application',
                    'amount' => $loan->lending_amount,
                    'time' => $loan->created_at ? $loan->created_at->diffForHumans() : 'N/A',
                    'created_at' => $loan->created_at,
                    'route' => 'lendings'
                ];
            });

        // 3. Withdraw Share Capital (pending withdrawals)
        $pendingWithdrawalsList = share_capital_transaction_tbl::with('shareCapitalAccount.user')
            ->where('type', 'Withdrawal')
            ->whereIn('status', ['Pending', 'pending'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get()
            ->map(function ($tx) {
                $user = $tx->shareCapitalAccount && $tx->shareCapitalAccount->user ? $tx->shareCapitalAccount->user : null;
                return [
                    'id' => $tx->id,
                    'name' => $user ? $user->first_name . ' ' . $user->last_name : 'Unknown',
                    'initials' => $user ? strtoupper(substr($user->first_name, 0, 1)) : 'U',
                    'type' => 'Withdraw Share Capital',
                    'amount' => $tx->total_amount,
                    'time' => $tx->created_at ? $tx->created_at->diffForHumans() : 'N/A',
                    'created_at' => $tx->created_at,
                    'route' => 'sharecapitals'
                ];
            });

        // Counts for tab badges

        // === RECENT ACTIVITY - SEPARATE BY TYPE ===

        // 4. Transactions (savings + share capital - completed/approved)
        $recentSavingsTx = savings_transaction_tbl::with('savingsAccount.user')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get()
            ->map(function ($tx) {
                return [
                    'type' => 'savings',
                    'subtype' => $tx->type,
                    'title' => $tx->type === 'deposit' ? 'Savings Deposit' : 'Savings Withdrawal',
                    'user' => ($tx->savingsAccount->user->first_name ?? 'Unknown') . ' ' . ($tx->savingsAccount->user->last_name ?? ''),
                    'initials' => strtoupper(substr($tx->savingsAccount->user->first_name ?? 'U', 0, 1)),
                    'amount' => $tx->amount,
                    'status' => 'Completed',
                    'time' => $tx->created_at ? $tx->created_at->diffForHumans() : 'N/A',
                    'created_at' => $tx->created_at
                ];
            });

        $recentShareCapitalTx = share_capital_transaction_tbl::with('shareCapitalAccount.user')
            ->whereIn('status', ['Completed', 'Approved', 'completed', 'approved'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get()
            ->map(function ($tx) {
                $user = $tx->shareCapitalAccount && $tx->shareCapitalAccount->user ? $tx->shareCapitalAccount->user : null;
                return [
                    'type' => 'share_capital',
                    'subtype' => strtolower($tx->type),
                    'title' => ucfirst($tx->type) . ' Share Capital',
                    'user' => $user ? $user->first_name . ' ' . $user->last_name : 'Unknown',
                    'initials' => $user ? strtoupper(substr($user->first_name, 0, 1)) : 'U',
                    'amount' => $tx->total_amount,
                    'status' => ucfirst($tx->status),
                    'time' => $tx->created_at ? $tx->created_at->diffForHumans() : 'N/A',
                    'created_at' => $tx->created_at
                ];
            });

        $recentTransactions = $recentSavingsTx->concat($recentShareCapitalTx)
            ->sortByDesc('created_at')
            ->take(10)
            ->values();

        // 5. Member Approvals (registrations + activations)
        $recentMemberApprovals = Users_tbl::whereIn('role', ['member', 'pending'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get()
            ->map(function ($user) {
                return [
                    'type' => 'member',
                    'title' => $user->role === 'pending' ? 'New Member Registration' : 'Member Activated',
                    'user' => $user->first_name . ' ' . $user->last_name,
                    'initials' => strtoupper(substr($user->first_name, 0, 1)),
                    'status' => $user->role === 'pending' ? 'Pending' : 'Active',
                    'time' => $user->created_at ? $user->created_at->diffForHumans() : 'N/A',
                    'created_at' => $user->created_at
                ];
            });

        // Savings by Month (last 6 months) - deposits minus withdrawals
        $savingsByMonth = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = now()->subMonths($i)->format('M');
            $monthNum = now()->subMonths($i)->format('n');
            $year = now()->subMonths($i)->format('Y');

            $deposits = savings_transaction_tbl::whereYear('created_at', $year)
                ->whereMonth('created_at', $monthNum)
                ->where('type', 'deposit')
                ->sum('amount') ?? 0;

            $withdrawals = savings_transaction_tbl::whereYear('created_at', $year)
                ->whereMonth('created_at', $monthNum)
                ->where('type', 'withdrawal')
                ->sum('amount') ?? 0;

            $savingsByMonth[$month] = $deposits - $withdrawals;
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

        // Loans by Type (for loan distribution)
        $allLoans = lending_program_tbl::where('status', 'Approved')->with('user')->get();
        
        $loanTypeCounts = [
            'Personal' => 0,
            'Education' => 0,
            'Emergency' => 0,
            'Business' => 0
        ];
        
        $loansByTypeDetails = [
            'Personal' => [],
            'Education' => [],
            'Emergency' => [],
            'Business' => []
        ];
        
        foreach ($allLoans as $loan) {
            $type = $loan->lending_type ?? '';
            $normalizedType = match ($type) {
                'Personal Lending', 'Personal Loan' => 'Personal',
                'Education Lending', 'Education Loan' => 'Education',
                'Emergency Lending', 'Emergency Loan' => 'Emergency',
                'Business Lending', 'Business Loan' => 'Business',
                default => 'Personal'
            };
            
            $loanTypeCounts[$normalizedType]++;
            $loansByTypeDetails[$normalizedType][] = $loan;
        }

        // Audit Logs
        $auditLogs = AuditLog::with('user')
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        // Recent Savings Transactions (for modal)
        $recentSavingsTransactions = savings_transaction_tbl::with('savingsAccount.user')
            ->orderByDesc('created_at')
            ->take(10)
            ->get()
            ->map(function ($tx) {
                return [
                    'id' => $tx->id,
                    'amount' => $tx->amount,
                    'total_amount' => $tx->total_amount,
                    'type' => $tx->type,
                    'reference_no' => $tx->reference_no,
                    'created_at' => $tx->created_at,
                    'user_name' => $tx->savingsAccount && $tx->savingsAccount->user ? $tx->savingsAccount->user->first_name . ' ' . $tx->savingsAccount->user->last_name : 'Unknown'
                ];
            });

        return view("admin_components.dashboard", compact(
            'totalMembers',
            'totalSavings',
            'totalShareCapital',
            'activeLoans',
            'earnedInterests',
            'totalDeposits',
            'totalWithdrawals',
            'pendingMembersList',
            'pendingLoansList',
            'pendingWithdrawalsList',
            'pendingMembersCount',
            'pendingLoansCount',
            'pendingWithdrawalsCount',
            'pendingResignationsCount',
            'pendingResignationsList',
            'upcomingSeminars',
            'upcomingSeminarsCount',
            'recentTransactions',
            'recentMemberApprovals',
            'savingsByMonth',
            'memberActivity',
            'loanTypeCounts',
            'loansByTypeDetails',
            'auditLogs'
        ));
    }

    public function dashboard_members(Request $request)
    {
        $query = Users_tbl::query()->where('role', '!=', 'admin');

        $status = $request->get('filter', 'all');
        if ($status === 'pending') {
            $query->where('role', 'pending');
        } elseif ($status === 'active') {
            $query->where('role', 'member');
        } elseif ($status === 'inactive') {
            $query->where('role', 'inactive');
        }

        $members = $query->orderBy('id', 'asc')->paginate(10);
        $pendingRequests = Users_tbl::where('role', 'pending')->orderBy('id', 'asc')->get();
        $admins = Users_tbl::where('role', 'admin')->get();

        $resignationRequests = \App\Models\ResignationRequest_tbl::with('user')
            ->where('status', 'pending')
            ->orderBy('created_at', 'desc')
            ->get();

        $inProcessResignations = \App\Models\ResignationRequest_tbl::with('user')
            ->where('status', 'approved')
            ->where('is_released', false)
            ->where('withdraw_share_capital', true)
            ->orderBy('release_date', 'asc')
            ->get();

        $resignees = \App\Models\ResignationRequest_tbl::with('user')
            ->where(function ($q) {
                $q->where('is_released', true)
                  ->orWhere(function ($q2) {
                      $q2->where('status', 'approved')
                         ->where('withdraw_share_capital', false);
                  });
            })
            ->orderBy('updated_at', 'desc')
            ->get();

        $memberCategoryCounts = DB::table('otherinfo_tbls')
            ->join('users_tbls', 'otherinfo_tbls.user_id', '=', 'users_tbls.id')
            ->where('users_tbls.role', '!=', 'admin')
            ->select('otherinfo_tbls.membership_category', DB::raw('COUNT(*) as count'))
            ->groupBy('otherinfo_tbls.membership_category')
            ->pluck('count', 'membership_category');

        $adminCategoryCounts = DB::table('otherinfo_tbls')
            ->join('users_tbls', 'otherinfo_tbls.user_id', '=', 'users_tbls.id')
            ->where('users_tbls.role', 'admin')
            ->select('otherinfo_tbls.membership_category', DB::raw('COUNT(*) as count'))
            ->groupBy('otherinfo_tbls.membership_category')
            ->pluck('count', 'membership_category');

        $roles = \App\Models\Role::orderBy('name')->get();
        $roleCounts = [];
        foreach ($roles as $role) {
            $roleCounts[$role->slug] = Users_tbl::where('role', $role->slug)->count();
        }

        $memberIds = $members->pluck('id')->toArray();

        $shareCapitals = DB::table('share_capital_account_tbls')
            ->whereIn('user_id', $memberIds)
            ->get()
            ->keyBy('user_id');

        $otherInfo = DB::table('otherinfo_tbls')
            ->whereIn('user_id', $memberIds)
            ->get()
            ->keyBy('user_id');

        $spouseInfo = DB::table('family_tbls')
            ->whereIn('user_id', $memberIds)
            ->get()
            ->keyBy('user_id');

        $govIds = DB::table('membergovern_ids_tbls')
            ->whereIn('user_id', $memberIds)
            ->get()
            ->keyBy('user_id');

        $vehicles = DB::table('membervehi_tbls')
            ->whereIn('user_id', $memberIds)
            ->get()
            ->groupBy('user_id');

        $members->getCollection()->transform(function ($member) use ($shareCapitals, $otherInfo, $spouseInfo, $govIds, $vehicles) {
            $sc = $shareCapitals->get($member->id);
            $member->sc_total_amount = $sc->total_amount ?? 0;
            $member->sc_total_shares = $sc->total_shares ?? 0;
            $member->sc_status = $sc->status ?? 'No Account';

            $other = $otherInfo->get($member->id);
            $member->profile_picture = $other->profile_picture ?? null;
            $member->skills = $other->skills ?? null;
            $member->membership_category = $other->membership_category ?? 'Investor Associate';

            $spouse = $spouseInfo->get($member->id);
            $member->spouse_name = $spouse->spouse_name ?? null;
            $member->spouse_date_birth = $spouse->spouse_date_birth ?? null;
            $member->spouse_place_birth = $spouse->spouse_place_birth ?? null;
            $member->number_son = $spouse->number_son ?? 0;
            $member->number_daughter = $spouse->number_daughter ?? 0;

            $gov = $govIds->get($member->id);
            $member->sss_id = $gov->sss_id ?? null;
            $member->philhealth_id = $gov->philhealth_id ?? null;
            $member->pagibig_id = $gov->pagibig_id ?? null;
            $member->tin_id = $gov->tin_id ?? null;

            $memberVehicles = $vehicles->get($member->id, collect());
            $member->vehicles = $memberVehicles->map(function ($v) {
                return [
                    'vehicle_type' => $v->vehicle_type,
                    'plate_no' => $v->plate_no,
                    'quantity' => $v->quantity ?? 1,
                ];
            })->values()->toArray();

            return $member;
        });

        return view("admin_components.members", compact('members', 'pendingRequests', 'admins', 'memberCategoryCounts', 'adminCategoryCounts', 'resignationRequests', 'inProcessResignations', 'resignees', 'roles', 'roleCounts'));
    }

    public function dashboard_savings(Request $request)
    {
        $search = $request->get('search', '');
        $typeFilter = $request->get('type', 'all');
        $statusFilter = $request->get('status', 'all');

        $query = savings_transaction_tbl::with('savingsAccount.user')
            ->where('archived', '!=', 1);

        if ($search) {
            $query->whereHas('savingsAccount.user', function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%");
            });
        }

        if ($typeFilter !== 'all') {
            $query->where('type', $typeFilter);
        }

        if ($statusFilter !== 'all') {
            $query->where('status', $statusFilter);
        }

        $transactions = $query->orderBy('created_at', 'desc')->paginate(10);

        $currentBalance = (savings_transaction_tbl::where('type', 'deposit')->sum('amount') ?? 0) - (savings_transaction_tbl::where('type', 'withdrawal')->sum('amount') ?? 0);

        $sixMonthsAgo = now()->subMonths(6)->startOfMonth();
        $monthlyAvg = savings_transaction_tbl::where('type', 'deposit')
            ->where('created_at', '>=', $sixMonthsAgo)
            ->sum('amount') / 6;

        $monthlyWithdrawalAvg = savings_transaction_tbl::where('type', 'withdrawal')
            ->where('created_at', '>=', $sixMonthsAgo)
            ->sum('amount') / 6;

        $thisMonth = now()->startOfMonth();
        $monthlyDeposits = (savings_transaction_tbl::where('type', 'deposit')
            ->where('created_at', '>=', $thisMonth)
            ->sum('amount') ?? 0)
            - (savings_transaction_tbl::where('type', 'withdrawal')
            ->where('created_at', '>=', $thisMonth)
            ->sum('amount') ?? 0);

        $monthlyWithdrawals = savings_transaction_tbl::where('type', 'withdrawal')
            ->where('created_at', '>=', $thisMonth)
            ->sum('amount') ?? 0;
        
        $monthlyWithdrawalsCount = savings_transaction_tbl::where('type', 'withdrawal')
            ->where('created_at', '>=', $thisMonth)
            ->count() ?? 0;

        $today = now()->startOfDay();
        $todayDeposits = savings_transaction_tbl::where('type', 'deposit')
            ->where('created_at', '>=', $today)
            ->sum('amount');

        $lastContribution = savings_transaction_tbl::orderByDesc('created_at')->first();

        $totalDeposits = savings_transaction_tbl::where('type', 'deposit')->sum('amount') ?? 0;
        $totalWithdrawals = savings_transaction_tbl::where('type', 'withdrawal')->sum('amount') ?? 0;

        $allMembers = Users_tbl::whereIn('role', ['member', 'pending'])
            ->select('id', 'first_name', 'last_name')
            ->orderBy('first_name')
            ->get();

        $monthlyData = [];
        $maxAmount = 0;
        $processedMonths = [];
        
        // Use simple sequential months from current month backward
        for ($i = 5; $i >= 0; $i--) {
            $monthDate = now()->subMonths($i);
            $monthKey = $monthDate->format('Y-m');
            
            // Skip if we've already processed this month
            if (in_array($monthKey, $processedMonths)) {
                continue;
            }
            $processedMonths[] = $monthKey;
            
            $amount = (float) savings_transaction_tbl::where('type', 'deposit')
                ->whereYear('created_at', $monthDate->format('Y'))
                ->whereMonth('created_at', $monthDate->format('m'))
                ->sum('amount');
            
            $monthlyData[] = [
                'name' => $monthDate->format('F'),
                'year' => $monthDate->format('Y'),
                'amount' => $amount
            ];
            if ($amount > $maxAmount) {
                $maxAmount = $amount;
            }
        }
        
        // Ensure we have exactly 6 months
        if (count($monthlyData) > 6) {
            $monthlyData = array_slice($monthlyData, -6);
        }
        
        foreach ($monthlyData as &$data) {
            $data['bar_height'] = $maxAmount > 0 ? ($data['amount'] / $maxAmount) * 150 : 0;
        }

        $highestMonth = [
            'name' => 'N/A',
            'amount' => 0
        ];
        foreach ($monthlyData as $data) {
            if ($data['amount'] > $highestMonth['amount']) {
                $highestMonth = $data;
            }
        }

        $monthlyWithdrawalData = [];
        $maxWithdrawalAmount = 0;
        $processedMonths = [];
        
        for ($i = 5; $i >= 0; $i--) {
            $monthDate = now()->subMonths($i);
            $monthKey = $monthDate->format('Y-m');
            
            // Skip if we've already processed this month
            if (in_array($monthKey, $processedMonths)) {
                continue;
            }
            $processedMonths[] = $monthKey;
            
            $amount = (float) savings_transaction_tbl::where('type', 'withdrawal')
                ->whereYear('created_at', $monthDate->format('Y'))
                ->whereMonth('created_at', $monthDate->format('m'))
                ->sum('amount');
            
            $monthlyWithdrawalData[] = [
                'name' => $monthDate->format('F'),
                'year' => $monthDate->format('Y'),
                'amount' => $amount
            ];
            if ($amount > $maxWithdrawalAmount) {
                $maxWithdrawalAmount = $amount;
            }
        }
        
        // Ensure we have exactly 6 months
        if (count($monthlyWithdrawalData) > 6) {
            $monthlyWithdrawalData = array_slice($monthlyWithdrawalData, -6);
        }
        
        foreach ($monthlyWithdrawalData as &$data) {
            $data['bar_height'] = $maxWithdrawalAmount > 0 ? ($data['amount'] / $maxWithdrawalAmount) * 150 : 0;
        }

        \Log::info('Monthly Withdrawal Data (' . count($monthlyWithdrawalData) . ' months): ' . json_encode($monthlyWithdrawalData));

        $highestWithdrawalMonth = [
            'name' => 'N/A',
            'amount' => 0
        ];
        foreach ($monthlyWithdrawalData as $data) {
            if ($data['amount'] > $highestWithdrawalMonth['amount']) {
                $highestWithdrawalMonth = $data;
            }
        }

         return view("admin_components.savings", compact(
             'transactions',
             'currentBalance',
             'monthlyAvg',
             'lastContribution',
             'totalDeposits',
             'totalWithdrawals',
             'allMembers',
             'highestMonth',
             'monthlyData',
             'monthlyDeposits',
             'todayDeposits',
             'monthlyWithdrawals',
             'monthlyWithdrawalsCount',
             // 'monthlyWithdrawalData',  // Now calculated in Blade template
             // 'highestWithdrawalMonth',  // Now calculated in Blade template
             'monthlyWithdrawalAvg'
         ));
    }

    public function archiveSavings($id)
    {
        $transaction = savings_transaction_tbl::findOrFail($id);
        $transaction->archived = 1;
        $transaction->save();

        AuditLog::log(
            'Archived Savings Transaction',
            "Archived savings transaction (ID: {$id}, Ref: {$transaction->reference_no})",
            'savings',
            $id
        );

        return redirect()->back()->with('success', 'Transaction archived successfully.');
    }

    public function unarchiveSavings($id)
    {
        $transaction = savings_transaction_tbl::findOrFail($id);
        $transaction->archived = 0;
        $transaction->save();

        AuditLog::log(
            'Unarchived Savings Transaction',
            "Unarchived savings transaction (ID: {$id}, Ref: {$transaction->reference_no})",
            'savings',
            $id
        );

        return redirect()->back()->with('success', 'Transaction restored successfully.');
    }

    public function dashboard_lendings(Request $request)
    {
        $statusFilter = $request->get('status', 'all');
        $search = $request->get('search', '');

        $query = lending_program_tbl::with(['user', 'repayments' => function($q) { $q->select('id', 'lending_id', 'payment_number'); }])
            ->where('status', '!=', 'Archived');

        if ($statusFilter !== 'all') {
            $query->where('status', ucfirst($statusFilter));
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('reference_no', 'like', "%{$search}%")
                  ->orWhere('lending_type', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($uq) use ($search) {
                      $uq->where('first_name', 'like', "%{$search}%")
                         ->orWhere('last_name', 'like', "%{$search}%")
                         ->orWhereRaw("CONCAT(first_name, ' ', last_name) like ?", ["%{$search}%"]);
                  });
            });
        }

        $loans = $query->orderBy('created_at', 'desc')->paginate(10);

        $allMembers = Users_tbl::where('role', 'Member')
            ->orderBy('first_name')
            ->get();
        
        $loanSettings = Loan_settings_tbl::pluck('interest_rate', 'loan_type')->toArray();
        
        $lateFeeSettings = Loan_settings_tbl::first();
        $lateFeePercentage = $lateFeeSettings->late_fee_percentage ?? 2.00;
        $gracePeriodMonths = $lateFeeSettings->grace_period_months ?? 1;
        
        $penalizedLoans = $this->calculatePenalties($lateFeePercentage, $gracePeriodMonths);
        
        return view("admin_components.lending", compact('loans', 'statusFilter', 'search', 'allMembers', 'loanSettings', 'lateFeePercentage', 'gracePeriodMonths', 'penalizedLoans'));
    }

    private function calculatePenalties($lateFeePercentage, $gracePeriodMonths)
    {
        $today = now();
        $penalizedLoans = [];
        
        $approvedLoans = lending_program_tbl::with('user')
            ->where('status', 'Approved')
            ->get();
        
        foreach ($approvedLoans as $loan) {
            $termMonths = (int) filter_var($loan->lending_type_term, FILTER_SANITIZE_NUMBER_INT);
            
            if (!$loan->due_date && $loan->created_at) {
                $dueDate = $loan->created_at->addMonths($termMonths);
                $loan->due_date = $dueDate->format('Y-m-d');
                $loan->save();
            }
            
            if (!$loan->due_date) {
                continue;
            }
            
            $dueDate = \Carbon\Carbon::parse($loan->due_date);
            $penaltyStartDate = $dueDate->copy()->addMonths($gracePeriodMonths);
            
            if ($today->gte($penaltyStartDate)) {
                $monthsOverdue = $dueDate->diffInMonths($today) - $gracePeriodMonths;
                $monthsOverdue = max(0, $monthsOverdue);
                
                if ($monthsOverdue > 0) {
                    $lateFee = $loan->lending_amount * ($lateFeePercentage / 100) * $monthsOverdue;
                    
                    $loan->late_fee = $lateFee;
                    $loan->penalty_applied_at = now();
                    $loan->save();
                    
                    AuditLog::log(
                        'Applied Late Penalty',
                        "Applied ₱{$lateFee} late fee to loan #{$loan->id} ({$monthsOverdue} month(s) overdue)",
                        'loan_penalty',
                        $loan->id
                    );
                    
                    $penalizedLoans[] = [
                        'id' => $loan->id,
                        'member_name' => ($loan->user->first_name ?? 'Unknown') . ' ' . ($loan->user->last_name ?? ''),
                        'lending_amount' => $loan->lending_amount,
                        'due_date' => $loan->due_date,
                        'months_overdue' => $monthsOverdue,
                        'late_fee' => $lateFee,
                        'status' => 'Overdue'
                    ];
                }
            }
        }
        
        return $penalizedLoans;
    }

    public function updateLoanSettings(Request $request)
    {
        $request->validate([
            'late_fee_percentage' => 'required|numeric|min:0|max:100',
            'grace_period_months' => 'required|integer|min:0|max:12'
        ]);
        
        Loan_settings_tbl::query()->update([
            'late_fee_percentage' => $request->late_fee_percentage,
            'grace_period_months' => $request->grace_period_months
        ]);

        AuditLog::log(
            'Updated Loan Settings',
            "Updated loan penalty settings: late fee {$request->late_fee_percentage}%, grace period {$request->grace_period_months} month(s)",
            'settings',
            null
        );
        
        return redirect()->back()->with('success', 'Loan penalty settings updated successfully.');
    }

    public function createLoanAdmin(Request $request)
    {
        $request->validate([
            'member_id' => 'required|exists:users_tbls,id',
            'lending_type' => 'required|string',
            'lending_amount' => 'required|numeric|min:1',
            'lending_type_term' => 'required|string',
            'monthly_income' => 'required|numeric|min:0',
            'purpose_loan' => 'required|string',
            'monthly_payment' => 'required|numeric',
            'total_payment' => 'required|numeric',
            'total_interest' => 'required|numeric',
        ]);

        $member = Users_tbl::findOrFail($request->member_id);

        $latestLoan = lending_program_tbl::orderBy('reference_no', 'desc')->first();
        $newRefNumber = $latestLoan ? intval(substr($latestLoan->reference_no, 4)) + 1 : 1;
        $referenceNo = 'LN-' . str_pad($newRefNumber, 4, '0', STR_PAD_LEFT);

        $loan = lending_program_tbl::create([
            'user_id' => $request->member_id,
            'reference_no' => $referenceNo,
            'lending_type' => $request->lending_type,
            'lending_amount' => $request->lending_amount,
            'lending_type_term' => $request->lending_type_term,
            'monthly_income' => $request->monthly_income,
            'monthly_payment' => $request->monthly_payment,
            'total_payment' => $request->total_payment,
            'total_interest' => $request->total_interest,
            'purpose_loan' => $request->purpose_loan,
            'status' => 'Approved',
            'due_date' => now()->addMonths((int) filter_var($request->lending_type_term, FILTER_SANITIZE_NUMBER_INT))->format('Y-m-d'),
        ]);

        if ($request->hasFile('valid_id')) {
            $loan->valid_id = $request->file('valid_id')->store('loan_documents', 'public');
        }
        if ($request->hasFile('proof_of_income')) {
            $loan->proof_of_income = $request->file('proof_of_income')->store('loan_documents', 'public');
        }
        $loan->save();

        AuditLog::log(
            'Admin Created Loan',
            "Created {$request->lending_type} loan of ₱{$request->lending_amount} for {$member->first_name} {$member->last_name} (Ref: {$referenceNo})",
            'loan',
            $loan->id
        );

        return redirect()->back()->with('success', 'Loan created successfully for ' . $member->first_name . ' ' . $member->last_name);
    }

    public function approveLoan(Request $request, $id)
    {
        $loan = lending_program_tbl::findOrFail($id);
        $loan->status = 'Approved';
        $loan->save();

        $user = $loan->user;
        AuditLog::log(
            'Approved Loan',
            "Approved loan (Ref: {$loan->reference_no}) for {$user->first_name} {$user->last_name} - ₱" . number_format($loan->lending_amount, 2),
            'loan',
            $id
        );

        return redirect()->back()->with('success', 'Loan application approved successfully.');
    }

    public function declineLoan(Request $request, $id)
    {
        $request->validate([
            'decline_reason' => 'required|string|max:500'
        ]);

        $loan = lending_program_tbl::findOrFail($id);
        $loan->status = 'Declined';
        $loan->decline_reason = $request->decline_reason;
        $loan->save();

        $user = $loan->user;
        AuditLog::log(
            'Declined Loan',
            "Declined loan (Ref: {$loan->reference_no}) for {$user->first_name} {$user->last_name} - Reason: {$request->decline_reason}",
            'loan',
            $id
        );

        return redirect()->back()->with('error', 'Loan application declined.');
    }

    public function archiveLoan($id)
    {
        $loan = lending_program_tbl::findOrFail($id);
        $loan->status = 'Archived';
        $loan->save();

        AuditLog::log(
            'Archived Loan',
            "Archived loan (ID: {$id}, Ref: {$loan->reference_no})",
            'loan',
            $id
        );

        return redirect()->back()->with('success', 'Loan archived successfully.');
    }

    public function unarchiveLoan($id)
    {
        $loan = lending_program_tbl::findOrFail($id);
        $loan->status = 'Approved';
        $loan->save();

        AuditLog::log(
            'Unarchived Loan',
            "Unarchived loan (ID: {$id}, Ref: {$loan->reference_no})",
            'loan',
            $id
        );

        return redirect()->back()->with('success', 'Loan restored successfully.');
    }

    public function dashboard_sharecapitals(Request $request)
    {
        $search = $request->get('search', '');
        $typeFilter = $request->get('type', 'all');
        $statusFilter = $request->get('status', 'all');

        $query = share_capital_transaction_tbl::with('shareCapitalAccount.user')
            ->where('archived', '!=', 1)
            ->where('status', '!=', 'failed');

        if ($search) {
            $query->whereHas('shareCapitalAccount.user', function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%");
            });
        }

        if ($typeFilter !== 'all') {
            $query->where('type', $typeFilter);
        }

        if ($statusFilter !== 'all') {
            $query->where('status', $statusFilter);
        }

        $transactions = $query->orderBy('created_at', 'desc')->paginate(10);

        $totalContributions = share_capital_transaction_tbl::whereIn('type', ['Deposit', 'Subscription'])
            ->where('status', 'Completed')
            ->sum('total_amount') ?? 0;

        $totalContributions = $totalContributions - (
            share_capital_transaction_tbl::where('type', 'Withdrawal')
            ->whereIn('status', ['Approved', 'approved'])
            ->sum('total_amount') ?? 0
        );

        $perShareValue = 1000;
        
        $deposits = share_capital_transaction_tbl::whereIn('type', ['Deposit', 'Subscription'])
            ->whereIn('status', ['Completed', 'completed'])
            ->sum('shares') ?? 0;
        
        $withdrawals = share_capital_transaction_tbl::where('type', 'Withdrawal')
            ->whereIn('status', ['Approved', 'approved'])
            ->sum('shares') ?? 0;
        
        $totalShares = $deposits - $withdrawals;
        $currentValue = $totalShares * $perShareValue;

        $lastContribution = share_capital_transaction_tbl::orderBy('created_at', 'desc')->first();

        $allMembers = Users_tbl::whereIn('role', ['member', 'pending'])
            ->select('id', 'first_name', 'last_name')
            ->orderBy('first_name')
            ->get();

        $pendingReleases = \App\Models\ResignationRequest_tbl::with('user.shareCapitalAccount')
            ->where('status', 'approved')
            ->where('withdraw_share_capital', true)
            ->where('is_released', false)
            ->orderBy('release_date', 'asc')
            ->get();

        return view("admin_components.sharecapitals", compact(
            'transactions',
            'totalContributions',
            'currentValue',
            'perShareValue',
            'lastContribution',
            'allMembers',
            'pendingReleases'
        ));
    }

    public function archiveShareCapital($id)
    {
        $transaction = share_capital_transaction_tbl::findOrFail($id);
        $transaction->archived = 1;
        $transaction->save();

        AuditLog::log(
            'Archived Share Capital Transaction',
            "Archived share capital transaction (ID: {$id}, Ref: {$transaction->reference_no})",
            'share_capital',
            $id
        );

        return redirect()->back()->with('success', 'Share capital transaction archived successfully.');
    }

    public function unarchiveShareCapital($id)
    {
        $transaction = share_capital_transaction_tbl::findOrFail($id);
        $transaction->archived = 0;
        $transaction->save();

        AuditLog::log(
            'Unarchived Share Capital Transaction',
            "Unarchived share capital transaction (ID: {$id}, Ref: {$transaction->reference_no})",
            'share_capital',
            $id
        );

        return redirect()->back()->with('success', 'Share capital transaction restored successfully.');
    }

    public function adminStoreShareCapital(Request $request)
    {
        $request->validate([
            'member_id' => 'required|exists:users_tbls,id',
            'shares' => 'required|numeric|min:0.5',
            'type' => 'required|string|in:Deposit,Withdrawal',
            'payment_method' => 'required|string|in:cash,bank_transfer,gcash,check',
            'note' => 'nullable|string|max:255',
        ]);

        $memberId = $request->member_id;
        $shares = (float) $request->shares;
        $amountPerShare = 1000;
        $totalAmount = $shares * $amountPerShare;
        $type = $request->type;
        $perShareValue = 1000;

        $account = share_capital_account_tbl::where('user_id', $memberId)->first();

        if (!$account) {
            $account = share_capital_account_tbl::create([
                'user_id' => $memberId,
                'total_shares' => 0,
                'total_amount' => 0,
                'status' => 'Active',
                'acquired_date' => Carbon::now(),
            ]);
        }

        if ($type === 'Withdrawal') {
            if ($account->total_shares < $shares) {
                return response()->json([
                    'success' => false,
                    'message' => 'Insufficient shares. Available: ' . $account->total_shares . ' shares'
                ], 422);
            }

            $newShares = $account->total_shares - $shares;
            $newAmount = $account->total_amount - $totalAmount;

            // Full withdrawal via admin → auto-resignation
            if ($newShares <= 0) {
                $existing = \App\Models\ResignationRequest_tbl::where('user_id', $memberId)
                    ->whereIn('status', ['pending'])
                    ->first();

                if (!$existing) {
                    \App\Models\ResignationRequest_tbl::create([
                        'user_id' => $memberId,
                        'withdraw_share_capital' => true,
                        'status' => 'pending',
                    ]);

                    Users_tbl::where('id', $memberId)->update(['status' => 'resignation_pending']);
                }

                AuditLog::log(
                    'Admin Withdrew All Share Capital',
                    "Full withdrawal of {$account->total_shares} shares for member (ID: {$memberId}) triggered auto-resignation",
                    'share_capital',
                    $account->id
                );

                return response()->json([
                    'success' => true,
                    'warning' => true,
                    'message' => 'Full withdrawal processed. An auto-resignation request has been created for this member, subject to the 60-day release rule.',
                    'reference_no' => 'N/A',
                    'new_shares' => 0,
                    'new_amount' => 0,
                ]);
            }
        } else {
            $newShares = $account->total_shares + $shares;
            $newAmount = $account->total_amount + $totalAmount;
        }

        $account->update([
            'total_shares' => $newShares,
            'total_amount' => $newAmount,
        ]);

        $referenceNo = 'SC-' . date('YmdHis') . rand(10, 99);

        share_capital_transaction_tbl::create([
            'share_capital_account_id' => $account->id,
            'user_id' => $memberId,
            'type' => $type,
            'shares' => $shares,
            'amount_per_share' => $amountPerShare,
            'total_amount' => $totalAmount,
            'payment_method' => $request->payment_method,
            'reference_no' => $referenceNo,
            'transaction_date' => Carbon::today(),
            'status' => 'Completed',
            'note' => $request->note,
        ]);

        $member = Users_tbl::find($memberId);
        AuditLog::log(
            'Admin ' . ucfirst($type) . ' Share Capital',
            ucfirst($type) . " of {$shares} shares (₱{$totalAmount}) for {$member?->first_name} {$member?->last_name} (Ref: {$referenceNo})",
            'share_capital',
            $account->id
        );

        return response()->json([
            'success' => true,
            'message' => ucfirst($type) . ' of ' . $shares . ' shares (₱' . number_format($totalAmount, 2) . ') successful!',
            'reference_no' => $referenceNo,
            'new_shares' => $newShares,
            'new_amount' => $newAmount,
        ]);
    }

    public function getMemberShareCapitalBalance($memberId)
    {
        $account = share_capital_account_tbl::where('user_id', $memberId)->first();
        
        return response()->json([
            'total_shares' => $account ? $account->total_shares : 0,
            'total_amount' => $account ? $account->total_amount : 0,
        ]);
    }

    public function updateWithdrawalStatus(Request $request, $id)
    {
        $request->validate([
            'action' => 'required|string|in:accept,reject',
        ]);

        $transaction = share_capital_transaction_tbl::findOrFail($id);

        if ($transaction->type !== 'Withdrawal') {
            return response()->json([
                'success' => false,
                'message' => 'This is not a withdrawal transaction.',
            ], 400);
        }

        if ($transaction->status !== 'Pending') {
            return response()->json([
                'success' => false,
                'message' => 'This withdrawal has already been processed.',
            ], 400);
        }

        $action = $request->action;

        if ($action === 'accept') {
            $account = DB::table('share_capital_account_tbls')
                ->where('id', $transaction->share_capital_account_id)
                ->first();

            if (!$account || $account->total_shares < $transaction->shares) {
                return response()->json([
                    'success' => false,
                    'message' => 'Insufficient shares to process this withdrawal.',
                ], 400);
            }

            DB::table('share_capital_account_tbls')
                ->where('id', $transaction->share_capital_account_id)
                ->decrement('total_shares', $transaction->shares);

            DB::table('share_capital_account_tbls')
                ->where('id', $transaction->share_capital_account_id)
                ->decrement('total_amount', $transaction->total_amount);

            $transaction->status = 'Approved';
            $transaction->save();

            AuditLog::log(
                'Approved Share Capital Withdrawal',
                "Approved withdrawal of {$transaction->shares} shares (Transaction ID: {$id})",
                'share_capital',
                $id
            );

            return response()->json([
                'success' => true,
                'message' => 'Withdrawal request accepted successfully.',
            ]);
        } else {
            $transaction->status = 'Rejected';
            $transaction->save();

            AuditLog::log(
                'Rejected Share Capital Withdrawal',
                "Rejected withdrawal of {$transaction->shares} shares (Transaction ID: {$id})",
                'share_capital',
                $id
            );

            return response()->json([
                'success' => true,
                'message' => 'Withdrawal request rejected.',
            ]);
        }
    }

    public function dashboard_reports(Request $request)
    {
        $fromDate = $request->get('from_date', now()->startOfMonth()->format('Y-m-d'));
        $toDate = $request->get('to_date', now()->endOfMonth()->format('Y-m-d'));
        $reportType = $request->get('report_type', 'all');
        $chartType = $request->get('chart', 'all');

        $totalDeposits = savings_transaction_tbl::where('type', 'deposit')
            ->whereBetween('created_at', [$fromDate, $toDate . ' 23:59:59'])
            ->sum('amount') ?? 0;

        $totalWithdrawals = savings_transaction_tbl::where('type', 'withdrawal')
            ->whereBetween('created_at', [$fromDate, $toDate . ' 23:59:59'])
            ->sum('amount') ?? 0;

        $loansIssued = lending_program_tbl::where('status', 'Approved')
            ->whereBetween('created_at', [$fromDate, $toDate . ' 23:59:59'])
            ->sum('lending_amount') ?? 0;

        $loanInterest = lending_program_tbl::where('status', 'Approved')
            ->whereBetween('created_at', [$fromDate, $toDate . ' 23:59:59'])
            ->sum('total_interest') ?? 0;

        $netIncome = $loanInterest;

        $savingsTrend = [];
        $lendingTrend = [];
        $shareCapitalTrend = [];
        $months = [];

        for ($i = 5; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $months[] = $month->format('M');
            $monthNum = $month->format('n');
            $year = $month->format('Y');

            $deposits = savings_transaction_tbl::where('type', 'deposit')
                ->whereYear('created_at', $year)
                ->whereMonth('created_at', $monthNum)
                ->sum('amount') ?? 0;

            $loans = lending_program_tbl::where('status', 'Approved')
                ->whereYear('created_at', $year)
                ->whereMonth('created_at', $monthNum)
                ->sum('lending_amount') ?? 0;

            $depositCap = share_capital_transaction_tbl::whereIn('type', ['Deposit', 'Subscription'])
                ->where('status', 'Completed')
                ->whereYear('created_at', $year)
                ->whereMonth('created_at', $monthNum)
                ->sum('total_amount') ?? 0;

            $withdrawCap = share_capital_transaction_tbl::where('type', 'Withdrawal')
                ->whereIn('status', ['Approved', 'approved'])
                ->whereYear('created_at', $year)
                ->whereMonth('created_at', $monthNum)
                ->sum('total_amount') ?? 0;

            $shareCap = $depositCap - $withdrawCap;

            $savingsTrend[] = round($deposits / 1000, 1);
            $lendingTrend[] = round($loans / 1000, 1);
            $shareCapitalTrend[] = round($shareCap / 1000, 1);
        }

        $savingsByMonth = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $monthName = $month->format('M');
            $monthNum = $month->format('n');
            $year = $month->format('Y');

            $deposits = savings_transaction_tbl::where('type', 'deposit')
                ->whereYear('created_at', $year)
                ->whereMonth('created_at', $monthNum)
                ->sum('amount') ?? 0;

            $loans = lending_program_tbl::where('status', 'Approved')
                ->whereYear('created_at', $year)
                ->whereMonth('created_at', $monthNum)
                ->sum('lending_amount') ?? 0;

            $savingsByMonth[$monthName] = [
                'savings' => round($deposits / 1000, 1),
                'loans' => round($loans / 1000, 1)
            ];
        }

        $transactions = DB::table('savings_transaction_tbls as st')
            ->select(
                'st.created_at',
                'st.reference_no',
                'st.amount',
                'st.type',
                DB::raw("CONCAT(u.first_name, ' ', u.last_name) as member_name"),
                DB::raw("'Savings' as category")
            )
            ->leftJoin('savings_account_tbls as sa', 'st.savings_account_id', '=', 'sa.id')
            ->leftJoin('users_tbls as u', 'sa.user_id', '=', 'u.id')
            ->whereBetween('st.created_at', [$fromDate, $toDate . ' 23:59:59'])
            ->orderBy('st.created_at', 'desc')
            ->limit(50)
            ->get();

        $deposits = DB::table('savings_transaction_tbls as st')
            ->select(
                'st.created_at',
                'st.reference_no',
                'st.amount',
                'st.type',
                DB::raw("CONCAT(u.first_name, ' ', u.last_name) as member_name")
            )
            ->leftJoin('savings_account_tbls as sa', 'st.savings_account_id', '=', 'sa.id')
            ->leftJoin('users_tbls as u', 'sa.user_id', '=', 'u.id')
            ->where('st.type', 'deposit')
            ->whereBetween('st.created_at', [$fromDate, $toDate . ' 23:59:59'])
            ->orderBy('st.created_at', 'desc')
            ->limit(10)
            ->get();

        $withdrawals = DB::table('savings_transaction_tbls as st')
            ->select(
                'st.created_at',
                'st.reference_no',
                'st.amount',
                'st.type',
                DB::raw("CONCAT(u.first_name, ' ', u.last_name) as member_name")
            )
            ->leftJoin('savings_account_tbls as sa', 'st.savings_account_id', '=', 'sa.id')
            ->leftJoin('users_tbls as u', 'sa.user_id', '=', 'u.id')
            ->where('st.type', 'withdrawal')
            ->whereBetween('st.created_at', [$fromDate, $toDate . ' 23:59:59'])
            ->orderBy('st.created_at', 'desc')
            ->limit(10)
            ->get();

        $loans = lending_program_tbl::with('user')
            ->where('status', 'Approved')
            ->whereBetween('created_at', [$fromDate, $toDate . ' 23:59:59'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get()
            ->map(function ($loan) {
                return [
                    'created_at' => $loan->created_at,
                    'reference_no' => $loan->reference_no,
                    'amount' => $loan->lending_amount,
                    'purpose' => $loan->purpose_loan,
                    'status' => $loan->status,
                    'member_name' => ($loan->user->first_name ?? 'Unknown') . ' ' . ($loan->user->last_name ?? '')
                ];
            });

        $depositsCount = DB::table('savings_transaction_tbls as st')
            ->where('st.type', 'deposit')
            ->whereBetween('st.created_at', [$fromDate, $toDate . ' 23:59:59'])
            ->count();

        $withdrawalsCount = DB::table('savings_transaction_tbls as st')
            ->where('st.type', 'withdrawal')
            ->whereBetween('st.created_at', [$fromDate, $toDate . ' 23:59:59'])
            ->count();

        $loansCount = lending_program_tbl::where('status', 'Approved')
            ->whereBetween('created_at', [$fromDate, $toDate . ' 23:59:59'])
            ->count();

        return view("admin_components.reports", compact(
            'fromDate',
            'toDate',
            'reportType',
            'chartType',
            'totalDeposits',
            'totalWithdrawals',
            'loansIssued',
            'netIncome',
            'savingsByMonth',
            'savingsTrend',
            'lendingTrend',
            'shareCapitalTrend',
            'months',
            'transactions',
            'deposits',
            'withdrawals',
            'loans',
            'depositsCount',
            'withdrawalsCount',
            'loansCount'
        ));
    }

    public function dashboard_settings(Request $request)
    {
        $adminUser = Auth::user();
        $companySettings = [
            'company_name' => system_settings_tbl::getValue('company_name', 'CoopAdmin Savings and Loan Cooperative'),
            'registration_number' => system_settings_tbl::getValue('registration_number', 'RN-2024-001234'),
            'company_address' => system_settings_tbl::getValue('company_address', ''),
            'company_phone' => system_settings_tbl::getValue('company_phone', ''),
            'company_email' => system_settings_tbl::getValue('company_email', ''),
        ];

        // Show ALL non-member users (admin, officer, secretary, treasurer, etc.)
        $adminList = Users_tbl::whereNotIn('role', ['member', 'pending', 'inactive'])
            ->orderBy('id')
            ->get()
            ->map(function ($user) {
                return [
                    'id' => $user->id,
                    'first_name' => $user->first_name,
                    'last_name' => $user->last_name,
                    'email' => $user->email,
                    'username' => $user->username,
                    'role' => $user->role,
                    'status' => $user->status,
                    'sidebar_permissions' => $user->sidebar_permissions,
                    'is_main' => $user->isMainAdmin(),
                ];
            });

        $roles = \App\Models\Role::orderBy('name')->get();

        $paymentMethods = \App\Models\PaymentMethod::orderBy('id')->get();

        // Count users per role for dynamic admin categories
        $roleCounts = [];
        foreach ($roles as $role) {
            $roleCounts[$role->slug] = Users_tbl::where('role', $role->slug)->count();
        }

        return view("admin_components.settings", compact('adminUser', 'companySettings', 'adminList', 'roles', 'roleCounts', 'paymentMethods'));
    }

    public function updateAdmin(Request $request)
    {
        if (!auth()->user()->isMainAdmin()) {
            return response()->json(['success' => false, 'message' => 'Only the main admin can update admin accounts.'], 403);
        }

        $validated = $request->validate([
            'id'                   => 'required|exists:users_tbls,id',
            'first_name'           => 'required|string|max:255',
            'last_name'            => 'required|string|max:255',
            'email'                => 'required|email|unique:users_tbls,email,' . $request->id,
            'role'                 => 'required|string|exists:roles,slug',
            'sidebar_permissions'  => 'nullable|array',
            'sidebar_permissions.*' => 'string',
        ]);

        $user = Users_tbl::findOrFail($validated['id']);

        if ($user->isMainAdmin() && auth()->id() !== $user->id) {
            return response()->json(['success' => false, 'message' => 'Cannot modify the main admin account.'], 403);
        }

        $user->update([
            'first_name'          => $validated['first_name'],
            'last_name'           => $validated['last_name'],
            'email'               => $validated['email'],
            'role'                => $validated['role'],
            'sidebar_permissions' => $validated['sidebar_permissions'] ?? [],
        ]);

        AuditLog::log(
            'Updated Admin',
            "Updated admin {$validated['first_name']} {$validated['last_name']} (ID: {$validated['id']})",
            'user',
            $validated['id']
        );

        return response()->json(['success' => true, 'message' => 'Admin account updated successfully.']);
    }

    public function deleteAdmin(Request $request)
    {
        if (!auth()->user()->isMainAdmin()) {
            return response()->json(['success' => false, 'message' => 'Only the main admin can delete admin accounts.'], 403);
        }

        $request->validate(['id' => 'required|exists:users_tbls,id']);

        $user = Users_tbl::findOrFail($request->id);

        if ($user->isMainAdmin()) {
            return response()->json(['success' => false, 'message' => 'Cannot delete the main admin account.'], 403);
        }

        AuditLog::log(
            'Deactivated Admin',
            "Deactivated admin {$user->first_name} {$user->last_name} (ID: {$request->id})",
            'user',
            $request->id
        );

        $user->update(['role' => 'inactive', 'status' => 'inactive']);

        return response()->json(['success' => true, 'message' => 'Admin account deactivated successfully.']);
    }

    public function toggleAdminStatus(Request $request)
    {
        if (!auth()->user()->isMainAdmin()) {
            return response()->json(['success' => false, 'message' => 'Only the main admin can change admin status.'], 403);
        }

        $validated = $request->validate([
            'id'     => 'required|exists:users_tbls,id',
            'status' => 'required|in:active,inactive,pending',
        ]);

        $user = Users_tbl::findOrFail($validated['id']);

        if ($user->isMainAdmin()) {
            return response()->json(['success' => false, 'message' => 'Cannot modify the main admin status.'], 403);
        }

        $user->update(['status' => $validated['status']]);

        AuditLog::log(
            'Changed Admin Status',
            "Changed admin {$user->first_name} {$user->last_name} (ID: {$validated['id']}) status to {$validated['status']}",
            'user',
            $validated['id']
        );

        return response()->json(['success' => true, 'message' => 'Admin status updated to ' . ucfirst($validated['status']) . '.']);
    }

    public function storeAdmin(Request $request)
    {
        if (!auth()->user()->isMainAdmin()) {
            abort(403, 'Only the main admin can create admin or officer accounts.');
        }

        $validated = $request->validate([
            'first_name'           => 'required|string|max:255',
            'last_name'            => 'required|string|max:255',
            'email'                => 'required|email|unique:users_tbls,email',
            'password'             => 'required|string|min:8',
            'role'                 => 'required|string|exists:roles,slug',
            'sidebar_permissions'  => 'nullable|array',
            'sidebar_permissions.*' => 'string',
        ]);

        $base = strtolower(preg_replace('/[^a-z0-9]/', '', $validated['first_name'] . '.' . $validated['last_name']));
        $username = $base;
        $counter = 1;
        while (Users_tbl::where('username', $username)->exists()) {
            $username = $base . $counter;
            $counter++;
        }

        $user = Users_tbl::create([
            'first_name'          => $validated['first_name'],
            'last_name'           => $validated['last_name'],
            'email'               => $validated['email'],
            'password'            => bcrypt($validated['password']),
            'role'                => $validated['role'],
            'username'            => $username,
            'status'              => 'active',
            'sidebar_permissions' => $validated['sidebar_permissions'] ?? [],
        ]);

        DB::table('otherinfo_tbls')->insert([
            'user_id'            => $user->id,
            'membership_category' => 'Admin',
            'signature'          => null,
            'approval_status'    => 'Approved',
            'created_at'         => now(),
            'updated_at'         => now(),
        ]);

        AuditLog::log(
            'Created Admin',
            "Created admin account for {$validated['first_name']} {$validated['last_name']} (ID: {$user->id}) with role {$validated['role']}",
            'user',
            $user->id
        );

        return redirect()->route('settings', ['#admin-management'])
            ->with('admin_created', true);
    }

    public function storeRole(Request $request)
    {
        if (!auth()->user()->isMainAdmin()) {
            return response()->json(['success' => false, 'message' => 'Only the main admin can create roles.'], 403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:roles,name',
            'slug' => 'required|string|max:100|unique:roles,slug',
            'description' => 'nullable|string|max:255',
        ]);

        $role = \App\Models\Role::create([
            'name' => $validated['name'],
            'slug' => $validated['slug'],
            'description' => $validated['description'] ?? null,
            'is_system' => false,
        ]);

        AuditLog::log(
            'Created Role',
            "Created role '{$role->name}' ({$role->slug})",
            'role',
            $role->id
        );

        return response()->json([
            'success' => true,
            'message' => "Role '{$role->name}' created successfully.",
            'role' => $role,
        ]);
    }

    public function deleteRole(Request $request)
    {
        if (!auth()->user()->isMainAdmin()) {
            return response()->json(['success' => false, 'message' => 'Only the main admin can delete roles.'], 403);
        }

        $validated = $request->validate([
            'id' => 'required|exists:roles,id',
        ]);

        $role = \App\Models\Role::findOrFail($validated['id']);

        if ($role->is_system) {
            return response()->json(['success' => false, 'message' => 'System roles cannot be deleted.'], 403);
        }

        $usersCount = Users_tbl::where('role', $role->slug)->count();
        if ($usersCount > 0) {
            return response()->json([
                'success' => false,
                'message' => "Cannot delete '{$role->name}' — {$usersCount} user(s) are assigned this role."
            ], 409);
        }

        AuditLog::log(
            'Deleted Role',
            "Deleted role '{$role->name}' ({$role->slug})",
            'role',
            $validated['id']
        );

        $role->delete();

        return response()->json([
            'success' => true,
            'message' => "Role '{$role->name}' deleted successfully."
        ]);
    }

    public function storeCooperativeTransaction(Request $request)
    {
        $request->validate([
            'description' => 'required|string|max:500',
            'category' => 'required|string|in:Vehicle Purchase,Bank Investment,Office Equipment,Utilities,Maintenance,Other',
            'transaction_type' => 'required|string|in:expense,investment',
            'amount' => 'required|numeric|min:0.01',
            'transaction_date' => 'required|date',
        ]);

        try {
            $transaction = CooperativeTransaction::create([
                'description' => $request->description,
                'category' => $request->category,
                'transaction_type' => $request->transaction_type,
                'amount' => $request->amount,
                'transaction_date' => $request->transaction_date,
            ]);

            AuditLog::log(
                'Recorded Cooperative Transaction',
                "Recorded {$request->transaction_type} of ₱{$request->amount} ({$request->category}: {$request->description})",
                'cooperative_transaction',
                $transaction->id
            );

            return response()->json([
                'success' => true,
                'message' => 'Transaction recorded successfully!',
                'transaction' => $transaction,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to record transaction: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function dashboard_financial_activity(Request $request)
    {
        $loanSettings = Loan_settings_tbl::pluck('interest_rate', 'loan_type')->toArray();

        $lateFeeSettings = Loan_settings_tbl::first();
        $lateFeePercentage = $lateFeeSettings->late_fee_percentage ?? 2.00;
        $gracePeriodMonths = $lateFeeSettings->grace_period_months ?? 1;

        if ($request->isMethod('POST')) {
            $request->validate([
                'interest_personal' => 'nullable|numeric|min:0|max:20',
                'interest_emergency' => 'nullable|numeric|min:0|max:20',
                'interest_business' => 'nullable|numeric|min:0|max:20',
                'interest_education' => 'nullable|numeric|min:0|max:20',
            ]);

            if ($request->has('interest_personal')) {
                Loan_settings_tbl::where('loan_type', 'Personal Loan')->update(['interest_rate' => $request->interest_personal]);
            }
            if ($request->has('interest_emergency')) {
                Loan_settings_tbl::where('loan_type', 'Emergency Loan')->update(['interest_rate' => $request->interest_emergency]);
            }
            if ($request->has('interest_business')) {
                Loan_settings_tbl::where('loan_type', 'Business Loan')->update(['interest_rate' => $request->interest_business]);
            }
            if ($request->has('interest_education')) {
                Loan_settings_tbl::where('loan_type', 'Education Loan')->update(['interest_rate' => $request->interest_education]);
            }

            $loanSettings = Loan_settings_tbl::pluck('interest_rate', 'loan_type')->toArray();

            AuditLog::log(
                'Updated Interest Rates',
                "Updated loan interest rates: Personal={$request->interest_personal}%, Emergency={$request->interest_emergency}%, Business={$request->interest_business}%, Education={$request->interest_education}%",
                'settings',
                null
            );
        }

        // Dividend data
        $year = $request->get('year', now()->year);
        $distribution = DB::table('dividend_distributions')
            ->where('year', $year)
            ->first();

        $dividends = collect();
        $approvedCount = 0;
        $disbursedCount = 0;
        $totalSumShareCapital = 0;
        $totalSumRecommended = 0;
        $totalSumApproved = 0;
        $currentYear = now()->year;
        $years = DB::table('dividend_distributions')
            ->orderByDesc('year')
            ->pluck('year');

        $dividendSetting = \App\Models\DividendSetting::where('year', $year)->first();
        $dividendFundPercentage = $dividendSetting ? $dividendSetting->dividend_fund_percentage : 60.00;

        if ($distribution) {
            $dividends = Dividend::with('user')
                ->where('year', $year)
                ->orderBy('id')
                ->paginate(10)
                ->appends(['year' => $year]);

            $approvedCount = Dividend::where('year', $year)->where('status', 'approved')->count();
            $disbursedCount = Dividend::where('year', $year)->where('status', 'disbursed')->count();
            $totalSumShareCapital = Dividend::where('year', $year)->sum('share_capital_amount');
            $totalSumRecommended = Dividend::where('year', $year)->sum('recommended_amount');
            $totalSumApproved = Dividend::where('year', $year)->sum('approved_amount');
        }

        $cooperativeTransactions = CooperativeTransaction::orderBy('created_at', 'desc')->take(10)->get();
        $cooperativeStats = [
            'total_expenses' => CooperativeTransaction::where('transaction_type', 'expense')->sum('amount'),
            'total_investments' => CooperativeTransaction::where('transaction_type', 'investment')->sum('amount'),
        ];

        return view("admin_components.financial_activity", compact(
            'loanSettings', 'lateFeePercentage', 'gracePeriodMonths',
            'distribution', 'dividends', 'year', 'years', 'currentYear',
            'approvedCount', 'disbursedCount',
            'totalSumShareCapital', 'totalSumRecommended', 'totalSumApproved',
            'cooperativeTransactions', 'cooperativeStats',
            'dividendFundPercentage'
        ));
    }

    public function dashboard_payments(Request $request)
    {
        $method = $request->get('method', 'all');

        $query = lending_repayments_tbl::with(['lending.user', 'user']);

        if ($method !== 'all') {
            $query->where('payment_method', $method);
        }

        $payments = $query->orderBy('created_at', 'desc')->paginate(15);

        $allMembers = Users_tbl::whereIn('role', ['member', 'pending'])
            ->orderBy('first_name')
            ->get(['id', 'first_name', 'last_name']);

        $paymentMethods = \App\Models\PaymentMethod::where('is_active', true)->orderBy('id')->get();

        return view("admin_components.payments", compact('payments', 'method', 'allMembers', 'paymentMethods'));
    }

    public function adminStoreRepayment(Request $request)
    {
        $request->validate([
            'member_id' => 'required|exists:users_tbls,id',
            'lending_id' => 'required|exists:lending_program_tbls,id',
            'amount_paid' => 'required|numeric|min:1',
            'payment_method' => 'required|string',
            'reference_no' => 'nullable|string|max:255',
            'payment_date' => 'required|date',
        ]);

        $loan = lending_program_tbl::findOrFail($request->lending_id);
        $status = lending_status_tbl::firstOrCreate(
            ['lending_id' => $request->lending_id],
            [
                'user_id' => $request->member_id,
                'remaining_balance' => $loan->total_payment,
                'total_paid' => 0,
                'payments_made' => 0,
                'total_payments' => max(1, (int) filter_var($loan->lending_type_term ?? '6', FILTER_SANITIZE_NUMBER_INT)),
                'interest_rate' => $loan->total_interest > 0 && $loan->lending_amount > 0
                    ? round(($loan->total_interest / $loan->lending_amount) * 100, 2) : 0,
                'due_date' => now()->addMonth()->format('Y-m-d'),
                'status' => 'Active',
            ]
        );

        DB::beginTransaction();
        try {
            $paymentsMade = lending_repayments_tbl::where('lending_id', $request->lending_id)->count();

            $repayment = lending_repayments_tbl::create([
                'lending_id' => $request->lending_id,
                'user_id' => $request->member_id,
                'payment_number' => $paymentsMade + 1,
                'amount_paid' => $request->amount_paid,
                'payment_date' => $request->payment_date,
                'payment_method' => $request->payment_method,
                'reference_no' => $request->reference_no ?: 'ADMIN-' . now()->format('YmdHis'),
                'notes' => 'Recorded by admin',
                'recorded_by' => auth()->id(),
            ]);

            if ($status) {
                $status->total_paid += $request->amount_paid;
                $status->remaining_balance = max(0, $status->remaining_balance - $request->amount_paid);
                $status->payments_made += 1;

                if ($status->remaining_balance <= 0 || $status->payments_made >= $status->total_payments) {
                    $status->status = 'Completed';
                    $status->payments_made = $status->total_payments;
                    $loan->update(['status' => 'Completed']);
                }

                $status->save();
            }

            DB::commit();

            $member = Users_tbl::find($request->member_id);
            AuditLog::log(
                'Admin Recorded Loan Repayment',
                "Recorded loan repayment of ₱{$request->amount_paid} for {$member?->first_name} {$member?->last_name} (Loan ID: {$request->lending_id})",
                'loan',
                $request->lending_id
            );

            return response()->json([
                'success' => true,
                'message' => 'Payment recorded successfully! Ref: ' . ($repayment->reference_no ?? 'N/A'),
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to record payment: ' . $e->getMessage(),
            ], 422);
        }
    }

    public function dashboard_officers_committees()
    {
        return view("admin_components.officers_committees");
    }

    public function dashboard_archives(Request $request)
    {
        $activeTab = $request->get('tab', 'savings');
        $fromDate = $request->get('from_date', '');
        $toDate = $request->get('to_date', '');

        $savingsQuery = savings_transaction_tbl::with('savingsAccount.user')
            ->where('archived', 1);

        $shareCapitalQuery = share_capital_transaction_tbl::with('shareCapitalAccount.user')
            ->where('archived', 1);

        $lendingQuery = lending_program_tbl::with('user')
            ->where('status', 'Archived');

        if ($fromDate) {
            $savingsQuery->whereDate('created_at', '>=', $fromDate);
            $shareCapitalQuery->whereDate('created_at', '>=', $fromDate);
            $lendingQuery->whereDate('created_at', '>=', $fromDate);
        }

        if ($toDate) {
            $savingsQuery->whereDate('created_at', '<=', $toDate);
            $shareCapitalQuery->whereDate('created_at', '<=', $toDate);
            $lendingQuery->whereDate('created_at', '<=', $toDate);
        }

        $savingsArchives = (clone $savingsQuery)->orderBy('created_at', 'desc')->paginate(15);
        $shareCapitalArchives = (clone $shareCapitalQuery)->orderBy('created_at', 'desc')->paginate(15);
        $lendingArchives = (clone $lendingQuery)->orderBy('created_at', 'desc')->paginate(15);

        $savingsCount = (clone $savingsQuery)->count();
        $shareCapitalCount = (clone $shareCapitalQuery)->count();
        $lendingCount = (clone $lendingQuery)->count();

        return view("admin_components.archives", compact(
            'activeTab',
            'fromDate',
            'toDate',
            'savingsArchives',
            'shareCapitalArchives',
            'lendingArchives',
            'savingsCount',
            'shareCapitalCount',
            'lendingCount'
        ));
    }
}
