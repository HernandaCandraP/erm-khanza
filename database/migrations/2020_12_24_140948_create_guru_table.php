<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGuruTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('guru', function (Blueprint $table) {
            $table->smallIncrements('id_guru');
            $table->char('nip', 22);
            $table->char('nama_guru', 50);
            $table->enum('jenis_kelamin', ['Laki-laki','Perempuan']);
            $table->char('alamat', 100);
            $table->char('no_telp', 13);
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
        Schema::dropIfExists('guru');
    }
}
