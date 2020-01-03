<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class M_Estimasi extends Model
{

    public $timestamps = false;

    protected $table = 'estimasi';
    protected $primaryKey = 'id_estimasi';
    protected $fillable = ['kode_barang', 'est_kerusakan', 'harga', 'jenis_barang'];

}