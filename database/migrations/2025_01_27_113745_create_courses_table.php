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

        Schema::create('courses', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('course_name')->unique();
            $table->foreignId('teacher_id')->constrained('staff');
            $table->foreignId('subject_id')->constrained();
            $table->foreignId('diploma_id')->nullable()->constrained();
            $table->foreignId('class_room_id')->constrained();
            $table->date('reg_date')->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();
            $table->string('period')->nullable();
            $table->json('days')->nullable();
            $table->string('status')->nullable();
            $table->timestamps();
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};
