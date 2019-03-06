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
}
