<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Dosen;
use App\Models\Mahasiswa;
use App\Models\Matakuliah;
use App\Models\ProgramStudi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ObeController extends Controller
{
    public function Mahasiswa()
    {
        $mahasiswa = Mahasiswa::whereRaw("CAST(SUBSTR(nim, 1, 2) AS UNSIGNED) > 24")
            ->select(
                'nim as npm',
                'nama_mahasiswa as nama',
                'email',
                'program_studi_kode as kode_prodi'
            )
            ->get();

        return response()->json(
            array(
                'status' => array(
                    'code' => 200,
                    'description' => "OK",
                    'pages_count' => 1
                ),
                'results' => $mahasiswa
            )
        );
    }
    public function Program_studi()
    {
        $data = ProgramStudi::join('jenjang', 'jenjang.id_jenjang', '=', 'program_studi.id_jenjang')
            ->select(
                'kode_program_studi as kode_prodi',
                'nama_program_studi as nama_prodi',
                'nama_jenjang as jenjang'
            )
            ->limit(10)->get();
        return response()->json(
            array(
                'status' => array(
                    'code' => 200,
                    'description' => "OK",
                    'pages_count' => 1
                ),
                'results' => $data
            )
        );
    }
    public function dosen()
    {
        $data = Dosen::join('program_studi', 'program_studi.kode_program_studi', '=', 'dosen.homebase')
            ->select(
                'nik',
                'nama_dosen as nama',
                'alamat_email as email',
                'singkatan_program_studi as home_base'
            )->get();
        return response()->json(
            array(
                'status' => array(
                    'code' => 200,
                    'description' => "OK",
                    'pages_count' => 1
                ),
                'results' => $data
            )
        );
    }
    public function matakuliah()
    {
        $data = Matakuliah::join('kurikulum', 'kurikulum.id_matakuliah', '=', 'matakuliah.id_matakuliah')
            ->select(
                'kode_program_studi as kode_prodi',
                'matakuliah.kode_matakuliah as kode_matkul',
                'nama_matakuliah as nama_matkul',
                DB::raw('(sks_teori + sks_praktek) as jumlah_sks_teori'),
                'sks_praktikum as jumlah_sks_praktik',
                DB::raw('0 as jumlah_pertemuan'),
                'semester as semester_matkul',
                DB::raw("
            CASE 
                WHEN jenis = 1 THEN 'Pilihan'
                WHEN jenis = 0 THEN 'Wajib'
                ELSE 'Tidak ada'
            END as jenis_matkul
        ")
            )->get();
        return response()->json(
            array(
                'status' => array(
                    'code' => 200,
                    'description' => "OK",
                    'pages_count' => 1
                ),
                'results' => $data
            )
        );
    }
}
