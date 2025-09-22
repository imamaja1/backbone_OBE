<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\User;

class Kurikulum extends Model
{
    protected $table = 'kurikulum';
    protected $primaryKey = 'kode_kurikulum';
    public $timestamps = false;
  
  	public function matakuliah()
    {
        return $this->hasOne(Matakuliah::class, 'id_matakuliah', 'id_matakuliah')->select('id_matakuliah','kode_matakuliah','nama_matakuliah','sks_teori','sks_praktek','sks_praktikum');
    }
}
