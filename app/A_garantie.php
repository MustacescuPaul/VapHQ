<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class A_garantie extends Model
{
    //
    protected $connection = 'garantii';
    public $timestamps = false;
    protected $table = 'a_garantie';
    protected $primaryKey = 'id_prod';
}
