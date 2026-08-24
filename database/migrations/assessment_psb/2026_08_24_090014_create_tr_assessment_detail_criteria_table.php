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
        Schema::create('tr_assessment_detail_criteria', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('assessment_detail_id');
            $table->uuid('criteria_id');
            $table->string('criteria_name');
            $table->uuid('category_id');
            $table->string('category_name');
            $table->double('criteria_max_score')->nullable();
            $table->uuid('criteria_type_id');
            $table->string('criteria_type_name');
            $table->uuid('operation_id')->nullable();
            $table->uuid('score_type_id');
            $table->double('max_score')->nullable();
            $table->double('obtained_score')->nullable();
            $table->double('calculated_value')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tr_assessment_detail_criteria');
    }
};
