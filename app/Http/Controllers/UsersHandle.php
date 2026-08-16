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
use App\Models\AuditLog;
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

            AuditLog::log(
                'Updated Application Form',
                "Updated profile/application form for user #{$id}",
                'user',
                $id
            );

            return redirect()->route('applicationForm', $id)
                ->with('success', 'Application form submitted successfully!');

        } catch (\Exception $e) {
            dd($e->getMessage(), $e->getLine(), $e->getFile());
        }
    }

    public function MemberPortal(Request $request)
    {
        $user = Auth::user();
        $member = $user->otherinfo;
        $username = $user->username ?? null;
        $email = $user->email ?? null;

        $firstName = $user->first_name ?? '';
        $middleName = $user->middle_name ?? '';
        $lastName = $user->last_name ?? '';

        // ── Year filter (drives all growth charts + announcements) ─────────────
        $currentYear = (int) Carbon::now()->year;
        $selectedYear = (int) $request->query('year', $currentYear);
        $availableYears = collect(range($currentYear, $currentYear - 4))->values();

        $referenceMonth = $selectedYear === $currentYear
            ? Carbon::now()
            : Carbon::create($selectedYear, 12, 1);

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

        // ── Savings Growth (bar chart, same logic as Savings page) ──────────────────
        $growthStart = $referenceMonth->copy()->startOfMonth()->subMonths(5);
        $growthMonths = collect(range(5, 0))->map(fn($i) => $referenceMonth->copy()->subMonths($i));

        $growthTxs = savings_transaction_tbl::where('savings_account_id', $savingsAccount->id)
            ->where('transaction_date', '>=', $growthStart)
            ->whereIn('type', ['deposit', 'withdrawal'])
            ->get()
            ->groupBy(fn($tx) => Carbon::parse($tx->transaction_date)->format('Y-m'));

        $savingsGrowth = collect();
        foreach ($growthMonths as $month) {
            $key = $month->format('Y-m');
            $monthTxs = $growthTxs->get($key, collect());
            $net = $monthTxs->sum(fn($tx) => $tx->type === 'deposit' ? (float) $tx->amount : -(float) $tx->amount);

            $savingsGrowth->push([
                'label' => $month->format('M'),
                'net' => $net,
                'is_current' => $month->isSameMonth(Carbon::now()),
            ]);
        }

        $maxGrowth = $savingsGrowth->max(fn($m) => max($m['net'], 0)) ?: 1;

        $savingsGrowth = $savingsGrowth->map(function ($m) use ($maxGrowth) {
            $m['height_percent'] = $m['net'] > 0
                ? max(6, round(($m['net'] / $maxGrowth) * 78))
                : 4;
            return $m;
        });

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

        // ── Balance month filter (drives Account Balance pie chart) ──────────────
        $balanceMonth = $request->query('balance_month', Carbon::now()->format('Y-m'));
        $balanceDateCarbon = Carbon::createFromFormat('Y-m', $balanceMonth)->endOfMonth()->endOfDay();

        $availableBalanceMonths = collect(range(0, 11))
            ->map(fn($i) => Carbon::now()->copy()->subMonths($i)->format('Y-m'))
            ->values();

        // ── Balances "as of" the selected date (drives Account Balance pie chart) ──
        $shareCapitalBalanceAsOf = 0;
        if ($shareCapitalAccount) {
            $shareCapitalBalanceAsOf = DB::table('share_capital_transaction_tbls')
                ->where('share_capital_account_id', $shareCapitalAccount->id)
                ->where('transaction_date', '<=', $balanceDateCarbon)
                ->whereIn('status', ['Completed', 'completed'])
                ->get()
                ->sum(fn($tx) => in_array($tx->type, ['Deposit', 'Subscription'])
                    ? (float) $tx->total_amount
                    : -(float) $tx->total_amount);
        }

        $isCurrentMonth = $balanceDateCarbon->isSameMonth(Carbon::now()) && $balanceDateCarbon->isSameYear(Carbon::now());

        $savingsBalanceAsOf = $isCurrentMonth
            ? (float) $savingsAccount->balance
            : DB::table('savings_transaction_tbls')
                ->where('savings_account_id', $savingsAccount->id)
                ->where('transaction_date', '<=', $balanceDateCarbon)
                ->get()
                ->sum(fn($tx) => strtolower($tx->type) === 'deposit' ? (float) $tx->amount : -(float) $tx->amount);

        $loanBalanceAsOf = DB::table('lending_program_tbls')
            ->where('user_id', $user->id)
            ->where('status', 'Approved')
            ->where('created_at', '<=', $balanceDateCarbon)
            ->get()
            ->sum(function ($loan) use ($balanceDateCarbon) {
                $repaidByDate = DB::table('lending_repayments_tbls')
                    ->where('lending_id', $loan->id)
                    ->where('payment_date', '<=', $balanceDateCarbon)
                    ->sum('amount_paid');

                return max(0, (float) $loan->lending_amount - (float) $repaidByDate);
            });

        // Still used elsewhere on the page (current, not "as of")
        $loanBalance = $loanBalanceAsOf;

        // ── Account Balance chart (Share Capital / Savings / Loan Balance) ───────
        $accountBalanceChart = collect([
            ['label' => 'Share Capital', 'value' => max(0, $shareCapitalBalanceAsOf), 'color' => 'gold'],
            ['label' => 'Savings', 'value' => max(0, $savingsBalanceAsOf), 'color' => 'blue'],
            ['label' => 'Loan Balance', 'value' => $loanBalanceAsOf, 'color' => 'coral'],
        ]);

        $maxBalance = $accountBalanceChart->max('value') ?: 1;

        $accountBalanceChart = $accountBalanceChart->map(function ($item) use ($maxBalance) {
            $item['height_percent'] = $item['value'] > 0
                ? max(6, round(($item['value'] / $maxBalance) * 78))
                : 4;
            return $item;
        });

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

        // ── Net Standing "as of" selected month (drives Net Standing modal) ──────
        $standingMonth = $request->query('standing_month', Carbon::now()->format('Y-m'));
        $standingDateCarbon = Carbon::createFromFormat('Y-m', $standingMonth)->endOfMonth()->endOfDay();

        $shareCapitalStandingAsOf = 0;
        if ($shareCapitalAccount) {
            $shareCapitalStandingAsOf = DB::table('share_capital_transaction_tbls')
                ->where('share_capital_account_id', $shareCapitalAccount->id)
                ->where('transaction_date', '<=', $standingDateCarbon)
                ->whereIn('status', ['Completed', 'completed'])
                ->get()
                ->sum(fn($tx) => in_array($tx->type, ['Deposit', 'Subscription'])
                    ? (float) $tx->total_amount
                    : -(float) $tx->total_amount);
        }

        $isStandingCurrentMonth = $standingDateCarbon->isSameMonth(Carbon::now()) && $standingDateCarbon->isSameYear(Carbon::now());

        $savingsStandingAsOf = $isStandingCurrentMonth
            ? (float) $savingsAccount->balance
            : DB::table('savings_transaction_tbls')
                ->where('savings_account_id', $savingsAccount->id)
                ->where('transaction_date', '<=', $standingDateCarbon)
                ->get()
                ->sum(fn($tx) => strtolower($tx->type) === 'deposit' ? (float) $tx->amount : -(float) $tx->amount);

        $loanStandingAsOf = DB::table('lending_program_tbls')
            ->where('user_id', $user->id)
            ->where('status', 'Approved')
            ->where('created_at', '<=', $standingDateCarbon)
            ->get()
            ->sum(function ($loan) use ($standingDateCarbon) {
                $repaidByDate = DB::table('lending_repayments_tbls')
                    ->where('lending_id', $loan->id)
                    ->where('payment_date', '<=', $standingDateCarbon)
                    ->sum('amount_paid');

                return max(0, (float) $loan->lending_amount - (float) $repaidByDate);
            });

        $netStandingAsOf = $shareCapitalStandingAsOf + $savingsStandingAsOf - $loanStandingAsOf;

        $overdueCount = count($penalizedLoans);

        // ── Net savings this month (Savings Balance card subtext) ────────────────
        $netSavingsThisMonth = DB::table('savings_transaction_tbls')
            ->where('savings_account_id', $savingsAccount->id)
            ->whereYear('transaction_date', Carbon::now()->year)
            ->whereMonth('transaction_date', Carbon::now()->month)
            ->get()
            ->sum(fn($tx) => strtolower($tx->type) === 'deposit' ? (float) $tx->amount : -(float) $tx->amount);

        // ── Net Standing (true total: Share Capital + Savings - Loan Balance) ────
        $netStandingTotal = $shareCapitalBalance + (float) $savingsAccount->balance - $loanBalance;

        // ── Next due date across active, non-overdue loans (Active Loans subtext) ──
        $nextDueLoan = $loans->where('status', 'Approved')
            ->filter(fn($loan) => !empty($loan->due_date) && Carbon::parse($loan->due_date)->isFuture())
            ->sortBy(fn($loan) => Carbon::parse($loan->due_date))
            ->first();
        $nextDueDisplay = $nextDueLoan ? Carbon::parse($nextDueLoan->due_date)->format('M d') : null;

        // ── Earliest overdue date (Overdue Loans subtext) ─────────────────────────
        $earliestOverdue = collect($penalizedLoans)->sortBy('due_date')->first();
        $earliestOverdueDisplay = $earliestOverdue ? Carbon::parse($earliestOverdue['due_date'])->format('M d') : null;

        // ── Share Capital Growth (net contributions over last 6 months) ─────────
        $scGrowthTxs = DB::table('share_capital_transaction_tbls')
            ->where('share_capital_account_id', $shareCapitalAccount->id ?? 0)
            ->where('transaction_date', '>=', $growthStart)
            ->whereIn('status', ['Completed', 'completed', 'Approved', 'approved'])
            ->get()
            ->groupBy(fn($tx) => Carbon::parse($tx->transaction_date)->format('Y-m'));

        $shareCapitalGrowth = collect();
        foreach ($growthMonths as $month) {
            $key = $month->format('Y-m');
            $monthTxs = $scGrowthTxs->get($key, collect());
            $net = $monthTxs->sum(fn($tx) => in_array($tx->type, ['Deposit', 'Subscription'])
                ? (float) $tx->total_amount
                : -(float) $tx->total_amount);

            $shareCapitalGrowth->push([
                'label' => $month->format('M'),
                'net' => $net,
                'is_current' => $month->isSameMonth(Carbon::now()),
            ]);
        }

        $maxScGrowth = $shareCapitalGrowth->max(fn($m) => max($m['net'], 0)) ?: 1;

        $shareCapitalGrowth = $shareCapitalGrowth->map(function ($m) use ($maxScGrowth) {
            $m['height_percent'] = $m['net'] > 0
                ? max(6, round(($m['net'] / $maxScGrowth) * 78))
                : 4;
            return $m;
        });

        // ── Loan Balance Growth (net repayments over last 6 months) ──────────────
        $loanGrowthTxs = DB::table('lending_repayments_tbls')
            ->where('user_id', $user->id)
            ->where('payment_date', '>=', $growthStart)
            ->get()
            ->groupBy(fn($tx) => Carbon::parse($tx->payment_date)->format('Y-m'));

        $loanBalanceGrowth = collect();
        foreach ($growthMonths as $month) {
            $key = $month->format('Y-m');
            $monthTxs = $loanGrowthTxs->get($key, collect());
            $net = $monthTxs->sum(fn($tx) => (float) $tx->amount_paid);

            $loanBalanceGrowth->push([
                'label' => $month->format('M'),
                'net' => $net,
                'is_current' => $month->isSameMonth(Carbon::now()),
            ]);
        }

        $maxLoanGrowth = $loanBalanceGrowth->max(fn($m) => max($m['net'], 0)) ?: 1;

        $loanBalanceGrowth = $loanBalanceGrowth->map(function ($m) use ($maxLoanGrowth) {
            $m['height_percent'] = $m['net'] > 0
                ? max(6, round(($m['net'] / $maxLoanGrowth) * 78))
                : 4;
            return $m;
        });

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
        $today2 = Carbon::today();
        $jun15ThisYear = Carbon::create($today2->year, 6, 15);
        $dec15ThisYear = Carbon::create($today2->year, 12, 15);
        $jun15NextYear = Carbon::create($today2->year + 1, 6, 15);

        if ($today2->lte($jun15ThisYear)) {
            $nextDividendDate = $jun15ThisYear;
        } elseif ($today2->lte($dec15ThisYear)) {
            $nextDividendDate = $dec15ThisYear;
        } else {
            $nextDividendDate = $jun15NextYear;
        }

        // ── Announcements (month + year filter) ───────────────────────────────────
        $announcementMonth = $request->query('announcement_month', Carbon::now()->format('Y-m'));
        $annCarbon = Carbon::createFromFormat('Y-m', $announcementMonth);

        $announcements = collect();
        try {
            if (DB::getSchemaBuilder()->hasTable('announcements_tbls')) {
                $announcements = DB::table('announcements_tbls')
                    ->whereYear('created_at', $annCarbon->year)
                    ->whereMonth('created_at', $annCarbon->month)
                    ->orderByDesc('created_at')
                    ->limit(3)
                    ->get()
                    ->map(fn($a) => [
                        'title' => $a->title,
                        'date' => Carbon::parse($a->created_at)->format('M d'),
                        'description' => $a->description,
                    ]);
            }
        } catch (\Throwable) {
        }

        if ($announcements->isEmpty()) {
            $announcements = collect([
                [
                    'title' => 'Annual General Assembly',
                    'date' => 'Aug 10',
                    'description' => 'All members are invited to the AGM at the Branch 2 hall, 9:00 AM.',
                ],
                [
                    'title' => 'Dividend Declaration',
                    'date' => 'Jul 15',
                    'description' => '5.2% dividend on share capital approved for FY2025, credited Aug 1.',
                ],
                [
                    'title' => 'System Maintenance',
                    'date' => 'Jul 12',
                    'description' => 'Online portal will be unavailable Sunday, 12AM–4AM for upgrades.',
                ],
            ]);
        }

        // ── Recent Transactions (dashboard preview, reuses Transactions page builders) ──
        $recentTransactions = collect()
            ->concat($this->buildShareCapitalEntries($user->id))
            ->concat($this->buildSavingsEntries($user->id))
            ->concat($this->buildLoanEntries($user->id))
            ->sortByDesc(fn($e) => $e['sort_at'])
            ->take(8)
            ->values();

        // ── Upcoming Dues (next payment per active loan) ─────────────────────────
        $typeMapDues = [
            'Personal Lending' => 'Personal Loan',
            'Emergency Lending' => 'Emergency Loan',
            'Business Lending' => 'Business Loan',
            'Education Lending' => 'Education Loan',
        ];

        $upcomingDues = collect();
        foreach ($loans->where('status', 'Approved') as $loan) {
            if (empty($loan->due_date)) {
                continue;
            }

            $dueDateCarbon = Carbon::parse($loan->due_date);
            if ($dueDateCarbon->isPast()) {
                continue; // already overdue — handled separately by penalty logic
            }

            $termMonths = (int) filter_var($loan->lending_type_term, FILTER_SANITIZE_NUMBER_INT);
            $monthlyDue = $termMonths > 0
                ? (float) $loan->lending_amount / $termMonths
                : (float) $loan->lending_amount;

            $displayType = $typeMapDues[$loan->lending_type] ?? $loan->lending_type;
            $daysLeft = (int) Carbon::today()->diffInDays($dueDateCarbon, false);

            $upcomingDues->push([
                'sort_at' => $dueDateCarbon,
                'icon' => 'gold',
                'icon_fa' => 'fa-calendar-day',
                'title' => "{$displayType} Payment",
                'subtitle' => $daysLeft === 0
                    ? 'Due today'
                    : ($daysLeft === 1 ? 'Due tomorrow' : "Due in {$daysLeft} days"),
                'date_display' => $dueDateCarbon->format('M d, Y'),
                'amount' => $monthlyDue,
            ]);
        }

        $upcomingDues = $upcomingDues->sortBy('sort_at')->take(6)->values();

        return view('members_components.member_portal', [
            'username' => $username,
            'email' => $email,
            'firstName' => $firstName,
            'middleName' => $middleName,
            'lastName' => $lastName,
            'member' => $member,

            // Savings
            'savingsAccount' => $savingsAccount,
            'savingsGrowth' => $savingsGrowth,

            'shareCapitalGrowth' => $shareCapitalGrowth,
            'loanBalanceGrowth' => $loanBalanceGrowth,

            // Transactions
            'recentTransactions' => $recentTransactions,

            // Upcoming Dues
            'upcomingDues' => $upcomingDues,

            // Share Capital
            'shareCapitalBalance' => $shareCapitalBalance,
            'shareCapitalShares' => $shareCapitalShares,
            'dividendRate' => $dividendRate,
            'nextDividendDate' => $nextDividendDate,

            // Account Balance chart
            'loanBalance' => $loanBalance,
            'accountBalanceChart' => $accountBalanceChart,

            // Announcements
            'announcements' => $announcements,

            // Year filter
            'selectedYear' => $selectedYear,
            'availableYears' => $availableYears,

            // Loans
            'loans' => $loans,
            'activeLoansCount' => $activeLoansCount,

            'balanceMonth' => $balanceMonth,
            'availableBalanceMonths' => $availableBalanceMonths,

            // Late Fee Penalties
            'penalizedLoans' => $penalizedLoans,
            'totalLateFees' => $totalLateFees,
            'overdueCount' => $overdueCount,
            'lateFeePercentage' => $lateFeePercentage,
            'gracePeriodMonths' => $gracePeriodMonths,

            'netSavingsThisMonth' => $netSavingsThisMonth,
            'netStandingTotal' => $netStandingTotal,
            'nextDueDisplay' => $nextDueDisplay,
            'earliestOverdueDisplay' => $earliestOverdueDisplay,
            'announcementMonth' => $announcementMonth,

            // Net Standing modal
            'standingMonth' => $standingMonth,
            'shareCapitalStandingAsOf' => $shareCapitalStandingAsOf,
            'savingsStandingAsOf' => $savingsStandingAsOf,
            'loanStandingAsOf' => $loanStandingAsOf,
            'netStandingAsOf' => $netStandingAsOf,
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
        $user = Auth::user();

        $username = Auth::check() ? Auth::user()->username : null;
        $email = Auth::check() ? Auth::user()->email : null;
        // $firstName = Auth::check() ? Auth::user()->first_name : null;

        $firstName = $user->first_name ?? '';

        $account = DB::table('share_capital_account_tbls')
            ->where('user_id', auth()->id())
            ->first();

        $currentShares = $account->total_shares ?? 0;
        $canApplyLoan = $currentShares >= 25;

        return view(
            'members_components.loan_application',
            [
                "username" => $username,
                'firstName' => $firstName,
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
                ->whereIn('type', ['Deposit', 'Subscription', ShareCapital::CONVERSION_TYPE])
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
                ->whereIn('type', ['Deposit', 'Subscription', ShareCapital::CONVERSION_TYPE])
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

    public function Settings()
    {
        $user = Auth::user();
        $username = $user->username ?? null;
        $email = $user->email ?? null;
        $memberId = $user->id;

        $settings = \App\Models\AccountSettings_tbl::firstOrCreate(
            ['user_id' => $memberId],
            [
                'loan_reminders' => true,
                'savings_updates' => true,
                'email_digest' => false,
                'announcements' => true,
                'two_factor_enabled' => false,
                'login_alerts' => true,
            ]
        );

        $passwordChangedAt = $user->password_changed_at
            ? Carbon::parse($user->password_changed_at)->diffForHumans()
            : 'Never changed';

        $pendingDeactivation = DB::table('resignation_requests_tbls')
            ->where('user_id', $memberId)
            ->where('status', 'Pending')
            ->exists();

        return view(
            "members_components.settings",
            [
                "username" => $username,
                "email" => $email,
                "settings" => $settings,
                "passwordChangedAt" => $passwordChangedAt,
                "pendingDeactivation" => $pendingDeactivation,
            ]
        );
    }

    public function UpdateSetting(Request $request)
    {
        $request->validate([
            'field' => 'required|string|in:loan_reminders,savings_updates,email_digest,announcements,two_factor_enabled,login_alerts',
            'value' => 'required|boolean',
        ]);

        $memberId = Auth::id();
        $settings = \App\Models\AccountSettings_tbl::firstOrCreate(['user_id' => $memberId]);
        $settings->{$request->field} = $request->boolean('value');
        $settings->save();

        $user = Auth::user();
        AuditLog::log(
            'Updated Settings',
            "{$user->first_name} {$user->last_name} toggled {$request->field} " . ($request->boolean('value') ? 'on' : 'off'),
            'user',
            $memberId
        );

        return response()->json([
            'success' => true,
            'field' => $request->field,
            'value' => $settings->{$request->field},
        ]);
    }

    public function ChangePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:8|confirmed',
        ]);

        $user = Auth::user();

        if (!\Illuminate\Support\Facades\Hash::check($request->current_password, $user->password)) {
            return response()->json(['success' => false, 'message' => 'Current password is incorrect.'], 422);
        }

        $user->password = bcrypt($request->new_password);
        $user->password_changed_at = now();
        $user->save();

        AuditLog::log(
            'Changed Password',
            "{$user->first_name} {$user->last_name} changed their password",
            'user',
            $user->id
        );

        return response()->json(['success' => true, 'message' => 'Password updated successfully.']);
    }

    public function RequestDeactivation(Request $request)
    {
        $request->validate([
            'reason' => 'nullable|string|max:500',
        ]);

        $memberId = Auth::id();

        $alreadyPending = DB::table('resignation_requests_tbls')
            ->where('user_id', $memberId)
            ->where('status', 'Pending')
            ->exists();

        if ($alreadyPending) {
            return response()->json([
                'success' => false,
                'message' => 'You already have a pending deactivation request.',
            ], 422);
        }

        DB::table('resignation_requests_tbls')->insert([
            'user_id' => $memberId,
            'reason' => $request->reason,
            'status' => 'Pending',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $user = Auth::user();
        AuditLog::log(
            'Requested Deactivation',
            "{$user->first_name} {$user->last_name} requested account deactivation",
            'user',
            $memberId
        );

        return response()->json([
            'success' => true,
            'message' => 'Deactivation request submitted. Our staff will contact you.',
        ]);
    }

    public function ExportData()
    {
        $userId = Auth::id();

        $user = Users_tbl::find($userId);
        $otherinfo = Otherinfo_tbl::where('user_id', $userId)->first();
        $savingsAccount = savings_account_tbl::where('user_id', $userId)->first();
        $shareCapitalAccount = share_capital_account_tbl::where('user_id', $userId)->first();
        $loans = lending_program_tbl::where('user_id', $userId)->get();

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('exports.member_data', compact(
            'user',
            'otherinfo',
            'savingsAccount',
            'shareCapitalAccount',
            'loans'
        ));

        AuditLog::log(
            'Exported Data',
            "{$user->first_name} {$user->last_name} exported their membership data",
            'user',
            $userId
        );

        return $pdf->download('membership-record-' . $user->id . '.pdf');
    }

    public function Transactions(Request $request)
    {
        $username = Auth::check() ? Auth::user()->username : null;
        $email = Auth::check() ? Auth::user()->email : null;
        $memberId = Auth::id();

        // ── Filters from query string ──────────────────────────────
        $type = $request->query('type', 'all');           // all | share_capital | savings | loans
        $search = trim((string) $request->query('search', ''));
        $date = $request->query('date', '');
        $status = strtolower(trim((string) $request->query('status', 'all'))); // all | pending | completed | released | locked | credited | rejected | approved
        $page = max(1, (int) $request->query('page', 1));
        $perPage = 10;

        $entries = collect()
            ->concat($this->buildShareCapitalEntries($memberId))
            ->concat($this->buildSavingsEntries($memberId))
            ->concat($this->buildLoanEntries($memberId));

        // ── Summary cards (computed from ALL completed entries, unfiltered by tab/search) ──
        $completed = $entries->filter(fn($e) => $e['status_class'] === 'completed');

        $totalDeposits = $completed
            ->filter(fn($e) => in_array($e['category'], ['share_capital', 'savings']) && $e['amount'] > 0)
            ->sum('amount');

        $totalRepayments = abs($completed
            ->filter(fn($e) => $e['category'] === 'loans' && $e['amount'] < 0)
            ->sum('amount'));

        $thisMonth = $completed->filter(
            fn($e) =>
            $e['sort_at']->isSameMonth(now()) && $e['sort_at']->isSameYear(now())
        );
        $transactThisMonth = $thisMonth->sum(fn($e) => abs($e['amount']));

        $netChange = $completed->sum('amount');

        // ── Build the list of statuses actually present, for the dropdown ──
        // Uses status_label (the granular, human-facing value: "Locked", "Released",
        // "Credited", etc.) rather than status_class, which only ever holds
        // 'completed' or 'pending' and would collapse the dropdown to 2 options.
        $availableStatuses = $entries
            ->pluck('status_label')
            ->filter()
            ->map(fn($s) => trim($s))
            ->unique()
            ->sortBy(fn($s) => strtolower($s))
            ->values();

        // ── Apply date filter ──────────────────────────────────
        if ($date !== '') {
            $entries = $entries->filter(fn($e) => $e['sort_at']->format('Y-m-d') === $date);
        }

        // ── Apply tab filter ──────────────────────────────────────────
        if ($type !== 'all') {
            $entries = $entries->filter(fn($e) => $e['category'] === $type);
        }

        // ── Apply status filter (matches on the granular status_label, case-insensitive) ──
        if ($status !== 'all') {
            $entries = $entries->filter(fn($e) => strtolower($e['status_label'] ?? '') === $status);
        }

        // ── Apply search (description or reference no.) ────────────────
        if ($search !== '') {
            $needle = strtolower($search);
            $entries = $entries->filter(function ($e) use ($needle) {
                return str_contains(strtolower($e['title']), $needle)
                    || str_contains(strtolower($e['subtitle']), $needle)
                    || str_contains(strtolower($e['reference_no']), $needle);
            });
        }

        // ── Sort newest first, then paginate manually ───────────────────
        $entries = $entries->sortByDesc(fn($e) => $e['sort_at'])->values();
        $total = $entries->count();
        $paged = $entries->slice(($page - 1) * $perPage, $perPage)->values();

        $transactions = new \Illuminate\Pagination\LengthAwarePaginator(
            $paged,
            $total,
            $perPage,
            $page,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        return view(
            "members_components.transactions",
            [
                "username" => $username,
                "email" => $email,
                "transactions" => $transactions,
                "type" => $type,
                "search" => $search,
                "date" => $date,
                "status" => $status,
                "availableStatuses" => $availableStatuses,
                "totalDeposits" => $totalDeposits,
                "totalRepayments" => $totalRepayments,
                "transactThisMonth" => $transactThisMonth,
                "netChange" => $netChange,
            ]
        );
    }

    // ─────────────────────────────────────────────────────────────────
// Normalized transaction builders
// ─────────────────────────────────────────────────────────────────

    private function buildShareCapitalEntries($memberId)
    {
        $account = DB::table('share_capital_account_tbls')->where('user_id', $memberId)->first();
        if (!$account)
            return collect();

        return DB::table('share_capital_transaction_tbls')
            ->where('share_capital_account_id', $account->id)
            ->get()
            ->map(function ($row) {
                $isDeposit = in_array($row->type, ['Deposit', 'Subscription', ShareCapital::CONVERSION_TYPE]);
                $statusRaw = strtolower($row->status ?? '');
                $statusClass = str_contains($statusRaw, 'complet') ? 'completed'
                    : (str_contains($statusRaw, 'pend') ? 'pending' : 'pending');

                return [
                    'sort_at' => Carbon::parse($row->created_at ?? $row->transaction_date),
                    'category' => 'share_capital',
                    'icon' => 'gold',
                    'icon_fa' => 'fa-layer-group',
                    'title' => $isDeposit ? 'Share Capital Contribution' : 'Share Capital Withdrawal',
                    'subtitle' => $row->note ?: ($isDeposit ? 'Deposit' : 'Withdrawal request'),
                    'reference_no' => $row->reference_no ?? '—',
                    'date_display' => Carbon::parse($row->transaction_date)->format('M d, Y'),
                    'time_display' => Carbon::parse($row->created_at ?? $row->transaction_date)
                        ->timezone('Asia/Manila')->format('g:i A'),
                    'amount' => $isDeposit ? (float) $row->total_amount : -(float) $row->total_amount,
                    'status_label' => ucfirst($statusRaw ?: 'Pending'),
                    'status_class' => $statusClass,
                ];
            });
    }

    private function buildSavingsEntries($memberId)
    {
        $account = DB::table('savings_account_tbls')->where('user_id', $memberId)->first();
        if (!$account)
            return collect();

        return DB::table('savings_transaction_tbls')
            ->where('savings_account_id', $account->id)
            ->get()
            ->map(function ($row) {
                $config = match ($row->type) {
                    'deposit' => ['title' => 'Savings Deposit', 'subtitle' => 'Regular Savings', 'icon' => 'savings', 'icon_fa' => 'fa-piggy-bank', 'sign' => 1],
                    'withdrawal' => ['title' => 'Savings Withdrawal', 'subtitle' => 'Regular Savings', 'icon' => 'savings', 'icon_fa' => 'fa-piggy-bank', 'sign' => -1],
                    'td_open' => ['title' => 'Time Deposit (Opened)', 'subtitle' => 'Goal set, no funds moved', 'icon' => 'gold', 'icon_fa' => 'fa-bullseye', 'sign' => 0],
                    'td_lock' => ['title' => 'Time Deposit (Deposit)', 'subtitle' => 'Deposited toward TD goal', 'icon' => 'savings', 'icon_fa' => 'fa-piggy-bank', 'sign' => 1],
                    'td_release' => ['title' => 'Time Deposit (Claimed)', 'subtitle' => 'Principal + interest released', 'icon' => 'mint', 'icon_fa' => 'fa-hand-holding-dollar', 'sign' => 1],
                    default => ['title' => ucfirst(str_replace('_', ' ', $row->type)), 'subtitle' => 'Savings activity', 'icon' => 'savings', 'icon_fa' => 'fa-piggy-bank', 'sign' => 1],
                };

                return [
                    'sort_at' => Carbon::parse($row->created_at ?? $row->transaction_date),
                    'category' => 'savings',
                    'icon' => $config['icon'],
                    'icon_fa' => $config['icon_fa'],
                    'title' => $config['title'],
                    'subtitle' => $row->note ?: $config['subtitle'],
                    'reference_no' => $row->reference_no ?? '—',
                    'date_display' => Carbon::parse($row->transaction_date)->format('M d, Y'),
                    'time_display' => Carbon::parse($row->created_at ?? $row->transaction_date)
                        ->timezone('Asia/Manila')->format('g:i A'),
                    'amount' => $config['sign'] * (float) $row->amount,
                    'status_label' => ucfirst($row->status ?? 'Completed'),
                    'status_class' => strtolower($row->status ?? 'completed') === 'pending' ? 'pending' : 'completed',
                ];
            });
    }

    private function buildLoanEntries($memberId)
    {
        $typeMap = [
            'Personal Lending' => 'Personal Loan',
            'Emergency Lending' => 'Emergency Loan',
            'Business Lending' => 'Business Loan',
            'Education Lending' => 'Education Loan',
        ];

        $loans = DB::table('lending_program_tbls')
            ->where('user_id', $memberId)
            ->get();

        // 1) Loan Application — one entry per submitted application
        $applications = $loans->map(function ($row) use ($typeMap) {
            $displayType = $typeMap[$row->lending_type] ?? $row->lending_type;
            $statusRaw = strtolower($row->status ?? 'pending');
            $statusClass = $statusRaw === 'pending' ? 'pending' : 'completed';

            return [
                'sort_at' => Carbon::parse($row->created_at),
                'category' => 'loans',
                'icon' => 'gold',
                'icon_fa' => 'fa-file-signature',
                'title' => 'Loan Application',
                'subtitle' => "{$displayType} application submitted",
                'reference_no' => $row->reference_no ?? '—',
                'date_display' => Carbon::parse($row->created_at)->format('M d, Y'),
                'time_display' => Carbon::parse($row->created_at)->timezone('Asia/Manila')->format('g:i A'),
                'amount' => 0,
                'status_label' => ucfirst($row->status ?? 'Pending'),
                'status_class' => $statusClass,
            ];
        });

        // 2) Loan Approval / Decline — the decision on the application
        $decisions = $loans
            ->filter(fn($row) => in_array(strtolower($row->status ?? ''), ['approved', 'declined', 'rejected']))
            ->map(function ($row) use ($typeMap) {
                $displayType = $typeMap[$row->lending_type] ?? $row->lending_type;
                $isApproved = strtolower($row->status) === 'approved';

                return [
                    'sort_at' => Carbon::parse($row->updated_at ?? $row->created_at),
                    'category' => 'loans',
                    'icon' => $isApproved ? 'mint' : 'coral',
                    'icon_fa' => $isApproved ? 'fa-circle-check' : 'fa-circle-xmark',
                    'title' => $isApproved ? 'Loan Approved' : 'Loan Declined',
                    'subtitle' => $isApproved
                        ? "{$displayType} approved"
                        : trim("{$displayType} declined" . ($row->decline_reason ? " — {$row->decline_reason}" : '')),
                    'reference_no' => $row->reference_no ?? '—',
                    'date_display' => Carbon::parse($row->updated_at ?? $row->created_at)->format('M d, Y'),
                    'time_display' => Carbon::parse($row->updated_at ?? $row->created_at)->timezone('Asia/Manila')->format('g:i A'),
                    'amount' => 0,
                    'status_label' => $isApproved ? 'Approved' : 'Declined',
                    'status_class' => 'completed',
                ];
            });

        // 3) Loan Disbursement — money actually released
        $disbursements = $loans
            ->filter(fn($row) => strtolower($row->status ?? '') === 'approved' && !empty($row->disbursed_at))
            ->map(function ($row) use ($typeMap) {
                $displayType = $typeMap[$row->lending_type] ?? $row->lending_type;

                return [
                    'sort_at' => Carbon::parse($row->disbursed_at),
                    'category' => 'loans',
                    'icon' => 'coral',
                    'icon_fa' => 'fa-file-invoice-dollar',
                    'title' => 'Loan Disbursement',
                    'subtitle' => "{$displayType} released" . ($row->disbursement_method ? " via {$row->disbursement_method}" : ''),
                    'reference_no' => $row->disbursement_reference ?? $row->reference_no ?? '—',
                    'date_display' => Carbon::parse($row->disbursed_at)->format('M d, Y'),
                    'time_display' => Carbon::parse($row->disbursed_at)->timezone('Asia/Manila')->format('g:i A'),
                    'amount' => (float) ($row->net_proceeds ?? $row->lending_amount),
                    'status_label' => 'Completed',
                    'status_class' => 'completed',
                ];
            });

        // 4) Loan Repayment — unchanged from before
        $repayments = DB::table('lending_repayments_tbls as r')
            ->leftJoin('lending_status_tbls as s', 's.lending_id', '=', 'r.lending_id')
            ->where('r.user_id', $memberId)
            ->select('r.*', 's.total_payments')
            ->get()
            ->map(function ($row) {
                $totalPayments = $row->total_payments ?? '?';
                return [
                    'sort_at' => Carbon::parse($row->created_at ?? $row->payment_date),
                    'category' => 'loans',
                    'icon' => 'mint',
                    'icon_fa' => 'fa-hand-holding-dollar',
                    'title' => 'Loan Repayment',
                    'subtitle' => "Installment {$row->payment_number} of {$totalPayments}",
                    'reference_no' => $row->reference_no ?? '—',
                    'date_display' => Carbon::parse($row->payment_date)->format('M d, Y'),
                    'time_display' => Carbon::parse($row->created_at ?? $row->payment_date)->timezone('Asia/Manila')->format('g:i A'),
                    'amount' => -(float) $row->amount_paid,
                    'status_label' => 'Completed',
                    'status_class' => 'completed',
                ];
            });

        return $applications->concat($decisions)->concat($disbursements)->concat($repayments);
    }

    public function Notifications()
    {
        $username = Auth::check() ? Auth::user()->username : null;
        $email = Auth::check() ? Auth::user()->email : null;
        $memberId = Auth::id();

        $notifications = \App\Models\Notifications_tbl::where('user_id', $memberId)
            ->orderBy('created_at', 'desc')
            ->get();

        $unreadCount = $notifications->where('is_read', false)->count();
        $importantCount = $notifications->where('is_important', true)->count();
        $inboxCount = $notifications->where('category', 'inbox')->count();
        $announcementCount = $notifications->where('category', 'announcement')->count();
        $spamCount = $notifications->where('category', 'spam')->count();
        $socialCount = $notifications->where('category', 'social')->count();

        // Group notifications into date buckets for display
        $grouped = $notifications->groupBy(function ($n) {
            $date = Carbon::parse($n->created_at);
            if ($date->isToday()) {
                return 'Today · ' . $date->format('M d, Y');
            } elseif ($date->isCurrentWeek()) {
                return 'Earlier this week';
            } elseif ($date->isCurrentMonth()) {
                return 'Earlier this month';
            }
            return $date->format('F Y');
        });

        return view(
            "members_components.notifications",
            [
                "username" => $username,
                "email" => $email,
                "notifications" => $notifications,
                "grouped" => $grouped,
                "unreadCount" => $unreadCount,
                "importantCount" => $importantCount,
                "inboxCount" => $inboxCount,
                "announcementCount" => $announcementCount,
                "spamCount" => $spamCount,
                "socialCount" => $socialCount,
            ]
        );
    }

    public function MarkAllRead(Request $request)
    {
        $memberId = Auth::id();

        \App\Models\Notifications_tbl::where('user_id', $memberId)
            ->where('is_read', false)
            ->update(['is_read' => true, 'updated_at' => now()]);

        if ($request->wantsJson()) {
            return response()->json(['success' => true]);
        }

        return redirect()->back()->with('success', 'All notifications marked as read.');
    }

    public function Financial(Request $request)
    {
        $user = Auth::user();
        $username = $user->username ?? null;
        $email = $user->email ?? null;
        $userId = $user->id;

        $currentYear = (int) now()->year;
        $year = (int) $request->query('year', $currentYear);
        $activeTab = $request->query('tab', 'dividends'); // 'dividends' | 'patronage' | 'records'
        $search = trim($request->query('search', ''));

        // ★ NEW: shared filter inputs
        $statusFilter = $request->query('status', 'all');
        $dateFilter = $request->query('date'); // Y-m-d or null

        $availableYears = \App\Models\Dividend::where('user_id', $userId)
            ->pluck('year')
            ->merge(
                \App\Models\PatronageRefundDistribution::where('user_id', $userId)->pluck('year')
            )
            ->push($currentYear)
            ->unique()
            ->sortDesc()
            ->values();

        // ── My Dividends for the selected year (paginated + filtered) ────
        $dividendsQuery = \App\Models\Dividend::where('user_id', $userId)
            ->where('year', $year);

        if ($statusFilter !== 'all') {
            $dividendsQuery->where('status', $statusFilter);
        }
        if ($dateFilter) {
            $dividendsQuery->whereDate('updated_at', $dateFilter);
        }
        if ($search !== '') {
            $dividendsQuery->where(function ($q) use ($search) {
                $q->where('approved_amount', 'like', "%{$search}%")
                    ->orWhere('recommended_amount', 'like', "%{$search}%")
                    ->orWhere('share_capital_amount', 'like', "%{$search}%");
            });
        }

        $myDividends = $dividendsQuery
            ->orderByDesc('created_at')
            ->paginate(10, ['*'], 'dividends_page')
            ->withQueryString();

        $totalDividendsApproved = \App\Models\Dividend::where('user_id', $userId)
            ->where('year', $year)
            ->whereIn('status', ['approved', 'disbursed'])
            ->sum('approved_amount');

        $totalDividendsDisbursed = \App\Models\Dividend::where('user_id', $userId)
            ->where('year', $year)
            ->where('status', 'disbursed')
            ->sum('approved_amount');

        // ── My Patronage Refunds for the selected year (paginated + filtered) ──
        $patronageQuery = \App\Models\PatronageRefundDistribution::where('user_id', $userId)
            ->where('year', $year);

        if ($statusFilter !== 'all') {
            $patronageQuery->where('status', $statusFilter);
        }
        if ($dateFilter) {
            $patronageQuery->whereDate('updated_at', $dateFilter);
        }
        if ($search !== '') {
            $patronageQuery->where(function ($q) use ($search) {
                $q->where('amount', 'like', "%{$search}%")
                    ->orWhere('total_patronage', 'like', "%{$search}%");
            });
        }

        $myPatronageRefunds = $patronageQuery
            ->orderByDesc('created_at')
            ->paginate(10, ['*'], 'patronage_page')
            ->withQueryString();

        $totalPatronageApproved = \App\Models\PatronageRefundDistribution::where('user_id', $userId)
            ->where('year', $year)
            ->whereIn('status', ['approved', 'disbursed'])
            ->sum('amount');

        $totalPatronageDisbursed = \App\Models\PatronageRefundDistribution::where('user_id', $userId)
            ->where('year', $year)
            ->where('status', 'disbursed')
            ->sum('amount');

        // ── Additional Patronage Records (date filter only — no status field) ──
        $recordsQuery = \App\Models\PatronageRecord::where('user_id', $userId)
            ->where('year', $year);

        if ($dateFilter) {
            $recordsQuery->whereDate('created_at', $dateFilter);
        }
        if ($search !== '') {
            $recordsQuery->where(function ($q) use ($search) {
                $q->where('source', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhere('amount', 'like', "%{$search}%");
            });
        }

        $myPatronageRecords = $recordsQuery
            ->orderByDesc('created_at')
            ->paginate(10, ['*'], 'records_page')
            ->withQueryString();

        $totalAdditionalPatronage = \App\Models\PatronageRecord::where('user_id', $userId)
            ->where('year', $year)
            ->sum('amount');

        $lifetimeDividends = \App\Models\Dividend::where('user_id', $userId)
            ->where('status', 'disbursed')
            ->sum('approved_amount');

        $lifetimePatronage = \App\Models\PatronageRefundDistribution::where('user_id', $userId)
            ->where('status', 'disbursed')
            ->sum('amount');

        return view('members_components.Financial', [
            'username' => $username,
            'email' => $email,
            'year' => $year,
            'currentYear' => $currentYear,
            'availableYears' => $availableYears,
            'activeTab' => $activeTab,
            'statusFilter' => $statusFilter,   // ★ NEW
            'search' => $search,
            'dateFilter' => $dateFilter,       // ★ NEW
            'myDividends' => $myDividends,
            'totalDividendsApproved' => $totalDividendsApproved,
            'totalDividendsDisbursed' => $totalDividendsDisbursed,
            'myPatronageRefunds' => $myPatronageRefunds,
            'totalPatronageApproved' => $totalPatronageApproved,
            'totalPatronageDisbursed' => $totalPatronageDisbursed,
            'myPatronageRecords' => $myPatronageRecords,
            'totalAdditionalPatronage' => $totalAdditionalPatronage,
            'lifetimeDividends' => $lifetimeDividends,
            'lifetimePatronage' => $lifetimePatronage,
        ]);
    }

    public function Seminars()
    {
        $user = Auth::user();
        $username = $user->username ?? null;
        $email = $user->email ?? null;
        $userId = $user->id;

        $typeLabels = [
            'pmes' => 'PMES',
            'fundamentals' => 'Fundamentals of Coops',
            'finance' => 'Cooperative Finance',
        ];

        $typeLabels = \App\Models\SeminarTypes_tbl::all()
            ->pluck('label', 'slug')
            ->union($typeLabels)
            ->all();

        // ── All of this member's attendee records, with their seminar info ──
        $attendeeRecords = \App\Models\SeminarAttendees_tbl::with('seminar')
            ->where('user_id', $userId)
            ->get()
            ->filter(fn($a) => $a->seminar); // guard against orphaned rows

        // ── Upcoming: scheduled sessions this member is registered for ──
        $upcomingSeminars = $attendeeRecords
            ->filter(fn($a) => $a->seminar->schedule_datetime >= now() && $a->status === 'pending')
            ->sortBy(fn($a) => $a->seminar->schedule_datetime)
            ->map(function ($a) use ($typeLabels) {
                $s = $a->seminar;
                return [
                    'label' => $typeLabels[$s->seminar_type] ?? ucfirst($s->seminar_type),
                    'datetime' => $s->schedule_datetime,
                    'delivery_type' => $s->delivery_type,
                    'online_link' => $s->online_link,
                    'meetup_place' => $s->meetup_place,
                    'exact_venue' => $s->exact_venue,
                ];
            })
            ->values();

        // ── History: past sessions — attended, absent, or awaiting admin marking ──
        $seminarHistory = $attendeeRecords
            ->filter(fn($a) => $a->seminar->schedule_datetime < now())
            ->sortByDesc(fn($a) => $a->seminar->schedule_datetime)
            ->map(function ($a) use ($typeLabels) {
                $s = $a->seminar;

                $status = match ($a->status) {
                    'attended' => 'attended',
                    'absent' => 'missed',
                    default => 'pending_review', // date passed, admin hasn't marked yet
                };

                return [
                    'label' => $typeLabels[$s->seminar_type] ?? ucfirst($s->seminar_type),
                    'datetime' => $s->schedule_datetime,
                    'delivery_type' => $s->delivery_type,
                    'meetup_place' => $s->meetup_place,
                    'status' => $status,
                ];
            })
            ->values();

        // ── Completion summary (drives hero card) ──
        $completion = \App\Models\SeminarCompletions_tbl::where('user_id', $userId)->first();
        $completedFlags = [
            'pmes' => (bool) ($completion->pmes_completed ?? false),
            'fundamentals' => (bool) ($completion->fundamentals_completed ?? false),
            'finance' => (bool) ($completion->finance_completed ?? false),
        ];

        $totalSeminars = count($completedFlags);
        $completedCount = collect($completedFlags)->filter()->count();
        $remainingCount = $totalSeminars - $completedCount;
        $isFullyComplete = $remainingCount === 0;

        $remainingLabels = collect($completedFlags)
            ->filter(fn($done) => !$done)
            ->keys()
            ->map(fn($key) => $typeLabels[$key]);

        $nextUpcoming = $upcomingSeminars->first();

        if ($isFullyComplete) {
            $heroTitle = "You've completed full membership training!";
            $heroSubtitle = "You've finished all required seminars and are a full member in good standing.";
            $heroNextLine = null;
        } else {
            $heroTitle = $remainingCount === 1
                ? "You're one seminar away from full membership."
                : "Complete {$remainingCount} more seminars to deepen your understanding of the cooperative.";

            $heroSubtitle = $remainingCount === 1
                ? "Complete {$remainingLabels->first()} to finish the required track and unlock full member benefits."
                : "Attend the required sessions below to unlock full member benefits and gain access to exclusive cooperative services, programs, and opportunities designed to support your financial growth.";

            $heroNextLine = null;
            if ($nextUpcoming) {
                $deliveryText = $nextUpcoming['delivery_type'] === 'online'
                    ? 'Online'
                    : 'F2F · ' . ($nextUpcoming['meetup_place'] ?? 'Venue TBA');

                $heroNextLine = "Next up: {$nextUpcoming['label']} · "
                    . $nextUpcoming['datetime']->format('M d') . ' · ' . $deliveryText;
            } else {
                $heroNextLine = 'Awaiting schedule from the cooperative.';
            }
        }

        $passcodeTypes = collect($completedFlags)
            ->filter(fn($done) => !$done)
            ->map(fn($done, $slug) => ['slug' => $slug, 'label' => $typeLabels[$slug]])
            ->values()
            ->all();

        return view('members_components.seminars', [
            'username' => $username,
            'email' => $email,
            'upcomingSeminars' => $upcomingSeminars,
            'seminarHistory' => $seminarHistory,
            'totalSeminars' => $totalSeminars,
            'completedCount' => $completedCount,
            'remainingCount' => $remainingCount,
            'isFullyComplete' => $isFullyComplete,
            'heroTitle' => $heroTitle,
            'heroSubtitle' => $heroSubtitle,
            'heroNextLine' => $heroNextLine,
            'passcodeTypes' => $passcodeTypes,
        ]);
    }

    public function verifySeminarPasscode(Request $request)
    {
        $request->validate([
            'seminar_type' => 'required|in:pmes,fundamentals,finance',
            'passcode' => 'required|string|max:64',
        ]);

        $user = Auth::user();
        $passcode = \App\Models\SeminarPasscodes_tbl::where('seminar_type', $request->seminar_type)->first();

        if (!$passcode) {
            return redirect()->route('Seminars')->with('error', 'No passcode has been set for this seminar yet.');
        }

        if ($passcode->expires_at && now()->gt($passcode->expires_at)) {
            return redirect()->route('Seminars')->with('error', 'This passcode has already expired.');
        }

        if (!hash_equals((string) $passcode->passcode, (string) $request->passcode)) {
            return redirect()->route('Seminars')->with('error', 'The passcode you entered is incorrect.');
        }

        $completion = \App\Models\SeminarCompletions_tbl::firstOrCreate(
            ['user_id' => $user->id],
            [
                'pmes_completed' => false,
                'fundamentals_completed' => false,
                'finance_completed' => false,
            ]
        );

        $column = $request->seminar_type . '_completed';
        if ($completion->$column) {
            return redirect()->route('Seminars')->with('info', 'You have already completed this seminar.');
        }

        $completion->$column = true;
        $completion->save();

        \App\Http\Controllers\SeminarController::autoUpgradeIfComplete($user->id, $completion);

        \App\Models\AuditLog::log(
            'Verified Seminar Passcode',
            "User #{$user->id} verified passcode for {$request->seminar_type}",
            'seminar_passcode',
            $user->id
        );

        return redirect()->route('Seminars')->with('success', 'Passcode accepted! Seminar marked as completed.');
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

        // ── Loan data for Account Balance & Repayment Progress ──────────────
        $typeMap = [
            'Personal Lending' => 'Personal Loan',
            'Emergency Lending' => 'Emergency Loan',
            'Business Lending' => 'Business Loan',
            'Education Lending' => 'Education Loan',
        ];

        $approvedLoans = DB::table('lending_program_tbls as l')
            ->leftJoin('lending_status_tbls as s', 's.lending_id', '=', 'l.id')
            ->where('l.user_id', $userId)
            ->where('l.status', 'Approved')
            ->select('l.*', 's.remaining_balance', 's.total_payments', 's.payments_made')
            ->get()
            ->map(function ($loan) use ($typeMap) {
                $loan->display_type = $typeMap[$loan->lending_type] ?? $loan->lending_type;
                $totalPayments = (int) ($loan->total_payments ?? 0);
                $paymentsMade = (int) ($loan->payments_made ?? 0);
                $loan->progress_percent = $totalPayments > 0
                    ? min(100, round(($paymentsMade / $totalPayments) * 100))
                    : 0;
                $loan->remaining_balance = (float) ($loan->remaining_balance ?? $loan->total_payment ?? $loan->lending_amount);
                return $loan;
            });

        // Total outstanding across ALL active loans → feeds "Loan Balance" stat
        $loanBalance = $approvedLoans->sum('remaining_balance');

        // Grouped by type → feeds "Loan Repayment Progress" panel.
        // Always show all 4 loan types, even ones the member has never taken —
        // zero balance / zero progress rather than omitting the row entirely.
        $loansByTypeActual = $approvedLoans->groupBy('display_type')->map(function ($loans) {
            return [
                'balance' => $loans->sum('remaining_balance'),
                'progress' => (int) round($loans->avg('progress_percent')),
            ];
        });

        $allLoanTypes = ['Personal Loan', 'Emergency Loan', 'Business Loan', 'Education Loan'];
        $loansByType = collect($allLoanTypes)->mapWithKeys(function ($type) use ($loansByTypeActual) {
            return [$type => $loansByTypeActual[$type] ?? ['balance' => 0, 'progress' => 0]];
        });

        $savedMonthlyIncome = DB::table('lending_program_tbls')
            ->where('user_id', $userId)
            ->whereNotNull('monthly_income')
            ->orderBy('created_at', 'desc')
            ->value('monthly_income');

        $shareCapitalBalance = (float) ($shareCapitalAccount->total_amount ?? 0);
        $savingsBalance = (float) ($savingsAccount->balance ?? 0);
        // Overall = straight sum of all three balances shown in the Account Balance
// card, not netted against the loan — the card lists Loan Balance as its
// own line, so this total is a "what's on this card" sum, not net worth.
        $overallBalance = $shareCapitalBalance + $savingsBalance + $loanBalance;

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
                        'amount' => in_array($item->type, ['Subscription', 'Deposit', ShareCapital::CONVERSION_TYPE]) ? $item->total_amount : -$item->total_amount,
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
        if ($otherinfo && empty($otherinfo->contact_no))
            $missingCount++;
        if ($otherinfo && empty($otherinfo->present_address))
            $missingCount++;
        if ($otherinfo && empty($otherinfo->permanent_address))
            $missingCount++;
        if ($otherinfo && empty($otherinfo->date_of_birth))
            $missingCount++;
        if ($otherinfo && empty($otherinfo->place_of_birth))
            $missingCount++;
        if ($otherinfo && empty($otherinfo->sex))
            $missingCount++;
        if ($otherinfo && empty($otherinfo->civil_status))
            $missingCount++;
        if ($otherinfo && empty($otherinfo->citizenship))
            $missingCount++;
        if ($otherinfo && empty($otherinfo->blood_type))
            $missingCount++;
        if ($otherinfo && empty($otherinfo->height))
            $missingCount++;
        if ($otherinfo && empty($otherinfo->weight))
            $missingCount++;
        if ($membergovernIds && empty($membergovernIds->sss_id))
            $missingCount++;
        if ($membergovernIds && empty($membergovernIds->philhealth_id))
            $missingCount++;
        if ($membergovernIds && empty($membergovernIds->pagibig_id))
            $missingCount++;
        if ($membergovernIds && empty($membergovernIds->tin_id))
            $missingCount++;

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
                // ── new ──
                "loanBalance" => $loanBalance,
                "loansByType" => $loansByType,
                "shareCapitalBalance" => $shareCapitalBalance,
                "savingsBalance" => $savingsBalance,
                "overallBalance" => $overallBalance,
                "savedMonthlyIncome" => $savedMonthlyIncome,
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
        $membergovernIds = Membergovern_ids_tbl::where('user_id', $userId)->first();
        $family = Family_tbl::where('user_id', $userId)->first();

        if ($request->_form === 'personal') {
            $request->validate([
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
            ]);
        }

        if ($request->_form === 'personal') {
            $user->first_name = $request->first_name;
            $user->middle_name = $request->middle_name;
            $user->last_name = $request->last_name;
            $user->save();
        }

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

        Otherinfo_tbl::updateOrCreate(['user_id' => $userId], $updateData);

        $govIdsData = [];
        $idFields = ['sss_id', 'philhealth_id', 'pagibig_id', 'tin_id'];

        foreach ($idFields as $field) {
            if ($request->hasFile($field)) {
                $govIdsData[$field] = $request->file($field)->store('government_ids', 'public');
            } elseif (!empty($membergovernIds->$field)) {
                $govIdsData[$field] = $membergovernIds->$field;
            }
        }

        if (!empty($govIdsData)) {
            Membergovern_ids_tbl::updateOrCreate(['user_id' => $userId], $govIdsData);
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
            Family_tbl::updateOrCreate(['user_id' => $userId], $familyData);
        }

        AuditLog::log(
            'Updated Profile',
            "{$user->first_name} {$user->last_name} updated their profile",
            'user',
            $userId
        );

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
        if (empty($otherinfo->contact_no))
            $missingCount++;
        if (empty($otherinfo->present_address))
            $missingCount++;
        if (empty($otherinfo->permanent_address))
            $missingCount++;
        if (empty($otherinfo->date_of_birth))
            $missingCount++;
        if (empty($otherinfo->place_of_birth))
            $missingCount++;
        if (empty($otherinfo->sex))
            $missingCount++;
        if (empty($otherinfo->civil_status))
            $missingCount++;
        if (empty($otherinfo->citizenship))
            $missingCount++;
        if (empty($otherinfo->blood_type))
            $missingCount++;
        if (empty($otherinfo->height))
            $missingCount++;
        if (empty($otherinfo->weight))
            $missingCount++;
        if (empty($membergovernIds->sss_id))
            $missingCount++;
        if (empty($membergovernIds->philhealth_id))
            $missingCount++;
        if (empty($membergovernIds->pagibig_id))
            $missingCount++;
        if (empty($membergovernIds->tin_id))
            $missingCount++;

        // ★ NEW: dynamic reminder-style notifications (not stored rows — computed live)
        $navNotifications = $this->buildMemberNotifications($userId);

        return view(
            "components.navbar2",
            [
                "username" => $username,
                "email" => $email,
                "missingCount" => $missingCount,
                "navNotifications" => $navNotifications, // ★ NEW
            ]
        );
    }

    /**
     * Builds the bell-icon notification feed: loan due dates, the Share Capital
     * 2-year CBU subscription deadline, patronage refunds credited to Savings,
     * and Time Deposit maturity reminders. Computed fresh on every page load —
     * nothing here is stored in notifications_tbls.
     */
    private function buildMemberNotifications($userId)
    {
        $notifications = collect();
        $today = Carbon::today();

        // ── 1) Loan due dates ──────────────────────────────────────────
        $typeMap = [
            'Personal Lending' => 'Personal Loan',
            'Emergency Lending' => 'Emergency Loan',
            'Business Lending' => 'Business Loan',
            'Education Lending' => 'Education Loan',
        ];

        $loans = DB::table('lending_program_tbls')
            ->where('user_id', $userId)
            ->where('status', 'Approved')
            ->whereNotNull('due_date')
            ->get();

        foreach ($loans as $loan) {
            $due = Carbon::parse($loan->due_date);
            $daysLeft = (int) $today->diffInDays($due, false);
            $displayType = $typeMap[$loan->lending_type] ?? $loan->lending_type;

            if ($daysLeft < 0) {
                $notifications->push([
                    'icon' => 'fa-triangle-exclamation',
                    'color' => 'red',
                    'title' => 'Loan Payment Overdue',
                    'message' => "{$displayType} was due on {$due->format('M d, Y')}. Please settle to avoid additional late fees.",
                    'time' => $due->diffForHumans(),
                    'sort_at' => $due,
                ]);
            } elseif ($daysLeft <= 7) {
                $notifications->push([
                    'icon' => 'fa-calendar-day',
                    'color' => 'gold',
                    'title' => 'Loan Payment Due Soon',
                    'message' => "{$displayType} is due on {$due->format('M d, Y')}"
                        . ($daysLeft === 0 ? ' (today)' : " (in {$daysLeft} day" . ($daysLeft === 1 ? '' : 's') . ")") . ".",
                    'time' => $due->diffForHumans(),
                    'sort_at' => $due,
                ]);
            }
        }

        // ── 2) Share Capital — 2-year CBU subscription deadline ─────────
        $scAccount = DB::table('share_capital_account_tbls')->where('user_id', $userId)->first();

        if ($scAccount) {
            $paidUp = DB::table('share_capital_transaction_tbls')
                ->where('share_capital_account_id', $scAccount->id)
                ->whereIn('type', ['Deposit', 'Subscription', ShareCapital::CONVERSION_TYPE])
                ->whereIn('status', ['Completed', 'completed'])
                ->sum('total_amount') ?? 0;

            $targetAmount = 10000; // full CBU subscription target
            $deadline = Carbon::parse($scAccount->created_at)->addYears(2);
            $daysLeft = (int) $today->diffInDays($deadline, false);

            // Only surface it once we're within 90 days of the deadline (or past it), and only if unpaid
            if ($paidUp < $targetAmount && $daysLeft <= 90) {
                $remaining = $targetAmount - $paidUp;

                $notifications->push([
                    'icon' => 'fa-layer-group',
                    'color' => $daysLeft < 0 ? 'red' : 'gold',
                    'title' => $daysLeft < 0 ? 'Share Capital Subscription Overdue' : 'Share Capital Deadline Approaching',
                    'message' => $daysLeft < 0
                        ? "Your 2-year window to complete your ₱" . number_format($targetAmount, 2) . " share capital subscription ended on {$deadline->format('M d, Y')}. ₱" . number_format($remaining, 2) . " remains unpaid."
                        : "You have ₱" . number_format($remaining, 2) . " remaining to complete your ₱" . number_format($targetAmount, 2) . " share capital subscription by {$deadline->format('M d, Y')} ({$daysLeft} day" . ($daysLeft === 1 ? '' : 's') . " left).",
                    'time' => $deadline->diffForHumans(),
                    'sort_at' => $today,
                ]);
            }
        }

        $savingsAccount = savings_account_tbl::where('user_id', $userId)->first();

        if ($savingsAccount) {
            // ── 3) Patronage refund credited to Savings (last 30 days) ──
            $patronageTxs = savings_transaction_tbl::where('savings_account_id', $savingsAccount->id)
                ->where('type', 'deposit')
                ->where('reference_no', 'like', 'PAT-%')
                ->where('transaction_date', '>=', $today->copy()->subDays(30))
                ->get();

            foreach ($patronageTxs as $tx) {
                $notifications->push([
                    'icon' => 'fa-piggy-bank',
                    'color' => 'mint',
                    'title' => 'Patronage Refund Credited',
                    'message' => "₱" . number_format($tx->amount, 2) . " patronage refund has been credited to your Savings account (Ref: {$tx->reference_no}).",
                    'time' => Carbon::parse($tx->created_at ?? $tx->transaction_date)->diffForHumans(),
                    'sort_at' => Carbon::parse($tx->created_at ?? $tx->transaction_date),
                ]);
            }

            // ── 4) Time Deposit maturity ─────────────────────────────────
            $activeTd = TimeDeposit::where('savings_account_id', $savingsAccount->id)
                ->where('status', 'active')
                ->latest('opened_at')
                ->first();

            if ($activeTd && $activeTd->maturity_date) {
                $maturity = Carbon::parse($activeTd->maturity_date);
                $daysLeft = (int) $today->diffInDays($maturity, false);

                if ($maturity->lte($today)) {
                    $notifications->push([
                        'icon' => 'fa-circle-check',
                        'color' => 'mint',
                        'title' => 'Time Deposit Matured',
                        'message' => "Your Time Deposit matured on {$maturity->format('M d, Y')}. Claim it to move your balance + interest back to Regular Savings.",
                        'time' => $maturity->diffForHumans(),
                        'sort_at' => $maturity,
                    ]);
                } elseif ($daysLeft <= 30) {
                    $notifications->push([
                        'icon' => 'fa-calendar-days',
                        'color' => 'gold',
                        'title' => 'Time Deposit Maturing Soon',
                        'message' => "Your Time Deposit matures on {$maturity->format('M d, Y')} (in {$daysLeft} day" . ($daysLeft === 1 ? '' : 's') . ").",
                        'time' => $maturity->diffForHumans(),
                        'sort_at' => $maturity,
                    ]);
                }
            }
        }

        return $notifications->sortByDesc('sort_at')->values();
    }

    public function logout()
    {
        $user = Auth::user();
        AuditLog::log(
            'Logged Out',
            "{$user?->first_name} {$user?->last_name} ({$user?->role}) logged out"
        );
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        return redirect()->route("login");
    }

    public function UserHandle()
    {
        $user = Auth::user();

        // All admin/staff roles go to dashboard; only regular members go to MemberPortal
        if (!in_array(strtolower($user->role), ['member', 'pending', 'inactive'])) {
            return redirect()->route("dashboard")->with("message", "Login successfully!");
        } else {
            return redirect()->route("MemberPortal")
                ->with("message", "Login successfully!")
                ->with("just_logged_in", true);
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
            $user = auth()->user();

            // All admin/staff roles (not regular members) bypass membership approval checks
            if (!in_array($user->role, ['member', 'pending', 'inactive'])) {
                if ($user->status === 'inactive') {
                    auth()->logout();
                    $request->session()->invalidate();
                    $request->session()->regenerateToken();
                    return redirect()->back()
                        ->withErrors(['login' => 'Your account has been deactivated. Contact the main administrator.'])
                        ->withInput($request->only('login'));
                }
                AuditLog::log(
                    'Logged In',
                    "{$user->first_name} {$user->last_name} ({$user->role}) logged in"
                );
                $request->session()->regenerate();
                $request->session()->flash('just_logged_in', true);
                return redirect()->route('UserHandle');
            }

            $otherInfo = DB::table('otherinfo_tbls')
                ->where('user_id', $user->id)
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

                AuditLog::log(
                    'Logged In',
                    "{$user->first_name} {$user->last_name} ({$user->role}) logged in"
                );
                $request->session()->regenerate();
                $request->session()->flash('just_logged_in', true);
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
                "role" => "Pending",
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

            AuditLog::log(
                'User Registered',
                "New user registered: {$request->first_name} {$request->last_name} ({$request->email})",
                'user',
                $users->id
            );

            return redirect()->route("RegisterPage")->with("success", "Create account successfully!");


        } catch (\Exception $e) {
            dd($e->getMessage(), $e->getLine(), $e->getFile());
        }

    }


}