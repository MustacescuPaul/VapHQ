<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cantitate_minima extends Model
{
    //
    protected $table = 'cantitate_minima';
    protected $primaryKey = 'id_prod';
    protected $connection = 'vaphq';

    public function preturi_reselleri()
    {
        return $this->belongsTo('App\Preturi_reselleri', 'id_produs', 'id_prod');
    }
    public function Comanda()
    {
        return $this->belongsTo(Comanda::class, 'id_prod', 'id_produs');
    }
}
