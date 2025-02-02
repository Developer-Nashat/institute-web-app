<?php

use App\Models\User;
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
            $table->date('hire_date')->nullable();
            $table->char('gender', 1)->nullable();
            $table->string('email')->unique()->nullable();
            $table->string('first_phone_number', 15)->nullable();
            $table->string('second_phone_number', 15)->nullable();
            $table->text('address')->nullable();
            $table->foreignId('nationality_id')->constrained('nationalities')->nullable();
            $table->decimal('salary', 10, 2)->nullable();
            $table->decimal('percentage', 10, 2)->nullable();
            $table->foreignId('position_id')->nullable()->constrained();
            $table->boolean('is_teacher')->default(false);
            $table->string('staff_id_no')->nullable();
            $table->foreignId('user_id')->constrained()->nullable();
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
