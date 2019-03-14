<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Users_vapoint extends Model
{
    public $timestamps = false;
    protected $table = 'users_vapoint';
    protected $primaryKey = 'id_vapoint';

    public function user()
    {
        return $this->belongsTo(User::class, 'id_vapoint', 'id_vapoint');
    }
}
