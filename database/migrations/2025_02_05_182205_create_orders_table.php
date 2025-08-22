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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->nullable();
            $table->string('status')->nullable();  
            $table->unsignedBigInteger('customer_id')->nullable(); 
            $table->integer('support_manager_code')->nullable();  
            $table->string('support_manager_name')->nullable();  
            $table->string('print_machine_center')->nullable(); 
            $table->string('winding_option')->nullable();
            $table->date('customer_shipment_date')->nullable();
            $table->string('previous_order_number')->nullable();
            $table->string('current_stage_approval')->nullable();
            $table->jsonb('edit_design_parameters')->nullable();
            $table->string('next_order_number')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
