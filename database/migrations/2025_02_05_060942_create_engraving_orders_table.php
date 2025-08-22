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
        Schema::create('engraving_orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedSmallInteger('sequence_number')->default(0);
            $table->foreignId('macro_order_id')->constrained()->onDelete('cascade');
            $table->unsignedBigInteger('order_id')->nullable();
            $table->unsignedBigInteger('profile_id')->nullable();
            $table->unsignedBigInteger('status_id')->nullable();
            $table->unsignedBigInteger('condition_id')->nullable();
            $table->unsignedBigInteger('repro_id')->nullable();
            $table->unsignedBigInteger('engraver_id')->nullable();
            $table->unsignedBigInteger('designer_id')->nullable();
            $table->float('gradation_increase')->nullable()->default(0.03);           
            $table->date('order_approval_date')->nullable();
            $table->string('cutting_line_color')->nullable()->default('70%black');
            $table->date('printing_date')->nullable();
            $table->unsignedBigInteger('engraving_request_user_id')->nullable();
            $table->date('engraving_request_date')->nullable();
            $table->integer('quantity_shaft')->nullable();  
            $table->unsignedBigInteger('repeat_engraving_order_id')->nullable();
            $table->jsonb('comments')->default(json_encode([
                'internal_comment' => null,
                'engraver_comment' => null,
            ]));
            $table->jsonb('type_work_parameters')->default(json_encode([
                'new_job' => false,
                'reengraving' => false,
                'engraving_with_change' => false,
                'installation' => false,
                'color_proof' => false,
                'test_print' => false,
            ]));
            $table->jsonb('technological_elements')->default(json_encode([
                'drive_label_right' => false,
                'drive_label_left' => false,
                'traffic_lights_right' => false,
                'traffic_lights_left' => false,
                'cross_right' => false,
                'cross_left' => false,
                'cutting_line_right' => false,
                'cutting_line_left' => false,   
            ]));
            $table->jsonb('additional_options')->default(json_encode([
                'automatic_stream' => false,
                'gap' => false,
                'rail' => false,
                'without_cutting' => false,
                'rotate_factor' => false,
                'label_coldseal' => false,
                'chevrons' => false,
                'centering_line' => false,    
            ]));
            $table->jsonb('parameters')->default(json_encode([
                'universal_shaft' => false,
                'approve_coordinator' => false,   
                'updated_application' => false, 
                'without_unloading_application' => false,    
            ]));
            $table->foreignId('mounting_parameter_id')->nullable()->constrained()->nullOnDelete();
            $table->float('format')->nullable();  
            $table->float('material_width')->nullable();  
            $table->float('stream_width')->nullable();  
            $table->float('print_step')->nullable();  
            $table->integer('streams_quantity')->nullable();   
            $table->integer('fragments_in_circulation')->nullable();  
            $table->float('sleeve_length')->nullable();  
            $table->float('engraving_width')->nullable();  
            $table->unsignedBigInteger('previous_engraving_order_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('engraving_orders');
    }
};
