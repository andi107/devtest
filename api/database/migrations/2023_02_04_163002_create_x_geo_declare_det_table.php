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
        Schema::create('x_geo_declare_det', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->bigInteger('x_geo_declare_id');
            $table->float('fflat');
            $table->float('fflon');
            $table->integer("fnindex");
            $table->integer('fnchkpoint')->default(0);

            $table->index([
                'id',
                'x_geo_declare_id',
                'fflat',
                'fflon',
                'fnchkpoint'
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
        Schema::dropIfExists('x_geo_declare_det');
    }
};
