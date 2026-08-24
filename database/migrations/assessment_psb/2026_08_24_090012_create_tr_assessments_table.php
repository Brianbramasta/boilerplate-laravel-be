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
        Schema::create('tr_assessments', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('pppoe_secret');
            $table->uuid('assessment_code_id');
            $table->uuid('work_order_id')->nullable();
            $table->uuid('current_pic_id')->nullable();
            $table->string('company_code')->default('griya');
            $table->string('system_code')->default('griya');
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->uuid('status_id');
            $table->uuid('result_id')->nullable();
            $table->boolean('is_had_draft')->default(false);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tr_assessments');
    }
};
