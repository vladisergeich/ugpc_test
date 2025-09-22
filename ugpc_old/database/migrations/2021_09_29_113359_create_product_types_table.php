<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_types', function (Blueprint $table) {
            $table->increments('id');
            $table->string('gp_code')->nullable();
            $table->string('description')->nullable();
            $table->string('product_category')->nullable();
            $table->string('note')->nullable();
            $table->string('customer')->nullable();
            $table->string('brand')->nullable();
            $table->string('sap_code')->nullable();
            $table->boolean('written_off')->default(false);
            $table->boolean('esko')->default(false);
            $table->boolean('worker')->default(false);
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
        Schema::dropIfExists('product_types');
    }
}
