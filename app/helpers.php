<?php
use Illuminate\Support\Facades\DB;

function ipk($nim){
    $sub = DB::table('krs')
        ->select(DB::raw('semester,nim,status,kd.id_matakuliah,nilai_harian,nilai_uts,nilai_akhir,nilai_uas, (sks_teori+sks_praktek+sks_praktikum) as sks, mak.kode_matakuliah'))
        ->join('krs_detail as kd', 'krs.kode_krs', '=', 'kd.kode_krs')
        ->join('khs_detail as khd', 'kd.kode_krs_detail', '=', 'khd.kode_krs_detail')
        ->join('matakuliah as mak', 'mak.id_matakuliah', '=', 'kd.id_matakuliah')
        ->where('nim', $nim)
        ->whereNotNull('nilai_akhir')
        ->orderBy('nilai_akhir', 'DESC');
    $data = DB::table(DB::raw("({$sub->toSql()}) as sub"))
        ->mergeBindings($sub) // you need to get underlying Query Builder
        ->groupBy('id_matakuliah')
        ->get();
    $sksn = 0;
    $total_sks = 0;
    foreach ($data as $row) {
        $sistem_penilaian = sistem_penilaian($nim, $row->semester);
        $total_sks = $total_sks + $row->sks;
        foreach ($sistem_penilaian as $item) {
            if ($row->nilai_akhir >= $item->nilai_minimum && $item->nilai_maksimum >= $row->nilai_akhir) {
                $sksn = $sksn + ($item->bobot_nilai * $row->sks);
            } else {
                $sksn = $sksn + 0;
            }
        }
    }
    if ($sksn == 0) {
        $ipk = 0;
    } else {
        $ipk = $sksn / $total_sks;
    }
    return number_format($ipk, 2);
}

function rti($nim)
{
    $id_rti = get_kode_rti();
    $sub = DB::table('krs')
        ->select(DB::raw('semester,nim,nama_matakuliah,status,kd.id_matakuliah,nilai_harian,nilai_uts,nilai_akhir,nilai_uas, (sks_teori+sks_praktek+sks_praktikum) as sks, mak.kode_matakuliah'))
        ->join('krs_detail as kd', 'krs.kode_krs', '=', 'kd.kode_krs')
        ->join('khs_detail as khd', 'kd.kode_krs_detail', '=', 'khd.kode_krs_detail')
        ->join('matakuliah as mak', 'mak.id_matakuliah', '=', 'kd.id_matakuliah')
        ->where('nim', $nim)
        ->whereIn('mak.id_matakuliah', $id_rti)
        ->whereNotNull('nilai_akhir')
        ->orderBy('nilai_akhir', 'DESC');
    $data = DB::table(DB::raw("({$sub->toSql()}) as sub"))
        ->mergeBindings($sub) // you need to get underlying Query Builder
        ->groupBy('id_matakuliah')
        ->first();
    if ($data) {
        $sistem_penilaian = sistem_penilaian($nim, $data->semester);
        $res['sks'] = $data->sks;
        $res['nama_matakuliah'] = $data->nama_matakuliah;
        foreach ($sistem_penilaian as $item) {
            if ($data->nilai_akhir >= $item->nilai_minimum && $item->nilai_maksimum >= $data->nilai_akhir) {
                $res['sksn'] = $item->bobot_nilai * $data->sks;
                $res['grade'] = $item->grade;
                $res['ket'] = $item->keterangan;
            }
        }
        if ($res['ket'] == 'Lulus') {
            $result['status'] = true;
            $result['msg'] = $res['nama_matakuliah'] . " dengan grade " . $res['grade'];
        } else {
            $result['status'] = false;
            $result['msg'] = $res['nama_matakuliah'] . " dengan grade " . $res['grade'];
        }
        return $result;
    } else {
        $result['status'] = false;
        $result['msg'] = "Anda belum memenuhi";
        return $result;
    }
}

