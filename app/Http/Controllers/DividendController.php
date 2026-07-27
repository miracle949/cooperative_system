<?php

namespace App\Http\Controllers;

use App\Models\Dividend;
use App\Models\DividendSetting;
use App\Models\Users_tbl;
use App\Models\share_capital_account_tbl;
use App\Models\savings_account_tbl;
use App\Models\savings_transaction_tbl;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class DividendController extends Controller
{
    private function getDividendData(Request $request)
    {
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

        $years = DB::table('dividend_distributions')
            ->orderByDesc('year')
            ->pluck('year');

        $currentYear = now()->year;

        $dividendSetting = DividendSetting::where('year', $year)->first();
        $dividendFundPercentage = $dividendSetting ? $dividendSetting->dividend_fund_percentage : 60.00;

        return compact(
            'distribution',
            'dividends',
            'year',
            'years',
            'currentYear',
            'approvedCount',
            'disbursedCount',
            'totalSumShareCapital',
            'totalSumRecommended',
            'totalSumApproved',
            'dividendFundPercentage'
        );
    }

    public function tablePartial(Request $request)
    {
        $data = $this->getDividendData($request);

        return view('admin_components.dividends_table_partial', $data);
    }

    public function index(Request $request)
    {
        $data = $this->getDividendData($request);

        if ($request->ajax()) {
            return view('admin_components.dividends_table_partial', $data);
        }

        return view('admin_components.dividends', $data);
    }

    public function calculate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'net_surplus' => 'required|numeric|min:1',
            'year' => 'required|integer|min:2000|max:2100',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $netSurplus = $request->net_surplus;
        $year = $request->year;

        $existingDistribution = DB::table('dividend_distributions')
            ->where('year', $year)
            ->first();

        if ($existingDistribution) {
            return redirect()->route('dividends.index', ['year' => $year])
                ->with('error', "Dividend calculations for year {$year} already exist. Delete them first to regenerate.");
        }

        $reserveFund = round($netSurplus * 0.10, 2);
        $educationFund = round($netSurplus * 0.10, 2);
        $communityFund = round($netSurplus * 0.03, 2);
        $optionalFund = round($netSurplus * 0.07, 2);
        $totalStatutory = $reserveFund + $educationFund + $communityFund + $optionalFund;
        $remainingSurplus = round($netSurplus - $totalStatutory, 2);

        $dividendSetting = DividendSetting::getForYear($year);
        $dividendFundPct = $dividendSetting->dividend_fund_percentage / 100;
        $patronageRefundPct = 1 - $dividendFundPct;

        $dividendPool = round($remainingSurplus * $dividendFundPct, 2);
        $patronageRefundPool = round($remainingSurplus * $patronageRefundPct, 2);

        $members = Users_tbl::where('role', 'member')->get();
        $totalShareCapital = 0;
        $memberCapitals = [];

        foreach ($members as $member) {
            $account = share_capital_account_tbl::where('user_id', $member->id)->first();
            $capitalAmount = $account ? $account->total_amount : 0;
            $memberCapitals[$member->id] = $capitalAmount;
            $totalShareCapital += $capitalAmount;
        }

        DB::beginTransaction();

        try {
            DB::table('dividend_distributions')->insert([
                'year' => $year,
                'net_surplus' => $netSurplus,
                'reserve_fund' => $reserveFund,
                'education_fund' => $educationFund,
                'community_fund' => $communityFund,
                'optional_fund' => $optionalFund,
                'dividend_pool' => $dividendPool,
                'patronage_refund_pool' => $patronageRefundPool,
                'status' => 'draft',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            foreach ($members as $member) {
                $capitalAmount = $memberCapitals[$member->id];

                $recommended = 0;
                if ($totalShareCapital > 0) {
                    $recommended = round(
                        ($capitalAmount / $totalShareCapital) * $dividendPool,
                        2
                    );
                }

                Dividend::create([
                    'user_id' => $member->id,
                    'year' => $year,
                    'share_capital_amount' => $capitalAmount,
                    'recommended_amount' => $recommended,
                    'approved_amount' => $recommended,
                    'status' => 'pending',
                ]);
            }

            DB::commit();

            AuditLog::log(
                'Calculated Dividends',
                "Generated dividend calculations for year {$year} with net surplus of ₱" . number_format($netSurplus, 2),
                'dividend',
                null
            );

            return redirect()->route('dividends.index', ['year' => $year])
                ->with('success', "Dividend calculations for {$year} generated successfully using the RA 9520 formula.");
        } catch (\Throwable $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Failed to generate calculations: ' . $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'approved_amount' => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => 'Invalid amount'], 422);
        }

        $dividend = Dividend::findOrFail($id);
        $oldAmount = $dividend->approved_amount;
        $dividend->approved_amount = $request->approved_amount;
        $dividend->save();

        AuditLog::log(
            'Updated Dividend',
            "Updated dividend for {$dividend->user->first_name} {$dividend->user->last_name} (Year: {$dividend->year}) from ₱" . number_format($oldAmount, 2) . " to ₱" . number_format($dividend->approved_amount, 2),
            'dividend',
            $id
        );

        return response()->json([
            'success' => true,
            'message' => 'Dividend amount updated successfully.',
            'approved_amount' => number_format($dividend->approved_amount, 2),
        ]);
    }

    public function approve($id)
    {
        $dividend = Dividend::findOrFail($id);

        if ($dividend->status !== 'pending') {
            return response()->json([
                'success' => false,
                'message' => 'Only pending dividends can be approved.',
            ], 400);
        }

        $dividend->status = 'approved';
        $dividend->save();

        AuditLog::log(
            'Approved Dividend',
            "Approved dividend for {$dividend->user->first_name} {$dividend->user->last_name} (Year: {$dividend->year}) - ₱" . number_format($dividend->approved_amount, 2),
            'dividend',
            $id
        );

        return response()->json([
            'success' => true,
            'message' => 'Dividend approved successfully.',
        ]);
    }

    public function disburseOne(Request $request, $id)
    {
        $dividend = Dividend::with('user')->findOrFail($id);
        $disbursementType = $request->get('disbursement_type', 'savings');

        if ($dividend->status === 'disbursed') {
            return response()->json([
                'success' => false,
                'message' => 'This dividend has already been disbursed.',
            ], 400);
        }

        DB::beginTransaction();

        try {
            $dividend->status = 'disbursed';
            $dividend->save();

            if ($disbursementType === 'savings') {
                $savingsAccount = savings_account_tbl::firstOrCreate(
                    ['user_id' => $dividend->user_id],
                    [
                        'balance' => 0,
                        'status' => 'Active',
                        'opened_at' => now(),
                    ]
                );

                $savingsAccount->balance += $dividend->approved_amount;
                $savingsAccount->save();

                savings_transaction_tbl::create([
                    'savings_account_id' => $savingsAccount->id,
                    'type' => 'deposit',
                    'amount' => $dividend->approved_amount,
                    'payment_method' => 'Dividend',
                    'balance_after' => $savingsAccount->balance,
                    'note' => "Dividend payout for year {$dividend->year}",
                    'reference_no' => 'DIV-' . $dividend->year . '-' . str_pad($dividend->id, 5, '0', STR_PAD_LEFT),
                    'transaction_date' => now()->toDateString(),
                    'status' => 'Completed',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            } elseif ($disbursementType === 'share_capital') {
                $scAccount = share_capital_account_tbl::firstOrCreate(
                    ['user_id' => $dividend->user_id],
                    [
                        'total_shares' => 0,
                        'total_amount' => 0,
                        'status' => 'Active',
                    ]
                );

                $scAccount->total_amount += $dividend->approved_amount;
                $scAccount->save();
            }

            DB::commit();

            AuditLog::log(
                'Disbursed Dividend',
                "Disbursed dividend for {$dividend->user->first_name} {$dividend->user->last_name} (Year: {$dividend->year}) - ₱" . number_format($dividend->approved_amount, 2) . " to {$disbursementType}",
                'dividend',
                $id
            );

            $partialView = view('admin_components.dividends_table_partial', $this->getDividendData($request))->render();

            return response()->json([
                'success' => true,
                'message' => "Dividend disbursed successfully to {$dividend->user->first_name} {$dividend->user->last_name}.",
                'html' => $partialView,
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Disbursement failed: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function disburseAll(Request $request, $year = null)
    {
        $year = $year ?? $request->get('year', now()->year);
        $disbursementType = $request->get('disbursement_type', 'savings');
        $isAjax = $request->ajax();

        $approvedDividends = Dividend::where('year', $year)
            ->where('status', 'approved')
            ->get();

        if ($approvedDividends->isEmpty()) {
            if ($isAjax) {
                return response()->json(['success' => false, 'message' => 'No approved dividends to disburse.'], 400);
            }
            return redirect()->back()->with('error', 'No approved dividends to disburse.');
        }

        DB::beginTransaction();

        try {
            foreach ($approvedDividends as $dividend) {
                $dividend->status = 'disbursed';
                $dividend->save();

                if ($disbursementType === 'savings') {
                    $savingsAccount = savings_account_tbl::firstOrCreate(
                        ['user_id' => $dividend->user_id],
                        [
                            'balance' => 0,
                            'status' => 'Active',
                            'opened_at' => now(),
                        ]
                    );

                    $savingsAccount->balance += $dividend->approved_amount;
                    $savingsAccount->save();

                    savings_transaction_tbl::create([
                        'savings_account_id' => $savingsAccount->id,
                        'type' => 'deposit',
                        'amount' => $dividend->approved_amount,
                        'payment_method' => 'Dividend',
                        'balance_after' => $savingsAccount->balance,
                        'note' => "Dividend payout for year {$year}",
                        'reference_no' => 'DIV-' . $year . '-' . str_pad($dividend->id, 5, '0', STR_PAD_LEFT),
                        'transaction_date' => now()->toDateString(),
                        'status' => 'Completed',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                } elseif ($disbursementType === 'share_capital') {
                    $scAccount = share_capital_account_tbl::firstOrCreate(
                        ['user_id' => $dividend->user_id],
                        [
                            'total_shares' => 0,
                            'total_amount' => 0,
                            'status' => 'Active',
                        ]
                    );

                    $scAccount->total_amount += $dividend->approved_amount;
                    $scAccount->save();
                }
            }

            DB::table('dividend_distributions')
                ->where('year', $year)
                ->update(['status' => 'released', 'updated_at' => now()]);

            DB::commit();

            $totalDisbursed = $approvedDividends->sum('approved_amount');
            AuditLog::log(
                'Disbursed Dividends',
                "Disbursed all approved dividends for year {$year} to {$disbursementType} accounts. Total: ₱" . number_format($totalDisbursed, 2) . " ({$approvedDividends->count()} members)",
                'dividend',
                null
            );

            if ($isAjax) {
                $partialView = view('admin_components.dividends_table_partial', $this->getDividendData($request))->render();
                $remainingApproved = Dividend::where('year', $year)->where('status', 'approved')->count();
                $totalDisbursedCount = Dividend::where('year', $year)->where('status', 'disbursed')->count();
                return response()->json([
                    'success' => true,
                    'message' => 'Successfully disbursed ' . $approvedDividends->count() . ' dividend(s) for year ' . $year . '.',
                    'disbursedCount' => $approvedDividends->count(),
                    'html' => $partialView,
                    'approvedCount' => $remainingApproved,
                    'distributionStatus' => 'released',
                ]);
            }

            return redirect()->route('dividends.index', ['year' => $year])
                ->with('success', 'All approved dividends have been disbursed successfully.');
        } catch (\Throwable $e) {
            DB::rollBack();
            if ($isAjax) {
                return response()->json(['success' => false, 'message' => 'Disbursement failed: ' . $e->getMessage()], 500);
            }
            return redirect()->back()
                ->with('error', 'Disbursement failed: ' . $e->getMessage());
        }
    }

    public function updateFundPercentage(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'year' => 'required|integer|min:2000|max:2100',
            'dividend_fund_percentage' => 'required|numeric|min:1|max:99',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => 'Invalid input.'], 422);
        }

        $year = $request->year;
        $percentage = round($request->dividend_fund_percentage, 2);

        $distribution = DB::table('dividend_distributions')
            ->where('year', $year)
            ->first();

        if (!$distribution) {
            return response()->json(['success' => false, 'message' => 'No distribution found for this year.'], 404);
        }

        DB::beginTransaction();

        try {
            DividendSetting::updateOrCreate(
                ['year' => $year],
                [
                    'dividend_fund_percentage' => $percentage,
                    'updated_by' => auth()->id(),
                ]
            );

            $netSurplus = $distribution->net_surplus;
            $totalStatutory = $distribution->reserve_fund + $distribution->education_fund + $distribution->community_fund + $distribution->optional_fund;
            $remainingSurplus = round($netSurplus - $totalStatutory, 2);

            $dividendFundPct = $percentage / 100;
            $newDividendPool = round($remainingSurplus * $dividendFundPct, 2);
            $newPatronageRefund = round($remainingSurplus - $newDividendPool, 2);

            DB::table('dividend_distributions')->where('year', $year)->update([
                'dividend_pool' => $newDividendPool,
                'patronage_refund_pool' => $newPatronageRefund,
                'updated_at' => now(),
            ]);

            $totalShareCapital = Dividend::where('year', $year)->sum('share_capital_amount');
            if ($totalShareCapital > 0) {
                Dividend::where('year', $year)->where('status', 'pending')->each(function ($dividend) use ($newDividendPool, $totalShareCapital) {
                    $recommended = round(($dividend->share_capital_amount / $totalShareCapital) * $newDividendPool, 2);
                    $dividend->update([
                        'recommended_amount' => $recommended,
                        'approved_amount' => $recommended,
                    ]);
                });
            }

            DB::commit();

            AuditLog::log(
                'Updated Dividend Fund Percentage',
                "Changed dividend fund percentage for year {$year} to {$percentage}%. New dividend pool: ₱" . number_format($newDividendPool, 2),
                'dividend',
                null
            );

            $totalSumApproved = Dividend::where('year', $year)->sum('approved_amount');

            return response()->json([
                'success' => true,
                'message' => 'Dividend fund percentage updated and allocations recalculated.',
                'dividend_fund_percentage' => $percentage,
                'patronage_refund_percentage' => round(100 - $percentage, 2),
                'dividend_pool' => number_format($newDividendPool, 2),
                'patronage_refund_pool' => number_format($newPatronageRefund, 2),
                'total_approved' => number_format($totalSumApproved, 2),
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Update failed: ' . $e->getMessage()], 500);
        }
    }
}
