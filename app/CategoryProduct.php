<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CategoryProduct extends Model
{
    //
    protected $table = 'ps_category_product';
    protected $primaryKey = 'id_category';
    protected $connection = 'vapez';
    public $timestamps = false;
    //
    // public function Category()
    // {
    // 	return $this->belongsTo('App\Category','id_category','id_category');
    // }
}
