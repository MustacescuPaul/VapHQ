<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Detaliu_bonuri extends Model {
	//
	protected $primaryKey = 'id_detaliu';
	public $timestamps = false;
	protected $table = 'detaliu_bonuri';

	public function bon() {
		return $this->belongsTo('App\Bon', 'id_bon', 'id_bon');
	}
}
