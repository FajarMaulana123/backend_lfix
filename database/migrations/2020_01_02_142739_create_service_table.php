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
            $table->integer('id');
            $table->integer('id_teknisi');
            $table->string('kode_service')->unique();
            $table->string('kode_barang');
            $table->string('lokasi');
            $table->string('total_harga');
            $table->string('garansi');
            $table->date('end_date');
            $table->string('status_service');
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
