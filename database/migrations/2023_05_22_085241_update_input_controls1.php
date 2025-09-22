<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateInputControls1 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('input_controls', function ($table) {
            $table->string('type_shaft')->nullable();
            $table->decimal('edge_radius', 17, 5)->nullable();
            $table->decimal('copper_plating', 17, 5)->nullable();
            $table->decimal('roughness', 17, 5)->nullable();
            $table->boolean('defect')->default(false);
            $table->string('defect_reason')->nullable();
            $table->string('work_shift_code')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