function etika_profesi($nim)
{
    $id_etika = get_kode_etika();
    $sub = DB::table('krs')
        ->select(DB::raw('semester,nim,nama_matakuliah,status,kd.id_matakuliah,nilai_harian,nilai_uts,nilai_akhir,nilai_uas, (sks_teori+sks_praktek+sks_praktikum) as sks, mak.kode_matakuliah'))
        ->join('krs_detail as kd', 'krs.kode_krs', '=', 'kd.kode_krs')
        ->join('khs_detail as khd', 'kd.kode_krs_detail', '=', 'khd.kode_krs_detail')
        ->join('matakuliah as mak', 'mak.id_matakuliah', '=', 'kd.id_matakuliah')
        ->where('nim', $nim)
        ->whereIn('mak.id_matakuliah', $id_etika)
        ->whereNotNull('nilai_akhir')
        ->orderBy('nilai_akhir', 'DESC');
    $data = DB::table(DB::raw("({$sub->toSql()}) as sub"))
        ->mergeBindings($sub) // you need to get underlying Query Builder
        ->groupBy('id_matakuliah')
        ->first();
    if ($data) {
        $sistem_penilaian = sistem_penilaian($nim, $data->semester);
        $res['sks'] = $data->sks;
        $res['nama_matakuliah'] = $data->nama_matakuliah;
        foreach ($sistem_penilaian as $item) {
            if ($data->nilai_akhir >= $item->nilai_minimum && $item->nilai_maksimum >= $data->nilai_akhir) {
                $res['sksn'] = $item->bobot_nilai * $data->sks;
                $res['grade'] = $item->grade;
                $res['ket'] = $item->keterangan;
            }
        }
        if ($res['ket'] == 'Lulus') {
            $result['status'] = true;
            $result['msg'] = $res['nama_matakuliah'] . " dengan grade " . $res['grade'];
        } else {
            $result['status'] = false;
            $result['msg'] = $res['nama_matakuliah'] . " dengan grade " . $res['grade'];

        }
        return $result;
    } else {
        $result['status'] = false;
        $result['msg'] = "Anda belum memenuhi";
        return $result;
    }
}

function kkp($nim)
{
    $kode_kkp = get_kode_matakuliah_kkp();
    $sub = DB::table('krs')
        ->select(DB::raw('semester,nim,nama_matakuliah,status,kd.id_matakuliah,nilai_harian,nilai_uts,nilai_akhir,nilai_uas, (sks_teori+sks_praktek+sks_praktikum) as sks, mak.kode_matakuliah'))
        ->join('krs_detail as kd', 'krs.kode_krs', '=', 'kd.kode_krs')
        ->join('khs_detail as khd', 'kd.kode_krs_detail', '=', 'khd.kode_krs_detail')
        ->join('matakuliah as mak', 'mak.id_matakuliah', '=', 'kd.id_matakuliah')
        ->where('nim', $nim)
        ->whereIn('mak.kode_matakuliah', $kode_kkp)
        ->whereNotNull('nilai_akhir')
        ->orderBy('nilai_akhir', 'DESC');
    $data = DB::table(DB::raw("({$sub->toSql()}) as sub"))
        ->mergeBindings($sub) // you need to get underlying Query Builder
        ->groupBy('id_matakuliah')
        ->first();
    if ($data) {
        $sistem_penilaian = sistem_penilaian($nim, $data->semester);
        $res['sks'] = $data->sks;
        $res['nama_matakuliah'] = $data->nama_matakuliah;
        foreach ($sistem_penilaian as $item) {
            if ($data->nilai_akhir >= $item->nilai_minimum && $item->nilai_maksimum >= $data->nilai_akhir) {
                $res['sksn'] = $item->bobot_nilai * $data->sks;
                $res['grade'] = $item->grade;
                $res['ket'] = $item->keterangan;
            }
        }
        if ($res['ket'] == 'Lulus') {
            $result['status'] = true;
            $result['msg'] = $res['nama_matakuliah'] . " dengan grade " . $res['grade'];
        } else {
            $result['status'] = false;
            $result['msg'] = $res['nama_matakuliah'] . " dengan grade " . $res['grade'];

        }
        return $result;
    } else {
        $result['status'] = false;
        $result['msg'] = "-";
        return $result;
    }
}

function skripsi_ta($nim)
{
    $kode_skripsi = get_kode_matakuliah_skripsi();
    $query = DB::table('krs')
        ->join('krs_detail as kd', 'krs.kode_krs', '=', 'kd.kode_krs')
        ->join('matakuliah as mak', 'mak.id_matakuliah', '=', 'kd.id_matakuliah')
        ->where('nim', $nim)
        ->where('kode_tahun_akademik', tahun_akademik_aktif()->kode_tahun_akademik)
        ->whereIn('mak.kode_matakuliah', $kode_skripsi)
        ->get();
    if (count($query) > 0) {
        return true;
    } else {
        return false;
    }
}

