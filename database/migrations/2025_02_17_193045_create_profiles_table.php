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
        Schema::create('profiles', function (Blueprint $table) {
            $table->id();
            $table->string('code')->nullable();
            $table->enum('print_type', ['Зеркальная', 'Прямая'])->default('Зеркальная');
            $table->string('supplier_analog_icc')->nullable();
            $table->unsignedBigInteger('engraver_id')->nullable();
            $table->unsignedBigInteger('primary_material_id')->nullable();
            $table->unsignedBigInteger('secondary_material_id')->nullable();
            $table->string('paint_series')->nullable();
            $table->jsonb('fingerprint_parameters')->default(json_encode([
                'cylinder_set_id' => '',
                'vendor' => '',
                'order_number' => '',
            ]));
            $table->jsonb('print_process_parameters')->default(json_encode([
                'print_machine' => '',
                'print_type' => '',
                'print_speed' => '',
                'substrate' => '',
                'type_material_process' => '',
                'surface_tension_material' => '',
                'manufacturer_paint_series' => '',
                'air_temperature' => '',
                'humidity' => '',
            ]));
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('profiles');
    }
};
