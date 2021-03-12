<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDetailmapelTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detailmapel', function (Blueprint $table) {
            $table->smallIncrements('id_detail');
            $table->smallInteger('id_mapel')->unsigned();
            $table->foreign('id_mapel')->references('id_mapel')->on('mapel')->onDelete('restrict')->onUpdate('restrict');
            $table->char('keterangan', 50)->nullable();
            $table->datetime('waktu_mulai');
            $table->datetime('waktu_selesai');
            $table->time('waktu_test', 0);
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
        Schema::dropIfExists('detailmapel');
    }
}
