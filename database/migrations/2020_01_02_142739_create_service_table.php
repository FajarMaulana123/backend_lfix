<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateServiceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('service', function (Blueprint $table) {
            $table->bigIncrements('id_service');
            $table->string('kode_service')->unique();
            $table->string('total_harga');
            $table->string('garansi');
            $table->string('status_service');
            $table->integer('id_pelanggan');
            $table->integer('id_kerusakan');
            $table->string('kode_barang')->unique();
            $table->integer('id_teknisi');
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
        Schema::dropIfExists('service');
    }
}
