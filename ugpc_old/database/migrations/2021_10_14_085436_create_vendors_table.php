<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVendorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vendors', function (Blueprint $table) {
            $table->increments('id');
            $table->string('vendor_name')->nullable();
            $table->string('address')->nullable();
            $table->string('phone_fax')->nullable();
            $table->string('сontact')->nullable();
            $table->string('сontact_position')->nullable();
            $table->string('сontact_phone')->nullable();
            $table->string('сontact_two')->nullable();
            $table->string('сontact_two_position')->nullable();
            $table->string('сontact_two_phone')->nullable();
            $table->string('vendor_short')->nullable();
            $table->string('code_navision')->nullable();
            $table->string('shortname')->nullable();
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
        Schema::dropIfExists('vendors');
    }
}
