<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class M_Barang extends Model
{

    public $timestamps = false;

    protected $table = 'barang';
    protected $primaryKey = 'kode_barang';
    protected $fillable = ['kode_barang', 'jenis_barang', 'icon'];

}