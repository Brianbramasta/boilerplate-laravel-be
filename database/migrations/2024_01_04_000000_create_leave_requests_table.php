<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tr_leave_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('mt_users')->cascadeOnDelete();
            $table->foreignId('leave_type_id')->constrained('ref_leave_types')->cascadeOnDelete();
            $table->date('start_date');
            $table->date('end_date');
            $table->integer('total_days');
            $table->string('reason');
            $table->string('status')->default('SUBMITTED');
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();

            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tr_leave_requests');
    }
};