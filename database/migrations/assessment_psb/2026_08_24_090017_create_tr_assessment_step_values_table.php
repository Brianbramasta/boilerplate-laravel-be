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
        Schema::create('tr_assessment_step_values', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('criteria_transaction_id');
            $table->integer('order');
            $table->string('step_code');
            $table->string('variable_code')->nullable();
            $table->string('variable_name')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tr_assessment_step_values');
    }
};
