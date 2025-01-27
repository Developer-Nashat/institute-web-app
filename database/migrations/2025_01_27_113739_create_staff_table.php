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

        Schema::create('staff', function (Blueprint $table) {
            $table->id();
            $table->decimal('staff_salary');
            $table->decimal('staff_percentage');
            $table->foreignId('position_id')->nullable()->constrained();
            $table->boolean('is_teacher');
            $table->foreignUuid('people_id')->constrained('people');
            $table->timestamps();
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('staff');
    }
};
