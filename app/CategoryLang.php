<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CategoryLang extends Model
{
    //
    protected $table = 'ps_category_lang';
     protected $primaryKey = 'id_category';

    public function Category()
    {
    	return $this->belongsTo('App\Category','id_category','id_category');
    }
}
