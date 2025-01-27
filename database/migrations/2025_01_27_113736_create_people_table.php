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
        Schema::disableForeignKeyConstraints();

        Schema::create('people', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('ar_name')->unique();
            $table->string('en_name')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->char('person_gender')->nullable();
            $table->string('person_email')->nullable();
            $table->string('first_phone', 15)->nullable();
            $table->string('second_phone', 15)->nullable();
            $table->text('person_address')->nullable();
            $table->foreignId('nationalty_id')->constrained('nationalities');
            $table->timestamps();
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('people');
    }
};
