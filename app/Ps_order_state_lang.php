<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ps_order_state_lang extends Model
{
    //
    protected $table = 'ps_order_state_lang';
    protected $primaryKey = 'id_order_state';
    protected $connection = 'vapez';
    public $timestamps = false;

    public function Ps_order_history()
    {
        return $this->belongsTo(Ps_order_history::class, 'id_order_state', 'id_order_state');
    }
}
