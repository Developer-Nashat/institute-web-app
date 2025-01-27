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

        Schema::create('affiliations', function (Blueprint $table) {
            $table->id();
            $table->string('aff_name');
            $table->string('supervisor');
            $table->text('aff_address');
            $table->string('first_phone', 15)->nullable();
            $table->string('second_phone', 15)->nullable();
            $table->string('aff_email')->nullable();
            $table->timestamps();
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('affiliations');
    }
};
