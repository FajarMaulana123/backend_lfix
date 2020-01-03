<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTeknisiTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('teknisi', function (Blueprint $table) {
            $table->bigIncrements('id_teknisi');
            $table->string('t_nama');
            $table->string('t_email')->unique();
            $table->string('t_alamat');
            $table->string('t_hp');
            $table->string('t_keahlian');
            $table->string('t_ktp');
            $table->string('t_selfi');
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
        Schema::dropIfExists('teknisi');
    }
}
