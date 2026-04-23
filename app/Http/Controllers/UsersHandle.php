<?php

namespace App\Http\Controllers;

use App\Models\educational_tbl;
use App\Models\Membergovern_ids_tbl;
use App\Models\Membervehi_tbl;
use App\Models\savings_account_tbl;
use App\Models\dividend_rates_tbl;
use App\Models\lending_program_tbl;
use App\Models\Loan_settings_tbl;
use App\Models\share_capital_account_tbl;
use App\Models\share_capital_transaction_tbl;
use App\Models\savings_transaction_tbl;
use App\Models\lending_repayments_tbl;
use Carbon\Carbon;
use App\Models\Otherinfo_tbl;
use App\Models\Family_tbl;
use App\Models\Users_tbl;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;


class UsersHandle extends Controller
{
    public function applicationFormButton(Request $request, $id)
    {
        // dd($request->all());
        try {
            $request->validate([
                // users_tbls
                'fullname' => 'nullable|string|max:255',
                'username' => 'nullable|string|max:255',
                'email' => 'nullable|email|max:255',

                // otherinfo_tbls
                'date_of_birth' => 'nullable|string|max:255',
                'place_of_birth' => 'nullable|string|max:255',
                'contact_no' => 'nullable|string|max:255',
                'present_address' => 'nullable|string|max:255',
                'permanent_address' => 'nullable|string|max:255',
                'sex' => 'nullable|string|max:255',
                'civil_status' => 'nullable|string|max:255',
                'citizenship' => 'nullable|string|max:255',
                'height' => 'nullable|string|max:255',
                'weight' => 'nullable|string|max:255',
                'blood_type' => 'nullable|string|max:255',
                'skills' => 'nullable|string|max:255',

                // spouse_tbls
                'spouse_name' => 'nullable|string|max:255',
                'spouse_date_birth' => 'nullable|date',
                'spouse_place_birth' => 'nullable|string|max:255',
                'number_son' => 'nullable|string|max:255',
                'number_daughter' => 'nullable|string|max:255',
                'other_spec' => 'nullable|string|max:255',

                // membergovern_ids_tbls
                'sss_id' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
                'philhealth_id' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
                'pagibig_id' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
                'tin_id' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',

                // membervehi_tbls
                'uv_plate_no' => 'nullable|array',
                'uv_plate_no.*' => 'nullable|string',
                'taxi_plate_no' => 'nullable|array',
                'taxi_plate_no.*' => 'nullable|string',
                'bus_plate_no' => 'nullable|array',
                'bus_plate_no.*' => 'nullable|string',
                'mini_bus_plate_no' => 'nullable|array',
                'mini_bus_plate_no.*' => 'nullable|string',
                'jeep_plate_no' => 'nullable|array',
                'jeep_plate_no.*' => 'nullable|string',
                'multi_cab_plate_no' => 'nullable|array',
                'multi_cab_plate_no.*' => 'nullable|string',
                'tricycle_plate_no' => 'nullable|array',
                'tricycle_plate_no.*' => 'nullable|string',
                'total_uv' => 'nullable|integer|min:0',
                'total_taxi' => 'nullable|integer|min:0',
                'total_bus' => 'nullable|integer|min:0',
                'total_mini_bus' => 'nullable|integer|min:0',
                'total_jeep' => 'nullable|integer|min:0',
                'total_multi_cab' => 'nullable|integer|min:0',
                'total_tricycle' => 'nullable|integer|min:0',

                // educational_tbls
                'educational_level' => 'nullable|array',
                'educational_level.*' => 'nullable|string|max:255',
                'edu_status' => 'nullable|array',
                'edu_status.*' => 'nullable|string|max:255',
                'edu_specify' => 'nullable|array',
                'edu_specify.*' => 'nullable|string|max:255',
            ]);

            Users_tbl::where('id', $id)->update($request->only([
                'fullname',
                'username',
                'email',
            ]));

            Family_tbl::updateOrCreate(
                ['user_id' => $id],
                [
                    'spouse_name' => $request->spouse_name,
                    'spouse_date_birth' => $request->spouse_date_birth,
                    'spouse_place_birth' => $request->spouse_place_birth,
                    'number_son' => $request->number_son,
                    'number_daughter' => $request->number_daughter,
                    'other_spec' => $request->other_spec,
                ]
            );

            Otherinfo_tbl::updateOrCreate(
                ['user_id' => $id],
                [
                    'date_of_birth' => $request->date_of_birth,
                    'place_of_birth' => $request->place_of_birth,
                    'contact_no' => $request->contact_no,
                    'present_address' => $request->present_address,
                    'permanent_address' => $request->permanent_address,
                    'sex' => $request->sex,
                    'civil_status' => $request->civil_status,
                    'citizenship' => $request->citizenship,
                    'height' => $request->height,
                    'weight' => $request->weight,
                    'blood_type' => $request->blood_type,
                    'skills' => $request->skills,
                ]
            );

            $governmentData = [];
            $fileFields = ['sss_id', 'philhealth_id', 'pagibig_id', 'tin_id'];

            foreach ($fileFields as $field) {
                if ($request->hasFile($field)) {
                    $path = $request->file($field)->store('government_ids', 'public');
                    $governmentData[$field] = $path;
                }
            }

            Membergovern_ids_tbl::updateOrCreate(
                ['user_id' => $id],
                $governmentData
            );

            Membervehi_tbl::where('user_id', $id)->delete();

            $vehicleTypes = [
                'UV' => ['plate_name' => 'uv_plate_no', 'qty_name' => 'total_uv'],
                'TAXI' => ['plate_name' => 'taxi_plate_no', 'qty_name' => 'total_taxi'],
                'BUS' => ['plate_name' => 'bus_plate_no', 'qty_name' => 'total_bus'],
                'MINI BUS' => ['plate_name' => 'mini_bus_plate_no', 'qty_name' => 'total_mini_bus'],
                'JEEP' => ['plate_name' => 'jeep_plate_no', 'qty_name' => 'total_jeep'],
                'MULTI-CAB' => ['plate_name' => 'multi_cab_plate_no', 'qty_name' => 'total_multi_cab'],
                'TRICYCLE' => ['plate_name' => 'tricycle_plate_no', 'qty_name' => 'total_tricycle'],
            ];

            foreach ($vehicleTypes as $type => $fields) {
                $quantity = (int) $request->input($fields['qty_name'], 0);
                $plates = $request->input($fields['plate_name']);

                if ($quantity <= 0 || empty($plates) || !is_array($plates)) {
                    continue;
                }

                foreach ($plates as $plate_no) {
                    $plate_no = trim((string) ($plate_no ?? ''));
                    if ($plate_no === '')
                        continue;

                    Membervehi_tbl::create([
                        'user_id' => $id,
                        'plate_no' => $plate_no,
                        'vehicle_type' => $type,
                        'quantity' => 1,
                    ]);
                }
            }

            $levels = ['Elementary', 'Secondary', 'Vocational/Trade Course', 'College'];

            foreach ($levels as $index => $level) {
                educational_tbl::updateOrCreate(
                    ['user_id' => $id, 'educational_level' => $level],
                    [
                        'status' => $request->edu_status[$index] ?? null,
                        'specify' => $request->edu_specify[$index] ?? null,
                    ]
                );
            }

            $user = Users_tbl::findOrFail($id);
            $vehicles = Membervehi_tbl::where('user_id', $id)->get()->groupBy('vehicle_type');
            $spouse = Family_tbl::where('user_id', $id)->first();
            $other = Otherinfo_tbl::where('user_id', $id)->first();
            $education = educational_tbl::where('user_id', $id)->get();
            $governmentIds = Membergovern_ids_tbl::where('user_id', $id)->first();

            // return view('members_components.application_form', compact(
            //     'user',
            //     'vehicles',
            //     'spouse',
            //     'other',
            //     'education',
            //     'governmentIds'
            // ));
            return redirect()->route('applicationForm', $id)
                ->with('success', 'Application form submitted successfully!');

        } catch (\Exception $e) {
            dd($e->getMessage(), $e->getLine(), $e->getFile());
        }
    }

