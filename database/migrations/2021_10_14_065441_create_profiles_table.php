<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProfilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('profiles', function (Blueprint $table) {
            $table->increments('id');
            $table->string('short_name')->nullable();
            $table->string('print')->nullable();
            $table->string('profile_name')->nullable();
            $table->string('supplier_analog_icc')->nullable();
            $table->string('engraver')->nullable();
            $table->string('primary')->nullable();
            $table->string('secondary_housing')->nullable();
            $table->string('paint_series')->nullable();
            $table->date('creation_date')->nullable();
            $table->string('proof_standart')->nullable();
            $table->string('profile_mx4')->nullable();
            $table->string('reference')->nullable();
            $table->boolean('lacquer')->default(false);
            $table->boolean('primer')->default(false);
            $table->string('note')->nullable();
            $table->float('density_c100', 10, 5)->nullable();
            $table->float('density_l_c100', 10, 5)->nullable();
            $table->float('density_a_c100', 10, 5)->nullable();
            $table->float('density_b_c100', 10, 5)->nullable();
            $table->float('density_m100', 10, 5)->nullable();
            $table->float('density_l_m100', 10, 5)->nullable();
            $table->float('density_a_m100', 10, 5)->nullable();
            $table->float('density_b_m100', 10, 5)->nullable();
            $table->float('density_y100', 10, 5)->nullable();
            $table->float('density_l_y100', 10, 5)->nullable();
            $table->float('density_a_y100', 10, 5)->nullable();
            $table->float('density_b_y100', 10, 5)->nullable();
            $table->float('density_k100', 10, 5)->nullable();
            $table->float('density_l_k100', 10, 5)->nullable();
            $table->float('density_a_k100', 10, 5)->nullable();
            $table->float('density_b_k100', 10, 5)->nullable();
            $table->float('density_c50', 10, 5)->nullable();
            $table->float('density_m50', 10, 5)->nullable();
            $table->float('density_y50', 10, 5)->nullable();
            $table->float('density_k50', 10, 5)->nullable();
            $table->float('density_d_c100', 10, 5)->nullable();
            $table->float('density_l_d_c100', 10, 5)->nullable();
            $table->float('density_a_d_c100', 10, 5)->nullable();
            $table->float('density_b_d_c100', 10, 5)->nullable();
            $table->float('density_d_m100', 10, 5)->nullable();
            $table->float('density_l_d_m100', 10, 5)->nullable();
            $table->float('density_a_d_m100', 10, 5)->nullable();
            $table->float('density_b_d_m100', 10, 5)->nullable();
            $table->float('density_d_y100', 10, 5)->nullable();
            $table->float('density_l_d_y100', 10, 5)->nullable();
            $table->float('density_a_d_y100', 10, 5)->nullable();
            $table->float('density_b_d_y100', 10, 5)->nullable();
            $table->float('density_d_k100', 10, 5)->nullable();
            $table->float('density_l_d_k100', 10, 5)->nullable();
            $table->float('density_a_d_k100', 10, 5)->nullable();
            $table->float('density_b_d_k100', 10, 5)->nullable();
            $table->float('density_d_c50', 10, 5)->nullable();
            $table->float('density_d_m50', 10, 5)->nullable();
            $table->float('density_d_y50', 10, 5)->nullable();
            $table->float('density_d_k50', 10, 5)->nullable();
            $table->boolean('obsolete')->default(false);
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
        Schema::dropIfExists('profiles');
    }
}
