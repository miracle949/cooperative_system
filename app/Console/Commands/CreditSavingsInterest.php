<?php

namespace App\Console\Commands;

use App\Services\SavingsInterestService;
use Illuminate\Console\Command;
use Carbon\Carbon;

class CreditSavingsInterest extends Command
{
    protected $signature = 'savings:credit-interest 
                            {--force : Re-credit even if already credited for the quarter}
                            {--current : Use the in-progress quarter instead of the previous completed one (for testing)}';

    protected $description = 'Credit quarterly interest to all active Regular Savings accounts using average daily balance.';

    public function handle(SavingsInterestService $service): int
    {
        if ($this->option('current')) {
            $now = Carbon::now();
            $startMonth = (intdiv($now->month - 1, 3) * 3) + 1;
            $quarterStart = Carbon::create($now->year, $startMonth, 1)->startOfDay();
            $quarterEnd = $now->copy()->endOfDay(); // partial quarter, up to today

            $this->comment("Using in-progress quarter: {$quarterStart->toDateString()} to {$quarterEnd->toDateString()} (testing mode)");

            $results = $service->creditForAllAccounts($quarterStart, $quarterEnd, force: (bool) $this->option('force'));
        } else {
            $results = $service->creditForAllAccounts(force: (bool) $this->option('force'));
        }

        $this->info('Credited interest to ' . count($results) . ' account(s).');
        foreach ($results as $r) {
            $this->line("Account #{$r['account_id']}: ₱{$r['interest']} (avg balance ₱" . number_format($r['avg_balance'], 2) . ") — {$r['reference_no']}");
        }

        return self::SUCCESS;
    }
}