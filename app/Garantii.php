<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Garantii extends Model {
	//
	protected $table = 'garantii';
	public $timestamps = false;

	public function produse() {
		return $this->hasMany('App\Produse', 'id', 'id_garantie');
	}

}
