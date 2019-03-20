<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ps_category_product extends Model
{
    //
    protected $table = 'ps_category_product';
    protected $primaryKey = 'id_product';
    protected $connection = 'vapez';
    public $timestamps = false;


    public function ps_product()
    {
        return $this->belongsTo('App\Ps_product', 'id_product', 'id_product');
    }
}
