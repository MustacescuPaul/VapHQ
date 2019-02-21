<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Bon extends Model {
	//
	protected $table = 'bonuri';
	protected $primaryKey = 'id_bon';
	public $timestamps = false;

	public function detaliu_bonuri() {
		return $this->hasMany('App\Detaliu_bonuri', 'id_bon', 'id_bon');
	}

}
