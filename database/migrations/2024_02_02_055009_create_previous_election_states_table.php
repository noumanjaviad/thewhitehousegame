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
        Schema::create('previous_election_states', function (Blueprint $table) {
            $table->id();
            $table->date('election_year');
            $table->string('gender')->nullable();
            $table->string('age_range')->nullable();
            $table->decimal('vote_percentage', 5, 2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('previous_election_states');
    }
};
