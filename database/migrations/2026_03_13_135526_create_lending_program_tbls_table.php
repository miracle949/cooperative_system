<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('lending_program_tbls', function (Blueprint $table) {
            $table->id();
            $table->foreignId("member_id")
                ->constrained("users_tbls")
                ->onDelete("cascade");
            $table->string("reference_no")->nullable()->unique();
            $table->string("lending_type")->nullable();
            $table->decimal("lending_amount", 10, 2)->nullable();
            $table->string("lending_type_term")->nullable();
            $table->decimal("monthly_income", 10, 2)->nullable();
            $table->decimal("monthly_payment", 10, 2)->nullable();
            $table->decimal("total_payment", 10, 2)->nullable();
            $table->decimal("total_interest", 10, 2)->nullable();
            $table->longText("valid_id")->nullable();
            $table->longText("proof_of_income")->nullable();
            $table->text("purpose_loan")->nullable();
            $table->enum("status", ["Pending", "Approved", "Declined"])->default("Pending");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lending_program_tbls');
    }
};
