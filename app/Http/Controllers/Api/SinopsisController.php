<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SinopsisController extends Controller
{
    public function cek_syarat($nim){
        $prodi = get_kode_prodi($nim);
        $sks = sks_ditemupuh($nim);
        $data['skripsi'] = skripsi_ta($nim);
        if ($prodi->id_jenjang == 1){
            $sks_must = 113;
        }else{
            $sks_must = 77;
        }

        $data['kkp'] = kkp($nim);
        $data['sks_syarat'] = $sks_must;
        $data['sks'] = $sks;
        $data['ipk'] = ipk($nim);
        $data['rti'] = rti($nim);
        $data['etika'] = etika_profesi($nim);

        return response()->json($data);
    }
}