function sks_ditemupuh($nim)
{
    $sub = DB::table('krs')
        ->select(DB::raw('semester,nim,status,kd.id_matakuliah,nilai_harian,nilai_uts,nilai_akhir,nilai_uas, (sks_teori+sks_praktek+sks_praktikum) as sks, mak.kode_matakuliah'))
        ->join('krs_detail as kd', 'krs.kode_krs', '=', 'kd.kode_krs')
        ->join('khs_detail as khd', 'kd.kode_krs_detail', '=', 'khd.kode_krs_detail')
        ->join('matakuliah as mak', 'mak.id_matakuliah', '=', 'kd.id_matakuliah')
        ->where('nim', $nim)
        ->orderBy('nilai_akhir', 'DESC');
    $data = DB::table(DB::raw("({$sub->toSql()}) as sub"))
        ->mergeBindings($sub) // you need to get underlying Query Builder
        ->groupBy('id_matakuliah')
        ->get();
    $total_sks = 0;
    foreach ($data as $row) {
        $total_sks = $total_sks + $row->sks;
    }
    return $total_sks;
}

function sistem_penilaian($nim)
{
    $kode_nama_kurikulum = kode_nama_kurikulum($nim);

    $penilaian = DB::table('sistem_penilaian as sp')
        ->join('nama_kurikulum as nk', 'nk.kode_nama_kurikulum', '=', 'sp.kode_nama_kurikulum')
        ->join('sistem_penilaian_detail as spd', 'sp.kode_sistem_penilaian', '=', 'spd.kode_sistem_penilaian')
        ->where('nk.kode_nama_kurikulum', $kode_nama_kurikulum)
        ->get();

    return $penilaian;
}

function semester($nim)
{
    $tahun_angkatan = substr($nim, 0, 2);
    $tahun = DB::table('tahun_akademik')
        ->select(DB::raw('substr(tahun_akademik,3,2) as tahun_akademik, semester'))
        ->where('status', 'A')
        ->first();
    $sem = $tahun->semester;
    $tahun_akademik = $tahun->tahun_akademik;

    if ($sem == 0) {
        $semester = ($tahun_akademik - $tahun_angkatan) * 2 + 2;
    } else {
        $semester = ($tahun_akademik - $tahun_angkatan) * 2 + 1;
    }

    return $semester;
}

function tahun_akademik_aktif()
{
    return DB::table('tahun_akademik')
        ->select('*', DB::raw('substring(tahun_akademik,3,2) as angkatan'))
        ->where('status', 'A')
        ->first();
}

function get_kode_prodi($nim)
{
    $mah = DB::table('mahasiswa')->where('nim', $nim)->get(['program_studi_kode'])->first();
    $prodi = DB::table('program_studi as ps')
        ->select('*', DB::raw('fk.kode_fakultas'))
        ->join('fakultas as fk', 'ps.kode_fakultas', '=', 'fk.kode_fakultas')
        ->where('ps.kode_program_studi', $mah->program_studi_kode)
        ->get()->first();

    return $prodi;
}

function kode_nama_kurikulum($nim)
{
    $ex = substr($nim, 6, 1);
    $gelombang = substr($nim, 5, 1);
    $angkatan = substr($nim, 0, 2);
    $prodi = get_kode_prodi($nim);
    if ($angkatan < 19) {
        if ($gelombang == '5') {
            $query = DB::table('nama_kurikulum as nk')
                ->select('*', DB::raw('nk.kode_nama_kurikulum'))
                ->join('kurikulum_angkatan as ka', 'nk.kode_nama_kurikulum', '=', 'ka.kode_nama_kurikulum')
                ->where(DB::raw('substr(angkatan,3,2)'), $angkatan)
                ->where('kode_program_studi', $prodi->kode_program_studi)
                ->where('ekstensi', 'Y')
                ->get()->first();
        } else {
            $query = DB::table('nama_kurikulum as nk')
                ->select('*', DB::raw('nk.kode_nama_kurikulum'))
                ->join('kurikulum_angkatan as ka', 'nk.kode_nama_kurikulum', '=', 'ka.kode_nama_kurikulum')
                ->where(DB::raw('substr(angkatan,3,2)'), $angkatan)
                ->where('kode_program_studi', $prodi->kode_program_studi)
                ->where('ekstensi', 'N')
                ->get()->first();
        }
    } else {
        if ($ex == '1') {
            $query = DB::table('nama_kurikulum as nk')
                ->select('*', DB::raw('nk.kode_nama_kurikulum'))
                ->join('kurikulum_angkatan as ka', 'nk.kode_nama_kurikulum', '=', 'ka.kode_nama_kurikulum')
                ->where(DB::raw('substr(angkatan,3,2)'), $angkatan)
                ->where('kode_program_studi', $prodi->kode_program_studi)
                ->where('ekstensi', 'Y')
                ->get()->first();
        } else {
            $query = DB::table('nama_kurikulum as nk')
                ->select('*', DB::raw('nk.kode_nama_kurikulum'))
                ->join('kurikulum_angkatan as ka', 'nk.kode_nama_kurikulum', '=', 'ka.kode_nama_kurikulum')
                ->where(DB::raw('substr(angkatan,3,2)'), $angkatan)
                ->where('kode_program_studi', $prodi->kode_program_studi)
                ->where('ekstensi', 'N')
                ->get()->first();
        }
    }

    return $query->kode_nama_kurikulum;
}

