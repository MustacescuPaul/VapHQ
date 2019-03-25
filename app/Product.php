<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    //
    protected $table = 'produse';
    protected $primaryKey = 'id_prod';
    public $timestamps = false;


    public function category()
    {
        return $this->belongsToMany('App\Category', 'ps_category_product', 'id_category', 'id_prod');
    }
    public function image()
    {
        return $this->hasMany('App\Image', 'id_product', 'id_prod');
    }
}
