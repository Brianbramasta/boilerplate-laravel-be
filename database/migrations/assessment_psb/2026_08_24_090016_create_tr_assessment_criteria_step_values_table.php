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
        Schema::create('tr_assessment_criteria_step_values', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('criteria_step_id');
            $table->string('variable_name');
            $table->string('variable_code');
            $table->integer('order');
            $table->string('type_data')->default('number');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tr_assessment_criteria_step_values');
    }
};
