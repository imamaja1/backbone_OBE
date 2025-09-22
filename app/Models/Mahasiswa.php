<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mahasiswa extends Model
{
    protected $table = 'mahasiswa';
    protected $primaryKey = "nim";
    public $timestamps = false;

    protected $hidden = ['sandi'];

    public function program_studi(){
        return $this->hasOne(ProgramStudi::class,'kode_program_studi','program_studi_kode');
    }
}
