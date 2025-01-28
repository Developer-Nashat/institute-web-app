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

        Schema::create('subjects', function (Blueprint $table) {
            $table->id();
            $table->string('sub_name')->unique();
            $table->decimal('sub_price');
            $table->string('duration_type')->nullable();
            $table->smallInteger('total_hours')->nullable();
            $table->smallInteger('total_days')->nullable();
            $table->foreignId('category_id')->constrained('categories');
            $table->timestamps();
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subjects');
    }
};
