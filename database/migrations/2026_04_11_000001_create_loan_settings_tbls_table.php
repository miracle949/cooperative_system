<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('loan_settings_tbls', function (Blueprint $table) {
            $table->id();
            $table->string('loan_type')->unique();
            $table->decimal('interest_rate', 5, 2)->default(2.00);
            $table->decimal('max_amount', 12, 2)->nullable();
            $table->timestamps();
        });

        // Insert default interest rates for each loan type
        DB::table('loan_settings_tbls')->insert([
            ['loan_type' => 'Personal Loan', 'interest_rate' => 2.00, 'max_amount' => 25000.00, 'created_at' => now(), 'updated_at' => now()],
            ['loan_type' => 'Emergency Loan', 'interest_rate' => 2.00, 'max_amount' => 25000.00, 'created_at' => now(), 'updated_at' => now()],
            ['loan_type' => 'Business Loan', 'interest_rate' => 2.00, 'max_amount' => 25000.00, 'created_at' => now(), 'updated_at' => now()],
            ['loan_type' => 'Education Loan', 'interest_rate' => 2.00, 'max_amount' => 25000.00, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('loan_settings_tbls');
    }
};