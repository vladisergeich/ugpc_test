<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->increments('id');
            $table->string('okvid_number')->nullable();
            $table->integer('upak_order')->nullable();
            $table->string('external_comment')->nullable();
            $table->string('order_status')->nullable();
            $table->string('prod_order')->nullable();
            $table->integer('cylinder_quantity')->nullable();
            $table->float('gradation_increase')->nullable();
            $table->string('output')->nullable();
            $table->string('defect_cause')->nullable();
            $table->string('condition')->nullable();
            $table->boolean('print')->default(false);
            $table->string('order_structure')->nullable();
            $table->string('designer')->nullable();
            $table->string('designer_new')->nullable();
            $table->string('manager_osz')->nullable();
            $table->string('external_comment_add')->nullable();
            $table->integer('format')->nullable();
            $table->boolean('from_old_film')->default(false);
            $table->string('internal_comment')->nullable();
            $table->boolean('outout_display')->default(false);
            $table->string('engraving_request_filled')->nullable();
            $table->string('supplier')->nullable();
            $table->string('supplier_engraving')->nullable();
            $table->boolean('new_job')->default(false);
            $table->boolean('order_reengraving')->default(false);
            $table->boolean('engraving_with_change')->default(false);
            $table->boolean('blank_making')->default(false);
            $table->boolean('diametr_change')->default(false);
            $table->boolean('overchroming')->default(false);
            $table->boolean('prepress')->default(false);
            $table->boolean('color_proof')->default(false);
            $table->boolean('test_print')->default(false);
            $table->boolean('electronic_file')->default(false);
            $table->boolean('promo_sample')->default(false);
            $table->integer('material_width')->nullable();
            $table->string('paint_series')->nullable();
            $table->string('test_number')->nullable();
            $table->string('profile')->nullable();
            $table->string('primary')->nullable();
            $table->string('secondary')->nullable();
            $table->boolean('drive_label_right')->default(false);
            $table->boolean('drive_label_left')->default(false);
            $table->boolean('traffic_lights_right')->default(false);
            $table->boolean('traffic_lights_left')->default(false);
            $table->boolean('cross_right')->default(false);
            $table->boolean('cross_left')->default(false);
            $table->boolean('cutting_line_right')->default(false);
            $table->boolean('cutting_line_left')->default(false);
            $table->string('electronic_version_fragment')->nullable();
            $table->string('digital_proofing_prim')->nullable();
            $table->string('electronic_version_escapement')->nullable();
            $table->string('proofing_press_prim')->nullable();
            $table->boolean('shipment_cylinder')->default(false);
            $table->string('file_placement')->nullable();
            $table->date('order_accepted_date')->nullable();
            $table->date('jpeg_sent_approval')->nullable();
            $table->date('layout_planned_date_approval')->nullable();
            $table->date('layout_agreed_text')->nullable();
            $table->date('date_arrival_shaft')->nullable();
            $table->date('color_proof_agreed')->nullable();
            $table->date('application_manufacture_bases')->nullable();
            $table->date('request_install_date')->nullable();
            $table->date('request_engraving_date')->nullable();
            $table->date('color_proof_sent_date')->nullable();
            $table->boolean('imprinted')->default(false);
            $table->integer('width_stream')->nullable();
            $table->integer('phototag_step')->nullable();
            $table->integer('amount_stream')->nullable();
            $table->integer('trim_release')->nullable();
            $table->string('cut_line_color')->nullable();
            $table->integer('fragment_in_circulation')->nullable();
            $table->string('photo_tag_size')->nullable();
            $table->string('winding_option')->nullable();
            $table->integer('tag_rsp_quantity')->nullable();
            $table->integer('tag_l_quantity')->nullable();
            $table->integer('gap_a_quantity')->nullable();
            $table->integer('gap_b_quantity')->nullable();
            $table->integer('field_c_quantity')->nullable();
            $table->integer('width_engraving')->nullable();
            $table->string('application_install_completed')->nullable();
            $table->string('application_manufact_bases_completed')->nullable();
            $table->string('repro')->nullable();
            $table->string('engraving')->nullable();
            $table->string('note_three')->nullable();
            $table->string('note_four')->nullable();
            $table->string('feedback_technologist')->nullable();
            $table->string('number_stages')->nullable();
            $table->integer('quantity_stages')->nullable();
            $table->boolean('marker_cs')->default(false);
            $table->string('timing_comment')->nullable();
            $table->boolean('no_folder')->default(false);
            $table->date('receipt_request')->nullable();
            $table->date('shipment_request')->nullable();
            $table->date('transfer_request')->nullable();
            $table->string('filled_parametr')->nullable();
            $table->integer('no_three_p')->nullable();
            $table->integer('no_three_o')->nullable();
            $table->integer('no_three_pb')->nullable();
            $table->boolean('unload_in_nav')->default(false);
            $table->integer('printing_stage')->nullable();
            $table->boolean('unload_in_nav_two')->default(false);
            $table->date('state_update')->nullable();
            $table->integer('sleeve_lenght')->nullable();
            $table->boolean('shevrony')->default(false);
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
        Schema::dropIfExists('orders');
    }
}
