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
        Schema::create('engraving_order_shafts', function (Blueprint $table) {
            $table->id();
            $table->unsignedSmallInteger('sequence_number');
            $table->foreignId('engraving_order_id')->constrained()->onDelete('cascade');
            $table->foreignId('shaft_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('status')->nullable();
            $table->string('color')->nullable();
            $table->string('lineature')->nullable();
            $table->string('corner')->nullable();
            $table->string('cutter')->nullable();
            $table->float('engraving_time')->nullable(); 
            $table->float('final_diameter')->nullable();
            $table->date('write_off_date')->nullable();
            $table->text('note')->nullable();
            $table->jsonb('parameters')->default(json_encode([
                'reengraving' => false,
                'shortening_scale_length' => false,
                'confirmed' => false,
            ]));
            $table->timestamps();

            $table->unique(['sequence_number','engraving_order_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('engraving_order_shafts');
    }
};
