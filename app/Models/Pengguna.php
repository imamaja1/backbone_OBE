<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pengguna extends Model
{
    protected $table = 'pengguna';
    public $timestamps = false;

    protected $hidden = ['sandi_pengguna'];
}
