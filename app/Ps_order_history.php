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

    public function Ps_order_state_lang()
    {
        return $this->hasOne(Ps_order_state_lang::class, 'id_order_state', 'id_order_state');
    }
}
