<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ps_order_history extends Model
{
    //
    protected $table = 'ps_order_history';
    protected $primaryKey = 'id_order_history';
    protected $connection = 'vapez';
    public $timestamps = false;
}
