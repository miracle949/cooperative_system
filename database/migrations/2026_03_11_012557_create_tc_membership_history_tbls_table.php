<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tc_membership_history_tbls', function (Blueprint $table) {
            $table->id();
            $table->foreignId("member_id")
                  ->constrained("users_tbls")
                  ->onDelete("cascade");
            $table->date("members_inclusive_dates_from")->nullable();
            $table->date("members_inclusive_dates_to")->nullable();
            $table->string("membership_category")->nullable();
            $table->date("tc_held_inclusive_dates_from")->nullable();
            $table->date("tc_held_inclusive_dates_to")->nullable();
            $table->string("position_held")->nullable();
            $table->string("monthly_salary_allowance")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tc_membership_history_tbls');
    }
};
