<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJawabanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('jawaban', function (Blueprint $table) {
            $table->smallIncrements('id_jawaban');
            $table->smallInteger('id_siswa')->unsigned();
            $table->foreign('id_siswa')->references('id_siswa')->on('siswa')->onDelete('restrict')->onUpdate('restrict');
            $table->smallInteger('id_soal')->unsigned();
            $table->foreign('id_soal')->references('id_soal')->on('soal')->onDelete('restrict')->onUpdate('restrict');
            $table->text('jawaban_siswa')->nullable();
            $table->text('stemming_jawaban_siswa')->nullable();
            $table->float('nilai_cosine');
            $table->float('nilai_jaccard');
            $table->float('nilai_guru')->nullable();
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
        Schema::dropIfExists('jawaban');
    }
}
