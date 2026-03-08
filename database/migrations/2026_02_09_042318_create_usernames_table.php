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
        Schema::create('usernames', function (Blueprint $table) {
            // Users_tbl
            $table->id();
            $table->string("membership_category");
            $table->string("first_name");
            $table->string("middle_name");
            $table->string("last_name");
            $table->string("email")->unique();
            $table->string("password");
            $table->string("date_of_birth")->nullable();
            $table->string("place_of_birth")->nullable();
            $table->integer("number_son")->nullable();
            $table->integer("number_daughter")->nullable();
            $table->string("other_spec")->nullable();
            $table->string("contact_no")->nullable();
            $table->string("civil_status")->nullable();
            $table->string("present_address")->nullable();
            $table->string("permanent_address")->nullable();
            $table->string("sex")->nullable();
            $table->string("citizenship")->nullable();
            $table->string("driver_license_no")->nullable();
            $table->string("tsc_name")->nullable();
            $table->string("skills")->nullable();
            $table->string("height")->nullable();
            $table->string("weight")->nullable();
            $table->string("blood_type")->nullable();
            $table->string("role")->default("Member");
            $table->timestamps();


            // Otherinfo_tbl
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

            // Membervehi_tbl
            $table->id();
            $table->foreignId("member_id")
                  ->constrained("users_tbls")
                  ->onDelete("cascade");
            $table->string("vehicle_type");
            $table->integer("quantity");
            $table->timestamps();

            // Spouse_tbl
            $table->id();
            $table->foreignId("member_id")
                  ->constrained("users_tbls")
                  ->onDelete("cascade");
            $table->string("spouse_name")->nullable();
            $table->date("spouse_date_birth")->nullable();
            $table->string("spouse_place_birth")->nullable();
            $table->timestamps();

            // Membergovern_ids_tbl
            $table->id();
            $table->foreignId("member_id")
                  ->constrained("users_tbls")
                  ->onDelete("cascade");
            $table->string("sss_no")->nullable();
            $table->string("philhealth_no")->nullable();
            $table->string("pagibig_no")->nullable();
            $table->string("tin_no")->nullable();
            $table->timestamps();
            // 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('usernames');
    }
};
