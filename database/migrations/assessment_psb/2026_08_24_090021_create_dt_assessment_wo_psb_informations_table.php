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
        Schema::create('dt_assessment_wo_psb_informations', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('assessment_information_id');
            $table->uuid('team_id');
            $table->string('team_name');
            $table->integer('sales_distance')->nullable();
            $table->integer('technician_distance')->nullable();
            $table->integer('dropcore')->nullable();
            $table->string('cable_status')->nullable();
            $table->bigInteger('cable_history_id')->nullable();
            $table->bigInteger('cable_detail_history_id')->nullable();
            $table->json('psb_extra')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dt_assessment_wo_psb_informations');
    }
};
