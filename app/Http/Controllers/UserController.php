<?php

namespace App\Http\Controllers;

use App\Models\educational_tbl;
use App\Models\Otherinfo_tbl;
use App\Models\Spouse_tbl;
use App\Models\Users_tbl;
use App\Models\Membervehi_tbl;
use App\Models\savings_account_tbl;
use App\Models\savings_transaction_tbl;
use App\Models\share_capital_account_tbl;
use App\Models\share_capital_transaction_tbl;
use App\Models\lending_program_tbl;
use App\Models\lending_status_tbl;
use App\Models\Loan_settings_tbl;
use App\Models\system_settings_tbl;
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
        $user->role = 'Member';
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

        Mail::to($user->email)->sendNow(new ShareCapital($user));

        return redirect()->back()->with('success', 'Share capital invitation sent!');
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
        try {
            $request->validate([
                'id' => 'required',
            ]);

            $user = Users_tbl::findOrFail($request->id);
            $previousRole = $user->role;

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
                            ]);
                    } else {
                        DB::table('otherinfo_tbls')->insert([
                            'user_id' => $request->id,
                            'membership_category' => 'Investor Associate',
                            'signature' => '',
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

            return response()->json(['success' => true, 'message' => 'Member updated successfully']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function sendShareCapitalEmail($id)
    {
        $user = Users_tbl::findOrFail($id);
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
        $activeLoans = lending_program_tbl::where('status', 'Approved')->sum('lending_amount') ?? 0;
        $earnedInterests = lending_program_tbl::where('status', 'Completed')->sum('total_interest') ?? 0;

        // Savings Activity counts
        $totalDeposits = savings_transaction_tbl::where('type', 'deposit')->count();
        $totalWithdrawals = savings_transaction_tbl::where('type', 'withdrawal')->count();

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
        $pendingMembersCount = $pendingMembersList->count();
        $pendingLoansCount = lending_program_tbl::where('status', 'Pending')->count();
        $pendingWithdrawalsCount = share_capital_transaction_tbl::where('type', 'Withdrawal')
            ->whereIn('status', ['Pending', 'pending'])->count();

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
            'recentTransactions',
            'recentMemberApprovals',
            'savingsByMonth',
            'memberActivity',
            'loanTypeCounts',
            'loansByTypeDetails',
            'recentSavingsTransactions'
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

        $members = $query->paginate(10);
        $pendingRequests = Users_tbl::where('role', 'pending')->get();
        $admins = Users_tbl::where('role', 'admin')->get();

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

        return view("admin_components.members", compact('members', 'pendingRequests', 'admins', 'memberCategoryCounts', 'adminCategoryCounts'));
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

        return redirect()->back()->with('success', 'Transaction archived successfully.');
    }

    public function unarchiveSavings($id)
    {
        $transaction = savings_transaction_tbl::findOrFail($id);
        $transaction->archived = 0;
        $transaction->save();

        return redirect()->back()->with('success', 'Transaction restored successfully.');
    }

    public function dashboard_lendings(Request $request)
    {
        $statusFilter = $request->get('status', 'all');

        $query = lending_program_tbl::with(['user', 'repayments' => function($q) { $q->select('id', 'lending_id', 'payment_number'); }])
            ->where('status', '!=', 'Archived');

        if ($statusFilter !== 'all') {
            $query->where('status', ucfirst($statusFilter));
        }

        $loans = $query->orderBy('created_at', 'desc')->paginate(10);

        return view("admin_components.lending", compact('loans', 'statusFilter'));
        
        $allMembers = Users_tbl::where('role', 'Member')
            ->orderBy('first_name')
            ->get();
        
        $loanSettings = Loan_settings_tbl::pluck('interest_rate', 'loan_type')->toArray();
        
        $lateFeeSettings = Loan_settings_tbl::first();
        $lateFeePercentage = $lateFeeSettings->late_fee_percentage ?? 2.00;
        $gracePeriodMonths = $lateFeeSettings->grace_period_months ?? 1;
        
        $penalizedLoans = $this->calculatePenalties($lateFeePercentage, $gracePeriodMonths);
        
        return view("admin_components.lending", compact('loans', 'statusFilter', 'allMembers', 'loanSettings', 'lateFeePercentage', 'gracePeriodMonths', 'penalizedLoans'));
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

        return redirect()->back()->with('success', 'Loan created successfully for ' . $member->first_name . ' ' . $member->last_name);
    }

    public function approveLoan(Request $request, $id)
    {
        $loan = lending_program_tbl::findOrFail($id);
        $loan->status = 'Approved';
        $loan->save();

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

        return redirect()->back()->with('error', 'Loan application declined.');
    }

    public function archiveLoan($id)
    {
        $loan = lending_program_tbl::findOrFail($id);
        $loan->status = 'Archived';
        $loan->save();

        return redirect()->back()->with('success', 'Loan archived successfully.');
    }

    public function unarchiveLoan($id)
    {
        $loan = lending_program_tbl::findOrFail($id);
        $loan->status = 'Approved';
        $loan->save();

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

        $totalContributions = share_capital_transaction_tbl::where('type', 'Deposit')
            ->where('status', 'Completed')
            ->sum('total_amount') ?? 0;

        $totalContributions = $totalContributions - (
            share_capital_transaction_tbl::where('type', 'Withdrawal')
            ->whereIn('status', ['Approved', 'approved'])
            ->sum('total_amount') ?? 0
        );

        $perShareValue = 1000;
        
        $deposits = share_capital_transaction_tbl::where('type', 'Deposit')
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

        return view("admin_components.sharecapitals", compact(
            'transactions',
            'totalContributions',
            'currentValue',
            'perShareValue',
            'lastContribution',
            'allMembers'
        ));
    }

    public function archiveShareCapital($id)
    {
        $transaction = share_capital_transaction_tbl::findOrFail($id);
        $transaction->archived = 1;
        $transaction->save();

        return redirect()->back()->with('success', 'Share capital transaction archived successfully.');
    }

    public function unarchiveShareCapital($id)
    {
        $transaction = share_capital_transaction_tbl::findOrFail($id);
        $transaction->archived = 0;
        $transaction->save();

        return redirect()->back()->with('success', 'Share capital transaction restored successfully.');
    }

    public function adminStoreShareCapital(Request $request)
    {
        $request->validate([
            'member_id' => 'required|exists:users_tbls,id',
            'shares' => 'required|numeric|min:1',
            'type' => 'required|string|in:Deposit,Withdrawal',
            'payment_method' => 'required|string|in:cash,bank_transfer,gcash,check',
            'note' => 'nullable|string|max:255',
        ]);

        $memberId = $request->member_id;
        $shares = (int) $request->shares;
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

            return response()->json([
                'success' => true,
                'message' => 'Withdrawal request accepted successfully.',
            ]);
        } else {
            $transaction->status = 'Rejected';
            $transaction->save();

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

            $depositCap = share_capital_transaction_tbl::where('type', 'Deposit')
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

        $loanSettings = Loan_settings_tbl::pluck('interest_rate', 'loan_type')->toArray();

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
        }

        return view("admin_components.settings", compact('adminUser', 'companySettings', 'loanSettings'));
    }

    public function dashboard_financial_activity()
    {
        return view("admin_components.financial_activity");
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
