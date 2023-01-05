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
        Schema::create('x_geo_declare', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('ftgate_name');
            $table->string('ftstate',2);
            $table->float('fflat_pos1');
            $table->float('fflon_pos1');
            $table->float('fflat_pos2');
            $table->float('fflon_pos2');
            $table->index([
                'id',
                'ftgate_name'
            ]);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('x_geo_declare');
    }
};