function nama_kurikulum($nim)
{
    $ex = substr($nim, 6, 1);
    $gelombang = substr($nim, 5, 1);
    $angkatan = substr($nim, 0, 2);
    $prodi = get_kode_prodi($nim);
    if ($angkatan < 19) {
        if ($gelombang == '5') {
            $query = DB::table('nama_kurikulum as nk')
                ->select('*', DB::raw('nk.kode_nama_kurikulum'))
                ->join('kurikulum_angkatan as ka', 'nk.kode_nama_kurikulum', '=', 'ka.kode_nama_kurikulum')
                ->where(DB::raw('substr(angkatan,3,2)'), $angkatan)
                ->where('kode_program_studi', $prodi->kode_program_studi)
                ->where('ekstensi', 'Y')
                ->get()->first();
        } else {
            $query = DB::table('nama_kurikulum as nk')
                ->select('*', DB::raw('nk.kode_nama_kurikulum'))
                ->join('kurikulum_angkatan as ka', 'nk.kode_nama_kurikulum', '=', 'ka.kode_nama_kurikulum')
                ->where(DB::raw('substr(angkatan,3,2)'), $angkatan)
                ->where('kode_program_studi', $prodi->kode_program_studi)
                ->where('ekstensi', 'N')
                ->get()->first();
        }
    } else {
        if ($ex == '1') {
            $query = DB::table('nama_kurikulum as nk')
                ->select('*', DB::raw('nk.kode_nama_kurikulum'))
                ->join('kurikulum_angkatan as ka', 'nk.kode_nama_kurikulum', '=', 'ka.kode_nama_kurikulum')
                ->where(DB::raw('substr(angkatan,3,2)'), $angkatan)
                ->where('kode_program_studi', $prodi->kode_program_studi)
                ->where('ekstensi', 'Y')
                ->get()->first();
        } else {
            $query = DB::table('nama_kurikulum as nk')
                ->select('*', DB::raw('nk.kode_nama_kurikulum'))
                ->join('kurikulum_angkatan as ka', 'nk.kode_nama_kurikulum', '=', 'ka.kode_nama_kurikulum')
                ->where(DB::raw('substr(angkatan,3,2)'), $angkatan)
                ->where('kode_program_studi', $prodi->kode_program_studi)
                ->where('ekstensi', 'N')
                ->get()->first();
        }
    }

    return $query;
}

function stup_grade($kode_kurikulum_angkatan, $semester = null)
{
    if ($semester == null) {
        return false;
    } else {
        $stup = DB::table('nama_kurikulum as nk')
            ->join('stup_grade as sg', 'nk.kode_nama_kurikulum', '=', 'sg.kode_nama_kurikulum')
            ->join('kurikulum_angkatan as ka', 'ka.kode_nama_kurikulum', '=', 'nk.kode_nama_kurikulum')
            ->where('kode_kurikulum_angkatan', $kode_kurikulum_angkatan)
            ->where(DB::raw('ka.semester_stup_grade'), ' <= ', $semester)
            ->get();
        if (count($stup) > 0) {
            return $stup;
        } else {
            return false;
        }
    }
}

function get_matakuliah($id_matakuliah)
{
    $query = DB::table('matakuliah')
        ->where('id_matakuliah', $id_matakuliah)
        ->get()->first();
    return $query;
}

