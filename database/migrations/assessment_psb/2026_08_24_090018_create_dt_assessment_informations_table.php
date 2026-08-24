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
        Schema::create('dt_assessment_informations', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('assessment_transaction_id');
            $table->uuid('branch_id');
            $table->string('branch_name');
            $table->string('regional_name');
            $table->string('pppoe_secret');
            $table->uuid('wo_id');
            $table->string('no_wo');
            $table->string('customer_name');
            $table->uuid('assessment_code_id');
            $table->date('work_order_date');
            $table->timestamp('start_work_order')->nullable();
            $table->timestamp('end_work_order')->nullable();
            $table->integer('duration_minute')->nullable();
            $table->text('address')->nullable();
            $table->json('cust_extra')->nullable();
            $table->json('erp_extra')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dt_assessment_informations');
    }
};
