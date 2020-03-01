<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class M_Sk extends Model
{

    public $timestamps = false;

    protected $table = 'sk';
    protected $primaryKey = 'id_sk';
    protected $fillable = ['isi_sk', 'tipe_sk'];

}