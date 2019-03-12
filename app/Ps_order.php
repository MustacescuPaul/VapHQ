<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ps_order extends Model
{
    //
    protected $table = 'ps_orders';
    protected $primaryKey = 'id_order';
    protected $connection = 'vapez';
    public $timestamps = false;


    public function ps_order_detail()
    {
        return $this->hasMany('App\Ps_order_detail', 'id_order_detail', 'id_order');
    }
}
