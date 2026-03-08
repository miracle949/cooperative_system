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
        Schema::create('otherinfo_tbls', function (Blueprint $table) {
            $table->id();
            $table->foreignId("member_id")
                  ->constrained("users_tbls")
                  ->onDelete("cascade");
            $table->string("is_member_other_coop");
            $table->boolean("are_you_willing_liability");
            $table->boolean("are_you_willing_abide_policy");

            $table->date("submitted_at");
            $table->string("day_of");
            $table->longText("signature");

            $table->enum("status", ["Pending", "Approved", "Disapproved"])
              ->default("Pending");
            $table->string("meeting_type")->nullable();
            
            $table->string("meeting_location")->nullable();
            $table->date("meeting_date")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('otherinfo_tbls');
    }
};
