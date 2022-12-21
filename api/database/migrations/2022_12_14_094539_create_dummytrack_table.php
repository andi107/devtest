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
        Schema::create('dummytrack', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->decimal('fnlat', 10,6);
            $table->decimal('fnlng', 10,6);
            $table->float('fnhead');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('dummytrack');
    }
};
