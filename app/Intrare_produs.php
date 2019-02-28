<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Intrare_produs extends Model
{
    //
    protected $connection = "garantii";
    public $timestamps = false;
    protected $table = 'intrari_produse';
}
