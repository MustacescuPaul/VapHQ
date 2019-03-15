<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ps_order_detail extends Model
{
    //
    protected $table = 'ps_order_detail';
    protected $primaryKey = 'id_order_detail';
    protected $connection = 'vapez';
    public $timestamps = false;


    public function ps_order()
    {
        return $this->belongsTo('App\Ps_order_detail', 'id_order', 'id_order');
    }
    public function Preturi_reselleri()
    {
        return $this->hasOne(Preturi_reselleri::class, 'id_produs', 'product_id');
    }
}
