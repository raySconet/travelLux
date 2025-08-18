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
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('name');               // Event name
            $table->string('color')->nullable();  // Event color (hex or text)

            $table->foreignId('user_id');          // Link to users table

            $table->dateTime('date_from');        // Start datetime
            $table->dateTime('date_to');          // End datetime
            $table->integer('isDeleted')->default(0);

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
