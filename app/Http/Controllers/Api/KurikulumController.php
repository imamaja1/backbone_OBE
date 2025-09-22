<?php


namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\DosenSIPPResource;
use App\Models\Dosen;
use App\Models\ProgramStudi;
use App\Models\Mahasiswa;
use App\Models\Kurikulum;
use App\Models\KurikulumAngkatan;
use App\Models\NamaKurikulum;
use App\User;
use Illuminate\Http\Request;

class KurikulumController extends Controller
{
   function index($id){
    	$NamaKurikulum = NamaKurikulum::where('kode_program_studi',$id)
          				->orderbyDesc('tanggal_terbuat')
          				->get()->first()->kode_nama_kurikulum;
     	
     	$kurikulum = Kurikulum::with('matakuliah')
          					->select('semester','id_matakuliah')
          					->where('kode_nama_kurikulum',$NamaKurikulum)
          					->get();
     
     	$data = array(); 
     	$semester = 0;
     	foreach ($kurikulum as $key => $value) {
          if($semester != $value->semester){
            $semester = $value->semester;
            $asu = 0;
            $data[$value->semester-1]['semester'] = $value->semester;
            $data[$value->semester-1]['data'][$asu] = $value->matakuliah;
		  }else{
            $asu++;
            $data[$value->semester-1]['data'][$asu] = $value->matakuliah;
          }	
            
        }
     
     	return response()->json($data, 200);	
   }
}