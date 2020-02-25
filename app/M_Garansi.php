<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class M_Garansi extends Model
{

    public $timestamps = true;

    protected $table = 'garansi';
    protected $primaryKey = 'id_garansi';
    protected $fillable = ['id_service', 'valid_until', 'status_garansi'];

}