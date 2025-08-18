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
        Schema::create('cases', function (Blueprint $table) {
            $table->id();

             // Client info
            $table->string('client_tel')->nullable();
            $table->string('client_email')->nullable();
            $table->string('client_address')->nullable();
            $table->string('client_dob')->nullable();
            $table->string('client_ssn')->nullable();

            // Third Party (3P)
            $table->string('third_party_claim')->nullable();
            $table->string('third_party_adjuster')->nullable();
            $table->string('third_party_tel')->nullable();
            $table->string('third_party_fax')->nullable();
            $table->string('third_party_email')->nullable();

            // First Party (1P)
            $table->string('first_party_claim')->nullable();
            $table->string('first_party_adjuster')->nullable();
            $table->string('first_party_tel')->nullable();
            $table->string('first_party_fax')->nullable();
            $table->string('first_party_email')->nullable();

            // Defense Counsel
            $table->string('defense_tel')->nullable();
            $table->string('defense_fax')->nullable();
            $table->string('defense_email')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cases');
    }
};
