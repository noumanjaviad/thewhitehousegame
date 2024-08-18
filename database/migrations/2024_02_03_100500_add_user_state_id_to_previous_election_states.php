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
        Schema::table('previous_election_states', function (Blueprint $table) {
           $table->unsignedBigInteger('user_state_id')->nullable()->after('votter_party_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('previous_election_states', function (Blueprint $table) {
            //
        });
    }
};
