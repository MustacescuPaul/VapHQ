<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comanda extends Model
{
    public $timestamps = false;
    protected $table = 'comanda';
    protected $primaryKey = 'id_prod';

    public function Preturi_reselleri()
    {
        return $this->hasOne(Preturi_reselleri::class, 'id_produs', 'id_prod');
    }
    public function Ps_stock_available()
    {
        return $this->hasOne(Ps_stock_available::class, 'id_product', 'id_prod');
    }
    public function ps_product()
    {
        return $this->hasOne('App\Ps_product', 'id_product', 'id_prod');
    }
}
