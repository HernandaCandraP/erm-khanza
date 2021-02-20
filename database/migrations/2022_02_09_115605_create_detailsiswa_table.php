<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDetailSiswaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detailsiswa', function (Blueprint $table) {
            $table->smallIncrements('id_detailsiswa');
            $table->smallInteger('id_detail')->unsigned();
            $table->smallInteger('id_siswa')->unsigned();
            $table->foreign('id_detail')->references('id_detail')->on('detailmapel')->onDelete('restrict')->onUpdate('restrict');
            $table->foreign('id_siswa')->references('id_siswa')->on('siswa')->onDelete('restrict')->onUpdate('restrict');
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
