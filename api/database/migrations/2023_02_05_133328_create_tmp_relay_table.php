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
        Schema::create('tmp_relay', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string("seq",10);
            $table->timestamp('time');
            $table->string("imei",30);
            $table->string("event",10);
            $table->float("long");
            $table->float("lat");
            $table->string("pdop",10);
            $table->string("direct",10);
            $table->string("speed",10);
            $table->string("bat",10);
            $table->string("sat",10);
            $table->integer("tmpflag")->default(1);
            $table->float("alt");
            $table->bigInteger("fnx_geo_declare_id")->default(0);
            $table->index([
                'id',
                'seq',
                'time',
                'imei',
                'long',
                'lat',
                'pdop',
                'direct',
                'speed',
                'bat',
                'sat',
                'alt',
                'tmpflag'
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
        Schema::dropIfExists('tmp_relay');
    }
};
