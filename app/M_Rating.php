<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class M_Rating extends Model
{

    public $timestamps = false;

    protected $table = 'rating';
    protected $primaryKey = 'id_rating';
    protected $fillable = ['id_rating', 'kode_service', 'id_user', 'rating','feedback', 'id_teknisi'];

}
 