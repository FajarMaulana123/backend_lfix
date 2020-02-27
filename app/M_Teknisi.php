<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class M_Teknisi extends Model
{

    public $timestamps = true;

    protected $table = 'teknisi';
    protected $primaryKey = 'id_teknisi';
    protected $fillable = ['t_nama', 't_email', 't_alamat', 't_hp', 't_keahlian', 't_ktp', 't_selfi', 'rating_teknisi'];

}