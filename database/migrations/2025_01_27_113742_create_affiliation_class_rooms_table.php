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

        Schema::create('affiliation_class_rooms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('affiliation_id')->constrained();
            $table->foreignId('class_room_id')->constrained();
            $table->decimal('rent_price')->nullable();
            $table->date('reg_date')->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();
            $table->string('status', 10)->nullable();
            $table->char('period', 1)->nullable();
            $table->timestamps();

            $table->index(['start_date', 'end_date', 'start_time', 'end_time']);
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('affiliation_class_rooms');
    }
};
