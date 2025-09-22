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
        Schema::create('operation_ledger_entries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('engraving_order_shaft_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('phase_stage_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('operation_id')->constrained()->nullOnDelete();
            $table->date('posting_date')->nullable();
            $table->time('starting_time', $precision = 0)->nullable();
            $table->time('ending_time', $precision = 0)->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('work_shift_code_id')->nullable();
            $table->unsignedBigInteger('machine_id')->nullable();
            $table->string('status')->nullable()->default('in_progress');
            
            $table->unique(['engraving_order_shaft_id', 'phase_stage_id','operation_id']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('operation_ledger_entries');
    }
};
