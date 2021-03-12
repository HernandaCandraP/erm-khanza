<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSoalTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('soal', function (Blueprint $table) {
            $table->smallIncrements('id_soal');
            $table->smallInteger('id_detail')->unsigned();
            $table->foreign('id_detail')->references('id_detail')->on('detailmapel')->onDelete('restrict')->onUpdate('restrict');
            $table->text('soal');
            $table->text('jawaban');
            $table->text('stemming');
            $table->text('image')->unsigned();
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
        Schema::dropIfExists('soal');
    }
}
