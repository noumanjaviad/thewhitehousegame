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
        Schema::table('voter_candidates', function (Blueprint $table) {
            $table->date('dob')->nullable();
            $table->string('birth_place')->nullable();
            $table->string('occupation')->nullable();
            $table->text('position')->nullable()->comment('JSON field storing an array of notable positions');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('voter_candidates', function (Blueprint $table) {
            //
        });
    }
};
