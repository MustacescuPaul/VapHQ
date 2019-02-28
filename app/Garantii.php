<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Garantii extends Model
{
    //
    protected $table = 'garantii';
    public $timestamps = false;
    protected $connection = 'garantii';
    public function produse()
    {
        return $this->hasMany('App\Produse', 'id', 'id_garantie');
    }
}
