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
        Schema::create('log_assessment_transactionals', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('assessment_transaction_id')->nullable();
            $table->uuid('from_status_id')->nullable();
            $table->uuid('to_status_id')->nullable();
            $table->uuid('changed_by');
            $table->timestamp('changed_at');
            $table->string('remark')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('log_assessment_transactionals');
    }
};
