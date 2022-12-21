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
        Schema::create('rcv_ignition2', function (Blueprint $table) {
            $table->bigInteger('id')->primary();
            $table->string('seq');
            $table->string('time');
            $table->string('imei');
            $table->string('event');
            $table->string('power');
            $table->string('bat');
            $table->string('sig');
            $table->string('sat');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rcv_ignition2');
    }
};
