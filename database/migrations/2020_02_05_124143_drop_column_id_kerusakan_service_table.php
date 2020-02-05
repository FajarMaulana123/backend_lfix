<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropColumnIdKerusakanServiceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         Schema::table('service', function (Blueprint $table) {
            $table->String('total_harga')->nullable()->change();
            $table->String('garansi')->nullable()->change();
            $table->dropColumn('id_kerusakan');
            $table->String('lokasi')->after('id_user');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('service', function (Blueprint $table) {
            $table->String('id_kerusakan')->after('id_user');
        });
    }
}
