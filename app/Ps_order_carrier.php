<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ps_order_carrier extends Model
{
    //
    protected $table = 'ps_order_carrier';
    protected $primaryKey = 'id_order_carrier';
    protected $connection = 'vapez';
    public $timestamps = false;
}
