<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProgramStudi extends Model
{
    protected $table = "program_studi";
    protected $primaryKey = "kode_program_studi";
    public $timestamps = false;
  
  	public function dosen()
    {
        return $this->hasOne(Dosen::class, 'kode_program_studi', 'homebase');
    }

    public function fakultas()
    {
        return $this->hasOne(Fakultas::class, 'kode_fakultas', 'kode_fakultas');
    }
  	public function jum_mahasiswa()
    {
        return $this->hasMany(Mahasiswa::class, 'program_studi_kode', 'kode_program_studi')->select('program_studi_kode','nama_mahasiswa');
    }
}
