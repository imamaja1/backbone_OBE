<?php


namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\DosenSIPPResource;
use App\Models\Dosen;
use App\User;
use Illuminate\Http\Request;

class DosenController extends Controller
{
    public function get_all()
    {
        $respon = Dosen::all();
        return response()->json($respon, 200);
    }

    public function autocomplate(Request $request)
    {
        $keyword = $request->keyword;
        $res = Dosen::where('nama_dosen', 'like', '%' . $keyword . '%')->limit(10)->get();
        return response()->json($res);
    }

    public function one($kode_dosen)
    {
        $res = Dosen::where('kode_dosen', $kode_dosen)->get()->first();
        return response()->json($res, 200);
    }

    // untuk sync aplikasi SIPP
    public function sipp_user_all()
    {
        $response = Dosen::with('prodi')->get();
//        $response = Dosen::get('nama')->makeVisible(['password']);
        $response = DosenSIPPResource::collection(Dosen::all());
        return response()->json($response, 200);
    }
  	public function dosen_prodi($id)
    {
        $response = Dosen::where('homebase',$id)->where('status_login','A')->get();
        return response()->json($response, 200);
    }
}