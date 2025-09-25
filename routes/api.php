<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});
Route::group([
    'prefix' => 'v1/auth'
], function () {
    Route::post('login', 'Api\AuthController@login');
    Route::post('signup', 'Api\AuthController@signup');

    Route::group([
        'middleware' => ['auth:api', 'role-api:1|2|3']
    ], function () {
        Route::get('logout', 'Api\AuthController@logout');
        Route::get('user', 'Api\AuthController@user');
    });

});

Route::group(['prefix' => 'v1'], function () {
    Route::group(['middleware' => ['throttle', 'auth:api', 'role-api:2|1']], function () {
        Route::get('cek-syarat-sinopsis/{nim}', 'Api\SinopsisController@cek_syarat');
        //        Route::post('mahasiswa/autocomplate', 'Api\MahasiswaController@autocomplate');
        //        Route::post('dosen/autocomplate', 'Api\DosenController@autocomplate');
        //        Route::get('dosen/get-all', 'Api\DosenController@get_all');
        //        Route::get('dosen/{id}/one', 'Api\DosenController@one');
        //        Route::get('program-studi/get-all', 'Api\ProgramStudiController@index');

        Route::get('tahun-akademik', 'Api\TahunAkademikController@index');
    });

    Route::group(['middleware' => ['throttle']], function () {
        Route::get('prodi/all', 'Api\ProdiController@index');

        Route::get('kurikulum/{id}', 'Api\KurikulumController@index');
        Route::get('obe-mahasiswa', 'Api\ObeController@Mahasiswa');
        Route::get('obe-mahasiswa/{tahun_akademik}', 'Api\ObeController@MahasiswaByTahunAkademik');
        Route::get('obe-program-studi', 'Api\ObeController@Program_studi');
        Route::get('obe-dosen', 'Api\ObeController@Dosen');
        Route::get('obe-matakuliah', 'Api\ObeController@matakuliah');
        Route::get('obe-mengajar', 'Api\ObeController@mengajar');
        Route::get('obe-kelas-mahasiswa', 'Api\ObeController@kelas_mahasiswa');


        Route::get('dosen/dosen_prodi/{id}', 'Api\DosenController@dosen_prodi');
        Route::post('dosen/autocomplate', 'Api\DosenController@autocomplate');
        Route::get('dosen/get-all', 'Api\DosenController@get_all');
        Route::get('dosen/{id}/one', 'Api\DosenController@one');
        Route::post('mahasiswa/autocomplate', 'Api\MahasiswaController@autocomplate');

        Route::get('program-studi/get-all', 'Api\ProgramStudiController@index');

        // untuk apliakasi SIPP
        Route::get('dosen/sipp/get-all', 'Api\DosenController@sipp_user_all');
        Route::post('mahasiswa/cari', 'Api\MahasiswaController@cari');
        Route::post('mahasiswa/ambil', 'Api\MahasiswaController@ambil');

        //  untuk web alumni
        Route::get('mahasiswa/alumni/{ta_lulus}', 'Api\MahasiswaController@getAlumni');
        Route::get('tahun-akademik', 'Api\TahunAkademikController@index');
    });
});

Route::group(['middleware' => ['cek-point']], function () {
    Route::get('tahun-akademik-cek', 'Api\TahunAkademikController@index');
    // Route::get('mahasiswa_skripsi2', 'Api\MahasiswaController@mahasiswa_skripsi');
});

