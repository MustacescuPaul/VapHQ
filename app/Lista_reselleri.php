<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Lista_reselleri extends Model
{
    //
    public $timestamps = false;
    protected $table = 'lista_reselleri';
    protected $primaryKey = 'id';
    protected $connection = 'vaphq';

    public function Users()
    {
        return $this->belongsToMany('App\Users', 'id_client', 'id_vapoint');
    }
}
