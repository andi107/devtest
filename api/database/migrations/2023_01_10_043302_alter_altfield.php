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
        Schema::table('ble_declare2', function($table) {
            $table->float('alt')->nullable();
        });
        Schema::table('button_declare2', function($table) {
            $table->float('alt')->nullable();
        });
        Schema::table('geo_declare2', function($table) {
            $table->float('alt')->nullable();
        });
        Schema::table('loc_relay', function($table) {
            $table->float('alt')->nullable();
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
