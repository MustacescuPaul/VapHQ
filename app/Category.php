<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    //
    protected $table = 'ps_category';
    protected $primaryKey = 'id_category';
    public function CategoryLang()
    {
        return $this->hasOne('App\CategoryLang', 'id_category', 'id_category');
    }

    // public function CategoryProduct()
    // {
    //     return $this->hasMany('App\Ps_product', 'id_category', 'id_category')->withPivot('ps_category_product')->orderBy('position', 'asc');;
    // }
    public function products()
    {
        return $this->belongsToMany('App\Product', 'ps_category_product', 'id_category', 'id_prod');
    }
    public function ps_product()
    {
        return $this->belongsToMany('App\Ps_product', 'ps_category_product', 'id_category', 'id_product')->withPivot('position')->orderBy('position', 'asc');
    }
}
