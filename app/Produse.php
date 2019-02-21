<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Produse extends Model {
	//
	public $timestamps = false;
	protected $table = 'produse';
	protected $primaryKey = 'id';

	public function garantii() {
		return $this->belongsTo('App\Garantii', 'id_garantie', 'id');
	}

}
