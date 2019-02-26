<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TagId extends Model {
	//
	public $timestamps = false;
	protected $table = 'tagid';
	protected $primaryKey = 'id_tag';
	protected $connection = 'vaphq';
}
