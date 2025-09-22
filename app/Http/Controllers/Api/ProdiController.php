<?php


namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\DosenSIPPResource;
use App\Models\Dosen;
use App\Models\ProgramStudi;
use App\Models\Mahasiswa;
use App\User;
use Illuminate\Http\Request;

class ProdiController extends Controller
{
   function index(){
		$data = ProgramStudi::select('kode_program_studi as kode','nama_program_studi','singkatan_program_studi')->get();
     	foreach($data as $key => $item){
        	$data[$key]->jum_mhs = Mahasiswa::where('program_studi_kode',$item->kode)->count();
        }
       return response()->json($data, 200);
   }
}