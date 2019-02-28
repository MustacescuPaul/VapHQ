<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    //
    protected $table = 'ps_image';
    protected $primaryKey = 'id_image';
    protected $connection = 'vapez';
    public function Product()
    {
        return $this->belongsTo('App\Product', 'id_product', 'id_prod');
    }
}
