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
        Schema::create('tr_assessment_details', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('assessment_transaction_id');
            $table->uuid('assessment_id');
            $table->string('assessment_name');
            $table->uuid('assessment_code_id');
            $table->double('total_score')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tr_assessment_details');
    }
};
