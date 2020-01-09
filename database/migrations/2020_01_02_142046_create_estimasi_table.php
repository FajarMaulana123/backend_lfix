<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEstimasiTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('estimasi', function (Blueprint $table) {
            $table->bigIncrements('id_estimasi');
            $table->string('kode_barang')->unique();
            $table->string('est_kerusakan');
            $table->integer('harga');
            $table->string('jenis_barang');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('estimasi');
    }
}
