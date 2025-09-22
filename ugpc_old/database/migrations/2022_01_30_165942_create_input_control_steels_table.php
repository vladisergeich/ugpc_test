<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInputControlSteelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('input_control_steels', function (Blueprint $table) {
            $table->increments('id');
            $table->string('shaft_id')->nullable();
            $table->date('document_date')->nullable();
            $table->string('executor')->nullable();
            $table->string('okvid_number')->nullable();
            $table->string('order_number')->nullable();
            $table->decimal('shaft_radial_runout_1')->nullable();
            $table->decimal('shaft_diameter_one_1')->nullable();
            $table->decimal('shaft_diameter_two_1')->nullable();
            $table->decimal('shaft_taper_1')->nullable();
            $table->decimal('shaft_ellipse_1')->nullable();
            $table->decimal('shaft_roughness_1')->nullable();
            $table->decimal('shaft_seats_1')->nullable();
            $table->decimal('shaft_mass_balance_1')->nullable();
            $table->decimal('shaft_appearance_1')->nullable();
            $table->decimal('shaft_end_radius_1')->nullable();
            $table->decimal('shaft_radial_runout_2')->nullable();
            $table->decimal('shaft_diameter_one_2')->nullable();
            $table->decimal('shaft_diameter_two_2')->nullable();
            $table->decimal('shaft_taper_2')->nullable();
            $table->decimal('shaft_ellipse_2')->nullable();
            $table->decimal('shaft_roughness_2')->nullable();
            $table->decimal('shaft_seats_2')->nullable();
            $table->decimal('shaft_mass_balance_2')->nullable();
            $table->decimal('shaft_appearance_2')->nullable();
            $table->decimal('shaft_end_radius_2')->nullable();
            $table->decimal('shaft_radial_runout_3')->nullable();
            $table->decimal('shaft_diameter_one_3')->nullable();
            $table->decimal('shaft_diameter_two_3')->nullable();
            $table->decimal('shaft_taper_3')->nullable();
            $table->decimal('shaft_ellipse_3')->nullable();
            $table->decimal('shaft_roughness_3')->nullable();
            $table->decimal('shaft_seats_3')->nullable();
            $table->decimal('shaft_mass_balance_3')->nullable();
            $table->decimal('shaft_appearance_3')->nullable();
            $table->decimal('shaft_end_radius_3')->nullable();
            $table->decimal('shaft_radial_runout_4')->nullable();
            $table->decimal('shaft_diameter_one_4')->nullable();
            $table->decimal('shaft_diameter_two_4')->nullable();
            $table->decimal('shaft_taper_4')->nullable();
            $table->decimal('shaft_ellipse_4')->nullable();
            $table->decimal('shaft_roughness_4')->nullable();
            $table->decimal('shaft_seats_4')->nullable();
            $table->decimal('shaft_mass_balance_4')->nullable();
            $table->decimal('shaft_appearance_4')->nullable();
            $table->decimal('shaft_end_radius_4')->nullable();
            $table->decimal('shaft_radial_runout_5')->nullable();
            $table->decimal('shaft_diameter_one_5')->nullable();
            $table->decimal('shaft_diameter_two_5')->nullable();
            $table->decimal('shaft_taper_5')->nullable();
            $table->decimal('shaft_ellipse_5')->nullable();
            $table->decimal('shaft_roughness_5')->nullable();
            $table->decimal('shaft_seats_5')->nullable();
            $table->decimal('shaft_mass_balance_5')->nullable();
            $table->decimal('shaft_appearance_')->nullable();
            $table->decimal('shaft_end_radius_5')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('input_control_steels');
    }
}