    public function MemberPortal()
    {
        $user = Auth::user();
        $member = $user->otherinfo;  // ← keep only this, delete the $member = null line
        $username = $user->username ?? null;
        $email = $user->email ?? null;

        // dd([
        //     'user_id' => $user->id,
        //     'member' => $member,
        //     'raw' => DB::table('otherinfo_tbls')->where('user_id', $user->id)->first(),
        // ]);

        $firstName = $user->first_name ?? '';
        $middleName = $user->middle_name ?? '';
        $lastName = $user->last_name ?? '';

        // ── Savings ──────────────────────────────────────────────────────────────
        $savingsAccount = savings_account_tbl::where('user_id', $user->id)->first();

        if (!$savingsAccount) {
            $savingsAccount = savings_account_tbl::create([
                'user_id' => $user->id,
                'balance' => 0.00,
                'status' => 'active',
                'opened_at' => Carbon::today(),
            ]);
        }

        // ── Share Capital ─────────────────────────────────────────────────────────
        $shareCapitalAccount = DB::table('share_capital_account_tbls')
            ->where('user_id', $user->id)
            ->first();

        if ($shareCapitalAccount) {
            $shareCapitalBalance = DB::table('share_capital_transaction_tbls')
                ->where('share_capital_account_id', $shareCapitalAccount->id)
                ->whereIn('status', ['Completed', 'completed'])
                ->sum('total_amount') ?? 0;

            $shareCapitalShares = DB::table('share_capital_transaction_tbls')
                ->where('share_capital_account_id', $shareCapitalAccount->id)
                ->whereIn('status', ['Completed', 'completed'])
                ->sum('shares') ?? 0;
        } else {
            $shareCapitalBalance = 0;
            $shareCapitalShares = 0;
        }

        // Dividend rate
        $dividendRateRecord = null;
        try {
            if (DB::getSchemaBuilder()->hasTable('dividend_rates_tbls')) {
                $dividendRateRecord = DB::table('dividend_rates_tbls')
                    ->orderByDesc('effective_year')
                    ->orderByDesc('created_at')
                    ->first();
            }
        } catch (\Throwable) {
        }
        $dividendRate = $dividendRateRecord->rate ?? 8.5;

        // Next dividend date
        $today = Carbon::today();
        $jun15ThisYear = Carbon::create($today->year, 6, 15);
        $dec15ThisYear = Carbon::create($today->year, 12, 15);
        $jun15NextYear = Carbon::create($today->year + 1, 6, 15);

        if ($today->lte($jun15ThisYear)) {
            $nextDividendDate = $jun15ThisYear;
        } elseif ($today->lte($dec15ThisYear)) {
            $nextDividendDate = $dec15ThisYear;
        } else {
            $nextDividendDate = $jun15NextYear;
        }

        // ── Loans ─────────────────────────────────────────────────────────────────
        $loans = lending_program_tbl::where('user_id', $user->id)
            ->orderByDesc('created_at')
            ->get();

        $activeLoansCount = $loans->where('status', 'Approved')->count();

        // ── Late Fee Penalties ────────────────────────────────────────────────────
        $lateFeeSettings = Loan_settings_tbl::first();
        $lateFeePercentage = $lateFeeSettings->late_fee_percentage ?? 2.00;
        $gracePeriodMonths = $lateFeeSettings->grace_period_months ?? 1;
        $today = Carbon::today();
        $penalizedLoans = [];
        $totalLateFees = 0;

        foreach ($loans->where('status', 'Approved') as $loan) {
            $termMonths = (int) filter_var($loan->lending_type_term, FILTER_SANITIZE_NUMBER_INT);
            
            if (!$loan->due_date && $loan->created_at) {
                $dueDate = $loan->created_at->copy()->addMonths($termMonths);
                $loan->due_date = $dueDate->format('Y-m-d');
                $loan->save();
            }
            
            if (!$loan->due_date) {
                continue;
            }
            
            $dueDate = Carbon::parse($loan->due_date);
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
                        'lending_type' => $loan->lending_type,
                        'lending_amount' => $loan->lending_amount,
                        'due_date' => $loan->due_date,
                        'months_overdue' => $monthsOverdue,
                        'late_fee' => $lateFee,
                    ];
                    $totalLateFees += $lateFee;
                }
            }
        }

        $overdueCount = count($penalizedLoans);

        return view('members_components.member_portal', [
            'username' => $username,
            'email' => $email,
            'firstName' => $firstName,
            'middleName' => $middleName,
            'lastName' => $lastName,
            'member' => $member,

            // Savings
            'savingsAccount' => $savingsAccount,

            // Share Capital
            'shareCapitalBalance' => $shareCapitalBalance,
            'shareCapitalShares' => $shareCapitalShares,
            'dividendRate' => $dividendRate,
            'nextDividendDate' => $nextDividendDate,

            // Loans
            'loans' => $loans,
            'activeLoansCount' => $activeLoansCount,

            // Late Fee Penalties
            'penalizedLoans' => $penalizedLoans,
            'totalLateFees' => $totalLateFees,
            'overdueCount' => $overdueCount,
            'lateFeePercentage' => $lateFeePercentage,
            'gracePeriodMonths' => $gracePeriodMonths,
        ]);
    }

    // public function LoanApplication()
    // {

    //     $username = Auth::check() ? Auth::user()->username : null;
    //     $email = Auth::check() ? Auth::user()->email : null;

    //     return view(
    //         "members_components.loan_application",
    //         [
    //             "username" => $username,
    //             "email" => $email
    //         ]
    //     );
    // }

    public function LoanApplication()
    {
        $username = Auth::check() ? Auth::user()->username : null;
        $email = Auth::check() ? Auth::user()->email : null;

        $account = DB::table('share_capital_account_tbls')
            ->where('user_id', auth()->id())
            ->first();

        $currentShares = $account->total_shares ?? 0;
        $canApplyLoan = $currentShares >= 25;

        return view(
            'members_components.loan_application',
            [
                "username" => $username,
                "email" => $email
            ],
            compact('currentShares', 'canApplyLoan')
        );
    }

    public function ShareCapitalMember()
    {

        $username = Auth::check() ? Auth::user()->username : null;
        $email = Auth::check() ? Auth::user()->email : null;

        $memberId = Auth::id();

        $account = DB::table('share_capital_account_tbls')
            ->where('user_id', $memberId)
            ->first();

        if ($account) {
            $depositAmount = DB::table('share_capital_transaction_tbls')
                ->where('share_capital_account_id', $account->id)
                ->where('type', 'Deposit')
                ->whereIn('status', ['Completed', 'completed'])
                ->sum('total_amount') ?? 0;

            $withdrawalAmount = DB::table('share_capital_transaction_tbls')
                ->where('share_capital_account_id', $account->id)
                ->where('type', 'Withdrawal')
                ->whereIn('status', ['Approved', 'approved'])
                ->sum('total_amount') ?? 0;

            $currentBalance = $depositAmount - $withdrawalAmount;

            $deposits = DB::table('share_capital_transaction_tbls')
                ->where('share_capital_account_id', $account->id)
                ->where('type', 'Deposit')
                ->whereIn('status', ['Completed', 'completed'])
                ->sum('shares') ?? 0;

            $withdrawals = DB::table('share_capital_transaction_tbls')
                ->where('share_capital_account_id', $account->id)
                ->where('type', 'Withdrawal')
                ->whereIn('status', ['Approved', 'approved'])
                ->sum('shares') ?? 0;

            $currentShares = $deposits - $withdrawals;
        } else {
            $currentBalance = 0;
            $currentShares = 0;
        }

        // Fetch real contribution history
        $contributions = $account
            ? DB::table('share_capital_transaction_tbls')
                ->where('share_capital_account_id', $account->id)
                ->where('status', '!=', 'failed')
                ->orderBy('created_at', 'desc')
                ->get()
            : collect();

        return view(
            'members_components.share_capital',
            [
                "username" => $username,
                "email" => $email
            ],
            compact('currentBalance', 'currentShares', 'contributions')
        );
    }

    public function ProfileMember()
    {
        $userId = Auth::id();

        $user = Users_tbl::find($userId);
        $otherinfo = Otherinfo_tbl::where('user_id', $userId)->first();
        $membergovernIds = Membergovern_ids_tbl::where('user_id', $userId)->first();
        $family = Family_tbl::where('user_id', $userId)->first();
        $vehicles = Membervehi_tbl::where('user_id', $userId)->get();
        $educational = educational_tbl::where('user_id', $userId)->first();
        $savingsAccount = savings_account_tbl::where('user_id', $userId)->first();
        $shareCapitalAccount = share_capital_account_tbl::where('user_id', $userId)->first();
        $dividendRate = dividend_rates_tbl::orderBy('effective_year', 'desc')->first();

        $savingsAccountId = $savingsAccount->id ?? null;
        $shareCapitalAccountId = $shareCapitalAccount->id ?? null;

        $savingsTransactions = collect();
        if ($savingsAccountId) {
            $savingsTransactions = savings_transaction_tbl::where('savings_account_id', $savingsAccountId)
                ->orderBy('transaction_date', 'desc')
                ->limit(10)
                ->get()
                ->map(function ($item) {
                    return [
                        'date' => $item->transaction_date,
                        'type' => ucfirst($item->type),
                        'description' => 'Regular Savings - ' . ucfirst($item->type),
                        'amount' => $item->type === 'deposit' ? $item->amount : -$item->amount,
                        'status' => $item->status ?? 'Completed',
                    ];
                });
        }

        $shareCapitalTransactions = collect();
        if ($shareCapitalAccountId) {
            $shareCapitalTransactions = share_capital_transaction_tbl::where('share_capital_account_id', $shareCapitalAccountId)
                ->orderBy('transaction_date', 'desc')
                ->limit(10)
                ->get()
                ->map(function ($item) {
                    return [
                        'date' => $item->transaction_date,
                        'type' => ucfirst($item->type),
                        'description' => 'Share Capital - ' . ucfirst($item->type),
                        'amount' => in_array($item->type, ['Subscription', 'Deposit']) ? $item->total_amount : -$item->total_amount,
                        'status' => $item->status ?? 'Completed',
                    ];
                });
        }

        $loanRepayments = lending_repayments_tbl::where('user_id', $userId)
            ->orderBy('payment_date', 'desc')
            ->limit(10)
            ->get()
            ->map(function ($item) {
                return [
                    'date' => $item->payment_date,
                    'type' => 'Loan payment',
                    'description' => 'Loan Repayment',
                    'amount' => -$item->amount_paid,
                    'status' => 'Completed',
                ];
            });

        $transactions = $savingsTransactions
            ->concat($shareCapitalTransactions)
            ->concat($loanRepayments)
            ->sortByDesc('date')
            ->take(20)
            ->values();

        $memberSince = $user->created_at->format('F Y');
        
        $missingCount = 0;
        if($otherinfo && empty($otherinfo->contact_no)) $missingCount++;
        if($otherinfo && empty($otherinfo->present_address)) $missingCount++;
        if($otherinfo && empty($otherinfo->permanent_address)) $missingCount++;
        if($otherinfo && empty($otherinfo->date_of_birth)) $missingCount++;
        if($otherinfo && empty($otherinfo->place_of_birth)) $missingCount++;
        if($otherinfo && empty($otherinfo->sex)) $missingCount++;
        if($otherinfo && empty($otherinfo->civil_status)) $missingCount++;
        if($otherinfo && empty($otherinfo->citizenship)) $missingCount++;
        if($otherinfo && empty($otherinfo->blood_type)) $missingCount++;
        if($otherinfo && empty($otherinfo->height)) $missingCount++;
        if($otherinfo && empty($otherinfo->weight)) $missingCount++;
        if($membergovernIds && empty($membergovernIds->sss_id)) $missingCount++;
        if($membergovernIds && empty($membergovernIds->philhealth_id)) $missingCount++;
        if($membergovernIds && empty($membergovernIds->pagibig_id)) $missingCount++;
        if($membergovernIds && empty($membergovernIds->tin_id)) $missingCount++;

        return view(
            "members_components.profile",
            [
                "user" => $user,
                "otherinfo" => $otherinfo,
                "membergovernIds" => $membergovernIds,
                "family" => $family,
                "vehicles" => $vehicles,
                "educational" => $educational,
                "savingsAccount" => $savingsAccount,
                "shareCapitalAccount" => $shareCapitalAccount,
                "dividendRate" => $dividendRate,
                "transactions" => $transactions,
                "memberSince" => $memberSince,
                "username" => $user->username ?? null,
                "email" => $user->email ?? null,
                "missingCount" => $missingCount,
            ]
        );
    }

    public function EditProfileMember()
    {
        $userId = Auth::id();

        $user = Users_tbl::find($userId);
        $otherinfo = Otherinfo_tbl::where('user_id', $userId)->first();
        $membergovernIds = Membergovern_ids_tbl::where('user_id', $userId)->first();
        $family = Family_tbl::where('user_id', $userId)->first();
        $vehicles = Membervehi_tbl::where('user_id', $userId)->get();
        $educational = educational_tbl::where('user_id', $userId)->first();

        return view(
            "members_components.edit_profile",
            [
                "user" => $user,
                "otherinfo" => $otherinfo,
                "membergovernIds" => $membergovernIds,
                "family" => $family,
                "vehicles" => $vehicles,
                "educational" => $educational,
            ]
        );
    }

    public function UpdateProfileMember(Request $request)
    {
        $userId = Auth::id();

        $user = Users_tbl::find($userId);
        $existingInfo = Otherinfo_tbl::where('user_id', $userId)->first();

        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
        ]);

        $user->first_name = $request->first_name;
        $user->middle_name = $request->middle_name;
        $user->last_name = $request->last_name;
        $user->save();

        $updateData = [
            'contact_no' => $request->contact_no,
            'present_address' => $request->present_address,
            'permanent_address' => $request->permanent_address,
            'date_of_birth' => $request->date_of_birth,
            'sex' => $request->sex,
            'civil_status' => $request->civil_status,
            'citizenship' => $request->citizenship,
            'height' => $request->height,
            'weight' => $request->weight,
            'blood_type' => $request->blood_type,
        ];

        foreach ($updateData as $key => $value) {
            if (empty($value) && !empty($existingInfo->$key)) {
                $updateData[$key] = $existingInfo->$key;
            }
        }

        Otherinfo_tbl::updateOrCreate(
            ['user_id' => $userId],
            $updateData
        );

        $govIdsData = [];
        $idFields = ['sss_id', 'philhealth_id', 'pagibig_id', 'tin_id'];
        
        foreach ($idFields as $field) {
            if ($request->hasFile($field)) {
                $file = $request->file($field);
                $filename = $field . '_' . $userId . '_' . time() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('images'), $filename);
                $govIdsData[$field] = $filename;
            } elseif (!empty($membergovernIds->$field)) {
                $govIdsData[$field] = $membergovernIds->$field;
            }
        }
        
        if (!empty($govIdsData)) {
            Membergovern_ids_tbl::updateOrCreate(
                ['user_id' => $userId],
                $govIdsData
            );
        }

        $familyData = [
            'spouse_name' => $request->spouse_name,
            'spouse_date_birth' => $request->spouse_date_birth,
            'number_son' => $request->number_son,
            'number_daughter' => $request->number_daughter,
        ];
        if (!empty(array_filter($familyData))) {
            foreach ($familyData as $key => $value) {
                if (empty($value) && !empty($family->$key)) {
                    $familyData[$key] = $family->$key;
                }
            }
            Family_tbl::updateOrCreate(
                ['user_id' => $userId],
                $familyData
            );
        }

        return redirect()->route('ProfileMember')->with('success', 'Profile updated successfully!');
    }

    public function Navbar2()
    {
        $userId = Auth::id();
        $username = Auth::check() ? Auth::user()->username : null;
        $email = Auth::check() ? Auth::user()->email : null;
        
        $otherinfo = Otherinfo_tbl::where('user_id', $userId)->first();
        $membergovernIds = Membergovern_ids_tbl::where('user_id', $userId)->first();
        $family = Family_tbl::where('user_id', $userId)->first();
        
        $missingCount = 0;
        if(empty($otherinfo->contact_no)) $missingCount++;
        if(empty($otherinfo->present_address)) $missingCount++;
        if(empty($otherinfo->permanent_address)) $missingCount++;
        if(empty($otherinfo->date_of_birth)) $missingCount++;
        if(empty($otherinfo->place_of_birth)) $missingCount++;
        if(empty($otherinfo->sex)) $missingCount++;
        if(empty($otherinfo->civil_status)) $missingCount++;
        if(empty($otherinfo->citizenship)) $missingCount++;
        if(empty($otherinfo->blood_type)) $missingCount++;
        if(empty($otherinfo->height)) $missingCount++;
        if(empty($otherinfo->weight)) $missingCount++;
        if(empty($membergovernIds->sss_id)) $missingCount++;
        if(empty($membergovernIds->philhealth_id)) $missingCount++;
        if(empty($membergovernIds->pagibig_id)) $missingCount++;
        if(empty($membergovernIds->tin_id)) $missingCount++;


        return view(
            "components.navbar2",
            [
                "username" => $username,
                "email" => $email,
                "missingCount" => $missingCount
            ]
        );
    }

    public function logout()
    {
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        return redirect()->route("login");
    }

    public function UserHandle()
    {
        $user = Auth::user();

        if (strtolower($user->role) === "admin") {
            return redirect()->route("dashboard")->with("message", "Login successfully!");
        } else {
            return redirect()->route("MemberPortal")->with("message", "Login successfully!");
        }
    }

    public function login(Request $request)
    {
        $incomingFields = $request->validate([
            "login" => "required",
            "password" => "required"
        ]);

        $loginInput = $incomingFields['login'];

        // Check if user exists by email
        $user = DB::table('users_tbls')->where('email', $loginInput)->first();

        if (!$user) {
            return redirect()->back()
                ->withErrors(['login' => 'That email isn\'t registered yet.'])
                ->withInput($request->only('login'));
        }

        // Attempt authentication
        $credentials = [
            'email' => $user->email,
            'password' => $incomingFields['password']
        ];

        if (auth()->attempt($credentials)) {
            $otherInfo = DB::table('otherinfo_tbls')
                ->where('user_id', auth()->id())
                ->first();

            if (!$otherInfo || $otherInfo->approval_status === 'Pending') {

                auth()->logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return redirect()->back()
                    ->withErrors(['login' => 'Your account is still pending approval'])
                    ->withInput($request->only('login'));

            } elseif (!$otherInfo || $otherInfo->approval_status === 'Declined') {
                auth()->logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return redirect()->back()
                    ->withErrors(['login' => 'Your membership application is declined.'])
                    ->withInput($request->only('login'));
            } else {

                $request->session()->regenerate();
                return redirect()->route('UserHandle');

            }


        } else {
            return redirect()->back()
                ->withErrors(['login' => 'Incorrect password. Please try again.'])
                ->withInput($request->only('login'));
        }

        // if (auth()->attempt($credentials)) {
        //     $otherInfo = DB::table('otherinfo_tbls')
        //         ->where('user_id', auth()->id())
        //         ->first();

        //     if (!$otherInfo || $otherInfo->status === 'Pending') {
        //         auth()->logout();
        //         $request->session()->invalidate();
        //         $request->session()->regenerateToken();

        //         return redirect()->back()
        //             ->withErrors(['login' => 'Your account is still pending approval.'])
        //             ->withInput($request->only('login'));
        //     } else if (!$otherInfo || $otherInfo->status === 'Declined') {
        //         auth()->logout();
        //         $request->session()->invalidate();
        //         $request->session()->regenerateToken();

        //         return redirect()->back()
        //             ->withErrors(['login' => 'Your membership application is declined.'])
        //             ->withInput($request->only('login'));
        //     } else {

        //         $request->session()->regenerate();
        //         return redirect()->route('UserHandle');

        //     }


        // } else {
        //     return redirect()->back()
        //         ->withErrors(['login' => 'Incorrect password. Please try again.'])
        //         ->withInput($request->only('login'));
        // }
    }

    public function checkEmail(Request $request)
    {
        $exists = \App\Models\Users_tbl::where('email', $request->email)->exists();
        return response()->json(['exists' => $exists]);
    }

    public function registration(Request $request)
    {
        try {

            $request->validate([
                "first_name" => "required",
                "middle_name" => "nullable|string|max:255",
                "last_name" => "required",
                "profile_picture" => "nullable|image|max:2048",
                "date_of_birth" => "required|date",
                "place_of_birth" => "required",
                "email" => ["required", "email", "regex:/@gmail\.com$/i", Rule::unique("users_tbls", "email")],
                "password" => "required|confirmed",
                "membership_category" => "required",
                "civil_status" => "required",
                "number_son" => "nullable|integer",
                "number_daughter" => "nullable|integer",
                "other_spec" => "nullable",

                "sss_id" => "nullable|file|mimes:jpg,jpeg,png,pdf|max:2048",
                "philhealth_id" => "nullable|file|mimes:jpg,jpeg,png,pdf|max:2048",
                "pagibig_id" => "nullable|file|mimes:jpg,jpeg,png,pdf|max:2048",
                "tin_id" => "nullable|file|mimes:jpg,jpeg,png,pdf|max:2048",

                "uv_plate_no" => "nullable|array",
                "uv_plate_no.*" => "nullable|string",
                "taxi_plate_no" => "nullable|array",
                "taxi_plate_no.*" => "nullable|string",
                "bus_plate_no" => "nullable|array",
                "bus_plate_no.*" => "nullable|string",
                "mini_bus_plate_no" => "nullable|array",
                "mini_bus_plate_no.*" => "nullable|string",
                "jeep_plate_no" => "nullable|array",
                "jeep_plate_no.*" => "nullable|string",
                "multi_cab_plate_no" => "nullable|array",
                "multi_cab_plate_no.*" => "nullable|string",
                "tricycle_plate_no" => "nullable|array",
                "tricycle_plate_no.*" => "nullable|string",

                "total_uv" => "nullable|integer|min:0",
                "total_taxi" => "nullable|integer|min:0",
                "total_bus" => "nullable|integer|min:0",
                "total_mini_bus" => "nullable|integer|min:0",
                "total_jeep" => "nullable|integer|min:0",
                "total_multi_cab" => "nullable|integer|min:0",
                "total_tricycle" => "nullable|integer|min:0",

                "signature" => "required",
            ]);

            // Profile picture
            $profilePicturePath = null;
            if ($request->hasFile('profile_picture')) {
                $profilePicturePath = $request->file('profile_picture')->store('profile_pictures', 'public');
            }

            // Create user
            $users = Users_tbl::create([
                "first_name" => $request->first_name,
                "middle_name" => $request->middle_name,
                "last_name" => $request->last_name,
                "username" => $request->username,
                "email" => $request->email,
                "password" => bcrypt($request->password),
                "role" => "Member",
            ]);

            // Spouse
            Family_tbl::create([
                "user_id" => $users->id,
                "spouse_name" => $request->spouse_name,
                "spouse_date_birth" => $request->spouse_date_birth ?: null,
                "spouse_place_birth" => $request->spouse_place_birth,
                "number_son" => $request->number_son,
                "number_daughter" => $request->number_daughter,
                "other_spec" => $request->other_spec,
            ]);


            $governmentIds = ['user_id' => $users->id];
            $fileFields = ['sss_id', 'philhealth_id', 'pagibig_id', 'tin_id'];

            foreach ($fileFields as $field) {
                if ($request->hasFile($field)) {
                    $governmentIds[$field] = $request->file($field)->store('government_ids', 'public');
                } else {
                    $governmentIds[$field] = null;
                }
            }

            Membergovern_ids_tbl::create($governmentIds);

            // In your RegisterController or wherever you save the member
            $emailVerified = Session::get('email_otp_verified_email') === $request->email ? 1 : 0;

            // Other info
            Otherinfo_tbl::create([
                "user_id" => $users->id,
                "membership_category" => $request->membership_category,
                'email_verified' => $emailVerified,
                "date_of_birth" => $request->date_of_birth,
                "place_of_birth" => $request->place_of_birth,
                "sex" => $request->sex,
                "civil_status" => $request->civil_status,
                "citizenship" => $request->citizenship, // ← add this
                "skills" => $request->skills_expertise,  // ← note: form uses skills_expertise
                "signature" => $request->signature,
                "profile_picture" => $profilePicturePath,
                "approval_status" => "Pending",
                "membership_status" => "Unofficial",
            ]);

            // Vehicles
            $vehicleTypes = [
                'UV' => ['plate_name' => 'uv_plate_no', 'qty_name' => 'total_uv'],
                'TAXI' => ['plate_name' => 'taxi_plate_no', 'qty_name' => 'total_taxi'],
                'BUS' => ['plate_name' => 'bus_plate_no', 'qty_name' => 'total_bus'],
                'MINI BUS' => ['plate_name' => 'mini_bus_plate_no', 'qty_name' => 'total_mini_bus'],
                'JEEP' => ['plate_name' => 'jeep_plate_no', 'qty_name' => 'total_jeep'],
                'MULTI-CAB' => ['plate_name' => 'multi_cab_plate_no', 'qty_name' => 'total_multi_cab'],
                'TRICYCLE' => ['plate_name' => 'tricycle_plate_no', 'qty_name' => 'total_tricycle'],
            ];

            foreach ($vehicleTypes as $type => $fields) {
                $quantity = (int) $request->input($fields['qty_name'], 0);
                $plates = $request->input($fields['plate_name']);

                if ($quantity <= 0 || empty($plates) || !is_array($plates)) {
                    continue;
                }

                foreach ($plates as $plate_no) {
                    $plate_no = trim((string) ($plate_no ?? ''));
                    if ($plate_no === '')
                        continue;

                    Membervehi_tbl::create([
                        'user_id' => $users->id,
                        'plate_no' => $plate_no,
                        'vehicle_type' => $type,
                        'quantity' => 1,
                    ]);
                }
            }

            return redirect()->route("RegisterPage")->with("success", "Create account successfully!");


        } catch (\Exception $e) {
            dd($e->getMessage(), $e->getLine(), $e->getFile());
        }

    }


}