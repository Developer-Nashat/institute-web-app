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

        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->string('ar_name')->unique();
            $table->string('en_name')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->char('student_gender')->nullable();
            $table->string('student_email')->unique()->nullable();
            $table->string('first_phone', 15)->nullable();
            $table->string('second_phone', 15)->nullable();
            $table->text('student_address')->nullable();
            $table->foreignId('nationalty_id')->constrained('nationalities');
            $table->text('student_img')->nullable();
            $table->string('student_id_no')->nullable();
            $table->timestamps();

            $table->unique(['ar_name', 'en_name', 'student_id_no']);
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
