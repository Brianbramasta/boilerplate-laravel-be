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
        Schema::create('dt_step_input_values', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('assessment_step_values_id');
            $table->string('variable_name')->nullable();
            $table->string('variable_code')->nullable();
            $table->integer('order');
            $table->string('type_data')->default('number');
            $table->string('value')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dt_step_input_values');
    }
};
