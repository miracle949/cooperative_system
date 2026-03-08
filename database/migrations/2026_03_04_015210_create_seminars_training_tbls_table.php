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
        Schema::create('seminars_training_tbls', function (Blueprint $table) {
            $table->id();
            $table->foreignId("member_id")
                  ->constrained("users_tbls")
                  ->onDelete("cascade");
            $table->string("title_seminar")->nullable();
            $table->date("attendance_from")->nullable();
            $table->date("attendance_to")->nullable();
            $table->string("sponsored_by")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('seminars_training_tbls');
    }
};
