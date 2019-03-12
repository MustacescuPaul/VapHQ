<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ps_stock_available extends Model
{
    //
    protected $table = 'ps_stock_available';
    protected $primaryKey = 'id_product';
    protected $connection = 'vapez';
    public $timestamps = false;
    public function Comanda()
    {
        return $this->belongsTo(Comanda::class, 'id_prod', 'id_product');
    }
}
