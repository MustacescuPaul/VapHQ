<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ps_stock_available extends Model
{
    //
    protected $table = 'ps_stock_available';
    protected $primaryKey = 'id_product';
    protected $connection = 'vapez';
}
