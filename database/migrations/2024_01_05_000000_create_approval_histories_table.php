<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('log_approval_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('leave_request_id')->constrained('tr_leave_requests')->cascadeOnDelete();
            $table->foreignId('actor_id')->constrained('mt_users')->cascadeOnDelete();
            $table->string('actor_name');
            $table->string('previous_status');
            $table->string('next_status');
            $table->string('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('log_approval_histories');
    }
};