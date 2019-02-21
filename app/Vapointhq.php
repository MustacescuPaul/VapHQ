<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Vapointhq extends Model {
	//
	protected $connection = 'mysql';
	protected $table = 'vapoints';
	public $timestamps = false;

}
