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
        Schema::create('users_tbls', function (Blueprint $table) {
            $table->id();
            $table->string("membership_category");
            $table->string("first_name");
            $table->string("middle_name");
            $table->string("last_name");
            $table->longText("profile_picture")->nullable();
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
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users_tbls');
    }
};
