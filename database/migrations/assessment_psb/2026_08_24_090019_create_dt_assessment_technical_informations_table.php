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
        Schema::create('dt_assessment_technical_informations', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('assessment_information_id');
            $table->uuid('area_id');
            $table->string('area_name');
            $table->string('area_code');
            $table->bigInteger('area_primary_id')->nullable();
            $table->string('area_primary_name')->nullable();
            $table->string('area_primary_code')->nullable();
            $table->integer('splitter_id');
            $table->string('splitter');
            $table->integer('customer_port');
            $table->json('tech_extra')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dt_assessment_technical_informations');
    }
};
