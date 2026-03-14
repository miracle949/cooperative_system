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
        Schema::create('employee_membership_informations', function (Blueprint $table) {
            $table->id();
            $table->foreignId("member_id")
                  ->constrained("users_tbls")
                  ->onDelete("cascade");
            $table->date("date_of_membership")->nullable();
            $table->date("date_of_cetos")->nullable();
            $table->string("membership_category")->nullable();
            $table->string("tc_member_id_no")->nullable();
            $table->string("no_units_owned")->nullable();
            $table->string("type_mode_unit")->nullable();
            $table->string("paid_up_capital")->nullable();
            $table->string("paid_up_price")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_membership_informations');
    }
};
