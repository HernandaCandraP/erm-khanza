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

Route::get('/mutasi', 'HomeController@mutasi')->name('home');
Route::get('/updateantrean', 'HomeController@updateantrean')->name('updatetask4');
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
Route::get('/ambil_icd', 'PegawaiController@ambil_icd')->name('ambil_icd');
Route::get('/cekstokobat', 'PegawaiController@cekstokobat')->name('cekstokobat');


//admin
Route::group(['middleware' => ['auth:pegawai', 'is_admin']], function () {
    Route::match(array('GET','POST'),'/admin', 'PegawaiController@admin')->name('homeadmin');
    Route::match(array('GET','POST'),'/riwayatradiologi', 'PegawaiController@riwayatradiologi')->name('riwayatradiologi');
    Route::match(array('GET','POST'),'/persentasepoli', 'PegawaiController@persentasepoli')->name('persentasepoli');
    Route::match(array('GET','POST'),'/persentaseranap', 'PegawaiController@persentaseranap')->name('persentaseranap');
    Route::get('/admin/profile', 'PegawaiController@profile')->name('homeadmin/profile');
    Route::post('/admin/profile/password', 'PegawaiController@gantipassword')->name('homeadmin/profile/password');

    Route::get('/admin/kelolalogin', 'LoginController@kelolalogin')->name('kelolalogin');
    Route::get('/admin/resetpassword', 'LoginController@resetpassword')->name('resetpassword');

    Route::get('/admin/persentase', 'PegawaiController@persentase')->name('persentase');
    Route::match(array('GET','POST'),'/admin/pemesananobat', 'PegawaiController@pemesananobat')->name('pemesananobat');
});

// login dokter
Route::group(['middleware' => ['auth:pegawai', 'is_dokter']], function () {
    Route::get('/dokter', 'PegawaiController@dokter')->name('homedokter');
    Route::get('/dokter/profile', 'PegawaiController@profile')->name('homedokter/profile');
    Route::post('/dokter/profile/password', 'PegawaiController@gantipassword')->name('homedokter/profile/password');

    Route::get('/pasien/ralan', 'PasienController@show')->name('pasien/ralan');    
    Route::get('/form/soap', 'FormController@formsoap')->name('form-soap');
    Route::get('/form/listdata', 'FormController@listdata')->name('form/listdata');
    Route::post('/form/tambah', 'FormController@create')->name('form/tambah');
    Route::get('/form/hapus', 'FormController@delete')->name('form/hapus');

    Route::get('/form', 'FormController@menurajal')->name('menu-rajal');
    Route::get('/form/awalmedisparu', 'FormController@rjawalmedisparu')->name('form-awalmedisparu');
    Route::get('/form/awalmedisbedah', 'FormController@rjawalmedisbedah')->name('form-awalmedisbedah');
    Route::get('/form/awalmedissyaraf', 'FormController@rjawalmedissyaraf')->name('form-awalmedissyaraf');
    Route::get('/form/awalmedisobsgyn', 'FormController@rjawalmedisobsgyn')->name('form-awalmedisobsgyn');
    Route::get('/form/awalmedisanak', 'FormController@rjawalmedisanak')->name('form-awalmedisanak');
    Route::get('/form/awalmedismata', 'FormController@rjawalmedismata')->name('form-awalmedismata');
    Route::get('/form/awalmedisdalam', 'FormController@rjawalmedisdalam')->name('form-awalmedisdalam');
    Route::get('/form/awalmedisjantung', 'FormController@rjawalmedisumum')->name('form-awalmedisjantung');
    Route::get('/form/awalmedis-', 'FormController@rjawalmedisumum')->name('form-awalmedis-');
    Route::get('/form/penanganandokter', 'FormController@rjpenanganandokter')->name('form-penanganandokter');
    Route::get('/form/diagnosa', 'FormController@rjdiagnosa')->name('form-diagnosa');
    Route::get('/form/resume', 'FormController@rjresume')->name('form-resume');
    Route::get('/form/permintaanlab', 'FormController@permintaanlab')->name('form-permintaanlab');
    Route::get('/form/permintaanrad', 'FormController@permintaanrad')->name('form-permintaanrad');
    Route::get('/form/resep', 'FormController@rjresep')->name('form-resep');
    Route::get('/form/rincianbiaya', 'FormController@rincianbiaya')->name('form-rincianbiaya');

    // Menu Rawat Inap
    Route::get('/menu-ranap', 'FormControllerRanap@menuranap')->name('menu-ranap');
    Route::get('/menu-ranap/resume', 'FormControllerRanap@riresume')->name('formri-resume');
    Route::get('/menu-ranap/soap', 'FormControllerRanap@risoap')->name('formri-soap');
    Route::get('/menu-ranap/penanganandokter', 'FormControllerRanap@ripenanganandokter')->name('formri-penanganandokter');
    Route::get('/menu-ranap/permintaanlab', 'FormControllerRanap@ripermintaanlab')->name('formri-permintaanlab');
    Route::get('/menu-ranap/permintaanrad', 'FormControllerRanap@ripermintaanrad')->name('formri-permintaanrad');
    Route::get('/menu-ranap/resep', 'FormControllerRanap@riresep')->name('formri-resep');
    Route::get('/menu-ranap/medisumum', 'FormControllerRanap@rimedisumum')->name('formri-medisumum');
    Route::get('/menu-ranap/mediskebidanan', 'FormControllerRanap@rimediskebidanan')->name('formri-mediskebidanan');
    Route::get('/menu-ranap/listdata', 'FormControllerRanap@listdata')->name('formri-listdata');
    Route::post('/menu-ranap/tambah', 'FormControllerRanap@create')->name('formri-tambah');
    Route::get('/menu-ranap/hapus', 'FormControllerRanap@delete')->name('formri-hapus');
    Route::get('/menu-ranap/rincianbiaya', 'FormControllerRanap@rincianbiaya')->name('formri-rincianbiaya');

    // Menu Radologi
    Route::match(array('GET','POST'),'/menu-radiologi', 'FormControllerRad@menuradiologi')->name('menu-radiologi');
    Route::get('/menu-radiologi/form', 'FormControllerRad@formradiologi')->name('form-radiologi');
    Route::post('/menu-radiologi/tambah', 'FormControllerRad@create')->name('formrad-tambah');
});

// login pegawai
Route::group(['middleware' => ['auth:pegawai', 'is_petugas']], function () {
    Route::get('/pegawai', 'PegawaiController@index')->name('homepegawai');
    Route::get('/pegawai/profile', 'PegawaiController@profile')->name('homepegawai/profile');
    Route::post('/pegawai/profile/password', 'PegawaiController@gantipassword')->name('homepegawai/profile/password');

    Route::get('/antrean', 'PetugasController@antrean')->name('antrean');
    Route::get('/antrean/updateantrean', 'PetugasController@updateantrean')->name('updateantrean');
});
