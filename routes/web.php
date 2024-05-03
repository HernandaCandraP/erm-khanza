<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });
// Route::get('/', 'TextMiningController@index')->name('welcome');
Route::get('/', 'LoginController@index')->name('welcome');

// Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/404', function () {
    return view('404');
});

// fallback roue when not correct link
Route::fallback(function () {
    return view('404');
});

// login
Route::get('/login', 'LoginController@index')->name('login');
Route::post('/postlogin', 'LoginController@postlogin')->name('postlogin');
Route::post('/logout', 'LoginController@logout')->name('logout');

//admin
Route::group(['middleware' => ['auth:pegawai', 'is_admin']], function () {
    Route::get('/admin', 'PegawaiController@admin')->name('homeadmin');
    Route::get('/admin/profile', 'PegawaiController@profile')->name('homeadmin/profile');
    Route::post('/admin/profile/password', 'PegawaiController@gantipassword')->name('homeadmin/profile/password');

    // admin/guru
    Route::get('/admin/guru', 'GuruController@tampilguru')->name('homeadmin/guru');
    Route::post('/admin/guru/tambah', 'GuruController@create')->name('homeadmin/guru/tambah');
    Route::get('/admin/guru/edit/{id}', 'GuruController@edit')->name('homeadmin/guru/edit');
    Route::put('/admin/guru/update/{id}', 'GuruController@update')->name('homeadmin/guru/update');
    Route::get('/admin/guru/hapus/{id}', 'GuruController@delete')->name('homeadmin/guru/hapus');
    Route::get('/admin/guru/reset/{id}', 'GuruController@reset')->name('homeadmin/siswa/reset');

    // admin/siswa
    Route::get('/admin/siswa', 'SiswaController@tampilsiswa')->name('homeadmin/siswa');
    Route::get('/admin/siswa/cetak', 'SiswaController@siswacetak')->name('homeadmin/siswa/cetak');
    Route::post('/admin/siswa/tambah', 'SiswaController@create')->name('homeadmin/siswa/tambah');
    Route::get('/admin/siswa/edit/{id}', 'SiswaController@edit')->name('homeadmin/siswa/edit');
    Route::put('/admin/siswa/update/{id}', 'SiswaController@update')->name('homeadmin/siswa/update');
    Route::get('/admin/siswa/hapus/{id}', 'SiswaController@delete')->name('homeadmin/siswa/hapus');
    Route::get('/admin/siswa/reset/{id}', 'SiswaController@reset')->name('homeadmin/siswa/reset');

    // admin/kelas
    Route::get('/admin/kelas', 'KelasController@tampilkelas')->name('homeadmin/kelas');
    Route::post('/admin/kelas/tambah', 'KelasController@create')->name('homeadmin/kelas/tambah');
    Route::get('/admin/kelas/edit/{id}', 'KelasController@edit')->name('homeadmin/kelas/edit');
    Route::put('/admin/kelas/update/{id}', 'KelasController@update')->name('homeadmin/kelas/update');
    Route::get('/admin/kelas/hapus/{id}', 'KelasController@delete')->name('homeadmin/kelas/hapus');
    Route::get('/admin/kelas/detail/{id}', 'KelasController@detail')->name('homeadmin/kelas/detail');

    // admin/mapel
    Route::get('/admin/mapel', 'MapelController@tampilmapel')->name('homeadmin/mapel');
    Route::post('/admin/mapel/tambah', 'MapelController@create')->name('homeadmin/mapel/tambah');
    Route::get('/admin/mapel/edit/{id}', 'MapelController@edit')->name('homeadmin/mapel/edit');
    Route::put('/admin/mapel/update/{id}', 'MapelController@update')->name('homeadmin/mapel/update');
    Route::get('/admin/mapel/hapus/{id}', 'MapelController@delete')->name('homeadmin/mapel/hapus');
});

