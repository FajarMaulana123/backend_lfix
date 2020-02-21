<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class M_Kerusakan extends Model
{

    public $timestamps = true;

    protected $table = 'kerusakan';
    protected $fillable = ['kode_service', 'harga', 'kerusakan'];

}