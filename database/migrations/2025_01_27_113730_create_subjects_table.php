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
            $table->string('sub_name');
            $table->decimal('sub_price');
            $table->string('DurationType');
            $table->smallInteger('TotalHours');
            $table->smallInteger('Totaldays');
            $table->foreignId('cat_id')->constrained('categories');
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
