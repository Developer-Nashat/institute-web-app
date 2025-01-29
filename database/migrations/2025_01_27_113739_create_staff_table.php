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
        Schema::create('staff', function (Blueprint $table) {
            $table->id();
            $table->string('ar_name')->unique();
            $table->string('en_name')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->char('gender')->nullable();
            $table->string('email')->unique()->nullable();
            $table->string('first_phone_number', 15)->nullable();
            $table->string('second_phone_number', 15)->nullable();
            $table->text('address')->nullable();
            $table->foreignId('nationality_id')->constrained('nationalities');
            $table->decimal('salary', 10, 2)->nullable();
            $table->decimal('percentage', 10, 2)->nullable();
            $table->foreignId('position_id')->nullable()->constrained();
            $table->boolean('is_teacher')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('staff');
    }
};
