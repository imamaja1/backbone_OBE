<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Mahasiswa;
use Illuminate\Http\Request;

class MahasiswaController extends Controller
{
    public function autocomplate(Request $request){
        $keyword = $request->keyword;
        $res = Mahasiswa::where('nama_mahasiswa','like','%'.$keyword.'%')
            ->orWhere('nim', 'like','%'.$keyword.'%')
            ->limit(10)->get();
        return response()->json($res);
    }
  
     public function cari(Request $request)
    {
        $keyword = $request->keyword;
        $res = Mahasiswa::where('status', 'A')
            ->where('nama_mahasiswa', 'like', '%' . $keyword . '%')
            ->orWhere('nim', 'like', '%' . $keyword . '%')
            ->limit(10)->get(['nim', 'nama_mahasiswa']);
        return response()->json([
            'status' => true,
            'data' => $res
        ]);
    }
    public function ambil(Request $request)
    {
        $keyword = $request->keyword;
        $res = Mahasiswa::with('program_studi')->where('nim',  $keyword)->first();
        return response()->json([
            'status' => true,
            'data' => [
                'nim' => $res->nim,
                'nama_mahasiswa' => $res->nama_mahasiswa,
                'prodi' => $res->program_studi->nama_program_studi
            ]
        ]);
    }

    public function mahasiswa_skripsi(Request $request)
    {
        $data = mahasiswa_skripsi_ta($request->keyword);
        return response()->json($data);
    }

    public function getAlumni($ta_lulus)
    {
        $kolom = ['nim', 'nama_mahasiswa', 'program_studi_kode', 'tempat_lahir', 'tanggal_lahir', 'alamat', 'ta_lulus'];
        // $data = Mahasiswa::where('ta_lulus', '=', $ta_lulus)->get($kolom);
        $data = Mahasiswa::where('status', '=', $ta_lulus)->get($kolom);
        return response()->json($data);
    }
}
