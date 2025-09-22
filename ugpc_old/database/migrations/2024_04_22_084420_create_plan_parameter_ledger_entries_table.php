<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('plan_parameter_ledger_entries', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('engraving_stage_id');
            $table->unsignedBigInteger('parameter_id');
            $table->float('float_value')->nullable();
            $table->string('string_value')->nullable();
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
        Schema::dropIfExists('plan_parameter_ledger_entries');
    }
};