// login dokter
Route::group(['middleware' => ['auth:pegawai', 'is_dokter']], function () {
    Route::get('/dokter', 'PegawaiController@dokter')->name('homedokter');
    Route::get('/dokter/profile', 'PegawaiController@profile')->name('homedokter/profile');
    Route::post('/dokter/profile/password', 'PegawaiController@gantipassword')->name('homedokter/profile/password');

    Route::get('/pasien/ralan', 'PasienController@show')->name('pasien/ralan');
    
    Route::get('/form-soap', 'FormController@formsoap')->name('form-soap');
    Route::get('/form/listdata', 'FormController@listdata')->name('form/listdata');

    Route::post('/form/tambah', 'FormController@create')->name('form/tambah');

    Route::get('/form/hapus', 'FormController@delete')->name('form/hapus');


    Route::get('/form/awalmedisparu', 'FormController@rjawalmedisparu')->name('form-awalmedisparu');
    Route::get('/form/penanganandokter', 'FormController@rjpenanganandokter')->name('form-penanganandokter');
    Route::get('/form/diagnosa', 'FormController@rjdiagnosa')->name('form-diagnosa');
    Route::get('/form/resume', 'FormController@rjresume')->name('form-resume');
    Route::get('/form/permintaanlab', 'FormController@permintaanlab')->name('form-permintaanlab');
    Route::get('/form/permintaanrad', 'FormController@permintaanrad')->name('form-permintaanrad');

    // admin/guru
    Route::get('/admin/guru', 'GuruController@tampilguru')->name('homeadmin/guru');
    Route::post('/admin/guru/tambah', 'GuruController@create')->name('homeadmin/guru/tambah');
    Route::get('/admin/guru/edit/{id}', 'GuruController@edit')->name('homeadmin/guru/edit');
    Route::put('/admin/guru/update/{id}', 'GuruController@update')->name('homeadmin/guru/update');
    Route::get('/admin/guru/hapus/{id}', 'GuruController@delete')->name('homeadmin/guru/hapus');
    Route::get('/admin/guru/reset/{id}', 'GuruController@reset')->name('homeadmin/siswa/reset');

    // admin/siswa
    Route::get('/admin/siswa', 'SiswaController@tampilsiswa')->name('homeadmin/siswa');
    Route::get('/admin/siswa/cetak', 'SiswaController@siswacetak')->name('homeadmin/siswa/cetak');
    Route::post('/admin/siswa/tambah', 'SiswaController@create')->name('homeadmin/siswa/tambah');
    Route::get('/admin/siswa/edit/{id}', 'SiswaController@edit')->name('homeadmin/siswa/edit');
    Route::put('/admin/siswa/update/{id}', 'SiswaController@update')->name('homeadmin/siswa/update');
    Route::get('/admin/siswa/hapus/{id}', 'SiswaController@delete')->name('homeadmin/siswa/hapus');
    Route::get('/admin/siswa/reset/{id}', 'SiswaController@reset')->name('homeadmin/siswa/reset');

    // admin/kelas
    Route::get('/admin/kelas', 'KelasController@tampilkelas')->name('homeadmin/kelas');
    Route::post('/admin/kelas/tambah', 'KelasController@create')->name('homeadmin/kelas/tambah');
    Route::get('/admin/kelas/edit/{id}', 'KelasController@edit')->name('homeadmin/kelas/edit');
    Route::put('/admin/kelas/update/{id}', 'KelasController@update')->name('homeadmin/kelas/update');
    Route::get('/admin/kelas/hapus/{id}', 'KelasController@delete')->name('homeadmin/kelas/hapus');
    Route::get('/admin/kelas/detail/{id}', 'KelasController@detail')->name('homeadmin/kelas/detail');

    // admin/mapel
    Route::get('/admin/mapel', 'MapelController@tampilmapel')->name('homeadmin/mapel');
    Route::post('/admin/mapel/tambah', 'MapelController@create')->name('homeadmin/mapel/tambah');
    Route::get('/admin/mapel/edit/{id}', 'MapelController@edit')->name('homeadmin/mapel/edit');
    Route::put('/admin/mapel/update/{id}', 'MapelController@update')->name('homeadmin/mapel/update');
    Route::get('/admin/mapel/hapus/{id}', 'MapelController@delete')->name('homeadmin/mapel/hapus');
});

// login pegawai
Route::group(['middleware' => ['auth:pegawai', 'is_petugas']], function () {
    Route::get('/pegawai', 'PegawaiController@index')->name('homepegawai');
    Route::get('/pegawai/profile', 'PegawaiController@profile')->name('homepegawai/profile');
    Route::post('/pegawai/profile/password', 'PegawaiController@gantipassword')->name('homepegawai/profile/password');


    // guru/Detail Mapel
    Route::get('/guru/detailmapel', 'DetailMapelController@detailmapel')->name('homeguru/detailmapel');
    Route::post('/guru/detailmapel/tambah', 'DetailMapelController@create')->name('homeguru/detailmapel/tambah');
    Route::get('/guru/detailmapel/edit/{id}', 'DetailMapelController@edit')->name('homeguru/detailmapel/edit');
    Route::put('/guru/detailmapel/update/{id}', 'DetailMapelController@update')->name('homeguru/detailmapel/update');
    Route::get('/guru/detailmapel/hapus/{id}', 'DetailMapelController@delete')->name('homeguru/detailmapel/hapus');

    // guru/soal
    Route::get('/guru/soal/{id}', 'SoalController@tampilsoal')->name('homeguru/soal');
    Route::post('/guru/soal/tambah/{id}', 'SoalController@create')->name('homeguru/soal/tambah');
    Route::get('/guru/soal/edit/{id}', 'SoalController@edit')->name('homeguru/soal/edit');
    Route::put('/guru/soal/update/{id}', 'SoalController@update')->name('homeguru/soal/update');
    Route::get('/guru/soal/hapus/{id}/{id_detail}', 'SoalController@delete')->name('homeguru/soal/hapus');

    // guru/kelas & siswa
    Route::get('/guru/kelas', 'KelasController@tampilkelas')->name('homeguru/kelas');
    Route::get('/guru/kelas/detail/{id}', 'KelasController@detail')->name('homeguru/kelas/detail');
    Route::get('/guru/siswa', 'SiswaController@tampilsiswa')->name('homeguru/siswa');
    Route::get('/guru/siswa/cetak', 'SiswaController@siswacetak')->name('homeadmin/siswa/cetak');

    // guru/penilaian ujian
    Route::get('/guru/penilaian/{id}', 'PenilaianController@penilaianguru')->name('homeguru/penilaian');
    Route::get('/guru/penilaian/edit/{id_detail}/{id_siswa}', 'PenilaianController@penilaianedit')->name('homeguru/penilaian/edit');
    Route::post('/guru/penilaian/update', 'PenilaianController@penilaianupdate')->name('homeguru/penilaian/update');
    Route::get('/guru/penilaian/{id}/cetak', 'PenilaianController@cetakpenilaian')->name('homeguru/penilaian/cetak');

    // image
    Route::get('/guru/deleteimage/{id_soal}', 'SoalController@deleteimage')->name('homeguru/deleteimage');
});
