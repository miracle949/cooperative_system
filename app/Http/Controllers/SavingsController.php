<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\savings_account_tbl;
use App\Models\savings_transaction_tbl;
use App\Models\share_capital_account_tbl;
use App\Models\share_capital_transaction_tbl;
use App\Models\Users_tbl;
use App\Models\AuditLog;
use Carbon\Carbon;

class SavingsController extends Controller
{
    /**
     * Generate a unique reference number.
     * Format: SAV-DEP-20260326-A3F9 or SAV-WDR-20260326-B7K2
     */
    private function generateReferenceNo(string $type): string
    {
        $prefix = $type === 'deposit' ? 'SAV-DEP' : 'SAV-WDR';
        $date = Carbon::today()->format('Ymd');

        do {
            $random = strtoupper(substr(bin2hex(random_bytes(3)), 0, 6));
            $refNo = "{$prefix}-{$date}-{$random}";
        } while (savings_transaction_tbl::where('reference_no', $refNo)->exists());

        return $refNo;
    }

    /**
     * Show the savings page.
     */
    public function index()
    {
        $user = Auth::user();
        $username = Auth::check() ? Auth::user()->username : null;
        $email = Auth::check() ? Auth::user()->email : null;

        // Get or create savings account
        $savingsAccount = savings_account_tbl::where('user_id', $user->id)->first();

        $hasShareCapital = \Illuminate\Support\Facades\DB::table('share_capital_account_tbls')
            ->where('user_id', $user->id)
            ->where('status', 'Active')
            ->where('total_shares', '>', 0)
            ->exists();

        if (!$savingsAccount) {
            $savingsAccount = savings_account_tbl::create([
                'user_id' => $user->id,
                'balance' => 0.00,
                'status' => 'active',
                'opened_at' => Carbon::today(),
            ]);
        }

        // Grouped transactions by Month Year
        $groupedTransactions = savings_transaction_tbl::where('savings_account_id', $savingsAccount->id)
            ->orderBy('transaction_date', 'desc')
            ->get()
            ->groupBy(function ($tx) {
                return Carbon::parse($tx->transaction_date)->format('F Y');
            });

        $totalMonths = savings_transaction_tbl::where('savings_account_id', $savingsAccount->id)
            ->groupByRaw("DATE_FORMAT(transaction_date, '%Y-%m')")
            ->count();

        $monthlyAverage = $totalMonths > 0
            ? $savingsAccount->balance / $totalMonths
            : 0;

        $lastUpdated = savings_transaction_tbl::where('savings_account_id', $savingsAccount->id)
            ->orderBy('transaction_date', 'desc')
            ->value('transaction_date');

        $lastUpdated = $lastUpdated
            ? Carbon::parse($lastUpdated)->diffForHumans()
            : 'No transactions yet';

        $monthsActive = (int) ceil(
            Carbon::parse($savingsAccount->opened_at)->floatDiffInMonths(Carbon::today())
        );

        return view(
            'members_components.savings',
            [
                "username" => $username,
                "email" => $email
            ],
            compact(
                'savingsAccount',
                'groupedTransactions',
                'totalMonths',
                'monthlyAverage',
                'lastUpdated',
                'monthsActive',
                'hasShareCapital'
            )
        );
    }

