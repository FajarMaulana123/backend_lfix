<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class M_Service extends Model
{

    public $timestamps = true;

    protected $table = 'service';
    protected $primaryKey = 'id_service';
    protected $fillable = ['kode_service', 'total_harga', 'garansi', 'status_service', 'id','lokasi', 'id_kerusakan', 'kode_barang', 'id_teknisi', 'created_at', 	'updated_at' ];

}
 