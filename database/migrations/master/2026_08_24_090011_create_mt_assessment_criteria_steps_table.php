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
        Schema::create('mt_assessment_criteria_steps', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('assessment_criteria_id');
            $table->bigInteger('sequence');
            $table->string('step_id');
            $table->string('step_code');
            $table->string('step_name');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mt_assessment_criteria_steps');
    }
};
