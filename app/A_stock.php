<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class A_stock extends Model
{
    //
    public $timestamps = false;
    protected $table = 'a_stock';
    protected $primaryKey = 'id';
}
