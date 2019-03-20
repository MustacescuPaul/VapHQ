<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ps_product extends Model
{
    //
    protected $table = 'ps_product';
    protected $primaryKey = 'id_product';
    protected $connection = 'vapez';
    public $timestamps = false;


    public function category()
    {
        return $this->belongsToMany('App\Category', 'ps_category_product', 'id_category', 'id_product')->withPivot('position')->orderBy('position', 'asc');
    }
    public function image()
    {
        return $this->hasMany('App\Image', 'id_product', 'id_product');
    }
    public function ps_product_lang()
    {
        return $this->hasMany('App\Ps_product_lang', 'id_product', 'id_product');
    }
    public function preturi_reselleri()
    {
        return $this->hasOne('App\Preturi_reselleri', 'id_product', 'id_produs');
    }
    public function comanda()
    {
        return $this->belongsTo('App\Comanda', 'id_prod', 'id_product');
    }
    // public function ps_category_product()
    // {
    //     return $this->hasOne('App\Ps_category_product', 'id_product', 'id_product');
    // }
}
