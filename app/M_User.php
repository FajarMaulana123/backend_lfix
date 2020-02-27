<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class M_User extends Model
{

    public $timestamps = false;

    protected $table = 'users';
    protected $primaryKey = 'id';
    protected $fillable = ['name', 'email','phone','email_verified_at','remember_token'];

}