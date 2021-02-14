<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMapelkelasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mapelkelas', function (Blueprint $table) {
            $table->smallIncrements('id_mapelkelas');
            $table->smallInteger('id_detail')->unsigned();
            $table->smallInteger('id_kelas')->unsigned();
            $table->foreign('id_detail')->references('id_detail')->on('detailmapel')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('id_kelas')->references('id_kelas')->on('kelas')->onDelete('cascade')->onUpdate('cascade');
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
        Schema::dropIfExists('mapelkelas');
    }
}
