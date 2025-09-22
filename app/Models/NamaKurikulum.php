<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\User;

class NamaKurikulum extends Model
{
    protected $table = 'nama_kurikulum';
    public $timestamps = false;
  	
  	public function kurikulum_angkatan()
    {
        return $this->hasOne(KurikulumAngkatan::class, 'kode_nama_kurikulum', 'kode_nama_kurikulum');
    }
}
