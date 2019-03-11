<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ps_order extends Model
{
    //
    protected $table = 'ps_order';
    protected $primaryKey = 'id_order';
    protected $connection = 'vapez';
}
