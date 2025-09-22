<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\User;

class Dosen extends Model
{
    protected $table = 'dosen';
    protected $primaryKey = 'kode_dosen';
    public $timestamps = false;

    protected $hidden = ['sandi_pengguna'];
  
      public function prodi()
    {
        return $this->belongsTo(ProgramStudi::class, 'homebase', 'kode_program_studi');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'kode_dosen', 'key_ref');
    }
}