function data_penilaian($nim, $semester)
{
    $kode_kurikulum_angkatan = nama_kurikulum($nim)->kode_kurikulum_angkatan;
    if (stup_grade($kode_kurikulum_angkatan, $semester)) {
        $data_penilaian = stup_grade($kode_kurikulum_angkatan, $semester);
    } else {
        $data_penilaian = sistem_penilaian($nim);
    }

    return $data_penilaian;
}

function get_kode_matakuliah_kkp_skripsi(){
    $query = DB::table('kurikulum as kur')
        ->select('mak.kode_matakuliah', 'nama_matakuliah')
        ->join('matakuliah as mak', 'kur.id_matakuliah', '=', 'mak.id_matakuliah')
        ->where('nama_matakuliah', 'like', '%kuliah kerja profesi%')
        ->orWhere('nama_matakuliah', 'like', '%magang%')
        ->orWhere('nama_matakuliah', 'like', '%skripsi%')
        ->orWhere('nama_matakuliah', 'like', '%tugas akhir%')
        ->orWhere('nama_matakuliah', 'like', '%KKP%')
        ->orWhere('nama_matakuliah', 'like', '%Kuliah Kerja Praktek/Magang%')
        ->groupBy('mak.kode_matakuliah')
        ->get();
    foreach ($query as $row) {
        $data[] = $row->kode_matakuliah;
    }
    return $data;
}

function available_kompetensi($nim){
    $prodi = get_kode_prodi($nim);
    if ($prodi->kompetensi == 'Y') {
        return true;
    } else {
        return false;
    }
}

function available_extensi($nim){
    $angkatan = substr($nim, 0, 2);
    $old_kompetensi = substr($nim, 5, 1);
    $new_kompetensi = substr($nim, 6, 1);

    if ($angkatan < 19) {
        if ($old_kompetensi == '5') {
            return true;
        } else {
            return false;
        }
    } else {
        if ($new_kompetensi == '1') {
            return true;
        } else {
            return false;
        }
    }
}

function get_kode_matakuliah_skripsi()
{
    $query = DB::table('kurikulum as kur')
        ->select('mak.kode_matakuliah')
        ->join('matakuliah as mak', 'kur.id_matakuliah', '=', 'mak.id_matakuliah')
        ->where('nama_matakuliah', 'like', '%skripsi%')
        ->orWhere('nama_matakuliah', 'like', '%tugas akhir%')
        ->orWhere('nama_matakuliah', 'like', '%tugas ahir%')
        ->groupBy('mak.kode_matakuliah')
        ->get();
    foreach ($query as $row) {
        $data[] = $row->kode_matakuliah;
    }
    return $data;
}

function get_kode_matakuliah_kkp()
{
    $query = DB::table('kurikulum as kur')
        ->select('mak.kode_matakuliah')
        ->join('matakuliah as mak', 'kur.id_matakuliah','=','mak.id_matakuliah')
        ->where('nama_matakuliah', 'like','%kuliah kerja profesi%')
        ->orWhere('nama_matakuliah', 'like','%magang%')
        ->orWhere('nama_matakuliah', 'like','%KKP%')
        ->groupBy('mak.kode_matakuliah')
        ->get();
    foreach ($query as $row) {
        $data[] = $row->kode_matakuliah;
    }
    return $data;
}

function get_kode_etika()
{
    $query = DB::table('kurikulum as kur')
        ->select('mak.id_matakuliah')
        ->join('matakuliah as mak', 'kur.id_matakuliah','=','mak.id_matakuliah')
        ->where('nama_matakuliah', 'like','%Hukum & Etika Profesi%')
        ->orWhere('nama_matakuliah', 'like','%Etika Profesi%')
        ->orWhere('nama_matakuliah', 'like','%Hukum Etika Profesi%')
        ->groupBy('mak.kode_matakuliah')
        ->get();
    foreach ($query as $row) {
        $data[] = $row->id_matakuliah;
    }
    return $data;
}

function get_kode_rti()
{
    $query = DB::table('kurikulum as kur')
        ->select('mak.id_matakuliah')
        ->join('matakuliah as mak', 'kur.id_matakuliah','=','mak.id_matakuliah')
        ->where('nama_matakuliah', 'like','%Riset Teknologi Informasi%')
        ->groupBy('mak.kode_matakuliah')
        ->get();
    foreach ($query as $row) {
        $data[] = $row->id_matakuliah;
    }
    return $data;
}