    /**
     * Handle deposit.
     */
    public function deposit(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1',
            'note' => 'nullable|string|max:255',
            'payment_method' => 'required|string|in:cash,gcash',
        ]);

        $user = Auth::user();
        $savingsAccount = savings_account_tbl::where('user_id', $user->id)->firstOrFail();
        $newBalance = $savingsAccount->balance + $request->amount;
        $referenceNo = $this->generateReferenceNo('deposit');

        $hasShareCapital = \Illuminate\Support\Facades\DB::table('share_capital_account_tbls')
            ->where('user_id', $user->id)
            ->where('status', 'Active')
            ->where('total_shares', '>', 0)
            ->exists();

        if (!$hasShareCapital) {
            return redirect()->route('savings.index')
                ->with('error', 'You must have an active Share Capital account before you can deposit or withdraw savings.');
        }

        $savingsAccount->update(['balance' => $newBalance]);

        savings_transaction_tbl::create([
            'savings_account_id' => $savingsAccount->id,
            'type' => 'deposit',
            'amount' => $request->amount,
            'payment_method' => $request->payment_method,
            'balance_after' => $newBalance,
            'note' => $request->note,
            'reference_no' => $referenceNo,
            'transaction_date' => Carbon::today(),
        ]);

        AuditLog::log(
            'Member Savings Deposit',
            "Deposited ₱{$request->amount} to savings (Ref: {$referenceNo})",
            'savings',
            $savingsAccount->id
        );

        return redirect()->route('savings.index')
            ->with('deposit_success', true)
            ->with('deposit_amount', $request->amount)
            ->with('deposit_reference', $referenceNo)
            ->with('deposit_balance', $newBalance);
    }

    /**
     * Handle withdrawal.
     */
    public function withdraw(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1',
            'note' => 'nullable|string|max:255',
            'payment_method' => 'required|string|in:cash,gcash',
        ]);

        $user = Auth::user();
        $savingsAccount = savings_account_tbl::where('user_id', $user->id)->firstOrFail();

        $hasShareCapital = \Illuminate\Support\Facades\DB::table('share_capital_account_tbls')
            ->where('user_id', $user->id)
            ->where('status', 'Active')
            ->where('total_shares', '>', 0)
            ->exists();

        if (!$hasShareCapital) {
            return redirect()->route('savings.index')
                ->with('error', 'You must have an active Share Capital account before you can deposit or withdraw savings.');
        }

        if ($request->amount > $savingsAccount->balance) {
            return back()
                ->withErrors(['amount' => 'Insufficient balance. Available: ₱ ' . number_format($savingsAccount->balance, 2)])
                ->withInput();
        }

        $newBalance = $savingsAccount->balance - $request->amount;
        $referenceNo = $this->generateReferenceNo('withdrawal');

        $savingsAccount->update(['balance' => $newBalance]);

        savings_transaction_tbl::create([
            'savings_account_id' => $savingsAccount->id,
            'type' => 'withdrawal',
            'amount' => $request->amount,
            'payment_method' => $request->payment_method,   // ← added
            'balance_after' => $newBalance,
            'note' => $request->note,
            'reference_no' => $referenceNo,
            'transaction_date' => Carbon::today(),
        ]);

        AuditLog::log(
            'Member Savings Withdrawal',
            "Withdrew ₱{$request->amount} from savings (Ref: {$referenceNo})",
            'savings',
            $savingsAccount->id
        );

        return redirect()->route('savings.index')
            ->with('withdraw_success', true)
            ->with('withdraw_amount', $request->amount)
            ->with('withdraw_reference', $referenceNo)
            ->with('withdraw_balance', $newBalance);
    }

    public function payViaGcash(Request $request)
    {
        if (!env('PAYMONGO_SECRET_KEY')) {
            return redirect()->back()->with('error', 'Payment gateway is not configured yet.');
        }

        $request->validate([
            'amount' => 'required|numeric|min:1',
            'transaction_type' => 'required|in:deposit,withdraw',
            'note' => 'nullable|string|max:255',
        ]);

        $amount = (float) $request->amount;

        session([
            'sav_pending_amount' => $amount,
            'sav_pending_note' => $request->note,
            'sav_pending_type' => $request->transaction_type,
        ]);

        $response = \Illuminate\Support\Facades\Http::withBasicAuth(env('PAYMONGO_SECRET_KEY'), '')
            ->withOptions(['verify' => false])
            ->post('https://api.paymongo.com/v1/sources', [
                'data' => [
                    'attributes' => [
                        'amount' => (int) ($amount * 100),
                        'currency' => 'PHP',
                        'type' => 'gcash',
                        'redirect' => [
                            'success' => route('savings.gcash.success'),
                            'failed' => route('savings.gcash.failed'),
                        ],
                    ],
                ],
            ]);

        $data = $response->json();

        if (isset($data['data']['attributes']['redirect']['checkout_url'])) {
            return redirect($data['data']['attributes']['redirect']['checkout_url']);
        }

            return redirect()->back()->with('error', 'GCash payment failed. Please try again.');
    }

    /**
    /**
     * Download receipt as JPG image.
     */
    public function downloadReceipt(string $referenceNo, Request $request)
    {
        $user = Auth::user();
        
        // Check if this is admin requesting - find transaction by reference_no
        $tx = savings_transaction_tbl::where('reference_no', $referenceNo)->first();
        
        if (!$tx) {
            // Fall back to member lookup
            $savingsAccount = savings_account_tbl::where('user_id', $user->id)->firstOrFail();
            $tx = savings_transaction_tbl::where('savings_account_id', $savingsAccount->id)
                ->where('reference_no', $referenceNo)
                ->firstOrFail();
        }

        // Get user for the transaction
        $savingsAccount = savings_account_tbl::find($tx->savings_account_id);
        $transactionUser = $savingsAccount ? Users_tbl::find($savingsAccount->user_id) : null;
        
        if (!$transactionUser) {
            $transactionUser = $user;
        }

        $type = ucfirst($tx->type);
        $date = \Carbon\Carbon::parse($tx->transaction_date)->format('F d, Y');
        $time = \Carbon\Carbon::parse($tx->created_at)->timezone('Asia/Manila')->format('h:i A');
        $amount = 'PHP ' . number_format($tx->amount, 2);
        $balance = 'PHP ' . number_format($tx->balance_after, 2);
        $note = $tx->note ?? 'N/A';
        $member = $transactionUser->first_name . ' ' . $transactionUser->last_name;
        $isDeposit = $tx->type === 'deposit';

        // Font paths
        $fontRegular = public_path('Poppins/Poppins-Regular.ttf');
        $fontSemiBold = public_path('Poppins/Poppins-SemiBold.ttf');

        // Canvas size — increased height to fit extra row
        $w = 600;
        $h = 550;
        $img = imagecreatetruecolor($w, $h);

        // Enable antialiasing
        imageantialias($img, true);

        // Colors
        $white = imagecolorallocate($img, 255, 255, 255);
        $green = imagecolorallocate($img, 30, 64, 53);
        $lightGreen = imagecolorallocate($img, 240, 247, 244);
        $red = imagecolorallocate($img, 220, 38, 38);
        $lightRed = imagecolorallocate($img, 254, 242, 242);
        $muted = imagecolorallocate($img, 107, 123, 116);
        $border = imagecolorallocate($img, 226, 232, 229);
        $dark = imagecolorallocate($img, 26, 26, 26);
        $accentClr = $isDeposit ? $green : $red;
        $accentBg = $isDeposit ? $lightGreen : $lightRed;

        // Background
        imagefilledrectangle($img, 0, 0, $w, $h, $white);

        // Top accent bar
        imagefilledrectangle($img, 0, 0, $w, 6, $accentClr);

        // Header background strip
        imagefilledrectangle($img, 0, 6, $w, 140, $accentBg);

        // Circle icon background
        imagefilledellipse($img, $w / 2, 80, 80, 80, $accentClr);
        imagefilledellipse($img, $w / 2, 80, 70, 70, $white);

        // Checkmark inside circle
        imageline($img, $w / 2 - 14, 80, $w / 2 - 4, 92, $accentClr);
        imageline($img, $w / 2 - 13, 80, $w / 2 - 3, 92, $accentClr);
        imageline($img, $w / 2 - 4, 92, $w / 2 + 16, 66, $accentClr);
        imageline($img, $w / 2 - 4, 91, $w / 2 + 16, 65, $accentClr);

        // Helper: centered text
        $centerText = function (int $fontSize, string $fontPath, string $text, int $y, $color) use ($img, $w) {
            $bbox = imagettfbbox($fontSize, 0, $fontPath, $text);
            $textWidth = $bbox[2] - $bbox[0];
            $x = ($w - $textWidth) / 2;
            imagettftext($img, $fontSize, 0, (int) $x, $y, $color, $fontPath, $text);
        };

        // Title
        $title = $isDeposit ? 'Deposit Successful!' : 'Withdrawal Successful!';
        $centerText(16, $fontSemiBold, $title, 165, $dark);

        // Subtitle
        $sub = 'KMPCATS Cooperative -- Official Receipt';
        $centerText(9, $fontRegular, $sub, 183, $muted);

        // Divider
        imageline($img, 40, 198, $w - 40, 198, $border);

        // Info rows — Time row added after Date
        $rows = [
            ['Reference No.', $referenceNo],
            ['Member', $member],
            ['Date', $date],
            ['Time', $time],       // <-- new row
            ['Type', $type],
            ['Amount', $amount],
            ['Balance After', $balance],
            ['Note', $note],
        ];

        $y = 220;
        foreach ($rows as $row) {
            // Label (left)
            imagettftext($img, 9, 0, 50, $y, $muted, $fontRegular, $row[0]);

            // Value (right-aligned)
            $val = strlen($row[1]) > 38 ? substr($row[1], 0, 38) . '...' : $row[1];
            $bbox = imagettfbbox(9, 0, $fontSemiBold, $val);
            $valW = $bbox[2] - $bbox[0];
            $valX = $w - 50 - $valW;
            $valColor = $row[0] === 'Amount' ? $accentClr : $dark;
            imagettftext($img, 9, 0, (int) $valX, $y, $valColor, $fontSemiBold, $val);

            // Draw line BELOW the text
            imageline($img, 50, $y + 10, $w - 50, $y + 10, $border);

            $y += 30;
        }

        // Bottom note
        $note1 = 'This receipt is system-generated and serves as';
        $note2 = 'official proof of your transaction.';
        $centerText(8, $fontRegular, $note1, $h - 38, $muted);
        $centerText(8, $fontRegular, $note2, $h - 24, $muted);

        // Bottom bar
        imagefilledrectangle($img, 0, $h - 20, $w, $h, $accentClr);
        $foot = 'KMPCATS Cooperative Management System';
        $centerText(8, $fontRegular, $foot, $h - 6, $white);

        // Output as JPG
        $filename = "Receipt-{$referenceNo}.jpg";
        ob_start();
        imagejpeg($img, null, 95);
        $imageData = ob_get_clean();
        imagedestroy($img);

        // Check if request is for inline view (for admin modal display)
        if ($request->query('view') === 'inline') {
            $base64 = 'data:image/jpeg;base64,' . base64_encode($imageData);
            return response()->json(['image' => $base64]);
        }

        return response($imageData, 200, [
            'Content-Type' => 'image/jpeg',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ]);
    }

    /**
     * Handle admin savings transaction (deposit/withdrawal for any member).
     */
    public function adminStoreSavings(Request $request)
    {
        $request->validate([
            'member_id' => 'required|exists:users_tbls,id',
            'amount' => 'required|numeric|min:1',
            'type' => 'required|string|in:deposit,withdrawal',
            'payment_method' => 'required|string|in:cash,bank_transfer,gcash,check',
            'note' => 'nullable|string|max:255',
        ]);

        // Get member's savings account
        $savingsAccount = savings_account_tbl::where('user_id', $request->member_id)->first();

        // If no savings account exists, create one
        if (!$savingsAccount) {
            $savingsAccount = savings_account_tbl::create([
                'user_id' => $request->member_id,
                'balance' => 0,
                'status' => 'active',
                'opened_at' => Carbon::today(),
            ]);
        }

        $amount = $request->amount;
        $type = $request->type;
        
        // Handle withdrawal - check balance
        if ($type === 'withdrawal') {
            if ($savingsAccount->balance < $amount) {
                return response()->json([
                    'success' => false,
                    'message' => 'Insufficient balance. Available: ₱' . number_format($savingsAccount->balance, 2)
                ], 422);
            }
            $newBalance = $savingsAccount->balance - $amount;
        } else {
            $newBalance = $savingsAccount->balance + $amount;
        }

        // Update balance
        $savingsAccount->update(['balance' => $newBalance]);

        // Generate reference number
        $referenceNo = $this->generateReferenceNo($type);

        // Create transaction record
        $transaction = savings_transaction_tbl::create([
            'savings_account_id' => $savingsAccount->id,
            'type' => $type,
            'amount' => $amount,
            'payment_method' => $request->payment_method,
            'balance_after' => $newBalance,
            'note' => $request->note,
            'reference_no' => $referenceNo,
            'transaction_date' => Carbon::today(),
        ]);

        $member = Users_tbl::find($request->member_id);
        AuditLog::log(
            'Admin ' . ucfirst($type) . ' Savings',
            ucfirst($type) . " of ₱{$amount} to/from {$member?->first_name} {$member?->last_name} (Ref: {$referenceNo})",
            'savings',
            $savingsAccount->id
        );

        return response()->json([
            'success' => true,
            'message' => ucfirst($type) . ' of ₱' . number_format($amount, 2) . ' successful!',
            'reference_no' => $referenceNo,
            'new_balance' => $newBalance,
        ]);
    }

    /**
     * Get member's current savings balance.
     */
    public function getMemberBalance($memberId)
    {
        $savingsAccount = savings_account_tbl::where('user_id', $memberId)->first();
        $balance = $savingsAccount ? $savingsAccount->balance : 0;
        return response()->json(['balance' => $balance]);
    }

    /**
     * Get member share capital balance for AJAX.
     */
    public function getMemberShareCapitalBalance($memberId)
    {
        $account = share_capital_account_tbl::where('user_id', $memberId)->first();
        $balance = $account ? $account->total_amount : 0;
        return response()->json(['balance' => $balance]);
    }

    /**
     * Convert/transfer a portion of a member's Savings into Share Capital.
     */
    public function convertToShareCapital(Request $request)
    {
        $request->validate([
            'member_id' => 'required|exists:users_tbls,id',
            'amount' => 'required|numeric|min:1',
        ]);

        $memberId = $request->member_id;
        $amount = (float) $request->amount;
        $amountPerShare = 1000;

        $savingsAccount = savings_account_tbl::where('user_id', $memberId)->first();

        if (!$savingsAccount) {
            return response()->json([
                'success' => false,
                'message' => 'Member does not have a savings account.',
            ], 422);
        }

        if ($amount > $savingsAccount->balance) {
            return response()->json([
                'success' => false,
                'message' => 'Insufficient savings balance. Available: ₱' . number_format($savingsAccount->balance, 2),
            ], 422);
        }

        $shares = (int) ($amount / $amountPerShare);
        $convertedAmount = $shares * $amountPerShare;

        if ($shares < 1) {
            return response()->json([
                'success' => false,
                'message' => 'Minimum conversion amount is ₱' . number_format($amountPerShare, 2) . ' (1 share).',
            ], 422);
        }

        if ($convertedAmount < $amount) {
            $remainder = $amount - $convertedAmount;
        } else {
            $remainder = 0;
        }

        $now = Carbon::now();
        $referenceNo = 'SCV-' . strtoupper(bin2hex(random_bytes(4))) . '-' . $now->format('Ymd');

        DB::beginTransaction();

        try {
            $savingsNewBalance = $savingsAccount->balance - $convertedAmount;
            $savingsAccount->update(['balance' => $savingsNewBalance]);

            savings_transaction_tbl::create([
                'savings_account_id' => $savingsAccount->id,
                'type' => 'withdrawal',
                'amount' => $convertedAmount,
                'payment_method' => 'Internal Transfer',
                'balance_after' => $savingsNewBalance,
                'note' => 'Transferred to Share Capital',
                'reference_no' => $referenceNo,
                'transaction_date' => $now->toDateString(),
            ]);

            $scAccount = share_capital_account_tbl::where('user_id', $memberId)->first();

            if ($scAccount) {
                $scAccount->update([
                    'total_shares' => $scAccount->total_shares + $shares,
                    'total_amount' => $scAccount->total_amount + $convertedAmount,
                    'status' => 'Active',
                ]);
                $scAccountId = $scAccount->id;
            } else {
                $scAccount = share_capital_account_tbl::create([
                    'user_id' => $memberId,
                    'total_shares' => $shares,
                    'total_amount' => $convertedAmount,
                    'status' => 'Active',
                ]);
                $scAccountId = $scAccount->id;
            }

            share_capital_transaction_tbl::create([
                'share_capital_account_id' => $scAccountId,
                'type' => 'Deposit',
                'shares' => $shares,
                'amount_per_share' => $amountPerShare,
                'total_amount' => $convertedAmount,
                'payment_method' => 'Internal Transfer',
                'reference_no' => $referenceNo,
                'note' => 'Converted from Savings',
                'status' => 'Completed',
                'transaction_date' => $now->toDateString(),
            ]);

            DB::commit();

            $member = Users_tbl::find($memberId);
            AuditLog::log(
                'Convert Savings to Share Capital',
                "Converted ₱{$convertedAmount} ({$shares} shares) from savings to share capital for {$member?->first_name} {$member?->last_name} (Ref: {$referenceNo})",
                'savings',
                $savingsAccount->id
            );

            return response()->json([
                'success' => true,
                'message' => 'Successfully converted ₱' . number_format($convertedAmount, 2) . ' (' . $shares . ' share(s)) to Share Capital.',
                'converted_amount' => $convertedAmount,
                'shares' => $shares,
                'reference_no' => $referenceNo,
                'remainder' => $remainder,
            ]);

        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Conversion failed: ' . $e->getMessage(),
            ], 500);
        }
    }
}