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
        // print('imei',imei)
        // print('latlng',lat,lon)
        // print('acc_cep',acc_cep)
        // print('direction',direction)
        // print('speed',speed)
        // print('battery',battery)
        // print('sattelite',sattelite)
        // print('altitude',altitude)
        
        // print('power',power)
        // print('signal',signal)
        
        Schema::table('debuging_routes', function($table) {
            $table->string('fttype',10)->nullable();
            $table->float('ffaccuracy_cep')->nullable();
            $table->float('ffdirection')->nullable();
            $table->float('ffspeed')->nullable();
            $table->float('ffbattery')->nullable();
            $table->integer('fnsattelite')->nullable();
            $table->float('ffaltitude')->nullable();
            $table->boolean('fbpower')->nullable();
            $table->integer('fnsignal')->nullable();
            
            $table->index([
                'fttype',
                'ffaccuracy_cep',
                'ffdirection',
                'ffspeed',
                'ffbattery',
                'fnsattelite',
                'ffaltitude'
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
        //
    }
};
