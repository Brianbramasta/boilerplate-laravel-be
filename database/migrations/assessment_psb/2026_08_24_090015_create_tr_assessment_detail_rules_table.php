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
        Schema::create('tr_assessment_detail_rules', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('criteria_transaction_id');
            $table->uuid('rule_id');
            $table->string('rule_name');
            $table->boolean('expected_value');
            $table->boolean('actual_value')->nullable();
            $table->boolean('is_pass')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tr_assessment_detail_rules');
    }
};
