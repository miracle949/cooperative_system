<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('savings_account_tbls', function (Blueprint $table) {
            $table->dropColumn([
                'td_balance',
                'interest_accrued_balance',
                'interest_last_credited_at',
                'td_interest_rate',
                'td_term_months',
                'td_opened_at',
                'td_maturity_date',
                'td_status',
                'td_reference_no',
                'last_interest_credited_at',
            ]);
        });
    }

    public function down(): void
    {
        Schema::table('savings_account_tbls', function (Blueprint $table) {
            $table->decimal('td_balance', 12, 2)->default(0.00)->after('balance');
            $table->decimal('interest_accrued_balance', 12, 2)->default(0.00)->after('td_balance');
            $table->date('interest_last_credited_at')->nullable()->after('interest_accrued_balance');
            $table->decimal('td_interest_rate', 5, 2)->nullable()->after('interest_last_credited_at');
            $table->unsignedInteger('td_term_months')->nullable()->after('td_interest_rate');
            $table->date('td_opened_at')->nullable()->after('td_term_months');
            $table->date('td_maturity_date')->nullable()->after('td_opened_at');
            $table->enum('td_status', ['none', 'active', 'matured', 'withdrawn'])->default('none')->after('td_maturity_date');
            $table->string('td_reference_no')->nullable()->after('td_status');
            $table->date('last_interest_credited_at')->nullable()->after('td_reference_no');
        });
    }
};