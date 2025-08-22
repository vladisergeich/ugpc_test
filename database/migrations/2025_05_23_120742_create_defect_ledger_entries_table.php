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
        Schema::create('defect_ledger_entries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('engraving_order_shaft_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('phase_stage_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('operation_id')->nullable()->constrained();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('work_shift_code_id')->nullable();
            $table->unsignedBigInteger('machine_id')->nullable();
            $table->unsignedBigInteger('reason_id')->nullable();
            $table->string('note')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('defect_ledger_entries');
    }
};
