<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class M_Service extends Model
{

    public $timestamps = true;

    protected $table = 'service';
    protected $primaryKey = 'id_service';
    protected $fillable = ['id','id_teknisi','kode_service', 'kode_barang', 'lokasi','total_harga', 'garansi', 'start_date','end_date', 'status_service', 'created_at','updated_at' ];

}
 