<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tr_leave_balances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('mt_users')->cascadeOnDelete();
            $table->foreignId('leave_type_id')->constrained('ref_leave_types')->cascadeOnDelete();
            $table->year('year');
            $table->integer('total_quota');
            $table->integer('used_quota')->default(0);
            $table->integer('remaining_balance');
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();

            $table->unique(['user_id', 'leave_type_id', 'year']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tr_leave_balances');
    }
};