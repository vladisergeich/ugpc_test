<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRouteMapCardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('route_map_cards', function (Blueprint $table) {
            $table->increments('id');
            $table->string('shaft_id')->nullable();
            $table->date('create_document_date')->nullable();
            $table->date('approval_document_date')->nullable();
            $table->string('approval_document_fio')->nullable();
            $table->string('okvid_number')->nullable();
            $table->string('order_number')->nullable();
            $table->integer('shaft_format')->nullable();
            $table->boolean('dechromization')->default(true);
            $table->string('dechromization_plan')->nullable();
            $table->string('dechromization_fact')->nullable();
            $table->dateTime('dechromization_datetime')->nullable();
            $table->string('dechromization_smena')->nullable();
            $table->string('dechromization_operator')->nullable();
            $table->boolean('base_grinding')->default(true);
            $table->string('base_grinding_diameter_plan')->nullable();
            $table->string('base_grinding_diameter_fact')->nullable();
            $table->string('base_grinding_kromka_plan')->nullable();
            $table->string('base_grinding_kromka_fact')->nullable();
            $table->string('base_grinding_rz_plan')->nullable();
            $table->string('base_grinding_rz_fact')->nullable();
            $table->dateTime('base_grinding_datetime')->nullable();
            $table->string('base_grinding_smena')->nullable();
            $table->string('base_grinding_operator')->nullable();
            $table->boolean('steel_degreasing')->default(true);
            $table->string('steel_degreasing_plan')->nullable();
            $table->string('steel_degreasing_fact')->nullable();
            $table->dateTime('steel_degreasing_datetime')->nullable();
            $table->string('steel_degreasing_smena')->nullable();
            $table->string('steel_degreasing_operator')->nullable();
            $table->boolean('pre_copper_plating')->default(true);
            $table->string('pre_copper_plating_plan')->nullable();
            $table->string('pre_copper_plating_fact')->nullable();
            $table->dateTime('pre_copper_plating_datetime')->nullable();
            $table->string('pre_copper_plating_smena')->nullable();
            $table->string('pre_copper_plating_operator')->nullable();
            $table->boolean('base_copper_plating')->default(true);
            $table->string('base_copper_plating_t_plan')->nullable();
            $table->string('base_copper_plating_t_fact')->nullable();
            $table->string('base_copper_plating_bath_plan')->nullable();
            $table->string('base_copper_plating_bath_fact')->nullable();
            $table->dateTime('base_copper_plating_datetime')->nullable();
            $table->string('base_copper_plating_smena')->nullable();
            $table->string('base_copper_plating_operator')->nullable();
            $table->boolean('base_degreasing')->default(true);
            $table->string('base_degreasing_plan')->nullable();
            $table->string('base_degreasing_fact')->nullable();
            $table->dateTime('base_degreasing_datetime')->nullable();
            $table->string('base_degreasing_smena')->nullable();
            $table->string('base_degreasing_operator')->nullable();
            $table->boolean('copper_plating_edition')->default(true);
            $table->string('copper_plating_edition_t_plan')->nullable();
            $table->string('copper_plating_edition_t_fact')->nullable();
            $table->string('copper_plating_edition_bath_plan')->nullable();
            $table->string('copper_plating_edition_bath_fact')->nullable();
            $table->dateTime('copper_plating_edition_datetime')->nullable();
            $table->string('copper_plating_edition_smena')->nullable();
            $table->string('copper_plating_edition_operator')->nullable();
            $table->boolean('grinding_edition')->default(true);
            $table->string('grinding_edition_diameter_plan')->nullable();
            $table->string('grinding_edition_diameter_fact')->nullable();
            $table->string('grinding_edition_rz_plan')->nullable();
            $table->string('grinding_edition_rz_fact')->nullable();
            $table->string('grinding_edition_hv_plan')->nullable();
            $table->string('grinding_edition_hv_fact')->nullable();
            $table->dateTime('grinding_edition_datetime')->nullable();
            $table->string('grinding_edition_smena')->nullable();
            $table->string('grinding_edition_operator')->nullable();
            $table->boolean('engraving')->default(true);
            $table->string('engraving_liniatura_plan')->nullable();
            $table->string('engraving_liniatura_fact')->nullable();
            $table->string('engraving_number_plan')->nullable();
            $table->string('engraving_number_fact')->nullable();
            $table->string('engraving_degree_plan')->nullable();
            $table->string('engraving_degree_fact')->nullable();
            $table->string('engraving_separation_plan')->nullable();
            $table->string('engraving_separation_fact')->nullable();
            $table->dateTime('engraving_datetime')->nullable();
            $table->string('engraving_smena')->nullable();
            $table->string('engraving_operator')->nullable();
            $table->boolean('degreasing_edition')->default(true);
            $table->string('degreasing_edition_plan')->nullable();
            $table->string('degreasing_edition_fact')->nullable();
            $table->dateTime('degreasing_edition_datetime')->nullable();
            $table->string('degreasing_edition_smena')->nullable();
            $table->string('degreasing_edition_operator')->nullable();
            $table->boolean('chrome_plating')->default(true);
            $table->string('chrome_plating_plan')->nullable();
            $table->string('chrome_plating_fact')->nullable();
            $table->dateTime('chrome_plating_datetime')->nullable();
            $table->string('chrome_plating_smena')->nullable();
            $table->string('chrome_plating_operator')->nullable();
            $table->boolean('polishing_chrome')->default(true);
            $table->string('polishing_chrome_plan')->nullable();
            $table->string('polishing_chrome_fact')->nullable();
            $table->dateTime('polishing_chrome_datetime')->nullable();
            $table->string('polishing_chrome_smena')->nullable();
            $table->string('polishing_chrome_operator')->nullable();
            $table->boolean('test_print')->default(true);
            $table->string('test_print_color_plan')->nullable();
            $table->string('test_print_color_fact')->nullable();
            $table->dateTime('test_print_datetime')->nullable();
            $table->string('test_print_smena')->nullable();
            $table->string('test_print_operator')->nullable();
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
        Schema::dropIfExists('route_map_cards');
    }
}
