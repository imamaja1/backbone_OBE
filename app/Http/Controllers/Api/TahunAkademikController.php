<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TahunAkademik;
use Illuminate\Http\Request;

class TahunAkademikController extends Controller
{
    public function index(){
        $tahun_akademik = TahunAkademik::all();
        return response()->json($tahun_akademik);
    }
}
