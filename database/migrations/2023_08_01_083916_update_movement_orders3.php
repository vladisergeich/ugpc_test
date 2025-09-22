<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateMovementOrders3 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('movement_orders', function ($table) {
            $table->string('engraver')->nullable();
            $table->date('customer_shipment_date')->nullable();
            $table->string('novizna')->nullable();
            $table->date('profil_engraving_date')->nullable();
            $table->date('repro_date')->nullable();
            $table->string('previous_order_number')->nullable();
            $table->string('previous_engraver')->nullable();
            $table->date('previous_order_printing_date')->nullable();
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
