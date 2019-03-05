<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Users_permisiuni extends Model
{
    //
    public $timestamps = false;
    protected $table = 'users_permisiuni';
    protected $primaryKey = 'id';
    protected $connection = 'vaphq';

    public function Users()
    {
        return $this->belongsTo('App\Users', 'id', 'id');
    }
}
