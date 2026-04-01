<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\savings_account_tbl;
use App\Models\savings_transaction_tbl;
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
                'monthsActive'
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

    // GCash success callback:
    public function gcashSuccess(Request $request)
    {
        $user = Auth::user();
        $amount = (float) session('sav_pending_amount', 0);
        $note = session('sav_pending_note');
        $type = session('sav_pending_type', 'deposit');  // 'deposit' or 'withdraw'
        $referenceNo = 'GCASH-' . now()->format('YmdHis');

        session()->forget(['sav_pending_amount', 'sav_pending_note', 'sav_pending_type']);

        $savingsAccount = savings_account_tbl::where('user_id', $user->id)->firstOrFail();

        if ($type === 'deposit') {
            $newBalance = $savingsAccount->balance + $amount;
            $txType = 'deposit';
        } else {
            if ($amount > $savingsAccount->balance) {
                return redirect()->route('savings.index')
                    ->with('error', 'Insufficient balance for GCash withdrawal.');
            }
            $newBalance = $savingsAccount->balance - $amount;
            $txType = 'withdrawal';
        }

        $savingsAccount->update(['balance' => $newBalance]);

        savings_transaction_tbl::create([
            'savings_account_id' => $savingsAccount->id,
            'type' => $txType,
            'amount' => $amount,
            'payment_method' => 'gcash',
            'balance_after' => $newBalance,
            'note' => $note,
            'reference_no' => $referenceNo,
            'transaction_date' => Carbon::today(),
        ]);

        $successKey = $type === 'deposit' ? 'deposit_success' : 'withdraw_success';
        $amountKey = $type === 'deposit' ? 'deposit_amount' : 'withdraw_amount';
        $referenceKey = $type === 'deposit' ? 'deposit_reference' : 'withdraw_reference';
        $balanceKey = $type === 'deposit' ? 'deposit_balance' : 'withdraw_balance';

        return redirect()->route('savings.index')
            ->with($successKey, true)
            ->with($amountKey, $amount)
            ->with($referenceKey, $referenceNo)
            ->with($balanceKey, $newBalance);
    }

    // GCash failed callback:
    public function gcashFailed(Request $request)
    {
        session()->forget(['sav_pending_amount', 'sav_pending_note', 'sav_pending_type']);
        return redirect()->route('savings.index')
            ->with('error', 'GCash payment failed. Please try again.');
    }

    /**
     * Download receipt as plain text file.
     */
    /**
     * Download receipt as JPG image.
     */
    public function downloadReceipt(string $referenceNo)
    {
        $user = Auth::user();
        $savingsAccount = savings_account_tbl::where('user_id', $user->id)->firstOrFail();

        $tx = savings_transaction_tbl::where('savings_account_id', $savingsAccount->id)
            ->where('reference_no', $referenceNo)
            ->firstOrFail();

        $type = ucfirst($tx->type);
        $date = \Carbon\Carbon::parse($tx->transaction_date)->format('F d, Y');
        $time = \Carbon\Carbon::parse($tx->created_at)->timezone('Asia/Manila')->format('h:i A');
        $amount = 'PHP ' . number_format($tx->amount, 2);
        $balance = 'PHP ' . number_format($tx->balance_after, 2);
        $note = $tx->note ?? 'N/A';
        $member = $user->first_name . ' ' . $user->last_name;
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

        return response($imageData, 200, [
            'Content-Type' => 'image/jpeg',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ]);
    }
}