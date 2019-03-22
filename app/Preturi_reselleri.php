<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Preturi_reselleri extends Model
{
    //
    protected $table = 'preturi_reselleri';
    protected $primaryKey = 'id_produs';
    protected $connection = 'vaphq';

    public function ps_product()
    {
        return $this->belongsTo('App\Ps_product', 'id_produs', 'id_product');
    }
    public function Comanda()
    {
        return $this->belongsTo(Comanda::class, 'id_prod', 'id_produs');
    }
    public function cantitate_minima()
    {
        return $this->hasOne('App\Cantitate_minima', 'id_prod', 'id_produs');
    }
}
