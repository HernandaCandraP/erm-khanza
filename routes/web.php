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
Route::get('/', 'TextMiningController@index')->name('welcome');

// Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/404', function () {
    return view('404');
});

// login
Route::get('/login', 'LoginController@index')->name('login');
Route::post('/postlogin', 'LoginController@postlogin')->name('postlogin');
Route::get('/logout', 'LoginController@logout')->name('logout');

// login siswa
Route::group(['middleware' => 'auth:siswa'], function () {
    Route::get('/siswa', 'SiswaController@index')->name('homesiswa');

    Route::get('/siswa/detailmapel', 'DetailMapelController@detailmapelsiswa')->name('homesiswa/detailmapel');
    Route::get('/siswa/keterangan/{id}', 'DetailMapelController@keterangan')->name('homesiswa/keterangan');
    Route::post('/siswa/jawaban/{id}', 'SoalController@jawabsoal')->name('homesiswa/jawaban');
});

// login guru dan admin
Route::group(['middleware' => 'auth:guru'], function () {
    Route::get('/admin', 'GuruController@admin')->name('homeadmin')->middleware('is_admin');

    // admin/guru
    Route::get('/admin/guru', 'GuruController@tampilguru')->name('homeadmin/guru')->middleware('is_admin');
    Route::post('/admin/guru/tambah', 'GuruController@create')->name('homeadmin/guru/tambah')->middleware('is_admin');
    Route::get('/admin/guru/edit/{id}', 'GuruController@edit')->name('homeadmin/guru/edit')->middleware('is_admin');
    Route::put('/admin/guru/update/{id}', 'GuruController@update')->name('homeadmin/guru/update')->middleware('is_admin');
    Route::get('/admin/guru/hapus/{id}', 'GuruController@delete')->name('homeadmin/guru/hapus')->middleware('is_admin');

    // admin/siswa
    Route::get('/admin/siswa', 'SiswaController@tampilsiswa')->name('homeadmin/siswa')->middleware('is_admin');
    Route::post('/admin/siswa/tambah', 'SiswaController@create')->name('homeadmin/siswa/tambah')->middleware('is_admin');
    Route::get('/admin/siswa/edit/{id}', 'SiswaController@edit')->name('homeadmin/siswa/edit')->middleware('is_admin');
    Route::put('/admin/siswa/update/{id}', 'SiswaController@update')->name('homeadmin/siswa/update')->middleware('is_admin');
    Route::get('/admin/siswa/hapus/{id}', 'SiswaController@delete')->name('homeadmin/siswa/hapus')->middleware('is_admin');
    
    // admin/kelas
    Route::get('/admin/kelas', 'KelasController@tampilkelas')->name('homeadmin/kelas')->middleware('is_admin');
    Route::post('/admin/kelas/tambah', 'KelasController@create')->name('homeadmin/kelas/tambah')->middleware('is_admin');
    Route::get('/admin/kelas/edit/{id}', 'KelasController@edit')->name('homeadmin/kelas/edit')->middleware('is_admin');
    Route::put('/admin/kelas/update/{id}', 'KelasController@update')->name('homeadmin/kelas/update')->middleware('is_admin');
    Route::get('/admin/kelas/hapus/{id}', 'KelasController@delete')->name('homeadmin/kelas/hapus')->middleware('is_admin');
    Route::get('/admin/kelas/detail/{id}', 'KelasController@detail')->name('homeadmin/kelas/detail')->middleware('is_admin');

    // admin/mapel
    Route::get('/admin/mapel', 'MapelController@tampilmapel')->name('homeadmin/mapel')->middleware('is_admin');
    Route::post('/admin/mapel/tambah', 'MapelController@create')->name('homeadmin/mapel/tambah')->middleware('is_admin');
    Route::get('/admin/mapel/edit/{id}', 'MapelController@edit')->name('homeadmin/mapel/edit')->middleware('is_admin');
    Route::put('/admin/mapel/update/{id}', 'MapelController@update')->name('homeadmin/mapel/update')->middleware('is_admin');
    Route::get('/admin/mapel/hapus/{id}', 'MapelController@delete')->name('homeadmin/mapel/hapus')->middleware('is_admin');
    
    // guru
    Route::get('/guru', 'GuruController@index')->name('homeguru');

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
});