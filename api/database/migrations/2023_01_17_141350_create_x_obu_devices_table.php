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
        Schema::create('x_obu_devices', function (Blueprint $table) {
            $table->string('ftimei')->primary();
            $table->string('ftname',100)->unique();
            $table->string('fttype',100)->nullable();
            $table->string('ftdescriptio',255)->nullable();
            $table->float('fflat')->default(0);
            $table->float('fflon')->default(0);
            $table->timestamp('created_at');
            $table->timestamp('updated_at');
            $table->index([
                'ftimei',
                'ftname',
                'fttype',
                'created_at',
                'updated_at'
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
        Schema::dropIfExists('x_obu_devices');
    }
};
