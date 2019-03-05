<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ps_product_lang extends Model
{
    //
    protected $table = 'ps_product_lang';
    protected $primaryKey = 'id_product';
    protected $connection = 'vapez';

    public function ps_product()
    {
        return $this->belongsTo('App\Ps_product', 'id_product', 'id_product');
    }
}
