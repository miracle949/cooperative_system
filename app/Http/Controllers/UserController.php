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

        DB::table('otherinfo_tbls')
            ->where('user_id', $id)
            ->update([
                'membership_status' => 'Active',
                'approval_status' => 'Approved',
            ]);

        Mail::to($user->email)->send(new ApprovedMail($user));

        return redirect()->back()->with('success', 'Member approved and email sent!');
    }
    public function messageAboutShare($id)
    {
        $user = Users_tbl::findOrFail($id);
        $user->role = 'member';
        $user->save();

        DB::table('otherinfo_tbls')
            ->where('user_id', $id)
            ->update(['status' => 'Approved']);

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
        $validated = $request->validate([
            'id' => 'required',
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email',
        ]);

        $user = Users_tbl::findOrFail($request->id);
        $previousRole = $user->role;

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

        if ($request->role === 'member' && $previousRole !== 'member') {
            DB::table('otherinfo_tbls')
                ->where('user_id', $request->id)
                ->update(['status' => 'Approved']);
        }

        return redirect()->back()->with('success', 'Member updated successfully.');
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
            'pendingRequests',
            'adminCount',
            'memberCount',
            'pendingCount',
            'recentActivities',
            'savingsByMonth',
            'memberActivity',
            'loansByPurpose',
            'recentSavingsTransactions'
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

        $memberIds = $members->pluck('id')->toArray();

        $shareCapitals = DB::table('share_capital_account_tbls')
            ->whereIn('user_id', $memberIds)
            ->get()
            ->keyBy('user_id');

        $otherInfo = DB::table('otherinfo_tbls')
            ->whereIn('user_id', $memberIds)
            ->get()
            ->keyBy('user_id');

        $spouseInfo = DB::table('spouse_tbls')
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

        return view("admin_components.members", compact('members', 'pendingRequests'));
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

        $transactions = $query->orderBy('created_at', 'desc')->paginate(15);

        $currentBalance = savings_account_tbl::sum('balance') ?? 0;

        $sixMonthsAgo = now()->subMonths(6)->startOfMonth();
        $monthlyAvg = savings_transaction_tbl::where('type', 'deposit')
            ->where('created_at', '>=', $sixMonthsAgo)
            ->sum('amount') / 6;

        $lastContribution = savings_transaction_tbl::orderByDesc('created_at')->first();

        $totalDeposits = savings_transaction_tbl::where('type', 'deposit')->sum('amount') ?? 0;
        $totalWithdrawals = savings_transaction_tbl::where('type', 'withdraw')->sum('amount') ?? 0;

        $allMembers = Users_tbl::whereIn('role', ['member', 'pending'])
            ->select('id', 'first_name', 'last_name')
            ->orderBy('first_name')
            ->get();

        $monthlyData = [];
        $maxAmount = 0;
        
        // Use simple sequential months from current month backward
        for ($i = 5; $i >= 0; $i--) {
            $monthDate = now()->subMonths($i);
            
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

        return view("admin_components.savings", compact(
            'transactions',
            'currentBalance',
            'monthlyAvg',
            'lastContribution',
            'totalDeposits',
            'totalWithdrawals',
            'allMembers',
            'highestMonth'
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

        $query = lending_program_tbl::with('user')
            ->where('status', '!=', 'Archived');

        if ($statusFilter !== 'all') {
            $query->where('status', ucfirst($statusFilter));
        }

        $loans = $query->orderBy('created_at', 'desc')->paginate(10);
        
        $allMembers = Users_tbl::where('role', 'member')
            ->orderBy('first_name')
            ->get();
        
        $loanSettings = Loan_settings_tbl::pluck('interest_rate', 'loan_type')->toArray();
        
        return view("admin_components.lending", compact('loans', 'statusFilter', 'allMembers', 'loanSettings'));
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

        $transactions = $query->orderBy('created_at', 'desc')->paginate(15);

        $totalContributions = share_capital_transaction_tbl::where('type', 'Deposit')
            ->where('status', 'Completed')
            ->sum('total_amount') ?? 0;

        $totalContributions = $totalContributions - (
            share_capital_transaction_tbl::where('type', 'Withdrawal')
            ->where('status', 'Approved')
            ->sum('total_amount') ?? 0
        );

        $perShareValue = 1000;
        
        $deposits = share_capital_transaction_tbl::where('type', 'Deposit')
            ->where('status', 'Completed')
            ->sum('shares') ?? 0;
        
        $withdrawals = share_capital_transaction_tbl::where('type', 'Withdrawal')
            ->where('status', 'Approved')
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

        $totalWithdrawals = savings_transaction_tbl::where('type', 'withdraw')
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
                ->where('status', 'Approved')
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
            ->where('st.type', 'withdraw')
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
            ->where('st.type', 'withdraw')
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

    public function createLoanAdmin(Request $request)
    {
        try {
            $request->validate([
                "member_id" => "required|exists:users_tbls,id",
                "lending_type" => "required|string",
                "lending_amount" => "required|numeric|min:1",
                "lending_type_term" => "required|string",
                "monthly_income" => "required|numeric|min:1",
                "monthly_payment" => "required|numeric",
                "total_payment" => "required|numeric",
                "total_interest" => "required|numeric",
                "purpose_loan" => "required|string",
                "valid_id" => "nullable|file|mimes:jpg,jpeg,png,pdf|max:2048",
                "proof_of_income" => "nullable|file|mimes:jpg,jpeg,png,pdf|max:2048",
            ]);

            // Store files and get their paths
            $validIdPath = null;
            $proofOfIncomePath = null;

            if ($request->hasFile('valid_id')) {
                $validIdPath = $request->file('valid_id')->store('documents/valid_id', 'public');
            }

            if ($request->hasFile('proof_of_income')) {
                $proofOfIncomePath = $request->file('proof_of_income')->store('documents/proof_of_income', 'public');
            }

            // Generate reference number
            $referenceNo = 'LN-' . date('YmdHis') . rand(10, 99);

            // Create loan as Pending first
            $loan = lending_program_tbl::create([
                "user_id" => $request->member_id,
                "reference_no" => $referenceNo,
                "lending_type" => $request->lending_type,
                "lending_amount" => $request->lending_amount,
                "lending_type_term" => $request->lending_type_term,
                "monthly_income" => $request->monthly_income,
                "monthly_payment" => $request->monthly_payment,
                "total_payment" => $request->total_payment,
                "total_interest" => $request->total_interest,
                "purpose_loan" => $request->purpose_loan,
                "valid_id" => $validIdPath,
                "proof_of_income" => $proofOfIncomePath,
                "status" => "Pending",
            ]);

            // Immediately approve the loan
            $loan->status = 'Approved';
            $loan->save();

            return redirect()->route('lendings')->with('success', 'Loan created and approved successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error creating loan: ' . $e->getMessage())->withInput();
        }
    }
}
